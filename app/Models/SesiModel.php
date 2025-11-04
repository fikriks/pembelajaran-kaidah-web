<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * SesiModel - Model untuk Sesi Pembelajaran
 *
 * Model ini mengelola data sesi pembelajaran siswa dengan integrasi
 * LCM Algorithm untuk randomisasi soal dan tracking progress.
 *
 * @author Khozinnatul Ulum (20210810076)
 * @version 1.0.0
 * @since 2025-11-04
 */
class SesiModel extends Model
{
    protected $table = 'sesi_pembelajaran';
    protected $primaryKey = 'id_sesi';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'id_siswa',
        'id_materi',
        'seed_digunakan',
        'total_soal',
        'soal_benar',
        'skor',
        'waktu_mulai',
        'waktu_selesai',
        'durasi_detik',
        'status',
        'waktu_diubah'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'waktu_dibuat';
    protected $updatedField = 'waktu_diubah';

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['setTimestamps'];
    protected $beforeUpdate = ['setTimestamps'];

    /**
     * Set timestamps untuk created dan updated
     */
    protected function setTimestamps(array $data)
    {
        $currentDateTime = date('Y-m-d H:i:s');

        if (!isset($data['data']['waktu_dibuat'])) {
            $data['data']['waktu_dibuat'] = $currentDateTime;
        }

        $data['data']['waktu_diubah'] = $currentDateTime;

        return $data;
    }

