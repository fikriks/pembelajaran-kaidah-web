<?php

namespace App\Controllers;

use App\Models\PenggunaModel;

class ProfileController extends BaseController
{
    protected $penggunaModel;

    public function __construct()
    {
        $this->penggunaModel = new PenggunaModel();
    }

    public function index()
    {
        $this->requireAuth();
        
        // Get fresh data from database to ensure all fields are available
        $userId = $this->currentUser['id_pengguna'];
        $user = $this->penggunaModel->find($userId);
        
        $data = [
            'title' => 'Profil Saya',
            'user' => $user,
            'currentUser' => $user
        ];

        return view('profile/index', $data);
    }

    public function edit()
    {
        $this->requireAuth();
        
        // Get fresh data from database to ensure all fields are available
        $userId = $this->currentUser['id_pengguna'];
        $user = $this->penggunaModel->find($userId);
        
        $data = [
            'title' => 'Edit Profil',
            'user' => $user,
            'currentUser' => $user
        ];

        return view('profile/edit', $data);
    }

    public function update()
    {
        $this->requireAuth();
        
        $userId = $this->currentUser['id_pengguna'];
        
        $validationRules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'foto_profil' => 'max_size[foto_profil,2048]'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'waktu_diubah' => date('Y-m-d H:i:s')
        ];

        // Handle photo upload
        $fotoProfil = $this->request->getFile('foto_profil');
        if ($fotoProfil && $fotoProfil->isValid() && !$fotoProfil->hasMoved()) {
            // Validate file type manually using simpler approach
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            $fileExtension = strtolower($fotoProfil->getExtension());
            if (!in_array($fileExtension, $allowedTypes)) {
                return redirect()->back()->withInput()->with('error', 'Tipe file tidak diizinkan. Hanya JPG, PNG, dan GIF yang diperbolehkan.');
            }
            
            // Validate file size (max 2MB)
            if ($fotoProfil->getSize() > 2048 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Ukuran file terlalu besar. Maksimal 2MB.');
            }
            
            // Generate unique filename
            $newName = $fotoProfil->getRandomName();
            $uploadPath = WRITEPATH . 'uploads/profile/';
            
            // Ensure upload directory and symlink exist
            if (!$this->ensureProfileUploadDirectory()) {
                return redirect()->back()->withInput()->with('error', 'Gagal mempersiapkan direktori upload. Silakan hubungi administrator.');
            }
            
            // Move file directly without complex validation
            if ($fotoProfil->move($uploadPath, $newName)) {
                // Delete old photo if exists
                $oldUser = $this->penggunaModel->find($userId);
                if (!empty($oldUser['foto_profil']) && $oldUser['foto_profil'] !== 'user-1.jpg') {
                    $oldPhotoPath = WRITEPATH . 'uploads/profile/' . $oldUser['foto_profil'];
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }
                
                $data['foto_profil'] = $newName;
            } else {
                return redirect()->back()->withInput()->with('error', 'Gagal mengupload file');
            }
        }
        


        if ($this->penggunaModel->update($userId, $data)) {
            // Update session data
            $updatedUser = $this->penggunaModel->find($userId);
            $this->session->set('user', $updatedUser);
            
            return redirect()->to(site_url('profile'))->with('success', 'Profil berhasil diperbarui');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui profil');
        }
    }

    public function changePassword()
    {
        $this->requireAuth();
        
        $userId = $this->currentUser['id_pengguna'];
        
        $validationRules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');

        // Verify current password
        $user = $this->penggunaModel->find($userId);
        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Pengguna tidak ditemukan');
        }

        if (!password_verify($currentPassword, $user['kata_sandi'])) {
            // Log the attempt for debugging (remove in production)
            log_message('debug', 'Password change failed for user ID ' . $userId . ': Invalid current password');
            return redirect()->back()->withInput()->with('error', 'Kata sandi saat ini salah');
        }

        $data = [
            'kata_sandi' => password_hash($newPassword, PASSWORD_DEFAULT),
            'waktu_diubah' => date('Y-m-d H:i:s')
        ];

        // Skip validation for password change
        $this->penggunaModel->skipValidation(true);

        try {
            if ($this->penggunaModel->update($userId, $data)) {
                // Reset validation skipping
                $this->penggunaModel->skipValidation(false);
                log_message('info', 'Password changed successfully for user ID ' . $userId);
                return redirect()->to(site_url('profile'))->with('success', 'Kata sandi berhasil diubah');
            } else {
                // Reset validation skipping
                $this->penggunaModel->skipValidation(false);
                $error = $this->penggunaModel->error();
                $errorMessage = is_array($error) ? implode(', ', $error) : 'Gagal mengubah kata sandi';
                log_message('error', 'Password update failed for user ID ' . $userId . ': ' . $errorMessage);
                return redirect()->back()->withInput()->with('error', 'Gagal mengubah kata sandi: ' . $errorMessage);
            }
        } catch (\Exception $e) {
            // Reset validation skipping
            $this->penggunaModel->skipValidation(false);
            log_message('error', 'Exception during password change for user ID ' . $userId . ': ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat mengubah kata sandi');
        }
    }

    // Method uploadFotoProfil dihapus karena sudah dipindahkan ke dalam method update

    /**
     * Ensure profile upload directory and symlink exist
     */
    private function ensureProfileUploadDirectory()
    {
        $uploadPath = WRITEPATH . 'uploads/profile/';
        $publicPath = ROOTPATH . 'public/uploads/profile';
        $relativePath = 'uploads/profile';

        // Create writable directory if not exists
        if (!is_dir($uploadPath)) {
            if (!mkdir($uploadPath, 0755, true)) {
                log_message('error', 'Failed to create writable upload directory: ' . $uploadPath);
                return false;
            }
            log_message('info', 'Created writable upload directory: ' . $uploadPath);
        }

        // Check if public/uploads directory exists
        $publicUploadDir = dirname($publicPath);
        if (!is_dir($publicUploadDir)) {
            if (!mkdir($publicUploadDir, 0755, true)) {
                log_message('error', 'Failed to create public uploads directory: ' . $publicUploadDir);
                return false;
            }
            log_message('info', 'Created public uploads directory: ' . $publicUploadDir);
        }

        // Create symlink if it doesn't exist
        if (!is_link($publicPath) && !file_exists($publicPath)) {
            // Create relative symlink for better portability
            $target = '../../writable/uploads/profile';

            if (symlink($target, $publicPath)) {
                log_message('info', 'Created symlink: ' . $publicPath . ' -> ' . $target);
            } else {
                log_message('error', 'Failed to create symlink: ' . $publicPath . ' -> ' . $target);
                return false;
            }
        } elseif (is_link($publicPath)) {
            // Verify existing symlink points to correct target
            $target = readlink($publicPath);
            $expectedTarget = '../../writable/uploads/profile';

            if ($target !== $expectedTarget) {
                log_message('warning', 'Symlink exists but points to wrong target: ' . $publicPath . ' -> ' . $target . ' (expected: ' . $expectedTarget . ')');
            }
        }

        // Verify directory is writable
        if (!is_writable($uploadPath)) {
            log_message('error', 'Upload directory is not writable: ' . $uploadPath);
            return false;
        }

        return true;
    }


}