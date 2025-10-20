<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MateriKaidahMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_materi' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'judul_kaidah' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'penjelasan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'contoh' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tingkat_kesulitan' => [
                'type'       => "ENUM('mudah', 'sedang', 'sulit')",
                'default'    => 'sedang',
                'null'       => false,
            ],
            'urutan' => [
                'type'       => 'INT',
                'default'    => 0,
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

        $this->forge->addKey('id_materi', true);
        $this->forge->addForeignKey('dibuat_oleh', 'pengguna', 'id_pengguna', 'CASCADE', 'CASCADE');
        $this->forge->createTable('materi_kaidah');
    }

    public function down()
    {
        $this->forge->dropTable('materi_kaidah');
    }
}