    /**
     * Buat sesi pembelajaran baru dengan LCM
     */
    public function createSesi($idSiswa, $idMateri, $jumlahSoal = 20)
    {
        $this->transStart();

        try {
            // Generate seed untuk LCM
            $seed = $this->generateSeed($idSiswa, $idMateri);

            // Simpan sesi
            $sesiData = [
                'id_siswa' => $idSiswa,
                'id_materi' => $idMateri,
                'seed_digunakan' => $seed,
                'total_soal' => $jumlahSoal,
                'soal_benar' => 0,
                'skor' => 0.00,
                'waktu_mulai' => date('Y-m-d H:i:s'),
                'status' => 'sedang_berjalan'
            ];

            $idSesi = $this->insert($sesiData);

            if (!$idSesi) {
                throw new \Exception('Gagal membuat sesi pembelajaran');
            }

            $this->transComplete();
            return $idSesi;

        } catch (\Exception $e) {
            $this->transRollback();
            log_message('error', 'Error creating sesi: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate seed unik untuk LCM
     */
    private function generateSeed($idSiswa, $idMateri)
    {
        $timestamp = time();
        $microtime = (int)(microtime(true) * 1000);

        // Kombinasi ID siswa, ID materi, timestamp untuk seed unik
        return ($idSiswa * 1000000) + ($idMateri * 10000) + $timestamp + $microtime;
    }

    /**
     * Get sesi dengan relasi ke materi dan siswa
     */
    public function getSesiWithRelations($idSesi = null)
    {
        $builder = $this->select('
            sesi_pembelajaran.*,
            materi_kaidah.judul_kaidah,
            materi_kaidah.tingkat_kesulitan as tingkat_kesulitan_materi,
            siswa.nama_lengkap as nama_siswa,
            siswa.kelas,
            siswa.nis
        ')
        ->join('materi_kaidah', 'materi_kaidah.id_materi = sesi_pembelajaran.id_materi')
        ->join('siswa', 'siswa.id = sesi_pembelajaran.id_siswa');

        if ($idSesi) {
            $builder = $builder->where('sesi_pembelajaran.id_sesi', $idSesi);
        }

        $result = $builder->orderBy('sesi_pembelajaran.waktu_mulai', 'DESC')
                          ->findAll();

        return $idSesi ? ($result[0] ?? null) : $result;
    }

    /**
     * Get sesi berdasarkan siswa
     */
    public function getSesiBySiswa($idSiswa, $status = null)
    {
        $builder = $this->where('id_siswa', $idSiswa);

        if ($status) {
            $builder = $builder->where('status', $status);
        }

        return $builder->orderBy('waktu_mulai', 'DESC')
                      ->findAll();
    }

    /**
     * Get sesi berdasarkan materi
     */
    public function getSesiByMateri($idMateri, $status = null)
    {
        $builder = $this->where('id_materi', $idMateri);

        if ($status) {
            $builder = $builder->where('status', $status);
        }

        return $builder->orderBy('waktu_mulai', 'DESC')
                      ->findAll();
    }

    /**
     * Update progress sesi (jawaban benar dan skor)
     */
    public function updateProgress($idSesi, $isBenar, $poinSoal)
    {
        $this->transStart();

        try {
            // Get current sesi data
            $sesi = $this->find($idSesi);
            if (!$sesi) {
                throw new \Exception('Sesi tidak ditemukan');
            }

            // Update counters
            $newSoalBenar = $isBenar ? $sesi['soal_benar'] + 1 : $sesi['soal_benar'];
            $newSkor = $sesi['skor'] + ($isBenar ? $poinSoal : 0);

            $updateData = [
                'soal_benar' => $newSoalBenar,
                'skor' => $newSkor,
                'waktu_diubah' => date('Y-m-d H:i:s')
            ];

            if (!$this->update($idSesi, $updateData)) {
                throw new \Exception('Gagal update progress sesi');
            }

            $this->transComplete();
            return true;

        } catch (\Exception $e) {
            $this->transRollback();
            log_message('error', 'Error updating progress: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Selesaikan sesi pembelajaran
     */
    public function selesaikanSesi($idSesi)
    {
        $this->transStart();

        try {
            $sesi = $this->find($idSesi);
            if (!$sesi) {
                throw new \Exception('Sesi tidak ditemukan');
            }

            // Calculate duration
            $waktuMulai = new \DateTime($sesi['waktu_mulai']);
            $waktuSelesai = new \DateTime();
            $durasi = $waktuSelesai->getTimestamp() - $waktuMulai->getTimestamp();

            $updateData = [
                'waktu_selesai' => $waktuSelesai->format('Y-m-d H:i:s'),
                'durasi_detik' => $durasi,
                'status' => 'selesai',
                'waktu_diubah' => date('Y-m-d H:i:s')
            ];

            if (!$this->update($idSesi, $updateData)) {
                throw new \Exception('Gagal menyelesaikan sesi');
            }

            $this->transComplete();
            return true;

        } catch (\Exception $e) {
            $this->transRollback();
            log_message('error', 'Error completing sesi: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Batalkan sesi pembelajaran
     */
    public function batalkanSesi($idSesi)
    {
        $updateData = [
            'waktu_selesai' => date('Y-m-d H:i:s'),
            'status' => 'dibatalkan',
            'waktu_diubah' => date('Y-m-d H:i:s')
        ];

        return $this->update($idSesi, $updateData);
    }

    /**
     * Get statistik sesi pembelajaran
     */
    public function getSesiStatistics()
    {
        $total = $this->countAll();
        $sedangBerjalan = $this->where('status', 'sedang_berjalan')->countAllResults();
        $selesai = $this->where('status', 'selesai')->countAllResults();
        $dibatalkan = $this->where('status', 'dibatalkan')->countAllResults();

        // Average scores
        $avgScore = $this->selectAvg('skor')->where('status', 'selesai')->first();
        $maxScore = $this->selectMax('skor')->where('status', 'selesai')->first();
        $minScore = $this->selectMin('skor')->where('status', 'selesai')->first();

        // Average duration
        $avgDuration = $this->selectAvg('durasi_detik')->where('status', 'selesai')->first();

        return [
            'total' => $total,
            'sedang_berjalan' => $sedangBerjalan,
            'selesai' => $selesai,
            'dibatalkan' => $dibatalkan,
            'rata_rata_skor' => $avgScore ? round($avgScore, 2) : 0,
            'skor_tertinggi' => $maxScore ?: 0,
            'skor_terendah' => $minScore ?: 0,
            'rata_rata_durasi_menit' => $avgDuration ? round($avgDuration / 60, 1) : 0
        ];
    }

    /**
     * Get statistik per materi
     */
    public function getStatistikPerMateri($limit = 10)
    {
        return $this->select('
            materi_kaidah.judul_kaidah,
            COUNT(*) as total_sesi,
            AVG(sesi_pembelajaran.skor) as rata_rata_skor,
            MAX(sesi_pembelajaran.skor) as skor_tertinggi,
            AVG(sesi_pembelajaran.durasi_detik/60) as rata_rata_durasi_menit
        ')
        ->join('materi_kaidah', 'materi_kaidah.id_materi = sesi_pembelajaran.id_materi')
        ->where('sesi_pembelajaran.status', 'selesai')
        ->groupBy('sesi_pembelajaran.id_materi')
        ->orderBy('total_sesi', 'DESC')
        ->limit($limit)
        ->findAll();
    }

    /**
     * Get statistik per siswa
     */
    public function getStatistikPerSiswa($limit = 10)
    {
        return $this->select('
            siswa.nama_lengkap,
            siswa.kelas,
            siswa.nis,
            COUNT(*) as total_sesi,
            AVG(sesi_pembelajaran.skor) as rata_rata_skor,
            MAX(sesi_pembelajaran.skor) as skor_tertinggi,
            SUM(CASE WHEN sesi_pembelajaran.status = "selesai" THEN 1 ELSE 0 END) as sesi_selesai
        ')
        ->join('siswa', 'siswa.id = sesi_pembelajaran.id_siswa')
        ->groupBy('sesi_pembelajaran.id_siswa')
        ->orderBy('rata_rata_skor', 'DESC')
        ->limit($limit)
        ->findAll();
    }

    /**
     * Get sesi aktif (sedang berjalan)
     */
    public function getSesiAktif()
    {
        return $this->getSesiWithRelations()
            ->where('status', 'sedang_berjalan')
            ->findAll();
    }

    /**
     * Get sesi yang sudah selesai hari ini
     */
    public function getSesiSelesaiHariIni()
    {
        $today = date('Y-m-d');

        return $this->getSesiWithRelations()
            ->where('status', 'selesai')
            ->where('DATE(waktu_selesai)', $today)
            ->orderBy('waktu_selesai', 'DESC')
            ->findAll();
    }

    /**
     * Check apakah siswa memiliki sesi aktif
     */
    public function hasSesiAktif($idSiswa)
    {
        return $this->where('id_siswa', $idSiswa)
                   ->where('status', 'sedang_berjalan')
                   ->countAllResults() > 0;
    }

    /**
     * Get sesi aktif siswa
     */
    public function getSesiAktifSiswa($idSiswa)
    {
        return $this->where('id_siswa', $idSiswa)
                   ->where('status', 'sedang_berjalan')
                   ->first();
    }

    /**
     * Auto cleanup sesi yang terlalu lama (timeout)
     */
    public function cleanupTimeoutSesi($timeoutMinutes = 30)
    {
        $timeoutTime = date('Y-m-d H:i:s', strtotime("-{$timeoutMinutes} minutes"));

        return $this->where('status', 'sedang_berjalan')
                   ->where('waktu_mulai <', $timeoutTime)
                   ->set([
                       'status' => 'dibatalkan',
                       'waktu_selesai' => date('Y-m-d H:i:s'),
                       'waktu_diubah' => date('Y-m-d H:i:s')
                   ])
                   ->update();
    }

    /**
     * Get sesi untuk API mobile
     */
    public function getSesiForMobile($idSesi)
    {
        $sesi = $this->getSesiWithRelations($idSesi);

        if (!$sesi) {
            return null;
        }

        // Get detail jawaban siswa
        $db = \Config\Database::connect();
        $detailJawaban = $db->table('detail_jawaban_siswa')
            ->select('detail_jawaban_siswa.*, soal.pertanyaan, pilihan_jawaban.teks_jawaban')
            ->join('soal', 'soal.id_soal = detail_jawaban_siswa.id_soal')
            ->join('pilihan_jawaban', 'pilihan_jawaban.id_pilihan = detail_jawaban_siswa.id_pilihan')
            ->where('detail_jawaban_siswa.id_sesi', $idSesi)
            ->orderBy('detail_jawaban_siswa.urutan_soal', 'ASC')
            ->get()
            ->getResultArray();

        $sesi['detail_jawaban'] = $detailJawaban;
        $sesi['progress_persen'] = $sesi['total_soal'] > 0 ?
            round(($sesi['soal_benar'] / $sesi['total_soal']) * 100, 1) : 0;

        return $sesi;
    }

    /**
     * Cari sesi berdasarkan keyword
     */
    public function searchSesi($keyword, $status = null)
    {
        $builder = $this->select('
            sesi_pembelajaran.*,
            materi_kaidah.judul_kaidah,
            siswa.nama_lengkap as nama_siswa,
            siswa.kelas
        ')
        ->join('materi_kaidah', 'materi_kaidah.id_materi = sesi_pembelajaran.id_materi')
        ->join('siswa', 'siswa.id = sesi_pembelajaran.id_siswa')
        ->groupStart()
            ->like('siswa.nama_lengkap', $keyword)
            ->orLike('siswa.nis', $keyword)
            ->orLike('materi_kaidah.judul_kaidah', $keyword)
        ->groupEnd();

        if ($status) {
            $builder = $builder->where('sesi_pembelajaran.status', $status);
        }

        return $builder->orderBy('sesi_pembelajaran.waktu_mulai', 'DESC')
                      ->findAll();
    }

    /**
     * Get sesi dengan pagination
     */
    public function getSesiWithPagination($perPage = 10, $page = 1, $filters = [])
    {
        $builder = $this->select('
            sesi_pembelajaran.*,
            materi_kaidah.judul_kaidah,
            siswa.nama_lengkap as nama_siswa,
            siswa.kelas,
            siswa.nis
        ')
        ->join('materi_kaidah', 'materi_kaidah.id_materi = sesi_pembelajaran.id_materi')
        ->join('siswa', 'siswa.id = sesi_pembelajaran.id_siswa');

        // Apply filters
        if (!empty($filters['status'])) {
            $builder->where('sesi_pembelajaran.status', $filters['status']);
        }
        if (!empty($filters['id_materi'])) {
            $builder->where('sesi_pembelajaran.id_materi', $filters['id_materi']);
        }
        if (!empty($filters['id_siswa'])) {
            $builder->where('sesi_pembelajaran.id_siswa', $filters['id_siswa']);
        }
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('siswa.nama_lengkap', $filters['search'])
                ->orLike('siswa.nis', $filters['search'])
                ->orLike('materi_kaidah.judul_kaidah', $filters['search'])
            ->groupEnd();
        }

        $data = $builder->orderBy('sesi_pembelajaran.waktu_mulai', 'DESC')
                       ->paginate($perPage, 'default', $page);

        return [
            'data' => $data,
            'total' => $this->countAllResults($builder),
            'perPage' => $perPage,
            'currentPage' => $page
        ];
    }
}