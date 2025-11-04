<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>LCM Algorithm Testing - <?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .lcm-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
    }

    .lcm-formula {
        font-family: 'Courier New', monospace;
        background: rgba(0,0,0,0.2);
        padding: 1rem;
        border-radius: 0.25rem;
        font-size: 1.2rem;
        text-align: center;
        margin: 1rem 0;
    }

    .lcm-params {
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1rem;
    }

    .param-item {
        text-align: center;
    }

    .param-value {
        font-size: 1.5rem;
        font-weight: bold;
        display: block;
    }

    .test-card {
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .test-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 12px rgba(0,0,0,0.15);
    }

    .sequence-item {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 0.25rem;
        padding: 0.5rem;
        margin-bottom: 0.5rem;
        font-family: 'Courier New', monospace;
    }

    .sequence-formula {
        color: #6c757d;
        font-size: 0.875rem;
    }

    .sequence-result {
        font-weight: bold;
        color: #495057;
    }

    .chi-square-result {
        padding: 1.5rem;
        border-radius: 0.5rem;
        text-align: center;
    }

    .chi-square-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .chi-square-fail {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    .distribution-bar {
        height: 30px;
        background: #e9ecef;
        border-radius: 15px;
        overflow: hidden;
        position: relative;
        margin-bottom: 0.5rem;
    }

    .distribution-fill {
        height: 100%;
        background: linear-gradient(90deg, #4CAF50, #8BC34A);
        transition: width 0.5s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 0.875rem;
    }

    .debug-item {
        background: #fff;
        border-left: 4px solid #007bff;
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 0.25rem;
    }

    .debug-formula {
        background: #f8f9fa;
        padding: 0.5rem;
        border-radius: 0.25rem;
        font-family: 'Courier New', monospace;
        margin: 0.5rem 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: bold;
        color: #007bff;
    }

    .stat-label {
        color: #6c757d;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .alert-info {
        background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
        border-color: #bee5eb;
    }

    .btn-test {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border: none;
        color: white;
    }

    .btn-test:hover {
        background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
        transform: translateY(-1px);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark">LCM Algorithm Testing</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('soal') ?>" class="text-muted">Manajemen Soal</a></li>
                <li class="breadcrumb-item active">LCM Testing</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="<?= site_url('soal') ?>" class="btn btn-secondary">
            <i class="ti ti-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<!-- LCM Algorithm Header -->
<div class="lcm-header">
    <div class="text-center">
        <h3 class="mb-3">
            <i class="ti ti-calculator me-2"></i>
            Linear Congruent Method (LCM) Algorithm
        </h3>
        <p class="mb-0">Algoritma pengacakan untuk penelitian skripsi pembelajaran kaidah bahasa Arab</p>
        <div class="lcm-formula">
            X<sub>n+1</sub> = (a × X<sub>n</sub> + c) mod m
        </div>
        <div class="lcm-params">
            <div class="param-item">
                <span class="param-value">a = <?= $lcmParameters['multiplier'] ?></span>
                <small>Multiplier</small>
            </div>
            <div class="param-item">
                <span class="param-value">c = <?= $lcmParameters['increment'] ?></span>
                <small>Increment</small>
            </div>
            <div class="param-item">
                <span class="param-value">m = <?= $lcmParameters['modulus'] ?></span>
                <small>Modulus</small>
            </div>
            <div class="param-item">
                <span class="param-value">X<sub>0</sub> = <?= $currentSeed ?></span>
                <small>Current Seed</small>
            </div>
        </div>
    </div>
</div>

<!-- Alert Info -->
<div class="alert alert-info d-flex align-items-center" role="alert">
    <i class="ti ti-info-circle me-2"></i>
    <div>
        <strong>Informasi Testing:</strong>
        Halaman ini digunakan untuk validasi akademik algoritma LCM yang digunakan dalam penelitian skripsi.
        Chi-square test digunakan untuk membuktikan bahwa distribusi bilangan acak yang dihasilkan seragam (uniform distribution).
    </div>
</div>

<!-- Test Controls -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card test-card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti ti-settings me-2"></i>Pengaturan Testing
                </h5>
                <form method="GET" action="<?= site_url('soal/test-lcm') ?>" class="row g-3">
                    <div class="col-md-4">
                        <label for="sample_size" class="form-label">Sample Size</label>
                        <input type="number" class="form-control" id="sample_size" name="sample_size"
                               value="<?= $sampleSize ?>" min="100" max="10000" step="100">
                        <div class="form-text">Jumlah sample untuk chi-square test</div>
                    </div>
                    <div class="col-md-4">
                        <label for="seed" class="form-label">Seed (Opsional)</label>
                        <input type="number" class="form-control" id="seed" name="seed"
                               value="<?= old('seed') ?>" placeholder="Kosongkan untuk random seed">
                        <div class="form-text">Seed untuk reproducible results</div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-test w-100">
                            <i class="ti ti-test-pipe me-2"></i>Run Test
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Results Section -->
<div class="row">
    <!-- Chi-Square Test Result -->
    <div class="col-md-6 mb-4">
        <div class="card test-card h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti ti-chart-bar me-2"></i>Chi-Square Test Result
                </h5>

                <div class="chi-square-result <?= $chiSquareResult['is_uniform_distribution'] ? 'chi-square-success' : 'chi-square-fail' ?>">
                    <h4 class="mb-2">
                        <?= $chiSquareResult['is_uniform_distribution'] ? '✓ PASSED' : '✗ FAILED' ?>
                    </h4>
                    <p class="mb-0">
                        <?= $chiSquareResult['conclusion'] ?>
                    </p>
                </div>

                <div class="stats-grid mt-3">
                    <div class="stat-card">
                        <div class="stat-value"><?= $chiSquareResult['chi_square_statistic'] ?></div>
                        <div class="stat-label">Chi-Square Statistic</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= $chiSquareResult['critical_value'] ?></div>
                        <div class="stat-label">Critical Value (α=0.05)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?= $chiSquareResult['degrees_of_freedom'] ?></div>
                        <div class="stat-label">Degrees of Freedom</div>
                    </div>
                </div>

                <div class="mt-3">
                    <h6>Expected vs Observed:</h6>
                    <div class="distribution-bar">
                        <div class="distribution-fill" style="width: 100%">
                            Expected: <?= round($chiSquareResult['expected_frequency'], 2) ?>
                        </div>
                    </div>
                    <small class="text-muted">Sample Size: <?= $chiSquareResult['sample_size'] ?> items</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Sample Sequence -->
    <div class="col-md-6 mb-4">
        <div class="card test-card h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti ti-number me-2"></i>Sample Sequence (20 items)
                </h5>
                <div class="mb-3">
                    <small class="text-muted">20 bilangan acak pertama yang di-generate:</small>
                </div>
                <div style="max-height: 300px; overflow-y: auto;">
                    <?php foreach ($sampleSequence as $i => $num): ?>
                        <div class="sequence-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Index <?= $i + 1 ?>:</span>
                                <span class="sequence-result"><?= $num ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="mt-2 text-center">
                    <button class="btn btn-sm btn-outline-primary" onclick="generateNewSequence()">
                        <i class="ti ti-refresh me-1"></i>Generate New Sequence
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Debug Information -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card test-card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti ti-bug me-2"></i>Debug Information - Step by Step Calculation
                </h5>
                <div class="row">
                    <?php foreach ($debugSequence['sequence'] as $step): ?>
                        <div class="col-md-6 mb-3">
                            <div class="debug-item">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>Iteration <?= $step['iteration'] ?>:</strong>
                                    <span class="badge bg-primary"><?= $step['result'] ?></span>
                                </div>
                                <div class="debug-formula">
                                    <?= $step['formula'] ?>
                                </div>
                                <div class="text-muted small">
                                    Seed before: <?= $step['seed_before'] ?> → Seed after: <?= $step['seed_after'] ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Frequency Distribution -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card test-card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti ti-chart-dots-3 me-2"></i>Frequency Distribution
                </h5>
                <div class="row">
                    <?php foreach ($chiSquareResult['frequencies'] as $value => $frequency): ?>
                        <div class="col-md-3 col-sm-6 mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Value <?= $value ?>:</span>
                                <span class="badge bg-info"><?= $frequency ?></span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <?php
                                $maxFreq = max($chiSquareResult['frequencies']);
                                $percentage = ($frequency / $maxFreq) * 100;
                                ?>
                                <div class="progress-bar" style="width: <?= $percentage ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="mt-3 text-center">
                    <small class="text-muted">
                        Distribusi frekuensi untuk setiap nilai (0 - <?= $lcmParameters['modulus'] - 1 ?>)
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Academic Validation -->
<div class="row">
    <div class="col-12">
        <div class="card test-card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="ti ti-school me-2"></i>Academic Validation
                </h5>
                <div class="alert alert-<?= $chiSquareResult['is_uniform_distribution'] ? 'success' : 'danger' ?>" role="alert">
                    <h6 class="alert-heading">
                        <i class="ti ti-<?= $chiSquareResult['is_uniform_distribution'] ? 'circle-check' : 'alert-triangle' ?> me-2"></i>
                        <?= $chiSquareResult['is_uniform_distribution'] ? '✓ VALIDATION SUCCESSFUL' : '✗ VALIDATION FAILED' ?>
                    </h6>
                    <p class="mb-0">
                        <?php if ($chiSquareResult['is_uniform_distribution']): ?>
                            Algoritma LCM dengan parameter a=<?= $lcmParameters['multiplier'] ?>, c=<?= $lcmParameters['increment'] ?>, m=<?= $lcmParameters['modulus'] ?>
                            menghasilkan distribusi yang seragam (uniform distribution) dengan nilai chi-square
                            <?= $chiSquareResult['chi_square_statistic'] ?> < critical value <?= $chiSquareResult['critical_value'] ?>.
                            <br><br>
                            <strong>Kesimpulan:</strong> Algoritma valid untuk digunakan dalam penelitian skripsi.
                        <?php else: ?>
                            Algoritma LCM dengan parameter yang digunakan tidak menghasilkan distribusi yang seragam.
                            Nilai chi-square <?= $chiSquareResult['chi_square_statistic'] ?> ≥ critical value <?= $chiSquareResult['critical_value'] ?>.
                            <br><br>
                            <strong>Rekomendasi:</strong> Evaluasi kembali parameter LCM atau gunakan algoritma alternatif.
                        <?php endif; ?>
                    </p>
                </div>

                <div class="row text-center">
                    <div class="col-md-4">
                        <h6 class="text-primary">Significance Level</h6>
                        <p class="mb-0">α = 0.05</p>
                        <small class="text-muted">95% confidence level</small>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-primary">Hypothesis</h6>
                        <p class="mb-0">H₀: Distribusi seragam</p>
                        <small class="text-muted">H₁: Distribusi tidak seragam</small>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-primary">Test Result</h6>
                        <p class="mb-0">
                            <?= $chiSquareResult['is_uniform_distribution'] ? 'Fail to reject H₀' : 'Reject H₀' ?>
                        </p>
                        <small class="text-muted">P-value ≈ <?= number_format(1 - ($chiSquareResult['chi_square_statistic'] / $chiSquareResult['critical_value']), 4) ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function generateNewSequence() {
    // Generate new seed and reload page
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.delete('seed');
    window.location.href = currentUrl.toString();
}

// Auto-refresh untuk real-time testing
let refreshInterval;
let isRefreshing = false;

function toggleAutoRefresh() {
    const btn = document.getElementById('autoRefreshBtn');
    if (isRefreshing) {
        clearInterval(refreshInterval);
        isRefreshing = false;
        btn.textContent = 'Auto Refresh: OFF';
        btn.classList.remove('btn-success');
        btn.classList.add('btn-outline-success');
    } else {
        refreshInterval = setInterval(() => {
            window.location.reload();
        }, 30000); // Refresh every 30 seconds
        isRefreshing = true;
        btn.textContent = 'Auto Refresh: ON';
        btn.classList.remove('btn-outline-success');
        btn.classList.add('btn-success');
    }
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
<?= $this->endSection() ?>