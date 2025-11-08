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

    /* Chapter card styling */
    .chapter-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.07);
        margin-bottom: 2rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .chapter-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.12);
    }

    .chapter-header {
        padding: 1.5rem;
        border-left: 5px solid #4CAF50;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .chapter-header.locked {
        border-left-color: #9E9E9E;
        background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
    }

    .chapter-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #212121;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .chapter-title.locked {
        color: #616161;
    }

    .chapter-description {
        color: #616161;
        font-size: 0.9rem;
        margin: 0.5rem 0 0 0;
    }

    .chapter-progress {
        margin-top: 1rem;
    }

    .progress-bar {
        height: 8px;
        background: #E0E0E0;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #4CAF50, #66BB6A);
        border-radius: 4px;
        transition: width 0.5s ease;
    }

    .chapter-stats {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 0.5rem;
        font-size: 0.85rem;
        color: #757575;
    }

    .unlock-badge {
        background: #4CAF50;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .unlock-badge.locked {
        background: #9E9E9E;
    }

    /* Kaidah card styling */
    .kaidah-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1rem;
        padding: 1.5rem;
    }

    .kaidah-card {
        background: white;
        border-radius: 8px;
        padding: 1.25rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border-left: 4px solid #4CAF50;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .kaidah-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    .kaidah-card.disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background: #f5f5f5;
    }

    .kaidah-card.disabled:hover {
        transform: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .kaidah-card.disabled .kaidah-number {
        background: #9E9E9E;
    }

    .kaidah-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: #4CAF50;
        color: white;
        border-radius: 50%;
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 0.75rem;
    }

    .kaidah-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #212121;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }

    .kaidah-description {
        color: #616161;
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .kaidah-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 0.75rem;
        border-top: 1px solid #E0E0E0;
    }

    .kaidah-status {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
    }

    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.75rem;
    }

    .status-belum-dimulai {
        background: #F3E5F5;
        color: #7B1FA2;
    }

    .status-sedang-belajar {
        background: #E3F2FD;
        color: #1976D2;
    }

    .status-selesai {
        background: #E8F5E9;
        color: #388E3C;
    }

    .kaidah-actions {
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

    .btn-edit {
        background: #FFF3E0;
        color: #F57C00;
    }

    .btn-delete {
        background: #FFEBEE;
        color: #D32F2F;
    }

    /* View toggle */
    .view-toggle {
        display: flex;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .view-toggle button {
        padding: 0.75rem 1rem;
        border: none;
        background: white;
        color: #616161;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .view-toggle button.active {
        background: #4CAF50;
        color: white;
    }

    /* Stats card animation */
    .stats-card {
        transition: transform 0.2s ease;
    }

    .stats-card:hover {
        transform: translateY(-3px);
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .kaidah-grid {
            grid-template-columns: 1fr;
        }

        .chapter-header {
            padding: 1rem;
        }

        .chapter-stats {
            flex-direction: column;
            gap: 0.5rem;
            align-items: flex-start;
        }
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
            'title' => 'Total Chapter',
            'value' => count(array_unique(array_column($kaidah ?? [], 'bab'))),
            'subtitle' => 'Bab',
            'icon' => 'folder',
            'variant' => 'info'
        ]
    ]
]) ?>

<!-- Action Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark">Pembelajaran Berbasis Chapter</h4>
        <small class="text-muted">Kelola materi pembelajaran kaidah bahasa Arab per chapter</small>
    </div>
    <div>
        <a href="<?= site_url('kaidah/create') ?>" class="btn btn-primary">
            <i class="ti ti-circle-plus me-2"></i>Tambah Materi
        </a>
    </div>
</div>

