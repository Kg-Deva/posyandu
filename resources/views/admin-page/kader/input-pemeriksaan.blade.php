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
<script src="{{ asset('js/dewasa-handler.js') }}"></script>
<script src="{{ asset('js/lansia-handler.js') }}"></script>
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
            const hasDewasaForm = html.includes('form-dewasa') || html.includes('Data Dewasa');
            const hasLansiaForm = html.includes('form-lansia') || html.includes('Data Lansia') || html.includes('👵 Data Lansia');
            
            console.log('🔍 Form detection:');
            console.log('  - Balita form:', hasBalitaForm ? '✅ FOUND' : '❌ NOT FOUND');
            console.log('  - Remaja form:', hasRemajaForm ? '✅ FOUND' : '❌ NOT FOUND');
            console.log('  - Bumil form:', hasBumilForm ? '✅ FOUND' : '❌ NOT FOUND');
            console.log('  - Dewasa form:', hasDewasaForm ? '✅ FOUND' : '❌ NOT FOUND');
            console.log('  - Lansia form:', hasLansiaForm ? '✅ FOUND' : '❌ NOT FOUND');
            
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
                if (hasDewasaForm) {
                    console.log('🔄 Initializing dewasa components...');
                    
                    // ✅ INIT DEWASA HANDLER (KALKULASI IMT, TEKANAN DARAH, dll)
                    if (typeof initializeDewasaHandler === 'function') {
                        console.log('📊 Calling initializeDewasaHandler...');
                        const success = initializeDewasaHandler();
                        if (success) {
                            console.log('✅ Dewasa handler initialized successfully');
                        } else {
                            console.error('❌ Failed to initialize dewasa handler');
                        }
                    } else {
                        console.error('❌ initializeDewasaHandler function not found in global scope');
                        console.log('Available functions:', Object.keys(window).filter(key => key.includes('dewasa') || key.includes('Dewasa')));
                    }
                } else {
                    console.log('ℹ️ No dewasa form detected, skipping dewasa initialization');
                }
                if (hasLansiaForm) {
                    console.log('🔄 Initializing lansia components...');
                    
                    // ✅ INIT LANSIA HANDLER (KALKULASI IMT, TEKANAN DARAH, dll)
                    if (typeof initializeLansiaHandler === 'function') {
                        console.log('📊 Calling initializeLansiaHandler...');
                        const success = initializeLansiaHandler();
                        if (success) {
                            console.log('✅ Lansia handler initialized successfully');
                        } else {
                            console.error('❌ Failed to initialize lansia handler');
                        }
                    } else {
                        console.error('❌ initializeLansiaHandler function not found in global scope');
                        console.log('Available functions:', Object.keys(window).filter(key => key.includes('lansia') || key.includes('Lansia')));
                    }
                } else {
                    console.log('ℹ️ No lansia form detected, skipping lansia initialization');
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

<!-- 
|--------------------------------------------------------------------------
| Start Modal Skrining Tahunan untuk Lansia
|--------------------------------------------------------------------------
-->


<div class="modal fade" id="skriningTahunanModal" tabindex="-1" aria-labelledby="skriningTahunanLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" id="modal-skrining-tahunan-content">
      <!-- AJAX akan load konten di sini -->
    </div>
  </div>
</div>

{{-- modal skrining tahunan untuk lansia --}}
<script>
function hitungSkorSkriningLansia() {
    let total = 0;
    let filled = 0;
    const form = document.getElementById('form-skrining-tahunan-lansia');
    if (!form) return;
    const inputs = form.querySelectorAll('.skor-input');
    inputs.forEach(function (el) {
        if (el.value !== "") {
            total += parseInt(el.value) || 0;
            filled++;
        }
    });

    if (filled === inputs.length) {
        document.getElementById('total-skor').value = total;

        let status = '';
        if (total === 20) status = 'Mandiri (M)';
        else if (total >= 15 && total <= 19) status = 'Ringan (R)';
        else if (total >= 11 && total <= 14) status = 'Sedang (S)';
        else if (total >= 6 && total <= 10) status = 'Berat (B)';
        else if (total >= 0 && total <= 5) status = 'Total (T)';
        document.getElementById('status-kemandirian').value = status;

        document.getElementById('rujuk-field').style.display = (total < 20) ? 'block' : 'none';
        updateStatusRujukanLansia(); // Tambahkan ini
    } else {
        document.getElementById('total-skor').value = '';
        document.getElementById('status-kemandirian').value = '';
        document.getElementById('rujuk-field').style.display = 'none';
    }
}

// Jalankan setiap kali modal skrining tahunan lansia ditampilkan
document.addEventListener('shown.bs.modal', function(e) {
    if (e.target.id === 'skriningTahunanModal') {
        const form = document.getElementById('form-skrining-tahunan-lansia');
        if (form) {
            form.querySelectorAll('.skor-input').forEach(function (el) {
                el.removeEventListener('change', hitungSkorSkriningLansia);
                el.addEventListener('change', hitungSkorSkriningLansia);
            });
            hitungSkorSkriningLansia();
        }
    }
});
</script>

{{-- SCRIPT KESEHATAN MENTAL --}}
<script>
document.addEventListener('shown.bs.modal', function(e) {
    if (e.target.id === 'skriningTahunanModal') {
        const form = document.getElementById('form-skrining-tahunan-lansia');
        if (form) {
            const mentalInputs = form.querySelectorAll('.skor-mental');
            function hitungSkorMental() {
                let total = 0;
                let adaPikiranAkhiriHidup = false;
                mentalInputs.forEach(function(el) {
                    if (el.value !== "") {
                        total += parseInt(el.value) || 0;
                        if (el.name.includes('pikiran_akhiri_hidup') && el.value === "1") {
                            adaPikiranAkhiriHidup = true;
                        }
                    }
                });
                document.getElementById('total-skor-mental').value = total;
                // Tampilkan rujukan jika skor >= 6 atau ada pikiran mengakhiri hidup
                document.getElementById('rujuk-mental-field').style.display = (total >= 6 || adaPikiranAkhiriHidup) ? 'block' : 'none';
                updateStatusRujukanLansia(); // Tambahkan ini
            }
            mentalInputs.forEach(function(el) {
                el.removeEventListener('change', hitungSkorMental);
                el.addEventListener('change', hitungSkorMental);
            });
            hitungSkorMental();
        }
    }
});
</script>

{{-- SCRIPT SKILASSSSSS --}}
<script>
document.addEventListener('shown.bs.modal', function(e) {
    if (e.target.id === 'skriningTahunanModal') {
        const form = document.getElementById('form-skrining-tahunan-lansia');
        if (form) {
            const skilasInputs = form.querySelectorAll('.skor-skilas');
            function cekRujukSkilas() {
                let perluRujuk = false;
                skilasInputs.forEach(function(el) {
                    if (el.value === "1") {
                        perluRujuk = true;
                    }
                });
                document.getElementById('rujuk-skilas-field').style.display = perluRujuk ? 'block' : 'none';
                updateStatusRujukanLansia(); // Tambahkan ini
            }
            skilasInputs.forEach(function(el) {
                el.removeEventListener('change', cekRujukSkilas);
                el.addEventListener('change', cekRujukSkilas);
            });
            cekRujukSkilas();
        }
    }
});

function updateStatusRujukanLansia() {
    const rujukAks = document.getElementById('rujuk-field')?.style.display === 'block';
    const rujukMental = document.getElementById('rujuk-mental-field')?.style.display === 'block';
    const rujukSkilas = document.getElementById('rujuk-skilas-field')?.style.display === 'block';
    const status = (rujukAks || rujukMental || rujukSkilas) ? 'Dirujuk' : 'Tidak Dirujuk';
    document.getElementById('status-rujukan').value = status;
}
</script>

{{-- /*
|--------------------------------------------------------------------------
|   End Modal Skrining Tahunan untuk Lansia
|--------------------------------------------------------------------------
*/ --}}


{{-- <script>
    function hitungSkorSkriningDewasa() {
    let total = 0;
    let filled = 0;
    const form = document.getElementById('form-skrining-tahunan-dewasa');
    if (!form) return;
    const inputs = form.querySelectorAll('.skor-input');
    inputs.forEach(function (el) {
        if (el.value !== "") {
            total += parseInt(el.value) || 0;
            filled++;
        }
    });

    if (filled === inputs.length) {
        document.getElementById('total-skor').value = total;

        let status = '';
        if (total === 23) status = 'Mandiri (M)';
        else if (total >= 15 && total <= 22) status = 'Ringan (R)';
        else if (total >= 11 && total <= 14) status = 'Sedang (S)';
        else if (total >= 6 && total <= 10) status = 'Berat (B)';
        else if (total >= 0 && total <= 5) status = 'Total (T)';
        document.getElementById('status-kemandirian').value = status;

        document.getElementById('rujuk-field').style.display = (total < 23) ? 'block' : 'none';
    } else {
        document.getElementById('total-skor').value = '';
        document.getElementById('status-kemandirian').value = '';
        document.getElementById('rujuk-field').style.display = 'none';
    }
}
// Jalankan setiap kali modal skrining tahunan dewasa ditampilkan
document.addEventListener('shown.bs.modal', function(e) {
    if (e.target.id === 'skriningTahunanModalDewasa') {
        const form = document.getElementById('form-skrining-tahunan-dewasa');
        if (form) {
            form.querySelectorAll('.skor-input').forEach(function (el) {
                el.removeEventListener('change', hitungSkorSkriningDewasa);
                el.addEventListener('change', hitungSkorSkriningDewasa);
            });
            hitungSkorSkriningDewasa();
        }
    }
});
</script> --}}


{{-- /*
|--------------------------------------------------------------------------
|   Start Modal Skrining Tahunan untuk Dewasa
|--------------------------------------------------------------------------
*/ --}}

{{-- SCRIPT KESEHATAN MENTAL --}}
<script>
document.addEventListener('shown.bs.modal', function(e) {
    if (e.target.id === 'skriningTahunanModalDewasa') { // Pastikan id modal sesuai
        const form = document.getElementById('form-skrining-tahunan-dewasa');
        if (form) {
            const mentalInputs = form.querySelectorAll('.skor-mental');
            function hitungSkorMental() {
                let total = 0;
                let adaPikiranAkhiriHidup = false;
                mentalInputs.forEach(function(el) {
                    if (el.value !== "") {
                        total += parseInt(el.value) || 0;
                        if (el.name.includes('pikiran_akhiri_hidup') && el.value === "1") {
                            adaPikiranAkhiriHidup = true;
                        }
                    }
                });
                document.getElementById('total-skor-mental').value = total;
                // Tampilkan rujukan jika skor >= 6 atau ada pikiran mengakhiri hidup
                document.getElementById('rujuk-mental-field').style.display = (total >= 6 || adaPikiranAkhiriHidup) ? 'block' : 'none';
                // Isi input hidden status rujukan (buat dulu di form)
                let statusInput = document.getElementById('status-rujukan');
                if (!statusInput) {
                    statusInput = document.createElement('input');
                    statusInput.type = 'hidden';
                    statusInput.name = 'status_rujukan';
                    statusInput.id = 'status-rujukan';
                    form.appendChild(statusInput);
                }
                statusInput.value = (total >= 6 || adaPikiranAkhiriHidup) ? 'Dirujuk' : 'Tidak Dirujuk';
            }
            mentalInputs.forEach(function(el) {
                el.removeEventListener('change', hitungSkorMental);
                el.addEventListener('change', hitungSkorMental);
            });
            hitungSkorMental();
        }
    }
});
</script>

{{-- modal skrining dewasa --}}
<div class="modal fade" id="skriningTahunanModalDewasa" tabindex="-1" aria-labelledby="skriningTahunanLabelDewasa" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" id="modal-skrining-tahunan-content-dewasa">
      <!-- AJAX akan load konten di sini -->
    </div>
  </div>
</div>




@endsection

