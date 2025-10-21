<?php

namespace App\Models;

use CodeIgniter\Model;

class PenggunaModel extends Model
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
        'email',
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
        'email'         => 'required|min_length[5]|max_length[100]|valid_email|is_unique[pengguna.email,id_pengguna,{id_pengguna}]',
        'nama_lengkap'  => 'required|min_length[3]|max_length[100]',
        'hak_akses'     => 'required|in_list[admin,guru]',
        'status'        => 'required|in_list[aktif,nonaktif]'
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
        'email' => [
            'required'      => 'Email harus diisi',
            'min_length'    => 'Email minimal 5 karakter',
            'max_length'    => 'Email maksimal 100 karakter',
            'valid_email'   => 'Format email tidak valid',
            'is_unique'     => 'Email sudah digunakan'
        ],
        'nama_lengkap' => [
            'required'      => 'Nama lengkap harus diisi',
            'min_length'    => 'Nama lengkap minimal 3 karakter',
            'max_length'    => 'Nama lengkap maksimal 100 karakter'
        ],
        'hak_akses' => [
            'required'      => 'Hak akses harus dipilih',
            'in_list'       => 'Hak akses tidak valid'
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
    protected $beforeInsert   = ['hashPassword', 'setTimestamps'];
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

    protected function getPasswordHash($id)
    {
        $user = $this->find($id);
        return $user ? $user['kata_sandi'] : null;
    }

    // Authentication methods
    public function authenticate($nama_pengguna, $kata_sandi)
    {
        $user = $this->where('nama_pengguna', $nama_pengguna)
                     ->where('status', 'aktif')
                     ->first();

        if ($user && password_verify($kata_sandi, $user['kata_sandi'])) {
            return $user;
        }

        return null;
    }

    public function getByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function getActiveUsers($hak_akses = null)
    {
        $builder = $this->where('status', 'aktif');

        if ($hak_akses) {
            $builder = $builder->where('hak_akses', $hak_akses);
        }

        return $builder->findAll();
    }

    public function getUsersWithStats()
    {
        return $this->select('pengguna.*, COUNT(DISTINCT materi_kaidah.id_materi) as total_materi, COUNT(DISTINCT soal.id_soal) as total_soal')
                     ->join('materi_kaidah', 'materi_kaidah.dibuat_oleh = pengguna.id_pengguna', 'left')
                     ->join('soal', 'soal.dibuat_oleh = pengguna.id_pengguna', 'left')
                     ->groupBy('pengguna.id_pengguna')
                     ->findAll();
    }
}