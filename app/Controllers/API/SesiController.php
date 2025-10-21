<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Libraries\QuizEngine;
use App\Models\SesiLatihanModel;
use App\Models\DetailJawabanSiswaModel;

class SesiController extends BaseController
{
    protected $quizEngine;
    protected $sesiModel;
    protected $detailJawabanModel;

    public function __construct()
    {
        $this->quizEngine = new QuizEngine();
        $this->sesiModel = new SesiLatihanModel();
        $this->detailJawabanModel = new DetailJawabanSiswaModel();
    }

    /**
     * Mulai sesi pembelajaran baru
     * POST /api/sesi/start
     */
    public function start()
    {
        $userId = $this->getUserIdFromToken();

        if (!$userId) {
            return $this->respondWithError('Token tidak valid', 401);
        }

        $rules = [
            'kaidah_id'   => 'required|integer',
            'jumlah_soal' => 'permit_empty|integer|greater_than[0]|less_than_equal[50]'
        ];

        if (!$this->validate($rules)) {
            return $this->respondWithError(
                'Validasi gagal',
                400,
                $this->validator->getErrors()
            );
        }

        $kaidahId = $this->request->getPost('kaidah_id');
        $jumlahSoal = $this->request->getPost('jumlah_soal') ?? 20;

        // Check if user can start session
        if (!$this->quizEngine->bisaMulaiSesi($userId, $kaidahId)) {
            return $this->respondWithError('Anda tidak bisa memulai sesi baru. Sesi sebelumnya masih berjalan.', 400);
        }

        // Start session
        $sesiId = $this->quizEngine->mulaiSesi($userId, $kaidahId, $jumlahSoal);

        if (!$sesiId) {
            return $this->respondWithError('Gagal memulai sesi pembelajaran', 500);
        }

        // Generate questions for this session
        $soalList = $this->quizEngine->generateSoalSesi($sesiId);

        if (empty($soalList)) {
            return $this->respondWithError('Tidak ada soal tersedia untuk kaidah ini', 400);
        }

        $sesi = $this->sesiModel->find($sesiId);

        return $this->respondWithSuccess([
            'sesi_id' => $sesiId,
            'kaidah_id' => $kaidahId,
            'total_soal' => count($soalList),
            'total_poin' => array_sum(array_column($soalList, 'poin')),
            'waktu_mulai' => $sesi['waktu_mulai'],
            'soal' => $soalList,
            'seed_used' => $sesi['seed_digunakan']
        ], 'Sesi pembelajaran berhasil dimulai');
    }

    /**
     * Get detail sesi pembelajaran
     * GET /api/sesi/{id}
     */
    public function show($id)
    {
        $userId = $this->getUserIdFromToken();

        if (!$userId) {
            return $this->respondWithError('Token tidak valid', 401);
        }

        // Check if session belongs to user
        $sesi = $this->sesiModel->where('id_sesi', $id)
                                 ->where('id_siswa', $userId)
                                 ->first();

        if (!$sesi) {
            return $this->respondWithError('Sesi tidak ditemukan', 404);
        }

        $detailSesi = $this->quizEngine->getDetailSesi($id);

        return $this->respondWithSuccess($detailSesi, 'Detail sesi berhasil diambil');
    }

    /**
     * Submit jawaban soal
     * POST /api/sesi/{id}/jawab
     */
    public function submitJawaban($id)
    {
        $userId = $this->getUserIdFromToken();

        if (!$userId) {
            return $this->respondWithError('Token tidak valid', 401);
        }

        // Check if session belongs to user and is active
        $sesi = $this->sesiModel->where('id_sesi', $id)
                                 ->where('id_siswa', $userId)
                                 ->where('status', 'sedang_berjalan')
                                 ->first();

        if (!$sesi) {
            return $this->respondWithError('Sesi tidak ditemukan atau sudah selesai', 404);
        }

        $rules = [
            'soal_id'     => 'required|integer',
            'pilihan_id'  => 'required|integer',
            'urutan_soal' => 'required|integer|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return $this->respondWithError(
                'Validasi gagal',
                400,
                $this->validator->getErrors()
            );
        }

        $soalId = $this->request->getPost('soal_id');
        $pilihanId = $this->request->getPost('pilihan_id');
        $urutanSoal = $this->request->getPost('urutan_soal');

        // Submit answer
        $success = $this->quizEngine->submitJawaban($id, $soalId, $pilihanId, $urutanSoal);

