<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Otomatis menjalankan MainSeeder yang sudah mengatur semua seeder
        $this->call('MainSeeder');

        echo "🎉 Database seeding completed!\n";
        echo "📊 All data has been seeded successfully:\n";
        echo "   - Pengguna (Admin & Guru)\n";
        echo "   - Bab (Chapters)\n";
        echo "   - Materi Kaidah\n";
        echo "   - Soal & Pilihan Jawaban\n";
        echo "   - Soal Khusus Bab 1 (Kalam) - 20 soal\n";
        echo "   - Soal Khusus Bab 2 (I'rab) - 20 soal\n";
        echo "   - Siswa\n";
        echo "\n✨ Database is ready for use!\n";
    }
}