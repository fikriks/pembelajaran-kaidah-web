<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Manajemen Materi Kaidah - <?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
/* Chapter Container */
.chapters-container {
    margin-top: 1rem;
}

/* Chapter Card */
.chapter-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    margin-bottom: 2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    overflow: hidden;
}

.chapter-header {
    background: #f8f9fa;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e9ecef;
}

.chapter-title {
    color: #495057;
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0;
}

.chapter-description {
    color: #6c757d;
    font-size: 0.9rem;
    margin: 0.5rem 0 0 0;
}

/* Kaidah Grid */
.kaidah-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
    padding: 1.5rem;
}

/* Kaidah Card */
.kaidah-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    min-height: 180px;
}

.kaidah-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    border-color: #4CAF50;
}

/* Kaidah Number */
.kaidah-number {
    background: #4CAF50;
    color: white;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

/* Kaidah Title */
.kaidah-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #495057;
    margin: 0 0 0.75rem 0;
    line-height: 1.4;
    min-height: 2.8rem;
    display: flex;
    align-items: center;
}

.kaidah-title .arabic-text {
    font-family: 'Amiri', 'Traditional Arabic', serif;
    font-size: 1.2rem;
    text-align: right;
    direction: rtl;
}

/* Kaidah Description */
.kaidah-description {
    color: #6c757d;
    font-size: 0.9rem;
    margin: 0 0 1rem 0;
    line-height: 1.5;
    flex-grow: 1;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Kaidah Actions */
.kaidah-actions {
    position: absolute;
    top: 1rem;
    right: 1rem;
    display: flex;
    gap: 0.5rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.kaidah-card:hover .kaidah-actions {
    opacity: 1;
}

.btn-action {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    font-size: 1rem;
}

.btn-edit {
    background: #ffc107;
    color: #212529;
}

.btn-edit:hover {
    background: #e0a800;
    color: #212529;
}

.btn-delete {
    background: #dc3545;
    color: white;
}

.btn-delete:hover {
    background: #c82333;
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .kaidah-grid {
        grid-template-columns: 1fr;
        padding: 1rem;
    }

    .kaidah-card {
        min-height: 160px;
    }

    .kaidah-actions {
        opacity: 1;
        position: static;
        margin-top: 1rem;
        justify-content: flex-end;
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
            'value' => count(array_unique(array_column($kaidah ?? [], 'nama_bab'))),
            'subtitle' => 'Bab',
            'icon' => 'folder',
            'variant' => 'info'
        ]
    ]
]) ?>

<!-- Action Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark">Materi Pembelajaran Kaidah</h4>
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
        $bab = $item['nama_bab'] ?? 'Uncategorized';
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
        <div class="chapter-card" data-chapter="<?= urlencode($chapter['name']) ?>">
            <!-- Chapter Header -->
            <div class="chapter-header">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="chapter-title">
                            <i class="ti ti-folder me-2"></i>
                            <?= esc($chapter['name']) ?>
                        </h5>
                        <p class="chapter-description"><?= esc($chapter['description']) ?></p>
                    </div>
                </div>
            </div>

            <!-- Kaidah Grid -->
            <div class="kaidah-grid">
                <?php foreach ($chapter['kaidah'] as $item): ?>
                <div class="kaidah-card" onclick="window.location.href='<?= site_url('kaidah/' . $item['id_materi'] . '/show') ?>'">
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
                    <!-- Kaidah Actions -->
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Delete confirmation function
function confirmDelete(id, title) {
    if (confirm('Apakah Anda yakin ingin menghapus materi "' + title + '"?')) {
        const form = document.getElementById('deleteForm');
        form.action = '<?= site_url('kaidah') ?>' + '/' + id;
        form.submit();
    }
}
</script>
<?= $this->endSection() ?>