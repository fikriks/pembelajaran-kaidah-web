<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestQuery extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'App';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'test:query';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Test database queries for soal';

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $db = \Config\Database::connect();

        // Check total soal
        $result = $db->query("SELECT COUNT(*) as total FROM soal")->getRow();
        CLI::write("Total soal: " . $result->total);

        // Check total materi kaidah
        $result = $db->query("SELECT COUNT(*) as total FROM materi_kaidah")->getRow();
        CLI::write("Total materi kaidah: " . $result->total);

        // Check if there are any soal with bab
        $result = $db->query("SELECT COUNT(*) as total FROM soal WHERE bab IS NOT NULL")->getRow();
        CLI::write("Soal with bab: " . $result->total);

        // Show first 5 soal if any exist
        $result = $db->query("SELECT s.*, '-' as judul_kaidah FROM soal s LIMIT 5")->getResult();
        if (!empty($result)) {
            CLI::write("\nFirst 5 soal:");
            foreach ($result as $soal) {
                CLI::write("- ID: {$soal->id_soal}, Materi: {$soal->judul_kaidah}, Pertanyaan: " . substr($soal->pertanyaan, 0, 50) . "...");
            }
        }

        // Check bab table data
        $result = $db->query("SELECT * FROM bab ORDER BY urutan ASC LIMIT 5")->getResult();
        if (!empty($result)) {
            CLI::write("\nFirst 5 bab:");
            foreach ($result as $bab) {
                CLI::write("- ID: {$bab->id_bab}, Nama: {$bab->nama_bab}, Urutan: {$bab->urutan}");
            }
        }

        // Check materi_kaidah with bab relationship
        $result = $db->query("SELECT mk.*, b.nama_bab FROM materi_kaidah mk LEFT JOIN bab b ON b.id_bab = mk.id_bab LIMIT 5")->getResult();
        if (!empty($result)) {
            CLI::write("\nFirst 5 materi with bab info:");
            foreach ($result as $materi) {
                CLI::write("- ID: {$materi->id_materi}, Judul: {$materi->judul_kaidah}, Bab: {$materi->nama_bab}");
            }
        }

        // Check soal with bab field
        $result = $db->query("SELECT id_soal, bab FROM soal LIMIT 5")->getResult();
        if (!empty($result)) {
            CLI::write("\nFirst 5 soal with bab field:");
            foreach ($result as $soal) {
                CLI::write("- ID: {$soal->id_soal}, Bab: {$soal->bab}");
            }
        }

        // Check first 5 soal with complete relationship
        $result = $db->query("SELECT s.id_soal, s.bab, s.pertanyaan, mk.judul_kaidah, b.nama_bab FROM soal s LEFT JOIN materi_kaidah mk ON mk.judul_kaidah = 'Fallback' LEFT JOIN bab b ON b.nama_bab = s.bab LIMIT 5")->getResult();
        if (!empty($result)) {
            CLI::write("\nFirst 5 soal with complete relationship:");
            foreach ($result as $soal) {
                CLI::write("- ID: {$soal->id_soal}, Bab: {$soal->bab}, Materi: {$soal->judul_kaidah}, Bab Name: {$soal->nama_bab}");
            }
        }
    }
}
