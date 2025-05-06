<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('landing-page/img/logoo.png') }}">
    <title>Login LPQ Baiturrahmah</title>
    <!-- Custom CSS -->
    <link href="{{ asset('login/dist/css/style.min.css') }}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    <style>
        /* Gaya untuk tombol toggle password */
        .btn-toggle-password {
            background: transparent;
            border: none;
            color: #6c757d;
            /* Warna ikon */
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.3s;
            padding: 0;
            margin: 0;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
        }

        .btn-toggle-password:hover {
            opacity: 1;
        }

        .btn-toggle-password:focus {
            outline: none;
        }

        .position-relative {
            position: relative;
        }

        .form-control {
            padding-right: 40px;
            /* Menambah ruang untuk tombol */
        }
    </style>
</head>

<body>
    <div class="main-wrapper">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative"
            style="background:url('{{ asset('landing-page/img/bgdasar.jpeg') }}') no-repeat center center;">
            {{-- {{ asset('login/assets/images/big/auth-bg.jpg') }} --}}
            <div class="auth-box row">
                <div class="col-lg-7 col-md-5 modal-bg-img"
                    style="background-image: url('{{ asset('landing-page/img/bg.jpeg') }}');">
                </div>
               
                <div class="col-lg-5 col-md-7 bg-white">
                    <div class="p-3">
                        {{-- <div class="text-center">
                            <img src="{{ asset('login/assets/images/big/icon.png') }}" alt="wrapkit">
                        </div> --}}
              
                        @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                        <h2 class="mt-3 text-center">Login</h2>
                        <p class="text-center">Masukan Email dan Password</p>
                        <form id="formAuthentication" method="GET" action="{{ route('postlogin') }}">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="email">Email</label>
                                        <input class="form-control" id="email" type="email" name="email"
                                            required placeholder="Masukan Email">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="password">Password</label>
                                        <div class="position-relative">
                                            <input class="form-control" id="password" type="password" name="password"
                                                required placeholder="Masukan Password">
                                            <button type="button" class="btn-toggle-password position-absolute"
                                                onclick="togglePasswordVisibility()">
                                                <i id="passwordIcon" class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="password">Password</label>
                                        <input class="form-control" id="password" type="password" name="password"
                                            required placeholder="Masukan Password">
                                    </div>
                                </div> --}}
                                <div class="col-lg-12 text-center">
                                    <button type="submit" class="btn btn-block btn-dark">Login</button>
                                </div>

                                <div class="col-lg-12 text-center mt-3">
                                    <button type="button" class="btn btn-outline-dark btn-block"
                                        onclick="window.location.href='/'">Kembali</button>
                                </div>

                                {{-- <div class="col-lg-12 text-center mt-5">
                                    Don't have an account? <a href="#" class="text-danger">Sign Up</a>
                                </div> --}}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="{{ asset('login/assets/libs/jquery/dist/jquery.min.js ') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('login/assets/libs/popper.js/dist/umd/popper.min.js ') }}"></script>
    <script src="{{ asset('login/assets/libs/bootstrap/dist/js/bootstrap.min.js ') }}"></script>
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
    <script>
        $(".preloader ").fadeOut();
    </script>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }
    </script>

</body>

</html>
