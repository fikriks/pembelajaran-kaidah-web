<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PilihanJawabanSeederOptimized extends Seeder
{
    public function run()
    {
        $data = [];
        $currentTime = date('Y-m-d H:i:s');

        // Generate 4 pilihan jawaban untuk setiap soal (1-100)
        for ($soalId = 1; $soalId <= 100; $soalId++) {
            // Tentukan tingkat kesulitan berdasarkan ID materi
            $materiId = ceil($soalId / 10);
            $tingkat = $this->getTingkatKesulitan($soalId);

            // Pilih template jawaban berdasarkan tingkat kesulitan
            $template = $this->getTemplateJawaban($tingkat, $soalId);

            // Random posisi jawaban benar (1-4)
            $correctPosition = rand(1, 4);

            // Generate 4 jawaban untuk setiap soal
            for ($i = 0; $i < 4; $i++) {
                $isBenar = ($i + 1 == $correctPosition);
                $urutan = $i + 1;

                $data[] = [
                    'id_soal' => $soalId,
                    'teks_jawaban' => $template[$i],
                    'is_benar' => $isBenar,
                    'urutan' => $urutan,
                    'waktu_dibuat' => $currentTime
                ];
            }
        }

        // Insert data dalam batch
        $this->db->table('pilihan_jawaban')->insertBatch($data);

        echo "✅ Generated " . count($data) . " pilihan jawaban untuk 100 soal\n";
        echo "📊 Distribution: 100 soal × 4 jawaban = 400 jawaban total\n";
        echo "🎯 1 jawaban benar per soal dengan posisi acak\n";
        echo "🌍 Mixed Arabic and Indonesian terms\n";
        echo "🔥 Ready for LCM algorithm testing!\n";
    }

    /**
     * Menentukan tingkat kesulitan berdasarkan pola distribusi
     * 40% mudah, 40% sedang, 20% sulit
     */
    private function getTingkatKesulitan($soalId)
    {
        $position = $soalId % 10;

        if ($position <= 4) {
            return 'mudah';    // 40% (posisi 1-4)
        } elseif ($position <= 8) {
            return 'sedang';   // 40% (posisi 5-8)
        } else {
            return 'sulit';    // 20% (posisi 9-10)
        }
    }

    /**
     * Mengembalikan template jawaban berdasarkan tingkat kesulitan dan soal ID
     */
    private function getTemplateJawaban($tingkat, $soalId)
    {
        // Template jawaban berdasarkan tingkat kesulitan
        $templates = [
            'mudah' => [
                ['Jawaban benar mudah', 'Jawaban salah 1', 'Jawaban salah 2', 'Jawaban salah 3'],
                ['Pilihan benar sederhana', 'Pilihan salah sederhana', 'Pilihan salah lain', 'Pilihan alternatif'],
                ['Kunci jawaban dasar', 'Opsi distraktor 1', 'Opsi distraktor 2', 'Opsi distraktor 3'],
                ['Benar (mudah)', 'Salah A', 'Salah B', 'Salah C'],
                ['Jawaban yang benar', 'Opsi salah', 'Pilihan lain', 'Distraktor'],
                ['Option benar', 'Option salah 1', 'Option salah 2', 'Option salah 3'],
                ['Correct answer', 'Wrong choice A', 'Wrong choice B', 'Wrong choice C'],
                ['Jawaban tepat', 'Jawaban kurang tepat', 'Opsi alternatif', 'Pilihan lain'],
                ['Benar sekali', 'Tidak benar', 'Kurang benar', 'Sangat salah'],
                ['Pilihan benar', 'Pilihan keliru', 'Pilihan ragu', 'Pilihan lain']
            ],
            'sedang' => [
                ['Jawaban benar sedang', 'Jawaban salah sedang 1', 'Jawaban salah sedang 2', 'Jawaban salah sedang 3'],
                ['Pilihan benar menengah', 'Pilihan salah menengah', 'Opsi distraktor menengah', 'Pilihan lain menengah'],
                ['Jawaban tepat', 'Jawaban kurang tepat', 'Opsi alternatif', 'Pilihan lain'],
                ['Benar (sedang)', 'Salah A', 'Salah B', 'Salah C'],
                ['Kunci jawaban utama', 'Distraktor sekunder', 'Opsi alternatif', 'Pilihan tambahan'],
                ['Option benar', 'Option salah', 'Pilihan alternatif', 'Opsi lain'],
                ['Correct', 'Incorrect', 'Maybe', 'Alternative'],
                ['Tepat sekali', 'Kurang tepat', 'Hampir benar', 'Jauh dari benar'],
                ['Benar', 'Salah', 'Mungkin benar', 'Pasti salah'],
                ['Pilihan tepat', 'Pilihan kurang', 'Opsi mendekati', 'Pilihan jauh']
            ],
            'sulit' => [
                ['Jawaban benar sulit', 'Jawaban salah sulit 1', 'Jawaban salah sulit 2', 'Jawaban salah sulit 3'],
                ['Pilihan benar kompleks', 'Pilihan salah kompleks', 'Distraktor tinggi', 'Opsi mendekati'],
                ['Jawaban akurat', 'Jawaban kurang akurat', 'Opsi hampir benar', 'Pilihan jauh'],
                ['Benar (sulit)', 'Salah A', 'Salah B', 'Salah C'],
                ['Kunci jawaban spesifik', 'Distraktor umum', 'Opsi mirip', 'Pilihan berbeda'],
                ['Option expert', 'Option tricky', 'Distractor close', 'Alternative far'],
                ['Precise answer', 'Imprecise answer', 'Close guess', 'Wrong guess'],
                ['Sangat benar', 'Agak benar', 'Hampir benar', 'Tidak benar'],
                ['Benar mutlak', 'Benar relatif', 'Salah relatif', 'Salah mutlak'],
                ['Pilihan spesifik', 'Pilihan umum', 'Opsi ambigu', 'Pilihan jelas']
            ]
        ];

        // Arabic terms untuk variasi
        $arabicTerms = [
            'مُفْعَلٌ (maf\'ulun)', 'فَاعِلٌ (fa\'ilun)', 'مَفْعُولٌ (maf\'ulun)', 'إِسْمٌ (ismun)',
            'كِتَابٌ (kitabun)', 'كُتُبٌ (kutubun)', 'قَرَأَةٌ (qira\'atun)', 'كِتَابَانِ (kitaban)',
            'رَفَعٌ (raf\'un)', 'نَصْبٌ (nashabun)', 'خَفْضٌ (khafdhun)', 'جَزْمٌ (jazmun)',
            'مُبْتَدَأٌ (mubtada\'un)', 'خَبَرٌ (khabarun)', 'هُوَ (huwa)', 'هِيَ (hiya)',
            'مُسْلِمُونَ (muslimuna)', 'مُسْلِمَاتٌ (muslimatun)', 'وَلَدٌ (waladun)', 'بِنْتٌ (bintun)',
            'الْكِتَابُ (al-kitabu)', 'الْوَلَدُ (al-waladu)', 'مِنْ (min)', 'إِلَى (ila)',
            'هَذَا (hadza)', 'هَذِهِ (hadzihi)', 'ذَلِكَ (dzalika)', 'تِلْكَ (tilka)',
            'مَسْجِدٌ (masjidun)', 'مَسَاجِدُ (masajidu)', 'جَبَلٌ (jabalun)', 'جِبَالٌ (jibalun)',
            'قَرَأَ (qara-a)', 'يَقْرَأُ (yaqra-u)', 'قِرَاءَةٌ (qira\'atun)', 'قَارِئٌ (qari\'un)'
        ];

        // Pilih template berdasarkan tingkat kesulitan
        $templateSet = $templates[$tingkat];
        $templateIndex = ($soalId - 1) % count($templateSet);
        $template = $templateSet[$templateIndex];

        // 30% chance menggunakan Arabic terms untuk variasi
        if (rand(1, 10) <= 3 && count($arabicTerms) >= 4) {
            $arabicIndex = ($soalId * 4) % count($arabicTerms);
            $arabicTemplate = array_slice($arabicTerms, $arabicIndex, 4);

            // Replace beberapa template dengan Arabic terms
            for ($i = 0; $i < 4; $i++) {
                if (isset($arabicTemplate[$i]) && rand(1, 2) == 1) {
                    $template[$i] = $arabicTemplate[$i];
                }
            }
        }

        return $template;
    }
}