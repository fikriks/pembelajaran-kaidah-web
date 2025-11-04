<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Detail Materi Kaidah - <?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Arabic text styling */
    .arabic-text {
        font-family: 'Amiri', 'Traditional Arabic', serif;
        font-size: 1.2rem;
        line-height: 1.8;
        text-align: right;
        direction: rtl;
    }

    .arabic-large {
        font-family: 'Amiri', 'Traditional Arabic', serif;
        font-size: 1.5rem;
        line-height: 2;
        text-align: right;
        direction: rtl;
        font-weight: bold;
    }

    /* Difficulty badges */
    .badge-mudah {
        background-color: #4CAF50;
        color: white;
    }

    .badge-sedang {
        background-color: #FF9800;
        color: white;
    }

    .badge-sulit {
        background-color: #F44336;
        color: white;
    }

    /* Card styling */
    .detail-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .detail-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .detail-card.mudah { border-left-color: #4CAF50; }
    .detail-card.sedang { border-left-color: #FF9800; }
    .detail-card.sulit { border-left-color: #F44336; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark">Detail Materi Kaidah</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('kaidah') ?>" class="text-muted">Manajemen Materi Kaidah</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="<?= site_url('kaidah') ?>" class="btn btn-secondary">
            <i class="ti ti-arrow-left me-2"></i>Kembali
        </a>
        <a href="<?= site_url('kaidah/' . $kaidah['id_materi'] . '/edit') ?>" class="btn btn-warning">
            <i class="ti ti-edit me-2"></i>Edit
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <!-- Left Column - Main Info -->
    <div class="col-lg-8">
        <!-- Header Card -->
        <div class="card border-0 shadow-sm mb-4 detail-card <?= $kaidah['tingkat_kesulitan'] ?>">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="flex-grow-1">
                        <div class="mb-2">
                            <span class="badge rounded-3 badge-<?= $kaidah['tingkat_kesulitan'] ?> me-2">
                                <?= ucfirst($kaidah['tingkat_kesulitan']) ?>
                            </span>
                            <span class="badge bg-light text-dark">
                                Urutan: #<?= $kaidah['urutan'] ?>
                            </span>
                        </div>
                        <h3 class="fw-bold mb-3">
                            <?php if (preg_match('/[\p{Arabic}]/u', $kaidah['judul_kaidah'])): ?>
                                <span class="arabic-large"><?= esc($kaidah['judul_kaidah']) ?></span>
                            <?php else: ?>
                                <?= esc($kaidah['judul_kaidah']) ?>
                            <?php endif; ?>
                        </h3>
                    </div>
                    <div class="text-end">
                        <small class="text-muted d-block">
                            <i class="ti ti-user me-1"></i>
                            <?= esc($kaidah['nama_pembuat'] ?? 'Unknown') ?>
                        </small>
                        <small class="text-muted d-block">
                            <i class="ti ti-calendar me-1"></i>
                            <?= date('d M Y H:i', strtotime($kaidah['waktu_dibuat'])) ?>
                        </small>
                    </div>
                </div>

                <?php if (!empty($kaidah['deskripsi'])): ?>
                <div class="mb-4">
                    <h6 class="text-muted mb-2">Deskripsi</h6>
                    <p class="mb-0">
                        <?php if (preg_match('/[\p{Arabic}]/u', $kaidah['deskripsi'])): ?>
                            <span class="arabic-text"><?= esc($kaidah['deskripsi']) ?></span>
                        <?php else: ?>
                            <?= esc($kaidah['deskripsi']) ?>
                        <?php endif; ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Penjelasan Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">
                    <i class="ti ti-book me-2 text-primary"></i>Penjelasan
                </h5>
                <div class="bg-light rounded p-3">
                    <?php if (preg_match('/[\p{Arabic}]/u', $kaidah['penjelasan'])): ?>
                        <div class="arabic-text"><?= $kaidah['penjelasan'] ?></div>
                    <?php else: ?>
                        <p class="mb-0"><?= $kaidah['penjelasan'] ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Contoh Card -->
        <?php if (!empty($kaidah['contoh'])): ?>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">
                    <i class="ti ti-list me-2 text-success"></i>Contoh
                </h5>
                <div class="bg-success bg-opacity-10 rounded p-3">
                    <?php if (preg_match('/[\p{Arabic}]/u', $kaidah['contoh'])): ?>
                        <div class="arabic-text"><?= $kaidah['contoh'] ?></div>
                    <?php else: ?>
                        <p class="mb-0"><?= $kaidah['contoh'] ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Right Column - Stats & Info -->
    <div class="col-lg-4">
        <!-- Stats Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="text-muted mb-3">Informasi Materi</h6>
                <div class="mb-3">
                    <label class="text-muted small">Tingkat Kesulitan</label>
                    <div>
                        <span class="badge rounded-3 badge-<?= $kaidah['tingkat_kesulitan'] ?>">
                            <?= ucfirst($kaidah['tingkat_kesulitan']) ?>
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Urutan Materi</label>
                    <div class="fw-semibold">#<?= $kaidah['urutan'] ?></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Dibuat Oleh</label>
                    <div class="fw-semibold"><?= esc($kaidah['nama_pembuat'] ?? 'Unknown') ?></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Tanggal Dibuat</label>
                    <div class="fw-semibold"><?= date('d M Y H:i', strtotime($kaidah['waktu_dibuat'])) ?></div>
                </div>
            </div>
        </div>

        <!-- Related Actions -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="text-muted mb-3">Aksi Cepat</h6>
                <div class="d-grid gap-2">
                    <a href="<?= site_url('kaidah/' . $kaidah['id_materi'] . '/edit') ?>" class="btn btn-warning">
                        <i class="ti ti-edit me-2"></i>Edit Materi
                    </a>
                    <button type="button" class="btn btn-info" onclick="window.print()">
                        <i class="ti ti-printer me-2"></i>Cetak
                    </button>
                    <button type="button" class="btn btn-danger"
                            onclick="confirmDelete(<?= $kaidah['id_materi'] ?>, '<?= esc(addslashes($kaidah['judul_kaidah'])) ?>')">
                        <i class="ti ti-trash me-2"></i>Hapus Materi
                    </button>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-3">Navigasi</h6>
                <a href="<?= site_url('kaidah') ?>" class="btn btn-outline-secondary w-100">
                    <i class="ti ti-arrow-left me-2"></i>Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for delete -->
<form id="deleteForm" method="POST" action="">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="DELETE">
</form>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Delete confirmation function
function confirmDelete(id, title) {
    if (confirm(`Apakah Anda yakin ingin menghapus materi kaidah "${title}"? Data yang dihapus tidak dapat dikembalikan.`)) {
        const form = document.getElementById('deleteForm');
        form.action = '<?= site_url('kaidah') ?>/' + id;
        form.submit();
    }
}

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