<?php
namespace App\Controllers;

use App\Models\UserModel;
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
                'name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'password_confirm' => 'matches[password]'
            ];
            
            if ($this->validate($rules)) {
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
        echo view('auth/login', [
            'validation' => $this->validator
        ]);
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
            $roleData = [
                'total_users' => $model->countAll(),
                'admin_count' => $model->where('role', 'admin')->countAllResults(false),
                'student_count' => $model->where('role', 'student')->countAllResults(false),
                'teacher_count' => $model->where('role', 'teacher')->countAllResults(),
            ];
        } elseif ($role === 'teacher') {
            // Fetch teacher-specific data (placeholder for future implementation)
            $roleData = [
                'courses' => [],
                'students' => 0,
            ];
        } else {
            // Fetch student-specific data (placeholder for future implementation)
            $roleData = [
                'enrolled_courses' => [],
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

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
