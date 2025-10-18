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

// Dashboard routes (requires authentication)
$routes->get('dashboard', 'DashboardController::index', ['filter' => 'auth']);

// Announcements route (requires authentication)
$routes->get('announcements', 'Announcement::index', ['filter' => 'auth']);

// Admin routes (requires authentication + RoleAuth filter) - Task 4
$routes->group('admin', ['filter' => 'roleauth'], function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('users', 'AdminController::users');
    $routes->get('create-user', 'AdminController::createUser');
    $routes->post('create-user', 'AdminController::createUser');
    $routes->post('update-role/(:num)', 'AdminController::updateRole/$1');
    $routes->get('delete-user/(:num)', 'AdminController::deleteUser/$1');
});

// Teacher routes (requires authentication + RoleAuth filter) - Task 4
$routes->group('teacher', ['filter' => 'roleauth'], function($routes) {
    $routes->get('dashboard', 'Teacher::dashboard');
});

// Student routes (requires authentication + RoleAuth filter) - Task 4
$routes->group('student', ['filter' => 'roleauth'], function($routes) {
    $routes->get('dashboard', 'DashboardController::studentDashboard');
});
