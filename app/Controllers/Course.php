<?php

namespace App\Controllers;

use App\Models\EnrollmentModel;
use App\Models\CourseModel;
use App\Models\NotificationModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

/**
 * Course Controller
 * Handles course enrollment and management
 */
class Course extends Controller
{
    protected $enrollmentModel;
    protected $courseModel;
    protected $notificationModel;
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->enrollmentModel = new EnrollmentModel();
        $this->courseModel = new CourseModel();
        $this->notificationModel = new NotificationModel();
        $this->userModel = new UserModel();
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

        // Check if user is already enrolled or has pending enrollment
        if ($this->enrollmentModel->isAlreadyEnrolled($user_id, $course_id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are already enrolled in this course or have a pending enrollment request.'
            ])->setStatusCode(400);
        }

        // Check if there's a declined enrollment - if so, update it to pending instead of creating new
        $existingEnrollment = $this->enrollmentModel
            ->where('course_id', $course_id)
            ->groupStart()
                ->where('user_id', $user_id)
                ->orWhere('student_id', $user_id)
            ->groupEnd()
            ->where('status', 'declined')
            ->first();

        if ($existingEnrollment) {
            // Update declined enrollment to pending
            $updateData = [
                'status' => 'pending',
                'enrollment_date' => date('Y-m-d H:i:s'),
                'decline_reason' => null, // Clear the decline reason
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            if ($this->enrollmentModel->update($existingEnrollment['id'], $updateData)) {
                // Get course information for notification
                $course = $this->courseModel->find($course_id);
                $courseName = $course ? $course['title'] : 'Course';
                
                // Get student information
                $student = $this->userModel->find($user_id);
                $studentName = $student ? $student['name'] : 'Student';
                
                // Create notification for the student
                $notificationData = [
                    'user_id' => $user_id,
                    'message' => "Re-enrollment request submitted for [{$courseName}]. Waiting for teacher approval.",
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $this->notificationModel->createNotification($notificationData);
                
                // Create notification for the teacher (if course has an instructor)
                if ($course && !empty($course['instructor_id'])) {
                    $teacherNotification = [
                        'user_id' => $course['instructor_id'],
                        'message' => "Re-enrollment request from [{$studentName}] for course [{$courseName}]",
                        'is_read' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $this->notificationModel->createNotification($teacherNotification);
                }
                
                // Create notification for all admins
                $admins = $this->userModel->where('role', 'admin')->findAll();
                foreach ($admins as $admin) {
                    $adminNotification = [
                        'user_id' => $admin['id'],
                        'message' => "Student [{$studentName}] has submitted a re-enrollment request for course [{$courseName}]",
                        'is_read' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $this->notificationModel->createNotification($adminNotification);
                }
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Re-enrollment request submitted! Waiting for teacher approval.'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to submit re-enrollment request.'
                ])->setStatusCode(500);
            }
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
            // Get course information for notification
            $course = $this->courseModel->find($course_id);
            $courseName = $course ? $course['title'] : 'Course';
            
            // Get student information
            $student = $this->userModel->find($user_id);
            $studentName = $student ? $student['name'] : 'Student';
            
            // Create notification for the student
            $notificationData = [
                'user_id' => $user_id,
                'message' => "Enrollment request submitted for [{$courseName}]. Waiting for teacher approval.",
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->notificationModel->createNotification($notificationData);
            
            // Create notification for the teacher (if course has an instructor)
            if ($course && !empty($course['instructor_id'])) {
                $teacherNotification = [
                    'user_id' => $course['instructor_id'],
                    'message' => "New enrollment request from [{$studentName}] for course [{$courseName}]",
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $this->notificationModel->createNotification($teacherNotification);
            }
            
            // Create notification for all admins
            $admins = $this->userModel->where('role', 'admin')->findAll();
            foreach ($admins as $admin) {
                $adminNotification = [
                    'user_id' => $admin['id'],
                    'message' => "Student [{$studentName}] has submitted an enrollment request for course [{$courseName}]",
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $this->notificationModel->createNotification($adminNotification);
            }
            
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

    /**
     * Search courses
     * Accepts GET or POST requests with search_term parameter
     * Returns JSON for AJAX requests or renders view for regular requests
     */
    public function search()
    {
        // Get search term from GET or POST request
        $searchTerm = $this->request->getGet('search_term') ?? $this->request->getPost('search_term');

        // Create a new instance to avoid modifying the model
        $courseModel = new \App\Models\CourseModel();

        // Build query
        if (!empty($searchTerm)) {
            // Search in title and description using LIKE queries
            $courseModel->groupStart()
                ->like('title', $searchTerm)
                ->orLike('description', $searchTerm)
                ->groupEnd();
        }

        // Get all courses matching the search
        $courses = $courseModel->findAll();

        // If AJAX request, return JSON
        if ($this->request->isAJAX()) {
            return $this->response->setJSON($courses);
        }

        // For regular requests, render search results view
        $data = [
            'title' => 'Search Results',
            'courses' => $courses,
            'searchTerm' => $searchTerm,
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('role'),
            ],
        ];

        return view('courses/search_results', $data);
    }
}

