{{-- filepath: c:\laragon\www\posyandu\resources\views\admin-page\kader\input-pemeriksaan.blade.php --}}
@extends('admin-layouts.main')
@section('content')
<div class="container">
    <h4>Input Pemeriksaan</h4>
    
    <!-- ✅ NOTIFICATION AREA untuk JavaScript -->
    <div id="notification-area">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            @if(session('message'))
            <br><small>{{ session('message') }}</small>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
    </div>
    
    <div class="mb-3">
        <input type="text" id="cari-nik" placeholder="Cari NIK atau Nama..." class="form-control">
    </div>
    <button id="btn-cari" class="btn btn-primary mb-3">
        <i class="bi bi-search"></i> Cari
    </button>
    <div id="hasil-cari"></div>
    <div id="form-pemeriksaan"></div>
</div>

{{-- ✅ LOAD EXTERNAL JS FILES --}}
<script src="{{ asset('js/balita-handler.js') }}"></script>
<script src="{{ asset('js/gejala-sakit-balita.js') }}"></script>
<script src="{{ asset('js/remaja-handler.js') }}"></script>
<script src="{{ asset('js/bumil-handler.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔧 Setting up input-pemeriksaan handlers...');
    
    // ✅ AUTO HIDE FLASH MESSAGES AFTER 5 SECONDS
    const flashAlerts = document.querySelectorAll('.alert');
    flashAlerts.forEach(alert => {
        setTimeout(() => {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 150);
        }, 5000);
    });
    
    const btnCari = document.getElementById('btn-cari');
    const inputCari = document.getElementById('cari-nik');
    const notificationArea = document.getElementById('notification-area');
    
    if (!btnCari || !inputCari) {
        console.error('❌ Search elements not found!');
        return;
    }
    
    console.log('✅ Elements found, attaching handlers...');
    
    // ✅ SIMPLE NOTIFICATION FUNCTION
    function showAlert(message, type = 'success') {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconHtml = type === 'error' ? '<i class="bi bi-exclamation-triangle"></i> ' : '';

        // ✅ CLEAR EXISTING ALERTS FIRST
        const existingAlerts = notificationArea.querySelectorAll('.alert');
        existingAlerts.forEach(alert => alert.remove());

        // ✅ ADD NEW ALERT
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert ${alertClass} alert-dismissible fade show`;
        alertDiv.setAttribute('role', 'alert');
        alertDiv.innerHTML = `
            ${iconHtml}${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        notificationArea.appendChild(alertDiv);
        
        // ✅ AUTO HIDE AFTER 5 SECONDS
        setTimeout(() => {
            alertDiv.classList.remove('show');
            setTimeout(() => alertDiv.remove(), 150);
        }, 5000);
        
        // ✅ SCROLL TO TOP
        window.scrollTo({top: 0, behavior: 'smooth'});
    }
    
    // ✅ GLOBAL FUNCTION untuk external scripts
    window.showInputAlert = showAlert;
    
    btnCari.onclick = function() {
        console.log('🔍 Search button clicked');
        
        let q = inputCari.value.trim();
        
        if (!q) {
            showAlert('Masukkan NIK atau nama untuk dicari', 'error');
            return;
        }
        
        console.log('🔍 Searching for:', q);
        
        btnCari.disabled = true;
        btnCari.innerHTML = '<i class="bi bi-search"></i> Mencari...';
        
        fetch('/cari-pasien', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({q: q})
        })
        .then(res => {
            console.log('Search response status:', res.status);
            if (!res.ok) {
                throw new Error('Data tidak ditemukan');
            }
            return res.text();
        })
        .then(html => {
            console.log('✅ Response HTML received, length:', html.length);
            
            document.getElementById('form-pemeriksaan').innerHTML = html;
            
            // ✅ SUCCESS ALERT
            showAlert('Data ditemukan! Silakan isi form pemeriksaan', 'success');
            
            // ✅ DETECT FORM TYPE AND INITIALIZE
            const hasBalitaForm = html.includes('form-balita') || html.includes('Berat Badan Balita');
            const hasRemajaForm = html.includes('form-remaja') || html.includes('Data Remaja') || html.includes('👤 Data Remaja');
            const hasBumilForm = html.includes('form-bumil') || html.includes('Data Ibu Hamil') || html.includes('🤱 Data Ibu Hamil');
            
            console.log('🔍 Form detection:');
            console.log('  - Balita form:', hasBalitaForm ? '✅ FOUND' : '❌ NOT FOUND');
            console.log('  - Remaja form:', hasRemajaForm ? '✅ FOUND' : '❌ NOT FOUND');
            console.log('  - Bumil form:', hasBumilForm ? '✅ FOUND' : '❌ NOT FOUND');
            
            // ✅ WAIT FOR HTML INJECTION THEN INITIALIZE
            setTimeout(() => {
                if (hasBalitaForm) {
                    console.log('🔄 Initializing balita components...');
                    
                    // ✅ INIT BALITA HANDLER (KALKULASI BB/TB)
                    if (typeof initializeBalitaHandler === 'function') {
                        console.log('📊 Calling initializeBalitaHandler...');
                        initializeBalitaHandler();
                    } else {
                        console.error('❌ initializeBalitaHandler not found');
                    }
                    
                    // ✅ INIT GEJALA SAKIT TOGGLE (DARI EXTERNAL JS)
                    if (typeof window.setupGejalaSakitToggleAfterAjax === 'function') {
                        console.log('🔄 Calling setupGejalaSakitToggleAfterAjax...');
                        window.setupGejalaSakitToggleAfterAjax();
                    } else {
                        console.error('❌ setupGejalaSakitToggleAfterAjax not found');
                    }
                }
                
                if (hasRemajaForm) {
                    console.log('🔄 Initializing remaja components...');
                    
                    // ✅ INIT REMAJA HANDLER (KALKULASI IMT, TEKANAN DARAH, dll)
                    if (typeof initializeRemajaHandler === 'function') {
                        console.log('📊 Calling initializeRemajaHandler...');
                        const success = initializeRemajaHandler();
                        if (success) {
                            console.log('✅ Remaja handler initialized successfully');
                        } else {
                            console.error('❌ Failed to initialize remaja handler');
                        }
                    } else {
                        console.error('❌ initializeRemajaHandler function not found in global scope');
                        console.log('Available functions:', Object.keys(window).filter(key => key.includes('remaja') || key.includes('Remaja')));
                    }
                } else {
                    console.log('ℹ️ No remaja form detected, skipping remaja initialization');
                }
                if (hasBumilForm) {
                    console.log('🔄 Initializing ibu hamil components...');
                    
                    // ✅ INIT IBU HAMIL HANDLER (KALKULASI BB, LILA, TEKANAN DARAH, TBC)
                    if (typeof initializeIbuHamilHandler === 'function') {
                        console.log('📊 Calling initializeIbuHamilHandler...');
                        const success = initializeIbuHamilHandler();
                        if (success) {
                            console.log('✅ Ibu hamil handler initialized successfully');
                        } else {
                            console.error('❌ Failed to initialize ibu hamil handler');
                        }
                    } else {
                        console.error('❌ initializeIbuHamilHandler function not found in global scope');
                        console.log('Available functions:', Object.keys(window).filter(key => key.includes('ibu') || key.includes('hamil') || key.includes('bumil')));
                    }
                } else {
                    console.log('ℹ️ No ibu hamil form detected, skipping ibu hamil initialization');
                }

                
            }, 200);
        })
        .catch(error => {
            console.error('💥 Search error:', error);
            showAlert(error.message, 'error');
            document.getElementById('form-pemeriksaan').innerHTML = '';
        })
        .finally(() => {
            btnCari.disabled = false;
            btnCari.innerHTML = '<i class="bi bi-search"></i> Cari';
        });
    };
    
    // ✅ SAFE EVENT LISTENER FOR ENTER KEY
    inputCari.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            btnCari.click();
        }
    });
    
    console.log('✅ Input pemeriksaan handlers attached successfully');
});
</script>
@endsection