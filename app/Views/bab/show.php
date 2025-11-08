<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Detail Bab - <?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark">Detail Bab</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('bab') ?>" class="text-muted">Manajemen Bab</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="<?= site_url('bab') ?>" class="btn btn-secondary">
            <i class="ti ti-arrow-left me-2"></i>Kembali
        </a>
        <a href="<?= site_url('bab/' . $bab['id_bab'] . '/edit') ?>" class="btn btn-warning">
            <i class="ti ti-edit me-2"></i>Edit
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <!-- Left Column - Main Info -->
    <div class="col-lg-8">
        <!-- Header Card -->
        <div class="card border-0 shadow-sm mb-4 detail-card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="flex-grow-1">
                        <div class="mb-2">
                            <span class="badge bg-light text-dark me-2">
                                Urutan: #<?= $bab['urutan'] ?>
                            </span>
                            <span class="badge <?= $bab['is_active'] ? 'bg-success' : 'bg-secondary' ?> text-white">
                                <?= $bab['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                            </span>
                        </div>
                        <h3 class="fw-bold mb-3">
                            <i class="ti ti-folder me-2 text-primary"></i>
                            <?= esc($bab['nama_bab']) ?>
                        </h3>
                    </div>
                    <div class="text-end">
                        <small class="text-muted d-block">
                            <i class="ti ti-calendar me-1"></i>
                            <?= date('d M Y H:i', strtotime($bab['waktu_dibuat'])) ?>
                        </small>
                        <small class="text-muted d-block">
                            <i class="ti ti-clock me-1"></i>
                            <?= date('d M Y H:i', strtotime($bab['waktu_diubah'])) ?>
                        </small>
                    </div>
                </div>

                <?php if (!empty($bab['deskripsi'])): ?>
                <div class="mb-4">
                    <h6 class="text-muted mb-2">Deskripsi</h6>
                    <p class="mb-0"><?= esc($bab['deskripsi']) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">
                    <i class="ti ti-chart-bar me-2 text-primary"></i>Statistik Bab
                </h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-xl bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="ti ti-book text-primary fs-4"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-bold"><?= $stats['total_materi'] ?></h4>
                                <p class="text-muted mb-0">Materi Kaidah</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-xl bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="ti ti-list text-success fs-4"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-bold"><?= $stats['total_soal'] ?></h4>
                                <p class="text-muted mb-0">Soal</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Materi Card -->
        <?php if (!empty($recent_materi)): ?>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">
                    <i class="ti ti-clock me-2 text-info"></i>Materi Terbaru
                </h5>
                <div class="list-group list-group-flush">
                    <?php foreach ($recent_materi as $materi): ?>
                    <div class="list-group-item px-0">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><?= esc($materi['judul_kaidah']) ?></h6>
                                <small class="text-muted">
                                    <i class="ti ti-user me-1"></i><?= esc($materi['nama_pembuat']) ?> •
                                    <i class="ti ti-calendar me-1"></i><?= date('d M Y', strtotime($materi['waktu_dibuat'])) ?>
                                </small>
                            </div>
                            <span class="badge bg-light text-dark">Urutan #<?= $materi['urutan'] ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php if ($stats['total_materi'] > 5): ?>
                <div class="text-center mt-3">
                    <small class="text-muted">Menampilkan 5 dari <?= $stats['total_materi'] ?> materi</small>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Right Column - Info & Actions -->
    <div class="col-lg-4">
        <!-- Quick Info Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="text-muted mb-3">Informasi Bab</h6>
                <div class="mb-3">
                    <label class="text-muted small">Nama Bab</label>
                    <div class="fw-semibold"><?= esc($bab['nama_bab']) ?></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Urutan</label>
                    <div class="fw-semibold">#<?= $bab['urutan'] ?></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Status</label>
                    <div class="fw-semibold">
                        <span class="badge <?= $bab['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                            <?= $bab['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Tanggal Dibuat</label>
                    <div class="fw-semibold"><?= date('d M Y H:i', strtotime($bab['waktu_dibuat'])) ?></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Terakhir Diubah</label>
                    <div class="fw-semibold"><?= date('d M Y H:i', strtotime($bab['waktu_diubah'])) ?></div>
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="text-muted mb-3">Tindakan</h6>
                <div class="d-grid gap-2">
                    <a href="<?= site_url('bab/' . $bab['id_bab'] . '/edit') ?>" class="btn btn-warning">
                        <i class="ti ti-edit me-2"></i>Edit Bab
                    </a>
                    <button type="button" class="btn <?= $bab['is_active'] ? 'btn-secondary' : 'btn-success' ?>"
                            onclick="toggleStatus(<?= $bab['id_bab'] ?>)">
                        <i class="ti ti-toggle-<?= $bab['is_active'] ? 'left' : 'right' ?> me-2"></i>
                        <?= $bab['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>
                    </button>
                    <a href="<?= site_url('kaidah/create?bab_id=' . $bab['id_bab']) ?>" class="btn btn-info">
                        <i class="ti ti-circle-plus me-2"></i>Tambah Materi
                    </a>
                    <a href="<?= site_url('soal/create?bab_id=' . $bab['id_bab']) ?>" class="btn btn-success">
                        <i class="ti ti-circle-plus me-2"></i>Tambah Soal
                    </a>
                </div>
            </div>
        </div>

        <!-- Related Links Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-3">Terkait</h6>
                <div class="d-grid gap-2">
                    <a href="<?= site_url('kaidah?bab=' . $bab['id_bab']) ?>" class="btn btn-outline-primary">
                        <i class="ti ti-book me-2"></i>Lihat Materi (<?= $stats['total_materi'] ?>)
                    </a>
                    <a href="<?= site_url('soal?bab=' . $bab['id_bab']) ?>" class="btn btn-outline-success">
                        <i class="ti ti-list me-2"></i>Lihat Soal (<?= $stats['total_soal'] ?>)
                    </a>
                </div>
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
// Toggle status function
function toggleStatus(id) {
    const currentStatus = <?= $bab['is_active'] ?>;
    const actionText = currentStatus ? 'menonaktifkan' : 'mengaktifkan';

    toast.confirm(
        `Apakah Anda yakin ingin ${actionText} bab ini?`,
        function() {
            const loading = toast.loading('Mengubah status...');

            fetch(`<?= site_url('bab/') ?>${id}/toggleStatus`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    toast.success('Status bab berhasil diperbarui!');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    toast.error(data.message || 'Gagal mengubah status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toast.error('Terjadi kesalahan saat mengubah status');
            })
            .finally(() => {
                loading.dismiss();
            });
        }
    );
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