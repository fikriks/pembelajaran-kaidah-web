<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Edit Materi Kaidah - <?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .form-label {
        font-weight: 600;
        color: var(--bs-heading-color);
        margin-bottom: 0.5rem;
    }
    .form-control:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
    }
    .arabic-input {
        font-family: 'Amiri', 'Traditional Arabic', serif;
        font-size: 1.2rem;
        direction: rtl;
        text-align: right;
    }
    .preview-box {
        background: var(--bs-gray-100);
        border: 1px solid var(--bs-gray-300);
        border-radius: var(--bs-border-radius);
        padding: 1rem;
        min-height: 120px;
        direction: rtl;
        font-family: 'Amiri', 'Traditional Arabic', serif;
    }
    .char-counter {
        font-size: 0.875rem;
        color: var(--bs-gray-500);
    }
    .difficulty-option {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    .difficulty-option:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .difficulty-option.selected {
        border-color: var(--bs-primary);
        background: var(--bs-primary-bg-subtle);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark">Edit Materi Kaidah</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('kaidah') ?>" class="text-muted">Manajemen Materi Kaidah</a></li>
                <li class="breadcrumb-item active">Edit Materi</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="<?= site_url('kaidah/' . $kaidah['id_materi']) ?>" class="btn btn-info me-2">
            <i class="ti ti-eye me-2"></i>Lihat
        </a>
        <a href="<?= site_url('kaidah') ?>" class="btn btn-secondary">
            <i class="ti ti-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<!-- Info Kaidah -->
<div class="alert alert-info d-flex align-items-center" role="alert">
    <i class="ti ti-info-circle me-2"></i>
    <div>
        <strong>Info Materi:</strong> <?= esc($kaidah['judul_kaidah']) ?>
        <br class="d-md-none">
        <small class="text-muted">ID: <?= $kaidah['id_materi'] ?> • Urutan: #<?= $kaidah['urutan'] ?> • Dibuat oleh: <?= esc($kaidah['nama_pembuat'] ?? 'Unknown') ?></small>
    </div>
</div>

<!-- Form Card -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <!-- Flash Messages -->
        <?= $this->include('partials/flash_messages') ?>

        <form method="POST" action="<?= site_url('kaidah/' . $kaidah['id_materi']) ?>" class="needs-validation" novalidate>
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="PUT">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="judul_kaidah" class="form-label">Judul Kaidah *</label>
                    <input type="text" class="form-control" id="judul_kaidah" name="judul_kaidah"
                           value="<?= esc(old('judul_kaidah', $kaidah['judul_kaidah'])) ?>"
                           placeholder="Contoh: Isim Mufrad dan Jamak" required>
                    <div class="invalid-feedback">
                        Judul kaidah wajib diisi (minimal 3 karakter)
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="urutan" class="form-label">Urutan *</label>
                    <input type="number" class="form-control" id="urutan" name="urutan"
                           value="<?= esc(old('urutan', $kaidah['urutan'])) ?>"
                           placeholder="Urutan tampilan" min="0" required>
                    <div class="invalid-feedback">
                        Urutan wajib diisi
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                              placeholder="Deskripsi singkat tentang kaidah ini..."><?= esc(old('deskripsi', $kaidah['deskripsi'])) ?></textarea>
                    <div class="char-counter">
                        <span id="deskripsiCount"><?= strlen(old('deskripsi', $kaidah['deskripsi'])) ?></span> / 500 karakter
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="nama_arab" class="form-label">Nama Arab (Opsional)</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ti ti-language"></i>
                        </span>
                        <input type="text" class="form-control arabic-input" id="nama_arab" name="nama_arab"
                               value="<?= esc(old('nama_arab', $kaidah['nama_arab'] ?? '')) ?>"
                               placeholder="مفرد وجمع"
                               style="font-family: 'Amiri', 'Traditional Arabic', serif; direction: rtl; text-align: right;">
                        <button class="btn btn-outline-secondary" type="button" id="toggleArabicKeyboard" title="Toggle Arabic Keyboard">
                            <i class="ti ti-keyboard"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="penjelasan" class="form-label">Penjelasan Kaidah *</label>
                    <textarea class="form-control" id="penjelasan" name="penjelasan" rows="6" required
                              placeholder="Tulis penjelasan lengkap tentang kaidah ini..."><?= esc(old('penjelasan', $kaidah['penjelasan'])) ?></textarea>
                    <div class="invalid-feedback">
                        Penjelasan kaidah wajib diisi
                    </div>
                    <!-- Arabic Preview -->
                    <div class="mt-2">
                        <label class="form-label text-muted small">Preview Teks Arab:</label>
                        <div class="preview-box" id="arabicPreview">
                            <?php if (!empty($kaidah['penjelasan'])): ?>
                                <div style="font-size: 1.1rem; line-height: 1.8;"><?= esc($kaidah['penjelasan']) ?></div>
                            <?php else: ?>
                                <span class="text-muted">Preview teks Arab akan muncul di sini...</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="contoh" class="form-label">Contoh Penggunaan</label>
                    <textarea class="form-control" id="contoh" name="contoh" rows="4"
                              placeholder="Berikan contoh-contoh penggunaan kaidah ini..."><?= esc(old('contoh', $kaidah['contoh'])) ?></textarea>
                    <div class="form-text">
                        Opsional: Berikan contoh untuk memperjelas pemahaman siswa
                    </div>
                </div>
            </div>

            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <i class="ti ti-alert-triangle me-2"></i>
                <div>
                    <strong>Catatan:</strong>
                    <ul class="mb-0 mt-1">
                        <li>Materi kaidah ini sudah dipelajari oleh beberapa siswa, perubahan dapat mempengaruhi progres belajar mereka</li>
                        <li>Gunakan bahasa yang jelas dan mudah dipahami siswa</li>
                        <li>Untuk teks Arab, gunakan format RTL (Right-to-Left)</li>
                        <li>Urutan akan menentukan posisi materi di daftar kaidah</li>
                    </ul>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-2"></i>Update Data
                </button>
                <a href="<?= site_url('kaidah/' . $kaidah['id_materi']) ?>" class="btn btn-info">
                    <i class="ti ti-eye me-2"></i>Lihat
                </a>
                <a href="<?= site_url('kaidah') ?>" class="btn btn-danger">
                    <i class="ti ti-circle-x me-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.needs-validation');

    Array.from(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        }, false);
    });
});

