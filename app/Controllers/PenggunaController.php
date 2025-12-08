<?php

namespace App\Controllers;

use App\Models\PenggunaModel;

class PenggunaController extends BaseController
{
    protected $penggunaModel;

    public function __construct()
    {
        $this->penggunaModel = new PenggunaModel();
    }

    /**
     * Display list of users
     */
    public function index()
    {
        $this->requireRole('ADMIN');

        // Get all data for client-side DataTables
        $users = $this->penggunaModel->findAll();

        // Calculate statistics
        $stats = [
            'total' => $this->penggunaModel->countAll(),
            'aktif' => $this->penggunaModel->where('status', 'AKTIF')->countAll(),
            'nonaktif' => $this->penggunaModel->where('status', 'NONAKTIF')->countAll()
        ];

        $data = [
            'users' => $users,
            'stats' => $stats
        ];

        return view('pengguna/index', $data);
    }

    /**
     * Show form to create new user
     */
    public function create()
    {
        $this->requireRole('ADMIN');

        $this->data = array_merge($this->data, [
            'page_title' => 'Tambah Pengguna Baru',
            'validation' => \Config\Services::validation()
        ]);

        return view('pengguna/create', $this->data);
    }

    /**
     * Store new user
     */
    public function store()
    {
        $this->requireRole('ADMIN');

        $rules = [
            'nama_pengguna' => [
                'rules' => 'required|min_length[3]|max_length[50]|is_unique[pengguna.nama_pengguna]',
                'errors' => [
                    'required' => 'Username wajib diisi',
                    'min_length' => 'Username minimal 3 karakter',
                    'max_length' => 'Username maksimal 50 karakter',
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
            'hak_akses' => [
                'rules' => 'required|in_list[ADMIN,GURU]',
                'errors' => [
                    'required' => 'Hak akses wajib dipilih',
                    'in_list' => 'Hak akses harus Admin atau Guru'
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
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->back()->withInput();
        }

        $data = [
            'nama_pengguna' => $this->request->getPost('nama_pengguna'),
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'hak_akses'     => $this->request->getPost('hak_akses'),
            'status'        => $this->request->getPost('status'),
            'kata_sandi'    => $this->request->getPost('kata_sandi')
        ];
        
        // Ensure password is hashed by the model
        // The model's hashPassword method will handle this automatically

        try {
            if ($this->penggunaModel->insert($data)) {
                session()->setFlashdata('success', 'Pengguna baru berhasil ditambahkan.');
                return redirect()->to(site_url('pengguna'));
            }

            // Log error untuk debugging
            log_message('error', 'Gagal insert pengguna, Data: {data}', [
                'data' => json_encode($data)
            ]);

            session()->setFlashdata('error', 'Gagal menambahkan pengguna. Pastikan semua data terisi dengan benar.');
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            // Log exception untuk debugging
            log_message('error', 'Exception saat insert pengguna: {message}', [
                'message' => $e->getMessage()
            ]);

            session()->setFlashdata('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show user details
     */
    public function show($id)
    {
        $this->requireRole('ADMIN');

        $user = $this->penggunaModel->find($id);

        if (!$user) {
            session()->setFlashdata('error', 'Pengguna tidak ditemukan.');
            return redirect()->to(site_url('pengguna'));
        }

        $this->data = array_merge($this->data, [
            'page_title' => 'Detail Pengguna',
            'user' => $user
        ]);

        return view('pengguna/show', $this->data);
    }

    /**
     * Show form to edit user
     */
    public function edit($id)
    {
        $this->requireRole('ADMIN');

        $user = $this->penggunaModel->find($id);

        if (!$user) {
            session()->setFlashdata('error', 'Pengguna tidak ditemukan.');
            return redirect()->to(site_url('pengguna'));
        }

        $this->data = array_merge($this->data, [
            'page_title' => 'Edit Pengguna',
            'user' => $user,
            'validation' => \Config\Services::validation()
        ]);

        return view('pengguna/edit', $this->data);
    }

    /**
     * Update user
     */
    public function update($id)
    {
        $this->requireRole('ADMIN');

        $user = $this->penggunaModel->find($id);

        if (!$user) {
            session()->setFlashdata('error', 'Pengguna tidak ditemukan.');
            return redirect()->to(site_url('pengguna'));
        }

        $rules = [
            'nama_pengguna' => [
                'rules' => "required|min_length[3]|max_length[50]|is_unique[pengguna.nama_pengguna,id_pengguna,{$id}]",
                'errors' => [
                    'required' => 'Username wajib diisi',
                    'min_length' => 'Username minimal 3 karakter',
                    'max_length' => 'Username maksimal 50 karakter',
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
            'hak_akses' => [
                'rules' => 'required|in_list[ADMIN,GURU]',
                'errors' => [
                    'required' => 'Hak akses wajib dipilih',
                    'in_list' => 'Hak akses harus Admin atau Guru'
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
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->back()->withInput();
        }

        $data = [
            'nama_pengguna' => $this->request->getPost('nama_pengguna'),
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'hak_akses'     => $this->request->getPost('hak_akses'),
            'status'        => $this->request->getPost('status')
        ];

        // Add password if provided
        if (!empty($password)) {
            $data['kata_sandi'] = $password;
            // The model's hashPassword method will handle hashing automatically
        }

        try {
            // Cek apakah ada perubahan data
            $existingUser = $this->penggunaModel->find($id);
            if (!$existingUser) {
                session()->setFlashdata('error', 'Pengguna tidak ditemukan.');
                return redirect()->back()->withInput();
            }

            // Bandingkan data untuk mendeteksi perubahan
            $hasChanges = false;
            $updateData = [];

            foreach ($data as $key => $value) {
                if (isset($existingUser[$key]) && $existingUser[$key] !== $value) {
                    $hasChanges = true;
                    $updateData[$key] = $value;
                }
            }

            // Jika tidak ada perubahan, anggap sebagai sukses
            if (!$hasChanges) {
                session()->setFlashdata('success', 'Data pengguna berhasil diperbarui.');
                return redirect()->to(site_url('pengguna'));
            }

            // Lakukan update dengan data yang berubah saja
            if ($this->penggunaModel->update($id, $updateData)) {
                session()->setFlashdata('success', 'Data pengguna berhasil diperbarui.');
                return redirect()->to(site_url('pengguna'));
            }

            // Log error untuk debugging
            log_message('error', 'Gagal update pengguna ID: {id}, Data: {data}', [
                'id' => $id,
                'data' => json_encode($updateData)
            ]);

            session()->setFlashdata('error', 'Gagal memperbarui data pengguna. Pastikan semua data terisi dengan benar.');
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            // Log exception untuk debugging
            log_message('error', 'Exception saat update pengguna: {message}', [
                'message' => $e->getMessage()
            ]);

            session()->setFlashdata('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Delete user
     */
    public function delete($id)
    {
        $this->requireRole('ADMIN');

        $user = $this->penggunaModel->find($id);

        if (!$user) {
            session()->setFlashdata('error', 'Pengguna tidak ditemukan.');
            return redirect()->to(site_url('pengguna'));
        }

        // Prevent deletion of current user
        if ($this->currentUser['id_pengguna'] == $id) {
            session()->setFlashdata('error', 'Tidak dapat menghapus akun yang sedang digunakan.');
            return redirect()->to(site_url('pengguna'));
        }

        try {
            if ($this->penggunaModel->delete($id)) {
                session()->setFlashdata('success', 'Pengguna berhasil dihapus.');
                return redirect()->to(site_url('pengguna'));
            }

            // Log error untuk debugging
            log_message('error', 'Gagal delete pengguna ID: {id}', [
                'id' => $id
            ]);

            session()->setFlashdata('error', 'Gagal menghapus pengguna. Pengguna mungkin sedang digunakan oleh sistem.');
            return redirect()->to(site_url('pengguna'));

        } catch (\Exception $e) {
            // Log exception untuk debugging
            log_message('error', 'Exception saat delete pengguna: {message}', [
                'message' => $e->getMessage()
            ]);

            session()->setFlashdata('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
            return redirect()->to(site_url('pengguna'));
        }
    }

    /**
     * Toggle user status
     */
    public function toggleStatus($id)
    {
        $this->requireRole('ADMIN');

        $user = $this->penggunaModel->find($id);

        if (!$user) {
            return $this->jsonError('Pengguna tidak ditemukan.', 404);
        }

        // Prevent status change of current user
        if ($this->currentUser['id_pengguna'] == $id) {
            return $this->jsonError('Tidak dapat mengubah status akun yang sedang digunakan.', 403);
        }

        $newStatus = $user['status'] === 'aktif' ? 'nonaktif' : 'aktif';

        if ($this->penggunaModel->update($id, ['status' => $newStatus])) {
            return $this->jsonSuccess('Status pengguna berhasil diperbarui.', [
                'status' => $newStatus,
                'status_text' => $newStatus === 'aktif' ? 'Aktif' : 'Nonaktif'
            ]);
        }

        return $this->jsonError('Gagal memperbarui status pengguna.');
    }

    /**
     * Check username availability
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

        $query = $this->penggunaModel->where('nama_pengguna', $username);

        if ($excludeId) {
            $query->where('id_pengguna !=', $excludeId);
        }

        $exists = $query->countAllResults() > 0;

        return $this->jsonResponse([
            'exists' => $exists,
            'message' => $exists ? 'Username sudah digunakan' : 'Username tersedia'
        ]);
    }

    /**
     * Bulk operations
     */
    public function bulkAction()
    {
        $this->requireRole('ADMIN');

        $action = $this->request->getPost('action');
        $userIds = $this->request->getPost('user_ids');

        if (empty($userIds) || !is_array($userIds)) {
            return $this->jsonError('Pilih pengguna terlebih dahulu.');
        }

        // Remove current user from selection
        $userIds = array_diff($userIds, [$this->currentUser['id_pengguna']]);

        if (empty($userIds)) {
            return $this->jsonError('Tidak dapat melakukan operasi pada akun yang sedang digunakan.');
        }

        switch ($action) {
            case 'activate':
                if ($this->penggunaModel->whereIn('id_pengguna', $userIds)->set(['status' => 'aktif'])->update()) {
                    return $this->jsonSuccess(count($userIds) . ' pengguna berhasil diaktifkan.');
                }
                break;

            case 'deactivate':
                if ($this->penggunaModel->whereIn('id_pengguna', $userIds)->set(['status' => 'nonaktif'])->update()) {
                    return $this->jsonSuccess(count($userIds) . ' pengguna berhasil dinonaktifkan.');
                }
                break;

            case 'delete':
                if ($this->penggunaModel->whereIn('id_pengguna', $userIds)->delete()) {
                    return $this->jsonSuccess(count($userIds) . ' pengguna berhasil dihapus.');
                }
                break;

            default:
                return $this->jsonError('Operasi tidak valid.');
        }

        return $this->jsonError('Gagal melakukan operasi bulk.');
    }
}