<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'user_id',
        'message',
        'is_read',
        'created_at',
    ];

    protected $useTimestamps = false;

    /**
     * Get unread notification count for a user
     * 
     * @param int $userId User ID
     * @return int Count of unread notifications
     */
    public function getUnreadCount($userId)
    {
        return $this->where('user_id', $userId)
            ->where('is_read', 0)
            ->countAllResults();
    }

    /**
     * Get notifications for a user (latest first, limit 5)
     * 
     * @param int $userId User ID
     * @param int $limit Number of notifications to fetch (default 5)
     * @return array List of notifications
     */
    public function getNotificationsForUser($userId, $limit = 5)
    {
        $result = $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
        
        // Ensure we return an array even if empty
        return $result ? $result : [];
    }

    /**
     * Mark a notification as read
     * 
     * @param int $notificationId Notification ID
     * @return bool True on success, false on failure
     */
    public function markAsRead($notificationId)
    {
        return $this->update($notificationId, ['is_read' => 1]);
    }

    /**
     * Create a new notification
     * 
     * @param array $data Notification data (user_id, message)
     * @return bool|int Insert ID on success, false on failure
     */
    public function createNotification($data)
    {
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        if (!isset($data['is_read'])) {
            $data['is_read'] = 0;
        }
        return $this->insert($data);
    }
}

