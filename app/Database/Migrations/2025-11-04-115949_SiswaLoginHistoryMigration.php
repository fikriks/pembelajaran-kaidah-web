<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SiswaLoginHistoryMigration extends Migration
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
            ],
            'login_time' => [
                'type' => 'DATETIME',
            ],
            'device_info' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
            ],
            'waktu_dibuat' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('nis', false, false, 'siswa_login_history_nis_index');
        $this->forge->createTable('siswa_login_history');
    }

    public function down()
    {
        $this->forge->dropTable('siswa_login_history');
    }
}
