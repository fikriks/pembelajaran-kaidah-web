<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        // Seed pengguna (admin & guru)
        $this->call('PenggunaSeeder');

        // Seed bab (chapters)
        $this->call('BabSeeder');

        // Seed materi kaidah
        $this->call('MateriKaidahSeeder');

        // Seed soal dan pilihan jawaban
        $this->call('SoalSeeder');
        $this->call('PilihanJawabanSeeder');

        // Seed soal khusus per bab
        $this->call('Bab1KalamSoalSeeder');
        $this->call('Bab2IrabSoalSeeder');

        // Seed siswa (jika ada)
        $this->call('SiswaSeeder');

        echo "✅ All seeders completed successfully!\n";
        echo "📊 Database seeded with:\n";
        echo "   - Pengguna (Admin & Guru)\n";
        echo "   - Bab (Chapters)\n";
        echo "   - Materi Kaidah\n";
        echo "   - Soal & Pilihan Jawaban\n";
        echo "   - Soal Khusus Bab 1 (Kalam) - 20 soal\n";
        echo "   - Soal Khusus Bab 2 (I'rab) - 20 soal\n";
        echo "   - Siswa\n";
    }
}