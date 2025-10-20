<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SesiLatihanMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_sesi' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_siswa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_materi' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'seed_digunakan' => [
                'type'       => 'BIGINT',
                'null'       => false,
            ],
            'total_soal' => [
                'type'       => 'INT',
                'default'    => 20,
                'null'       => false,
            ],
            'soal_benar' => [
                'type'       => 'INT',
                'default'    => 0,
                'null'       => false,
            ],
            'skor' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
                'null'       => false,
            ],
            'waktu_mulai' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'waktu_selesai' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'durasi_detik' => [
                'type' => 'INT',
                'null' => true,
            ],
            'status' => [
                'type'       => "ENUM('sedang_berjalan', 'selesai')",
                'default'    => 'sedang_berjalan',
                'null'       => false,
            ],
            'waktu_dibuat' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id_sesi', true);
        $this->forge->addForeignKey('id_materi', 'materi_kaidah', 'id_materi', 'CASCADE', 'CASCADE');
        $this->forge->createTable('sesi_latihan');
    }

    public function down()
    {
        $this->forge->dropTable('sesi_latihan');
    }
}
