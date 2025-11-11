<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Detail Progress - <?= esc($siswa['nama_lengkap']) ?> - <?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= site_url('progress') ?>" class="text-muted">Progress Belajar</a></li>
            <li class="breadcrumb-item active">Detail Progress</li>
        </ol>
    </nav>
</div>

<!-- Student Info Card -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="ti ti-user text-primary fs-1"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold text-dark mb-1"><?= esc($siswa['nama_lengkap']) ?></h4>
                        <p class="text-muted mb-1">NIS: <?= esc($siswa['nis']) ?></p>
                        <p class="text-muted mb-0">Kelas: <?= esc($siswa['kelas']) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="<?= site_url('progress') ?>" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Progress Overview -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-primary">
            <div class="card-body text-center">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-block mb-2">
                    <i class="ti ti-progress text-primary fs-2"></i>
                </div>
                <h3 class="fw-bold text-primary mb-1"><?= $progress['completion_percentage'] ?>%</h3>
                <p class="text-muted mb-0">Progress Keseluruhan</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-success">
            <div class="card-body text-center">
                <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-block mb-2">
                    <i class="ti ti-check text-success fs-2"></i>
                </div>
                <h3 class="fw-bold text-success mb-1"><?= $progress['completed_kaidah'] ?></h3>
                <p class="text-muted mb-0">Kaidah Selesai</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-info">
            <div class="card-body text-center">
                <div class="rounded-circle bg-info bg-opacity-10 p-3 d-inline-block mb-2">
                    <i class="ti ti-clock text-info fs-2"></i>
                </div>
                <h3 class="fw-bold text-info mb-1"><?= $progress['completed_sessions'] ?></h3>
                <p class="text-muted mb-0">Sesi Selesai</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-warning">
            <div class="card-body text-center">
                <div class="rounded-circle bg-warning bg-opacity-10 p-3 d-inline-block mb-2">
                    <i class="ti ti-star text-warning fs-2"></i>
                </div>
                <h3 class="fw-bold text-warning mb-1"><?= $progress['average_score'] ?></h3>
                <p class="text-muted mb-0">Skor Rata-rata</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mb-4">
    <!-- Weekly Activity Chart -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-0 bg-white py-3">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="ti ti-chart-line text-primary me-2"></i>Aktivitas Mingguan
                </h5>
            </div>
            <div class="card-body">
                <canvas id="weeklyActivityChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Progress Distribution Chart -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-0 bg-white py-3">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="ti ti-chart-pie text-info me-2"></i>Distribusi Progress
                </h5>
            </div>
            <div class="card-body">
                <canvas id="progressDistributionChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Kaidah Progress Table -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header border-0 bg-white py-3">
        <h5 class="card-title mb-0 fw-bold">
            <i class="ti ti-book text-primary me-2"></i>Progress per Kaidah
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="text-dark">
                    <tr>
                        <th>No</th>
                        <th>Kaidah</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Terakhir Diakses</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($kaidahProgress)): ?>
                        <?php foreach ($kaidahProgress as $index => $kaidah): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td>
                                    <div class="fw-semibold"><?= esc($kaidah['judul_kaidah']) ?></div>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= character_limiter(esc($kaidah['deskripsi'] ?? ''), 50) ?>
                                    </small>
                                </td>
                                <td>
                                    <?php
                                    $statusColors = [
                                        'selesai' => 'success',
                                        'sedang_belajar' => 'warning',
                                        'belum_dimulai' => 'secondary'
                                    ];
                                    $color = $statusColors[$kaidah['status']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $color ?> rounded-3">
                                        <?= ucfirst(str_replace('_', ' ', $kaidah['status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 8px; width: 100px;">
                                            <div class="progress-bar bg-primary" style="width: <?= $kaidah['completion_percentage'] ?>%"></div>
                                        </div>
                                        <span class="small fw-semibold"><?= round($kaidah['completion_percentage'], 1) ?>%</span>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($kaidah['last_accessed']): ?>
                                        <small class="text-muted">
                                            <?= date('d/m H:i', strtotime($kaidah['last_accessed'])) ?>
                                        </small>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="ti ti-book-off fs-1 text-muted mb-2"></i>
                                <p class="text-muted">Belum ada progress kaidah</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Session History -->
<div class="card border-0 shadow-sm">
    <div class="card-header border-0 bg-white py-3">
        <h5 class="card-title mb-0 fw-bold">
            <i class="ti ti-history text-info me-2"></i>Riwayat Sesi Pembelajaran
        </h5>
    </div>
    <div class="card-body">
        <?php if (!empty($sessions)): ?>
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Waktu Mulai</th>
                            <th>Kaidah</th>
                            <th>Status</th>
                            <th>Durasi</th>
                            <th>Soal</th>
                            <th>Benar</th>
                            <th>Skor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sessions as $session): ?>
                            <tr>
                                <td>
                                    <small class="text-muted">
                                        <?= date('d/m H:i', strtotime($session['waktu_mulai'])) ?>
                                    </small>
                                </td>
                                <td><?= esc($session['judul_kaidah']) ?></td>
                                <td>
                                    <span class="badge bg-<?= ($session['sesi_status'] === 'selesai') ? 'success' : 'warning' ?> rounded-3">
                                        <?= ucfirst($session['sesi_status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($session['durasi_detik']): ?>
                                        <small><?= round($session['durasi_detik'] / 60) ?> menit</small>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $session['total_soal'] ?? 0 ?></td>
                                <td><?= $session['soal_benar'] ?? 0 ?></td>
                                <td>
                                    <?php if ($session['sesi_status'] === 'selesai'): ?>
                                        <span class="fw-bold text-primary"><?= round($session['skor'], 1) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-4">
                <i class="ti ti-history-off fs-1 text-muted mb-2"></i>
                <p class="text-muted">Belum ada riwayat sesi pembelajaran</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Weekly Activity Chart
    const weeklyActivityCtx = document.getElementById('weeklyActivityChart').getContext('2d');
    const weeklyActivityData = <?= json_encode($weeklyActivity) ?>;

    new Chart(weeklyActivityCtx, {
        type: 'bar',
        data: {
            labels: weeklyActivityData.map(item => item.day_name),
            datasets: [{
                label: 'Jumlah Sesi',
                data: weeklyActivityData.map(item => item.sessions),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Progress Distribution Chart
    const progressDistributionCtx = document.getElementById('progressDistributionChart').getContext('2d');

    new Chart(progressDistributionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Selesai', 'Sedang Belajar', 'Belum Dimulai'],
            datasets: [{
                data: [
                    <?= $progress['completed_kaidah'] ?>,
                    <?= $progress['in_progress_kaidah'] ?>,
                    <?= $progress['not_started_kaidah'] ?>
                ],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(108, 117, 125, 0.8)'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
<?= $this->endSection() ?>