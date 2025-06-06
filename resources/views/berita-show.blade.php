<!DOCTYPE html>
<html lang="id">

@include('layouts.header')
<title>Berita</title>
@include('layouts.css-banner')

<body>
    {{-- @include('layouts.navbar') --}}

    <!-- Start Berita Detail Area -->
   
    
    <section class="popular-course-area section-gap">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="menu-content pb-70 col-lg-8">
                    <h1 class="text-uppercase font-weight-bold mb-3 text-center" style="font-size: 2rem; line-height: 1.4; word-wrap: break-word; hyphens: auto;">
                        Berita
                    </h1>
                <!-- Gambar Berita -->
                <div class="mb-4" style="padding-top: 30px;">
                    <img src="{{ asset('images/' . $berita->gambar) }}" alt="{{ $berita->judul }}" class="img-fluid rounded shadow" style="object-fit: cover; max-height: 400px; width: 100%; height: auto;">
                </div>

                <!-- Judul & Informasi Berita -->
                <h1 class="text-uppercase font-weight-bold mb-3" style="font-size: 1.5rem; line-height: 1.4; word-wrap: break-word; hyphens: auto;">
                    {{ $berita->judul }}
                </h1>
                                <p class="text-muted mb-1">Ditulis oleh: <strong>{{ $berita->penulis }}</strong></p>
                <p class="text-muted">Tanggal: {{ \Carbon\Carbon::parse($berita->tanggal)->format('d M Y') }}</p>

                <!-- Deskripsi Berita -->
                <div class="mt-4">
                    <p style="text-align: justify; font-size: 1.1rem; line-height: 1.6;">{!! nl2br(e($berita->deskripsi)) !!}</p>
                </div>

                <!-- Tombol Kembali -->
                {{-- <a href="{{ route('/') }}" class="btn btn-primary mt-3">Kembali ke Daftar Berita</a> --}}
                <a href="javascript:history.back()" class="btn btn-primary mt-3">Kembali</a>

            </div>
        </div>
    </section>
    <!-- End Berita Detail Area -->

    @include('layouts.footer')
    @include('layouts.js')
</body>

</html>
