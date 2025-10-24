<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

/**
 * Dashboard Controller
 * Handles role-based dashboard routing and display
 */
class DashboardController extends Controller
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
    }

    /**
     * Main dashboard entry point
     * Redirects users to appropriate dashboard based on role
     */
    public function index()
    {
        // Check authentication
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Get user role from session
        $role = $this->session->get('role');

        // Redirect based on role
        if ($role === 'admin') {
            return redirect()->to('/admin/dashboard');
        } else {
            return redirect()->to('/student/dashboard');
        }
    }

    /**
     * Admin Dashboard
     * Only accessible by users with admin role
     */
    public function adminDashboard()
    {
        // Prepare data for admin dashboard
        $data = [
            'title' => 'Admin Dashboard',
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('role'),
            ],
            'stats' => $this->getAdminStats(),
        ];

        return view('dashboard/admin', $data);
    }

    /**
     * Student Dashboard
     * Only accessible by users with student role
     */
    public function studentDashboard()
    {
        // Prepare data for student dashboard
        $data = [
            'title' => 'Student Dashboard',
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('role'),
            ],
            'courses' => $this->getStudentCourses(),
        ];

        return view('dashboard/student', $data);
    }

    /**
     * Get statistics for admin dashboard
     * 
     * @return array
     */
    private function getAdminStats()
    {
        $totalUsers = $this->userModel->countAll();
        $adminCount = $this->userModel->where('role', 'admin')->countAllResults(false);
        $studentCount = $this->userModel->where('role', 'student')->countAllResults();

        return [
            'total_users' => $totalUsers,
            'admin_count' => $adminCount,
            'student_count' => $studentCount,
            'total_courses' => 0, // Placeholder for future implementation
            'total_enrollments' => 0, // Placeholder for future implementation
        ];
    }

    /**
     * Get courses for student dashboard
     * 
     * @return array
     */
    private function getStudentCourses()
    {
        // Placeholder for future implementation
        // In a real application, this would fetch enrolled courses from database
        return [];
    }
}
