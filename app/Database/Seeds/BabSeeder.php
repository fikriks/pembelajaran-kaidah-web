<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BabSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_bab' => 'BAB 1: KALAM',
                'deskripsi' => 'Pengenalan dasar kalam (kalimat) dan huruf-huruf dalam bahasa Arab',
                'urutan' => 1,
                'is_active' => true,
                'waktu_dibuat' => date('Y-m-d H:i:s'),
                'waktu_diubah' => date('Y-m-d H:i:s'),
            ],
            [
                'nama_bab' => 'BAB 2: I\'RAB',
                'deskripsi' => 'Pengenalan dasar ilmu i\'rab (nahwu) untuk memahami struktur kalimat Arab',
                'urutan' => 2,
                'is_active' => true,
                'waktu_dibuat' => date('Y-m-d H:i:s'),
                'waktu_diubah' => date('Y-m-d H:i:s'),
            ],
                    ];

        $this->db->table('bab')->insertBatch($data);
    }
}
