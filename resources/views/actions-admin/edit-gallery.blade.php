<!DOCTYPE html>
<html lang="en">
<title>Edit Gallery</title>

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
                    <h3>Edit Gallery</h3>
                </div>
            </div>
            <div class="section d-flex justify-content-center align-items-center flex-grow-1">
                <div class="col-md-7">
                    <div class="card mb-4">
                        <section class="section d-flex justify-content-center align-items-center flex-grow-1">
                            <div class="card w-100 w-md-30 w-lg-20">
                                <form class="mb-3" action="{{ url('update-gallery', $data->id) }}"
                                    method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                <div class="card-body">
                                    <!-- Hidden Textarea -->
                                    <div class="card-header">
                                        <label for="helpInputTop">Unggah Gambar atau Taruh Gambar</label>
                                    </div>
                                    <div class="card-content">
                                        <!-- Input file untuk gambar -->
                                        <input type="file" name="image" accept="image/*" onchange="previewImages(event)">
                                        <div id="gallery-previews" class="mt-3 d-flex flex-wrap gap-2"></div>
                                    <div class="form-group">
                                        <img src="{{ asset('storage/' . $data->gallery_item) }}" height="10%"
                                            width="50%" alt="" srcset="">
                                    </div>
                                    
                                    <div class="d-flex flex-column mt-4">
                                        <button type="submit" class="btn btn-primary w-100 mb-2">Submit</button>
                                        <a href="/gallery-item" class="btn btn-secondary w-100">Kembali</a>
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
