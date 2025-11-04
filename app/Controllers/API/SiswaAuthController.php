<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use CodeIgniter\HTTP\ResponseInterface;

class SiswaAuthController extends BaseController
{
    protected $siswaModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    }

    public function login()
    {
        // Handle preflight OPTIONS request
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            return $this->response->setStatusCode(200);
        }

        $json = $this->request->getJSON();

        if (!$json) {
            return $this->respondWithError('Invalid JSON format', 400);
        }

        $nis = $json->nis ?? null;
        $password = $json->password ?? null;
        $deviceInfo = $json->device_info ?? null;

        // Validation
        if (!$nis || !$password) {
            return $this->respondWithError('NIS dan password wajib diisi', 400);
        }

        // Find siswa by NIS
        $siswa = $this->siswaModel->getSiswaByNis($nis);

        if (!$siswa) {
            return $this->respondWithError('NIS atau password salah', 401);
        }

        // Verify password
        if (!$this->siswaModel->verifyPassword($nis, $password)) {
            return $this->respondWithError('NIS atau password salah', 401);
        }

        // Check if siswa is active
        if ($siswa['status'] !== 'AKTIF') {
            return $this->respondWithError('Akun siswa tidak aktif', 403);
        }

        // Record login history
        $this->recordLoginHistory($siswa['nis'], $deviceInfo);

        // Success response
        $response = [
            'status' => 'success',
            'message' => 'Login berhasil',
            'data' => [
                'siswa' => [
                    'id' => $siswa['id'],
                    'nis' => $siswa['nis'],
                    'nama_lengkap' => $siswa['nama_lengkap'],
                    'jenis_kelamin' => $siswa['jenis_kelamin'],
                    'kelas' => $siswa['kelas'],
                    'status' => $siswa['status']
                ],
                'token' => $this->generateSimpleToken($siswa),
                'login_time' => date('Y-m-d H:i:s')
            ]
        ];

        return $this->response->setJSON($response, 200);
    }

    public function profile()
    {
        // Handle preflight OPTIONS request
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            return $this->response->setStatusCode(200);
        }

        // Get token from Authorization header
        $token = $this->request->getHeaderLine('Authorization');

        // Remove "Bearer " prefix if present
        if (strpos($token, 'Bearer ') === 0) {
            $token = substr($token, 7);
        }

        if (!$token) {
            return $this->respondWithError('Token tidak ditemukan', 401);
        }

        // Validate simple token (basic approach for skripsi)
        $siswaData = $this->validateSimpleToken($token);

        if (!$siswaData) {
            return $this->respondWithError('Token tidak valid', 401);
        }

        // Get fresh siswa data
        $siswa = $this->siswaModel->find($siswaData['id']);

        if (!$siswa) {
            return $this->respondWithError('Data siswa tidak ditemukan', 404);
        }

        // Response
        $response = [
            'status' => 'success',
            'message' => 'Profile berhasil diambil',
            'data' => [
                'siswa' => [
                    'id' => $siswa['id'],
                    'nis' => $siswa['nis'],
                    'nama_lengkap' => $siswa['nama_lengkap'],
                    'jenis_kelamin' => $siswa['jenis_kelamin'],
                    'kelas' => $siswa['kelas'],
                    'status' => $siswa['status'],
                    'waktu_dibuat' => $siswa['waktu_dibuat']
                ]
            ]
        ];

        return $this->response->setJSON($response, 200);
    }

    private function recordLoginHistory($nis, $deviceInfo = null)
    {
        $db = \Config\Database::connect();

        $data = [
            'nis' => $nis,
            'login_time' => date('Y-m-d H:i:s'),
            'device_info' => $deviceInfo ?: 'Unknown Device',
            'ip_address' => $this->request->getIPAddress(),
            'waktu_dibuat' => date('Y-m-d H:i:s')
        ];

        $db->table('siswa_login_history')->insert($data);
    }

    private function generateSimpleToken($siswa)
    {
        // Simple token generation for skripsi purposes
        // Format: base64_encode(id:nis:timestamp:hash)
        $timestamp = time();
        $payload = $siswa['id'] . ':' . $siswa['nis'] . ':' . $timestamp;
        $hash = hash('sha256', $payload . 'secret_key_skripsi');
        $token = base64_encode($payload . ':' . $hash);

        return $token;
    }

    private function validateSimpleToken($token)
    {
        try {
            $decoded = base64_decode($token);
            if (!$decoded) {
                return false;
            }

            $parts = explode(':', $decoded);
            if (count($parts) !== 4) {
                return false;
            }

            $id = $parts[0];
            $nis = $parts[1];
            $timestamp = $parts[2];
            $hash = $parts[3];

            // Check if token is not too old (24 hours)
            if (time() - $timestamp > 86400) {
                return false;
            }

            // Verify hash
            $payload = $id . ':' . $nis . ':' . $timestamp;
            $expectedHash = hash('sha256', $payload . 'secret_key_skripsi');

            if ($hash !== $expectedHash) {
                return false;
            }

            // Verify siswa exists and matches
            $siswa = $this->siswaModel->find($id);
            if (!$siswa || $siswa['nis'] !== $nis) {
                return false;
            }

            return [
                'id' => $id,
                'nis' => $nis,
                'timestamp' => $timestamp
            ];

        } catch (\Exception $e) {
            return false;
        }
    }

    private function respondWithError($message, $code = 400)
    {
        $response = [
            'status' => 'error',
            'message' => $message,
            'data' => null
        ];

        return $this->response->setJSON($response, $code);
    }

    public function options()
    {
        // Handle CORS preflight
        return $this->response->setStatusCode(200);
    }
}