<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('lending/register', 'RegisterController::index', ['filter' => 'guestFilter']);
$routes->post('lending/register', 'RegisterController::register', ['filter' => 'guestFilter']);

$routes->get('lending/login', 'LoginController::index', ['filter' => 'guestFilter']);
$routes->post('lending/login', 'LoginController::authenticate', ['filter' => 'guestFilter']);
 
$routes->get('lending/logout', 'LoginController::logout', ['filter' => 'authFilter']);
$routes->get('lending/dashboard', 'DashboardController::index', ['filter' => 'authFilter']);
$routes->get('lending', 'DashboardController::index', ['filter' => 'authFilter']);

// Customer routes
$routes->get('lending/customer', 'CustomerController::index', ['filter' => 'authFilter']);
$routes->get('lending/customer/create', 'CustomerController::create', ['filter' => 'authFilter']);
$routes->post('lending/customer/add', 'CustomerController::add', ['filter' => 'authFilter']);
$routes->get('lending/customer/edit/(:num)', 'CustomerController::edit/$1', ['filter' => 'authFilter']);
$routes->post('lending/customer/update/(:num)', 'CustomerController::update/$1', ['filter' => 'authFilter']);
$routes->get('lending/customer/delete/(:num)', 'CustomerController::delete/$1', ['filter' => 'authFilter']);

// Loan routes
$routes->get('lending/loan', 'LoanController::index', ['filter' => 'authFilter']);
//$routes->get('lending/loan/create/(:num)', 'LoanController::create/$1', ['filter' => 'authFilter']);
$routes->get('lending/loan/create', 'LoanController::create', ['filter' => 'authFilter']);
$routes->post('lending/loan/add', 'LoanController::add', ['filter' => 'authFilter']);
$routes->get('lending/loan/edit/(:num)', 'LoanController::edit/$1', ['filter' => 'authFilter']);
$routes->post('lending/loan/update/(:num)', 'LoanController::update/$1', ['filter' => 'authFilter']);
$routes->get('lending/loan/delete/(:num)', 'LoanController::delete/$1', ['filter' => 'authFilter']);
$routes->get('lending/loan/getLoanRecordOfCustomer/(:num)', 'LoanController::getLoanByCustomer/$1');

// Payment routes
$routes->get('lending/payment', 'PaymentController::index', ['filter' => 'authFilter']);
$routes->get('lending/payment/perLoan/(:num)', 'PaymentController::perLoan/$1', ['filter' => 'authFilter']);
$routes->get('lending/payment/make', 'PaymentController::makePayment', ['filter' => 'authFilter']);
$routes->get('lending/payment/make/(:num)', 'PaymentController::makePaymentPerLoan/$1', ['filter' => 'authFilter']);
$routes->post('lending/payment/add', 'PaymentController::add', ['filter' => 'authFilter']);
$routes->get('lending/payment/getPaymentsByBatch', 'PaymentController::getRecordsByBatch', ['filter' => 'authFilter']);

// Report routes
$routes->get('lending/report', 'ReportController::index', ['filter' => 'authFilter']);
$routes->get('lending/report/generate', 'ReportController::generateReport', ['filter' => 'authFilter']);


// Groups
// $routes->group('lending/loan', function ($routes) {
//     $routes->get('users', 'Admin\Users::index');
//     $routes->get('blog', 'Admin\Blog::index');
// });