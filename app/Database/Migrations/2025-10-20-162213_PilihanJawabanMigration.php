<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PilihanJawabanMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pilihan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_soal' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'teks_jawaban' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'is_benar' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
                'null'       => false,
            ],
            'urutan' => [
                'type'       => 'INT',
                'default'    => 0,
                'null'       => false,
            ],
            'waktu_dibuat' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id_pilihan', true);
        $this->forge->addForeignKey('id_soal', 'soal', 'id_soal', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pilihan_jawaban');
    }

    public function down()
    {
        $this->forge->dropTable('pilihan_jawaban');
    }
}
