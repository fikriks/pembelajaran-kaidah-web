<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use CodeIgniter\HTTP\ResponseInterface;

class SiswaController extends BaseController
{
    protected $siswaModel;

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
        $rules = [
            'nis' => 'required|min_length[5]|max_length[20]|is_unique[siswa,nis]',
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'kelas' => 'required|max_length[10]',
        ];

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
        $rules = [
            'nis' => "required|min_length[5]|max_length[20]|is_unique[siswa,nis,{$id}]",
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'kelas' => 'required|max_length[10]',
            'status' => 'required|in_list[AKTIF,NONAKTIF]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nis' => $this->request->getPost('nis'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'kelas' => $this->request->getPost('kelas'),
            'status' => $this->request->getPost('status')
        ];

        if ($this->siswaModel->update($id, $data)) {
            return redirect()->to('/siswa')->with('success', 'Data siswa berhasil diperbarui');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui data siswa');
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
