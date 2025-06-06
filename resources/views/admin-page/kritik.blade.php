<!DOCTYPE html>
<html lang="en">
<title>Kritik & Saran</title>

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
                <h3>Kritik & Saran</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    {{-- <div class="col-12 col-lg-9"> --}}
                    <div class="col-lg-16">

                        <!-- Hoverable rows start -->
                        <section class="section">
                            <div class="row" id="table-hover-row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            {{-- <p> <a class="dropdown-item text-success" href="/tambah-program"><i
                                                        data-feather="plus"></i>
                                                    Tambah</a></p> --}}
                                            <!-- table hover -->
                                            <div class="table-responsive">
                                                <table class="table table-hover mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Nama</th>
                                                            <th>Email</th>
                                                            <th>Subjek</th>
                                                            <th>Pesan</th>
                                                            <th>Dikirim Pada</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($kritik as $index => $item)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $item->name }}</td>
                                                            <td>{{ $item->email }}</td>
                                                            <td>{{ $item->subjek }}</td>
                                                            <td>
                                                                <button class="btn btn-sm border-0 text-body" data-bs-toggle="modal" data-bs-target="#pesanModal{{ $item->id }}">
                                                                    {{ Str::limit($item->pesan, 50, '...') }}
                                                                </button>
                                                            
                                                                <!-- Modal untuk menampilkan pesan lengkap -->
                                                                <div class="modal fade" id="pesanModal{{ $item->id }}" tabindex="-1" aria-labelledby="pesanModalLabel{{ $item->id }}" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="pesanModalLabel{{ $item->id }}">Detail Pesan</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                {{ $item->pesan }}
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            {{-- <td>{{ $item->created_at->format('d M Y H:i') }}</td> <!-- Format waktu --> --}}
                                                            <td>{{ $item->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB</td>

                                                            <td>
                                                                <div class="d-flex gap-2">
                                                                    <a class="btn btn-sm btn-primary" 
                                                                        href="mailto:{{ $item->email }}?subject=Tanggapan untuk: {{ $item->pesan }}&body=Halo {{ $item->name }},%0D%0A%0D%0ATerima kasih atas kritik dan sarannya! Berikut tanggapan kami:">
                                                                        <i data-feather="mail"></i> Tanggapi
                                                                    </a>
                                                                    <a class="btn btn-sm btn-danger" href="{{ url('delete-kritik', $item->id) }}" onclick="confirmation(event)">
                                                                        <i data-feather="trash"></i> Delete
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
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

                    </div>

                </section>
            </div>

            @include('admin-layouts.footer')
        </div>
    </div>
    @include('admin-layouts.js')

</body>

</html>
