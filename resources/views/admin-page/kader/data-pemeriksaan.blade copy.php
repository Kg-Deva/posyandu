{{-- filepath: resources/views/admin-page/kader/data-pemeriksaan.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<title>Data Pemeriksaan</title>
@include('admin-layouts.header')

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        @include('admin-layouts.icon')
                        <div class="sidebar-toggler x">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                @include('admin-layouts.navbar')
            </div>
        </div>
        
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            {{-- ALERT MESSAGES --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="page-heading">
                <h3>Data Pemeriksaan</h3>
                <h5 class="text-muted">Kelola dan lihat data hasil pemeriksaan posyandu</h5>
            </div>

            <div class="page-content">
                {{-- FILTER & SEARCH SECTION --}}
                <section class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    {{-- SEARCH --}}
                                    <div class="col-lg-2 col-md-4 mb-3">
                                        <label class="form-label">Pencarian</label>
                                        <input type="text" id="search" class="form-control" placeholder="NIK atau Nama...">
                                    </div>
                                    
                                    {{-- FILTER BULAN --}}
                                    <div class="col-lg-2 col-md-3 mb-3">
                                        <label class="form-label">Bulan</label>
                                        <select id="filter-bulan" class="form-select">
                                            <option value="">Semua</option>
                                            <option value="1">Januari</option>
                                            <option value="2">Februari</option>
                                            <option value="3">Maret</option>
                                            <option value="4">April</option>
                                            <option value="5">Mei</option>
                                            <option value="6">Juni</option>
                                            <option value="7">Juli</option>
                                            <option value="8">Agustus</option>
                                            <option value="9">September</option>
                                            <option value="10">Oktober</option>
                                            <option value="11">November</option>
                                            <option value="12">Desember</option>
                                        </select>
                                    </div>
                                    
                                    {{-- FILTER TAHUN --}}
                                    <div class="col-lg-2 col-md-3 mb-3">
                                        <label class="form-label">Tahun</label>
                                        <select id="filter-tahun" class="form-select">
                                            <option value="">Semua</option>
                                        </select>
                                    </div>
                                    
                                    {{-- FILTER KATEGORI --}}
                                    <div class="col-lg-2 col-md-3 mb-3">
                                        <label class="form-label">Kategori</label>
                                        <select id="filter-role" class="form-select">
                                            <option value="">Semua</option>
                                            <option value="balita">Balita</option>
                                            <option value="remaja">Remaja</option>
                                            <option value="dewasa">Dewasa</option>
                                            <option value="lansia">Lansia</option>
                                            <option value="ibuhamil">Ibu Hamil</option>
                                        </select>
                                    </div>
                                    
                                    {{-- FILTER RW --}}
                                    <div class="col-lg-1 col-md-3 mb-3">
                                        <label class="form-label">RW</label>
                                        <select id="filter-rw" class="form-select">
                                            <option value="">Semua</option>
                                        </select>
                                    </div>
                                    
                                    {{-- ✅ FILTER RUJUKAN BARU --}}
                                    <div class="col-lg-2 col-md-4 mb-3">
                                        <label class="form-label">Rujukan</label>
                                        <select id="filter-rujukan" class="form-select">
                                            <option value="">Semua</option>
                                            <option value="Perlu Rujukan">Perlu Rujukan</option>
                                            <option value="Tidak Perlu Rujukan">Normal</option>
                                        </select>
                                    </div>
                                    
                                    {{-- RESET BUTTON --}}
                                    <div class="col-lg-1 col-md-2 mb-3 d-flex align-items-end">
                                        <button id="btn-reset" class="btn btn-outline-secondary w-100" title="Reset">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- STATISTICS CARDS --}}
                {{-- GANTI CARD TOTAL PEMERIKSAAN JADI NON WARGA --}}
                <section class="row mb-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 d-flex justify-content-start">
                                        <div class="stats-icon blue mb-2">
                                            <i class="iconly-boldProfile"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Non Warga</h6>
                                        <h6 class="font-extrabold mb-0" id="non-warga">0</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 d-flex justify-content-start">
                                        <div class="stats-icon green mb-2">
                                            <i class="iconly-boldCalendar"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Bulan Ini</h6>
                                        <h6 class="font-extrabold mb-0" id="bulan-ini">0</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 d-flex justify-content-start">
                                        <div class="stats-icon red mb-2">
                                            <i class="iconly-boldDanger"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Perlu Rujukan</h6>
                                        <h6 class="font-extrabold mb-0" id="perlu-rujukan">0</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- LOADING --}}
                <div id="loading" class="text-center py-5" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat data...</p>
                </div>

                {{-- DATA TABLE --}}
                <section class="section" id="data-container">
                    <div class="row" id="table-hover-row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="card-title">Daftar Pemeriksaan</h4>
                                    <a href="/input-pemeriksaan" class="btn btn-primary">
                                        <i data-feather="plus"></i> Input Pemeriksaan
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                           <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Tanggal</th>
                                                    <th>NIK</th>
                                                    <th>Nama</th>
                                                    <th>RW</th>
                                                    <th>Kategori</th>
                                                    <th>BB (kg)</th>        {{-- ✅ JELAS DENGAN SATUAN --}}
                                                    <th>TB (cm)</th>        {{-- ✅ JELAS DENGAN SATUAN --}}
                                                    {{-- <th>Info Lain</th>       --}}
                                                    {{-- <th>Status Kesehatan</th>  --}}
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data-tbody">
                                                <!-- Data akan dimuat di sini -->
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    {{-- PAGINATION --}}
                                    <div id="pagination-container" class="mt-3 d-flex justify-content-between align-items-center">
                                        <div id="pagination-info" class="text-muted">
                                            <!-- Info pagination -->
                                        </div>
                                        <nav>
                                            <ul id="pagination-links" class="pagination pagination-sm mb-0">
                                                <!-- Links pagination -->
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- NO DATA --}}
                <div id="no-data" class="text-center py-5" style="display: none;">
                    <div class="card">
                        <div class="card-body">
                            <i class="bi bi-inbox display-4 text-muted"></i>
                            <h5 class="mt-3 text-muted">Tidak ada data pemeriksaan</h5>
                            <p class="text-muted">Belum ada data atau sesuaikan filter pencarian</p>
                            <a href="/input-pemeriksaan" class="btn btn-primary">
                                <i data-feather="plus"></i> Input Pemeriksaan Pertama
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @include('admin-layouts.footer')
        </div>
    </div>

    {{-- MODAL DETAIL --}}
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Pemeriksaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detail-content">
                    <!-- Content akan dimuat di sini -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @include('admin-layouts.js')

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing Data Pemeriksaan...');
        
        let currentPage = 1;
        let isLoading = false;
        let isSearchMode = false;
        let searchTimeout = null;
        
        // ELEMENTS
        // UPDATE ELEMENTS - GANTI totalPemeriksaan JADI nonWarga
        const elements = {
            search: document.getElementById('search'),
            filterBulan: document.getElementById('filter-bulan'),
            filterTahun: document.getElementById('filter-tahun'),
            filterRole: document.getElementById('filter-role'),
            filterRw: document.getElementById('filter-rw'),
            filterRujukan: document.getElementById('filter-rujukan'), // ✅ TAMBAH INI
            btnReset: document.getElementById('btn-reset'),
            loading: document.getElementById('loading'),
            dataContainer: document.getElementById('data-container'),
            dataTbody: document.getElementById('data-tbody'),
            noData: document.getElementById('no-data'),
            paginationInfo: document.getElementById('pagination-info'),
            paginationLinks: document.getElementById('pagination-links'),
            nonWarga: document.getElementById('non-warga'),
            bulanIni: document.getElementById('bulan-ini'),
            perluRujukan: document.getElementById('perlu-rujukan'),
            detailModal: new bootstrap.Modal(document.getElementById('detailModal')),
            detailContent: document.getElementById('detail-content')
        };
        
        // LOAD FILTER OPTIONS
        function loadFilterOptions() {
            fetch('/data-pemeriksaan/filter-options')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // POPULATE TAHUN
                        data.years.forEach(year => {
                            const option = document.createElement('option');
                            option.value = year;
                            option.textContent = year;
                            elements.filterTahun.appendChild(option);
                        });
                        
                        // POPULATE ROLE
                        data.role_list.forEach(role => {
                            const option = document.createElement('option');
                            option.value = role;
                            option.textContent = role.charAt(0).toUpperCase() + role.slice(1);
                            elements.filterRole.appendChild(option);
                        });
                        
                        // POPULATE RW
                        data.rw_list.forEach(rw => {
                            const option = document.createElement('option');
                            option.value = rw;
                            option.textContent = `RW ${rw}`;
                            elements.filterRw.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading filter options:', error);
                });
        }
        
        // LOAD DATA
        function loadData(page = 1) {
            if (isLoading) return;
            
            isLoading = true;
            currentPage = page;
            
            // SHOW LOADING
            elements.loading.style.display = 'block';
            elements.dataContainer.style.display = 'none';
            elements.noData.style.display = 'none';
            
            // BUILD PARAMS - TAMBAH RUJUKAN
            const params = new URLSearchParams({
                page: page,
                search: elements.search.value,
                bulan: elements.filterBulan.value,
                tahun: elements.filterTahun.value,
                role: elements.filterRole.value,
                rw: elements.filterRw.value,
                rujukan: elements.filterRujukan.value // ✅ TAMBAH INI
            });
            
            fetch(`/data-pemeriksaan/get-data?${params}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderData(data.data, data.pagination);
                        updateStats(data.stats || {});
                    } else {
                        throw new Error(data.message || 'Error loading data');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    elements.noData.style.display = 'block';
                })
                .finally(() => {
                    isLoading = false;
                    elements.loading.style.display = 'none';
                });
        }
        
        // RENDER DATA - FIX INI BRO!
        function renderData(data, pagination) {
            if (!data || data.length === 0) {
                elements.noData.style.display = 'block';
                elements.dataContainer.style.display = 'none';
                return;
            }
            
            let html = '';
            data.forEach((item, index) => {
                const no = ((pagination.current_page - 1) * pagination.per_page) + index + 1;
                const tanggal = new Date(item.tanggal_pemeriksaan).toLocaleDateString('id-ID');
                
                const userData = item.user || {};
                const nama = userData.nama || '-';
                const rw = userData.rw || '-';
                const level = userData.level || '-';
                
                // STATUS BADGES
                const roleBadge = getBadgeClass(level);
                
                // ✅ CUMA RUJUKAN BADGE AJA (STATUS KESEHATAN DIHILANGKAN)
                const rujukanBadge = item.rujuk_puskesmas === 'Perlu Rujukan' ? 
                    '<span class="badge bg-danger">Perlu Rujukan</span>' : 
                    '<span class="badge bg-success">Normal</span>';
                
                html += `
                    <tr>
                        <td>${no}</td>
                        <td>${tanggal}</td>
                        <td class="text-bold-500">${item.nik}</td>
                        <td class="text-bold-500">${nama}</td>
                        <td class="text-center"><span class="badge bg-secondary">RW ${rw}</span></td>
                        <td class="text-center">${roleBadge}</td>
                        <td class="text-center"><strong>${item.bb || '-'}</strong></td>
                        <td class="text-center"><strong>${item.tb || '-'}</strong></td>
                        <!-- ✅ HILANGKAN STATUS KESEHATAN COLUMN -->
                        <td class="text-center">${rujukanBadge}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="showDetail(${item.id})" title="Detail">
                                <i data-feather="eye"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            
            elements.dataTbody.innerHTML = html;
            renderPagination(pagination);
            elements.dataContainer.style.display = 'block';
            elements.noData.style.display = 'none';
            
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }
        
        // GET BADGE CLASS
        function getBadgeClass(role) {
            const badges = {
                'balita': '<span class="badge bg-primary">Balita</span>',
                'remaja': '<span class="badge bg-info">Remaja</span>',
                'dewasa': '<span class="badge bg-success">Dewasa</span>',
                'lansia': '<span class="badge bg-warning">Lansia</span>',
                'ibuhamil': '<span class="badge bg-danger">Ibu Hamil</span>'
            };
            return badges[role] || `<span class="badge bg-secondary">${role}</span>`;
        }
        
        // RENDER PAGINATION
        function renderPagination(pagination) {
            // INFO
            elements.paginationInfo.textContent = 
                `Menampilkan ${pagination.from || 0} - ${pagination.to || 0} dari ${pagination.total} data`;
            
            // LINKS
            let linksHtml = '';
            
            // Previous
            if (pagination.current_page > 1) {
                linksHtml += `
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="${pagination.current_page - 1}">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                `;
            }
            
            // Pages
            for (let i = 1; i <= pagination.last_page; i++) {
                if (i === pagination.current_page) {
                    linksHtml += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
                } else if (
                    i === 1 || 
                    i === pagination.last_page || 
                    (i >= pagination.current_page - 2 && i <= pagination.current_page + 2)
                ) {
                    linksHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                } else if (
                    i === pagination.current_page - 3 || 
                    i === pagination.current_page + 3
                ) {
                    linksHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
            }
            
            // Next
            if (pagination.current_page < pagination.last_page) {
                linksHtml += `
                    <li class="page-item">
                        </a>
                    </li>
                `;
            }
            
            elements.paginationLinks.innerHTML = linksHtml;
            
            // PAGINATION CLICK HANDLERS
            elements.paginationLinks.querySelectorAll('a.page-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = parseInt(this.dataset.page);
                    if (page && page !== currentPage) {
                        loadData(page);
                    }
                });
            });
        }
        
        // UPDATE STATS
        // UPDATE FUNCTION updateStats() - GANTI totalPemeriksaan JADI nonWarga
        function updateStats(stats) {
            elements.nonWarga.textContent = stats.non_warga || 0; // ✅ GANTI INI
            elements.bulanIni.textContent = stats.bulan_ini || 0;
            elements.perluRujukan.textContent = stats.perlu_rujukan || 0;
        }
        
        // SHOW DETAIL MODAL
        window.showDetail = function(id) {
            elements.detailContent.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat detail...</p>
                </div>
            `;
            
            elements.detailModal.show();
            
            fetch(`/data_pemeriksaan/detail/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderDetailModal(data.data);
                    } else {
                        elements.detailContent.innerHTML = `
                            <div class="alert alert-danger">${data.message}</div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    elements.detailContent.innerHTML = `
                        <div class="alert alert-danger">Gagal memuat detail pemeriksaan</div>
                    `;
                });
        };
        
        // RENDER DETAIL MODAL - FIX INI JUGA
        function renderDetailModal(data) {
            const tanggal = new Date(data.tanggal_pemeriksaan).toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            // ✅ FIX AKSES DATA DARI RELATIONSHIP
            const userData = data.user || {};
            
            elements.detailContent.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Data Pasien</h6>
                        <table class="table table-sm">
                            <tr><td><strong>NIK</strong></td><td>: ${data.nik}</td></tr>
                            <tr><td><strong>Nama</strong></td><td>: ${userData.nama || '-'}</td></tr>
                            <tr><td><strong>Alamat</strong></td><td>: ${userData.alamat || '-'}</td></tr>
                            <tr><td><strong>RW</strong></td><td>: ${userData.rw || '-'}</td></tr>
                            <tr><td><strong>Kategori</strong></td><td>: ${userData.level || '-'}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Data Pemeriksaan</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Tanggal</strong></td><td>: ${tanggal}</td></tr>
                            <tr><td><strong>Umur</strong></td><td>: ${data.umur} bulan</td></tr>
                            <tr><td><strong>Pemeriksa</strong></td><td>: ${data.pemeriksa}</td></tr>
                        </table>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-success">Hasil Pengukuran</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Berat Badan</strong></td><td>: ${data.bb} kg</td></tr>
                            <tr><td><strong>Tinggi Badan</strong></td><td>: ${data.tb} cm</td></tr>
                            <tr><td><strong>Lingkar Kepala</strong></td><td>: ${data.lingkar_kepala || '-'} cm</td></tr>
                            <tr><td><strong>LILA</strong></td><td>: ${data.lila || '-'} cm</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-info">Kesimpulan</h6>
                        <table class="table table-sm">
                            <tr><td><strong>BB/U</strong></td><td>: ${data.kesimpulan_bbu || '-'}</td></tr>
                            <tr><td><strong>TB/U</strong></td><td>: ${data.kesimpulan_tbuu || '-'}</td></tr>
                            <tr><td><strong>BB/TB</strong></td><td>: ${data.kesimpulan_bbtb || '-'}</td></tr>
                            <tr><td><strong>Lingkar Kepala</strong></td><td>: ${data.kesimpulan_lingkar_kepala || '-'}</td></tr>
                            <tr><td><strong>LILA</strong></td><td>: ${data.kesimpulan_lila || '-'}</td></tr>
                        </table>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-warning">Status & Rujukan</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Status Perubahan BB:</strong> ${data.status_perubahan_bb || '-'}</p>
                                <p><strong>Jumlah Gejala TBC:</strong> ${data.jumlah_gejala_tbc || 0} gejala</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Rujukan Puskesmas:</strong> 
                                    ${data.rujuk_puskesmas === 'Perlu Rujukan' ? 
                                        '<span class="badge bg-danger">Perlu Rujukan</span>' : 
                                        '<span class="badge bg-success">Tidak Perlu Rujukan</span>'
                                    }
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // SEARCH & FILTER HANDLERS
        elements.search.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                loadData(1);
            }, 500);
        });
        
        [elements.filterBulan, elements.filterTahun, elements.filterRole, elements.filterRw, elements.filterRujukan].forEach(filter => {
            filter.addEventListener('change', () => {
                loadData(1);
            });
        });
        
        // RESET FILTER
        elements.btnReset.addEventListener('click', function() {
            elements.search.value = '';
            elements.filterBulan.value = '';
            elements.filterTahun.value = '';
            elements.filterRole.value = '';
            elements.filterRw.value = '';
            elements.filterRujukan.value = ''; // ✅ TAMBAH INI
            loadData(1);
        });
        
        // INITIALIZE
        loadFilterOptions();
        loadData(1);
        
        console.log('Data Pemeriksaan initialized successfully');
    });
    </script>
</body>
</html>