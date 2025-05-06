<!DOCTYPE html>
<html lang="zxx" class="no-js">
{{-- header --}}
<title>Struktur Organisasi</title>
@include('layouts.header')

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
    .pdf-container {
        text-align: center;
        margin-top: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    .pdf-link {
        display: inline-block;
        margin-top: 10px;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .pdf-link:hover {
        background-color: #0056b3;
    }

    canvas {
        max-width: 100%;
        height: auto;
        border: 1px solid #ccc;
        margin-top: 20px;
    }
</style>

@include('layouts.css-banner')
{{-- header --}}

<body>
    {{-- navbar --}}
    @include('layouts.navbar')
    {{-- navbar --}}

    <!-- start banner Area -->
    <section class="custom-banner-area relative custom-about-banner" id="custom-home">
        <div class="custom-overlay custom-overlay-bg"></div>
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="about-content col-lg-12">
                    <h1 class="text-white">
                        Stuktur Organisasi
                    </h1>
                    <p class="text-white link-nav"><a href="/">Beranda </a> <span
                            class="lnr lnr-arrow-right"></span> <a href="/peraturan"> Struktur Organisasi</a></p>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <!-- Start PDF.js Section -->
    <section class="gallery-area section-gap">
        <div class="container">
            <div class="row">
                {{-- @foreach($strk as $data)
                <div class="col-lg-12">
                    <div class="pdf-container">
                        <canvas id="pdf-render"></canvas>
                        <a class="pdf-link" href="{{ asset('storage/public/peraturan/' . $data->file_path) }}" download>Download
                            PDF</a>
                    </div>
                </div>
                @endforeach --}}

                @if($strk->isNotEmpty())
                    @foreach($strk as $data)
                        <div class="col-lg-12">
                            <div class="pdf-container">
                                <canvas id="pdf-render-{{ $loop->index }}"></canvas>
                                <a class="pdf-link" href="{{ asset('storage/public/peraturan/' . $data->file_path) }}" download>
                                    Download PDF
                                </a>
                            </div>
                        </div>
                    @endforeach
                    @else
                        <div class="col-lg-12 text-center">
                            <img src="{{ asset('storage/default/logo.png') }}" alt="Belum Ada Struktur Organisasi" class="img-fluid" style="max-width: 200px;">
                            <p class="text-muted">Struktur organisasi belum tersedia</p>
                        </div>
                @endif

            </div>
        </div>
    </section>
    <!-- End PDF.js Section -->

    {{-- footer --}}
    @include('layouts.footer')
    {{-- footer --}}

    {{-- js --}}
    @include('layouts.js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    {{-- <script>
        const url = '{{ asset('storage/public/peraturan/' . $data->file_path) }}';
        // const url = '{{ asset('storage/public/peraturan/' . $data->file_path) }}';

        let pdfDoc = null,
            pageNum = 1,
            scale = 1.5,
            canvas = document.getElementById('pdf-render'),
            ctx = canvas.getContext('2d');

        // Render the page
        const renderPage = num => {
            // Get page
            pdfDoc.getPage(num).then(page => {
                const viewport = page.getViewport({
                    scale
                });
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                const renderCtx = {
                    canvasContext: ctx,
                    viewport
                };

                page.render(renderCtx);
            });
        };

        // Get Document
        pdfjsLib.getDocument(url).promise.then(pdfDoc_ => {
            pdfDoc = pdfDoc_;
            renderPage(pageNum); // Render first page
        });
    </script> --}}

    <script>
        @if($strk->isNotEmpty())
    @foreach($strk as $data)
        @if($data->file_path)
            let url{{ $loop->index }} = '{{ asset('storage/public/peraturan/' . $data->file_path) }}';
            let pdfDoc{{ $loop->index }} = null,
                pageNum = 1,
                scale = 1.5,
                canvas{{ $loop->index }} = document.getElementById('pdf-render-{{ $loop->index }}'),
                ctx{{ $loop->index }} = canvas{{ $loop->index }}.getContext('2d');

            pdfjsLib.getDocument(url{{ $loop->index }}).promise.then(pdfDoc_ => {
                pdfDoc{{ $loop->index }} = pdfDoc_;
                pdfDoc{{ $loop->index }}.getPage(pageNum).then(page => {
                    let viewport = page.getViewport({ scale });
                    canvas{{ $loop->index }}.height = viewport.height;
                    canvas{{ $loop->index }}.width = viewport.width;

                    let renderCtx = {
                        canvasContext: ctx{{ $loop->index }},
                        viewport
                    };
                    page.render(renderCtx);
                });
            });
        @endif
    @endforeach
@endif

        </script>
    {{-- js --}}
</body>

</html>
