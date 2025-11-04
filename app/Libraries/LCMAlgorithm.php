<?php

namespace App\Libraries;

/**
 * Linear Congruent Method (LCM) Algorithm Library
 *
 * Library ini mengimplementasikan algoritma Linear Congruent Method (LCM)
 * untuk menghasilkan bilangan acak pseudo-random dengan parameter yang telah ditentukan.
 * Algoritma ini digunakan untuk mengacak soal dan jawaban dalam sistem pembelajaran
 * kaidah bahasa Arab.
 *
 * Parameter LCM (hardcoded sesuai penelitian skripsi):
 * - a (multiplier) = 10
 * - c (increment) = 23
 * - m (modulus) = 29
 * - X0 (seed) = dinamis berdasarkan timestamp + user_id
 *
 * Formula: Xn+1 = (a × Xn + c) mod m
 *
 * @author Khozinnatul Ulum (20210810076)
 * @version 1.0.0
 * @since 2025-11-04
 */
class LCMAlgorithm
{
    /**
     * Parameter LCM yang hardcoded sesuai penelitian skripsi
     *
     * @var int
     */
    private const MULTIPLIER = 10;      // a
    private const INCREMENT = 23;       // c
    private const MODULUS = 29;         // m

    /**
     * Seed yang digunakan untuk generate sequence
     *
     * @var int
     */
    private $seed;

    /**
     * Konstruktor class
     *
     * @param int|null $seed Seed awal untuk LCM. Jika null, akan digenerate dari timestamp
     */
    public function __construct(?int $seed = null)
    {
        $this->seed = $seed ?? $this->generateSeed();
    }

    /**
     * Generate seed dari timestamp dan user_id
     *
     * @param int|null $userId ID user untuk membuat seed unik per user
     * @return int Seed yang digenerate
     */
    public function generateSeed(?int $userId = null): int
    {
        $timestamp = time();
        $microtime = (int)(microtime(true) * 1000);

        if ($userId) {
            // Kombinasi timestamp + user_id untuk seed unik
            return $timestamp + $userId + $microtime;
        }

        return $timestamp + $microtime;
    }

    /**
     * Generate satu bilangan acak berikutnya menggunakan LCM
     *
     * @return int Bilangan acak yang dihasilkan (0 - m-1)
     */
    public function next(): int
    {
        $this->seed = (self::MULTIPLIER * $this->seed + self::INCREMENT) % self::MODULUS;
        return $this->seed;
    }

    /**
     * Generate sequence bilangan acak sebanyak n
     *
     * @param int $count Jumlah bilangan yang ingin di-generate
     * @return array Array of random numbers
     */
    public function generateSequence(int $count): array
    {
        $sequence = [];

        for ($i = 0; $i < $count; $i++) {
            $sequence[] = $this->next();
        }

        return $sequence;
    }

    /**
     * Generate bilangan acak dalam range tertentu
     *
     * @param int $min Nilai minimum
     * @param int $max Nilai maksimum
     * @return int Bilangan acak dalam range [min, max]
     */
    public function generateInRange(int $min, int $max): int
    {
        $randomValue = $this->next();
        $range = $max - $min + 1;

        return $min + ($randomValue % $range);
    }

    /**
     * Shuffle array menggunakan LCM
     *
     * @param array $array Array yang akan di-shuffle
     * @param bool $preserveKeys Apakah keys array dipertahankan
     * @return array Array yang sudah di-shuffle
     */
    public function shuffleArray(array $array, bool $preserveKeys = false): array
    {
        $shuffled = [];
        $indices = array_keys($array);

        // Acak indeks menggunakan LCM
        $shuffledIndices = $this->shuffleIndices($indices);

        // Bangun array baru dengan indeks yang sudah diacak
        foreach ($shuffledIndices as $index) {
            if ($preserveKeys) {
                $shuffled[$index] = $array[$index];
            } else {
                $shuffled[] = $array[$index];
            }
        }

        return $shuffled;
    }

    /**
     * Shuffle indeks array
     *
     * @param array $indices Array indeks yang akan di-shuffle
     * @return array Indeks yang sudah di-shuffle
     */
    private function shuffleIndices(array $indices): array
    {
        $shuffled = $indices;
        $n = count($shuffled);

        // Fisher-Yates shuffle dengan LCM
        for ($i = $n - 1; $i > 0; $i--) {
            $j = $this->generateInRange(0, $i);
            // Swap
            $temp = $shuffled[$i];
            $shuffled[$i] = $shuffled[$j];
            $shuffled[$j] = $temp;
        }

        return $shuffled;
    }

