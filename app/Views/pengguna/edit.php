<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Edit Pengguna - <?= $this->endSection() ?>

<?= $this->section('styles') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark">Edit Pengguna</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('pengguna') ?>">Pengguna</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= site_url('pengguna') ?>" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- User Info Card -->
    <div class="card border-info bg-light mb-4">
        <div class="card-body p-3">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                        <i class="ti ti-user fs-2"></i>
                    </div>
                </div>
                <div class="col">
                    <h5 class="mb-1"><?= esc($user['nama_lengkap']) ?></h5>
                    <p class="text-muted mb-0">
                        <strong>Username:</strong> <?= esc($user['nama_pengguna']) ?> |
                        <strong>Role:</strong>
                        <?php if ($user['hak_akses'] === 'ADMIN'): ?>
                            <span class="badge bg-danger">Administrator</span>
                        <?php else: ?>
                            <span class="badge bg-info">Guru</span>
                        <?php endif; ?> |
                        <strong>Status:</strong>
                        <?php if ($user['status'] === 'AKTIF'): ?>
                            <span class="badge bg-success">Aktif</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Nonaktif</span>
                        <?php endif; ?>
                    </p>
                    <small class="text-muted">Dibuat: <?= date('d M Y H:i', strtotime($user['waktu_dibuat'])) ?></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <!-- Flash Messages -->
            <?= $this->include('partials/flash_messages') ?>

            <!-- Alert Warning -->
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <i class="ti ti-alert-triangle me-2"></i>
                <div>
                    <strong>Perhatian:</strong> Jika password dibiarkan kosong, password lama akan tetap digunakan.
                    Isi password baru hanya jika ingin mengubah password pengguna.
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="<?= site_url('pengguna/' . $user['id_pengguna']) ?>" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">

                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama_pengguna" class="form-label">Username *</label>
                            <input type="text" class="form-control" id="nama_pengguna" name="nama_pengguna"
                                   value="<?= old('nama_pengguna', $user['nama_pengguna']) ?>"
                                   required minlength="3" maxlength="50"
                                   pattern="[a-zA-Z0-9\s]+" placeholder="contoh: admin123">
                            <div class="invalid-feedback">
                                Username harus diisi (3-50 karakter, huruf, angka, dan spasi)
                            </div>
                            <small class="text-muted">Hanya huruf, angka, dan spasi yang diperbolehkan</small>
                        </div>

                        <div class="mb-3">
                            <label for="kata_sandi" class="form-label">Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="kata_sandi" name="kata_sandi"
                                       minlength="6" placeholder="Kosongkan jika tidak ingin mengubah">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                    <i class="ti ti-eye" id="passwordToggleIcon"></i>
                                </button>
                            </div>
                            <div class="form-text">Kosongkan untuk tetap menggunakan password lama</div>
                        </div>

                        <?php if (old('kata_sandi') || !empty($_POST['kata_sandi'])): ?>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password Baru *</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                   required minlength="6" placeholder="Ulangi password baru">
                            <div class="invalid-feedback">
                                Konfirmasi password harus cocok
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap *</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                                   value="<?= old('nama_lengkap', $user['nama_lengkap']) ?>"
                                   required minlength="3" maxlength="100"
                                   placeholder="contoh: Ahmad Fauzi">
                            <div class="invalid-feedback">
                                Nama lengkap harus diisi (3-100 karakter)
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
  
                        <div class="mb-3">
                            <label for="hak_akses" class="form-label">Hak Akses *</label>
                            <select class="form-select" id="hak_akses" name="hak_akses" required>
                                <option value="">Pilih Hak Akses</option>
                                <option value="ADMIN" <?= old('hak_akses', $user['hak_akses']) === 'ADMIN' ? 'selected' : '' ?>>
                                    Administrator (Admin)
                                </option>
                                <option value="GURU" <?= old('hak_akses', $user['hak_akses']) === 'GURU' ? 'selected' : '' ?>>
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
                                <option value="AKTIF" <?= old('status', $user['status']) === 'AKTIF' ? 'selected' : '' ?>>
                                    Aktif
                                </option>
                                <option value="NONAKTIF" <?= old('status', $user['status']) === 'NONAKTIF' ? 'selected' : '' ?>>
                                    Nonaktif
                                </option>
                            </select>
                            <div class="invalid-feedback">
                                Status harus dipilih
                            </div>
                        </div>

                        <!-- Last Login Info -->
                        <div class="card border-secondary bg-light">
                            <div class="card-body p-3">
                                <h6 class="card-title text-secondary mb-2">
                                    <i class="ti ti-history me-1"></i>Informasi Terakhir
                                </h6>
                                <div class="small">
                                    <p class="mb-1">
                                        <strong>Dibuat:</strong> <?= date('d M Y H:i', strtotime($user['waktu_dibuat'])) ?>
                                    </p>
                                    <?php if (!empty($user['waktu_diubah'])): ?>
                                    <p class="mb-1">
                                        <strong>Diubah:</strong> <?= date('d M Y H:i', strtotime($user['waktu_diubah'])) ?>
                                    </p>
                                    <?php endif; ?>
                                    <p class="mb-0">
                                        <strong>User ID:</strong> #<?= $user['id_pengguna'] ?>
                                    </p>
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
                            <button type="button" class="btn btn-success" onclick="resetPassword()">
                                <i class="ti ti-key me-2"></i>Reset Password
                            </button>
                            <button type="button" class="btn btn-warning" onclick="generateNewPassword()">
                                <i class="ti ti-lock-open me-2"></i>Generate Password
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-2"></i>Simpan Perubahan
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

    <!-- Danger Zone -->
    <div class="card border-danger mt-4">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">
                <i class="ti ti-alert-triangle me-2"></i>Bahaya - Tindakan Permanen
            </h5>
        </div>
        <div class="card-body">
            <p class="text-muted mb-3">
                Tindakan di bawah ini bersifat permanen dan tidak dapat dibatalkan. Harap berhati-hati.
            </p>
            <div class="row">
                <div class="col-md-6">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-warning" onclick="toggleUserStatus()">
                            <i class="ti ti-toggle-left me-2"></i>
                            <?= $user['status'] === 'AKTIF' ? 'Nonaktifkan' : 'Aktifkan' ?> Pengguna
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <?php if ($user['id_pengguna'] != session()->get('user_id')): ?>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                            <i class="ti ti-trash me-2"></i>Hapus Pengguna
                        </button>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="ti ti-lock me-2"></i>
                        Anda tidak dapat menghapus akun yang sedang digunakan
                    </div>
                    <?php endif; ?>
                </div>
            </div>
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
        if (confirmInput) confirmInput.type = 'text';
        toggleIcon.className = 'ti ti-eye-off';
    } else {
        passwordInput.type = 'password';
        if (confirmInput) confirmInput.type = 'password';
        toggleIcon.className = 'ti ti-eye';
    }
}

