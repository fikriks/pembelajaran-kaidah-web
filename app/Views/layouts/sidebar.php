<!-- Sidebar Start -->
<aside class="left-sidebar">
  <!-- Sidebar scroll-->
  <div>
    <div class="brand-logo d-flex align-items-center justify-content-between">
      <a href="<?= site_url('dashboard') ?>" class="text-nowrap logo-img">
        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white me-3" style="width: 36px; height: 36px;">
          <i class="ti ti-book"></i>
        </div>
        <div>
          <div class="fw-semibold text-white">Pembelajaran Kaidah</div>
          <small class="text-white-50">Dashboard Admin</small>
        </div>
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
          <span class="hide-menu">Menu Utama</span>
        </li>

        <!-- Dashboard -->
        <li class="sidebar-item">
          <a class="sidebar-link <?= (uri_string() === '/' || uri_string() === 'dashboard') ? 'active' : '' ?>" href="<?= site_url('dashboard') ?>" aria-expanded="false">
            <span>
              <i class="ti ti-layout-dashboard"></i>
            </span>
            <span class="hide-menu">Dashboard</span>
          </a>
        </li>

        <!-- User Management (Admin only) -->
        <?php if ($currentUser && $currentUser['hak_akses'] === 'admin'): ?>
        <li class="sidebar-item">
          <a class="sidebar-link <?= (strpos(uri_string(), 'users') === 0) ? 'active' : '' ?>" href="<?= site_url('users') ?>" aria-expanded="false">
            <span>
              <i class="ti ti-users"></i>
            </span>
            <span class="hide-menu">Manajemen Pengguna</span>
          </a>
        </li>
        <?php endif; ?>

        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Pembelajaran</span>
        </li>

        <!-- Materi Kaidah -->
        <li class="sidebar-item">
          <a class="sidebar-link <?= (strpos(uri_string(), 'kaidah') === 0) ? 'active' : '' ?>" href="<?= site_url('kaidah') ?>" aria-expanded="false">
            <span>
              <i class="ti ti-book"></i>
            </span>
            <span class="hide-menu">Materi Kaidah</span>
          </a>
        </li>

        <!-- Soal Management -->
        <li class="sidebar-item">
          <a class="sidebar-link <?= (strpos(uri_string(), 'soal') === 0) ? 'active' : '' ?>" href="<?= site_url('soal') ?>" aria-expanded="false">
            <span>
              <i class="ti ti-file-description"></i>
            </span>
            <span class="hide-menu">Manajemen Soal</span>
          </a>
        </li>

        <!-- Laporan -->
        <li class="sidebar-item">
          <a class="sidebar-link <?= (strpos(uri_string(), 'reports') === 0) ? 'active' : '' ?>" href="<?= site_url('reports') ?>" aria-expanded="false">
            <span>
              <i class="ti ti-chart-bar"></i>
            </span>
            <span class="hide-menu">Laporan</span>
          </a>
        </li>

        <!-- Settings (Admin only) -->
        <?php if ($currentUser && $currentUser['hak_akses'] === 'admin'): ?>
        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Pengaturan</span>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link <?= (uri_string() === 'settings') ? 'active' : '' ?>" href="<?= site_url('settings') ?>" aria-expanded="false">
            <span>
              <i class="ti ti-settings"></i>
            </span>
            <span class="hide-menu">Pengaturan</span>
          </a>
        </li>
        <?php endif; ?>

        <!-- User Profile -->
        <?php if ($currentUser): ?>
        <li class="sidebar-item">
          <a class="sidebar-link" href="<?= site_url('profile') ?>" aria-expanded="false">
            <span>
              <i class="ti ti-user"></i>
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
        <?php endif; ?>

        <!-- Upgrade to Pro (Hidden) -->
        <div class="unlimited-access hide-menu bg-light-primary position-relative mb-7 mt-5 rounded" style="display: none !important;">
          <div class="d-flex">
            <div class="unlimited-access-title me-3">
              <h6 class="fw-semibold fs-4 mb-6 text-dark w-85">Upgrade to pro</h6>
              <a href="https://adminmart.com/product/modernize-bootstrap-5-admin-template/" target="_blank" class="btn btn-primary fs-2 fw-semibold lh-sm">Buy Pro</a>
            </div>
            <div class="unlimited-access-img">
              <img src="<?= base_url('assets/images/backgrounds/rocket.png') ?>" alt="" class="img-fluid">
            </div>
          </div>
        </div>
      </ul>
    </nav>
    <!-- End Sidebar navigation -->
  </div>
  <!-- End Sidebar scroll-->
</aside>
<!--  Sidebar End -->