<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $this->renderSection('title') ?>Pembelajaran Kaidah Bahasa Arab</title>
  <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
  <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
  <link rel="shortcut icon" href="/favicon.ico" />
  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
  <meta name="apple-mobile-web-app-title" content="MyWebSite" />
  <link rel="manifest" href="/site.webmanifest" />
  <link rel="stylesheet" href="<?= base_url('assets/css/styles.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>" />

  <!-- Tabler Icons -->
  <link rel="stylesheet" href="<?= base_url('assets/css/icons/tabler-icons/tabler-icons.css') ?>" />

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/libs/datatables/dataTables.bootstrap5.css') ?>" />

  <!-- Notyf CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/libs/notyf/notyf.min.css') ?>" />

  <!-- Additional Page-specific CSS -->
  <?= $this->renderSection('styles') ?>
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">

    <?= $this->include('layouts/sidebar.php') ?>
    <!--  Main wrapper -->
    <div class="body-wrapper">

      <?= $this->include('layouts/navbar.php') ?>

      <div class="container-fluid">
        <?= $this->include('partials/flash_messages') ?>
        <?= $this->renderSection('content') ?>

        <?= $this->include('layouts/footer.php') ?>
      </div>
    </div>
  </div>
  <script src="<?= base_url('assets/libs/jquery/dist/jquery.min.js') ?>"></script>
  <script src="<?= base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/sidebarmenu.js') ?>"></script>
  <script src="<?= base_url('assets/js/app.min.js') ?>"></script>
  <script src="<?= base_url('assets/libs/simplebar/dist/simplebar.js') ?>"></script>

  <!-- DataTables JavaScript -->
  <script src="<?= base_url('assets/libs/datatables/dataTables.min.js') ?>"></script>
  <script src="<?= base_url('assets/libs/datatables/dataTables.bootstrap5.js') ?>"></script>
  <script src="<?= base_url('assets/js/datatables-helper.js') ?>"></script>

  <!-- Notyf JavaScript -->
  <script src="<?= base_url('assets/libs/notyf/notyf.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/toast-helper.js') ?>"></script>

  <!-- Additional Page-specific JavaScript -->
  <?= $this->renderSection('scripts') ?>
</body>

</html>
