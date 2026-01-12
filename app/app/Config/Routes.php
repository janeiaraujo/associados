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
$routes->group('', static function ($routes) {
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
        $routes->post('store', 'Associados::store', ['filter' => 'permission:associados.create']);
        $routes->get('edit/(:num)', 'Associados::edit/$1', ['filter' => 'permission:associados.update']);
        $routes->post('update/(:num)', 'Associados::update/$1', ['filter' => 'permission:associados.update']);
        $routes->post('delete/(:num)', 'Associados::delete/$1', ['filter' => 'permission:associados.delete']);
        $routes->get('view/(:num)', 'Associados::view/$1', ['filter' => 'permission:associados.view']);
        $routes->get('export', 'Associados::export', ['filter' => 'permission:relatorios.export']);
    });
    
    // Importação
    $routes->group('importacao', static function ($routes) {
        $routes->get('/', 'Importacao::index', ['filter' => 'permission:associados.import']);
        $routes->post('upload', 'Importacao::upload', ['filter' => 'permission:associados.import']);
        $routes->get('downloadTemplate', 'Importacao::downloadTemplate', ['filter' => 'permission:associados.import']);
    });
    
    // Unidades
    $routes->group('unidades', static function ($routes) {
        $routes->get('/', 'Unidades::index', ['filter' => 'permission:unidades.view']);
        $routes->get('create', 'Unidades::create', ['filter' => 'permission:unidades.create']);
        $routes->post('store', 'Unidades::store', ['filter' => 'permission:unidades.create']);
        $routes->get('edit/(:num)', 'Unidades::edit/$1', ['filter' => 'permission:unidades.update']);
        $routes->put('update/(:num)', 'Unidades::update/$1', ['filter' => 'permission:unidades.update']);
        $routes->delete('delete/(:num)', 'Unidades::delete/$1', ['filter' => 'permission:unidades.delete']);
    });
    
    // Funções
    $routes->group('funcoes', static function ($routes) {
        $routes->get('/', 'Funcoes::index', ['filter' => 'permission:funcoes.view']);
        $routes->get('create', 'Funcoes::create', ['filter' => 'permission:funcoes.create']);
        $routes->post('store', 'Funcoes::store', ['filter' => 'permission:funcoes.create']);
        $routes->get('edit/(:num)', 'Funcoes::edit/$1', ['filter' => 'permission:funcoes.update']);
        $routes->put('update/(:num)', 'Funcoes::update/$1', ['filter' => 'permission:funcoes.update']);
        $routes->delete('delete/(:num)', 'Funcoes::delete/$1', ['filter' => 'permission:funcoes.delete']);
    });
    
    // Relatórios
    $routes->group('relatorios', static function ($routes) {
        $routes->get('/', 'Relatorios::index', ['filter' => 'permission:relatorios.view']);
        $routes->post('generate', 'Relatorios::generate', ['filter' => 'permission:relatorios.view']);
        $routes->get('export/(:alpha)', 'Relatorios::export/$1', ['filter' => 'permission:relatorios.export']);
    });
    
    // Users (admin only)
    $routes->group('users', static function ($routes) {
        $routes->get('/', 'Users::index', ['filter' => 'permission:users.view']);
        $routes->get('create', 'Users::create', ['filter' => 'permission:users.create']);
        $routes->post('store', 'Users::store', ['filter' => 'permission:users.create']);
        $routes->get('edit/(:num)', 'Users::edit/$1', ['filter' => 'permission:users.update']);
        $routes->put('update/(:num)', 'Users::update/$1', ['filter' => 'permission:users.update']);
        $routes->delete('delete/(:num)', 'Users::delete/$1', ['filter' => 'permission:users.delete']);
    });
    
    // Audit logs
    $routes->get('audit', 'Audit::index', ['filter' => 'permission:audit.view']);
});
