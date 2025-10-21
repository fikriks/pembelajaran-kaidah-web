<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<!--  Row 1 -->
<div class="row">
  <div class="col-lg-8 d-flex align-items-strech">
    <div class="card w-100">
      <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
          <div class="mb-3 mb-sm-0">
            <h5 class="card-title fw-semibold">Statistik Pembelajaran</h5>
          </div>
          <div>
            <select class="form-select">
              <option value="1">Bulan Ini</option>
              <option value="2">Bulan Lalu</option>
              <option value="3">3 Bulan Terakhir</option>
              <option value="4">Tahun Ini</option>
            </select>
          </div>
        </div>
        <div id="sales-overview"></div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="row">
      <div class="col-lg-12">
        <!-- Yearly Breakup -->
        <div class="card overflow-hidden">
          <div class="card-body p-4">
            <h5 class="card-title mb-9 fw-semibold">Total Sesi</h5>
            <div class="row align-items-center">
              <div class="col-8">
                <h4 class="fw-semibold mb-3"><?= number_format($stats['total_sessions']) ?></h4>
                <div class="d-flex align-items-center mb-3">
                  <span
                    class="me-1 rounded-circle bg-light-success round-20 d-flex align-items-center justify-content-center">
                    <i class="ti ti-arrow-up-left text-success"></i>
                  </span>
                  <p class="text-dark me-1 fs-3 mb-0">+<?= number_format($stats['completed_sessions']) ?> selesai</p>
                  <p class="fs-3 mb-0">selesai</p>
                </div>
                <div class="d-flex align-items-center">
                  <div class="me-4">
                    <span class="round-8 bg-primary rounded-circle me-2 d-inline-block"></span>
                    <span class="fs-2">Aktif</span>
                  </div>
                  <div>
                    <span class="round-8 bg-light-primary rounded-circle me-2 d-inline-block"></span>
                    <span class="fs-2">Selesai</span>
                  </div>
                </div>
              </div>
              <div class="col-4">
                <div class="d-flex justify-content-center">
                  <div id="breakup"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-12">
        <!-- Monthly Earnings -->
        <div class="card">
          <div class="card-body">
            <div class="row align-items-start">
              <div class="col-8">
                <h5 class="card-title mb-9 fw-semibold"> Tingkat Penyelesaian </h5>
                <h4 class="fw-semibold mb-3">
                  <?php
                  $completion_rate = $stats['total_sessions'] > 0
                      ? round(($stats['completed_sessions'] / $stats['total_sessions']) * 100, 1)
                      : 0;
                  ?>
                  <?= $completion_rate ?>%
                </h4>
                <div class="d-flex align-items-center pb-1">
                  <span
                    class="me-2 rounded-circle bg-light-success round-20 d-flex align-items-center justify-content-center">
                    <i class="ti ti-arrow-up-right text-success"></i>
                  </span>
                  <p class="text-dark me-1 fs-3 mb-0">Bagus</p>
                  <p class="fs-3 mb-0">progress</p>
                </div>
              </div>
              <div class="col-4">
                <div class="d-flex justify-content-end">
                  <div
                    class="text-white bg-secondary rounded-circle p-6 d-flex align-items-center justify-content-center">
                    <i class="ti ti-chart-pie fs-6"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="earning"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-lg-4 d-flex align-items-stretch">
    <div class="card w-100">
      <div class="card-body p-4">
        <div class="mb-4">
          <h5 class="card-title fw-semibold">Statistik Pengguna</h5>
        </div>
        <div class="d-flex justify-content-between mb-3">
          <div class="text-center">
            <div class="h4 text-primary"><?= number_format($user_stats['admin_count']) ?></div>
            <div class="text-muted small">Admin</div>
          </div>
          <div class="text-center">
            <div class="h4 text-success"><?= number_format($user_stats['guru_count']) ?></div>
            <div class="text-muted small">Guru</div>
          </div>
        </div>
        <div class="d-flex justify-content-between">
          <div class="text-center">
            <div class="h4 text-info"><?= number_format($stats['active_users']) ?></div>
            <div class="text-muted small">Aktif</div>
          </div>
          <div class="text-center">
            <div class="h4 text-warning"><?= number_format($stats['total_materi']) ?></div>
            <div class="text-muted small">Materi</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-8 d-flex align-items-stretch">
    <div class="card w-100">
      <div class="card-body p-4">
        <h5 class="card-title fw-semibold mb-4">Sesi Pembelajaran Terbaru</h5>
        <div class="table-responsive">
          <table class="table text-nowrap mb-0 align-middle">
            <thead class="text-dark fs-4">
              <tr>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Siswa</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Materi Kaidah</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Waktu</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Skor</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Status</h6>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php if (isset($recentSessions) && !empty($recentSessions)): ?>
                <?php foreach (array_slice($recentSessions, 0, 5) as $session): ?>
                <tr>
                  <td class="border-bottom-0">
                    <h6 class="fw-semibold mb-1"><?= esc($session['nama_lengkap']) ?></h6>
                    <span class="fw-normal text-muted">Siswa</span>
                  </td>
                  <td class="border-bottom-0">
                    <p class="mb-0 fw-normal"><?= esc($session['judul_kaidah']) ?></p>
                  </td>
                  <td class="border-bottom-0">
                    <span class="fs-3"><?= date('d M Y H:i', strtotime($session['waktu_mulai'])) ?></span>
                  </td>
                  <td class="border-bottom-0">
                    <h6 class="fw-semibold mb-0 fs-4"><?= $session['skor'] ? number_format($session['skor'], 1) : '-' ?></h6>
                  </td>
                  <td class="border-bottom-0">
                    <div class="d-flex align-items-center gap-2">
                      <?php if ($session['status'] === 'selesai'): ?>
                        <span class="badge bg-success rounded-3 fw-semibold">Selesai</span>
                      <?php else: ?>
                        <span class="badge bg-warning rounded-3 fw-semibold">Berjalan</span>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">
                    <div class="mb-3">
                      <i class="ti ti-inbox display-1 text-muted"></i>
                    </div>
                    <div>Belum ada sesi pembelajaran</div>
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

