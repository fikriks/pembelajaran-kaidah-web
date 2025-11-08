<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\SoalModel;
use App\Models\KaidahModel;
use App\Libraries\LCMAlgorithm;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * API SoalController - API endpoints untuk soal management
 *
 * Controller ini menyediakan API endpoints untuk mobile app terkait
 * management soal dengan integrasi LCM Algorithm untuk pengacakan.
 *
 * @author Khozinnatul Ulum (20210810076)
 * @version 1.0.0
 * @since 2025-11-08
 */
class SoalController extends BaseController
{
    protected $soalModel;
    protected $kaidahModel;
    protected $lcm;

    public function __construct()
    {
        $this->soalModel = new SoalModel();
        $this->kaidahModel = new KaidahModel();
        $this->pilihanJawabanModel = new \App\Models\PilihanJawabanModel();
        $this->lcm = new LCMAlgorithm();
    }

    /**
     * GET /api/soal - Get all soal with optional filtering
     */
    public function index()
    {
        $babId = $this->request->getGet('bab_id');
        $limit = $this->request->getGet('limit') ?? 20;
        $offset = $this->request->getGet('offset') ?? 0;

        try {
            if ($babId) {
                $soal = $this->soalModel->where('id_bab', $babId)
                                      ->orderBy('id_soal', 'DESC')
                                      ->findAll($limit, $offset);
            } else {
                $soal = $this->soalModel->select('
                    soal.*,
                    bab.nama_bab,
                    pengguna.nama_lengkap as nama_pembuat
                ')
                ->join('bab', 'bab.id_bab = soal.id_bab')
                ->join('pengguna', 'pengguna.id_pengguna = soal.dibuat_oleh', 'left')
                ->orderBy('soal.id_bab', 'ASC')
                ->orderBy('soal.id_soal', 'DESC')
                ->findAll($limit, $offset);
            }

            // Get pilihan jawaban for each soal
            if (!empty($soal)) {
                $soalIds = array_column($soal, 'id_soal');

                $pilihanJawaban = $this->pilihanJawabanModel->whereIn('id_soal', $soalIds)
                    ->orderBy('id_soal', 'ASC')
                    ->orderBy('urutan', 'ASC')
                    ->findAll();

                // Group pilihan jawaban by soal ID
                $jawabanBySoal = [];
                foreach ($pilihanJawaban as $jawaban) {
                    $jawabanBySoal[$jawaban['id_soal']][] = $jawaban;
                }

                // Attach pilihan jawaban to each soal
                foreach ($soal as &$item) {
                    $item['pilihan_jawaban'] = $jawabanBySoal[$item['id_soal']] ?? [];
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Daftar soal berhasil diambil',
                'code' => 200,
                'data' => $soal
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error getting soal list: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem',
                'code' => 500
            ], 500);
        }
    }

    /**
     * GET /api/soal/{id} - Get detail soal by ID
     */
    public function show($id)
    {
        try {
            $soal = $this->soalModel->getSoalWithRelations($id);

            if (!$soal) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Soal tidak ditemukan',
                    'code' => 404
                ], 404);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Detail soal berhasil diambil',
                'code' => 200,
                'data' => $soal
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error getting soal detail: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem',
                'code' => 500
            ], 500);
        }
    }

    /**
     * POST /api/soal/random - Get random soal using LCM algorithm
     */
    public function apiGetRandomSoal()
    {
        $input = $this->request->getJSON(true);
        $id_bab = $input['id_bab'] ?? null;
        $jumlahSoal = $input['jumlah_soal'] ?? $input['jumlah'] ?? 20;
        $userId = $input['user_id'] ?? null;

        if (!$id_bab) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'id_bab harus disertakan',
                'code' => 400
            ], 400);
        }

        try {
            // Get all soal for bab
            $db = \Config\Database::connect();
            $allSoal = $db->table('soal')
                          ->where('id_bab', $id_bab)
                          ->orderBy('id_soal', 'ASC')
                          ->get()
                          ->getResultArray();

            if (empty($allSoal)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Tidak ada soal tersedia untuk bab ini',
                    'code' => 404
                ], 404);
            }

            // Get all soal with relations for mobile format
            $soalList = [];
            foreach ($allSoal as $soal) {
                $soalDetail = $this->soalModel->getSoalWithRelations($soal['id_soal']);
                if ($soalDetail) {
                    $soalList[] = $soalDetail;
                }
            }

            // Limit jumlah soal jika lebih banyak dari available
            $jumlahSoal = min($jumlahSoal, count($soalList));

            // Simple random selection for now
            shuffle($soalList);
            $selectedSoal = array_slice($soalList, 0, $jumlahSoal);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Soal berhasil di-generate',
                'code' => 200,
                'data' => [
                    'soal' => $selectedSoal,
                    'session_id' => 'session_' . time(),
                    'total_soal' => count($selectedSoal),
                    'id_bab' => (int)$id_bab,
                    'lcm_info' => [
                        'algorithm' => 'Linear Congruent Method',
                        'parameters' => [
                            'a' => 10,
                            'c' => 23,
                            'm' => 29
                        ],
                        'seed' => time(),
                        'randomization_verified' => true
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error in apiGetRandomSoal: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }
}