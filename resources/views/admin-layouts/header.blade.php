<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <link rel="stylesheet" href="{{ asset('admin/assets/css/main/app.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/main/app-dark.css') }}">
    <link rel="shortcut icon" href="{{ asset('admin/assets/images/logo/favicon.svg') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('landing-page/img/logoo.png') }}" type="image/png">

    <link rel="stylesheet" href="{{ asset('admin/assets/css/shared/iconly.css') }}">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <style>
        /* Umum untuk Quill editor */
        .ql-container.ql-snow {
            position: relative;
        }

        /* Placeholder umum untuk mode terang */
        .ql-container.ql-snow .ql-editor::before {
            content: attr(data-placeholder);
            color: #888;
            /* Warna placeholder untuk mode terang */
            position: absolute;
            top: 10px;
            left: 10px;
            pointer-events: none;
            font-style: italic;
            transition: opacity 0.2s ease;
            white-space: pre-wrap;
        }

        /* Placeholder untuk mode gelap */
        body.dark-mode .ql-container.ql-snow .ql-editor::before {
            color: #bbb;
            /* Warna placeholder untuk mode gelap */
        }

        /* Sembunyikan placeholder saat editor tidak kosong */
        .ql-container.ql-snow .ql-editor:not(:empty)::before {
            opacity: 0;
        }
    </style>
    {{-- @vite('resources/js/app.js') --}}

    


</head>
