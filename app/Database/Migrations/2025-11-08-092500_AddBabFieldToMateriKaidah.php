<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBabFieldToMateriKaidah extends Migration
{
    public function up()
    {
        // Add bab field after urutan
        $this->forge->addColumn('materi_kaidah', [
            'bab' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'BAB 1',
                'after'      => 'urutan',
                'null'       => false,
            ],
            'deskripsi_bab' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'bab',
            ],
        ]);

        // Add index for better performance
        $this->forge->addKey('bab', false, 'idx_materi_kaidah_bab');
    }

    public function down()
    {
        // Drop the index first
        $this->forge->dropKey('idx_materi_kaidah_bab');

        // Drop the columns
        $this->forge->dropColumn('materi_kaidah', 'deskripsi_bab');
        $this->forge->dropColumn('materi_kaidah', 'bab');
    }
}