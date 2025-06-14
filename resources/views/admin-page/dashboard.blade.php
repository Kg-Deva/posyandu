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
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i
                                    class="bi bi-x bi-middle"></i></a>
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
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
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
                                            <input type="text" id="search-anggota" class="form-control mt-2" placeholder="Cari nama/email akun...">
                                        </div>
                                        <div class="card-body">
                                            <p>
                                                <a class="dropdown-item text-success" href="{{ url('tambah-anggota') }}">
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
                                                      @php
                                                        $no = ($data->currentPage() - 1) * $data->perPage() + 1;
                                                    @endphp
                                                    @foreach($data as $item)
                                                        @if($item->id != auth()->user()->id)
                                                            <tr>
                                                                <td>{{ $no++ }}</td>
                                                                {{-- <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td> --}}
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
                                                                    @if($item->status)
                                                                        <span class="badge bg-success">Aktif</span>
                                                                    @else
                                                                        <span class="badge bg-danger">Tidak Aktif</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($item->level != 'admin')
                                                                    <div class="d-flex gap-2">
                                                                        <a class="btn btn-sm btn-primary" href="{{ url('edit-anggota', $item->id) }}">
                                                                            <i data-feather="edit"></i> Edit
                                                                        </a>
                                                                        <a class="btn btn-sm btn-danger" href="{{ url('delete-anggota', $item->id) }}" onclick="confirmation(event)">
                                                                            <i data-feather="trash"></i> Delete
                                                                        </a>
                                                                    </div>
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

    <script>
    // Simpan user id ke JS variable
    const currentUserId = {{ auth()->user()->id }};
</script>
@verbatim
<script>
document.getElementById('search-anggota').addEventListener('keyup', function() {
    let q = this.value;
    fetch('/search-anggota?q=' + encodeURIComponent(q))
        .then(res => res.json())
        .then(data => {
            let tbody = document.getElementById('anggota-table-body');
            tbody.innerHTML = '';
            let no = 1;
            data.forEach(item => {
                if(item.id != currentUserId) {
                    tbody.innerHTML += `
                        <tr>
                            <td>${no++}</td>
                            <td class="text-bold-500">${item.name}</td>
                            <td>${item.email}</td>
                            <td class="text-bold-500">${item.level}</td>
                            <td>
                                ${item.status ? 
                                    '<span class="badge bg-success">Aktif</span>' : 
                                    '<span class="badge bg-danger">Tidak Aktif</span>'}
                            </td>
                            <td>
                                ${item.level !== 'admin' ? `
                                <div class="d-flex gap-2">
                                    <a class="btn btn-sm btn-primary" href="/edit-anggota/${item.id}">
                                        <i data-feather="edit"></i> Edit
                                    </a>
                                    <a class="btn btn-sm btn-danger" href="/delete-anggota/${item.id}" onclick="confirmation(event)">
                                        <i data-feather="trash"></i> Delete
                                    </a>
                                </div>
                                ` : ''}
                            </td>
                        </tr>
                    `;
                }
            });
        });
});
</script>
@endverbatim
</body>

</html>
