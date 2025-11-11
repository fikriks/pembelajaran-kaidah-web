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

// Guru Management removed - Only one guru exists: KM. Muhammad Faiz, S.Ag.

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

// Bab Management
$routes->group('bab', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'BabController::index');
    $routes->get('create', 'BabController::create');
    $routes->post('/', 'BabController::store');
    $routes->get('(:num)', 'BabController::show/$1');
    $routes->get('(:num)/edit', 'BabController::edit/$1');
    $routes->put('(:num)', 'BabController::update/$1');
    $routes->delete('(:num)', 'BabController::delete/$1');
    $routes->post('(:num)/toggleStatus', 'BabController::toggleStatus/$1');
    $routes->get('statistics', 'BabController::statistics');

    // API routes
    $routes->get('chapters', 'BabController::getChapters');
    $routes->get('(:num)/detail', 'BabController::getChapterDetail/$1');
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
    $routes->post('reorder', 'KaidahController::reorder');

    // Chapter-based API routes for mobile app
    $routes->get('chapters', 'KaidahController::getChapters');
    $routes->get('chapters/(:alphanum)', 'KaidahController::getChapterDetail/$1');
    $routes->get('progress/overall', 'KaidahController::getOverallProgress');
    $routes->get('progress/overview', 'KaidahController::getProgressOverview');
});

// Soal Management
$routes->group('soal', function($routes) {
    $routes->get('/', 'SoalController::index');
    $routes->get('create', 'SoalController::create');
    $routes->post('/', 'SoalController::store');
    $routes->get('(:num)/show', 'SoalController::show/$1');
    $routes->get('(:num)/edit', 'SoalController::edit/$1');
    $routes->put('(:num)', 'SoalController::update/$1');
    $routes->delete('(:num)', 'SoalController::delete/$1');
    $routes->get('batch', 'SoalController::batchImport');
    $routes->post('batch', 'SoalController::processBatchImport');
    $routes->get('analysis', 'SoalController::analysis');
    $routes->get('test-lcm', 'SoalController::testLCM');
    $routes->post('api/random-soal', 'SoalController::apiGetRandomSoal');
    $routes->get('statistics', 'SoalController::statistics');
    $routes->get('(:num)/preview-randomization', 'SoalController::previewRandomization/$1');
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

// Progress Belajar
$routes->group('progress', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'ProgressController::index');
    $routes->get('detail/(:num)', 'ProgressController::detail/$1');
    $routes->get('students', 'ProgressController::getStudents');
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
    $routes->get('kaidah', 'KaidahController::index');
    $routes->get('kaidah/(:num)', 'KaidahController::show/$1');
    $routes->get('kaidah/grouped', 'KaidahController::getGroupedByBab');
    $routes->get('kaidah/first/(:num)', 'KaidahController::getFirstMateriByBab/$1');

    // Soal routes
    $routes->get('soal', 'SoalController::index');
    $routes->get('soal/(:num)', 'SoalController::show/$1');
    $routes->post('soal/random', 'SoalController::apiGetRandomSoal');

    // Sesi/Learning routes
    $routes->post('sesi/start', 'SesiController::start');
    $routes->get('sesi/active', 'SesiController::active');
    $routes->get('sesi/(:num)', 'SesiController::show/$1');
    $routes->post('sesi/(:num)/jawab', 'SesiController::submitJawaban/$1');
    $routes->post('sesi/(:num)/finish', 'SesiController::finish/$1');
    $routes->get('sesi/(:num)/hasil', 'SesiController::hasil/$1');

    // Progress routes
    $routes->get('progress', 'ProgressController::index');
    $routes->get('progress/detail', 'ProgressController::detail');
    $routes->get('progress/history', 'ProgressController::history');
    $routes->get('progress/statistics', 'ProgressController::statistics');
    $routes->get('progress/chart', 'ProgressController::chart');

    // Material progress routes
    $routes->post('progress/materi/(:num)/complete', 'ProgressController::completeMateri/$1');
    $routes->get('progress/materi/(:num)', 'ProgressController::getMateriProgress/$1');

    // Bab/Chapter routes for Mobile App
    $routes->get('bab/chapters', 'BabController::getChapters');
    $routes->get('bab/chapters/(:num)', 'BabController::getChapterDetail/$1');
    $routes->get('bab/progress', 'BabController::getProgressOverview');
    $routes->get('bab/statistics', 'BabController::getStatistics');
    $routes->get('bab/(:num)/unlock-status', 'BabController::checkUnlockStatus/$1');
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