        if (!$success) {
            return $this->respondWithError('Gagal menyimpan jawaban', 500);
        }

        // Validate answer and return feedback
        $isCorrect = $this->quizEngine->validateJawaban($pilihanId, $soalId);

        return $this->respondWithSuccess([
            'is_correct' => $isCorrect,
            'message' => $isCorrect ? 'Jawaban benar!' : 'Jawaban salah, coba lagi di soal lain.'
        ], 'Jawaban berhasil disimpan');
    }

    /**
     * Selesaikan sesi pembelajaran
     * POST /api/sesi/{id}/finish
     */
    public function finish($id)
    {
        $userId = $this->getUserIdFromToken();

        if (!$userId) {
            return $this->respondWithError('Token tidak valid', 401);
        }

        // Check if session belongs to user and is active
        $sesi = $this->sesiModel->where('id_sesi', $id)
                                 ->where('id_siswa', $userId)
                                 ->where('status', 'sedang_berjalan')
                                 ->first();

        if (!$sesi) {
            return $this->respondWithError('Sesi tidak ditemukan atau sudah selesai', 404);
        }

        // Finish session and calculate results
        $hasil = $this->quizEngine->selesaikanSesi($id);

        return $this->respondWithSuccess($hasil, 'Sesi pembelajaran berhasil diselesaikan');
    }

    /**
     * Get hasil sesi pembelajaran
     * GET /api/sesi/{id}/hasil
     */
    public function hasil($id)
    {
        $userId = $this->getUserIdFromToken();

        if (!$userId) {
            return $this->respondWithError('Token tidak valid', 401);
        }

        // Check if session belongs to user
        $sesi = $this->sesiModel->where('id_sesi', $id)
                                 ->where('id_siswa', $userId)
                                 ->first();

        if (!$sesi) {
            return $this->respondWithError('Sesi tidak ditemukan', 404);
        }

        if ($sesi['status'] === 'sedang_berjalan') {
            return $this->respondWithError('Sesi belum selesai', 400);
        }

        $hasil = [
            'sesi_id' => $id,
            'total_soal' => $sesi['total_soal'],
            'soal_benar' => $sesi['soal_benar'],
            'skor' => floatval($sesi['skor']),
            'waktu_mulai' => $sesi['waktu_mulai'],
            'waktu_selesai' => $sesi['waktu_selesai'],
            'durasi_detik' => $sesi['durasi_detik']
        ];

        // Get detailed answers
        $answers = $this->detailJawabanModel->getBySession($id);
        $hasil['detail_jawaban'] = $answers;

        return $this->respondWithSuccess($hasil, 'Hasil sesi berhasil diambil');
    }

    /**
     * Get active sessions for user
     * GET /api/sesi/active
     */
    public function active()
    {
        $userId = $this->getUserIdFromToken();

        if (!$userId) {
            return $this->respondWithError('Token tidak valid', 401);
        }

        $activeSessions = $this->quizEngine->getActiveSessions($userId);

        return $this->respondWithSuccess($activeSessions, 'Sesi aktif berhasil diambil');
    }

    /**
     * Continue existing session
     * POST /api/sesi/{id}/continue
     */
    public function continue($id)
    {
        $userId = $this->getUserIdFromToken();

        if (!$userId) {
            return $this->respondWithError('Token tidak valid', 401);
        }

        // Check if session belongs to user and is active
        $sesi = $this->sesiModel->where('id_sesi', $id)
                                 ->where('id_siswa', $userId)
                                 ->where('status', 'sedang_berjalan')
                                 ->first();

        if (!$sesi) {
            return $this->respondWithError('Sesi tidak ditemukan atau sudah selesai', 404);
        }

        // Generate questions for this session (same order as before)
        $soalList = $this->quizEngine->generateSoalSesi($id);

        // Get user's current answers
        $jawabanUser = $this->detailJawabanModel->getBySession($id);
        $answeredQuestions = array_column($jawabanUser, 'id_soal');

        return $this->respondWithSuccess([
            'sesi_id' => $id,
            'kaidah_id' => $sesi['id_materi'],
            'total_soal' => count($soalList),
            'waktu_mulai' => $sesi['waktu_mulai'],
            'soal' => $soalList,
            'answered_questions' => $answeredQuestions,
            'seed_used' => $sesi['seed_digunakan']
        ], 'Sesi berhasil dilanjutkan');
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