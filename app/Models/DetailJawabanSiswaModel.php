<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailJawabanSiswaModel extends Model
{
    protected $table            = 'detail_jawaban_siswa';
    protected $primaryKey       = 'id_detail';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_sesi',
        'id_soal',
        'id_pilihan',
        'urutan_soal',
        'is_benar',
        'waktu_jawab'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';

    // Validation
    protected $validationRules      = [
        'id_sesi'       => 'required|integer|greater_than[0]',
        'id_soal'       => 'required|integer|greater_than[0]',
        'id_pilihan'    => 'required|integer|greater_than[0]',
        'urutan_soal'   => 'required|integer|greater_than[0]',
        'is_benar'      => 'required|in_list[0,1]',
        'waktu_jawab'   => 'required|valid_date[Y-m-d H:i:s]'
    ];
    protected $validationMessages   = [
        'id_sesi' => [
            'required'      => 'ID sesi harus diisi',
            'integer'       => 'ID sesi harus berupa angka',
            'greater_than'  => 'ID sesi tidak valid'
        ],
        'id_soal' => [
            'required'      => 'ID soal harus diisi',
            'integer'       => 'ID soal harus berupa angka',
            'greater_than'  => 'ID soal tidak valid'
        ],
        'id_pilihan' => [
            'required'      => 'ID pilihan harus diisi',
            'integer'       => 'ID pilihan harus berupa angka',
            'greater_than'  => 'ID pilihan tidak valid'
        ],
        'urutan_soal' => [
            'required'      => 'Urutan soal harus diisi',
            'integer'       => 'Urutan soal harus berupa angka',
            'greater_than'  => 'Urutan soal harus lebih dari 0'
        ],
        'is_benar' => [
            'required'      => 'Status jawaban harus dipilih',
            'in_list'       => 'Status jawaban tidak valid'
        ],
        'waktu_jawab' => [
            'required'      => 'Waktu jawab harus diisi',
            'valid_date'    => 'Format waktu jawab tidak valid'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Custom methods
    public function saveAnswer($id_sesi, $id_soal, $id_pilihan, $urutan_soal)
    {
        // Cek jawaban benar atau salah
        $jawabanModel = new PilihanJawabanModel();
        $jawaban = $jawabanModel->find($id_pilihan);
        $is_benar = $jawaban && $jawaban['is_benar'] ? 1 : 0;

        $data = [
            'id_sesi'       => $id_sesi,
            'id_soal'       => $id_soal,
            'id_pilihan'    => $id_pilihan,
            'urutan_soal'   => $urutan_soal,
            'is_benar'      => $is_benar,
            'waktu_jawab'   => date('Y-m-d H:i:s')
        ];

        return $this->insert($data);
    }

    public function getBySession($id_sesi)
    {
        return $this->select('detail_jawaban_siswa.*, soal.pertanyaan, pilihan_jawaban.teks_jawaban')
                     ->join('soal', 'soal.id_soal = detail_jawaban_siswa.id_soal')
                     ->join('pilihan_jawaban', 'pilihan_jawaban.id_pilihan = detail_jawaban_siswa.id_pilihan')
                     ->where('detail_jawaban_siswa.id_sesi', $id_sesi)
                     ->orderBy('detail_jawaban_siswa.urutan_soal', 'ASC')
                     ->findAll();
    }

    public function getSessionStats($id_sesi)
    {
        return $this->select('COUNT(*) as total_jawaban, SUM(is_benar) as jawaban_benar, AVG(is_benar) * 100 as persentase_benar')
                     ->where('id_sesi', $id_sesi)
                     ->first();
    }

    public function getCorrectAnswersBySession($id_sesi)
    {
        return $this->where('id_sesi', $id_sesi)
                     ->where('is_benar', 1)
                     ->findAll();
    }

    public function getWrongAnswersBySession($id_sesi)
    {
        return $this->where('id_sesi', $id_sesi)
                     ->where('is_benar', 0)
                     ->findAll();
    }

    public function getQuestionAnalysis($id_soal, $limit = null)
    {
        $builder = $this->select('COUNT(*) as total_jawaban, SUM(is_benar) as jawaban_benar, AVG(is_benar) * 100 as persentase_benar')
                        ->where('id_soal', $id_soal);

        if ($limit) {
            $builder = $builder->limit($limit);
        }

        return $builder->first();
    }

    public function getAnswerDistribution($id_soal)
    {
        return $this->select('pilihan_jawaban.teks_jawaban, COUNT(detail_jawaban_siswa.id_detail) as jumlah_dipilih, AVG(detail_jawaban_siswa.is_benar) * 100 as persentase_benar')
                     ->join('pilihan_jawaban', 'pilihan_jawaban.id_pilihan = detail_jawaban_siswa.id_pilihan')
                     ->where('detail_jawaban_siswa.id_soal', $id_soal)
                     ->groupBy('detail_jawaban_siswa.id_pilihan')
                     ->orderBy('jumlah_dipilih', 'DESC')
                     ->findAll();
    }

    public function getStudentAnswerHistory($id_siswa, $id_materi = null, $limit = null)
    {
        $builder = $this->select('detail_jawaban_siswa.*, sesi_latihan.skor as sesi_skor, materi_kaidah.judul_kaidah')
                        ->join('sesi_latihan', 'sesi_latihan.id_sesi = detail_jawaban_siswa.id_sesi')
                        ->join('materi_kaidah', 'materi_kaidah.id_materi = sesi_latihan.id_materi')
                        ->where('sesi_latihan.id_siswa', $id_siswa)
                        ->orderBy('detail_jawaban_siswa.waktu_jawab', 'DESC');

        if ($id_materi) {
            $builder = $builder->where('sesi_latihan.id_materi', $id_materi);
        }

        if ($limit) {
            $builder = $builder->limit($limit);
        }

        return $builder->findAll();
    }

    public function getDifficultyAnalysis($id_materi)
    {
        return $this->select('soal.tingkat_kesulitan, COUNT(*) as total_jawaban, AVG(detail_jawaban_siswa.is_benar) * 100 as persentase_benar')
                     ->join('soal', 'soal.id_soal = detail_jawaban_siswa.id_soal')
                     ->where('soal.id_bab', $id_materi)
                     ->groupBy('soal.tingkat_kesulitan')
                     ->findAll();
    }

    public function getTimeAnalysis($id_sesi)
    {
        return $this->select('detail_jawaban_siswa.urutan_soal, detail_jawaban_siswa.waktu_jawab, TIMESTAMPDIFF(SECOND, sesi_latihan.waktu_mulai, detail_jawaban_siswa.waktu_jawab) as waktu_detik')
                     ->join('sesi_latihan', 'sesi_latihan.id_sesi = detail_jawaban_siswa.id_sesi')
                     ->where('detail_jawaban_siswa.id_sesi', $id_sesi)
                     ->orderBy('detail_jawaban_siswa.urutan_soal', 'ASC')
                     ->findAll();
    }

    public function batchSaveAnswers($id_sesi, $answers)
    {
        $data = [];
        $jawabanModel = new PilihanJawabanModel();

        foreach ($answers as $urutan => $answer) {
            $jawaban = $jawabanModel->find($answer['id_pilihan']);
            $is_benar = $jawaban && $jawaban['is_benar'] ? 1 : 0;

            $data[] = [
                'id_sesi'       => $id_sesi,
                'id_soal'       => $answer['id_soal'],
                'id_pilihan'    => $answer['id_pilihan'],
                'urutan_soal'   => $urutan + 1,
                'is_benar'      => $is_benar,
                'waktu_jawab'   => date('Y-m-d H:i:s')
            ];
        }

        return $this->insertBatch($data);
    }

    public function getMostDifficultQuestions($id_materi = null, $limit = 10)
    {
        $builder = $this->select('soal.id_soal, soal.pertanyaan, COUNT(*) as total_jawaban, AVG(is_benar) * 100 as persentase_benar')
                        ->join('soal', 'soal.id_soal = detail_jawaban_siswa.id_soal')
                        ->groupBy('detail_jawaban_siswa.id_soal')
                        ->having('total_jawaban >=', 5) // Minimal 5 jawaban untuk valid
                        ->orderBy('persentase_benar', 'ASC')
                        ->limit($limit);

        if ($id_materi) {
            $builder = $builder->where('soal.id_bab', $id_materi);
        }

        return $builder->findAll();
    }
}