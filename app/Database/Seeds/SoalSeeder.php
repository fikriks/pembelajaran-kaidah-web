<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SoalSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Soal untuk kaidah 1 (Isim Mufrad dan Jamak)
            [
                'id_materi'       => 1,
                'pertanyaan'      => 'Apa bentuk jamak dari كِتَابٌ (kitabun)?',
                'tipe_soal'       => 'pilihan_ganda',
                'tingkat_kesulitan'=> 'mudah',
                'poin'           => 10,
                'dibuat_oleh'     => 2, // guru1
                'waktu_dibuat'    => date('Y-m-d H:i:s'),
                'waktu_diubah'    => date('Y-m-d H:i:s'),
            ],
            [
                'id_materi'       => 1,
                'pertanyaan'      => 'Manakah yang termasuk isim mufrad?',
                'tipe_soal'       => 'pilihan_ganda',
                'tingkat_kesulitan'=> 'mudah',
                'poin'           => 10,
                'dibuat_oleh'     => 2, // guru1
                'waktu_dibuat'    => date('Y-m-d H:i:s'),
                'waktu_diubah'    => date('Y-m-d H:i:s'),
            ],
            [
                'id_materi'       => 1,
                'pertanyaan'      => 'مُدَرِّسُونَ (mudarrisuna) adalah bentuk jamak dari kata?',
                'tipe_soal'       => 'pilihan_ganda',
                'tingkat_kesulitan'=> 'sedang',
                'poin'           => 15,
                'dibuat_oleh'     => 2, // guru1
                'waktu_dibuat'    => date('Y-m-d H:i:s'),
                'waktu_diubah'    => date('Y-m-d H:i:s'),
            ],

            // Soal untuk kaidah 2 (Isim Mudzakkar dan Muannats)
            [
                'id_materi'       => 2,
                'pertanyaan'      => 'Apa lawan dari وَلَدٌ (waladun)?',
                'tipe_soal'       => 'pilihan_ganda',
                'tingkat_kesulitan'=> 'mudah',
                'poin'           => 10,
                'dibuat_oleh'     => 2, // guru1
                'waktu_dibuat'    => date('Y-m-d H:i:s'),
                'waktu_diubah'    => date('Y-m-d H:i:s'),
            ],
            [
                'id_materi'       => 2,
                'pertanyaan'      => 'مُسْلِمَةٌ (muslimatun) termasuk jenis isim apa?',
                'tipe_soal'       => 'pilihan_ganda',
                'tingkat_kesulitan'=> 'sedang',
                'poin'           => 15,
                'dibuat_oleh'     => 2, // guru1
                'waktu_dibuat'    => date('Y-m-d H:i:s'),
                'waktu_diubah'    => date('Y-m-d H:i:s'),
            ],

            // Soal untuk kaidah 3 (Rafa', Nashab, dan Khafdh)
            [
                'id_materi'       => 3,
                'pertanyaan'      => 'Kata الْوَلَدُُ (al-waladu) berada dalam keadaan apa?',
                'tipe_soal'       => 'pilihan_ganda',
                'tingkat_kesulitan'=> 'sedang',
                'poin'           => 15,
                'dibuat_oleh'     => 3, // guru2
                'waktu_dibuat'    => date('Y-m-d H:i:s'),
                'waktu_diubah'    => date('Y-m-d H:i:s'),
            ],
            [
                'id_materi'       => 3,
                'pertanyaan'      => 'Tanda baris untuk keadaan nashab adalah...',
                'tipe_soal'       => 'pilihan_ganda',
                'tingkat_kesulitan'=> 'mudah',
                'poin'           => 10,
                'dibuat_oleh'     => 3, // guru2
                'waktu_dibuat'    => date('Y-m-d H:i:s'),
                'waktu_diubah'    => date('Y-m-d H:i:s'),
            ],

            // Soal untuk kaidah 4 (Al-Marfu\'at)
            [
                'id_materi'       => 4,
                'pertanyaan'      => 'Yang termasuk kata yang selalu rafa\' adalah...',
                'tipe_soal'       => 'pilihan_ganda',
                'tingkat_kesulitan'=> 'sedang',
                'poin'           => 15,
                'dibuat_oleh'     => 3, // guru2
                'waktu_dibuat'    => date('Y-m-d H:i:s'),
                'waktu_diubah'    => date('Y-m-d H:i:s'),
            ],

            // Soal untuk kaidah 5 (Al-Mansubat)
            [
                'id_materi'       => 5,
                'pertanyaan'      => 'Dalam kalimat "قَرَأْتُ الْكِتَابَ", kata الْكِتَابَ berada dalam keadaan...',
                'tipe_soal'       => 'pilihan_ganda',
                'tingkat_kesulitan'=> 'sedang',
                'poin'           => 15,
                'dibuat_oleh'     => 2, // guru1
                'waktu_dibuat'    => date('Y-m-d H:i:s'),
                'waktu_diubah'    => date('Y-m-d H:i:s'),
            ],
            [
                'id_materi'       => 5,
                'pertanyaan'      => 'Maf\'ul bihi adalah contoh dari kata yang...',
                'tipe_soal'       => 'pilihan_ganda',
                'tingkat_kesulitan'=> 'sulit',
                'poin'           => 20,
                'dibuat_oleh'     => 2, // guru1
                'waktu_dibuat'    => date('Y-m-d H:i:s'),
                'waktu_diubah'    => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('soal')->insertBatch($data);
    }
}
