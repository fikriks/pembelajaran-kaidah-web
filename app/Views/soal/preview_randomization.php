<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Preview Randomization - <?= $materi['judul_kaidah'] ?> <?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .lcm-info {
        background: #ffffff;
        color: #333333;
        padding: 1.5rem;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
        border: 1px solid #e9ecef;
    }

    .lcm-params {
        font-family: 'Courier New', monospace;
        background: rgba(0,0,0,0.2);
        padding: 0.5rem;
        border-radius: 0.25rem;
        margin-top: 0.5rem;
    }

    .arabic-text {
        font-family: 'Amiri', 'Traditional Arabic', serif;
        font-size: 1.1rem;
        line-height: 1.8;
        text-align: right;
        direction: rtl;
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        border-right: 4px solid #4CAF50;
    }

    .soal-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .soal-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .soal-number {
        display: inline-block;
        background: #4CAF50;
        color: white;
        width: 30px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        border-radius: 50%;
        margin-bottom: 0.5rem;
        font-weight: bold;
    }

    .debug-info {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 2rem;
    }

    .sequence-item {
        font-family: 'Courier New', monospace;
        background: #f8f9fa;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        margin: 0.25rem;
        display: inline-block;
    }

    .jawaban-option {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
    }

    .jawaban-option.correct {
        background: #d4edda;
        border-color: #c3e6cb;
        border-left: 4px solid #28a745;
    }

    .meta-info {
        background: #ffffff;
        color: #333333;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        border: 1px solid #e9ecef;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark">Preview Randomization LCM</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('soal') ?>" class="text-muted">Manajemen Soal</a></li>
                    <li class="breadcrumb-item active">Preview Randomization</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= site_url('soal') ?>" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Meta Information -->
    <div class="meta-info">
        <h5 class="mb-2">
            <i class="ti ti-book me-2"></i><?= esc($materi['judul_kaidah']) ?>
        </h5>
        <p class="mb-0">Preview randomisasi soal menggunakan Linear Congruent Method (LCM)</p>
    </div>

    <!-- LCM Algorithm Info -->
    <div class="lcm-info">
        <div class="d-flex align-items-center">
            <i class="ti ti-info-circle me-3 fs-4"></i>
            <div>
                <h6 class="mb-1">Linear Congruent Method (LCM) Algorithm</h6>
                <p class="mb-0">Parameter LCM yang digunakan untuk penelitian skripsi:</p>
                <div class="lcm-params">
                    Xn+1 = (a × Xn + c) mod m | a = <?= $lcmParameters['multiplier'] ?>, c = <?= $lcmParameters['increment'] ?>, m = <?= $lcmParameters['modulus'] ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="ti ti-list text-primary mb-3" style="font-size: 3.5rem;"></i>
                    <h6 class="card-title">Total Soal</h6>
                    <h2 class="text-primary"><?= count($allSoal) ?></h2>
                    <p class="text-muted mb-0">Tersedia</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="ti ti-eye text-success mb-3" style="font-size: 3.5rem;"></i>
                    <h6 class="card-title">Preview</h6>
                    <h2 class="text-success"><?= count($previewResult['questions'] ?? []) ?></h2>
                    <p class="text-muted mb-0">Soal</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="ti ti-rotate-clockwise text-warning mb-3" style="font-size: 3.5rem;"></i>
                    <h6 class="card-title">Seed</h6>
                    <h2 class="text-warning"><?= $previewResult['seed'] ?? 'N/A' ?></h2>
                    <p class="text-muted mb-0">Random Seed</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="ti ti-code text-info mb-3" style="font-size: 3.5rem;"></i>
                    <h6 class="card-title">Sequence</h6>
                    <h2 class="text-info"><?= $previewResult['sequence'] ?? [] ? count($previewResult['sequence']) : 0 ?></h2>
                    <p class="text-muted mb-0">Items</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Results -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white text-dark border-bottom">
            <h5 class="mb-0">
                <i class="ti ti-eye me-2"></i>Preview Hasil Randomisasi
            </h5>
        </div>
        <div class="card-body">
            <?php if (!empty($previewResult['questions'])): ?>
                <?php foreach ($previewResult['questions'] as $index => $question): ?>
                    <div class="soal-card">
                        <div class="d-flex align-items-start mb-3">
                            <div class="soal-number me-3"><?= $index + 1 ?></div>
                            <div class="flex-grow-1">
                                <div class="arabic-text">
                                    <?= esc($question['pertanyaan']) ?>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    ID Asli: <?= $question['id_soal'] ?> | Urutan Random: <?= $previewResult['sequence'][$index] ?? 'N/A' ?>
                                </small>
                            </div>
                        </div>

                        <?php if (!empty($question['pilihan_jawaban'])): ?>
                            <div class="mt-3">
                                <small class="text-muted fw-semibold">Pilihan Jawaban:</small>
                                <?php foreach ($question['pilihan_jawaban'] as $jawaban): ?>
                                    <div class="jawaban-option <?= $jawaban['is_benar'] ? 'correct' : '' ?>">
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <?php if ($jawaban['is_benar']): ?>
                                                    <i class="ti ti-circle-check text-success"></i>
                                                <?php else: ?>
                                                    <i class="ti ti-circle text-secondary"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-grow-1">
                                                <?= esc($jawaban['teks_jawaban']) ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-warning">
                    <i class="ti ti-alert-triangle me-2"></i>
                    <strong>Peringatan:</strong> Tidak ada data preview yang tersedia.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Debug Information -->
    <div class="debug-info">
        <h6 class="mb-3">
            <i class="ti ti-bug me-2"></i>Debug Information
        </h6>

        <div class="row">
            <div class="col-md-6">
                <h6 class="text-muted mb-2">LCM Sequence (5 items):</h6>
                <div>
                    <?php if (!empty($debugInfo['sequence'])): ?>
                        <?php foreach ($debugInfo['sequence'] as $i => $seq): ?>
                            <span class="sequence-item">Step <?= $i ?>: <?= is_array($seq) ? json_encode($seq) : $seq ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="text-muted">Tidak ada data sequence</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted mb-2">Random Seeds:</h6>
                <div>
                    <?php if (!empty($debugInfo['seeds'])): ?>
                        <?php foreach ($debugInfo['seeds'] as $i => $seed): ?>
                            <span class="sequence-item">Seed <?= $i ?>: <?= is_array($seed) ? json_encode($seed) : $seed ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="text-muted">Tidak ada data seed</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// No additional scripts needed
</script>
<?= $this->endSection() ?>