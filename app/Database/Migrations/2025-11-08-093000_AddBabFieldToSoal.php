<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBabFieldToSoal extends Migration
{
    public function up()
    {
        // Add bab field after id_materi
        $this->forge->addColumn('soal', [
            'bab' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'BAB 1',
                'after'      => 'id_materi',
                'null'       => false,
            ],
        ]);

        // Add index for better performance
        $this->forge->addKey('bab', false, 'idx_soal_bab');
    }

    public function down()
    {
        // Drop the index first
        $this->forge->dropKey('idx_soal_bab');

        // Drop the column
        $this->forge->dropColumn('soal', 'bab');
    }
}