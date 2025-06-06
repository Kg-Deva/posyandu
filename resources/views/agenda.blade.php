<!DOCTYPE html>
<html lang="zxx" class="no-js">

{{-- header --}}

@include('layouts.header')
<title>Agenda</title>
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
                        Agenda
                    </h1>
                    <p class="text-white link-nav"><a href="/">Beranda </a> <span
                            class="lnr lnr-arrow-right"></span> <a href="/agenda"> Agenda</a></p>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <!-- Start feature Area -->

    <!-- End feature Area -->

    {{-- start feature --}}

    {{-- <section class="popular-course-area section-gap">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="menu-content pb-70 col-lg-8">
                    <div class="title text-center">
                        <h1 class="mb-10">Agenda</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($agendas as $agenda)
                <div class="active-popular-carusel">
                    <div class="single-popular-carusel">

                        <div class="thumb-wrap relative">
                            <div class="thumb relative">
                                <div class="overlay overlay-bg"></div>
                                <img class="img-fluid" src="{{ asset('images/' . $agenda->gambar) }}" alt="{{ $agenda->judul }}"
                                    alt="">
                            </div>
                        </div>

                        <div class="details">
                            <p class="text-muted pt-2 mb-1" style="font-size: 14px;">{{ \Carbon\Carbon::parse($agenda->tanggal)->format('d F, Y') }} | By <a>{{ $agenda->penulis }}</p>
                                <a href="{{ route('agendashow', $agenda->id) }}">
                                <h4>
                                    {{ \Illuminate\Support\Str::limit($agenda->judul, 10, '...') }}
                                </h4>
                            </a>
                            <p>
                                {{ Str::limit($agenda->deskripsi, 20, '...') }}
                            </p>
                        </div>
                    </div>

                    @endforeach
                </div>
            </div>
        </div>
    </section> --}}

    <section class="popular-course-area section-gap">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="menu-content pb-70 col-lg-8">
                    <div class="title text-center">
                        <h1 class="mb-10">Agenda</h1>
                    </div>
                </div>
            </div>
    
            <!-- Slick Carousel -->
            <div class="active-popular-carusel">
                @foreach($agendas as $agenda)
                    <div class="single-popular-carusel">
                        <div class="thumb-wrap relative">
                            <div class="thumb relative">
                                <div class="overlay overlay-bg"></div>
                                <img class="img-fluid" src="{{ asset('images/' . $agenda->gambar) }}" alt="{{ $agenda->judul }}">
                            </div>
                        </div>
    
                        <div class="details">
                            <p class="text-muted pt-2 mb-1" style="font-size: 14px;">
                                {{ \Carbon\Carbon::parse($agenda->tanggal)->format('d F, Y') }} | By <a>{{ $agenda->penulis }}</a>
                            </p>
                            <a href="{{ route('agendashow', $agenda->id) }}">
                                <h4>{{ \Illuminate\Support\Str::limit($agenda->judul, 10, '...') }}</h4>
                            </a>
                            <p>{{ Str::limit($agenda->deskripsi, 20, '...') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    
    <!-- Tambahkan Slick.js untuk Auto Slider -->
    @section('scripts')
    <script>
        $(document).ready(function(){
            $('.active-popular-carusel').slick({
                slidesToShow: 3,  // Jumlah item per slide
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 3000, // 3 detik per geser
                arrows: false, // Hilangkan tombol navigasi
                dots: false, // Hilangkan indikator titik
                responsive: [
                    { breakpoint: 992, settings: { slidesToShow: 2 } }, // Tablet
                    { breakpoint: 768, settings: { slidesToShow: 1 } }  // Mobile
                ]
            });
        });
    </script>
    @endsection
    
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
