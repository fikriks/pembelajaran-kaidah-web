<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['url', 'form', 'html', 'text'];

    /**
     * Authentication instance
     *
     * @var \App\Libraries\Authentication
     */
    protected $auth;

    /**
     * Current user data
     *
     * @var array|null
     */
    protected $currentUser = null;

    /**
     * Data to be passed to views
     *
     * @var array
     */
    protected $data = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Initialize session
        $this->session = service('session');

        // Check if user is logged in
        $this->currentUser = $this->session->get('user');

        // Share data to all views
        $this->shareDataToViews();
    }

    /**
     * Share common data to all views
     */
    protected function shareDataToViews()
    {
        // Share current user
        $this->data['currentUser'] = $this->currentUser;

        // Share site config
        $this->data['siteName'] = 'Pembelajaran Kaidah Bahasa Arab';
        $this->data['siteDescription'] = 'Aplikasi pembelajaran kaidah bahasa Arab menggunakan algoritma LCM';

        // Share menu items
        $this->data['menuItems'] = $this->getMenuItems();

        // Share notifications (if any)
        $this->data['notifications'] = $this->getNotifications();
    }

    /**
     * Get menu items based on user role
     */
    protected function getMenuItems()
    {
        $menuItems = [];

        if (!$this->currentUser) {
            return $menuItems;
        }

        $role = $this->currentUser['hak_akses'];

        // Dashboard (always shown for logged in users)
        $menuItems[] = [
            'title' => 'Dashboard',
            'icon'  => 'fas fa-tachometer-alt',
            'url'   => site_url('dashboard'),
            'active' => uri_string() === 'dashboard'
        ];

        // User Management (Admin only)
        if ($role === 'ADMIN') {
            $menuItems[] = [
                'title' => 'Manajemen Pengguna',
                'icon'  => 'fas fa-users',
                'url'   => site_url('pengguna'),
                'active' => strpos(uri_string(), 'pengguna') === 0,
                'submenu' => [
                    [
                        'title' => 'Daftar Pengguna',
                        'url'   => site_url('pengguna'),
                        'active' => uri_string() === 'pengguna'
                    ],
                    [
                        'title' => 'Tambah Pengguna',
                        'url'   => site_url('pengguna/create'),
                        'active' => uri_string() === 'pengguna/create'
                    ]
                ]
            ];
        }

        // Siswa Management (Admin only)
        if ($role === 'ADMIN') {
            $menuItems[] = [
                'title' => 'Manajemen Siswa',
                'icon'  => 'fas fa-user-graduate',
                'url'   => site_url('siswa'),
                'active' => strpos(uri_string(), 'siswa') === 0,
                'submenu' => [
                    [
                        'title' => 'Daftar Siswa',
                        'url'   => site_url('siswa'),
                        'active' => uri_string() === 'siswa'
                    ],
                    [
                        'title' => 'Tambah Siswa',
                        'url'   => site_url('siswa/create'),
                        'active' => uri_string() === 'siswa/create'
                    ]
                ]
            ];
        }

        // Kaidah Management (Admin & Guru)
        $menuItems[] = [
            'title' => 'Materi Kaidah',
            'icon'  => 'fas fa-book',
            'url'   => site_url('kaidah'),
            'active' => strpos(uri_string(), 'kaidah') === 0,
            'submenu' => [
                [
                    'title' => 'Daftar Kaidah',
                    'url'   => site_url('kaidah'),
                    'active' => uri_string() === 'kaidah'
                ],
                [
                    'title' => 'Tambah Kaidah',
                    'url'   => site_url('kaidah/create'),
                    'active' => uri_string() === 'kaidah/create'
                ]
            ]
        ];

        // Soal Management (Admin & Guru)
        $menuItems[] = [
            'title' => 'Manajemen Soal',
            'icon'  => 'fas fa-question-circle',
            'url'   => site_url('soal'),
            'active' => strpos(uri_string(), 'soal') === 0,
            'submenu' => [
                [
                    'title' => 'Daftar Soal',
                    'url'   => site_url('soal'),
                    'active' => uri_string() === 'soal'
                ],
                [
                    'title' => 'Tambah Soal',
                    'url'   => site_url('soal/create'),
                    'active' => uri_string() === 'soal/create'
                ]
            ]
        ];

        // Laporan (Admin & Guru)
        $menuItems[] = [
            'title' => 'Laporan',
            'icon'  => 'fas fa-chart-bar',
            'url'   => site_url('reports'),
            'active' => strpos(uri_string(), 'reports') === 0,
            'submenu' => [
                [
                    'title' => 'Progress Siswa',
                    'url'   => site_url('reports/progress'),
                    'active' => uri_string() === 'reports/progress'
                ],
                [
                    'title' => 'Statistik Soal',
                    'url'   => site_url('reports/statistics'),
                    'active' => uri_string() === 'reports/statistics'
                ],
                [
                    'title' => 'Export Data',
                    'url'   => site_url('reports/export'),
                    'active' => uri_string() === 'reports/export'
                ]
            ]
        ];

        // Settings (Admin only)
        if ($role === 'admin') {
            $menuItems[] = [
                'title' => 'Pengaturan',
                'icon'  => 'fas fa-cog',
                'url'   => site_url('settings'),
                'active' => uri_string() === 'settings'
            ];
        }

        return $menuItems;
    }

    /**
     * Get notifications for current user
     */
    protected function getNotifications()
    {
        if (!$this->currentUser) {
            return [];
        }

        // For now, return empty array
        // In the future, this can fetch from database
        return [];
    }

    /**
     * Check if user has permission
     */
    protected function hasPermission($permission)
    {
        if (!$this->currentUser) {
            return false;
        }

        $role = $this->currentUser['hak_akses'];

        // Define permission matrix
        $permissions = [
            'ADMIN' => ['view_dashboard', 'manage_users', 'manage_kaidah', 'manage_soal', 'view_reports', 'manage_settings'],
            'GURU'  => ['view_dashboard', 'manage_kaidah', 'manage_soal', 'view_reports']
        ];

        return in_array($permission, $permissions[$role] ?? []);
    }

    /**
     * Require authentication
     */
    protected function requireAuth()
    {
        if (!$this->currentUser) {
            return redirect()->to(site_url('login'))->with('error', 'Anda harus login terlebih dahulu');
        }
    }

    /**
     * Require specific role
     */
    protected function requireRole($role)
    {
        $this->requireAuth();

        if ($this->currentUser['hak_akses'] !== $role) {
            return redirect()->to(site_url('dashboard'))->with('error', 'Anda tidak memiliki hak akses');
        }
    }

    /**
     * Require one of multiple roles
     */
    protected function requireAnyRole($roles)
    {
        $this->requireAuth();

        if (!in_array($this->currentUser['hak_akses'], $roles)) {
            return redirect()->to(site_url('dashboard'))->with('error', 'Anda tidak memiliki hak akses');
        }
    }

    /**
     * JSON response helper
     */
    protected function jsonResponse($data, $statusCode = 200)
    {
        return $this->response->setJSON($data)->setStatusCode($statusCode);
    }

    /**
     * Success JSON response
     */
    protected function jsonSuccess($message = 'Success', $data = null, $statusCode = 200)
    {
        $response = [
            'status'  => 'success',
            'message' => $message
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return $this->jsonResponse($response, $statusCode);
    }

    /**
     * Error JSON response
     */
    protected function jsonError($message = 'Error', $statusCode = 400, $errors = null)
    {
        $response = [
            'status'  => 'error',
            'message' => $message
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return $this->jsonResponse($response, $statusCode);
    }

    /**
     * Paginate helper
     */
    protected function paginate($model, $perPage = 10, $group = 'default')
    {
        $data = $model->paginate($perPage, $group);

        return [
            'data'  => $data,
            'pager' => $model->pager->links(),
            'total' => $model->pager->getTotal()
        ];
    }

    /**
     * Upload file helper
     */
    protected function uploadFile($fieldName, $uploadPath = 'uploads/', $allowedTypes = [], $maxSize = 2048)
    {
        $file = $this->request->getFile($fieldName);

        if ($file->isValid() && !$file->hasMoved()) {
            // Validate file type
            if (!empty($allowedTypes) && !in_array($file->getExtension(), $allowedTypes)) {
                return ['success' => false, 'message' => 'Tipe file tidak diizinkan'];
            }

            // Validate file size
            if ($file->getSize() > $maxSize * 1024) {
                return ['success' => false, 'message' => 'Ukuran file terlalu besar'];
            }

            // Generate unique filename
            $newName = $file->getRandomName();

            // Move file
            if ($file->move(WRITEPATH . $uploadPath, $newName)) {
                return [
                    'success' => true,
                    'filename' => $newName,
                    'filepath' => $uploadPath . $newName
                ];
            } else {
                return ['success' => false, 'message' => 'Gagal mengupload file'];
            }
        }

        return ['success' => false, 'message' => 'Tidak ada file yang diupload'];
    }

  }
