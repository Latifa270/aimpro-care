<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
// $routes->get('/', 'Home::index');

// AUTH
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::authenticate');
$routes->post('logout', 'Auth::logout');

// ROUTE ADMIN 
$routes->group('admin', ['filter' => ['auth', 'role:Admin']], static function ($routes) {});

// ROUTE QC
$routes->group('qc', ['filter' => ['auth', 'role:QC']], static function ($routes) {});
