<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\BabModel;
use App\Models\MateriKaidahModel;
use App\Models\SoalModel;
use CodeIgniter\API\ResponseTrait;

class BabController extends BaseController
{
    use ResponseTrait;

    protected $babModel;
    protected $materiModel;
    protected $soalModel;

    public function __construct()
    {
        $this->babModel = new BabModel();
        $this->materiModel = new MateriKaidahModel();
        $this->soalModel = new SoalModel();
    }

    /**
     * Get all chapters for mobile app
     */
    public function getChapters()
    {
        try {
            $limit = $this->request->getGet('limit') ?? 20;
            $offset = $this->request->getGet('offset') ?? 0;

            // Get chapters with progress statistics
            $babList = $this->babModel
                ->where('is_active', 1)
                ->orderBy('urutan', 'ASC')
                ->findAll($limit, $offset);

            // Add progress information to each chapter
            $chapters = [];
            foreach ($babList as $bab) {
                $totalMateri = $this->materiModel->where('id_bab', $bab['id_bab'])->countAllResults();
                $totalSoal = $this->soalModel->where('id_bab', $bab['id_bab'])->countAllResults();

                $chapterData = [
                    'id_bab' => (int)$bab['id_bab'],
                    'nama_bab' => $bab['nama_bab'],
                    'deskripsi' => $bab['deskripsi'],
                    'urutan' => (int)$bab['urutan'],
                    'is_active' => (bool)$bab['is_active'],
                    'total_materi' => (int)$totalMateri,
                    'total_soal' => (int)$totalSoal,
                    'created_at' => $bab['waktu_dibuat'],
                    'updated_at' => $bab['waktu_diubah']
                ];

                $chapters[] = $chapterData;
            }

            $total = $this->babModel->where('is_active', 1)->countAllResults();

            return $this->respond([
                'status' => 'success',
                'message' => 'Data bab berhasil diambil',
                'code' => 200,
                'data' => [
                    'chapters' => $chapters,
                    'pagination' => [
                        'total' => (int)$total,
                        'limit' => (int)$limit,
                        'offset' => (int)$offset,
                        'has_more' => ($offset + $limit) < $total
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error getting chapters: ' . $e->getMessage());
            return $this->fail('Terjadi kesalahan saat mengambil data bab');
        }
    }

    /**
     * Get chapter detail by ID
     */
    public function getChapterDetail($id)
    {
        try {
            $bab = $this->babModel->find($id);

            if (!$bab) {
                return $this->failNotFound('Bab tidak ditemukan');
            }

            if (!$bab['is_active']) {
                return $this->fail('Bab tidak aktif');
            }

            // Get related materi
            $materiList = $this->materiModel
                ->where('id_bab', $id)
                ->orderBy('urutan', 'ASC')
                ->findAll();

            // Get related soal count
            $totalSoal = $this->soalModel->where('id_bab', $id)->countAllResults();

            $chapterData = [
                'id_bab' => (int)$bab['id_bab'],
                'nama_bab' => $bab['nama_bab'],
                'deskripsi' => $bab['deskripsi'],
                'urutan' => (int)$bab['urutan'],
                'is_active' => (bool)$bab['is_active'],
                'total_materi' => count($materiList),
                'total_soal' => (int)$totalSoal,
                'materi_list' => $materiList,
                'created_at' => $bab['waktu_dibuat'],
                'updated_at' => $bab['waktu_diubah']
            ];

            return $this->respond([
                'status' => 'success',
                'message' => 'Detail bab berhasil diambil',
                'code' => 200,
                'data' => $chapterData
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error getting chapter detail: ' . $e->getMessage());
            return $this->fail('Terjadi kesalahan saat mengambil detail bab');
        }
    }

    /**
     * Get progress overview for all chapters
     */
    public function getProgressOverview()
    {
        try {
            // For now, return basic progress structure
            // In a real app, this would include user-specific progress data
            $babList = $this->babModel
                ->where('is_active', 1)
                ->orderBy('urutan', 'ASC')
                ->findAll();

            $chapters = [];
            foreach ($babList as $bab) {
                $totalMateri = $this->materiModel->where('id_bab', $bab['id_bab'])->countAllResults();

                $chapterData = [
                    'id_bab' => (int)$bab['id_bab'],
                    'nama_bab' => $bab['nama_bab'],
                    'urutan' => (int)$bab['urutan'],
                    'total_materi' => (int)$totalMateri,
                    'completed_materi' => 0, // This would come from user progress table
                    'progress_percentage' => 0,
                    'status_color' => 'secondary',
                    'next_action' => 'start',
                    'is_unlocked' => $bab['urutan'] <= 2 // First 2 chapters are unlocked for testing
                ];

                $chapters[] = $chapterData;
            }

            return $this->respond([
                'status' => 'success',
                'message' => 'Progress overview berhasil diambil',
                'code' => 200,
                'data' => [
                    'chapters' => $chapters,
                    'overall_progress' => [
                        'total_chapters' => count($chapters),
                        'completed_chapters' => 0,
                        'overall_percentage' => 0
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error getting progress overview: ' . $e->getMessage());
            return $this->fail('Terjadi kesalahan saat mengambil progress overview');
        }
    }

    /**
     * Get chapter statistics
     */
    public function getStatistics()
    {
        try {
            $totalBab = $this->babModel->where('is_active', 1)->countAllResults();
            $totalMateri = $this->materiModel->countAllResults();
            $totalSoal = $this->soalModel->countAllResults();

            return $this->respond([
                'status' => 'success',
                'message' => 'Statistik bab berhasil diambil',
                'code' => 200,
                'data' => [
                    'total_chapters' => (int)$totalBab,
                    'total_materi' => (int)$totalMateri,
                    'total_soal' => (int)$totalSoal,
                    'average_materi_per_chapter' => $totalBab > 0 ? round($totalMateri / $totalBab, 2) : 0,
                    'average_soal_per_chapter' => $totalBab > 0 ? round($totalSoal / $totalBab, 2) : 0
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error getting statistics: ' . $e->getMessage());
            return $this->fail('Terjadi kesalahan saat mengambil statistik');
        }
    }

    /**
     * Check unlock status for a specific chapter
     */
    public function checkUnlockStatus($id)
    {
        try {
            $bab = $this->babModel->find($id);

            if (!$bab) {
                return $this->failNotFound('Bab tidak ditemukan');
            }

            if (!$bab['is_active']) {
                return $this->fail('Bab tidak aktif');
            }

            // For testing, first 2 chapters are unlocked
            // In a real app, this would check user progress of previous chapters
            $isUnlocked = $bab['urutan'] <= 2;

            return $this->respond([
                'status' => 'success',
                'message' => 'Status unlock bab berhasil diambil',
                'code' => 200,
                'data' => [
                    'id_bab' => (int)$bab['id_bab'],
                    'nama_bab' => $bab['nama_bab'],
                    'urutan' => (int)$bab['urutan'],
                    'is_unlocked' => $isUnlocked,
                    'unlock_reason' => $isUnlocked ? 'Tersedia' : 'Selesaikan bab sebelumnya'
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error checking unlock status: ' . $e->getMessage());
            return $this->fail('Terjadi kesalahan saat mengecek status unlock');
        }
    }
}