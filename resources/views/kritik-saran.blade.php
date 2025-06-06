<!DOCTYPE html>
<html lang="zxx" class="no-js">
{{-- header --}}

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
                        Kritik & Saran
                    </h1>
                    <p class="text-white link-nav"><a href="/">Beranda </a> <span
                            class="lnr lnr-arrow-right"></span> <a href="/kritik-saran"> Kritik & Saran</a></p>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <!-- Start contact-page Area -->
    <section class="contact-page-area section-gap">
        <div class="container">
            <div class="row">
             

                <div class="container">
                    <div class="row justify-content-center align-items-center min-vh-100">
                        <div class="col-lg-8 col-md-10 col-sm-12">
                            {{-- <form class="form-area contact-form" id="myForm" action="{{ route('simpan-kritiksaran') }}" method="POST"> --}}
                                <form class="form-area contact-form" action="{{ route('simpan-kritiksaran') }}" method="POST" enctype="multipart/form-data">

                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 form-group">
                                        <input name="name" placeholder="Masukan Nama"
                                            onfocus="this.placeholder = ''"
                                            onblur="this.placeholder = 'Masukan Nama'"
                                            class="common-input mb-20 form-control" required="" type="text">

                                        <input name="email" placeholder="Masukan Email"
                                            pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{1,63}$"
                                            onfocus="this.placeholder = ''"
                                            onblur="this.placeholder = 'Masukan Email'"
                                            class="common-input mb-20 form-control" required="" type="email">

                                        {{-- <input name="subjek" placeholder="Masukan Kritik atau Saran"
                                            onfocus="this.placeholder = ''" onblur="this.placeholder = 'Masukan Kritik atau Saran'"
                                            class="common-input mb-20 form-control" required="" type="text"> --}}

                                           

                                            <select name="subjek" class="common-input mb-20 form-control" required
                                                style="font-weight: 400; font-size: 14px; padding: 10px 15px; color: #6c757d; height: 45px; border-radius: 5px; border: 1px solid #ced4da; background-color: #fff;">
                                                <option value="" disabled selected hidden>Pilih Subjek</option>
                                                <option value="Kritik" style="font-weight: normal;">Kritik</option>
                                                <option value="Saran" style="font-weight: normal;">Saran</option>
                                            </select>



                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <textarea class="common-textarea form-control" name="pesan" placeholder="Tulis Kritik atau Saran Anda disini"
                                            onfocus="this.placeholder = ''" onblur="this.placeholder = 'Tulis Kritik atau Saran Anda disini'" required=""></textarea>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="alert-msg" style="text-align: left;"></div>
                                        <button class="genric-btn primary" type="submit" style="float: right;">Kirim Pesan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
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
