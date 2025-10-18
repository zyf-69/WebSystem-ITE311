<?php

namespace App\Controllers;

use CodeIgniter\Controller;

/**
 * Admin Controller
 * Handles admin-specific functionality
 */
class Admin extends Controller
{
    protected $session;

    public function __construct()
    {
        $this->session = session();
    }

    /**
     * Admin Dashboard
     * Display welcome message for admins
     */
    public function dashboard()
    {
        $data = [
            'title' => 'Admin Dashboard',
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('role'),
            ],
        ];

        return view('admin_dashboard', $data);
    }
}
