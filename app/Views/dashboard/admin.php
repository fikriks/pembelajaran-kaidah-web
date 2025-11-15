<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Dashboard - <?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark">Dashboard Pembelajaran</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">Selamat datang di Sistem Pembelajaran Kaidah Bahasa Arab</li>
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
            'title' => 'Total Kaidah',
            'value' => $stats['total_materi'] ?? 0,
            'subtitle' => 'Materi tersedia',
            'icon' => 'book',
            'variant' => 'primary'
        ],
        [
            'title' => 'Total Soal',
            'value' => $stats['total_soal'] ?? 0,
            'subtitle' => 'Soal aktif',
            'icon' => 'file-text',
            'variant' => 'info'
        ],
        [
            'title' => 'Siswa Aktif',
            'value' => $stats['active_users'] ?? 0,
            'subtitle' => 'Pengguna terdaftar',
            'icon' => 'users',
            'variant' => 'success'
        ],
        [
            'title' => 'Sesi Selesai',
            'value' => $stats['completed_sessions'] ?? 0,
            'subtitle' => 'Pembelajaran selesai',
            'icon' => 'chart-line',
            'variant' => 'warning'
        ]
    ]
]) ?>

<!-- Learning Progress Overview -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-semibold mb-0">Statistik Pembelajaran</h5>
                    <select class="form-select form-select-sm" style="width: auto;">
                        <option value="7">7 Hari Terakhir</option>
                        <option value="30">30 Hari Terakhir</option>
                        <option value="90">3 Bulan Terakhir</option>
                    </select>
                </div>
                <div id="learning-chart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Tingkat Kesulitan Kaidah</h5>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Mudah</span>
                        <span class="fw-semibold"><?= $difficulty_stats['mudah']['percentage'] ?>% (<?= $difficulty_stats['mudah']['count'] ?>)</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: <?= $difficulty_stats['mudah']['percentage'] ?>%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Sedang</span>
                        <span class="fw-semibold"><?= $difficulty_stats['sedang']['percentage'] ?>% (<?= $difficulty_stats['sedang']['count'] ?>)</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-warning" style="width: <?= $difficulty_stats['sedang']['percentage'] ?>%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Sulit</span>
                        <span class="fw-semibold"><?= $difficulty_stats['sulit']['percentage'] ?>% (<?= $difficulty_stats['sulit']['count'] ?>)</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-danger" style="width: <?= $difficulty_stats['sulit']['percentage'] ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Progress Pembelajaran</h5>
                <div class="text-center">
                    <div class="position-relative d-inline-block">
                        <div class="progress-circle" style="width: 120px; height: 120px;">
                            <svg class="progress-ring" width="120" height="120">
                                <circle class="progress-ring-circle" stroke="#e9ecef" stroke-width="8" fill="transparent" r="52" cx="60" cy="60" />
                                <circle class="progress-ring-circle" stroke="#4CAF50" stroke-width="8" fill="transparent" r="52" cx="60" cy="60"
                                    stroke-dasharray="326.73" stroke-dashoffset="81.68" stroke-linecap="round"
                                    style="transform: rotate(-90deg); transform-origin: 50% 50%;" />
                            </svg>
                            <div class="progress-text">
                                <h4 class="mb-0 fw-bold">75%</h4>
                                <small class="text-muted">Completed</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Recent Learning Activities -->
