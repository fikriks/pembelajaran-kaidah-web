<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Libraries\QuizEngine;
use App\Models\RiwayatBelajarModel;
use App\Models\SesiLatihanModel;

class ProgressController extends BaseController
{
    protected $quizEngine;
    protected $riwayatBelajarModel;
    protected $sesiLatihanModel;

    public function __construct()
    {
        $this->quizEngine = new QuizEngine();
        $this->riwayatBelajarModel = new RiwayatBelajarModel();
        $this->sesiLatihanModel = new SesiLatihanModel();
    }

    /**
     * Get overall progress belajar siswa
     * GET /api/progress
     */
    public function index()
    {
        $userId = $this->getUserIdFromToken();

        if (!$userId) {
            return $this->respondWithError('Token tidak valid', 401);
        }

        $stats = $this->riwayatBelajarModel->getStats($userId);

        if (!$stats) {
            $stats = [
                'total_kaidah' => 0,
                'kaidah_selesai' => 0,
                'kaidah_sedang_belajar' => 0,
                'kaidah_belum_dimulai' => 0,
                'rata_rata_skor' => 0,
                'total_sesi_selesai' => 0
            ];
        }

        // Calculate percentage
        $persentaseKelulusan = $stats['total_kaidah'] > 0
            ? round(($stats['kaidah_selesai'] / $stats['total_kaidah']) * 100, 2)
            : 0;

        $result = [
            'overview' => [
                'total_kaidah' => $stats['total_kaidah'],
                'kaidah_selesai' => $stats['kaidah_selesai'],
                'kaidah_sedang_belajar' => $stats['kaidah_sedang_belajar'],
                'kaidah_belum_dimulai' => $stats['kaidah_belum_dimulai'],
                'persentase_kelulusan' => $persentaseKelulusan,
                'rata_rata_skor' => floatval($stats['rata_rata_skor'] ?? 0),
                'total_sesi_selesai' => $stats['total_sesi_selesai']
            ]
        ];

        // Get recent activity
        $recentActivity = $this->getRecentActivity($userId);
        $result['recent_activity'] = $recentActivity;

        // Get learning streak
        $streak = $this->calculateLearningStreak($userId);
        $result['learning_streak'] = $streak;

        return $this->respondWithSuccess($result, 'Progress berhasil diambil');
    }

    /**
     * Get detail progress per kaidah
     * GET /api/progress/detail
     */
    public function detail()
    {
        $userId = $this->getUserIdFromToken();

        if (!$userId) {
            return $this->respondWithError('Token tidak valid', 401);
        }

        $filters = $this->request->getGet();
        $progressList = $this->riwayatBelajarModel->getDetailedProgress($userId, $filters);

        return $this->respondWithSuccess($progressList, 'Detail progress berhasil diambil');
    }

    /**
     * Get learning history
     * GET /api/progress/history
     */
    public function history()
    {
        $userId = $this->getUserIdFromToken();

        if (!$userId) {
            return $this->respondWithError('Token tidak valid', 401);
        }

        $page = $this->request->getGet('page', 1);
        $limit = $this->request->getGet('limit', 20);
        $offset = ($page - 1) * $limit;

        $history = $this->sesiLatihanModel->getLearningHistory($userId, $limit, $offset);
        $total = $this->sesiLatihanModel->countLearningHistory($userId);

        $result = [
            'data' => $history,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $limit,
                'total' => $total,
                'total_pages' => ceil($total / $limit)
            ]
        ];