    /**
     * Generate indeks soal untuk quiz
     *
     * @param int $totalSoal Jumlah total soal yang tersedia
     * @param int $jumlahYangDiambil Jumlah soal yang akan diambil
     * @return array Array indeks soal yang sudah diacak
     */
    public function generateQuestionIndices(int $totalSoal, int $jumlahYangDiambil): array
    {
        if ($totalSoal <= $jumlahYangDiambil) {
            // Jika total soal <= yang diambil, ambil semua dengan acak
            $indices = range(0, $totalSoal - 1);
            return $this->shuffleArray($indices);
        }

        $indices = range(0, $totalSoal - 1);
        $selectedIndices = [];

        // Pilih indeks secara acak tanpa duplikasi
        for ($i = 0; $i < $jumlahYangDiambil; $i++) {
            $randomIndex = $this->generateInRange(0, count($indices) - 1);
            $selectedIndices[] = $indices[$randomIndex];

            // Hapus indeks yang sudah dipilih
            array_splice($indices, $randomIndex, 1);
        }

        return $selectedIndices;
    }

    /**
     * Generate data quiz lengkap dengan LCM
     *
     * @param array $soalList Array semua soal yang tersedia
     * @param int $jumlahSoal Jumlah soal yang akan ditampilkan
     * @param bool $shuffleAnswers Apakah jawaban juga diacak
     * @return array Data quiz yang sudah disiapkan
     */
    public function generateQuizData(array $soalList, int $jumlahSoal = 20, bool $shuffleAnswers = true): array
    {
        $totalSoal = count($soalList);

        if ($totalSoal === 0) {
            return [
                'success' => false,
                'message' => 'Tidak ada soal tersedia',
                'data' => []
            ];
        }

        // Generate indeks soal yang akan digunakan
        $questionIndices = $this->generateQuestionIndices($totalSoal, $jumlahSoal);

        $quizData = [];
        foreach ($questionIndices as $index) {
            $soal = $soalList[$index];

            // Acak jawaban jika diperlukan
            if ($shuffleAnswers && isset($soal['pilihan_jawaban']) && is_array($soal['pilihan_jawaban'])) {
                $shuffledJawaban = $this->shuffleArray($soal['pilihan_jawaban']);
                $soal['pilihan_jawaban'] = $shuffledJawaban;
            }

            $quizData[] = $soal;
        }

        return [
            'success' => true,
            'message' => 'Data quiz berhasil di-generate',
            'data' => $quizData,
            'metadata' => [
                'total_soal_tersedia' => $totalSoal,
                'jumlah_soal_diambil' => count($quizData),
                'seed_digunakan' => $this->seed,
                'lcm_parameters' => [
                    'a' => self::MULTIPLIER,
                    'c' => self::INCREMENT,
                    'm' => self::MODULUS
                ]
            ]
        ];
    }

    /**
     * Uji chi-square untuk validasi distribusi LCM
     *
     * @param int $sampleSize Jumlah sample untuk pengujian
     * @return array Hasil uji chi-square
     */
    public function chiSquareTest(int $sampleSize = 1000): array
    {
        // Generate sample data
        $samples = [];
        for ($i = 0; $i < $sampleSize; $i++) {
            $samples[] = $this->next();
        }

        // Hitung frekuensi setiap nilai (0 - m-1)
        $frequencies = array_fill(0, self::MODULUS, 0);
        foreach ($samples as $sample) {
            $frequencies[$sample]++;
        }

        // Expected frequency (uniform distribution)
        $expectedFrequency = $sampleSize / self::MODULUS;

        // Hitung chi-square statistic
        $chiSquare = 0;
        foreach ($frequencies as $observed) {
            $chiSquare += pow($observed - $expectedFrequency, 2) / $expectedFrequency;
        }

        // Degrees of freedom = m - 1
        $degreesOfFreedom = self::MODULUS - 1;

        // Critical value untuk alpha = 0.05 (dari chi-square table)
        // Untuk df = 28, critical value ≈ 41.337
        $criticalValue = 41.337;

        $isUniform = $chiSquare <= $criticalValue;

        return [
            'success' => true,
            'chi_square_statistic' => round($chiSquare, 4),
            'critical_value' => $criticalValue,
            'degrees_of_freedom' => $degreesOfFreedom,
            'alpha' => 0.05,
            'is_uniform_distribution' => $isUniform,
            'sample_size' => $sampleSize,
            'frequencies' => $frequencies,
            'expected_frequency' => round($expectedFrequency, 2),
            'conclusion' => $isUniform ?
                'Distribusi LCM seragam (terbukti valid)' :
                'Distribusi LCM tidak seragam (perlu evaluasi parameter)'
        ];
    }

    /**
     * Reset seed ke nilai awal
     *
     * @param int|null $newSeed Seed baru, jika null gunakan generateSeed()
     * @return void
     */
    public function resetSeed(?int $newSeed = null): void
    {
        $this->seed = $newSeed ?? $this->generateSeed();
    }

