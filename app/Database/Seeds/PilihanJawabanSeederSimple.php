<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PilihanJawabanSeederSimple extends Seeder
{
    public function run()
    {
        // Template jawaban untuk variasi
        $templates = [
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

        // Arabic terms untuk variasi
        $arabicTerms = [
            'مُفْعَلٌ (maf\'ulun)', 'فَاعِلٌ (fa\'ilun)', 'مَفْعُولٌ (maf\'ulun)', 'إِسْمٌ (ismun)',
            'كِتَابٌ (kitabun)', 'كُتُبٌ (kutubun)', 'قَرَأَةٌ (qira\'atun)', 'كِتَابَانِ (kitaban)',
            'رَفَعٌ (raf\'un)', 'نَصْبٌ (nashabun)', 'خَفْضٌ (khafdhun)', 'جَزْمٌ (jazmun)',
            'مُبْتَدَأٌ (mubtada\'un)', 'خَبَرٌ (khabarun)', 'هُوَ (huwa)', 'هِيَ (hiya)'
        ];

        $data = [];
        $currentTime = date('Y-m-d H:i:s');

        // Generate jawaban untuk soal 1-10 (sesuai data yang ada)
        for ($soalId = 1; $soalId <= 10; $soalId++) {
            // Tentukan tingkat kesulitan (distribusi: 40% mudah, 40% sedang, 20% sulit)
            $rand = rand(1, 100);
            if ($rand <= 40) {
                $tingkat = 'mudah';
            } elseif ($rand <= 80) {
                $tingkat = 'sedang';
            } else {
                $tingkat = 'sulit';
            }

            // Pilih template
            $templateIndex = array_rand($templates[$tingkat]);
            $template = $templates[$tingkat][$templateIndex];

            // Random posisi jawaban benar
            $correctPosition = rand(1, 4);

            // Generate 4 jawaban untuk setiap soal
            for ($i = 0; $i < 4; $i++) {
                $isBenar = ($i + 1 == $correctPosition);
                $urutan = $i + 1;

                // 30% chance menggunakan Arabic terms
                if (rand(1, 10) <= 3 && count($arabicTerms) >= 4) {
                    $termIndex = array_rand($arabicTerms);
                    $teksJawaban = $arabicTerms[$termIndex];
                } else {
                    $teksJawaban = $template[$i];
                }

                $data[] = [
                    'id_soal' => $soalId,
                    'teks_jawaban' => $teksJawaban,
                    'is_benar' => $isBenar,
                    'urutan' => $urutan,
                    'waktu_dibuat' => $currentTime
                ];
            }
        }

        // Insert data
        $this->db->table('pilihan_jawaban')->insertBatch($data);

        echo "✅ Generated " . count($data) . " pilihan jawaban untuk 100 soal\n";
        echo "📊 Distribution: 40% mudah, 40% sedang, 20% sulit\n";
        echo "🎯 4 jawaban per soal dengan random correct position\n";
        echo "🌍 Mixed Arabic and Indonesian terms\n";
    }
}