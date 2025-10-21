<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<style>
    .form-label {
        font-weight: 500;
        color: var(--neutral-700);
        margin-bottom: 0.5rem;
    }
    .form-control:focus {
        border-color: var(--primary-500);
        box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
    }
    .arabic-input {
        font-family: var(--font-arabic);
        font-size: 1.2rem;
        direction: rtl;
        text-align: right;
    }
    .preview-box {
        background: var(--neutral-50);
        border: 1px solid var(--neutral-200);
        border-radius: var(--radius-md);
        padding: 1rem;
        min-height: 120px;
        direction: rtl;
        font-family: var(--font-arabic);
    }
    .char-counter {
        font-size: 0.875rem;
        color: var(--neutral-500);
    }
    .difficulty-option {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .difficulty-option:hover {
        transform: translateY(-2px);
    }
    .difficulty-option.selected {
        border-color: var(--primary-500);
        background: var(--primary-50);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark">Tambah Kaidah Baru</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('kaidah') ?>" class="text-muted">Materi Kaidah</a></li>
                <li class="breadcrumb-item active">Tambah Kaidah</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="<?= site_url('kaidah') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<form action="<?= site_url('kaidah') ?>" method="post" id="kaidahForm" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-info-circle me-2 text-primary"></i>Informasi Dasar
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label for="judul_kaidah" class="form-label">Judul Kaidah <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="judul_kaidah" name="judul_kaidah"
                               value="<?= esc(old('judul_kaidah')) ?>" required
                               placeholder="Contoh: Isim Mufrad dan Jamak">
                        <div class="form-text">Judul kaidah dalam Bahasa Indonesia</div>
                    </div>

                    <div class="mb-4">
                        <label for="nama_arab" class="form-label">Nama Arab</label>
                        <div class="input-group">
                            <input type="text" class="form-control arabic-input" id="nama_arab" name="nama_arab"
                                   value="<?= esc(old('nama_arab')) ?>"
                                   placeholder="مفرد وجمع">
                            <button class="btn btn-outline-secondary" type="button" id="toggleArabicKeyboard">
                                <i class="bi bi-keyboard"></i>
                            </button>
                        </div>
                        <div class="form-text">Nama kaidah dalam Bahasa Arab (opsional)</div>
                    </div>

                    <div class="mb-4">
                        <label for="deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required
                                  placeholder="Deskripsi singkat tentang kaidah ini..."><?= esc(old('deskripsi')) ?></textarea>
                        <div class="char-counter">
                            <span id="deskripsiCount">0</span> / 500 karakter
                        </div>
                    </div>
                </div>
            </div>

            <!-- Penjelasan -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-book me-2 text-primary"></i>Penjelasan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="penjelasan" class="form-label">Penjelasan Kaidah</label>
                        <textarea class="form-control" id="penjelasan" name="penjelasan" rows="6"
                                  placeholder="Tulis penjelasan lengkap tentang kaidah ini..."><?= esc(old('penjelasan')) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Preview Teks Arab:</label>
                        <div class="preview-box" id="arabicPreview">
                            <span class="text-muted">Preview teks Arab akan muncul di sini...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contoh -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-lightbulb me-2 text-primary"></i>Contoh
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="contoh" class="form-label">Contoh Penggunaan</label>
                        <textarea class="form-control" id="contoh" name="contoh" rows="4"
                                  placeholder="Berikan contoh-contoh penggunaan kaidah ini..."><?= esc(old('contoh')) ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Pengaturan -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-gear me-2 text-primary"></i>Pengaturan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label">Tingkat Kesulitan <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            <div class="col-4">
                                <div class="card difficulty-option text-center p-3" data-difficulty="mudah">
                                    <i class="bi bi-emoji-smile fs-3 text-success mb-2"></i>
                                    <div class="fw-500">Mudah</div>
                                    <small class="text-muted">Pemula</small>
                                </div>
                                <input type="radio" name="tingkat_kesulitan" value="mudah" class="d-none"
                                       <?= old('tingkat_kesulitan') === 'mudah' ? 'checked' : '' ?> required>
                            </div>
                            <div class="col-4">
                                <div class="card difficulty-option text-center p-3" data-difficulty="sedang">
                                    <i class="bi bi-emoji-neutral fs-3 text-warning mb-2"></i>
                                    <div class="fw-500">Sedang</div>
                                    <small class="text-muted">Menengah</small>
                                </div>
                                <input type="radio" name="tingkat_kesulitan" value="sedang" class="d-none"
                                       <?= old('tingkat_kesulitan') === 'sedang' ? 'checked' : '' ?>>
                            </div>
                            <div class="col-4">
                                <div class="card difficulty-option text-center p-3" data-difficulty="sulit">
                                    <i class="bi bi-emoji-frown fs-3 text-danger mb-2"></i>
                                    <div class="fw-500">Sulit</div>
                                    <small class="text-muted">Lanjutan</small>
                                </div>
                                <input type="radio" name="tingkat_kesulitan" value="sulit" class="d-none"
                                       <?= old('tingkat_kesulitan') === 'sulit' ? 'checked' : '' ?>>
                            </div>
                        </div>
                        <?php if (isset($validation) && $validation->hasError('tingkat_kesulitan')): ?>
                            <div class="text-danger small mt-1"><?= $validation->getError('tingkat_kesulitan') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label for="urutan" class="form-label">Urutan</label>
                        <input type="number" class="form-control" id="urutan" name="urutan"
                               value="<?= esc(old('urutan') ?? 1) ?>" min="1">
                        <div class="form-text">Urutan tampilan dalam daftar kaidah</div>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="aktif" <?= old('status') === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="nonaktif" <?= old('status') === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Simpan Kaidah
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Character counter
const deskripsi = document.getElementById('deskripsi');
const deskripsiCount = document.getElementById('deskripsiCount');

deskripsi.addEventListener('input', function() {
    const count = this.value.length;
    deskripsiCount.textContent = count;
    if (count > 500) {
        this.value = this.value.substring(0, 500);
        deskripsiCount.textContent = 500;
    }
});

// Difficulty selection
document.querySelectorAll('.difficulty-option').forEach(option => {
    option.addEventListener('click', function() {
        document.querySelectorAll('.difficulty-option').forEach(opt => opt.classList.remove('selected'));
        this.classList.add('selected');
        const difficulty = this.dataset.difficulty;
        document.querySelector(`input[name="tingkat_kesulitan"][value="${difficulty}"]`).checked = true;
    });
});

// Initialize selected difficulty
const selectedDifficulty = document.querySelector('input[name="tingkat_kesulitan"]:checked');
if (selectedDifficulty) {
    document.querySelector(`[data-difficulty="${selectedDifficulty.value}"]`).classList.add('selected');
}

// Arabic preview
const penjelasan = document.getElementById('penjelasan');
const arabicPreview = document.getElementById('arabicPreview');

penjelasan.addEventListener('input', function() {
    const arabicText = this.value;
    if (arabicText.trim()) {
        arabicPreview.innerHTML = `<div style="font-size: 1.1rem; line-height: 1.8;">${arabicText}</div>`;
    } else {
        arabicPreview.innerHTML = '<span class="text-muted">Preview teks Arab akan muncul di sini...</span>';
    }
});

// Arabic keyboard toggle (simplified version)
document.getElementById('toggleArabicKeyboard').addEventListener('click', function() {
    alert('Keyboard Arab akan segera tersedia.\n\nUntuk sementara, gunakan keyboard Arab dari sistem operasi Anda atau copy-paste dari sumber lain.');
});

// Form validation
document.getElementById('kaidahForm').addEventListener('submit', function(e) {
    const judulKaidah = document.getElementById('judul_kaidah').value.trim();
    const deskripsi = document.getElementById('deskripsi').value.trim();
    const tingkatKesulitan = document.querySelector('input[name="tingkat_kesulitan"]:checked');

    if (!judulKaidah) {
        e.preventDefault();
        alert('Judul kaidah harus diisi!');
        document.getElementById('judul_kaidah').focus();
        return;
    }

    if (!deskripsi) {
        e.preventDefault();
        alert('Deskripsi harus diisi!');
        document.getElementById('deskripsi').focus();
        return;
    }

    if (!tingkatKesulitan) {
        e.preventDefault();
        alert('Tingkat kesulitan harus dipilih!');
        return;
    }
});

// Auto-save draft (optional)
let autoSaveTimer;
function autoSaveDraft() {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(function() {
        const formData = new FormData(document.getElementById('kaidahForm'));
        // Implement auto-save logic here if needed
        console.log('Auto-saving draft...');
    }, 30000); // Auto-save after 30 seconds of inactivity
}

// Listen for form changes
document.querySelectorAll('#kaidahForm input, #kaidahForm textarea, #kaidahForm select').forEach(element => {
    element.addEventListener('input', autoSaveDraft);
    element.addEventListener('change', autoSaveDraft);
});
</script>
<?= $this->endSection() ?>