// Character counter for deskripsi
const deskripsi = document.getElementById('deskripsi');
const deskripsiCount = document.getElementById('deskripsiCount');

if (deskripsi && deskripsiCount) {
    deskripsi.addEventListener('input', function() {
        const count = this.value.length;
        deskripsiCount.textContent = count;
        if (count > 500) {
            this.value = this.value.substring(0, 500);
            deskripsiCount.textContent = 500;
        }
    });

    // Initialize counter
    deskripsiCount.textContent = deskripsi.value.length;
}

// Arabic preview for penjelasan
const penjelasan = document.getElementById('penjelasan');
const arabicPreview = document.getElementById('arabicPreview');

if (penjelasan && arabicPreview) {
    // Function to detect Arabic text
    function hasArabic(text) {
        const arabicRegex = /[\u0600-\u06FF]/;
        return arabicRegex.test(text);
    }

    // Function to extract Arabic text from mixed content
    function extractArabicText(text) {
        if (hasArabic(text)) {
            // If the text contains Arabic characters, show them with proper styling
            const lines = text.split('\n');
            return lines.map(line => {
                if (hasArabic(line)) {
                    return `<div style="font-family: 'Amiri', 'Traditional Arabic', serif; font-size: 1.1rem; line-height: 1.8; direction: rtl; text-align: right; margin-bottom: 8px;">${line}</div>`;
                }
                return `<div style="font-size: 0.9rem; color: #666; margin-bottom: 8px;">${line}</div>`;
            }).join('');
        }
        return null;
    }

    penjelasan.addEventListener('input', function() {
        const text = this.value.trim();
        const arabicHtml = extractArabicText(text);

        if (arabicHtml) {
            arabicPreview.innerHTML = arabicHtml;
        } else if (text) {
            arabicPreview.innerHTML = `<span class="text-muted">Tidak ada teks Arab terdeteksi dalam penjelasan.</span>`;
        } else {
            arabicPreview.innerHTML = '<span class="text-muted">Preview teks Arab akan muncul di sini...</span>';
        }
    });

    // Initialize preview
    penjelasan.dispatchEvent(new Event('input'));
}

// Arabic keyboard helper
document.getElementById('toggleArabicKeyboard').addEventListener('click', function() {
    // Create a simple Arabic keyboard helper
    const arabicKeyboard = `
        <div class="alert alert-info mt-2">
            <h6><i class="ti ti-keyboard me-2"></i>Petik Keyboard Arab:</h6>
            <div class="row g-2">
                <div class="col-6">
                    <small class="d-block">ا ب ت ث ج ح خ د ذ ر ز س ش ص ض ط ظ ع غ ف ق ك ل م ن ه و ي</small>
                </div>
                <div class="col-6">
                    <small class="d-block">َ ُ ِ ْ ّ ً ٌ ٍ ًّ ُّ ِّ</small>
                    <small class="d-block text-muted">(Fatha, Damma, Kasra, Sukun, Tanwin)</small>
                </div>
            </div>
            <hr>
            <small class="text-muted">
                <strong>Tips:</strong> Gunakan keyboard Arab dari sistem operasi atau copy-paste dari sumber lain.
                Untuk Windows: Win + Space, untuk Mac: Cmd + Space.
            </small>
        </div>
    `;

    // Toggle keyboard helper
    const helperDiv = document.getElementById('arabicKeyboardHelper');
    if (helperDiv) {
        helperDiv.remove();
    } else {
        const div = document.createElement('div');
        div.id = 'arabicKeyboardHelper';
        div.innerHTML = arabicKeyboard;
        this.closest('.input-group').parentNode.insertBefore(div, this.closest('.input-group').nextSibling);
    }
});

// Auto-save draft functionality (optional - for future implementation)
let autoSaveTimer;
function autoSaveDraft() {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(function() {
        const form = document.getElementById('kaidahForm');
        if (form) {
            const formData = new FormData(form);
            // Future: Implement auto-save to localStorage or server
            console.log('Auto-saving draft...');
            // localStorage.setItem('kaidahEditDraft', JSON.stringify(Object.fromEntries(formData)));
        }
    }, 30000); // Auto-save after 30 seconds of inactivity
}

// Listen for form changes to trigger auto-save
document.querySelectorAll('#kaidahForm input, #kaidahForm textarea, #kaidahForm select').forEach(element => {
    element.addEventListener('input', autoSaveDraft);
    element.addEventListener('change', autoSaveDraft);
});
</script>
<?= $this->endSection() ?>