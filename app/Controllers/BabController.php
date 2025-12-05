<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BabModel;
use App\Models\MateriKaidahModel;
use App\Models\SoalModel;

class BabController extends BaseController
{
    protected $babModel;
    protected $materiKaidahModel;
    protected $soalModel;

    public function __construct()
    {
        $this->babModel = new BabModel();
        $this->materiKaidahModel = new MateriKaidahModel();
        $this->soalModel = new SoalModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Bab',
            'page' => 'bab',
            'subtitle' => 'Pengelolaan materi pembelajaran per bab'
        ];

        // Get statistics for dashboard
        $stats = $this->getBabStatistics();

        // Get all bab data
        $bab = $this->babModel->getAllWithStats();

        return view('bab/index', array_merge($data, [
            'bab' => $bab,
            'stats' => $stats
        ]));
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Bab',
            'page' => 'bab',
            'subtitle' => 'Tambah bab baru untuk materi pembelajaran',
            'validation' => \Config\Services::validation(),
            'nextOrder' => $this->babModel->getNextOrder()
        ];

        return view('bab/create', $data);
    }

    public function store()
    {

        // Validation rules
        $rules = [
            'nama_bab' => [
                'rules' => 'required|min_length[3]|max_length[100]|is_unique[bab.nama_bab]',
                'errors' => [
                    'required' => 'Nama bab wajib diisi',
                    'min_length' => 'Nama bab minimal 3 karakter',
                    'max_length' => 'Nama bab maksimal 100 karakter',
                    'is_unique' => 'Nama bab sudah digunakan, gunakan nama lain'
                ]
            ],
            'deskripsi' => [
                'rules' => 'max_length[1000]',
                'errors' => [
                    'max_length' => 'Deskripsi maksimal 1000 karakter'
                ]
            ],
            'urutan' => [
                'rules' => 'required|integer|greater_than_equal_to[1]|is_unique[bab.urutan]',
                'errors' => [
                    'required' => 'Urutan wajib diisi',
                    'integer' => 'Urutan harus berupa angka',
                    'greater_than_equal_to' => 'Urutan minimal 1',
                    'is_unique' => 'Urutan sudah digunakan, gunakan urutan lain'
                ]
            ],
            'is_active' => [
                'rules' => 'required|in_list[0,1]',
                'errors' => [
                    'required' => 'Status wajib dipilih',
                    'in_list' => 'Status tidak valid'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->back()->withInput();
        }

        $data = [
            'nama_bab' => $this->request->getPost('nama_bab'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'urutan' => $this->request->getPost('urutan'),
            'is_active' => $this->request->getPost('is_active')
        ];

        try {
            if ($this->babModel->insert($data)) {
                session()->setFlashdata('success', 'Data bab berhasil ditambahkan');
                return redirect()->to('/bab');
            } else {
                session()->setFlashdata('error', 'Gagal menambahkan data bab');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            log_message('error', 'Error saat menyimpan bab: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan saat menyimpan data bab');
            return redirect()->back()->withInput();
        }
    }

    public function show($id)
    {

        $bab = $this->babModel->find($id);
        if (!$bab) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Bab tidak ditemukan');
        }

        // Get statistics for this bab
        $stats = [
            'total_materi' => $this->materiKaidahModel->where('id_bab', $id)->countAllResults(),
            'total_soal' => $this->soalModel->where('id_bab', $id)->countAllResults()
        ];

        // Get recent materi for this bab
        $recent_materi = $this->materiKaidahModel->select('materi_kaidah.*, pengguna.nama_lengkap as nama_pembuat')
                                                   ->join('pengguna', 'pengguna.id_pengguna = materi_kaidah.dibuat_oleh')
                                                   ->where('materi_kaidah.id_bab', $id)
                                                   ->orderBy('materi_kaidah.waktu_dibuat', 'DESC')
                                                   ->limit(5)
                                                   ->findAll();

        $data = [
            'bab' => $bab,
            'stats' => $stats,
            'recent_materi' => $recent_materi,
            'page_title' => 'Detail Bab: ' . $bab['nama_bab']
        ];

        return view('bab/show', array_merge($this->data, $data));
    }

    public function edit($id)
    {

        $bab = $this->babModel->getWithStats($id);
        if (!$bab) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Bab tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Bab',
            'page' => 'bab',
            'subtitle' => 'Edit bab: ' . $bab['nama_bab'],
            'bab' => $bab,
            'page_title' => 'Edit Bab: ' . $bab['nama_bab'],
            'validation' => \Config\Services::validation()
        ];

        return view('bab/edit', array_merge($this->data, $data));
    }

    public function update($id)
    {

        $bab = $this->babModel->find($id);
        if (!$bab) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Bab tidak ditemukan');
        }

        // Validation rules
        $rules = [
            'nama_bab' => [
                'rules' => "required|min_length[3]|max_length[100]|is_unique[bab.nama_bab,id_bab,{$id}]",
                'errors' => [
                    'required' => 'Nama bab wajib diisi',
                    'min_length' => 'Nama bab minimal 3 karakter',
                    'max_length' => 'Nama bab maksimal 100 karakter',
                    'is_unique' => 'Nama bab sudah digunakan, gunakan nama lain'
                ]
            ],
            'deskripsi' => [
                'rules' => 'max_length[1000]',
                'errors' => [
                    'max_length' => 'Deskripsi maksimal 1000 karakter'
                ]
            ],
            'urutan' => [
                'rules' => "required|integer|greater_than_equal_to[1]|is_unique[bab.urutan,id_bab,{$id}]",
                'errors' => [
                    'required' => 'Urutan wajib diisi',
                    'integer' => 'Urutan harus berupa angka',
                    'greater_than_equal_to' => 'Urutan minimal 1',
                    'is_unique' => 'Urutan sudah digunakan, gunakan urutan lain'
                ]
            ],
            'is_active' => [
                'rules' => 'required|in_list[0,1]',
                'errors' => [
                    'required' => 'Status wajib dipilih',
                    'in_list' => 'Status tidak valid'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->back()->withInput();
        }

        $data = [
            'nama_bab' => $this->request->getPost('nama_bab'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'urutan' => $this->request->getPost('urutan'),
            'is_active' => $this->request->getPost('is_active')
        ];

        try {
            if ($this->babModel->update($id, $data)) {
                session()->setFlashdata('success', 'Data bab berhasil diperbarui');
                return redirect()->to('/bab');
            } else {
                session()->setFlashdata('error', 'Gagal memperbarui data bab');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            log_message('error', 'Error saat update bab: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan saat memperbarui data bab');
            return redirect()->back()->withInput();
        }
    }

    public function delete($id)
    {

        $bab = $this->babModel->find($id);
        if (!$bab) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Bab tidak ditemukan');
        }

        // Check if there are related materi
        $materiCount = $this->materiKaidahModel->where('id_bab', $id)->countAllResults();
        if ($materiCount > 0) {
            session()->setFlashdata('error', "Tidak dapat menghapus bab ini karena masih ada {$materiCount} materi kaidah yang terkait. Hapus atau pindahkan materi tersebut terlebih dahulu.");
            return redirect()->back();
        }

        try {
            if ($this->babModel->delete($id)) {
                session()->setFlashdata('success', 'Data bab berhasil dihapus');
            } else {
                session()->setFlashdata('error', 'Gagal menghapus data bab');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error saat menghapus bab: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan saat menghapus data bab');
        }

        return redirect()->to('/bab');
    }

    public function toggleStatus($id)
    {

        $bab = $this->babModel->find($id);
        if (!$bab) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Bab tidak ditemukan'
            ], 404);
        }

        $newStatus = $bab['is_active'] ? 0 : 1;

        try {
            if ($this->babModel->update($id, ['is_active' => $newStatus])) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Status bab berhasil diperbarui',
                    'new_status' => $newStatus
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal memperbarui status bab'
                ], 500);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error saat toggle status bab: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui status bab'
            ], 500);
        }
    }

    public function statistics()
    {

        $stats = $this->babModel->getStatistics();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $stats
        ]);
    }

    // API methods
    public function getChapters()
    {
        $limit = $this->request->getGet('limit') ?? 20;
        $page = $this->request->getGet('page') ?? 1;

        $result = $this->babModel->getBabWithStats($limit, $page);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data bab berhasil diambil',
            'data' => $result
        ]);
    }

    public function getChapterDetail($id)
    {
        $bab = $this->babModel->find($id);
        if (!$bab) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Bab tidak ditemukan',
                'data' => null
            ], 404);
        }

        // Get additional stats
        $stats = [
            'total_materi' => $this->materiKaidahModel->where('id_bab', $id)->countAllResults(),
            'total_soal' => $this->soalModel->where('id_bab', $id)->countAllResults()
        ];

        $bab['stats'] = $stats;

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Detail bab berhasil diambil',
            'data' => $bab
        ]);
    }

    /**
     * Get bab statistics for dashboard
     */
    private function getBabStatistics()
    {
        $db = \Config\Database::connect();

        // Get total bab
        $totalBab = $this->babModel->countAllResults();

        // Get active bab
        $activeBab = $this->babModel->where('is_active', 1)->countAllResults();

        // Get materi statistics per bab
        $materiPerBab = $db->table('bab b')
            ->select('
                b.id_bab,
                b.nama_bab,
                COUNT(mk.id_materi) as total_materi,
                COUNT(rb.id_riwayat) as total_dipelajari,
                COUNT(DISTINCT rb.id_siswa) as total_siswa,
                COUNT(CASE WHEN rb.status = "selesai" THEN 1 END) as materi_selesai
            ')
            ->join('materi_kaidah mk', 'mk.id_bab = b.id_bab', 'left')
            ->join('riwayat_belajar rb', 'rb.id_materi = mk.id_materi AND rb.status = "selesai"', 'left')
            ->groupBy('b.id_bab, b.nama_bab')
            ->orderBy('b.urutan')
            ->get()
            ->getResultArray();

        // Get overall progress
        $totalMateri = $db->table('materi_kaidah')->countAllResults();
        $completedMateri = $db->table('riwayat_belajar')
            ->where('status', 'selesai')
            ->distinct('id_materi')
            ->countAllResults();

        return [
            'total_bab' => $totalBab,
            'active_bab' => $activeBab,
            'inactive_bab' => $totalBab - $activeBab,
            'total_materi' => $totalMateri,
            'completed_materi' => $completedMateri,
            'completion_rate' => $totalMateri > 0 ? round(($completedMateri / $totalMateri) * 100, 1) : 0,
            'materi_per_bab' => $materiPerBab
        ];
    }
}