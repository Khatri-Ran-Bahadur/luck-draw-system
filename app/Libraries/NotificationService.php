<?php

namespace App\Libraries;

use App\Models\NotificationModel;
use App\Models\UserModel;

class NotificationService
{
    protected $notificationModel;
    protected $userModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
        $this->userModel = new UserModel();
    }

    /**
     * Send notification to admin about user activity
     */
    public function notifyAdmin($type, $userId, $data = [])
    {
        $user = $this->userModel->find($userId);
        if (!$user) {
            return false;
        }

        $templates = $this->getAdminNotificationTemplates();

        if (!isset($templates[$type])) {
            return false;
        }

        $template = $templates[$type];
        $title = $this->replacePlaceholders($template['title'], $user, $data);
        $message = $this->replacePlaceholders($template['message'], $user, $data);

        return $this->notificationModel->insert([
            'user_id' => $userId,
            'admin_id' => null, // Admin notification
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'priority' => $template['priority'] ?? 'medium'
        ]);
    }

    /**
     * Send notification to user about admin action
     */
    public function notifyUser($userId, $type, $data = [], $adminId = null)
    {
        $user = $this->userModel->find($userId);
        if (!$user) {
            return false;
        }

        $templates = $this->getUserNotificationTemplates();

        if (!isset($templates[$type])) {
            return false;
        }

        $template = $templates[$type];
        $title = $this->replacePlaceholders($template['title'], $user, $data);
        $message = $this->replacePlaceholders($template['message'], $user, $data);

        return $this->notificationModel->insert([
            'user_id' => $userId,
            'admin_id' => $adminId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'priority' => $template['priority'] ?? 'medium'
        ]);
    }

    /**
     * Send system message to user
     */
    public function sendSystemMessage($userId, $title, $message, $priority = 'medium', $expiresAt = null)
    {
        return $this->notificationModel->insert([
            'user_id' => $userId,
            'admin_id' => null,
            'type' => 'system_message',
            'title' => $title,
            'message' => $message,
            'priority' => $priority,
            'expires_at' => $expiresAt
        ]);
    }

    /**
     * Send admin message to user
     */
    public function sendAdminMessage($userId, $adminId, $title, $message, $priority = 'medium')
    {
        return $this->notificationModel->insert([
            'user_id' => $userId,
            'admin_id' => $adminId, // This can be null for system messages
            'type' => 'admin_message',
            'title' => $title,
            'message' => $message,
            'priority' => $priority
        ]);
    }

    /**
     * Broadcast message to all users
     */
    public function broadcastToAllUsers($title, $message, $priority = 'medium', $expiresAt = null)
    {
        $users = $this->userModel->where('is_admin', false)->where('status', 'active')->findAll();
        $notifications = [];

        foreach ($users as $user) {
            $notifications[] = [
                'user_id' => $user['id'],
                'admin_id' => null,
                'type' => 'system_message',
                'title' => $title,
                'message' => $message,
                'priority' => $priority,
                'expires_at' => $expiresAt,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        return $this->notificationModel->insertBatch($notifications);
    }

    /**
     * Get admin notification templates
     */
    private function getAdminNotificationTemplates()
    {
        return [
            'user_topup' => [
                'title' => 'New User Deposit - {user_name}',
                'message' => '{user_name} (@{username}) has deposited ${amount} to their wallet using {payment_method}.',
                'priority' => 'medium'
            ],
            'user_withdraw' => [
                'title' => 'Withdrawal Request - {user_name}',
                'message' => '{user_name} (@{username}) has requested to withdraw ${amount} from their wallet. Please review and approve.',
                'priority' => 'high'
            ],
            'draw_participation' => [
                'title' => 'New Draw Entry - {user_name}',
                'message' => '{user_name} (@{username}) has entered the draw "{draw_title}" with entry fee ${entry_fee}.',
                'priority' => 'low'
            ]
        ];
    }

    /**
     * Get user notification templates
     */
    private function getUserNotificationTemplates()
    {
        return [
            'withdrawal_approved' => [
                'title' => 'Withdrawal Approved! 🎉',
                'message' => 'Great news! Your withdrawal request of ${amount} has been approved and processed. The funds should reach your account within 1-3 business days.',
                'priority' => 'high'
            ],
            'withdrawal_rejected' => [
                'title' => 'Withdrawal Request Update',
                'message' => 'Your withdrawal request of ${amount} has been reviewed. {rejection_reason} The amount has been refunded to your wallet.',
                'priority' => 'high'
            ],
            'draw_win' => [
                'title' => 'Congratulations! You Won! 🏆',
                'message' => 'Amazing news! You won ${prize_amount} in the "{draw_title}" draw! Your winnings have been added to your wallet.',
                'priority' => 'urgent'
            ],
            'system_message' => [
                'title' => 'System Notification',
                'message' => '{message}',
                'priority' => 'medium'
            ]
        ];
    }

    /**
     * Replace placeholders in templates
     */
    private function replacePlaceholders($text, $user, $data = [])
    {
        $placeholders = [
            '{user_name}' => $user['full_name'] ?: $user['username'],
            '{username}' => $user['username'],
            '{email}' => $user['email']
        ];

        // Add data placeholders
        foreach ($data as $key => $value) {
            $placeholders['{' . $key . '}'] = $value;
        }

        return str_replace(array_keys($placeholders), array_values($placeholders), $text);
    }

    /**
     * Get notification icon and color based on type
     */
    public function getNotificationStyle($type)
    {
        $styles = [
            'user_topup' => ['icon' => 'fa-arrow-down', 'color' => 'green'],
            'user_withdraw' => ['icon' => 'fa-arrow-up', 'color' => 'orange'],
            'draw_participation' => ['icon' => 'fa-ticket-alt', 'color' => 'blue'],
            'withdrawal_approved' => ['icon' => 'fa-check-circle', 'color' => 'green'],
            'withdrawal_rejected' => ['icon' => 'fa-times-circle', 'color' => 'red'],
            'draw_win' => ['icon' => 'fa-trophy', 'color' => 'yellow'],
            'system_message' => ['icon' => 'fa-info-circle', 'color' => 'blue'],
            'admin_message' => ['icon' => 'fa-user-shield', 'color' => 'purple']
        ];

        return $styles[$type] ?? ['icon' => 'fa-bell', 'color' => 'gray'];
    }

    /**
     * Get priority badge style
     */
    public function getPriorityStyle($priority)
    {
        $styles = [
            'low' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Low'],
            'medium' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Medium'],
            'high' => ['class' => 'bg-orange-100 text-orange-800', 'text' => 'High'],
            'urgent' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Urgent']
        ];

        return $styles[$priority] ?? $styles['medium'];
    }

    /**
     * Clean up old notifications
     */
    public function cleanup($daysOld = 30)
    {
        // Clean expired notifications
        $this->notificationModel->cleanupExpired();

        // Clean old read notifications
        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$daysOld} days"));
        return $this->notificationModel->where('is_read', true)
            ->where('created_at <', $cutoffDate)
            ->delete();
    }
}
