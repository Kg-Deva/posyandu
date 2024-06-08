<!DOCTYPE html>
<html lang="zxx" class="no-js">
{{-- header --}}

@include('layouts.header')
<style>
    .img-container {
        position: relative;
        overflow: hidden;
        width: 100%;
        height: 0;
        padding-top: 54.7%;
        /* Aspek rasio lanskap 4:3 (3/4 * 100%) */
    }

    .img-container img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Gambar akan terpotong atau diperpanjang agar memenuhi ukuran div */
    }
</style>
@include('layouts.css-banner')
{{-- header --}}

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
                        Gallery
                    </h1>
                    <p class="text-white link-nav"><a href="/">Beranda </a> <span
                            class="lnr lnr-arrow-right"></span> <a href="/gallery"> Gallery</a></p>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <!-- Start gallery Area -->
    <section class="gallery-area section-gap">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <a href="{{ asset('landing-page/img/gallery/g1.jpg') }}" class="img-gal">
                        <div class="single-imgs relative">
                            <div class="overlay overlay-bg"></div>
                            <div class="img-container relative">
                                <img class="img-fluid" src="{{ asset('landing-page/img/gallery/g1.jpg') }}"
                                    alt="">
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-6">
                    <a href="{{ asset('landing-page/img/gallery/g2.jpg') }}" class="img-gal">
                        <div class="single-imgs relative">
                            <div class="overlay overlay-bg"></div>
                            <div class="img-container relative">
                                <img class="img-fluid" src="{{ asset('landing-page/img/gallery/g2.jpg') }}"
                                    alt="">
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-6">
                    <a href="{{ asset('landing-page/img/gallery/g2.jpg') }}" class="img-gal">
                        <div class="single-imgs relative">
                            <div class="overlay overlay-bg"></div>
                            <div class="img-container relative">
                                <img class="img-fluid" src="{{ asset('landing-page/img/gallery/g1.jpg') }}"
                                    alt="">
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-6">
                    <a href="{{ asset('landing-page/img/gallery/g2.jpg') }}" class="img-gal">
                        <div class="single-imgs relative">
                            <div class="overlay overlay-bg"></div>
                            <div class="img-container relative">
                                <img class="img-fluid" src="{{ asset('landing-page/img/gallery/g1.jpg') }}"
                                    alt="">
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-6">
                    <a href="{{ asset('landing-page/img/gallery/g2.jpg') }}" class="img-gal">
                        <div class="single-imgs relative">
                            <div class="overlay overlay-bg"></div>
                            <div class="img-container relative">
                                <img class="img-fluid" src="{{ asset('landing-page/img/gallery/g1.jpg') }}"
                                    alt="">
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-6">
                    <a href="{{ asset('landing-page/img/gallery/g2.jpg') }}" class="img-gal">
                        <div class="single-imgs relative">
                            <div class="overlay overlay-bg"></div>
                            <div class="img-container relative">
                                <img class="img-fluid" src="{{ asset('landing-page/img/gallery/g1.jpg') }}"
                                    alt="">
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- End gallery Area -->


    <!-- Start cta-two Area -->
    <section class="cta-two-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 cta-left">
                    <h1>Not Yet Satisfied with our Trend?</h1>
                </div>
                <div class="col-lg-4 cta-right">
                    <a class="primary-btn wh" href="#">view our blog</a>
                </div>
            </div>
        </div>
    </section>
    <!-- End cta-two Area -->

    {{-- footer --}}

    @include('layouts.footer')

    {{-- footer --}}
    <!-- End footer Area -->


    {{-- js --}}

    @include('layouts.js')

    {{-- js --}}
</body>

</html>
