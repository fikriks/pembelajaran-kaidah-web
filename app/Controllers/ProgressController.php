<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MateriKaidahModel;
use App\Models\SesiLatihanModel;
use App\Models\SiswaModel;
use App\Models\RiwayatBelajarModel;
use CodeIgniter\API\ResponseTrait;

class ProgressController extends BaseController
{
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
     * Display progress overview for all students
     */
    public function index()
    {
        $data = [
            'title' => 'Progress Belajar Siswa',
            'page' => 'progress',
            'subtitle' => 'Monitoring progress pembelajaran siswa'
        ];

        // Get overall statistics
        $data['stats'] = $this->getOverallStats();

        return view('progress/index', $data);
    }

    /**
     * Display detailed progress for specific student
     */
    public function detail($siswaId = null)
    {
        if (!$siswaId) {
            return redirect()->to('/progress')->with('error', 'ID siswa tidak valid');
        }

        // Get student data
        $siswa = $this->siswaModel->find($siswaId);
        if (!$siswa) {
            return redirect()->to('/progress')->with('error', 'Siswa tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Progress - ' . $siswa['nama_lengkap'],
            'page' => 'progress',
            'subtitle' => 'Detail progress pembelajaran siswa',
            'siswa' => $siswa
        ];

        // Get student progress overview
        $data['progress'] = $this->getStudentProgress($siswaId);

        // Get kaidah progress
        $data['kaidahProgress'] = $this->getStudentKaidahProgress($siswaId);

        // Get session history
        $data['sessions'] = $this->getStudentSessions($siswaId);

        // Get weekly activity
        $data['weeklyActivity'] = $this->getWeeklyActivity($siswaId);

        return view('progress/detail', $data);
    }

    /**
     * API endpoint for student list with progress
     */
    public function getStudents()
    {
        $search = $this->request->getVar('search');
        $kelas = $this->request->getVar('kelas');
        $status = $this->request->getVar('status');
        $limit = $this->request->getVar('limit') ?? 10;
        $offset = $this->request->getVar('offset') ?? 0;

        $students = $this->getStudentsWithProgress($search, $kelas, $status, $limit, $offset);

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $students['data'],
            'total' => $students['total'],
            'limit' => $limit,
            'offset' => $offset
        ]);
    }

    /**
     * Get overall statistics for dashboard
     */
    private function getOverallStats()
    {
        $db = \Config\Database::connect();

        // Get total students
        $totalStudents = $db->table('siswa')
            ->where('status', 'AKTIF')
            ->countAllResults();

        // Get students with progress
        $studentsWithProgress = $db->table('riwayat_belajar rb')
            ->distinct()
            ->select('rb.id_riwayat, rb.id_siswa, rb.id_materi, rb.status, rb.persentase_penguasaan, rb.waktu_dibuat, rb.waktu_diubah, rb.waktu_akses_terakhir')
            ->join('siswa s', 's.id = rb.id_siswa')
            ->where('s.status', 'AKTIF')
            ->countAllResults();

        // Get total sessions
        $totalSessions = $db->table('sesi_pembelajaran sp')
            ->join('siswa s', 's.id = sp.id_siswa')
            ->where('s.status', 'AKTIF')
            ->countAllResults();

        // Get completed sessions
        $completedSessions = $db->table('sesi_pembelajaran sp')
            ->join('siswa s', 's.id = sp.id_siswa')
            ->where('s.status', 'AKTIF')
            ->where('sp.status', 'selesai')
            ->countAllResults();

        // Get average score
        $avgScoreQuery = $db->table('sesi_pembelajaran sp')
            ->join('siswa s', 's.id = sp.id_siswa')
            ->where('s.status', 'AKTIF')
            ->where('sp.status', 'selesai')
            ->selectAvg('sp.skor', 'avg_score')
            ->get()
            ->getRowArray();
        $avgScore = $avgScoreQuery['avg_score'] ?? 0;

        // Get completion rate
        $completionRate = $totalSessions > 0 ? round(($completedSessions / $totalSessions) * 100, 1) : 0;

        // Get kaidah statistics
        $totalKaidah = $db->table('materi_kaidah')->countAllResults();

        $completedKaidahQuery = $db->table('riwayat_belajar rb')
            ->select('rb.id_materi')
            ->join('siswa s', 's.id = rb.id_siswa')
            ->where('s.status', 'AKTIF')
            ->where('rb.status', 'selesai')
            ->distinct()
            ->get()
            ->getResultArray();
        $completedKaidah = count($completedKaidahQuery);

        return [
            'total_students' => $totalStudents,
            'students_with_progress' => $studentsWithProgress,
            'total_sessions' => $totalSessions,
            'completed_sessions' => $completedSessions,
            'completion_rate' => $completionRate,
            'average_score' => round($avgScore, 2),
            'total_kaidah' => $totalKaidah,
            'completed_kaidah' => $completedKaidah
        ];
    }

   
  
    
    /**
     * Get student progress overview
     */
    private function getStudentProgress($siswaId)
    {
        $db = \Config\Database::connect();

        // Get all kaidah
        $totalKaidah = $db->table('materi_kaidah')->countAllResults();

        // Get completed kaidah from riwayat_belajar
        $completedKaidah = $db->table('riwayat_belajar')
            ->where('id_siswa', $siswaId)
            ->where('status', 'selesai')
            ->distinct('id_materi')
            ->countAllResults();

        // Get in-progress kaidah
        $inProgressKaidah = $db->table('riwayat_belajar')
            ->where('id_siswa', $siswaId)
            ->where('status', 'sedang_belajar')
            ->distinct('id_materi')
            ->countAllResults();

        // Get session statistics
        $sessionStats = $db->table('sesi_pembelajaran')
            ->select('
                COUNT(*) as total_sessions,
                SUM(CASE WHEN status = "selesai" THEN 1 ELSE 0 END) as completed_sessions,
                AVG(CASE WHEN status = "selesai" THEN skor END) as avg_score,
                MAX(CASE WHEN status = "selesai" THEN skor END) as best_score,
                SUM(CASE WHEN status = "selesai" THEN total_soal END) as total_questions,
                SUM(CASE WHEN status = "selesai" THEN soal_benar END) as total_correct
            ')
            ->where('id_siswa', $siswaId)
            ->get()
            ->getRowArray();

        return [
            'total_kaidah' => $totalKaidah,
            'completed_kaidah' => $completedKaidah,
            'in_progress_kaidah' => $inProgressKaidah,
            'not_started_kaidah' => $totalKaidah - $completedKaidah - $inProgressKaidah,
            'completion_percentage' => $totalKaidah > 0 ? round(($completedKaidah / $totalKaidah) * 100, 1) : 0,
            'total_sessions' => (int)$sessionStats['total_sessions'],
            'completed_sessions' => (int)$sessionStats['completed_sessions'],
            'average_score' => round($sessionStats['avg_score'] ?: 0, 2),
            'best_score' => round($sessionStats['best_score'] ?: 0, 2),
            'total_questions' => (int)($sessionStats['total_questions'] ?: 0),
            'total_correct' => (int)($sessionStats['total_correct'] ?: 0),
            'accuracy' => $sessionStats['total_questions'] > 0 ?
                round(($sessionStats['total_correct'] / $sessionStats['total_questions']) * 100, 1) : 0
        ];
    }

    /**
     * Get student kaidah progress
     */
    private function getStudentKaidahProgress($siswaId)
    {
        $db = \Config\Database::connect();

        // Get all materi
        $materiList = $db->table('materi_kaidah mk')
            ->select('
                mk.id_materi,
                mk.judul_kaidah,
                mk.deskripsi,
                mk.id_bab,
                bab.nama_bab
            ')
            ->join('bab', 'bab.id_bab = mk.id_bab', 'left')
            ->orderBy('mk.id_bab', 'ASC')
            ->orderBy('mk.urutan', 'ASC')
            ->get()
            ->getResultArray();

        // Get latest progress for each materi
        $progressData = [];
        $riwayatList = $db->table('riwayat_belajar')
            ->where('id_siswa', $siswaId)
            ->orderBy('waktu_diubah', 'DESC')
            ->get()
            ->getResultArray();

        // Group riwayat by materi and get latest
        foreach ($riwayatList as $riwayat) {
            $materiId = $riwayat['id_materi'];
            if (!isset($progressData[$materiId])) {
                $progressData[$materiId] = $riwayat;
            }
        }

        // Combine materi with progress
        foreach ($materiList as &$materi) {
            $materiId = $materi['id_materi'];
            $progress = $progressData[$materiId] ?? null;

            $materi['status'] = $progress['status'] ?? 'belum_dimulai';
            $materi['completion_percentage'] = $progress['persentase_penguasaan'] ?? 0;
            $materi['last_accessed'] = $progress['waktu_akses_terakhir'] ?? null;
        }

        return $materiList;
    }

    /**
     * Get student session history
     */
    private function getStudentSessions($siswaId, $limit = 10)
    {
        $db = \Config\Database::connect();

        return $db->table('sesi_pembelajaran sp')
            ->select('
                sp.id_sesi,
                sp.id_siswa,
                sp.id_materi,
                sp.waktu_mulai,
                sp.waktu_selesai,
                sp.status as sesi_status,
                sp.total_soal,
                sp.soal_benar,
                sp.skor,
                sp.durasi_detik,
                mk.judul_kaidah,
                bab.nama_bab
            ')
            ->join('materi_kaidah mk', 'mk.id_materi = sp.id_materi')
            ->join('bab', 'bab.id_bab = mk.id_bab', 'left')
            ->where('sp.id_siswa', $siswaId)
            ->orderBy('sp.waktu_mulai', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Get weekly activity for student
     */
    private function getWeeklyActivity($siswaId)
    {
        $db = \Config\Database::connect();

        // Get last 7 days
        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $days[$date] = [
                'date' => $date,
                'day_name' => date('D', strtotime($date)),
                'sessions' => 0,
                'study_time' => 0
            ];
        }

        // Get sessions in last 7 days
        $sessions = $db->table('sesi_pembelajaran')
            ->select('DATE(waktu_mulai) as date, COUNT(*) as sessions, SUM(durasi_detik) as study_time')
            ->where('id_siswa', $siswaId)
            ->where('waktu_mulai >=', date('Y-m-d', strtotime('-6 days')))
            ->groupBy('DATE(waktu_mulai)')
            ->get()
            ->getResultArray();

        foreach ($sessions as $session) {
            if (isset($days[$session['date']])) {
                $days[$session['date']]['sessions'] = (int)$session['sessions'];
                $days[$session['date']]['study_time'] = (int)($session['study_time'] / 60); // Convert to minutes
            }
        }

        return array_values($days);
    }

    /**
     * Get students with progress filtering
     */
    private function getStudentsWithProgress($search = null, $kelas = null, $status = null, $limit = 10, $offset = 0)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('siswa s')
            ->select('
                s.id,
                s.nama_lengkap,
                s.nis,
                s.kelas,
                s.status as siswa_status,
                COUNT(rb.id_riwayat) as total_kaidah_progress,
                SUM(CASE WHEN rb.status = "selesai" THEN 1 ELSE 0 END) as completed_kaidah,
                COUNT(sp.id_sesi) as total_sessions,
                AVG(sp.skor) as avg_score,
                MAX(sp.waktu_mulai) as last_activity
            ')
            ->join('riwayat_belajar rb', 'rb.id_siswa = s.id', 'left')
            ->join('sesi_pembelajaran sp', 'sp.id_siswa = s.id', 'left')
            ->groupBy('s.id, s.nama_lengkap, s.nis, s.kelas, s.status');

        // Apply filters
        if ($search) {
            $builder->groupStart()
                ->like('s.nama_lengkap', $search)
                ->orLike('s.nis', $search)
                ->orLike('s.email', $search)
                ->groupEnd();
        }

        if ($kelas) {
            $builder->where('s.kelas', $kelas);
        }

        if ($status) {
            $builder->where('s.status', $status);
        }

        // Get total count
        $total = $builder->countAllResults(false);

        // Get paginated results
        $students = $builder->orderBy('s.nama_lengkap', 'ASC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();

        return [
            'data' => array_map(function($student) {
                return [
                    'id' => $student['id'],
                    'nama_lengkap' => $student['nama_lengkap'],
                    'nis' => $student['nis'],
                    'kelas' => $student['kelas'],
                    'status' => $student['siswa_status'],
                    'total_kaidah_progress' => (int)$student['total_kaidah_progress'],
                    'completed_kaidah' => (int)$student['completed_kaidah'],
                    'total_sessions' => (int)$student['total_sessions'],
                    'average_score' => round($student['avg_score'] ?: 0, 2),
                    'last_activity' => $student['last_activity'],
                    'progress_percentage' => $student['total_kaidah_progress'] > 0 ?
                        round(($student['completed_kaidah'] / $student['total_kaidah_progress']) * 100, 1) : 0
                ];
            }, $students),
            'total' => $total
        ];
    }
}