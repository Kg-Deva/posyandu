<div class="sidebar-menu">
    <ul class="menu">
        <li class="sidebar-title">Menu</li>

        <li class="sidebar-item {{ Request::is('dashboard') ? 'active' : '' }}">
            <a href="/dashboard" class='sidebar-link'>
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="sidebar-item {{ Request::is('beranda') ? 'active' : '' }}">
            <a href="/beranda" class='sidebar-link'>
                <i class="bi bi-house"></i>
                <span>Beranda</span>
            </a>
        </li>

        <li class="sidebar-item has-sub {{ Request::is('profil') || Request::is('admin/struktur-organisasi') ? 'active' : '' }}">
            <a href="#" class='sidebar-link'>
                <i class="bi bi-stack"></i>
                <span>Profil</span>
            </a>
            <ul class="submenu">
                <li class="submenu-item {{ Request::is('profil') ? 'active' : '' }}">
                    <a href="/profil">LPQ Baiturrahmah</a>
                </li>
                <li class="submenu-item {{ Request::is('admin/struktur-organisasi') ? 'active' : '' }}">
                    <a href="/admin/struktur-organisasi">Struktur Organisasi</a>
                </li>
            </ul>
        </li>

        <li class="sidebar-item {{ Request::is('gallery-item') ? 'active' : '' }}">
            <a href="/gallery-item" class='sidebar-link'>
                <i class="bi bi-images"></i>
                <span>Galeri</span>
            </a>
        </li>

        <li class="sidebar-item has-sub {{ Request::is('admin/berita') || Request::is('admin/agenda') ? 'active' : '' }}">
            <a href="#" class='sidebar-link'>
                <i class="bi bi-info-circle"></i>
                <span>Informasi</span>
            </a>
            <ul class="submenu">
                <li class="submenu-item {{ Request::is('admin/berita') ? 'active' : '' }}">
                    <a href="/admin/berita">Berita</a>
                </li>
                <li class="submenu-item {{ Request::is('admin/agenda') ? 'active' : '' }}">
                    <a href="/admin/agenda">Agenda</a>
                </li>
            </ul>
        </li>

        <li class="sidebar-item has-sub {{ Request::is('program') || Request::is('ekstra') ? 'active' : '' }}">
            <a href="#" class='sidebar-link'>
                <i class="bi bi-book-half"></i>
                <span>Program Pendidikan</span>
            </a>
            <ul class="submenu">
                <li class="submenu-item {{ Request::is('program') ? 'active' : '' }}">
                    <a href="/program">Program Pendidikan</a>
                </li>
                <li class="submenu-item {{ Request::is('ekstra') ? 'active' : '' }}">
                    <a href="/ekstra">Ekstrakurikuler</a>
                </li>
            </ul>
        </li>

        <li class="sidebar-item {{ Request::is('admin-kritik') ? 'active' : '' }}">
            <a href="/admin-kritik" class='sidebar-link'>
                <i class="bi bi-chat-dots"></i>
                <span>Kritik & Saran</span>
            </a>
        </li>

        <li class="sidebar-item {{ Request::is('kontak') ? 'active' : '' }}">
            <a href="/kontak" class='sidebar-link'>
                <i class="bi bi-person-rolodex"></i>
                <span>kontak</span>
            </a>
        </li>

        <li class="sidebar-title">Logout</li>
        <li class="sidebar-item">
            <a href="javascript:void(0);" onclick="event.preventDefault(); confirmLogout();" class='sidebar-link'>
                <i class="bi bi-x-octagon-fill"></i>
                <span>Logout</span>
            </a>
        </li>

        <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </ul>
</div>
