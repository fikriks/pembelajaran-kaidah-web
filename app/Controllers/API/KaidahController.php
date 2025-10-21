<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\MateriKaidahModel;
use App\Models\RiwayatBelajarModel;

class KaidahController extends BaseController
{
    protected $kaidahModel;
    protected $riwayatBelajarModel;

    public function __construct()
    {
        $this->kaidahModel = new MateriKaidahModel();
        $this->riwayatBelajarModel = new RiwayatBelajarModel();
    }

    /**
     * Get all materi kaidah
     * GET /api/kaidah
     */
    public function index()
    {
        $userId = $this->getUserIdFromToken();

        if (!$userId) {
            return $this->respondWithError('Token tidak valid', 401);
        }

        $filters = $this->request->getGet();
        $kaidahList = $this->kaidahModel->getAllWithProgress($userId, $filters);

        return $this->respondWithSuccess($kaidahList, 'Daftar kaidah berhasil diambil');
    }

    /**
     * Get detail materi kaidah
     * GET /api/kaidah/{id}
     */
    public function show($id)
    {
        $userId = $this->getUserIdFromToken();

        if (!$userId) {
            return $this->respondWithError('Token tidak valid', 401);
        }

        $kaidah = $this->kaidahModel->getWithDetails($id);

        if (!$kaidah) {
            return $this->respondWithError('Kaidah tidak ditemukan', 404);
        }

        // Get user progress for this kaidah
        $progress = $this->riwayatBelajarModel
            ->where('id_siswa', $userId)
            ->where('id_materi', $id)
            ->first();

        $kaidah['user_progress'] = $progress ? [
            'status' => $progress['status'],
            'persentase' => floatval($progress['persentase_penguasaan']),
            'waktu_akses_terakhir' => $progress['waktu_akses_terakhir']
        ] : [
            'status' => 'belum_dimulai',
            'persentase' => 0,
            'waktu_akses_terakhir' => null
        ];

        return $this->respondWithSuccess($kaidah, 'Detail kaidah berhasil diambil');
    }

    /**
     * Get progress belajar untuk kaidah tertentu
     * GET /api/kaidah/{id}/progress
     */
    public function progress($id)
    {
        $userId = $this->getUserIdFromToken();

        if (!$userId) {
            return $this->respondWithError('Token tidak valid', 401);
        }

        // Check if kaidah exists
        $kaidah = $this->kaidahModel->find($id);

        if (!$kaidah) {
            return $this->respondWithError('Kaidah tidak ditemukan', 404);
        }

        $progress = $this->riwayatBelajarModel
            ->where('id_siswa', $userId)
            ->where('id_materi', $id)
            ->first();

        if (!$progress) {
            return $this->respondWithSuccess([
                'status' => 'belum_dimulai',
                'persentase' => 0,
                'waktu_akses_terakhir' => null
            ], 'Progress berhasil diambil');
        }

        return $this->respondWithSuccess([
            'status' => $progress['status'],
            'persentase' => floatval($progress['persentase_penguasaan']),
            'waktu_akses_terakhir' => $progress['waktu_akses_terakhir']
        ], 'Progress berhasil diambil');
    }

    /**
     * Start learning kaidah (update riwayat belajar)
     * POST /api/kaidah/{id}/start
     */
    public function start($id)
    {
        $userId = $this->getUserIdFromToken();

        if (!$userId) {
            return $this->respondWithError('Token tidak valid', 401);
        }

        // Check if kaidah exists
        $kaidah = $this->kaidahModel->find($id);

        if (!$kaidah) {
            return $this->respondWithError('Kaidah tidak ditemukan', 404);
        }

        // Check if there are questions for this kaidah
        $soalCount = $this->kaidahModel->countSoal($id);

        if ($soalCount === 0) {
            return $this->respondWithError('Belum ada soal untuk kaidah ini', 400);
        }

        // Update or create riwayat belajar
        $existingProgress = $this->riwayatBelajarModel
            ->where('id_siswa', $userId)
            ->where('id_materi', $id)
            ->first();

        $data = [
            'id_siswa' => $userId,
            'id_materi' => $id,
            'status' => 'sedang_belajar',
            'waktu_akses_terakhir' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s')
        ];

        if ($existingProgress) {
            // Update existing
            $this->riwayatBelajarModel->update($existingProgress['id_riwayat'], $data);
            $riwayatId = $existingProgress['id_riwayat'];
        } else {
            // Create new
            $data['persentase_penguasaan'] = 0;
            $data['waktu_dibuat'] = date('Y-m-d H:i:s');
            $riwayatId = $this->riwayatBelajarModel->insert($data);
        }

        return $this->respondWithSuccess([
            'riwayat_id' => $riwayatId,
            'kaidah' => $kaidah,
            'total_soal' => $soalCount
        ], 'Pembelajaran dimulai');
    }

    /**
     * Search kaidah by keyword
     * GET /api/kaidah/search?q={keyword}
     */
    public function search()
    {
        $userId = $this->getUserIdFromToken();

        if (!$userId) {
            return $this->respondWithError('Token tidak valid', 401);
        }

        $keyword = $this->request->getGet('q');

        if (empty($keyword)) {
            return $this->respondWithError('Keyword pencarian harus diisi', 400);
        }

        $results = $this->kaidahModel->searchWithProgress($userId, $keyword);

        return $this->respondWithSuccess($results, 'Hasil pencarian berhasil diambil');
    }

    /**
     * Get filter options
     * GET /api/kaidah/filters
     */
    public function filters()
    {
        $userId = $this->getUserIdFromToken();

        if (!$userId) {
            return $this->respondWithError('Token tidak valid', 401);
        }

        $tingkatKesulitan = $this->kaidahModel->getTingkatKesulitanOptions();
        $statusOptions = [
            ['value' => 'belum_dimulai', 'label' => 'Belum Dimulai'],
            ['value' => 'sedang_belajar', 'label' => 'Sedang Belajar'],
            ['value' => 'selesai', 'label' => 'Selesai']
        ];

        return $this->respondWithSuccess([
            'tingkat_kesulitan' => $tingkatKesulitan,
            'status' => $statusOptions
        ], 'Filter options berhasil diambil');
    }

    /**
     * Helper method to get user ID from token
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