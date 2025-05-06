<!DOCTYPE html>
<html lang="en">
<title>Profil</title>

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
                <h3>Profil LPQ Baiturrahmah</h3>
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
                                            {{-- @if (auth()->user()->level == 'adminpengelola')     --}}
                                            <p> <a class="dropdown-item text-success" href="{{ 'tambah-profil' }}"><i
                                                        data-feather="plus"></i>
                                                    Tambah</a></p>
                                                    {{-- @endif --}}
                                            <!-- table hover -->
                                            <div class="table-responsive">
                                                <table class="table table-hover mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Visi</th>
                                                            <th>Misi</th>
                                                            <th>Tujuan</th>
                                                            <th>Gambar</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($data as $key => $item)
                                                            <tr>
                                                                <td class="text-bold-500">
                                                                    <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px;">
                                                                        {{ $item->visi }}
                                                                    </div>
                                                                </td>
                                                                <td class="text-bold-500">
                                                                    <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px;">
                                                                        {{ $item->misi }}
                                                                    </div>
                                                                </td>
                                                                <td class="text-bold-500">
                                                                    <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px;">
                                                                        {{ $item->tujuan }}
                                                                    </div>
                                                                </td>           
                                                                <td class="text-bold-500">
                                                                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="Gambar Profil" class="img-fluid" style="max-width: 150px; max-height: 150px; object-fit: cover; border-radius: 8px;">
                                                                </td>
                                                                
                                                                <td>
                                                                    <div class="d-flex gap-2">
                                                                        <a class="btn btn-sm btn-primary" href="{{ url('edit-profil', $item->id) }}">
                                                                            <i data-feather="edit"></i> Edit
                                                                        </a>
                                                                        <a class="btn btn-sm btn-danger" href="{{ url('delete-profil', $item->id) }}" onclick="confirmation(event)">
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
