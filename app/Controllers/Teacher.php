<?php

namespace App\Controllers;

use CodeIgniter\Controller;

/**
 * Teacher Controller
 * Handles teacher-specific functionality
 */
class Teacher extends Controller
{
    protected $session;

    public function __construct()
    {
        $this->session = session();
    }

    /**
     * Teacher Dashboard
     * Display welcome message for teachers
     */
    public function dashboard()
    {
        $data = [
            'title' => 'Teacher Dashboard',
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('role'),
            ],
        ];

        return view('teacher_dashboard', $data);
    }
}
