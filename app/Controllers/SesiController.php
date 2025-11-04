<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SesiModel;
use App\Models\SoalModel;
use App\Models\KaidahModel;
use App\Models\SiswaModel;
use App\Libraries\LCMAlgorithm;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * SesiController - Management Sesi Pembelajaran dengan LCM
 *
 * Controller ini mengelola sesi pembelajaran siswa dengan integrasi LCM Algorithm
 * untuk randomisasi soal dan tracking progress real-time.
 *
 * @author Khozinnatul Ulum (20210810076)
 * @version 1.0.0
 * @since 2025-11-04
 */
class SesiController extends BaseController
{
    protected $sesiModel;
    protected $soalModel;
    protected $kaidahModel;
    protected $siswaModel;
    protected $lcm;

    public function __construct()
    {
        $this->sesiModel = new SesiModel();
        $this->soalModel = new SoalModel();
        $this->kaidahModel = new KaidahModel();
        $this->siswaModel = new SiswaModel();
        $this->lcm = new LCMAlgorithm();
    }

    /**
     * Display list sesi pembelajaran
     */
    public function index()
    {
        $search = $this->request->getGet('search');
        $statusFilter = $this->request->getGet('status');
        $materiFilter = $this->request->getGet('materi');
        $siswaFilter = $this->request->getGet('siswa');
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 10;

        // Build filters
        $filters = [
            'status' => $statusFilter,
            'id_materi' => $materiFilter,
            'id_siswa' => $siswaFilter,
            'search' => $search
        ];

        // Get sesi with pagination
        $sesiData = $this->sesiModel->getSesiWithPagination($perPage, $page, $filters);

        // Get statistics
        $stats = $this->sesiModel->getSesiStatistics();

        // Get dropdown data
        $allMateri = $this->kaidahModel->orderBy('urutan', 'ASC')->findAll();
        $allSiswa = $this->siswaModel->orderBy('nama_lengkap', 'ASC')->findAll();

        // Get active sessions for monitoring
        $activeSessions = $this->sesiModel->getSesiAktif();

        // Prepare data for view
        $data = [
            'title' => 'Manajemen Sesi Pembelajaran',
            'sesi' => $sesiData['data'],
            'pager' => $this->sesiModel->pager,
            'total' => $sesiData['total'],
            'perPage' => $sesiData['perPage'],
            'currentPage' => $sesiData['currentPage'],
            'search' => $search,
            'statusFilter' => $statusFilter,
            'materiFilter' => $materiFilter,
            'siswaFilter' => $siswaFilter,
            'stats' => $stats,
            'allMateri' => $allMateri,
            'allSiswa' => $allSiswa,
            'activeSessions' => $activeSessions,
            'user' => session()->get('user')
        ];

        return view('sesi/index', $data);
    }

