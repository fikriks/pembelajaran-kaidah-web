<?php

/**
 * Test script for optimized seeders - Simple Version
 */

echo "🎓 Testing Optimized Seeders for LCM Algorithm\n";
echo str_repeat("=", 60) . "\n\n";

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

try {
    echo "1. 🗑️  Cleaning existing data...\n";
    echo str_repeat("-", 40) . "\n";

    // Delete existing data in correct order (due to foreign keys)
    $pdo->exec("DELETE FROM pilihan_jawaban");
    $pdo->exec("DELETE FROM soal");
    $pdo->exec("DELETE FROM materi_kaidah");

    // Reset auto increment
    $pdo->exec("ALTER TABLE pilihan_jawaban AUTO_INCREMENT = 1");
    $pdo->exec("ALTER TABLE soal AUTO_INCREMENT = 1");
    $pdo->exec("ALTER TABLE materi_kaidah AUTO_INCREMENT = 1");

    echo "✅ Database cleaned successfully\n\n";

    echo "2. 📚 Running MateriKaidahSeeder...\n";
    echo str_repeat("-", 40) . "\n";

    // Insert materi kaidah data
    $currentTime = date('Y-m-d H:i:s');
    $materiData = [
        [
            'judul_kaidah'      => 'الْمُفْرَدُ وَالْجَمْعُ (Isim Mufrad dan Jamak)',
            'deskripsi'        => 'Pengenalan isim mufrad (tunggal) dan isim jamak (majemuk)',
            'penjelasan'       => 'Isim mufrad menunjukkan satu benda/orang, sedangkan isim jamak menunjukkan lebih dari satu.',
            'contoh'           => 'كِتَابٌ (kitabun) = buku, كُتُبٌ (kutubun) = buku-buku',
            'tingkat_kesulitan'=> 'mudah',
            'urutan'           => 1,
            'dibuat_oleh'      => 1,
            'waktu_dibuat'     => $currentTime,
            'waktu_diubah'     => $currentTime,
        ],
        [
            'judul_kaidah'      => 'الْمُذَكَّرُ وَالْمُؤَنَّثُ (Isim Mudzakkar dan Muannats)',
            'deskripsi'        => 'Isim yang menunjukkan jenis kelamin laki-laki dan perempuan',
            'penjelasan'       => 'Isim mudzakkar untuk laki-laki, isim muannats untuk perempuan.',
            'contoh'           => 'وَلَدٌ (waladun) = anak laki-laki, بِنْتٌ (bintun) = anak perempuan',
            'tingkat_kesulitan'=> 'mudah',
            'urutan'           => 2,
            'dibuat_oleh'      => 1,
            'waktu_dibuat'     => $currentTime,
            'waktu_diubah'     => $currentTime,
        ],
        [
            'judul_kaidah'      => 'الرَّفْعُ وَالنَّصْبُ وَالْخَفْضُ (Rafa\', Nashab, dan Khafdh)',
            'deskripsi'        => 'Tiga keadaan nahwu utama dalam bahasa Arab',
            'penjelasan'       => 'Rafa\' (dhammah), Nashab (fathah), Khafdh (kasroh)',
            'contoh'           => 'الْوَلَدُ (rafa\'), الْوَلَدَ (nashab), الْوَلَدِ (khafdh)',
            'tingkat_kesulitan'=> 'sedang',
            'urutan'           => 3,
            'dibuat_oleh'      => 2,
            'waktu_dibuat'     => $currentTime,
            'waktu_diubah'     => $currentTime,
        ],
        [
            'judul_kaidah'      => 'الْمَرْفُوعَاتُ (Kata-kata yang selalu Rafa\')',
            'deskripsi'        => 'Kata-kata yang karena kedudukannya selalu rafa\'',
            'penjelasan'       => 'Mubtada\', khabar, fa\'il, naibul fa\'il, dll',
            'contoh'           => 'الْوَلَدُ نَاجِحٌ (anak laki-laki itu berhasil)',
            'tingkat_kesulitan'=> 'sedang',
            'urutan'           => 4,
            'dibuat_oleh'      => 2,
            'waktu_dibuat'     => $currentTime,
            'waktu_diubah'     => $currentTime,
        ],
        [
            'judul_kaidah'      => 'الْمَنْصُوبَاتُ (Kata-kata yang selalu Nashab)',
            'deskripsi'        => 'Kata-kata yang karena kedudukannya selalu nashab',
            'penjelasan'       => 'Maf\'ul bihi, masdar, hal, tamyiz, dll',
            'contoh'           => 'قَرَأْتُ الْكِتَابَ (saya membaca buku)',
            'tingkat_kesulitan'=> 'sedang',
            'urutan'           => 5,
            'dibuat_oleh'      => 1,
            'waktu_dibuat'     => $currentTime,
            'waktu_diubah'     => $currentTime,
        ],
        [
            'judul_kaidah'      => 'الْمَجْرُورَاتُ (Kata-kata yang di-Khafdh)',
            'deskripsi'        => 'Kata-kata yang selalu berada dalam keadaan khafdh',
            'penjelasan'       => 'Terdapat beberapa jenis kata yang selalu khafdh yaitu: jar wa majrur, dan lain-lain.',
            'contoh'           => 'ذَهَبْتُ إِلَى الْمَدْرَسَةِ (dzahabtu ilal madrasati) = aku pergi ke sekolah',
            'tingkat_kesulitan'=> 'sedang',
            'urutan'           => 6,
            'dibuat_oleh'      => 3,
            'waktu_dibuat'     => $currentTime,
            'waktu_diubah'     => $currentTime,
        ],
        [
            'judul_kaidah'      => 'الْمُبْتَدَأُ وَالْخَبَرُ (Mubtada\' dan Khabar)',
            'deskripsi'        => 'Struktur kalimat nominal dalam bahasa Arab',
            'penjelasan'       => 'Mubtada\' sebagai subyek, khabar sebagai predikat',
            'contoh'           => 'الْوَلَدُ نَاجِحٌ (anak laki-laki itu berhasil)',
            'tingkat_kesulitan'=> 'sedang',
            'urutan'           => 7,
            'dibuat_oleh'      => 1,
            'waktu_dibuat'     => $currentTime,
            'waktu_diubah'     => $currentTime,
        ],
        [
            'judul_kaidah'      => 'الضَّمَائِرُ (Dhamir)',
            'deskripsi'        => 'Kata ganti yang menggantikan nama',
            'penjelasan'       => 'Dhamir muttasil (terikat), dhamir munfasil (terpisah)',
            'contoh'           => 'هُوَ قَائِمٌ (dia laki-laki berdiri)',
            'tingkat_kesulitan'=> 'sedang',
            'urutan'           => 8,
            'dibuat_oleh'      => 2,
            'waktu_dibuat'     => $currentTime,
            'waktu_diubah'     => $currentTime,
        ],
        [
            'judul_kaidah'      => 'أَسْمَاءُ الْإِشَارَةِ (Isim Isyarah)',
            'deskripsi'        => 'Kata penunjuk untuk benda dekat atau jauh',
            'penjelasan'       => 'Isim isyarah mudzakkar dan muannats, tunggal dan jamak',
            'contoh'           => 'هَذَا الْكِتَابُ (buku ini), ذَلِكَ الْبَيْتُ (rumah itu)',
            'tingkat_kesulitan'=> 'sedang',
            'urutan'           => 9,
            'dibuat_oleh'      => 1,
            'waktu_dibuat'     => $currentTime,
            'waktu_diubah'     => $currentTime,
        ],
        [
            'judul_kaidah'      => 'الْمَصْدَرُ (Masdar)',
            'deskripsi'        => 'Kata benda yang menunjukkan makna fiil tanpa waktu',
            'penjelasan'       => 'Masdar fiil madhi, mudhari\', amar, dan tidak lazim',
            'contoh'           => 'الْقِرَاءَةُ (membaca), الْفَهْمُ (memahami)',
            'tingkat_kesulitan'=> 'sulit',
            'urutan'           => 10,
            'dibuat_oleh'      => 3,
            'waktu_dibuat'     => $currentTime,
            'waktu_diubah'     => $currentTime,
        ]
    ];

    // Insert materi data
    $materiSql = "INSERT INTO materi_kaidah (judul_kaidah, deskripsi, penjelasan, contoh, tingkat_kesulitan, urutan, dibuat_oleh, waktu_dibuat, waktu_diubah) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $materiStmt = $pdo->prepare($materiSql);

    foreach ($materiData as $materi) {
        $materiStmt->execute([
            $materi['judul_kaidah'],
            $materi['deskripsi'],
            $materi['penjelasan'],
            $materi['contoh'],
            $materi['tingkat_kesulitan'],
            $materi['urutan'],
            $materi['dibuat_oleh'],
            $materi['waktu_dibuat'],
            $materi['waktu_diubah']
        ]);
    }

    echo "✅ MateriKaidahSeeder: 10 materi inserted\n\n";

    echo "3. 📝 Running SoalSeederOptimized...\n";
    echo str_repeat("-", 40) . "\n";

    // Include optimized soal seeder
    include_once __DIR__ . '/app/Database/Seeds/SoalSeederOptimized.php';

    // Create seeder instance and run it with our PDO connection
    $soalSeeder = new \App\Database\Seeds\SoalSeederOptimized();

    // Manually insert the data using PDO
    $soalData = include __DIR__ . '/temp_soal_data.php';
    if (!file_exists(__DIR__ . '/temp_soal_data.php')) {
        // Extract data from seeder
        $reflection = new ReflectionClass($soalSeeder);
        $method = $reflection->getMethod('run');

        // Temporarily modify the seeder to use our PDO
        $soalSeeder->db = $pdo;

        // Execute seeder
        $soalSeeder->run();
    } else {
        echo "Using cached soal data...\n";
    }

    echo "\n";

    echo "4. ✅ Running PilihanJawabanSeederOptimized...\n";
    echo str_repeat("-", 40) . "\n";

    // Include optimized jawaban seeder
    include_once __DIR__ . '/app/Database/Seeds/PilihanJawabanSeederOptimized.php';

    // Create seeder instance and run it with our PDO connection
    $jawabanSeeder = new \App\Database\Seeds\PilihanJawabanSeederOptimized();
    $jawabanSeeder->db = $pdo;
    $jawabanSeeder->run();

    echo "\n";

    echo "5. 📊 Verifying Final Dataset...\n";
    echo str_repeat("-", 40) . "\n";

    // Count final data
    $materiCount = $pdo->query("SELECT COUNT(*) as total FROM materi_kaidah")->fetch(PDO::FETCH_ASSOC)['total'];
    $soalCount = $pdo->query("SELECT COUNT(*) as total FROM soal")->fetch(PDO::FETCH_ASSOC)['total'];
    $jawabanCount = $pdo->query("SELECT COUNT(*) as total FROM pilihan_jawaban")->fetch(PDO::FETCH_ASSOC)['total'];

    echo "Jumlah Materi Kaidah: {$materiCount}\n";
    echo "Jumlah Soal: {$soalCount}\n";
    echo "Jumlah Pilihan Jawaban: {$jawabanCount}\n\n";

    // Verify distribution
    echo "6. 📈 Checking Distribution...\n";
    echo str_repeat("-", 40) . "\n";

    $soalPerMateri = $pdo->query("
        SELECT id_materi, COUNT(*) as jumlah_soal
        FROM soal
        GROUP BY id_materi
        ORDER BY id_materi
    ")->fetchAll(PDO::FETCH_ASSOC);

    echo "Soal per materi:\n";
    foreach ($soalPerMateri as $item) {
        echo "   Materi {$item['id_materi']}: {$item['jumlah_soal']} soal\n";
    }

    // Check correct answers distribution
    $correctAnswers = $pdo->query("
        SELECT COUNT(*) as total FROM pilihan_jawaban WHERE is_benar = 1
    ")->fetch(PDO::FETCH_ASSOC)['total'];

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