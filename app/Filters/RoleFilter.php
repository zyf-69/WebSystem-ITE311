<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Role-Based Authorization Filter
 * Restricts access based on user roles
 */
class RoleFilter implements FilterInterface
{
    /**
     * Check if user has required role before allowing access
     *
     * @param RequestInterface $request
     * @param array|null $arguments - Expected format: ['admin'] or ['student']
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // First check if user is authenticated
        if (!$session->get('isLoggedIn')) {
            $session->setFlashdata('error', 'Please login to access this page.');
            return redirect()->to('/login');
        }
        
        // Get user's role from session
        $userRole = $session->get('role');
        
        // Check if role is specified in arguments
        if ($arguments !== null && is_array($arguments)) {
            // Check if user's role matches any of the allowed roles
            if (!in_array($userRole, $arguments)) {
                $session->setFlashdata('error', 'You do not have permission to access this page.');
                
                // Redirect based on user's actual role
                if ($userRole === 'admin') {
                    return redirect()->to('/admin/dashboard');
                } else {
                    return redirect()->to('/student/dashboard');
                }
            }
        }
    }

    /**
     * Allows after() to be called on the filter
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array|null $arguments
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after request
    }
}
