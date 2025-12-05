<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Tambah Siswa - <?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .form-label {
        font-weight: 600;
        color: var(--bs-heading-color);
        margin-bottom: 0.5rem;
    }
    .form-control:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark">Tambah Siswa Baru</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('siswa') ?>" class="text-muted">Manajemen Siswa</a></li>
                <li class="breadcrumb-item active">Tambah Siswa</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="<?= site_url('siswa') ?>" class="btn btn-secondary">
            <i class="ti ti-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<!-- Form Card -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <!-- Flash Messages -->
        <?= $this->include('partials/flash_messages') ?>

        <form method="POST" action="<?= site_url('siswa') ?>" class="needs-validation" novalidate>
            <?= csrf_field() ?>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nis" class="form-label">NIS *</label>
                    <input type="text" class="form-control" id="nis" name="nis"
                           placeholder="Masukkan Nomor Induk Siswa" required>
                    <div class="invalid-feedback">
                        NIS wajib diisi (minimal 5 karakter)
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap *</label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                           placeholder="Masukkan nama lengkap siswa" required>
                    <div class="invalid-feedback">
                        Nama lengkap wajib diisi
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin *</label>
                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="">Pilih jenis kelamin</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                    <div class="invalid-feedback">
                        Jenis kelamin wajib dipilih
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="kelas" class="form-label">Kelas *</label>
                    <input type="text" class="form-control" id="kelas" name="kelas"
                           placeholder="Contoh: XI-A, X-B" required>
                    <div class="invalid-feedback">
                        Kelas wajib diisi
                    </div>
                </div>
            </div>

            <input type="hidden" name="password_info" value="default">

            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="ti ti-info-circle me-2"></i>
                <div>
                    Password default akan diset ke <strong>123456789</strong> untuk siswa baru.
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-2"></i>Simpan
                </button>
                <a href="<?= site_url('siswa') ?>" class="btn btn-danger">
                    <i class="ti ti-circle-x me-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.needs-validation');

    Array.from(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();

                // Show validation error toast using Notyf
                if (typeof toast !== 'undefined' && toast.error) {
                    toast.error('Mohon lengkapi semua field yang wajib diisi.');
                } else if (typeof notyf !== 'undefined') {
                    // Fallback to direct Notyf usage
                    notyf.error('Mohon lengkapi semua field yang wajib diisi.');
                } else {
                    console.error('Toast and Notyf objects not available');
                    alert('Mohon lengkapi semua field yang wajib diisi.');
                }
            }

            form.classList.add('was-validated');
        }, false);
    });

    // Debug toast availability
    console.log('Toast object availability:', typeof toast);
    console.log('Notyf object availability:', typeof notyf);

    if (typeof toast !== 'undefined') {
        console.log('Toast object is available');
        // Test error toast for invalid fields
        setTimeout(() => {
            if (document.querySelectorAll('.is-invalid').length > 0) {
                toast.error('Ada field yang belum diisi dengan benar.');
            }
        }, 500);
    } else if (typeof notyf !== 'undefined') {
        console.log('Direct Notyf object is available');
        // Test error toast for invalid fields
        setTimeout(() => {
            if (document.querySelectorAll('.is-invalid').length > 0) {
                notyf.error('Ada field yang belum diisi dengan benar.');
            }
        }, 500);
    } else {
        console.error('Both Toast and Notyf objects are not available');
    }
});
</script>
<?= $this->endSection() ?>