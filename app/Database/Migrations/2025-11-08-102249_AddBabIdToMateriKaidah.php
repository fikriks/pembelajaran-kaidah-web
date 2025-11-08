<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBabIdToMateriKaidah extends Migration
{
    public function up()
    {
        // Add id_bab field if it doesn't exist
        if (!$this->db->fieldExists('id_bab', 'materi_kaidah')) {
            $this->forge->addColumn('materi_kaidah', [
                'id_bab' => [
                    'type' => 'INT',
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'urutan'
                ]
            ]);

            // Add foreign key constraint
            $this->forge->addKey('materi_kaidah', 'id_bab', false, true, 'bab', 'id_bab', 'CASCADE', 'CASCADE');
        }

        // Update existing materi_kaidah to assign to BAB 1 (KALAM) as default
        // Since we know from earlier that the existing materi are about KALAM and related topics
        $builder = $this->db->table('materi_kaidah');

        // Get first bab (BAB 1: KALAM)
        $firstBab = $this->db->table('bab')
                         ->where('is_active', 1)
                         ->orderBy('urutan', 'ASC')
                         ->get()
                         ->getRowArray();

        if ($firstBab) {
            // Update all existing materi to belong to first bab
            $builder->where('id_bab IS NULL')
                    ->update(['id_bab' => $firstBab['id_bab']]);
        }

        // Skip making NOT NULL for now - keep it nullable to avoid migration issues
        // id_bab can remain nullable for now
    }

    public function down()
    {
        // Drop foreign key first
        $this->forge->dropForeignKey('materi_kaidah', 'materi_kaidah_id_bab_foreign');

        // Drop the column
        $this->forge->dropColumn('materi_kaidah', 'id_bab');
    }
}
