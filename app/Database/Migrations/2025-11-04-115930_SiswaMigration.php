<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SiswaMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nis' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'unique' => true,
            ],
            'nama_lengkap' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'kata_sandi' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'jenis_kelamin' => [
                'type' => 'ENUM',
                'constraint' => ['L', 'P'],
            ],
            'kelas' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['AKTIF', 'NONAKTIF'],
                'default' => 'AKTIF',
            ],
            'waktu_dibuat' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'waktu_diubah' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('siswa');
    }

    public function down()
    {
        $this->forge->dropTable('siswa');
    }
}
