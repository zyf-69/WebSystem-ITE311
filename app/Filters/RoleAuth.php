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
        $userRole = strtolower($session->get('role') ?? '');
        
        // If no role, deny access
        if (empty($userRole)) {
            return redirect()->to('/login')->with('error', 'Invalid session. Please login again.');
        }
        
        // Get the current URI path (normalize it)
        $uri = $request->getUri();
        $rawPath = $uri->getPath();
        
        // Remove base path if exists (e.g., /ITE311-DIGA/public)
        $basePath = base_url();
        $basePathUri = parse_url($basePath, PHP_URL_PATH);
        if ($basePathUri && $basePathUri !== '/' && strpos($rawPath, $basePathUri) === 0) {
            $rawPath = substr($rawPath, strlen($basePathUri));
        }
        
        // Also handle index.php if present
        $rawPath = str_replace('/index.php', '', $rawPath);
        $rawPath = str_replace('index.php', '', $rawPath);
        
        // Normalize path - remove any query strings or fragments
        $rawPath = preg_replace('/[?#].*$/', '', $rawPath);
        
        $path = trim($rawPath, '/'); // Remove leading/trailing slashes
        $pathWithSlash = '/' . $path; // Path with leading slash for matching
        $pathSegments = !empty($path) ? explode('/', $path) : []; // Split path into segments
        $firstSegment = !empty($pathSegments) ? $pathSegments[0] : '';
        
        // Debug logging (can be removed in production)
        // log_message('debug', "RoleAuth - Role: {$userRole}, RawPath: {$rawPath}, Path: {$path}, FirstSegment: {$firstSegment}");

        // Role-based access control logic
        switch (strtolower($userRole)) {
            case 'admin':
                // MAIN ADMIN - FULL ACCESS TO EVERYTHING
                // Admin can access:
                // - ALL routes starting with /admin (full admin panel access - create, edit, delete, etc.)
                // - Dashboard and announcements (like other users)
                // - Any other routes that don't conflict
                if (empty($path) ||
                    $firstSegment === 'admin' || 
                    $firstSegment === 'dashboard' || 
                    $firstSegment === 'announcements' ||
                    $pathWithSlash === '/' || 
                    $pathWithSlash === '/home' ||
                    strpos($pathWithSlash, '/admin') === 0 || 
                    strpos($pathWithSlash, '/dashboard') === 0 || 
                    strpos($pathWithSlash, '/announcements') === 0) {
                    return; // Allow access - ADMIN HAS FULL ACCESS
                }
                // Admin should have access to everything, so allow by default
                // Only block if it's a teacher or student specific route
                if ($firstSegment === 'teacher' || $firstSegment === 'student' ||
                    strpos($pathWithSlash, '/teacher') === 0 || strpos($pathWithSlash, '/student') === 0) {
                    // Don't block, but these routes are handled by their own filters
                    // Actually, let admin access these too for management purposes
                    return; // Allow admin to access everything
                }
                return; // Default: Allow admin access to everything

            case 'teacher':
                // Teacher can access:
                // - Routes starting with /teacher (including my-courses, my-students, etc.)
                // - /my-course route (simplified URL for My Courses)
                // - /my-students route (simplified URL for My Students)
                // - Dashboard and announcements
                // - Course enrollment route
                // - Course upload routes (for materials)
                // - Materials routes (download, delete)
                // - Empty path (home)
                // Check first segment
                if (empty($path) || 
                    $firstSegment === 'teacher' || 
                    $firstSegment === 'my-course' ||
                    $firstSegment === 'my-students' ||
                    $firstSegment === 'dashboard' || 
                    $firstSegment === 'announcements' ||
                    $firstSegment === 'materials' ||
                    ($firstSegment === 'course' && (isset($pathSegments[1]) && ($pathSegments[1] === 'enroll' || $pathSegments[1] === 'upload' || is_numeric($pathSegments[1])))) ||
                    $pathWithSlash === '/' ||
                    $pathWithSlash === '/home') {
                    return; // Allow access
                }
                // Check full path with leading slash
                if (strpos($pathWithSlash, '/teacher') === 0 || 
                    strpos($pathWithSlash, '/my-course') === 0 ||
                    strpos($pathWithSlash, '/my-students') === 0 ||
                    strpos($pathWithSlash, '/dashboard') === 0 || 
                    strpos($pathWithSlash, '/announcements') === 0 ||
                    strpos($pathWithSlash, '/course/enroll') === 0 ||
                    strpos($pathWithSlash, '/course/') === 0 ||
                    strpos($pathWithSlash, '/materials/') === 0) {
                    return; // Allow access
                }
                // Check raw path (without leading slash) for additional safety
                if (strpos($path, 'teacher') === 0 || 
                    strpos($path, 'my-course') === 0 ||
                    strpos($path, 'my-students') === 0 ||
                    strpos($path, 'dashboard') === 0 || 
                    strpos($path, 'announcements') === 0 ||
                    strpos($path, 'course/enroll') === 0 ||
                    strpos($path, 'course/') === 0 ||
                    strpos($path, 'materials/') === 0) {
                    return; // Allow access
                }
                // Final check - if path contains these keywords (handles base path issues)
                if (strpos($rawPath, 'announcements') !== false || 
                    strpos($rawPath, 'dashboard') !== false ||
                    strpos($rawPath, 'teacher') !== false ||
                    strpos($rawPath, 'my-course') !== false ||
                    strpos($rawPath, 'my-students') !== false ||
                    strpos($rawPath, 'course/') !== false ||
                    strpos($rawPath, 'materials/') !== false) {
                    return; // Allow access
                }
                break;

            case 'student':
                // Student can access routes starting with /student, /dashboard, /announcements, /my-course, and /course/enroll
                if ($firstSegment === 'student' || 
                    $firstSegment === 'dashboard' || 
                    $firstSegment === 'announcements' ||
                    $firstSegment === 'my-course' ||
                    ($firstSegment === 'course' && isset($pathSegments[1]) && $pathSegments[1] === 'enroll')) {
                    return; // Allow access
                }
                // Also check full path for backward compatibility
                if (strpos('/' . $path, '/student') === 0 || 
                    strpos('/' . $path, '/dashboard') === 0 || 
                    strpos('/' . $path, '/announcements') === 0 ||
                    strpos('/' . $path, '/my-course') === 0 ||
                    strpos('/' . $path, '/course/enroll') === 0) {
                    return; // Allow access
                }
                // Check raw path for additional safety
                if (strpos($path, 'my-course') === 0) {
                    return; // Allow access
                }
                // Final check - if path contains these keywords
                if (strpos($rawPath, 'my-course') !== false) {
                    return; // Allow access
                }
                break;
        }

        // If we reach here, access is denied (for non-admin users)
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
