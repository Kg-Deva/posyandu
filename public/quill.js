
document.addEventListener('DOMContentLoaded', () => {
    // Initialize Quill editors
    const taglineQuill = new Quill('#tagline-editor-container', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{
                    'header': [1, 2, false]
                }],

                ['bold', 'italic', 'underline']
            ]
        }

    });


    const descriptionQuill = new Quill('#description-editor-container', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{
                    'header': [1, 2, false]
                }],
                ['bold', 'italic', 'underline'],
                // ['image', 'code-block']
            ]
        }
    });

    const historyQuill = new Quill('#history-editor-container', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{
                    'header': [1, 2, false]
                }],
                ['bold', 'italic', 'underline']
                // ['image', 'code-block'] // Uncomment if needed
            ]
        }
    });

    const visiQuill = new Quill('#visi-editor-container', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{
                    'header': [1, 2, false]
                }],
                ['bold', 'italic', 'underline']
                // ['image', 'code-block'] // Uncomment if needed
            ]
        }
    });

    const misiQuill = new Quill('#misi-editor-container', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{
                    'header': [1, 2, false]
                }],
                ['bold', 'italic', 'underline']
                // ['image', 'code-block'] // Uncomment if needed
            ]
        }
    });

    const beritaQuill = new Quill('#berita-editor-container', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{
                    'header': [1, 2, false]
                }],
                ['bold', 'italic', 'underline']
                // ['image', 'code-block'] // Uncomment if needed
            ]
        }
    });

    // Function to update hidden inputs with Quill content
    function updateHiddenInput(editor, inputId) {
        const input = document.querySelector(inputId);
        input.value = editor.root.innerHTML;
    }

    // Update hidden inputs before form submission
    document.querySelector('#my-form').addEventListener('submit', () => {
        updateHiddenInput(taglineQuill, '#tagline-textarea');
        updateHiddenInput(descriptionQuill, '#description-textarea');
        updateHiddenInput(historyQuill, '#history-textarea'); // Tambahkan update untuk history
        updateHiddenInput(visiQuill, '#visi-textarea'); // Tambahkan update untuk history
        updateHiddenInput(misiQuill, '#misi-textarea'); // Tambahkan update untuk history
        updateHiddenInput(beritaQuill, '#berita-textarea'); // Tambahkan update untuk history

    });

    // Optional: Initialize hidden inputs with current Quill content
    updateHiddenInput(taglineQuill, '#tagline-textarea');
    updateHiddenInput(descriptionQuill, '#description-textarea');
    updateHiddenInput(historyQuill, '#history-textarea'); // Tambahkan inisialisasi untuk history
    updateHiddenInput(visiQuill, '#visi-textarea'); // Tambahkan inisialisasi untuk history
    updateHiddenInput(misiQuill, '#misi-textarea'); // Tambahkan inisialisasi untuk history
    updateHiddenInput(beritaQuill, '#berita-textarea'); // Tambahkan inisialisasi untuk history

});
