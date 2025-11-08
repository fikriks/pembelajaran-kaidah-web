<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveIdMateriFromSoal extends Migration
{
    public function up()
    {
        // Drop foreign key constraint first
        $this->forge->dropForeignKey('soal', 'soal_id_materi_foreign');

        // Remove id_materi column from soal table
        $this->forge->dropColumn('soal', 'id_materi');
    }

    public function down()
    {
        // Add back id_materi column for rollback
        $fields = [
            'id_materi' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'after'      => 'id_soal'
            ]
        ];

        $this->forge->addColumn('soal', $fields);
        $this->forge->addForeignKey('id_materi', 'materi_kaidah', 'id_materi', 'CASCADE', 'CASCADE', 'fk_soal_id_materi');
    }
}