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
    <link rel="stylesheet" href="<?= base_url('dist/css/app.css') ?>">

    <!-- Additional Auth Styles -->
    <style>
        .auth-container {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-50), white);
        }

        .auth-left {
            background: linear-gradient(135deg, var(--primary-600), var(--primary-800));
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            text-align: center;
        }

        .auth-right {
            background: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-title {
            font-family: var(--font-arabic);
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .auth-subtitle {
            font-size: 1.25rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .auth-logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            font-size: 2rem;
        }

        .auth-card {
            background: white;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            padding: 3rem;
            width: 100%;
            max-width: 450px;
        }

        .auth-form .form-control {
            padding: 1rem 1.25rem;
            border-radius: var(--radius-lg);
            border: 2px solid var(--neutral-200);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .auth-form .form-control:focus {
            border-color: var(--primary-500);
            box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
        }

        .auth-btn {
            padding: 1rem 2rem;
            font-weight: 600;
            border-radius: var(--radius-lg);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .auth-demo {
            background: rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-top: 2rem;
            backdrop-filter: blur(10px);
        }

        @media (max-width: 768px) {
            .auth-left {
                display: none;
            }

            .auth-right {
                padding: 2rem;
            }

            .auth-card {
                padding: 2rem;
            }
        }
    </style>

    <?= $this->renderSection('styles') ?>
</head>
<body class="auth-container">
    <div class="row g-0 h-100">
        <!-- Left Side - Branding -->
        <div class="col-md-6 auth-left">
            <div class="auth-logo">
                <i class="bi bi-book"></i>
            </div>
            <h1 class="auth-title">تعلم القواعد</h1>
            <p class="auth-subtitle">Pembelajaran Kaidah Bahasa Arab dengan Algoritma LCM</p>

            <div class="auth-demo">
                <h5 class="mb-3">
                    <i class="bi bi-lightbulb me-2"></i>
                    Demo Credentials
                </h5>
                <div class="text-start">
                    <div class="mb-2">
                        <strong>Admin:</strong><br>
                        Username: admin<br>
                        Password: admin123
                    </div>
                    <div>
                        <strong>Guru:</strong><br>
                        Username: guru<br>
                        Password: guru123
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="col-md-6 auth-right">
            <div class="auth-card mx-auto">
                <!-- Logo -->
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary text-white mb-3"
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-book fs-3"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-2">Selamat Datang</h3>
                    <p class="text-muted">Silakan login ke akun Anda</p>
                </div>

                <!-- Flash Messages -->
                <?= $this->include('partials/flash_messages') ?>

                <!-- Page Content -->
                <?= $this->renderSection('content') ?>

                <!-- Footer -->
                <div class="text-center mt-4">
                    <p class="text-muted small mb-0">
                        <i class="bi bi-shield-check me-1"></i>
                        Login Aman • Sistem Pembelajaran Kaidah Bahasa Arab
                    </p>
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

        // Form validation feedback
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.needs-validation');

            Array.from(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    form.classList.add('was-validated');
                }, false);
            });
        });

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