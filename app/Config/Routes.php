<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public routes (no authentication required)
$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');

// Authentication routes
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::login');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::register');
$routes->get('logout', 'Auth::logout');

// Alternative auth routes (for backward compatibility)
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/login', 'Auth::login');
$routes->get('auth/register', 'Auth::register');
$routes->post('auth/register', 'Auth::register');

// Dashboard route (unified, requires authentication)
// Step 6: Unified Dashboard Route - All users redirect here after login
$routes->get('dashboard', 'Auth::dashboard', ['filter' => 'auth']);
$routes->get('/dashboard', 'Auth::dashboard', ['filter' => 'auth']); // Alternative route format

// Announcements route (requires authentication - accessible to all logged-in users)
$routes->get('announcements', 'Announcement::index', ['filter' => 'auth']);
$routes->get('/announcements', 'Announcement::index', ['filter' => 'auth']); // Alternative route format

// Course enrollment route (requires authentication)
$routes->post('course/enroll', 'Course::enroll', ['filter' => 'auth']);
$routes->post('/course/enroll', 'Course::enroll', ['filter' => 'auth']);

// My Course route (simplified URL - for both teachers and students)
$routes->get('my-course', 'Auth::myCourse', ['filter' => 'roleauth']);
$routes->get('/my-course', 'Auth::myCourse', ['filter' => 'roleauth']);

// Teacher My Students route (simplified URL)
$routes->get('my-students', 'Teacher::myStudents', ['filter' => 'roleauth']);
$routes->get('/my-students', 'Teacher::myStudents', ['filter' => 'roleauth']);

// Admin routes (requires authentication + RoleAuth filter) - Task 4
$routes->group('admin', ['filter' => 'roleauth'], function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('users', 'AdminController::users');
    $routes->get('create-user', 'AdminController::createUser');
    $routes->post('create-user', 'AdminController::createUser');
    $routes->get('edit-user/(:num)', 'AdminController::editUser/$1');
    $routes->post('edit-user/(:num)', 'AdminController::editUser/$1');
    $routes->post('update-role/(:num)', 'AdminController::updateRole/$1');
    $routes->get('delete-user/(:num)', 'AdminController::deleteUser/$1');
    $routes->get('recover-user/(:num)', 'AdminController::recoverUser/$1');
    
    // Course management routes
    $routes->get('courses', 'AdminController::courses');
    $routes->get('create-course', 'AdminController::createCourse');
    $routes->post('create-course', 'AdminController::createCourse');
    $routes->get('assign-course', 'AdminController::assignCourse');
    $routes->post('assign-course', 'AdminController::assignCourse');
    $routes->get('edit-course/(:num)', 'AdminController::editCourse/$1');
    $routes->post('edit-course/(:num)', 'AdminController::editCourse/$1');
    $routes->get('delete-course/(:num)', 'AdminController::deleteCourse/$1');
});

// Teacher routes (requires authentication + RoleAuth filter) - Task 4
$routes->group('teacher', ['filter' => 'roleauth'], function($routes) {
    $routes->get('dashboard', 'Teacher::dashboard');
    $routes->get('my-courses', 'Teacher::myCourses');
    $routes->get('my-students', 'Teacher::myStudents');
    $routes->get('accept-enrollment/(:num)', 'Teacher::acceptEnrollment/$1');
    $routes->get('decline-enrollment/(:num)', 'Teacher::declineEnrollment/$1');
    $routes->get('unenroll-student/(:num)', 'Teacher::unenrollStudent/$1');
});

// Student routes (requires authentication + RoleAuth filter) - Task 4
$routes->group('student', ['filter' => 'roleauth'], function($routes) {
    $routes->get('dashboard', 'DashboardController::studentDashboard');
});
