<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Dashboard Guru - <?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Greeting Section -->
<div class="card border-0 shadow-sm mb-4 bg-gradient-primary">
    <div class="card-body py-4">
        <div class="row align-items-center">
            <div class="col-auto">
                <img src="<?= $userPhoto ?>" alt="Profile" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover; border: 4px solid rgba(255,255,255,0.2);">
            </div>
            <div class="col">
                <h2 class="mb-1 text-white fw-bold"><?= $greeting ?>, <?= $currentUser['nama_lengkap'] ?>!</h2>
                <p class="mb-0 text-white">Selamat datang kembali di dashboard Pembelajaran Kaidah Bahasa Arab</p>
                <div class="d-flex align-items-center mt-2">
                    <span class="badge bg-info bg-opacity-75 text-white me-2">
                        <i class="ti ti-user me-1"></i><?= $user_role_display ?>
                    </span>
                    <span class="text-white small">
                        <i class="ti ti-clock me-1"></i><?= date('d F Y H:i') ?>
                    </span>
                </div>
            </div>
            <div class="col-auto">
                <a href="<?= site_url('profile') ?>" class="btn btn-light btn-sm">
                    <i class="ti ti-user me-1"></i> Lihat Profil
                </a>
            </div>
        </div>
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

    <!-- Student Progress per Material -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="ti ti-user-check me-2"></i>Progress Materi Tiap Siswa
                </h5>
                
                <?php if (!empty($student_progress)): ?>
                    <div class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                        <?php 
                        $counter = 0;
                        foreach ($student_progress as $progress): 
                            if ($counter >= 8) break; // Limit to 8 items
                            $counter++;
                        ?>
                            <div class="list-group-item border-0 px-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><?= esc($progress['nama_siswa']) ?></h6>
                                        <small class="text-muted">
                                            <?= esc($progress['kelas']) ?> • <?= esc($progress['judul_kaidah']) ?>
                                        </small>
                                        <div class="progress mb-1" style="height: 6px;">
                                            <div class="progress-bar bg-success" 
                                                 style="width: <?= $progress['progress_percent'] ?>%">
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            Skor Terbaik: <?= $progress['best_score'] ?>% • 
                                            Sesi: <?= $progress['total_sessions'] ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($student_progress) > 8): ?>
                        <div class="text-center mt-2">
                            <small class="text-muted">
                                Menampilkan 8 dari <?= count($student_progress) ?> progress siswa
                            </small>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="ti ti-user-check fs-1 text-muted mb-3"></i>
                        <p class="text-muted mb-0">Belum ada data progress siswa</p>
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