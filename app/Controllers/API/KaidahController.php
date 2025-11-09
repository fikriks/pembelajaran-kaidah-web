<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\MateriKaidahModel;
use App\Models\SoalModel;
use App\Models\SesiLatihanModel;
use App\Models\RiwayatBelajarModel;
use App\Models\BabModel;
use App\Libraries\APIHelper;
use CodeIgniter\API\ResponseTrait;

class KaidahController extends BaseController
{
    use ResponseTrait;

    protected $materiKaidahModel;
    protected $soalModel;
    protected $sesiLatihanModel;
    protected $riwayatBelajarModel;
    protected $babModel;

    public function __construct()
    {
        $this->materiKaidahModel = new MateriKaidahModel();
        $this->soalModel = new SoalModel();
        $this->sesiLatihanModel = new SesiLatihanModel();
        $this->riwayatBelajarModel = new RiwayatBelajarModel();
        $this->babModel = new BabModel();
    }

    /**
     * Get all kaidah for mobile with simple progress tracking
     * GET /api/kaidah
     */
    public function index()
    {
        // Get query parameters
        $search = $this->request->getVar('search');
        $page = $this->request->getVar('page') ?? 1;
        $limit = $this->request->getVar('limit') ?? 20;

        // For simplicity, use default user ID 1 for demo
        $userId = 1;

        // Get all materi kaidah with bab info
        $builder = $this->materiKaidahModel->select('materi_kaidah.*, bab.nama_bab')
                                                    ->join('bab', 'bab.id_bab = materi_kaidah.id_bab')
                                                    ->orderBy('materi_kaidah.urutan', 'ASC');

        // Apply search filter if provided
        if ($search) {
            $builder->like('materi_kaidah.judul_kaidah', $search)
                   ->orLike('materi_kaidah.deskripsi', $search);
        }

        // Get total count
        $total = $builder->countAllResults(false);

        // Get data with pagination
        $kaidahList = $builder->get($limit, ($page - 1) * $limit)->getResultArray();

        // Add progress info for each kaidah
        foreach ($kaidahList as &$kaidah) {
            // Count total questions for this bab
            $totalSoal = $this->soalModel->where('id_bab', $kaidah['id_bab'])->countAllResults();

            // Get progress from riwayat_belajar
            $riwayat = $this->riwayatBelajarModel
                ->where('id_siswa', $userId)
                ->where('id_materi', $kaidah['id_materi'])
                ->orderBy('waktu_diubah', 'DESC')
                ->first();

            // Calculate completion percentage and status
            $completionPercentage = 0;
            $status = 'belum_dimulai';

            if ($riwayat) {
                $completionPercentage = $riwayat['persentase_penguasaan'] ?? 0;
                $status = $riwayat['status'] ?? 'belum_dimulai';
            }

            $kaidah['total_soal'] = $totalSoal;
            $kaidah['progress_percentage'] = $completionPercentage;
            $kaidah['status'] = $status;
            $kaidah['is_locked'] = false; // For simplicity, all kaidah are unlocked
        }

        $response = [
            'status' => 'success',
            'message' => 'Daftar kaidah berhasil diambil',
            'code' => 200,
            'data' => [
                'kaidah' => $kaidahList,
                'pagination' => [
                    'current_page' => (int)$page,
                    'per_page' => (int)$limit,
                    'total' => $total,
                    'total_pages' => ceil($total / $limit)
                ]
            ]
        ];

        return $this->respond($response, 200);
    }

    /**
     * Get detail kaidah
     * GET /api/kaidah/:id
     */
    public function show($id)
    {
        // No authentication required for simplicity

        $kaidah = $this->materiKaidahModel->find($id);

        if (!$kaidah) {
            return $this->fail('Kaidah tidak ditemukan', 404);
        }

        // Handle missing id_bab field - populate based on urutan
        if (!isset($kaidah['id_bab']) || empty($kaidah['id_bab'])) {
            // BAB 1: KALAM for materials 1-10, BAB 2: I'RAB for materials 11-20
            $kaidah['id_bab'] = ($kaidah['urutan'] <= 10) ? 1 : 2;
        }

        // Get additional information
        $totalSoal = $this->soalModel->where('id_bab', $kaidah['id_bab'])->countAllResults();

        // No learning progress tracking for simplicity
        $riwayat = null;

        // No user-specific sessions for simplicity
        $recentSessions = [];

        $response = [
            'status' => 'success',
            'message' => 'Detail kaidah berhasil diambil',
            'code' => 200,
            'data' => [
                'kaidah' => [
                    'id_materi' => $kaidah['id_materi'],
                    'judul_kaidah' => $kaidah['judul_kaidah'],
                    'deskripsi' => $kaidah['deskripsi'],
                    'penjelasan' => $kaidah['penjelasan'],
                    'contoh' => $kaidah['contoh'],
                    'urutan' => $kaidah['urutan'],
                    'dibuat_oleh' => $kaidah['dibuat_oleh'],
                    'waktu_dibuat' => $kaidah['waktu_dibuat']
                ],
                'total_soal' => $totalSoal,
                'recent_sessions' => $recentSessions
            ]
        ];

        return $this->respond($response, 200);
    }

