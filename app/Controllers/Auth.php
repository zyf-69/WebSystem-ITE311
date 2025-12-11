<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\EnrollmentModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function register()
    {
        helper(['form']);
        $session = session();
        $model = new UserModel();
        
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]|regex_match[/^[a-zA-Z\s\-\']+$/]',
                'email' => 'required|valid_email|is_unique[users.email]|regex_match[/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/]',
                'password' => 'required|min_length[6]',
                'password_confirm' => 'matches[password]'
            ];
            
            $errors = [
                'name' => [
                    'regex_match' => 'Name can only contain letters, spaces, hyphens, and apostrophes. No special characters allowed.',
                ],
                'email' => [
                    'regex_match' => 'Email contains invalid characters. Only letters, numbers, dots, underscores, hyphens, and @ symbol are allowed.',
                ],
            ];
            
            if ($this->validate($rules, $errors)) {
                $data = [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                    'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'role' => 'student'
                ];
                
                // Save user to database
                if ($model->insert($data)) {
                    $session->setFlashdata('success', 'Registration successful. Please login.');
                    return redirect()->to('/login');
                } else {
                    // Get the last error for debugging
                    $errors = $model->errors();
                    $errorMessage = 'Registration failed. ';
                    if (!empty($errors)) {
                        $errorMessage .= implode(', ', $errors);
                    } else {
                        $errorMessage .= 'Please try again.';
                    }
                    $session->setFlashdata('error', $errorMessage);
                }
            }
        }
        
        echo view('auth/register', [
            'validation' => $this->validator
        ]);
    }

    public function login()
    {
        helper(['form']);
        $session = session();
        $model = new UserModel();
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required'
            ];
            if ($this->validate($rules)) {
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');
                $user = $model->where('email', $email)->first();
                if ($user && password_verify($password, $user['password'])) {
                    // Check if user is deleted
                    if (!empty($user['deleted_at'])) {
                        $session->setFlashdata('error', 'This account has been marked as deleted. Please contact an administrator to recover your account.');
                        return redirect()->to('/login');
                    }
                    
                    $session->set([
                        'user_id' => $user['id'],
                        'user_name' => $user['name'],
                        'user_email' => $user['email'],
                        'role' => $user['role'],
                        'isLoggedIn' => true,
                    ]);
                    $session->setFlashdata('success', 'Welcome, ' . $user['name'] . '!');
                    
                    // Unified dashboard redirect - role check happens in dashboard method
                    return redirect()->to('/dashboard');
                } else {
                    $session->setFlashdata('error', 'Invalid login credentials.');
                }
            }
        }
        $data = [
            'validation' => $this->validator ?? null
        ];
        
        echo view('auth/login', $data);
    }

    public function dashboard()
    {
        $session = session();
        
        // Authorization check - ensure user is logged in
        if (!$session->get('isLoggedIn')) {
            $session->setFlashdata('error', 'Please login to access the dashboard.');
            return redirect()->to('/login');
        }
        
        // Get user role from session
        $role = $session->get('role');
        
        // Prepare user data
        $userData = [
            'id' => $session->get('user_id'),
            'name' => $session->get('user_name'),
            'email' => $session->get('user_email'),
            'role' => $role,
        ];
        
        // Fetch role-specific data from database
        $model = new UserModel();
        $roleData = [];
        
        if ($role === 'admin') {
            // Fetch admin-specific data
            $courseModel = new \App\Models\CourseModel();
            $db = \Config\Database::connect();
            
            // Fetch all courses with instructor names
            $builder = $db->table('courses');
            $builder->select('courses.*, users.name as instructor_name');
            $builder->join('users', 'users.id = courses.instructor_id', 'left');
            $builder->orderBy('courses.created_at', 'DESC');
            $allCourses = $builder->get()->getResultArray();
            
            $roleData = [
                'total_users' => $model->countAll(),
                'admin_count' => $model->where('role', 'admin')->countAllResults(false),
                'student_count' => $model->where('role', 'student')->countAllResults(false),
                'teacher_count' => $model->where('role', 'teacher')->countAllResults(),
                'courses' => $allCourses,
            ];
        } elseif ($role === 'teacher') {
            // Fetch teacher-specific data
            $courseModel = new \App\Models\CourseModel();
            $enrollmentModel = new \App\Models\EnrollmentModel();
            
            // Get courses assigned to this teacher
            $myCourses = $courseModel->where('instructor_id', $userData['id'])->findAll();
            
            // Get all enrollments for teacher's courses
            $db = \Config\Database::connect();
            $builder = $db->table('enrollments');
            $builder->select('enrollments.*, courses.title as course_title, courses.id as course_id');
            $builder->join('courses', 'courses.id = enrollments.course_id', 'inner');
            $builder->where('courses.instructor_id', $userData['id']);
            $builder->orderBy('enrollments.created_at', 'DESC');
            $enrollments = $builder->get()->getResultArray();
            
            // Get student information for each enrollment
            foreach ($enrollments as &$enrollment) {
                $studentId = $enrollment['user_id'] ?? $enrollment['student_id'];
                if ($studentId) {
                    $student = $model->find($studentId);
                    $enrollment['student_name'] = $student['name'] ?? 'Unknown';
                    $enrollment['student_email'] = $student['email'] ?? '';
                } else {
                    $enrollment['student_name'] = 'Unknown';
                    $enrollment['student_email'] = '';
                }
            }
            unset($enrollment);
            
            // Count students (unique students across all courses)
            $studentIds = [];
            foreach ($enrollments as $enrollment) {
                $studentId = $enrollment['user_id'] ?? $enrollment['student_id'];
                if ($studentId && !in_array($studentId, $studentIds)) {
                    $studentIds[] = $studentId;
                }
            }
            
            // Count pending enrollments and get pending list
            $pendingCount = 0;
            $pendingEnrollments = [];
            foreach ($enrollments as $enrollment) {
                if (($enrollment['status'] ?? '') === 'pending') {
                    $pendingCount++;
                    $pendingEnrollments[] = $enrollment;
                }
            }
            
            $roleData = [
                'courses' => $myCourses,
                'students' => count($studentIds),
                'enrollments' => $enrollments,
                'pending_count' => $pendingCount,
                'pending_enrollments' => $pendingEnrollments,
            ];
        } else {
            // Fetch student-specific data
            $enrollmentModel = new \App\Models\EnrollmentModel();
            $courseModel = new \App\Models\CourseModel();
            
            // Get enrolled courses (status = 'enrolled')
            $enrolledCourses = $enrollmentModel->getUserEnrollments($userData['id'], 'enrolled');
            
            // Get all enrollments (including pending) to filter available courses
            $allEnrollments = $enrollmentModel->getUserEnrollments($userData['id']);
            $enrolledCourseIds = array_column($allEnrollments, 'course_id');
            
            // Get all available courses
            $allCourses = $courseModel->getAllCourses();
            
            // Filter out courses that student is already enrolled in or has pending enrollment
            $availableCourses = array_filter($allCourses, function($course) use ($enrolledCourseIds) {
                return !in_array($course['id'], $enrolledCourseIds);
            });
            
            // Get pending enrollments
            $pendingEnrollments = $enrollmentModel->getUserEnrollments($userData['id'], 'pending');
            
            $roleData = [
                'enrolled_courses' => $enrolledCourses,
                'available_courses' => $availableCourses,
                'pending_enrollments' => $pendingEnrollments,
                'all_enrollments' => $allEnrollments, // For filtering
                'assignments' => [],
            ];
        }
        
        // Pass user role and relevant data to the view
        $data = [
            'title' => 'Dashboard',
            'user' => $userData,
            'roleData' => $roleData,
        ];
        
        return view('auth/dashboard', $data);
    }

    /**
     * My Courses - Shared method for both teachers and students
     * Teachers see assigned courses, students see enrolled courses
     */
    public function myCourse()
    {
        // Check authentication
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userRole = strtolower(session()->get('role') ?? '');
        $userId = session()->get('user_id');
        
        if ($userRole === 'teacher') {
            // For teachers: show assigned courses
            $courseModel = new \App\Models\CourseModel();
            $enrollmentModel = new \App\Models\EnrollmentModel();
            
            // Get courses assigned to this teacher
            $courses = $courseModel->where('instructor_id', $userId)->findAll();
            
            // Get enrollment counts for each course
            foreach ($courses as &$course) {
                $enrollments = $enrollmentModel->where('course_id', $course['id'])->findAll();
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
                'userRole' => 'teacher',
                'user' => [
                    'id' => $userId,
                    'name' => session()->get('user_name'),
                    'email' => session()->get('user_email'),
                    'role' => session()->get('role'),
                ],
            ];
            
            return view('teacher/my_courses', $data);
            
        } elseif ($userRole === 'student') {
            // For students: show enrolled courses
            $enrollmentModel = new \App\Models\EnrollmentModel();
            
            // Get enrolled courses (status = 'enrolled')
            $enrolledCourses = $enrollmentModel->getUserEnrollments($userId, 'enrolled');
            
            // Get pending enrollments
            $pendingEnrollments = $enrollmentModel->getUserEnrollments($userId, 'pending');
            
            $data = [
                'title' => 'My Courses',
                'enrolledCourses' => $enrolledCourses,
                'pendingEnrollments' => $pendingEnrollments,
                'userRole' => 'student',
                'user' => [
                    'id' => $userId,
                    'name' => session()->get('user_name'),
                    'email' => session()->get('user_email'),
                    'role' => session()->get('role'),
                ],
            ];
            
            return view('student/my_courses', $data);
            
        } else {
            // Admin or other roles - redirect to dashboard
            return redirect()->to('/dashboard');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
