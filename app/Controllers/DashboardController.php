<?php

namespace App\Controllers;

use App\Models\PenggunaModel;
use App\Models\MateriKaidahModel;
use App\Models\SoalModel;
use App\Models\SesiLatihanModel;
use App\Models\RiwayatBelajarModel;

class DashboardController extends BaseController
{
    protected $penggunaModel;
    protected $materiKaidahModel;
    protected $soalModel;
    protected $sesiLatihanModel;
    protected $riwayatBelajarModel;

    public function __construct()
    {
        $this->penggunaModel = new PenggunaModel();
        $this->materiKaidahModel = new MateriKaidahModel();
        $this->soalModel = new SoalModel();
        $this->sesiLatihanModel = new SesiLatihanModel();
        $this->riwayatBelajarModel = new RiwayatBelajarModel();
    }

    /**
     * Show dashboard
     */
    public function index()
    {
        $this->requireAuth();

        $role = $this->currentUser['hak_akses'];

        if ($role === 'ADMIN') {
            return $this->adminDashboard();
        } elseif ($role === 'GURU') {
            return $this->guruDashboard();
        }

        return redirect()->to(site_url('auth/logout'));
    }

    /**
     * Admin dashboard
     */
    private function adminDashboard()
    {
        // Get real statistics from database
        $stats = [
            'total_users' => $this->penggunaModel->countAllResults(),
            'total_materi' => $this->materiKaidahModel->countAllResults(),
            'total_soal' => $this->soalModel->countAllResults(),
            'total_sessions' => $this->sesiLatihanModel->countAllResults(),
            'active_users' => $this->penggunaModel->countAllResults(), // For now, assume all users are active
            'completed_sessions' => $this->sesiLatihanModel->where('status', 'selesai')->countAllResults()
        ];

        // Empty recent sessions for now
        $recentSessions = [];

        // User statistics
        $userStats = [
            'admin_count' => $this->penggunaModel->where('hak_akses', 'ADMIN')->countAllResults(),
            'guru_count' => $this->penggunaModel->where('hak_akses', 'GURU')->countAllResults()
        ];

        // Material statistics (empty for now)
        $materialStats = [
            ['total_materials' => 0]
        ];

        // Empty activity data
        $activityData = [];

        $this->data = array_merge($this->data, [
            'page_title' => 'Dashboard Admin',
            'stats' => $stats,
            'recent_sessions' => $recentSessions,
            'user_stats' => $userStats,
            'material_stats' => $materialStats,
            'activity_data' => $activityData
        ]);

        return view('dashboard/admin', $this->data);
    }

    /**
     * Guru dashboard
     */
    private function guruDashboard()
    {
        $userId = $this->currentUser['id_pengguna'];

        // Get statistics for this guru
        $stats = [
            'total_materi' => $this->materiKaidahModel->where('dibuat_oleh', $userId)->countAllResults(),
            'total_soal' => $this->soalModel->where('dibuat_oleh', $userId)->countAllResults(),
            'total_sessions' => $this->sesiLatihanModel->countAllResults(),
            'my_material_sessions' => $this->getMyMaterialSessions($userId)
        ];

        // Get guru's materials
        $myMaterials = $this->materiKaidahModel->where('dibuat_oleh', $userId)
                                               ->orderBy('urutan', 'ASC')
                                               ->findAll();

        // Get recent sessions for guru's materials
        $recentSessions = $this->sesiLatihanModel->select('sesi_latihan.*, materi_kaidah.judul_kaidah')
                                                ->join('materi_kaidah', 'materi_kaidah.id_materi = sesi_latihan.id_materi')
                                                ->where('materi_kaidah.dibuat_oleh', $userId)
                                                ->orderBy('sesi_latihan.waktu_mulai', 'DESC')
                                                ->limit(5)
                                                ->findAll();

        // Get material performance
        $materialPerformance = $this->getMaterialPerformance($userId);

        // Get activity data for chart (last 7 days for guru's materials)
        $activityData = $this->getGuruActivityData($userId, 7);

        $this->data = array_merge($this->data, [
            'page_title' => 'Dashboard Guru',
            'stats' => $stats,
            'my_materials' => $myMaterials,
            'recent_sessions' => $recentSessions,
            'material_performance' => $materialPerformance,
            'activity_data' => $activityData
        ]);

        return view('dashboard/guru', $this->data);
    }

