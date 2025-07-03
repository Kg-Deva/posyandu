
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
        @endif

        @if(auth()->check() && auth()->user()->level == 'balita')
        <li class="sidebar-item {{ Request::is('balita-home') ? 'active' : '' }}">
                <a href="/balita-home" class='sidebar-link'>
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        @endif
        @if(auth()->check() && auth()->user()->level == 'remaja')
        <li class="sidebar-item {{ Request::is('remaja-home') ? 'active' : '' }}">
                <a href="/remaja-home" class='sidebar-link'>
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        @endif
         @if(auth()->check() && auth()->user()->level == 'ibu hamil')
        <li class="sidebar-item {{ Request::is('ibu-hamil-home') ? 'active' : '' }}">
                <a href="/ibu-hamil-home" class='sidebar-link'>
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        @endif
        @if(auth()->check() && auth()->user()->level == 'dewasa')
        <li class="sidebar-item {{ Request::is('dewasa-home') ? 'active' : '' }}">
                <a href="/ibu-hamil-home" class='sidebar-link'>
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        @endif
         @if(auth()->check() && auth()->user()->level == 'lansia')
        <li class="sidebar-item {{ Request::is('lansia-home') ? 'active' : '' }}">
                <a href="/lansia-home" class='sidebar-link'>
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        @endif
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



