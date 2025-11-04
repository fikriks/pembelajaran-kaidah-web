<?php

/**
 * Script untuk generate pilihan jawaban tambahan
 * Untuk melengkapi PilihanJawabanSeeder.php
 */

// Load CodeIgniter environment
require_once 'spark';

// Get database connection from CodeIgniter
$db = \Config\Database::connect();

if (!$db) {
    echo "❌ Database connection failed\n";
    exit(1);
}

echo "Connected to database successfully\n";

// Template jawaban yang akan digunakan
$jawabanTemplates = [
    'mudah' => [
        ['Jawaban benar mudah 1', 'Jawaban salah 1', 'Jawaban salah 2', 'Jawaban salah 3'],
        ['Pilihan benar sederhana', 'Pilihan salah sederhana', 'Pilihan salah lain', 'Pilihan alternatif'],
        ['Kunci jawaban dasar', 'Opsi distraktor 1', 'Opsi distraktor 2', 'Opsi distraktor 3'],
        ['Benar (mudah)', 'Salah A', 'Salah B', 'Salah C'],
        ['Jawaban yang benar', 'Opsi salah', 'Pilihan lain', 'Distraktor']
    ],
    'sedang' => [
        ['Jawaban benar sedang 1', 'Jawaban salah sedang 1', 'Jawaban salah sedang 2', 'Jawaban salah sedang 3'],
        ['Pilihan benar menengah', 'Pilihan salah menengah', 'Opsi distraktor menengah', 'Pilihan lain menengah'],
        ['Jawaban tepat', 'Jawaban kurang tepat', 'Opsi alternatif', 'Pilihan lain'],
        ['Benar (sedang)', 'Salah A', 'Salah B', 'Salah C'],
        ['Kunci jawaban utama', 'Distraktor sekunder', 'Opsi alternatif', 'Pilihan tambahan']
    ],
    'sulit' => [
        ['Jawaban benar sulit 1', 'Jawaban salah sulit 1', 'Jawaban salah sulit 2', 'Jawaban salah sulit 3'],
        ['Pilihan benar kompleks', 'Pilihan salah kompleks', 'Distraktor tinggi', 'Opsi mendekati'],
        ['Jawaban akurat', 'Jawaban kurang akurat', 'Opsi hampir benar', 'Pilihan jauh'],
        ['Benar (sulit)', 'Salah A', 'Salah B', 'Salah C'],
        ['Kunci jawaban spesifik', 'Distraktor umum', 'Opsi mirip', 'Pilihan berbeda']
    ]
];

// Arabic terms untuk jawaban
$arabicTerms = [
    'مُفْعَلٌ (maf\'ulun)', 'فَاعِلٌ (fa\'ilun)', 'مَفْعُولٌ (maf\'ulun)', 'إِسْمٌ (ismun)',
    'كِتَابٌ (kitabun)', 'كُتُبٌ (kutubun)', 'قَرَأَةٌ (qira\'atun)', 'كِتَابَانِ (kitaban)',
    'رَفَعٌ (raf\'un)', 'نَصْبٌ (nashabun)', 'خَفْضٌ (khafdhun)', 'جَزْمٌ (jazmun)',
    'مُبْتَدَأٌ (mubtada\'un)', 'خَبَرٌ (khabarun)', 'مَسْنَدٌ (masnun)', 'مُضَافٌ (mudhafun)',
    'هُوَ (huwa)', 'هِيَ (hiya)', 'هُمْ (hum)', 'أَنْتَ (anta)',
    'هَذَا (hadza)', 'هَذِهِ (hadzihi)', 'ذَلِكَ (dzalika)', 'تِلْكَ (tilka)'
];

echo "Starting to generate jawaban entries...\n";

$jawabanCount = 0;
$totalGenerated = 0;

// Get difficulty for each soal from the database
$soalDifficulties = [];
$soalQuery = $db->query("SELECT id_soal, tingkat_kesulitan FROM soal ORDER BY id_soal");
$soalResults = $soalQuery->getResult();

foreach ($soalResults as $soal) {
    $soalDifficulties[$soal->id_soal] = $soal->tingkat_kesulitan;
}

// Generate jawaban untuk soal 31-210
for ($soalId = 31; $soalId <= 210; $soalId++) {
    // Get difficulty from soal data
    $tingkat = $soalDifficulties[$soalId] ?? 'sedang';

    // Pilih template jawaban
    $templateIndex = array_rand($jawabanTemplates[$tingkat]);
    $template = $jawabanTemplates[$tingkat][$templateIndex];

    // Random posisi jawaban benar (1-4)
    $correctPosition = rand(1, 4);

    // Generate 4 jawaban
    $batchData = [];
    for ($i = 0; $i < 4; $i++) {
        $isBenar = ($i + 1 == $correctPosition);
        $urutan = $i + 1;

        // Kadang gunakan Arabic terms untuk variasi
        if (rand(1, 3) == 1 && count($arabicTerms) >= 4) {
            $termIndex = array_rand($arabicTerms);
            $teksJawaban = $arabicTerms[$termIndex];
        } else {
            $teksJawaban = $template[$i];
        }

        $batchData[] = [
            'id_soal' => $soalId,
            'teks_jawaban' => $teksJawaban,
            'is_benar' => $isBenar,
            'urutan' => $urutan,
            'waktu_dibuat' => date('Y-m-d H:i:s')
        ];

        $jawabanCount++;
        $totalGenerated++;
    }

    // Batch insert untuk setiap soal
    if (!empty($batchData)) {
        $db->table('pilihan_jawaban')->insertBatch($batchData);
    }

    // Progress indicator setiap 100 entries
    if ($totalGenerated % 100 == 0) {
        echo "Generated $totalGenerated jawaban entries so far...\n";
    }
}

echo "\n✅ Generation complete!\n";
echo "📊 Summary:\n";
echo "   • Total jawaban yang dibuat: $jawabanCount\n";
echo "   • Untuk soal: 31-210 (180 soal)\n";
echo "   • Rata-rata: 4 jawaban per soal\n\n";

// Cek total entries di database
$result = $db->query("SELECT COUNT(*) as total FROM pilihan_jawaban")->getRow();
echo "📈 Total pilihan jawaban di database: " . $result->total . "\n";

if ($result->total >= 800) {
    echo "🎉 Target 800 entries tercapai!\n";
} else {
    echo "⚠️  Masih butuh " . (800 - $result->total) . " entries lagi\n";
}

echo "\n🎓 Seeder optimization completed!\n";
echo "📚 Total materi kaidah: 10\n";
echo "📝 Total soal: 200\n";
echo "📋 Total pilihan jawaban: " . $result->total . "\n";
echo "🔢 Rata-rata soal per materi: 20\n";
echo "🔢 Rata-rata pilihan jawaban per soal: 4\n";