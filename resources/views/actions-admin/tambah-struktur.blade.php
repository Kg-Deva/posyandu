<!DOCTYPE html>
<html lang="en">
<title>Tambah Struktur</title>

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
            <div class="section d-flex justify-content-center align-items-center flex-grow-1">

                <div class="page-heading">
                    <h3>Tambah Struktur</h3>
                </div>
            </div>
            {{-- <section class="section">
                <div class="card"> --}}
            <div class="section d-flex justify-content-center align-items-center flex-grow-1">
                <div class="col-md-7">
                    <div class="card mb-4">
                        <section class="section d-flex justify-content-center align-items-center flex-grow-1">
                            <div class="card w-100 w-md-30 w-lg-20">
                                <form action="{{ route('simpan-struktur') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                <div class="card-body">

                                    <div class="card-content">
                                        <label for="helpInputTop">Struktur Organisasi</label>
                                        <div class="mb-3">
                                            <div class="input-group mb-3">
                                                <label class="input-group-text" for="inputGroupFile01"><i
                                                        class="bi bi-upload"></i></label>
                                                <input type="file" class="form-control" id="inputGroupFile01" accept=".pdf" name="file_peraturan" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column mt-4">
                                        <button type="submit" class="btn btn-primary w-100 mb-2">Submit</button>
                                        <a href="/admin/struktur-organisasi" class="btn btn-secondary w-100">Kembali</a>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </section>
                    </div>
                </div>
            </div>

            @include('admin-layouts.footer')
        </div>
    </div>

    @include('admin-layouts.js')

</body>

</html>
