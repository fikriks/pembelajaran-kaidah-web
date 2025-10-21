<?php

namespace App\Libraries;

/**
 * Linear Congruent Method (LCM) Algorithm Library
 *
 * Implementasi algoritma LCM untuk pengacakan soal-soal pembelajaran
 * Sesuai dengan parameter penelitian:
 * - a (pengali) = 10
 * - c (penambah) = 23
 * - m (modulus) = 29
 */
class LCMAlgorithm
{
    // Parameter LCM berdasarkan penelitian
    private const A = 10;  // Multiplier (pengali)
    private const C = 23;  // Increment (penambah)
    private const M = 29;  // Modulus

    /**
     * Generate sequence of random numbers using LCM
     *
     * @param int $seed Nilai awal (X0)
     * @param int $count Jumlah angka yang akan di-generate
     * @return array Array of random numbers
     */
    public function generate(int $seed, int $count): array
    {
        $result = [];
        $x = $seed;

        for ($i = 0; $i < $count; $i++) {
            $x = ($this::A * $x + $this::C) % $this::M;
            $result[] = $x;
        }

        return $result;
    }

    /**
     * Shuffle array menggunakan LCM
     *
     * @param array $array Array yang akan diacak
     * @param int $seed Seed untuk reproducible results
     * @return array Array yang sudah diacak
     */
    public function shuffleArray(array $array, int $seed): array
    {
        if (empty($array)) {
            return $array;
        }

        $count = count($array);
        if ($count <= 1) {
            return $array;
        }

        // Generate random indices using LCM
        $indices = $this->generate($seed, $count * 2); // Generate lebih untuk memastikan覆盖

        // Shuffle array using Fisher-Yates dengan LCM indices
        $shuffled = $array;
        for ($i = $count - 1; $i > 0; $i--) {
            $randomIndex = $indices[$count - 1 - $i] % ($i + 1);

            // Swap elements
            $temp = $shuffled[$i];
            $shuffled[$i] = $shuffled[$randomIndex];
            $shuffled[$randomIndex] = $temp;
        }

        return $shuffled;
    }

    /**
     * Shuffle list of questions dengan LCM
     *
     * @param array $questions Array soal
     * @param int $seed Seed LCM
     * @return array Array soal yang sudah diacak
     */
    public function shuffleQuestions(array $questions, int $seed): array
    {
        return $this->shuffleArray($questions, $seed);
    }

    /**
     * Shuffle jawaban untuk setiap soal
     *
     * @param array $questions Array soal dengan jawaban
     * @param int $baseSeed Seed dasar
     * @return array Array soal dengan jawaban yang diacak
     */
    public function shuffleAnswers(array $questions, int $baseSeed): array
    {
        foreach ($questions as $index => &$question) {
            if (isset($question['jawaban']) && is_array($question['jawaban'])) {
                // Gunakan seed yang berbeda untuk setiap soal
                $seed = $baseSeed + $index;
                $question['jawaban'] = $this->shuffleArray($question['jawaban'], $seed);

                // Update urutan jawaban
                foreach ($question['jawaban'] as $answerIndex => &$answer) {
                    $answer['urutan_acak'] = $answerIndex + 1;
                }
            }
        }

        return $questions;
    }

    /**
     * Generate random soal untuk sesi pembelajaran
     *
     * @param array $allQuestions Semua soal yang tersedia
     * @param int $jumlahSoal Jumlah soal yang dibutuhkan
     * @param int $seed Seed LCM
     * @return array Soal-soal yang sudah diacak
     */
    public function generateRandomQuestions(array $allQuestions, int $jumlahSoal, int $seed): array
    {
        if (empty($allQuestions)) {
            return [];
        }

        $totalSoal = count($allQuestions);

        // Jika jumlah soal yang diminta lebih dari yang tersedia, ambil semua
        if ($jumlahSoal >= $totalSoal) {
            $questions = $allQuestions;
        } else {
            // Generate random indices untuk memilih soal
            $randomIndices = $this->generate($seed, $totalSoal);
            $selectedIndices = array_unique(array_slice($randomIndices, 0, $jumlahSoal));

            // Ambil soal berdasarkan indices yang dipilih
            $questions = [];
            foreach ($selectedIndices as $index) {
                if ($index < $totalSoal) {
                    $questions[] = $allQuestions[$index];
                }
            }

            // Jika hasil kurang dari yang diminta, tambahkan dari awal
            while (count($questions) < $jumlahSoal && count($questions) < $totalSoal) {
                $remainingIndices = array_diff(range(0, $totalSoal - 1), $selectedIndices);
                if (empty($remainingIndices)) {
                    break;
                }
                $questions[] = $allQuestions[array_values($remainingIndices)[0]];
            }
        }

        // Acak urutan soal
        $questions = $this->shuffleQuestions($questions, $seed);

        // Acak jawaban untuk setiap soal
        $questions = $this->shuffleAnswers($questions, $seed);

        // Update urutan soal
        foreach ($questions as $index => &$question) {
            $question['urutan_soal'] = $index + 1;
        }

        return $questions;
    }

