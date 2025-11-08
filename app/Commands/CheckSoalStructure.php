<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckSoalStructure extends BaseCommand
{
    protected $group = 'App';
    protected $name = 'check:soal';
    protected $description = 'Check soal table structure';

    public function run(array $params)
    {
        $db = \Config\Database::connect();

        CLI::write("=== Current Soal Table Structure ===");

        // Get table structure
        $result = $db->query("DESCRIBE soal")->getResult();
        if (!empty($result)) {
            CLI::write("\nSoal table columns:");
            foreach ($result as $row) {
                CLI::write("- {$row->Field}: {$row->Type} (Null: {$row->Null}, Default: {$row->Default})");
            }
        }

        // Check first 5 soal records
        $result = $db->query("SELECT * FROM soal LIMIT 5")->getResult();
        if (!empty($result)) {
            CLI::write("\nFirst 5 soal records:");
            foreach ($result as $row) {
                CLI::write("- ID: {$row->id_soal}, id_bab: {$row->id_bab}, Pertanyaan: " . substr($row->pertanyaan, 0, 50) . "...");
            }
        }

        // Check id_bab values
        $result = $db->query("SELECT id_bab, COUNT(*) as total FROM soal GROUP BY id_bab ORDER BY id_bab")->getResult();
        if (!empty($result)) {
            CLI::write("\nSoal distribution by id_bab:");
            foreach ($result as $row) {
                CLI::write("- id_bab {$row->id_bab}: {$row->total} soal");
            }
        }
    }
}