    /**
     * Start new learning session
     */
    public function startSession()
    {
        $idSiswa = $this->request->getPost('id_siswa');
        $idMateri = $this->request->getPost('id_materi');
        $jumlahSoal = $this->request->getPost('jumlah_soal') ?? 20;

        // Validate input
        if (!$idSiswa || !$idMateri) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ID siswa dan materi harus disertakan'
            ], 400);
        }

        // Check if siswa exists
        $siswa = $this->siswaModel->find($idSiswa);
        if (!$siswa) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Siswa tidak ditemukan'
            ], 404);
        }

        // Check if materi exists
        $materi = $this->kaidahModel->find($idMateri);
        if (!$materi) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Materi tidak ditemukan'
            ], 404);
        }

        // Check if siswa has active session
        if ($this->sesiModel->hasSesiAktif($idSiswa)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Siswa memiliki sesi aktif, selesaikan terlebih dahulu'
            ], 409);
        }

        try {
            // Create new session
            $idSesi = $this->sesiModel->createSesi($idSiswa, $idMateri, $jumlahSoal);

            if (!$idSesi) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal membuat sesi pembelajaran'
                ], 500);
            }

            // Get session data
            $sesi = $this->sesiModel->getSesiWithRelations($idSesi);

            // Generate quiz data using LCM
            $allSoal = $this->soalModel->getByMateri($idMateri);
            $quizResult = $this->lcm->generateQuizData($allSoal, $jumlahSoal, true);

            if (!$quizResult['success']) {
                // Cancel session if quiz generation fails
                $this->sesiModel->batalkanSesi($idSesi);
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal generate soal: ' . $quizResult['message']
                ], 500);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Sesi pembelajaran berhasil dimulai',
                'data' => [
                    'sesi' => $sesi,
                    'soal' => $quizResult['data'],
                    'metadata' => $quizResult['metadata']
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error starting session: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    /**
     * Submit answer for a question
     */
    public function submitAnswer()
    {
        $idSesi = $this->request->getPost('id_sesi');
        $idSoal = $this->request->getPost('id_soal');
        $idPilihan = $this->request->getPost('id_pilihan');

        // Validate input
        if (!$idSesi || !$idSoal || !$idPilihan) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data tidak lengkap'
            ], 400);
        }

        try {
            // Get session data
            $sesi = $this->sesiModel->find($idSesi);
            if (!$sesi) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Sesi tidak ditemukan'
                ], 404);
            }

            // Check if session is still active
            if ($sesi['status'] !== 'sedang_berjalan') {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Sesi sudah tidak aktif'
                ], 400);
            }

            // Get question data
            $soal = $this->soalModel->find($idSoal);
            if (!$soal) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Soal tidak ditemukan'
                ], 404);
            }

            // Get answer data
            $db = \Config\Database::connect();
            $pilihan = $db->table('pilihan_jawaban')
                ->where('id_pilihan', $idPilihan)
                ->where('id_soal', $idSoal)
                ->get()
                ->getRowArray();

            if (!$pilihan) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Pilihan jawaban tidak valid'
                ], 400);
            }

            // Check if already answered
            $existingAnswer = $db->table('detail_jawaban_siswa')
                ->where('id_sesi', $idSesi)
                ->where('id_soal', $idSoal)
                ->get()
                ->getRowArray();

            if ($existingAnswer) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Soal sudah dijawab'
                ], 400);
            }

            // Save answer
            $urutanSoal = $db->table('detail_jawaban_siswa')
                ->where('id_sesi', $idSesi)
                ->countAllResults() + 1;

            $answerData = [
                'id_sesi' => $idSesi,
                'id_soal' => $idSoal,
                'id_pilihan' => $idPilihan,
                'urutan_soal' => $urutanSoal,
                'is_benar' => (bool)$pilihan['is_benar'],
                'waktu_jawab' => date('Y-m-d H:i:s'),
                'waktu_dibuat' => date('Y-m-d H:i:s')
            ];

            $db->table('detail_jawaban_siswa')->insert($answerData);

            // Update session progress
            $this->sesiModel->updateProgress($idSesi, $pilihan['is_benar'], $soal['poin']);

            // Get updated session data
            $updatedSesi = $this->sesiModel->find($idSesi);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Jawaban berhasil disimpan',
                'data' => [
                    'is_benar' => (bool)$pilihan['is_benar'],
                    'poin_didapat' => $pilihan['is_benar'] ? $soal['poin'] : 0,
                    'total_benar' => $updatedSesi['soal_benar'],
                    'total_skor' => $updatedSesi['skor'],
                    'progress' => $updatedSesi['total_soal'] > 0 ?
                        round(($updatedSesi['soal_benar'] / $updatedSesi['total_soal']) * 100, 1) : 0,
                    'urutan_soal' => $urutanSoal
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error submitting answer: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    /**
     * Complete session
     */
    public function completeSession()
    {
        $idSesi = $this->request->getPost('id_sesi');

        if (!$idSesi) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ID sesi harus disertakan'
            ], 400);
        }

        try {
            // Complete session
            if (!$this->sesiModel->selesaikanSesi($idSesi)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal menyelesaikan sesi'
                ], 500);
            }

            // Get completed session data
            $sesi = $this->sesiModel->getSesiWithRelations($idSesi);

            // Get all answers for this session
            $db = \Config\Database::connect();
            $answers = $db->table('detail_jawaban_siswa')
                ->select('detail_jawaban_siswa.*, soal.pertanyaan, soal.poin, pilihan_jawaban.teks_jawaban')
                ->join('soal', 'soal.id_soal = detail_jawaban_siswa.id_soal')
                ->join('pilihan_jawaban', 'pilihan_jawaban.id_pilihan = detail_jawaban_siswa.id_pilihan')
                ->where('detail_jawaban_siswa.id_sesi', $idSesi)
                ->orderBy('detail_jawaban_siswa.urutan_soal', 'ASC')
                ->get()
                ->getResultArray();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Sesi berhasil diselesaikan',
                'data' => [
                    'sesi' => $sesi,
                    'answers' => $answers,
                    'statistics' => [
                        'total_soal' => $sesi['total_soal'],
                        'soal_benar' => $sesi['soal_benar'],
                        'soal_salah' => $sesi['total_soal'] - $sesi['soal_benar'],
                        'skor_akhir' => $sesi['skor'],
                        'persentase_benar' => $sesi['total_soal'] > 0 ?
                            round(($sesi['soal_benar'] / $sesi['total_soal']) * 100, 1) : 0,
                        'durasi_menit' => $sesi['durasi_detik'] ? round($sesi['durasi_detik'] / 60, 1) : 0
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error completing session: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    /**
     * Cancel session
     */
    public function cancelSession()
    {
        $idSesi = $this->request->getPost('id_sesi');

        if (!$idSesi) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ID sesi harus disertakan'
            ], 400);
        }

        try {
            if (!$this->sesiModel->batalkanSesi($idSesi)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal membatalkan sesi'
                ], 500);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Sesi berhasil dibatalkan'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error cancelling session: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    /**
     * Display session details
     */
    public function show($id)
    {
        $sesi = $this->sesiModel->getSesiForMobile($id);

        if (!$sesi) {
            return redirect()->to('/sesi')->with('error', 'Sesi tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Sesi Pembelajaran',
            'sesi' => $sesi,
            'user' => session()->get('user')
        ];

        return view('sesi/show', $data);
    }

    /**
     * Monitor active sessions
     */
    public function monitor()
    {
        // Auto cleanup timeout sessions
        $this->sesiModel->cleanupTimeoutSesi(30);

        $activeSessions = $this->sesiModel->getSesiAktif();
        $recentSessions = $this->sesiModel->getSesiSelesaiHariIni();
        $stats = $this->sesiModel->getSesiStatistics();

        $data = [
            'title' => 'Monitor Sesi Aktif',
            'activeSessions' => $activeSessions,
            'recentSessions' => $recentSessions,
            'stats' => $stats,
            'user' => session()->get('user')
        ];

        return view('sesi/monitor', $data);
    }

    /**
     * API: Get session status
     */
    public function getSessionStatus($idSesi)
    {
        $sesi = $this->sesiModel->getSesiForMobile($idSesi);

        if (!$sesi) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Sesi tidak ditemukan'
            ], 404);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => [
                'sesi' => $sesi,
                'is_active' => $sesi['status'] === 'sedang_berjalan',
                'progress' => $sesi['progress_persen'],
                'durasi_menit' => $sesi['durasi_detik'] ? round($sesi['durasi_detik'] / 60, 1) : 0
            ]
        ]);
    }

    /**
     * API: Get next question for session
     */
    public function getNextQuestion($idSesi)
    {
        $sesi = $this->sesiModel->find($idSesi);

        if (!$sesi) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Sesi tidak ditemukan'
            ], 404);
        }

        if ($sesi['status'] !== 'sedang_berjalan') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Sesi tidak aktif'
            ], 400);
        }

        try {
            // Get all questions for this session's materi
            $allSoal = $this->soalModel->getByMateri($sesi['id_materi']);

            // Generate questions using LCM with session seed
            $this->lcm->resetSeed($sesi['seed_digunakan']);
            $quizResult = $this->lcm->generateQuizData($allSoal, $sesi['total_soal'], true);

            if (!$quizResult['success']) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal generate soal'
                ], 500);
            }

            // Get current answered questions
            $db = \Config\Database::connect();
            $answeredQuestions = $db->table('detail_jawaban_siswa')
                ->where('id_sesi', $idSesi)
                ->get()
                ->getResultArray();

            $answeredSoalIds = array_column($answeredQuestions, 'id_soal');

            // Find next unanswered question
            $nextQuestion = null;
            $nextQuestionIndex = null;

            foreach ($quizResult['data'] as $index => $question) {
                if (!in_array($question['id_soal'], $answeredSoalIds)) {
                    $nextQuestion = $question;
                    $nextQuestionIndex = $index + 1;
                    break;
                }
            }

            if ($nextQuestion) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => [
                        'question' => $nextQuestion,
                        'nomor' => $nextQuestionIndex,
                        'total_questions' => count($quizResult['data']),
                        'answered_count' => count($answeredQuestions),
                        'remaining_count' => count($quizResult['data']) - count($answeredQuestions)
                    ]
                ]);
            } else {
                // All questions answered
                return $this->response->setJSON([
                    'status' => 'completed',
                    'message' => 'Semua soal telah dijawab',
                    'data' => [
                        'answered_count' => count($answeredQuestions),
                        'total_questions' => count($quizResult['data'])
                    ]
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error getting next question: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    /**
     * API: Get statistics
     */
    public function statistics()
    {
        try {
            $stats = $this->sesiModel->getSesiStatistics();
            $statistikPerMateri = $this->sesiModel->getStatistikPerMateri(5);
            $statistikPerSiswa = $this->sesiModel->getStatistikPerSiswa(5);

            return $this->response->setJSON([
                'status' => 'success',
                'data' => [
                    'overall' => $stats,
                    'per_materi' => $statistikPerMateri,
                    'per_siswa' => $statistikPerSiswa
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error getting statistics: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    /**
     * API: Start session for mobile
     */
    public function startSessionMobile()
    {
        $nis = $this->request->getPost('nis');
        $idMateri = $this->request->getPost('id_materi');
        $jumlahSoal = $this->request->getPost('jumlah_soal') ?? 20;

        if (!$nis || !$idMateri) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'NIS dan ID materi harus disertakan'
            ], 400);
        }

        // Get siswa by NIS
        $siswa = $this->siswaModel->where('nis', $nis)->first();
        if (!$siswa) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Siswa tidak ditemukan'
            ], 404);
        }

        // Check if siswa has active session
        if ($this->sesiModel->hasSesiAktif($siswa['id'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Siswa memiliki sesi aktif'
            ], 409);
        }

        try {
            // Create session
            $idSesi = $this->sesiModel->createSesi($siswa['id'], $idMateri, $jumlahSoal);

            if (!$idSesi) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal membuat sesi'
                ], 500);
            }

            // Get session data
            $sesi = $this->sesiModel->getSesiWithRelations($idSesi);

            // Generate questions with LCM
            $allSoal = $this->soalModel->getSoalForMobile($idMateri);
            $quizResult = $this->lcm->generateQuizData($allSoal, $jumlahSoal, true);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Sesi berhasil dimulai',
                'data' => [
                    'sesi' => $sesi,
                    'soal' => $quizResult['success'] ? $quizResult['data'] : [],
                    'session_id' => $this->lcm->generateSessionId($siswa['id'], $idMateri),
                    'lcm_info' => $quizResult['metadata'] ?? null
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error in startSessionMobile: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }
}