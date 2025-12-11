<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CourseModel;
use Config\AvailableCourses;
use CodeIgniter\Controller;

/**
 * Admin Controller
 * Handles administrative functions like user management
 */
class AdminController extends Controller
{
    protected $userModel;
    protected $courseModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->courseModel = new CourseModel();
        $this->session = session();
    }
    
    /**
     * Helper method to check if current user is admin
     * This provides an additional security layer
     */
    protected function ensureAdmin()
    {
        if (!$this->session->get('isLoggedIn')) {
            $this->session->setFlashdata('error', 'Please login first.');
            return redirect()->to('/login');
        }
        
        $userRole = strtolower($this->session->get('role'));
        if ($userRole !== 'admin') {
            $this->session->setFlashdata('error', 'Access Denied: Admin privileges required.');
            // Redirect based on user role
            if ($userRole === 'teacher') {
                return redirect()->to('/teacher/dashboard');
            } elseif ($userRole === 'student') {
                return redirect()->to('/dashboard');
            } else {
                return redirect()->to('/announcements');
            }
        }
        
        return null; // User is admin, allow access
    }

    /**
     * Display all users with search functionality
     */
    public function users()
    {
        // Ensure admin access
        $check = $this->ensureAdmin();
        if ($check !== null) return $check;
        
        // Get search query
        $search = $this->request->getGet('search');
        
        // Build query - include deleted users
        $query = $this->userModel;
        
        // Apply search if provided
        if (!empty($search)) {
            $query = $query->groupStart()
                          ->like('name', $search)
                          ->orLike('email', $search)
                          ->groupEnd();
        }
        
        // Get all users including deleted ones
        $users = $query->orderBy('deleted_at', 'ASC')
                      ->orderBy('created_at', 'DESC')
                      ->findAll();
        
        $data = [
            'title' => 'User Management',
            'users' => $users,
            'search' => $search,
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('role'),
            ],
        ];

        return view('admin/users', $data);
    }

    /**
     * Soft delete a user (mark as deleted)
     * 
     * @param int $id User ID to delete
     */
    public function deleteUser($id)
    {
        // Ensure admin access
        $check = $this->ensureAdmin();
        if ($check !== null) return $check;
        
        // Prevent admin from deleting themselves
        if ($id == $this->session->get('user_id')) {
            $this->session->setFlashdata('error', 'You cannot delete your own account.');
            return redirect()->to('/admin/users');
        }

        // Check if user exists
        $user = $this->userModel->find($id);
        if (!$user) {
            $this->session->setFlashdata('error', 'User not found.');
            return redirect()->to('/admin/users');
        }

        // Check if already deleted
        if (!empty($user['deleted_at'])) {
            $this->session->setFlashdata('error', 'User is already marked as deleted.');
            return redirect()->to('/admin/users');
        }

        // Soft delete - mark as deleted (set deleted_at timestamp)
        if ($this->userModel->update($id, ['deleted_at' => date('Y-m-d H:i:s')])) {
            $this->session->setFlashdata('success', 'User marked as deleted successfully. You can recover it later.');
        } else {
            $this->session->setFlashdata('error', 'Failed to delete user.');
        }

        return redirect()->to('/admin/users');
    }
    
    /**
     * Recover a deleted user
     * 
     * @param int $id User ID to recover
     */
    public function recoverUser($id)
    {
        // Ensure admin access
        $check = $this->ensureAdmin();
        if ($check !== null) return $check;
        
        // Check if user exists
        $user = $this->userModel->find($id);
        if (!$user) {
            $this->session->setFlashdata('error', 'User not found.');
            return redirect()->to('/admin/users');
        }

        // Check if user is actually deleted
        if (empty($user['deleted_at'])) {
            $this->session->setFlashdata('error', 'User is not deleted.');
            return redirect()->to('/admin/users');
        }

        // Recover user - clear deleted_at
        if ($this->userModel->update($id, ['deleted_at' => null])) {
            $this->session->setFlashdata('success', 'User recovered successfully.');
        } else {
            $this->session->setFlashdata('error', 'Failed to recover user.');
        }

        return redirect()->to('/admin/users');
    }

    /**
     * Edit user (update name and email)
     * 
     * @param int $id User ID to edit
     */
    public function editUser($id)
    {
        // Ensure admin access
        $check = $this->ensureAdmin();
        if ($check !== null) return $check;
        
        helper(['form']);
        
        // Check if user exists
        $userToEdit = $this->userModel->find($id);
        if (!$userToEdit) {
            $this->session->setFlashdata('error', 'User not found.');
            return redirect()->to('/admin/users');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]|regex_match[/^[a-zA-Z\s\-\']+$/]',
                'email' => "required|valid_email|is_unique[users.email,id,{$id}]|regex_match[/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/]"
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
                    'email' => $this->request->getPost('email')
                ];

                if ($this->userModel->update($id, $data)) {
                    $this->session->setFlashdata('success', 'User updated successfully.');
                    return redirect()->to('/admin/users');
                } else {
                    $this->session->setFlashdata('error', 'Failed to update user.');
                }
            }
        }

        $data = [
            'title' => 'Edit User',
            'validation' => $this->validator,
            'userToEdit' => $userToEdit,
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('role'),
            ],
        ];

        return view('admin/edit_user', $data);
    }

    /**
     * Update user role
     * 
     * @param int $id User ID to update
     */
    public function updateRole($id)
    {
        // Prevent admin from changing their own role
        if ($id == $this->session->get('user_id')) {
            $this->session->setFlashdata('error', 'You cannot change your own role.');
            return redirect()->to('/admin/users');
        }

        $newRole = $this->request->getPost('role');

        // Validate role
        if (!in_array($newRole, ['admin', 'teacher', 'student'])) {
            $this->session->setFlashdata('error', 'Invalid role specified.');
            return redirect()->to('/admin/users');
        }

        // Update user role
        if ($this->userModel->update($id, ['role' => $newRole])) {
            $this->session->setFlashdata('success', 'User role updated successfully.');
        } else {
            $this->session->setFlashdata('error', 'Failed to update user role.');
        }

        return redirect()->to('/admin/users');
    }

    /**
     * Create new user (admin function)
     */
    public function createUser()
    {
        // Ensure admin access
        $check = $this->ensureAdmin();
        if ($check !== null) return $check;
        
        helper(['form']);

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]|regex_match[/^[a-zA-Z\s\-\']+$/]',
                'email' => 'required|valid_email|is_unique[users.email]|regex_match[/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/]',
                'password' => 'required|min_length[6]',
                'role' => 'required|in_list[admin,teacher,student]'
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
                    'role' => $this->request->getPost('role')
                ];

                if ($this->userModel->insert($data)) {
                    $this->session->setFlashdata('success', 'User created successfully.');
                    return redirect()->to('/admin/users');
                } else {
                    $this->session->setFlashdata('error', 'Failed to create user.');
                }
            }
        }

        $data = [
            'title' => 'Create User',
            'validation' => $this->validator,
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('role'),
            ],
        ];

        return view('admin/create_user', $data);
    }

    /**
     * Display courses management page (redirects to dashboard)
     */
    public function courses()
    {
        $check = $this->ensureAdmin();
        if ($check !== null) return $check;
        
        // Redirect to unified dashboard where course management table is displayed
        return redirect()->to('/dashboard');
    }

    /**
     * Create new course
     */
    public function createCourse()
    {
        $check = $this->ensureAdmin();
        if ($check !== null) return $check;
        
        helper(['form']);
        $availableCourses = new AvailableCourses();
        
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'title' => 'required|min_length[3]|max_length[200]',
                'description' => 'permit_empty|max_length[500]',
                'level' => 'permit_empty|max_length[50]',
                'status' => 'permit_empty|in_list[active,inactive,completed]',
            ];
            
            if ($this->validate($rules)) {
                $data = [
                    'title' => $this->request->getPost('title'),
                    'description' => $this->request->getPost('description'),
                    'level' => $this->request->getPost('level'),
                    'status' => $this->request->getPost('status') ?? 'active',
                    'instructor_id' => null, // No auto-assignment
                ];
                
                if ($this->courseModel->insert($data)) {
                    $this->session->setFlashdata('success', 'Course created successfully.');
                    return redirect()->to('/dashboard');
                } else {
                    $this->session->setFlashdata('error', 'Failed to create course.');
                }
            }
        }
        
        // Get all available course titles from config
        $allTitles = $availableCourses->courseTitles;
        
        // Get already used titles
        $usedTitles = array_column($this->courseModel->select('title')->findAll(), 'title');
        
        // Get unused titles
        $availableTitles = array_diff($allTitles, $usedTitles);
        
        $data = [
            'title' => 'Create Course',
            'validation' => $this->validator,
            'availableTitles' => $availableTitles,
            'allTitles' => $allTitles,
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('role'),
            ],
        ];
        
        return view('admin/create_course', $data);
    }

    /**
     * Assign course to teacher
     */
    public function assignCourse()
    {
        $check = $this->ensureAdmin();
        if ($check !== null) return $check;
        
        helper(['form']);
        
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'course_id' => 'required|integer',
                'instructor_id' => 'required|integer',
                'start_time' => 'permit_empty',
                'end_time' => 'permit_empty',
                'schedule_days' => 'permit_empty',
                'room' => 'permit_empty|max_length[50]',
                'start_date' => 'permit_empty|valid_date',
                'end_date' => 'permit_empty|valid_date',
            ];
            
            if ($this->validate($rules)) {
                $data = [
                    'instructor_id' => $this->request->getPost('instructor_id'),
                    'start_time' => $this->request->getPost('start_time'),
                    'end_time' => $this->request->getPost('end_time'),
                    'schedule_days' => $this->request->getPost('schedule_days') ? implode(',', $this->request->getPost('schedule_days')) : null,
                    'room' => $this->request->getPost('room'),
                    'start_date' => $this->request->getPost('start_date'),
                    'end_date' => $this->request->getPost('end_date'),
                ];
                
                $courseId = $this->request->getPost('course_id');
                
                if ($this->courseModel->update($courseId, $data)) {
                    $this->session->setFlashdata('success', 'Course assigned to teacher successfully.');
                    return redirect()->to('/admin/assign-course');
                } else {
                    $this->session->setFlashdata('error', 'Failed to assign course.');
                }
            }
        }
        
        // Show all courses so admins can (re)assign even if they already have an instructor
        // This prevents an empty dropdown when all seeded courses are pre-assigned
        $unassignedCourses = $this->courseModel
            ->orderBy('created_at', 'DESC')
            ->findAll();
        
        // Get all courses with instructor info
        $db = \Config\Database::connect();
        $builder = $db->table('courses');
        $builder->select('courses.*, users.name as instructor_name');
        $builder->join('users', 'users.id = courses.instructor_id', 'left');
        $builder->orderBy('courses.created_at', 'DESC');
        $allCourses = $builder->get()->getResultArray();
        
        // Get all teachers
        $teachers = $this->userModel->where('role', 'teacher')->findAll();
        
        $data = [
            'title' => 'Assign Course to Teacher',
            'validation' => $this->validator,
            'unassignedCourses' => $unassignedCourses,
            'allCourses' => $allCourses,
            'teachers' => $teachers,
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('role'),
            ],
        ];
        
        return view('admin/assign_course', $data);
    }

    /**
     * Edit course
     */
    public function editCourse($id)
    {
        $check = $this->ensureAdmin();
        if ($check !== null) return $check;
        
        helper(['form']);
        $course = $this->courseModel->find($id);
        
        if (!$course) {
            $this->session->setFlashdata('error', 'Course not found.');
            return redirect()->to('/dashboard');
        }
        
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'title' => 'required|min_length[3]|max_length[200]',
                'description' => 'permit_empty|max_length[500]',
                'level' => 'permit_empty|max_length[50]',
                'status' => 'permit_empty|in_list[active,inactive,completed]',
                'instructor_id' => 'permit_empty|integer',
            ];
            
            if ($this->validate($rules)) {
                $data = [
                    'title' => $this->request->getPost('title'),
                    'description' => $this->request->getPost('description'),
                    'level' => $this->request->getPost('level'),
                    'status' => $this->request->getPost('status'),
                    'instructor_id' => $this->request->getPost('instructor_id') ?: null,
                ];
                
                if ($this->courseModel->update($id, $data)) {
                    $this->session->setFlashdata('success', 'Course updated successfully.');
                    return redirect()->to('/dashboard');
                } else {
                    $this->session->setFlashdata('error', 'Failed to update course.');
                }
            }
        }
        
        // Get all teachers
        $teachers = $this->userModel->where('role', 'teacher')->findAll();
        
        $data = [
            'title' => 'Edit Course',
            'validation' => $this->validator,
            'course' => $course,
            'teachers' => $teachers,
            'user' => [
                'id' => $this->session->get('user_id'),
                'name' => $this->session->get('user_name'),
                'email' => $this->session->get('user_email'),
                'role' => $this->session->get('role'),
            ],
        ];
        
        return view('admin/edit_course', $data);
    }

    /**
     * Delete course
     */
    public function deleteCourse($id)
    {
        $check = $this->ensureAdmin();
        if ($check !== null) return $check;
        
        if ($this->courseModel->delete($id)) {
            $this->session->setFlashdata('success', 'Course deleted successfully.');
        } else {
            $this->session->setFlashdata('error', 'Failed to delete course.');
        }
        
        return redirect()->to('/dashboard');
    }
}
