<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SoalMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_soal' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_materi' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'pertanyaan' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'tipe_soal' => [
                'type'       => "ENUM('pilihan_ganda')",
                'default'    => 'pilihan_ganda',
                'null'       => false,
            ],
            'tingkat_kesulitan' => [
                'type'       => "ENUM('mudah', 'sedang', 'sulit')",
                'default'    => 'sedang',
                'null'       => false,
            ],
            'poin' => [
                'type'       => 'INT',
                'default'    => 10,
                'null'       => false,
            ],
            'dibuat_oleh' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'waktu_dibuat' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'waktu_diubah' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id_soal', true);
        $this->forge->addForeignKey('id_materi', 'materi_kaidah', 'id_materi', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('dibuat_oleh', 'pengguna', 'id_pengguna', 'CASCADE', 'CASCADE');
        $this->forge->createTable('soal');
    }

    public function down()
    {
        $this->forge->dropTable('soal');
    }
}
