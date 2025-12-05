<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<?php
// Get flash messages
$error_message = session('error');
$errors = session('errors');
$old_input = session()->getFlashdata('_ci_old_input') ?? [];
?>

<?php if ($error_message): ?>
    <div class="alert alert-danger d-flex align-items-center" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <div>
            <strong>Login Gagal!</strong><br>
            <?= esc($error_message) ?>
        </div>
    </div>
<?php endif; ?>

<?php if ($errors && is_array($errors)): ?>
    <div class="alert alert-danger d-flex align-items-center" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <div>
            <strong>Validasi Gagal!</strong><br>
            <ul class="mb-0 mt-2">
                <?php foreach ($errors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>

<form action="<?= site_url('login') ?>" method="post" class="auth-form">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label for="nama_pengguna" class="form-label">Nama Pengguna</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-person"></i>
            </span>
            <input type="text" class="form-control" id="nama_pengguna" name="nama_pengguna"
                   placeholder="Masukkan nama pengguna"
                   value="<?= esc($old_input['nama_pengguna'] ?? '') ?>"
                   required>
        </div>
        <?php if ($errors && isset($errors['nama_pengguna'])): ?>
            <div class="invalid-feedback d-block">
                <i class="bi bi-exclamation-circle"></i> <?= esc($errors['nama_pengguna']) ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="kata_sandi" class="form-label">Kata Sandi</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-lock"></i>
            </span>
            <input type="password" class="form-control" id="kata_sandi" name="kata_sandi"
                   placeholder="Masukkan kata sandi" required>
            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#kata_sandi">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        <?php if ($errors && isset($errors['kata_sandi'])): ?>
            <div class="invalid-feedback d-block">
                <i class="bi bi-exclamation-circle"></i> <?= esc($errors['kata_sandi']) ?>
            </div>
        <?php endif; ?>
    </div>


    <button type="submit" class="btn btn-primary auth-btn w-100">
        <i class="bi bi-box-arrow-in-right me-2"></i>Login
    </button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus on first empty field or password field if username is filled
    const usernameField = document.getElementById('nama_pengguna');
    const passwordField = document.getElementById('kata_sandi');

    if (usernameField.value.trim()) {
        passwordField.focus();
    } else {
        usernameField.focus();
    }

    // Add visual feedback for authentication errors
    <?php if ($error_message): ?>
        // Add red border to input fields on error
        usernameField.classList.add('is-invalid');
        passwordField.classList.add('is-invalid');

        // Clear validation when user starts typing
        [usernameField, passwordField].forEach(function(field) {
            field.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
        });
    <?php endif; ?>
});
</script>
<?= $this->endSection() ?>