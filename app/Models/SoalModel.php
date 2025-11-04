<?php

namespace App\Models;

use CodeIgniter\Model;

class SoalModel extends Model
{
    protected $table            = 'soal';
    protected $primaryKey       = 'id_soal';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_materi',
        'pertanyaan',
        'tipe_soal',
        'tingkat_kesulitan',
        'poin',
        'dibuat_oleh'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'waktu_dibuat';
    protected $updatedField  = 'waktu_diubah';

    // Validation
    protected $validationRules      = [
        'id_materi'          => 'required|integer|greater_than[0]',
        'pertanyaan'         => 'required',
        'tipe_soal'          => 'required|in_list[pilihan_ganda]',
        'tingkat_kesulitan'  => 'required|in_list[mudah,sedang,sulit]',
        'poin'               => 'required|integer|greater_than[0]',
        'dibuat_oleh'        => 'required|integer|greater_than[0]'
    ];
    protected $validationMessages   = [
        'id_materi' => [
            'required'      => 'Materi kaidah harus dipilih',
            'integer'       => 'ID materi harus berupa angka',
            'greater_than'  => 'ID materi tidak valid'
        ],
        'pertanyaan' => [
            'required'      => 'Pertanyaan harus diisi'
        ],
        'tipe_soal' => [
            'required'      => 'Tipe soal harus dipilih',
            'in_list'       => 'Tipe soal tidak valid'
        ],
        'tingkat_kesulitan' => [
            'required'      => 'Tingkat kesulitan harus dipilih',
            'in_list'       => 'Tingkat kesulitan tidak valid'
        ],
        'poin' => [
            'required'      => 'Poin harus diisi',
            'integer'       => 'Poin harus berupa angka',
            'greater_than'  => 'Poin harus lebih dari 0'
        ],
        'dibuat_oleh' => [
            'required'      => 'Pembuat harus dipilih',
            'integer'       => 'ID pembuat harus berupa angka',
            'greater_than'  => 'ID pembuat tidak valid'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setTimestamps'];
    protected $beforeUpdate   = ['setTimestamps'];

    protected function setTimestamps(array $data)
    {
        $currentDateTime = date('Y-m-d H:i:s');
        $data['data']['waktu_dibuat'] = $currentDateTime;
        $data['data']['waktu_diubah'] = $currentDateTime;
        return $data;
    }

    // Enhanced methods for comprehensive functionality

    // Fungsi untuk mendapatkan pilihan jawaban soal
    public function getPilihanJawaban($id_soal)
    {
        $db = \Config\Database::connect();
        return $db->table('pilihan_jawaban')
            ->where('id_soal', $id_soal)
            ->orderBy('urutan', 'ASC')
            ->get()
            ->getResultArray();
    }

    // Fungsi untuk menyimpan soal dengan pilihan jawaban (transactional)
    public function saveSoalWithJawaban($soalData, $pilihanJawaban)
    {
        $this->transStart();

        try {
            // Insert soal
            $idSoal = $this->insert($soalData);

            if (!$idSoal) {
                throw new \Exception('Gagal menyimpan soal');
            }

            // Insert pilihan jawaban
            $db = \Config\Database::connect();
            foreach ($pilihanJawaban as $index => $pilihan) {
                $pilihanData = [
                    'id_soal' => $idSoal,
                    'teks_jawaban' => $pilihan['teks_jawaban'],
                    'is_benar' => $pilihan['is_benar'],
                    'urutan' => $index + 1,
                    'waktu_dibuat' => date('Y-m-d H:i:s')
                ];
                $db->table('pilihan_jawaban')->insert($pilihanData);
            }

            $this->transComplete();
            return $idSoal;
        } catch (\Exception $e) {
            $this->transRollback();
            log_message('error', 'Error saving soal with jawaban: ' . $e->getMessage());
            return false;
        }
    }

    // Fungsi untuk update soal dengan pilihan jawaban (transactional)
    public function updateSoalWithJawaban($id, $soalData, $pilihanJawaban)
    {
        $this->transStart();

        try {
            // Update soal
            if (!$this->update($id, $soalData)) {
                throw new \Exception('Gagal mengupdate soal');
            }

            // Delete existing pilihan jawaban
            $db = \Config\Database::connect();
            $db->table('pilihan_jawaban')->where('id_soal', $id)->delete();

            // Insert new pilihan jawaban
            foreach ($pilihanJawaban as $index => $pilihan) {
                $pilihanData = [
                    'id_soal' => $id,
                    'teks_jawaban' => $pilihan['teks_jawaban'],
                    'is_benar' => $pilihan['is_benar'],
                    'urutan' => $index + 1,
                    'waktu_dibuat' => date('Y-m-d H:i:s')
                ];
                $db->table('pilihan_jawaban')->insert($pilihanData);
            }

            $this->transComplete();
            return true;
        } catch (\Exception $e) {
            $this->transRollback();
            log_message('error', 'Error updating soal with jawaban: ' . $e->getMessage());
            return false;
        }
    }

    // Fungsi untuk delete soal dengan cascade delete pilihan jawaban
    public function deleteSoalWithJawaban($id)
    {
        $this->transStart();

        try {
            // Delete pilihan jawaban
            $db = \Config\Database::connect();
            $db->table('pilihan_jawaban')->where('id_soal', $id)->delete();

            // Delete soal
            if (!$this->delete($id)) {
                throw new \Exception('Gagal menghapus soal');
            }

            $this->transComplete();
            return true;
        } catch (\Exception $e) {
            $this->transRollback();
            log_message('error', 'Error deleting soal: ' . $e->getMessage());
            return false;
        }
    }

    // Enhanced get with answers for complete data
    public function getSoalWithRelations($id = null)
    {
        $builder = $this->select('
            soal.*,
            materi_kaidah.judul_kaidah,
            materi_kaidah.tingkat_kesulitan as tingkat_kesulitan_kaidah,
            pengguna.nama_lengkap as nama_pembuat
        ')
        ->join('materi_kaidah', 'materi_kaidah.id_materi = soal.id_materi')
        ->join('pengguna', 'pengguna.id_pengguna = soal.dibuat_oleh');

        if ($id) {
            $builder = $builder->where('soal.id_soal', $id);
        }

        $result = $builder->orderBy('soal.id_materi', 'ASC')
                      ->orderBy('soal.id_soal', 'DESC')
                      ->findAll();

        // Get pilihan jawaban for each soal
        foreach ($result as &$soal) {
            $soal['pilihan_jawaban'] = $this->getPilihanJawaban($soal['id_soal']);
        }

        return $id ? ($result[0] ?? null) : $result;
    }

    // Fungsi untuk mendapatkan statistik soal lengkap
    public function getSoalStatistics()
    {
        $total = $this->countAll();
        $mudah = $this->where('tingkat_kesulitan', 'mudah')->countAllResults();
        $sedang = $this->where('tingkat_kesulitan', 'sedang')->countAllResults();
        $sulit = $this->where('tingkat_kesulitan', 'sulit')->countAllResults();

        // Average points
        $avgPoints = $this->selectAvg('poin')->first();
        $maxPoints = $this->selectMax('poin')->first();
        $minPoints = $this->selectMin('poin')->first();

        return [
            'total' => $total,
            'mudah' => $mudah,
            'sedang' => $sedang,
            'sulit' => $sulit,
            'rata_rata_poin' => $avgPoints ? round($avgPoints, 2) : 0,
            'poin_tertinggi' => $maxPoints ?: 0,
            'poin_terendah' => $minPoints ?: 0
        ];
    }

    // Fungsi untuk validasi pilihan jawaban
    public function validatePilihanJawaban($pilihanJawaban)
    {
        if (empty($pilihanJawaban) || count($pilihanJawaban) < 2) {
            return ['success' => false, 'message' => 'Minimal harus ada 2 pilihan jawaban'];
        }

        $hasCorrectAnswer = false;
        foreach ($pilihanJawaban as $pilihan) {
            if (!empty($pilihan['teks_jawaban'])) {
                if (!empty($pilihan['is_benar'])) {
                    $hasCorrectAnswer = true;
                }
            } else {
                return ['success' => false, 'message' => 'Semua pilihan jawaban harus diisi'];
            }
        }

        if (!$hasCorrectAnswer) {
            return ['success' => false, 'message' => 'Harus ada satu jawaban yang benar'];
        }

        return ['success' => true];
    }

    // Fungsi untuk mendapatkan soal acak (placeholder untuk LCM)
    public function getRandomSoalForQuiz($id_materi, $jumlah = 10, $excludeIds = [])
    {
        $builder = $this->select('id_soal')
            ->where('id_materi', $id_materi);

        if (!empty($excludeIds)) {
            $builder->whereNotIn('id_soal', $excludeIds);
        }

        $ids = $builder->get()->getResultArray();

        if (count($ids) <= $jumlah) {
            return array_column($ids, 'id_soal');
        }

        // Simple random selection (akan diganti dengan LCM)
        shuffle($ids);
        return array_slice(array_column($ids, 'id_soal'), 0, $jumlah);
    }

    // Fungsi untuk batch import soal dari Excel/CSV
    public function batchImportSoal($data, $idPembuat)
    {
        $this->transStart();

        try {
            $db = \Config\Database::connect();
            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            foreach ($data as $index => $item) {
                try {
                    $soalData = [
                        'id_materi' => $item['id_materi'],
                        'pertanyaan' => $item['pertanyaan'],
                        'tingkat_kesulitan' => $item['tingkat_kesulitan'],
                        'poin' => $item['poin'] ?? 10,
                        'dibuat_oleh' => $idPembuat
                    ];

                    $idSoal = $this->insert($soalData);

                    if ($idSoal && !empty($item['pilihan_jawaban'])) {
                        foreach ($item['pilihan_jawaban'] as $jawabanIndex => $jawaban) {
                            $pilihanData = [
                                'id_soal' => $idSoal,
                                'teks_jawaban' => $jawaban['teks_jawaban'],
                                'is_benar' => $jawaban['is_benar'] ?? false,
                                'urutan' => $jawabanIndex + 1,
                                'waktu_dibuat' => date('Y-m-d H:i:s')
                            ];
                            $db->table('pilihan_jawaban')->insert($pilihanData);
                        }
                        $successCount++;
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            $this->transComplete();
            return [
                'success' => true,
                'imported' => $successCount,
                'errors' => $errorCount,
                'error_details' => $errors
            ];
        } catch (\Exception $e) {
            $this->transRollback();
            log_message('error', 'Error batch importing soal: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // Fungsi untuk mendapatkan soal untuk API mobile dengan struktur khusus
    public function getSoalForMobile($id_materi, $jumlah = 20, $seed = null)
    {
        // Ini akan digunakan dengan LCM algorithm
        $soalIds = $this->getRandomSoalForQuiz($id_materi, $jumlah);
        $soalList = [];

        foreach ($soalIds as $id) {
            $soal = $this->getSoalWithRelations($id);
            if ($soal) {
                $soalList[] = [
                    'id_soal' => $soal['id_soal'],
                    'pertanyaan' => $soal['pertanyaan'],
                    'poin' => $soal['poin'],
                    'tingkat_kesulitan' => $soal['tingkat_kesulitan'],
                    'pilihan_jawaban' => array_map(function($pilihan) {
                        return [
                            'id_pilihan' => $pilihan['id_pilihan'],
                            'teks_jawaban' => $pilihan['teks_jawaban'],
                            'is_benar' => (bool)$pilihan['is_benar']
                        ];
                    }, $soal['pilihan_jawaban'] ?? [])
                ];
            }
        }

        return $soalList;
    }

    // Custom methods
    public function getWithMateri()
    {
        return $this->select('soal.*, materi_kaidah.judul_kaidah, materi_kaidah.tingkat_kesulitan as tingkat_kesulitan_materi, pengguna.nama_lengkap as nama_pembuat')
                     ->join('materi_kaidah', 'materi_kaidah.id_materi = soal.id_materi')
                     ->join('pengguna', 'pengguna.id_pengguna = soal.dibuat_oleh')
                     ->orderBy('materi_kaidah.urutan', 'ASC')
                     ->orderBy('soal.id_soal', 'ASC')
                     ->findAll();
    }

    public function getByMateri($id_materi)
    {
        return $this->where('id_materi', $id_materi)
                     ->orderBy('id_soal', 'ASC')
                     ->findAll();
    }

    public function getByDifficulty($tingkat_kesulitan)
    {
        return $this->where('tingkat_kesulitan', $tingkat_kesulitan)
                     ->orderBy('id_soal', 'ASC')
                     ->findAll();
    }

    public function getByCreator($id_pembuat)
    {
        return $this->where('dibuat_oleh', $id_pembuat)
                     ->orderBy('id_soal', 'ASC')
                     ->findAll();
    }

    public function getWithAnswers($id_soal = null)
    {
        $builder = $this->select('soal.*, materi_kaidah.judul_kaidah, pilihan_jawaban.*')
                        ->join('materi_kaidah', 'materi_kaidah.id_materi = soal.id_materi')
                        ->join('pilihan_jawaban', 'pilihan_jawaban.id_soal = soal.id_soal')
                        ->orderBy('soal.id_soal', 'ASC')
                        ->orderBy('pilihan_jawaban.urutan', 'ASC');

        if ($id_soal) {
            $builder = $builder->where('soal.id_soal', $id_soal);
        }

        return $builder->findAll();
    }

    public function getWithCorrectAnswer($id_soal = null)
    {
        $builder = $this->select('soal.*, materi_kaidah.judul_kaidah, pilihan_jawaban.teks_jawaban as jawaban_benar')
                        ->join('materi_kaidah', 'materi_kaidah.id_materi = soal.id_materi')
                        ->join('pilihan_jawaban', 'pilihan_jawaban.id_soal = soal.id_soal AND pilihan_jawaban.is_benar = 1')
                        ->orderBy('soal.id_soal', 'ASC');

        if ($id_soal) {
            $builder = $builder->where('soal.id_soal', $id_soal);
        }

        return $builder->findAll();
    }

    public function search($keyword, $id_materi = null)
    {
        $builder = $this->like('pertanyaan', $keyword);

        if ($id_materi) {
            $builder = $builder->where('id_materi', $id_materi);
        }

        return $builder->orderBy('id_soal', 'ASC')->findAll();
    }

    public function getRandomQuestions($id_materi, $jumlah = 10)
    {
        return $this->where('id_materi', $id_materi)
                     ->orderBy('RAND()')
                     ->limit($jumlah)
                     ->findAll();
    }

    public function getStats()
    {
        return $this->select('COUNT(*) as total_soal, AVG(poin) as rata_rata_poin')
                     ->first();
    }

    public function getStatsByMateri($id_materi)
    {
        return $this->select('COUNT(*) as total_soal, AVG(poin) as rata_rata_poin, MAX(poin) as poin_tertinggi, MIN(poin) as poin_terendah')
                     ->where('id_materi', $id_materi)
                     ->first();
    }
}