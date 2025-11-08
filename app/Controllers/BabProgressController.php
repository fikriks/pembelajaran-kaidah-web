<?php

namespace App\Controllers;

use App\Models\BabModel;
use App\Models\MateriKaidahModel;
use App\Models\SoalModel;
use App\Models\RiwayatBelajarModel;

class BabProgressController extends BaseController
{
    protected $babModel;
    protected $materiModel;
    protected $soalModel;
    protected $riwayatModel;

    public function __construct()
    {
        $this->babModel = new BabModel();
        $this->materiModel = new MateriKaidahModel();
        $this->soalModel = new SoalModel();
        $this->riwayatModel = new RiwayatBelajarModel();
    }

    /**
     * Check completion status for current bab
     */
    public function checkCompletion($babId, $siswaId = null)
    {
        // If no siswaId provided, get from session or use default
        if (!$siswaId) {
            // For demo purposes, using default siswa ID 1
            $siswaId = 1;
        }

        // Get current bab
        $currentBab = $this->babModel->find($babId);
        if (!$currentBab) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Bab tidak ditemukan'
            ]);
        }

        // Get all materi in current bab
        $materiList = $this->materiModel->getByBab($babId);
        $totalMateri = count($materiList);

        if ($totalMateri === 0) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Bab ini tidak memiliki materi'
            ]);
        }

        // Check completed materi for this siswa
        $completedMateri = 0;
        foreach ($materiList as $materi) {
            $riwayat = $this->riwayatModel->getBySiswaAndMateri($siswaId, $materi['id_materi']);
            if ($riwayat && $riwayat['status'] === 'selesai') {
                $completedMateri++;
            }
        }

        $isCompleted = $completedMateri === $totalMateri;
        $completionPercentage = ($completedMateri / $totalMateri) * 100;

        // Get next bab if available
        $nextBab = $this->babModel->getByOrder($currentBab['urutan'] + 1);

        // Get previous bab if available
        $prevBab = $this->babModel->getByOrder($currentBab['urutan'] - 1);

        return $this->response->setJSON([
            'status' => 'success',
            'data' => [
                'current_bab' => $currentBab,
                'total_materi' => $totalMateri,
                'completed_materi' => $completedMateri,
                'completion_percentage' => round($completionPercentage, 2),
                'is_completed' => $isCompleted,
                'next_bab' => $nextBab,
                'previous_bab' => $prevBab,
                'has_next_bab' => !empty($nextBab),
                'has_previous_bab' => !empty($prevBab)
            ]
        ]);
    }

    /**
     * Show congrats page when bab is completed
     */
    public function congrats($babId)
    {
        // Get current bab
        $currentBab = $this->babModel->find($babId);
        if (!$currentBab) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Get completion data
        $completionCheck = $this->checkCompletion($babId);
        $completionData = json_decode($completionCheck->getBody(), true);

        if ($completionData['status'] !== 'success' || !$completionData['data']['is_completed']) {
            // Redirect to bab list if not completed
            return redirect()->to('/bab')->with('error', 'Bab belum selesai dipelajari');
        }

        $data = [
            'page_title' => 'Selamat! Bab Selesai',
            'current_bab' => $completionData['data']['current_bab'],
            'next_bab' => $completionData['data']['next_bab'],
            'has_next_bab' => $completionData['data']['has_next_bab'],
            'completion_percentage' => $completionData['data']['completion_percentage']
        ];

        return view('bab/congrats', $data);
    }

    /**
     * Navigate to next bab
     */
    public function nextBab($currentBabId)
    {
        $currentBab = $this->babModel->find($currentBabId);
        if (!$currentBab) {
            return redirect()->to('/bab')->with('error', 'Bab tidak ditemukan');
        }

        $nextBab = $this->babModel->getByOrder($currentBab['urutan'] + 1);

        if ($nextBab) {
            return redirect()->to('/bab/show/' . $nextBab['id_bab']);
        } else {
            return redirect()->to('/bab')->with('info', 'Ini adalah bab terakhir');
        }
    }

    /**
     * Navigate to previous bab
     */
    public function previousBab($currentBabId)
    {
        $currentBab = $this->babModel->find($currentBabId);
        if (!$currentBab) {
            return redirect()->to('/bab')->with('error', 'Bab tidak ditemukan');
        }

        $prevBab = $this->babModel->getByOrder($currentBab['urutan'] - 1);

        if ($prevBab) {
            return redirect()->to('/bab/show/' . $prevBab['id_bab']);
        } else {
            return redirect()->to('/bab')->with('info', 'Ini adalah bab pertama');
        }
    }

    /**
     * Mark materi as completed (called when student finishes reading a materi)
     */
    public function markMateriCompleted($materiId, $siswaId = null)
    {
        if (!$siswaId) {
            $siswaId = 1; // Default for demo
        }

        $materi = $this->materiModel->find($materiId);
        if (!$materi) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Materi tidak ditemukan'
            ]);
        }

        // Check if riwayat exists
        $riwayat = $this->riwayatModel->getBySiswaAndMateri($siswaId, $materiId);

        if ($riwayat) {
            // Update existing riwayat
            $this->riwayatModel->update($riwayat['id_riwayat'], [
                'status' => 'selesai',
                'persentase_penguasaan' => 100,
                'waktu_akses_terakhir' => date('Y-m-d H:i:s')
            ]);
        } else {
            // Create new riwayat
            $this->riwayatModel->insert([
                'id_siswa' => $siswaId,
                'id_materi' => $materiId,
                'status' => 'selesai',
                'persentase_penguasaan' => 100,
                'waktu_akses_terakhir' => date('Y-m-d H:i:s'),
                'waktu_dibuat' => date('Y-m-d H:i:s')
            ]);
        }

        // Check if bab is completed
        $completionCheck = $this->checkCompletion($materi['id_bab'], $siswaId);
        $completionData = json_decode($completionCheck->getBody(), true);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Materi berhasil ditandai selesai',
            'data' => [
                'bab_completed' => $completionData['status'] === 'success' ? $completionData['data']['is_completed'] : false,
                'next_bab_available' => $completionData['status'] === 'success' ? $completionData['data']['has_next_bab'] : false
            ]
        ]);
    }
}