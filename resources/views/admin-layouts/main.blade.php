{{-- filepath: resources/views/admin-layouts/main.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title', 'Posyandu')</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('admin-layouts.header')
    <style>
        html,
        body,
        #app,
        #main {
            height: 100%;
        }

        #main {
            flex: 1 0 auto;
            display: flex;
            flex-direction: column;
            min-height: 0;
        }

        .content {
            flex: 1 1 auto;
        }
    </style>
</head>

<body style="min-height:100vh;display:flex;flex-direction:column;">

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

        <div id="main" style="flex:1 0 auto; display: flex; flex-direction: column;">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            <div style="display: flex; flex: 1 0 auto;">
                <div class="content flex-grow-1">
                    @yield('content')
                </div>
            </div>
            @include('admin-layouts.footer')
        </div>
    </div>



    @include('admin-layouts.js')
</body>

</html>
