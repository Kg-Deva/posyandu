<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Too Many Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        img {
            width: 50%;
            max-width: 400px;
        }
    </style>
</head>
<body>
    <h1>Oops! Terlalu Banyak Permintaan</h1>
    <p>Kamu hanya bisa mengirim 2 kritik & saran per 12 jam.</p>
    {{-- <img src="{{ asset('images/error-404.svg') }}" alt="Error 404"> --}}
    <img src="{{ asset('storage/images/error-404.svg') }}" alt="Error 404">

</body>
{{-- <script>
    setTimeout(function() {
        window.location.href = "/kritik-saran";
    }, 3000); // 5000ms = 5 detik
</script> --}}

<script>
    setTimeout(function() {
        // Gunakan pushState untuk mengganti URL tanpa reload
        window.history.pushState({}, '', '/kritik-saran'); 
        document.querySelector('.alert').style.display = 'none';
    }, 100);
</script>

</html>
