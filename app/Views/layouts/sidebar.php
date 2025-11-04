<!-- Sidebar Start -->
<aside class="left-sidebar">
  <!-- Sidebar scroll-->
  <div>
    <div class="brand-logo d-flex align-items-center justify-content-between">
      <a href="<?= site_url('dashboard') ?>" class="text-nowrap logo-img">
        <img src="../assets/images/logos/dark-logo.svg" width="180" alt="Pembelajaran Kaidah" />
      </a>
      <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
        <i class="ti ti-x fs-8"></i>
      </div>
    </div>
    <!-- Sidebar navigation-->
    <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
      <ul id="sidebarnav">
        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Dashboard</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link <?= (current_url() == site_url('dashboard')) ? 'active' : '' ?>" href="<?= site_url('dashboard') ?>" aria-expanded="false">
            <span>
              <i class="ti ti-layout-dashboard"></i>
            </span>
            <span class="hide-menu">Beranda</span>
          </a>
        </li>

        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Manajemen Kaidah</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link <?= (strpos(current_url(), site_url('kaidah')) !== false) ? 'active' : '' ?>" href="<?= site_url('kaidah') ?>" aria-expanded="false">
            <span>
              <i class="ti ti-book"></i>
            </span>
            <span class="hide-menu">Materi Kaidah</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="<?= site_url('kaidah/create') ?>" aria-expanded="false">
            <span>
              <i class="ti ti-plus"></i>
            </span>
            <span class="hide-menu">Tambah Kaidah</span>
          </a>
        </li>

        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Manajemen Soal</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="<?= site_url('soal') ?>" aria-expanded="false">
            <span>
              <i class="ti ti-file-text"></i>
            </span>
            <span class="hide-menu">Bank Soal</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="<?= site_url('soal/create') ?>" aria-expanded="false">
            <span>
              <i class="ti ti-plus"></i>
            </span>
            <span class="hide-menu">Tambah Soal</span>
          </a>
        </li>

        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Laporan</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="<?= site_url('laporan') ?>" aria-expanded="false">
            <span>
              <i class="ti ti-chart-bar"></i>
            </span>
            <span class="hide-menu">Statistik Pembelajaran</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="<?= site_url('laporan/siswa') ?>" aria-expanded="false">
            <span>
              <i class="ti ti-users"></i>
            </span>
            <span class="hide-menu">Progress Siswa</span>
          </a>
        </li>

        <?php if (session()->get('user_role') === 'admin'): ?>
        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Pengaturan</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="<?= site_url('users') ?>" aria-expanded="false">
            <span>
              <i class="ti ti-user"></i>
            </span>
            <span class="hide-menu">Manajemen User</span>
          </a>
        </li>
        <?php endif; ?>

        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Akun</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="<?= site_url('profile') ?>" aria-expanded="false">
            <span>
              <i class="ti ti-user-circle"></i>
            </span>
            <span class="hide-menu">Profil Saya</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="<?= site_url('logout') ?>" aria-expanded="false">
            <span>
              <i class="ti ti-logout"></i>
            </span>
            <span class="hide-menu">Keluar</span>
          </a>
        </li>
      </ul>

      <!-- Learning Stats Card -->
      <div class="unlimited-access hide-menu bg-light-primary position-relative mb-7 mt-5 rounded">
        <div class="d-flex">
          <div class="unlimited-access-title me-3">
            <h6 class="fw-semibold fs-4 mb-2 text-dark w-85">Statistik Pembelajaran</h6>
            <p class="text-muted small mb-3">Pantau progress belajar kaidah bahasa Arab</p>
            <a href="<?= site_url('laporan') ?>" class="btn btn-primary fs-2 fw-semibold lh-sm">Lihat Laporan</a>
          </div>
          <div class="unlimited-access-img">
            <img src="../assets/images/backgrounds/rocket.png" alt="Progress" class="img-fluid">
          </div>
        </div>
      </div>
    </nav>
    <!-- End Sidebar navigation -->
  </div>
  <!-- End Sidebar scroll-->
</aside>
<!--  Sidebar End -->