<?php

namespace App\Libraries;

use App\Libraries\LCMAlgorithm;
use App\Models\SoalModel;
use App\Models\PilihanJawabanModel;
use App\Models\SesiLatihanModel;
use App\Models\DetailJawabanSiswaModel;
use App\Models\RiwayatBelajarModel;

/**
 * Quiz Engine Library
 *
 * Engine untuk generate dan mengelola sesi quiz pembelajaran
 * menggunakan algoritma LCM untuk pengacakan soal
 */
class QuizEngine
{
    protected $lcm;
    protected $soalModel;
    protected $pilihanJawabanModel;
    protected $sesiLatihanModel;
    protected $detailJawabanSiswaModel;
    protected $riwayatBelajarModel;

    public function __construct()
    {
        $this->lcm = new LCMAlgorithm();
        $this->soalModel = new SoalModel();
        $this->pilihanJawabanModel = new PilihanJawabanModel();
        $this->sesiLatihanModel = new SesiLatihanModel();
        $this->detailJawabanSiswaModel = new DetailJawabanSiswaModel();
        $this->riwayatBelajarModel = new RiwayatBelajarModel();
    }

    /**
     * Mulai sesi pembelajaran baru
     *
     * @param int $idSiswa ID siswa
     * @param int $idMateri ID materi kaidah
     * @param int $jumlahSoal Jumlah soal yang ingin dikerjakan
     * @return int|null ID sesi yang dibuat
     */
    public function mulaiSesi(int $idSiswa, int $idMateri, int $jumlahSoal = 20): ?int
    {
        // Generate seed unik untuk sesi ini
        $seed = $this->lcm->generateSeed($idSiswa, time(), $idMateri);

        // Start session di database
        $sesiId = $this->sesiLatihanModel->startSession($idSiswa, $idMateri, $jumlahSoal);

        if (!$sesiId) {
            return null;
        }

        // Update seed yang digunakan
        $this->sesiLatihanModel->update($sesiId, ['seed_digunakan' => $seed]);

        // Update riwayat belajar
        $this->riwayatBelajarModel->startLearning($idSiswa, $idMateri);

        return $sesiId;
    }

    /**
     * Generate soal untuk sesi pembelajaran
     *
     * @param int $idSesi ID sesi
     * @return array|null Array soal yang sudah diacak
     */
    public function generateSoalSesi(int $idSesi): ?array
    {
        // Get sesi info
        $sesi = $this->sesiLatihanModel->find($idSesi);
        if (!$sesi) {
            return null;
        }

        // Get all questions for the material
        $allQuestions = $this->soalModel->getWithAnswers($sesi['id_materi']);

        if (empty($allQuestions)) {
            return [];
        }

        // Generate random questions using LCM
        $randomQuestions = $this->lcm->generateRandomQuestions(
            $allQuestions,
            $sesi['total_soal'],
            $sesi['seed_digunakan']
        );

        return $randomQuestions;
    }

    /**
     * Submit jawaban siswa
     *
     * @param int $idSesi ID sesi
     * @param int $idSoal ID soal
     * @param int $idPilihan ID pilihan jawaban
     * @param int $urutanSoal Urutan soal dalam sesi
     * @return bool Status submit
     */
    public function submitJawaban(int $idSesi, int $idSoal, int $idPilihan, int $urutanSoal): bool
    {
        // Check if sesi exists and is active
        $sesi = $this->sesiLatihanModel->find($idSesi);
        if (!$sesi || $sesi['status'] !== 'sedang_berjalan') {
            return false;
        }

        // Save answer
        $this->detailJawabanSiswaModel->saveAnswer($idSesi, $idSoal, $idPilihan, $urutanSoal);

        return true;
    }