    /**
     * Get first materi of a specific bab
     * GET /api/kaidah/first/{bab_id}
     */
    public function getFirstMateriByBab($bab_id)
    {
        try {
            // Get user ID from Authorization header
            $authHeader = $this->request->getHeader('Authorization');
            $userId = 1; // Default user ID

            if ($authHeader) {
                $token = str_replace('Bearer ', '', $authHeader->getValue());
                $extractedUserId = $this->extractUserIdFromToken($token);
                if ($extractedUserId) {
                    $userId = $extractedUserId;
                }
            }

            // Get first materi of the specific bab
            $firstMateri = $this->materiKaidahModel
                ->where('id_bab', $bab_id)
                ->orderBy('urutan', 'ASC')
                ->orderBy('id_materi', 'ASC')
                ->first();

            if (!$firstMateri) {
                $response = [
                    'status' => 'error',
                    'message' => 'Tidak ada materi untuk bab ini',
                    'code' => 404
                ];
                return $this->respond($response, 404);
            }

            // Count total questions for this bab
            $totalSoal = $this->soalModel->where('id_bab', $bab_id)->countAllResults();

            // Get progress from riwayat_belajar
            $riwayat = $this->riwayatBelajarModel
                ->where('id_siswa', $userId)
                ->where('id_materi', $firstMateri['id_materi'])
                ->orderBy('waktu_diubah', 'DESC')
                ->first();

            // Calculate completion percentage and status
            $completionPercentage = 0;
            $status = 'belum_dimulai';

            if ($riwayat) {
                $completionPercentage = $riwayat['persentase_penguasaan'] ?? 0;
                $status = $riwayat['status'] ?? 'belum_dimulai';
            }

            // Build response
            $response = [
                'status' => 'success',
                'message' => 'Materi pertama bab berhasil diambil',
                'code' => 200,
                'data' => [
                    'id_materi' => $firstMateri['id_materi'],
                    'id_bab' => $firstMateri['id_bab'],
                    'judul_kaidah' => $firstMateri['judul_kaidah'],
                    'deskripsi' => $firstMateri['deskripsi'],
                    'penjelasan' => $firstMateri['penjelasan'],
                    'contoh' => $firstMateri['contoh'],
                    'urutan' => $firstMateri['urutan'],
                    'total_soal' => $totalSoal,
                    'progress_percentage' => $completionPercentage,
                    'status' => $status,
                    'is_first_materi' => true
                ]
            ];

            return $this->respond($response, 200);

        } catch (\Exception $e) {
            log_message('error', 'Error getting first materi by bab: ' . $e->getMessage());

            $response = [
                'status' => 'error',
                'message' => 'Terjadi kesalahan server',
                'code' => 500
            ];

            return $this->respond($response, 500);
        }
    }

