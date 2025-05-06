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
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4 d-flex justify-content-start">
                                                <div class="stats-icon purple mb-2">
                                                    <i class="iconly-boldPaper"></i> <!-- Icon Berita -->
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Berita</h6>
                                                <h6 class="font-extrabold mb-0">{{ $beritaCount }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4 d-flex justify-content-start">
                                                <div class="stats-icon blue mb-2">
                                                    <i class="iconly-boldCalendar"></i> <!-- Icon Agenda -->
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Agenda</h6>
                                                <h6 class="font-extrabold mb-0">{{ $agendaCount }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4 d-flex justify-content-start">
                                                <div class="stats-icon green mb-2">
                                                    <i class="iconly-boldImage"></i> <!-- Icon Gallery -->
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Gallery</h6>
                                                <h6 class="font-extrabold mb-0">{{ $galleryCount }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4 d-flex justify-content-start">
                                                <div class="stats-icon red mb-2">
                                                     <i class="iconly-boldChat"></i> <!-- Icon Admin -->
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Kritik & Saran</h6>
                                                <h6 class="font-extrabold mb-0">{{ $kritikCount }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    
                        
                    @if (auth()->user()->level == 'adminpengelola')    
                        <!-- Hoverable rows start -->
                        <section class="section">
                            <div class="row" id="table-hover-row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Daftar Admin</h4>
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
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $no = 1; @endphp
                                                        @foreach($data as $item)
                                                            @if($item->id != auth()->user()->id)
                                                            <tr>
                                                                <td>{{ $no++ }}</td>
                                                                <td class="text-bold-500">{{ $item->name }}</td>
                                                                <td>{{ $item->email }}</td>
                                                                <td class="text-bold-500">{{ $item->level }}</td>
                                                                <td>
                                                                    <div class="d-flex gap-2">
                                                                        <a class="btn btn-sm btn-primary" href="{{ url('edit-anggota', $item->id) }}">
                                                                            <i data-feather="edit"></i> Edit
                                                                        </a>
                                                                        <a class="btn btn-sm btn-danger" href="{{ url('delete-anggota', $item->id) }}" onclick="confirmation(event)">
                                                                            <i data-feather="trash"></i> Delete
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- Hoverable rows end -->
                        @endif  

                    </div>

                </section>
            </div>

            @include('admin-layouts.footer')
        </div>
    </div>
    @include('admin-layouts.js')

    
    
    

</body>

</html>
