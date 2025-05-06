<header id="header" id="home">
    <div class="header-top">
        <div class="container">
            <div class="row">
              



                {{-- yang asli --}}
                {{-- <div class="col-lg-6 col-sm-6 col-4 header-top-right no-padding text-end">
                    @if($kontaks)
                        <a href="tel:{{ $kontaks->no_telp }}">
                            <i class="fa fa-phone"></i> <span class="text">{{ $kontaks->no_telp }}</span>
                        </a>
                        <a href="mailto:{{ $kontaks->email }}" class="ms-3">
                            <i class="fa fa-envelope"></i> <span class="text">{{ $kontaks->email }}</span>
                        </a>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $kontaks->whatsapp) }}" class="ms-3" target="_blank">
                            <i class="fa fa-whatsapp"></i> <span class="text">WhatsApp</span>
                        </a>
                    @endif
                </div> --}}

                {{-- improve --}}
                <div class="col-lg-12 col-sm-12 col-4 header-top-right no-padding text-end">
                    @if($kontaks)
                        @if($kontaks->no_telp)
                            <a href="tel:{{ $kontaks->no_telp }}">
                                <i class="fa fa-phone"></i> <span class="text">{{ $kontaks->no_telp }}</span>
                            </a>
                        @endif
                
                        @if($kontaks->email)
                            <a href="mailto:{{ $kontaks->email }}" class="ms-3">
                                <i class="fa fa-envelope"></i> <span class="text">{{ $kontaks->email }}</span>
                            </a>
                        @endif
                
                       
                        @if($kontaks->whatsapp)
                            <a href="https://wa.me/62{{ ltrim(preg_replace('/[^0-9]/', '', $kontaks->whatsapp), '0') }}" 
                            class="ms-3" target="_blank">
                                <i class="fa fa-whatsapp"></i> <span class="text">WhatsApp</span>
                            </a>
                        @endif

                    @else
                        <p class="text-muted">Kontak belum tersedia.</p>
                    @endif
                </div>
                
                
            </div>
        </div>
    </div>
    
    <div class="container main-menu">
        <div class="d-flex align-items-center justify-content-between">
          <div id="logo" class="me-3">
            <a href="/">
              <img src="{{ asset('landing-page/img/logoo.png') }}" alt="Logo" style="height: 60px;">
            </a>
          </div>
            <nav id="nav-menu-container">
                <ul class="nav-menu">
                    <li><a href="/">Beranda</a></li>
                    <li class="menu-has-children"><a href="">Profil</a>
                        <ul>
                            <li><a href="/profil-lpq">Profil LPQ Baiturrahmah</a></li>
                            {{-- <li><a href="/profil-pengajar">Profil Pengajar</a></li> --}}
                            <li><a href="/struktur-organisasi">Struktur Organisasi</a></li>
                        </ul>
                    </li>
                    <li><a href="/gallery">Galeri</a></li>

                    <li class="menu-has-children"><a href="">Informasi</a>
                        <ul>
                            <li><a href="/berita">Berita</a></li>
                            <li><a href="/agenda">Agenda</a></li>
                        </ul>
                    </li>
                    <li><a href="/program-pendidikan">Program Pendidikan</a></li>
                    <li><a href="/kritik-saran">Kritik & Saran</a></li>
                    {{-- <li><a href="/login-lpq">Login</a></li> --}}
                </ul>
            </nav><!-- #nav-menu-container -->
        </div>
    </div>
</header><!-- #header -->
