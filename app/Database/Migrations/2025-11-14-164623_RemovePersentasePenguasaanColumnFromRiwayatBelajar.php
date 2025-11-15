<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemovePersentasePenguasaanColumnFromRiwayatBelajar extends Migration
{
    public function up()
    {
        // Remove persentase_penguasaan column from riwayat_belajar table
        $this->forge->dropColumn('riwayat_belajar', 'persentase_penguasaan');
    }

    public function down()
    {
        // Add back persentase_penguasaan column for rollback
        $this->forge->addColumn('riwayat_belajar', [
            'persentase_penguasaan' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
                'null'       => false,
            ],
        ]);
    }
}
