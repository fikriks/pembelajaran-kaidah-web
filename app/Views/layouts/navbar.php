 <!--  Header Start -->
 <header class="app-header">
   <nav class="navbar navbar-expand-lg navbar-light">
     <ul class="navbar-nav">
       <li class="nav-item d-block d-xl-none">
         <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
           <i class="ti ti-menu-2"></i>
         </a>
       </li>
       </ul>
     <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
       <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                  <li class="nav-item dropdown">
          <?php
          $user = session()->get('user');
          $userName = $user['nama_lengkap'] ?? 'User';
          $userRole = $user['hak_akses'] ?? 'GURU';
          $userPhoto = !empty($user['foto_profil']) ? base_url('assets/images/profile/' . $user['foto_profil']) : base_url('assets/images/profile/user-1.jpg');
          ?>
           <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
             aria-expanded="false">
             <img src="<?= $userPhoto ?>" alt="<?= $userName ?>" width="35" height="35" class="rounded-circle">
           </a>
           <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
             <div class="message-body">
               <div class="d-flex align-items-center gap-2 p-3 border-bottom">
                 <img src="<?= $userPhoto ?>" alt="<?= $userName ?>" width="40" height="40" class="rounded-circle">
                 <div>
                   <h6 class="mb-0 fw-semibold"><?= $userName ?></h6>
                   <small class="text-muted"><?= ucfirst(strtolower($userRole)) ?></small>
                 </div>
               </div>
               <a href="<?= site_url('profile') ?>" class="d-flex align-items-center gap-2 dropdown-item">
                 <i class="ti ti-user fs-6"></i>
                 <p class="mb-0 fs-3">Profil Saya</p>
               </a>
               <a href="<?= site_url('dashboard') ?>" class="d-flex align-items-center gap-2 dropdown-item">
                 <i class="ti ti-layout-dashboard fs-6"></i>
                 <p class="mb-0 fs-3">Dashboard</p>
               </a>
               <div class="dropdown-divider"></div>
               <a href="<?= site_url('logout') ?>" class="btn btn-outline-danger mx-3 mt-2 d-block">
                <i class="ti ti-logout me-1"></i> Keluar
              </a>
             </div>
           </div>
         </li>
       </ul>
     </div>
   </nav>
 </header>
 <!--  Header End -->