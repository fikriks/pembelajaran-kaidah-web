<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Edit Soal - <?= $this->endSection() ?>

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

    .arabic-textarea {
        font-family: 'Amiri', 'Traditional Arabic', serif;
        font-size: 1.1rem;
        direction: rtl;
        text-align: right;
        min-height: 120px;
    }

    .preview-box {
        background: var(--bs-gray-100);
        border: 1px solid var(--bs-gray-300);
        border-radius: var(--bs-border-radius);
        padding: 1rem;
        min-height: 100px;
        direction: rtl;
        font-family: 'Amiri', 'Traditional Arabic', serif;
    }

    .char-counter {
        font-size: 0.875rem;
        color: var(--bs-gray-500);
    }

    .jawaban-item {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
        position: relative;
        transition: all 0.3s ease;
    }

    .jawaban-item:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .jawaban-item.correct-answer {
        border-left: 4px solid #28a745;
        background: #d4edda;
    }

    .jawaban-number {
        position: absolute;
        top: -10px;
        left: -10px;
        width: 30px;
        height: 30px;
        background: var(--bs-primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.875rem;
    }

    .btn-remove-jawaban {
        position: absolute;
        top: 10px;
        right: 10px;
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

    .lcm-info {
        background: #ffffff;
        color: #333333;
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        border: 1px solid #e9ecef;
    }

    .lcm-params {
        font-family: 'Courier New', monospace;
        background: rgba(0,0,0,0.05);
        padding: 0.5rem;
        border-radius: 0.25rem;
        margin-top: 0.5rem;
    }

    .alert-warning {
        background: #fff3cd;
        border-color: #ffeaa7;
    }

    .form-hint {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark">Tambah Soal Baru</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('soal') ?>" class="text-muted">Manajemen Soal</a></li>
                <li class="breadcrumb-item active">Tambah Soal</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="<?= site_url('soal') ?>" class="btn btn-secondary">
            <i class="ti ti-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<!-- LCM Algorithm Info -->
<div class="lcm-info">
    <div class="d-flex align-items-center">
        <i class="ti ti-info-circle me-3 fs-4"></i>
        <div>
            <h6 class="mb-1">Linear Congruent Method (LCM) Algorithm</h6>
            <p class="mb-0">Soal akan diacak menggunakan parameter LCM untuk penelitian skripsi:</p>
            <div class="lcm-params">
                Xn+1 = (a × Xn + c) mod m | a = 10, c = 23, m = 29
            </div>
        </div>
    </div>
</div>

<!-- Form Card -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <!-- Flash Messages -->
        <?= $this->include('partials/flash_messages') ?>

        <form method="POST" action="<?= site_url('soal') ?>" id="soalForm">
            <?= csrf_field() ?>

            <!-- Informasi Soal -->
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="mb-3">
                        <i class="ti ti-file-text me-2"></i>Informasi Soal
                    </h5>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="id_materi" class="form-label">Materi Kaidah *</label>
                    <select class="form-select" id="id_materi" name="id_materi" required>
                        <option value="">Pilih materi kaidah</option>
                        <?php foreach ($materiList as $materi): ?>
                            <option value="<?= $materi['id_materi'] ?>"
                                    data-urutan="<?= $materi['urutan'] ?>">
                                <?= esc($materi['judul_kaidah']) ?> (Urutan #<?= $materi['urutan'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-hint">Pilih materi kaidah terkait dengan soal ini</div>
                    <div class="invalid-feedback">
                        Materi kaidah harus dipilih
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="tingkat_kesulitan" class="form-label">Tingkat Kesulitan *</label>
                    <select class="form-select" id="tingkat_kesulitan" name="tingkat_kesulitan" required>
                        <option value="">Pilih tingkat</option>
                        <option value="mudah">Mudah</option>
                        <option value="sedang">Sedang</option>
                        <option value="sulit">Sulit</option>
                    </select>
                    <div class="invalid-feedback">
                        Tingkat kesulitan harus dipilih
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="poin" class="form-label">Poin *</label>
                    <input type="number" class="form-control" id="poin" name="poin"
                           value="10" min="1" max="100" required>
                    <div class="form-hint">Poin untuk jawaban benar</div>
                    <div class="invalid-feedback">
                        Poin harus diisi (1-100)
                    </div>
                </div>
            </div>

            <!-- Pertanyaan -->
            <div class="row mb-4">
                <div class="col-12">
                    <label for="pertanyaan" class="form-label">Pertanyaan *</label>
                    <textarea class="form-control arabic-textarea" id="pertanyaan" name="pertanyaan" rows="4" required
                              placeholder="Tulis pertanyaan dalam bahasa Arab atau Indonesia..."></textarea>
                    <div class="char-counter">
                        <span id="pertanyaanCount">0</span> / 1000 karakter
                    </div>
                    <!-- Preview Arabic -->
                    <div class="mt-2">
                        <label class="form-label text-muted small">Preview Teks Arab:</label>
                        <div class="preview-box" id="arabicPreview">
                            <span class="text-muted">Preview teks Arab akan muncul di sini...</span>
                        </div>
                    </div>
                    <div class="invalid-feedback">
                        Pertanyaan harus diisi
                    </div>
                </div>
            </div>

            <!-- Pilihan Jawaban -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">
                            <i class="ti ti-checkbox me-2"></i>Pilihan Jawaban *
                        </h5>
                        <button type="button" class="btn btn-sm btn-success" onclick="addJawaban()">
                            <i class="ti ti-plus me-1"></i>Tambah Jawaban
                        </button>
                    </div>
                    <div class="form-hint mb-3">Minimal 2 pilihan jawaban, salah satu harus ditandai sebagai jawaban benar</div>

                    <div id="jawabanContainer">
                        <!-- Default 2 jawaban -->
                        <div class="jawaban-item" data-index="0">
                            <div class="jawaban-number">A</div>
                            <div class="row">
                                <div class="col-md-1 text-center">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input correct-answer-radio" type="radio"
                                               name="correct_answer" value="0" id="correct_0" required>
                                    </div>
                                </div>
                                <div class="col-md-11">
                                    <label class="form-label small">Jawaban A</label>
                                    <textarea class="form-control arabic-input" name="pilihan_jawaban[0][teks_jawaban]"
                                              rows="2" placeholder="Tulis jawaban..." required></textarea>
                                    <input type="hidden" name="pilihan_jawaban[0][is_benar]" value="0">
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-danger btn-remove-jawaban" onclick="removeJawaban(this)" style="display: none;">
                                <i class="ti ti-x"></i>
                            </button>
                        </div>

                        <div class="jawaban-item" data-index="1">
                            <div class="jawaban-number">B</div>
                            <div class="row">
                                <div class="col-md-1 text-center">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input correct-answer-radio" type="radio"
                                               name="correct_answer" value="1" id="correct_1" required>
                                    </div>
                                </div>
                                <div class="col-md-11">
                                    <label class="form-label small">Jawaban B</label>
                                    <textarea class="form-control arabic-input" name="pilihan_jawaban[1][teks_jawaban]"
                                              rows="2" placeholder="Tulis jawaban..." required></textarea>
                                    <input type="hidden" name="pilihan_jawaban[1][is_benar]" value="0">
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-danger btn-remove-jawaban" onclick="removeJawaban(this)" style="display: none;">
                                <i class="ti ti-x"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alert Instructions -->
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <i class="ti ti-alert-triangle me-2"></i>
                <div>
                    <strong>Petunjuk:</strong>
                    <ul class="mb-0 mt-1">
                        <li>Pilih salah satu jawaban sebagai jawaban benar dengan mengklik radio button</li>
                        <li>Gunakan bahasa Arab atau Indonesia yang jelas dan mudah dipahami</li>
                        <li>Pastikan tidak ada jawaban yang duplikat atau ambigu</li>
                        <li>Untuk teks Arab, gunakan format RTL (Right-to-Left)</li>
                        <li>Soal akan diacak secara otomatis menggunakan LCM Algorithm saat digunakan</li>
                    </ul>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-2"></i>Simpan Soal
                </button>
                <button type="button" class="btn btn-info" onclick="previewSoal()">
                    <i class="ti ti-eye me-2"></i>Preview
                </button>
                <a href="<?= site_url('soal') ?>" class="btn btn-danger">
                    <i class="ti ti-circle-x me-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Soal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Preview content akan di-generate -->
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let jawabanIndex = 2;
const maxJawaban = 10;

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    // Custom validation untuk jawaban
    const form = document.getElementById('soalForm');
    form.addEventListener('submit', function(event) {
        if (!validateJawaban()) {
            event.preventDefault();
            event.stopPropagation();
        }
    }, false);

    // Initialize
    updateJawabanLabels();
    initializeCorrectAnswerHandlers();
});

// Character counter untuk pertanyaan
const pertanyaan = document.getElementById('pertanyaan');
const pertanyaanCount = document.getElementById('pertanyaanCount');

if (pertanyaan && pertanyaanCount) {
    pertanyaan.addEventListener('input', function() {
        const count = this.value.length;
        pertanyaanCount.textContent = count;
        if (count > 1000) {
            this.value = this.value.substring(0, 1000);
            pertanyaanCount.textContent = 1000;
        }
    });
}

// Arabic preview
const arabicPreview = document.getElementById('arabicPreview');

if (pertanyaan && arabicPreview) {
    function hasArabic(text) {
        const arabicRegex = /[\u0600-\u06FF]/;
        return arabicRegex.test(text);
    }

    pertanyaan.addEventListener('input', function() {
        const text = this.value.trim();

        if (hasArabic(text)) {
            const lines = text.split('\n');
            const arabicHtml = lines.map(line => {
                if (hasArabic(line)) {
                    return `<div style="font-family: 'Amiri', 'Traditional Arabic', serif; font-size: 1.1rem; line-height: 1.8; direction: rtl; text-align: right; margin-bottom: 8px;">${line}</div>`;
                }
                return `<div style="font-size: 0.9rem; color: #666; margin-bottom: 8px;">${line}</div>`;
            }).join('');
            arabicPreview.innerHTML = arabicHtml;
        } else if (text) {
            arabicPreview.innerHTML = `<span class="text-muted">Tidak ada teks Arab terdeteksi dalam pertanyaan.</span>`;
        } else {
            arabicPreview.innerHTML = '<span class="text-muted">Preview teks Arab akan muncul di sini...</span>';
        }
    });
}

// Fungsi untuk tambah jawaban
function addJawaban() {
    const container = document.getElementById('jawabanContainer');
    const currentCount = container.children.length;

    if (currentCount >= maxJawaban) {
        alert(`Maksimal ${maxJawaban} pilihan jawaban`);
        return;
    }

    const jawabanDiv = document.createElement('div');
    jawabanDiv.className = 'jawaban-item';
    jawabanDiv.dataset.index = jawabanIndex;

    const letter = String.fromCharCode(65 + jawabanIndex); // A, B, C, ...

    jawabanDiv.innerHTML = `
        <div class="jawaban-number">${letter}</div>
        <div class="row">
            <div class="col-md-1 text-center">
                <div class="form-check mt-2">
                    <input class="form-check-input correct-answer-radio" type="radio"
                           name="correct_answer" value="${jawabanIndex}" id="correct_${jawabanIndex}">
                </div>
            </div>
            <div class="col-md-11">
                <label class="form-label small">Jawaban ${letter}</label>
                <textarea class="form-control arabic-input" name="pilihan_jawaban[${jawabanIndex}][teks_jawaban]"
                          rows="2" placeholder="Tulis jawaban..."></textarea>
                <input type="hidden" name="pilihan_jawaban[${jawabanIndex}][is_benar]" value="0">
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-danger btn-remove-jawaban" onclick="removeJawaban(this)">
            <i class="ti ti-x"></i>
        </button>
    `;

    container.appendChild(jawabanDiv);
    jawabanIndex++;

    updateJawabanLabels();
    initializeCorrectAnswerHandlers();
    updateRemoveButtons();
}

// Fungsi untuk hapus jawaban
function removeJawaban(button) {
    const container = document.getElementById('jawabanContainer');
    const currentCount = container.children.length;

    if (currentCount <= 2) {
        alert('Minimal harus ada 2 pilihan jawaban');
        return;
    }

    button.closest('.jawaban-item').remove();
    updateJawabanLabels();
    updateRemoveButtons();
}

// Update label jawaban (A, B, C, ...)
function updateJawabanLabels() {
    const container = document.getElementById('jawabanContainer');
    const jawabanItems = container.querySelectorAll('.jawaban-item');

    jawabanItems.forEach((item, index) => {
        const letter = String.fromCharCode(65 + index);
        const numberDiv = item.querySelector('.jawaban-number');
        const label = item.querySelector('.form-label small');

        if (numberDiv) numberDiv.textContent = letter;
        if (label) label.textContent = `Jawaban ${letter}`;

        // Update name attributes
        const jawabanInputs = item.querySelectorAll('textarea, input[type="hidden"]');
        jawabanInputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                const newName = name.replace(/\[\d+\]/, `[${index}]`);
                input.setAttribute('name', newName);
            }
        });

        // Update radio button value
        const radio = item.querySelector('.correct-answer-radio');
        if (radio) radio.value = index;
    });
}

