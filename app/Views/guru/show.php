<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Detail Guru - <?= $this->endSection() ?>

<?= $this->section('styles') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark">Detail Guru</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('guru') ?>">Guru</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= site_url('guru') ?>" class="btn btn-secondary me-2">
                <i class="ti ti-arrow-left me-2"></i>Kembali
            </a>
            <a href="<?= site_url('guru/' . $guru['id_pengguna'] . '/edit') ?>" class="btn btn-primary">
                <i class="ti ti-edit me-2"></i>Edit Guru
            </a>
        </div>
    </div>

    <!-- Guru Profile Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row">
                <!-- Profile Section -->
                <div class="col-md-4 text-center">
                    <div class="avatar-xxl bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                        <i class="ti ti-school" style="font-size: 6rem;"></i>
                    </div>
                    <h4 class="fw-bold"><?= esc($guru['nama_lengkap']) ?></h4>
                    <p class="text-muted mb-2">@<?= esc($guru['nama_pengguna']) ?></p>

                    <!-- Badges -->
                    <div class="mb-3">
                        <span class="badge bg-info rounded-3 fs-6">Guru</span>
                        <?php if ($guru['status'] === 'AKTIF'): ?>
                            <span class="badge bg-success rounded-3 fs-6">Aktif</span>
                        <?php else: ?>
                            <span class="badge bg-secondary rounded-3 fs-6">Nonaktif</span>
                        <?php endif; ?>
                    </div>

                    <!-- Quick Actions -->
                    <div class="d-grid gap-2">
                        <a href="<?= site_url('guru/' . $guru['id_pengguna'] . '/edit') ?>" class="btn btn-primary btn-sm">
                            <i class="ti ti-edit me-2"></i>Edit Profil
                        </a>
                        <?php if ($guru['id_pengguna'] != session()->get('user_id')): ?>
                        <button type="button" class="btn btn-warning btn-sm" onclick="toggleStatus()">
                            <i class="ti ti-toggle-<?= $guru['status'] === 'AKTIF' ? 'left' : 'right' ?> me-2"></i>
                            <?= $guru['status'] === 'AKTIF' ? 'Nonaktifkan' : 'Aktifkan' ?>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Information Section -->
                <div class="col-md-8">
                    <ul class="nav nav-tabs mb-4" id="guruTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                                <i class="ti ti-user me-2"></i>Informasi Pribadi
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="system-tab" data-bs-toggle="tab" data-bs-target="#system" type="button" role="tab">
                                <i class="ti ti-settings me-2"></i>Informasi Sistem
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="teaching-tab" data-bs-toggle="tab" data-bs-target="#teaching" type="button" role="tab">
                                <i class="ti ti-school me-2"></i>Mengajar
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab">
                                <i class="ti ti-history me-2"></i>Aktivitas
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="guruTabContent">
                        <!-- Personal Information Tab -->
                        <div class="tab-pane fade show active" id="info" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Nama Lengkap</label>
                                        <h5 class="fw-semibold"><?= esc($guru['nama_lengkap']) ?></h5>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Username</label>
                                        <h5 class="fw-semibold"><?= esc($guru['nama_pengguna']) ?></h5>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Email</label>
                                        <h5 class="fw-semibold"><?= esc($guru['email'] ?? 'Tidak ada') ?></h5>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Hak Akses</label>
                                        <h5 class="fw-semibold">
                                            <span class="badge bg-info">Guru</span>
                                        </h5>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Status Akun</label>
                                        <h5 class="fw-semibold">
                                            <?php if ($guru['status'] === 'AKTIF'): ?>
                                                <span class="badge bg-success">Aktif</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            <?php endif; ?>
                                        </h5>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Guru ID</label>
                                        <h5 class="fw-semibold">#<?= $guru['id_pengguna'] ?></h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Information Tab -->
                        <div class="tab-pane fade" id="system" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Dibuat Pada</label>
                                        <h5 class="fw-semibold"><?= format_date_time($guru['waktu_dibuat']) ?></h5>
                                        <small class="text-muted"><?= time_ago($guru['waktu_dibuat']) ?></small>
                                    </div>
                                    <?php if (!empty($guru['waktu_diubah'])): ?>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Terakhir Diubah</label>
                                        <h5 class="fw-semibold"><?= format_date_time($guru['waktu_diubah']) ?></h5>
                                        <small class="text-muted"><?= time_ago($guru['waktu_diubah']) ?></small>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Akses Guru</label>
                                        <div class="border rounded p-3">
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-2"><i class="ti ti-check text-success me-2"></i>Kelola materi kaidah</li>
                                                <li class="mb-2"><i class="ti ti-check text-success me-2"></i>Kelola soal pembelajaran</li>
                                                <li class="mb-2"><i class="ti ti-check text-success me-2"></i>Lihat progress siswa</li>
                                                <li class="mb-2"><i class="ti ti-check text-success me-2"></i>Akses laporan pembelajaran</li>
                                                <li class="mb-0"><i class="ti ti-x text-danger me-2"></i>Kelola pengguna (admin only)</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Teaching Information Tab -->
                        <div class="tab-pane fade" id="teaching" role="tabpanel">
                            <div class="text-center py-5">
                                <i class="ti ti-book fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">Informasi Mengajar</h5>
                                <p class="text-muted">Data mata pelajaran dan jadwal mengajar guru akan segera tersedia</p>
                                <div class="alert alert-info">
                                    <i class="ti ti-info-circle me-2"></i>
                                    Fitur informasi mengajar sedang dalam pengembangan
                                </div>
                            </div>
                        </div>

                        <!-- Activity Tab -->
                        <div class="tab-pane fade" id="activity" role="tabpanel">
                            <div class="text-center py-5">
                                <i class="ti ti-clock fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">Aktivitas Guru</h5>
                                <p class="text-muted">Riwayat aktivitas guru akan segera tersedia</p>
                                <div class="alert alert-info">
                                    <i class="ti ti-info-circle me-2"></i>
                                    Fitur tracking aktivitas sedang dalam pengembangan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary bg-light">
                <div class="card-body text-center">
                    <i class="ti ti-calendar text-primary mb-2" style="font-size: 5rem;"></i>
                    <h4 class="fw-bold"><?= calculate_days_since($guru['waktu_dibuat']) ?></h4>
                    <p class="text-muted mb-0">Hari Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success bg-light">
                <div class="card-body text-center">
                    <i class="ti ti-shield-check text-success mb-2" style="font-size: 5rem;"></i>
                    <h4 class="fw-bold">
                        <?php if ($guru['status'] === 'AKTIF'): ?>
                            Aktif
                        <?php else: ?>
                            Nonaktif
                        <?php endif; ?>
                    </h4>
                    <p class="text-muted mb-0">Status Saat Ini</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info bg-light">
                <div class="card-body text-center">
                    <i class="ti ti-school text-info mb-2" style="font-size: 5rem;"></i>
                    <h4 class="fw-bold">GURU</h4>
                    <p class="text-muted mb-0">Hak Akses</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning bg-light">
                <div class="card-body text-center">
                    <i class="ti ti-clock text-warning mb-2" style="font-size: 5rem;"></i>
                    <h4 class="fw-bold">
                        <?= date('H:i', strtotime($guru['waktu_dibuat'])) ?>
                    </h4>
                    <p class="text-muted mb-0">Waktu Daftar</p>
                </div>
            </div>
        </div>
    </div>

  </div>

