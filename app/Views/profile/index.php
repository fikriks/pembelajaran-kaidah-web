<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-md-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Profil Saya</h1>
            <p class="mb-0">Kelola informasi profil dan preferensi akun Anda</p>
        </div>
        <div>
            <a href="<?= site_url('profile/edit') ?>" class="btn btn-primary">
                <i class="ti ti-edit me-1"></i> Edit Profil
            </a>
        </div>
    </div>

    <!-- Profile Card -->
    <div class="row">
        <div class="col-lg-4">
            <!-- Profile Information Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0 text-white">
                        <i class="ti ti-user me-2"></i> Informasi Profil
                    </h5>
                </div>
                <div class="card-body text-center">
                    <!-- Profile Photo -->
                    <div class="mb-4">
                        <?php
                        $userPhoto = !empty($user['foto_profil']) ? $user['foto_profil'] : 'user-1.jpg';
                        $photoPath = base_url('assets/images/profile/' . $userPhoto);
                        if ($userPhoto !== 'user-1.jpg' && file_exists(WRITEPATH . 'uploads/profile/' . $userPhoto)) {
                            $photoPath = base_url('uploads/profile/' . $userPhoto);
                        }
                        ?>
                        <img src="<?= $photoPath ?>" alt="<?= $user['nama_lengkap'] ?>"
                             class="rounded-circle shadow-sm"
                             style="width: 150px; height: 150px; object-fit: cover; object-position: center;">

                    </div>

                    <!-- User Information -->
                    <h4 class="mb-1"><?= $user['nama_lengkap'] ?></h4>
                    <p class="text-muted mb-3">
                        <span class="badge bg-<?= $user['hak_akses'] === 'ADMIN' ? 'primary' : 'success' ?>">
                            <?= ucfirst(strtolower($user['hak_akses'])) ?>
                        </span>
                    </p>

                    <div class="text-start">
                        <div class="mb-2">
                            <strong><i class="ti ti-user me-2"></i> Nama Pengguna:</strong><br>
                            <span class="text-muted"><?= $user['nama_pengguna'] ?></span>
                        </div>
                        <div class="mb-2">
                            <strong><i class="ti ti-calendar me-2"></i> Bergabung:</strong><br>
                            <span class="text-muted">
                                <?= isset($user['waktu_dibuat']) && $user['waktu_dibuat'] ? date('d M Y', strtotime($user['waktu_dibuat'])) : 'Tidak tersedia' ?>
                            </span>
                        </div>
                        <div class="mb-2">
                            <strong><i class="ti ti-clock me-2"></i> Terakhir Diubah:</strong><br>
                            <span class="text-muted">
                                <?= isset($user['waktu_diubah']) && $user['waktu_diubah'] ? date('d M Y H:i', strtotime($user['waktu_diubah'])) : 'Tidak tersedia' ?>
                            </span>
                        </div>
                        <div>
                            <strong><i class="ti ti-circle-check me-2"></i> Status:</strong><br>
                            <span class="badge bg-<?= isset($user['status']) && $user['status'] === 'AKTIF' ? 'success' : 'danger' ?>">
                                <?= isset($user['status']) && $user['status'] === 'AKTIF' ? 'Aktif' : 'Nonaktif' ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Quick Actions -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card border-left-primary shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="ti ti-edit text-primary" style="font-size: 2rem;"></i>
                                </div>
                                <div>
                                    <h6 class="card-title text-primary mb-1">Edit Profil</h6>
                                    <p class="card-text text-muted small mb-0">Ubah informasi profil dan foto</p>
                                </div>
                            </div>
                            <a href="<?= site_url('profile/edit') ?>" class="stretched-link"></a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card border-left-warning shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="ti ti-lock text-warning" style="font-size: 2rem;"></i>
                                </div>
                                <div>
                                    <h6 class="card-title text-warning mb-1">Ubah Kata Sandi</h6>
                                    <p class="card-text text-muted small mb-0">Perbarui kata sandi akun Anda</p>
                                </div>
                            </div>
                            <a href="#changePasswordModal" data-bs-toggle="modal" class="stretched-link"></a>
                        </div>
                    </div>
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
});
</script>
<?= $this->endSection() ?>