    /**
     * Test kualitas random number generation
     *
     * @param int $seed Seed untuk testing
     * @param int $count Jumlah angka untuk testing
     * @return array Analisis distribusi
     */
    public function testRandomQuality(int $seed, int $count = 1000): array
    {
        $numbers = $this->generate($seed, $count);

        // Hitung distribusi
        $distribution = array_fill(0, $this::M, 0);
        foreach ($numbers as $num) {
            $distribution[$num]++;
        }

        // Hitung statistik
        $mean = array_sum($numbers) / count($numbers);
        $variance = 0;
        foreach ($numbers as $num) {
            $variance += pow($num - $mean, 2);
        }
        $variance /= count($numbers);
        $stdDev = sqrt($variance);

        // Period detection
        $period = $this->detectPeriod($seed);

        return [
            'seed' => $seed,
            'count' => $count,
            'mean' => $mean,
            'variance' => $variance,
            'std_deviation' => $stdDev,
            'period' => $period,
            'distribution' => $distribution,
            'uniformity' => $this->calculateUniformity($distribution),
            'chi_square' => $this->chiSquareTest($distribution)
        ];
    }

    /**
     * Deteksi periode dari LCM sequence
     *
     * @param int $seed Seed awal
     * @return int Periode yang terdeteksi
     */
    private function detectPeriod(int $seed): int
    {
        $maxIterations = 1000;
        $sequence = [];
        $x = $seed;

        for ($i = 0; $i < $maxIterations; $i++) {
            $x = ($this::A * $x + $this::C) % $this::M;

            // Cek apakah angka sudah muncul sebelumnya
            if (in_array($x, $sequence)) {
                return $i + 1;
            }

            $sequence[] = $x;
        }

        return $maxIterations; // Default jika tidak menemukan periode
    }

    /**
     * Hitung uniformity distribusi
     *
     * @param array $distribution Distribusi angka
     * @return float Skor uniformity (0-1)
     */
    private function calculateUniformity(array $distribution): float
    {
        $total = array_sum($distribution);
        $expected = $total / count($distribution);

        $variance = 0;
        foreach ($distribution as $count) {
            $variance += pow($count - $expected, 2);
        }

        $maxVariance = $total * $total / count($distribution);

        return $maxVariance > 0 ? 1 - ($variance / $maxVariance) : 0;
    }

    /**
     * Chi-square test untuk uniformity
     *
     * @param array $distribution Distribusi observed
     * @return array Hasil chi-square test
     */
    private function chiSquareTest(array $distribution): array
    {
        $total = array_sum($distribution);
        $expected = $total / count($distribution);

        $chiSquare = 0;
        foreach ($distribution as $observed) {
            if ($expected > 0) {
                $chiSquare += pow($observed - $expected, 2) / $expected;
            }
        }

        $degreesOfFreedom = count($distribution) - 1;

        return [
            'chi_square' => $chiSquare,
            'degrees_of_freedom' => $degreesOfFreedom,
            'p_value' => $this->approxPValue($chiSquare, $degreesOfFreedom),
            'is_uniform' => $chiSquare < $this->getCriticalValue($degreesOfFreedom, 0.05)
        ];
    }

    /**
     * Approximate p-value calculation
     */
    private function approxPValue(float $chiSquare, int $df): float
    {
        // Simplified approximation
        return exp(-$chiSquare / (2 * $df));
    }

    /**
     * Get critical value for chi-square test
     */
    private function getCriticalValue(int $df, float $alpha): float
    {
        // Simplified critical values for common alpha levels
        $criticalValues = [
            1 => [0.05 => 3.841, 0.01 => 6.635],
            2 => [0.05 => 5.991, 0.01 => 9.210],
            5 => [0.05 => 11.070, 0.01 => 15.086],
            10 => [0.05 => 18.307, 0.01 => 23.209],
            20 => [0.05 => 31.410, 0.01 => 37.566],
            28 => [0.05 => 41.337, 0.01 => 48.278] // M-1 untuk modulus 29
        ];

        return $criticalValues[$df][$alpha] ?? 41.337;
    }

    /**
     * Generate seed dari kombinasi user_id, timestamp, dan materi_id
     *
     * @param int $userId ID user
     * @param int $timestamp Unix timestamp
     * @param int $materiId ID materi
     * @return int Generated seed
     */
    public function generateSeed(int $userId, int $timestamp, int $materiId): int
    {
        // Kombinasi berbagai faktor untuk seed yang unik
        return ($userId * 1000) + ($timestamp % 10000) + ($materiId * 100);
    }

    /**
     * Validasi seed yang digunakan untuk reproducibility
     *
     * @param int $seed Seed yang akan divalidasi
     * @return bool True jika seed valid
     */
    public function validateSeed(int $seed): bool
    {
        return $seed >= 0 && $seed < PHP_INT_MAX;
    }

    /**
     * Get LCM parameters
     *
     * @return array Parameter LCM yang digunakan
     */
    public function getParameters(): array
    {
        return [
            'a' => $this::A,
            'c' => $this::C,
            'm' => $this::M,
            'description' => 'Parameter LCM berdasarkan penelitian skripsi'
        ];
    }

    /**
     * Simulasi LCM dengan visual output (untuk debugging/educational)
     *
     * @param int $seed Seed awal
     * @param int $iterations Jumlah iterasi
     * @return array Detail simulasi
     */
    public function simulate(int $seed, int $iterations = 10): array
    {
        $result = [
            'parameters' => $this->getParameters(),
            'seed' => $seed,
            'iterations' => []
        ];

        $x = $seed;
        for ($i = 0; $i < $iterations; $i++) {
            $nextX = ($this::A * $x + $this::C) % $this::M;

            $result['iterations'][] = [
                'iteration' => $i + 1,
                'current_x' => $x,
                'calculation' => "({$this::A} × $x + $this::C) mod $this::M = $nextX",
                'next_x' => $nextX
            ];

            $x = $nextX;
        }

        return $result;
    }
}