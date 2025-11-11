<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Tambah Materi Kaidah - <?= $this->endSection() ?>

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

    /* Arabic Keyboard Styles */
    .arabic-keyboard {
        background: white;
        border: 2px solid #4CAF50;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        padding: 1rem;
        margin-top: 0.5rem;
        display: none;
        position: relative;
        z-index: 1000;
    }

    .arabic-keyboard.show {
        display: block;
    }

    .keyboard-row {
        display: flex;
        gap: 0.25rem;
        margin-bottom: 0.25rem;
        justify-content: center;
    }

    .arabic-key {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 0.5rem 0.6rem;
        font-family: 'Amiri', 'Traditional Arabic', serif;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        min-width: 40px;
        text-align: center;
        font-weight: bold;
    }

    .arabic-key:hover {
        background: #4CAF50;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
    }

    .arabic-key:active {
        transform: translateY(0);
        background: #388E3C;
    }

    .arabic-key.wide {
        min-width: 80px;
    }

    .arabic-key.special {
        background: #e3f2fd;
        color: #1976d2;
    }

    .arabic-key.special:hover {
        background: #1976d2;
        color: white;
    }

    .keyboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e9ecef;
    }

    .keyboard-title {
        font-weight: 600;
        color: #333;
        margin: 0;
        font-size: 0.9rem;
    }

    .keyboard-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        color: #666;
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .keyboard-close:hover {
        background: #f1f3f4;
        color: #333;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark">Tambah Materi Kaidah</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('kaidah') ?>" class="text-muted">Manajemen Materi Kaidah</a></li>
                <li class="breadcrumb-item active">Tambah Materi</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="<?= site_url('kaidah') ?>" class="btn btn-secondary">
            <i class="ti ti-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<!-- Form Card -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <!-- Flash Messages -->
        <?= $this->include('partials/flash_messages') ?>

        <form method="POST" action="<?= site_url('kaidah') ?>" class="needs-validation" novalidate>
            <?= csrf_field() ?>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="judul_kaidah" class="form-label">Judul Kaidah *</label>
                    <input type="text" class="form-control" id="judul_kaidah" name="judul_kaidah"
                           value="<?= esc(old('judul_kaidah')) ?>"
                           placeholder="Contoh: Isim Mufrad dan Jamak" required>
                    <div class="invalid-feedback">
                        Judul kaidah wajib diisi (minimal 3 karakter)
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="id_bab" class="form-label">Bab *</label>
                    <select class="form-control" id="id_bab" name="id_bab" required>
                        <option value="">Pilih Bab</option>
                        <?php
                        $babModel = new \App\Models\BabModel();
                        $babList = $babModel->getActive();
                        $selectedBab = old('id_bab') ?: $_GET['bab_id'] ?? null;
                        foreach ($babList as $bab): ?>
                        <option value="<?= $bab['id_bab'] ?>" <?= $selectedBab == $bab['id_bab'] ? 'selected' : '' ?>>
                            <?= esc($bab['nama_bab']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        Bab wajib dipilih
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="urutan" class="form-label">Urutan *</label>
                    <input type="number" class="form-control" id="urutan" name="urutan"
                           value="<?= esc(old('urutan') ?? $lastOrder ?? 1) ?>"
                           placeholder="Urutan tampilan dalam bab" min="1" required>
                    <div class="invalid-feedback">
                        Urutan wajib diisi
                    </div>
                    <div class="form-text">Urutan menentukan penampilan materi dalam bab yang dipilih</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                              placeholder="Deskripsi singkat tentang kaidah ini..."><?= esc(old('deskripsi')) ?></textarea>
                    <div class="char-counter">
                        <span id="deskripsiCount">0</span> / 500 karakter
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
                               value="<?= esc(old('nama_arab')) ?>"
                               placeholder="مفرد وجمع"
                               style="font-family: 'Amiri', 'Traditional Arabic', serif; direction: rtl; text-align: right;">
                        <button class="btn btn-outline-success" type="button" id="toggleArabicKeyboard" title="Toggle Arabic Keyboard">
                            <i class="ti ti-keyboard"></i>
                        </button>
                    </div>

                    <!-- Arabic Virtual Keyboard -->
                    <div id="arabicKeyboard" class="arabic-keyboard">
                        <div class="keyboard-header">
                            <h6 class="keyboard-title">
                                <i class="ti ti-keyboard me-2"></i>Keyboard Virtual Arab
                            </h6>
                            <button type="button" class="keyboard-close" id="closeKeyboard">
                                <i class="ti ti-x"></i>
                            </button>
                        </div>

                        <!-- Arabic Letters Row 1 -->
                        <div class="keyboard-row">
                            <div class="arabic-key" data-char="ض">ض</div>
                            <div class="arabic-key" data-char="ص">ص</div>
                            <div class="arabic-key" data-char="ث">ث</div>
                            <div class="arabic-key" data-char="ق">ق</div>
                            <div class="arabic-key" data-char="ف">ف</div>
                            <div class="arabic-key" data-char="غ">غ</div>
                            <div class="arabic-key" data-char="ع">ع</div>
                            <div class="arabic-key" data-char="ه">ه</div>
                            <div class="arabic-key" data-char="خ">خ</div>
                            <div class="arabic-key" data-char="ح">ح</div>
                            <div class="arabic-key" data-char="ج">ج</div>
                            <div class="arabic-key special wide" data-char="backspace">⌫</div>
                        </div>

                        <!-- Arabic Letters Row 2 -->
                        <div class="keyboard-row">
                            <div class="arabic-key" data-char="ش">ش</div>
                            <div class="arabic-key" data-char="س">س</div>
                            <div class="arabic-key" data-char="ي">ي</div>
                            <div class="arabic-key" data-char="ب">ب</div>
                            <div class="arabic-key" data-char="ل">ل</div>
                            <div class="arabic-key" data-char="ا">ا</div>
                            <div class="arabic-key" data-char="ت">ت</div>
                            <div class="arabic-key" data-char="ن">ن</div>
                            <div class="arabic-key" data-char="م">م</div>
                            <div class="arabic-key" data-char="ك">ك</div>
                            <div class="arabic-key" data-char="ط">ط</div>
                        </div>

                        <!-- Arabic Letters Row 3 -->
                        <div class="keyboard-row">
                            <div class="arabic-key" data-char="ئ">ئ</div>
                            <div class="arabic-key" data-char="ء">ء</div>
                            <div class="arabic-key" data-char="ؤ">ؤ</div>
                            <div class="arabic-key" data-char="ر">ر</div>
                            <div class="arabic-key" data-char="لا">لا</div>
                            <div class="arabic-key" data-char="ى">ى</div>
                            <div class="arabic-key" data-char="ة">ة</div>
                            <div class="arabic-key" data-char="و">و</div>
                            <div class="arabic-key" data-char="ز">ز</div>
                            <div class="arabic-key" data-char="ظ">ظ</div>
                            <div class="arabic-key" data-char="ذ">ذ</div>
                        </div>

                        <!-- Diacritics Row -->
                        <div class="keyboard-row">
                            <div class="arabic-key special" data-char="َ">َ</div>
                            <div class="arabic-key special" data-char="ُ">ُ</div>
                            <div class="arabic-key special" data-char="ِ">ِ</div>
                            <div class="arabic-key special" data-char="ْ">ْ</div>
                            <div class="arabic-key special" data-char="ّ">ّ</div>
                            <div class="arabic-key special" data-char="ً">ً</div>
                            <div class="arabic-key special" data-char="ٌ">ٌ</div>
                            <div class="arabic-key special" data-char="ٍ">ٍ</div>
                            <div class="arabic-key special" data-char="ـ">ـ</div>
                            <div class="arabic-key wide special" data-char=" ">Space</div>
                        </div>

                        <!-- Numbers Row -->
                        <div class="keyboard-row">
                            <div class="arabic-key special" data-char="١">١</div>
                            <div class="arabic-key special" data-char="٢">٢</div>
                            <div class="arabic-key special" data-char="٣">٣</div>
                            <div class="arabic-key special" data-char="٤">٤</div>
                            <div class="arabic-key special" data-char="٥">٥</div>
                            <div class="arabic-key special" data-char="٦">٦</div>
                            <div class="arabic-key special" data-char="٧">٧</div>
                            <div class="arabic-key special" data-char="٨">٨</div>
                            <div class="arabic-key special" data-char="٩">٩</div>
                            <div class="arabic-key special" data-char="٠">٠</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="penjelasan" class="form-label">Penjelasan Kaidah *</label>
                    <textarea class="form-control" id="penjelasan" name="penjelasan" rows="6" required
                              placeholder="Tulis penjelasan lengkap tentang kaidah ini..."><?= esc(old('penjelasan')) ?></textarea>
                    <div class="invalid-feedback">
                        Penjelasan kaidah wajib diisi
                    </div>
                    <!-- Arabic Preview -->
                    <div class="mt-2">
                        <label class="form-label text-muted small">Preview Teks Arab:</label>
                        <div class="preview-box" id="arabicPreview">
                            <span class="text-muted">Preview teks Arab akan muncul di sini...</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="contoh" class="form-label">Contoh Penggunaan</label>
                    <textarea class="form-control" id="contoh" name="contoh" rows="4"
                              placeholder="Berikan contoh-contoh penggunaan kaidah ini..."><?= esc(old('contoh')) ?></textarea>
                    <div class="form-text">
                        Opsional: Berikan contoh untuk memperjelas pemahaman siswa
                    </div>
                </div>
            </div>

            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="ti ti-info-circle me-2"></i>
                <div>
                    <strong>Tips:</strong>
                    <ul class="mb-0 mt-1">
                        <li>Gunakan bahasa yang jelas dan mudah dipahami siswa</li>
                        <li>Sertakan contoh yang relevan dengan kehidupan sehari-hari</li>
                        <li>Untuk teks Arab, gunakan format RTL (Right-to-Left)</li>
                        <li>Urutan akan menentukan posisi materi di daftar kaidah</li>
                    </ul>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-2"></i>Simpan
                </button>
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

// Arabic Virtual Keyboard
const toggleKeyboardBtn = document.getElementById('toggleArabicKeyboard');
const arabicKeyboard = document.getElementById('arabicKeyboard');
const closeKeyboardBtn = document.getElementById('closeKeyboard');
const arabicInput = document.getElementById('nama_arab');

// Toggle keyboard visibility
toggleKeyboardBtn.addEventListener('click', function() {
    arabicKeyboard.classList.toggle('show');
    if (arabicKeyboard.classList.contains('show')) {
        this.classList.remove('btn-outline-success');
        this.classList.add('btn-success');
        this.innerHTML = '<i class="ti ti-keyboard-off"></i>';
    } else {
        this.classList.remove('btn-success');
        this.classList.add('btn-outline-success');
        this.innerHTML = '<i class="ti ti-keyboard"></i>';
    }
});

// Close keyboard
closeKeyboardBtn.addEventListener('click', function() {
    arabicKeyboard.classList.remove('show');
    toggleKeyboardBtn.classList.remove('btn-success');
    toggleKeyboardBtn.classList.add('btn-outline-success');
    toggleKeyboardBtn.innerHTML = '<i class="ti ti-keyboard"></i>';
});

// Handle keyboard clicks
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('arabic-key')) {
        e.preventDefault();
        const char = e.target.dataset.char;

        if (char === 'backspace') {
            // Remove last character
            const currentValue = arabicInput.value;
            arabicInput.value = currentValue.slice(0, -1);
        } else {
            // Add character to input
            arabicInput.value += char;
        }

        // Trigger input event to update any listeners
        arabicInput.dispatchEvent(new Event('input'));

        // Visual feedback
        e.target.style.transform = 'scale(0.95)';
        setTimeout(() => {
            e.target.style.transform = '';
        }, 100);

        // Keep focus on input
        arabicInput.focus();
    }
});