    /**
     * Get current seed
     *
     * @return int Seed saat ini
     */
    public function getCurrentSeed(): int
    {
        return $this->seed;
    }

    /**
     * Get LCM parameters
     *
     * @return array Parameter LCM yang digunakan
     */
    public function getParameters(): array
    {
        return [
            'multiplier' => self::MULTIPLIER,
            'increment' => self::INCREMENT,
            'modulus' => self::MODULUS,
            'current_seed' => $this->seed
        ];
    }

    /**
     * Reproduce sequence dari seed tertentu (untuk debugging/testing)
     *
     * @param int $seed Seed awal
     * @param int $count Jumlah bilangan yang ingin di-generate
     * @return array Sequence yang dihasilkan
     */
    public function reproduceSequence(int $seed, int $count): array
    {
        $originalSeed = $this->seed;
        $this->seed = $seed;

        $sequence = $this->generateSequence($count);

        // Restore original seed
        $this->seed = $originalSeed;

        return $sequence;
    }

    /**
     * Generate unique session ID untuk quiz
     *
     * @param int $userId ID user
     * @param int $materiId ID materi
     * @return string Unique session ID
     */
    public function generateSessionId(int $userId, int $materiId): string
    {
        $seed = $this->generateSeed($userId + $materiId);
        $timestamp = time();

        return hash('sha256', $seed . $timestamp . $userId . $materiId);
    }

    /**
     * Debug: Print sequence generation info
     *
     * @param int $count Jumlah bilangan yang akan di-generate
     * @return array Debug information
     */
    public function debugSequence(int $count = 10): array
    {
        $originalSeed = $this->seed;
        $sequence = [];

        for ($i = 0; $i < $count; $i++) {
            $step = [
                'iteration' => $i + 1,
                'seed_before' => $this->seed,
                'formula' => sprintf("(%d × %d + %d) mod %d", self::MULTIPLIER, $this->seed, self::INCREMENT, self::MODULUS),
                'result' => $this->next(),
                'seed_after' => $this->seed
            ];
            $sequence[] = $step;
        }

        // Restore original seed
        $this->seed = $originalSeed;

        return [
            'parameters' => $this->getParameters(),
            'sequence' => $sequence,
            'total_generated' => count($sequence)
        ];
    }

    /**
     * Shuffle soal untuk sesi pembelajaran (compatibility dengan existing code)
     *
     * @param array $questions Array soal
     * @param int $seed Seed LCM
     * @return array Array soal yang sudah diacak
     */
    public function shuffleQuestions(array $questions, int $seed): array
    {
        $originalSeed = $this->seed;
        $this->seed = $seed;

        $shuffled = $this->shuffleArray($questions);

        // Restore original seed
        $this->seed = $originalSeed;

        return $shuffled;
    }

    /**
     * Shuffle jawaban untuk setiap soal (compatibility dengan existing code)
     *
     * @param array $questions Array soal dengan jawaban
     * @param int $baseSeed Seed dasar
     * @return array Array soal dengan jawaban yang diacak
     */
    public function shuffleAnswers(array $questions, int $baseSeed): array
    {
        $originalSeed = $this->seed;

        foreach ($questions as $index => &$question) {
            if (isset($question['pilihan_jawaban']) && is_array($question['pilihan_jawaban'])) {
                // Gunakan seed yang berbeda untuk setiap soal
                $this->seed = $baseSeed + $index;
                $question['pilihan_jawaban'] = $this->shuffleArray($question['pilihan_jawaban']);
            }
        }

        // Restore original seed
        $this->seed = $originalSeed;

        return $questions;
    }

    /**
     * Generate random soal untuk sesi pembelajaran (compatibility dengan existing code)
     *
     * @param array $allQuestions Semua soal yang tersedia
     * @param int $jumlahSoal Jumlah soal yang dibutuhkan
     * @param int $seed Seed LCM
     * @return array Soal-soal yang sudah diacak
     */
    public function generateRandomQuestions(array $allQuestions, int $jumlahSoal, int $seed): array
    {
        $originalSeed = $this->seed;
        $this->seed = $seed;

        $result = $this->generateQuizData($allQuestions, $jumlahSoal, true);

        // Restore original seed
        $this->seed = $originalSeed;

        return $result['success'] ? $result['data'] : [];
    }

    /**
     * Generate seed dari kombinasi user_id, timestamp, dan materi_id (compatibility)
     *
     * @param int $userId ID user
     * @param int $timestamp Unix timestamp
     * @param int $materiId ID materi
     * @return int Generated seed
     */
    public function generateSeedLegacy(int $userId, int $timestamp, int $materiId): int
    {
        return ($userId * 1000) + ($timestamp % 10000) + ($materiId * 100);
    }
}