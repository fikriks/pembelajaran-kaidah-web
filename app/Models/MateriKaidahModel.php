<?php

namespace App\Models;

use CodeIgniter\Model;

class MateriKaidahModel extends Model
{
    protected $table            = 'materi_kaidah';
    protected $primaryKey       = 'id_materi';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'judul_kaidah',
        'deskripsi',
        'penjelasan',
        'contoh',
        'urutan',
        'id_bab',
        'dibuat_oleh'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'waktu_dibuat';
    protected $updatedField  = 'waktu_diubah';

    // Validation
    protected $validationRules      = [
        'judul_kaidah'      => 'required|min_length[3]|max_length[255]|is_unique[materi_kaidah.judul_kaidah,id_materi,{id_materi}]',
        'deskripsi'         => 'required|max_length[500]',
        'penjelasan'        => 'required',
        'contoh'            => 'required',
        'urutan'            => 'required|integer|greater_than_equal_to[1]',
        'dibuat_oleh'       => 'required|integer|greater_than[0]'
    ];
    protected $validationMessages   = [
        'judul_kaidah' => [
            'required'      => 'Judul kaidah harus diisi',
            'min_length'    => 'Judul kaidah minimal 3 karakter',
            'max_length'    => 'Judul kaidah maksimal 255 karakter',
            'is_unique'     => 'Judul kaidah sudah digunakan'
        ],
        'deskripsi' => [
            'required'      => 'Deskripsi harus diisi',
            'max_length'    => 'Deskripsi maksimal 500 karakter'
        ],
        'penjelasan' => [
            'required'      => 'Penjelasan harus diisi'
        ],
        'contoh' => [
            'required'      => 'Contoh harus diisi'
        ],
        'urutan' => [
            'required'      => 'Urutan harus diisi',
            'integer'       => 'Urutan harus berupa angka',
            'greater_than_equal_to' => 'Urutan minimal 1'
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

    // Custom methods
    public function getWithCreator()
    {
        return $this->select('materi_kaidah.*, bab.nama_bab, bab.deskripsi as deskripsi_bab, pengguna.nama_lengkap as nama_pembuat')
                     ->join('bab', 'bab.id_bab = materi_kaidah.id_bab')
                     ->join('pengguna', 'pengguna.id_pengguna = materi_kaidah.dibuat_oleh')
                     ->orderBy('materi_kaidah.urutan', 'ASC')
                     ->findAll();
    }

    // Removed getByDifficulty method as tingkat_kesulitan field no longer exists

    public function getByCreator($id_pembuat)
    {
        return $this->where('dibuat_oleh', $id_pembuat)
                     ->orderBy('urutan', 'ASC')
                     ->findAll();
    }

    public function getWithStats()
    {
        return $this->select('materi_kaidah.*, bab.nama_bab, bab.deskripsi as deskripsi_bab, COUNT(DISTINCT soal.id_soal) as total_soal, COUNT(DISTINCT pilihan_jawaban.id_pilihan) as total_jawaban')
                     ->join('bab', 'bab.id_bab = materi_kaidah.id_bab', 'left')
                     ->join('soal', 'soal.id_materi = materi_kaidah.id_materi', 'left')
                     ->join('pilihan_jawaban', 'pilihan_jawaban.id_soal = soal.id_soal', 'left')
                     ->groupBy('materi_kaidah.id_materi')
                     ->orderBy('materi_kaidah.urutan', 'ASC')
                     ->findAll();
    }

    public function getNextOrder()
    {
        $result = $this->selectMax('urutan')->first();
        return ($result && $result['urutan']) ? $result['urutan'] + 1 : 1;
    }

    public function reorder($orderData)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($orderData as $order => $id_materi) {
            $this->update($id_materi, ['urutan' => $order + 1]);
        }

        $db->transComplete();
        return $db->transStatus();
    }

    public function search($keyword)
    {
        return $this->like('judul_kaidah', $keyword)
                     ->orLike('deskripsi', $keyword)
                     ->orLike('penjelasan', $keyword)
                     ->orderBy('urutan', 'ASC')
                     ->findAll();
    }

    public function getForDropdown()
    {
        return $this->select('id_materi, judul_kaidah')
                     ->orderBy('urutan', 'ASC')
                     ->findAll();
    }

    // API methods
    public function getKaidahWithProgress($userId, $search = null, $difficulty = null, $limit = 20, $page = 1)
    {
        $builder = $this->select('materi_kaidah.*')
                         ->orderBy('materi_kaidah.urutan', 'ASC');

        if ($search) {
            $builder->groupStart()
                    ->like('materi_kaidah.judul_kaidah', $search)
                    ->orLike('materi_kaidah.deskripsi', $search)
                    ->groupEnd();
        }

        $offset = ($page - 1) * $limit;
        $result = $builder->findAll($limit, $offset);
        $total = $this->countAllResults();

        return [
            'data' => $result,
            'current_page' => $page,
            'per_page' => $limit,
            'total' => $total
        ];
    }

    public function searchKaidah($keyword, $userId = null, $limit = 10, $page = 1)
    {
        $builder = $this->select('materi_kaidah.*')
                         ->groupStart()
                         ->like('materi_kaidah.judul_kaidah', $keyword)
                         ->orLike('materi_kaidah.deskripsi', $keyword)
                         ->orLike('materi_kaidah.penjelasan', $keyword)
                         ->groupEnd()
                         ->orderBy('materi_kaidah.urutan', 'ASC');

        $offset = ($page - 1) * $limit;
        $result = $builder->findAll($limit, $offset);
        $total = $this->countAllResults();

        return [
            'data' => $result,
            'current_page' => $page,
            'per_page' => $limit,
            'total' => $total
        ];
    }

    public function countByDifficulty($difficulty)
    {
        // Since tingkat_kesulitan field was removed, return 0 for all difficulty counts
        return 0;
    }

    // Chapter-based methods
    public function getGroupedByChapter($userId = null)
    {
        $builder = $this->select('bab.nama_bab as bab, bab.deskripsi as deskripsi_bab, COUNT(*) as total_materi')
                         ->join('bab', 'bab.id_bab = materi_kaidah.id_bab')
                         ->groupBy('bab.id_bab, bab.nama_bab, bab.deskripsi')
                         ->orderBy('bab.urutan', 'ASC');

        return $builder->findAll();
    }

    public function getByChapter($chapter, $userId = null)
    {
        // If $chapter is numeric, treat as id_bab, otherwise treat as nama_bab
        if (is_numeric($chapter)) {
            $builder = $this->where('materi_kaidah.id_bab', $chapter);
        } else {
            $builder = $this->join('bab', 'bab.id_bab = materi_kaidah.id_bab')
                            ->where('bab.nama_bab', $chapter);
        }

        $builder->orderBy('materi_kaidah.urutan', 'ASC');

        return $builder->findAll();
    }

    public function getChapterProgress($userId, $chapter)
    {
        // If $chapter is numeric, treat as id_bab, otherwise treat as nama_bab
        $chapterCondition = is_numeric($chapter)
            ? "mk.id_bab = ?"
            : "b.nama_bab = ?";

        $sql = "
            SELECT
                mk.*,
                b.nama_bab,
                b.deskripsi as deskripsi_bab,
                CASE
                    WHEN rb.status IS NULL THEN 'belum_dimulai'
                    WHEN rb.status = 'selesai' THEN 'selesai'
                    ELSE 'sedang_belajar'
                END as status,
                COALESCE(rb.persentase_penguasaan, 0) as persentase_penguasaan
            FROM materi_kaidah mk
            JOIN bab b ON b.id_bab = mk.id_bab
            LEFT JOIN (
                SELECT id_materi, MAX(status) as status, MAX(persentase_penguasaan) as persentase_penguasaan
                FROM riwayat_belajar
                WHERE id_siswa = ?
                GROUP BY id_materi
            ) rb ON rb.id_materi = mk.id_materi
            WHERE {$chapterCondition}
            ORDER BY mk.urutan ASC
        ";

        return $this->db->query($sql, [$userId, $chapter])->getResultArray();
    }

    public function getChapterStats($userId, $chapter)
    {
        $materi = $this->getChapterProgress($userId, $chapter);

        if (empty($materi)) {
            return [
                'total_materi' => 0,
                'completed' => 0,
                'in_progress' => 0,
                'not_started' => 0,
                'progress_percentage' => 0,
                'is_unlocked' => false
            ];
        }

        $total = count($materi);
        $completed = 0;
        $inProgress = 0;
        $notStarted = 0;

        foreach ($materi as $item) {
            switch ($item['status']) {
                case 'selesai':
                    $completed++;
                    break;
                case 'sedang_belajar':
                    $inProgress++;
                    break;
                default:
                    $notStarted++;
                    break;
            }
        }

        $progressPercentage = $total > 0 ? round(($completed / $total) * 100, 2) : 0;

        // Unlock logic: BAB 2 hanya unlocked jika BAB 1 100% completed
        $isUnlocked = true;
        if ($chapter === 'BAB 2: I\'RAB') {
            $bab1Progress = $this->getChapterStats($userId, 'BAB 1: KALAM');
            $isUnlocked = $bab1Progress['progress_percentage'] >= 100;
        }

        return [
            'total_materi' => $total,
            'completed' => $completed,
            'in_progress' => $inProgress,
            'not_started' => $notStarted,
            'progress_percentage' => $progressPercentage,
            'is_unlocked' => $isUnlocked,
            'materi' => $materi
        ];
    }

    public function getOverallProgress($userId)
    {
        $chapters = ['BAB 1: KALAM', 'BAB 2: I\'RAB'];
        $overallStats = [];

        foreach ($chapters as $chapter) {
            $overallStats[$chapter] = $this->getChapterStats($userId, $chapter);
        }

        return $overallStats;
    }

    // API methods for chapter-based learning
    public function getKaidahByChapterWithProgress($userId, $chapter = null)
    {
        if ($chapter) {
            $chapterStats = $this->getChapterStats($userId, $chapter);
            if (!$chapterStats['is_unlocked']) {
                return [
                    'chapter' => $chapter,
                    'is_unlocked' => false,
                    'message' => 'Chapter ini belum dibuka. Selesaikan chapter sebelumnya terlebih dahulu.',
                    'data' => []
                ];
            }
            return [
                'chapter' => $chapter,
                'is_unlocked' => true,
                'stats' => $chapterStats,
                'data' => $chapterStats['materi']
            ];
        } else {
            // Return all chapters with progress
            $chapters = $this->getGroupedByChapter();
            $result = [];

            foreach ($chapters as $chapterInfo) {
                $chapterStats = $this->getChapterStats($userId, $chapterInfo['bab']);
                $result[] = [
                    'bab' => $chapterInfo['bab'],
                    'deskripsi_bab' => $chapterInfo['deskripsi_bab'],
                    'total_materi' => $chapterStats['total_materi'],
                    'progress_percentage' => $chapterStats['progress_percentage'],
                    'is_unlocked' => $chapterStats['is_unlocked']
                ];
            }

            return $result;
        }
    }
}