// Generate new password
function generateNewPassword() {
    if (confirm('Generate password baru? Password lama akan diganti dengan password yang di-generate.')) {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
        let password = '';

        // Generate password dengan minimal 8 karakter
        for (let i = 0; i < 10; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }

        document.getElementById('kata_sandi').value = password;

        // Show confirm password field if not visible
        const confirmField = document.getElementById('confirm_password');
        if (confirmField) {
            confirmField.value = password;
            confirmField.required = true;
        }

        // Show notification
        showNotification('Password baru berhasil digenerate!', 'success');
    }
}

// Reset password to default
function resetPassword() {
    if (confirm('Reset password ke default (password123)?')) {
        document.getElementById('kata_sandi').value = 'password123';

        // Show confirm password field if not visible
        const confirmField = document.getElementById('confirm_password');
        if (confirmField) {
            confirmField.value = 'password123';
            confirmField.required = true;
        }

        showNotification('Password di-reset ke: password123', 'warning');
    }
}

// Toggle user status
function toggleUserStatus() {
    const currentStatus = '<?= $user['status'] ?>';
    const newStatus = currentStatus === 'AKTIF' ? 'NONAKTIF' : 'AKTIF';
    const actionText = newStatus === 'AKTIF' ? 'mengaktifkan' : 'menonaktifkan';

    if (confirm(`Apakah Anda yakin ingin ${actionText} pengguna ini?`)) {
        fetch(`<?= site_url('pengguna/toggleStatus/') ?><?= $user['id_pengguna'] ?>`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload();
            } else {
                alert(data.message || 'Gagal mengubah status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengubah status');
        });
    }
}

// Confirm delete
function confirmDelete() {
    if (confirm('Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan!')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= site_url('pengguna/delete/') ?><?= $user['id_pengguna'] ?>';

        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '<?= csrf_token() ?>';
            csrfInput.value = '<?= csrf_hash() ?>';
            form.appendChild(csrfInput);
        }

        document.body.appendChild(form);
        form.submit();
    }
}

// Show notification
function showNotification(message, type = 'info') {
    const alertClass = type === 'success' ? 'alert-success' :
                      type === 'error' ? 'alert-danger' :
                      type === 'warning' ? 'alert-warning' : 'alert-info';

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
    const originalUsername = '<?= $user['nama_pengguna'] ?>';

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
        } else if (value !== originalUsername) {
            // Check if username changed
            this.classList.remove('is-invalid');
            this.classList.remove('is-valid');
            checkUsernameAvailability(value);
        } else {
            this.setCustomValidity('');
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });

    // Password validation
    passwordInput.addEventListener('input', function() {
        if (this.value && this.value.length < 6) {
            this.setCustomValidity('Password minimal 6 karakter');
            this.classList.add('is-invalid');
        } else {
            this.setCustomValidity('');
            this.classList.remove('is-invalid');
            if (this.value) {
                this.classList.add('is-valid');
            }
        }

        // Check confirm password
        validateConfirmPassword();
    });

    // Confirm password validation
    if (confirmInput) {
        confirmInput.addEventListener('input', validateConfirmPassword);
    }

    function validateConfirmPassword() {
        if (!confirmInput) return;

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
    function checkUsernameAvailability(username) {
        clearTimeout(usernameCheckTimeout);

        if (username.length >= 3) {
            usernameCheckTimeout = setTimeout(() => {
                fetch('<?= site_url('pengguna/checkUsername') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        username: username,
                        exclude_id: <?= $user['id_pengguna'] ?>
                    })
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
            }, 500);
        }
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
            generateNewPassword();
        }

        // Escape to cancel
        if (e.key === 'Escape') {
            window.location.href = '<?= site_url('pengguna') ?>';
        }
    });
});
</script>
<?= $this->endSection() ?>