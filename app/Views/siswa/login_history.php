<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Login History - <?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .stats-card {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
    }
    .login-item {
        border-left: 4px solid var(--primary-500);
        transition: all 0.3s ease;
    }
    .login-item:hover {
        background-color: var(--bs-gray-50);
        transform: translateX(4px);
    }
    .device-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    .time-badge {
        background: var(--primary-100);
        color: var(--primary-800);
        font-weight: 600;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark">Login History Siswa</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('siswa') ?>" class="text-muted">Manajemen Siswa</a></li>
                <li class="breadcrumb-item active">Login History</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="<?= site_url('siswa') ?>" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<!-- Student Info Card -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="ti ti-user text-primary fs-2"></i>
                    </div>
                    <div>
                        <h5 class="mb-1"><?= esc($siswa['nama_lengkap']) ?></h5>
                        <p class="mb-1 text-muted">
                            <strong>NIS:</strong> <?= esc($siswa['nis']) ?> |
                            <strong>Kelas:</strong> <?= esc($siswa['kelas']) ?> |
                            <strong>Status:</strong>
                            <span class="badge bg-<?= ($siswa['status'] === 'aktif') ? 'success' : 'secondary' ?> rounded-3">
                                <?= ucfirst($siswa['status']) ?>
                            </span>
                        </p>
                        <small class="text-muted">
                            <i class="ti ti-clock me-1"></i>
                            Total Login: <strong><?= count($loginHistory) ?> kali</strong>
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <div class="stats-card card border-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="ti ti-clock-history fs-3 me-2"></i>
                            <div>
                                <h6 class="mb-0">History</h6>
                                <h3 class="mb-0"><?= count($loginHistory) ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Login History List -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title mb-0">
                <i class="ti ti-list me-2"></i>Riwayat Login (50 Terakhir)
            </h5>
            <div>
                <button type="button" class="btn btn-primary btn-sm" onclick="refreshHistory()">
                    <i class="ti ti-refresh me-1"></i>Refresh
                </button>
                <button type="button" class="btn btn-success btn-sm" onclick="exportHistory()">
                    <i class="ti ti-download me-1"></i>Export
                </button>
            </div>
        </div>

        <?php if (!empty($loginHistory)): ?>
            <div class="login-history-list">
                <?php foreach ($loginHistory as $index => $login): ?>
                    <div class="login-item card mb-3 border-0 bg-light">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-md-1 text-center">
                                    <div class="time-badge rounded-3 d-inline-block px-2 py-1">
                                        #<?= $index + 1 ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-calendar-check text-success me-2"></i>
                                        <div>
                                            <strong><?= date('d M Y', strtotime($login['login_time'])) ?></strong>
                                            <br>
                                            <small class="text-muted"><?= date('H:i:s', strtotime($login['login_time'])) ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-device-laptop text-info me-2"></i>
                                        <div>
                                            <?php if ($login['device_info']): ?>
                                                <strong>Device:</strong>
                                                <span class="device-badge bg-info text-white rounded-2">
                                                    <?= esc($login['device_info']) ?>
                                                </span>
                                                <br>
                                            <?php else: ?>
                                                <strong>Device:</strong>
                                                <span class="text-muted">Tidak diketahui</span>
                                                <br>
                                            <?php endif; ?>

                                            <?php if ($login['ip_address']): ?>
                                                <small class="text-muted">
                                                    <i class="ti ti-world me-1"></i><?= esc($login['ip_address']) ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-end">
                                    <div class="time-badge rounded-3 d-inline-block">
                                        <i class="ti ti-clock-hour-4 me-1"></i>
                                         <?= time_ago($login['login_time']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="ti ti-clock-history fs-1 text-muted mb-3"></i>
                <h5 class="text-muted">Belum Ada History Login</h5>
                <p class="text-muted">Siswa ini belum pernah melakukan login ke aplikasi mobile</p>
                <div class="alert alert-info d-inline-block">
                    <i class="ti ti-info-circle me-2"></i>
                    History akan muncul saat siswa login melalui aplikasi mobile
                </div>
            </div>
        <?php endif; ?>

        <!-- Statistics Summary -->
        <?php if (!empty($loginHistory)): ?>
            <div class="row mt-4 pt-4 border-top">
                <div class="col-md-3">
                    <div class="text-center">
                        <h6 class="text-muted mb-2">Total Login</h6>
                        <h4 class="text-primary mb-0"><?= count($loginHistory) ?></h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h6 class="text-muted mb-2">Login Pertama</h6>
                        <h6 class="text-success mb-0">
                            <?= date('d M Y', strtotime($loginHistory[count($loginHistory) - 1]['login_time'])) ?>
                        </h6>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h6 class="text-muted mb-2">Login Terakhir</h6>
                        <h6 class="text-info mb-0">
                            <?= date('d M Y', strtotime($loginHistory[0]['login_time'])) ?>
                        </h6>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h6 class="text-muted mb-2">Rata-rata/Hari</h6>
                        <h6 class="text-warning mb-0">
                            <?php
                            $daysDiff = max(1, (strtotime($loginHistory[0]['login_time']) - strtotime($loginHistory[count($loginHistory) - 1]['login_time'])) / (60 * 60 * 24));
                            echo round(count($loginHistory) / $daysDiff, 1);
                            ?>
                        </h6>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function refreshHistory() {
    window.location.reload();
}

function exportHistory() {
    const studentName = '<?= esc($siswa['nama_lengkap']) ?>';
    const studentNIS = '<?= esc($siswa['nis']) ?>';
    const timestamp = new Date().toISOString().replace(/[:.]/g, '-');

    // Create CSV content
    let csvContent = "No,Tanggal Login,Waktu Login,Device Info,IP Address\n";

    <?php foreach ($loginHistory as $index => $login): ?>
    csvContent += "<?= $index + 1 ?>,<?= date('d/m/Y', strtotime($login['login_time'])) ?>,<?= date('H:i:s', strtotime($login['login_time'])) ?>,\"<?= esc($login['device_info'] ?? 'Tidak diketahui') ?>\",\"<?= esc($login['ip_address'] ?? '-') ?>\"\n";
    <?php endforeach; ?>

    // Create blob and download
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement("a");
    const url = URL.createObjectURL(blob);
    link.setAttribute("href", url);
    link.setAttribute("download", `Login_History_${studentNIS}_${studentName}_${timestamp}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Auto-refresh every 30 seconds (optional)
// setInterval(refreshHistory, 30000);
</script>
<?= $this->endSection() ?>