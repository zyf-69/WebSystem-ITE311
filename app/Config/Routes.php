<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');
<<<<<<< HEAD
$routes->setAutoRoute(false);
=======
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');
$routes->setAutoRoute(false);
>>>>>>> 90cc1f5 (Added Home controller, routes, and views for basic navigation)
