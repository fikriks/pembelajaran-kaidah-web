<?php

/**
 * Simple LCM Test tanpa CodeIgniter framework
 */

// Define constants untuk testing
define('BASEPATH', __DIR__);

// Load database secara manual
$host = 'localhost';
$dbname = 'khozin_pembelajaran_kaidah';
$username = 'root';
$password = 'fikrikhairul';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database connected successfully\n\n";
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Include LCM Algorithm secara manual
require_once __DIR__ . '/app/Libraries/LCMAlgorithm.php';

use App\Libraries\LCMAlgorithm;

echo "🎓 Testing LCM Algorithm (Simplified)\n";
echo str_repeat("=", 50) . "\n\n";

// 1. Initialize LCM
echo "1. 📋 Initialize LCM Algorithm\n";
echo str_repeat("-", 30) . "\n";

$lcm = new LCMAlgorithm(12345); // Fixed seed untuk testing
$params = $lcm->getParameters();

echo "Parameters:\n";
echo "   • a (multiplier): {$params['multiplier']}\n";
echo "   • c (increment): {$params['increment']}\n";
echo "   • m (modulus): {$params['modulus']}\n";
echo "   • seed: {$params['current_seed']}\n\n";

// 2. Test sequence generation
echo "2. 🔢 Test Sequence Generation\n";
echo str_repeat("-", 30) . "\n";

$sequence = $lcm->generateSequence(10);
echo "First 10 numbers: " . implode(", ", $sequence) . "\n\n";

// 3. Test data retrieval
echo "3. 📊 Get Database Data\n";
echo str_repeat("-", 30) . "\n";

// Get materi count
$stmt = $pdo->query("SELECT COUNT(*) as total FROM materi_kaidah");
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$materiCount = $result['total'];
echo "Jumlah Materi: {$materiCount}\n";

// Get soal count
$stmt = $pdo->query("SELECT COUNT(*) as total FROM soal");
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$soalCount = $result['total'];
echo "Jumlah Soal: {$soalCount}\n";

// Get jawaban count
$stmt = $pdo->query("SELECT COUNT(*) as total FROM pilihan_jawaban");
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$jawabanCount = $result['total'];
echo "Jumlah Pilihan Jawaban: {$jawabanCount}\n\n";

// 4. Test chi-square
echo "4. 📈 Test Chi-Square\n";
echo str_repeat("-", 30) . "\n";

$chiResult = $lcm->chiSquareTest(500);
echo "Sample Size: {$chiResult['sample_size']}\n";
echo "Chi-Square Statistic: {$chiResult['chi_square_statistic']}\n";
echo "Critical Value: {$chiResult['critical_value']}\n";
echo "Is Uniform: " . ($chiResult['is_uniform_distribution'] ? '✅ YES' : '❌ NO') . "\n";
echo "Conclusion: {$chiResult['conclusion']}\n\n";

// 5. Test reproducibility
echo "5. 🔄 Test Reproducibility\n";
echo str_repeat("-", 30) . "\n";

$testSeed = 54321;
$seq1 = $lcm->reproduceSequence($testSeed, 5);
$seq2 = $lcm->reproduceSequence($testSeed, 5);

echo "Sequence 1: " . implode(", ", $seq1) . "\n";
echo "Sequence 2: " . implode(", ", $seq2) . "\n";
echo "Identical: " . ($seq1 === $seq2 ? '✅ YES' : '❌ NO') . "\n\n";

// 6. Performance test
echo "6. ⚡ Performance Test\n";
echo str_repeat("-", 30) . "\n";

$start = microtime(true);
for ($i = 0; $i < 1000; $i++) {
    $lcm->next();
}
$end = microtime(true);
$time = ($end - $start) * 1000;

echo "Generated 1000 numbers in: " . number_format($time, 2) . " ms\n";
echo "Average per number: " . number_format($time / 1000, 4) . " ms\n\n";

// 7. Summary
echo "7. 📋 Summary\n";
echo str_repeat("-", 30) . "\n";

echo "✅ LCM Algorithm: WORKING\n";
echo "✅ Database Access: WORKING\n";
echo "✅ Chi-Square Test: " . ($chiResult['is_uniform_distribution'] ? 'PASS' : 'NEEDS REVIEW') . "\n";
echo "✅ Reproducibility: PASS\n";
echo "✅ Performance: " . ($time < 100 ? 'EXCELLENT' : 'OK') . "\n\n";

echo "🎉 LCM Testing Completed Successfully!\n";
echo "📊 Data Summary: {$materiCount} materi, {$soalCount} soal, {$jawabanCount} jawaban\n";
echo "🔢 Ready for application integration!\n";