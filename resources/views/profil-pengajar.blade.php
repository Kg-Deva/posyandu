<!DOCTYPE html>
<html lang="zxx" class="no-js">

{{-- header --}}
<title>Profil Pengajar</title>
@include('layouts.header')

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
                        Profil Pengajar
                    </h1>
                    <p class="text-white link-nav"><a href="/">Beranda </a> <span
                            class="lnr lnr-arrow-right"></span> <a href="/profil-pengajar"> Profil Pengajar</a></p>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <!-- Start events-list Area -->
    {{-- <section class="events-list-area section-gap event-page-lists">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 pb-30">
                    <div class="single-carusel row align-items-center">
                        <div class="col-12 col-md-6 thumb">
                            <img class="img-fluid" src="{{ asset('landing-page/img/e1.jpg') }}" alt="">
                        </div>
                        <div class="detials col-12 col-md-6">

                            <h4>Danieal Spd</h4>
                            <p>
                                Guru Bhasa Jawa
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 pb-30">
                    <div class="single-carusel row align-items-center">
                        <div class="col-12 col-md-6 thumb">
                            <img class="img-fluid" src="{{ asset('landing-page/img/e1.jpg') }}" alt="">
                        </div>
                        <div class="detials col-12 col-md-6">
                            <p>25th February, 2018</p>
                            <a href="event-details.html">
                                <h4>The Universe Through
                                    A Child S Eyes</h4>
                            </a>
                            <p>
                                For most of us, the idea of astronomy is something we directly connect to “stargazing”,
                                telescopes and seeing magnificent displays in the heavens.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 pb-30">
                    <div class="single-carusel row align-items-center">
                        <div class="col-12 col-md-6 thumb">
                            <img class="img-fluid" src="{{ asset('landing-page/img/e1.jpg') }}" alt="">
                        </div>
                        <div class="detials col-12 col-md-6">
                            <p>25th February, 2018</p>
                            <a href="event-details.html">
                                <h4>The Universe Through
                                    A Child S Eyes</h4>
                            </a>
                            <p>
                                For most of us, the idea of astronomy is something we directly connect to “stargazing”,
                                telescopes and seeing magnificent displays in the heavens.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 pb-30">
                    <div class="single-carusel row align-items-center">
                        <div class="col-12 col-md-6 thumb">
                            <img class="img-fluid" src="{{ asset('landing-page/img/e1.jpg') }}" alt="">
                        </div>
                        <div class="detials col-12 col-md-6">
                            <p>25th February, 2018</p>
                            <a href="event-details.html">
                                <h4>The Universe Through
                                    A Child S Eyes</h4>
                            </a>
                            <p>
                                For most of us, the idea of astronomy is something we directly connect to “stargazing”,
                                telescopes and seeing magnificent displays in the heavens.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 pb-30">
                    <div class="single-carusel row align-items-center">
                        <div class="col-12 col-md-6 thumb">
                            <img class="img-fluid" src="{{ asset('landing-page/img/e1.jpg') }}" alt="">
                        </div>
                        <div class="detials col-12 col-md-6">
                            <p>25th February, 2018</p>
                            <a href="event-details.html">
                                <h4>The Universe Through
                                    A Child S Eyes</h4>
                            </a>
                            <p>
                                For most of us, the idea of astronomy is something we directly connect to “stargazing”,
                                telescopes and seeing magnificent displays in the heavens.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" pb-30>
                    <div class="single-carusel row align-items-center">
                        <div class="col-12 col-md-6 thumb">
                            <img class="img-fluid" src="{{ asset('landing-page/img/e1.jpg') }}" alt="">
                        </div>
                        <div class="detials col-12 col-md-6">
                            <p>25th February, 2018</p>
                            <a href="event-details.html">
                                <h4>The Universe Through
                                    A Child S Eyes</h4>
                            </a>
                            <p>
                                For most of us, the idea of astronomy is something we directly connect to “stargazing”,
                                telescopes and seeing magnificent displays in the heavens.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 pb-30">
                    <div class="single-carusel row align-items-center">
                        <div class="col-12 col-md-6 thumb">
                            <img class="img-fluid" src="{{ asset('landing-page/img/e1.jpg') }}" alt="">
                        </div>
                        <div class="detials col-12 col-md-6">
                            <p>25th February, 2018</p>
                            <a href="event-details.html">
                                <h4>The Universe Through
                                    A Child S Eyes</h4>
                            </a>
                            <p>
                                For most of us, the idea of astronomy is something we directly connect to “stargazing”,
                                telescopes and seeing magnificent displays in the heavens.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="single-carusel row align-items-center">
                        <div class="col-12 col-md-6 thumb">
                            <img class="img-fluid" src="{{ asset('landing-page/img/e1.jpg') }}" alt="">
                        </div>
                        <div class="detials col-12 col-md-6">
                            <p>25th February, 2018</p>
                            <a href="#">
                                <h4>The Universe Through
                                    A Child S Eyes</h4>
                            </a>
                            <p>
                                For most of us, the idea of astronomy is something we directly connect to “stargazing”,
                                telescopes and seeing magnificent displays in the heavens.
                            </p>
                        </div>
                    </div>
                </div>
                <a href="#" class="text-uppercase primary-btn mx-auto mt-40">Load more courses</a>
            </div>
        </div>
    </section> --}}


    <section class="events-list-area section-gap event-page-lists">
        <div class="container">
            <div class="row align-items-center">
                @foreach($pengajar as $guru)
                <div class="col-lg-6 pb-30">
                    <div class="single-carusel row align-items-center">
                        <div class="col-12 col-md-6 thumb">
                            <img class="img-fluid" src="{{ asset('storage/' . $guru->gambar) }}" alt="{{ $guru->nama }}">
                        </div>
                        <div class="detials col-12 col-md-6">
                            <h4>{{ $guru->pengajar }}</h4>
                            <p>
                                {{ $guru->jabatan }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
    
            <!-- Pagination -->
            <div class="row justify-content-center mt-4">
                {{ $pengajar->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </section>
    <!-- End events-list Area -->


    <!-- Start cta-two Area -->
    {{-- <section class="cta-two-area">
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
    </section> --}}
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
