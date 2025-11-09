<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\MateriKaidahModel;
use App\Models\SesiLatihanModel;
use App\Models\SiswaModel;
use App\Models\RiwayatBelajarModel;
use CodeIgniter\API\ResponseTrait;

class ProgressController extends BaseController
{
    use ResponseTrait;

    protected $materiKaidahModel;
    protected $sesiLatihanModel;
    protected $siswaModel;
    protected $riwayatBelajarModel;

    public function __construct()
    {
        $this->materiKaidahModel = new MateriKaidahModel();
        $this->sesiLatihanModel = new SesiLatihanModel();
        $this->siswaModel = new SiswaModel();
        $this->riwayatBelajarModel = new RiwayatBelajarModel();
    }

    /**
     * Get overall progress for student
     * GET /api/progress
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

        $db = \Config\Database::connect();

        // Get student data
        $siswa = $this->siswaModel->find($userId);
        if (!$siswa) {
            return $this->fail('Siswa tidak ditemukan', 404);
        }

        // Get all kaidah
        $allKaidah = $this->materiKaidahModel->findAll();
        $totalKaidah = count($allKaidah);

        // Get riwayat belajar untuk progress
        $riwayatBelajar = $this->riwayatBelajarModel
            ->where('id_siswa', $userId)
            ->findAll();

        // Get completed sessions untuk statistik quiz
        $completedSessions = $this->sesiLatihanModel
            ->where('id_siswa', $userId)
            ->where('status', 'selesai')
            ->findAll();

        // Calculate statistics
        $totalSesi = count($completedSessions);
        $avgScore = $totalSesi > 0 ? array_sum(array_column($completedSessions, 'skor')) / $totalSesi : 0;
        $totalQuestions = array_sum(array_column($completedSessions, 'total_soal'));
        $correctAnswers = array_sum(array_column($completedSessions, 'soal_benar'));

        // Get kaidah progress (based on riwayat belajar)
        $kaidahProgress = [];
        foreach ($allKaidah as $kaidah) {
            // Get riwayat belajar for this kaidah
            $riwayatItem = array_filter($riwayatBelajar, function($item) use ($kaidah) {
                return $item['id_materi'] == $kaidah['id_materi'];
            });

            // Get sessions for this kaidah
            $kaidahSessions = array_filter($completedSessions, function($session) use ($kaidah) {
                return $session['id_materi'] == $kaidah['id_materi'];
            });

            // Initialize default values
            $status = 'belum_dimulai';
            $completionPercentage = 0;
            $lastAccessed = null;

            // Determine status and completion based on riwayat belajar
            if (!empty($riwayatItem)) {
                $riwayat = reset($riwayatItem); // Get the most recent riwayat
                $status = $riwayat['status'];
                $completionPercentage = (float) $riwayat['persentase_penguasaan'];
                $lastAccessed = $riwayat['waktu_akses_terakhir'];
            }

            // Override with session data if available
            if (!empty($kaidahSessions)) {
                // Calculate completion percentage based on quiz performance
                $bestScore = max(array_column($kaidahSessions, 'skor'));

                // Update status based on best score
                if ($bestScore >= 80) {
                    $status = 'selesai';
                    $completionPercentage = 100;
                } elseif ($bestScore >= 50) {
                    $status = 'sedang_belajar';
                    // Use actual score as completion percentage
                    $completionPercentage = (float) $bestScore;
                } else {
                    $status = 'sedang_belajar';
                    $completionPercentage = (float) $bestScore;
                }

                $lastAccessed = max(array_column($kaidahSessions, 'waktu_selesai'));
            }

            $kaidahProgress[] = [
                'id_materi' => $kaidah['id_materi'],
                'judul_kaidah' => $kaidah['judul_kaidah'],
                'deskripsi' => $kaidah['deskripsi'],
                'status' => $status,
                'completion_percentage' => round($completionPercentage, 2),
                'last_accessed' => $lastAccessed
            ];
        }

        // Get weekly activity (last 7 days)
        $weeklyActivity = $this->getWeeklyActivity($userId);

        // Get achievements/badges
        $achievements = $this->calculateAchievements($userId, $totalSesi, $avgScore, count($kaidahProgress));

        $response = [
            'status' => 'success',
            'message' => 'Progress berhasil diambil',
            'code' => 200,
            'data' => [
                'siswa' => [
                    'id' => $siswa['id'],
                    'nama_lengkap' => $siswa['nama_lengkap'],
                    'kelas' => $siswa['kelas'],
                    'status' => $siswa['status']
                ],
                'overview' => [
                    'total_kaidah' => $totalKaidah,
                    'kaidah_selesai' => count(array_filter($kaidahProgress, fn($k) => $k['status'] == 'selesai')),
                    'kaidah_sedang_belajar' => count(array_filter($kaidahProgress, fn($k) => $k['status'] == 'sedang_belajar')),
                    'kaidah_belum_dimulai' => count(array_filter($kaidahProgress, fn($k) => $k['status'] == 'belum_dimulai')),
                    'total_sesi' => $totalSesi,
                    'rata_rata_skor' => round($avgScore, 2),
                    'total_soal_dijawab' => $totalQuestions,
                    'total_jawaban_benar' => $correctAnswers,
                    'persentase_benar_keseluruhan' => $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 1) : 0,
                    'persentase_kemajuan' => $totalKaidah > 0 ? round((count(array_filter($kaidahProgress, fn($k) => $k['status'] == 'selesai')) / $totalKaidah) * 100, 1) : 0,
                    // Add kaidah_progress to overview for Android compatibility
                    'kaidah_progress' => $kaidahProgress
                ],
                'kaidah_progress' => $kaidahProgress,
                'weekly_activity' => $weeklyActivity,
                'achievements' => $achievements
            ]
        ];

        return $this->respond($response, 200);
    }

    /**
     * Get detailed progress
     * GET /api/progress/detail
     */
    public function detail()
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

