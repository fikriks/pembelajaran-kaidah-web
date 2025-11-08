<?php

namespace App\Models;

use CodeIgniter\Model;

class BabModel extends Model
{
    protected $table            = 'bab';
    protected $primaryKey       = 'id_bab';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_bab',
        'deskripsi',
        'urutan',
        'is_active'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'waktu_dibuat';
    protected $updatedField  = 'waktu_diubah';

    // Validation
    protected $validationRules      = [
        'nama_bab' => 'required|min_length[3]|max_length[100]|is_unique[bab.nama_bab,id_bab,{id_bab}]',
        'deskripsi' => 'max_length[1000]',
        'urutan' => 'required|integer|greater_than_equal_to[1]|is_unique[bab.urutan,id_bab,{id_bab}]',
        'is_active' => 'required|in_list[0,1]'
    ];
    protected $validationMessages   = [
        'nama_bab' => [
            'required' => 'Nama bab harus diisi',
            'min_length' => 'Nama bab minimal 3 karakter',
            'max_length' => 'Nama bab maksimal 100 karakter',
            'is_unique' => 'Nama bab sudah digunakan'
        ],
        'deskripsi' => [
            'max_length' => 'Deskripsi maksimal 1000 karakter'
        ],
        'urutan' => [
            'required' => 'Urutan harus diisi',
            'integer' => 'Urutan harus berupa angka',
            'greater_than_equal_to' => 'Urutan minimal 1',
            'is_unique' => 'Urutan sudah digunakan'
        ],
        'is_active' => [
            'required' => 'Status harus dipilih',
            'in_list' => 'Status tidak valid'
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
    public function getActive()
    {
        return $this->where('is_active', 1)
                     ->orderBy('urutan', 'ASC')
                     ->findAll();
    }

    public function getAllWithStats()
    {
        $builder = $this->select('bab.*, COUNT(DISTINCT mk.id_materi) as total_materi, COUNT(DISTINCT s.id_soal) as total_soal')
                         ->join('materi_kaidah mk', 'mk.id_bab = bab.id_bab', 'left')
                         ->join('soal s', 's.id_bab = bab.id_bab', 'left')
                         ->groupBy('bab.id_bab')
                         ->orderBy('bab.urutan', 'ASC');

        return $builder->findAll();
    }

    public function getByOrder($order)
    {
        return $this->where('urutan', $order)->first();
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

        foreach ($orderData as $order => $id_bab) {
            $this->update($id_bab, ['urutan' => $order + 1]);
        }

        $db->transComplete();
        return $db->transStatus();
    }

    public function toggleStatus($id_bab)
    {
        $bab = $this->find($id_bab);
        if (!$bab) {
            return false;
        }

        $newStatus = $bab['is_active'] ? 0 : 1;
        return $this->update($id_bab, ['is_active' => $newStatus]);
    }

    public function search($keyword)
    {
        return $this->like('nama_bab', $keyword)
                     ->orLike('deskripsi', $keyword)
                     ->orderBy('urutan', 'ASC')
                     ->findAll();
    }

    public function getForDropdown()
    {
        return $this->select('id_bab, nama_bab')
                     ->where('is_active', 1)
                     ->orderBy('urutan', 'ASC')
                     ->findAll();
    }

    // API methods
    public function getBabWithStats($limit = 20, $page = 1)
    {
        $builder = $this->select('bab.*, COUNT(DISTINCT mk.id_materi) as total_materi, COUNT(DISTINCT s.id_soal) as total_soal')
                         ->join('materi_kaidah mk', 'mk.id_bab = bab.id_bab', 'left')
                         ->join('soal s', 's.id_bab = bab.id_bab', 'left')
                         ->groupBy('bab.id_bab')
                         ->orderBy('bab.urutan', 'ASC');

        $offset = ($page - 1) * $limit;
        $result = $builder->findAll($limit, $offset);
        $total = $this->countAllResults();

        return [
            'data' => $result,
            'current_page' => $page,
            'per_page' => $limit,
            'total' => $total
        ];
    }

    public function searchBab($keyword, $limit = 10, $page = 1)
    {
        $builder = $this->like('nama_bab', $keyword)
                         ->orLike('deskripsi', $keyword)
                         ->orderBy('urutan', 'ASC');

        $offset = ($page - 1) * $limit;
        $result = $builder->findAll($limit, $offset);
        $total = $this->countAllResults();

        return [
            'data' => $result,
            'current_page' => $page,
            'per_page' => $limit,
            'total' => $total
        ];
    }

    public function getStatistics()
    {
        $total = $this->countAll();
        $active = $this->where('is_active', 1)->countAllResults();
        $inactive = $total - $active;

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive
        ];
    }
}