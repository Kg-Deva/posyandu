<!DOCTYPE html>
<html lang="en">
<title>Dashboard</title>
@include('admin-layouts.header')

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">

                        @include('admin-layouts.icon')

                        <div class="sidebar-toggler  x">
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
            {{-- @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif --}}
            {{-- Update bagian alert di dashboard.blade.php --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- ✅ TAMBAHKAN ERROR ALERT --}}
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="page-heading">
                <h3>Dashboard</h3>
                <h5 class="text-muted">
                    {{ Auth::user()->name ?? 'Nama Tidak Tersedia' }},
                    <span class="fw-bold">{{ Auth::user()->level ?? 'Level Tidak Tersedia' }}</span>
                </h5>
            </div>
            <div class="page-content">
                <section class="row">
                    {{-- <div class="col-12 col-lg-9"> --}}
                    <div class="col-lg-16">
                        <div class="row">
                            {{-- <div class="row mb-4"> --}}
                                <!-- 1. BALITA -->
                                <div class="col-6 col-lg-4 col-md-4">
                                    <div class="card">
                                        <div class="card-body px-4 py-4-5">
                                            <div class="row">
                                                <div class="col-md-4 d-flex justify-content-start">
                                                    <div class="stats-icon blue mb-2">
                                                        <i class="iconly-boldProfile"></i> <!-- Icon Balita -->
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <h6 class="text-muted font-semibold">Balita</h6>
                                                    <h6 class="font-extrabold mb-0">{{ $balitaCount ?? 0 }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 2. REMAJA -->
                                <div class="col-6 col-lg-4 col-md-4">
                                    <div class="card">
                                        <div class="card-body px-4 py-4-5">
                                            <div class="row">
                                                <div class="col-md-4 d-flex justify-content-start">
                                                    <div class="stats-icon purple mb-2">
                                                        <i class="iconly-boldUser"></i> <!-- Icon Remaja -->
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <h6 class="text-muted font-semibold">Remaja</h6>
                                                    <h6 class="font-extrabold mb-0">{{ $remajaCount ?? 0 }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 3. DEWASA -->
                                <div class="col-6 col-lg-4 col-md-4">
                                    <div class="card">
                                        <div class="card-body px-4 py-4-5">
                                            <div class="row">
                                                <div class="col-md-4 d-flex justify-content-start">
                                                    <div class="stats-icon green mb-2">
                                                        <i class="iconly-boldWork"></i> <!-- Icon Dewasa -->
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <h6 class="text-muted font-semibold">Dewasa</h6>
                                                    <h6 class="font-extrabold mb-0">{{ $dewasaCount ?? 0 }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- ROW 2: Kategori pasien posyandu (3 item) -->
                                {{-- <div class="row mb-4"> --}}
                                <!-- 4. IBU HAMIL -->
                                <div class="col-6 col-lg-4 col-md-4">
                                    <div class="card">
                                        <div class="card-body px-4 py-4-5">
                                            <div class="row">
                                                <div class="col-md-4 d-flex justify-content-start">
                                                    <div class="stats-icon bg-danger mb-2">
                                                        <i class="iconly-boldHeart"></i> <!-- Icon Ibu Hamil -->
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <h6 class="text-muted font-semibold">Ibu Hamil</h6>
                                                    <h6 class="font-extrabold mb-0">{{ $ibuhamilCount ?? 0 }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 5. LANSIA -->
                                <div class="col-6 col-lg-4 col-md-4">
                                    <div class="card">
                                        <div class="card-body px-4 py-4-5">
                                            <div class="row">
                                                <div class="col-md-4 d-flex justify-content-start">
                                                    <div class="stats-icon red mb-2">
                                                        <i class="iconly-boldStar"></i> <!-- Icon Lansia -->
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <h6 class="text-muted font-semibold">Lansia</h6>
                                                    <h6 class="font-extrabold mb-0">{{ $lansiaCount ?? 0 }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 6. TOTAL PASIEN -->
                                <div class="col-6 col-lg-4 col-md-4">
                                    <div class="card">
                                        <div class="card-body px-4 py-4-5">
                                            <div class="row">
                                                <div class="col-md-4 d-flex justify-content-start">
                                                    <div class="stats-icon bg-primary mb-2">
                                                        <i class="iconly-boldActivity"></i> <!-- Icon Total -->
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <h6 class="text-muted font-semibold">Total Akun</h6>
                                                    <h6 class="font-extrabold mb-0">{{ $totalPasien ?? 0 }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {{-- </div> --}}
                        </div>



                        {{-- @if (auth()->user()->level == 'adminpengelola')     --}}
                        <!-- Hoverable rows start -->
                        <section class="section">
                            <div class="row" id="table-hover-row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Daftar Role Akun / Pasien</h4>
                                            <input type="text" id="search-anggota" class="form-control mt-2"
                                                placeholder="Cari nama/email akun...">
                                        </div>
                                        <div class="card-body">
                                            <p>
                                                <a class="dropdown-item text-success" href="{{ url('tambah-pasien') }}">
                                                    <i data-feather="plus"></i> Tambah
                                                </a>
                                            </p>
                                            <!-- table hover -->
                                            <div class="table-responsive">
                                                <table class="table table-hover mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Nama</th>
                                                            <th>Email</th>
                                                            <th>Level</th>
                                                            <th>Status</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="anggota-table-body">
                                                        @foreach ($data as $item)
                                                            @if ($item->id != auth()->user()->id)
                                                                <tr>
                                                                    {{-- <tr class="{{ $item->type == 'bukan warga' ? 'bg-warning' : '' }}"> --}}
                                                                    <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                                                    </td>

                                                                    <td class="text-bold-500">
                                                                        {{ $item->name }}
                                                                        @if ($item->type == 'bukan warga')
                                                                            <span class="badge bg-secondary ms-1"
                                                                                title="Bukan warga">NW</span>
                                                                        @endif
                                                                    </td>

                                                                    <td>{{ $item->email }}</td>
                                                                    <td class="text-bold-500">{{ $item->level }}</td>
                                                                    <td>
                                                                        @if ($item->status)
                                                                            <span class="badge bg-success">Aktif</span>
                                                                        @else
                                                                            <span class="badge bg-danger">Tidak
                                                                                Aktif</span>
                                                                        @endif
                                                                    </td>
                                                                   
                                                                    <td>
                                                                        @if ($item->level != 'admin' && $item->level != 'kader')
                                                                            <div class="d-flex gap-2">
                                                                                <a class="btn btn-sm btn-primary"
                                                                                    href="{{ url('edit-pasien', $item->id) }}">
                                                                                    <i data-feather="edit"></i> Edit
                                                                                </a>
                                                                                <a class="btn btn-sm btn-danger"
                                                                                    href="{{ url('delete-pasien', $item->id) }}"
                                                                                    onclick="confirmation(event)">
                                                                                    <i data-feather="trash"></i> Delete
                                                                                </a>
                                                                            </div>
                                                                    <td>
                                                                        @if ($item->status)
                                                                            <button
                                                                                class="btn btn-sm {{ $item->data_lengkap ? 'btn-info' : 'btn-warning' }} lengkapi-data-btn"
                                                                                data-id="{{ $item->id }}">
                                                                                <i
                                                                                    data-feather="{{ $item->data_lengkap ? 'edit' : 'user-check' }}"></i>
                                                                                {{ $item->data_lengkap ? 'Edit Data' : 'Lengkapi Data' }}
                                                                            </button>
                                                                        @endif
                                                                    </td>
                                                            @endif
                                                            </td>
                                                            </tr>
                                                        @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="mt-3">
                                                    {{ $data->links('pagination::bootstrap-4') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- Hoverable rows end -->
                        {{-- @endif   --}}

                    </div>

                </section>
            </div>

            @include('admin-layouts.footer')
        </div>
    </div>
    @include('admin-layouts.js')

    {{-- <script>
        // Simpan user id ke JS variable
        const currentUserId = {{ auth()->user()->id }};
    </script>
    @verbatim
        <script>
            document.getElementById('search-anggota').addEventListener('keyup', function() {
                let q = this.value.toLowerCase();
                let rows = document.querySelectorAll('#anggota-table-body tr');
                rows.forEach(row => {
                    let nama = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    let email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    if (nama.includes(q) || email.includes(q)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        </script>
    @endverbatim
</script> --}}



<script>
    const currentUserId = {{ auth()->user()->id }};
    let isSearchMode = false;
    let searchTimeout = null;
</script>

<script>
document.getElementById('search-anggota').addEventListener('keyup', function() {
    let q = this.value.trim();
    let tbody = document.getElementById('anggota-table-body');
    
    clearTimeout(searchTimeout);
    
    // ✅ JIKA KOSONG - RELOAD PAGINATION
    if (q === '') {
        if (isSearchMode) {
            window.location.reload();
        }
        return;
    }
    
    isSearchMode = true;
    
    // ✅ DEBOUNCE SEARCH
    searchTimeout = setTimeout(() => {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-3">🔍 Mencari...</td></tr>';

        fetch('/search-pasien?q=' + encodeURIComponent(q))
            .then(res => res.json())
            .then(data => {
                console.log('Data received:', data); // ✅ DEBUG LOG
                
                tbody.innerHTML = '';
                
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4">❌ Data tidak ditemukan</td></tr>';
                    return;
                }
                
                // ✅ BUILD TABLE HTML
                let html = '';
                data.forEach(item => {
                    if(item.id != currentUserId) {
                        html += `
                            <tr>
                                <td>${item.row_number}</td>
                                <td class="text-bold-500">
                                    ${item.name}
                                    ${item.type == 'bukan warga' ? '<span class="badge bg-secondary ms-1">NW</span>' : ''}
                                </td>
                                <td>${item.email}</td>
                                <td class="text-bold-500">${item.level}</td>
                                <td>
                                    ${item.status ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>'}
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a class="btn btn-sm btn-primary" href="/edit-pasien/${item.id}">
                                            <i data-feather="edit"></i> Edit
                                        </a>
                                        <a class="btn btn-sm btn-danger" href="/delete-pasien/${item.id}" onclick="return confirm('Yakin hapus?')">
                                            <i data-feather="trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    ${item.status ? `
                                        <button class="btn btn-sm ${item.data_lengkap ? 'btn-info' : 'btn-warning'} lengkapi-data-btn" data-id="${item.id}">
                                            <i data-feather="${item.data_lengkap ? 'edit' : 'user-check'}"></i>
                                            ${item.data_lengkap ? 'Edit Data' : 'Lengkapi Data'}
                                        </button>
                                    ` : ''}
                                </td>
                            </tr>
                        `;
                    }
                });
                
                tbody.innerHTML = html;
                
                // ✅ HIDE PAGINATION
                const paginationDiv = document.querySelector('.mt-3');
                if (paginationDiv) {
                    paginationDiv.style.display = 'none';
                }
                
                // ✅ RE-INIT LENGKAPI DATA BUTTONS (INLINE)
                document.querySelectorAll('.lengkapi-data-btn').forEach(btn => {
                    btn.onclick = function() {
                        let userId = this.dataset.id;
                        fetch('/lengkapi-data/' + userId)
                            .then(res => res.text())
                            .then(html => {
                                document.getElementById('modal-lengkapi-data-content').innerHTML = html;
                                new bootstrap.Modal(document.getElementById('lengkapiDataModal')).show();
                            });
                    };
                });
                
                // ✅ RE-INIT FEATHER ICONS
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            })
            .catch(error => {
                console.error('Error:', error); // ✅ DEBUG LOG
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger py-3">❌ Error loading data</td></tr>';
            });
    }, 300);
});
</script>


</script>

    <!-- Modal Lengkapi Data -->
    <div class="modal fade" id="lengkapiDataModal" tabindex="-1" aria-labelledby="lengkapiDataLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="modal-lengkapi-data-content">
                <!-- Form akan dimuat via AJAX -->
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.lengkapi-data-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var userId = this.getAttribute('data-id');
                    fetch('/lengkapi-data/' + userId)
                        .then(response => response.text())
                        .then(html => {
                            document.getElementById('modal-lengkapi-data-content').innerHTML =
                                html;
                            var modal = new bootstrap.Modal(document.getElementById(
                                'lengkapiDataModal'));
                            modal.show();
                        });
                });
            });
        });
    </script>

    <script>
        function hitungUmur(tanggal) {
            if (!tanggal) return '';
            const today = new Date();
            const birthDate = new Date(tanggal);
            let umur = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                umur--;
            }
            if (umur < 0 || isNaN(umur)) return '';
            return umur + ' tahun';
        }

        function updateUmurField() {
            const tgl = document.getElementById('tanggal_lahir');
            const umur = document.getElementById('umur');
            if (tgl && umur) {
                function update() {
                    umur.value = hitungUmur(tgl.value);
                }
                tgl.removeEventListener('change', update); // prevent double
                tgl.addEventListener('change', update);
                update();
            }
        }

        // Lebih efektif: trigger saat modal Bootstrap benar-benar tampil
        document.addEventListener('shown.bs.modal', function(e) {
            if (e.target.id === 'lengkapiDataModal') { // pastikan id modal benar
                updateUmurField();
            }
        });

        // Fallback jika modal bukan Bootstrap atau script termuat langsung
        setTimeout(updateUmurField, 300);
    </script>
    {{-- //modalllll --}}
    <script>
        function hitungUmur(tanggal) {
            if (!tanggal) return '';
            const today = new Date();
            const birthDate = new Date(tanggal);

            // Validasi tanggal lahir tidak boleh di masa depan
            if (birthDate > today) {
                alert('Tanggal lahir tidak boleh lebih dari tanggal hari ini');
                return 'Tanggal tidak valid';
            }

            // Buat tanggal tanpa jam
            const tglToday = new Date(today.getFullYear(), today.getMonth(), today.getDate());
            const tglLahir = new Date(birthDate.getFullYear(), birthDate.getMonth(), birthDate.getDate());

            let tahun = tglToday.getFullYear() - tglLahir.getFullYear();
            let bulan = tglToday.getMonth() - tglLahir.getMonth();
            let hari = tglToday.getDate() - tglLahir.getDate();

            if (hari < 0) {
                bulan--;
                // Ambil jumlah hari di bulan sebelumnya
                let prevMonth = new Date(tglToday.getFullYear(), tglToday.getMonth(), 0);
                hari += prevMonth.getDate();
            }
            if (bulan < 0) {
                tahun--;
                bulan += 12;
            }

            if (tahun >= 1) {
                return tahun + ' tahun';
            } else if (bulan >= 1) {
                return bulan + ' bulan ' + hari + ' hari';
            } else {
                // Jika kurang dari 1 bulan, tampilkan hari
                const diffHari = Math.round((tglToday - tglLahir) / (1000 * 60 * 60 * 24));
    
                if (diffHari === 0) {
                    return 'Baru lahir (0 hari)';
                } else {
                    return diffHari + ' hari';
                }
            }
        }

        function updateUmurField() {
            const tgl = document.getElementById('tanggal_lahir');
            const umur = document.getElementById('umur');
            if (tgl && umur) {
                function update() {
                    umur.value = hitungUmur(tgl.value);
                }
                tgl.removeEventListener('change', update);
                tgl.addEventListener('change', update);
                update();
            }
        }

        document.addEventListener('shown.bs.modal', function(e) {
            if (e.target.id === 'lengkapiDataModal') {
                updateUmurField();
            }
        });
    </script>

{{-- //// notifikasi untuk nik --}}

</body>

</html>
