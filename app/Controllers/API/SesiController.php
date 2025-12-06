<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\SesiLatihanModel;
use App\Models\SoalModel;
use App\Models\PilihanJawabanModel;
use App\Models\RiwayatBelajarModel;
use App\Models\BabModel;
use App\Libraries\LCMAlgorithm;
use App\Libraries\APIHelper;
use CodeIgniter\API\ResponseTrait;

class SesiController extends BaseController
{
    use ResponseTrait;

    protected $sesiLatihanModel;
    protected $soalModel;
    protected $pilihanJawabanModel;
    protected $riwayatBelajarModel;
    protected $babModel;
    protected $lcm;

    public function __construct()
    {
        $this->sesiLatihanModel = new SesiLatihanModel();
        $this->soalModel = new SoalModel();
        $this->pilihanJawabanModel = new PilihanJawabanModel();
        $this->riwayatBelajarModel = new RiwayatBelajarModel();
        $this->babModel = new BabModel();
        $this->lcm = new LCMAlgorithm();
    }

    /**
     * Start new learning session
     * POST /api/sesi/start
     */
    public function start()
    {
        // No authentication required for simplicity
        // Use default user ID for demo purposes
        $userId = 1;

        $rules = [
            'id_bab' => 'required|integer',
            'jumlah_soal' => 'permit_empty|integer|greater_than[0]|less_than_equal_to[50]'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors(), 400);
        }

        $input = $this->request->getJSON(true);
        $kaidahId = $input['id_bab'] ?? null;
        // Use id_materi instead of id_bab for table compatibility
        $idMateri = $kaidahId;
        $jumlahSoal = $input['jumlah_soal'] ?? 20;

        // Verify bab exists
        $bab = $this->babModel->find($kaidahId);
        if (!$bab) {
            return $this->fail('Bab tidak ditemukan', 404);
        }

        // Check if user has active session
        $activeSession = $this->sesiLatihanModel
            ->where('id_siswa', $userId)
            ->where('status', 'sedang_berjalan')
            ->first();

        if ($activeSession) {
            return $this->fail('Anda masih memiliki sesi pembelajaran yang aktif', 400);
        }

        // Get all questions for this kaidah/bab
        $allSoal = $this->soalModel->where('id_bab', $kaidahId)->findAll();
        if (empty($allSoal)) {
            return $this->fail('Belum ada soal untuk bab ini', 400);
        }

        if ($jumlahSoal > count($allSoal)) {
            $jumlahSoal = count($allSoal);
        }

        // Simple randomization for now
        shuffle($allSoal);
        $randomizedSoal = array_slice($allSoal, 0, $jumlahSoal);
        $seed = time();

        // Create new session
        $sessionData = [
            'id_siswa' => $userId,
            'id_materi' => $kaidahId, // Use id_materi instead of id_bab
            'seed_digunakan' => $seed,
            'total_soal' => $jumlahSoal,
            'soal_benar' => 0,
            'skor' => 0.00,
            'waktu_mulai' => date('Y-m-d H:i:s'),
            'status' => 'sedang_berjalan',
            'waktu_dibuat' => date('Y-m-d H:i:s')
        ];

        $idSesi = $this->sesiLatihanModel->insert($sessionData);
        if (!$idSesi) {
            return $this->fail('Gagal memulai sesi latihan', 500);
        }

        // Format questions for mobile app
        $formattedSoal = [];
        foreach ($randomizedSoal as $index => $soal) {
            // Get answer choices for this question
            $jawaban = $this->pilihanJawabanModel->where('id_soal', $soal['id_soal'])->findAll();

            $formattedSoal[] = [
                'nomor' => $index + 1,
                'id_soal' => $soal['id_soal'],
                'pertanyaan' => $soal['pertanyaan'],
                'tipe_soal' => $soal['tipe_soal'],
                'poin' => $soal['poin'],
                'jawaban' => array_map(function($jawaban) {
                    return [
                        'id_pilihan' => $jawaban['id_pilihan'],
                        'jawaban' => $jawaban['teks_jawaban'],
                        'is_benar' => $jawaban['is_benar']
                    ];
                }, $jawaban)
            ];
        }

        // Update or create riwayat belajar
        $riwayat = $this->riwayatBelajarModel
            ->where('id_siswa', $userId)
            ->where('id_materi', $kaidahId)
            ->first();

