<!-- Sidebar Start -->
<aside class="left-sidebar">
  <!-- Sidebar scroll-->
  <div>
    <div class="brand-logo d-flex align-items-center justify-content-between">
      <a href="<?= site_url('dashboard') ?>" class="text-nowrap logo-img mt-3">
        <img src="<?= base_url('assets/images/logos/logo.png') ?>" width="110" alt="Pembelajaran Kaidah" />
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
          <span class="hide-menu">Manajemen Bab</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link <?= (strpos(current_url(), site_url('bab')) !== false) ? 'active' : '' ?>" href="<?= site_url('bab') ?>" aria-expanded="false">
            <span>
              <i class="ti ti-folder"></i>
            </span>
            <span class="hide-menu">Manajemen Bab</span>
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

        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Manajemen Soal</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link <?= (strpos(current_url(), site_url('soal')) !== false) ? 'active' : '' ?>" href="<?= site_url('soal') ?>" aria-expanded="false">
            <span>
              <i class="ti ti-file-text"></i>
            </span>
            <span class="hide-menu">Bank Soal</span>
          </a>
        </li>

        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Progress Belajar</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link <?= (strpos(current_url(), site_url('progress')) !== false) ? 'active' : '' ?>" href="<?= site_url('progress') ?>" aria-expanded="false">
            <span>
              <i class="ti ti-chart-line"></i>
            </span>
            <span class="hide-menu">Progress Siswa</span>
          </a>
        </li>


        <?php if (session()->get('user_role') === 'ADMIN'): ?>
        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Pengaturan</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link <?= (strpos(current_url(), site_url('pengguna')) !== false) ? 'active' : '' ?>" href="<?= site_url('pengguna') ?>" aria-expanded="false">
            <span>
              <i class="ti ti-user"></i>
            </span>
            <span class="hide-menu">Manajemen User</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link <?= (strpos(current_url(), site_url('siswa')) !== false) ? 'active' : '' ?>" href="<?= site_url('siswa') ?>" aria-expanded="false">
            <span>
              <i class="ti ti-user-check"></i>
            </span>
            <span class="hide-menu">Manajemen Siswa</span>
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
    </nav>
    <!-- End Sidebar navigation -->
  </div>
  <!-- End Sidebar scroll-->
</aside>
<!--  Sidebar End -->