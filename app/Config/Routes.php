<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
// $routes->get('/', 'Home::index');

$routes->get('/', static function () {
    if (session()->get('isLoggedIn')) {
        $role = session()->get('role');

        if ($role === 'Admin') {
            return redirect()->to('/admin/dashboard');
        }

        if ($role === 'QC') {
            return redirect()->to('/qc/dashboard');
        }
    }

    return redirect()->to('/login');
});

// AUTH
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::authenticate');
$routes->post('logout', 'Auth::logout');

// offline PWA
$routes->get('offline', static function () {
    return view('errors/offline');
});

// ROUTE ADMIN 
$routes->group('admin', ['filter' => ['auth', 'role:Admin']], static function ($routes) {
    $routes->get('dashboard',       'Admin\DashboardController::index');

    $routes->get('products',        'Admin\ProductController::index');
    $routes->get('products/create', 'Admin\ProductController::create');
    $routes->post('products',       'Admin\ProductController::store');
    $routes->get('products/(:num)/edit', 'Admin\ProductController::edit/$1');
    $routes->post('products/(:num)/update', 'Admin\ProductController::update/$1');
    $routes->post('products/(:num)/delete', 'Admin\ProductController::delete/$1');

    $routes->get('units', 'Admin\ProductUnitController::index');
    $routes->post('units', 'Admin\ProductUnitController::store');
    $routes->get('units/ready-for-sale', 'Admin\ProductUnitController::readyForSale');
    $routes->post('units/(:num)/delete', 'Admin\ProductUnitController::delete/$1');
    $routes->post('units/(:num)/resend-to-qc', 'Admin\ProductUnitController::resendToQc/$1');
    $routes->get('activity-logs', 'Admin\ActivityLogController::index');

    $routes->get('sales', 'Admin\SalesController::index');
    $routes->get('sales/(:num)', 'Admin\SalesController::show/$1');

    $routes->get('warranty-activation', 'Admin\WarrantyActivationController::index');
    $routes->get('warranty-activation/(:num)', 'Admin\WarrantyActivationController::create/$1');
    $routes->post('warranty-activation', 'Admin\WarrantyActivationController::store');

    $routes->get('warranty-cards', 'Admin\WarrantyCardController::index');
    $routes->get('warranty-cards/(:num)', 'Admin\WarrantyCardController::show/$1');
    $routes->post('warranty-cards/(:num)/generate', 'Admin\WarrantyCardController::generate/$1');
    $routes->get('warranty-cards/(:num)/download', 'Admin\WarrantyCardController::download/$1');

    $routes->get('claims', 'Admin\ClaimProcessingController::index');
    $routes->get('claims/(:num)', 'Admin\ClaimProcessingController::show/$1');
    $routes->post('claims/(:num)/status', 'Admin\ClaimProcessingController::updateStatus/$1');

    $routes->get('customers', 'Admin\CustomerController::index');
    $routes->get('settings', 'Admin\SettingController::index');
    $routes->post('settings', 'Admin\SettingController::update');

    $routes->get('reports', 'Admin\ReportController::index');
    $routes->get('reports/export-excel', 'Admin\ReportController::exportExcel');
    $routes->get('reports/export-pdf', 'Admin\ReportController::exportPdf');
});

// ROUTE QC
$routes->group('qc', ['filter' => ['auth', 'role:QC']], static function ($routes) {
    $routes->get('dashboard', 'Qc\DashboardController::index');

    $routes->get('inspections', 'Qc\QcInspectionController::index');
    $routes->get('inspections/(:num)', 'Qc\QcInspectionController::create/$1');
    $routes->post('inspections/(:num)', 'Qc\QcInspectionController::store/$1');
    $routes->get('inspections/(:num)/checklist', 'Qc\QcInspectionController::checklist/$1');
    $routes->post('inspections/(:num)/checklist', 'Qc\QcInspectionController::saveChecklist/$1');
    $routes->get('inspections/(:num)/result', 'Qc\QcInspectionController::result/$1');
    $routes->post('inspections/(:num)/result', 'Qc\QcInspectionController::saveResult/$1');
    $routes->get('inspections/(:num)/documentation', 'Qc\QcInspectionController::documentation/$1');
    $routes->post('inspections/(:num)/documentation', 'Qc\QcInspectionController::saveDocumentation/$1');

    $routes->get('history', 'Qc\QcHistoryController::index');
    $routes->get('units', 'Qc\QcUnitController::index');
});


// ROUTE PENGGUNA 
$routes->get('cek-garansi', 'Customer\PublicWarrantyController::index');
$routes->post('cek-garansi', 'Customer\PublicWarrantyController::search');
$routes->get('garansi/(:segment)', 'Customer\PublicWarrantyController::byToken/$1');
$routes->get('garansi/(:segment)/klaim', 'Customer\PublicClaimController::create/$1');
$routes->post('garansi/(:segment)/klaim', 'Customer\PublicClaimController::store/$1');
$routes->get('klaim/berhasil', 'Customer\PublicClaimController::success');