<!-- Quick Actions Row -->
<div class="row mt-4">
  <div class="col-sm-6 col-xl-3">
    <div class="card overflow-hidden rounded-2">
      <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h6 class="fw-semibold mb-0">Quick Actions</h6>
        </div>
        <div class="d-grid gap-2">
          <a href="<?= site_url('kaidah/create') ?>" class="btn btn-primary btn-sm">
            <i class="ti ti-plus me-1"></i> Tambah Kaidah
          </a>
          <a href="<?= site_url('soal/create') ?>" class="btn btn-success btn-sm">
            <i class="ti ti-plus me-1"></i> Tambah Soal
          </a>
          <?php if ($currentUser['hak_akses'] === 'admin'): ?>
          <a href="<?= site_url('users/create') ?>" class="btn btn-warning btn-sm">
            <i class="ti ti-user-plus me-1"></i> Tambah User
          </a>
          <?php endif; ?>
          <a href="<?= site_url('reports') ?>" class="btn btn-info btn-sm">
            <i class="ti ti-chart-bar me-1"></i> Laporan
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Statistics Cards -->
  <div class="col-sm-6 col-xl-3">
    <div class="card overflow-hidden rounded-2">
      <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h6 class="fw-semibold mb-0">Total Pengguna</h6>
          <i class="ti ti-users text-primary fs-4"></i>
        </div>
        <h3 class="fw-bold mb-1"><?= number_format($stats['total_users']) ?></h3>
        <small class="text-muted">Total pengguna terdaftar</small>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card overflow-hidden rounded-2">
      <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h6 class="fw-semibold mb-0">Materi Kaidah</h6>
          <i class="ti ti-book text-success fs-4"></i>
        </div>
        <h3 class="fw-bold mb-1"><?= number_format($stats['total_materi']) ?></h3>
        <small class="text-muted">Materi pembelajaran</small>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card overflow-hidden rounded-2">
      <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h6 class="fw-semibold mb-0">Total Soal</h6>
          <i class="ti ti-file-description text-warning fs-4"></i>
        </div>
        <h3 class="fw-bold mb-1"><?= number_format($stats['total_soal']) ?></h3>
        <small class="text-muted">Soal latihan</small>
      </div>
    </div>
  </div>
</div>

