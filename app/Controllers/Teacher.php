<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\UserModel;
use App\Models\NotificationModel;
use CodeIgniter\Controller;

/**
 * Teacher Controller
 * Handles teacher-specific functionality
 */
class Teacher extends Controller
{
    protected $session;
    protected $courseModel;
    protected $enrollmentModel;
    protected $userModel;
    protected $notificationModel;

    public function __construct()
    {
        $this->session = session();
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->userModel = new UserModel();
        $this->notificationModel = new NotificationModel();
    }

    /**
     * Check if user is a teacher
     */
    protected function ensureTeacher()
    {
        if (!$this->session->get('isLoggedIn')) {
            $this->session->setFlashdata('error', 'Please login first.');
            return redirect()->to('/login');
        }
        
        $userRole = strtolower($this->session->get('role'));
        if ($userRole !== 'teacher') {
            $this->session->setFlashdata('error', 'Access Denied: Teacher privileges required.');
            return redirect()->to('/dashboard');
        }
        
        return null;
    }

    /**
     * Teacher Dashboard    
     * Display welcome message for teachers
     */
    public function dashboard()
    {
        // Redirect to unified dashboard
        return redirect()->to('/dashboard');
    }

    /**
     * Display courses assigned to the teacher
     */
    public function myCourses()
    {
        $check = $this->ensureTeacher();
        if ($check !== null) return $check;
        
        $teacherId = $this->session->get('user_id');
        
        // Get courses assigned to this teacher
        $courses = $this->courseModel->where('instructor_id', $teacherId)->findAll();
        
        // Load MaterialModel to get materials for each course
        $materialModel = new \App\Models\MaterialModel();
        
        // Get enrollment counts and materials for each course
        foreach ($courses as &$course) {
            $enrollments = $this->enrollmentModel->where('course_id', $course['id'])->findAll();
            $course['total_students'] = count($enrollments);
            $course['pending_count'] = count(array_filter($enrollments, function($e) {
                return ($e['status'] ?? '') === 'pending';
            }));
            $course['enrolled_count'] = count(array_filter($enrollments, function($e) {
                return ($e['status'] ?? '') === 'enrolled';
            }));
            // Get materials for this course
            $course['materials'] = $materialModel->getMaterialsByCourse($course['id']);
        }
        unset($course);
        
        $data = [
            'title' => 'My Courses',
            'courses' => $courses,
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('role'),
            ],
        ];
        
