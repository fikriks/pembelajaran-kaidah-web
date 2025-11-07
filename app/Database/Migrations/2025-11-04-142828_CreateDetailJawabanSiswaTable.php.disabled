<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetailJawabanSiswaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_detail' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_sesi' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => false,
            ],
            'id_soal' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => false,
            ],
            'id_pilihan' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'ID pilihan jawaban yang dipilih siswa',
            ],
            'urutan_soal' => [
                'type'       => 'INT',
                'null'       => false,
                'comment'    => 'Urutan soal setelah diacak dengan LCM (1,2,3...)',
            ],
            'is_benar' => [
                'type'       => 'BOOLEAN',
                'null'       => false,
                'default'    => false,
                'comment'    => 'Apakah jawaban siswa benar',
            ],
            'waktu_jawab' => [
                'type'       => 'DATETIME',
                'null'       => false,
                'comment'    => 'Waktu siswa menjawab soal',
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

        $this->forge->addKey('id_detail', true);
        $this->forge->addForeignKey('id_sesi', 'sesi_pembelajaran', 'id_sesi', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_soal', 'soal', 'id_soal', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_pilihan', 'pilihan_jawaban', 'id_pilihan', 'CASCADE', 'CASCADE');
        $this->forge->addKey(['id_sesi', 'urutan_soal']);
        $this->forge->addKey(['id_sesi', 'is_benar']);
        $this->forge->addKey(['waktu_jawab']);

        $this->forge->createTable('detail_jawaban_siswa');
    }

    public function down()
    {
        $this->forge->dropTable('detail_jawaban_siswa');
    }
}
