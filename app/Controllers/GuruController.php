<?php

namespace App\Controllers;

use App\Models\GuruModel;

class GuruController extends BaseController
{
    protected $guruModel;

    public function __construct()
    {
        $this->guruModel = new GuruModel();
    }

    /**
     * Display list of teachers
     */
    public function index()
    {
        $this->requireRole('ADMIN');

        // Get all teacher data for client-side DataTables
        $gurus = $this->guruModel->getAllGurus();

        // Calculate statistics for teachers only
        $stats = [
            'total' => $this->guruModel->countAllGurus(),
            'aktif' => $this->guruModel->where('status', 'AKTIF')->countAllResults(),
            'nonaktif' => $this->guruModel->where('status', 'NONAKTIF')->countAllResults()
        ];

        $data = [
            'gurus' => $gurus,
            'stats' => $stats
        ];

        return view('guru/index', $data);
    }

    /**
     * Show form to create new teacher
     */
    public function create()
    {
        $this->requireRole('ADMIN');

        $this->data = array_merge($this->data, [
            'page_title' => 'Tambah Guru Baru',
            'validation' => \Config\Services::validation()
        ]);

        return view('guru/create', $this->data);
    }

    /**
     * Store new teacher
     */
    public function store()
    {
        $this->requireRole('ADMIN');

        $rules = [
            'nama_pengguna' => [
                'rules' => 'required|min_length[3]|max_length[50]|alpha_numeric_space|is_unique[pengguna.nama_pengguna]',
                'errors' => [
                    'required' => 'Username wajib diisi',
                    'min_length' => 'Username minimal 3 karakter',
                    'max_length' => 'Username maksimal 50 karakter',
                    'alpha_numeric_space' => 'Username hanya boleh mengandung huruf, angka, dan spasi',
                    'is_unique' => 'Username sudah digunakan, silakan pilih username lain'
                ]
            ],
            'kata_sandi' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'Password wajib diisi',
                    'min_length' => 'Password minimal 6 karakter'
                ]
            ],
            'nama_lengkap' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama lengkap wajib diisi',
                    'min_length' => 'Nama lengkap minimal 3 karakter',
                    'max_length' => 'Nama lengkap maksimal 100 karakter'
                ]
            ],
            'status' => [
                'rules' => 'required|in_list[AKTIF,NONAKTIF]',
                'errors' => [
                    'required' => 'Status wajib dipilih',
                    'in_list' => 'Status harus Aktif atau Nonaktif'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors())
                           ->with('error', 'Data guru gagal disimpan. Periksa kembali input Anda.');
        }

        $data = [
            'nama_pengguna' => $this->request->getPost('nama_pengguna'),
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'status'        => $this->request->getPost('status'),
            'kata_sandi'    => $this->request->getPost('kata_sandi'),
            'hak_akses'     => 'GURU' // Auto-set role sebagai GURU
        ];

        try {
            if ($this->guruModel->insert($data)) {
                return redirect()->to(site_url('guru'))
                               ->with('success', 'Guru baru berhasil ditambahkan.');
            }

            // Log error untuk debugging
            log_message('error', 'Gagal insert guru, Data: {data}', [
                'data' => json_encode($data)
            ]);

            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Gagal menambahkan guru. Pastikan semua data terisi dengan benar.');

        } catch (\Exception $e) {
            // Log exception untuk debugging
            log_message('error', 'Exception saat insert guru: {message}', [
                'message' => $e->getMessage()
            ]);

            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Show teacher details
     */
    public function show($id)
    {
        $this->requireRole('ADMIN');

        $guru = $this->guruModel->getGuruById($id);

        if (!$guru) {
            return redirect()->to(site_url('guru'))
                           ->with('error', 'Guru tidak ditemukan.');
        }

        $this->data = array_merge($this->data, [
            'page_title' => 'Detail Guru',
            'guru' => $guru
        ]);

        return view('guru/show', $this->data);
    }

    /**
     * Show form to edit teacher
     */
    public function edit($id)
    {
        $this->requireRole('ADMIN');

        $guru = $this->guruModel->getGuruById($id);

        if (!$guru) {
            return redirect()->to(site_url('guru'))
                           ->with('error', 'Guru tidak ditemukan.');
        }

        $this->data = array_merge($this->data, [
            'page_title' => 'Edit Guru',
            'guru' => $guru,
            'validation' => \Config\Services::validation()
        ]);

        return view('guru/edit', $this->data);
    }

    /**
     * Update teacher
     */
    public function update($id)
    {
        $this->requireRole('ADMIN');

        $guru = $this->guruModel->getGuruById($id);

        if (!$guru) {
            return redirect()->to(site_url('guru'))
                           ->with('error', 'Guru tidak ditemukan.');
        }

        $rules = [
            'nama_pengguna' => [
                'rules' => "required|min_length[3]|max_length[50]|alpha_numeric_space|is_unique[pengguna.nama_pengguna,id_pengguna,{$id}]",
                'errors' => [
                    'required' => 'Username wajib diisi',
                    'min_length' => 'Username minimal 3 karakter',
                    'max_length' => 'Username maksimal 50 karakter',
                    'alpha_numeric_space' => 'Username hanya boleh mengandung huruf, angka, dan spasi',
                    'is_unique' => 'Username sudah digunakan oleh pengguna lain'
                ]
            ],
            'nama_lengkap' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama lengkap wajib diisi',
                    'min_length' => 'Nama lengkap minimal 3 karakter',
                    'max_length' => 'Nama lengkap maksimal 100 karakter'
                ]
            ],
            'status' => [
                'rules' => 'required|in_list[AKTIF,NONAKTIF]',
                'errors' => [
                    'required' => 'Status wajib dipilih',
                    'in_list' => 'Status harus Aktif atau Nonaktif'
                ]
            ]
        ];

        // Add password validation if password is provided
        $password = $this->request->getPost('kata_sandi');
        if (!empty($password)) {
            $rules['kata_sandi'] = [
                'rules' => 'min_length[6]',
                'errors' => [
                    'min_length' => 'Password minimal 6 karakter jika diubah'
                ]
            ];
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors())
                           ->with('error', 'Data guru gagal diperbarui. Periksa kembali input Anda.');
        }

        $data = [
            'nama_pengguna' => $this->request->getPost('nama_pengguna'),
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'status'        => $this->request->getPost('status')
        ];

        // Add password if provided
        if (!empty($password)) {
            $data['kata_sandi'] = $password;
        }

        try {
            // Cek apakah ada perubahan data
            $existingGuru = $this->guruModel->find($id);
            if (!$existingGuru) {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Guru tidak ditemukan.');
            }

            // Bandingkan data untuk mendeteksi perubahan
            $hasChanges = false;
            $updateData = [];

            foreach ($data as $key => $value) {
                if (isset($existingGuru[$key]) && $existingGuru[$key] !== $value) {
                    $hasChanges = true;
                    $updateData[$key] = $value;
                }
            }

            // Jika tidak ada perubahan, anggap sebagai sukses
            if (!$hasChanges) {
                return redirect()->to(site_url('guru'))
                               ->with('success', 'Data guru berhasil diperbarui.');
            }

            // Lakukan update dengan data yang berubah saja
            if ($this->guruModel->update($id, $updateData)) {
                return redirect()->to(site_url('guru'))
                               ->with('success', 'Data guru berhasil diperbarui.');
            }

            // Log error untuk debugging
            log_message('error', 'Gagal update guru ID: {id}, Data: {data}', [
                'id' => $id,
                'data' => json_encode($updateData)
            ]);

            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Gagal memperbarui data guru. Pastikan semua data terisi dengan benar.');

        } catch (\Exception $e) {
            // Log exception untuk debugging
            log_message('error', 'Exception saat update guru: {message}', [
                'message' => $e->getMessage()
            ]);

            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Delete teacher
     */
    public function delete($id)
    {
        $this->requireRole('ADMIN');

        $guru = $this->guruModel->getGuruById($id);

        if (!$guru) {
            return redirect()->to(site_url('guru'))
                           ->with('error', 'Guru tidak ditemukan.');
        }

        // Prevent deletion of current user
        if ($this->currentUser['id_pengguna'] == $id) {
            return redirect()->to(site_url('guru'))
                           ->with('error', 'Tidak dapat menghapus akun yang sedang digunakan.');
        }

        try {
            if ($this->guruModel->delete($id)) {
                return redirect()->to(site_url('guru'))
                               ->with('success', 'Guru berhasil dihapus.');
            }

            // Log error untuk debugging
            log_message('error', 'Gagal delete guru ID: {id}', [
                'id' => $id
            ]);

            return redirect()->to(site_url('guru'))
                           ->with('error', 'Gagal menghapus guru. Guru mungkin sedang digunakan oleh sistem.');

        } catch (\Exception $e) {
            // Log exception untuk debugging
            log_message('error', 'Exception saat delete guru: {message}', [
                'message' => $e->getMessage()
            ]);

            return redirect()->to(site_url('guru'))
                           ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Toggle teacher status
     */
    public function toggleStatus($id)
    {
        $this->requireRole('ADMIN');

        $guru = $this->guruModel->getGuruById($id);

        if (!$guru) {
            return $this->jsonError('Guru tidak ditemukan.', 404);
        }

        // Prevent status change of current user
        if ($this->currentUser['id_pengguna'] == $id) {
            return $this->jsonError('Tidak dapat mengubah status akun yang sedang digunakan.', 403);
        }

        $newStatus = $guru['status'] === 'AKTIF' ? 'NONAKTIF' : 'AKTIF';

        if ($this->guruModel->update($id, ['status' => $newStatus])) {
            return $this->jsonSuccess('Status guru berhasil diperbarui.', [
                'status' => $newStatus,
                'status_text' => $newStatus === 'AKTIF' ? 'Aktif' : 'Nonaktif'
            ]);
        }

        return $this->jsonError('Gagal memperbarui status guru.');
    }

    /**
     * Check username availability for teachers
     */
    public function checkUsername()
    {
        $this->requireRole('ADMIN');

        $json = $this->request->getJSON(true);
        $username = $json['username'] ?? '';
        $excludeId = $json['exclude_id'] ?? null;

        if (empty($username)) {
            return $this->jsonError('Username diperlukan');
        }

        $exists = $this->guruModel->isUsernameExist($username, $excludeId);

        return $this->jsonResponse([
            'exists' => $exists,
            'message' => $exists ? 'Username sudah digunakan' : 'Username tersedia'
        ]);
    }

    /**
     * Bulk operations for teachers
     */
    public function bulkAction()
    {
        $this->requireRole('ADMIN');

        $action = $this->request->getPost('action');
        $guruIds = $this->request->getPost('guru_ids');

        if (empty($guruIds) || !is_array($guruIds)) {
            return $this->jsonError('Pilih guru terlebih dahulu.');
        }

        // Remove current user from selection
        $guruIds = array_diff($guruIds, [$this->currentUser['id_pengguna']]);

        if (empty($guruIds)) {
            return $this->jsonError('Tidak dapat melakukan operasi pada akun yang sedang digunakan.');
        }

        switch ($action) {
            case 'activate':
                if ($this->guruModel->whereIn('id_pengguna', $guruIds)->set(['status' => 'AKTIF'])->update()) {
                    return $this->jsonSuccess(count($guruIds) . ' guru berhasil diaktifkan.');
                }
                break;

            case 'deactivate':
                if ($this->guruModel->whereIn('id_pengguna', $guruIds)->set(['status' => 'NONAKTIF'])->update()) {
                    return $this->jsonSuccess(count($guruIds) . ' guru berhasil dinonaktifkan.');
                }
                break;

            case 'delete':
                if ($this->guruModel->whereIn('id_pengguna', $guruIds)->delete()) {
                    return $this->jsonSuccess(count($guruIds) . ' guru berhasil dihapus.');
                }
                break;

            default:
                return $this->jsonError('Operasi tidak valid.');
        }

        return $this->jsonError('Gagal melakukan operasi bulk.');
    }
}