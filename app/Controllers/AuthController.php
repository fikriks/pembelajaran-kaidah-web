<?php

namespace App\Controllers;

use App\Models\PenggunaModel;

class AuthController extends BaseController
{
    protected $penggunaModel;

    public function __construct()
    {
        $this->penggunaModel = new PenggunaModel();
    }

    /**
     * Show login form
     */
    public function login()
    {
        // If user is already logged in, redirect to dashboard
        if ($this->currentUser) {
            return redirect()->to(site_url('dashboard'));
        }

        // Set page title for login
        $this->data['page_title'] = 'Login';

        return view('auth/login', $this->data);
    }

    /**
     * Process login
     */
    public function attemptLogin()
    {
        // Validate input
        $rules = [
            'nama_pengguna' => 'required|min_length[3]',
            'kata_sandi'     => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors())
                           ->with('error', 'Login gagal. Periksa kembali input Anda.');
        }

        $nama_pengguna = $this->request->getPost('nama_pengguna');
        $kata_sandi     = $this->request->getPost('kata_sandi');

        // Attempt authentication
        $user = $this->penggunaModel->authenticate($nama_pengguna, $kata_sandi);

        if ($user) {
            // Set session
            $this->session->set('user', $user);

            return redirect()->to(site_url('dashboard'))
                           ->with('success', 'Selamat datang, ' . $user['nama_lengkap'] . '!');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Nama pengguna atau kata sandi salah.');
    }

    /**
     * Logout user
     */
    public function logout()
    {
        if ($this->currentUser) {
            // Clear session
            $this->session->remove('user');
        }

        return redirect()->to(site_url('login'))
                       ->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Show forgot password form
     */
    public function forgotPassword()
    {
        // If user is already logged in, redirect to dashboard
        if ($this->currentUser) {
            return redirect()->to(site_url('dashboard'));
        }

        return view('auth/forgot_password', $this->data);
    }

    /**
     * Process forgot password
     */
    public function sendResetLink()
    {
        $rules = [
            'email' => 'required|valid_email'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $user = $this->penggunaModel->getByEmail($email);

        if ($user) {
            // Generate reset token
            $resetToken = bin2hex(random_bytes(32));

            // Store reset token in session (simplified approach)
            $this->session->set('reset_token', [
                'token' => $resetToken,
                'user_id' => $user['id_pengguna'],
                'expires' => time() + 3600 // 1 hour
            ]);

            
            // In a real application, send email with reset link
            // For now, just show success message
            return redirect()->back()
                           ->with('success', 'Link reset password telah dikirim ke email Anda. (Demo: Token: ' . $resetToken . ')');
        }

        // Don't reveal if email exists or not for security
        return redirect()->back()
                       ->with('success', 'Jika email terdaftar, link reset password akan dikirim.');
    }

    /**
     * Show reset password form
     */
    public function resetPassword($token = null)
    {
        // If user is already logged in, redirect to dashboard
        if ($this->currentUser) {
            return redirect()->to(site_url('dashboard'));
        }

        $resetToken = $this->session->get('reset_token');

        if (!$token || !$resetToken || $resetToken['token'] !== $token || $resetToken['expires'] < time()) {
            return redirect()->to(site_url('auth/forgot-password'))
                           ->with('error', 'Link reset password tidak valid atau telah kadaluarsa.');
        }

        $this->data['resetToken'] = $token;
        return view('auth/reset_password', $this->data);
    }

    /**
     * Process password reset
     */
    public function updatePassword()
    {
        $token = $this->request->getPost('token');
        $resetToken = $this->session->get('reset_token');

        if (!$token || !$resetToken || $resetToken['token'] !== $token || $resetToken['expires'] < time()) {
            return redirect()->to(site_url('auth/forgot-password'))
                           ->with('error', 'Link reset password tidak valid atau telah kadaluarsa.');
        }

        $rules = [
            'kata_sandi'     => 'required|min_length[6]',
            'konfirmasi_sandi' => 'required|matches[kata_sandi]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $kata_sandi = $this->request->getPost('kata_sandi');

        // Update password
        $this->penggunaModel->update($resetToken['user_id'], [
            'kata_sandi' => password_hash($kata_sandi, PASSWORD_DEFAULT)
        ]);

        
        // Clear reset token
        $this->session->remove('reset_token');

        return redirect()->to(site_url('login'))
                       ->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }
}