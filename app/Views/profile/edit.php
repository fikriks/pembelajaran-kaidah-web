<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-md-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Edit Profil</h1>
            <p class="mb-0">Perbarui informasi profil dan foto Anda</p>
        </div>
        <div>
            <a href="<?= site_url('profile') ?>" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-1"></i> Kembali ke Profil
            </a>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-edit me-2"></i> Informasi Profil
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <i class="ti ti-check me-2"></i> <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <i class="ti ti-alert-circle me-2"></i> <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('profile/update') ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_pengguna" class="form-label">Nama Pengguna</label>
                                    <input type="text" class="form-control" id="nama_pengguna" 
                                           value="<?= $user['nama_pengguna'] ?>" readonly>
                                    <div class="form-text">Nama pengguna tidak dapat diubah</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hak_akses" class="form-label">Hak Akses</label>
                                    <input type="text" class="form-control" 
                                           value="<?= ucfirst(strtolower($user['hak_akses'])) ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                   value="<?= old('nama_lengkap', $user['nama_lengkap']) ?>" required>
                        </div>

                        <!-- Photo Upload Section -->
                        <div class="mb-4">
                            <label class="form-label">Foto Profil</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <?php
                                    $userPhoto = !empty($user['foto_profil']) ? $user['foto_profil'] : 'user-1.jpg';
                                    $photoPath = base_url('assets/images/profile/' . $userPhoto);
                                    if ($userPhoto !== 'user-1.jpg' && file_exists(WRITEPATH . 'uploads/profile/' . $userPhoto)) {
                                        $photoPath = base_url('uploads/profile/' . $userPhoto);
                                    }
                                    ?>
                                    <img src="<?= $photoPath ?>" alt="Current Photo" 
                                         class="img-thumbnail w-100" style="max-width: 200px;">
                                </div>
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <input type="file" class="form-control" id="foto_profil" name="foto_profil" 
                                               accept="image/jpeg,image/png,image/gif">
                                        <div class="form-text">
                                            Format: JPG, PNG, GIF (maks. 2MB). Kosongkan jika tidak ingin mengubah foto.
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Bergabung</label>
                                    <input type="text" class="form-control" 
                                           value="<?= isset($user['waktu_dibuat']) && $user['waktu_dibuat'] ? date('d M Y H:i', strtotime($user['waktu_dibuat'])) : 'Tidak tersedia' ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Terakhir Diubah</label>
                                    <input type="text" class="form-control" 
                                           value="<?= isset($user['waktu_diubah']) && $user['waktu_diubah'] ? date('d M Y H:i', strtotime($user['waktu_diubah'])) : 'Tidak tersedia' ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check me-1"></i> Simpan Perubahan
                            </button>
                            <a href="<?= site_url('profile') ?>" class="btn btn-secondary">
                                <i class="ti ti-x me-1"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Tips Card -->
            <div class="card border-left-info shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="card-title text-info">
                        <i class="ti ti-info-circle me-2"></i> Tips Profil
                    </h6>
                    <ul class="small text-muted mb-0">
                        <li>Gunakan foto profil yang jelas dan profesional</li>
                        <li>Pastikan nama lengkap sesuai dengan identitas resmi</li>
                        <li>Foto profil akan ditampilkan di seluruh aplikasi</li>
                        <li>Format foto yang didukung: JPG, PNG, GIF</li>
                        <li>Ukuran maksimal foto: 2MB</li>
                    </ul>
                </div>
            </div>

            <!-- Security Card -->
            <div class="card border-left-warning shadow-sm">
                <div class="card-body">
                    <h6 class="card-title text-warning">
                        <i class="ti ti-shield-lock me-2"></i> Keamanan
                    </h6>
                    <p class="small text-muted mb-3">
                        Pastikan informasi profil Anda selalu diperbarui untuk keamanan akun.
                    </p>
                    <a href="#changePasswordModal" data-bs-toggle="modal" class="btn btn-warning btn-sm w-100">
                        <i class="ti ti-lock me-1"></i> Ubah Kata Sandi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Ubah Kata Sandi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('profile/change-password') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Kata Sandi Saat Ini</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Kata Sandi Baru</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" minlength="6" required>
                        <div class="form-text">Minimal 6 karakter</div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ubah Kata Sandi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Password confirmation validation
$(document).ready(function() {
    $('#new_password, #confirm_password').on('keyup', function() {
        if ($('#new_password').val() !== $('#confirm_password').val()) {
            $('#confirm_password').addClass('is-invalid');
        } else {
            $('#confirm_password').removeClass('is-invalid');
        }
    });

    // Photo preview
    $('#foto_profil').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('.img-thumbnail').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    // Delete photo checkbox
    $('#delete_photo').on('change', function() {
        if ($(this).is(':checked')) {
            $('#foto_profil').prop('disabled', true);
        } else {
            $('#foto_profil').prop('disabled', false);
        }
    });
});
</script>
<?= $this->endSection() ?>