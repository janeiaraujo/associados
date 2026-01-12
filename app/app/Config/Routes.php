<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default redirect to login
$routes->get('/', static function() {
    return redirect()->to('/login');
});

// Auth routes (no authentication required)
$routes->group('', ['filter' => ''], static function ($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::loginPost');
    $routes->get('logout', 'Auth::logout');
    $routes->get('forgot-password', 'Auth::forgotPassword');
    $routes->post('forgot-password', 'Auth::forgotPasswordPost');
    $routes->get('reset-password', 'Auth::resetPassword');
    $routes->post('reset-password', 'Auth::resetPasswordPost');
});

// Protected routes (authentication required)
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'Dashboard::index', ['filter' => 'permission:dashboard.view']);
    
    // Associados
    $routes->group('associados', static function ($routes) {
        $routes->get('/', 'Associados::index', ['filter' => 'permission:associados.view']);
        $routes->get('create', 'Associados::create', ['filter' => 'permission:associados.create']);
        $routes->post('create', 'Associados::store', ['filter' => 'permission:associados.create']);
        $routes->get('edit/(:num)', 'Associados::edit/$1', ['filter' => 'permission:associados.update']);
        $routes->post('update/(:num)', 'Associados::update/$1', ['filter' => 'permission:associados.update']);
        $routes->post('delete/(:num)', 'Associados::delete/$1', ['filter' => 'permission:associados.delete']);
        $routes->get('view/(:num)', 'Associados::view/$1', ['filter' => 'permission:associados.view']);
    });
    
    // Importação
    $routes->group('importacao', static function ($routes) {
        $routes->get('/', 'Importacao::index', ['filter' => 'permission:associados.import']);
        $routes->post('upload', 'Importacao::upload', ['filter' => 'permission:associados.import']);
    });
    
    // Relatórios
    $routes->group('relatorios', static function ($routes) {
        $routes->get('/', 'Relatorios::index', ['filter' => 'permission:relatorios.view']);
        $routes->post('generate', 'Relatorios::generate', ['filter' => 'permission:relatorios.view']);
        $routes->get('export/(:alpha)', 'Relatorios::export/$1', ['filter' => 'permission:relatorios.export']);
    });
    
    // Users (admin only)
    $routes->group('users', static function ($routes) {
        $routes->get('/', 'Users::index', ['filter' => 'permission:users.manage']);
        $routes->get('create', 'Users::create', ['filter' => 'permission:users.manage']);
        $routes->post('create', 'Users::store', ['filter' => 'permission:users.manage']);
        $routes->get('edit/(:num)', 'Users::edit/$1', ['filter' => 'permission:users.manage']);
        $routes->post('update/(:num)', 'Users::update/$1', ['filter' => 'permission:users.manage']);
        $routes->post('delete/(:num)', 'Users::delete/$1', ['filter' => 'permission:users.manage']);
    });
    
    // Audit logs
    $routes->get('audit', 'Audit::index', ['filter' => 'permission:audit.view']);
});
