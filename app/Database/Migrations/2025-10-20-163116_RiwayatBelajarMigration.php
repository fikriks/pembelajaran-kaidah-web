<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RiwayatBelajarMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_riwayat' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_siswa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_materi' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'status' => [
                'type'       => "ENUM('belum_dimulai', 'sedang_belajar', 'selesai')",
                'default'    => 'belum_dimulai',
                'null'       => false,
            ],
            'persentase_penguasaan' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
                'null'       => false,
            ],
            'waktu_akses_terakhir' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'waktu_dibuat' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'waktu_diubah' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id_riwayat', true);
        $this->forge->addForeignKey('id_materi', 'materi_kaidah', 'id_materi', 'CASCADE', 'CASCADE');
        $this->forge->createTable('riwayat_belajar');
    }

    public function down()
    {
        $this->forge->dropTable('riwayat_belajar');
    }
}
