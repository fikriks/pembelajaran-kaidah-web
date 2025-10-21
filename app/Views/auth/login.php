<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<form action="<?= site_url('login') ?>" method="post" class="auth-form needs-validation" novalidate>
    <?= csrf_field() ?>

    <div class="mb-3">
        <label for="nama_pengguna" class="form-label">Nama Pengguna</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-person"></i>
            </span>
            <input type="text" class="form-control" id="nama_pengguna" name="nama_pengguna"
                   placeholder="Masukkan nama pengguna" required>
        </div>
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
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="remember">
            <label class="form-check-label" for="remember">
                Ingat saya
            </label>
        </div>
        <a href="#" class="text-decoration-none text-muted small">
            Lupa kata sandi?
        </a>
    </div>

    <button type="submit" class="btn btn-primary auth-btn w-100">
        <i class="bi bi-box-arrow-in-right me-2"></i>Login
    </button>
</form>
<?= $this->endSection() ?>