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

        $perPage = $this->request->getGet('per_page') ?? 10;
        $search = $this->request->getGet('search');
        $role = $this->request->getGet('role');
        $status = $this->request->getGet('status');

        $builder = $this->penggunaModel;

        // Apply filters
        if ($search) {
            $builder = $builder->like('nama_lengkap', $search)
                             ->orLike('nama_pengguna', $search);
        }

        if ($role && in_array($role, ['ADMIN', 'GURU'])) {
            $builder = $builder->where('hak_akses', $role);
        }

        if ($status && in_array($status, ['AKTIF', 'NONAKTIF'])) {
            $builder = $builder->where('status', $status);
        }

        $users = $builder->paginate($perPage);

        $this->data = array_merge($this->data, [
            'page_title' => 'Manajemen Pengguna',
            'users' => $users,
            'pager' => $this->penggunaModel->pager,
            'search' => $search,
            'role' => $role,
            'status' => $status,
            'per_page' => $perPage
        ]);

        return view('pengguna/index', $this->data);
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
            'nama_pengguna' => 'required|min_length[3]|max_length[50]|alpha_numeric_space|is_unique[pengguna.nama_pengguna]',
            'kata_sandi'    => 'required|min_length[6]',
                        'nama_lengkap'  => 'required|min_length[3]|max_length[100]',
            'hak_akses'     => 'required|in_list[ADMIN,GURU]',
            'status'        => 'required|in_list[AKTIF,NONAKTIF]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors())
                           ->with('error', 'Data pengguna gagal disimpan. Periksa kembali input Anda.');
        }

        $data = [
            'nama_pengguna' => $this->request->getPost('nama_pengguna'),
            'email'         => $this->request->getPost('email'),
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'hak_akses'     => $this->request->getPost('hak_akses'),
            'status'        => $this->request->getPost('status'),
            'kata_sandi'    => $this->request->getPost('kata_sandi')
        ];

        if ($this->penggunaModel->insert($data)) {
            return redirect()->to(site_url('users'))
                           ->with('success', 'Pengguna baru berhasil ditambahkan.');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Gagal menambahkan pengguna. Silakan coba lagi.');
    }

    /**
     * Show user details
     */
    public function show($id)
    {
        $this->requireRole('ADMIN');

        $user = $this->penggunaModel->find($id);

        if (!$user) {
            return redirect()->to(site_url('users'))
                           ->with('error', 'Pengguna tidak ditemukan.');
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
            return redirect()->to(site_url('users'))
                           ->with('error', 'Pengguna tidak ditemukan.');
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
            return redirect()->to(site_url('users'))
                           ->with('error', 'Pengguna tidak ditemukan.');
        }

        $rules = [
            'nama_pengguna' => "required|min_length[3]|max_length[50]|alpha_numeric_space|is_unique[pengguna.nama_pengguna,id_pengguna,{$id}]",
                        'nama_lengkap'  => 'required|min_length[3]|max_length[100]',
            'hak_akses'     => 'required|in_list[ADMIN,GURU]',
            'status'        => 'required|in_list[AKTIF,NONAKTIF]'
        ];

        // Add password validation if password is provided
        $password = $this->request->getPost('kata_sandi');
        if (!empty($password)) {
            $rules['kata_sandi'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors())
                           ->with('error', 'Data pengguna gagal diperbarui. Periksa kembali input Anda.');
        }

        $data = [
            'nama_pengguna' => $this->request->getPost('nama_pengguna'),
            'email'         => $this->request->getPost('email'),
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'hak_akses'     => $this->request->getPost('hak_akses'),
            'status'        => $this->request->getPost('status')
        ];

        // Add password if provided
        if (!empty($password)) {
            $data['kata_sandi'] = $password;
        }

        if ($this->penggunaModel->update($id, $data)) {
            return redirect()->to(site_url('users'))
                           ->with('success', 'Data pengguna berhasil diperbarui.');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Gagal memperbarui pengguna. Silakan coba lagi.');
    }

    /**
     * Delete user
     */
    public function delete($id)
    {
        $this->requireRole('ADMIN');

        $user = $this->penggunaModel->find($id);

        if (!$user) {
            return redirect()->to(site_url('users'))
                           ->with('error', 'Pengguna tidak ditemukan.');
        }

        // Prevent deletion of current user
        if ($this->currentUser['id_pengguna'] == $id) {
            return redirect()->to(site_url('users'))
                           ->with('error', 'Tidak dapat menghapus akun yang sedang digunakan.');
        }

        if ($this->penggunaModel->delete($id)) {
            return redirect()->to(site_url('users'))
                           ->with('success', 'Pengguna berhasil dihapus.');
        }

        return redirect()->to(site_url('users'))
                       ->with('error', 'Gagal menghapus pengguna. Silakan coba lagi.');
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