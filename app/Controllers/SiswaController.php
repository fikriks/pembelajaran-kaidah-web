<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use CodeIgniter\HTTP\ResponseInterface;

class SiswaController extends BaseController
{
    protected $siswaModel;

    // Base validation rules untuk menghindari DRY
    private $siswaBaseRules = [
        'nis' => [
            'rules' => 'required|min_length[5]|max_length[20]',
            'errors' => [
                'required' => 'NIS wajib diisi',
                'min_length' => 'NIS minimal 5 karakter',
                'max_length' => 'NIS maksimal 20 karakter'
            ]
        ],
        'nama_lengkap' => [
            'rules' => 'required|min_length[3]|max_length[100]',
            'errors' => [
                'required' => 'Nama lengkap wajib diisi',
                'min_length' => 'Nama lengkap minimal 3 karakter',
                'max_length' => 'Nama lengkap maksimal 100 karakter'
            ]
        ],
        'jenis_kelamin' => [
            'rules' => 'required|in_list[L,P]',
            'errors' => [
                'required' => 'Jenis kelamin wajib dipilih',
                'in_list' => 'Jenis kelamin harus Laki-laki atau Perempuan'
            ]
        ],
        'kelas' => [
            'rules' => 'required|max_length[10]',
            'errors' => [
                'required' => 'Kelas wajib diisi',
                'max_length' => 'Kelas maksimal 10 karakter'
            ]
        ]
    ];

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $kelas = $this->request->getGet('kelas');
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 10;

        $paginationData = $this->siswaModel->getSiswaPaginated($perPage, $page, $search, $kelas);

        // Get all unique kelas for filter dropdown
        $kelasOptions = [];
        $allSiswa = $this->siswaModel->findAll();
        foreach ($allSiswa as $siswa) {
            if (!in_array($siswa['kelas'], $kelasOptions)) {
                $kelasOptions[] = $siswa['kelas'];
            }
        }
        sort($kelasOptions);

        // Prepare data for view with proper structure
        $data = [
            'title' => 'Manajemen Siswa',
            'siswa' => $paginationData['data'],
            'total' => $paginationData['total'],
            'perPage' => $paginationData['perPage'],
            'currentPage' => $paginationData['currentPage'],
            'search' => $search,
            'selectedKelas' => $kelas,
            'kelasOptions' => $kelasOptions,
            'stats' => $this->siswaModel->getStatistics()
        ];

        return view('siswa/index', $data);
    }

    public function create()
    {
        return view('siswa/create');
    }

    public function store()
    {
        // Clone base rules dan tambahkan is_unique untuk NIS
        $rules = $this->siswaBaseRules;
        $rules['nis']['rules'] .= '|is_unique[siswa.nis]';
        $rules['nis']['errors']['is_unique'] = 'NIS sudah digunakan, gunakan NIS lain';

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nis' => $this->request->getPost('nis'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'kata_sandi' => $this->siswaModel->generateRandomPassword(),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'kelas' => $this->request->getPost('kelas'),
            'status' => 'AKTIF'
        ];

        if ($this->siswaModel->insert($data)) {
            return redirect()->to('/siswa')->with('success', 'Data siswa berhasil ditambahkan');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan data siswa');
        }
    }

    public function edit($id)
    {
        $siswa = $this->siswaModel->find($id);

        if (!$siswa) {
            return redirect()->to('/siswa')->with('error', 'Data siswa tidak ditemukan');
        }

        $data['siswa'] = $siswa;
        return view('siswa/edit', $data);
    }

    public function update($id)
    {
        // Clone base rules dan modifikasi untuk update
        $rules = $this->siswaBaseRules;

        // Tambahkan is_unique dengan exception untuk current record
        $rules['nis']['rules'] .= "|is_unique[siswa.nis,id,{$id}]";
        $rules['nis']['errors']['is_unique'] = 'NIS sudah digunakan oleh siswa lain';

        // Tambahkan status field untuk update
        $rules['status'] = [
            'rules' => 'required|in_list[AKTIF,NONAKTIF]',
            'errors' => [
                'required' => 'Status wajib dipilih',
                'in_list' => 'Status harus Aktif atau Nonaktif'
            ]
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $data = [
            'nis' => $this->request->getPost('nis'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'kelas' => $this->request->getPost('kelas'),
            'status' => $this->request->getPost('status')
        ];

        try {
            // Check if data has actually changed
            $currentSiswa = $this->siswaModel->find($id);
            $hasChanges = false;

            foreach ($data as $key => $value) {
                if ($currentSiswa[$key] !== $value) {
                    $hasChanges = true;
                    break;
                }
            }

            if (!$hasChanges) {
                log_message('info', 'No changes detected for student: ID ' . $id);
                return redirect()->to('/siswa')->with('success', 'Berhasil mengubah data siswa');
            }

            $updateResult = $this->siswaModel->update($id, $data);

            if ($updateResult) {
                log_message('info', 'Student updated successfully: ID ' . $id);
                return redirect()->to('/siswa')->with('success', 'Data siswa berhasil diperbarui');
            } else {
                // Check if update failed due to no changes vs actual error
                $affectedRows = $this->siswaModel->db->affectedRows();
                $dbError = $this->siswaModel->db->error();

                if ($affectedRows === 0 && empty($dbError['message'])) {
                    log_message('info', 'Update successful but no rows affected (data unchanged): ID ' . $id);
                    return redirect()->to('/siswa')->with('success', 'Data siswa berhasil diperbarui');
                } else {
                    log_message('error', 'Database error on update: ' . json_encode($dbError));
                    log_message('error', 'Failed to update student: ID ' . $id);
                    return redirect()->back()->with('error', 'Gagal memperbarui data siswa. Silakan coba lagi.');
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception in update: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui data siswa. Terjadi kesalahan sistem.');
        }
    }

    public function delete($id)
    {
        $siswa = $this->siswaModel->find($id);

        if (!$siswa) {
            return redirect()->to('/siswa')->with('error', 'Data siswa tidak ditemukan');
        }

        if ($this->siswaModel->delete($id)) {
            return redirect()->to('/siswa')->with('success', 'Data siswa berhasil dihapus');
        } else {
            return redirect()->to('/siswa')->with('error', 'Gagal menghapus data siswa');
        }
    }

    public function resetPassword($id)
    {
        $siswa = $this->siswaModel->find($id);

        if (!$siswa) {
            return redirect()->to('/siswa')->with('error', 'Data siswa tidak ditemukan');
        }

        $newPassword = $this->siswaModel->generateRandomPassword();

        if ($this->siswaModel->update($id, ['kata_sandi' => $newPassword])) {
            return redirect()->to('/siswa')->with('success', "Password berhasil direset. Password baru: {$newPassword}");
        } else {
            return redirect()->to('/siswa')->with('error', 'Gagal reset password');
        }
    }

    public function loginHistory($id)
    {
        $siswa = $this->siswaModel->find($id);

        if (!$siswa) {
            return redirect()->to('/siswa')->with('error', 'Data siswa tidak ditemukan');
        }

        $db = \Config\Database::connect();
        $query = $db->table('siswa_login_history')
                    ->where('nis', $siswa['nis'])
                    ->orderBy('login_time', 'DESC')
                    ->limit(50)
                    ->get();

        $data['siswa'] = $siswa;
        $data['loginHistory'] = $query->getResultArray();

        return view('siswa/login_history', $data);
    }
}
