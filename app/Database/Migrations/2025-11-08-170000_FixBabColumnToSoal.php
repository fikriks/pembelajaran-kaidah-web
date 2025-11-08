<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixBabColumnToSoal extends Migration
{
    public function up()
    {
        // Add bab column to soal table if it doesn't exist
        $fields = [
            'bab' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'id_materi'
            ]
        ];

        $this->forge->addColumn('soal', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('soal', 'bab');
    }
}