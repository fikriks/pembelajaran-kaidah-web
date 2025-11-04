<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSesiPembelajaranTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_sesi' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_siswa' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => false,
            ],
            'id_materi' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => false,
            ],
            'seed_digunakan' => [
                'type'       => 'BIGINT',
                'null'       => false,
                'comment'    => 'Seed LCM yang digunakan untuk randomisasi',
            ],
            'total_soal' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 20,
                'null'       => false,
                'comment'    => 'Jumlah total soal dalam sesi',
            ],
            'soal_benar' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 0,
                'null'       => false,
                'comment'    => 'Jumlah soal yang dijawab benar',
            ],
            'skor' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
                'null'       => false,
                'comment'    => 'Total skor yang didapat',
            ],
            'waktu_mulai' => [
                'type'       => 'DATETIME',
                'null'       => false,
                'comment'    => 'Waktu sesi dimulai',
            ],
            'waktu_selesai' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'comment'    => 'Waktu sesi selesai',
            ],
            'durasi_detik' => [
                'type'       => 'INT',
                'null'       => true,
                'comment'    => 'Durasi pengerjaan dalam detik',
            ],
            'status' => [
                'type'       => "ENUM('sedang_berjalan', 'selesai', 'dibatalkan')",
                'default'    => 'sedang_berjalan',
                'null'       => false,
                'comment'    => 'Status sesi pembelajaran',
            ],
            'waktu_dibuat' => [
                'type'       => 'DATETIME',
                'null'       => false,
            ],
            'waktu_diubah' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id_sesi', true);
        $this->forge->addForeignKey('id_siswa', 'siswa', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_materi', 'materi_kaidah', 'id_materi', 'CASCADE', 'CASCADE');
        $this->forge->addKey(['id_siswa', 'id_materi']);
        $this->forge->addKey(['status']);
        $this->forge->addKey(['waktu_mulai']);

        $this->forge->createTable('sesi_pembelajaran');
    }

    public function down()
    {
        $this->forge->dropTable('sesi_pembelajaran');
    }
}
