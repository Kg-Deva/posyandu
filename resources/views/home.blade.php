<!DOCTYPE html>
<html lang="zxx" class="no-js">

{{-- header --}}

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
                        We Ensure better education
                        for a better world
                    </h1>
                    <p class="pt-10 pb-10">
                        In the history of modern astronomy, there is probably no one greater leap forward than the
                        building and launch of the space telescope known as the Hubble.
                    </p>
                    <a href="#" class="primary-btn text-uppercase">Get Started</a>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <!-- Start feature Area -->
    <section class="feature-area">
        <div class="container">
            <div class="row">

                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 mx-auto">
                            <div class="single-feature">
                                <div class="title">
                                    {{-- <h4>No.1 of universities</h4> --}}
                                    <h4>TPQ Baiturrahmah</h4>

                                </div>
                                <div class="desc-wrap">
                                    <p>
                                        For many of us, our very first experience of learning about the celestial bodies
                                        begins
                                        when we saw our first.
                                    </p>
                                    <a href="#">Join Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>




            </div>
        </div>
    </section>
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
                <div class="col-lg-3 col-md-6 single-blog">
                    <div class="thumb">
                        <img class="img-fluid" src="{{ asset('landing-page/img/b1.jpg') }}" alt="">
                    </div>
                    <p class="meta">25 April, 2018 | By <a href="#">Mark Wiens</a></p>
                    <a href="blog-single.html">
                        <h5>Addiction When Gambling Becomes A Problem</h5>
                    </a>
                    <p>
                        Computers have become ubiquitous in almost every facet of our lives. At work, desk jockeys spend
                        hours in front of their.
                    </p>
                    <a href="#" class="details-btn d-flex justify-content-center align-items-center"><span
                            class="details">Details</span><span class="lnr lnr-arrow-right"></span></a>
                </div>

                <div class="col-lg-3 col-md-6 single-blog">
                    <div class="thumb">
                        <img class="img-fluid" src="{{ asset('landing-page/img/b2.jpg') }}" alt="">
                    </div>
                    <p class="meta">25 April, 2018 | By <a href="#">Mark Wiens</a></p>
                    <a href="blog-single.html">
                        <h5>Computer Hardware Desktops And Notebooks</h5>
                    </a>
                    <p>
                        Ah, the technical interview. Nothing like it. Not only does it cause anxiety, but it causes
                        anxiety for several different reasons.
                    </p>
                    <a href="#" class="details-btn d-flex justify-content-center align-items-center"><span
                            class="details">Details</span><span class="lnr lnr-arrow-right"></span></a>
                </div>
                <div class="col-lg-3 col-md-6 single-blog">
                    <div class="thumb">
                        <img class="img-fluid" src="{{ asset('landing-page/img/b3.jpg') }}" alt="">
                    </div>
                    <p class="meta">25 April, 2018 | By <a href="#">Mark Wiens</a></p>
                    <a href="blog-single.html">
                        <h5>Make Myspace Your Best Designed Space</h5>
                    </a>
                    <p>
                        Plantronics with its GN Netcom wireless headset creates the next generation of wireless headset
                        and other products such as wireless.
                    </p>
                    <a href="#" class="details-btn d-flex justify-content-center align-items-center"><span
                            class="details">Details</span><span class="lnr lnr-arrow-right"></span></a>
                </div>
                <div class="col-lg-3 col-md-6 single-blog">
                    <div class="thumb">
                        <img class="img-fluid" src="{{ asset('landing-page/img/b4.jpg') }}" alt="">
                    </div>
                    <p class="meta">25 April, 2018 | By <a href="#">Mark Wiens</a></p>
                    <a href="blog-single.html">
                        <h5>Video Games Playing With Imagination</h5>
                    </a>
                    <p>
                        About 64% of all on-line teens say that do things online that they wouldn’t want their parents
                        to know about. 11% of all adult internet
                    </p>
                    <a href="#" class="details-btn d-flex justify-content-center align-items-center"><span
                            class="details">Details</span><span class="lnr lnr-arrow-right"></span></a>
                </div>
            </div>
        </div>
    </section>
    {{-- end feature --}}
    <!-- Start popular-course Area -->
    <section class="popular-course-area section-gap">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="menu-content pb-70 col-lg-8">
                    <div class="title text-center">
                        <h1 class="mb-10">Events</h1>
                        {{-- <p>There is a moment in the life of any aspiring.</p> --}}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="active-popular-carusel">
                    <div class="single-popular-carusel">
                        {{-- <div class="thumb-wrap relative">
                            <div class="thumb relative">
                                <div class="overlay overlay-bg"></div>
                                <img class="img-fluid" src="{{ asset('landing-page/img/p1.jpg') }}" alt="">
                            </div>
                            <div class="meta d-flex justify-content-between">
                                <p><span class="lnr lnr-users"></span> 355 <span class="lnr lnr-bubble"></span>35</p>
                                <h4>$150</h4>
                            </div>
                        </div> --}}

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
    </section>
    <!-- End popular-course Area -->








    <!-- End review Area -->

    <!-- Start cta-one Area -->
    {{-- <section class="cta-one-area relative section-gap">
        <div class="container">
            <div class="overlay overlay-bg"></div>
            <div class="row justify-content-center">
                <div class="wrap">
                    <h1 class="text-white">Become an instructor</h1>
                    <p>
                        There is a moment in the life of any aspiring astronomer that it is time to buy that first
                        telescope. It’s exciting to think about setting up your own viewing station whether that is on
                        the deck.
                    </p>
                    <a class="primary-btn wh" href="#">Apply for the post</a>
                </div>
            </div>
        </div>
    </section> --}}
    <!-- End cta-one Area -->

    <!-- Start blog Area -->

    <!-- End blog Area -->
    {{-- mapss --}}
    <section class="popular-course-area section-gap">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="menu-content pb-70 col-lg-8">
                    <div class="title text-center">
                        <h1 class="mb-10">Lokasi</h1>
                    </div>
                </div>
                <div id="map" style="width: 100%; height: 400px;">
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
    {{-- js --}}
</body>

</html>
