<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\KaidahModel;
use App\Models\SoalModel;
use App\Models\SesiModel;
use CodeIgniter\API\ResponseTrait;

class KaidahController extends BaseController
{
    use ResponseTrait;

    protected $kaidahModel;
    protected $soalModel;
    protected $sesiModel;

    public function __construct()
    {
        $this->kaidahModel = new KaidahModel();
        $this->soalModel = new SoalModel();
        $this->sesiModel = new SesiModel();
    }

    /**
     * Get all kaidah for mobile
     * GET /api/kaidah
     */
    public function index()
    {
        $authHeader = $this->request->getHeader('Authorization');
        if (!$authHeader) {
            return $this->fail('Token diperlukan', 401);
        }

        $token = str_replace('Bearer ', '', $authHeader->getValue());
        $userId = $this->extractUserIdFromToken($token);

        if (!$userId) {
            return $this->fail('Token tidak valid', 401);
        }

        // Get query parameters
        $search = $this->request->getVar('search');
        $difficulty = $this->request->getVar('difficulty');
        $page = $this->request->getVar('page') ?? 1;
        $limit = $this->request->getVar('limit') ?? 20;

        // Get kaidah with progress info
        $kaidahList = $this->kaidahModel->getKaidahWithProgress($userId, $search, $difficulty, $limit, $page);

        $response = [
            'status' => 'success',
            'message' => 'Daftar kaidah berhasil diambil',
            'data' => [
                'kaidah' => $kaidahList['data'],
                'pagination' => [
                    'current_page' => $kaidahList['current_page'],
                    'per_page' => $kaidahList['per_page'],
                    'total' => $kaidahList['total'],
                    'total_pages' => ceil($kaidahList['total'] / $kaidahList['per_page'])
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
        $authHeader = $this->request->getHeader('Authorization');
        if (!$authHeader) {
            return $this->fail('Token diperlukan', 401);
        }

        $token = str_replace('Bearer ', '', $authHeader->getValue());
        $userId = $this->extractUserIdFromToken($token);

        if (!$userId) {
            return $this->fail('Token tidak valid', 401);
        }

        $kaidah = $this->kaidahModel->find($id);
        if (!$kaidah) {
            return $this->fail('Kaidah tidak ditemukan', 404);
        }

        // Get total soal for this kaidah
        $totalSoal = $this->soalModel->where('id_materi', $id)->countAllResults();

        // Get user progress for this kaidah
        $progress = $this->getUserKaidahProgress($userId, $id);

        $response = [
            'status' => 'success',
            'message' => 'Detail kaidah berhasil diambil',
            'data' => [
                'kaidah' => [
                    'id_materi' => $kaidah['id_materi'],
                    'judul_kaidah' => $kaidah['judul_kaidah'],
                    'deskripsi' => $kaidah['deskripsi'],
                    'penjelasan' => $kaidah['penjelasan'],
                    'contoh' => $kaidah['contoh'],
                                        'urutan' => $kaidah['urutan'],
                    'total_soal' => $totalSoal,
                    'waktu_dibuat' => $kaidah['waktu_dibuat']
                ],
                'user_progress' => $progress
            ]
        ];

        return $this->respond($response, 200);
    }

    /**
     * Get progress for specific kaidah
     * GET /api/kaidah/:id/progress
     */
    public function progress($id)
    {
        $authHeader = $this->request->getHeader('Authorization');
        if (!$authHeader) {
            return $this->fail('Token diperlukan', 401);
        }

        $token = str_replace('Bearer ', '', $authHeader->getValue());
        $userId = $this->extractUserIdFromToken($token);

        if (!$userId) {
            return $this->fail('Token tidak valid', 401);
        }

        // Check if kaidah exists
        $kaidah = $this->kaidahModel->find($id);
        if (!$kaidah) {
            return $this->fail('Kaidah tidak ditemukan', 404);
        }

        // Get detailed progress
        $progress = $this->getUserKaidahProgress($userId, $id, true);

        $response = [
            'status' => 'success',
            'message' => 'Progress kaidah berhasil diambil',
            'data' => [
                'kaidah_id' => $id,
                'judul_kaidah' => $kaidah['judul_kaidah'],
                'progress' => $progress
            ]
        ];

        return $this->respond($response, 200);
    }

    /**
     * Start learning kaidah
     * POST /api/kaidah/:id/start
     */
    public function start($id)
    {
        $authHeader = $this->request->getHeader('Authorization');
        if (!$authHeader) {
            return $this->fail('Token diperlukan', 401);
        }

        $token = str_replace('Bearer ', '', $authHeader->getValue());
        $userId = $this->extractUserIdFromToken($token);

        if (!$userId) {
            return $this->fail('Token tidak valid', 401);
        }

        // Check if kaidah exists
        $kaidah = $this->kaidahModel->find($id);
        if (!$kaidah) {
            return $this->fail('Kaidah tidak ditemukan', 404);
        }

        // Check if user has active session
        if ($this->sesiModel->hasSesiAktif($userId)) {
            return $this->fail('Anda masih memiliki sesi pembelajaran yang aktif', 400);
        }

        // Get number of questions (default 20)
        $jumlahSoal = $this->request->getVar('jumlah_soal') ?? 20;

        // Check if there are enough questions
        $totalSoal = $this->soalModel->where('id_materi', $id)->countAllResults();
        if ($totalSoal == 0) {
            return $this->fail('Belum ada soal untuk kaidah ini', 400);
        }

        if ($jumlahSoal > $totalSoal) {
            $jumlahSoal = $totalSoal;
        }

        // Create new session
        $idSesi = $this->sesiModel->createSesi($userId, $id, $jumlahSoal);
        if (!$idSesi) {
            return $this->fail('Gagal memulai sesi pembelajaran', 500);
        }

        $response = [
            'status' => 'success',
            'message' => 'Sesi pembelajaran berhasil dimulai',
            'data' => [
                'sesi_id' => $idSesi,
                'kaidah' => [
                    'id_materi' => $kaidah['id_materi'],
                    'judul_kaidah' => $kaidah['judul_kaidah'],
                    'tingkat_kesulitan' => $kaidah['tingkat_kesulitan']
                ],
                'jumlah_soal' => $jumlahSoal,
                'waktu_mulai' => date('Y-m-d H:i:s')
            ]
        ];

        return $this->respond($response, 201);
    }

    /**
     * Search kaidah
     * GET /api/kaidah/search
     */
    public function search()
    {
        $authHeader = $this->request->getHeader('Authorization');
        if (!$authHeader) {
            return $this->fail('Token diperlukan', 401);
        }

        $token = str_replace('Bearer ', '', $authHeader->getValue());
        $userId = $this->extractUserIdFromToken($token);

        if (!$userId) {
            return $this->fail('Token tidak valid', 401);
        }

        $keyword = $this->request->getVar('q');
        if (!$keyword) {
            return $this->fail('Keyword pencarian diperlukan', 400);
        }

        $page = $this->request->getVar('page') ?? 1;
        $limit = $this->request->getVar('limit') ?? 10;

        $results = $this->kaidahModel->searchKaidah($keyword, $userId, $limit, $page);

        $response = [
            'status' => 'success',
            'message' => 'Hasil pencarian kaidah',
            'data' => [
                'keyword' => $keyword,
                'results' => $results['data'],
                'pagination' => [
                    'current_page' => $results['current_page'],
                    'per_page' => $results['per_page'],
                    'total' => $results['total'],
                    'total_pages' => ceil($results['total'] / $results['per_page'])
                ]
            ]
        ];

        return $this->respond($response, 200);
    }

    /**
     * Get available filters
     * GET /api/kaidah/filters
     */
    public function filters()
    {
        $authHeader = $this->request->getHeader('Authorization');
        if (!$authHeader) {
            return $this->fail('Token diperlukan', 401);
        }

        $token = str_replace('Bearer ', '', $authHeader->getValue());
        $userId = $this->extractUserIdFromToken($token);

        if (!$userId) {
            return $this->fail('Token tidak valid', 401);
        }

        // Get difficulty levels
        $difficultyLevels = [
            ['value' => 'mudah', 'label' => 'Mudah', 'count' => $this->kaidahModel->countByDifficulty('mudah')],
            ['value' => 'sedang', 'label' => 'Sedang', 'count' => $this->kaidahModel->countByDifficulty('sedang')],
            ['value' => 'sulit', 'label' => 'Sulit', 'count' => $this->kaidahModel->countByDifficulty('sulit')]
        ];

        // Get progress status options
        $progressOptions = [
            ['value' => 'belum_dimulai', 'label' => 'Belum Dimulai'],
            ['value' => 'sedang_belajar', 'label' => 'Sedang Belajar'],
            ['value' => 'selesai', 'label' => 'Selesai']
        ];

        $response = [
            'status' => 'success',
            'message' => 'Filter options berhasil diambil',
            'data' => [
                'difficulty_levels' => $difficultyLevels,
                'progress_options' => $progressOptions,
                'sort_options' => [
                    ['value' => 'urutan', 'label' => 'Urutan'],
                    ['value' => 'judul', 'label' => 'Judul A-Z'],
                    ['value' => 'created', 'label' => 'Terbaru'],
                    ['value' => 'difficulty', 'label' => 'Tingkat Kesulitan']
                ]
            ]
        ];

        return $this->respond($response, 200);
    }

    /**
     * Get user progress for specific kaidah
     */
    private function getUserKaidahProgress($userId, $kaidahId, $detailed = false)
    {
        $db = \Config\Database::connect();

        // Get completed sessions for this kaidah
        $completedSessions = $db->table('sesi_pembelajaran')
            ->where('id_siswa', $userId)
            ->where('id_materi', $kaidahId)
            ->where('status', 'selesai')
            ->orderBy('waktu_selesai', 'DESC')
            ->get()
            ->getResultArray();

        if (empty($completedSessions)) {
            return [
                'status' => 'belum_dimulai',
                'total_sessions' => 0,
                'average_score' => 0,
                'best_score' => 0,
                'last_attempt' => null,
                'completed_questions' => 0
            ];
        }

        $totalSessions = count($completedSessions);
        $averageScore = array_sum(array_column($completedSessions, 'skor')) / $totalSessions;
        $bestScore = max(array_column($completedSessions, 'skor'));
        $lastAttempt = $completedSessions[0]['waktu_selesai'];
        $totalQuestions = array_sum(array_column($completedSessions, 'soal_benar'));

        // Determine status based on average score
        $status = 'sedang_belajar';
        if ($averageScore >= 80) {
            $status = 'selesai';
        }

        $progress = [
            'status' => $status,
            'total_sessions' => $totalSessions,
            'average_score' => round($averageScore, 2),
            'best_score' => round($bestScore, 2),
            'last_attempt' => $lastAttempt,
            'completed_questions' => $totalQuestions
        ];

        if ($detailed) {
            // Get session history
            $progress['session_history'] = array_map(function($session) {
                return [
                    'session_id' => $session['id_sesi'],
                    'score' => round($session['skor'], 2),
                    'correct_answers' => $session['soal_benar'],
                    'total_questions' => $session['total_soal'],
                    'duration_minutes' => round($session['durasi_detik'] / 60, 1),
                    'completed_at' => $session['waktu_selesai']
                ];
            }, $completedSessions);
        }

        return $progress;
    }

    /**
     * Extract user ID dari simple token
     */
    private function extractUserIdFromToken($token)
    {
        try {
            $decoded = base64_decode($token);
            if ($decoded && strpos($decoded, ':') !== false) {
                list($userId, $timestamp) = explode(':', $decoded);

                // Check if token is not too old (24 hours)
                if (time() - $timestamp < 86400) {
                    return (int)$userId;
                }
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }
}