<div class="row mb-4">
    <div class="col-lg-4 d-flex align-items-stretch">
        <div class="card border-0 shadow-sm w-100">
            <div class="card-body p-4">
                <div class="mb-4">
                    <h5 class="card-title fw-semibold">Aktivitas Terbaru</h5>
                </div>
                <ul class="timeline-widget mb-0 position-relative mb-n5">
                    <?php if (!empty($recent_sessions)): ?>
                        <?php foreach ($recent_sessions as $session): ?>
                            <li class="timeline-item d-flex position-relative overflow-hidden">
                                <div class="timeline-time text-dark flex-shrink-0 text-end"><?= date('H:i', strtotime($session['waktu_mulai'])) ?></div>
                                <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                    <span class="timeline-badge border-2 border border-<?= $session['status'] === 'selesai' ? 'success' : ($session['status'] === 'sedang_berjalan' ? 'info' : 'warning') ?> flex-shrink-0 my-8"></span>
                                    <span class="timeline-badge-border d-block flex-shrink-0"></span>
                                </div>
                                <div class="timeline-desc fs-3 text-dark mt-n1">
                                    <strong><?= esc($session['nama_siswa']) ?></strong>
                                    <?php if ($session['status'] === 'selesai'): ?>
                                        menyelesaikan kaidah <span class="text-primary"><?= esc($session['judul_kaidah']) ?></span>
                                    <?php else: ?>
                                        mulai belajar <span class="text-primary"><?= esc($session['judul_kaidah']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="timeline-item d-flex position-relative overflow-hidden">
                            <div class="timeline-desc fs-3 text-dark mt-n1 text-muted">
                                Belum ada aktivitas terbaru
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-8 d-flex align-items-stretch">
        <div class="card border-0 shadow-sm w-100">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4">Top Performers Siswa</h5>
                <div class="table-responsive">
                    <table class="table text-nowrap mb-0 align-middle">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">Peringkat</th>
                                <th class="border-bottom-0">Nama Siswa</th>
                                <th class="border-bottom-0">Kaidah Selesai</th>
                                <th class="border-bottom-0">Rata-rata Skor</th>
                                <th class="border-bottom-0">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($top_performers)): ?>
                                <?php $rank = 1; ?>
                                <?php foreach ($top_performers as $performer): ?>
                                    <tr>
                                        <td class="border-bottom-0">
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-<?= $rank == 1 ? 'warning' : ($rank == 2 ? 'secondary' : ($rank == 3 ? 'danger' : 'dark')) ?> rounded-3 fw-semibold me-2"><?= $rank ?></span>
                                                <?php if ($rank == 1): ?>
                                                    <i class="ti ti-trophy text-warning"></i>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="border-bottom-0">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                                    <i class="ti ti-user text-primary fs-4"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-semibold"><?= esc($performer['nama']) ?></h6>
                                                    <span class="text-muted small">Kelas <?= esc($performer['kelas']) ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="mb-0 fw-semibold"><?= $performer['total_sessions'] ?></h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-success rounded-3 fw-semibold"><?= number_format($performer['avg_score'], 1) ?></span>
                                            </div>
                                        </td>
                                        <td class="border-bottom-0">
                                            <span class="badge bg-success rounded-3 fw-semibold">Aktif</span>
                                        </td>
                                    </tr>
                                    <?php $rank++; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="border-bottom-0 text-center text-muted">
                                        Belum ada data performa siswa
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/libs/apexcharts/dist/apexcharts.min.js') ?>"></script>
<script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
<script>
// Initialize Learning Chart
document.addEventListener('DOMContentLoaded', function() {
    const learningChartElement = document.querySelector("#learning-chart");
    if (learningChartElement) {
        const learningChart = {
            series: [{
                name: "Sesi Belajar",
                data: [31, 40, 28, 51, 42, 109, 100]
            }, {
                name: "Kuis Selesai",
                data: [11, 32, 45, 32, 34, 52, 41]
            }],
            chart: {
                height: 350,
                type: 'area',
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            colors: ["#4CAF50", "#2196F3"],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.3,
                    opacityTo: 0.1,
                }
            },
            xaxis: {
                categories: ["Sen", "Sel", "Rab", "Kam", "Jum", "Sab", "Min"],
            },
            yaxis: {
                title: {
                    text: 'Jumlah Sesi'
                }
            },
            tooltip: {
                theme: 'light'
            }
        };

        const chart = new ApexCharts(learningChartElement, learningChart);
        chart.render();
    }
});

// Handle date range selector
document.addEventListener('DOMContentLoaded', function() {
    const dateSelect = document.querySelector('select[style*="width: auto"]');
    if (dateSelect) {
        dateSelect.addEventListener('change', function() {
            const days = this.value;
            // Here you can make an AJAX call to get data for selected period
            console.log('Load data for ' + days + ' days');
            // Reload chart with new data
        });
    }
});
</script>
<?= $this->endSection() ?>