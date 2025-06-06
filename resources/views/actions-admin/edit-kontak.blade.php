<!DOCTYPE html>
<html lang="en">
<title>Edit Kontak</title>

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
                    <h3>Edit Kontak</h3>
                </div>
            </div>
            {{-- <section class="section">
                <div class="card"> --}}
            <div class="section d-flex justify-content-center align-items-center flex-grow-1">
                <div class="col-md-7">
                    <div class="card mb-4">
                        <section class="section d-flex justify-content-center align-items-center flex-grow-1">
                            <div class="card w-100 w-md-30 w-lg-20">
                                <form  action="{{ url('update-kontak', $data->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                  
                                     <!-- Whatsapp -->
                                     <div class="mb-3">
                                        <label for="Whatsapp" class="form-label">Whatsapp</label>
                                        <input type="text" class="form-control @error('whatsapp') is-invalid @enderror" id="Whatsapp" name="whatsapp" value="{!! $data['whatsapp'] !!}" required onkeypress="return hanyaAngka(event)">
                                        @error('whatsapp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                
                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label for="Email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{!! $data['email'] !!}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                
                                     <!-- No_Telp -->
                                    <div class="mb-3">
                                        <label for="Notelp" class="form-label">Nomor Telepon</label>
                                        <input type="text" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp" name="no_telp" value="{!! $data['no_telp'] !!}" required onkeypress="return hanyaAngka(event)">
                                        @error('no_telp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!-- Alam
                                        at -->
                                    <div class="mb-3">
                                        <label for="Alamat" class="form-label">Alamat</label>
                                        <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="Alamat" name="alamat" value="{!! $data['alamat'] !!}" required>
                                        @error('alamat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                

                                
                                    <div class="d-flex flex-column mt-4">
                                        <button type="submit" class="btn btn-primary w-100 mb-2">Submit</button>
                                        <a href="/kontak" class="btn btn-secondary w-100">Kembali</a>
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
    <script>
        function hanyaAngka(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
        </script>


</body>

</html>
