<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\MateriKaidahModel;
use App\Models\SoalModel;
use App\Models\SesiLatihanModel;
use App\Models\RiwayatBelajarModel;
use App\Models\BabModel;
use App\Libraries\APIHelper;
use CodeIgniter\API\ResponseTrait;

class KaidahController extends BaseController
{
    use ResponseTrait;

    protected $materiKaidahModel;
    protected $soalModel;
    protected $sesiLatihanModel;
    protected $riwayatBelajarModel;
    protected $babModel;

    public function __construct()
    {
        $this->materiKaidahModel = new MateriKaidahModel();
        $this->soalModel = new SoalModel();
        $this->sesiLatihanModel = new SesiLatihanModel();
        $this->riwayatBelajarModel = new RiwayatBelajarModel();
        $this->babModel = new BabModel();
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

    /**
     * Get kaidah grouped by bab - Simple version
     * GET /api/kaidah/grouped
     */
    public function getGroupedByBab()
    {
        try {
            // Get all active bab with urutan
            $babList = $this->babModel
                ->where('is_active', 1)
                ->orderBy('urutan', 'ASC')
                ->findAll();

            if (empty($babList)) {
                return $this->respond([
                    'status' => 'success',
                    'message' => 'No bab records found',
                    'data' => ['groups' => []]
                ]);
            }

            $groups = [];

            foreach ($babList as $bab) {
                // Get all materi for this bab
                $kaidahList = $this->materiKaidahModel
                    ->where('id_bab', $bab['id_bab'])
                    ->findAll();

                // Process kaidah data and ensure ID is integer
                $processedKaidahList = [];
                $totalSoal = 0;
                foreach ($kaidahList as $kaidah) {
                    $processedKaidah = $kaidah;
                    // Ensure ID is integer
                    $processedKaidah['id_materi'] = (int)$kaidah['id_materi'];
                    $processedKaidah['id_bab'] = (int)$kaidah['id_bab'];
                    $processedKaidah['urutan'] = (int)$kaidah['urutan'];
                    $processedKaidah['dibuat_oleh'] = (int)$kaidah['dibuat_oleh'];

                    $processedKaidahList[] = $processedKaidah;

                    // Count soal for this kaidah
                    $soalCount = $this->soalModel
                        ->where('id_materi', $kaidah['id_materi'])
                        ->countAllResults();
                    $totalSoal += $soalCount;
                }

                $groups[] = [
                    'bab' => [
                        'id_bab' => (int)$bab['id_bab'],
                        'nama_bab' => $bab['nama_bab'],
                        'deskripsi' => $bab['deskripsi'],
                        'urutan' => (int)$bab['urutan']
                    ],
                    'kaidah_list' => $processedKaidahList,
                    'total_kaidah' => count($processedKaidahList),
                    'total_soal' => $totalSoal
                ];
            }

            return $this->respond([
                'status' => 'success',
                'message' => 'Data kaidah per bab berhasil diambil',
                'data' => [
                    'groups' => $groups,
                    'summary' => [
                        'total_bab' => count($babList),
                        'total_kaidah' => array_sum(array_column($groups, 'total_kaidah')),
                        'total_soal' => array_sum(array_column($groups, 'total_soal'))
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return $this->fail('Error: ' . $e->getMessage(), 500);
        }
    }
}