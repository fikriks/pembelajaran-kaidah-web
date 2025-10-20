<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PilihanJawabanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Jawaban untuk soal 1 (kaidah 1)
            ['id_soal' => 1, 'teks_jawaban' => 'كُتُبٌ (kutubun)', 'is_benar' => true, 'urutan' => 1, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 1, 'teks_jawaban' => 'كِتَابَانِ (kitaban)', 'is_benar' => false, 'urutan' => 2, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 1, 'teks_jawaban' => 'كَاتَبَ (kataba)', 'is_benar' => false, 'urutan' => 3, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 1, 'teks_jawaban' => 'مَكْتَبٌ (maktabun)', 'is_benar' => false, 'urutan' => 4, 'waktu_dibuat' => date('Y-m-d H:i:s')],

            // Jawaban untuk soal 2 (kaidah 1)
            ['id_soal' => 2, 'teks_jawaban' => 'مُعَلِّمُونَ (muallimuna)', 'is_benar' => false, 'urutan' => 1, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 2, 'teks_jawaban' => 'مُعَلِّمٌ (muallimun)', 'is_benar' => true, 'urutan' => 2, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 2, 'teks_jawaban' => 'مُعَلِّمَاتٌ (muallimatun)', 'is_benar' => false, 'urutan' => 3, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 2, 'teks_jawaban' => 'تَلَامِيذُ (talimidzu)', 'is_benar' => false, 'urutan' => 4, 'waktu_dibuat' => date('Y-m-d H:i:s')],

            // Jawaban untuk soal 3 (kaidah 1)
            ['id_soal' => 3, 'teks_jawaban' => 'مُدَرِّسٌ (mudarrisun)', 'is_benar' => true, 'urutan' => 1, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 3, 'teks_jawaban' => 'مَدْرَسَةٌ (madrasatun)', 'is_benar' => false, 'urutan' => 2, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 3, 'teks_jawaban' => 'مُدَرِّسَاتٌ (mudarrisatun)', 'is_benar' => false, 'urutan' => 3, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 3, 'teks_jawaban' => 'دَرْسٌ (darsun)', 'is_benar' => false, 'urutan' => 4, 'waktu_dibuat' => date('Y-m-d H:i:s')],

            // Jawaban untuk soal 4 (kaidah 2)
            ['id_soal' => 4, 'teks_jawaban' => 'بِنْتٌ (bintun)', 'is_benar' => true, 'urutan' => 1, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 4, 'teks_jawaban' => 'أَخٌ (akhun)', 'is_benar' => false, 'urutan' => 2, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 4, 'teks_jawaban' => 'وَلَدٌ (waladun)', 'is_benar' => false, 'urutan' => 3, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 4, 'teks_jawaban' => 'رَجُلٌ (rajulun)', 'is_benar' => false, 'urutan' => 4, 'waktu_dibuat' => date('Y-m-d H:i:s')],

            // Jawaban untuk soal 5 (kaidah 2)
            ['id_soal' => 5, 'teks_jawaban' => 'Isim mudzakkar', 'is_benar' => false, 'urutan' => 1, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 5, 'teks_jawaban' => 'Isim muannats', 'is_benar' => true, 'urutan' => 2, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 5, 'teks_jawaban' => 'Isim jamak', 'is_benar' => false, 'urutan' => 3, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 5, 'teks_jawaban' => 'Fiil madhi', 'is_benar' => false, 'urutan' => 4, 'waktu_dibuat' => date('Y-m-d H:i:s')],

            // Jawaban untuk soal 6 (kaidah 3)
            ['id_soal' => 6, 'teks_jawaban' => 'Nashab', 'is_benar' => false, 'urutan' => 1, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 6, 'teks_jawaban' => 'Khafdh', 'is_benar' => false, 'urutan' => 2, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 6, 'teks_jawaban' => 'Rafa\'', 'is_benar' => true, 'urutan' => 3, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 6, 'teks_jawaban' => 'Jazm', 'is_benar' => false, 'urutan' => 4, 'waktu_dibuat' => date('Y-m-d H:i:s')],

            // Jawaban untuk soal 7 (kaidah 3)
            ['id_soal' => 7, 'teks_jawaban' => 'Dommah (ـُ)', 'is_benar' => false, 'urutan' => 1, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 7, 'teks_jawaban' => 'Fathah (ـَ)', 'is_benar' => true, 'urutan' => 2, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 7, 'teks_jawaban' => 'Kasrah (ـِ)', 'is_benar' => false, 'urutan' => 3, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 7, 'teks_jawaban' => 'Sukun (ـْ)', 'is_benar' => false, 'urutan' => 4, 'waktu_dibuat' => date('Y-m-d H:i:s')],

            // Jawaban untuk soal 8 (kaidah 4)
            ['id_soal' => 8, 'teks_jawaban' => 'Maf\'ul bihi', 'is_benar' => false, 'urutan' => 1, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 8, 'teks_jawaban' => 'Mubtada\' dan Khabar', 'is_benar' => true, 'urutan' => 2, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 8, 'teks_jawaban' => 'Jar wa Majrur', 'is_benar' => false, 'urutan' => 3, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 8, 'teks_jawaban' => 'Isim dhamir', 'is_benar' => false, 'urutan' => 4, 'waktu_dibuat' => date('Y-m-d H:i:s')],

            // Jawaban untuk soal 9 (kaidah 5)
            ['id_soal' => 9, 'teks_jawaban' => 'Rafa\'', 'is_benar' => false, 'urutan' => 1, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 9, 'teks_jawaban' => 'Khafdh', 'is_benar' => false, 'urutan' => 2, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 9, 'teks_jawaban' => 'Nashab', 'is_benar' => true, 'urutan' => 3, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 9, 'teks_jawaban' => 'Jazm', 'is_benar' => false, 'urutan' => 4, 'waktu_dibuat' => date('Y-m-d H:i:s')],

            // Jawaban untuk soal 10 (kaidah 5)
            ['id_soal' => 10, 'teks_jawaban' => 'Rafa\'', 'is_benar' => false, 'urutan' => 1, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 10, 'teks_jawaban' => 'Nashab', 'is_benar' => true, 'urutan' => 2, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 10, 'teks_jawaban' => 'Khafdh', 'is_benar' => false, 'urutan' => 3, 'waktu_dibuat' => date('Y-m-d H:i:s')],
            ['id_soal' => 10, 'teks_jawaban' => 'Jazm', 'is_benar' => false, 'urutan' => 4, 'waktu_dibuat' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('pilihan_jawaban')->insertBatch($data);
    }
}
