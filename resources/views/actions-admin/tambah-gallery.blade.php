<!DOCTYPE html>
<html lang="en">
<title>Tambah Galeri</title>

@include('admin-layouts.header')
<style>
    /* Popup styles */
    .popup {
        display: none;
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .popup img {
        max-width: 80%;
        max-height: 80%;
    }

    .popup-close {
        position: absolute;
        top: 20px;
        right: 30px;
        color: white;
        font-size: 30px;
        cursor: pointer;
    }

    .img-thumbnail {
        margin-right: 10px;
        cursor: pointer;
    }
</style>

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
                    <h3>Tambah Galeri</h3>
                </div>
            </div>
            <div class="section d-flex justify-content-center align-items-center flex-grow-1">
                <div class="col-md-7">
                    <div class="card mb-4">
                        <section class="section d-flex justify-content-center align-items-center flex-grow-1">
                            <div class="card w-100 w-md-30 w-lg-20">
                                <form action="{{ route('simpan-gallery') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                <div class="card-body">
                                    <div class="card-header">
                                        <label for="helpInputTop">Unggah Gambar atau Taruh Gambar</label>
                                    </div>
                                    <div class="card-content">
                                        <!-- Input file untuk gambar -->
                                        <input type="file" name="images[]" multiple accept="image/*" onchange="previewImages(event)" required>
                                        <div id="gallery-previews" class="mt-3 d-flex flex-wrap gap-2"></div>
                                        <div class="d-flex flex-column mt-4">
                                            <button type="submit" class="btn btn-primary w-100 mb-2">Submit</button>
                                            <a href="/gallery-item" class="btn btn-secondary w-100">Kembali</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>

            @include('admin-layouts.footer')
        </div>
    </div>
    @include('admin-layouts.js')
   
      <script>
        function previewImages(event) {
          const files = event.target.files;
          const previewContainer = document.getElementById('gallery-previews');
          previewContainer.innerHTML = ''; // Clear previous previews
      
          Array.from(files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function (e) {
              const imageUrl = e.target.result;
      
              const imagePreview = document.createElement('div');
              imagePreview.classList.add('image-preview-container');
              imagePreview.style.display = 'inline-block';
              imagePreview.style.marginRight = '10px';
              imagePreview.style.position = 'relative';
      
            //   const imgElement = document.createElement('img');
            //   imgElement.src = imageUrl;
            //   imgElement.classList.add('img-thumbnail');
            //   imgElement.style.maxWidth = '100px'; // Gambar akan sedikit lebih besar
            //   imgElement.style.cursor = 'pointer';

            const imgElement = document.createElement('img');
            imgElement.src = imageUrl;
            imgElement.classList.add('img-thumbnail');

            // Menyesuaikan ukuran gambar agar semua gambar seragam
            imgElement.style.width = '100px';
            imgElement.style.height = '100px';
            imgElement.style.objectFit = 'cover';
            imgElement.style.cursor = 'pointer';


      
              // Create delete button with "X"
              const deleteButton = document.createElement('span');
              deleteButton.innerHTML = '×';
              deleteButton.classList.add('btn', 'btn-danger', 'btn-sm');
              deleteButton.style.position = 'absolute';
              deleteButton.style.top = '5px';
              deleteButton.style.right = '5px';
              deleteButton.style.fontSize = '20px';
              deleteButton.style.cursor = 'pointer';
              deleteButton.onclick = () => removePreview(imagePreview, file);
      
              // Append image and delete button to the container
              imagePreview.appendChild(imgElement);
              imagePreview.appendChild(deleteButton);
      
              // Add image to preview container
              previewContainer.appendChild(imagePreview);
      
              // Add click functionality to open image in popup
              imgElement.onclick = () => openPopup(imageUrl);
            };
            reader.readAsDataURL(file); // Read the image as a data URL
          });
        }
      
        function openPopup(imageUrl) {
          const popup = document.createElement('div');
          popup.className = 'popup';
          popup.innerHTML = `
            <span class="popup-close" onclick="closePopup()">×</span>
            <img src="${imageUrl}" alt="Preview" class="popup-image">
          `;
          document.body.appendChild(popup);
          popup.style.display = 'flex'; // Show the popup
        }
      
        function closePopup() {
          const popup = document.querySelector('.popup');
          if (popup) popup.remove();
        }
      
        function removePreview(imagePreview, file) {
          // Remove the image preview from the container
          imagePreview.remove();
      
          // Remove the file from the input list
          const inputField = document.querySelector('input[type="file"]');
          const fileList = Array.from(inputField.files);
      
          // Filter out the deleted file from the file list
          const updatedFiles = fileList.filter(f => f !== file);
      
          // Update the input field's file list
          const dataTransfer = new DataTransfer();
          updatedFiles.forEach(f => dataTransfer.items.add(f));
      
          inputField.files = dataTransfer.files;
        }
      
        const toggleDark = document.getElementById('toggle-dark');
        toggleDark.addEventListener('change', () => {
          document.body.classList.toggle('dark-theme');
        });
      </script>
      
      {{-- <style>
        .popup {
          position: fixed;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background-color: rgba(0, 0, 0, 0.8);
          display: none;
          align-items: center;
          justify-content: center;
          z-index: 9999;
        }
      
        .popup-image {
          max-width: 80%;
          max-height: 80%;
          object-fit: contain;
        }
      
        .popup-close {
          position: absolute;
          top: 20px;
          right: 20px;
          font-size: 30px;
          color: white;
          cursor: pointer;
        }
      
        .image-preview-container {
          position: relative;
          display: inline-block;
          margin-right: 10px;
        }
      </style> --}}
      
      
      
</body>

</html>
