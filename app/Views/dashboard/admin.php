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
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="ti ti-book fs-4 text-primary"></i>
                        </div>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Total Kaidah</h6>
                        <h3 class="mb-0 fw-bold">24</h3>
                        <small class="text-success">
                            <i class="ti ti-arrow-up"></i> 12% dari bulan lalu
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="ti ti-file-text fs-4 text-info"></i>
                        </div>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Total Soal</h6>
                        <h3 class="mb-0 fw-bold">156</h3>
                        <small class="text-success">
                            <i class="ti ti-arrow-up"></i> 8% dari bulan lalu
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="ti ti-users fs-4 text-success"></i>
                        </div>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Siswa Aktif</h6>
                        <h3 class="mb-0 fw-bold">89</h3>
                        <small class="text-success">
                            <i class="ti ti-arrow-up"></i> 23% dari bulan lalu
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="ti ti-chart-line fs-4 text-warning"></i>
                        </div>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Rata-rata Skor</h6>
                        <h3 class="mb-0 fw-bold">78.5</h3>
                        <small class="text-success">
                            <i class="ti ti-arrow-up"></i> 5.2% dari bulan lalu
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                        <span class="fw-semibold">45%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: 45%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Sedang</span>
                        <span class="fw-semibold">35%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-warning" style="width: 35%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Sulit</span>
                        <span class="fw-semibold">20%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-danger" style="width: 20%"></div>
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
                                <circle class="progress-ring-circle" stroke="#e9ecef" stroke-width="8" fill="transparent" r="52" cx="60" cy="60"/>
                                <circle class="progress-ring-circle" stroke="#4CAF50" stroke-width="8" fill="transparent" r="52" cx="60" cy="60"
                                        stroke-dasharray="326.73" stroke-dashoffset="81.68" stroke-linecap="round"
                                        style="transform: rotate(-90deg); transform-origin: 50% 50%;"/>
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
                    <li class="timeline-item d-flex position-relative overflow-hidden">
                        <div class="timeline-time text-dark flex-shrink-0 text-end">10:30</div>
                        <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                            <span class="timeline-badge border-2 border border-success flex-shrink-0 my-8"></span>
                            <span class="timeline-badge-border d-block flex-shrink-0"></span>
                        </div>
                        <div class="timeline-desc fs-3 text-dark mt-n1">
                            <strong>Ahmad Rizki</strong> menyelesaikan kaidah <span class="text-primary">Isim Mufrad</span>
                        </div>
                    </li>
                    <li class="timeline-item d-flex position-relative overflow-hidden">
                        <div class="timeline-time text-dark flex-shrink-0 text-end">09:45</div>
                        <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                            <span class="timeline-badge border-2 border border-info flex-shrink-0 my-8"></span>
                            <span class="timeline-badge-border d-block flex-shrink-0"></span>
                        </div>
                        <div class="timeline-desc fs-3 text-dark mt-n1 fw-semibold">
                            <strong>Siti Nurhaliza</strong> mulai belajar <span class="text-primary">Fi'il Madhi</span>
                        </div>
                    </li>
                    <li class="timeline-item d-flex position-relative overflow-hidden">
                        <div class="timeline-time text-dark flex-shrink-0 text-end">09:15</div>
                        <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                            <span class="timeline-badge border-2 border border-warning flex-shrink-0 my-8"></span>
                            <span class="timeline-badge-border d-block flex-shrink-0"></span>
                        </div>
                        <div class="timeline-desc fs-3 text-dark mt-n1">
                            <strong>Budi Santoso</strong> mencapai skor <span class="text-success">95</span> pada quiz <span class="text-primary">Harf Jar</span>
                        </div>
                    </li>
                    <li class="timeline-item d-flex position-relative overflow-hidden">
                        <div class="timeline-time text-dark flex-shrink-0 text-end">08:30</div>
                        <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                            <span class="timeline-badge border-2 border border-primary flex-shrink-0 my-8"></span>
                            <span class="timeline-badge-border d-block flex-shrink-0"></span>
                        </div>
                        <div class="timeline-desc fs-3 text-dark mt-n1 fw-semibold">
                            Kaidah baru ditambahkan: <span class="text-primary">Mushabarakah</span>
                        </div>
                    </li>
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
                            <tr>
                                <td class="border-bottom-0">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-warning rounded-3 fw-semibold me-2">1</span>
                                        <i class="ti ti-trophy text-warning"></i>
                                    </div>
                                </td>
                                <td class="border-bottom-0">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                            <i class="ti ti-user text-primary fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold">Ahmad Rizki</h6>
                                            <span class="text-muted small">Kelas XI-A</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="border-bottom-0">
                                    <h6 class="mb-0 fw-semibold">18/24</h6>
                                </td>
                                <td class="border-bottom-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-success rounded-3 fw-semibold">92.5</span>
                                    </div>
                                </td>
                                <td class="border-bottom-0">
                                    <span class="badge bg-success rounded-3 fw-semibold">Aktif</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom-0">
                                    <span class="badge bg-secondary rounded-3 fw-semibold">2</span>
                                </td>
                                <td class="border-bottom-0">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-info bg-opacity-10 p-2 me-2">
                                            <i class="ti ti-user text-info fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold">Siti Nurhaliza</h6>
                                            <span class="text-muted small">Kelas XI-B</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="border-bottom-0">
                                    <h6 class="mb-0 fw-semibold">16/24</h6>
                                </td>
                                <td class="border-bottom-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-info rounded-3 fw-semibold">88.3</span>
                                    </div>
                                </td>
                                <td class="border-bottom-0">
                                    <span class="badge bg-success rounded-3 fw-semibold">Aktif</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom-0">
                                    <span class="badge bg-danger rounded-3 fw-semibold">3</span>
                                </td>
                                <td class="border-bottom-0">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-success bg-opacity-10 p-2 me-2">
                                            <i class="ti ti-user text-success fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold">Budi Santoso</h6>
                                            <span class="text-muted small">Kelas X-A</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="border-bottom-0">
                                    <h6 class="mb-0 fw-semibold">15/24</h6>
                                </td>
                                <td class="border-bottom-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-primary rounded-3 fw-semibold">85.7</span>
                                    </div>
                                </td>
                                <td class="border-bottom-0">
                                    <span class="badge bg-success rounded-3 fw-semibold">Aktif</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-bottom-0">
                                    <span class="badge bg-dark rounded-3 fw-semibold">4</span>
                                </td>
                                <td class="border-bottom-0">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-warning bg-opacity-10 p-2 me-2">
                                            <i class="ti ti-user text-warning fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold">Dewi Lestari</h6>
                                            <span class="text-muted small">Kelas XI-C</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="border-bottom-0">
                                    <h6 class="mb-0 fw-semibold">14/24</h6>
                                </td>
                                <td class="border-bottom-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-warning rounded-3 fw-semibold">82.1</span>
                                    </div>
                                </td>
                                <td class="border-bottom-0">
                                    <span class="badge bg-warning rounded-3 fw-semibold">Less Active</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Popular Kaidah -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4">Kaidah Populer Minggu Ini</h5>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 border-0 bg-light">
                            <div class="card-body text-center">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-block mb-3">
                                    <i class="ti ti-book fs-4 text-primary"></i>
                                </div>
                                <h6 class="card-title fw-semibold">Isim Mufrad</h6>
                                <p class="text-muted small mb-2">Kata benda tunggal dalam bahasa Arab</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success rounded-3 fw-semibold">45 siswa</span>
                                    <small class="text-muted">92% success</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 border-0 bg-light">
                            <div class="card-body text-center">
                                <div class="rounded-circle bg-info bg-opacity-10 p-3 d-inline-block mb-3">
                                    <i class="ti ti-book fs-4 text-info"></i>
                                </div>
                                <h6 class="card-title fw-semibold">Fi'il Madhi</h6>
                                <p class="text-muted small mb-2">Kata kerja bentuk lampau dalam bahasa Arab</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success rounded-3 fw-semibold">38 siswa</span>
                                    <small class="text-muted">88% success</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 border-0 bg-light">
                            <div class="card-body text-center">
                                <div class="rounded-circle bg-warning bg-opacity-10 p-3 d-inline-block mb-3">
                                    <i class="ti ti-book fs-4 text-warning"></i>
                                </div>
                                <h6 class="card-title fw-semibold">Harf Jar</h6>
                                <p class="text-muted small mb-2">Huruf jar dan penggunaannya dalam kalimat</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-warning rounded-3 fw-semibold">32 siswa</span>
                                    <small class="text-muted">75% success</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 border-0 bg-light">
                            <div class="card-body text-center">
                                <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-block mb-3">
                                    <i class="ti ti-book fs-4 text-success"></i>
                                </div>
                                <h6 class="card-title fw-semibold">Mushabarakah</h6>
                                <p class="text-muted small mb-2">Kata sifat yang mengikuti kata yang disifati</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-info rounded-3 fw-semibold">28 siswa</span>
                                    <small class="text-muted">81% success</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>