<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('lending', 'MainController::index');




// Dashboard routes
// $routes->get('lending/', 'DashboardController::index');

// Loan routes
$routes->get('lending/loan', 'LoanController::index');
$routes->get('lending/loan/(:any)', 'LoanController::showAmount/$1');

// User routes
$routes->get('lending/login', 'UserController::login');

// Customer routes
$routes->get('lending/customer', 'CustomerController::index');


// Groups
// $routes->group('lending/loan', function ($routes) {
//     $routes->get('users', 'Admin\Users::index');
//     $routes->get('blog', 'Admin\Blog::index');
// });