<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Libraries\APISessionHelper;
use CodeIgniter\API\ResponseTrait;

class SiswaAuthController extends BaseController
{
    use ResponseTrait;

    protected $siswaModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
    }

    /**
     * API Login untuk Siswa (Mobile App)
     * POST /api/siswa/login
     */
    public function login()
    {
        $rules = [
            'nis' => 'required|min_length[3]|max_length[20]',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors(), 400);
        }

        $nis = $this->request->getVar('nis');
        $password = $this->request->getVar('password');

        // Cari siswa berdasarkan NIS
        $siswa = $this->siswaModel->where('nis', $nis)
                                  ->where('status', 'AKTIF')
                                  ->first();

        if (!$siswa) {
            return $this->fail('NIS atau password salah', 401);
        }

        // Verifikasi password
        if (!password_verify($password, $siswa['kata_sandi'])) {
            return $this->fail('NIS atau password salah', 401);
        }

        // Simpan login history
        $this->saveLoginHistory($siswa['id'], $nis);

        // Generate simple token (base64 encoded user_id + timestamp)
        $token = APISessionHelper::generateSessionToken($siswa['id']);

        $response = [
            'status' => 'success',
            'message' => 'Login berhasil',
            'code' => 200,  // Add HTTP status code to response body
            'data' => [
                'siswa' => [
                    'id' => $siswa['id'],
                    'nis' => $siswa['nis'],
                    'nama_lengkap' => $siswa['nama_lengkap'],
                    'jenis_kelamin' => $siswa['jenis_kelamin'],
                    'kelas' => $siswa['kelas'],
                    'status' => $siswa['status']
                ],
                'token' => $token,
                'login_time' => date('Y-m-d H:i:s')
            ]
        ];

        return $this->respond($response, 200);
    }

    /**
     * Get Profile Siswa
     * GET /api/siswa/profile
     */
    public function profile()
    {
        // Validate session
        $userId = APISessionHelper::validateSession($this->request);

        if (!$userId) {
            return $this->fail('Session tidak valid atau kadaluarsa', 401);
        }

        $siswa = $this->siswaModel->find($userId);
        if (!$siswa) {
            return $this->fail('Siswa tidak ditemukan', 404);
        }

        // Get login history terakhir
        $loginHistory = $this->getLatestLoginHistory($siswa['nis']);

        $response = [
            'status' => 'success',
            'message' => 'Profile berhasil diambil',
            'code' => 200,
            'data' => [
                'siswa' => [
                    'id' => $siswa['id'],
                    'nis' => $siswa['nis'],
                    'nama_lengkap' => $siswa['nama_lengkap'],
                    'jenis_kelamin' => $siswa['jenis_kelamin'],
                    'kelas' => $siswa['kelas'],
                    'status' => $siswa['status'],
                    'waktu_dibuat' => $siswa['waktu_dibuat']
                ],
                'login_history' => $loginHistory
            ]
        ];

        return $this->respond($response, 200);
    }

    /**
     * Update Profile Siswa
     * PUT /api/siswa/profile
     */
    public function updateProfile()
    {
        $authHeader = $this->request->getHeader('Authorization');
        if (!$authHeader) {
            return $this->fail('Token diperlukan', 401);
        }

        $token = str_replace('Bearer ', '', $authHeader->getValue());
        $userId = $this->extractUserIdFromToken($token);

        if (!$userId) {
            return $this->fail('Token tidak valid', 401);
        }

        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'kelas' => 'required|max_length[20]'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors(), 400);
        }

        $siswa = $this->siswaModel->find($userId);
        if (!$siswa) {
            return $this->fail('Siswa tidak ditemukan', 404);
        }

        $data = [
            'nama_lengkap' => $this->request->getVar('nama_lengkap'),
            'jenis_kelamin' => $this->request->getVar('jenis_kelamin'),
            'kelas' => $this->request->getVar('kelas'),
            'waktu_diubah' => date('Y-m-d H:i:s')
        ];

        if ($this->siswaModel->update($userId, $data)) {
            $response = [
                'status' => 'success',
                'message' => 'Profile berhasil diperbarui',
                'data' => [
                    'siswa' => array_merge($siswa, $data)
                ]
            ];
            return $this->respond($response, 200);
        } else {
            return $this->fail('Gagal memperbarui profile', 500);
        }
    }

    /**
     * Logout Siswa
     * POST /api/siswa/logout
     */
    public function logout()
    {
        $authHeader = $this->request->getHeader('Authorization');
        if (!$authHeader) {
            return $this->fail('Token diperlukan', 401);
        }

        // Untuk sederhananya, kita tidak perlu blacklist token
        // Client hanya perlu menghapus token locally

        $response = [
            'status' => 'success',
            'message' => 'Logout berhasil',
            'data' => null
        ];

        return $this->respond($response, 200);
    }

    /**
     * OPTIONS method untuk CORS
     */
    public function options()
    {
        return $this->respond(null, 200);
    }

    /**
     * Simpan login history
     */
    private function saveLoginHistory($userId, $nis)
    {
        $db = \Config\Database::connect();

        $data = [
            'nis' => $nis,
            'login_time' => date('Y-m-d H:i:s'),
            'device_info' => $this->request->getUserAgent() ?: 'Unknown',
            'ip_address' => $this->request->getIPAddress(),
            'waktu_dibuat' => date('Y-m-d H:i:s')
        ];

        $db->table('siswa_login_history')->insert($data);

        // Keep only last 50 login records (simpler approach for MySQL compatibility)
        $allRecords = $db->table('siswa_login_history')
                       ->where('nis', $nis)
                       ->orderBy('login_time', 'DESC')
                       ->get()
                       ->getResultArray();

        if (count($allRecords) > 50) {
            // Get IDs to delete (older records beyond 50)
            $toDelete = array_slice($allRecords, 50);
            $idsToDelete = array_column($toDelete, 'id');

            if (!empty($idsToDelete)) {
                $db->table('siswa_login_history')
                   ->whereIn('id', $idsToDelete)
                   ->delete();
            }
        }
    }

    /**
     * Get login history terakhir
     */
    private function getLatestLoginHistory($nis, $limit = 5)
    {
        $db = \Config\Database::connect();

        return $db->table('siswa_login_history')
            ->where('nis', $nis)
            ->orderBy('login_time', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Extract user ID dari simple token
     */
    private function extractUserIdFromToken($token)
    {
        try {
            $decoded = base64_decode($token);
            if ($decoded && strpos($decoded, ':') !== false) {
                list($userId, $timestamp) = explode(':', $decoded);

                // Check if token is not too old (24 hours)
                if (time() - $timestamp < 86400) {
                    return (int)$userId;
                }
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }
}