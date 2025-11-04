<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Manajemen Materi Kaidah - <?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Arabic text styling */
    .arabic-text {
        font-family: 'Amiri', 'Traditional Arabic', serif;
        font-size: 1.1rem;
        line-height: 1.8;
        text-align: right;
        direction: rtl;
    }

    .arabic-small {
        font-family: 'Amiri', 'Traditional Arabic', serif;
        font-size: 0.9rem;
        line-height: 1.6;
        text-align: right;
        direction: rtl;
    }

    /* Difficulty badges */
    .badge-mudah {
        background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
        color: #1a5f3f;
    }

    .badge-sedang {
        background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        color: #8b4513;
    }

    .badge-sulit {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        color: #8b0000;
    }

    /* Kaidah card hover */
    .kaidah-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .kaidah-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .kaidah-card.mudah { border-left-color: #4CAF50; }
    .kaidah-card.sedang { border-left-color: #FF9800; }
    .kaidah-card.sulit { border-left-color: #F44336; }

    /* Table styling */
    .table-actions {
        white-space: nowrap;
    }

    .table-actions .btn {
        margin-right: 2px;
    }

    /* Stats card animation */
    .stats-card {
        transition: transform 0.2s ease;
    }

    .stats-card:hover {
        transform: translateY(-3px);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="mb-4">
    <h4 class="fw-bold text-dark">Manajemen Materi Kaidah</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
            <li class="breadcrumb-item active">Manajemen Materi Kaidah</li>
        </ol>
    </nav>
</div>

<!-- Statistics Cards -->
<?= view('partials/stats_row', [
    'stats' => [
        [
            'title' => 'Total Materi',
            'value' => $stats['total'] ?? 0,
            'subtitle' => 'Kaidah',
            'icon' => 'book',
            'variant' => 'primary'
        ],
        [
            'title' => 'Mudah',
            'value' => $stats['mudah'] ?? 0,
            'subtitle' => 'Materi',
            'icon' => 'trophy',
            'variant' => 'success'
        ],
        [
            'title' => 'Sedang',
            'value' => $stats['sedang'] ?? 0,
            'subtitle' => 'Materi',
            'icon' => 'battery-2',
            'variant' => 'warning'
        ],
        [
            'title' => 'Sulit',
            'value' => $stats['sulit'] ?? 0,
            'subtitle' => 'Materi',
            'icon' => 'battery-1',
            'variant' => 'danger'
        ]
    ]
]) ?>

<!-- Action Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark">Data Materi Kaidah</h4>
        <small class="text-muted">Kelola materi pembelajaran kaidah bahasa Arab</small>
    </div>
    <div>
        <a href="<?= site_url('kaidah/create') ?>" class="btn btn-primary">
            <i class="ti ti-circle-plus me-2"></i>Tambah Materi
        </a>
    </div>
</div>

<!-- Search and Filter -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="<?= site_url('kaidah') ?>" class="row g-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="ti ti-search"></i>
                    </span>
                    <input type="text" class="form-control" name="search" placeholder="Cari judul atau deskripsi..." value="<?= esc($search ?? '') ?>">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="difficulty">
                    <option value="">Semua Tingkat</option>
                    <option value="mudah" <?= ($selectedDifficulty ?? '') === 'mudah' ? 'selected' : '' ?>>Mudah</option>
                    <option value="sedang" <?= ($selectedDifficulty ?? '') === 'sedang' ? 'selected' : '' ?>>Sedang</option>
                    <option value="sulit" <?= ($selectedDifficulty ?? '') === 'sulit' ? 'selected' : '' ?>>Sulit</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="ti ti-search me-1"></i>Cari
                </button>
            </div>
            <div class="col-md-2">
                <a href="<?= site_url('kaidah') ?>" class="btn btn-secondary w-100">
                    <i class="ti ti-refresh me-1"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Data Table -->
<div class="card border-0 shadow-sm dataTables-card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="kaidahTable" class="table text-nowrap mb-0 align-middle datatable" data-type="kaidah">
                <thead class="text-dark">
                    <tr>
                        <th class="border-bottom-0">No</th>
                        <th class="border-bottom-0">Judul Kaidah</th>
                        <th class="border-bottom-0">Deskripsi</th>
                        <th class="border-bottom-0">Tingkat</th>
                        <th class="border-bottom-0">Urutan</th>
                        <th class="border-bottom-0">Dibuat Oleh</th>
                        <th class="border-bottom-0 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($kaidah)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="ti ti-inbox fs-1 text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada materi kaidah</h5>
                            <p class="text-muted">Mulai tambahkan materi pembelajaran kaidah bahasa Arab</p>
                            <a href="<?= site_url('kaidah/create') ?>" class="btn btn-primary">
                                <i class="ti ti-circle-plus me-2"></i>Tambah Materi
                            </a>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php $no = ($currentPage - 1) * $perPage + 1; ?>
                        <?php foreach ($kaidah as $item): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <div class="d-flex align-items-start">
                                    <div>
                                        <h6 class="mb-1 fw-semibold">
                                            <?php if (preg_match('/[\p{Arabic}]/u', $item['judul_kaidah'])): ?>
                                                <span class="arabic-text"><?= esc($item['judul_kaidah']) ?></span>
                                            <?php else: ?>
                                                <?= esc($item['judul_kaidah']) ?>
                                            <?php endif; ?>
                                        </h6>
                                        <small class="text-muted">ID: <?= $item['id_materi'] ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <?php
                                    $description = $item['deskripsi'] ?? 'Tidak ada deskripsi';
                                    if (strlen($description) > 50) {
                                        $description = substr($description, 0, 50) . '...';
                                    }
                                    ?>
                                    <?php if (preg_match('/[\p{Arabic}]/u', $description)): ?>
                                        <span class="arabic-small"><?= esc($description) ?></span>
                                    <?php else: ?>
                                        <small class="text-muted"><?= esc($description) ?></small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge rounded-3 badge-<?= $item['tingkat_kesulitan'] ?>">
                                    <?= ucfirst($item['tingkat_kesulitan']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-light text-dark me-2">#<?= $item['urutan'] ?></span>
                                </div>
                            </td>
                            <td>
                                <small class="text-muted"><?= esc($item['nama_pembuat'] ?? 'Unknown') ?></small>
                            </td>
                            <td class="text-center">
                                <div class="table-actions justify-content-center">
                                    <!-- View -->
                                    <a href="<?= site_url('kaidah/' . $item['id_materi']) ?>"
                                       class="btn btn-sm btn-info me-1"
                                       title="Lihat Detail">
                                        <i class="ti ti-eye"></i>
                                    </a>

                                    <!-- Edit -->
                                    <a href="<?= site_url('kaidah/' . $item['id_materi'] . '/edit') ?>"
                                       class="btn btn-sm btn-warning me-1"
                                       title="Edit">
                                        <i class="ti ti-edit"></i>
                                    </a>

                                    <!-- Delete -->
                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            onclick="confirmDelete(<?= $item['id_materi'] ?>, '<?= esc(addslashes($item['judul_kaidah'])) ?>')"
                                            title="Hapus">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination (if using manual pagination) -->
        <?php if (isset($pager)): ?>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">
                    Menampilkan <?= $total ?> data
                </small>
                <?= $pager->links('default', 'custom_pagination') ?>
            </div>
        <?php endif; ?>
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

// Initialize DataTable if needed
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#kaidahTable').DataTable({
            responsive: true,
            pageLength: 10,
            ordering: true,
            searching: false, // We use custom search
            paging: false, // We use custom pagination
            info: false,
            language: {
                "emptyTable": "Tidak ada data materi kaidah",
                "zeroRecords": "Tidak ditemukan data yang cocok",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "infoFiltered": "(difilter dari _MAX_ total data)"
            }
        });
    }
});

// Auto-refresh statistics every 30 seconds
setInterval(function() {
    fetch('<?= site_url('kaidah/statistics') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const stats = data.data;
                // Update stats cards if needed
                document.querySelector('.stats-card-primary h2').textContent = stats.total || 0;
                document.querySelector('.stats-card-success h2').textContent = stats.mudah || 0;
                document.querySelector('.stats-card-warning h2').textContent = stats.sedang || 0;
                document.querySelector('.stats-card-danger h2').textContent = stats.sulit || 0;
            }
        })
        .catch(error => console.error('Error fetching statistics:', error));
}, 30000);
</script>
<?= $this->endSection() ?>