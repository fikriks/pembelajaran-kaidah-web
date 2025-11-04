<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Manajemen Siswa - <?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="mb-4">
    <h4 class="fw-bold text-dark">Manajemen Siswa</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
            <li class="breadcrumb-item active">Manajemen Siswa</li>
        </ol>
    </nav>
</div>

<!-- Statistics Cards -->
<?= view('partials/stats_row', [
    'stats' => [
        [
            'title' => 'Total Siswa',
            'value' => $stats['total'] ?? 0,
            'subtitle' => 'Terdaftar',
            'icon' => 'users',
            'variant' => 'primary'
        ],
        [
            'title' => 'Aktif',
            'value' => $stats['aktif'] ?? 0,
            'subtitle' => 'Siswa',
            'icon' => 'circle-check',
            'variant' => 'success'
        ],
        [
            'title' => 'Nonaktif',
            'value' => $stats['nonaktif'] ?? 0,
            'subtitle' => 'Siswa',
            'icon' => 'circle-x',
            'variant' => 'warning'
        ],
        [
            'title' => 'Total Kelas',
            'value' => count($kelasOptions),
            'subtitle' => 'Kelas',
            'icon' => 'building',
            'variant' => 'info'
        ]
    ]
]) ?>

<!-- Action Buttons -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark">Data Siswa</h4>
        <small class="text-muted">Gunakan search bar di bawah untuk mencari data</small>
    </div>
    <div>
        <a href="<?= site_url('siswa/create') ?>" class="btn btn-primary">
            <i class="ti ti-circle-plus me-2"></i>Tambah Siswa
        </a>
    </div>
</div>

<!-- Siswa List -->
<div class="card border-0 shadow-sm dataTables-card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="siswaTable" class="table text-nowrap mb-0 align-middle datatable" data-type="students">
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
                            <td colspan="6" class="text-center py-5">
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
// DataTables will be auto-initialized by datatables-helper.js

$(document).ready(function() {
    // Get DataTable instance
    const table = $('#siswaTable').DataTable();
});

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