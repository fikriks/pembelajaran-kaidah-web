<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Bab2IrabSoalSeeder extends Seeder
{
    public function run()
    {
        $data = [];

        // BAB 2: I'RAB - 20 Soal (2 soal per materi)

        // Materi 11: I'rab (ID: 11)
        $data[] = [
            'id_bab' => 2,
            'pertanyaan' => 'إِعْرَابٌ (I\'rab) adalah perubahan akhir kata karena:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'mudah',
            'poin' => 10,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'Perubahan faktor grammar (amil)', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'Perubahan arti kata', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'Perubahan struktur kalimat', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'Perubahan jumlah kata', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        $data[] = [
            'id_bab' => 2,
            'pertanyaan' => 'Tanda asli untuk status رَفْعٌ (rafa\') adalah:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'mudah',
            'poin' => 10,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'ضَمَّةٌ (dammah) atau tanwin dhammah', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'فَتْحَةٌ (fathah) atau tanwin fathah', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'كَسْرَةٌ (kasrah) atau tanwin kasrah', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'سُكُونٌ (sukun)', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        // Materi 12: I'rab Khosus (ID: 12)
        $data[] = [
            'id_bab' => 2,
            'pertanyaan' => 'أَبٌ (abun) termasuk dalam إِعْرَابُ الْأَسْمَاءِ الْخَمْسَةِ (i\'rab 5 isim). Tandanya:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'sedang',
            'poin' => 15,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'رَفْعٌ: واو (ذُوْ), نَصْبٌ: ا (أَبَا), خَفْضٌ: ي (أَبِيْ)', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'رَفْعٌ: ضَمَّة, نَصْبٌ: فَتْحَة, خَفْضٌ: كَسْرَة', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'رَفْعٌ: ا, نَصْبٌ: و, خَفْضٌ: ي', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'رَفْعٌ: نُون, نَصْبٌ: تَاء, خَفْضٌ: يَاء', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        $data[] = [
            'id_bab' => 2,
            'pertanyaan' => 'Manakah yang BUKAN termasuk dalam إِعْرَابٌ خَاصٌّ (i\'rab khusus)?',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'sedang',
            'poin' => 15,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'إِعْرَابُ الْمُثَنَّى (I\'rab mutsanna)', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'إِعْرَابُ الْأَسْمَاءِ الْخَمْسَةِ', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'إِعْرَابُ جَمْعِ الْمُذَكَّرِ السَّالِمِ', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'إِعْرَابُ الْأَفْعَالِ الْخَمْسَةِ', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        // Materi 13: I'rab Majmu' (ID: 13)
        $data[] = [
            'id_bab' => 2,
            'pertanyaan' => 'I\'rab untuk جَمْعُ الْمُذَكَّرِ السَّالِمِ (jamak mudzakkar salim) adalah:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'sedang',
            'poin' => 15,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'رَفْعٌ: واوٌ + نُوْنٌ, نَصْبٌ: يَاءً + نُوْنٌ, خَفْضٌ: يَاءٍ + نُوْنٌ', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'رَفْعٌ: ضَمَّة, نَصْبٌ: فَتْحَة, خَفْضٌ: كَسْرَة', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'رَفْعٌ: ا, نَصْبٌ: ى, خَفْضٌ: ي', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'رَفْعٌ: تَاء, نَصْبٌ: تَاء, خَفْضٌ: تَاء', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        $data[] = [
            'id_bab' => 2,
            'pertanyaan' => 'Perbedaan i\'rab antara جَمْعُ الْمُؤَنَّثِ السَّالِمِ (jamak muannats salim) dengan jamak taksir adalah:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'sedang',
            'poin' => 15,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'Jamak muannats: ا + تاء untuk semua kasus, Jamak taksir: mengikuti pola isim', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'Jamak muannats: و + ن, Jamak taksir: ض + ف + ك', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'Jamak muannats: ي + ن, Jamak taksir: ا + ت + ن', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'Tidak ada perbedaan', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        // Materi 14: I'rab Mutsanna (ID: 14)
        $data[] = [
            'id_bab' => 2,
            'pertanyaan' => 'Tanda رَفْعٌ (rafa\') untuk kata ganda (mutsanna) adalah:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'sedang',
            'poin' => 15,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'ا (alif) di akhir kata', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'ي (ya) di akhir kata', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'و (waw) di akhir kata', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'ن (nun) di akhir kata', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        $data[] = [
            'id_bab' => 2,
            'pertanyaan' => 'كِتَابٌ → كِتَابَانِ adalah contoh pembentukan kata ganda dari:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'sedang',
            'poin' => 15,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'Isim maskulin tanpa ta marbutah', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'Isim muannats dengan ta marbutah', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'Fi\'il madhi dengan dhamir ketiga', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'Fi\'il amr dengan dhamir kedua', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        // Materi 15: Isim Mufrad (ID: 15)
        $data[] = [
            'id_bab' => 2,
            'pertanyaan' => 'إِسْمٌ عَلَمٌ (isim \'alam) adalah:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'mudah',
            'poin' => 10,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'Nama proper (nama orang, tempat)', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'Nama generik (jenis benda)', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'Kata kerja dalam bentuk kata benda', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'Keterangan cara melakukan sesuatu', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        $data[] = [
            'id_bab' => 2,
            'pertanyaan' => 'مِفْتَاحٌ (miftah) termasuk dalam jenis isim:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'sedang',
            'poin' => 15,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'إِسْمٌ آلَةٌ (Isim Alat)', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'إِسْمٌ عَلَمٌ (Isim Alam)', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'إِسْمٌ جِنْسٌ (Isim Jins)', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'إِسْمٌ مَصْدَرٌ (Isim Mashdar)', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        // Materi 16: Fi'il Madhi (ID: 16)
        $data[] = [
            'id_bab' => 2,
            'pertanyaan' => 'فِعْلٌ مَاضِيٌ (Fi\'il Madhi) adalah kata kerja yang menunjukkan:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'mudah',
            'poin' => 10,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'Perbuatan yang sudah terjadi di masa lalu', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'Perbuatan yang sedang terjadi', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'Perbuatan yang akan terjadi', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'Perbuatan yang sedang diminta', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        $data[] = [
            'id_bab' => 2,
            'pertanyaan' => 'Dhamir untuk orang ketiga laki-laki dalam fi\'il madhi adalah:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'sedang',
            'poin' => 15,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => ' (tanda tanpa huruf) untuk fi\'il biasa', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'ت (ta) untuk fi\'il feminin', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'ن (nun) untuk jamak feminin', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'نا (na) untuk jamak', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        // Materi 17: Fi'il Mudhari' (ID: 17)
        $data[] = [
            'id_bab' => 2,
            'pertanyaan' => 'فِعْلٌ مُضَارِعٌ (Fi\'il Mudhari\') menunjukkan perbuatan yang:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'mudah',
            'poin' => 10,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'Sedang terjadi atau akan terjadi', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'Sudah terjadi di masa lalu', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'Diminta untuk dilakukan', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'Tidak akan terjadi', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        $data[] = [
            'id_bab' => 2,
            'pertanyaan' => 'Perubahan كَتَبَ (kataba) menjadi fi\'il mudhari\' adalah:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'sedang',
            'poin' => 15,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'يَكْتُبُ (yaktubu)', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'كَاتِبُ (katibu)', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'كَاتِبِ (katib)', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'مَكْتُوبُ (maktubu)', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        // Materi 18: Fi'il Amr (ID: 18)
        $data[] = [
            'id_bab' => 2,
            'pertanyaan' => 'فِعْلٌ أَمْرٌ (Fi\'il Amr) digunakan untuk:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'mudah',
            'poin' => 10,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'Meminta atau menyuruh seseorang melakukan sesuatu', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'Menceritakan kejadian yang lalu', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'Menanyatakan keadaan', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'Menyatakan kepemilikan', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        $data[] = [
            'id_bab' => 2,
            'pertanyaan' => 'Pola pembentukan fi\'il amr dari يَذْهَبُ (yadzhabu) adalah:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'sedang',
            'poin' => 15,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'Hilangkan ي, tambahkan dhamir, akhiri sukun: اذْهَبْ', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'Hilangkan ي, tambahkan س, akhiri ت: ذْهَبِتْ', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'Hilangkan ي, tambahkan و, akhiri و: ذْهَبُوْ', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'Hilangkan ي, tambahkan ن, akhiri ن: ذْهَبَنْ', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        // Materi 19: Mudzakkar dan Muannats (ID: 19)
        $data[] = [
            'id_bab' => 2,
            'pertanyaan' => 'Tanda umum untuk مُؤَنَّثٌ (muannats/feminin) adalah:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'mudah',
            'poin' => 10,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'ة (ta marbutah) di akhir kata', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'Tidak ada tanda khusus', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'Tanwin di akhir kata', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'Dhammah di akhir kata', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        $data[] = [
            'id_bab' => 2,
            'pertanyaan' => 'Manakah yang termasuk تَاءُ مُلَا (ta mula\'ah) sebagai tanda muannats?',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'sedang',
            'poin' => 15,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'أَرْضٌ (bumi), شَمْسٌ (matahari), نَارٌ (api)', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'بَابٌ (pintu), بَيْتٌ (rumah)', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'وَلَدٌ (anak laki-laki), رَجُلٌ (pria)', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'مُدَرِّسٌ (guru laki-laki)', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        // Materi 20: Tanda-tanda I'rab (ID: 20)
        $data[] = [
            'id_bab' => 2,
            'pertanyaan' => 'Tanda جَزْمٌ (jazm) adalah:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'mudah',
            'poin' => 10,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'سُكُونٌ (sukun) di akhir kata', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'ضَمَّةٌ (dammah) di akhir kata', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'فَتْحَةٌ (fathah) di akhir kata', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'كَسْرَةٌ (kasrah) di akhir kata', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        $data[] = [
            'id_bab' => 2,
            'pertanyaan' => 'Tanda pengganti untuk رَفْعٌ (rafa\') pada isim muannats salim adalah:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'sedang',
            'poin' => 15,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'ا (alif) untuk isim muannats salim dan fi\'il mutakallim', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'و (waw) untuk jamak mudzakkar salim', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'ي (ya) untuk kata ganda dan 5 isim khusus', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'ن (nun) untuk jamak muannats salim', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        // Insert soal satu per satu untuk mendapatkan ID yang benar
        $pilihanJawabanData = [];

        foreach ($data as $soal) {
            // Insert soal dan dapatkan ID
            $soalData = [
                'id_bab' => $soal['id_bab'],
                'pertanyaan' => $soal['pertanyaan'],
                'tipe_soal' => $soal['tipe_soal'],
                'tingkat_kesulitan' => $soal['tingkat_kesulitan'],
                'poin' => $soal['poin'],
                'dibuat_oleh' => $soal['dibuat_oleh'],
                'waktu_dibuat' => $soal['waktu_dibuat'],
                'waktu_diubah' => $soal['waktu_diubah'],
            ];

            $this->db->table('soal')->insert($soalData);
            $idSoal = $this->db->insertID();

            // Siapkan pilihan jawaban untuk soal ini
            foreach ($soal['pilihan_jawaban'] as $pilihan) {
                $pilihanJawabanData[] = [
                    'id_soal' => $idSoal,
                    'teks_jawaban' => $pilihan['teks_jawaban'],
                    'is_benar' => $pilihan['is_benar'],
                    'urutan' => $pilihan['urutan'],
                    'waktu_dibuat' => date('Y-m-d H:i:s')
                ];
            }
        }

        // Insert semua pilihan jawaban
        if (!empty($pilihanJawabanData)) {
            $this->db->table('pilihan_jawaban')->insertBatch($pilihanJawabanData);
        }
    }
}