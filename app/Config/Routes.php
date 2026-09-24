<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
// $routes->get('/', 'Home::index');

// AUTH
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::authenticate');
$routes->post('logout', 'Auth::logout');

// ROUTE ADMIN 
$routes->group('admin', ['filter' => ['auth', 'role:Admin']], static function ($routes) {
    $routes->get('dashboard',       'Admin\Dashboard::index');
    $routes->get('products',        'Admin\Product::index');
    $routes->get('products/create', 'Admin\Product::create');
    $routes->post('products',       'Admin\Product::store');
});

// ROUTE QC
$routes->group('qc', ['filter' => ['auth', 'role:QC']], static function ($routes) {
    $routes->get('dashboard', 'Qc\Dashboard::index');
});
