
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>404</title>
    @include('admin-layouts.header')
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .svg-404 {
            width: 90vw;
            max-width: 600px;
            height: auto;
            display: block;
        }
    </style>
</head>
<body>
    <div>
        <div class="svg-404 mb-4">
            @include('errors.error-404-svg')
        </div>
        <div class="error-page container">
            <div class="col-md-8 col-12 offset-md-2">
                <div class="text-center">
                    <h1 class="error-title">Not Found</h1>
                    <p class='fs-5 text-gray-600'>Halaman Tidak Ditemukan</p>
                    <a href="#" onclick="window.history.back(); return false;" class="btn btn-lg btn-outline-primary mt-3">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
