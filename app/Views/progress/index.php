<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Progress Belajar - <?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="mb-4">
    <h4 class="fw-bold text-dark">Progress Belajar Siswa</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>" class="text-muted">Dashboard</a></li>
            <li class="breadcrumb-item active">Progress Belajar</li>
        </ol>
    </nav>
</div>

<!-- Statistics Cards -->
<?= view('partials/stats_row', [
    'stats' => [
        [
            'title' => 'Total Siswa',
            'value' => $stats['total_students'] ?? 0,
            'subtitle' => 'Terdaftar',
            'icon' => 'users',
            'variant' => 'primary'
        ],
        [
            'title' => 'Siswa Aktif Belajar',
            'value' => $stats['students_with_progress'] ?? 0,
            'subtitle' => 'Sudah ada progress',
            'icon' => 'chart-line',
            'variant' => 'success'
        ]
    ]
]) ?>


<!-- Students with Progress Section -->
<div class="row">
    <div class="col-lg-8">
        <!-- Students Progress Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header border-0 bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0 fw-bold">Progress Siswa</h5>
                        <small class="text-muted">Klik nama siswa untuk detail progress</small>
                    </div>
                    <div class="d-flex gap-2">
                        <select id="kelasFilter" class="form-select form-select-sm" style="width: 150px;">
                            <option value="">Semua Kelas</option>
                            <option value="X">Kelas X</option>
                            <option value="XI">Kelas XI</option>
                            <option value="XII">Kelas XII</option>
                        </select>
                        <select id="statusFilter" class="form-select form-select-sm" style="width: 150px;">
                            <option value="">Semua Status</option>
                            <option value="AKTIF">Aktif</option>
                            <option value="NONAKTIF">Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="studentsProgressTable" class="table table-hover text-nowrap align-middle">
                        <thead class="text-dark">
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Status</th>
                                <th>Progress</th>
                                <th>Sesi</th>
                                <th>Skor Rata-rata</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="studentsProgressBody">
                            <!-- Will be loaded via JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        <small>Menampilkan <span id="showingCount">0</span> siswa</small>
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
    status: ''
};

$(document).ready(function() {
    loadStudents();

    // Event listeners
    $('#kelasFilter, #statusFilter').on('change', function() {
        currentPage = 1;
        loadStudents();
    });

    // Search functionality
    $('#studentsProgressTable').on('keyup', 'thead input', function() {
        currentFilters.search = $(this).val();
        currentPage = 1;
        loadStudents();
    });
});

function loadStudents() {
    currentFilters.kelas = $('#kelasFilter').val();
    currentFilters.status = $('#statusFilter').val();

    const params = new URLSearchParams({
        limit: 10,
        offset: (currentPage - 1) * 10,
        ...currentFilters
    });

    fetch(`<?= site_url('progress/students') ?>?${params}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                renderStudents(data.data);
                renderPagination(data.total);
                updateShowingCount(data.data.length, data.total);
            } else {
                toast.error('Gagal memuat data siswa');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toast.error('Terjadi kesalahan saat memuat data');
        });
}

function renderStudents(students) {
    const tbody = $('#studentsProgressBody');

    if (students.length === 0) {
        tbody.html(`
            <tr>
                <td colspan="7" class="text-center py-4">
                    <i class="ti ti-inbox fs-1 text-muted mb-2"></i>
                    <p class="text-muted">Tidak ada data siswa</p>
                </td>
            </tr>
        `);
        return;
    }

    tbody.html(students.map(student => `
        <tr>
            <td>
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                        <i class="ti ti-user text-primary fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-semibold">${escapeHtml(student.nama_lengkap)}</div>
                        <div class="small text-muted">${escapeHtml(student.nis)}</div>
                    </div>
                </div>
            </td>
            <td>${escapeHtml(student.kelas)}</td>
            <td>
                <span class="badge bg-${student.status === 'AKTIF' ? 'success' : 'secondary'} rounded-3">
                    ${student.status}
                </span>
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="progress flex-grow-1 me-2" style="height: 6px;">
                        <div class="progress-bar bg-primary" style="width: ${student.progress_percentage}%"></div>
                    </div>
                    <span class="small fw-semibold">${student.progress_percentage}%</span>
                </div>
            </td>
            <td>
                <span class="badge bg-info rounded-3">${student.total_sessions} sesi</span>
            </td>
            <td>
                <span class="fw-bold text-primary">${student.average_score}</span>
            </td>
            <td>
                <div class="table-actions">
                    <a href="<?= site_url('progress') ?>/${student.id}/show"
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
    loadStudents();
}

function updateShowingCount(showing, total) {
    $('#showingCount').text(`Menampilkan ${showing} dari ${total} siswa`);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
<?= $this->endSection() ?>