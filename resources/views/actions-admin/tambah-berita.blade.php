<!DOCTYPE html>
<html lang="en">
<title>Tambah Berita</title>

@include('admin-layouts.header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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
                    <h3>Tambah Berita</h3>
                </div>
            </div>
            {{-- <section class="section">
                <div class="card"> --}}
            <div class="section d-flex justify-content-center align-items-center flex-grow-1">
                <div class="col-md-7">
                    <div class="card mb-4">
                        <section class="section d-flex justify-content-center align-items-center flex-grow-1">
                            <div class="card w-100 w-md-30 w-lg-20">
                                <form action="{{ route('simpan-berita') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <!-- Judul Berita -->
                                        <div class="mb-3">
                                            <label for="judulBerita" class="form-label">Judul Berita</label>
                                            <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judulBerita" name="judul" value="{{ old('judul') }}" required>
                                            @error('judul')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    
                                        <!-- Deskripsi Berita -->
                                        <div class="mb-3">
                                            <label for="deskripsiBerita" class="form-label">Deskripsi Berita</label>
                                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsiBerita" name="deskripsi" rows="3" required>{{ old('deskripsi') }}</textarea>
                                            @error('deskripsi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    
                                        <!-- Tanggal Berita -->
                                        <div class="mb-3">
                                            <label for="tanggalBerita" class="form-label">Tanggal Berita</label>
                                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggalBerita" name="tanggal" value="{{ old('tanggal') }}" required>
                                            @error('tanggal')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    
                                        <!-- Penulis Berita -->
                                        <div class="mb-3">
                                            <label for="penulisBerita" class="form-label">Penulis Berita</label>
                                            <input type="text" class="form-control @error('penulis') is-invalid @enderror" id="penulisBerita" name="penulis" value="admin" required readonly placeholder="admin">
                                            @error('penulis')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    
                                        <!-- Gambar -->
                                        <div class="card-content">
                                            <label for="helpInputTop">Gambar</label>
                                            <div class="mb-3">
                                                <div class="input-group mb-3">
                                                    <label class="input-group-text" for="inputGroupFile01"><i class="bi bi-upload"></i></label>
                                                    <input type="file" class="form-control @error('gambar') is-invalid @enderror" id="inputGroupFile01" name="gambar" accept="image/*" required>
                                                </div>
                                                @error('gambar')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    <div class="d-flex flex-column mt-4">
                                        <button type="submit" class="btn btn-primary w-100 mb-2">Submit</button>
                                        <a href="/admin/berita" class="btn btn-secondary w-100">Kembali</a>
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  flatpickr("#tanggalBerita", {
    dateFormat: "Y-m-d", // Format tanggal
  });
</script>


</body>

</html>
