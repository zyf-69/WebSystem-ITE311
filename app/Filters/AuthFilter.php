<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Authentication Filter
 * Protects routes that require user authentication
 */
class AuthFilter implements FilterInterface
{
    /**
     * Check if user is authenticated before allowing access
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
            // Store the intended URL to redirect after login
            $session->set('redirect_url', current_url());
            
            // Set flash message
            $session->setFlashdata('error', 'Please login to access this page.');
            
            // Redirect to login page
            return redirect()->to('/login');
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