        $filters = [
            'kaidah_id' => $this->request->getVar('kaidah_id'),
            'status' => $this->request->getVar('status'),
            'date_from' => $this->request->getVar('date_from'),
            'date_to' => $this->request->getVar('date_to'),
            'limit' => $this->request->getVar('limit') ?? 10,
            'offset' => $this->request->getVar('offset') ?? 0
        ];

        $sessions = $this->getDetailedProgress($userId, $filters);

        $response = [
            'status' => 'success',
            'message' => 'Detail progress berhasil diambil',
            'code' => 200,
            'data' => [
                'filters' => $filters,
                'sessions' => $sessions['data'],
                'pagination' => [
                    'total' => $sessions['total'],
                    'limit' => $filters['limit'],
                    'offset' => $filters['offset'],
                    'has_more' => $sessions['total'] > ($filters['offset'] + $filters['limit'])
                ]
            ]
        ];

        return $this->respond($response, 200);
    }

    /**
     * Get learning history
     * GET /api/progress/history
     */
    public function history()
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

        $period = $this->request->getVar('period') ?? 'month'; // week, month, year
        $limit = $this->request->getVar('limit') ?? 30;

        $history = $this->getLearningHistory($userId, $period, $limit);

        $response = [
            'status' => 'success',
            'message' => 'Riwayat pembelajaran berhasil diambil',
            'code' => 200,
            'data' => [
                'period' => $period,
                'history' => $history
            ]
        ];

        return $this->respond($response, 200);
    }

    /**
     * Get progress statistics
     * GET /api/progress/statistics
     */
    public function statistics()
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

        $db = \Config\Database::connect();

        // Get session statistics
        $sessionStats = $db->table('sesi_pembelajaran')
            ->select('
                COUNT(*) as total_sessions,
                SUM(CASE WHEN status = "selesai" THEN 1 ELSE 0 END) as completed_sessions,
                SUM(CASE WHEN status = "dibatalkan" THEN 1 ELSE 0 END) as cancelled_sessions,
                AVG(CASE WHEN status = "selesai" THEN skor END) as avg_score,
                MAX(CASE WHEN status = "selesai" THEN skor END) as best_score,
                MIN(CASE WHEN status = "selesai" THEN skor END) as worst_score,
                SUM(CASE WHEN status = "selesai" THEN total_soal END) as total_questions,
                SUM(CASE WHEN status = "selesai" THEN soal_benar END) as total_correct,
                AVG(CASE WHEN status = "selesai" THEN durasi_detik END) as avg_duration
            ')
            ->where('id_siswa', $userId)
            ->get()
            ->getRowArray();

        // Get kaidah breakdown
        $kaidahStats = $db->table('sesi_pembelajaran sp')
            ->select('
                mk.id_materi,
                mk.judul_kaidah,
                COUNT(*) as total_sessions,
                AVG(sp.skor) as avg_score,
                MAX(sp.skor) as best_score
            ')
            ->join('materi_kaidah mk', 'mk.id_materi = sp.id_materi')
            ->where('sp.id_siswa', $userId)
            ->where('sp.status', 'selesai')
            ->groupBy('mk.id_materi, mk.judul_kaidah')
            ->get()
            ->getResultArray();

        // Get monthly performance (last 6 months)
        $monthlyPerformance = $this->getMonthlyPerformance($userId);

        // Get learning streak
        $streak = $this->calculateLearningStreak($userId);

        $response = [
            'status' => 'success',
            'message' => 'Statistik progress berhasil diambil',
            'code' => 200,
            'data' => [
                'session_statistics' => [
                    'total_sessions' => (int)$sessionStats['total_sessions'],
                    'completed_sessions' => (int)$sessionStats['completed_sessions'],
                    'cancelled_sessions' => (int)$sessionStats['cancelled_sessions'],
                    'completion_rate' => $sessionStats['total_sessions'] > 0 ?
                        round(($sessionStats['completed_sessions'] / $sessionStats['total_sessions']) * 100, 1) : 0,
                    'average_score' => round($sessionStats['avg_score'] ?: 0, 2),
                    'best_score' => round($sessionStats['best_score'] ?: 0, 2),
                    'worst_score' => round($sessionStats['worst_score'] ?: 0, 2),
                    'total_questions_answered' => (int)($sessionStats['total_questions'] ?: 0),
                    'total_correct_answers' => (int)($sessionStats['total_correct'] ?: 0),
                    'overall_accuracy' => $sessionStats['total_questions'] > 0 ?
                        round(($sessionStats['total_correct'] / $sessionStats['total_questions']) * 100, 1) : 0,
                    'average_duration_minutes' => round($sessionStats['avg_duration'] / 60 ?: 0, 1)
                ],
                'kaidah_breakdown' => array_map(function($stat) {
                    return [
                        'id_materi' => $stat['id_materi'],
                        'judul_kaidah' => $stat['judul_kaidah'],
                        'total_sessions' => (int)$stat['total_sessions'],
                        'average_score' => round($stat['avg_score'], 2),
                        'best_score' => round($stat['best_score'], 2)
                    ];
                }, $kaidahStats),
                'monthly_performance' => $monthlyPerformance,
                'learning_streak' => $streak
            ]
        ];

        return $this->respond($response, 200);
    }

    /**
     * Get progress chart data
     * GET /api/progress/chart
     */
    public function chart()
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

        $chartType = $this->request->getVar('type') ?? 'line'; // line, bar, radar
        $period = $this->request->getVar('period') ?? 'month'; // week, month, year

        $chartData = $this->getChartData($userId, $chartType, $period);

        $response = [
            'status' => 'success',
            'message' => 'Data chart progress berhasil diambil',
            'code' => 200,
            'data' => [
                'chart_type' => $chartType,
                'period' => $period,
                'chart_data' => $chartData
            ]
        ];

        return $this->respond($response, 200);
    }

    /**
     * Get weekly activity data
     */
    private function getWeeklyActivity($userId)
    {
        $db = \Config\Database::connect();

        // Get last 7 days
        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $days[$date] = 0;
        }

        // Get sessions in last 7 days
        $sessions = $db->table('sesi_pembelajaran')
            ->select('DATE(waktu_mulai) as date, COUNT(*) as sessions')
            ->where('id_siswa', $userId)
            ->where('waktu_mulai >=', date('Y-m-d', strtotime('-6 days')))
            ->groupBy('DATE(waktu_mulai)')
            ->get()
            ->getResultArray();

        foreach ($sessions as $session) {
            $days[$session['date']] = (int)$session['sessions'];
        }

        return array_map(function($date, $count) {
            return [
                'date' => $date,
                'day_name' => date('D', strtotime($date)),
                'sessions' => $count
            ];
        }, array_keys($days), $days);
    }

    /**
     * Calculate achievements
     */
    private function calculateAchievements($userId, $totalSessions, $avgScore, $kaidahProgressCount)
    {
        $achievements = [];

        // First session achievement
        if ($totalSessions >= 1) {
            $achievements[] = [
                'id' => 'first_session',
                'title' => 'Pemula',
                'description' => 'Menyelesaikan sesi pembelajaran pertama',
                'icon' => '🎯',
                'earned_at' => $this->getFirstSessionDate($userId)
            ];
        }

        // Score achievements
        if ($avgScore >= 80) {
            $achievements[] = [
                'id' => 'high_scorer',
                'title' => 'Pecandu Skor Tinggi',
                'description' => 'Rata-rata skor 80+',
                'icon' => '⭐',
                'earned_at' => null
            ];
        }

        if ($avgScore >= 90) {
            $achievements[] = [
                'id' => 'perfect_student',
                'title' => 'Siswa Sempurna',
                'description' => 'Rata-rata skor 90+',
                'icon' => '🏆',
                'earned_at' => null
            ];
        }

        // Session count achievements
        if ($totalSessions >= 10) {
            $achievements[] = [
                'id' => 'regular_learner',
                'title' => 'Pembelajar Rutin',
                'description' => 'Menyelesaikan 10 sesi',
                'icon' => '📚',
                'earned_at' => null
            ];
        }

        if ($totalSessions >= 25) {
            $achievements[] = [
                'id' => 'dedicated_student',
                'title' => 'Siswa Berdedikasi',
                'description' => 'Menyelesaikan 25 sesi',
                'icon' => '🎓',
                'earned_at' => null
            ];
        }

        // Kaidah achievements
        if ($kaidahProgressCount >= 5) {
            $achievements[] = [
                'id' => 'kaidah_explorer',
                'title' => 'Eksplorator Kaidah',
                'description' => 'Mempelajari 5 kaidah berbeda',
                'icon' => '🔍',
                'earned_at' => null
            ];
        }

        return $achievements;
    }

    /**
     * Get detailed progress with filters
     */
    private function getDetailedProgress($userId, $filters)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('sesi_pembelajaran sp')
            ->select('
                sp.*,
                mk.judul_kaidah,
                s.nama_lengkap as nama_siswa
            ')
            ->join('materi_kaidah mk', 'mk.id_materi = sp.id_materi')
            ->join('siswa s', 's.id = sp.id_siswa')
            ->where('sp.id_siswa', $userId);

        // Apply filters
        if (!empty($filters['kaidah_id'])) {
            $builder->where('sp.id_materi', $filters['kaidah_id']);
        }

        if (!empty($filters['status'])) {
            $builder->where('sp.status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $builder->where('sp.waktu_mulai >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $builder->where('sp.waktu_mulai <=', $filters['date_to'] . ' 23:59:59');
        }

        $builder->orderBy('sp.waktu_mulai', 'DESC');

        // Get total count
        $total = $builder->countAllResults(false);

        // Get paginated results
        $data = $builder->limit($filters['limit'], $filters['offset'])
                       ->get()
                       ->getResultArray();

        return [
            'data' => array_map(function($session) {
                return [
                    'sesi_id' => $session['id_sesi'],
                    'kaidah' => [
                        'id_materi' => $session['id_materi'],
                        'judul_kaidah' => $session['judul_kaidah']
                    ],
                    'skor' => round($session['skor'], 2),
                    'total_soal' => $session['total_soal'],
                    'soal_benar' => $session['soal_benar'],
                    'persentase_benar' => round(($session['soal_benar'] / $session['total_soal']) * 100, 1),
                    'durasi_menit' => round($session['durasi_detik'] / 60, 1),
                    'status' => $session['status'],
                    'waktu_mulai' => $session['waktu_mulai'],
                    'waktu_selesai' => $session['waktu_selesai']
                ];
            }, $data),
            'total' => $total
        ];
    }

    /**
     * Get learning history
     */
    private function getLearningHistory($userId, $period, $limit)
    {
        $db = \Config\Database::connect();

        $dateFormat = match($period) {
            'week' => '%Y-%u',
            'month' => '%Y-%m',
            'year' => '%Y',
            default => '%Y-%m'
        };

        $history = $db->table('sesi_pembelajaran')
            ->select("
                DATE_FORMAT(waktu_mulai, '$dateFormat') as period,
                COUNT(*) as total_sessions,
                SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as completed_sessions,
                AVG(CASE WHEN status = 'selesai' THEN skor END) as avg_score,
                SUM(CASE WHEN status = 'selesai' THEN total_soal END) as total_questions,
                SUM(CASE WHEN status = 'selesai' THEN soal_benar END) as total_correct
            ")
            ->where('id_siswa', $userId)
            ->groupBy('period')
            ->orderBy('period', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        return array_map(function($item) {
            return [
                'period' => $item['period'],
                'total_sessions' => (int)$item['total_sessions'],
                'completed_sessions' => (int)$item['completed_sessions'],
                'completion_rate' => round(($item['completed_sessions'] / $item['total_sessions']) * 100, 1),
                'average_score' => round($item['avg_score'] ?: 0, 2),
                'total_questions' => (int)($item['total_questions'] ?: 0),
                'total_correct' => (int)($item['total_correct'] ?: 0),
                'accuracy' => $item['total_questions'] > 0 ?
                    round(($item['total_correct'] / $item['total_questions']) * 100, 1) : 0
            ];
        }, $history);
    }

    /**
     * Get monthly performance
     */
    private function getMonthlyPerformance($userId)
    {
        $db = \Config\Database::connect();

        $performance = $db->table('sesi_pembelajaran')
            ->select("
                DATE_FORMAT(waktu_mulai, '%Y-%m') as month,
                COUNT(*) as sessions,
                AVG(CASE WHEN status = 'selesai' THEN skor END) as avg_score,
                MAX(CASE WHEN status = 'selesai' THEN skor END) as best_score
            ")
            ->where('id_siswa', $userId)
            ->where('waktu_mulai >=', date('Y-m-01', strtotime('-5 months')))
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->get()
            ->getResultArray();

        return array_map(function($item) {
            return [
                'month' => $item['month'],
                'month_name' => date('F Y', strtotime($item['month'] . '-01')),
                'sessions' => (int)$item['sessions'],
                'average_score' => round($item['avg_score'] ?: 0, 2),
                'best_score' => round($item['best_score'] ?: 0, 2)
            ];
        }, $performance);
    }

    /**
     * Calculate learning streak
     */
    private function calculateLearningStreak($userId)
    {
        $db = \Config\Database::connect();

        // Get dates of completed sessions in last 30 days
        $sessions = $db->table('sesi_pembelajaran')
            ->select('DISTINCT DATE(waktu_selesai) as date')
            ->where('id_siswa', $userId)
            ->where('status', 'selesai')
            ->where('waktu_selesai >=', date('Y-m-d', strtotime('-29 days')))
            ->orderBy('date', 'DESC')
            ->get()
            ->getResultArray();

        if (empty($sessions)) {
            return ['current_streak' => 0, 'longest_streak' => 0];
        }

        $currentStreak = 0;
        $longestStreak = 0;
        $tempStreak = 0;
        $expectedDate = date('Y-m-d');

        foreach ($sessions as $session) {
            if ($session['date'] == $expectedDate) {
                $tempStreak++;
                if ($currentStreak == 0) {
                    $currentStreak = $tempStreak;
                }
                $expectedDate = date('Y-m-d', strtotime($expectedDate . ' -1 day'));
            } elseif ($session['date'] < $expectedDate) {
                $currentStreak = 0;
                break;
            }
        }

        // Calculate longest streak
        $expectedDate = date('Y-m-d', strtotime('-29 days'));
        $tempStreak = 0;

        foreach ($sessions as $session) {
            if ($session['date'] == $expectedDate) {
                $tempStreak++;
                $longestStreak = max($longestStreak, $tempStreak);
                $expectedDate = date('Y-m-d', strtotime($expectedDate . ' -1 day'));
            } else {
                $tempStreak = 0;
                $expectedDate = date('Y-m-d', strtotime($session['date'] . ' -1 day'));
            }
        }

        return [
            'current_streak' => $currentStreak,
            'longest_streak' => $longestStreak
        ];
    }

    /**
     * Get chart data
     */
    private function getChartData($userId, $chartType, $period)
    {
        switch ($chartType) {
            case 'line':
            case 'bar':
                return $this->getLearningHistory($userId, $period, 30);

            case 'radar':
                return $this->getKaidahRadarData($userId);

            default:
                return [];
        }
    }

    /**
     * Get kaidah radar data
     */
    private function getKaidahRadarData($userId)
    {
        $db = \Config\Database::connect();

        $data = $db->table('sesi_pembelajaran sp')
            ->select('
                mk.id_materi,
                mk.judul_kaidah,
                AVG(sp.skor) as avg_score,
                COUNT(*) as sessions
            ')
            ->join('materi_kaidah mk', 'mk.id_materi = sp.id_materi')
            ->where('sp.id_siswa', $userId)
            ->where('sp.status', 'selesai')
            ->groupBy('mk.id_materi, mk.judul_kaidah')
            ->get()
            ->getResultArray();

        return array_map(function($item) {
            return [
                'kaidah' => $item['judul_kaidah'],
                'score' => round($item['avg_score'], 2),
                'sessions' => (int)$item['sessions']
            ];
        }, $data);
    }

    /**
     * Get first session date
     */
    private function getFirstSessionDate($userId)
    {
        $db = \Config\Database::connect();

        $session = $db->table('sesi_pembelajaran')
            ->where('id_siswa', $userId)
            ->where('status', 'selesai')
            ->orderBy('waktu_selesai', 'ASC')
            ->limit(1)
            ->get()
            ->getRowArray();

        return $session ? $session['waktu_selesai'] : null;
    }

    /**
     * Update progress for specific material
     * POST /api/progress/materi/{id}/complete
     */
    public function completeMateri($id)
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

        $db = \Config\Database::connect();

        // Get student data
        $siswa = $this->siswaModel->find($userId);
        if (!$siswa) {
            return $this->fail('Siswa tidak ditemukan', 404);
        }

        // Get materi data
        $materi = $this->materiKaidahModel->find($id);
        if (!$materi) {
            return $this->fail('Materi tidak ditemukan', 404);
        }

        try {
            // Check if riwayat already exists
            $existingRiwayat = $db->table('riwayat_belajar')
                ->where('id_siswa', $userId)
                ->where('id_materi', $id)
                ->orderBy('waktu_diubah', 'DESC')
                ->get()
                ->getRowArray();

            if ($existingRiwayat) {
                // Update existing record
                $db->table('riwayat_belajar')
                    ->where('id_riwayat', $existingRiwayat['id_riwayat'])
                    ->update([
                        'status' => 'selesai',
                        'persentase_penguasaan' => 100.0,
                        'waktu_diubah' => date('Y-m-d H:i:s')
                    ]);
            } else {
                // Create new record
                $db->table('riwayat_belajar')->insert([
                    'id_siswa' => $userId,
                    'id_materi' => $id,
                    'status' => 'selesai',
                    'persentase_penguasaan' => 100.0,
                    'waktu_diubah' => date('Y-m-d H:i:s'),
                    'waktu_dibuat' => date('Y-m-d H:i:s'),
                    'waktu_akses_terakhir' => date('Y-m-d H:i:s')
                ]);
            }

            // Also update materi_kaidah table progress if it has those fields
            $materiFields = $db->getFieldData('materi_kaidah');
            if (in_array('progress_percentage', array_column($materiFields, 'name'))) {
                $db->table('materi_kaidah')
                    ->where('id_materi', $id)
                    ->update([
                        'progress_percentage' => 100,
                        'completed' => 1
                    ]);
            }

            $response = [
                'status' => 'success',
                'message' => 'Progress materi berhasil diperbarui',
                'code' => 200,
                'data' => [
                    'materi_id' => (int)$id,
                    'materi_judul' => $materi['judul_kaidah'],
                    'status' => 'selesai',
                    'completion_percentage' => 100,
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            ];

            return $this->respond($response, 200);

        } catch (\Exception $e) {
            log_message('error', 'Error updating materi progress: ' . $e->getMessage());

            return $this->fail([
                'status' => 'error',
                'message' => 'Gagal memperbarui progress materi: ' . $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }

    /**
     * Get material progress for specific student and materi
     * GET /api/progress/materi/{id}
     */
    public function getMateriProgress($id)
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

        // Get materi data
        $materi = $this->materiKaidahModel->find($id);
        if (!$materi) {
            return $this->fail('Materi tidak ditemukan', 404);
        }

        $db = \Config\Database::connect();

        // Get progress from riwayat_belajar
        $riwayat = $db->table('riwayat_belajar')
            ->where('id_siswa', $userId)
            ->where('id_materi', $id)
            ->orderBy('waktu_diubah', 'DESC')
            ->get()
            ->getRowArray();

        $status = 'belum_dimulai';
        $completionPercentage = 0;
        $lastAccessed = null;

        if ($riwayat) {
            $status = $riwayat['status'];
            $completionPercentage = (float) $riwayat['persentase_penguasaan'];
            $lastAccessed = $riwayat['waktu_diubah'];
        }

        $response = [
            'status' => 'success',
            'message' => 'Progress materi berhasil diambil',
            'code' => 200,
            'data' => [
                'materi' => [
                    'id_materi' => (int)$materi['id_materi'],
                    'judul_kaidah' => $materi['judul_kaidah'],
                    'deskripsi' => $materi['deskripsi']
                ],
                'progress' => [
                    'status' => $status,
                    'completion_percentage' => $completionPercentage,
                    'last_accessed' => $lastAccessed
                ]
            ]
        ];

        return $this->respond($response, 200);
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
                if (time() - (int)$timestamp < 86400) {
                    return (int)$userId;
                }
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }
}