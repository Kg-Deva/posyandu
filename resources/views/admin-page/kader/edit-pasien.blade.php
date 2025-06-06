<!DOCTYPE html>
<html lang="en">
<title>Edit Pasien</title>

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
                    <h3>Edit Pasien</h3>
                </div>
            </div>
            <div class="section d-flex justify-content-center align-items-center flex-grow-1">
                <div class="col-md-7">
                    <div class="card mb-4">
                        <section class="section d-flex justify-content-center align-items-center flex-grow-1">
                            <div class="card w-100 w-md-30 w-lg-20">
                                <form class="mb-3" action="{{ url('update-pasien', $data->id) }}"
                                    method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                <div class="card-body">

                                    <div class="mb-3">
                                        <div class="form-group has-icon-left">
                                            <label for="first-name-vertical">Nama</label>
                                            <div class="position-relative">
                                                <input type="text" id="first-name-vertical" class="form-control"
                                                    name="name" value="{!! $data['name'] !!}">
                                                <div class="form-control-icon">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-group has-icon-left">
                                            <label for="level-edit">Level</label>
                                            <div class="position-relative">
                                                <input type="text" id="level-edit" class="form-control"
                                                    name="level" value="{{ $data['level'] }}" disabled>
                                                <div class="form-control-icon">
                                                    <i class="bi bi-person-badge"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-group has-icon-left">
                                            <label for="email-id-vertical">Email</label>
                                            <div class="position-relative">
                                                <input type="email" id="email-id-vertical" class="form-control"
                                                    name="email" value="{!! $data['email'] !!}">
                                                <div class="form-control-icon">
                                                    <i class="bi bi-envelope"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="mb-3">
                                        <div class="form-group has-icon-left">
                                            <label for="password-vertical">Password</label>
                                            <div class="position-relative">
                                                <input type="password" id="password-vertical" class="form-control"
                                                    name="password" value="{!! $data['password'] !!}" disabled>
                                                <div class="form-control-icon" onclick="togglePasswordVisibility()">
                                                    <i id="password-icon" class="bi bi-eye"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="mb-3">
                                        <div class="form-group has-icon-left">
                                            <label>Status</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="status-switch" name="status"
                                                    {{ $data['status'] == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status-switch">Aktif</label>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="level" value="{{ $data['level'] }}">
                                    <div class="d-flex flex-column mt-4">
                                        <button type="submit" class="btn btn-primary w-100 mb-2">Submit</button>
                                        <a href="/kader-home" class="btn btn-secondary w-100">Kembali</a>
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

</body>

</html>
