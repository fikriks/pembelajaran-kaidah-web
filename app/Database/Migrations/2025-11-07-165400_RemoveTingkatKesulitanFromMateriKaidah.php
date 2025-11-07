<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveTingkatKesulitanFromMateriKaidah extends Migration
{
    public function up()
    {
        // Menghapus kolom tingkat_kesulitan dari tabel materi_kaidah
        $this->forge->dropColumn('materi_kaidah', 'tingkat_kesulitan');
    }

    public function down()
    {
        // Menambahkan kembali kolom tingkat_kesulitan jika rollback
        $this->forge->addColumn('materi_kaidah', [
            'tingkat_kesulitan' => [
                'type'       => "ENUM('mudah', 'sedang', 'sulit')",
                'default'    => 'sedang',
                'null'       => false,
                'after'      => 'contoh'
            ]
        ]);
    }
}