<!DOCTYPE html>
<html lang="zxx" class="no-js">

{{-- header --}}

@include('layouts.header')
<title>Berita</title>
{{-- header --}}
@include('layouts.css-banner')
<style>
    /* CSS */
    .thumb {
        position: relative;
        width: 100%;
        height: 0;
        padding-top: 75%;
        /* Aspect ratio 4:3 (width:height) */
    }

    .thumb img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Mengatur responsivitas untuk mengubah jumlah kolom */
    @media (max-width: 992px) {
        .col-lg-3 {
            flex: 0 0 50%;
            /* Mengubah lebar kolom menjadi setengah saat ukuran layar kurang dari 992px */
        }
    }

    @media (max-width: 768px) {
        .col-md-6 {
            flex: 0 0 100%;
            /* Mengubah lebar kolom menjadi penuh saat ukuran layar kurang dari 768px */
        }
    }
</style>

<body>
    {{-- navbar --}}

    @include('layouts.navbar')

    {{-- navbar --}}


    <!-- start banner Area -->
    <section class="custom-banner-area relative custom-about-banner" id="custom-home">
        <div class="custom-overlay custom-overlay-bg"></div>
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="about-content col-lg-12">
                    <h1 class="text-white">
                        Berita
                    </h1>
                    <p class="text-white link-nav"><a href="/">Beranda </a> <span
                            class="lnr lnr-arrow-right"></span> <a href="/berita"> Berita</a></p>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <!-- Start feature Area -->

    <!-- End feature Area -->

    {{-- start feature --}}

    

    <section class="blog-area section-gap" id="blog">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="menu-content pb-70 col-lg-8">
                    <div class="title text-center">
                        <h1 class="mb-10">Berita</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($beritas as $berita)
                    <div class="col-lg-3 col-md-6 single-blog">
                        <div class="thumb">
                            <img class="img-fluid" src="{{ asset('images/' . $berita->gambar) }}" alt="{{ $berita->judul }}">
                        </div>
                        <p class="meta">{{ \Carbon\Carbon::parse($berita->tanggal)->format('d F, Y') }} | By <a>{{ $berita->penulis }}</a></p>
                        <a href="{{ route('beritashow', $berita->id) }}">
                            <h5>{{ \Illuminate\Support\Str::limit($berita->judul, 10, '...') }}</h5>
                        </a>
                        <p>
                            {{ Str::limit($berita->deskripsi, 20, '...') }}
                        </p>
                        <a href="{{ route('beritashow', $berita->id) }}" class="details-btn d-flex justify-content-center align-items-center">
                            <span class="details">Details</span><span class="lnr lnr-arrow-right"></span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

         <!-- Pagination -->
         <div class="row justify-content-center mt-4">
            {{ $beritas->links('pagination::bootstrap-4') }}
        </div>
    </div>
    </section>

    {{-- end feature --}}
    <!-- Start popular-course Area -->

    <!-- End popular-course Area -->









    <!-- End cta-one Area -->

    <!-- Start blog Area -->

    <!-- End blog Area -->
    {{-- mapss --}}




    <!-- start footer Area -->
    {{-- footer --}}

    @include('layouts.footer')

    {{-- footer --}}
    <!-- End footer Area -->


    {{-- js --}}

    @include('layouts.js')

    {{-- js --}}
</body>

</html>