<!-- Chapter-based Layout -->
<div class="chapters-container">
    <?php
    // Group kaidah by bab
    $chapters = [];
    foreach ($kaidah as $item) {
        $bab = $item['bab'] ?? 'Uncategorized';
        if (!isset($chapters[$bab])) {
            $chapters[$bab] = [
                'name' => $bab,
                'description' => $item['deskripsi_bab'] ?? 'Deskripsi chapter',
                'kaidah' => []
            ];
        }
        $chapters[$bab]['kaidah'][] = $item;
    }
    ?>

    <?php if (empty($chapters)): ?>
    <!-- Empty State -->
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="ti ti-folder-off fs-1 text-muted mb-3"></i>
            <h5 class="text-muted">Belum ada materi kaidah</h5>
            <p class="text-muted">Mulai tambahkan materi pembelajaran kaidah bahasa Arab</p>
            <a href="<?= site_url('kaidah/create') ?>" class="btn btn-primary">
                <i class="ti ti-circle-plus me-2"></i>Tambah Materi Pertama
            </a>
        </div>
    </div>
    <?php else: ?>
        <?php foreach ($chapters as $chapter): ?>
        <?php
        // Get actual progress data for this chapter
        $chapterStats = $chapterProgress[$chapter['name']] ?? ['progress_percentage' => 0, 'is_unlocked' => true, 'total_materi' => 0];
        $isLocked = !$chapterStats['is_unlocked'];
        $progressPercent = round($chapterStats['progress_percentage']);
        ?>
        <div class="chapter-card" data-chapter-code="<?= ($chapter['name'] === 'BAB 2: I\'RAB') ? 'bab2' : 'bab1' ?>">
            <!-- Chapter Header -->
            <div class="chapter-header <?= $isLocked ? 'locked' : '' ?>">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="chapter-title <?= $isLocked ? 'locked' : '' ?>">
                            <i class="ti ti-<?= $isLocked ? 'lock' : 'folder' ?> me-2"></i>
                            <?= esc($chapter['name']) ?>
                        </h5>
                        <p class="chapter-description"><?= esc($chapter['description']) ?></p>
                    </div>
                    <span class="unlock-badge <?= $isLocked ? 'locked' : '' ?>">
                        <i class="ti ti-<?= $isLocked ? 'lock' : 'lock-open' ?> me-1"></i>
                        <?= $isLocked ? 'Terkunci' : 'Terbuka' ?>
                    </span>
                </div>

                <!-- Chapter Progress -->
                <div class="chapter-progress">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?= $progressPercent ?>%"></div>
                    </div>
                    <div class="chapter-stats">
                        <span><?= count($chapter['kaidah']) ?> materi</span>
                        <span>Progress: <?= $progressPercent ?>% (<?= $chapterStats['total_materi'] ?? 0 ?> total)</span>
                    </div>
                </div>
            </div>

            <!-- Kaidah Grid -->
            <div class="kaidah-grid">
                <?php
                // Get individual materi progress from chapter stats
                $materiProgress = $chapterStats['materi'] ?? [];
                // Create associative array for easier lookup
                $progressMap = [];
                foreach ($materiProgress as $materi) {
                    $progressMap[$materi['id_materi']] = $materi;
                }

                foreach ($chapter['kaidah'] as $item):
                    // Get progress for this specific kaidah
                    $kaidahProgress = $progressMap[$item['id_materi']] ?? [
                        'status' => 'belum_dimulai',
                        'persentase_penguasaan' => 0
                    ];

                    // Determine status badge class and text
                    $statusClass = 'status-belum-dimulai';
                    $statusText = 'Belum Dimulai';

                    if ($kaidahProgress['status'] === 'selesai') {
                        $statusClass = 'status-selesai';
                        $statusText = 'Selesai';
                    } elseif ($kaidahProgress['status'] === 'sedang_belajar') {
                        $statusClass = 'status-sedang-belajar';
                        $statusText = 'Sedang Belajar';
                    }

                    $progressPercent = round($kaidahProgress['persentase_penguasaan']);
                ?>
                <div class="kaidah-card <?= $isLocked ? 'disabled' : '' ?>" onclick="<?= $isLocked ? 'event.preventDefault(); alert(\'Chapter terkunci! Selesaikan ' . ($chapter['name'] === 'BAB 2: I\\\'RAB' ? 'BAB 1: KALAM' : 'chapter sebelumnya') . ' terlebih dahulu.\')' : 'viewKaidah(' . $item['id_materi'] . ')' ?>">
                    <!-- Kaidah Number -->
                    <div class="kaidah-number">
                        <?= $item['urutan'] ?>
                    </div>

                    <!-- Kaidah Title -->
                    <h6 class="kaidah-title">
                        <?php if (preg_match('/[\p{Arabic}]/u', $item['judul_kaidah'])): ?>
                            <span class="arabic-text"><?= esc($item['judul_kaidah']) ?></span>
                        <?php else: ?>
                            <?= esc($item['judul_kaidah']) ?>
                        <?php endif; ?>
                    </h6>

                    <!-- Kaidah Description -->
                    <p class="kaidah-description">
                        <?= esc($item['deskripsi'] ?? 'Tidak ada deskripsi') ?>
                    </p>

                    <!-- Kaidah Footer -->
                    <div class="kaidah-footer">
                        <div class="kaidah-status">
                            <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                            <span class="text-muted"><?= $progressPercent ?>% selesai</span>
                        </div>
                        <div class="kaidah-actions">
                            <a href="<?= site_url('kaidah/' . $item['id_materi'] . '/edit') ?>"
                               class="btn-action btn-edit"
                               title="Edit"
                               onclick="event.stopPropagation();">
                                <i class="ti ti-edit"></i>
                            </a>
                            <button type="button"
                                    class="btn-action btn-delete"
                                    onclick="event.stopPropagation(); confirmDelete(<?= $item['id_materi'] ?>, '<?= esc(addslashes($item['judul_kaidah'])) ?>')"
                                    title="Hapus">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
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

