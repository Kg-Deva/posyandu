<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>404</title>
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
    <div class="svg-404">
        @include('errors.error-404-svg')
    </div>
</body>
</html>
