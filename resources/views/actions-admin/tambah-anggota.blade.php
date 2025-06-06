<!DOCTYPE html>
<html lang="en">
<title>Tambah Beranda</title>

@include('admin-layouts.header')

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        @include('admin-layouts.icon')

                        <div class="sidebar-toggler  x">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i
                                    class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                @include('admin-layouts.navbar')
            </div>
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            <div class="section d-flex justify-content-center align-items-center flex-grow-1">

                <div class="page-heading">
                    <h3>Tambah Admin Konten</h3>
                </div>
            </div>
            {{-- <form action="{{ route('simpan-anggota') }}" method="POST">
                @csrf --}}
            
            <div class="section d-flex justify-content-center align-items-center flex-grow-1">
                <div class="col-md-7">
                    <div class="card mb-4">
                        <section class="section d-flex justify-content-center align-items-center flex-grow-1">
                            <div class="card w-100 w-md-30 w-lg-20">
                                <form action="{{ route('simpan-anggota') }}" method="POST">
                                    @csrf
                                <div class="card-body">

                                    <div class="mb-3">
                                        <div class="form-group has-icon-left">
                                            <label for="first-name-vertical">Nama</label>
                                            <div class="position-relative">
                                                <input type="text" id="first-name-vertical" class="form-control"
                                                    name="name" placeholder="Masukan Nama Lengkap">
                                                <div class="form-control-icon">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-group has-icon-left">
                                            <label for="level-select">Level</label>
                                            <div class="position-relative">
                                                <select id="level-select" name="level" class="form-control" required>
                                                    <option value="">-- Pilih Level --</option>
                                                    @if(auth()->user()->level == 'admin')
                                                        <option value="kader">Kader</option>
                                                    @endif
                                                    <option value="balita">Balita</option>
                                                    <option value="remaja">Remaja</option>
                                                    <option value="dewasa">Dewasa</option>
                                                    <option value="ibu hamil">Ibu Hamil</option>
                                                    <option value="lansia">Lansia</option>
                                                </select>
                                                <div class="form-control-icon">
                                                    <i class="bi bi-person-badge"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3" id="status-group">
                                        <div class="form-group has-icon-left">
                                            <label>Status</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="status-switch" name="status" checked>
                                                <label class="form-check-label" for="status-switch">Aktif</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-group has-icon-left">
                                            <label for="email-id-vertical">Email</label>
                                            <div class="position-relative">
                                                <input type="email" id="email-id-vertical" class="form-control"
                                                    name="email" placeholder="Email"required>
                                                <div class="form-control-icon">
                                                    <i class="bi bi-envelope"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-group has-icon-left">
                                            <label for="password-vertical">Password</label>
                                            <div class="position-relative">
                                                <input type="password" id="password-vertical" class="form-control"
                                                    name="password" placeholder="Password" required>
                                                <div class="form-control-icon" onclick="togglePasswordVisibility()">
                                                    <i id="password-icon" class="bi bi-eye"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column mt-4">
                                        <button type="submit" class="btn btn-primary w-100 mb-2">Submit</button>
                                        <a href="/dashboard" class="btn btn-secondary w-100">Kembali</a>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </form>

            @include('admin-layouts.footer')
        </div>
    </div>
    @include('admin-layouts.js')
    <script>
document.getElementById('level-select').addEventListener('change', function() {
    const statusGroup = document.getElementById('status-group');
    if (this.value === 'admin') {
        statusGroup.style.display = '';
    } else {
        statusGroup.style.display = 'none';
    }
});
</script>

</body>

</html>
