<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PenggunaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_pengguna' => 'admin',
                'kata_sandi'    => password_hash('admin123', PASSWORD_DEFAULT),
                'nama_lengkap'  => 'Administrator',
                'hak_akses'     => 'ADMIN',
                'foto_profil'   => null,
                'status'        => 'AKTIF',
                'waktu_dibuat'  => date('Y-m-d H:i:s'),
                'waktu_diubah'  => date('Y-m-d H:i:s'),
            ],
            [
                'nama_pengguna' => 'muhammad_faiz',
                'kata_sandi'    => password_hash('guru123', PASSWORD_DEFAULT),
                'nama_lengkap'  => 'KM. Muhammad Faiz, S.Ag.',
                'hak_akses'     => 'GURU',
                'foto_profil'   => null,
                'status'        => 'AKTIF',
                'waktu_dibuat'  => date('Y-m-d H:i:s'),
                'waktu_diubah'  => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('pengguna')->insertBatch($data);
    }
}
