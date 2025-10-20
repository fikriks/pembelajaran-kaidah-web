<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DetailJawabanSiswaMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_detail' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_sesi' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_soal' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_pilihan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'urutan_soal' => [
                'type'       => 'INT',
                'null'       => false,
            ],
            'is_benar' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
                'null'       => false,
            ],
            'waktu_jawab' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id_detail', true);
        $this->forge->addForeignKey('id_sesi', 'sesi_latihan', 'id_sesi', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_soal', 'soal', 'id_soal', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_pilihan', 'pilihan_jawaban', 'id_pilihan', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detail_jawaban_siswa');
    }

    public function down()
    {
        $this->forge->dropTable('detail_jawaban_siswa');
    }
}
