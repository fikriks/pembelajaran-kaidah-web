<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Detail Quiz - <?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h4 class="fw-bold text-dark">Detail Quiz</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('quiz-monitoring') ?>" class="text-muted">Monitoring Quiz</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </nav>
        </div>
        <a href="<?= site_url('quiz-monitoring') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="ti ti-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<!-- Quiz Summary Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Informasi Siswa</h6>
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                <i class="ti ti-user text-primary fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold"><?= esc($siswa['nama_lengkap']) ?></h5>
                                <p class="text-muted mb-0">NIS: <?= esc($siswa['nis']) ?> • Kelas: <?= esc($siswa['kelas']) ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Informasi Quiz</h6>
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                                <i class="ti ti-file-description text-success fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold"><?= esc($materi['judul_kaidah']) ?></h5>
                                <p class="text-muted mb-0"><?= formatDate($sesi['waktu_mulai']) ?> • <?= formatTime($sesi['waktu_mulai']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row text-center">
                    <div class="col-6 col-md-2">
                        <div class="border rounded p-3">
                            <h3 class="mb-1 fw-bold text-primary"><?= $sesi['skor'] ?></h3>
                            <small class="text-muted">Skor Akhir</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="border rounded p-3">
                            <h3 class="mb-1 fw-bold text-success"><?= $sesi['soal_benar'] ?>/<?= $sesi['total_soal'] ?></h3>
                            <small class="text-muted">Jawaban Benar</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="border rounded p-3">
                            <h3 class="mb-1 fw-bold text-info"><?= round(($sesi['soal_benar']/$sesi['total_soal'])*100, 1) ?>%</h3>
                            <small class="text-muted">Persentase</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="border rounded p-3">
                            <h3 class="mb-1 fw-bold text-warning"><?= formatDuration($sesi['durasi_detik']) ?></h3>
                            <small class="text-muted">Durasi</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="border rounded p-3">
                            <h3 class="mb-1 fw-bold"><?= getStatusBadge($sesi['status']) ?></h3>
                            <small class="text-muted">Status</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Answers -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-0 bg-white py-3">
                <h5 class="card-title mb-0 fw-bold">Detail Jawaban Siswa</h5>
                <small class="text-muted">Lihat semua jawaban siswa untuk setiap soal</small>
            </div>
            <div class="card-body">
                <div id="answersContainer">
                    <?php if (empty($answers)): ?>
                        <div class="text-center py-5">
                            <i class="ti ti-inbox fs-1 text-muted mb-2"></i>
                            <p class="text-muted">Belum ada jawaban untuk sesi ini</p>
                        </div>
                    <?php else: ?>
                        <?php $questionNumber = 1; ?>
                        <?php foreach ($answers as $answer): ?>
                            <div class="border rounded p-4 mb-3 <?= $answer['is_benar'] ? 'border-success bg-white' : 'border-danger bg-white' ?>">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                            <span class="fw-bold text-primary"><?= $questionNumber ?></span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold">Pertanyaan:</h6>
                                            <p class="mb-2"><?= esc($answer['pertanyaan']) ?></p>
                                            <small class="text-muted">
                                                <i class="ti ti-book me-1"></i><?= esc($answer['judul_kaidah']) ?>
                                                <?php if ($answer['tipe_soal']): ?>
                                                    • <i class="ti ti-tag me-1"></i><?= esc(ucfirst($answer['tipe_soal'])) ?>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <?php if ($answer['is_benar']): ?>
                                            <span class="badge bg-success rounded-3">
                                                <i class="ti ti-check me-1"></i>Benar
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger rounded-3">
                                                <i class="ti ti-x me-1"></i>Salah
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="ms-5">
                                    <h6 class="mb-3">Pilihan Jawaban:</h6>
                                    <div class="row">
                                        <?php foreach ($answer['semua_pilihan'] as $option): ?>
                                            <div class="col-12 mb-2">
                                                <div class="d-flex align-items-center p-3 rounded <?= getOptionClass($option) ?>">
                                                    <div class="form-check me-3">
                                                        <input class="form-check-input" type="radio"
                                                               name="question_<?= $answer['id_soal'] ?>"
                                                               id="option_<?= $option['id_pilihan'] ?>"
                                                               <?= $option['is_selected'] ? 'checked' : '' ?>
                                                               disabled>
                                                    </div>
                                                    <label class="form-check-label flex-grow-1" for="option_<?= $option['id_pilihan'] ?>">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span><?= esc($option['teks_jawaban']) ?></span>
                                                            <div>
                                                                <?php if ($option['is_benar']): ?>
                                                                    <span class="badge bg-success rounded-3 me-2">
                                                                        <i class="ti ti-star me-1"></i>Kunci Jawaban
                                                                    </span>
                                                                <?php endif; ?>
                                                                <?php if ($option['is_selected']): ?>
                                                                    <span class="badge bg-primary rounded-3">
                                                                        <i class="ti ti-user-check me-1"></i>Dipilih Siswa
                                                                    </span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <?php $questionNumber++; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// No additional JavaScript needed for static view
</script>

<?php
// Helper functions
function formatDate($dateString) {
    if (!$dateString) return '-';
    $date = new DateTime($dateString);
    return $date->format('d M Y');
}

function formatTime($dateString) {
    if (!$dateString) return '-';
    $date = new DateTime($dateString);
    return $date->format('H:i');
}

function formatDuration($seconds) {
    if (!$seconds || $seconds == 0) return '0 detik';

    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $seconds = $seconds % 60;

    $parts = [];
    if ($hours > 0) $parts[] = $hours . ' jam';
    if ($minutes > 0) $parts[] = $minutes . ' menit';
    if ($seconds > 0 || empty($parts)) $parts[] = $seconds . ' detik';

    return implode(' ', $parts);
}

function getStatusBadge($status) {
    switch ($status) {
        case 'selesai':
            return '<span class="badge bg-success rounded-3">Selesai</span>';
        case 'sedang_berlangsung':
            return '<span class="badge bg-warning rounded-3">Berlangsung</span>';
        case 'dibatalkan':
            return '<span class="badge bg-danger rounded-3">Dibatalkan</span>';
        default:
            return '<span class="badge bg-secondary rounded-3">' . ucfirst($status) . '</span>';
    }
}

function getOptionClass($option) {
    if ($option['is_benar'] && $option['is_selected']) {
        return 'border border-2 border-success bg-white'; // Correct answer selected
    } elseif ($option['is_benar']) {
        return 'border border-2 border-success bg-white';  // Correct answer not selected
    } elseif ($option['is_selected']) {
        return 'border border-2 border-danger bg-white';   // Wrong answer selected
    } else {
        return 'border border-2 border-secondary bg-white'; // Other options
    }
}
?>
<?= $this->endSection() ?>