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

    <section class="popular-course-area section-gap">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="menu-content pb-70 col-lg-8">
                    <div class="title text-center">
                        <h1 class="mb-10">Agenda</h1>
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
