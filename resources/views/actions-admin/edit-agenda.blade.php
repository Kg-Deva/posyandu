<!DOCTYPE html>
<html lang="en">
<title>Edit Agenda</title>

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
                    <h3>Edit Agenda</h3>
                </div>
            </div>
            {{-- <section class="section">
                <div class="card"> --}}
            <div class="section d-flex justify-content-center align-items-center flex-grow-1">
                <div class="col-md-7">
                    <div class="card mb-4">
                        <section class="section d-flex justify-content-center align-items-center flex-grow-1">
                            <div class="card w-100 w-md-30 w-lg-20">
                                <form  action="{{ url('update-agenda', $data->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="judulBerita" class="form-label">Judul Agenda</label>
                                        <input type="text" class="form-control" id="judulAgenda" name="judul" value="{!! $data['judul'] !!}"
                                            required>
                                    </div>
                                    <!-- Deskripsi Agenda -->
                                    <div class="mb-3">
                                        <label for="deskripsiAgenda" class="form-label">Deskripsi Agenda</label>
                                        <textarea class="form-control" id="deskripsiAgenda" name="deskripsi" rows="3" required>{{ old('deskripsi', $data->deskripsi) }}</textarea>
                                    </div>
                                    <!-- Tanggall -->
                                    <div class="mb-3">
                                        <label for="tanggal" class="form-label">Tanggal</label>
                                        <input type="date" class="form-control" id="tanggal" name="tanggal"
                                            required value="{!! $data['tanggal'] !!}">
                                    </div>
                                    <!-- Penulis Agenda -->
                                    <div class="mb-3">
                                        <label for="penulisAgenda" class="form-label">Penulis Agenda</label>
                                        <input type="text" class="form-control" id="penulisAgenda" name="penulis" value="{!! $data['penulis'] !!}"
                                            required readonly>
                                    </div>
                                    <div class="card-content">
                                        <label for="helpInputTop">Gambar</label>
                                        <div class="mb-3">
                                            <div class="input-group mb-3">
                                                <label class="input-group-text" for="inputGroupFile01"><i
                                                        class="bi bi-upload"></i></label>
                                                <input type="file" class="form-control" id="inputGroupFile01" name="gambar" accept="image/*"> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <img src="{{ asset('images/' . $data->gambar) }}" height="10%"
                                            width="50%" alt="" srcset="">

                                    </div>
                                    <div class="d-flex flex-column mt-4">
                                        <button type="submit" class="btn btn-primary w-100 mb-2">Submit</button>
                                        <a href="/admin/agenda" class="btn btn-secondary w-100">Kembali</a>
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
  flatpickr("#tanggal", {
    dateFormat: "Y-m-d", // Format tanggal
  });
</script>


</body>

</html>
