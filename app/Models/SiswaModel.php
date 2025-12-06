<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table            = 'siswa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nis', 'nama_lengkap', 'kata_sandi', 'jenis_kelamin', 'kelas', 'status'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'waktu_dibuat';
    protected $updatedField  = 'waktu_diubah';

    // Validation
    protected $validationRules      = [
        'nis' => 'required|min_length[5]|max_length[20]',
        'nama_lengkap' => 'required|min_length[3]|max_length[100]',
        'kata_sandi' => 'required|min_length[6]',
        'jenis_kelamin' => 'required|in_list[L,P]',
        'kelas' => 'required|max_length[10]',
        'status' => 'required|in_list[AKTIF,NONAKTIF]'
    ];

    protected $validationMessages   = [
        'nis' => [
            'required' => 'NIS wajib diisi',
            'min_length' => 'NIS minimal 5 karakter',
            'max_length' => 'NIS maksimal 20 karakter'
        ],
        'nama_lengkap' => [
            'required' => 'Nama lengkap wajib diisi',
            'min_length' => 'Nama minimal 3 karakter',
            'max_length' => 'Nama maksimal 100 karakter'
        ],
        'kata_sandi' => [
            'required' => 'Kata sandi wajib diisi',
            'min_length' => 'Kata sandi minimal 6 karakter'
        ],
        'jenis_kelamin' => [
            'required' => 'Jenis kelamin wajib dipilih',
            'in_list' => 'Jenis kelamin harus L atau P'
        ],
        'kelas' => [
            'required' => 'Kelas wajib diisi',
            'max_length' => 'Kelas maksimal 10 karakter'
        ],
        'status' => [
            'required' => 'Status wajib dipilih',
            'in_list' => 'Status harus AKTIF atau NONAKTIF'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['hashPassword'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    // Hash password before saving
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['kata_sandi'])) {
            $data['data']['kata_sandi'] = password_hash($data['data']['kata_sandi'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    // Get siswa by NIS for login
    public function getSiswaByNis($nis)
    {
        return $this->where('nis', $nis)->where('status', 'AKTIF')->first();
    }

    // Verify password
    public function verifyPassword($nis, $password)
    {
        $siswa = $this->getSiswaByNis($nis);

        if ($siswa && password_verify($password, $siswa['kata_sandi'])) {
            return $siswa;
        }

        return false;
    }

    // Get statistics
    public function getStatistics()
    {
        return [
            'total' => $this->countAllResults(),
            'aktif' => $this->where('status', 'AKTIF')->countAllResults(),
            'nonaktif' => $this->where('status', 'NONAKTIF')->countAllResults(),
        ];
    }

    // Get siswa by kelas
    public function getByKelas($kelas)
    {
        return $this->where('kelas', $kelas)->findAll();
    }

    // Search siswa
    public function search($keyword)
    {
        return $this->like('nis', $keyword)
                   ->orLike('nama_lengkap', $keyword)
                   ->findAll();
    }

    // Generate default password for new siswa
    public function generateRandomPassword($length = 8)
    {
        // Return default password for all new siswa (keeping method name for compatibility)
        return '123456789';
    }

    // Alternative method name for clarity
    public function getDefaultPassword()
    {
        return '123456789';
    }

    // Get siswa with pagination
    public function getSiswaPaginated($perPage = 10, $page = 1, $search = '', $kelas = '')
    {
        $builder = $this->builder();

        if (!empty($search)) {
            $builder->like('nis', $search)
                   ->orLike('nama_lengkap', $search);
        }

        if (!empty($kelas)) {
            $builder->where('kelas', $kelas);
        }

        $offset = ($page - 1) * $perPage;

        // Get total first before applying limit
        $total = $builder->countAllResults();

        return [
            'data' => $builder->get($perPage, $offset)->getResultArray(),
            'total' => $total,
            'perPage' => $perPage,
            'currentPage' => $page
        ];
    }

    public function getAllSiswa($search = '', $kelas = '')
    {
        $builder = $this->builder();

        if (!empty($search)) {
            $builder->like('nis', $search)
                   ->orLike('nama_lengkap', $search);
        }

        if (!empty($kelas)) {
            $builder->where('kelas', $kelas);
        }

        return $builder->get()->getResultArray();
    }
}
