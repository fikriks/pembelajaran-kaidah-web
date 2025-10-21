<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('AuthController');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

// Web Routes (Admin & Guru)
$routes->get('/', 'AuthController::login');
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::attemptLogin');
$routes->get('/logout', 'AuthController::logout');
$routes->post('/logout', 'AuthController::processLogout');

// Dashboard
$routes->get('/dashboard', 'DashboardController::index');

// User Management (Admin only)
$routes->group('/users', function($routes) {
    $routes->get('/', 'UserController::index');
    $routes->get('/create', 'UserController::create');
    $routes->post('/', 'UserController::store');
    $routes->get('/(:num)', 'UserController::show/$1');
    $routes->get('/(:num)/edit', 'UserController::edit/$1');
    $routes->put('/(:num)', 'UserController::update/$1');
    $routes->delete('/(:num)', 'UserController::delete/$1');
});

// Materi Kaidah Management
$routes->group('/kaidah', function($routes) {
    $routes->get('/', 'KaidahController::index');
    $routes->get('/create', 'KaidahController::create');
    $routes->post('/', 'KaidahController::store');
    $routes->get('/(:num)', 'KaidahController::show/$1');
    $routes->get('/(:num)/edit', 'KaidahController::edit/$1');
    $routes->put('/(:num)', 'KaidahController::update/$1');
    $routes->delete('/(:num)', 'KaidahController::delete/$1');
    $routes->get('/statistics', 'KaidahController::statistics');
});

// Soal Management
$routes->group('/soal', function($routes) {
    $routes->get('/', 'SoalController::index');
    $routes->get('/create', 'SoalController::create');
    $routes->post('/', 'SoalController::store');
    $routes->get('/(:num)', 'SoalController::show/$1');
    $routes->get('/(:num)/edit', 'SoalController::edit/$1');
    $routes->put('/(:num)', 'SoalController::update/$1');
    $routes->delete('/(:num)', 'SoalController::delete/$1');
    $routes->get('/batch', 'SoalController::batchImport');
    $routes->post('/batch', 'SoalController::processBatchImport');
    $routes->get('/analysis', 'SoalController::analysis');
});

// Reports
$routes->group('/reports', function($routes) {
    $routes->get('/progress', 'ReportController::progress');
    $routes->get('/statistics', 'ReportController::statistics');
    $routes->get('/export', 'ReportController::export');
    $routes->get('/performance', 'ReportController::performance');
});

// Settings (Admin only)
$routes->get('/settings', 'SettingsController::index');

// API Routes for Mobile App
$routes->group('/api', ['namespace' => 'App\Controllers\API'], function($routes) {
    // Authentication routes
    $routes->post('/auth/login', 'AuthController::login');
    $routes->post('/auth/register', 'AuthController::register');
    $routes->get('/auth/profile', 'AuthController::profile');
    $routes->put('/auth/profile', 'AuthController::updateProfile');
    $routes->post('/auth/logout', 'AuthController::logout');

    // Kaidah routes
    $routes->get('/kaidah', 'KaidahController::index');
    $routes->get('/kaidah/(:num)', 'KaidahController::show/$1');
    $routes->get('/kaidah/(:num)/progress', 'KaidahController::progress/$1');
    $routes->post('/kaidah/(:num)/start', 'KaidahController::start/$1');
    $routes->get('/kaidah/search', 'KaidahController::search');
    $routes->get('/kaidah/filters', 'KaidahController::filters');

    // Sesi/Learning routes
    $routes->post('/sesi/start', 'SesiController::start');
    $routes->get('/sesi/active', 'SesiController::active');
    $routes->get('/sesi/(:num)', 'SesiController::show/$1');
    $routes->post('/sesi/(:num)/continue', 'SesiController::continue/$1');
    $routes->post('/sesi/(:num)/jawab', 'SesiController::submitJawaban/$1');
    $routes->post('/sesi/(:num)/finish', 'SesiController::finish/$1');
    $routes->get('/sesi/(:num)/hasil', 'SesiController::hasil/$1');

    // Progress routes
    $routes->get('/progress', 'ProgressController::index');
    $routes->get('/progress/detail', 'ProgressController::detail');
    $routes->get('/progress/history', 'ProgressController::history');
    $routes->get('/progress/statistics', 'ProgressController::statistics');
    $routes->get('/progress/chart', 'ProgressController::chart');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
