<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SoalModel;
use App\Models\KaidahModel;
use App\Libraries\LCMAlgorithm;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * SoalController - Management Soal dan Pilihan Jawaban
 *
 * Controller ini mengelola manajemen soal dan pilihan jawaban untuk sistem
 * pembelajaran kaidah bahasa Arab dengan integrasi LCM Algorithm untuk
 * pengacakan soal.
 *
 * @author Khozinnatul Ulum (20210810076)
 * @version 1.0.0
 * @since 2025-11-04
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
     * Display list of soal dengan DataTables
     */
    public function index()
    {
        // Get all data for client-side DataTables
        $soal = $this->soalModel->select('
            soal.*,
            COALESCE(bab.nama_bab, "Tidak ada bab") as nama_bab,
            COALESCE(bab.deskripsi, "Deskripsi tidak tersedia") as deskripsi_bab,
            COALESCE(pengguna.nama_lengkap, "Tidak ada pembuat") as nama_pembuat
        ')
        ->join('bab', 'bab.id_bab = soal.id_bab', 'left')
        ->join('pengguna', 'pengguna.id_pengguna = soal.dibuat_oleh', 'left')
        ->orderBy('soal.id_bab', 'ASC')
        ->orderBy('soal.id_soal', 'DESC')
        ->findAll();

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

        // Get statistics
        $stats = $this->soalModel->getSoalStatistics();

        // Get all materi for filter dropdown
        $allMateri = $this->kaidahModel->orderBy('urutan', 'ASC')->findAll();

        // Prepare data for view
        $data = [
            'title' => 'Manajemen Soal',
            'soal' => $soal,
            'stats' => $stats,
            'allMateri' => $allMateri,
            'user' => session()->get('user')
        ];

        return view('soal/index', $data);
    }

    /**
     * Display form untuk create soal baru
     */
    public function create()
    {
        // Get all materi for dropdown
        $materiList = $this->kaidahModel->orderBy('urutan', 'ASC')->findAll();

        $data = [
            'title' => 'Tambah Soal Baru',
            'materiList' => $materiList,
            'user' => session()->get('user'),
            'lastSoalData' => $this->session->getFlashdata('lastSoalData') ?? []
        ];

        return view('soal/create', $data);
    }

    /**
     * Process soal creation dengan pilihan jawaban
     */
    public function store()
    {
        // Validate form data
        $rules = [
            'id_materi' => [
                'rules' => 'required|integer|greater_than[0]',
                'errors' => [
                    'required' => 'Materi kaidah harus dipilih',
                    'integer' => 'ID materi harus berupa angka',
                    'greater_than' => 'ID materi tidak valid'
                ]
            ],
            'pertanyaan' => [
                'rules' => 'required|min_length[5]',
                'errors' => [
                    'required' => 'Pertanyaan harus diisi',
                    'min_length' => 'Pertanyaan minimal 5 karakter'
                ]
            ],
            'tingkat_kesulitan' => [
                'rules' => 'required|in_list[mudah,sedang,sulit]',
                'errors' => [
                    'required' => 'Tingkat kesulitan harus dipilih',
                    'in_list' => 'Tingkat kesulitan tidak valid'
                ]
            ],
            'poin' => [
                'rules' => 'required|integer|greater_than[0]',
                'errors' => [
                    'required' => 'Poin harus diisi',
                    'integer' => 'Poin harus berupa angka',
                    'greater_than' => 'Poin harus lebih dari 0'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Validate pilihan jawaban
        $pilihanJawaban = $this->request->getPost('pilihan_jawaban');
        if (empty($pilihanJawaban) || !is_array($pilihanJawaban)) {
            return redirect()->back()->withInput()->with('error', 'Pilihan jawaban harus diisi minimal 2 opsi');
        }

        // Validate jawaban data
        $validation = $this->soalModel->validatePilihanJawaban($pilihanJawaban);
        if (!$validation['success']) {
            return redirect()->back()->withInput()->with('error', $validation['message']);
        }

        // Prepare soal data
        $soalData = [
            'id_materi' => $this->request->getPost('id_materi'),
            'pertanyaan' => $this->request->getPost('pertanyaan'),
            'tingkat_kesulitan' => $this->request->getPost('tingkat_kesulitan'),
            'poin' => $this->request->getPost('poin'),
            'dibuat_oleh' => session()->get('user')['id_pengguna']
        ];

        try {
            // Save soal dengan pilihan jawaban (transactional)
            $idSoal = $this->soalModel->saveSoalWithJawaban($soalData, $pilihanJawaban);

            if (!$idSoal) {
                return redirect()->back()->withInput()->with('error', 'Gagal menyimpan soal');
            }

            return redirect()->to('/soal')->with('success', 'Soal berhasil ditambahkan');
        } catch (\Exception $e) {
            log_message('error', 'Error creating soal: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    /**
     * Display form untuk edit soal
     */
    public function edit($id)
    {
        $soal = $this->soalModel->getSoalWithRelations($id);

        if (!$soal) {
            return redirect()->to('/soal')->with('error', 'Soal tidak ditemukan');
        }

        // Get all materi for dropdown
        $materiList = $this->kaidahModel->orderBy('urutan', 'ASC')->findAll();

        $data = [
            'title' => 'Edit Soal',
            'soal' => $soal,
            'materiList' => $materiList,
            'user' => session()->get('user')
        ];

        return view('soal/edit', $data);
    }

    /**
     * Process soal update
     */
    public function update($id)
    {
        $soal = $this->soalModel->find($id);

        if (!$soal) {
            return redirect()->to('/soal')->with('error', 'Soal tidak ditemukan');
        }

        // Validate form data
        $rules = [
            'id_materi' => [
                'rules' => 'required|integer|greater_than[0]',
                'errors' => [
                    'required' => 'Materi kaidah harus dipilih',
                    'integer' => 'ID materi harus berupa angka',
                    'greater_than' => 'ID materi tidak valid'
                ]
            ],
            'pertanyaan' => [
                'rules' => 'required|min_length[5]',
                'errors' => [
                    'required' => 'Pertanyaan harus diisi',
                    'min_length' => 'Pertanyaan minimal 5 karakter'
                ]
            ],
            'tingkat_kesulitan' => [
                'rules' => 'required|in_list[mudah,sedang,sulit]',
                'errors' => [
                    'required' => 'Tingkat kesulitan harus dipilih',
                    'in_list' => 'Tingkat kesulitan tidak valid'
                ]
            ],
            'poin' => [
                'rules' => 'required|integer|greater_than[0]',
                'errors' => [
                    'required' => 'Poin harus diisi',
                    'integer' => 'Poin harus berupa angka',
                    'greater_than' => 'Poin harus lebih dari 0'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Validate pilihan jawaban
        $pilihanJawaban = $this->request->getPost('pilihan_jawaban');
        if (empty($pilihanJawaban) || !is_array($pilihanJawaban)) {
            return redirect()->back()->withInput()->with('error', 'Pilihan jawaban harus diisi minimal 2 opsi');
        }

        // Validate jawaban data
        $validation = $this->soalModel->validatePilihanJawaban($pilihanJawaban);
        if (!$validation['success']) {
            return redirect()->back()->withInput()->with('error', $validation['message']);
        }

        // Prepare soal data
        $soalData = [
            'id_materi' => $this->request->getPost('id_materi'),
            'pertanyaan' => $this->request->getPost('pertanyaan'),
            'tingkat_kesulitan' => $this->request->getPost('tingkat_kesulitan'),
            'poin' => $this->request->getPost('poin')
        ];

        try {
            // Update soal dengan pilihan jawaban (transactional)
            if (!$this->soalModel->updateSoalWithJawaban($id, $soalData, $pilihanJawaban)) {
                return redirect()->back()->withInput()->with('error', 'Gagal mengupdate soal');
            }

            return redirect()->to('/soal')->with('success', 'Soal berhasil diupdate');
        } catch (\Exception $e) {
            log_message('error', 'Error updating soal: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    /**
     * Delete soal dengan cascade delete pilihan jawaban
     */
    public function delete($id)
    {
        $soal = $this->soalModel->find($id);

        if (!$soal) {
            return redirect()->to('/soal')->with('error', 'Soal tidak ditemukan');
        }

        try {
            if (!$this->soalModel->deleteSoalWithJawaban($id)) {
                return redirect()->to('/soal')->with('error', 'Gagal menghapus soal');
            }

            return redirect()->to('/soal')->with('success', 'Soal berhasil dihapus');
        } catch (\Exception $e) {
            log_message('error', 'Error deleting soal: ' . $e->getMessage());
            return redirect()->to('/soal')->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    /**
     * Show detail soal
     */
    public function show($id)
    {
        $soal = $this->soalModel->getSoalWithRelations($id);

        if (!$soal) {
            return redirect()->to('/soal')->with('error', 'Soal tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Soal',
            'soal' => $soal,
            'user' => session()->get('user')
        ];

        return view('soal/show', $data);
    }

    /**
     * API untuk mendapatkan soal berdasarkan materi (untuk AJAX)
     */
    public function getSoalByMateri()
    {
        $materiId = $this->request->getGet('materi_id');

        if (!$materiId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ID materi harus disertakan'
            ], 400);
        }

        try {
            $soalList = $this->soalModel->getByMateri($materiId);

            return $this->response->setJSON([
                'status' => 'success',
                'data' => $soalList,
                'total' => count($soalList)
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error getting soal by materi: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    /**
     * Preview LCM randomization untuk materi tertentu
     */
    public function previewRandomization($materiId)
    {
        // Validate materi exists
        $materi = $this->kaidahModel->find($materiId);
        if (!$materi) {
            return redirect()->to('/soal')->with('error', 'Materi tidak ditemukan');
        }

        // Get all soal for materi
        $allSoal = $this->soalModel->getByMateri($materiId);

        if (empty($allSoal)) {
            return redirect()->to('/soal')->with('error', 'Tidak ada soal untuk materi ini');
        }

        // Generate preview dengan LCM
        $jumlahSoal = min(10, count($allSoal)); // Preview max 10 soal
        $previewResult = $this->lcm->generateQuizData($allSoal, $jumlahSoal, true);

        // Debug info
        $debugInfo = $this->lcm->debugSequence(5);

        $data = [
            'title' => 'Preview Randomization - ' . $materi['judul_kaidah'],
            'materi' => $materi,
            'allSoal' => $allSoal,
            'previewResult' => $previewResult,
            'debugInfo' => $debugInfo,
            'lcmParameters' => $this->lcm->getParameters(),
            'user' => session()->get('user')
        ];

        return view('soal/preview_randomization', $data);
    }

    /**
     * LCM Algorithm testing dan validation
     */
    public function testLCM()
    {
        $sampleSize = $this->request->getGet('sample_size') ?? 1000;
        $seed = $this->request->getGet('seed') ?? null;

        // Set seed if provided
        if ($seed) {
            $this->lcm->resetSeed($seed);
        }

        // Run chi-square test
        $chiSquareResult = $this->lcm->chiSquareTest($sampleSize);

        // Get sample sequence for visualization
        $sequence = $this->lcm->generateSequence(20);

        // Debug sequence with formula
        $debugSequence = $this->lcm->debugSequence(10);

        $data = [
            'title' => 'LCM Algorithm Testing',
            'chiSquareResult' => $chiSquareResult,
            'sampleSequence' => $sequence,
            'debugSequence' => $debugSequence,
            'lcmParameters' => $this->lcm->getParameters(),
            'currentSeed' => $this->lcm->getCurrentSeed(),
            'sampleSize' => $sampleSize,
            'user' => session()->get('user')
        ];

        return view('soal/test_lcm', $data);
    }

    /**
     * API endpoint untuk mobile - get random soal
     */
    public function apiGetRandomSoal()
    {
        $materiId = $this->request->getPost('materi_id');
        $jumlahSoal = $this->request->getPost('jumlah_soal') ?? 20;
        $userId = $this->request->getPost('user_id');

        if (!$materiId || !$userId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'materi_id dan user_id harus disertakan'
            ], 400);
        }

        try {
            // Get all soal for materi
            $allSoal = $this->soalModel->getSoalForMobile($materiId);

            if (empty($allSoal)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Tidak ada soal tersedia untuk materi ini'
                ], 404);
            }

            // Generate unique seed for this session
            $seed = $this->lcm->generateSeed($userId);
            $this->lcm->resetSeed($seed);

            // Generate random soal dengan LCM
            $result = $this->lcm->generateQuizData($allSoal, $jumlahSoal, true);

            if (!$result['success']) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => $result['message']
                ], 500);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Soal berhasil di-generate',
                'data' => [
                    'soal' => $result['data'],
                    'session_id' => $this->lcm->generateSessionId($userId, $materiId),
                    'seed_used' => $result['metadata']['seed_digunakan'],
                    'lcm_parameters' => $result['metadata']['lcm_parameters']
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error in apiGetRandomSoal: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    /**
     * API endpoint untuk statistics
     */
    public function statistics()
    {
        try {
            $stats = $this->soalModel->getSoalStatistics();

            return $this->response->setJSON([
                'status' => 'success',
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error getting soal statistics: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    /**
     * Batch import soal dari Excel/CSV (placeholder)
     */
    public function import()
    {
        // TODO: Implement batch import functionality
        return view('soal/import', [
            'title' => 'Import Soal',
            'user' => session()->get('user')
        ]);
    }

    /**
     * Export soal to Excel/PDF (placeholder)
     */
    public function export()
    {
        // TODO: Implement export functionality
        return redirect()->to('/soal')->with('info', 'Fitur export akan segera tersedia');
    }
}