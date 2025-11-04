<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SiswaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nis' => '2025001',
                'nama_lengkap' => 'Ahmad Fauzi',
                'kata_sandi' => '123456', // Will be hashed by model callback
                'jenis_kelamin' => 'L',
                'kelas' => 'XI-A',
                'status' => 'AKTIF',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
                'waktu_diubah' => date('Y-m-d H:i:s'),
            ],
            [
                'nis' => '2025002',
                'nama_lengkap' => 'Siti Nurhaliza',
                'kata_sandi' => '123456',
                'jenis_kelamin' => 'P',
                'kelas' => 'XI-A',
                'status' => 'AKTIF',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
                'waktu_diubah' => date('Y-m-d H:i:s'),
            ],
            [
                'nis' => '2025003',
                'nama_lengkap' => 'Muhammad Rizki',
                'kata_sandi' => '123456',
                'jenis_kelamin' => 'L',
                'kelas' => 'XI-B',
                'status' => 'AKTIF',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
                'waktu_diubah' => date('Y-m-d H:i:s'),
            ],
            [
                'nis' => '2025004',
                'nama_lengkap' => 'Fatimah Az Zahra',
                'kata_sandi' => '123456',
                'jenis_kelamin' => 'P',
                'kelas' => 'X-A',
                'status' => 'AKTIF',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
                'waktu_diubah' => date('Y-m-d H:i:s'),
            ],
            [
                'nis' => '2025005',
                'nama_lengkap' => 'Abdul Rahman',
                'kata_sandi' => '123456',
                'jenis_kelamin' => 'L',
                'kelas' => 'X-B',
                'status' => 'NONAKTIF',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
                'waktu_diubah' => date('Y-m-d H:i:s'),
            ],
            [
                'nis' => '2025006',
                'nama_lengkap' => 'Khadijah binti Khuwailid',
                'kata_sandi' => '123456',
                'jenis_kelamin' => 'P',
                'kelas' => 'XI-B',
                'status' => 'AKTIF',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
                'waktu_diubah' => date('Y-m-d H:i:s'),
            ],
            [
                'nis' => '2025007',
                'nama_lengkap' => 'Umar bin Khattab',
                'kata_sandi' => '123456',
                'jenis_kelamin' => 'L',
                'kelas' => 'X-A',
                'status' => 'AKTIF',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
                'waktu_diubah' => date('Y-m-d H:i:s'),
            ],
            [
                'nis' => '2025008',
                'nama_lengkap' => 'Aisyah binti Abu Bakar',
                'kata_sandi' => '123456',
                'jenis_kelamin' => 'P',
                'kelas' => 'XI-A',
                'status' => 'AKTIF',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
                'waktu_diubah' => date('Y-m-d H:i:s'),
            ],
            [
                'nis' => '2025009',
                'nama_lengkap' => 'Ali bin Abi Thalib',
                'kata_sandi' => '123456',
                'jenis_kelamin' => 'L',
                'kelas' => 'X-B',
                'status' => 'AKTIF',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
                'waktu_diubah' => date('Y-m-d H:i:s'),
            ],
            [
                'nis' => '2025010',
                'nama_lengkap' => 'Zainab binti Muhammad',
                'kata_sandi' => '123456',
                'jenis_kelamin' => 'P',
                'kelas' => 'XI-B',
                'status' => 'AKTIF',
                'waktu_dibuat' => date('Y-m-d H:i:s'),
                'waktu_diubah' => date('Y-m-d H:i:s'),
            ],
        ];

        // Using Simple Query Builder to bypass model callback for now
        // We'll hash kata_sandi manually since we're inserting directly
        $this->db->table('siswa')->insertBatch($data);

        // Now we need to hash the kata_sandi
        $siswaModel = new \App\Models\SiswaModel();
        $allSiswa = $this->db->table('siswa')->get()->getResultArray();

        foreach ($allSiswa as $siswa) {
            // Update with hashed kata_sandi
            $hashedPassword = password_hash('123456', PASSWORD_DEFAULT);
            $this->db->table('siswa')
                ->where('id', $siswa['id'])
                ->update(['kata_sandi' => $hashedPassword]);
        }

        echo "SiswaSeeder: " . count($data) . " siswa berhasil ditambahkan.\n";
    }
}