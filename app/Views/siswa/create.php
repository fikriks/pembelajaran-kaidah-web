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

            <div class="form-group mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="generatePassword" name="generate_password" checked>
                    <label class="form-check-label" for="generatePassword">
                        Generate random password otomatis
                    </label>
                </div>
                <small class="form-text text-muted">
                    Password akan digenerate otomatis dan ditampilkan setelah data berhasil disimpan.
                </small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-2"></i>Simpan
                </button>
                <a href="<?= site_url('siswa') ?>" class="btn btn-danger">
                    <i class="ti ti-x-circle me-2"></i>Batal
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
            }

            form.classList.add('was-validated');
        }, false);
    });
});
</script>
<?= $this->endSection() ?>