<!-- Modal for viewing kaidah details -->
<div class="modal fade" id="kaidahModal" tabindex="-1" aria-labelledby="kaidahModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kaidahModalLabel">Detail Materi Kaidah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="kaidahModalContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="#" id="editKaidahBtn" class="btn btn-warning">
                    <i class="ti ti-edit me-2"></i>Edit
                </a>
            </div>
        </div>
    </div>
</div>
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

// View kaidah details in modal
function viewKaidah(id) {
    const modal = new bootstrap.Modal(document.getElementById('kaidahModal'));
    const content = document.getElementById('kaidahModalContent');
    const editBtn = document.getElementById('editKaidahBtn');

    // Show loading state
    content.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memuat detail materi...</p>
        </div>
    `;

    // Fetch kaidah details
    fetch(`<?= site_url('kaidah/') ?>${id}/show`)
        .then(response => response.text())
        .then(html => {
            content.innerHTML = html;
            editBtn.href = `<?= site_url('kaidah/') ?>${id}/edit`;
        })
        .catch(error => {
            content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="ti ti-alert-circle me-2"></i>
                    Gagal memuat detail materi. Silakan coba lagi.
                </div>
            `;
        });

    modal.show();
}

// Auto-refresh statistics every 30 seconds
setInterval(function() {
    fetch('<?= site_url('kaidah/statistics') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const stats = data.data;
                // Update stats cards if needed
                const totalCard = document.querySelector('.stats-card-primary h2');
                if (totalCard) totalCard.textContent = stats.total || 0;

                const chapterCard = document.querySelector('.stats-card-info h2');
                if (chapterCard) chapterCard.textContent = stats.total_chapter || 0;
            }
        })
        .catch(error => console.error('Error fetching statistics:', error));
}, 30000);

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Smooth scroll to chapter when clicking on chapter header
document.querySelectorAll('.chapter-header').forEach(function(header) {
    header.style.cursor = 'pointer';
    header.addEventListener('click', function() {
        const chapterCard = this.closest('.chapter-card');
        const kaidahGrid = chapterCard.querySelector('.kaidah-grid');

        if (kaidahGrid) {
            kaidahGrid.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    });
});
</script>
<?= $this->endSection() ?>