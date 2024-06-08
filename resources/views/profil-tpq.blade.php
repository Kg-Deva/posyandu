<!DOCTYPE html>
<html lang="zxx" class="no-js">

{{-- header --}}

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
                        Profil TPQ Baiturrahmah
                    </h1>
                    <p class="text-white link-nav"><a href="/">Beranda </a> <span
                            class="lnr lnr-arrow-right"></span> <a href="/profil-tpq"> Profil TPQ Baiturrahmah</a></p>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <!-- Start feature Area -->
    <section class="feature-area pb-120">
        <div class="container">
            <div class="row">


                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 mx-auto">
                            <div class="single-feature">
                                <div class="title">
                                    <h4>No.1 of universities</h4>
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

    {{-- <!-- Start info Area -->
    <section class="info-area pb-120">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-6 no-padding info-area-left">
                    <img class="img-fluid" src="{{ asset('landing-page/img/about-img.jpg') }}" alt="">
                </div>
                <div class="col-lg-6 info-area-right">
                    <h1>Sejarah TPQ Baiturrahmah</h1>
                    <p>inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct
                        standards especially in the workplace. That’s why it’s crucial that, as women, our behavior on
                        the job is beyond reproach.</p>
                    <br>
                    <p>
                        inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct
                        standards especially in the workplace. That’s why it’s crucial that, as women, our behavior on
                        the job is beyond reproach. inappropriate behavior is often laughed off as “boys will be boys,”
                        women face higher conduct standards especially in the workplace. That’s why it’s crucial that,
                        as women, our behavior on the job is beyond reproach.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- End info Area --> --}}



    <section class="info-area pb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 left-contents">
                    <div class="main-image">
                        <img class="img-fluid" src="{{ asset('landing-page/img/m-img.jpg') }}" alt="">
                    </div>
                    <div class="jq-tab-wrapper" id="horizontalTab">
                        <div class="jq-tab-menu">
                            <div class="jq-tab-title active" data-tab="1">Sejarah</div>
                            <div class="jq-tab-title" data-tab="2">Visi & Misi</div>

                        </div>
                        <div class="jq-tab-content-wrapper">
                            <div class="jq-tab-content active" data-tab="1">
                                When you enter into any new area of science, you almost always find yourself with a
                                baffling new language of technical terms to learn before you can converse with the
                                experts. This is certainly true in astronomy both in terms of terms that refer to the
                                cosmos and terms that describe the tools of the trade, the most prevalent being the
                                telescope.
                                <br>
                                <br>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                exercitation ullamco laboris nisi ut aliquip ex ea commodoconsequat. Duis aute irure
                                dolor in reprehenderit in voluptate velit esse cillum. Lorem ipsum dolor sit amet,
                                consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna
                                aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                                aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                                velit esse cillum.
                            </div>
                            <div class="jq-tab-content" data-tab="2">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure
                                dolor in reprehenderit in voluptate velit esse cillum.
                                <br>
                                <br>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                exercitation ullamco laboris nisi ut aliquip ex ea commodoconsequat. Duis aute irure
                                dolor in reprehenderit in voluptate velit esse cillum. Lorem ipsum dolor sit amet,
                                consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna
                                aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                                aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                                velit esse cillum.
                            </div>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End course-details Area -->





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
