<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KaidahModel;
use CodeIgniter\HTTP\ResponseInterface;

class KaidahController extends BaseController
{
    protected $kaidahModel;

    public function __construct()
    {
        $this->kaidahModel = new KaidahModel();
    }

    public function index()
    {
        // Get all kaidah data for DataTables (client-side)
        $kaidah = $this->kaidahModel->findAll();

        // Get statistics for dashboard
        $stats = $this->kaidahModel->getKaidahStatistics();

        // Prepare data for view
        $data = [
            'title' => 'Manajemen Materi Kaidah',
            'kaidah' => $kaidah,
            'stats' => $stats,
            'user' => session()->get('user') // untuk info pembuat
        ];

        return view('kaidah/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Materi Kaidah',
            'user' => session()->get('user'),
            'lastOrder' => $this->kaidahModel->getLastOrder() + 1
        ];

        return view('kaidah/create', $data);
    }

    public function store()
    {
        // Validation rules
        $rules = [
            'judul_kaidah' => [
                'rules' => 'required|min_length[3]|max_length[255]',
                'errors' => [
                    'required' => 'Judul kaidah harus diisi',
                    'min_length' => 'Judul kaidah minimal 3 karakter',
                    'max_length' => 'Judul kaidah maksimal 255 karakter'
                ]
            ],
            'deskripsi' => [
                'rules' => 'max_length[500]',
                'errors' => [
                    'max_length' => 'Deskripsi maksimal 500 karakter'
                ]
            ],
            'penjelasan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Penjelasan kaidah harus diisi'
                ]
            ],
            'contoh' => [
                'rules' => 'max_length[1000]',
                'errors' => [
                    'max_length' => 'Contoh maksimal 1000 karakter'
                ]
            ],
            'tingkat_kesulitan' => [
                'rules' => 'required|in_list[mudah,sedang,sulit]',
                'errors' => [
                    'required' => 'Tingkat kesulitan harus dipilih',
                    'in_list' => 'Tingkat kesulitan tidak valid'
                ]
            ],
            'urutan' => [
                'rules' => 'required|integer|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'Urutan harus diisi',
                    'integer' => 'Urutan harus berupa angka',
                    'greater_than_equal_to' => 'Urutan minimal 0'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data
        $data = [
            'judul_kaidah' => $this->request->getPost('judul_kaidah'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'penjelasan' => $this->request->getPost('penjelasan'),
            'contoh' => $this->request->getPost('contoh'),
            'tingkat_kesulitan' => $this->request->getPost('tingkat_kesulitan'),
            'urutan' => $this->request->getPost('urutan'),
            'dibuat_oleh' => session()->get('user')['id_pengguna']
        ];

        try {
            $id = $this->kaidahModel->insertKaidah($data);

            if (!$id) {
                return redirect()->back()->withInput()->with('error', 'Gagal menyimpan materi kaidah');
            }

            return redirect()->to('/kaidah')->with('success', 'Materi kaidah berhasil ditambahkan');
        } catch (\Exception $e) {
            log_message('error', 'Error creating kaidah: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    public function edit($id)
    {
        $kaidah = $this->kaidahModel->getKaidahById($id);

        if (!$kaidah) {
            return redirect()->to('/kaidah')->with('error', 'Materi kaidah tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Materi Kaidah',
            'kaidah' => $kaidah,
            'user' => session()->get('user')
        ];

        return view('kaidah/edit', $data);
    }

    public function update($id)
    {
        $kaidah = $this->kaidahModel->getKaidahById($id);

        if (!$kaidah) {
            return redirect()->to('/kaidah')->with('error', 'Materi kaidah tidak ditemukan');
        }

        // Validation rules
        $rules = [
            'judul_kaidah' => [
                'rules' => 'required|min_length[3]|max_length[255]',
                'errors' => [
                    'required' => 'Judul kaidah harus diisi',
                    'min_length' => 'Judul kaidah minimal 3 karakter',
                    'max_length' => 'Judul kaidah maksimal 255 karakter'
                ]
            ],
            'deskripsi' => [
                'rules' => 'max_length[500]',
                'errors' => [
                    'max_length' => 'Deskripsi maksimal 500 karakter'
                ]
            ],
            'penjelasan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Penjelasan kaidah harus diisi'
                ]
            ],
            'contoh' => [
                'rules' => 'max_length[1000]',
                'errors' => [
                    'max_length' => 'Contoh maksimal 1000 karakter'
                ]
            ],
            'tingkat_kesulitan' => [
                'rules' => 'required|in_list[mudah,sedang,sulit]',
                'errors' => [
                    'required' => 'Tingkat kesulitan harus dipilih',
                    'in_list' => 'Tingkat kesulitan tidak valid'
                ]
            ],
            'urutan' => [
                'rules' => 'required|integer|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'Urutan harus diisi',
                    'integer' => 'Urutan harus berupa angka',
                    'greater_than_equal_to' => 'Urutan minimal 0'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data
        $data = [
            'judul_kaidah' => $this->request->getPost('judul_kaidah'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'penjelasan' => $this->request->getPost('penjelasan'),
            'contoh' => $this->request->getPost('contoh'),
            'tingkat_kesulitan' => $this->request->getPost('tingkat_kesulitan'),
            'urutan' => $this->request->getPost('urutan')
        ];

        try {
            if (!$this->kaidahModel->updateKaidah($id, $data)) {
                return redirect()->back()->withInput()->with('error', 'Gagal mengupdate materi kaidah');
            }

            return redirect()->to('/kaidah')->with('success', 'Materi kaidah berhasil diupdate');
        } catch (\Exception $e) {
            log_message('error', 'Error updating kaidah: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    public function delete($id)
    {
        $kaidah = $this->kaidahModel->getKaidahById($id);

        if (!$kaidah) {
            return redirect()->to('/kaidah')->with('error', 'Materi kaidah tidak ditemukan');
        }

        try {
            if (!$this->kaidahModel->delete($id)) {
                return redirect()->to('/kaidah')->with('error', 'Gagal menghapus materi kaidah');
            }

            return redirect()->to('/kaidah')->with('success', 'Materi kaidah berhasil dihapus');
        } catch (\Exception $e) {
            log_message('error', 'Error deleting kaidah: ' . $e->getMessage());
            return redirect()->to('/kaidah')->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    public function show($id)
    {
        $kaidah = $this->kaidahModel->getKaidahById($id);

        if (!$kaidah) {
            return redirect()->to('/kaidah')->with('error', 'Materi kaidah tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Materi Kaidah',
            'kaidah' => $kaidah,
            'user' => session()->get('user')
        ];

        return view('kaidah/show', $data);
    }

    // Method untuk statistics API (bisa dipakai dashboard)
    public function statistics()
    {
        $stats = $this->kaidahModel->getKaidahStatistics();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $stats
        ]);
    }

    // Method untuk reordering (drag & drop)
    public function reorder()
    {
        $fromOrder = $this->request->getPost('fromOrder');
        $toOrder = $this->request->getPost('toOrder');

        if (!is_numeric($fromOrder) || !is_numeric($toOrder)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid order parameters'
            ], 400);
        }

        try {
            $result = $this->kaidahModel->reorderKaidah($fromOrder, $toOrder);

            if ($result) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Urutan kaidah berhasil diupdate'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal mengupdate urutan kaidah'
                ], 400);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error reordering kaidah: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }
}