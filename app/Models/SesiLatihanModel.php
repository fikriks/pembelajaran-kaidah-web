<?php

namespace App\Models;

use CodeIgniter\Model;

class SesiLatihanModel extends Model
{
    protected $table            = 'sesi_latihan';
    protected $primaryKey       = 'id_sesi';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_siswa',
        'id_bab',
        'id_materi',
        'seed_digunakan',
        'total_soal',
        'soal_benar',
        'skor',
        'waktu_mulai',
        'waktu_selesai',
        'durasi_detik',
        'status',
        'waktu_dibuat'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'waktu_dibuat';

    // Validation
    protected $validationRules      = [
        'id_siswa'          => 'required|integer|greater_than[0]',
        'id_materi'         => 'required|integer|greater_than[0]',
        'seed_digunakan'    => 'required|integer',
        'total_soal'        => 'required|integer|greater_than[0]',
        'soal_benar'        => 'permit_empty|integer|greater_than_equal_to[0]',
        'skor'              => 'permit_empty|numeric|greater_than_equal_to[0]',
        'waktu_mulai'       => 'required|valid_date[Y-m-d H:i:s]',
        'waktu_selesai'     => 'permit_empty|valid_date[Y-m-d H:i:s]',
        'durasi_detik'      => 'permit_empty|integer|greater_than_equal_to[0]',
        'status'            => 'required|in_list[sedang_berjalan,selesai]'
    ];
    protected $validationMessages   = [
        'id_siswa' => [
            'required'      => 'ID siswa harus diisi',
            'integer'       => 'ID siswa harus berupa angka',
            'greater_than'  => 'ID siswa tidak valid'
        ],
        'id_materi' => [
            'required'      => 'Materi harus dipilih',
            'integer'       => 'ID materi harus berupa angka',
            'greater_than'  => 'ID materi tidak valid'
        ],
        'seed_digunakan' => [
            'required'      => 'Seed harus diisi',
            'integer'       => 'Seed harus berupa angka'
        ],
        'total_soal' => [
            'required'      => 'Total soal harus diisi',
            'integer'       => 'Total soal harus berupa angka',
            'greater_than'  => 'Total soal harus lebih dari 0'
        ],
        'soal_benar' => [
            'integer'       => 'Jumlah soal benar harus berupa angka',
            'greater_than_equal_to' => 'Jumlah soal benar tidak boleh negatif'
        ],
        'skor' => [
            'numeric'       => 'Skor harus berupa angka',
            'greater_than_equal_to' => 'Skor tidak boleh negatif'
        ],
        'waktu_mulai' => [
            'required'      => 'Waktu mulai harus diisi',
            'valid_date'    => 'Format waktu mulai tidak valid'
        ],
        'waktu_selesai' => [
            'valid_date'    => 'Format waktu selesai tidak valid'
        ],
        'durasi_detik' => [
            'integer'       => 'Durasi harus berupa angka',
            'greater_than_equal_to' => 'Durasi tidak boleh negatif'
        ],
        'status' => [
            'required'      => 'Status harus dipilih',
            'in_list'       => 'Status tidak valid'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setTimestamp'];
    protected $beforeUpdate   = ['calculateScore'];

    protected function setTimestamp(array $data)
    {
        $data['data']['waktu_dibuat'] = date('Y-m-d H:i:s');
        return $data;
    }

    protected function calculateScore(array $data)
    {
        // Hitung skor berdasarkan jumlah soal benar jika ada perubahan
        if (isset($data['data']['soal_benar']) && isset($data['data']['total_soal'])) {
            $data['data']['skor'] = ($data['data']['soal_benar'] / $data['data']['total_soal']) * 100;
        }

        // Hitung durasi jika waktu selesai diisi
        if (isset($data['data']['waktu_selesai']) && isset($data['data']['waktu_mulai'])) {
            $waktu_mulai = strtotime($data['data']['waktu_mulai']);
            $waktu_selesai = strtotime($data['data']['waktu_selesai']);
            $data['data']['durasi_detik'] = $waktu_selesai - $waktu_mulai;
        }

        return $data;
    }

    // Custom methods
    public function startSession($id_siswa, $id_materi, $total_soal = 20)
    {
        // Generate seed berdasarkan timestamp + user_id + materi_id
        $seed = time() + $id_siswa + $id_materi;

        $data = [
            'id_siswa'       => $id_siswa,
            'id_materi'      => $id_materi,
            'seed_digunakan' => $seed,
            'total_soal'     => $total_soal,
            'soal_benar'     => 0,
            'skor'           => 0.00,
            'waktu_mulai'    => date('Y-m-d H:i:s'),
            'status'         => 'sedang_berjalan'
        ];

        return $this->insert($data);
    }

    public function finishSession($id_sesi, $soal_benar)
    {
        $sesi = $this->find($id_sesi);
        if (!$sesi) {
            return false;
        }

        $data = [
            'soal_benar'     => $soal_benar,
            'skor'           => ($soal_benar / $sesi['total_soal']) * 100,
            'waktu_selesai'  => date('Y-m-d H:i:s'),
            'status'         => 'selesai'
        ];

        return $this->update($id_sesi, $data);
    }

    public function getActiveSession($id_siswa, $id_materi)
    {
        return $this->where('id_siswa', $id_siswa)
                     ->where('id_materi', $id_materi)
                     ->where('status', 'sedang_berjalan')
                     ->first();
    }

    public function getWithDetails($id_sesi)
    {
        return $this->select('sesi_latihan.*, materi_kaidah.judul_kaidah, materi_kaidah.tingkat_kesulitan')
                     ->join('materi_kaidah', 'materi_kaidah.id_materi = sesi_latihan.id_materi')
                     ->where('sesi_latihan.id_sesi', $id_sesi)
                     ->first();
    }

    public function getByStudent($id_siswa, $limit = null)
    {
        $builder = $this->select('sesi_latihan.*, materi_kaidah.judul_kaidah')
                        ->join('materi_kaidah', 'materi_kaidah.id_materi = sesi_latihan.id_materi')
                        ->where('sesi_latihan.id_siswa', $id_siswa)
                        ->orderBy('sesi_latihan.waktu_mulai', 'DESC');

        if ($limit) {
            $builder = $builder->limit($limit);
        }

        return $builder->findAll();
    }

    public function getByMateri($id_materi, $limit = null)
    {
        $builder = $this->where('id_materi', $id_materi)
                        ->orderBy('waktu_mulai', 'DESC');

        if ($limit) {
            $builder = $builder->limit($limit);
        }

        return $builder->findAll();
    }

    public function getStats($id_siswa = null, $id_materi = null)
    {
        $builder = $this->select('COUNT(*) as total_sesi, AVG(skor) as rata_rata_skor, MAX(skor) as skor_tertinggi, COUNT(CASE WHEN status = "selesai" THEN 1 END) as sesi_selesai')
                        ->where('status', 'selesai');

        if ($id_siswa) {
            $builder = $builder->where('id_siswa', $id_siswa);
        }

        if ($id_materi) {
            $builder = $builder->where('id_materi', $id_materi);
        }

        return $builder->first();
    }

    public function getProgressOverTime($id_siswa, $days = 30)
    {
        $startDate = date('Y-m-d', strtotime("-{$days} days"));

        return $this->select('DATE(waktu_mulai) as tanggal, AVG(skor) as rata_rata_skor, COUNT(*) as total_sesi')
                     ->where('id_siswa', $id_siswa)
                     ->where('status', 'selesai')
                     ->where('waktu_mulai >=', $startDate)
                     ->groupBy('DATE(waktu_mulai)')
                     ->orderBy('tanggal', 'ASC')
                     ->findAll();
    }

    public function getLeaderboard($id_materi = null, $limit = 10)
    {
        $builder = $this->select('id_siswa, MAX(skor) as skor_tertinggi, AVG(skor) as rata_rata_skor, COUNT(*) as total_sesi')
                        ->where('status', 'selesai')
                        ->groupBy('id_siswa')
                        ->orderBy('skor_tertinggi', 'DESC')
                        ->orderBy('rata_rata_skor', 'DESC')
                        ->limit($limit);

        if ($id_materi) {
            $builder = $builder->where('id_materi', $id_materi);
        }

        return $builder->findAll();
    }

    public function getStatsByMateri($id_materi)
    {
        return $this->select('COUNT(*) as total_soal, AVG(skor) as rata_rata_poin, MIN(skor) as poin_terendah, MAX(skor) as poin_tertinggi')
                    ->where('id_materi', $id_materi)
                    ->where('status', 'selesai')
                    ->first();
    }
}