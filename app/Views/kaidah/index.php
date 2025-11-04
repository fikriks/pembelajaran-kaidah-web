<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Manajemen Kaidah - <?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .kaidah-card {
        transition: all 0.3s ease;
        border-left: 4px solid var(--primary-500);
    }
    .kaidah-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }
    .difficulty-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    .stats-card {
        background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
        color: white;
    }
    .action-buttons .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark">Materi Kaidah</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
                <li class="breadcrumb-item active">Materi Kaidah</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="<?= site_url('kaidah/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Tambah Kaidah
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stats-card border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-book fs-2 opacity-75"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">Total Kaidah</h6>
                        <h3 class="mb-0"><?= $totalKaidah ?? 0 ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0" style="background: var(--accent-teal); color: white;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-check-circle fs-2 opacity-75"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">Aktif</h6>
                        <h3 class="mb-0"><?= $kaidahAktif ?? 0 ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0" style="background: var(--accent-amber); color: white;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-clock fs-2 opacity-75"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">Draft</h6>
                        <h3 class="mb-0"><?= $kaidahDraft ?? 0 ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0" style="background: var(--accent-blue); color: white;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-list-task fs-2 opacity-75"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">Total Soal</h6>
                        <h3 class="mb-0"><?= $totalSoal ?? 0 ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="<?= site_url('kaidah') ?>">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" name="search"
                               placeholder="Cari judul kaidah..."
                               value="<?= esc($search ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="difficulty">
                        <option value="">Semua Tingkat</option>
                        <option value="mudah" <?= (isset($difficulty) && $difficulty === 'mudah') ? 'selected' : '' ?>>Mudah</option>
                        <option value="sedang" <?= (isset($difficulty) && $difficulty === 'sedang') ? 'selected' : '' ?>>Sedang</option>
                        <option value="sulit" <?= (isset($difficulty) && $difficulty === 'sulit') ? 'selected' : '' ?>>Sulit</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="status">
                        <option value="">Semua Status</option>
                        <option value="aktif" <?= (isset($status) && $status === 'aktif') ? 'selected' : '' ?>>Aktif</option>
                        <option value="nonaktif" <?= (isset($status) && $status === 'nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary flex-fill">
                            <i class="bi bi-funnel"></i>
                        </button>
                        <a href="<?= site_url('kaidah') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Kaidah List -->
<div class="row">
    <?php if (isset($kaidahList) && !empty($kaidahList)): ?>
        <?php foreach ($kaidahList as $kaidah): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card kaidah-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1">
                                <h6 class="card-title fw-bold text-dark mb-1">
                                    <?= esc($kaidah['judul_kaidah']) ?>
                                </h6>
                                <?php if ($kaidah['nama_arab']): ?>
                                    <p class="mb-2" style="font-family: var(--font-arabic); font-size: 1.1rem; color: var(--primary-700);">
                                        <?= esc($kaidah['nama_arab']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <span class="difficulty-badge badge bg-<?=
                                $kaidah['tingkat_kesulitan'] === 'mudah' ? 'success' :
                                ($kaidah['tingkat_kesulitan'] === 'sedang' ? 'warning' : 'danger')
                            ?>">
                                <?= ucfirst(esc($kaidah['tingkat_kesulitan'])) ?>
                            </span>
                        </div>

                        <p class="card-text text-muted small mb-3">
                            <?= character_limiter(strip_tags($kaidah['deskripsi']), 100) ?>
                        </p>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex gap-3">
                                <small class="text-muted">
                                    <i class="bi bi-list-task me-1"></i>
                                    <?= $kaidah['jumlah_soal'] ?? 0 ?> soal
                                </small>
                                <small class="text-muted">
                                    <i class="bi bi-bar-chart me-1"></i>
                                    <?= $kaidah['jumlah_sesi'] ?? 0 ?> sesi
                                </small>
                            </div>
                            <div>
                                <?php if ($kaidah['status'] === 'aktif'): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="action-buttons d-flex gap-2">
                            <a href="<?= site_url('kaidah/' . $kaidah['id_materi']) ?>"
                               class="btn btn-sm btn-outline-primary flex-fill">
                                <i class="bi bi-eye me-1"></i>Detail
                            </a>
                            <a href="<?= site_url('kaidah/' . $kaidah['id_materi'] . '/edit') ?>"
                               class="btn btn-sm btn-outline-warning flex-fill">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </a>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDelete(<?= $kaidah['id_materi'] ?>)">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada data kaidah</h5>
                <p class="text-muted">Mulai dengan menambahkan materi kaidah pertama</p>
                <a href="<?= site_url('kaidah/create') ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Kaidah
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Pagination -->
<?php if (isset($pager)): ?>
    <div class="d-flex justify-content-center mt-4">
        <?= $pager->links() ?>
    </div>
<?php endif; ?>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus kaidah ini?</p>
                <p class="text-muted small">
                    <strong>Peringatan:</strong> Semua soal yang terkait dengan kaidah ini juga akan dihapus.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus kaidah ini?')) {
        const form = document.getElementById('deleteForm');
        form.action = '<?= site_url('kaidah') ?>/' + id;

        // Add method override for DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

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