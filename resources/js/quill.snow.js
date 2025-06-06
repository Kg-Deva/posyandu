// // resources/js/app.js
// import Quill from 'quill';
// import 'quill/dist/quill.snow.css'; // Import theme CSS

// document.addEventListener('DOMContentLoaded', () => {
//     const taglineQuill = new Quill('#tagline-editor-container', {
//         theme: 'snow',
//         modules: {
//             toolbar: [
//                 ['bold', 'italic', 'underline']
//             ]
//         }
//     });

//     const descriptionQuill = new Quill('#description-editor-container', {
//         theme: 'snow',
//         modules: {
//             toolbar: [
//                 [{ 'header': [1, 2, false] }],
//                 ['bold', 'italic', 'underline'],
//                 ['image', 'code-block']
//             ]
//         }
//     });

//     function updateTextarea(editor, textareaId) {
//         const textarea = document.querySelector(textareaId);
//         textarea.value = editor.root.innerHTML;
//     }

//     taglineQuill.on('text-change', () => updateTextarea(taglineQuill, '#tagline-textarea'));
//     descriptionQuill.on('text-change', () => updateTextarea(descriptionQuill, '#description-textarea'));

//     updateTextarea(taglineQuill, '#tagline-textarea');
//     updateTextarea(descriptionQuill, '#description-textarea');
// });

// import Quill from 'quill';
// import 'quill/dist/quill.snow.css'; // Import theme CSS

// document.addEventListener('DOMContentLoaded', () => {
//     const taglineQuill = new Quill('#tagline-editor-container', {
//         theme: 'snow',
//         placeholder: 'Masukan Tagline...', // Placeholder text
//         modules: {
//             toolbar: [
//                 ['bold', 'italic', 'underline']
//             ]
//         }
//     });

//     const descriptionQuill = new Quill('#description-editor-container', {
//         theme: 'snow',
//         placeholder: 'Masukan Deskripsi...', // Placeholder text
//         modules: {
//             toolbar: [
//                 [{ 'header': [1, 2, false] }],
//                 ['bold', 'italic', 'underline'],
//                 ['image', 'code-block']
//             ]
//         }
//     });

//     function updateTextarea(editor, textareaId) {
//         const textarea = document.querySelector(textareaId);
//         textarea.value = editor.root.innerHTML;
//     }

//     taglineQuill.on('text-change', () => updateTextarea(taglineQuill, '#tagline-textarea'));
//     descriptionQuill.on('text-change', () => updateTextarea(descriptionQuill, '#description-textarea'));

//     updateTextarea(taglineQuill, '#tagline-textarea');
//     updateTextarea(descriptionQuill, '#description-textarea');
// });




///
//


// import Quill from 'quill';
// import 'quill/dist/quill.snow.css'; // Import theme CSS

// document.addEventListener('DOMContentLoaded', () => {
//     const taglineQuill = new Quill('#tagline-editor-container', {
//         theme: 'snow',
//         modules: {
//             toolbar: [
//                 ['bold', 'italic', 'underline']
//             ]
//         }
//     });

//     const descriptionQuill = new Quill('#description-editor-container', {
//         theme: 'snow',
//         modules: {
//             toolbar: [
//                 [{ 'header': [1, 2, false] }],
//                 ['bold', 'italic', 'underline'],
//                 ['image', 'code-block']
//             ]
//         }
//     });

//     function updateTextarea(editor, textareaId) {
//         const textarea = document.querySelector(textareaId);
//         textarea.value = editor.root.innerHTML;
//     }

//     taglineQuill.on('text-change', () => updateTextarea(taglineQuill, '#tagline-textarea'));
//     descriptionQuill.on('text-change', () => updateTextarea(descriptionQuill, '#description-textarea'));

//     updateTextarea(taglineQuill, '#tagline-textarea');
//     updateTextarea(descriptionQuill, '#description-textarea');
// });