        return view('teacher/my_courses', $data);
    }

    /**
     * Display students enrolled in teacher's courses
     */
    public function myStudents()
    {
        $check = $this->ensureTeacher();
        if ($check !== null) return $check;
        
        $teacherId = $this->session->get('user_id');
        
        // Get courses assigned to this teacher
        $courses = $this->courseModel->where('instructor_id', $teacherId)->findAll();
        
        // Get enrollments for each course
        $courseEnrollments = [];
        $totalStudents = 0;
        $pendingCount = 0;
        $enrolledCount = 0;
        
        foreach ($courses as $course) {
            // Query enrollments - handle both user_id and student_id fields
            $db = \Config\Database::connect();
            $builder = $db->table('enrollments');
            $builder->where('course_id', $course['id']);
            $enrollments = $builder->get()->getResultArray();
            
            // Get student information for each enrollment
            foreach ($enrollments as &$enrollment) {
                $studentId = $enrollment['user_id'] ?? $enrollment['student_id'];
                if ($studentId) {
                    $student = $this->userModel->find($studentId);
                    $enrollment['student_name'] = $student['name'] ?? 'Unknown';
                    $enrollment['student_email'] = $student['email'] ?? '';
                    
                    // Count students (unique count)
                    $status = $enrollment['status'] ?? 'pending';
                    if ($status === 'pending') {
                        $pendingCount++;
                    } elseif ($status === 'enrolled') {
                        $enrolledCount++;
                        $totalStudents++;
                    }
                    // Note: declined enrollments are still shown but not counted in totals
                } else {
                    $enrollment['student_name'] = 'Unknown';
                    $enrollment['student_email'] = '';
                }
            }
            unset($enrollment);
            
            // Add course with enrollments (even if empty, so teacher can see all their courses)
            $courseEnrollments[] = [
                'course' => $course,
                'enrollments' => $enrollments,
            ];
        }
        
        // Get all students for enrollment
        $allStudents = $this->userModel->where('role', 'student')->findAll();
        
        // For each student, check their enrollment status in teacher's courses
        foreach ($allStudents as &$student) {
            $student['enrollments'] = [];
            foreach ($courses as $course) {
                $enrollment = $this->enrollmentModel
                    ->where('course_id', $course['id'])
                    ->groupStart()
                        ->where('user_id', $student['id'])
                        ->orWhere('student_id', $student['id'])
                    ->groupEnd()
                    ->first();
                
                if ($enrollment) {
                    $student['enrollments'][$course['id']] = $enrollment['status'] ?? 'pending';
                } else {
                    $student['enrollments'][$course['id']] = null; // Not enrolled
                }
            }
        }
        unset($student);
        
        $data = [
            'title' => 'My Students',
            'courseEnrollments' => $courseEnrollments,
            'totalStudents' => $totalStudents,
            'pendingCount' => $pendingCount,
            'enrolledCount' => $enrolledCount,
            'allStudents' => $allStudents,
            'myCourses' => $courses,
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('role'),
            ],
        ];
        
        return view('teacher/my_students', $data);
    }

    /**
     * Accept a pending enrollment
     */
    public function acceptEnrollment($enrollment_id)
    {
        $check = $this->ensureTeacher();
        if ($check !== null) return $check;
        
        $teacherId = $this->session->get('user_id');
        
        // Get enrollment
        $enrollment = $this->enrollmentModel->find($enrollment_id);
        if (!$enrollment) {
            $this->session->setFlashdata('error', 'Enrollment not found.');
            return redirect()->to('/my-students');
        }
        
        // Verify the course belongs to this teacher
        $course = $this->courseModel->find($enrollment['course_id']);
        if (!$course || $course['instructor_id'] != $teacherId) {
            $this->session->setFlashdata('error', 'You do not have permission to manage this enrollment.');
            return redirect()->to('/my-students');
        }
        
        // Update enrollment status
        if ($this->enrollmentModel->update($enrollment_id, ['status' => 'enrolled'])) {
            $student = $this->userModel->find($enrollment['user_id'] ?? $enrollment['student_id']);
            $studentName = $student['name'] ?? 'Student';
            $studentId = $enrollment['user_id'] ?? $enrollment['student_id'];
            
            // Create notification for the student
            $notificationData = [
                'user_id' => $studentId,
                'message' => "Your enrollment request for [{$course['title']}] has been approved!",
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->notificationModel->createNotification($notificationData);
            
            // Create notification for all admins
            $admins = $this->userModel->where('role', 'admin')->findAll();
            foreach ($admins as $admin) {
                $adminNotification = [
                    'user_id' => $admin['id'],
                    'message' => "Teacher has approved enrollment request: [{$studentName}] is now enrolled in [{$course['title']}]",
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $this->notificationModel->createNotification($adminNotification);
            }
            
            $this->session->setFlashdata('success', "Enrollment accepted! {$studentName} is now enrolled in {$course['title']}.");
        } else {
            $this->session->setFlashdata('error', 'Failed to accept enrollment.');
        }
        
        return redirect()->to('/my-students');
    }

    /**
     * Decline a pending enrollment
     */
    public function declineEnrollment($enrollment_id)
    {
        $check = $this->ensureTeacher();
        if ($check !== null) return $check;
        
        $teacherId = $this->session->get('user_id');
        
        // Get enrollment
        $enrollment = $this->enrollmentModel->find($enrollment_id);
        if (!$enrollment) {
            $this->session->setFlashdata('error', 'Enrollment not found.');
            return redirect()->to('/my-students');
        }
        
        // Verify the course belongs to this teacher
        $course = $this->courseModel->find($enrollment['course_id']);
        if (!$course || $course['instructor_id'] != $teacherId) {
            $this->session->setFlashdata('error', 'You do not have permission to manage this enrollment.');
            return redirect()->to('/my-students');
        }
        
        // Get decline reason from POST (if available)
        $declineReason = $this->request->getPost('decline_reason');
        
        // Prepare update data
        $updateData = ['status' => 'declined'];
        if (!empty($declineReason)) {
            $updateData['decline_reason'] = $declineReason;
        }
        
        // Update enrollment status
        if ($this->enrollmentModel->update($enrollment_id, $updateData)) {
            $student = $this->userModel->find($enrollment['user_id'] ?? $enrollment['student_id']);
            $studentName = $student['name'] ?? 'Student';
            $studentId = $enrollment['user_id'] ?? $enrollment['student_id'];
            
            // Create notification message with reason if provided
            $notificationMessage = "Your enrollment request for [{$course['title']}] has been declined.";
            if (!empty($declineReason)) {
                $notificationMessage .= " Reason: " . $declineReason;
            }
            
            // Create notification for the student
            $notificationData = [
                'user_id' => $studentId,
                'message' => $notificationMessage,
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->notificationModel->createNotification($notificationData);
            
            // Create notification for all admins
            $admins = $this->userModel->where('role', 'admin')->findAll();
            foreach ($admins as $admin) {
                $adminMessage = "Teacher has declined enrollment request: [{$studentName}] for course [{$course['title']}]";
                if (!empty($declineReason)) {
                    $adminMessage .= ". Reason: " . $declineReason;
                }
                $adminNotification = [
                    'user_id' => $admin['id'],
                    'message' => $adminMessage,
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $this->notificationModel->createNotification($adminNotification);
            }
            
            $this->session->setFlashdata('success', "Enrollment declined for {$studentName} in {$course['title']}.");
        } else {
            $this->session->setFlashdata('error', 'Failed to decline enrollment.');
        }
        
        return redirect()->to('/my-students');
    }

    /**
     * Unenroll a student from a course
     */
    public function unenrollStudent($enrollment_id)
    {
        $check = $this->ensureTeacher();
        if ($check !== null) return $check;
        
        $teacherId = $this->session->get('user_id');
        
        // Get enrollment
        $enrollment = $this->enrollmentModel->find($enrollment_id);
        if (!$enrollment) {
            $this->session->setFlashdata('error', 'Enrollment not found.');
            return redirect()->to('/my-students');
        }
        
        // Verify the course belongs to this teacher
        $course = $this->courseModel->find($enrollment['course_id']);
        if (!$course || $course['instructor_id'] != $teacherId) {
            $this->session->setFlashdata('error', 'You do not have permission to manage this enrollment.');
            return redirect()->to('/my-students');
        }
        
        // Delete enrollment
        $student = $this->userModel->find($enrollment['user_id'] ?? $enrollment['student_id']);
        $studentName = $student['name'] ?? 'Student';
        
        if ($this->enrollmentModel->delete($enrollment_id)) {
            $this->session->setFlashdata('success', "{$studentName} has been unenrolled from {$course['title']}.");
        } else {
            $this->session->setFlashdata('error', 'Failed to unenroll student.');
        }
        
        return redirect()->to('/my-students');
    }

    /**
     * Enroll a student in a course (teacher-initiated enrollment)
     */
    public function enrollStudent()
    {
        $check = $this->ensureTeacher();
        if ($check !== null) return $check;
        
        $teacherId = $this->session->get('user_id');
        $studentId = $this->request->getPost('student_id');
        $courseId = $this->request->getPost('course_id');
        
        // Validate inputs
        if (empty($studentId) || empty($courseId)) {
            $this->session->setFlashdata('error', 'Student ID and Course ID are required.');
            return redirect()->to('/my-students');
        }
        
        // Verify the course is assigned to this teacher
        $course = $this->courseModel->find($courseId);
        if (!$course || $course['instructor_id'] != $teacherId) {
            $this->session->setFlashdata('error', 'You are not authorized to enroll students in this course.');
            return redirect()->to('/my-students');
        }
        
        // Check if student exists
        $student = $this->userModel->find($studentId);
        if (!$student || $student['role'] !== 'student') {
            $this->session->setFlashdata('error', 'Invalid student.');
            return redirect()->to('/my-students');
        }
        
        // Check if already enrolled or has pending enrollment
        if ($this->enrollmentModel->isAlreadyEnrolled($studentId, $courseId)) {
            $this->session->setFlashdata('error', 'Student is already enrolled or has a pending enrollment in this course.');
            return redirect()->to('/my-students');
        }
        
        // Check if there's a declined enrollment - update it instead
        $existingEnrollment = $this->enrollmentModel
            ->where('course_id', $courseId)
            ->groupStart()
                ->where('user_id', $studentId)
                ->orWhere('student_id', $studentId)
            ->groupEnd()
            ->where('status', 'declined')
            ->first();
        
        if ($existingEnrollment) {
            // Update declined enrollment to enrolled (teacher can directly enroll)
            $updateData = [
                'status' => 'enrolled',
                'enrollment_date' => date('Y-m-d H:i:s'),
                'decline_reason' => null, // Clear the decline reason
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            if ($this->enrollmentModel->update($existingEnrollment['id'], $updateData)) {
                // Create notifications
                $studentName = $student['name'] ?? 'Student';
                
                // Notify student
                $notificationData = [
                    'user_id' => $studentId,
                    'message' => "You have been enrolled in course [{$course['title']}] by your teacher.",
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $this->notificationModel->createNotification($notificationData);
                
                // Notify all admins
                $admins = $this->userModel->where('role', 'admin')->findAll();
                foreach ($admins as $admin) {
                    $adminNotification = [
                        'user_id' => $admin['id'],
                        'message' => "Teacher has enrolled [{$studentName}] in course [{$course['title']}]",
                        'is_read' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $this->notificationModel->createNotification($adminNotification);
                }
                
                $this->session->setFlashdata('success', "Successfully enrolled {$studentName} in {$course['title']}.");
            } else {
                $this->session->setFlashdata('error', 'Failed to enroll student.');
            }
        } else {
            // Create new enrollment (directly enrolled by teacher)
            $data = [
                'user_id' => $studentId,
                'student_id' => $studentId,
                'course_id' => $courseId,
                'enrollment_date' => date('Y-m-d H:i:s'),
                'status' => 'enrolled' // Teacher can directly enroll
            ];
            
            if ($this->enrollmentModel->enrollUser($data)) {
                // Create notifications
                $studentName = $student['name'] ?? 'Student';
                
                // Notify student
                $notificationData = [
                    'user_id' => $studentId,
                    'message' => "You have been enrolled in course [{$course['title']}] by your teacher.",
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $this->notificationModel->createNotification($notificationData);
                
                // Notify all admins
                $admins = $this->userModel->where('role', 'admin')->findAll();
                foreach ($admins as $admin) {
                    $adminNotification = [
                        'user_id' => $admin['id'],
                        'message' => "Teacher has enrolled [{$studentName}] in course [{$course['title']}]",
                        'is_read' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $this->notificationModel->createNotification($adminNotification);
                }
                
                $this->session->setFlashdata('success', "Successfully enrolled {$studentName} in {$course['title']}.");
            } else {
                $this->session->setFlashdata('error', 'Failed to enroll student.');
            }
        }
        
        return redirect()->to('/my-students');
    }
}
