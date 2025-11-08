<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckBabDistribution extends BaseCommand
{
    protected $group = 'App';
    protected $name = 'check:bab';
    protected $description = 'Check bab distribution in soal table';

    public function run(array $params)
    {
        $db = \Config\Database::connect();

        CLI::write("=== Bab Distribution in Soal Table ===");

        // Check soal distribution by bab
        $result = $db->query("SELECT bab, COUNT(*) as total FROM soal GROUP BY bab ORDER BY bab")->getResult();
        if (!empty($result)) {
            CLI::write("\nSoal distribution by bab:");
            foreach ($result as $row) {
                CLI::write("- {$row->bab}: {$row->total} soal");
            }
        }

        // Check materi distribution by bab
        $result = $db->query("SELECT b.nama_bab, COUNT(mk.id_materi) as total_materi FROM bab b LEFT JOIN materi_kaidah mk ON b.id_bab = mk.id_bab GROUP BY b.id_bab ORDER BY b.urutan")->getResult();
        if (!empty($result)) {
            CLI::write("\nMateri distribution by bab:");
            foreach ($result as $row) {
                CLI::write("- {$row->nama_bab}: {$row->total_materi} materi");
            }
        }

        // Check which materi belong to each bab
        $result = $db->query("SELECT b.nama_bab, mk.judul_kaidah FROM bab b JOIN materi_kaidah mk ON b.id_bab = mk.id_bab ORDER BY b.urutan, mk.urutan")->getResult();
        if (!empty($result)) {
            CLI::write("\nMateri list by bab:");
            $current_bab = '';
            foreach ($result as $row) {
                if ($current_bab != $row->nama_bab) {
                    $current_bab = $row->nama_bab;
                    CLI::write("\n{$current_bab}:");
                }
                CLI::write("  - {$row->judul_kaidah}");
            }
        }
    }
}