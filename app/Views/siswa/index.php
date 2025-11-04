<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Manajemen Siswa - <?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .stats-card {
        background: linear-gradient(135deg, var(--bs-primary), var(--bs-secondary));
        color: white;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    .stats-card .card-body {
        position: relative;
        z-index: 1;
    }
    .stats-card .stats-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        font-size: 1.5rem;
    }
    .stats-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        z-index: 0;
    }
    .action-buttons .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    .table-actions {
        white-space: nowrap;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark">Manajemen Siswa</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
                <li class="breadcrumb-item active">Manajemen Siswa</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="<?= site_url('siswa/create') ?>" class="btn btn-primary">
            <i class="ti ti-circle-plus me-2"></i>Tambah Siswa
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stats-card border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon">
                        <i class="ti ti-users text-white"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 opacity-75">Total Siswa</h6>
                        <h2 class="mb-0 fw-bold"><?= $stats['total'] ?? 0 ?></h2>
                        <small class="opacity-50">Terdaftar</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card border-0" style="background: linear-gradient(135deg, #26A69A, #00897B);">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon">
                        <i class="ti ti-circle-check text-white"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 opacity-75">Aktif</h6>
                        <h2 class="mb-0 fw-bold"><?= $stats['aktif'] ?? 0 ?></h2>
                        <small class="opacity-50">Siswa</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card border-0" style="background: linear-gradient(135deg, #FFA726, #FF9800);">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon">
                        <i class="ti ti-circle-x text-white"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 opacity-75">Nonaktif</h6>
                        <h2 class="mb-0 fw-bold"><?= $stats['nonaktif'] ?? 0 ?></h2>
                        <small class="opacity-50">Siswa</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card border-0" style="background: linear-gradient(135deg, #42A5F5, #2196F3);">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon">
                        <i class="ti ti-building text-white"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 opacity-75">Total Kelas</h6>
                        <h2 class="mb-0 fw-bold"><?= count($kelasOptions) ?></h2>
                        <small class="opacity-50">Kelas</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="<?= site_url('siswa') ?>">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ti ti-search"></i>
                        </span>
                        <input type="text" class="form-control" name="search"
                               placeholder="Cari nama atau NIS..."
                               value="<?= esc($search ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="kelas">
                        <option value="">Semua Kelas</option>
                        <?php foreach ($kelasOptions as $kelas): ?>
                            <option value="<?= esc($kelas) ?>"
                                    <?= ($selectedKelas === $kelas) ? 'selected' : '' ?>>
                                <?= esc($kelas) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary flex-fill">
                            <i class="ti ti-filter"></i>
                        </button>
                        <a href="<?= site_url('siswa') ?>" class="btn btn-outline-secondary">
                            <i class="ti ti-refresh"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Siswa List -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table text-nowrap mb-0 align-middle">
                <thead class="text-dark">
                    <tr>
                        <th class="border-bottom-0">NIS</th>
                        <th class="border-bottom-0">Nama Lengkap</th>
                        <th class="border-bottom-0">Jenis Kelamin</th>
                        <th class="border-bottom-0">Kelas</th>
                        <th class="border-bottom-0">Status</th>
                        <th class="border-bottom-0">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($siswa)): ?>
                        <?php foreach ($siswa as $item): ?>
                            <tr>
                                <td class="border-bottom-0">
                                    <span class="fw-semibold"><?= esc($item['nis']) ?></span>
                                </td>
                                <td class="border-bottom-0">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                            <i class="ti ti-user text-primary fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?= esc($item['nama_lengkap']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="border-bottom-0">
                                    <span class="badge bg-<?= ($item['jenis_kelamin'] === 'L') ? 'info' : 'danger' ?> rounded-3">
                                        <?= ($item['jenis_kelamin'] === 'L') ? 'Laki-laki' : 'Perempuan' ?>
                                    </span>
                                </td>
                                <td class="border-bottom-0">
                                    <span class="fw-semibold"><?= esc($item['kelas']) ?></span>
                                </td>
                                <td class="border-bottom-0">
                                    <span class="badge bg-<?= ($item['status'] === 'AKTIF') ? 'success' : 'secondary' ?> rounded-3">
                                        <?= esc($item['status']) ?>
                                    </span>
                                </td>
                                <td class="border-bottom-0">
                                    <div class="table-actions">
                                        <a href="<?= site_url('siswa/' . $item['id'] . '/login-history') ?>"
                                           class="btn btn-sm btn-info me-1"
                                           title="Lihat Login History">
                                            <i class="ti ti-clock"></i> History
                                        </a>
                                        <a href="<?= site_url('siswa/' . $item['id'] . '/edit') ?>"
                                           class="btn btn-sm btn-warning me-1"
                                           title="Edit">
                                            <i class="ti ti-edit"></i> Edit
                                        </a>
                                        <button type="button"
                                                class="btn btn-sm btn-secondary me-1"
                                                onclick="confirmResetPassword(<?= $item['id'] ?>)"
                                                title="Reset Password">
                                            <i class="ti ti-key"></i> Reset
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-danger"
                                                onclick="confirmDelete(<?= $item['id'] ?>)"
                                                title="Hapus">
                                            <i class="ti ti-trash"></i> Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="ti ti-inbox fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada data siswa</h5>
                                <p class="text-muted">Mulai dengan menambahkan siswa pertama</p>
                                <a href="<?= site_url('siswa/create') ?>" class="btn btn-primary">
                                    <i class="ti ti-circle-plus me-2"></i>Tambah Siswa
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total > $perPage): ?>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    Menampilkan <?= count($siswa) ?> dari <?= $total ?> data
                </div>
                <div>
                    <nav>
                        <ul class="pagination mb-0">
                            <?php
                            $totalPages = ceil($total / $perPage);

                            // Previous button
                            if ($currentPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= site_url('siswa?page=' . ($currentPage - 1) . '&search=' . urlencode($search ?? '') . '&kelas=' . urlencode($selectedKelas ?? '')) ?>">
                                        <i class="ti ti-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <!-- Page numbers -->
                            <?php
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($totalPages, $currentPage + 2);

                            for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= site_url('siswa?page=' . $i . '&search=' . urlencode($search ?? '') . '&kelas=' . urlencode($selectedKelas ?? '')) ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <!-- Next button -->
                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= site_url('siswa?page=' . ($currentPage + 1) . '&search=' . urlencode($search ?? '') . '&kelas=' . urlencode($selectedKelas ?? '')) ?>">
                                        <i class="ti ti-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus siswa ini?</p>
                <p class="text-muted small">
                    <strong>Peringatan:</strong> Data siswa dan semua history login akan dihapus.
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

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reset Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin reset password siswa ini?</p>
                <p class="text-muted small">
                    Password baru akan ditampilkan setelah proses reset selesai.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="resetPasswordForm" method="POST" style="display: inline;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PATCH">
                    <button type="submit" class="btn btn-warning">Reset Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus siswa ini?')) {
        const form = document.getElementById('deleteForm');
        form.action = '<?= site_url('siswa') ?>/' + id;

        // Add method override for DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        form.submit();
    }
}

function confirmResetPassword(id) {
    const form = document.getElementById('resetPasswordForm');
    form.action = '<?= site_url('siswa') ?>/' + id + '/reset-password';

    // Add method override for PATCH
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'PATCH';
    form.appendChild(methodInput);

    form.submit();
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