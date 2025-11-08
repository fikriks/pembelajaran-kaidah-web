<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\MateriKaidahModel;
use App\Models\SoalModel;
use App\Models\SesiLatihanModel;
use App\Models\RiwayatBelajarModel;
use App\Libraries\APIHelper;
use CodeIgniter\API\ResponseTrait;

class KaidahController extends BaseController
{
    use ResponseTrait;

    protected $materiKaidahModel;
    protected $soalModel;
    protected $sesiLatihanModel;
    protected $riwayatBelajarModel;

    public function __construct()
    {
        $this->materiKaidahModel = new MateriKaidahModel();
        $this->soalModel = new SoalModel();
        $this->sesiLatihanModel = new SesiLatihanModel();
        $this->riwayatBelajarModel = new RiwayatBelajarModel();
    }

    /**
     * Get all kaidah for mobile
     * GET /api/kaidah
     */
    public function index()
    {
        // Get query parameters
        $search = $this->request->getVar('search');
        $page = $this->request->getVar('page') ?? 1;
        $limit = $this->request->getVar('limit') ?? 20;
        $offset = ($page - 1) * $limit;

        // Build query
        $builder = $this->materiKaidahModel->select('id_materi, judul_kaidah, deskripsi, penjelasan, contoh, urutan, dibuat_oleh, waktu_dibuat');

        if ($search) {
            $builder->like('judul_kaidah', $search)
                   ->orLike('deskripsi', $search);
        }

        $builder->orderBy('urutan', 'ASC');

        // Get total count
        $total = $builder->countAllResults(false);

        // Get data with pagination
        $kaidahList = $builder->get($limit, $offset)->getResultArray();

        // Add total questions for each kaidah (no progress tracking for simplicity)
        foreach ($kaidahList as &$kaidah) {
            // Count total questions for this kaidah
            $totalSoal = $this->soalModel->where('id_materi', $kaidah['id_materi'])->countAllResults();

            // No progress tracking - simplified response
            $riwayat = null;

            // Calculate completion percentage
            $completionPercentage = 0;
            $status = 'belum_dimulai';

            if ($riwayat) {
                $completionPercentage = $riwayat['persentase_penguasaan'] ?? 0;
                $status = $riwayat['status'] ?? 'belum_dimulai';
            }

            $kaidah['total_soal'] = $totalSoal;
            $kaidah['progress_percentage'] = $completionPercentage;
            $kaidah['status'] = $status;
            $kaidah['is_locked'] = false; // For simplicity, all kaidah are unlocked
        }

        $response = [
            'status' => 'success',
            'message' => 'Daftar kaidah berhasil diambil',
            'code' => 200,
            'data' => [
                'kaidah' => $kaidahList,
                'pagination' => [
                    'current_page' => (int)$page,
                    'per_page' => (int)$limit,
                    'total' => $total,
                    'total_pages' => ceil($total / $limit)
                ]
            ]
        ];

        return $this->respond($response, 200);
    }

    /**
     * Get detail kaidah
     * GET /api/kaidah/:id
     */
    public function show($id)
    {
        // No authentication required for simplicity

        $kaidah = $this->materiKaidahModel->find($id);

        if (!$kaidah) {
            return $this->fail('Kaidah tidak ditemukan', 404);
        }

        // Get additional information
        $totalSoal = $this->soalModel->where('id_materi', $id)->countAllResults();

        // No learning progress tracking for simplicity
        $riwayat = null;

        // No user-specific sessions for simplicity
        $recentSessions = [];

        $response = [
            'status' => 'success',
            'message' => 'Detail kaidah berhasil diambil',
            'code' => 200,
            'data' => [
                'kaidah' => [
                    'id_materi' => $kaidah['id_materi'],
                    'judul_kaidah' => $kaidah['judul_kaidah'],
                    'deskripsi' => $kaidah['deskripsi'],
                    'penjelasan' => $kaidah['penjelasan'],
                    'contoh' => $kaidah['contoh'],
                    'urutan' => $kaidah['urutan'],
                    'dibuat_oleh' => $kaidah['dibuat_oleh'],
                    'waktu_dibuat' => $kaidah['waktu_dibuat']
                ],
                'total_soal' => $totalSoal,
                'recent_sessions' => $recentSessions
            ]
        ];

        return $this->respond($response, 200);
    }
}