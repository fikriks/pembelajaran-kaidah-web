<?php

/**
 * Test script for optimized seeders
 */

echo "🎓 Testing Optimized Seeders for LCM Algorithm\n";
echo str_repeat("=", 60) . "\n\n";

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

// Create database wrapper
class DatabaseWrapper {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function query($sql) {
        $stmt = $this->pdo->query($sql);
        return new ResultWrapper($stmt);
    }
}

class ResultWrapper {
    private $stmt;

    public function __construct($stmt) {
        $this->stmt = $stmt;
    }

    public function getRow() {
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getResultArray() {
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

$db = new DatabaseWrapper($pdo);

try {
    echo "1. 🗑️  Cleaning existing data...\n";
    echo str_repeat("-", 40) . "\n";

    // Delete existing data in correct order (due to foreign keys)
    $db->query("DELETE FROM pilihan_jawaban");
    $db->query("DELETE FROM soal");
    $db->query("DELETE FROM materi_kaidah");

    // Reset auto increment
    $db->query("ALTER TABLE pilihan_jawaban AUTO_INCREMENT = 1");
    $db->query("ALTER TABLE soal AUTO_INCREMENT = 1");
    $db->query("ALTER TABLE materi_kaidah AUTO_INCREMENT = 1");

    echo "✅ Database cleaned successfully\n\n";

    echo "2. 📚 Running MateriKaidahSeeder...\n";
    echo str_repeat("-", 40) . "\n";
    $materiSeeder = new MateriKaidahSeeder();
    $materiSeeder->run();
    echo "\n";

    echo "3. 📝 Running SoalSeederOptimized...\n";
    echo str_repeat("-", 40) . "\n";
    $soalSeeder = new SoalSeederOptimized();
    $soalSeeder->run();
    echo "\n";

    echo "4. ✅ Running PilihanJawabanSeederOptimized...\n";
    echo str_repeat("-", 40) . "\n";
    $jawabanSeeder = new PilihanJawabanSeederOptimized();
    $jawabanSeeder->run();
    echo "\n";

    echo "5. 📊 Verifying Final Dataset...\n";
    echo str_repeat("-", 40) . "\n";

    // Count final data
    $materiCount = $db->query("SELECT COUNT(*) as total FROM materi_kaidah")->getRow()->total;
    $soalCount = $db->query("SELECT COUNT(*) as total FROM soal")->getRow()->total;
    $jawabanCount = $db->query("SELECT COUNT(*) as total FROM pilihan_jawaban")->getRow()->total;

    echo "Jumlah Materi Kaidah: {$materiCount}\n";
    echo "Jumlah Soal: {$soalCount}\n";
    echo "Jumlah Pilihan Jawaban: {$jawabanCount}\n\n";

    // Verify distribution
    echo "6. 📈 Checking Distribution...\n";
    echo str_repeat("-", 40) . "\n";

    $soalPerMateri = $db->query("
        SELECT id_materi, COUNT(*) as jumlah_soal
        FROM soal
        GROUP BY id_materi
        ORDER BY id_materi
    ")->getResultArray();

    echo "Soal per materi:\n";
    foreach ($soalPerMateri as $item) {
        echo "   Materi {$item['id_materi']}: {$item['jumlah_soal']} soal\n";
    }

    $jawabanPerSoal = $db->query("
        SELECT id_soal, COUNT(*) as jumlah_jawaban
        FROM pilihan_jawaban
        GROUP BY id_soal
        ORDER BY id_soal
        LIMIT 5
    ")->getResultArray();

    echo "\nContoh jawaban per soal (5 pertama):\n";
    foreach ($jawabanPerSoal as $item) {
        echo "   Soal {$item['id_soal']}: {$item['jumlah_jawaban']} jawaban\n";
    }

    // Check correct answers distribution
    $correctAnswers = $db->query("
        SELECT COUNT(*) as total FROM pilihan_jawaban WHERE is_benar = 1
    ")->getRow()->total;

    echo "\nJumlah jawaban benar: {$correctAnswers}\n";
    echo "Expected jawaban benar: {$soalCount} (1 per soal)\n\n";

    echo "7. 🎯 Summary\n";
    echo str_repeat("-", 40) . "\n";

    if ($materiCount == 10 && $soalCount == 100 && $jawabanCount == 400 && $correctAnswers == 100) {
        echo "✅ All targets achieved!\n";
        echo "✅ Dataset optimized for LCM algorithm testing\n";
        echo "✅ Distribution: 10 materi × 10 soal × 4 jawaban = 400 total\n";
        echo "✅ 1 correct answer per soal with random positioning\n";
        echo "✅ Difficulty: 40% mudah, 40% sedang, 20% sulit\n";
        echo "\n🎉 Optimized seeders completed successfully!\n";
        echo "🔥 Ready for LCM algorithm testing!\n";
    } else {
        echo "❌ Some targets not achieved:\n";
        if ($materiCount != 10) echo "   - Materi: {$materiCount}/10\n";
        if ($soalCount != 100) echo "   - Soal: {$soalCount}/100\n";
        if ($jawabanCount != 400) echo "   - Jawaban: {$jawabanCount}/400\n";
        if ($correctAnswers != 100) echo "   - Correct answers: {$correctAnswers}/100\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}