// Update tombol remove
function updateRemoveButtons() {
    const container = document.getElementById('jawabanContainer');
    const jawabanItems = container.querySelectorAll('.jawaban-item');
    const currentCount = jawabanItems.length;

    jawabanItems.forEach(item => {
        const removeBtn = item.querySelector('.btn-remove-jawaban');
        if (removeBtn) {
            removeBtn.style.display = currentCount > 2 ? 'block' : 'none';
        }
    });
}

// Initialize correct answer handlers
function initializeCorrectAnswerHandlers() {
    document.querySelectorAll('.correct-answer-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            // Reset semua is_benar
            document.querySelectorAll('input[name^="pilihan_jawaban"][name$="[is_benar]"]').forEach(input => {
                input.value = 0;
            });

            // Set is_benar untuk jawaban yang dipilih
            if (this.checked) {
                const jawabanItem = this.closest('.jawaban-item');
                const isBenarInput = jawabanItem.querySelector('input[name$="[is_benar]"]');
                if (isBenarInput) {
                    isBenarInput.value = 1;
                }

                // Update styling
                document.querySelectorAll('.jawaban-item').forEach(item => {
                    item.classList.remove('correct-answer');
                });
                jawabanItem.classList.add('correct-answer');
            }
        });
    });
}

// Validate jawaban sebelum submit
function validateJawaban() {
    const container = document.getElementById('jawabanContainer');
    const jawabanItems = container.querySelectorAll('.jawaban-item');
    let hasCorrectAnswer = false;
    let allFilled = true;

    jawabanItems.forEach((item, index) => {
        const textarea = item.querySelector('textarea');
        const isCorrect = item.querySelector('.correct-answer-radio').checked;

        if (!textarea.value.trim()) {
            allFilled = false;
            textarea.classList.add('is-invalid');
        } else {
            textarea.classList.remove('is-invalid');
        }

        if (isCorrect) {
            hasCorrectAnswer = true;
        }
    });

    if (!allFilled) {
        alert('Semua pilihan jawaban harus diisi');
        return false;
    }

    if (!hasCorrectAnswer) {
        alert('Pilih salah satu jawaban sebagai jawaban benar');
        return false;
    }

    return true;
}

