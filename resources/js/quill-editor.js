// resources/js/quill-editor.js

// import Quill from 'quill';
// import 'quill/dist/quill.snow.css'; // Mengimpor CSS tema Snow

// document.addEventListener('DOMContentLoaded', () => {
//     const quill = new Quill('#editor', {
//         theme: 'snow'
//     });

// });


// resources/js/quill-editor.js

import Quill from 'quill';
import 'quill/dist/quill.snow.css'; // Import CSS tema Snow

document.addEventListener('DOMContentLoaded', () => {
    // Pilih semua elemen dengan ID yang diawali dengan 'editor'
    const editors = document.querySelectorAll('[id^="editor"]');

    editors.forEach((editorElement, index) => {
        // Inisialisasi Quill untuk setiap elemen
        const quill = new Quill(editorElement, {
            theme: 'snow'
        });

        // Set konten default jika diperlukan
        // const defaultContent = index === 0 ? '<p>Ini adalah konten default untuk editor pertama.</p>' : '<p>Ini adalah konten default untuk editor kedua.</p>';
        const defaultContent = index === 0 ? '' : '';

        quill.root.innerHTML = defaultContent;
    });
});