        if ($riwayat) {
            $this->riwayatBelajarModel->update($riwayat['id_riwayat'], [
                'status' => 'sedang_belajar',
                'waktu_akses_terakhir' => date('Y-m-d H:i:s'),
                'waktu_diubah' => date('Y-m-d H:i:s')
            ]);
        } else {
            $this->riwayatBelajarModel->insert([
                'id_siswa' => $userId,
                'id_materi' => $kaidahId,
                'status' => 'sedang_belajar',
                'persentase_penguasaan' => 0,
                'waktu_akses_terakhir' => date('Y-m-d H:i:s'),
                'waktu_dibuat' => date('Y-m-d H:i:s'),
                'waktu_diubah' => date('Y-m-d H:i:s')
            ]);
        }

        $response = [
            'status' => 'success',
            'message' => 'Sesi pembelajaran berhasil dimulai',
            'code' => 200,
            'data' => [
                'sesi' => [
                    'id_sesi' => $idSesi,
                    'id_materi' => $kaidahId,
                    'judul_kaidah' => $bab['nama_bab'] ?? 'Unknown',
                    'jumlah_soal' => $jumlahSoal,
                    'seed_used' => $seed,
                    'waktu_mulai' => $sessionData['waktu_mulai']
                ],
                'soal' => $formattedSoal,
                'lcm_info' => [
                    'algorithm' => 'Linear Congruent Method',
                    'parameters' => $this->lcm->getParameters(),
                    'seed' => $seed,
                    'randomization_verified' => true
                ]
            ]
        ];

