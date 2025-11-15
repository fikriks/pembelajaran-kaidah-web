<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Monitoring Quiz - <?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="mb-4">
    <h4 class="fw-bold text-dark">Monitoring Quiz Siswa</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
            <li class="breadcrumb-item active">Monitoring Quiz</li>
        </ol>
    </nav>
</div>

<!-- Statistics Cards -->
<?= view('partials/stats_row', [
    'stats' => [
        [
            'title' => 'Total Sesi Quiz',
            'value' => $stats['total_sessions'] ?? 0,
            'subtitle' => 'Semua sesi',
            'icon' => 'file-description',
            'variant' => 'primary'
        ],
        [
            'title' => 'Skor Rata-rata',
            'value' => $stats['average_score'] ?? 0,
            'subtitle' => 'Skor tertinggi: ' . ($stats['best_score'] ?? 0),
            'icon' => 'trending-up',
            'variant' => 'info'
        ],
        [
            'title' => 'Siswa Aktif',
            'value' => $stats['unique_students'] ?? 0,
            'subtitle' => 'Hari ini: ' . ($stats['today_sessions'] ?? 0) . ' sesi',
            'icon' => 'users',
            'variant' => 'warning'
        ],
        [
            'title' => 'Waktu Rata-rata',
            'value' => $stats['average_duration'] ?? '0 detik',
            'subtitle' => 'Minggu ini: ' . ($stats['week_sessions'] ?? 0) . ' sesi',
            'icon' => 'clock',
            'variant' => 'secondary'
        ]
    ]
]) ?>

<!-- Quiz Sessions Section -->
<div class="row">
    <div class="col-12">
        <!-- Quiz Sessions Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header border-0 bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0 fw-bold">Hasil Quiz Siswa</h5>
                        <small class="text-muted">Klik detail untuk melihat jawaban siswa</small>
                    </div>
                    <div class="d-flex gap-2">
                        <input type="date" id="tanggalFilter" class="form-control form-control-sm" style="width: 150px;">
                        <select id="kelasFilter" class="form-select form-select-sm" style="width: 150px;">
                            <option value="">Semua Kelas</option>
                            <option value="X">Kelas X</option>
                            <option value="XI">Kelas XI</option>
                            <option value="XII">Kelas XII</option>
                        </select>
                        <select id="statusFilter" class="form-select form-select-sm" style="width: 150px;">
                            <option value="">Semua Status</option>
                            <option value="selesai">Selesai</option>
                            <option value="sedang_berlangsung">Sedang Berlangsung</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="quizSessionsTable" class="table table-hover text-nowrap align-middle">
                        <thead class="text-dark">
                            <tr>
                                <th>Siswa</th>
                                <th>Materi</th>
                                <th>Waktu Mulai</th>
                                <th>Status</th>
                                <th>Nilai</th>
                                <th>Benar</th>
                                <th>Durasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="quizSessionsBody">
                            <!-- Will be loaded via JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        <small>Menampilkan <span id="showingCount">0</span> sesi</small>
                    </div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0" id="pagination">
                            <!-- Will be populated via JavaScript -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let currentPage = 1;
let totalPages = 1;
let currentFilters = {
    search: '',
    kelas: '',
    status: '',
    tanggal: ''
};

$(document).ready(function() {
    loadQuizSessions();

    // Set today's date as default filter
    $('#tanggalFilter').val(new Date().toISOString().split('T')[0]);

    // Event listeners
    $('#kelasFilter, #statusFilter, #tanggalFilter').on('change', function() {
        currentPage = 1;
        loadQuizSessions();
    });

    // Search functionality
    $('#quizSessionsTable').on('keyup', 'thead input', function() {
        currentFilters.search = $(this).val();
        currentPage = 1;
        loadQuizSessions();
    });
});

function loadQuizSessions() {
    currentFilters.kelas = $('#kelasFilter').val();
    currentFilters.status = $('#statusFilter').val();
    currentFilters.tanggal = $('#tanggalFilter').val();

    const params = new URLSearchParams({
        limit: 10,
        offset: (currentPage - 1) * 10,
        ...currentFilters
    });

    fetch(`<?= site_url('quiz-monitoring/sessions') ?>?${params}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                renderQuizSessions(data.data);
                renderPagination(data.total);
                updateShowingCount(data.data.length, data.total);
            } else {
                toast.error('Gagal memuat data sesi quiz');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toast.error('Terjadi kesalahan saat memuat data');
        });
}

function renderQuizSessions(sessions) {
    const tbody = $('#quizSessionsBody');

    if (sessions.length === 0) {
        tbody.html(`
            <tr>
                <td colspan="8" class="text-center py-4">
                    <i class="ti ti-file-description fs-1 text-muted mb-2"></i>
                    <p class="text-muted">Tidak ada data sesi quiz</p>
                </td>
            </tr>
        `);
        return;
    }

    tbody.html(sessions.map(session => `
        <tr>
            <td>
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                        <i class="ti ti-user text-primary fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-semibold">${escapeHtml(session.nama_lengkap)}</div>
                        <div class="small text-muted">${escapeHtml(session.nis)} • ${escapeHtml(session.kelas)}</div>
                    </div>
                </div>
            </td>
            <td>
                <div>
                    <div class="fw-semibold">${escapeHtml(session.judul_kaidah)}</div>
                    <div class="small text-muted">${escapeHtml(session.nama_bab)}</div>
                </div>
            </td>
            <td>
                <div class="small">
                    <div>${formatDate(session.waktu_mulai)}</div>
                    <div class="text-muted">${formatTime(session.waktu_mulai)}</div>
                </div>
            </td>
            <td>${session.status_badge}</td>
            <td>
                <div class="d-flex align-items-center">
                    <span class="fw-bold ${getScoreColor(session.skor)} me-2">${session.skor}</span>
                    <div class="small text-muted">(${session.persentase_benar}%)</div>
                </div>
            </td>
            <td>
                <span class="badge bg-success rounded-3">${session.soal_benar}/${session.total_soal}</span>
            </td>
            <td>
                <span class="small text-muted">${session.durasi}</span>
            </td>
            <td>
                <div class="table-actions">
                    <a href="<?= site_url('quiz-monitoring') ?>/${session.id_sesi}/detail"
                       class="btn btn-sm btn-primary me-1" title="Lihat Detail">
                        <i class="ti ti-eye"></i>
                    </a>
                </div>
            </td>
        </tr>
    `).join(''));
}

function renderPagination(total) {
    totalPages = Math.ceil(total / 10);
    const pagination = $('#pagination');

    if (totalPages <= 1) {
        pagination.html('');
        return;
    }

    let html = '';

    // Previous button
    html += `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage - 1})">
                <i class="ti ti-chevron-left"></i>
            </a>
        </li>
    `;

    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
            html += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                </li>
            `;
        } else if (i === currentPage - 2 || i === currentPage + 2) {
            html += '<li class="page-item disabled"><a class="page-link">...</a></li>';
        }
    }

    // Next button
    html += `
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage + 1})">
                <i class="ti ti-chevron-right"></i>
            </a>
        </li>
    `;

    pagination.html(html);
}

function changePage(page) {
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    loadQuizSessions();
}

function updateShowingCount(showing, total) {
    $('#showingCount').text(`Menampilkan ${showing} dari ${total} sesi`);
}

function getScoreColor(score) {
    if (score >= 80) return 'text-success';
    if (score >= 60) return 'text-warning';
    return 'text-danger';
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
}

function formatTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
<?= $this->endSection() ?>