<script>
// Toggle guru status
function toggleStatus() {
    const currentStatus = '<?= $guru['status'] ?>';
    const newStatus = currentStatus === 'AKTIF' ? 'NONAKTIF' : 'AKTIF';
    const actionText = newStatus === 'AKTIF' ? 'mengaktifkan' : 'menonaktifkan';

    toast.confirm(
        `Apakah Anda yakin ingin ${actionText} guru ini?`,
        function() {
            // Show loading
            const loading = toast.loading('Mengubah status...');

            fetch(`<?= site_url('guru/') ?><?= $guru['id_pengguna'] ?>/toggleStatus`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    toast.success('Status guru berhasil diperbarui!');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    toast.error(data.message || 'Gagal mengubah status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toast.error('Terjadi kesalahan saat mengubah status');
            })
            .finally(() => {
                // Dismiss loading
                loading.dismiss();
            });
        }
    );
}

// Helper functions are now defined in PHP section

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips if available
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // E to edit
        if (e.key === 'e' && !e.ctrlKey && !e.metaKey && !e.altKey) {
            if (document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
                e.preventDefault();
                window.location.href = '<?= site_url('guru/edit/' . $guru['id_pengguna']) ?>';
            }
        }

        // Escape to go back
        if (e.key === 'Escape') {
            window.location.href = '<?= site_url('guru') ?>';
        }
    });
});
</script>

<style>
.avatar-xl {
    width: 120px;
    height: 120px;
}

.avatar-xxl {
    width: 180px;
    height: 180px;
}

.nav-tabs .nav-link {
    color: #6c757d;
    border-bottom: 2px solid transparent;
}

.nav-tabs .nav-link.active {
    color: #4CAF50;
    border-bottom-color: #4CAF50;
}

.nav-tabs .nav-link:hover {
    color: #4CAF50;
    border-bottom-color: #4CAF50;
}

.tab-content {
    min-height: 300px;
}

.card.border-primary {
    border-left: 4px solid #007bff !important;
}

.card.border-success {
    border-left: 4px solid #28a745 !important;
}

.card.border-info {
    border-left: 4px solid #17a2b8 !important;
}

.card.border-warning {
    border-left: 4px solid #ffc107 !important;
}
</style>
<?= $this->endSection() ?>