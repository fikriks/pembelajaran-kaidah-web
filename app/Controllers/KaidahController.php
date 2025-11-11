<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MateriKaidahModel;
use CodeIgniter\HTTP\ResponseInterface;

class KaidahController extends BaseController
{
    protected $materiKaidahModel;

    public function __construct()
    {
        $this->materiKaidahModel = new MateriKaidahModel();
    }

    public function index()
    {
        // Get all kaidah data with creator names for DataTables (client-side)
        $kaidah = $this->materiKaidahModel->getWithCreator();

        // Get statistics for dashboard
        $stats = $this->getKaidahStatistics();

        // Get chapter progress data (for web interface)
        $userId = session()->get('user')['id_pengguna'] ?? 1; // Default to 1 for testing
        $chapterProgress = $this->materiKaidahModel->getOverallProgress($userId);

        // Prepare data for view
        $data = [
            'title' => 'Manajemen Materi Kaidah',
            'kaidah' => $kaidah,
            'stats' => $stats,
            'chapterProgress' => $chapterProgress,
            'user' => session()->get('user') // untuk info pembuat
        ];

        return view('kaidah/index', $data);
    }

    public function create()
    {
        // Get default bab from query parameter or use first bab
        $defaultBab = $this->request->getGet('id_bab') ?? 1;

        $data = [
            'title' => 'Tambah Materi Kaidah',
            'user' => session()->get('user'),
            'lastOrder' => $this->materiKaidahModel->getNextOrderInChapter($defaultBab),
            'defaultBab' => $defaultBab
        ];

        return view('kaidah/create', $data);
    }