<div class="py-6 px-6 text-center">
  <p class="mb-0 fs-4">Aplikasi Pembelajaran Kaidah Bahasa Arab &copy; <?= date('Y') ?> - Powered by <a href="#" class="pe-1 text-primary text-decoration-underline">AdminMart</a></p>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(function() {
  // Sales Overview Chart - adapted for learning sessions
  var chart = {
    series: [
      { name: "Sesi Selesai:", data: [355, 390, 300, 350, 390, 180, 355, 390] },
      { name: "Sesi Aktif:", data: [280, 250, 325, 215, 250, 310, 280, 250] },
    ],
    chart: {
      type: "bar",
      height: 345,
      offsetX: -15,
      toolbar: { show: true },
      foreColor: "#adb0bb",
      fontFamily: 'inherit',
      sparkline: { enabled: false },
    },
    colors: ["#5D87FF", "#49BEFF"],
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: "35%",
        borderRadius: [6],
        borderRadiusApplication: 'end',
        borderRadiusWhenStacked: 'all'
      },
    },
    markers: { size: 0 },
    dataLabels: { enabled: false },
    legend: { show: false },
    grid: {
      borderColor: "rgba(0,0,0,0.1)",
      strokeDashArray: 3,
      xaxis: { lines: { show: false } }
    },
    stroke: {
      show: true,
      width: 3,
      lineCap: 'square',
      colors: ['transparent']
    },
    xaxis: {
      categories: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
    },
    yaxis: {
      title: { text: 'Jumlah Sesi' },
    },
    tooltip: {
      theme: 'light',
      x: { show: false },
    },
  };

  // Initialize the chart
  var salesChart = new ApexCharts(document.querySelector("#sales-overview"), chart);
  salesChart.render();

  // Yearly Breakup - adapted for completion stats
  var breakup = {
    series: [
      { name: "Selesai", data: [25, 30, 20, 40, 30, 35] },
      { name: "Aktif", data: [35, 25, 30, 20, 40, 30] }
    ],
    chart: {
      type: 'donut',
      fontFamily: 'inherit',
      height: 160,
      sparkline: { enabled: false },
    },
    colors: ["#5D87FF", "#49BEFF"],
    plotOptions: {
      pie: {
        startAngle: 0,
        endAngle: 360,
        donut: {
          size: '75%',
          background: 'transparent',
          labels: {
            show: false,
          }
        },
      }
    },
    stroke: { show: false },
    legend: { show: false },
    dataLabels: {
      enabled: true,
      formatter: function (val) {
        return val + '%';
      },
    },
    tooltip: {
      theme: 'light',
      x: { show: false },
    },
    responsive: [{
      breakpoint: 480,
      options: {
        chart: {
          height: 200,
        }
      }
    }]
  };

  // Initialize the breakup chart
  var breakupChart = new ApexCharts(document.querySelector("#breakup"), breakup);
  breakupChart.render();

  // Monthly Earnings - adapted for completion rate
  var earning = {
    series: [
      {
        name: "Penyelesaian",
        data: [<?= number_format($stats['completed_sessions']) ?>, <?= number_format($stats['total_sessions'] - $stats['completed_sessions']) ?>],
      }
    ],
    chart: {
      type: 'bar',
      height: 70,
      sparkline: { enabled: false },
      stacked: true,
      toolbar: { show: false },
      offsetX: 0,
    },
    plotOptions: {
      bar: {
        columnWidth: '30%',
        borderRadius: 3,
        borderRadiusApplication: 'end',
      }
    },
    colors: ["#5D87FF", "#e9ecef"],
    stroke: { show: false, width: 0 },
    fill: { opacity: 1 },
    dataLabels: {
      enabled: false,
    },
    tooltip: {
      theme: 'light',
      x: { show: false },
    },
    legend: { show: false },
    grid: {
      show: false,
      padding: 0,
      xaxis: { lines: { show: false } },
    },
    xaxis: { tooltip: { enabled: false }, labels: { show: false } },
    yaxis: { tooltip: { enabled: false }, labels: { show: false } },
  };

  // Initialize the earning chart
  var earningChart = new ApexCharts(document.querySelector("#earning"), earning);
  earningChart.render();
});
</script>
<?= $this->endSection() ?>