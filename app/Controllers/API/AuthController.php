<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\PenggunaModel;

class AuthController extends BaseController
{
    protected $penggunaModel;

    public function __construct()
    {
        $this->penggunaModel = new PenggunaModel();
    }

    /**
     * API Login untuk Siswa
     * POST /api/auth/login
     */
    public function login()
    {
        $rules = [
            'nama_pengguna' => 'required|min_length[3]',
            'kata_sandi'    => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return $this->respondWithError(
                'Validasi gagal',
                400,
                $this->validator->getErrors()
            );
        }

        $nama_pengguna = $this->request->getPost('nama_pengguna');
        $kata_sandi = $this->request->getPost('kata_sandi');

        // Authenticate user
        $user = $this->penggunaModel->authenticate($nama_pengguna, $kata_sandi);

        if (!$user) {
            return $this->respondWithError('Nama pengguna atau kata sandi salah', 401);
        }

        // Only allow siswa role for mobile API
        if ($user['hak_akses'] !== 'siswa') {
            return $this->respondWithError('Hanya siswa yang bisa login melalui aplikasi mobile', 403);
        }

        // Remove sensitive data
        unset($user['kata_sandi']);

        return $this->respondWithSuccess([
            'user' => $user,
            'token' => $this->generateSimpleToken($user['id_pengguna'])
        ], 'Login berhasil');
    }

    /**
     * API Register untuk Siswa Baru
     * POST /api/auth/register
     */
    public function register()
    {
        $rules = [
            'nama_pengguna' => 'required|min_length[3]|max_length[50]|is_unique[pengguna.nama_pengguna]',
            'kata_sandi'    => 'required|min_length[6]',
            'email'         => 'required|valid_email|is_unique[pengguna.email]',
            'nama_lengkap'  => 'required|min_length[3]|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return $this->respondWithError(
                'Validasi gagal',
                400,
                $this->validator->getErrors()
            );
        }

        $data = [
            'nama_pengguna' => $this->request->getPost('nama_pengguna'),
            'kata_sandi'    => password_hash($this->request->getPost('kata_sandi'), PASSWORD_DEFAULT),
            'email'         => $this->request->getPost('email'),
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'hak_akses'     => 'siswa',
            'status'        => 'aktif',
            'waktu_dibuat'  => date('Y-m-d H:i:s')
        ];

        try {
            $userId = $this->penggunaModel->insert($data);

            if (!$userId) {
                return $this->respondWithError('Gagal mendaftarkan pengguna', 500);
            }

            $user = $this->penggunaModel->find($userId);
            unset($user['kata_sandi']);

            return $this->respondWithSuccess([
                'user' => $user,
                'token' => $this->generateSimpleToken($userId)
            ], 'Pendaftaran berhasil', 201);

        } catch (\Exception $e) {
            return $this->respondWithError('Terjadi kesalahan saat mendaftar', 500);
        }
    }

    /**
     * Get Profile Siswa
     * GET /api/auth/profile
     */
    public function profile()
    {
        $userId = $this->getUserIdFromToken();

        if (!$userId) {
            return $this->respondWithError('Token tidak valid', 401);
        }

        $user = $this->penggunaModel->find($userId);

        if (!$user) {
            return $this->respondWithError('Pengguna tidak ditemukan', 404);
        }

        // Remove sensitive data
        unset($user['kata_sandi']);

        return $this->respondWithSuccess($user, 'Profile berhasil diambil');
    }

    /**
     * Update Profile Siswa
     * PUT /api/auth/profile
     */
    public function updateProfile()
    {
        $userId = $this->getUserIdFromToken();

        if (!$userId) {
            return $this->respondWithError('Token tidak valid', 401);
        }

        $user = $this->penggunaModel->find($userId);

        if (!$user) {
            return $this->respondWithError('Pengguna tidak ditemukan', 404);
        }

        $rules = [
            'email'        => 'permit_empty|valid_email|is_unique[pengguna.email,id_pengguna,' . $userId . ']',
            'nama_lengkap' => 'permit_empty|min_length[3]|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return $this->respondWithError(
                'Validasi gagal',
                400,
                $this->validator->getErrors()
            );
        }

        $data = [];

        if ($this->request->getPost('email')) {
            $data['email'] = $this->request->getPost('email');
        }

        if ($this->request->getPost('nama_lengkap')) {
            $data['nama_lengkap'] = $this->request->getPost('nama_lengkap');
        }

        if (!empty($data)) {
            $data['waktu_diubah'] = date('Y-m-d H:i:s');

            if (!$this->penggunaModel->update($userId, $data)) {
                return $this->respondWithError('Gagal mengupdate profile', 500);
            }
        }

        $updatedUser = $this->penggunaModel->find($userId);
        unset($updatedUser['kata_sandi']);

        return $this->respondWithSuccess($updatedUser, 'Profile berhasil diupdate');
    }

    /**
     * Logout (clear token on client side)
     * POST /api/auth/logout
     */
    public function logout()
    {
        return $this->respondWithSuccess(null, 'Logout berhasil');
    }

    /**
     * Generate simple token (untuk skripsi, sederhana)
     * In production, gunakan JWT atau OAuth
     */
    private function generateSimpleToken($userId)
    {
        $payload = [
            'user_id' => $userId,
            'exp'     => time() + (30 * 24 * 60 * 60), // 30 hari
            'iat'     => time()
        ];

        return base64_encode(json_encode($payload));
    }

    /**
     * Get user ID from simple token
     */
    private function getUserIdFromToken()
    {
        $authorization = $this->request->getHeaderLine('Authorization');

        if (empty($authorization) || !preg_match('/Bearer\s+(.*)$/i', $authorization, $matches)) {
            return null;
        }

        $token = $matches[1];
        $payload = json_decode(base64_decode($token), true);

        if (!$payload || !isset($payload['user_id']) || !isset($payload['exp'])) {
            return null;
        }

        // Check if token expired
        if ($payload['exp'] < time()) {
            return null;
        }

        return $payload['user_id'];
    }
}