<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Manajemen Pengguna - <?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="mb-4">
    <h4 class="fw-bold text-dark">Manajemen Pengguna</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
            <li class="breadcrumb-item active">Manajemen Pengguna</li>
        </ol>
    </nav>
</div>

<!-- Statistics Cards -->
<?= view('partials/stats_row', [
    'stats' => [
        [
            'title' => 'Total Pengguna',
            'value' => $stats['total'] ?? 0,
            'subtitle' => 'Terdaftar',
            'icon' => 'users',
            'variant' => 'primary'
        ],
        [
            'title' => 'Aktif',
            'value' => $stats['aktif'] ?? 0,
            'subtitle' => 'Pengguna',
            'icon' => 'circle-check',
            'variant' => 'success'
        ],
        [
            'title' => 'Nonaktif',
            'value' => $stats['nonaktif'] ?? 0,
            'subtitle' => 'Pengguna',
            'icon' => 'circle-x',
            'variant' => 'warning'
        ]
    ]
]) ?>

<!-- Action Buttons -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark">Data Pengguna</h4>
        <small class="text-muted">Gunakan search bar di bawah untuk mencari data</small>
    </div>
    <div>
        <a href="<?= site_url('pengguna/create') ?>" class="btn btn-primary">
            <i class="ti ti-circle-plus me-2"></i>Tambah Pengguna
        </a>
    </div>
</div>

<!-- Pengguna List -->
<div class="card border-0 shadow-sm dataTables-card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="penggunaTable" class="table text-nowrap mb-0 align-middle datatable" data-type="basic">
                <thead class="text-dark">
                    <tr>
                        <th class="border-bottom-0">ID</th>
                        <th class="border-bottom-0">Username</th>
                        <th class="border-bottom-0">Nama Lengkap</th>
                        <th class="border-bottom-0">Role</th>
                        <th class="border-bottom-0">Status</th>
                        <th class="border-bottom-0">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $item): ?>
                            <tr>
                                <td class="border-bottom-0">
                                    <span class="fw-semibold"><?= esc($item['id_pengguna']) ?></span>
                                </td>
                                <td class="border-bottom-0">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                            <i class="ti ti-user text-primary fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?= esc($item['nama_pengguna']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="border-bottom-0">
                                    <span class="fw-semibold"><?= esc($item['nama_lengkap']) ?></span>
                                </td>
                                <td class="border-bottom-0">
                                    <span class="badge bg-<?= ($item['hak_akses'] === 'ADMIN') ? 'danger' : 'info' ?> rounded-3">
                                        <?= ($item['hak_akses'] === 'ADMIN') ? 'Admin' : 'Guru' ?>
                                    </span>
                                </td>
                                <td class="border-bottom-0">
                                    <span class="badge bg-<?= ($item['status'] === 'AKTIF') ? 'success' : 'secondary' ?> rounded-3">
                                        <?= esc($item['status']) ?>
                                    </span>
                                </td>
                                <td class="border-bottom-0">
                                    <div class="table-actions">
                                        <a href="<?= site_url('pengguna/show/' . $item['id_pengguna']) ?>"
                                            class="btn btn-sm btn-info me-1"
                                            title="Detail">
                                            <i class="ti ti-eye me-1"></i>Detail
                                        </a>
                                        <a href="<?= site_url('pengguna/edit/' . $item['id_pengguna']) ?>"
                                            class="btn btn-sm btn-warning me-1"
                                            title="Edit">
                                            <i class="ti ti-edit me-1"></i>Edit
                                        </a>
                                        <button type="button" class="btn btn-sm btn-success me-1"
                                            onclick="toggleStatus(<?= $item['id_pengguna'] ?>)"
                                            title="Ubah Status">
                                            <i class="ti ti-toggle-<?= $item['status'] === 'AKTIF' ? 'left' : 'right' ?> me-1"></i>Status
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="confirmDelete(<?= $item['id_pengguna'] ?>)"
                                            title="Hapus">
                                            <i class="ti ti-trash me-1"></i>Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="ti ti-users fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada data pengguna</h5>
                                <p class="text-muted">Tambahkan pengguna baru untuk mulai menggunakan sistem</p>
                                <a href="<?= site_url('pengguna/create') ?>" class="btn btn-primary">
                                    <i class="ti ti-circle-plus me-2"></i>Tambah Pengguna Baru
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Get DataTable instance
        const table = $('#penggunaTable').DataTable();
    });

    // Toggle status
    function toggleStatus(id) {
        fetch(`<?= site_url('pengguna/toggleStatus/') ?>${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    location.reload();
                } else {
                    alert(data.message || 'Gagal mengubah status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengubah status');
            });
    }

    // Confirm delete
    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `<?= site_url('pengguna/delete/') ?>${id}`;

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
        }
    }

    // DataTables is auto-initialized by datatables-helper.js
    // No need for manual initialization when using class="datatable"
</script>
<?= $this->endSection() ?>