    /**
     * Selesaikan sesi pembelajaran
     *
     * @param int $idSesi ID sesi
     * @return array Hasil akhir sesi
     */
    public function selesaikanSesi(int $idSesi): array
    {
        // Get all answers for this session
        $answers = $this->detailJawabanSiswa->getBySession($idSesi);

        // Calculate correct answers
        $jawabanBenar = $this->detailJawabanSiswa->getCorrectAnswersBySession($idSesi);

        // Get total questions in session
        $sesi = $this->sesiLatihanModel->find($idSesi);
        $totalSoal = $sesi ? $sesi['total_soal'] : 0;

        // Finish the session
        $this->sesiLatihanModel->finishSession($idSesi, count($jawabanBenar));

        // Update learning progress
        $persentase = $totalSoal > 0 ? (count($jawabanBenar) / $totalSoal) * 100 : 0;
        $this->riwayatBelajarModel->updateProgress(
            $sesi['id_siswa'],
            $sesi['id_materi'],
            $persentase
        );

        return [
            'sesi_id' => $idSesi,
            'total_soal' => $totalSoal,
            'soal_benar' => count($jawabanBenar),
            'skor' => round($persentase, 2),
            'waktu_mulai' => $sesi['waktu_mulai'],
            'waktu_selesai' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Get detail sesi pembelajaran
     *
     * @param int $idSesi ID sesi
     * @return array|null Detail sesi
     */
    public function getDetailSesi(int $idSesi): ?array
    {
        $sesi = $this->sesiLatihanModel->getWithDetails($idSesi);
        if (!$sesi) {
            return null;
        }

        // Get answers for this session
        $answers = $this->detailJawabanSiswa->getBySession($idSesi);

        // Get session statistics
        $stats = $this->detailJawabanSiswa->getSessionStats($idSesi);

        return [
            'sesi' => $sesi,
            'answers' => $answers,
            'stats' => $stats
        ];
    }

    /**
     * Generate preview soal (tanpa menyimpan ke database)
     *
     * @param int $idMateri ID materi
     * @param int $jumlahSoal Jumlah soal
     * @param int $seed Opsional seed untuk reproducibility
     * @return array Preview soal yang diacak
     */
    public function generatePreviewSoal(int $idMateri, int $jumlahSoal, ?int $seed = null): array
    {
        $seed = $seed ?: time();

        // Get all questions for the material
        $allQuestions = $this->soalModel->getWithAnswers($idMateri);

        if (empty($allQuestions)) {
            return [];
        }

        // Generate random questions using LCM
        return $this->lcm->generateRandomQuestions($allQuestions, $jumlahSoal, $seed);
    }

    /**
     * Validate jawaban siswa
     *
     * @param int $idPilihan ID pilihan jawaban
     * @param int $idSoal ID soal
     * @return bool True jika jawaban benar
     */
    public function validateJawaban(int $idPilihan, int $idSoal): bool
    {
        $jawaban = $this->pilihanJawabanModel->find($idPilihan);

        if (!$jawaban || $jawaban['id_soal'] !== $idSoal) {
            return false;
        }

        return $jawaban['is_benar'];
    }

    /**
     * Hitung progress pembelajaran siswa
     *
     * @param int $idSiswa ID siswa
     * @param int $idMateri ID materi (opsional)
     * @return array Progress data
     */
    public function hitungProgress(int $idSiswa, ?int $idMateri = null): array
    {
        if ($idMateri) {
            // Progress untuk materi tertentu
            $riwayat = $this->riwayatBelajarModel->where('id_siswa', $idSiswa)
                                                    ->where('id_materi', $idMateri)
                                                    ->first();

            if ($riwayat) {
                return [
                    'id_materi' => $idMateri,
                    'status' => $riwayat['status'],
                    'persentase' => floatval($riwayat['persentase_penguasaan']),
                    'waktu_akses_terakhir' => $riwayat['waktu_akses_terakhir']
                ];
            }
        } else {
            // Progress keseluruhan
            $stats = $this->riwayatBelajarModel->getStats($idSiswa);
            return $stats ?: [];
        }
    }

    /**
     * Get sesi pembelajaran yang sedang berjalan
     *
     * @param int $idSiswa ID siswa
     * @return array Active sessions
     */
    public function getActiveSessions(int $idSiswa): array
    {
        return $this->sesiLatihanModel->where('id_siswa', $idSiswa)
                                     ->where('status', 'sedang_berjalan')
                                     ->orderBy('waktu_mulai', 'DESC')
                                     ->findAll();
    }

    /**
     * Check apakah siswa bisa memulai sesi
     *
     * @param int $idSiswa ID siswa
     * @param int $idMateri ID materi
     * @return bool True jika bisa memulai
     */
    public function bisaMulaiSesi(int $idSiswa, int $idMateri): bool
    {
        // Check if there's already an active session
        $activeSession = $this->sesiLatihanModel->where('id_siswa', $idSiswa)
                                                      ->where('id_materi', $idMateri)
                                                      ->where('status', 'sedang_berjalan')
                                                      ->first();

        if ($activeSession) {
            return false; // Sudah ada sesi aktif
        }

        // Check if there are questions available
        $jumlahSoal = $this->soalModel->where('id_materi', $idMateri)->countAllResults();

        return $jumlahSoal > 0;
    }

    /**
     * Generate seed untuk debugging/reproducibility
     *
     * @param int $idSiswa ID siswa
     * @param int $idMateri ID materi
     * @param int $timestamp Timestamp (opsional)
     * @return int Generated seed
     */
    public function generateSeed(int $idSiswa, int $idMateri, ?int $timestamp = null): int
    {
        return $this->lcm->generateSeed($idSiswa, $timestamp ?? time(), $idMateri);
    }

    /**
     * Simulasi pengacakan soal untuk demo/educational
     *
     * @param array $soal Array soal
     * @param int $seed Seed untuk reproducibility
     * @return array Hasil simulasi
     */
    public function simulasiPengacakan(array $soal, int $seed): array
    {
        $originalOrder = [];
        foreach ($soal as $index => $s) {
            $originalOrder[] = $index;
        }

        $shuffledSoal = $this->lcm->shuffleArray($soal, $seed);
        $shuffledOrder = [];

        // Map shuffled questions to their original indices
        $originalToShuffled = [];
        foreach ($shuffledSoal as $newIndex => $question) {
            $originalIndex = array_search($question, $soal, true);
            if ($originalIndex !== false) {
                $originalToShuffled[$originalIndex] = $newIndex;
            }
        }

        return [
            'seed' => $seed,
            'jumlah_soal' => count($soal),
            'original_order' => $originalOrder,
            'shuffled_order' => $originalToShuffled,
            'shuffled_questions' => $shuffledSoal
        ];
    }

    /**
     * Get statistik performa soal
     *
     * @param int $idMateri ID materi (opsional)
     * @return array Statistik performa soal
     */
    public function getStatistikPerformaSoal(?int $idMateri = null): array
    {
        if ($idMateri) {
            // Statistik per materi
            $questions = $this->soalModel->getWithAnswers($idMateri);
            $totalQuestions = count($questions);
            $totalOptions = 0;

            foreach ($questions as $question) {
                if (isset($question['jawaban'])) {
                    $totalOptions += count($question['jawaban']);
                }
            }

            $avgOptionsPerQuestion = $totalQuestions > 0 ? $totalOptions / $totalQuestions : 0;

            return [
                'id_materi' => $idMateri,
                'total_soal' => $totalQuestions,
                'total_options' => $totalOptions,
                'avg_options_per_question' => round($avgOptionsPerQuestion, 2)
            ];
        } else {
            // Statistik keseluruhan
            $stats = $this->soalModel->getStats();
            $jawabanStats = $this->pilihanJawabanModel->getQuestionAnswerCount(1);

            return [
                'total_soal' => $stats['total_soal'] ?? 0,
                'total_options' => array_sum($jawabanStats) ?? 0,
                'avg_options_per_question' => $stats['total_soal'] > 0 ?
                    round((array_sum($jawabanStats) ?? 0) / $stats['total_soal'], 2) : 0
            ];
        }
    }

    /**
     * Get seed information untuk debugging
     *
     * @param int $idSesi ID sesi
     * @return array|null Seed info
     */
    public function getSeedInfo(int $idSesi): ?array
    {
        $sesi = $this->sesiLatihanModel->find($idSesi);

        if (!$sesi) {
            return null;
        }

        return [
            'seed_digunakan' => $sesi['seed_digunakan'],
            'user_id' => $sesi['id_siswa'],
            'materi_id' => $sesi['id_materi'],
            'timestamp' => strtotime($sesi['waktu_mulai']),
            'formula' => "({$sesi[id_siswa]} × 1000) + (" . strtotime($sesi['waktu_mulai']) . " % 10000) + ({$sesi[id_materi]} × 100) = {$sesi['seed_digunakan']}"
        ];
    }
}