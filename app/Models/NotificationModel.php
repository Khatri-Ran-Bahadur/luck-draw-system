<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table            = 'notifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'admin_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'priority',
        'expires_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'data' => 'json',
        'is_read' => 'boolean',
        'expires_at' => '?datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'type' => 'required|in_list[user_topup,user_withdraw,draw_participation,withdrawal_approved,withdrawal_rejected,draw_win,system_message,admin_message]',
        'title' => 'required|max_length[255]',
        'message' => 'required',
        'priority' => 'in_list[low,medium,high,urgent]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get notifications for a specific user
     */
    public function getUserNotifications($userId, $limit = 10, $unreadOnly = false)
    {
        $builder = $this->where('user_id', $userId)
            ->where('(expires_at IS NULL OR expires_at > NOW())', null, false)
            ->orderBy('created_at', 'DESC');

        if ($unreadOnly) {
            $builder->where('is_read', false);
        }

        return $builder->limit($limit)->findAll();
    }

    /**
     * Get notifications for admin (all user activities)
     */
    public function getAdminNotifications($limit = 20, $unreadOnly = false)
    {
        $builder = $this->select('notifications.*, users.username, users.full_name, users.email')
            ->join('users', 'users.id = notifications.user_id', 'left')
            ->where('notifications.admin_id IS NULL') // Admin notifications
            ->where('(notifications.expires_at IS NULL OR notifications.expires_at > NOW())', null, false)
            ->orderBy('notifications.created_at', 'DESC');

        if ($unreadOnly) {
            $builder->where('notifications.is_read', false);
        }

        return $builder->limit($limit)->findAll();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId, $userId = null)
    {
        $builder = $this->where('id', $notificationId);

        if ($userId) {
            $builder->where('user_id', $userId);
        }

        return $builder->set('is_read', true)->update();
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead($userId)
    {
        return $this->where('user_id', $userId)
            ->set('is_read', true)
            ->update();
    }

    /**
     * Get unread count for user
     */
    public function getUnreadCount($userId)
    {
        return $this->where('user_id', $userId)
            ->where('is_read', false)
            ->where('(expires_at IS NULL OR expires_at > NOW())', null, false)
            ->countAllResults();
    }

    /**
     * Get unread count for admin
     */
    public function getAdminUnreadCount()
    {
        return $this->where('admin_id IS NULL')
            ->where('is_read', false)
            ->where('(expires_at IS NULL OR expires_at > NOW())', null, false)
            ->countAllResults();
    }

    /**
     * Clean up expired notifications
     */
    public function cleanupExpired()
    {
        return $this->where('expires_at <', date('Y-m-d H:i:s'))
            ->delete();
    }

    /**
     * Get notification statistics
     */
    public function getStats($userId = null)
    {
        $builder = $this->selectCount('id', 'total')
            ->selectCount('id', 'unread', 'is_read = 0')
            ->selectCount('id', 'high_priority', 'priority IN ("high", "urgent")')
            ->where('(expires_at IS NULL OR expires_at > NOW())', null, false);

        if ($userId) {
            $builder->where('user_id', $userId);
        } else {
            $builder->where('admin_id IS NULL');
        }

        return $builder->get()->getRowArray();
    }
}
