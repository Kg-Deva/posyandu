<!DOCTYPE html>
<html lang="zxx" class="no-js">
{{-- header --}}
<title>Program Pendidikan</title>
@include('layouts.header')

{{-- header --}}
@include('layouts.css-banner')


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
                        Program Pendidikan
                    </h1>
                    <p class="text-white link-nav"><a href="/">Beranda </a> <span
                            class="lnr lnr-arrow-right"></span> <a href="/program-pendidikan"> Program Pendidikan</a></p>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <!-- Start contact-page Area -->
   <!-- Start Program Pendidikan Area -->
<section class="program-area section-gap">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="menu-content pb-70 col-lg-8">
                <div class="title text-center">
                    <h1 class="mb-10">Program Pendidikan</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <ul class="list-group list-group-flush">
                    @foreach($program as $item)
                    <li class="list-group-item">
                        {{-- <h5>{{ $item->materi }}</h5>  <!-- Nama kelas --> --}}
                        <h5>{{ $item->materi }}</h5>
                        <p>{{ $item->deskripsi }}</p>  <!-- Deskripsi kelas -->
                    </li>
                @endforeach
                   
                </ul>
            </div>
        </div>
    </div>

    </section>
    <section class="program-area section-gap">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="menu-content pb-70 col-lg-8">
                    <div class="title text-center">
                        <h1 class="mb-10">Ekstrakurikuler</h1>
                    </div>
                </div>
            </div>
    
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <ul class="list-group list-group-flush">
                        @foreach($ekstra as $item)
                        <li class="list-group-item">
                            {{-- <h5>{{ $item->materi }}</h5>  <!-- Nama kelas --> --}}
                            <h5>{{ $item->ekstra }}</h5>
                            <p>{{ $item->deskripsi }}</p>  <!-- Deskripsi kelas -->
                        </li>
                    @endforeach
                       
                    </ul>
                </div>
            </div>
        </div>
</section>

    <!-- End contact-page Area -->

    <!-- start footer Area -->
    {{-- footer --}}

    @include('layouts.footer')

    {{-- footer --}}
    <!-- End footer Area -->


    {{-- js --}}

    @include('layouts.js')
</body>

</html>
