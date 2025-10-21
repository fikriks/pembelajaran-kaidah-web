<?php

namespace App\Models;

use CodeIgniter\Model;

class MateriKaidahModel extends Model
{
    protected $table            = 'materi_kaidah';
    protected $primaryKey       = 'id_materi';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'judul_kaidah',
        'deskripsi',
        'penjelasan',
        'contoh',
        'tingkat_kesulitan',
        'urutan',
        'dibuat_oleh'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'waktu_dibuat';
    protected $updatedField  = 'waktu_diubah';

    // Validation
    protected $validationRules      = [
        'judul_kaidah'      => 'required|min_length[3]|max_length[255]|is_unique[materi_kaidah.judul_kaidah,id_materi,{id_materi}]',
        'deskripsi'         => 'required|max_length[500]',
        'penjelasan'        => 'required',
        'contoh'            => 'required',
        'tingkat_kesulitan' => 'required|in_list[mudah,sedang,sulit]',
        'urutan'            => 'required|integer|greater_than_equal_to[1]',
        'dibuat_oleh'       => 'required|integer|greater_than[0]'
    ];
    protected $validationMessages   = [
        'judul_kaidah' => [
            'required'      => 'Judul kaidah harus diisi',
            'min_length'    => 'Judul kaidah minimal 3 karakter',
            'max_length'    => 'Judul kaidah maksimal 255 karakter',
            'is_unique'     => 'Judul kaidah sudah digunakan'
        ],
        'deskripsi' => [
            'required'      => 'Deskripsi harus diisi',
            'max_length'    => 'Deskripsi maksimal 500 karakter'
        ],
        'penjelasan' => [
            'required'      => 'Penjelasan harus diisi'
        ],
        'contoh' => [
            'required'      => 'Contoh harus diisi'
        ],
        'tingkat_kesulitan' => [
            'required'      => 'Tingkat kesulitan harus dipilih',
            'in_list'       => 'Tingkat kesulitan tidak valid'
        ],
        'urutan' => [
            'required'      => 'Urutan harus diisi',
            'integer'       => 'Urutan harus berupa angka',
            'greater_than_equal_to' => 'Urutan minimal 1'
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
    public function getWithCreator()
    {
        return $this->select('materi_kaidah.*, pengguna.nama_lengkap as nama_pembuat')
                     ->join('pengguna', 'pengguna.id_pengguna = materi_kaidah.dibuat_oleh')
                     ->orderBy('materi_kaidah.urutan', 'ASC')
                     ->findAll();
    }

    public function getByDifficulty($tingkat_kesulitan)
    {
        return $this->where('tingkat_kesulitan', $tingkat_kesulitan)
                     ->orderBy('urutan', 'ASC')
                     ->findAll();
    }

    public function getByCreator($id_pembuat)
    {
        return $this->where('dibuat_oleh', $id_pembuat)
                     ->orderBy('urutan', 'ASC')
                     ->findAll();
    }

    public function getWithStats()
    {
        return $this->select('materi_kaidah.*, COUNT(DISTINCT soal.id_soal) as total_soal, COUNT(DISTINCT pilihan_jawaban.id_pilihan) as total_jawaban')
                     ->join('soal', 'soal.id_materi = materi_kaidah.id_materi', 'left')
                     ->join('pilihan_jawaban', 'pilihan_jawaban.id_soal = soal.id_soal', 'left')
                     ->groupBy('materi_kaidah.id_materi')
                     ->orderBy('materi_kaidah.urutan', 'ASC')
                     ->findAll();
    }

    public function getNextOrder()
    {
        $result = $this->selectMax('urutan')->first();
        return ($result && $result['urutan']) ? $result['urutan'] + 1 : 1;
    }

    public function reorder($orderData)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($orderData as $order => $id_materi) {
            $this->update($id_materi, ['urutan' => $order + 1]);
        }

        $db->transComplete();
        return $db->transStatus();
    }

    public function search($keyword)
    {
        return $this->like('judul_kaidah', $keyword)
                     ->orLike('deskripsi', $keyword)
                     ->orLike('penjelasan', $keyword)
                     ->orderBy('urutan', 'ASC')
                     ->findAll();
    }

    public function getForDropdown()
    {
        return $this->select('id_materi, judul_kaidah, tingkat_kesulitan')
                     ->orderBy('urutan', 'ASC')
                     ->findAll();
    }
}