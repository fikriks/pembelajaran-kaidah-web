<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PopulateBabFieldInSoal extends Migration
{
    public function up()
    {
        // Update soal table to populate bab field based on materi relationships
        $sql = "
            UPDATE soal s
            JOIN materi_kaidah mk ON s.id_materi = mk.id_materi
            JOIN bab b ON mk.id_bab = b.id_bab
            SET s.bab = b.nama_bab
        ";

        $this->db->query($sql);
    }

    public function down()
    {
        // Empty the bab field
        $this->db->query('UPDATE soal SET bab = NULL');
    }
}