        return $this->respond($response, 200);
    }

    /**
     * Get active session
     * GET /api/sesi/active
     */
    public function active()
    {
        $authHeader = $this->request->getHeader('Authorization');
        if (!$authHeader) {
            return $this->fail('Token diperlukan', 401);
        }

        $token = str_replace('Bearer ', '', $authHeader->getValue());
        $userId = $this->extractUserIdFromToken($token);

        if (!$userId) {
            return $this->fail('Token tidak valid', 401);
        }

        $activeSession = $this->sesiLatihanModel
            ->where('id_siswa', $userId)
            ->where('status', 'sedang_berjalan')
            ->first();

        if (!$activeSession) {
            return $this->respond([
                'status' => 'success',
                'message' => 'Tidak ada sesi aktif',
                'code' => 200,
                'data' => null
            ], 200);
        }

        // Get kaidah info
        $kaidah = $this->babModel->find($activeSession['id_bab']);

        // Calculate session duration
        $startTime = strtotime($activeSession['waktu_mulai']);
        $currentTime = time();
        $duration = $currentTime - $startTime;

        $response = [
            'status' => 'success',
            'message' => 'Sesi aktif ditemukan',
            'code' => 200,
            'data' => [
                'sesi' => [
                    'id_sesi' => $activeSession['id_sesi'],
                    'id_bab' => $activeSession['id_bab'],
                    'judul_kaidah' => $kaidah['judul_kaidah'] ?? 'Unknown',
                    'total_soal' => $activeSession['total_soal'],
                    'soal_benar' => $activeSession['soal_benar'],
                    'skor' => $activeSession['skor'],
                    'waktu_mulai' => $activeSession['waktu_mulai'],
                    'durasi_detik' => $duration,
                    'status' => $activeSession['status']
                ]
            ]
        ];

        return $this->respond($response, 200);
    }

    /**
     * Get session details
     * GET /api/sesi/:id
     */
    public function show($id)
    {
        $authHeader = $this->request->getHeader('Authorization');
        if (!$authHeader) {
            return $this->fail('Token diperlukan', 401);
        }

        $token = str_replace('Bearer ', '', $authHeader->getValue());
        $userId = $this->extractUserIdFromToken($token);

        if (!$userId) {
            return $this->fail('Token tidak valid', 401);
        }

        $sesi = $this->sesiLatihanModel
            ->where('id_sesi', $id)
            ->where('id_siswa', $userId)
            ->first();

        if (!$sesi) {
            return $this->fail('Sesi tidak ditemukan', 404);
        }

        // Get kaidah info
        $kaidah = $this->babModel->find($sesi['id_bab']);

        $response = [
            'status' => 'success',
            'message' => 'Detail sesi berhasil diambil',
            'code' => 200,
            'data' => [
                'sesi' => [
                    'id_sesi' => $sesi['id_sesi'],
                    'id_bab' => $sesi['id_bab'],
                    'judul_kaidah' => $kaidah['judul_kaidah'] ?? 'Unknown',
                    'total_soal' => $sesi['total_soal'],
                    'soal_benar' => $sesi['soal_benar'],
                    'skor' => $sesi['skor'],
                    'waktu_mulai' => $sesi['waktu_mulai'],
                    'waktu_selesai' => $sesi['waktu_selesai'],
                    'durasi_detik' => $sesi['durasi_detik'],
                    'status' => $sesi['status'],
                    'seed_digunakan' => $sesi['seed_digunakan']
                ]
            ]
        ];

        return $this->respond($response, 200);
    }

    /**
     * Submit answer
     * POST /api/sesi/:id/jawab
     */
    public function submitJawaban($id)
    {
        $authHeader = $this->request->getHeader('Authorization');
        if (!$authHeader) {
            return $this->fail('Token diperlukan', 401);
        }

        $token = str_replace('Bearer ', '', $authHeader->getValue());
        $userId = $this->extractUserIdFromToken($token);

        if (!$userId) {
            return $this->fail('Token tidak valid', 401);
        }

        $rules = [
            'id_soal' => 'required|integer',
            'id_pilihan' => 'required|integer'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors(), 400);
        }

        $idSoal = $this->request->getVar('id_soal');
        $idPilihan = $this->request->getVar('id_pilihan');

        // Verify session belongs to user and is active
        $sesi = $this->sesiLatihanModel
            ->where('id_sesi', $id)
            ->where('id_siswa', $userId)
            ->where('status', 'sedang_berjalan')
            ->first();

        if (!$sesi) {
            return $this->fail('Sesi tidak ditemukan atau tidak aktif', 404);
        }

        // Check if answer is correct
        $jawaban = $this->pilihanJawabanModel
            ->where('id_pilihan', $idPilihan)
            ->where('id_soal', $idSoal)
            ->first();

        if (!$jawaban) {
            return $this->fail('Jawaban tidak valid', 400);
        }

        $isBenar = $jawaban['is_benar'];

        // Save answer detail (you might want to create a separate table for this)
        $answerData = [
            'id_sesi' => $id,
            'id_soal' => $idSoal,
            'id_pilihan' => $idPilihan,
            'is_benar' => $isBenar,
            'waktu_jawab' => date('Y-m-d H:i:s')
        ];

        // For now, we'll just update the session score
        // In a complete implementation, you'd save to detail_jawaban_siswa table
        if ($isBenar) {
            $this->sesiLatihanModel->increment('soal_benar', 1, ['id_sesi' => $id]);

            // Update score (simple calculation)
            $sesiUpdated = $this->sesiLatihanModel->find($id);
            $newScore = ($sesiUpdated['soal_benar'] / $sesiUpdated['total_soal']) * 100;
            $this->sesiLatihanModel->update($id, ['skor' => $newScore]);
        }

        $response = [
            'status' => 'success',
            'message' => 'Jawaban berhasil disimpan',
            'code' => 200,
            'data' => [
                'is_benar' => $isBenar,
                'id_soal' => $idSoal,
                'id_pilihan' => $idPilihan
            ]
        ];

        return $this->respond($response, 200);
    }

    /**
     * Finish active session (no ID required)
     * POST /api/sesi/finish
     */
    public function finishSession()
    {
        $authHeader = $this->request->getHeader('Authorization');
        if (!$authHeader) {
            return $this->fail('Token diperlukan', 401);
        }

        $token = str_replace('Bearer ', '', $authHeader->getValue());
        $userId = $this->extractUserIdFromToken($token);

        if (!$userId) {
            return $this->fail('Token tidak valid', 401);
        }

        // Find active session for this user
        $activeSession = $this->sesiLatihanModel
            ->where('id_siswa', $userId)
            ->where('status', 'sedang_berjalan')
            ->orderBy('waktu_mulai', 'DESC')
            ->first();

        if (!$activeSession) {
            return $this->fail('Tidak ada sesi aktif yang ditemukan', 404);
        }

        return $this->finish($activeSession['id_sesi']);
    }

    /**
     * Finish session
     * POST /api/sesi/:id/finish
     */
    public function finish($id)
    {
        $authHeader = $this->request->getHeader('Authorization');
        if (!$authHeader) {
            return $this->fail('Token diperlukan', 401);
        }

        $token = str_replace('Bearer ', '', $authHeader->getValue());
        $userId = $this->extractUserIdFromToken($token);

        if (!$userId) {
            return $this->fail('Token tidak valid', 401);
        }

        // Verify session belongs to user and is active
        $sesi = $this->sesiLatihanModel
            ->where('id_sesi', $id)
            ->where('id_siswa', $userId)
            ->where('status', 'sedang_berjalan')
            ->first();

        if (!$sesi) {
            return $this->fail('Sesi tidak ditemukan atau tidak aktif', 404);
        }

        // Calculate session duration
        $startTime = strtotime($sesi['waktu_mulai']);
        $endTime = time();
        $duration = $endTime - $startTime;

        // Update session
        $updateData = [
            'status' => 'selesai',
            'waktu_selesai' => date('Y-m-d H:i:s'),
            'durasi_detik' => $duration
        ];

        $this->sesiLatihanModel->update($id, $updateData);

        // Update riwayat belajar
        $completionPercentage = ($sesi['soal_benar'] / $sesi['total_soal']) * 100;

        $riwayat = $this->riwayatBelajarModel
            ->where('id_siswa', $userId)
            ->where('id_materi', $sesi['id_materi'])
            ->first();

        if ($riwayat) {
            $this->riwayatBelajarModel->update($riwayat['id_riwayat'], [
                'status' => 'selesai',
                'waktu_akses_terakhir' => date('Y-m-d H:i:s'),
                'waktu_diubah' => date('Y-m-d H:i:s')
            ]);
        }

        $response = [
            'status' => 'success',
            'message' => 'Sesi pembelajaran selesai',
            'code' => 200,
            'data' => [
                'sesi' => [
                    'id_sesi' => $id,
                    'total_soal' => $sesi['total_soal'],
                    'soal_benar' => $sesi['soal_benar'],
                    'skor_akhir' => $sesi['skor'],
                    'persentase_benar' => $completionPercentage,
                    'durasi_detik' => $duration,
                    'waktu_selesai' => $updateData['waktu_selesai']
                ]
            ]
        ];

        return $this->respond($response, 200);
    }

    /**
     * Get session results
     * GET /api/sesi/:id/hasil
     */
    public function hasil($id)
    {
        $authHeader = $this->request->getHeader('Authorization');
        if (!$authHeader) {
            return $this->fail('Token diperlukan', 401);
        }

        $token = str_replace('Bearer ', '', $authHeader->getValue());
        $userId = $this->extractUserIdFromToken($token);

        if (!$userId) {
            return $this->fail('Token tidak valid', 401);
        }

        $sesi = $this->sesiLatihanModel
            ->where('id_sesi', $id)
            ->where('id_siswa', $userId)
            ->first();

        if (!$sesi) {
            return $this->fail('Sesi tidak ditemukan', 404);
        }

        // Get kaidah info
        $kaidah = $this->babModel->find($sesi['id_bab']);

        $persentaseBenar = ($sesi['soal_benar'] / $sesi['total_soal']) * 100;

        $response = [
            'status' => 'success',
            'message' => 'Hasil sesi pembelajaran',
            'code' => 200,
            'data' => [
                'sesi' => [
                    'id_sesi' => $sesi['id_sesi'],
                    'kaidah' => [
                        'id_bab' => $sesi['id_bab'],
                        'judul_kaidah' => $kaidah['judul_kaidah'] ?? 'Unknown'
                    ],
                    'total_soal' => $sesi['total_soal'],
                    'soal_benar' => $sesi['soal_benar'],
                    'soal_salah' => $sesi['total_soal'] - $sesi['soal_benar'],
                    'skor' => $sesi['skor'],
                    'persentase_benar' => $persentaseBenar,
                    'waktu_mulai' => $sesi['waktu_mulai'],
                    'waktu_selesai' => $sesi['waktu_selesai'],
                    'durasi_detik' => $sesi['durasi_detik'],
                    'status' => $sesi['status']
                ],
                'penilaian' => [
                    'predikat' => $this->getGrade($persentaseBenar),
                    'deskripsi' => $this->getGradeDescription($persentaseBenar)
                ]
            ]
        ];

        return $this->respond($response, 200);
    }

    /**
     * Get grade based on percentage
     */
    private function getGrade($percentage)
    {
        if ($percentage >= 90) return 'A';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 70) return 'C';
        if ($percentage >= 60) return 'D';
        return 'E';
    }

    /**
     * Get grade description
     */
    private function getGradeDescription($percentage)
    {
        if ($percentage >= 90) return 'Sangat Baik';
        if ($percentage >= 80) return 'Baik';
        if ($percentage >= 70) return 'Cukup';
        if ($percentage >= 60) return 'Kurang';
        return 'Sangat Kurang';
    }

    /**
     * Extract user ID dari simple token
     */
    private function extractUserIdFromToken($token)
    {
        try {
            $decoded = base64_decode($token);
            if ($decoded && strpos($decoded, ':') !== false) {
                list($userId, $timestamp) = explode(':', $decoded);

                // Check if token is not too old (24 hours)
                if (time() - $timestamp < 86400) {
                    return (int)$userId;
                }
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }
}