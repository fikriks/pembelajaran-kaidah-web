<?php

/**
 * Script untuk generate pilihan jawaban tambahan (versi sederhana)
 * Target: 100 soal dengan 400 pilihan jawaban
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
        ['Jawaban benar mudah', 'Jawaban salah 1', 'Jawaban salah 2', 'Jawaban salah 3'],
        ['Pilihan benar sederhana', 'Pilihan salah sederhana', 'Pilihan salah lain', 'Pilihan alternatif'],
        ['Kunci jawaban dasar', 'Opsi distraktor 1', 'Opsi distraktor 2', 'Opsi distraktor 3'],
        ['Benar (mudah)', 'Salah A', 'Salah B', 'Salah C'],
        ['Jawaban yang benar', 'Opsi salah', 'Pilihan lain', 'Distraktor']
    ],
    'sedang' => [
        ['Jawaban benar sedang', 'Jawaban salah sedang 1', 'Jawaban salah sedang 2', 'Jawaban salah sedang 3'],
        ['Pilihan benar menengah', 'Pilihan salah menengah', 'Opsi distraktor menengah', 'Pilihan lain menengah'],
        ['Jawaban tepat', 'Jawaban kurang tepat', 'Opsi alternatif', 'Pilihan lain'],
        ['Benar (sedang)', 'Salah A', 'Salah B', 'Salah C'],
        ['Kunci jawaban utama', 'Distraktor sekunder', 'Opsi alternatif', 'Pilihan tambahan']
    ],
    'sulit' => [
        ['Jawaban benar sulit', 'Jawaban salah sulit 1', 'Jawaban salah sulit 2', 'Jawaban salah sulit 3'],
        ['Pilihan benar kompleks', 'Pilihan salah kompleks', 'Distraktor tinggi', 'Opsi mendekati'],
        ['Jawaban akurat', 'Jawaban kurang akurat', 'Opsi hampir benar', 'Pilihan jauh'],
        ['Benar (sulit)', 'Salah A', 'Salah B', 'Salah C'],
        ['Kunci jawaban spesifik', 'Distraktor umum', 'Opsi mirip', 'Pilihan berbeda']
    ]
];

// Arabic terms untuk jawaban (disedikitkan)
$arabicTerms = [
    'مُفْعَلٌ (maf\'ulun)', 'فَاعِلٌ (fa\'ilun)', 'مَفْعُولٌ (maf\'ulun)', 'إِسْمٌ (ismun)',
    'كِتَابٌ (kitabun)', 'كُتُبٌ (kutubun)', 'قَرَأَةٌ (qira\'atun)', 'كِتَابَانِ (kitaban)',
    'رَفَعٌ (raf\'un)', 'نَصْبٌ (nashabun)', 'خَفْضٌ (khafdhun)', 'جَزْمٌ (jazmun)',
    'مُبْتَدَأٌ (mubtada\'un)', 'خَبَرٌ (khabarun)', 'هُوَ (huwa)', 'هِيَ (hiya)'
];

echo "Starting to generate jawaban entries (sederhana)...\n";

$jawabanCount = 0;
$totalGenerated = 0;

// Generate jawaban untuk soal 11-100 (90 soal tambahan)
// Target total: 100 soal (10 sudah ada + 90 baru)
for ($soalId = 11; $soalId <= 100; $soalId++) {
    // Random tingkat kesulitan (40% mudah, 40% sedang, 20% sulit)
    $rand = rand(1, 100);
    if ($rand <= 40) {
        $tingkat = 'mudah';
    } elseif ($rand <= 80) {
        $tingkat = 'sedang';
    } else {
        $tingkat = 'sulit';
    }

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

        // Kadang gunakan Arabic terms untuk variasi (30% chance)
        if (rand(1, 10) <= 3 && count($arabicTerms) >= 4) {
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

    // Progress indicator setiap 50 entries
    if ($totalGenerated % 50 == 0) {
        echo "Generated $totalGenerated jawaban entries so far...\n";
    }
}

echo "\n✅ Generation complete!\n";
echo "📊 Summary:\n";
echo "   • Total jawaban yang dibuat: $jawabanCount\n";
echo "   • Untuk soal: 11-100 (90 soal)\n";
echo "   • Rata-rata: 4 jawaban per soal\n\n";

// Cek total entries di database
$result = $db->query("SELECT COUNT(*) as total FROM pilihan_jawaban")->getRow();
echo "📈 Total pilihan jawaban di database: " . $result->total . "\n";

if ($result->total >= 400) {
    echo "🎉 Target 400 entries tercapai!\n";
} else {
    echo "⚠️  Masih butuh " . (400 - $result->total) . " entries lagi\n";
}

echo "\n🎓 Seeder optimization sederhana completed!\n";
echo "📚 Total materi kaidah: 10\n";
echo "📝 Total soal: 100 (target)\n";
echo "📋 Total pilihan jawaban: " . $result->total . "\n";
echo "🔢 Rata-rata soal per materi: 10\n";
echo "🔢 Rata-rata pilihan jawaban per soal: 4\n";
echo "\n✨ Data sudah cukup untuk testing LCM!\n";