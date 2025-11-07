<?php

namespace App\Models;

use CodeIgniter\Model;

class KaidahModel extends Model
{
    protected $table = 'materi_kaidah';
    protected $primaryKey = 'id_materi';
    protected $allowedFields = [
        'judul_kaidah',
        'deskripsi',
        'penjelasan',
        'contoh',
        'urutan',
        'dibuat_oleh',
        'waktu_dibuat',
        'waktu_diubah'
    ];

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $validationRules = [
        'judul_kaidah' => 'required|min_length[3]|max_length[255]',
        'urutan' => 'required|integer|greater_than_equal_to[0]',
        'dibuat_oleh' => 'required|integer'
    ];
    protected $validationMessages = [
        'judul_kaidah' => [
            'required' => 'Judul kaidah harus diisi',
            'min_length' => 'Judul kaidah minimal 3 karakter',
            'max_length' => 'Judul kaidah maksimal 255 karakter'
        ],
        'urutan' => [
            'required' => 'Urutan harus diisi',
            'integer' => 'Urutan harus berupa angka',
            'greater_than_equal_to' => 'Urutan minimal 0'
        ],
        'dibuat_oleh' => [
            'required' => 'Pembuat harus dipilih',
            'integer' => 'ID pembuat tidak valid'
        ]
    ];

    // Fungsi untuk mendapatkan semua kaidah dengan relasi pengguna
    public function getAllKaidah()
    {
        return $this->select('materi_kaidah.*, pengguna.nama_lengkap as nama_pembuat')
                    ->join('pengguna', 'pengguna.id_pengguna = materi_kaidah.dibuat_oleh', 'left')
                    ->orderBy('materi_kaidah.urutan', 'ASC')
                    ->orderBy('materi_kaidah.id_materi', 'DESC')
                    ->findAll();
    }

    // Fungsi untuk mendapatkan kaidah dengan pagination
    public function getKaidahWithPagination($perPage = 10, $page = 1)
    {
        return $this->select('materi_kaidah.*, pengguna.nama_lengkap as nama_pembuat')
                    ->join('pengguna', 'pengguna.id_pengguna = materi_kaidah.dibuat_oleh', 'left')
                    ->orderBy('materi_kaidah.urutan', 'ASC')
                    ->orderBy('materi_kaidah.id_materi', 'DESC')
                    ->paginate($perPage, 'default', $page);
    }

    // Fungsi untuk mendapatkan kaidah berdasarkan ID
    public function getKaidahById($id)
    {
        return $this->select('materi_kaidah.*, pengguna.nama_lengkap as nama_pembuat')
                    ->join('pengguna', 'pengguna.id_pengguna = materi_kaidah.dibuat_oleh', 'left')
                    ->where('materi_kaidah.id_materi', $id)
                    ->first();
    }

    
    // Fungsi untuk mencari kaidah
    public function searchKaidah($keyword)
    {
        return $this->select('materi_kaidah.*, pengguna.nama_lengkap as nama_pembuat')
                    ->join('pengguna', 'pengguna.id_pengguna = materi_kaidah.dibuat_oleh', 'left')
                    ->groupStart()
                        ->like('materi_kaidah.judul_kaidah', $keyword)
                        ->orLike('materi_kaidah.deskripsi', $keyword)
                        ->orLike('materi_kaidah.penjelasan', $keyword)
                        ->orLike('materi_kaidah.contoh', $keyword)
                    ->groupEnd()
                    ->orderBy('materi_kaidah.urutan', 'ASC')
                    ->findAll();
    }

    // Fungsi untuk mendapatkan statistik kaidah
    public function getKaidahStatistics()
    {
        $total = $this->countAll();

        return [
            'total' => $total
        ];
    }

    // Fungsi untuk update kaidah dengan timestamp otomatis
    public function updateKaidah($id, $data)
    {
        $data['waktu_diubah'] = date('Y-m-d H:i:s');
        return $this->update($id, $data);
    }

    // Fungsi untuk insert kaidah dengan timestamp otomatis
    public function insertKaidah($data)
    {
        $data['waktu_dibuat'] = date('Y-m-d H:i:s');
        $data['waktu_diubah'] = date('Y-m-d H:i:s');
        return $this->insert($data);
    }

    // Fungsi untuk mendapatkan urutan terakhir
    public function getLastOrder()
    {
        $result = $this->selectMax('urutan')->first();
        return $result ? $result['urutan'] : 0;
    }

    // Fungsi untuk mengupdate urutan kaidah (reordering)
    public function reorderKaidah($fromOrder, $toOrder)
    {
        $this->transStart();

        try {
            // Update kaidah yang dipindahkan
            $kaidahToMove = $this->where('urutan', $fromOrder)->first();
            if ($kaidahToMove) {
                $this->update($kaidahToMove['id_materi'], ['urutan' => $toOrder]);
            }

            // Update kaidah lain yang terpengaruh
            if ($fromOrder < $toOrder) {
                // Move down
                $this->where('urutan >', $fromOrder)
                     ->where('urutan <=', $toOrder)
                     ->set('urutan', 'urutan - 1')
                     ->update();
            } else {
                // Move up
                $this->where('urutan >=', $toOrder)
                     ->where('urutan <', $fromOrder)
                     ->set('urutan', 'urutan + 1')
                     ->update();
            }

            $this->transComplete();
            return $this->transStatus();
        } catch (\Exception $e) {
            $this->transRollback();
            return false;
        }
    }

    // Fungsi untuk mendapatkan kaidah yang tersedia untuk API mobile
    public function getKaidahForAPI()
    {
        return $this->select('id_materi, judul_kaidah, deskripsi, urutan')
                    ->orderBy('urutan', 'ASC')
                    ->findAll();
    }
}