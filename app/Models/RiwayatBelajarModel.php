<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatBelajarModel extends Model
{
    protected $table            = 'riwayat_belajar';
    protected $primaryKey       = 'id_riwayat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_siswa',
        'id_materi',
        'status',
        'persentase_penguasaan',
        'waktu_akses_terakhir'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'waktu_dibuat';
    protected $updatedField  = 'waktu_diubah';

    // Validation
    protected $validationRules      = [
        'id_siswa'               => 'required|integer|greater_than[0]',
        'id_materi'              => 'required|integer|greater_than[0]',
        'status'                 => 'required|in_list[belum_dimulai,sedang_belajar,selesai]',
        'persentase_penguasaan'  => 'permit_empty|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
        'waktu_akses_terakhir'   => 'permit_empty|valid_date[Y-m-d H:i:s]'
    ];
    protected $validationMessages   = [
        'id_siswa' => [
            'required'      => 'ID siswa harus diisi',
            'integer'       => 'ID siswa harus berupa angka',
            'greater_than'  => 'ID siswa tidak valid'
        ],
        'id_materi' => [
            'required'      => 'ID materi harus diisi',
            'integer'       => 'ID materi harus berupa angka',
            'greater_than'  => 'ID materi tidak valid'
        ],
        'status' => [
            'required'      => 'Status harus dipilih',
            'in_list'       => 'Status tidak valid'
        ],
        'persentase_penguasaan' => [
            'numeric'       => 'Persentase penguasaan harus berupa angka',
            'greater_than_equal_to' => 'Persentase penguasaan tidak boleh kurang dari 0',
            'less_than_equal_to' => 'Persentase penguasaan tidak boleh lebih dari 100'
        ],
        'waktu_akses_terakhir' => [
            'valid_date'    => 'Format waktu akses terakhir tidak valid'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setTimestamps'];
    protected $beforeUpdate   = ['setTimestamps'];

    protected function setTimestamps(array $data)
    {
        $currentDateTime = date('Y-m-d H:i:s');
        $data['data']['waktu_dibuat'] = $currentDateTime;
        $data['data']['waktu_diubah'] = $currentDateTime;
        return $data;
    }

    // Custom methods
    public function getOrCreate($id_siswa, $id_materi)
    {
        $riwayat = $this->where('id_siswa', $id_siswa)
                       ->where('id_materi', $id_materi)
                       ->first();

        if ($riwayat) {
            return $riwayat['id_riwayat'];
        }

        // Create new riwayat
        $data = [
            'id_siswa'              => $id_siswa,
            'id_materi'             => $id_materi,
            'status'                => 'belum_dimulai',
            'persentase_penguasaan' => 0.00,
            'waktu_akses_terakhir'  => null
        ];

        return $this->insert($data);
    }

    public function startLearning($id_siswa, $id_materi)
    {
        $id_riwayat = $this->getOrCreate($id_siswa, $id_materi);

        return $this->update($id_riwayat, [
            'status'                => 'sedang_belajar',
            'persentase_penguasaan' => 0.00,
            'waktu_akses_terakhir'  => date('Y-m-d H:i:s')
        ]);
    }

    public function updateProgress($id_siswa, $id_materi, $persentase_penguasaan)
    {
        $id_riwayat = $this->getOrCreate($id_siswa, $id_materi);

        $status = 'belum_dimulai';
        if ($persentase_penguasaan > 0 && $persentase_penguasaan < 100) {
            $status = 'sedang_belajar';
        } elseif ($persentase_penguasaan >= 100) {
            $status = 'selesai';
            $persentase_penguasaan = 100.00;
        }

        return $this->update($id_riwayat, [
            'status'                => $status,
            'persentase_penguasaan' => $persentase_penguasaan,
            'waktu_akses_terakhir'  => date('Y-m-d H:i:s')
        ]);
    }

    public function finishLearning($id_siswa, $id_materi)
    {
        $id_riwayat = $this->getOrCreate($id_siswa, $id_materi);

        return $this->update($id_riwayat, [
            'status'                => 'selesai',
            'persentase_penguasaan' => 100.00,
            'waktu_akses_terakhir'  => date('Y-m-d H:i:s')
        ]);
    }

    public function getStudentProgress($id_siswa)
    {
        return $this->select('riwayat_belajar.*, materi_kaidah.judul_kaidah, materi_kaidah.tingkat_kesulitan, materi_kaidah.urutan')
                     ->join('materi_kaidah', 'materi_kaidah.id_materi = riwayat_belajar.id_materi')
                     ->where('riwayat_belajar.id_siswa', $id_siswa)
                     ->orderBy('materi_kaidah.urutan', 'ASC')
                     ->findAll();
    }

    public function getWithMateri($id_siswa = null, $status = null)
    {
        $builder = $this->select('riwayat_belajar.*, materi_kaidah.judul_kaidah, materi_kaidah.tingkat_kesulitan, materi_kaidah.urutan')
                        ->join('materi_kaidah', 'materi_kaidah.id_materi = riwayat_belajar.id_materi')
                        ->orderBy('materi_kaidah.urutan', 'ASC');

        if ($id_siswa) {
            $builder = $builder->where('riwayat_belajar.id_siswa', $id_siswa);
        }

        if ($status) {
            $builder = $builder->where('riwayat_belajar.status', $status);
        }

        return $builder->findAll();
    }

    public function getStats($id_siswa)
    {
        return $this->select('COUNT(*) as total_materi,
                                  SUM(CASE WHEN status = "selesai" THEN 1 ELSE 0 END) as materi_selesai,
                                  SUM(CASE WHEN status = "sedang_belajar" THEN 1 ELSE 0 END) as materi_dipelajari,
                                  SUM(CASE WHEN status = "belum_dimulai" THEN 1 ELSE 0 END) as materi_belum,
                                  AVG(persentase_penguasaan) as rata_rata_penguasaan')
                     ->where('id_siswa', $id_siswa)
                     ->first();
    }

    public function getOverallStats()
    {
        return $this->select('COUNT(*) as total_riwayat,
                                  COUNT(DISTINCT id_siswa) as total_siswa,
                                  COUNT(DISTINCT id_materi) as total_materi_unik,
                                  AVG(persentase_penguasaan) as rata_rata_penguasaan,
                                  SUM(CASE WHEN status = "selesai" THEN 1 ELSE 0 END) as total_selesai')
                     ->first();
    }

    public function getProgressByMateri($id_materi)
    {
        return $this->select('COUNT(*) as total_siswa,
                                  SUM(CASE WHEN status = "selesai" THEN 1 ELSE 0 END) as siswa_selesai,
                                  SUM(CASE WHEN status = "sedang_belajar" THEN 1 ELSE 0 END) as siswa_belajar,
                                  SUM(CASE WHEN status = "belum_dimulai" THEN 1 ELSE 0 END) as siswa_belum,
                                  AVG(persentase_penguasaan) as rata_rata_penguasaan')
                     ->where('id_materi', $id_materi)
                     ->first();
    }

    public function getMostLearnedMaterials($limit = 10)
    {
        return $this->select('materi_kaidah.id_materi, materi_kaidah.judul_kaidah, COUNT(*) as total_siswa, AVG(persentase_penguasaan) as rata_rata_penguasaan')
                     ->join('materi_kaidah', 'materi_kaidah.id_materi = riwayat_belajar.id_materi')
                     ->groupBy('riwayat_belajar.id_materi')
                     ->orderBy('total_siswa', 'DESC')
                     ->limit($limit)
                     ->findAll();
    }

    public function getLeastLearnedMaterials($limit = 10)
    {
        return $this->select('materi_kaidah.id_materi, materi_kaidah.judul_kaidah, COUNT(*) as total_siswa, AVG(persentase_penguasaan) as rata_rata_penguasaan')
                     ->join('materi_kaidah', 'materi_kaidah.id_materi = riwayat_belajar.id_materi')
                     ->groupBy('riwayat_belajar.id_materi')
                     ->orderBy('total_siswa', 'ASC')
                     ->limit($limit)
                     ->findAll();
    }

    public function getRecentActivity($id_siswa = null, $limit = 10)
    {
        $builder = $this->select('riwayat_belajar.*, materi_kaidah.judul_kaidah')
                        ->join('materi_kaidah', 'materi_kaidah.id_materi = riwayat_belajar.id_materi')
                        ->orderBy('riwayat_belajar.waktu_akses_terakhir', 'DESC')
                        ->orderBy('riwayat_belajar.waktu_diubah', 'DESC')
                        ->limit($limit);

        if ($id_siswa) {
            $builder = $builder->where('riwayat_belajar.id_siswa', $id_siswa);
        }

        return $builder->findAll();
    }

    public function getLearningStreak($id_siswa)
    {
        // Get last 30 days of activity
        $startDate = date('Y-m-d', strtotime('-30 days'));

        $activities = $this->select('DATE(waktu_akses_terakhir) as tanggal, COUNT(*) as aktivitas')
                           ->where('id_siswa', $id_siswa)
                           ->where('waktu_akses_terakhir >=', $startDate)
                           ->groupBy('DATE(waktu_akses_terakhir)')
                           ->orderBy('tanggal', 'DESC')
                           ->findAll();

        if (empty($activities)) {
            return ['current_streak' => 0, 'longest_streak' => 0];
        }

        $currentStreak = 0;
        $longestStreak = 0;
        $tempStreak = 0;
        $lastDate = null;

        foreach ($activities as $activity) {
            if ($lastDate === null) {
                // First activity
                $tempStreak = 1;
            } else {
                // Check if consecutive day
                $current = strtotime($activity['tanggal']);
                $previous = strtotime($lastDate);
                $diffDays = ($previous - $current) / (60 * 60 * 24);

                if ($diffDays == 1) {
                    $tempStreak++;
                } else {
                    $tempStreak = 1;
                }
            }

            $lastDate = $activity['tanggal'];
            $longestStreak = max($longestStreak, $tempStreak);

            // If this is today or yesterday, it's part of current streak
            $dayDiff = (strtotime(date('Y-m-d')) - strtotime($activity['tanggal'])) / (60 * 60 * 24);
            if ($dayDiff <= 1) {
                $currentStreak = $tempStreak;
            }
        }

        return [
            'current_streak' => $currentStreak,
            'longest_streak' => $longestStreak
        ];
    }
}