<?php

namespace App\Models;

use CodeIgniter\Model;

class SoalModel extends Model
{
    protected $table            = 'soal';
    protected $primaryKey       = 'id_soal';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_materi',
        'pertanyaan',
        'tipe_soal',
        'tingkat_kesulitan',
        'poin',
        'dibuat_oleh'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'waktu_dibuat';
    protected $updatedField  = 'waktu_diubah';

    // Validation
    protected $validationRules      = [
        'id_materi'          => 'required|integer|greater_than[0]',
        'pertanyaan'         => 'required',
        'tipe_soal'          => 'required|in_list[pilihan_ganda]',
        'tingkat_kesulitan'  => 'required|in_list[mudah,sedang,sulit]',
        'poin'               => 'required|integer|greater_than[0]',
        'dibuat_oleh'        => 'required|integer|greater_than[0]'
    ];
    protected $validationMessages   = [
        'id_materi' => [
            'required'      => 'Materi kaidah harus dipilih',
            'integer'       => 'ID materi harus berupa angka',
            'greater_than'  => 'ID materi tidak valid'
        ],
        'pertanyaan' => [
            'required'      => 'Pertanyaan harus diisi'
        ],
        'tipe_soal' => [
            'required'      => 'Tipe soal harus dipilih',
            'in_list'       => 'Tipe soal tidak valid'
        ],
        'tingkat_kesulitan' => [
            'required'      => 'Tingkat kesulitan harus dipilih',
            'in_list'       => 'Tingkat kesulitan tidak valid'
        ],
        'poin' => [
            'required'      => 'Poin harus diisi',
            'integer'       => 'Poin harus berupa angka',
            'greater_than'  => 'Poin harus lebih dari 0'
        ],
        'dibuat_oleh' => [
            'required'      => 'Pembuat harus dipilih',
            'integer'       => 'ID pembuat harus berupa angka',
            'greater_than'  => 'ID pembuat tidak valid'
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
    public function getWithMateri()
    {
        return $this->select('soal.*, materi_kaidah.judul_kaidah, materi_kaidah.tingkat_kesulitan as tingkat_kesulitan_materi, pengguna.nama_lengkap as nama_pembuat')
                     ->join('materi_kaidah', 'materi_kaidah.id_materi = soal.id_materi')
                     ->join('pengguna', 'pengguna.id_pengguna = soal.dibuat_oleh')
                     ->orderBy('materi_kaidah.urutan', 'ASC')
                     ->orderBy('soal.id_soal', 'ASC')
                     ->findAll();
    }

    public function getByMateri($id_materi)
    {
        return $this->where('id_materi', $id_materi)
                     ->orderBy('id_soal', 'ASC')
                     ->findAll();
    }

    public function getByDifficulty($tingkat_kesulitan)
    {
        return $this->where('tingkat_kesulitan', $tingkat_kesulitan)
                     ->orderBy('id_soal', 'ASC')
                     ->findAll();
    }

    public function getByCreator($id_pembuat)
    {
        return $this->where('dibuat_oleh', $id_pembuat)
                     ->orderBy('id_soal', 'ASC')
                     ->findAll();
    }

    public function getWithAnswers($id_soal = null)
    {
        $builder = $this->select('soal.*, materi_kaidah.judul_kaidah, pilihan_jawaban.*')
                        ->join('materi_kaidah', 'materi_kaidah.id_materi = soal.id_materi')
                        ->join('pilihan_jawaban', 'pilihan_jawaban.id_soal = soal.id_soal')
                        ->orderBy('soal.id_soal', 'ASC')
                        ->orderBy('pilihan_jawaban.urutan', 'ASC');

        if ($id_soal) {
            $builder = $builder->where('soal.id_soal', $id_soal);
        }

        return $builder->findAll();
    }

    public function getWithCorrectAnswer($id_soal = null)
    {
        $builder = $this->select('soal.*, materi_kaidah.judul_kaidah, pilihan_jawaban.teks_jawaban as jawaban_benar')
                        ->join('materi_kaidah', 'materi_kaidah.id_materi = soal.id_materi')
                        ->join('pilihan_jawaban', 'pilihan_jawaban.id_soal = soal.id_soal AND pilihan_jawaban.is_benar = 1')
                        ->orderBy('soal.id_soal', 'ASC');

        if ($id_soal) {
            $builder = $builder->where('soal.id_soal', $id_soal);
        }

        return $builder->findAll();
    }

    public function search($keyword, $id_materi = null)
    {
        $builder = $this->like('pertanyaan', $keyword);

        if ($id_materi) {
            $builder = $builder->where('id_materi', $id_materi);
        }

        return $builder->orderBy('id_soal', 'ASC')->findAll();
    }

    public function getRandomQuestions($id_materi, $jumlah = 10)
    {
        return $this->where('id_materi', $id_materi)
                     ->orderBy('RAND()')
                     ->limit($jumlah)
                     ->findAll();
    }

    public function getStats()
    {
        return $this->select('COUNT(*) as total_soal, AVG(poin) as rata_rata_poin')
                     ->first();
    }

    public function getStatsByMateri($id_materi)
    {
        return $this->select('COUNT(*) as total_soal, AVG(poin) as rata_rata_poin, MAX(poin) as poin_tertinggi, MIN(poin) as poin_terendah')
                     ->where('id_materi', $id_materi)
                     ->first();
    }
}