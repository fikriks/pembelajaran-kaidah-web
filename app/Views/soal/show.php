<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Detail Soal - <?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .detail-card {
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border-radius: 12px;
    }

    .arabic-text {
        font-family: 'Amiri', 'Traditional Arabic', serif;
        font-size: 1.3rem;
        line-height: 1.8;
        text-align: right;
        direction: rtl;
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        border-right: 4px solid #4CAF50;
    }

    .jawaban-card {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .jawaban-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .jawaban-correct {
        border-left: 4px solid #4CAF50;
        background: linear-gradient(90deg, rgba(76, 175, 80, 0.05) 0%, rgba(76, 175, 80, 0.02) 100%);
    }

    .jawaban-incorrect {
        border-left: 4px solid #e9ecef;
        background: #f8f9fa;
    }

    .badge-jawaban {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }

    .meta-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .meta-item i {
        margin-right: 0.75rem;
        width: 20px;
        text-align: center;
    }

    .btn-back {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: linear-gradient(135deg, #5a6268 0%, #495057 100%);
        transform: translateY(-2px);
        color: white;
        text-decoration: none;
    }

    .difficulty-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .difficulty-mudah {
        background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
        color: #0f5132;
    }

    .difficulty-sedang {
        background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        color: #664d03;
    }

    .difficulty-sulit {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        color: #842029;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('soal') ?>" class="text-muted">Manajemen Soal</a></li>
                    <li class="breadcrumb-item active">Detail Soal</li>
                </ol>
            </nav>
            <h4 class="fw-bold text-dark mb-0">Detail Soal</h4>
        </div>
        <div>
            <a href="<?= site_url('soal') ?>" class="btn-back">
                <i class="ti ti-arrow-left me-2"></i>
                Kembali ke Daftar Soal
            </a>
        </div>
    </div>

    <!-- Meta Information -->
    <div class="meta-info">
        <div class="row">
            <div class="col-md-8">
                <div class="meta-item">
                    <i class="ti ti-book"></i>
                    <span><strong>Materi:</strong> <?= esc($soal['judul_kaidah']) ?></span>
                </div>
                <div class="meta-item">
                    <i class="ti ti-hash"></i>
                    <span><strong>ID Soal:</strong> <?= $soal['id_soal'] ?></span>
                </div>
                <div class="meta-item">
                    <i class="ti ti-user"></i>
                    <span><strong>Dibuat oleh:</strong> <?= esc($soal['nama_pembuat']) ?></span>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="meta-item justify-content-md-end">
                    <span class="badge difficulty-badge difficulty-<?= $soal['tingkat_kesulitan'] ?>">
                        <i class="ti ti-chart-line me-1"></i>
                        <?= ucfirst($soal['tingkat_kesulitan']) ?>
                    </span>
                </div>
                <div class="meta-item justify-content-md-end">
                    <span class="badge bg-warning text-dark">
                        <i class="ti ti-star me-1"></i>
                        <?= $soal['poin'] ?> Poin
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Card -->
    <div class="card detail-card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="ti ti-help-circle me-2"></i>
                Pertanyaan
            </h5>
        </div>
        <div class="card-body">
            <div class="arabic-text">
                <?= esc($soal['pertanyaan']) ?>
            </div>
        </div>
    </div>

    <!-- Answers Section -->
    <div class="card detail-card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="ti ti-list-check me-2"></i>
                Pilihan Jawaban
            </h5>
        </div>
        <div class="card-body">
            <?php if (isset($soal['pilihan_jawaban']) && !empty($soal['pilihan_jawaban'])): ?>
                <?php foreach ($soal['pilihan_jawaban'] as $index => $jawaban): ?>
                    <div class="jawaban-card <?= $jawaban['is_benar'] == 1 ? 'jawaban-correct' : 'jawaban-incorrect' ?>">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="rounded-circle bg-<?= $jawaban['is_benar'] == 1 ? 'success' : 'secondary' ?> bg-opacity-10 p-3">
                                    <i class="ti ti-<?= $jawaban['is_benar'] == 1 ? 'circle-check' : 'circle-x' ?> text-<?= $jawaban['is_benar'] == 1 ? 'success' : 'secondary' ?> fs-4"></i>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-0">Opsi <?= chr(65 + $jawaban['urutan']) ?></h6>
                                    <?php if ($jawaban['is_benar'] == 1): ?>
                                        <span class="badge badge-jawaban bg-success">
                                            <i class="ti ti-check me-1"></i>Jawaban Benar
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-jawaban bg-secondary">
                                            <i class="ti ti-x me-1"></i>Jawaban Salah
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="arabic-text" style="font-size: 1.1rem; margin-bottom: 0;">
                                    <?= esc($jawaban['teks_jawaban']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-warning">
                    <i class="ti ti-alert-triangle me-2"></i>
                    <strong>Peringatan:</strong> Soal ini belum memiliki pilihan jawaban.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Statistics Info -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="ti ti-list-numbers text-primary fs-2 mb-3"></i>
                    <h5 class="card-title">Total Pilihan</h5>
                    <h2 class="text-primary"><?= count($soal['pilihan_jawaban']) ?></h2>
                    <p class="text-muted mb-0">Opsi jawaban</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="ti ti-circle-check text-success fs-2 mb-3"></i>
                    <h5 class="card-title">Jawaban Benar</h5>
                    <h2 class="text-success">
                        <?php
                        $correctCount = 0;
                        foreach ($soal['pilihan_jawaban'] as $jawaban) {
                            if ($jawaban['is_benar'] == 1) $correctCount++;
                        }
                        echo $correctCount;
                        ?>
                    </h2>
                    <p class="text-muted mb-0">Jawaban yang benar</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="ti ti-calendar-event text-info fs-2 mb-3"></i>
                    <h5 class="card-title">Dibuat</h5>
                    <h2 class="text-info"><?= date('d', strtotime($soal['waktu_dibuat'])) ?></h2>
                    <p class="text-muted mb-0"><?= date('M Y', strtotime($soal['waktu_dibuat'])) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex gap-3 mt-4">
        <a href="<?= site_url('soal/edit/' . $soal['id_soal']) ?>" class="btn btn-warning">
            <i class="ti ti-edit me-2"></i>
            Edit Soal
        </a>
        <button type="button" class="btn btn-danger" onclick="confirmDelete(<?= $soal['id_soal'] ?>)">
            <i class="ti ti-trash me-2"></i>
            Hapus Soal
        </button>
        <button type="button" class="btn btn-info" onclick="window.print()">
            <i class="ti ti-printer me-2"></i>
            Cetak
        </button>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus soal ini? Tindakan ini tidak dapat dibatalkan.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= site_url('soal/delete/') ?>${id}`;

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

// Print functionality - hide action buttons when printing
window.addEventListener('beforeprint', function() {
    document.querySelector('.btn-back').style.display = 'none';
    document.querySelector('.d-flex.gap-3').style.display = 'none';
});

window.addEventListener('afterprint', function() {
    document.querySelector('.btn-back').style.display = 'inline-flex';
    document.querySelector('.d-flex.gap-3').style.display = 'flex';
});
</script>
<?= $this->endSection() ?>