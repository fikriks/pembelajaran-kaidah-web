<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($page_title) ? esc($page_title) . ' - ' : '' ?><?= esc($siteName) ?></title>
  <link rel="shortcut icon" type="image/png" href="<?= base_url('assets/images/logos/favicon.png') ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/css/styles.min.css') ?>" />

  <!-- Custom Green Theme Override -->
  <style>
    :root {
      --bs-primary: #4CAF50;
      --bs-secondary: #8BC34A;
      --bs-success: #13DEB9;
      --bs-info: #539BFF;
      --bs-warning: #FFAE1F;
      --bs-danger: #FA896B;
      --bs-light: #F6F9FC;
      --bs-dark: #2A3547;
      --bs-primary-rgb: 76, 175, 80;
      --bs-secondary-rgb: 139, 195, 74;
      --bs-success-rgb: 19, 222, 185;
      --bs-info-rgb: 83, 155, 255;
      --bs-warning-rgb: 255, 174, 31;
      --bs-danger-rgb: 250, 137, 107;
      --bs-light-rgb: 246, 249, 252;
      --bs-dark-rgb: 42, 53, 71;
    }

    /* Green sidebar gradient */
    .left-sidebar {
      background: linear-gradient(180deg, #2E7D32, #1B5E20) !important;
    }

    /* Custom primary buttons */
    .btn-primary {
      background-color: #4CAF50;
      border-color: #4CAF50;
    }

    .btn-primary:hover {
      background-color: #388E3C;
      border-color: #388E3C;
    }

    /* Override Modernize CSS Variables for Green Theme */
    .left-sidebar {
      background: linear-gradient(180deg, #2E7D32, #1B5E20) !important;
    }

    /* Sidebar all text colors - Higher specificity */
    .left-sidebar .sidebar-link {
      color: rgba(255, 255, 255, 0.9) !important;
    }

    .left-sidebar .sidebar-link:hover {
      color: white !important;
      background-color: rgba(255, 255, 255, 0.1) !important;
    }

    .left-sidebar .sidebar-link.active {
      background-color: #4CAF50 !important;
      color: white !important;
    }

    /* Sidebar titles - Higher specificity */
    .left-sidebar .nav-small-cap {
      color: rgba(255, 255, 255, 0.7) !important;
    }

    .left-sidebar .nav-small-cap-icon {
      color: rgba(255, 255, 255, 0.7) !important;
    }

    .left-sidebar .hide-menu {
      color: rgba(255, 255, 255, 0.9) !important;
    }

    /* Sidebar icons - Higher specificity */
    .left-sidebar .sidebar-link span i {
      color: rgba(255, 255, 255, 0.9) !important;
    }

    .left-sidebar .sidebar-link:hover span i {
      color: white !important;
    }

    .left-sidebar .sidebar-link.active span i {
      color: white !important;
    }

    /* Sidebar brand text - Higher specificity */
    .brand-logo .text-white,
    .brand-logo .small {
      color: white !important;
    }

    /* Close button in sidebar */
    .left-sidebar .close-btn i {
      color: white !important;
    }

    /* Sidebar scrollbar */
    .left-sidebar .scroll-sidebar {
      scrollbar-width: thin;
      scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
    }

    .left-sidebar .scroll-sidebar::-webkit-scrollbar {
      width: 6px;
    }

    .left-sidebar .scroll-sidebar::-webkit-scrollbar-track {
      background: transparent;
    }

    .left-sidebar .scroll-sidebar::-webkit-scrollbar-thumb {
      background-color: rgba(255, 255, 255, 0.3);
      border-radius: 3px;
    }

    /* Header background */
    .app-header {
      background: white !important;
      border-bottom: 1px solid #e9ecef;
    }
  </style>

  <?= $this->renderSection('styles') ?>
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">

    <!-- Sidebar -->
    <?= $this->include('layouts/sidebar') ?>

    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header -->
      <?= $this->include('layouts/navbar') ?>

      <div class="container-fluid">
        <!-- Page Header -->
        <?php if (isset($page_title)): ?>
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
          <div class="mb-3 mb-sm-0">
            <h5 class="card-title fw-semibold"><?= esc($page_title) ?></h5>
            <?php if (isset($page_subtitle)): ?>
              <p class="mb-0"><?= esc($page_subtitle) ?></p>
            <?php endif; ?>
          </div>
          <?php if (isset($breadcrumbs)): ?>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <?php foreach ($breadcrumbs as $key => $breadcrumb): ?>
                <li class="breadcrumb-item <?php echo $key === array_key_last($breadcrumbs) ? 'active' : '' ?>">
                  <?php if ($key === array_key_last($breadcrumbs)): ?>
                    <?= esc($breadcrumb) ?>
                  <?php else: ?>
                    <a href="#" class="text-decoration-none"><?= esc($breadcrumb) ?></a>
                  <?php endif; ?>
                </li>
              <?php endforeach; ?>
            </ol>
          </nav>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Flash Messages -->
        <?= $this->include('partials/flash_messages') ?>

        <!-- Page Content -->
        <?= $this->renderSection('content') ?>
      </div>
    </div>
  </div>

  <!-- JavaScript -->
  <script src="<?= base_url('assets/libs/jquery/dist/jquery.min.js') ?>"></script>
  <script src="<?= base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/sidebarmenu.js') ?>"></script>
  <script src="<?= base_url('assets/js/app.min.js') ?>"></script>
  <script src="<?= base_url('assets/libs/apexcharts/dist/apexcharts.min.js') ?>"></script>
  <script src="<?= base_url('assets/libs/simplebar/dist/simplebar.js') ?>"></script>
  <script src="<?= base_url('assets/js/dashboard.js') ?>"></script>

  <!-- Custom JavaScript -->
  <script>
    // Initialize app
    document.addEventListener('DOMContentLoaded', function() {
      // Confirm delete actions
      const deleteButtons = document.querySelectorAll('.btn-delete');
      deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
          if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            e.preventDefault();
            return false;
          }
        });
      });

      // Auto-hide flash messages
      setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
          alert.style.opacity = '0';
          setTimeout(() => {
            alert.remove();
          }, 300);
        });
      }, 5000);
    });

    // Utility function to show alerts
    function showAlert(message, type = 'info') {
      const alertContainer = document.querySelector('.body-wrapper') || document.body;
      const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 9999;" role="alert">
          ${message}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      `;

      alertContainer.insertAdjacentHTML('afterbegin', alertHtml);

      // Auto-hide after 5 seconds
      setTimeout(() => {
        const alert = alertContainer.querySelector('.alert');
        if (alert) {
          alert.remove();
        }
      }, 5000);
    }

    // AJAX form submissions
    document.addEventListener('DOMContentLoaded', function() {
      const ajaxForms = document.querySelectorAll('.ajax-form');

      ajaxForms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
          e.preventDefault();

          const formData = new FormData(form);
          const url = form.getAttribute('action');
          const method = form.getAttribute('method') || 'POST';

          fetch(url, {
            method: method,
            body: formData,
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          })
          .then(response => response.json())
          .then(data => {
            if (data.status === 'success') {
              showAlert(data.message, 'success');

              if (data.redirect) {
                window.location.href = data.redirect;
              } else {
                setTimeout(() => {
                  window.location.reload();
                }, 1000);
              }
            } else {
              showAlert(data.message, 'danger');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan. Silakan coba lagi.', 'danger');
          });
        });
      });
    });
  </script>

  <?= $this->renderSection('scripts') ?>
</body>

</html>