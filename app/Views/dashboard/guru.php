<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Dashboard Guru - <?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark">Dashboard Guru</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">Selamat datang di Dashboard Guru Pembelajaran Kaidah Bahasa Arab</li>
            </ol>
        </nav>
    </div>
    <div>
        <span class="text-muted"> <?= date('d F Y H:i') ?></span>
    </div>
</div>

<!-- Statistics Cards -->
<?= view('partials/stats_row', [
    'stats' => [
        [
            'title' => 'Materi Saya',
            'value' => $stats['total_materi'] ?? 0,
            'subtitle' => 'Materi yang dibuat',
            'icon' => 'book',
            'variant' => 'primary'
        ],
        [
            'title' => 'Soal Saya',
            'value' => $stats['total_soal'] ?? 0,
            'subtitle' => 'Soal yang dibuat',
            'icon' => 'file-text',
            'variant' => 'info'
        ],
        [
            'title' => 'Total Sesi',
            'value' => $stats['total_sessions'] ?? 0,
            'subtitle' => 'Sesi pembelajaran',
            'icon' => 'chart-line',
            'variant' => 'success'
        ],
        [
            'title' => 'Sesi Materi Saya',
            'value' => $stats['my_material_sessions'] ?? 0,
            'subtitle' => 'Sesi pada materi saya',
            'icon' => 'users',
            'variant' => 'warning'
        ]
    ]
]) ?>

<div class="row">
    <!-- Recent Sessions -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-clock me-2"></i>Sesi Terbaru
                    </h5>
                    <a href="<?= site_url('progress') ?>" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                
                <?php if (!empty($recent_sessions)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recent_sessions as $session): ?>
                            <div class="list-group-item border-0 px-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?= esc($session['judul_kaidah']) ?></h6>
                                        <small class="text-muted">
                                            <?= date('d M Y H:i', strtotime($session['waktu_mulai'])) ?>
                                            <?php if ($session['status'] === 'selesai'): ?>
                                                • <span class="text-success">Selesai</span>
                                            <?php else: ?>
                                                • <span class="text-warning">Berjalan</span>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary rounded-3">
                                            <?= $session['skor'] ?? 0 ?>%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="ti ti-clock-history fs-1 text-muted mb-3"></i>
                        <p class="text-muted mb-0">Belum ada sesi pembelajaran</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Material Performance -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="ti ti-chart-bar me-2"></i>Performance Materi
                </h5>
                
                <?php if (!empty($material_performance)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($material_performance as $material): ?>
                            <div class="list-group-item border-0 px-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><?= esc($material['judul_kaidah']) ?></h6>
                                        <div class="progress mb-1" style="height: 6px;">
                                            <div class="progress-bar bg-success" 
                                                 style="width: <?= min(100, $material['average_score'] ?? 0) ?>%">
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            Rata-rata: <?= round($material['average_score'] ?? 0, 1) ?>% • 
                                            Sesi: <?= $material['total_sessions'] ?? 0 ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="ti ti-chart-bar fs-1 text-muted mb-3"></i>
                        <p class="text-muted mb-0">Belum ada data performance</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- My Materials -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0">
                <i class="ti ti-book me-2"></i>Materi Saya
            </h5>
            <a href="<?= site_url('kaidah') ?>" class="btn btn-sm btn-outline-primary">
                Kelola Materi
            </a>
        </div>
        
        <?php if (!empty($my_materials)): ?>
            <div class="row">
                <?php foreach ($my_materials as $material): ?>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="card-title"><?= esc($material['judul_kaidah']) ?></h6>
                                <p class="card-text text-muted small">
                                    <?= esc(substr($material['deskripsi'] ?? 'Tidak ada deskripsi', 0, 100)) ?>...
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        Urutan: <?= $material['urutan'] ?>
                                    </small>
                                    <span class="badge bg-success rounded-3">
                                        Aktif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-4">
                <i class="ti ti-book fs-1 text-muted mb-3"></i>
                <p class="text-muted mb-2">Belum ada materi yang dibuat</p>
                <a href="<?= site_url('kaidah/create') ?>" class="btn btn-primary btn-sm">
                    <i class="ti ti-plus me-1"></i>Tambah Materi
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Activity chart for guru dashboard
const activityData = <?= json_encode($activity_data ?? []) ?>;

if (activityData && activityData.length > 0) {
    // Initialize activity chart here if needed
    console.log('Activity data loaded:', activityData);
}
</script>
<?= $this->endSection() ?>