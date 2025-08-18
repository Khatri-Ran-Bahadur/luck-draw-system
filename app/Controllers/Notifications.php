<?php

namespace App\Controllers;

use App\Models\NotificationModel;
use App\Libraries\NotificationService;

class Notifications extends BaseController
{
    protected $notificationModel;
    protected $notificationService;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
        $this->notificationService = new NotificationService();
    }

    /**
     * Get user notifications (AJAX)
     */
    public function getUserNotifications()
    {
        if (!session()->get('user_id')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $userId = session()->get('user_id');
        $limit = $this->request->getGet('limit') ?? 10;
        $unreadOnly = $this->request->getGet('unread_only') === 'true';

        $notifications = $this->notificationModel->getUserNotifications($userId, $limit, $unreadOnly);
        $unreadCount = $this->notificationModel->getUnreadCount($userId);

        // Add styling information
        foreach ($notifications as &$notification) {
            $notification['style'] = $this->notificationService->getNotificationStyle($notification['type']);
            $notification['priority_style'] = $this->notificationService->getPriorityStyle($notification['priority']);
            $notification['time_ago'] = $this->timeAgo($notification['created_at']);
        }

        return $this->response->setJSON([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Get admin notifications (AJAX)
     */
    public function getAdminNotifications()
    {
        if (!session()->get('is_admin')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $limit = $this->request->getGet('limit') ?? 20;
        $unreadOnly = $this->request->getGet('unread_only') === 'true';

        $notifications = $this->notificationModel->getAdminNotifications($limit, $unreadOnly);
        $unreadCount = $this->notificationModel->getAdminUnreadCount();

        // Add styling information
        foreach ($notifications as &$notification) {
            $notification['style'] = $this->notificationService->getNotificationStyle($notification['type']);
            $notification['priority_style'] = $this->notificationService->getPriorityStyle($notification['priority']);
            $notification['time_ago'] = $this->timeAgo($notification['created_at']);
        }

        return $this->response->setJSON([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $userId = session()->get('user_id');
        $isAdmin = session()->get('is_admin');

        if (!$userId && !$isAdmin) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $success = $this->notificationModel->markAsRead($id, $isAdmin ? null : $userId);

        return $this->response->setJSON([
            'success' => $success,
            'message' => $success ? 'Notification marked as read' : 'Failed to mark notification as read'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $userId = session()->get('user_id');
        $isAdmin = session()->get('is_admin');

        if (!$userId && !$isAdmin) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        if ($isAdmin) {
            // Mark all admin notifications as read
            $success = $this->notificationModel->where('admin_id IS NULL')
                ->set('is_read', true)
                ->update();
        } else {
            $success = $this->notificationModel->markAllAsRead($userId);
        }

        return $this->response->setJSON([
            'success' => $success,
            'message' => $success ? 'All notifications marked as read' : 'Failed to mark notifications as read'
        ]);
    }

    /**
     * Delete notification
     */
    public function delete($id)
    {
        $userId = session()->get('user_id');
        $isAdmin = session()->get('is_admin');

        if (!$userId && !$isAdmin) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $builder = $this->notificationModel->where('id', $id);

        if (!$isAdmin) {
            $builder->where('user_id', $userId);
        }

        $success = $builder->delete();

        return $this->response->setJSON([
            'success' => $success,
            'message' => $success ? 'Notification deleted' : 'Failed to delete notification'
        ]);
    }

    /**
     * Get notification counts for header
     */
    public function getCounts()
    {
        $userId = session()->get('user_id');
        $isAdmin = session()->get('is_admin');

        if (!$userId && !$isAdmin) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        if ($isAdmin) {
            $unreadCount = $this->notificationModel->getAdminUnreadCount();
        } else {
            $unreadCount = $this->notificationModel->getUnreadCount($userId);
        }

        return $this->response->setJSON([
            'success' => true,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Send admin message to user (Admin only)
     */
    public function sendAdminMessage()
    {
        if (!session()->get('is_admin')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $rules = [
            'user_id' => 'required|integer',
            'title' => 'required|max_length[255]',
            'message' => 'required',
            'priority' => 'in_list[low,medium,high,urgent]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $adminId = session()->get('user_id');
        $userId = $this->request->getPost('user_id');
        $title = $this->request->getPost('title');
        $message = $this->request->getPost('message');
        $priority = $this->request->getPost('priority') ?? 'medium';

        $success = $this->notificationService->sendAdminMessage($userId, $adminId, $title, $message, $priority);

        return $this->response->setJSON([
            'success' => $success,
            'message' => $success ? 'Message sent successfully' : 'Failed to send message'
        ]);
    }

    /**
     * Broadcast message to all users (Admin only)
     */
    public function broadcast()
    {
        if (!session()->get('is_admin')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $rules = [
            'title' => 'required|max_length[255]',
            'message' => 'required',
            'priority' => 'in_list[low,medium,high,urgent]',
            'expires_hours' => 'permit_empty|integer|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $title = $this->request->getPost('title');
        $message = $this->request->getPost('message');
        $priority = $this->request->getPost('priority') ?? 'medium';
        $expiresHours = $this->request->getPost('expires_hours');

        $expiresAt = null;
        if ($expiresHours) {
            $expiresAt = date('Y-m-d H:i:s', strtotime("+{$expiresHours} hours"));
        }

        $success = $this->notificationService->broadcastToAllUsers($title, $message, $priority, $expiresAt);

        return $this->response->setJSON([
            'success' => $success,
            'message' => $success ? 'Message broadcasted successfully' : 'Failed to broadcast message'
        ]);
    }

    /**
     * Helper function to calculate time ago
     */
    private function timeAgo($datetime)
    {
        $time = time() - strtotime($datetime);

        if ($time < 60) return 'just now';
        if ($time < 3600) return floor($time / 60) . 'm ago';
        if ($time < 86400) return floor($time / 3600) . 'h ago';
        if ($time < 2592000) return floor($time / 86400) . 'd ago';
        if ($time < 31536000) return floor($time / 2592000) . 'mo ago';
        return floor($time / 31536000) . 'y ago';
    }
}
