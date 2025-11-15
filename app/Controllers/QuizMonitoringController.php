<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SesiLatihanModel;
use App\Models\DetailJawabanSiswaModel;
use App\Models\SiswaModel;
use App\Models\MateriKaidahModel;
use App\Models\SoalModel;
use App\Models\PilihanJawabanModel;
use CodeIgniter\API\ResponseTrait;

class QuizMonitoringController extends BaseController
{
    protected $sesiLatihanModel;
    protected $detailJawabanSiswaModel;
    protected $siswaModel;
    protected $materiKaidahModel;
    protected $soalModel;
    protected $pilihanJawabanModel;

    public function __construct()
    {
        $this->sesiLatihanModel = new SesiLatihanModel();
        $this->detailJawabanSiswaModel = new DetailJawabanSiswaModel();
        $this->siswaModel = new SiswaModel();
        $this->materiKaidahModel = new MateriKaidahModel();
        $this->soalModel = new SoalModel();
        $this->pilihanJawabanModel = new PilihanJawabanModel();
    }

    /**
     * Display quiz monitoring overview
     */
    public function index()
    {
        $data = [
            'title' => 'Monitoring Quiz Siswa',
            'page' => 'quiz-monitoring',
            'subtitle' => 'Monitoring hasil quiz dan jawaban siswa'
        ];

        // Get overall statistics
        $data['stats'] = $this->getQuizStats();

        return view('quiz-monitoring/index', $data);
    }

    /**
     * Display detailed quiz results for specific session
     */
    public function detail($sesiId = null)
    {
        if (!$sesiId) {
            return redirect()->to('/quiz-monitoring')->with('error', 'ID sesi tidak valid');
        }

        // Get session data
        $sesi = $this->sesiLatihanModel->find($sesiId);
        if (!$sesi) {
            return redirect()->to('/quiz-monitoring')->with('error', 'Sesi tidak ditemukan');
        }

        // Get student data
        $siswa = $this->siswaModel->find($sesi['id_siswa']);
        $studentName = $siswa['nama_lengkap'] ?? 'Siswa Tidak Diketahui';

        // Get materi data
        $materi = $this->materiKaidahModel->find($sesi['id_materi']);
        $materiName = $materi['judul_kaidah'] ?? 'Materi Tidak Diketahui';

        $data = [
            'title' => 'Detail Quiz - ' . $studentName,
            'page' => 'quiz-monitoring',
            'subtitle' => 'Detail hasil quiz ' . $studentName . ' - ' . $materiName,
            'sesi' => $sesi,
            'siswa' => $siswa,
            'materi' => $materi
        ];

        // Get detailed answers
        $data['answers'] = $this->getSessionAnswers($sesiId);

        return view('quiz-monitoring/detail', $data);
    }