// Close keyboard when clicking outside
document.addEventListener('click', function(e) {
    if (!arabicKeyboard.contains(e.target) &&
        e.target !== toggleKeyboardBtn &&
        !toggleKeyboardBtn.contains(e.target) &&
        arabicKeyboard.classList.contains('show')) {
        arabicKeyboard.classList.remove('show');
        toggleKeyboardBtn.classList.remove('btn-success');
        toggleKeyboardBtn.classList.add('btn-outline-success');
        toggleKeyboardBtn.innerHTML = '<i class="ti ti-keyboard"></i>';
    }
});

// Add keyboard support for the virtual keyboard
document.addEventListener('keydown', function(e) {
    if (arabicKeyboard.classList.contains('show') && arabicInput === document.activeElement) {
        // Allow certain keys to work normally
        if (e.key === 'Escape') {
            arabicKeyboard.classList.remove('show');
            toggleKeyboardBtn.classList.remove('btn-success');
            toggleKeyboardBtn.classList.add('btn-outline-success');
            toggleKeyboardBtn.innerHTML = '<i class="ti ti-keyboard"></i>';
        }
        // Allow backspace, delete, arrow keys, tab
        else if (['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Tab'].includes(e.key)) {
            return;
        }
        // Prevent other characters to avoid mixing keyboards
        else if (e.key.length === 1) {
            e.preventDefault();
        }
    }
});

// Auto-focus on first field
document.addEventListener('DOMContentLoaded', function() {
    const firstInput = document.querySelector('input:not([type="hidden"]):not([readonly])');
    if (firstInput && !firstInput.value) {
        firstInput.focus();
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
            // localStorage.setItem('kaidahDraft', JSON.stringify(Object.fromEntries(formData)));
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