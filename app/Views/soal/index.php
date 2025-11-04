<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Manajemen Soal - <?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
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

    .difficulty-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

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

    .soal-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .soal-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .soal-card.mudah { border-left-color: #4CAF50; }
    .soal-card.sedang { border-left-color: #FF9800; }
    .soal-card.sulit { border-left-color: #F44336; }

    .jawaban-option {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 0.375rem;
        padding: 0.5rem;
        margin-bottom: 0.25rem;
        font-size: 0.875rem;
    }

    .jawaban-option.correct {
        background: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .table-actions {
        white-space: nowrap;
    }

    .table-actions .btn {
        margin-right: 2px;
    }

    .stats-card {
        transition: transform 0.2s ease;
    }

    .stats-card:hover {
        transform: translateY(-3px);
    }

    .preview-modal .modal-dialog {
        max-width: 800px;
    }

    .lcm-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="mb-4">
    <h4 class="fw-bold text-dark">Manajemen Soal</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
            <li class="breadcrumb-item active">Manajemen Soal</li>
        </ol>
    </nav>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stats-card stats-card-primary border-0 d-print-none">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon">
                        <i class="ti ti-help text-white"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">Total Soal</h6>
                        <h2 class="mb-0 fw-bold"><?= $stats['total'] ?? 0 ?></h2>
                        <small>Soal</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card stats-card-success border-0 d-print-none">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon">
                        <i class="ti ti-trophy text-white"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">Mudah</h6>
                        <h2 class="mb-0 fw-bold"><?= $stats['mudah'] ?? 0 ?></h2>
                        <small>Soal</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card stats-card-warning border-0 d-print-none">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon">
                        <i class="ti ti-battery-2 text-white"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">Sedang</h6>
                        <h2 class="mb-0 fw-bold"><?= $stats['sedang'] ?? 0 ?></h2>
                        <small>Soal</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card stats-card-danger border-0 d-print-none">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon">
                        <i class="ti ti-battery-1 text-white"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">Sulit</h6>
                        <h2 class="mb-0 fw-bold"><?= $stats['sulit'] ?? 0 ?></h2>
                        <small>Soal</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark">Data Soal</h4>
        <small class="text-muted">Kelola soal dan pilihan jawaban untuk pembelajaran kaidah bahasa Arab</small>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('soal/test-lcm') ?>" class="btn btn-info me-2">
            <i class="ti ti-test-pipe me-2"></i>Test LCM
        </a>
        <a href="<?= site_url('soal/create') ?>" class="btn btn-primary">
            <i class="ti ti-circle-plus me-2"></i>Tambah Soal
        </a>
    </div>
</div>

<!-- Search and Filter -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="<?= site_url('soal') ?>" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="ti ti-search"></i>
                    </span>
                    <input type="text" class="form-control" name="search" placeholder="Cari pertanyaan..." value="<?= esc($search ?? '') ?>">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="materi">
                    <option value="">Semua Materi</option>
                    <?php foreach ($allMateri as $materi): ?>
                        <option value="<?= $materi['id_materi'] ?>" <?= ($materiFilter ?? '') == $materi['id_materi'] ? 'selected' : '' ?>>
                            <?= esc($materi['judul_kaidah']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="difficulty">
                    <option value="">Semua Tingkat</option>
                    <option value="mudah" <?= ($difficultyFilter ?? '') === 'mudah' ? 'selected' : '' ?>>Mudah</option>
                    <option value="sedang" <?= ($difficultyFilter ?? '') === 'sedang' ? 'selected' : '' ?>>Sedang</option>
                    <option value="sulit" <?= ($difficultyFilter ?? '') === 'sulit' ? 'selected' : '' ?>>Sulit</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="ti ti-search me-1"></i>Cari
                </button>
            </div>
            <div class="col-md-1">
                <a href="<?= site_url('soal') ?>" class="btn btn-secondary w-100">
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
            <table id="soalTable" class="table text-nowrap mb-0 align-middle datatable" data-type="soal">
                <thead class="text-dark">
                    <tr>
                        <th class="border-bottom-0">No</th>
                        <th class="border-bottom-0">Pertanyaan</th>
                        <th class="border-bottom-0">Materi</th>
                        <th class="border-bottom-0">Tingkat</th>
                        <th class="border-bottom-0">Poin</th>
                        <th class="border-bottom-0">Jawaban</th>
                        <th class="border-bottom-0 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($soal)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="ti ti-inbox fs-1 text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada soal</h5>
                            <p class="text-muted">Mulai tambahkan soal untuk pembelajaran kaidah bahasa Arab</p>
                            <a href="<?= site_url('soal/create') ?>" class="btn btn-primary">
                                <i class="ti ti-circle-plus me-2"></i>Tambah Soal
                            </a>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php $no = ($currentPage - 1) * $perPage + 1; ?>
                        <?php foreach ($soal as $item): ?>
                        <tr class="soal-card <?= $item['tingkat_kesulitan'] ?>">
                            <td><?= $no++ ?></td>
                            <td>
                                <div class="d-flex align-items-start">
                                    <div>
                                        <h6 class="mb-1 fw-semibold">
                                            <?php if (preg_match('/[\p{Arabic}]/u', $item['pertanyaan'])): ?>
                                                <span class="arabic-text"><?= esc($item['pertanyaan']) ?></span>
                                            <?php else: ?>
                                                <?php
                                                $pertanyaan = $item['pertanyaan'];
                                                if (strlen($pertanyaan) > 50) {
                                                    $pertanyaan = substr($pertanyaan, 0, 50) . '...';
                                                }
                                                echo esc($pertanyaan);
                                                ?>
                                            <?php endif; ?>
                                        </h6>
                                        <small class="text-muted">ID: <?= $item['id_soal'] ?> • Pembuat: <?= esc($item['nama_pembuat'] ?? 'Unknown') ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <small class="text-muted fw-semibold"><?= esc($item['judul_kaidah']) ?></small>
                                    <br>
                                    <span class="badge bg-light text-dark difficulty-badge">
                                        <?= ucfirst($item['tingkat_kesulitan_materi']) ?>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="badge rounded-3 badge-<?= $item['tingkat_kesulitan'] ?> difficulty-badge">
                                    <?= ucfirst($item['tingkat_kesulitan']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary rounded-3"><?= $item['poin'] ?></span>
                            </td>
                            <td>
                                <div>
                                    <?php if (isset($item['pilihan_jawaban']) && is_array($item['pilihan_jawaban'])): ?>
                                        <?php $correctCount = 0; ?>
                                        <?php foreach ($item['pilihan_jawaban'] as $jawaban): ?>
                                            <?php if (!empty($jawaban['is_benar'])) $correctCount++; ?>
                                        <?php endforeach; ?>
                                        <small class="text-muted"><?= count($item['pilihan_jawaban']) ?> opsi</small>
                                        <br>
                                        <span class="badge bg-success rounded-3"><?= $correctCount ?> benar</span>
                                    <?php else: ?>
                                        <small class="text-muted">Tidak ada jawaban</small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="table-actions justify-content-center">
                                    <!-- Preview -->
                                    <button type="button" class="btn btn-sm btn-info me-1"
                                            onclick="previewSoal(<?= $item['id_soal'] ?>)"
                                            title="Preview Soal">
                                        <i class="ti ti-eye"></i>
                                    </button>

                                    <!-- Edit -->
                                    <a href="<?= site_url('soal/' . $item['id_soal'] . '/edit') ?>"
                                       class="btn btn-sm btn-warning me-1"
                                       title="Edit">
                                        <i class="ti ti-edit"></i>
                                    </a>

                                    <!-- LCM Test -->
                                    <button type="button" class="btn btn-sm btn-secondary me-1"
                                            onclick="testLCMForMateri(<?= $item['id_materi'] ?>)"
                                            title="Test LCM">
                                        <i class="ti ti-test-pipe"></i>
                                    </button>

                                    <!-- Delete -->
                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            onclick="confirmDelete(<?= $item['id_soal'] ?>, '<?= esc(addslashes(substr($item['pertanyaan'], 0, 30))) ?>')"
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

        <!-- Pagination -->
        <?php if (isset($pager)): ?>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">
                    Menampilkan <?= count($soal) ?> dari <?= $total ?> data
                </small>
                <?= $pager->links('default', 'custom_pagination') ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg preview-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Soal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Content akan di-load via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for delete -->
<form id="deleteForm" method="POST" action="">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="DELETE">
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Delete confirmation function
function confirmDelete(id, title) {
    if (confirm(`Apakah Anda yakin ingin menghapus soal "${title}..."?\n\nData yang dihapus tidak dapat dikembalikan.`)) {
        const form = document.getElementById('deleteForm');
        form.action = '<?= site_url('soal') ?>/' + id;
        form.submit();
    }
}

// Preview soal function
function previewSoal(id) {
    fetch(`<?= site_url('soal') ?>/${id}`)
        .then(response => response.text())
        .then(html => {
            // Extract soal content dari response
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const soalContent = doc.querySelector('.card-body');

            if (soalContent) {
                document.getElementById('previewContent').innerHTML = soalContent.innerHTML;
            } else {
                document.getElementById('previewContent').innerHTML = '<p class="text-center text-muted">Data tidak tersedia</p>';
            }

            const modal = new bootstrap.Modal(document.getElementById('previewModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error loading preview:', error);
            document.getElementById('previewContent').innerHTML = '<p class="text-center text-danger">Gagal memuat data</p>';
            const modal = new bootstrap.Modal(document.getElementById('previewModal'));
            modal.show();
        });
}

// Test LCM for materi
function testLCMForMateri(materiId) {
    window.open(`<?= site_url('soal/preview-randomization') ?>/${materiId}`, '_blank');
}

// Initialize DataTable if needed
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#soalTable').DataTable({
            responsive: true,
            pageLength: 10,
            ordering: true,
            searching: false, // We use custom search
            paging: false, // We use custom pagination
            info: false,
            language: {
                "emptyTable": "Tidak ada data soal",
                "zeroRecords": "Tidak ditemukan data yang cocok",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "infoFiltered": "(difilter dari _MAX_ total data)"
            }
        });
    }

    // Auto-refresh statistics every 30 seconds
    setInterval(function() {
        fetch('<?= site_url('soal/statistics') ?>')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const stats = data.data;
                    // Update stats cards
                    document.querySelector('.stats-card-primary h2').textContent = stats.total || 0;
                    document.querySelector('.stats-card-success h2').textContent = stats.mudah || 0;
                    document.querySelector('.stats-card-warning h2').textContent = stats.sedang || 0;
                    document.querySelector('.stats-card-danger h2').textContent = stats.sulit || 0;
                }
            })
            .catch(error => console.error('Error fetching statistics:', error));
    }, 30000);
});
</script>
<?= $this->endSection() ?>