<div class="sidebar-menu">
    <ul class="menu">
        <li class="sidebar-title">Menu</li>

        {{-- Menu khusus admin --}}
        @if(auth()->check() && auth()->user()->level == 'admin')
            <li class="sidebar-item {{ Request::is('dashboard') ? 'active' : '' }}">
                <a href="/dashboard" class='sidebar-link'>
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            {{-- <li class="sidebar-item {{ Request::is('gallery-item') ? 'active' : '' }}">
                <a href="/gallery-item" class='sidebar-link'>
                    <i class="bi bi-images"></i>
                    <span>Galeri</span>
                </a>
            </li> --}}
            {{-- Tambahkan menu admin lain di sini --}}
        @endif

        {{-- Menu khusus kader --}}
        @if(auth()->check() && auth()->user()->level == 'kader')
            <li class="sidebar-item {{ Request::is('kader-home') ? 'active' : '' }}">
                <a href="/kader-home" class='sidebar-link'>
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-item has-sub
                {{ Request::is('input-pemeriksaan') ||
                   Request::is('data-pemeriksaan') ||
                   Request::is('profil') ||
                   Request::is('admin/struktur-organisasi')
                   ? 'active' : '' }}">
                <a href="#" class='sidebar-link'>
                    <i class="bi bi-stack"></i>
                    <span>Pemeriksaan</span>
                </a>
                <ul class="submenu">
                    <li class="submenu-item {{ Request::is('input-pemeriksaan') ? 'active' : '' }}">
                        <a href="/input-pemeriksaan">Input Pemeriksaan</a>
                    </li>
                    <li class="submenu-item {{ Request::is('data-pemeriksaan') ? 'active' : '' }}">
                        <a href="/data-pemeriksaan">Data Pemeriksaan</a>
                    </li>
                </ul>
            </li>
            {{-- <li class="sidebar-item {{ Request::is('gallery-item') ? 'active' : '' }}">
                <a href="/gallery-item" class='sidebar-link'>
                    <i class="bi bi-images"></i>
                    <span>Galeri</span>
                </a>
            </li> --}}
            {{-- Tambahkan menu kader lain di sini --}}
        @endif

        {{-- Menu umum (bisa diakses semua role) --}}
        {{-- <li class="sidebar-item {{ Request::is('admin/berita') || Request::is('admin/agenda') ? 'active' : '' }}">
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
                <span>Kontak</span>
            </a>
        </li> --}}

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
