<?php

namespace App\Models;

use CodeIgniter\Model;

class GuruModel extends Model
{
    protected $table            = 'pengguna';
    protected $primaryKey       = 'id_pengguna';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_pengguna',
        'kata_sandi',
        'nama_lengkap',
        'hak_akses',
        'foto_profil',
        'status'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'waktu_dibuat';
    protected $updatedField  = 'waktu_diubah';

    // Validation
    protected $validationRules      = [
        'nama_pengguna' => 'required|min_length[3]|max_length[50]|alpha_numeric_space|is_unique[pengguna.nama_pengguna,id_pengguna,{id_pengguna}]',
        'kata_sandi'    => 'required|min_length[6]',
        'nama_lengkap'  => 'required|min_length[3]|max_length[100]',
        'status'        => 'required|in_list[AKTIF,NONAKTIF]'
    ];
    protected $validationMessages   = [
        'nama_pengguna' => [
            'required'      => 'Nama pengguna harus diisi',
            'min_length'    => 'Nama pengguna minimal 3 karakter',
            'max_length'    => 'Nama pengguna maksimal 50 karakter',
            'alpha_numeric_space' => 'Nama pengguna hanya boleh mengandung huruf, angka, dan spasi',
            'is_unique'     => 'Nama pengguna sudah digunakan'
        ],
        'kata_sandi' => [
            'required'      => 'Kata sandi harus diisi',
            'min_length'    => 'Kata sandi minimal 6 karakter'
        ],
        'nama_lengkap' => [
            'required'      => 'Nama lengkap harus diisi',
            'min_length'    => 'Nama lengkap minimal 3 karakter',
            'max_length'    => 'Nama lengkap maksimal 100 karakter'
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
    protected $beforeInsert   = ['hashPassword', 'setTimestamps', 'setGuruRole'];
    protected $beforeUpdate   = ['hashPassword', 'setTimestamps'];

    // Custom methods
    protected function hashPassword(array $data)
    {
        if (!isset($data['data']['kata_sandi'])) {
            return $data;
        }

        // Hash password jika bukan update tanpa mengubah password
        if (isset($data['id']) && $data['data']['kata_sandi'] === $this->getPasswordHash($data['id'])) {
            return $data;
        }

        $data['data']['kata_sandi'] = password_hash($data['data']['kata_sandi'], PASSWORD_DEFAULT);
        return $data;
    }

    protected function setTimestamps(array $data)
    {
        $currentDateTime = date('Y-m-d H:i:s');

        if (!isset($data['id'])) {
            // Insert
            $data['data']['waktu_dibuat'] = $currentDateTime;
        }
        $data['data']['waktu_diubah'] = $currentDateTime;

        return $data;
    }

    protected function setGuruRole(array $data)
    {
        // Set default role sebagai GURU untuk insert baru
        if (!isset($data['id'])) {
            $data['data']['hak_akses'] = 'GURU';
        }

        return $data;
    }

    protected function getPasswordHash($id)
    {
        $user = $this->find($id);
        return $user ? $user['kata_sandi'] : null;
    }

    // Custom method untuk mendapatkan semua guru
    public function getAllGurus(?int $limit = null, int $offset = 0)
    {
        return $this->where('hak_akses', 'GURU')
                     ->orderBy('waktu_dibuat', 'DESC')
                     ->findAll($limit, $offset);
    }

    // Custom method untuk mencari guru berdasarkan ID
    public function getGuruById($id)
    {
        return $this->where('id_pengguna', $id)
                     ->where('hak_akses', 'GURU')
                     ->first();
    }

    // Custom method untuk menghitung total guru
    public function countAllGurus()
    {
        return $this->where('hak_akses', 'GURU')
                     ->countAllResults();
    }

    // Get active teachers only
    public function getActiveGurus()
    {
        return $this->where('hak_akses', 'GURU')
                     ->where('status', 'AKTIF')
                     ->orderBy('nama_lengkap', 'ASC')
                     ->findAll();
    }

    // Get non-active teachers
    public function getNonActiveGurus()
    {
        return $this->where('hak_akses', 'GURU')
                     ->where('status', 'NONAKTIF')
                     ->orderBy('waktu_diubah', 'DESC')
                     ->findAll();
    }

    // Get teachers with statistics (how many materials and questions they've created)
    public function getGurusWithStats()
    {
        return $this->select('pengguna.*, COUNT(DISTINCT materi_kaidah.id_materi) as total_materi, COUNT(DISTINCT soal.id_soal) as total_soal')
                     ->where('pengguna.hak_akses', 'GURU')
                     ->join('materi_kaidah', 'materi_kaidah.dibuat_oleh = pengguna.id_pengguna', 'left')
                     ->join('soal', 'soal.dibuat_oleh = pengguna.id_pengguna', 'left')
                     ->groupBy('pengguna.id_pengguna')
                     ->orderBy('pengguna.nama_lengkap', 'ASC')
                     ->findAll();
    }

    // Check if username exists for teachers only
    public function isUsernameExist($username, $excludeId = null)
    {
        $builder = $this->where('hak_akses', 'GURU')
                        ->where('nama_pengguna', $username);

        if ($excludeId) {
            $builder->where('id_pengguna !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    // Authentication for teachers
    public function authenticateGuru($nama_pengguna, $kata_sandi)
    {
        $user = $this->where('nama_pengguna', $nama_pengguna)
                     ->where('hak_akses', 'GURU')
                     ->where('status', 'AKTIF')
                     ->first();

        if ($user && password_verify($kata_sandi, $user['kata_sandi'])) {
            return $user;
        }

        return null;
    }
}