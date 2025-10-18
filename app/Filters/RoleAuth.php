<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * RoleAuth Filter
 * Implements role-based authorization for route access control
 * Task 4: Authorization Filter
 */
class RoleAuth implements FilterInterface
{
    /**
     * Check if user has permission to access the requested route
     *
     * @param RequestInterface $request
     * @param array|null $arguments
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        // Get user role from session
        $userRole = $session->get('role');
        
        // Get the current URI path
        $uri = $request->getUri();
        $path = $uri->getPath();

        // Role-based access control logic
        switch ($userRole) {
            case 'admin':
                // Admin can access any route starting with /admin
                if (strpos($path, '/admin') === 0) {
                    return; // Allow access
                }
                break;

            case 'teacher':
                // Teacher can only access routes starting with /teacher
                if (strpos($path, '/teacher') === 0) {
                    return; // Allow access
                }
                break;

            case 'student':
                // Student can access routes starting with /student and /announcements
                if (strpos($path, '/student') === 0 || strpos($path, '/announcements') === 0) {
                    return; // Allow access
                }
                break;
        }

        // If we reach here, access is denied
        return redirect()->to('/announcements')
                        ->with('error', 'Access Denied: Insufficient Permissions');
    }

    /**
     * After filter (not used)
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array|null $arguments
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
