<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Manajemen Guru - <?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="mb-4">
    <h4 class="fw-bold text-dark">Manajemen Guru</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
            <li class="breadcrumb-item active">Manajemen Guru</li>
        </ol>
    </nav>
</div>

<!-- Statistics Cards -->
<?= view('partials/stats_row', [
    'stats' => [
        [
            'title' => 'Total Guru',
            'value' => $stats['total'] ?? 0,
            'subtitle' => 'Terdaftar',
            'icon' => 'school',
            'variant' => 'primary'
        ],
        [
            'title' => 'Aktif',
            'value' => $stats['aktif'] ?? 0,
            'subtitle' => 'Guru',
            'icon' => 'circle-check',
            'variant' => 'success'
        ],
        [
            'title' => 'Nonaktif',
            'value' => $stats['nonaktif'] ?? 0,
            'subtitle' => 'Guru',
            'icon' => 'circle-x',
            'variant' => 'warning'
        ]
    ]
]) ?>

<!-- Action Buttons -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark">Data Guru</h4>
        <small class="text-muted">Gunakan search bar di bawah untuk mencari data guru</small>
    </div>
    <div>
        <a href="<?= site_url('guru/create') ?>" class="btn btn-primary">
            <i class="ti ti-circle-plus me-2"></i>Tambah Guru
        </a>
    </div>
</div>

<!-- Guru List -->
<div class="card border-0 shadow-sm dataTables-card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="guruTable" class="table text-nowrap mb-0 align-middle datatable" data-type="basic">
                <thead class="text-dark">
                    <tr>
                        <th class="border-bottom-0">ID</th>
                        <th class="border-bottom-0">Username</th>
                        <th class="border-bottom-0">Nama Lengkap</th>
                        <th class="border-bottom-0">Status</th>
                        <th class="border-bottom-0">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($gurus)): ?>
                        <?php foreach ($gurus as $item): ?>
                            <tr>
                                <td class="border-bottom-0">
                                    <span class="fw-semibold"><?= esc($item['id_pengguna']) ?></span>
                                </td>
                                <td class="border-bottom-0">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                            <i class="ti ti-school text-primary fs-4"></i>
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
                                    <span class="badge bg-<?= ($item['status'] === 'AKTIF') ? 'success' : 'secondary' ?> rounded-3">
                                        <?= esc($item['status']) ?>
                                    </span>
                                </td>
                                <td class="border-bottom-0">
                                    <div class="table-actions">
                                        <a href="<?= site_url('guru/show/' . $item['id_pengguna']) ?>"
                                            class="btn btn-sm btn-info me-1"
                                            title="Detail">
                                            <i class="ti ti-eye me-1"></i>Detail
                                        </a>
                                        <a href="<?= site_url('guru/' . $item['id_pengguna'] . '/edit') ?>"
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
                            <td colspan="5" class="text-center py-5">
                                <i class="ti ti-school fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada data guru</h5>
                                <p class="text-muted">Tambahkan guru baru untuk mulai mengelola data pendidik</p>
                                <a href="<?= site_url('guru/create') ?>" class="btn btn-primary">
                                    <i class="ti ti-circle-plus me-2"></i>Tambah Guru Baru
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
        const table = $('#guruTable').DataTable();
    });

    // Toggle status
    function toggleStatus(id) {
        fetch(`<?= site_url('guru/') ?>${id}/toggleStatus`, {
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
        if (confirm('Apakah Anda yakin ingin menghapus guru ini?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `<?= site_url('guru/delete/') ?>${id}`;

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