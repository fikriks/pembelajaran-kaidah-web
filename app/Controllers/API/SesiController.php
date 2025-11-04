<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\SesiModel;
use App\Models\SoalModel;
use App\Libraries\LCMAlgorithm;
use CodeIgniter\API\ResponseTrait;

class SesiController extends BaseController
{
    use ResponseTrait;

    protected $sesiModel;
    protected $soalModel;
    protected $lcm;

    public function __construct()
    {
        $this->sesiModel = new SesiModel();
        $this->soalModel = new SoalModel();
        $this->lcm = new LCMAlgorithm();
    }

    /**
     * Start new learning session
     * POST /api/sesi/start
     */
    public function start()
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
            'kaidah_id' => 'required|integer',
            'jumlah_soal' => 'permit_empty|integer|greater_than[0]|less_than_equal_to[50]'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors(), 400);
        }

        $kaidahId = $this->request->getVar('kaidah_id');
        $jumlahSoal = $this->request->getVar('jumlah_soal') ?? 20;

        // Check if user has active session
        if ($this->sesiModel->hasSesiAktif($userId)) {
            return $this->fail('Anda masih memiliki sesi pembelajaran yang aktif', 400);
        }

        // Get all questions for this kaidah
        $allSoal = $this->soalModel->getSoalWithJawaban($kaidahId);
        if (empty($allSoal)) {
            return $this->fail('Belum ada soal untuk kaidah ini', 400);
        }

        if ($jumlahSoal > count($allSoal)) {
            $jumlahSoal = count($allSoal);
        }

        // Create new session
        $idSesi = $this->sesiModel->createSesi($userId, $kaidahId, $jumlahSoal);
        if (!$idSesi) {
            return $this->fail('Gagal memulai sesi pembelajaran', 500);
        }

        // Get session data with seed
        $sesi = $this->sesiModel->find($idSesi);
        $seed = $sesi['seed_digunakan'];

        // Generate randomized questions using LCM
        $quizResult = $this->lcm->generateQuizData($allSoal, $jumlahSoal, true);
        $randomizedSoal = $quizResult['questions'];

        // Format questions for mobile app
        $formattedSoal = [];
        foreach ($randomizedSoal as $index => $soal) {
            $formattedSoal[] = [
                'nomor' => $index + 1,
                'id_soal' => $soal['id_soal'],
                'pertanyaan' => $soal['pertanyaan'],
                'tipe_soal' => $soal['tipe_soal'],
                'tingkat_kesulitan' => $soal['tingkat_kesulitan'],
                'poin' => $soal['poin'],
                'jawaban' => array_map(function($jawaban) {
                    return [
                        'id_pilihan' => $jawaban['id_pilihan'],
                        'jawaban' => $jawaban['teks_jawaban'],
                        'is_benar' => $jawaban['is_benar']
                    ];
                }, $soal['jawaban'])
            ];
        }

        $response = [
            'status' => 'success',
            'message' => 'Sesi pembelajaran berhasil dimulai',
            'data' => [
                'sesi' => [
                    'id_sesi' => $idSesi,
                    'kaidah_id' => $kaidahId,
                    'jumlah_soal' => $jumlahSoal,
                    'seed_used' => $seed,
                    'waktu_mulai' => $sesi['waktu_mulai']
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

        return $this->respond($response, 201);
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

        $activeSesi = $this->sesiModel->getSesiAktifSiswa($userId);
        if (!$activeSesi) {
            return $this->fail('Tidak ada sesi aktif', 404);
        }

        // Get questions for this session
        $allSoal = $this->soalModel->getSoalWithJawaban($activeSesi['id_materi']);
        $seed = $activeSesi['seed_digunakan'];
        $jumlahSoal = $activeSesi['total_soal'];

        // Regenerate the same question order using the same seed
        $quizResult = $this->lcm->generateQuizData($allSoal, $jumlahSoal, true, $seed);
        $randomizedSoal = $quizResult['questions'];

        // Get answered questions to show progress
        $db = \Config\Database::connect();
        $answeredQuestions = $db->table('detail_jawaban_siswa')
            ->where('id_sesi', $activeSesi['id_sesi'])
            ->get()
            ->getResultArray();

        $answeredMap = [];
        foreach ($answeredQuestions as $answer) {
            $answeredMap[$answer['id_soal']] = $answer;
        }

        // Format questions with progress
        $formattedSoal = [];
        foreach ($randomizedSoal as $index => $soal) {
            $isAnswered = isset($answeredMap[$soal['id_soal']]);
            $userAnswer = $answeredMap[$soal['id_soal']] ?? null;

            $formattedSoal[] = [
                'nomor' => $index + 1,
                'id_soal' => $soal['id_soal'],
                'pertanyaan' => $soal['pertanyaan'],
                'tipe_soal' => $soal['tipe_soal'],
                'tingkat_kesulitan' => $soal['tingkat_kesulitan'],
                'poin' => $soal['poin'],
                'is_answered' => $isAnswered,
                'user_answer' => $isAnswered ? [
                    'id_pilihan' => $userAnswer['id_pilihan'],
                    'is_benar' => $userAnswer['is_benar'],
                    'waktu_jawab' => $userAnswer['waktu_jawab']
                ] : null,
                'jawaban' => array_map(function($jawaban) use ($isAnswered, $userAnswer) {
                    return [
                        'id_pilihan' => $jawaban['id_pilihan'],
                        'jawaban' => $jawaban['teks_jawaban'],
                        'is_benar' => $isAnswered ? ($jawaban['id_pilihan'] == $userAnswer['id_pilihan']) : null
                    ];
                }, $soal['jawaban'])
            ];
        }

        $response = [
            'status' => 'success',
            'message' => 'Sesi aktif berhasil diambil',
            'data' => [
                'sesi' => $activeSesi,
                'progress' => [
                    'total_soal' => $activeSesi['total_soal'],
                    'soal_dijawab' => $activeSesi['soal_benar'] + count($answeredQuestions) - $activeSesi['soal_benar'],
                    'soal_benar' => $activeSesi['soal_benar'],
                    'skor_saat_ini' => round($activeSesi['skor'], 2),
                    'persentase_selesai' => round((count($answeredQuestions) / $activeSesi['total_soal']) * 100, 1)
                ],
                'soal' => $formattedSoal
            ]
        ];

        return $this->respond($response, 200);
    }

    /**
     * Get session detail
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

        $sesi = $this->sesiModel->getSesiWithRelations($id);
        if (!$sesi) {
            return $this->fail('Sesi tidak ditemukan', 404);
        }

        // Check if session belongs to user
        if ($sesi['id_siswa'] != $userId) {
            return $this->fail('Anda tidak memiliki akses ke sesi ini', 403);
        }

        // Get detailed answers
        $detailJawaban = $this->sesiModel->getSesiForMobile($id);
        $sesi['detail_jawaban'] = $detailJawaban['detail_jawaban'] ?? [];
        $sesi['progress_persen'] = $detailJawaban['progress_persen'] ?? 0;

        $response = [
            'status' => 'success',
            'message' => 'Detail sesi berhasil diambil',
            'data' => [
                'sesi' => $sesi
            ]
        ];

        return $this->respond($response, 200);
    }

    /**
     * Continue session (get next question)
     * POST /api/sesi/:id/continue
     */
    public function continue($id)
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

        $sesi = $this->sesiModel->find($id);
        if (!$sesi) {
            return $this->fail('Sesi tidak ditemukan', 404);
        }

        if ($sesi['id_siswa'] != $userId) {
            return $this->fail('Anda tidak memiliki akses ke sesi ini', 403);
        }

        if ($sesi['status'] != 'sedang_berjalan') {
            return $this->fail('Sesi sudah selesai atau dibatalkan', 400);
        }

        // Get next unanswered question
        $db = \Config\Database::connect();
        $answeredQuestions = $db->table('detail_jawaban_siswa')
            ->where('id_sesi', $id)
            ->get()
            ->getResultArray();

        $answeredIds = array_column($answeredQuestions, 'id_soal');
        $totalAnswered = count($answeredQuestions);

        if ($totalAnswered >= $sesi['total_soal']) {
            return $this->fail('Semua soal sudah dijawab', 400);
        }

        // Get questions and regenerate order
        $allSoal = $this->soalModel->getSoalWithJawaban($sesi['id_materi']);
        $quizResult = $this->lcm->generateQuizData($allSoal, $sesi['total_soal'], true, $sesi['seed_digunakan']);
        $randomizedSoal = $quizResult['questions'];

        // Find next unanswered question
        $nextQuestion = null;
        foreach ($randomizedSoal as $index => $soal) {
            if (!in_array($soal['id_soal'], $answeredIds)) {
                $nextQuestion = $soal;
                $nextQuestion['nomor'] = $index + 1;
                break;
            }
        }

        if (!$nextQuestion) {
            return $this->fail('Tidak ada soal tersisa', 400);
        }

        $response = [
            'status' => 'success',
            'message' => 'Soal berikutnya berhasil diambil',
            'data' => [
                'sesi_progress' => [
                    'total_soal' => $sesi['total_soal'],
                    'sudah_dijawab' => $totalAnswered,
                    'sisa_soal' => $sesi['total_soal'] - $totalAnswered,
                    'persentase' => round(($totalAnswered / $sesi['total_soal']) * 100, 1)
                ],
                'soal' => [
                    'nomor' => $nextQuestion['nomor'],
                    'id_soal' => $nextQuestion['id_soal'],
                    'pertanyaan' => $nextQuestion['pertanyaan'],
                    'tipe_soal' => $nextQuestion['tipe_soal'],
                    'tingkat_kesulitan' => $nextQuestion['tingkat_kesulitan'],
                    'poin' => $nextQuestion['poin'],
                    'jawaban' => array_map(function($jawaban) {
                        return [
                            'id_pilihan' => $jawaban['id_pilihan'],
                            'jawaban' => $jawaban['teks_jawaban']
                        ];
                    }, $nextQuestion['jawaban'])
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

        $sesi = $this->sesiModel->find($id);
        if (!$sesi) {
            return $this->fail('Sesi tidak ditemukan', 404);
        }

        if ($sesi['id_siswa'] != $userId) {
            return $this->fail('Anda tidak memiliki akses ke sesi ini', 403);
        }

        if ($sesi['status'] != 'sedang_berjalan') {
            return $this->fail('Sesi sudah selesai atau dibatalkan', 400);
        }

        $idSoal = $this->request->getVar('id_soal');
        $idPilihan = $this->request->getVar('id_pilihan');

        // Check if question already answered
        $db = \Config\Database::connect();
        $existingAnswer = $db->table('detail_jawaban_siswa')
            ->where('id_sesi', $id)
            ->where('id_soal', $idSoal)
            ->get()
            ->getRowArray();

        if ($existingAnswer) {
            return $this->fail('Soal ini sudah dijawab', 400);
        }

        // Get question to determine correct answer
        $soal = $this->soalModel->find($idSoal);
        if (!$soal) {
            return $this->fail('Soal tidak ditemukan', 404);
        }

        // Check if the chosen answer is correct
        $correctAnswer = $db->table('pilihan_jawaban')
            ->where('id_soal', $idSoal)
            ->where('is_benar', 1)
            ->get()
            ->getRowArray();

        $isBenar = $correctAnswer && $correctAnswer['id_pilihan'] == $idPilihan;

        // Save answer
        $this->transStart();

        try {
            // Get question order for this session
            $allSoal = $this->soalModel->getSoalWithJawaban($sesi['id_materi']);
            $quizResult = $this->lcm->generateQuizData($allSoal, $sesi['total_soal'], true, $sesi['seed_digunakan']);
            $randomizedSoal = $quizResult['questions'];

            $urutanSoal = 1;
            foreach ($randomizedSoal as $index => $soalItem) {
                if ($soalItem['id_soal'] == $idSoal) {
                    $urutanSoal = $index + 1;
                    break;
                }
            }

            // Save detailed answer
            $db->table('detail_jawaban_siswa')->insert([
                'id_sesi' => $id,
                'id_soal' => $idSoal,
                'id_pilihan' => $idPilihan,
                'urutan_soal' => $urutanSoal,
                'is_benar' => $isBenar,
                'waktu_jawab' => date('Y-m-d H:i:s'),
                'waktu_dibuat' => date('Y-m-d H:i:s')
            ]);

            // Update session progress
            $this->sesiModel->updateProgress($id, $isBenar, $soal['poin']);

            $this->transComplete();

            $response = [
                'status' => 'success',
                'message' => 'Jawaban berhasil disimpan',
                'data' => [
                    'is_benar' => $isBenar,
                    'poin_didapat' => $isBenar ? $soal['poin'] : 0,
                    'waktu_jawab' => date('Y-m-d H:i:s'),
                    'feedback' => $isBenar ? 'Jawaban benar!' : 'Jawaban salah, coba pelajari lagi materinya.'
                ]
            ];

            return $this->respond($response, 200);

        } catch (\Exception $e) {
            $this->transRollback();
            log_message('error', 'Error saving answer: ' . $e->getMessage());
            return $this->fail('Gagal menyimpan jawaban', 500);
        }
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

        $sesi = $this->sesiModel->find($id);
        if (!$sesi) {
            return $this->fail('Sesi tidak ditemukan', 404);
        }

        if ($sesi['id_siswa'] != $userId) {
            return $this->fail('Anda tidak memiliki akses ke sesi ini', 403);
        }

        if ($sesi['status'] != 'sedang_berjalan') {
            return $this->fail('Sesi sudah selesai atau dibatalkan', 400);
        }

        // Complete the session
        if (!$this->sesiModel->selesaikanSesi($id)) {
            return $this->fail('Gagal menyelesaikan sesi', 500);
        }

        // Get updated session data
        $completedSesi = $this->sesiModel->getSesiWithRelations($id);

        $response = [
            'status' => 'success',
            'message' => 'Sesi pembelajaran selesai',
            'data' => [
                'hasil' => [
                    'sesi_id' => $id,
                    'total_soal' => $completedSesi['total_soal'],
                    'soal_benar' => $completedSesi['soal_benar'],
                    'soal_salah' => $completedSesi['total_soal'] - $completedSesi['soal_benar'],
                    'skor_akhir' => round($completedSesi['skor'], 2),
                    'persentase_benar' => round(($completedSesi['soal_benar'] / $completedSesi['total_soal']) * 100, 1),
                    'durasi_menit' => round($completedSesi['durasi_detik'] / 60, 1),
                    'waktu_selesai' => $completedSesi['waktu_selesai']
                ],
                'kaidah' => [
                    'id_materi' => $completedSesi['id_materi'],
                    'judul_kaidah' => $completedSesi['judul_kaidah']
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

        $sesi = $this->sesiModel->getSesiWithRelations($id);
        if (!$sesi) {
            return $this->fail('Sesi tidak ditemukan', 404);
        }

        if ($sesi['id_siswa'] != $userId) {
            return $this->fail('Anda tidak memiliki akses ke sesi ini', 403);
        }

        if ($sesi['status'] != 'selesai') {
            return $this->fail('Sesi belum selesai', 400);
        }

        // Get detailed answers
        $db = \Config\Database::connect();
        $detailJawaban = $db->table('detail_jawaban_siswa')
            ->select('detail_jawaban_siswa.*, soal.pertanyaan, pilihan_jawaban.teks_jawaban')
            ->join('soal', 'soal.id_soal = detail_jawaban_siswa.id_soal')
            ->join('pilihan_jawaban', 'pilihan_jawaban.id_pilihan = detail_jawaban_siswa.id_pilihan')
            ->where('detail_jawaban_siswa.id_sesi', $id)
            ->orderBy('detail_jawaban_siswa.urutan_soal', 'ASC')
            ->get()
            ->getResultArray();

        $response = [
            'status' => 'success',
            'message' => 'Hasil sesi berhasil diambil',
            'data' => [
                'sesi' => $sesi,
                'statistik' => [
                    'total_soal' => $sesi['total_soal'],
                    'soal_benar' => $sesi['soal_benar'],
                    'soal_salah' => $sesi['total_soal'] - $sesi['soal_benar'],
                    'skor_akhir' => round($sesi['skor'], 2),
                    'persentase_benar' => round(($sesi['soal_benar'] / $sesi['total_soal']) * 100, 1),
                    'durasi_menit' => round($sesi['durasi_detik'] / 60, 1),
                    'rata_rata_waktu_per_soal' => round($sesi['durasi_detik'] / $sesi['total_soal'], 1) . ' detik'
                ],
                'detail_jawaban' => array_map(function($jawaban) {
                    return [
                        'nomor_soal' => $jawaban['urutan_soal'],
                        'pertanyaan' => $jawaban['pertanyaan'],
                        'jawaban_siswa' => $jawaban['teks_jawaban'],
                        'is_benar' => $jawaban['is_benar'],
                        'waktu_jawab' => $jawaban['waktu_jawab']
                    ];
                }, $detailJawaban)
            ]
        ];

        return $this->respond($response, 200);
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