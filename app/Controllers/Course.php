<?php

namespace App\Controllers;

use App\Models\EnrollmentModel;
use CodeIgniter\Controller;

/**
 * Course Controller
 * Handles course enrollment and management
 */
class Course extends Controller
{
    protected $enrollmentModel;
    protected $session;

    public function __construct()
    {
        $this->enrollmentModel = new EnrollmentModel();
        $this->session = session();
    }

    /**
     * Handle AJAX enrollment request
     * Enrolls a user in a course
     */
    public function enroll()
    {
        // Check if user is logged in
        if (!$this->session->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login to enroll in courses.'
            ])->setStatusCode(401);
        }

        // Get user ID from session
        $user_id = $this->session->get('user_id');
        
        // Get course_id from POST request
        $course_id = $this->request->getPost('course_id');
        
        // Validate course_id
        if (empty($course_id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course ID is required.'
            ])->setStatusCode(400);
        }

        // Check if user is already enrolled
        if ($this->enrollmentModel->isAlreadyEnrolled($user_id, $course_id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are already enrolled in this course.'
            ])->setStatusCode(400);
        }

        // Prepare enrollment data - status is 'pending' until teacher approves
        $data = [
            'user_id' => $user_id,
            'student_id' => $user_id, // For backward compatibility
            'course_id' => $course_id,
            'enrollment_date' => date('Y-m-d H:i:s'),
            'status' => 'pending' // Requires teacher approval
        ];

        // Insert enrollment record
        if ($this->enrollmentModel->enrollUser($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Enrollment request submitted! Waiting for teacher approval.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to enroll in the course. Please try again.'
            ])->setStatusCode(500);
        }
    }
}

