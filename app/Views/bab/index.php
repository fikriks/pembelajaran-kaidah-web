<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Manajemen Bab - <?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .bab-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.07);
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-left: 5px solid #4CAF50;
        transition: all 0.3s ease;
    }

    .bab-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.12);
    }

    .bab-card.inactive {
        border-left-color: #9E9E9E;
        opacity: 0.7;
    }

    .bab-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .bab-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #212121;
        margin: 0;
    }

    .bab-stats {
        display: flex;
        gap: 1rem;
        margin-top: 0.75rem;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #f5f5f5;
        border-radius: 8px;
        font-size: 0.875rem;
    }

    .stat-item i {
        color: #4CAF50;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-aktif {
        background: #E8F5E9;
        color: #388E3C;
    }

    .status-nonaktif {
        background: #F5F5F5;
        color: #616161;
    }

    .bab-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .table-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        padding: 0.375rem 0.75rem;
        border: none;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-action:hover {
        transform: translateY(-1px);
    }

    .btn-detail {
        background: #E3F2FD;
        color: #1976D2;
    }

    .btn-edit {
        background: #FFF3E0;
        color: #F57C00;
    }

    .btn-status {
        background: #E8F5E9;
        color: #388E3C;
    }

    .btn-status.nonaktif {
        background: #F5F5F5;
        color: #616161;
    }

    .btn-delete {
        background: #FFEBEE;
        color: #D32F2F;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="mb-4">
    <h4 class="fw-bold text-dark">Manajemen Bab</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
            <li class="breadcrumb-item active">Manajemen Bab</li>
        </ol>
    </nav>
</div>

<!-- Statistics Cards -->
<?= view('partials/stats_row', [
    'stats' => [
        [
            'title' => 'Total Bab',
            'value' => $stats['total'] ?? 0,
            'subtitle' => 'Tersedia',
            'icon' => 'folder',
            'variant' => 'primary'
        ],
        [
            'title' => 'Aktif',
            'value' => $stats['active'] ?? 0,
            'subtitle' => 'Dapat Digunakan',
            'icon' => 'circle-check',
            'variant' => 'success'
        ],
        [
            'title' => 'Nonaktif',
            'value' => $stats['inactive'] ?? 0,
            'subtitle' => 'Tidak Aktif',
            'icon' => 'circle-x',
            'variant' => 'secondary'
        ]
    ]
]) ?>

<!-- Action Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark">Daftar Bab Pembelajaran</h4>
        <small class="text-muted">Kelola bab-bab untuk mengorganisir materi kaidah</small>
    </div>
    <div>
        <a href="<?= site_url('bab/create') ?>" class="btn btn-primary">
            <i class="ti ti-circle-plus me-2"></i>Tambah Bab
        </a>
    </div>
</div>

<!-- Bab List -->
<div class="row">
    <?php if (empty($bab)): ?>
    <!-- Empty State -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="ti ti-folder-off fs-1 text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada bab</h5>
                <p class="text-muted">Mulai tambahkan bab untuk mengorganisir materi pembelajaran</p>
                <a href="<?= site_url('bab/create') ?>" class="btn btn-primary">
                    <i class="ti ti-circle-plus me-2"></i>Tambah Bab Pertama
                </a>
            </div>
        </div>
    </div>
    <?php else: ?>
        <?php foreach ($bab as $item): ?>
        <div class="col-lg-6">
            <div class="bab-card <?= $item['is_active'] ? '' : 'inactive' ?>">
                <div class="bab-header">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="bab-title">
                                <i class="ti ti-folder me-2 text-primary"></i>
                                <?= esc($item['nama_bab']) ?>
                            </h5>
                            <span class="status-badge <?= $item['is_active'] ? 'status-aktif' : 'status-nonaktif' ?>">
                                <?= $item['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                            </span>
                        </div>
                        <?php if (!empty($item['deskripsi'])): ?>
                        <p class="text-muted mb-3"><?= esc($item['deskripsi']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bab-stats">
                    <div class="stat-item">
                        <i class="ti ti-book"></i>
                        <span><?= $item['total_materi'] ?> Materi</span>
                    </div>
                    <div class="stat-item">
                        <i class="ti ti-list"></i>
                        <span><?= $item['total_soal'] ?> Soal</span>
                    </div>
                    <div class="stat-item">
                        <i class="ti ti-hash"></i>
                        <span>Urutan #<?= $item['urutan'] ?></span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bab-actions">
                    <a href="<?= site_url('bab/' . $item['id_bab']) ?>" class="btn-action btn-detail">
                        <i class="ti ti-eye me-1"></i>Detail
                    </a>
                    <a href="<?= site_url('bab/' . $item['id_bab'] . '/edit') ?>" class="btn-action btn-edit">
                        <i class="ti ti-edit me-1"></i>Edit
                    </a>
                    <button type="button" class="btn-action btn-status <?= $item['is_active'] ? '' : 'nonaktif' ?>"
                            onclick="toggleStatus(<?= $item['id_bab'] ?>)" title="Ubah Status">
                        <i class="ti ti-toggle-<?= $item['is_active'] ? 'left' : 'right' ?>"></i>
                        <span class="ms-1">Status</span>
                    </button>
                    <button type="button" class="btn-action btn-delete"
                            onclick="confirmDelete(<?= $item['id_bab'] ?>, '<?= esc($item['nama_bab']) ?>')" title="Hapus">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
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

// Confirm delete function
function confirmDelete(id, title) {
    toast.confirm(
        `Apakah Anda yakin ingin menghapus bab "${title}"? Data yang dihapus tidak dapat dikembalikan.`,
        function() {
            const loading = toast.loading('Menghapus bab...');

            const form = document.getElementById('deleteForm');
            form.action = `<?= site_url('bab/delete/') ?>${id}`;

            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '<?= csrf_token() ?>';
                csrfInput.value = '<?= csrf_hash() ?>';
                form.appendChild(csrfInput);
            }

            document.body.appendChild(form);
            form.submit();

            setTimeout(() => loading.dismiss(), 2000);
        },
        null,
        {
            title: 'Hapus Bab',
            confirmText: 'Hapus',
            confirmClass: 'btn-danger'
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