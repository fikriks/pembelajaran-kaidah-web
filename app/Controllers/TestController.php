<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TestController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $siswa = $db->table('siswa')->get()->getResult();

        echo "<h1>Data Siswa</h1>";
        echo "<pre>";
        print_r($siswa);
        echo "</pre>";

        // Test login dengan data yang ada
        if (count($siswa) > 0) {
            $firstSiswa = $siswa[0];
            echo "<h2>Test Login dengan:</h2>";
            echo "NIS: " . $firstSiswa->nis . "<br>";
            echo "Password: (hash) " . $firstSiswa->kata_sandi . "<br>";

            // Test password verification
            if (password_verify('123456', $firstSiswa->kata_sandi)) {
                echo "<br>Password '123456' cocok!";
            } else {
                echo "<br>Password '123456' tidak cocok. Coba password lain...";

                // Test beberapa password default
                $passwords = ['password', 'siswa123', 'student', 'test123', '123'];
                foreach ($passwords as $pwd) {
                    if (password_verify($pwd, $firstSiswa->kata_sandi)) {
                        echo "<br>Password '$pwd' cocok!";
                        break;
                    }
                }
            }
        }
    }
}
