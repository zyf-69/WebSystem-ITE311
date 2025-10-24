<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

/**
 * Admin Controller
 * Handles administrative functions like user management
 */
class AdminController extends Controller
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
    }

    /**
     * Display all users
     */
    public function users()
    {
        $data = [
            'title' => 'User Management',
            'users' => $this->userModel->findAll(),
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
     * Delete a user
     * 
     * @param int $id User ID to delete
     */
    public function deleteUser($id)
    {
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

        // Delete user
        if ($this->userModel->delete($id)) {
            $this->session->setFlashdata('success', 'User deleted successfully.');
        } else {
            $this->session->setFlashdata('error', 'Failed to delete user.');
        }

        return redirect()->to('/admin/users');
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
        if (!in_array($newRole, ['admin', 'student'])) {
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
        helper(['form']);

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'role' => 'required|in_list[admin,student]'
            ];

            if ($this->validate($rules)) {
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
}