    public function store()
    {
        // Validation rules
        $rules = [
            'judul_kaidah' => [
                'rules' => 'required|min_length[3]|max_length[255]',
                'errors' => [
                    'required' => 'Judul kaidah harus diisi',
                    'min_length' => 'Judul kaidah minimal 3 karakter',
                    'max_length' => 'Judul kaidah maksimal 255 karakter'
                ]
            ],
            'id_bab' => [
                'rules' => 'required|integer|greater_than[0]',
                'errors' => [
                    'required' => 'Bab wajib dipilih',
                    'integer' => 'Bab harus berupa angka',
                    'greater_than' => 'Pilih bab yang valid'
                ]
            ],
            'deskripsi' => [
                'rules' => 'max_length[500]',
                'errors' => [
                    'max_length' => 'Deskripsi maksimal 500 karakter'
                ]
            ],
            'penjelasan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Penjelasan kaidah harus diisi'
                ]
            ],
            'contoh' => [
                'rules' => 'max_length[1000]',
                'errors' => [
                    'max_length' => 'Contoh maksimal 1000 karakter'
                ]
            ],
            'urutan' => [
                'rules' => 'required|integer|greater_than_equal_to[1]',
                'errors' => [
                    'required' => 'Urutan harus diisi',
                    'integer' => 'Urutan harus berupa angka',
                    'greater_than_equal_to' => 'Urutan minimal 1'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data
        $data = [
            'judul_kaidah' => $this->request->getPost('judul_kaidah'),
            'id_bab' => $this->request->getPost('id_bab'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'penjelasan' => $this->request->getPost('penjelasan'),
            'contoh' => $this->request->getPost('contoh'),
            'urutan' => $this->request->getPost('urutan'),
            'dibuat_oleh' => session()->get('user')['id_pengguna']
        ];

        try {
            $id = $this->materiKaidahModel->insert($data);

            if (!$id) {
                return redirect()->back()->withInput()->with('error', 'Gagal menyimpan materi kaidah');
            }

            return redirect()->to('/kaidah')->with('success', 'Materi kaidah berhasil ditambahkan');
        } catch (\Exception $e) {
            log_message('error', 'Error creating kaidah: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    public function edit($id)
    {
        $kaidah = $this->materiKaidahModel->find($id);

        if (!$kaidah) {
            return redirect()->to('/kaidah')->with('error', 'Materi kaidah tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Materi Kaidah',
            'kaidah' => $kaidah,
            'user' => session()->get('user')
        ];

        return view('kaidah/edit', $data);
    }

    public function update($id)
    {
        $kaidah = $this->materiKaidahModel->find($id);

        if (!$kaidah) {
            return redirect()->to('/kaidah')->with('error', 'Materi kaidah tidak ditemukan');
        }

        // Validation rules
        $rules = [
            'judul_kaidah' => [
                'rules' => 'required|min_length[3]|max_length[255]',
                'errors' => [
                    'required' => 'Judul kaidah harus diisi',
                    'min_length' => 'Judul kaidah minimal 3 karakter',
                    'max_length' => 'Judul kaidah maksimal 255 karakter'
                ]
            ],
            'id_bab' => [
                'rules' => 'required|integer|greater_than[0]',
                'errors' => [
                    'required' => 'Bab wajib dipilih',
                    'integer' => 'Bab harus berupa angka',
                    'greater_than' => 'Pilih bab yang valid'
                ]
            ],
            'deskripsi' => [
                'rules' => 'max_length[500]',
                'errors' => [
                    'max_length' => 'Deskripsi maksimal 500 karakter'
                ]
            ],
            'penjelasan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Penjelasan kaidah harus diisi'
                ]
            ],
            'contoh' => [
                'rules' => 'max_length[1000]',
                'errors' => [
                    'max_length' => 'Contoh maksimal 1000 karakter'
                ]
            ],
            'urutan' => [
                'rules' => 'required|integer|greater_than_equal_to[1]',
                'errors' => [
                    'required' => 'Urutan harus diisi',
                    'integer' => 'Urutan harus berupa angka',
                    'greater_than_equal_to' => 'Urutan minimal 1'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data
        $data = [
            'judul_kaidah' => $this->request->getPost('judul_kaidah'),
            'id_bab' => $this->request->getPost('id_bab'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'penjelasan' => $this->request->getPost('penjelasan'),
            'contoh' => $this->request->getPost('contoh'),
            'urutan' => $this->request->getPost('urutan')
        ];

        try {
            if (!$this->materiKaidahModel->update($id, $data)) {
                return redirect()->back()->withInput()->with('error', 'Gagal mengupdate materi kaidah');
            }

            return redirect()->to('/kaidah')->with('success', 'Materi kaidah berhasil diupdate');
        } catch (\Exception $e) {
            log_message('error', 'Error updating kaidah: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    public function delete($id)
    {
        $kaidah = $this->materiKaidahModel->find($id);

        if (!$kaidah) {
            return redirect()->to('/kaidah')->with('error', 'Materi kaidah tidak ditemukan');
        }

        try {
            if (!$this->materiKaidahModel->delete($id)) {
                return redirect()->to('/kaidah')->with('error', 'Gagal menghapus materi kaidah');
            }

            return redirect()->to('/kaidah')->with('success', 'Materi kaidah berhasil dihapus');
        } catch (\Exception $e) {
            log_message('error', 'Error deleting kaidah: ' . $e->getMessage());
            return redirect()->to('/kaidah')->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    public function show($id)
    {
        $kaidah = $this->materiKaidahModel->getWithCreatorById($id);

        if (!$kaidah) {
            return redirect()->to('/kaidah')->with('error', 'Materi kaidah tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Materi Kaidah',
            'kaidah' => $kaidah,
            'user' => session()->get('user')
        ];

        return view('kaidah/show', $data);
    }

    // Method untuk statistics API (bisa dipakai dashboard)
    public function statistics()
    {
        // Calculate statistics manually since getKaidahStatistics method doesn't exist
        $stats = [
            'total' => $this->materiKaidahModel->countAll(),
            'bab1_count' => $this->materiKaidahModel->where('bab', 'BAB 1: KALAM')->countAllResults(),
            'bab2_count' => $this->materiKaidahModel->where('bab', 'BAB 2: I\'RAB')->countAllResults(),
        ];

        return $this->response->setJSON([
            'status' => 'success',
            'code' => 200,
            'data' => $stats
        ]);
    }

    // Method untuk reordering (drag & drop)
    public function reorder()
    {
        $orderData = $this->request->getPost('orderData');

        if (!$orderData || !is_array($orderData)) {
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid order data'
            ], 400);
        }

        try {
            $result = $this->materiKaidahModel->reorder($orderData);

            if ($result) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Urutan kaidah berhasil diupdate'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Gagal mengupdate urutan kaidah'
                ], 400);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error reordering kaidah: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 500,
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    // Chapter-based API methods for mobile app
    public function getChapters()
    {
        // Get user from session or API token
        $userId = $this->getUserIdFromRequest();

        if (!$userId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'User not authenticated',
                'code' => 401
            ], 401);
        }

        try {
            $chapters = $this->materiKaidahModel->getKaidahByChapterWithProgress($userId);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data chapters berhasil diambil',
                'code' => 200,
                'data' => $chapters
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error getting chapters: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem',
                'code' => 500
            ], 500);
        }
    }

    public function getChapterDetail($chapterCode)
    {
        $userId = $this->getUserIdFromRequest();

        if (!$userId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'User not authenticated',
                'code' => 401
            ], 401);
        }

        try {
            // Map chapter codes to actual chapter names
            $chapterMap = [
                'bab1' => 'BAB 1: KALAM',
                'bab2' => 'BAB 2: I\'RAB'
            ];

            $chapter = $chapterMap[$chapterCode] ?? $chapterCode;
            $chapterData = $this->materiKaidahModel->getKaidahByChapterWithProgress($userId, $chapter);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data chapter berhasil diambil',
                'code' => 200,
                'data' => $chapterData
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error getting chapter detail: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem',
                'code' => 500
            ], 500);
        }
    }

    public function getOverallProgress()
    {
        $userId = $this->getUserIdFromRequest();

        if (!$userId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'User not authenticated',
                'code' => 401
            ], 401);
        }

        try {
            $progress = $this->materiKaidahModel->getOverallProgress($userId);

            // Calculate overall statistics
            $totalMateri = 0;
            $totalCompleted = 0;
            $overallProgress = 0;
            $chaptersUnlocked = 0;

            foreach ($progress as $chapter => $stats) {
                $totalMateri += $stats['total_materi'];
                $totalCompleted += $stats['completed'];
                if ($stats['is_unlocked']) {
                    $chaptersUnlocked++;
                }
            }

            if ($totalMateri > 0) {
                $overallProgress = round(($totalCompleted / $totalMateri) * 100, 2);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data progress berhasil diambil',
                'code' => 200,
                'data' => [
                    'overall_progress' => $overallProgress,
                    'total_materi' => $totalMateri,
                    'total_completed' => $totalCompleted,
                    'chapters_unlocked' => $chaptersUnlocked,
                    'chapters_detail' => $progress
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error getting overall progress: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem',
                'code' => 500
            ], 500);
        }
    }

    public function getProgressOverview()
    {
        $userId = $this->getUserIdFromRequest();

        if (!$userId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'User not authenticated',
                'code' => 401
            ], 401);
        }

        try {
            // Get all chapters with their stats
            $chapters = $this->materiKaidahModel->getGroupedByChapter();
            $overview = [];

            foreach ($chapters as $chapter) {
                $chapterStats = $this->materiKaidahModel->getChapterStats($userId, $chapter['bab']);

                // Create chapter code for URL
                $chapterCode = 'bab1';
                if (strpos($chapter['bab'], 'BAB 2') !== false) {
                    $chapterCode = 'bab2';
                }

                $overview[] = [
                    'bab' => $chapter['bab'],
                    'deskripsi_bab' => $chapter['deskripsi_bab'],
                    'chapter_code' => $chapterCode,
                    'total_materi' => $chapterStats['total_materi'],
                    'completed' => $chapterStats['completed'],
                    'in_progress' => $chapterStats['in_progress'],
                    'not_started' => $chapterStats['not_started'],
                    'progress_percentage' => round($chapterStats['progress_percentage']),
                    'is_unlocked' => $chapterStats['is_unlocked'],
                    'status_color' => $chapterStats['progress_percentage'] >= 100 ? 'success' :
                                   ($chapterStats['progress_percentage'] > 0 ? 'warning' : 'secondary'),
                    'next_action' => $chapterStats['progress_percentage'] >= 100 ? 'review' :
                                   ($chapterStats['progress_percentage'] > 0 ? 'continue' : 'start')
                ];
            }

            // Calculate overall stats
            $totalMateri = array_sum(array_column($overview, 'total_materi'));
            $totalCompleted = array_sum(array_column($overview, 'completed'));
            $overallProgress = $totalMateri > 0 ? round(($totalCompleted / $totalMateri) * 100) : 0;

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Progress overview berhasil diambil',
                'code' => 200,
                'data' => [
                    'overall_progress' => $overallProgress,
                    'total_chapters' => count($overview),
                    'unlocked_chapters' => count(array_filter($overview, fn($c) => $c['is_unlocked'])),
                    'chapters' => $overview
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error getting progress overview: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem',
                'code' => 500
            ], 500);
        }
    }

    // Helper method to get user ID from request (session or API token)
    private function getUserIdFromRequest()
    {
        // Try to get from session first (web access)
        if (session()->has('user')) {
            return session()->get('user')['id_siswa'] ?? session()->get('user')['id_pengguna'];
        }

        // For API access, implement token validation here
        // For now, return null - implement proper token validation in production
        $token = $this->request->getHeader('Authorization');
        if ($token) {
            // TODO: Implement proper JWT token validation
            // For development, you can use simple token validation
            return null;
        }

        // For development/testing, return a mock user ID
        // In production, this should return null and require proper authentication
        return 1; // Mock user ID for testing
    }

    /**
     * Get kaidah statistics for dashboard
     */
    private function getKaidahStatistics()
    {
        $db = \Config\Database::connect();

        // Get total kaidah
        $totalKaidah = $this->materiKaidahModel->countAllResults();

        // Get kaidah per bab
        $kaidahPerBab = $db->table('materi_kaidah mk')
            ->select('b.nama_bab, COUNT(mk.id_materi) as total')
            ->join('bab b', 'b.id_bab = mk.id_bab', 'left')
            ->groupBy('b.id_bab, b.nama_bab')
            ->orderBy('b.id_bab')
            ->get()
            ->getResultArray();

        return [
            'total_kaidah' => $totalKaidah,
            'kaidah_per_bab' => $kaidahPerBab
        ];
    }
}