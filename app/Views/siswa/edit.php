<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Edit Siswa - <?= $this->endSection() ?>

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
    .current-password {
        background-color: var(--bs-gray-100);
        font-family: var(--font-mono);
        font-size: 0.875rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark">Edit Siswa</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('siswa') ?>" class="text-muted">Manajemen Siswa</a></li>
                <li class="breadcrumb-item active">Edit Siswa</li>
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

        <!-- Info Siswa -->
        <div class="alert alert-info d-flex align-items-center" role="alert">
            <i class="ti ti-info-circle me-2"></i>
            <div>
                <strong>Info Siswa:</strong> <?= esc($siswa['nama_lengkap']) ?> (NIS: <?= esc($siswa['nis']) ?>)
                <br class="d-md-none">
                <small class="text-muted">Status: <span class="badge bg-<?= ($siswa['status'] === 'aktif') ? 'success' : 'secondary' ?> rounded-3"><?= ucfirst($siswa['status']) ?></span></small>
            </div>
        </div>

        <form method="POST" action="<?= site_url('siswa/' . $siswa['id']) ?>" class="needs-validation" novalidate>
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="PUT">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nis" class="form-label">NIS *</label>
                    <input type="text" class="form-control" id="nis" name="nis"
                           value="<?= esc($siswa['nis']) ?>"
                           placeholder="Masukkan Nomor Induk Siswa" required>
                    <div class="invalid-feedback">
                        NIS wajib diisi (minimal 5 karakter)
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap *</label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                           value="<?= esc($siswa['nama_lengkap']) ?>"
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
                        <option value="L" <?= ($siswa['jenis_kelamin'] === 'L') ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="P" <?= ($siswa['jenis_kelamin'] === 'P') ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                    <div class="invalid-feedback">
                        Jenis kelamin wajib dipilih
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="kelas" class="form-label">Kelas *</label>
                    <input type="text" class="form-control" id="kelas" name="kelas"
                           value="<?= esc($siswa['kelas']) ?>"
                           placeholder="Contoh: XI-A, X-B" required>
                    <div class="invalid-feedback">
                        Kelas wajib diisi
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status Akun *</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="">Pilih status</option>
                        <option value="aktif" <?= ($siswa['status'] === 'aktif') ? 'selected' : '' ?>>Aktif</option>
                        <option value="nonaktif" <?= ($siswa['status'] === 'nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                    <div class="invalid-feedback">
                        Status wajib dipilih
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password Saat Ini</label>
                    <div class="current-password form-control">
                        <i class="ti ti-key me-2"></i>
                        <span class="text-muted">Password tersimpan (terenkripsi)</span>
                    </div>
                    <small class="form-text text-muted">
                        Password tidak ditampilkan untuk alasan keamanan. Gunakan tombol "Reset Password" untuk mengubah password.
                    </small>
                </div>
            </div>

            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <i class="ti ti-alert-triangle me-2"></i>
                <div>
                    <strong>Catatan:</strong>
                    <ul class="mb-0 mt-1">
                        <li>NIS harus unik dan tidak boleh sama dengan siswa lain</li>
                        <li>Status nonaktif akan mencegah siswa login ke aplikasi mobile</li>
                        <li>Password bisa direset menggunakan tombol "Reset Password" di halaman manajemen siswa</li>
                    </ul>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-2"></i>Update Data
                </button>
                <a href="<?= site_url('siswa/' . $siswa['id'] . '/reset-password') ?>"
                   class="btn btn-warning"
                   onclick="return confirm('Apakah Anda yakin ingin reset password siswa ini?')">
                    <i class="ti ti-key me-2"></i>Reset Password
                </a>
                <a href="<?= site_url('siswa') ?>" class="btn btn-danger">
                    <i class="ti ti-x-circle me-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Quick Actions -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-body">
        <h6 class="card-title mb-3">Quick Actions</h6>
        <div class="row">
            <div class="col-md-4 mb-2">
                <a href="<?= site_url('siswa/' . $siswa['id'] . '/login-history') ?>"
                   class="btn btn-info w-100">
                    <i class="ti ti-clock-history me-2"></i>Lihat Login History
                </a>
            </div>
            <div class="col-md-4 mb-2">
                <button type="button" class="btn btn-success w-100" onclick="window.print()">
                    <i class="ti ti-printer me-2"></i>Cetak Data
                </button>
            </div>
            <div class="col-md-4 mb-2">
                <button type="button"
                        class="btn btn-danger w-100"
                        onclick="confirmDelete(<?= $siswa['id'] ?>)">
                    <i class="ti ti-trash me-2"></i>Hapus Data
                </button>
            </div>
        </div>
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

// Delete confirmation
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus siswa ini? Data yang dihapus tidak dapat dikembalikan.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= site_url('siswa') ?>/' + id;

        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        form.appendChild(csrfInput);

        // Add method override for DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?= $this->endSection() ?>