    /**
     * Get kaidah grouped by bab with progress data
     * GET /api/kaidah/grouped
     */
    public function getGroupedByBab()
    {
        try {
            // Get user ID from Authorization header
            $authHeader = $this->request->getHeader('Authorization');
            $userId = 1; // Default user ID

            if ($authHeader) {
                $token = str_replace('Bearer ', '', $authHeader->getValue());
                $extractedUserId = $this->extractUserIdFromToken($token);
                if ($extractedUserId) {
                    $userId = $extractedUserId;
                }
            }

            // Get all active bab with urutan
            $babList = $this->babModel
                ->where('is_active', 1)
                ->orderBy('urutan', 'ASC')
                ->findAll();

            if (empty($babList)) {
                return $this->respond([
                    'status' => 'success',
                    'message' => 'No bab records found',
                    'data' => ['groups' => []]
                ]);
            }

            $groups = [];

            foreach ($babList as $bab) {
                // Get all materi for this bab
                $kaidahList = $this->materiKaidahModel
                    ->where('id_bab', $bab['id_bab'])
                    ->orderBy('urutan', 'ASC')
                    ->findAll();

                // Process kaidah data with progress information
                $processedKaidahList = [];
                $totalSoal = 0;
                $completedMateri = 0;
                $inProgressMateri = 0;
                $notStartedMateri = 0;

                foreach ($kaidahList as $kaidah) {
                    // Get progress from riwayat_belajar
                    $riwayat = $this->riwayatBelajarModel
                        ->where('id_siswa', $userId)
                        ->where('id_materi', $kaidah['id_materi'])
                        ->orderBy('waktu_diubah', 'DESC')
                        ->first();

                    // Initialize progress data
                    $progressPercentage = 0;
                    $status = 'belum_dimulai';
                    $completed = false;

                    // Get progress from riwayat if exists
                    if ($riwayat) {
                        $progressPercentage = (float) ($riwayat['persentase_penguasaan'] ?? 0);
                        $status = $riwayat['status'] ?? 'belum_dimulai';
                        $completed = $progressPercentage >= 100;
                    }

                    // Count materi status for Bab progress
                    if ($completed || $progressPercentage >= 100) {
                        $completedMateri++;
                    } elseif ($progressPercentage > 0) {
                        $inProgressMateri++;
                    } else {
                        $notStartedMateri++;
                    }

                    $processedKaidah = $kaidah;
                    // Ensure ID is integer
                    $processedKaidah['id_materi'] = (int)$kaidah['id_materi'];
                    $processedKaidah['id_bab'] = (int)$kaidah['id_bab'];
                    $processedKaidah['urutan'] = (int)$kaidah['urutan'];
                    $processedKaidah['dibuat_oleh'] = (int)$kaidah['dibuat_oleh'];

                    // Add progress data
                    $processedKaidah['progress_percentage'] = round($progressPercentage, 2);
                    $processedKaidah['status'] = $status;
                    $processedKaidah['completed'] = $completed;

                    $processedKaidahList[] = $processedKaidah;

                    // Count soal for this bab
                    $soalCount = $this->soalModel
                        ->where('id_bab', $kaidah['id_bab'])
                        ->countAllResults();
                    $totalSoal += $soalCount;
                }

                // Calculate Bab progress
                $totalMateri = count($processedKaidahList);
                $babProgressPercentage = $totalMateri > 0
                    ? round(($completedMateri / $totalMateri) * 100, 2)
                    : 0;

                // Determine Bab status color
                $statusColor = 'secondary';
                if ($babProgressPercentage >= 100) {
                    $statusColor = 'success';
                } elseif ($babProgressPercentage > 0) {
                    $statusColor = 'warning';
                }

                $groups[] = [
                    'bab' => [
                        'id_bab' => (int)$bab['id_bab'],
                        'nama_bab' => $bab['nama_bab'],
                        'deskripsi' => $bab['deskripsi'],
                        'urutan' => (int)$bab['urutan'],
                        // Add Bab progress data
                        'total_materi' => $totalMateri,
                        'completed_materi' => $completedMateri,
                        'in_progress_materi' => $inProgressMateri,
                        'not_started_materi' => $notStartedMateri,
                        'progress_percentage' => $babProgressPercentage,
                        'status_color' => $statusColor
                    ],
                    'kaidah_list' => $processedKaidahList,
                    'total_kaidah' => $totalMateri,
                    'total_soal' => $totalSoal
                ];
            }

            return $this->respond([
                'status' => 'success',
                'message' => 'Data kaidah per bab berhasil diambil',
                'data' => [
                    'groups' => $groups,
                    'summary' => [
                        'total_bab' => count($babList),
                        'total_kaidah' => array_sum(array_column($groups, 'total_kaidah')),
                        'total_soal' => array_sum(array_column($groups, 'total_soal'))
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error in getGroupedByBab: ' . $e->getMessage());
            return $this->fail('Error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Extract user ID from simple token
     */
    private function extractUserIdFromToken($token)
    {
        try {
            $decoded = base64_decode($token);
            if ($decoded && strpos($decoded, ':') !== false) {
                list($userId, $timestamp) = explode(':', $decoded);

                // Check if token is not too old (24 hours)
                if (time() - (int)$timestamp < 86400) {
                    return (int)$userId;
                }
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }
}