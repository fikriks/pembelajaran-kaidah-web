<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Detail Pengguna - <?= $this->endSection() ?>

<?= $this->section('styles') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark">Detail Pengguna</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('pengguna') ?>">Pengguna</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= site_url('pengguna') ?>" class="btn btn-secondary me-2">
                <i class="ti ti-arrow-left me-2"></i>Kembali
            </a>
            <a href="<?= site_url('pengguna/edit/' . $user['id_pengguna']) ?>" class="btn btn-primary">
                <i class="ti ti-edit me-2"></i>Edit Pengguna
            </a>
        </div>
    </div>

    <!-- User Profile Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row">
                <!-- Profile Section -->
                <div class="col-md-4 text-center">
                    <div class="avatar-xl bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                        <i class="ti ti-user fs-1"></i>
                    </div>
                    <h4 class="fw-bold"><?= esc($user['nama_lengkap']) ?></h4>
                    <p class="text-muted mb-2">@<?= esc($user['nama_pengguna']) ?></p>

                    <!-- Badges -->
                    <div class="mb-3">
                        <?php if ($user['hak_akses'] === 'ADMIN'): ?>
                            <span class="badge bg-danger rounded-3 fs-6">Administrator</span>
                        <?php else: ?>
                            <span class="badge bg-info rounded-3 fs-6">Guru</span>
                        <?php endif; ?>
                        <?php if ($user['status'] === 'AKTIF'): ?>
                            <span class="badge bg-success rounded-3 fs-6">Aktif</span>
                        <?php else: ?>
                            <span class="badge bg-secondary rounded-3 fs-6">Nonaktif</span>
                        <?php endif; ?>
                    </div>

                    <!-- Quick Actions -->
                    <div class="d-grid gap-2">
                        <a href="<?= site_url('pengguna/edit/' . $user['id_pengguna']) ?>" class="btn btn-primary btn-sm">
                            <i class="ti ti-edit me-2"></i>Edit Profil
                        </a>
                        <?php if ($user['id_pengguna'] != session()->get('user_id')): ?>
                        <button type="button" class="btn btn-warning btn-sm" onclick="toggleStatus()">
                            <i class="ti ti-toggle-<?= $user['status'] === 'AKTIF' ? 'left' : 'right' ?> me-2"></i>
                            <?= $user['status'] === 'AKTIF' ? 'Nonaktifkan' : 'Aktifkan' ?>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Information Section -->
                <div class="col-md-8">
                    <ul class="nav nav-tabs mb-4" id="userTab" role="tablist">
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
                            <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab">
                                <i class="ti ti-history me-2"></i>Aktivitas
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="userTabContent">
                        <!-- Personal Information Tab -->
                        <div class="tab-pane fade show active" id="info" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Nama Lengkap</label>
                                        <h5 class="fw-semibold"><?= esc($user['nama_lengkap']) ?></h5>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Username</label>
                                        <h5 class="fw-semibold"><?= esc($user['nama_pengguna']) ?></h5>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Email</label>
                                        <h5 class="fw-semibold"><?= esc($user['email'] ?? 'Tidak ada') ?></h5>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Hak Akses</label>
                                        <h5 class="fw-semibold">
                                            <?php if ($user['hak_akses'] === 'ADMIN'): ?>
                                                <span class="badge bg-danger">Administrator</span>
                                            <?php else: ?>
                                                <span class="badge bg-info">Guru</span>
                                            <?php endif; ?>
                                        </h5>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Status Akun</label>
                                        <h5 class="fw-semibold">
                                            <?php if ($user['status'] === 'AKTIF'): ?>
                                                <span class="badge bg-success">Aktif</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            <?php endif; ?>
                                        </h5>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">User ID</label>
                                        <h5 class="fw-semibold">#<?= $user['id_pengguna'] ?></h5>
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
                                        <h5 class="fw-semibold"><?= date('d M Y H:i:s', strtotime($user['waktu_dibuat'])) ?></h5>
                                        <small class="text-muted"><?= time_ago($user['waktu_dibuat']) ?></small>
                                    </div>
                                    <?php if (!empty($user['waktu_diubah'])): ?>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Terakhir Diubah</label>
                                        <h5 class="fw-semibold"><?= date('d M Y H:i:s', strtotime($user['waktu_diubah'])) ?></h5>
                                        <small class="text-muted"><?= time_ago($user['waktu_diubah']) ?></small>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Role Permissions</label>
                                        <div class="border rounded p-3">
                                            <?php if ($user['hak_akses'] === 'ADMIN'): ?>
                                                <ul class="list-unstyled mb-0">
                                                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i>Kelola semua pengguna</li>
                                                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i>Kelola data siswa</li>
                                                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i>Kelola materi kaidah</li>
                                                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i>Kelola soal</li>
                                                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i>Lihat semua laporan</li>
                                                    <li class="mb-0"><i class="ti ti-check text-success me-2"></i>Akses sistem penuh</li>
                                                </ul>
                                            <?php else: ?>
                                                <ul class="list-unstyled mb-0">
                                                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i>Kelola materi kaidah</li>
                                                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i>Kelola soal</li>
                                                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i>Lihat progress siswa</li>
                                                    <li class="mb-0"><i class="ti ti-x text-danger me-2"></i>Kelola pengguna (tidak bisa)</li>
                                                </ul>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Tab -->
                        <div class="tab-pane fade" id="activity" role="tabpanel">
                            <div class="text-center py-5">
                                <i class="ti ti-clock fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">Aktivitas Pengguna</h5>
                                <p class="text-muted">Riwayat aktivitas pengguna akan segera tersedia</p>
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
                    <i class="ti ti-calendar text-primary fs-2 mb-2"></i>
                    <h4 class="fw-bold"><?= calculate_days_since($user['waktu_dibuat']) ?></h4>
                    <p class="text-muted mb-0">Hari Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success bg-light">
                <div class="card-body text-center">
                    <i class="ti ti-shield-check text-success fs-2 mb-2"></i>
                    <h4 class="fw-bold">
                        <?php if ($user['status'] === 'AKTIF'): ?>
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
                    <i class="ti ti-user-shield text-info fs-2 mb-2"></i>
                    <h4 class="fw-bold"><?= $user['hak_akses'] ?></h4>
                    <p class="text-muted mb-0">Hak Akses</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning bg-light">
                <div class="card-body text-center">
                    <i class="ti ti-clock text-warning fs-2 mb-2"></i>
                    <h4 class="fw-bold">
                        <?= date('H:i', strtotime($user['waktu_dibuat'])) ?>
                    </h4>
                    <p class="text-muted mb-0">Waktu Daftar</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="card border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Tindakan Cepat</h5>
                    <div class="d-grid gap-2 d-md-flex">
                        <a href="<?= site_url('pengguna/edit/' . $user['id_pengguna']) ?>" class="btn btn-primary">
                            <i class="ti ti-edit me-2"></i>Edit Pengguna
                        </a>
                        <?php if ($user['id_pengguna'] != session()->get('user_id')): ?>
                        <button type="button" class="btn btn-warning" onclick="toggleStatus()">
                            <i class="ti ti-toggle-<?= $user['status'] === 'AKTIF' ? 'left' : 'right' ?> me-2"></i>
                            <?= $user['status'] === 'AKTIF' ? 'Nonaktifkan' : 'Aktifkan' ?>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Navigasi</h5>
                    <div class="d-grid gap-2 d-md-flex">
                        <a href="<?= site_url('pengguna') ?>" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-2"></i>Kembali ke Daftar
                        </a>
                        <a href="<?= site_url('dashboard') ?>" class="btn btn-info">
                            <i class="ti ti-layout-dashboard me-2"></i>Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle user status
function toggleStatus() {
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

// Helper functions
function time_ago($datetime) {
    // Simple time ago function - replace with actual implementation
    return $datetime ? 'Beberapa waktu lalu' : 'Tidak ada data';
}

function calculate_days_since($datetime) {
    if (!$datetime) return 0;
    $created = strtotime($datetime);
    $today = time();
    $days = floor(($today - $created) / (60 * 60 * 24));
    return max(1, $days);
}

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
                window.location.href = '<?= site_url('pengguna/edit/' . $user['id_pengguna']) ?>';
            }
        }

        // Escape to go back
        if (e.key === 'Escape') {
            window.location.href = '<?= site_url('pengguna') ?>';
        }
    });
});
</script>

<style>
.avatar-xl {
    width: 120px;
    height: 120px;
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