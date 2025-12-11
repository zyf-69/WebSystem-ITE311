<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\UserModel;
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

    public function __construct()
    {
        $this->session = session();
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->userModel = new UserModel();
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
        
        // Get enrollment counts for each course
        foreach ($courses as &$course) {
            $enrollments = $this->enrollmentModel->where('course_id', $course['id'])->findAll();
            $course['total_students'] = count($enrollments);
            $course['pending_count'] = count(array_filter($enrollments, function($e) {
                return ($e['status'] ?? '') === 'pending';
            }));
            $course['enrolled_count'] = count(array_filter($enrollments, function($e) {
                return ($e['status'] ?? '') === 'enrolled';
            }));
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
        
        $data = [
            'title' => 'My Students',
            'courseEnrollments' => $courseEnrollments,
            'totalStudents' => $totalStudents,
            'pendingCount' => $pendingCount,
            'enrolledCount' => $enrolledCount,
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
        
        // Update enrollment status
        if ($this->enrollmentModel->update($enrollment_id, ['status' => 'declined'])) {
            $student = $this->userModel->find($enrollment['user_id'] ?? $enrollment['student_id']);
            $studentName = $student['name'] ?? 'Student';
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
}
