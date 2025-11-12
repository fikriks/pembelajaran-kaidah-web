<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Bab1KalamSoalSeeder extends Seeder
{
    public function run()
    {
        $data = [];

        // BAB 1: KALAM - 20 Soal (2 soal per materi)

        // Materi 1: Pengenalan Kalam (ID: 1)
        $data[] = [
            'id_bab' => 1,
            'pertanyaan' => 'Apa pengertian كَلاَمٌ (kalam) menurut ilmu nahwu?',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'mudah',
            'poin' => 10,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'Ucapan yang tersusun dari kata-kata bermakna', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'Tulisan tanpa arti', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'Kata tunggal saja', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'Percakapan bebas', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        $data[] = [
            'id_bab' => 1,
            'pertanyaan' => 'Manakah yang BUKAN syarat kalam dalam ilmu nahwu?',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'mudah',
            'poin' => 10,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'Mempunyai arti yang jelas', 'is_benar' => false, 'urutan' => 1],
                ['teks_jawaban' => 'Terdiri dari satu kata', 'is_benar' => true, 'urutan' => 2],
                ['teks_jawaban' => 'Mengikuti aturan bahasa Arab', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'Dapat dimengerti pendengar', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        // Materi 2: Huruf-huruf Kalam (ID: 2)
        $data[] = [
            'id_bab' => 1,
            'pertanyaan' => 'Berapa jumlah huruf hijaiyyah dalam bahasa Arab?',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'mudah',
            'poin' => 10,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => '26 huruf', 'is_benar' => false, 'urutan' => 1],
                ['teks_jawaban' => '28 huruf', 'is_benar' => true, 'urutan' => 2],
                ['teks_jawaban' => '30 huruf', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => '24 huruf', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        $data[] = [
            'id_bab' => 1,
            'pertanyaan' => 'Manakah huruf yang termasuk حُرُوفٌ مُهْمَلَةٌ (huruf tanpa titik)?',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'sedang',
            'poin' => 15,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'ب, ج, خ, ن, ي', 'is_benar' => false, 'urutan' => 1],
                ['teks_jawaban' => 'ا, د, ذ, ر, ز, و', 'is_benar' => true, 'urutan' => 2],
                ['teks_jawaban' => 'ت, ث, س, ش, ص, ض', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'ط, ظ, ع, غ, ف, ق', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        // Materi 3: Alif dan Lam (ID: 3)
        $data[] = [
            'id_bab' => 1,
            'pertanyaan' => 'Apa fungsi dari أَلِفٌ وَلاَمٌ (alif dan lam) dalam bahasa Arab?',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'mudah',
            'poin' => 10,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'Sebagai definite article (penunjuk benda tertentu)', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'Sebagai kata tanya', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'Sebagai kata hubung', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'Sebagai partikel negasi', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        $data[] = [
            'id_bab' => 1,
            'pertanyaan' => 'Kata الشَّمْسُ (asy-syamsu) menggunakan jenis alif lam apa?',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'sedang',
            'poin' => 15,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'الْقَمَرِيَّةُ (Al-Qamariyyah)', 'is_benar' => false, 'urutan' => 1],
                ['teks_jawaban' => 'الشَّمْسِيَّةُ (Asy-Syamsiyyah)', 'is_benar' => true, 'urutan' => 2],
                ['teks_jawaban' => 'الْمُؤَنَّثُ (Al-Muannats)', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'الْمُذَكَّرُ (Al-Mudzakkar)', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        // Materi 4: Ta Marbutah (ID: 4)
        $data[] = [
            'id_bab' => 1,
            'pertanyaan' => 'Bagaimana cara membaca تَاءٌ مَرْبُوطَةٌ (ta marbutah) saat waqaf (berhenti)?',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'mudah',
            'poin' => 10,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'Dibaca "h" (هاء)', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'Dibaca "t" (تاء)', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'Dibaca "s" (سين)', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'Tidak dibaca', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        $data[] = [
            'id_bab' => 1,
            'pertanyaan' => 'Perubahan apa yang terjadi pada تَاءٌ مَرْبُوطَةٌ saat menjadi mudhaf ilaih (ditambah milik)?',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'sedang',
            'poin' => 15,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'Menjadi اتِ (ati)', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'Menjadi تَاتِ (tati)', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'Menjadi تِ (ti)', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'Tidak berubah', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        // Materi 5: Waw (ID: 5)
        $data[] = [
            'id_bab' => 1,
            'pertanyaan' => 'وَاوُ الْعَطْفِ (Wawu athaf) memiliki fungsi apa dalam kalimat?',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'mudah',
            'poin' => 10,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'Sebagai kata hubung "dan"', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'Sebagai kata ganti', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'Sebagai partikel negasi', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'Sebagai kata tanya', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        $data[] = [
            'id_bab' => 1,
            'pertanyaan' => 'Dalam kalimat وَاللهِ لَأَفْعَلَنَّ (wallahi lafa\'lanan), waw berfungsi sebagai:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'sedang',
            'poin' => 15,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'وَاوُ الْعَطْفِ (Wawu athaf)', 'is_benar' => false, 'urutan' => 1],
                ['teks_jawaban' => 'وَاوُ الْقَسَمِ (Wawu qasam)', 'is_benar' => true, 'urutan' => 2],
                ['teks_jawaban' => 'وَاوُ الْحَالِ (Wawu hal)', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'وَاوُ مَعْنَوِيَّةٌ (Wawu ma\'nawiyah)', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        // Materi 6: Ya (ID: 6)
        $data[] = [
            'id_bab' => 1,
            'pertanyaan' => 'يَاءُ الْمُتَكَلِّمِ (Ya mutakallim) digunakan untuk:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'mudah',
            'poin' => 10,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'Penutur pertama (aku, kami)', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'Orang kedua (kamu)', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'Orang ketiga (dia)', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'Tempat (di, ke)', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        $data[] = [
            'id_bab' => 1,
            'pertanyaan' => 'مِصْرِيّ (mishriyy) adalah contoh dari يَاءُ:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'sedang',
            'poin' => 15,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'يَاءُ الْمُتَكَلِّمِ (Ya mutakallim)', 'is_benar' => false, 'urutan' => 1],
                ['teks_jawaban' => 'يَاءُ الْمُخَاطَبِ (Ya mukhathab)', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'يَاءُ النِّسْبَةِ (Ya nisbah)', 'is_benar' => true, 'urutan' => 3],
                ['teks_jawaban' => 'يَاءُ الْغَائِبِ (Ya ghaib)', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        // Materi 7: Nun (ID: 7)
        $data[] = [
            'id_bab' => 1,
            'pertanyaan' => 'نُوْنُ التَّثْنِيَةِ (Nun tatsniyah) digunakan untuk:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'mudah',
            'poin' => 10,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'Bentuk ganda (dua)', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'Bentuk tunggal', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'Bentuk jamak', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'Bentuk feminin', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        $data[] = [
            'id_bab' => 1,
            'pertanyaan' => 'Perubahan huruf nun saat bertemu dengan alif adalah:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'sedang',
            'poin' => 15,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'Menjadi ا (idgham)', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'Menjadi ن (nun)', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'Menjadi م (mim)', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'Tidak berubah', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        // Materi 8: Ta Mabsuthah (ID: 8)
        $data[] = [
            'id_bab' => 1,
            'pertanyaan' => 'Perbedaan utama تَاءٌ مَبْسُوْطَةٌ (ta mabsuthah) dengan تَاءٌ مَرْبُوطَةٌ (ta marbutah) adalah:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'mudah',
            'poin' => 10,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'Ta mabsuthah selalu dibaca "t"', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'Ta marbutah selalu dibaca "t"', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'Ta mabsuthah tidak pernah dipakai', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'Tidak ada perbedaan', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        $data[] = [
            'id_bab' => 1,
            'pertanyaan' => 'Manakah kata yang menggunakan ta mabsuthah di akhir?',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'sedang',
            'poin' => 15,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'فَاطِمَةٌ (Fatimah)', 'is_benar' => false, 'urutan' => 1],
                ['teks_jawaban' => 'بَنَاتُ (banatu)', 'is_benar' => true, 'urutan' => 2],
                ['teks_jawaban' => 'حَدِيْقَةٌ (hadidqah)', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'بَيْتٌ (baitun)', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        // Materi 9: Ta Mabsuthah di Akhir Kata (ID: 9)
        $data[] = [
            'id_bab' => 1,
            'pertanyaan' => 'Ta di akhir kata menunjukkan:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'mudah',
            'poin' => 10,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'Jenis kelamin feminin', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'Jenis kelamin maskulin', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'Bentuk tunggal', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'Bentuk jamak', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        $data[] = [
            'id_bab' => 1,
            'pertanyaan' => 'Kata طَبِيْبَةٌ (thabibah) adalah contoh dari:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'sedang',
            'poin' => 15,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'Dokter laki-laki', 'is_benar' => false, 'urutan' => 1],
                ['teks_jawaban' => 'Dokter perempuan', 'is_benar' => true, 'urutan' => 2],
                ['teks_jawaban' => 'Siswa laki-laki', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'Siswa perempuan', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        // Materi 10: Sukun (ID: 10)
        $data[] = [
            'id_bab' => 1,
            'pertanyaan' => 'سُكُونٌ (Sukun) adalah tanda baca yang menunjukkan:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'mudah',
            'poin' => 10,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'Huruf mati (tidak memiliki vokal)', 'is_benar' => true, 'urutan' => 1],
                ['teks_jawaban' => 'Huruf berbaris fathah', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'Huruf berbaris kasrah', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'Huruf berbaris dammah', 'is_benar' => false, 'urutan' => 4]
            ]
        ];

        $data[] = [
            'id_bab' => 1,
            'pertanyaan' => 'Dalam kalimat "الْحَمْدُ لِلّٰهِ", huruf ba (ب) menggunakan:',
            'tipe_soal' => 'pilihan_ganda',
            'tingkat_kesulitan' => 'sedang',
            'poin' => 15,
            'dibuat_oleh' => 2,
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'waktu_diubah' => date('Y-m-d H:i:s'),
            'pilihan_jawaban' => [
                ['teks_jawaban' => 'Fathah (َ)', 'is_benar' => false, 'urutan' => 1],
                ['teks_jawaban' => 'Kasrah (ِ)', 'is_benar' => false, 'urutan' => 2],
                ['teks_jawaban' => 'Dammah (ُ)', 'is_benar' => false, 'urutan' => 3],
                ['teks_jawaban' => 'Sukun (ْ)', 'is_benar' => true, 'urutan' => 4]
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