    /**
     * Get activity data for charts
     */
    private function getActivityData($days)
    {
        $startDate = date('Y-m-d', strtotime("-{$days} days"));

        $sessions = $this->sesiLatihanModel->select("DATE(waktu_mulai) as date, COUNT(*) as sessions, SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as completed")
                                          ->where('waktu_mulai >=', $startDate)
                                          ->groupBy('DATE(waktu_mulai)')
                                          ->findAll();

        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $dayData = [
                'date' => $date,
                'label' => date('d M', strtotime($date)),
                'sessions' => 0,
                'completed' => 0
            ];

            foreach ($sessions as $session) {
                if ($session['date'] === $date) {
                    $dayData['sessions'] = (int) $session['sessions'];
                    $dayData['completed'] = (int) $session['completed'];
                }
            }

            $data[] = $dayData;
        }

        return $data;
    }

    /**
     * Get guru's material sessions
     */
    private function getMyMaterialSessions($userId)
    {
        $materialIds = $this->materiKaidahModel->select('id_materi')
                                               ->where('dibuat_oleh', $userId)
                                               ->findAll();

        if (empty($materialIds)) {
            return 0;
        }

        $ids = array_column($materialIds, 'id_materi');

        return $this->sesiLatihanModel->whereIn('id_materi', $ids)
                                     ->countAllResults();
    }

    /**
     * Get material performance for guru
     */
    private function getMaterialPerformance($userId)
    {
        $materials = $this->materiKaidahModel->select('materi_kaidah.id_materi, materi_kaidah.judul_kaidah')
                                            ->where('dibuat_oleh', $userId)
                                            ->findAll();

        $performance = [];
        foreach ($materials as $material) {
            $stats = $this->sesiLatihanModel->getStatsByMateri($material['id_materi']);

            $performance[] = [
                'id_materi' => $material['id_materi'],
                'judul_kaidah' => $material['judul_kaidah'],
                'total_sessions' => $stats['total_soal'] ?? 0,
                'average_score' => $stats['rata_rata_poin'] ?? 0,
                'completion_rate' => $stats['total_soal'] > 0 ?
                    (($stats['total_soal'] - ($stats['poin_terendah'] ?? 0)) / $stats['total_soal']) * 100 : 0
            ];
        }

        return $performance;
    }

    /**
     * Get activity data for guru
     */
    private function getGuruActivityData($userId, $days)
    {
        $materialIds = $this->materiKaidahModel->select('id_materi')
                                               ->where('dibuat_oleh', $userId)
                                               ->findAll();

        if (empty($materialIds)) {
            return [];
        }

        $ids = array_column($materialIds, 'id_materi');
        $startDate = date('Y-m-d', strtotime("-{$days} days"));

        $sessions = $this->sesiLatihanModel->select("DATE(waktu_mulai) as date, COUNT(*) as sessions, AVG(skor) as avg_score")
                                          ->whereIn('id_materi', $ids)
                                          ->where('waktu_mulai >=', $startDate)
                                          ->groupBy('DATE(waktu_mulai)')
                                          ->findAll();

        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $dayData = [
                'date' => $date,
                'label' => date('d M', strtotime($date)),
                'sessions' => 0,
                'avg_score' => 0
            ];

            foreach ($sessions as $session) {
                if ($session['date'] === $date) {
                    $dayData['sessions'] = (int) $session['sessions'];
                    $dayData['avg_score'] = round((float) $session['avg_score'], 2);
                }
            }

            $data[] = $dayData;
        }

        return $data;
    }

    /**
     * API endpoint for dashboard stats (AJAX)
     */
    public function getStats()
    {
        $this->requireAuth();
        $this->response->setContentType('application/json');

        $role = $this->currentUser['hak_akses'];

        if ($role === 'admin') {
            $stats = [
                'total_users' => $this->penggunaModel->countAllResults(),
                'total_materi' => $this->materiKaidahModel->countAllResults(),
                'total_soal' => $this->soalModel->countAllResults(),
                'active_sessions' => $this->sesiLatihanModel->where('status', 'sedang_berjalan')->countAllResults()
            ];
        } else {
            $userId = $this->currentUser['id_pengguna'];
            $stats = [
                'total_materi' => $this->materiKaidahModel->where('dibuat_oleh', $userId)->countAllResults(),
                'total_soal' => $this->soalModel->where('dibuat_oleh', $userId)->countAllResults(),
                'total_sessions' => $this->getMyMaterialSessions($userId),
                'active_sessions' => $this->sesiLatihanModel->where('status', 'sedang_berjalan')->countAllResults()
            ];
        }

        return $this->jsonSuccess('Stats retrieved successfully', $stats);
    }
}