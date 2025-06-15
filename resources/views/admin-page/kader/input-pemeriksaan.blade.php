{{-- filepath: resources/views/admin-page/input-pemeriksaan.blade.php --}}
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

<script src="{{ asset('js/balita-handler.js') }}"></script>

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
            
            // ✅ DETECT BALITA FORM
            const hasBalitaForm = html.includes('form-balita') || html.includes('Berat Badan Balita');
            console.log('Form balita detected:', hasBalitaForm);
            
            if (hasBalitaForm) {
                console.log('🔄 Initializing balita handler...');
                
                // ✅ WAIT FOR HTML INJECTION THEN INITIALIZE
                setTimeout(() => {
                    if (typeof initializeBalitaHandler === 'function') {
                        console.log('📊 Calling initializeBalitaHandler...');
                        initializeBalitaHandler();
                    } else {
                        console.error('❌ initializeBalitaHandler not found');
                    }
                }, 200);
            }
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