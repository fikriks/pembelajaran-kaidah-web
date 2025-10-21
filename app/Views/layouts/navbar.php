<!--  Header Start -->
<header class="app-header">
  <nav class="navbar navbar-expand-lg navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item d-block d-xl-none">
        <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
          <i class="ti ti-menu-2"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link nav-icon-hover" href="javascript:void(0)">
          <i class="ti ti-bell-ringing"></i>
          <div class="notification bg-primary rounded-circle"></div>
        </a>
      </li>
    </ul>
    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
      <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">

        <!-- Quick Actions (Admin & Guru only) -->
        <?php if ($currentUser && in_array($currentUser['hak_akses'], ['admin', 'guru'])): ?>
        <li class="nav-item dropdown">
          <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="quickActionsDropdown" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="ti ti-bolt"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="quickActionsDropdown">
            <div class="message-body">
              <h6 class="dropdown-header">Quick Actions</h6>
              <a href="<?= site_url('kaidah/create') ?>" class="d-flex align-items-center gap-2 dropdown-item">
                <i class="ti ti-plus fs-6 text-success"></i>
                <p class="mb-0 fs-3">Tambah Kaidah</p>
              </a>
              <a href="<?= site_url('soal/create') ?>" class="d-flex align-items-center gap-2 dropdown-item">
                <i class="ti ti-file-description fs-6 text-primary"></i>
                <p class="mb-0 fs-3">Tambah Soal</p>
              </a>
              <?php if ($currentUser['hak_akses'] === 'admin'): ?>
              <a href="<?= site_url('users/create') ?>" class="d-flex align-items-center gap-2 dropdown-item">
                <i class="ti ti-user-plus fs-6 text-warning"></i>
                <p class="mb-0 fs-3">Tambah User</p>
              </a>
              <?php endif; ?>
              <a href="<?= site_url('reports') ?>" class="d-flex align-items-center gap-2 dropdown-item">
                <i class="ti ti-chart-bar fs-6 text-info"></i>
                <p class="mb-0 fs-3">Lihat Laporan</p>
              </a>
            </div>
          </div>
        </li>
        <?php endif; ?>

        <!-- User Dropdown -->
        <?php if ($currentUser): ?>
        <li class="nav-item dropdown">
          <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
            aria-expanded="false">
            <?php if ($currentUser['foto_profil']): ?>
              <img src="<?= base_url('uploads/profiles/' . $currentUser['foto_profil']) ?>" alt="" width="35" height="35" class="rounded-circle">
            <?php else: ?>
              <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white" style="width: 35px; height: 35px;">
                <i class="ti ti-user fs-6"></i>
              </div>
            <?php endif; ?>
          </a>
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
            <div class="message-body">
              <h6 class="dropdown-header"><?= esc($currentUser['nama_lengkap']) ?></h6>
              <a href="<?= site_url('profile') ?>" class="d-flex align-items-center gap-2 dropdown-item">
                <i class="ti ti-user fs-6"></i>
                <p class="mb-0 fs-3">Profil Saya</p>
              </a>
              <a href="<?= site_url('settings') ?>" class="d-flex align-items-center gap-2 dropdown-item">
                <i class="ti ti-settings fs-6"></i>
                <p class="mb-0 fs-3">Pengaturan</p>
              </a>
              <a href="<?= site_url('help') ?>" class="d-flex align-items-center gap-2 dropdown-item">
                <i class="ti ti-help-circle fs-6"></i>
                <p class="mb-0 fs-3">Bantuan</p>
              </a>
              <a href="<?= site_url('logout') ?>" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
            </div>
          </div>
        </li>
        <?php endif; ?>
      </ul>
    </div>
  </nav>
</header>
<!--  Header End -->