<!DOCTYPE html>
<html lang="zxx" class="no-js">

{{-- header --}}
<title>LPQ Baiturrahmah</title>
@include('layouts.header')

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

{{-- maps style --}}
<style>
    body {
        margin: 0;
        padding: 0;
    }

    .popular-course-area {
        display: flex;
        justify-content: center;
        /* Center horizontally */
        align-items: center;
        /* Center vertically */
        min-height: 100vh;
        /* Ensure it takes up at least the viewport height */
        padding: 20px;
        /* Add padding around the section */
    }

    .map-container {
        position: relative;
        width: 80%;
        /* Adjust the width percentage as needed */
        max-width: 900px;
        /* Set a maximum width for larger screens */
        aspect-ratio: 16 / 9;
        /* Maintain a 16:9 aspect ratio */
        background: #f3f3f3;
        /* Optional, for background color */
        border-radius: 8px;
        /* Optional, for rounded corners */
        overflow: hidden;
        /* Ensure content doesn’t overflow */
    }

    .map-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
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
            <div class="row fullscreen d-flex align-items-center justify-content-between">
                <div class="banner-content col-lg-9 col-md-12">
                    <h1 class="text-uppercase">
                   
                        {{-- {{ $berandas->tagline}} --}}
                        {{ $berandas?->tagline ?? 'Tagline belum tersedia' }}
                    </h1>
                    <p class="pt-10 pb-10">
                        {{-- {{ $berandas->deskripsi }} --}}
                        {{ $berandas?->deskripsi ?? 'Deskripsi belum tersedia' }}
                    </p>
                    {{-- <a href="#" class="primary-btn text-uppercase">Get Started</a> --}}
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <!-- Start feature Area -->
    {{-- <section class="feature-area">
        <div class="container">
            <div class="row">

                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 mx-auto">
                            <div class="single-feature">
                                <div class="title">
                                    <h4>LPQ Baiturrahmah</h4>

                                </div>
                                <div class="desc-wrap">
                                    <p>
                                        "Daftar sekarang untuk bimbingan dari pengajar ahli
                                        dan mulai memahami Quran dengan lebih mendalam!"
                                    </p>
                                    <a href="#">Daftar Sekarang</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>




            </div>
        </div>
    </section> --}}
    <!-- End feature Area -->

    {{-- start feature --}}

    

    <section class="blog-area section-gap" id="blog">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="menu-content pb-70 col-lg-8">
                    <div class="title text-center">
                        <h1 class="mb-10">Berita Terbaru</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($beritas as $berita)
                    <div class="col-lg-3 col-md-6 single-blog">
                        <div class="thumb">
                            <img class="img-fluid" src="{{ asset('images/' . $berita->gambar) }}" alt="{{ $berita->judul }}">
                        </div>
                        <p class="meta">{{ \Carbon\Carbon::parse($berita->tanggal)->format('d F, Y') }} | By <a href="#">{{ $berita->penulis }}</a></p>
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
    </section>
    
    {{-- end feature --}}
    <!-- Start popular-course Area -->
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
                <div class="active-popular-carusel">
                    <div class="single-popular-carusel">
                        <div class="thumb-wrap relative">
                            <div class="thumb relative">
                                <div class="overlay overlay-bg"></div>
                                <img class="img-fluid" src="{{ asset('landing-page/img/gallery/g1.jpg') }}"
                                    alt="">
                            </div>
                            <div class="meta d-flex justify-content-between">
                                <p><span class="lnr lnr-users"></span> 355 <span class="lnr lnr-bubble"></span>35</p>
                                <h4>$150</h4>
                            </div>
                        </div>

                        <div class="details">
                            <a href="#">
                                <h4>
                                    Learn Designing
                                </h4>
                            </a>
                            <p>
                                When television was young, there was a hugely popular show based on the still popular
                                fictional characte
                            </p>
                        </div>
                    </div>
                    <div class="single-popular-carusel">
                        <div class="thumb-wrap relative">
                            <div class="thumb relative">
                                <div class="overlay overlay-bg"></div>
                                <img class="img-fluid" src="{{ asset('landing-page/img/p2.jpg') }}" alt="">
                            </div>
                            <div class="meta d-flex justify-content-between">
                                <p><span class="lnr lnr-users"></span> 355 <span class="lnr lnr-bubble"></span>35</p>
                                <h4>$150</h4>
                            </div>
                        </div>
                        <div class="details">
                            <a href="#">
                                <h4>
                                    Learn React js beginners
                                </h4>
                            </a>
                            <p>
                                When television was young, there was a hugely popular show based on the still popular
                                fictional characte
                            </p>
                        </div>
                    </div>
                    <div class="single-popular-carusel">
                        <div class="thumb-wrap relative">
                            <div class="thumb relative">
                                <div class="overlay overlay-bg"></div>
                                <img class="img-fluid" src="{{ asset('landing-page/img/p3.jpg') }}" alt="">
                            </div>
                            <div class="meta d-flex justify-content-between">
                                <p><span class="lnr lnr-users"></span> 355 <span class="lnr lnr-bubble"></span>35</p>
                                <h4>$150</h4>
                            </div>
                        </div>
                        <div class="details">
                            <a href="#">
                                <h4>
                                    Learn Photography
                                </h4>
                            </a>
                            <p>
                                When television was young, there was a hugely popular show based on the still popular
                                fictional characte
                            </p>
                        </div>
                    </div>
                    <div class="single-popular-carusel">
                        <div class="thumb-wrap relative">
                            <div class="thumb relative">
                                <div class="overlay overlay-bg"></div>
                                <img class="img-fluid" src="{{ asset('landing-page/img/p4.jpg') }}" alt="">
                            </div>
                            <div class="meta d-flex justify-content-between">
                                <p><span class="lnr lnr-users"></span> 355 <span class="lnr lnr-bubble"></span>35</p>
                                <h4>$150</h4>
                            </div>
                        </div>
                        <div class="details">
                            <a href="#">
                                <h4>
                                    Learn Surveying
                                </h4>
                            </a>
                            <p>
                                When television was young, there was a hugely popular show based on the still popular
                                fictional characte
                            </p>
                        </div>
                    </div>
                    <div class="single-popular-carusel">
                        <div class="thumb-wrap relative">
                            <div class="thumb relative">
                                <div class="overlay overlay-bg"></div>
                                <img class="img-fluid" src="{{ asset('landing-page/img/p1.jpg') }}" alt="">
                            </div>
                            <div class="meta d-flex justify-content-between">
                                <p><span class="lnr lnr-users"></span> 355 <span class="lnr lnr-bubble"></span>35</p>
                                <h4>$150</h4>
                            </div>
                        </div>
                        <div class="details">
                            <a href="#">
                                <h4>
                                    Learn Designing
                                </h4>
                            </a>
                            <p>
                                When television was young, there was a hugely popular show based on the still popular
                                fictional characte
                            </p>
                        </div>
                    </div>
                    <div class="single-popular-carusel">
                        <div class="thumb-wrap relative">
                            <div class="thumb relative">
                                <div class="overlay overlay-bg"></div>
                                <img class="img-fluid" src="{{ asset('landing-page/img/p2.jpg') }}" alt="">
                            </div>
                            <div class="meta d-flex justify-content-between">
                                <p><span class="lnr lnr-users"></span> 355 <span class="lnr lnr-bubble"></span>35</p>
                                <h4>$150</h4>
                            </div>
                        </div>
                        <div class="details">
                            <a href="#">
                                <h4>
                                    Learn React js beginners
                                </h4>
                            </a>
                            <p>
                                When television was young, there was a hugely popular show based on the still popular
                                fictional characte
                            </p>
                        </div>
                    </div>
                    <div class="single-popular-carusel">
                        <div class="thumb-wrap relative">
                            <div class="thumb relative">
                                <div class="overlay overlay-bg"></div>
                                <img class="img-fluid" src="{{ asset('landing-page/img/p3.jpg') }}" alt="">
                            </div>
                            <div class="meta d-flex justify-content-between">
                                <p><span class="lnr lnr-users"></span> 355 <span class="lnr lnr-bubble"></span>35</p>
                                <h4>$150</h4>
                            </div>
                        </div>
                        <div class="details">
                            <a href="#">
                                <h4>
                                    Learn Photography
                                </h4>
                            </a>
                            <p>
                                When television was young, there was a hugely popular show based on the still popular
                                fictional characte
                            </p>
                        </div>
                    </div>
                    <div class="single-popular-carusel">
                        <div class="thumb-wrap relative">
                            <div class="thumb relative">
                                <div class="overlay overlay-bg"></div>
                                <img class="img-fluid" src="{{ asset('landing-page/img/p4.jpg') }}" alt="">
                            </div>
                            <div class="meta d-flex justify-content-between">
                                <p><span class="lnr lnr-users"></span> 355 <span class="lnr lnr-bubble"></span>35</p>
                                <h4>$150</h4>
                            </div>
                        </div>
                        <div class="details">
                            <a href="#">
                                <h4>
                                    Learn Surveying
                                </h4>
                            </a>
                            <p>
                                When television was young, there was a hugely popular show based on the still popular
                                fictional characte
                            </p>
                        </div>
                    </div>
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


    <section class="popular-course-area section-gap">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="menu-content pb-70 col-lg-8">
                    <div class="title text-center">
                        <h1 class="mb-10">Lokasi</h1>
                    </div>
                </div>
            </div>

            <!-- Kontainer responsif untuk peta -->
            {{-- <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!4v1725270898001!6m8!1m7!1szE6IxDY19UXGT2CcoeoncA!2m2!1d-6.986612916975554!2d110.379446798736!3f14.609894560675123!4f8.547371538380375!5f0.7820865974627469"
                            width="600" height="450" style="border:0;" allowfullscreen=""
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div> --}}

            {{-- <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe 
                            src="{{ $berandas->maps }}" 
                            width="100%" 
                            height="450" 
                            style="border:0; border-radius: 10px;" 
                            allowfullscreen 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div> --}}
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="embed-responsive embed-responsive-16by9" style="position: relative;">
                        @if($berandas && $berandas->maps)
                            <iframe 
                                src="{{ $berandas->maps }}" 
                                width="100%" 
                                height="450" 
                                style="border:0; border-radius: 10px;" 
                                allowfullscreen 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        @else
                            <div style="width: 100%; height: 450px; display: flex; align-items: center; justify-content: center; border: 2px dashed #ccc; border-radius: 10px; background-color: #f9f9f9;">
                                <p class="text-muted m-0" style="font-size: 1.2rem;">📍 Peta belum tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            
            
        </div>
    </section>


    <!-- start footer Area -->
    {{-- footer --}}

    @include('layouts.footer')

    {{-- footer --}}
    <!-- End footer Area -->


    {{-- js --}}

    @include('layouts.js')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAARdULA3CekBDk4kEEwQbWH_FhkXNlB4U&callback=initMap" async
        defer></script>

    <script>
        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: -6.9866055,
                    lng: 110.3796423
                }, // Koordinat Masjid Jami' Baiturrahmah
                zoom: 15 // Tingkat zoom
            });

            // Tambahkan marker untuk menunjukkan lokasi Masjid Jami' Baiturrahmah
            var marker = new google.maps.Marker({
                position: {
                    lat: -6.9866055,
                    lng: 110.3796423
                },
                map: map,
                title: 'Masjid Jami\' Baiturrahmah'
            });
        }
    </script>

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
    {{-- js --}}
</body>

</html>
