<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropBabFieldUseIdBab extends Migration
{
    public function up()
    {
        // Drop the bab field from soal table
        $this->forge->dropColumn('soal', 'bab');
    }

    public function down()
    {
        // Add back bab field for rollback
        $fields = [
            'bab' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'id_bab'
            ]
        ];

        $this->forge->addColumn('soal', $fields);
    }
}