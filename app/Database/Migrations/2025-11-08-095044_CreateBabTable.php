<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBabTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_bab' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_bab' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'urutan' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => false,
                'default'    => 1,
            ],
            'is_active' => [
                'type'       => 'BOOLEAN',
                'null'       => false,
                'default'    => true,
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
        $this->forge->addKey('id_bab', true);
        $this->forge->addUniqueKey('nama_bab');
        $this->forge->createTable('bab');
    }

    public function down()
    {
        $this->forge->dropTable('bab');
    }
}
