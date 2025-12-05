<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= isset($page_title) ? esc($page_title) . ' - ' : '' ?>Pembelajaran Kaidah Bahasa Arab</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Custom App CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/styles.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">

    <?= $this->renderSection('styles') ?>
</head>
<body class="auth-container">
    <div class="row g-0 h-100" style="height: 100%; min-height: 100vh;">
        <!-- Left Side - Branding -->
        <div class="col-md-6 auth-left">
            <div class="auth-logo">
                <i class="bi bi-book"></i>
            </div>
            <h1 class="auth-title" style="color: white;">تعلم القواعد</h1>
            <p class="auth-subtitle">Pembelajaran Kaidah Bahasa Arab dengan Algoritma LCM</p>

          </div>

        <!-- Right Side - Login Form -->
        <div class="col-md-6 auth-right">
            <div class="auth-card mx-auto">
                <!-- Logo -->
                <div class="text-center mb-4">
                    <img src="<?= base_url('assets/images/logos/logo.png') ?>" alt="Pembelajaran Kaidah"
                         class="mb-3" style="max-width: 120px; height: auto;">
                    <h3 class="fw-bold text-dark mb-2">Selamat Datang</h3>
                    <p class="text-muted">Silakan login ke akun Anda</p>
                </div>

                <!-- Flash Messages -->
                <?= $this->include('partials/flash_messages') ?>

                <!-- Page Content -->
                <?= $this->renderSection('content') ?>

                <!-- Footer -->
                <div class="text-center mt-4">
                    <p class="text-muted small">
                        © 2025 Pembelajaran Kaidah. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        // Auto-hide flash messages after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            });
        }, 5000);



        // Show/hide password functionality
        document.querySelectorAll('.toggle-password').forEach(function(button) {
            button.addEventListener('click', function() {
                const target = document.querySelector(this.getAttribute('data-target'));
                const icon = this.querySelector('i');

                if (target.type === 'password') {
                    target.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    target.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            });
        });
    </script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>