// Removed auto sync materi dengan tingkat kesulitan since materi no longer has difficulty field

// Preview soal
function previewSoal() {
    const form = document.getElementById('soalForm');
    const formData = new FormData(form);

    // Collect jawaban data
    const jawabanData = [];
    const container = document.getElementById('jawabanContainer');
    const jawabanItems = container.querySelectorAll('.jawaban-item');

    jawabanItems.forEach((item, index) => {
        const textarea = item.querySelector('textarea');
        const isCorrect = item.querySelector('.correct-answer-radio').checked;

        if (textarea.value.trim()) {
            jawabanData.push({
                teks_jawaban: textarea.value,
                is_benar: isCorrect
            });
        }
    });

    const previewHtml = `
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Pertanyaan:</h6>
                <p class="card-text">${formData.get('pertanyaan') || 'Belum diisi'}</p>

                <h6 class="card-title mt-3">Pilihan Jawaban:</h6>
                <div class="list-group">
                    ${jawabanData.map((jawaban, index) => {
                        const letter = String.fromCharCode(65 + index);
                        return `
                            <div class="list-group-item ${jawaban.is_benar ? 'list-group-item-success' : ''}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><strong>${letter}.</strong> ${jawaban.teks_jawaban}</span>
                                    ${jawaban.is_benar ? '<span class="badge bg-success">Benar</span>' : ''}
                                </div>
                            </div>
                        `;
                    }).join('')}
                </div>
            </div>
        </div>
    `;

    document.getElementById('previewContent').innerHTML = previewHtml;
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
}

// Initialize remove buttons on load
updateRemoveButtons();
</script>
<?= $this->endSection() ?>