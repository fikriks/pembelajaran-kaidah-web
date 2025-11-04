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
$routes->setAutoRoute(false);

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
$routes->group('pengguna', function($routes) {
    $routes->get('/', 'PenggunaController::index');
    $routes->get('create', 'PenggunaController::create');
    $routes->post('/', 'PenggunaController::store');
    $routes->get('(:num)', 'PenggunaController::show/$1');
    $routes->get('(:num)/show', 'PenggunaController::show/$1');
    $routes->get('(:num)/edit', 'PenggunaController::edit/$1');
    $routes->put('(:num)', 'PenggunaController::update/$1');
    $routes->delete('(:num)', 'PenggunaController::delete/$1');
    $routes->post('checkUsername', 'PenggunaController::checkUsername');
    $routes->post('(:num)/toggleStatus', 'PenggunaController::toggleStatus/$1');
    $routes->post('bulkAction', 'PenggunaController::bulkAction');
});

// Guru Management (Admin only)
$routes->group('guru', function($routes) {
    $routes->get('/', 'GuruController::index');
    $routes->get('create', 'GuruController::create');
    $routes->post('/', 'GuruController::store');
    $routes->get('(:num)/show', 'GuruController::show/$1');
    $routes->get('(:num)/edit', 'GuruController::edit/$1');
    $routes->put('(:num)', 'GuruController::update/$1');
    $routes->delete('(:num)', 'GuruController::delete/$1');
    $routes->post('checkUsername', 'GuruController::checkUsername');
    $routes->post('(:num)/toggleStatus', 'GuruController::toggleStatus/$1');
    $routes->post('bulkAction', 'GuruController::bulkAction');
});

// Siswa Management (Admin only)
$routes->group('siswa', function($routes) {
    $routes->get('/', 'SiswaController::index');
    $routes->get('create', 'SiswaController::create');
    $routes->post('/', 'SiswaController::store');
    $routes->get('(:num)/show', 'SiswaController::show/$1');
    $routes->get('(:num)/edit', 'SiswaController::edit/$1');
    $routes->put('(:num)', 'SiswaController::update/$1');
    $routes->delete('(:num)', 'SiswaController::delete/$1');
    $routes->patch('(:num)/reset-password', 'SiswaController::resetPassword/$1');
    $routes->get('(:num)/login-history', 'SiswaController::loginHistory/$1');
});

// Materi Kaidah Management
$routes->group('kaidah', function($routes) {
    $routes->get('/', 'KaidahController::index');
    $routes->get('create', 'KaidahController::create');
    $routes->post('/', 'KaidahController::store');
    $routes->get('(:num)', 'KaidahController::show/$1');
    $routes->get('(:num)/show', 'KaidahController::show/$1');
    $routes->get('(:num)/edit', 'KaidahController::edit/$1');
    $routes->put('(:num)', 'KaidahController::update/$1');
    $routes->delete('(:num)', 'KaidahController::delete/$1');
    $routes->get('statistics', 'KaidahController::statistics');
});

// Soal Management
$routes->group('soal', function($routes) {
    $routes->get('/', 'SoalController::index');
    $routes->get('create', 'SoalController::create');
    $routes->post('/', 'SoalController::store');
    $routes->get('(:num)', 'SoalController::show/$1');
    $routes->get('(:num)/edit', 'SoalController::edit/$1');
    $routes->put('(:num)', 'SoalController::update/$1');
    $routes->delete('(:num)', 'SoalController::delete/$1');
    $routes->get('batch', 'SoalController::batchImport');
    $routes->post('batch', 'SoalController::processBatchImport');
    $routes->get('analysis', 'SoalController::analysis');
    $routes->get('test-lcm', 'SoalController::testLCM');
    $routes->post('api/random-soal', 'SoalController::apiGetRandomSoal');
    $routes->get('statistics', 'SoalController::statistics');
    $routes->get('preview-randomization/(:num)', 'SoalController::previewRandomization/$1');
});

// Sesi Pembelajaran Management
$routes->group('sesi', function($routes) {
    $routes->get('/', 'SesiController::index');
    $routes->post('start', 'SesiController::startSession');
    $routes->post('submit-answer', 'SesiController::submitAnswer');
    $routes->post('complete', 'SesiController::completeSession');
    $routes->post('cancel', 'SesiController::cancelSession');
    $routes->get('(:num)', 'SesiController::show/$1');
    $routes->get('monitor', 'SesiController::monitor');

    // API endpoints
    $routes->get('status/(:num)', 'SesiController::getSessionStatus/$1');
    $routes->get('next-question/(:num)', 'SesiController::getNextQuestion/$1');
    $routes->get('statistics', 'SesiController::statistics');

    // Mobile API endpoints
    $routes->post('mobile/start', 'SesiController::startSessionMobile');
});

// Laporan
$routes->group('laporan', function($routes) {
    $routes->get('progress', 'LaporanController::progress');
    $routes->get('statistics', 'LaporanController::statistics');
    $routes->get('export', 'LaporanController::export');
    $routes->get('performance', 'LaporanController::performance');
});

// Settings (Admin only)
$routes->get('/settings', 'SettingsController::index');

// API Routes for Mobile App
$routes->group('api', ['namespace' => 'App\Controllers\API'], function($routes) {
    // Authentication routes
    $routes->post('auth/login', 'AuthController::login');
    $routes->post('auth/register', 'AuthController::register');
    $routes->get('auth/profile', 'AuthController::profile');
    $routes->put('auth/profile', 'AuthController::updateProfile');
    $routes->post('auth/logout', 'AuthController::logout');

    // Siswa Authentication routes (Mobile App)
    $routes->post('siswa/login', 'SiswaAuthController::login');
    $routes->get('siswa/profile', 'SiswaAuthController::profile');
    $routes->options('siswa/login', 'SiswaAuthController::options');
    $routes->options('siswa/profile', 'SiswaAuthController::options');

    // Kaidah routes
    
    // Sesi/Learning routes
    $routes->post('sesi/start', 'SesiController::start');
    $routes->get('sesi/active', 'SesiController::active');
    $routes->get('sesi/(:num)', 'SesiController::show/$1');
    $routes->post('sesi/(:num)/continue', 'SesiController::continue/$1');
    $routes->post('sesi/(:num)/jawab', 'SesiController::submitJawaban/$1');
    $routes->post('sesi/(:num)/finish', 'SesiController::finish/$1');
    $routes->get('sesi/(:num)/hasil', 'SesiController::hasil/$1');

    // Progress routes
    $routes->get('progress', 'ProgressController::index');
    $routes->get('progress/detail', 'ProgressController::detail');
    $routes->get('progress/history', 'ProgressController::history');
    $routes->get('progress/statistics', 'ProgressController::statistics');
    $routes->get('progress/chart', 'ProgressController::chart');
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
