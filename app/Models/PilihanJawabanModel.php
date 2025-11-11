<?php

namespace App\Models;

use CodeIgniter\Model;

class PilihanJawabanModel extends Model
{
    protected $table            = 'pilihan_jawaban';
    protected $primaryKey       = 'id_pilihan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_soal',
        'teks_jawaban',
        'is_benar',
        'urutan'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'waktu_dibuat';

    // Validation
    protected $validationRules      = [
        'id_soal'       => 'required|integer|greater_than[0]',
        'teks_jawaban'  => 'required',
        'is_benar'      => 'required|in_list[0,1]',
        'urutan'        => 'required|integer|greater_than[0]'
    ];
    protected $validationMessages   = [
        'id_soal' => [
            'required'      => 'Soal harus dipilih',
            'integer'       => 'ID soal harus berupa angka',
            'greater_than'  => 'ID soal tidak valid'
        ],
        'teks_jawaban' => [
            'required'      => 'Teks jawaban harus diisi'
        ],
        'is_benar' => [
            'required'      => 'Status jawaban benar harus dipilih',
            'in_list'       => 'Status jawaban tidak valid'
        ],
        'urutan' => [
            'required'      => 'Urutan harus diisi',
            'integer'       => 'Urutan harus berupa angka',
            'greater_than'  => 'Urutan harus lebih dari 0'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setTimestamp', 'ensureSingleCorrectAnswer'];
    protected $beforeUpdate   = ['setTimestamp', 'ensureSingleCorrectAnswer'];

    protected function setTimestamp(array $data)
    {
        $data['data']['waktu_dibuat'] = date('Y-m-d H:i:s');
        return $data;
    }

    protected function ensureSingleCorrectAnswer(array $data)
    {
        // Jika jawaban ini diset sebagai benar, pastikan tidak ada jawaban benar lain untuk soal yang sama
        if (isset($data['data']['is_benar']) && $data['data']['is_benar'] == 1) {
            $this->where('id_soal', $data['data']['id_soal'])
                 ->where('id_pilihan !=', $data['id'] ?? 0)
                 ->set(['is_benar' => 0])
                 ->update();
        }

        return $data;
    }

    // Custom methods
    public function getByQuestion($id_soal)
    {
        return $this->where('id_soal', $id_soal)
                     ->orderBy('urutan', 'ASC')
                     ->findAll();
    }

    public function getCorrectAnswer($id_soal)
    {
        return $this->where('id_soal', $id_soal)
                     ->where('is_benar', 1)
                     ->first();
    }

    public function getWithQuestion($id_pilihan = null)
    {
        $builder = $this->select('pilihan_jawaban.*, soal.pertanyaan, bab.nama_bab')
                        ->join('soal', 'soal.id_soal = pilihan_jawaban.id_soal')
                        ->join('bab', 'bab.id_bab = soal.id_bab')
                        ->orderBy('pilihan_jawaban.id_soal', 'ASC')
                        ->orderBy('pilihan_jawaban.urutan', 'ASC');

        if ($id_pilihan) {
            $builder = $builder->where('pilihan_jawaban.id_pilihan', $id_pilihan);
        }

        return $builder->findAll();
    }

    public function shuffleAnswers($id_soal, $seed = null)
    {
        $answers = $this->getByQuestion($id_soal);

        if ($seed !== null) {
            // Implement simple LCM shuffle untuk reproducible results
            $answers = $this->lcmShuffle($answers, $seed);
        } else {
            shuffle($answers);
        }

        return $answers;
    }

    private function lcmShuffle($array, $seed)
    {
        $count = count($array);
        if ($count <= 1) return $array;

        // Simple LCM implementation
        $a = 10;
        $c = 23;
        $m = 29;
        $x = $seed;

        $shuffled = [];
        $indices = range(0, $count - 1);

        for ($i = 0; $i < $count; $i++) {
            $x = ($a * $x + $c) % $m;
            $index = $x % $count;

            if (!isset($shuffled[$i])) {
                $shuffled[$i] = $array[$indices[$index]];
                unset($indices[$index]);
                $indices = array_values($indices);
            }
        }

        return array_values($shuffled);
    }

    public function createBatch($id_soal, $answers)
    {
        $data = [];
        foreach ($answers as $index => $answer) {
            $data[] = [
                'id_soal'       => $id_soal,
                'teks_jawaban'  => $answer['teks_jawaban'],
                'is_benar'      => $answer['is_benar'] ?? 0,
                'urutan'        => $index + 1,
                'waktu_dibuat'  => date('Y-m-d H:i:s')
            ];
        }

        return $this->insertBatch($data);
    }

    public function updateOrder($id_soal, $orderData)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($orderData as $urutan => $id_pilihan) {
            $this->update($id_pilihan, ['urutan' => $urutan + 1]);
        }

        $db->transComplete();
        return $db->transStatus();
    }

    public function validateQuestionHasCorrectAnswer($id_soal)
    {
        $correctAnswer = $this->where('id_soal', $id_soal)
                              ->where('is_benar', 1)
                              ->countAllResults();

        return $correctAnswer > 0;
    }

    public function getQuestionAnswerCount($id_soal)
    {
        return $this->where('id_soal', $id_soal)
                     ->countAllResults();
    }
}