    /**
     * API endpoint for quiz sessions list
     */
    public function getSessions()
    {
        $search = $this->request->getVar('search');
        $kelas = $this->request->getVar('kelas');
        $status = $this->request->getVar('status');
        $tanggal = $this->request->getVar('tanggal');
        $limit = $this->request->getVar('limit') ?? 10;
        $offset = $this->request->getVar('offset') ?? 0;

        $sessions = $this->getQuizSessions($search, $kelas, $status, $tanggal, $limit, $offset);

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $sessions['data'],
            'total' => $sessions['total'],
            'limit' => $limit,
            'offset' => $offset
        ]);
    }

    /**
     * API endpoint for detailed answers
     */
    public function getAnswers($sesiId)
    {
        $answers = $this->getSessionAnswers($sesiId);

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $answers
        ]);
    }

    /**
     * Get overall quiz statistics
     */
    private function getQuizStats()
    {
        $db = \Config\Database::connect();

        // Get total sessions
        $totalSessions = $db->table('sesi_latihan')
            ->countAllResults();

        // Get completed sessions
        $completedSessions = $db->table('sesi_latihan')
            ->where('status', 'selesai')
            ->countAllResults();

        // Get average score
        $avgScoreQuery = $db->table('sesi_latihan')
            ->where('status', 'selesai')
            ->selectAvg('skor', 'avg_score')
            ->get()
            ->getRowArray();
        $avgScore = $avgScoreQuery['avg_score'] ?? 0;

        // Get best score
        $bestScoreQuery = $db->table('sesi_latihan')
            ->where('status', 'selesai')
            ->selectMax('skor', 'best_score')
            ->get()
            ->getRowArray();
        $bestScore = $bestScoreQuery['best_score'] ?? 0;

        // Get today's sessions
        $todaySessions = $db->table('sesi_latihan')
            ->where('DATE(waktu_mulai)', date('Y-m-d'))
            ->countAllResults();

        // Get this week's sessions
        $weekSessions = $db->table('sesi_latihan')
            ->where('waktu_mulai >=', date('Y-m-d', strtotime('-7 days')))
            ->countAllResults();

        // Get unique students who took quiz
        $uniqueStudents = $db->table('sesi_latihan')
            ->distinct()
            ->select('id_siswa')
            ->countAllResults();

        // Get average duration
        $avgDurationQuery = $db->table('sesi_latihan')
            ->where('status', 'selesai')
            ->where('durasi_detik >', 0)
            ->selectAvg('durasi_detik', 'avg_duration')
            ->get()
            ->getRowArray();
        $avgDuration = $avgDurationQuery['avg_duration'] ?? 0;

        return [
            'total_sessions' => $totalSessions,
            'completed_sessions' => $completedSessions,
            'completion_rate' => $totalSessions > 0 ? round(($completedSessions / $totalSessions) * 100, 1) : 0,
            'average_score' => round($avgScore, 2),
            'best_score' => round($bestScore, 2),
            'today_sessions' => $todaySessions,
            'week_sessions' => $weekSessions,
            'unique_students' => $uniqueStudents,
            'average_duration' => $this->formatDuration($avgDuration)
        ];
    }

    /**
     * Get quiz sessions with filtering
     */
    private function getQuizSessions($search = null, $kelas = null, $status = null, $tanggal = null, $limit = 10, $offset = 0)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('sesi_latihan sp')
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
                s.nama_lengkap,
                s.nis,
                s.kelas,
                mk.judul_kaidah,
                bab.nama_bab
            ')
            ->join('siswa s', 's.id = sp.id_siswa')
            ->join('materi_kaidah mk', 'mk.id_materi = sp.id_materi')
            ->join('bab', 'bab.id_bab = mk.id_bab', 'left');

        // Apply filters
        if ($search) {
            $builder->groupStart()
                ->like('s.nama_lengkap', $search)
                ->orLike('s.nis', $search)
                ->orLike('mk.judul_kaidah', $search)
                ->groupEnd();
        }

        if ($kelas) {
            $builder->where('s.kelas', $kelas);
        }

        if ($status) {
            $builder->where('sp.status', $status);
        }

        if ($tanggal) {
            $builder->where('DATE(sp.waktu_mulai)', $tanggal);
        }

        // Get total count
        $total = $builder->countAllResults(false);

        // Get paginated results
        $sessions = $builder->orderBy('sp.waktu_mulai', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();

        return [
            'data' => array_map(function($session) {
                return [
                    'id_sesi' => $session['id_sesi'],
                    'id_siswa' => $session['id_siswa'],
                    'id_materi' => $session['id_materi'],
                    'waktu_mulai' => $session['waktu_mulai'],
                    'waktu_selesai' => $session['waktu_selesai'],
                    'status' => $session['sesi_status'],
                    'total_soal' => (int)$session['total_soal'],
                    'soal_benar' => (int)$session['soal_benar'],
                    'skor' => round($session['skor'], 2),
                    'durasi_detik' => (int)$session['durasi_detik'],
                    'durasi' => $this->formatDuration($session['durasi_detik']),
                    'nama_lengkap' => $session['nama_lengkap'],
                    'nis' => $session['nis'],
                    'kelas' => $session['kelas'],
                    'judul_kaidah' => $session['judul_kaidah'],
                    'nama_bab' => $session['nama_bab'],
                    'persentase_benar' => $session['total_soal'] > 0 ?
                        round(($session['soal_benar'] / $session['total_soal']) * 100, 1) : 0,
                    'status_badge' => $this->getStatusBadge($session['sesi_status'])
                ];
            }, $sessions),
            'total' => $total
        ];
    }

    /**
     * Get detailed answers for a session
     */
    private function getSessionAnswers($sesiId)
    {
        $db = \Config\Database::connect();

        // Get session answers
        $answers = $db->table('detail_jawaban_siswa djs')
            ->select('
                djs.id_detail,
                djs.id_sesi,
                djs.id_soal,
                djs.id_pilihan_jawaban,
                djs.is_benar,
                so.pertanyaan,
                so.tipe_soal,
                mk.judul_kaidah,
                pj.teks_jawaban,
                pj.is_kunci
            ')
            ->join('soal so', 'so.id_soal = djs.id_soal')
            ->join('materi_kaidah mk', 'mk.id_materi = so.id_materi')
            ->join('pilihan_jawaban pj', 'pj.id_pilihan = djs.id_pilihan_jawaban', 'left')
            ->where('djs.id_sesi', $sesiId)
            ->orderBy('so.id_soal', 'ASC')
            ->get()
            ->getResultArray();

        // Get all options for each question to show wrong answers
        $detailedAnswers = [];
        foreach ($answers as $answer) {
            $soalId = $answer['id_soal'];

            // Get all options for this question
            $allOptions = $db->table('pilihan_jawaban')
                ->where('id_soal', $soalId)
                ->orderBy('urutan', 'ASC')
                ->get()
                ->getResultArray();

            $detailedAnswers[] = [
                'id_detail' => $answer['id_detail'],
                'id_soal' => $answer['id_soal'],
                'pertanyaan' => $answer['pertanyaan'],
                'tipe_soal' => $answer['tipe_soal'],
                'judul_kaidah' => $answer['judul_kaidah'],
                'jawaban_siswa' => $answer['id_pilihan_jawaban'],
                'teks_jawaban_siswa' => $answer['teks_jawaban'],
                'is_benar' => $answer['is_benar'],
                'semua_pilihan' => array_map(function($option) use ($answer) {
                    return [
                        'id_pilihan' => $option['id_pilihan'],
                        'teks_jawaban' => $option['teks_jawaban'],
                        'is_kunci' => $option['is_kunci'],
                        'is_selected' => $option['id_pilihan'] == $answer['id_pilihan_jawaban'],
                        'badge_class' => $option['is_kunci'] ? 'success' :
                                       ($option['id_pilihan'] == $answer['id_pilihan_jawaban'] ? 'danger' : 'secondary')
                    ];
                }, $allOptions)
            ];
        }

        return $detailedAnswers;
    }

    /**
     * Format duration to human readable format
     */
    private function formatDuration($seconds)
    {
        if ($seconds == 0) return '0 detik';

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        $parts = [];
        if ($hours > 0) $parts[] = $hours . ' jam';
        if ($minutes > 0) $parts[] = $minutes . ' menit';
        if ($seconds > 0 || empty($parts)) $parts[] = $seconds . ' detik';

        return implode(' ', $parts);
    }

    /**
     * Get status badge HTML
     */
    private function getStatusBadge($status)
    {
        switch ($status) {
            case 'selesai':
                return '<span class="badge bg-success">Selesai</span>';
            case 'sedang_berlangsung':
                return '<span class="badge bg-warning">Sedang Berlangsung</span>';
            case 'dibatalkan':
                return '<span class="badge bg-danger">Dibatalkan</span>';
            default:
                return '<span class="badge bg-secondary">' . ucfirst($status) . '</span>';
        }
    }
}