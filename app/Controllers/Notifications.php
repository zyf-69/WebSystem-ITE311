<?php

namespace App\Controllers;

use App\Models\NotificationModel;
use CodeIgniter\Controller;

class Notifications extends Controller
{
    protected $notificationModel;
    protected $session;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
        $this->session = session();
    }

    /**
     * Get notifications for the current user
     * Returns JSON response with unread count and list of notifications
     */
    public function get()
    {
        // Check if user is logged in
        if (!$this->session->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login to view notifications.'
            ])->setStatusCode(401);
        }

        $userId = $this->session->get('user_id');
        
        if (empty($userId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User ID not found in session.'
            ])->setStatusCode(401);
        }

        // Get unread count
        $unreadCount = $this->notificationModel->getUnreadCount($userId);

        // Get latest notifications
        $notifications = $this->notificationModel->getNotificationsForUser($userId, 5);

        return $this->response->setJSON([
            'success' => true,
            'unread_count' => (int)$unreadCount,
            'notifications' => $notifications ? $notifications : []
        ])->setContentType('application/json');
    }

    /**
     * Mark a notification as read
     * 
     * @param int $id Notification ID
     */
    public function mark_as_read($id)
    {
        // Check if user is logged in
        if (!$this->session->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login to mark notifications as read.'
            ])->setStatusCode(401);
        }

        $userId = $this->session->get('user_id');

        // Verify the notification belongs to the current user
        $notification = $this->notificationModel->find($id);
        if (!$notification) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Notification not found.'
            ])->setStatusCode(404);
        }

        if ((int)$notification['user_id'] !== (int)$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are not authorized to mark this notification as read.'
            ])->setStatusCode(403);
        }

        // Mark as read
        if ($this->notificationModel->markAsRead($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Notification marked as read.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to mark notification as read.'
            ])->setStatusCode(500);
        }
    }
}

