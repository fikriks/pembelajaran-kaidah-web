<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PopulateIdBabInSoal extends Migration
{
    public function up()
    {
        // First, let's check what bab IDs exist and update soal accordingly
        $db = \Config\Database::connect();

        // Update soal based on historical mapping from materi to bab
        // BAB 1: KALAM (id_bab = 1) - contains materi 1-10
        // BAB 2: I'RAB (id_bab = 2) - contains materi 11-20

        // Update soal for BAB 1: KALAM
        // These soal were originally created with materi_id 1-10
        // We need to reconstruct this from the materi_kaidah relationships

        // First, create a temporary mapping table or use a subquery
        $sql = "
            UPDATE soal s
            JOIN materi_kaidah mk ON mk.urutan <= 10
            JOIN bab b ON b.id_bab = mk.id_bab AND b.urutan = 1
            SET s.id_bab = 1
            WHERE s.id_soal <= 120  -- Assuming first 120 soal belong to BAB 1
        ";

        $db->query($sql);

        // Update soal for BAB 2: I'RAB
        $sql = "
            UPDATE soal s
            JOIN bab b ON b.urutan = 2
            SET s.id_bab = 2
            WHERE s.id_soal > 120  -- Assuming soal > 120 belong to BAB 2
        ";

        $db->query($sql);
    }

    public function down()
    {
        // Reset all id_bab to 0 for rollback
        $this->db->query('UPDATE soal SET id_bab = 0');
    }
}