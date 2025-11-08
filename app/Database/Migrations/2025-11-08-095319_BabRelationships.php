<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BabRelationships extends Migration
{
    public function up()
    {
        // Add id_bab foreign key to materi_kaidah table
        $this->forge->addColumn('materi_kaidah', [
            'id_bab' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true, // Temporary null for data migration
                'after' => 'id_materi'
            ]
        ]);

        // Add id_bab foreign key to soal table
        $this->forge->addColumn('soal', [
            'id_bab' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true, // Temporary null for data migration
                'after' => 'id_soal'
            ]
        ]);

        // Add foreign key constraints
        $this->forge->addForeignKey('id_bab', 'bab', 'id_bab', 'CASCADE', 'CASCADE', 'materi_kaidah_id_bab_foreign');
        $this->forge->addForeignKey('id_bab', 'bab', 'id_bab', 'CASCADE', 'CASCADE', 'soal_id_bab_foreign');

        // Update materi_kaidah data based on existing bab field
        $this->db->query("
            UPDATE materi_kaidah mk
            SET mk.id_bab = (
                CASE
                    WHEN mk.bab LIKE '%BAB 1%' OR mk.bab LIKE '%KALAM%' THEN 1
                    WHEN mk.bab LIKE '%BAB 2%' OR mk.bab LIKE '%I\\'RAB%' THEN 2
                    ELSE 1
                END
            )
            WHERE mk.id_bab IS NULL
        ");

        // Update soal data based on existing bab field
        $this->db->query("
            UPDATE soal s
            SET s.id_bab = (
                CASE
                    WHEN s.bab LIKE '%BAB 1%' OR s.bab LIKE '%KALAM%' THEN 1
                    WHEN s.bab LIKE '%BAB 2%' OR s.bab LIKE '%I\\'RAB%' THEN 2
                    ELSE 1
                END
            )
            WHERE s.id_bab IS NULL
        ");

        // Make id_bab NOT NULL after data migration
        $this->forge->modifyColumn('materi_kaidah', [
            'id_bab' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false,
                'after' => 'id_materi'
            ]
        ]);

        $this->forge->modifyColumn('soal', [
            'id_bab' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false,
                'after' => 'id_soal'
            ]
        ]);

        // Drop old bab text fields
        $this->forge->dropColumn('materi_kaidah', 'bab');
        $this->forge->dropColumn('soal', 'bab');
    }

    public function down()
    {
        // Add back the old bab text fields
        $this->forge->addColumn('materi_kaidah', [
            'bab' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
                'default' => 'BAB 1: KALAM'
            ]
        ]);

        $this->forge->addColumn('soal', [
            'bab' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
                'default' => 'BAB 1: KALAM'
            ]
        ]);

        // Update text fields with data from bab table
        $this->db->query("
            UPDATE materi_kaidah mk
            SET mk.bab = (
                SELECT CONCAT('BAB ', b.urutan, ': ', b.nama_bab)
                FROM bab b
                WHERE b.id_bab = mk.id_bab
            )
        ");

        $this->db->query("
            UPDATE soal s
            SET s.bab = (
                SELECT CONCAT('BAB ', b.urutan, ': ', b.nama_bab)
                FROM bab b
                WHERE b.id_bab = s.id_bab
            )
        ");

        // Drop foreign keys
        $this->forge->dropForeignKey('materi_kaidah', 'materi_kaidah_id_bab_foreign');
        $this->forge->dropForeignKey('soal', 'soal_id_bab_foreign');

        // Drop id_bab columns
        $this->forge->dropColumn('materi_kaidah', 'id_bab');
        $this->forge->dropColumn('soal', 'id_bab');
    }
}
