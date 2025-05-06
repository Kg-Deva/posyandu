<!DOCTYPE html>
<html lang="en">
<title>Edit Profil</title>

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
                    <h3>Edit Profil</h3>
                </div>
            </div>
            <div class="section d-flex justify-content-center align-items-center flex-grow-1">
                <div class="col-md-7">
                    <div class="card mb-4">
                        <section class="section d-flex justify-content-center align-items-center flex-grow-1">
                            <div class="card w-100 w-md-30 w-lg-20">
                                <form class="mb-3" action="{{ url('update-profil', $data->id) }}"
                                    method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                <div class="card-body">
                                    <!-- Hidden Textarea -->
                                   
                                    <div class="card-content">
                                        <div class="mb-3">
                                            <label for="visi">Visi</label>
                                            <textarea id="Visi" class="form-control" name="visi" rows="4" placeholder="Masukan Visi">{{ old('visi', $data->visi) }}</textarea>
                                            @error('visi')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <div class="mb-3">
                                            <label for="misi">Misi</label>
                                            <textarea id="Misi" class="form-control" name="misi" rows="4" style="white-space: pre-line;" placeholder="Masukan Misi">{{ old('misi', $data->misi) }}</textarea>

                                            {{-- <textarea id="Misi" class="form-control" name="misi" rows="4" placeholder="Masukan Misi">{{ old('misi', $data->misi) }}</textarea> --}}
                                            @error('misi')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <div class="mb-3">
                                            <label for="tujuan">Tujuan</label>
                                            <textarea id="tujuan" class="form-control" name="tujuan" rows="4" placeholder="Masukan tujuan" >{{ old('tujuan', $data->tujuan) }}</textarea>
                                            @error('tujuan')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
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
                                        <img src="{{ asset('storage/' . $data->gambar) }}" height="10%"
                                            width="50%" alt="" srcset="">
                                    </div>
                                    
                                    <div class="d-flex flex-column mt-4">
                                        <button type="submit" class="btn btn-primary w-100 mb-2">Submit</button>
                                        <a href="/profil" class="btn btn-secondary w-100">Kembali</a>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </form>
            @include('admin-layouts.footer')
        </div>
    </div>
    @include('admin-layouts.js')

</body>

</html>
