<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Tambah Pengguna - <?= $this->endSection() ?>

<?= $this->section('styles') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark">Tambah Pengguna Baru</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('pengguna') ?>">Pengguna</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= site_url('pengguna') ?>" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <!-- Flash Messages -->
            <?= $this->include('partials/flash_messages') ?>

            <!-- Alert Info -->
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="ti ti-info-circle me-2"></i>
                <div>
                    <strong>Info:</strong> Password minimal 6 karakter. Admin memiliki akses penuh ke sistem,
                    sedangkan Guru hanya dapat mengelola materi kaidah dan soal.
                </div>
            </div>

            <!-- Form -->
            <form method="post" action="<?= site_url('pengguna/store') ?>" class="needs-validation" novalidate>
                <?= csrf_field() ?>

                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama_pengguna" class="form-label">Username *</label>
                            <input type="text" class="form-control" id="nama_pengguna" name="nama_pengguna"
                                   value="<?= old('nama_pengguna') ?>" required minlength="3" maxlength="50"
                                   pattern="[a-zA-Z0-9\s]+" placeholder="contoh: admin123">
                            <div class="invalid-feedback">
                                Username harus diisi (3-50 karakter, huruf, angka, dan spasi)
                            </div>
                            <small class="text-muted">Hanya huruf, angka, dan spasi yang diperbolehkan</small>
                        </div>

                        <div class="mb-3">
                            <label for="kata_sandi" class="form-label">Password *</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="kata_sandi" name="kata_sandi"
                                       required minlength="6" placeholder="Minimal 6 karakter">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                    <i class="ti ti-eye" id="passwordToggleIcon"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">
                                Password harus diisi minimal 6 karakter
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password *</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                   required minlength="6" placeholder="Ulangi password">
                            <div class="invalid-feedback">
                                Konfirmasi password harus cocok
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap *</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                                   value="<?= old('nama_lengkap') ?>" required minlength="3" maxlength="100"
                                   placeholder="contoh: Ahmad Fauzi">
                            <div class="invalid-feedback">
                                Nama lengkap harus diisi (3-100 karakter)
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?= old('email') ?>" maxlength="100"
                                   placeholder="contoh: email@example.com">
                            <div class="form-text">Opsional, digunakan untuk notifikasi sistem</div>
                        </div>

                        <div class="mb-3">
                            <label for="hak_akses" class="form-label">Hak Akses *</label>
                            <select class="form-select" id="hak_akses" name="hak_akses" required>
                                <option value="">Pilih Hak Akses</option>
                                <option value="ADMIN" <?= old('hak_akses') === 'ADMIN' ? 'selected' : '' ?>>
                                    Administrator (Admin)
                                </option>
                                <option value="GURU" <?= old('hak_akses') === 'GURU' ? 'selected' : '' ?>>
                                    Guru
                                </option>
                            </select>
                            <div class="invalid-feedback">
                                Hak akses harus dipilih
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">Pilih Status</option>
                                <option value="AKTIF" <?= old('status') === 'AKTIF' ? 'selected' : '' ?>>
                                    Aktif
                                </option>
                                <option value="NONAKTIF" <?= old('status') === 'NONAKTIF' ? 'selected' : '' ?>>
                                    Nonaktif
                                </option>
                            </select>
                            <div class="invalid-feedback">
                                Status harus dipilih
                            </div>
                        </div>

                        <!-- Role Information Card -->
                        <div class="card border-info bg-light">
                            <div class="card-body p-3">
                                <h6 class="card-title text-info mb-2">
                                    <i class="ti ti-info-circle me-1"></i>Informasi Hak Akses
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Admin:</strong>
                                        <ul class="small mb-0">
                                            <li>✅ Kelola semua pengguna</li>
                                            <li>✅ Kelola data siswa</li>
                                            <li>✅ Kelola materi kaidah</li>
                                            <li>✅ Lihat semua laporan</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Guru:</strong>
                                        <ul class="small mb-0">
                                            <li>✅ Kelola materi kaidah</li>
                                            <li>✅ Kelola soal</li>
                                            <li>✅ Lihat progress siswa</li>
                                            <li>❌ Kelola pengguna</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="row mt-4">
                    <div class="col-12">
                        <hr>
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="button" class="btn btn-success" onclick="generatePassword()">
                                <i class="ti ti-key me-2"></i>Generate Password
                            </button>
                            <button type="reset" class="btn btn-warning">
                                <i class="ti ti-refresh me-2"></i>Reset Form
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-2"></i>Simpan Pengguna
                            </button>
                            <a href="<?= site_url('pengguna') ?>" class="btn btn-danger">
                                <i class="ti ti-circle-x me-2"></i>Batal
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
function togglePassword() {
    const passwordInput = document.getElementById('kata_sandi');
    const confirmInput = document.getElementById('confirm_password');
    const toggleIcon = document.getElementById('passwordToggleIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        confirmInput.type = 'text';
        toggleIcon.className = 'ti ti-eye-off';
    } else {
        passwordInput.type = 'password';
        confirmInput.type = 'password';
        toggleIcon.className = 'ti ti-eye';
    }
}

