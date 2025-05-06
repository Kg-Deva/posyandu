<div class="sidebar-menu">
    <ul class="menu">
        <li class="sidebar-title">Menu</li>

        {{-- <li class="sidebar-item active "> --}}
        <li class="sidebar-item ">
            <a href="/dashboard" class='sidebar-link'>
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="sidebar-item  ">
            <a href="/beranda" class='sidebar-link'>
                <i class="bi bi-house"></i>


                <span>Beranda</span>
            </a>
        </li>

        <li class="sidebar-item  has-sub">
            <a href="#" class='sidebar-link'>
                <i class="bi bi-stack"></i>
                <span>Profil</span>
            </a>
            <ul class="submenu ">
                <li class="submenu-item ">
                    <a href="/profil">LPQ Baiturrahmah</a>
                </li>
                {{-- <li class="submenu-item ">
                    <a href="/admin/profil-pengajar">Profil Pengajar</a>
                </li> --}}
                <li class="submenu-item ">
                    <a href="/admin/struktur-organisasi">Struktur Organisasi</a>
                </li>
            </ul>
        </li>



        <li class="sidebar-item ">
            <a href="/gallery-item" class='sidebar-link'>
                <i class="bi bi-images"></i>
                <span>Galeri</span>
            </a>
        </li>

        {{-- <li class="sidebar-item  has-sub">
            <a href="#" class='sidebar-link'>
                <i class="bi bi-book-half"></i>
                <span>Akademik</span>
            </a>
            <ul class="submenu ">
               
                <li class="submenu-item ">
                    <a href="/peraturan-akademik">Struktur Organisasi</a>
                </li>
            </ul>
        </li> --}}


        <li class="sidebar-item has-sub ">
            <a href="" class='sidebar-link'>
                <i class="bi bi-info-circle"></i>
                <span>Informasi</span>
            </a>

            <ul class="submenu ">
                <li class="submenu-item ">
                    <a href="/admin/berita">Berita</a>
                </li>
                <li class="submenu-item ">
                    <a href="/admin/agenda">Agenda</a>
                </li>
            </ul>
        </li>

{{-- 
        <li class="sidebar-item">
            <a href="/program" class='sidebar-link'>
                <i class="bi bi-book-half"></i>
                <span>Program Pendidikan</span>
            </a>
        </li> --}}

        <li class="sidebar-item has-sub ">
            <a href="" class='sidebar-link'>
                <i class="bi bi-book-half"></i>
                <span>Program Pendidikan</span>
            </a>

            <ul class="submenu ">
                <li class="submenu-item ">
                    <a href="/program">Program Pendidikan</a>
                </li>
                <li class="submenu-item ">
                    <a href="/ekstra">Ekstrakurikuler</a>
                </li>
            </ul>
        </li>

        <li class="sidebar-item">
            <a href="/admin-kritik" class='sidebar-link'>
                <i class="bi bi-chat-dots"></i> <!-- Ganti dengan ikon yang sesuai -->
                <span>Kritik & Saran</span>
            </a>
        </li>
        

        <li class="sidebar-item">
            <a href="/kontak" class='sidebar-link'>
                <i class="bi bi-person-rolodex"></i>
                <span>kontak</span>
            </a>
        </li>





        {{-- <li class="sidebar-title">Logout</li>

        <li class="sidebar-item  ">
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); confirmLogout();" class='sidebar-link'>
                <i class="bi bi-x-octagon-fill"></i>
                <span>Logout</span>
            </a>

        </li> --}}

        <li class="sidebar-title">Logout</li>

        <li class="sidebar-item">
            <a href="javascript:void(0);" onclick="event.preventDefault(); confirmLogout();" class='sidebar-link'>
                <i class="bi bi-x-octagon-fill"></i>
                <span>Logout</span>
            </a>
        </li>

        <!-- Form untuk Logout -->
        <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>


    </ul>
</div>