        return $this->respondWithSuccess($result, 'History pembelajaran berhasil diambil');
    }

    /**
     * Get statistik pembelajaran
     * GET /api/progress/statistics
     */
    public function statistics()
    {
        $userId = $this->getUserIdFromToken();

        if (!$userId) {
            return $this->respondWithError('Token tidak valid', 401);
        }

        // Get learning statistics
        $stats = $this->quizEngine->hitungProgress($userId);

        // Get monthly progress (last 6 months)
        $monthlyProgress = $this->getMonthlyProgress($userId);

        // Get difficulty distribution
        $difficultyStats = $this->getDifficultyStats($userId);

        // Get best performing kaidah
        $bestKaidah = $this->getBestPerformingKaidah($userId);

        // Get struggling kaidah
        $strugglingKaidah = $this->getStrugglingKaidah($userId);

        $result = [
            'overall_stats' => $stats,
            'monthly_progress' => $monthlyProgress,
            'difficulty_distribution' => $difficultyStats,
            'best_performing_kaidah' => $bestKaidah,
            'struggling_kaidah' => $strugglingKaidah
        ];

        return $this->respondWithSuccess($result, 'Statistik pembelajaran berhasil diambil');
    }

    /**
     * Get progress chart data
     * GET /api/progress/chart
     */
    public function chart()
    {
        $userId = $this->getUserIdFromToken();

        if (!$userId) {
            return $this->respondWithError('Token tidak valid', 401);
        }

        $type = $this->request->getGet('type', 'weekly'); // weekly, monthly, yearly

        switch ($type) {
            case 'weekly':
                $chartData = $this->getWeeklyChart($userId);
                break;
            case 'monthly':
                $chartData = $this->getMonthlyChart($userId);
                break;
            case 'yearly':
                $chartData = $this->getYearlyChart($userId);
                break;
            default:
                return $this->respondWithError('Tipe chart tidak valid', 400);
        }

        return $this->respondWithSuccess($chartData, 'Data chart berhasil diambil');
    }

    /**
     * Get recent activity for user
     */
    private function getRecentActivity($userId, $limit = 5)
    {
        return $this->sesiLatihanModel
            ->select('sesi_latihan.*, materi_kaidah.judul_kaidah')
            ->join('materi_kaidah', 'materi_kaidah.id_materi = sesi_latihan.id_materi')
            ->where('sesi_latihan.id_siswa', $userId)
            ->where('sesi_latihan.status', 'selesai')
            ->orderBy('sesi_latihan.waktu_selesai', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Calculate learning streak
     */
    private function calculateLearningStreak($userId)
    {
        // Get last 30 days of activity
        $activities = $this->sesiLatihanModel
            ->select('DATE(waktu_selesai) as activity_date')
            ->where('id_siswa', $userId)
            ->where('status', 'selesai')
            ->where('waktu_selesai >=', date('Y-m-d', strtotime('-30 days')))
            ->groupBy('DATE(waktu_selesai)')
            ->orderBy('activity_date', 'DESC')
            ->findAll();

        if (empty($activities)) {
            return [
                'current_streak' => 0,
                'longest_streak' => 0,
                'last_activity_date' => null
            ];
        }

        $currentStreak = 0;
        $longestStreak = 0;
        $tempStreak = 0;
        $lastDate = null;

        foreach ($activities as $activity) {
            $activityDate = $activity['activity_date'];

            if ($lastDate === null) {
                // First activity
                $currentStreak = 1;
                $tempStreak = 1;
            } else {
                $dateDiff = (strtotime($lastDate) - strtotime($activityDate)) / (60 * 60 * 24);

                if ($dateDiff == 1) {
                    // Consecutive day
                    $tempStreak++;
                } else {
                    // Break in streak
                    $longestStreak = max($longestStreak, $tempStreak);
                    $tempStreak = 1;
                }
            }

            $lastDate = $activityDate;
        }

        $longestStreak = max($longestStreak, $tempStreak);

        // Check if current streak is still active (activity today or yesterday)
        $lastActivityDate = $activities[0]['activity_date'];
        $daysSinceLastActivity = (strtotime(date('Y-m-d')) - strtotime($lastActivityDate)) / (60 * 60 * 24);

        if ($daysSinceLastActivity > 1) {
            $currentStreak = 0;
        } else {
            $currentStreak = $tempStreak;
        }

        return [
            'current_streak' => $currentStreak,
            'longest_streak' => $longestStreak,
            'last_activity_date' => $lastActivityDate
        ];
    }

    /**
     * Get monthly progress data
     */
    private function getMonthlyProgress($userId, $months = 6)
    {
        $data = [];
        $currentDate = date('Y-m-d');

        for ($i = $months - 1; $i >= 0; $i--) {
            $monthDate = date('Y-m-01', strtotime("-$i months", strtotime($currentDate)));
            $monthName = date('F Y', strtotime($monthDate));

            $sessions = $this->sesiLatihanModel
                ->where('id_siswa', $userId)
                ->where('status', 'selesai')
                ->like('waktu_selesai', $monthDate, 'after')
                ->countAllResults();

            $avgScore = $this->sesiLatihanModel
                ->selectAvg('skor')
                ->where('id_siswa', $userId)
                ->where('status', 'selesai')
                ->like('waktu_selesai', $monthDate, 'after')
                ->first();

            $data[] = [
                'month' => $monthName,
                'sessions_completed' => $sessions,
                'average_score' => floatval($avgScore['skor'] ?? 0)
            ];
        }

        return $data;
    }

    /**
     * Get difficulty statistics
     */
    private function getDifficultyStats($userId)
    {
        $stats = $this->sesiLatihanModel
            ->select('
                materi_kaidah.tingkat_kesulitan,
                COUNT(*) as total_sessions,
                AVG(sesi_latihan.skor) as average_score
            ')
            ->join('materi_kaidah', 'materi_kaidah.id_materi = sesi_latihan.id_materi')
            ->where('sesi_latihan.id_siswa', $userId)
            ->where('sesi_latihan.status', 'selesai')
            ->groupBy('materi_kaidah.tingkat_kesulitan')
            ->findAll();

        $result = [
            'mudah' => ['sessions' => 0, 'avg_score' => 0],
            'sedang' => ['sessions' => 0, 'avg_score' => 0],
            'sulit' => ['sessions' => 0, 'avg_score' => 0]
        ];

        foreach ($stats as $stat) {
            $difficulty = $stat['tingkat_kesulitan'];
            if (isset($result[$difficulty])) {
                $result[$difficulty]['sessions'] = (int)$stat['total_sessions'];
                $result[$difficulty]['avg_score'] = floatval($stat['average_score']);
            }
        }

        return $result;
    }

    /**
     * Get best performing kaidah
     */
    private function getBestPerformingKaidah($userId, $limit = 3)
    {
        return $this->sesiLatihanModel
            ->select('
                materi_kaidah.id_materi,
                materi_kaidah.judul_kaidah,
                materi_kaidah.tingkat_kesulitan,
                AVG(sesi_latihan.skor) as average_score,
                COUNT(*) as total_sessions
            ')
            ->join('materi_kaidah', 'materi_kaidah.id_materi = sesi_latihan.id_materi')
            ->where('sesi_latihan.id_siswa', $userId)
            ->where('sesi_latihan.status', 'selesai')
            ->groupBy('materi_kaidah.id_materi')
            ->having('total_sessions >=', 2)
            ->orderBy('average_score', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get struggling kaidah
     */
    private function getStrugglingKaidah($userId, $limit = 3)
    {
        return $this->sesiLatihanModel
            ->select('
                materi_kaidah.id_materi,
                materi_kaidah.judul_kaidah,
                materi_kaidah.tingkat_kesulitan,
                AVG(sesi_latihan.skor) as average_score,
                COUNT(*) as total_sessions
            ')
            ->join('materi_kaidah', 'materi_kaidah.id_materi = sesi_latihan.id_materi')
            ->where('sesi_latihan.id_siswa', $userId)
            ->where('sesi_latihan.status', 'selesai')
            ->groupBy('materi_kaidah.id_materi')
            ->having('total_sessions >=', 2)
            ->orderBy('average_score', 'ASC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get weekly chart data
     */
    private function getWeeklyChart($userId)
    {
        $data = [];
        $currentDate = date('Y-m-d');

        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days", strtotime($currentDate)));
            $dayName = date('D', strtotime($date));

            $sessions = $this->sesiLatihanModel
                ->where('id_siswa', $userId)
                ->where('status', 'selesai')
                ->where('DATE(waktu_selesai)', $date)
                ->countAllResults();

            $data[] = [
                'day' => $dayName,
                'date' => $date,
                'sessions' => $sessions
            ];
        }

        return $data;
    }

    /**
     * Get monthly chart data
     */
    private function getMonthlyChart($userId)
    {
        return $this->getMonthlyProgress($userId, 12);
    }

    /**
     * Get yearly chart data
     */
    private function getYearlyChart($userId)
    {
        $data = [];
        $currentYear = date('Y');

        for ($i = 2; $i >= 0; $i--) {
            $year = $currentYear - $i;

            $sessions = $this->sesiLatihanModel
                ->where('id_siswa', $userId)
                ->where('status', 'selesai')
                ->where('YEAR(waktu_selesai)', $year)
                ->countAllResults();

            $avgScore = $this->sesiLatihanModel
                ->selectAvg('skor')
                ->where('id_siswa', $userId)
                ->where('status', 'selesai')
                ->where('YEAR(waktu_selesai)', $year)
                ->first();

            $data[] = [
                'year' => $year,
                'sessions' => $sessions,
                'average_score' => floatval($avgScore['skor'] ?? 0)
            ];
        }

        return $data;
    }

    /**
     * Helper method to get user ID from token
     */
    private function getUserIdFromToken()
    {
        $authorization = $this->request->getHeaderLine('Authorization');

        if (empty($authorization) || !preg_match('/Bearer\s+(.*)$/i', $authorization, $matches)) {
            return null;
        }

        $token = $matches[1];
        $payload = json_decode(base64_decode($token), true);

        if (!$payload || !isset($payload['user_id']) || !isset($payload['exp'])) {
            return null;
        }

        // Check if token expired
        if ($payload['exp'] < time()) {
            return null;
        }

        return $payload['user_id'];
    }
}