// Generate random password
function generatePassword() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
    let password = '';

    // Generate password dengan minimal 8 karakter
    for (let i = 0; i < 10; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
    }

    document.getElementById('kata_sandi').value = password;
    document.getElementById('confirm_password').value = password;

    // Show notification
    showNotification('Password berhasil digenerate!', 'success');
}

// Show notification
function showNotification(message, type = 'info') {
    const alertClass = type === 'success' ? 'alert-success' :
                      type === 'error' ? 'alert-danger' : 'alert-info';

    const notification = document.createElement('div');
    notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Real-time validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.needs-validation');
    const passwordInput = document.getElementById('kata_sandi');
    const confirmInput = document.getElementById('confirm_password');
    const usernameInput = document.getElementById('nama_pengguna');

    // Username validation
    usernameInput.addEventListener('input', function() {
        const value = this.value;
        const pattern = /^[a-zA-Z0-9\s]*$/;

        if (!pattern.test(value)) {
            this.setCustomValidity('Username hanya boleh mengandung huruf, angka, dan spasi');
            this.classList.add('is-invalid');
        } else if (value.length < 3) {
            this.setCustomValidity('Username minimal 3 karakter');
            this.classList.add('is-invalid');
        } else {
            this.setCustomValidity('');
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });

    // Password validation
    passwordInput.addEventListener('input', function() {
        if (this.value.length < 6) {
            this.setCustomValidity('Password minimal 6 karakter');
            this.classList.add('is-invalid');
        } else {
            this.setCustomValidity('');
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }

        // Check confirm password
        validateConfirmPassword();
    });

    // Confirm password validation
    confirmInput.addEventListener('input', validateConfirmPassword);

    function validateConfirmPassword() {
        const password = passwordInput.value;
        const confirm = confirmInput.value;

        if (confirm && password !== confirm) {
            confirmInput.setCustomValidity('Password tidak cocok');
            confirmInput.classList.add('is-invalid');
        } else if (confirm && password === confirm) {
            confirmInput.setCustomValidity('');
            confirmInput.classList.remove('is-invalid');
            confirmInput.classList.add('is-valid');
        }
    }

    // Form submission validation
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }

        form.classList.add('was-validated');
    });

    // Check username availability (AJAX)
    let usernameCheckTimeout;
    usernameInput.addEventListener('blur', function() {
        clearTimeout(usernameCheckTimeout);
        const username = this.value.trim();

        if (username.length >= 3) {
            usernameCheckTimeout = setTimeout(() => {
                checkUsernameAvailability(username);
            }, 500);
        }
    });

    function checkUsernameAvailability(username) {
        fetch('<?= site_url('pengguna/checkUsername') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ username: username })
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                usernameInput.setCustomValidity('Username sudah digunakan');
                usernameInput.classList.add('is-invalid');
                showNotification('Username sudah digunakan, pilih username lain', 'error');
            } else {
                usernameInput.setCustomValidity('');
                usernameInput.classList.remove('is-invalid');
                usernameInput.classList.add('is-valid');
            }
        })
        .catch(error => {
            console.error('Error checking username:', error);
        });
    }

    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + S to save
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            form.submit();
        }

        // Ctrl/Cmd + G to generate password
        if ((e.ctrlKey || e.metaKey) && e.key === 'g') {
            e.preventDefault();
            generatePassword();
        }

        // Escape to cancel
        if (e.key === 'Escape') {
            window.location.href = '<?= site_url('pengguna') ?>';
        }
    });
});
</script>
<?= $this->endSection() ?>