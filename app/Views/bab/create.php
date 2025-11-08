<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Tambah Bab - <?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark">Tambah Bab</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('bab') ?>" class="text-muted">Manajemen Bab</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="<?= site_url('bab') ?>" class="btn btn-secondary">
            <i class="ti ti-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<!-- Form Card -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <!-- Flash Messages -->
        <?= $this->include('partials/flash_messages') ?>

        <form method="POST" action="<?= site_url('bab') ?>" class="needs-validation" novalidate>
            <?= csrf_field() ?>

            <!-- Row 1: Nama Bab dan Urutan -->
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="nama_bab" class="form-label">Nama Bab *</label>
                    <input type="text" class="form-control" id="nama_bab" name="nama_bab"
                           placeholder="Contoh: BAB 1: KALAM" value="<?= old('nama_bab') ?>" required>
                    <div class="invalid-feedback">
                        Nama bab wajib diisi
                    </div>
                    <?php if (isset($validation) && $validation->getError('nama_bab')): ?>
                        <div class="text-danger small mt-1"><?= $validation->getError('nama_bab') ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="urutan" class="form-label">Urutan *</label>
                    <input type="number" class="form-control" id="urutan" name="urutan"
                           placeholder="1" value="<?= old('urutan') ?: $babModel->getNextOrder() ?>" min="1" required>
                    <div class="invalid-feedback">
                        Urutan wajib diisi
                    </div>
                    <?php if (isset($validation) && $validation->getError('urutan')): ?>
                        <div class="text-danger small mt-1"><?= $validation->getError('urutan') ?></div>
                    <?php endif; ?>
                    <small class="text-muted">Urutan menentukan penampilan bab di aplikasi</small>
                </div>
            </div>

            <!-- Row 2: Deskripsi -->
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"
                          placeholder="Deskripsi bab (opsional)"><?= old('deskripsi') ?></textarea>
                <div class="form-text">Jelaskan secara singkat isi dari bab ini (maksimal 1000 karakter)</div>
                <?php if (isset($validation) && $validation->getError('deskripsi')): ?>
                    <div class="text-danger small mt-1"><?= $validation->getError('deskripsi') ?></div>
                <?php endif; ?>
            </div>

            <!-- Row 3: Status -->
            <div class="mb-4">
                <label class="form-label">Status *</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="is_active" id="status_aktif"
                               value="1" <?= old('is_active', '1') == '1' ? 'checked' : '' ?> required>
                        <label class="form-check-label" for="status_aktif">
                            <i class="ti ti-circle-check me-1 text-success"></i>Aktif
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="is_active" id="status_nonaktif"
                               value="0" <?= old('is_active') == '0' ? 'checked' : '' ?> required>
                        <label class="form-check-label" for="status_nonaktif">
                            <i class="ti ti-circle-x me-1 text-danger"></i>Nonaktif
                        </label>
                    </div>
                </div>
                <?php if (isset($validation) && $validation->getError('is_active')): ?>
                    <div class="text-danger small mt-1"><?= $validation->getError('is_active') ?></div>
                <?php endif; ?>
                <div class="form-text">Bab aktif akan ditampilkan di aplikasi mobile</div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-2"></i>Simpan
                </button>
                <a href="<?= site_url('bab') ?>" class="btn btn-secondary">
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
(function() {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');

    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();

// Auto-capitalize nama bab
document.getElementById('nama_bab').addEventListener('input', function(e) {
    var value = e.target.value;
    // Convert to uppercase common patterns
    value = value.replace(/\bbab\b/gi, 'BAB');
    e.target.value = value;
});

// Auto-hide flash messages
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>
<?= $this->endSection() ?>