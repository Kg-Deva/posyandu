<!DOCTYPE html>
<html lang="zxx" class="no-js">
{{-- header --}}

@include('layouts.header')
<style>
    .pdf-container {
        text-align: center;
        margin-top: 20px;
    }

    .pdf-link {
        display: inline-block;
        margin-top: 10px;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .pdf-link:hover {
        background-color: #0056b3;
        /* Warna saat hover */
    }

    .pdf-iframe {
        width: 100%;
        height: 100vh;
        /* Tinggi penuh viewport */
        border: none;
        /* Menghilangkan border iframe */
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
                        Peraturan Akademik
                    </h1>
                    <p class="text-white link-nav"><a href="/">Beranda </a> <span
                            class="lnr lnr-arrow-right"></span> <a href="/peraturan"> Peraturan Akademik</a></p>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <!-- Start gallery Area -->
    <section class="gallery-area section-gap">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pdf-container">
                        {{-- <h2 style="margin-bottom: 20px;">Download Peraturan Akademik</h2> --}}
                        <iframe class="pdf-iframe" src="{{ asset('storage/peraturan-akademik.pdf') }}"></iframe>
                        <a class="pdf-link" href="{{ asset('storage/peraturan-akademik.pdf') }}" download>Download
                            PDF</a>
                    </div>
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
