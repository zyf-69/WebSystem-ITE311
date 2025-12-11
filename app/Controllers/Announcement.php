<?php

namespace App\Controllers;

use App\Models\AnnouncementModel;
use CodeIgniter\Controller;

/**
 * Announcement Controller
 * Handles announcement display and management
 */
class Announcement extends Controller
{
    protected $announcementModel;
    protected $session;

    public function __construct()
    {
        $this->announcementModel = new AnnouncementModel();
        $this->session = session();
    }

    /**
     * Display all announcements
     * Accessible to all logged-in users
     */
    public function index()
    {
        // Fetch all announcements ordered by created_at DESC (newest first)
        $announcements = $this->announcementModel
                              ->orderBy('created_at', 'DESC')
                              ->findAll();

        $data = [
            'title' => 'Announcements',
            'announcements' => $announcements,
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('role'),
            ],
        ];

        return view('announcements', $data);
    }
}
