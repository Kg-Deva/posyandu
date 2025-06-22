// ✅ KHUSUS UNTUK GEJALA SAKIT TOGGLE
// ✅ GLOBAL FUNCTION UNTUK INLINE ONCLICK
window.toggleGejalaSakitInline = function (cb) {
  console.log('🔄 Inline toggle gejala sakit:', cb.checked);
  const ta = document.getElementById('sebutkan_gejala');
  if (!ta) {
    console.log('❌ Textarea not found');
    return;
  }

  if (cb.checked) {
    ta.disabled = false;
    ta.required = true;
    ta.style.backgroundColor = '#ffffff';
    ta.style.cursor = 'text';
    ta.placeholder = 'Sebutkan gejala sakit yang dialami...';
    setTimeout(() => ta.focus(), 100);
    console.log('✅ Textarea ENABLED via inline');
  } else {
    ta.disabled = true;
    ta.required = false;
    ta.value = '';
    ta.style.backgroundColor = '#f8f9fa';
    ta.style.cursor = 'not-allowed';
    ta.placeholder = 'Centang "Ada Gejala Sakit" untuk mengisi...';
    console.log('❌ Textarea DISABLED via inline');
  }
};

// ✅ FUNCTION UNTUK SETUP SETELAH AJAX LOAD
window.setupGejalaSakitToggleAfterAjax = function () {
  console.log('🔧 Setting up gejala sakit toggle after AJAX...');

  const checkbox = document.getElementById('ada_gejala_sakit');
  const textarea = document.getElementById('sebutkan_gejala');

  if (checkbox && textarea) {
    // ✅ SET INITIAL STATE
    textarea.disabled = true;
    textarea.style.backgroundColor = '#f8f9fa';
    textarea.style.cursor = 'not-allowed';
    textarea.placeholder = 'Centang "Ada Gejala Sakit" untuk mengisi...';

    // ✅ TOGGLE FUNCTION VIA EVENT LISTENER
    function toggleTextarea() {
      console.log('🔄 Toggle gejala sakit via event listener:', checkbox.checked);

      if (checkbox.checked) {
        textarea.disabled = false;
        textarea.required = true;
        textarea.style.backgroundColor = '#ffffff';
        textarea.style.cursor = 'text';
        textarea.placeholder = 'Sebutkan gejala sakit yang dialami...';
        textarea.focus();
        console.log('✅ Textarea ENABLED via event listener');
      } else {
        textarea.disabled = true;
        textarea.required = false;
        textarea.value = '';
        textarea.style.backgroundColor = '#f8f9fa';
        textarea.style.cursor = 'not-allowed';
        textarea.placeholder = 'Centang "Ada Gejala Sakit" untuk mengisi...';
        console.log('❌ Textarea DISABLED via event listener');
      }
    }

    // ✅ ADD EVENT LISTENERS (BACKUP UNTUK INLINE)
    checkbox.addEventListener('change', toggleTextarea);
    checkbox.addEventListener('click', toggleTextarea);

    console.log('✅ Gejala sakit toggle setup completed after AJAX');
  } else {
    console.log('❌ Gejala sakit elements not found after AJAX');
  }
};

// ✅ AUTO INIT JIKA ELEMENTS SUDAH ADA DI DOM
document.addEventListener('DOMContentLoaded', function () {
  console.log('🔧 Checking for existing gejala sakit elements...');

  if (document.getElementById('ada_gejala_sakit') && document.getElementById('sebutkan_gejala')) {
    console.log('✅ Gejala sakit elements found, auto-initializing...');
    window.setupGejalaSakitToggleAfterAjax();
  } else {
    console.log('⏳ Gejala sakit elements not found, waiting for AJAX load...');
  }
});
