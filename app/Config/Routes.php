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

// Admin routes (requires authentication + admin role)
$routes->group('admin', ['filter' => ['auth', 'role:admin']], function($routes) {
    $routes->get('dashboard', 'DashboardController::adminDashboard');
    $routes->get('users', 'AdminController::users');
    $routes->get('create-user', 'AdminController::createUser');
    $routes->post('create-user', 'AdminController::createUser');
    $routes->post('update-role/(:num)', 'AdminController::updateRole/$1');
    $routes->get('delete-user/(:num)', 'AdminController::deleteUser/$1');
});

// Student routes (requires authentication + student role)
$routes->group('student', ['filter' => ['auth', 'role:student']], function($routes) {
    $routes->get('dashboard', 'DashboardController::studentDashboard');
});
