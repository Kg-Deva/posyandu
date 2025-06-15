<!-- filepath: c:\laragon\www\posyandu\resources\views\admin-page\pemeriksaan-form\balita.blade.php -->

<form id="form-balita" action="/simpan-pemeriksaan-balita" method="POST">
    @csrf
    
    <input type="hidden" id="nik_balita" name="nik" value="{{ $user->nik ?? '' }}">
    <!-- ✅ USE 'name' FIELD INSTEAD OF 'nama' -->
    <input type="hidden" name="pemeriksa" value="{{ auth()->user()->name ?? 'System' }}">
    
    @if(isset($user))
    <div class="data mb-3">
        <div class="mb-2">
            <strong>📋 Data Balita</strong>
        </div>
        
        <div class="info-row">
            <span class="label">NIK</span>
            <span class="colon">:</span>
            <span class="value">{{ $user->nik }}</span>
        </div>
    
        <div class="info-row">
            <span class="label">Nama</span>
            <span class="colon">:</span>
            <span class="value">{{ $user->nama }}</span>
        </div>
        
        <div class="info-row">
            <span class="label">Pemeriksa</span>
            <span class="colon">:</span>
            <span class="value">{{ auth()->user()->name ?? 'System' }}</span>
        </div>
    </div>

    <!-- ✅ TAMBAH COMPARISON CARD INI: -->
    <div id="bb-comparison-card" class="comparison-card mb-3" style="display: none;">
        <div class="d-flex align-items-center mb-2">
            <i class="bi bi-graph-up-arrow me-2"></i>
            <strong id="comparison-title">📊 Status Berat Badan</strong>
        </div>
        
        <!-- ✅ UNTUK PEMERIKSAAN PERTAMA -->
        <div id="first-time-content" style="display: none;">
            <div class="text-center py-3">
                <i class="bi bi-star-fill fs-3 mb-2"></i>
                <h5>Pemeriksaan Pertama</h5>
                <p class="mb-0">Ini adalah data awal untuk <strong>{{ $user->nama ?? 'Balita' }}</strong></p>
                <small class="text-muted">Data ini akan menjadi baseline untuk pemeriksaan selanjutnya</small>
            </div>
        </div>
        
        <!-- ✅ UNTUK PERBANDINGAN (ADA DATA SEBELUMNYA) -->
        <div id="comparison-content" style="display: none;">
            <div class="row">
                <div class="col-6">
                    <div class="comparison-item">
                        <small class="text-muted">Pemeriksaan Terakhir</small>
                        <div class="comparison-value">
                            <span id="bb-last">-</span> kg
                        </div>
                        <small class="text-muted" id="date-last">-</small>
                    </div>
                </div>
                
                <div class="col-6">
                    <div class="comparison-item">
                        <small class="text-muted">Berat Badan Hari Ini</small>
                        <div class="comparison-value">
                            <span id="bb-current">-</span> kg
                        </div>
                        <small class="text-muted">{{ date('d/m/Y') }}</small>
                    </div>
                </div>
            </div>
            
            <!-- ✅ SIMPLE STATUS: NAIK/TIDAK NAIK -->
            <div class="status-indicator mt-3 text-center">
                <div id="bb-status" class="status-badge waiting">
                    <i id="status-icon" class="bi bi-dash-circle"></i>
                    <span id="status-text">Tunggu input...</span>
                </div>
                <div id="bb-difference" class="difference-text mt-1">
                    <span class="difference-value">-</span>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- ✅ GANTI FORM FIELDS JADI INI: -->
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="tanggal_pemeriksaan" class="form-label">Tanggal Pemeriksaan</label>
                <input type="date" class="form-control" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan" 
                       value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                <div class="form-text">Tidak boleh lebih dari hari ini</div>
            </div>
            
            <div class="mb-3">
                <label for="bb" class="form-label">Berat Badan (kg)</label>
                <input type="number" step="0.01" class="form-control" id="bb" name="bb" required>
            </div>
            
            <div class="mb-3">
                <label for="tb" class="form-label">Tinggi/Panjang Badan (cm)</label>
                <input type="number" step="0.1" class="form-control" id="tb" name="tb" required>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="umur" class="form-label">Umur Balita (bulan)</label>
                <input type="number" class="form-control" id="umur" name="umur" 
                       placeholder="Masukkan umur dalam bulan" required>
                <div class="form-text">Konversi dari "{{ $user->umur ?? 'Data umur' }}"</div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Kesimpulan BB/U (0-5 tahun)</label>
                <input type="text" class="form-control bg-light" id="kesimpulan_bbu" name="kesimpulan_bbu" readonly>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Kesimpulan TB/U (0-5 tahun)</label>
                <input type="text" class="form-control bg-light" id="kesimpulan_tbuu" name="kesimpulan_tbuu" readonly>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Kesimpulan Hasil Pengukuran BB/TB</label>
        <input type="text" class="form-control bg-light" id="kesimpulan_bbtb" name="kesimpulan_bbtb" readonly>
    </div>

    <!-- ✅ TAMBAH FIELD STATUS PERUBAHAN BB BARU: -->
    <div class="mb-3">
        <label class="form-label">Status Perubahan Berat Badan</label>
        <input type="text" class="form-control bg-light" id="status_perubahan_bb" name="status_perubahan_bb" readonly>
        <div class="form-text">Otomatis terisi berdasarkan perbandingan dengan pemeriksaan sebelumnya</div>
    </div>

    <div class="mb-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Simpan Data Pemeriksaan
        </button>
        <button type="reset" class="btn btn-secondary">
            <i class="bi bi-arrow-clockwise"></i> Reset Form
        </button>
    </div>
</form>

<!-- ✅ TAMBAH STYLE DI BAWAH FORM: -->
<link rel="stylesheet" href="{{ asset('css/input-pemeriksaan.css') }}">

{{-- <script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔧 Balita form loading...');
    
    const form = document.getElementById('form-balita');
    
    if (form) {
        // ✅ PREVENT DOUBLE SUBMIT
        let isSubmitting = false;
        
        form.addEventListener('submit', function(e) {
            console.log('🚀 Form submitting...');
            
            // ✅ BLOCK DOUBLE SUBMIT
            if (isSubmitting) {
                e.preventDefault();
                console.log('❌ Already submitting, blocked!');
                return;
            }
            
            isSubmitting = true;
            
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-clock"></i> Menyimpan...';
            }
            
            // ✅ TAMBAH TIMEOUT RESET (SAFETY NET)
            setTimeout(function() {
                isSubmitting = false;
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-save"></i> Simpan Data Pemeriksaan';
                }
            }, 5000); // Reset after 5 seconds
        });
        
        // ✅ LISTEN FOR SUCCESS RESPONSE (OPTIONAL)
        form.addEventListener('submit', function() {
            // Check if form submission was successful after page reload
            setTimeout(function() {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('success') === 'true') {
                    // ✅ SIMPLE ALERT NOTIFICATION
                    alert('✅ Data berhasil disimpan!');
                }
            }, 100);
        });
    }
});
</script> --}}

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔧 Balita form loading...');
    
    const form = document.getElementById('form-balita');
    
    if (form) {
        // ✅ PREVENT DOUBLE SUBMIT - TETAP DI SINI
        let isSubmitting = false;
        
        form.addEventListener('submit', function(e) {
            console.log('🚀 Form submitting...');
            
            if (isSubmitting) {
                e.preventDefault();
                console.log('❌ Already submitting, blocked!');
                return;
            }
            
            isSubmitting = true;
            
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-clock"></i> Menyimpan...';
            }
            
            setTimeout(function() {
                isSubmitting = false;
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-save"></i> Simpan Data Pemeriksaan';
                }
            }, 5000);
        });
        
        form.addEventListener('submit', function() {
            setTimeout(function() {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('success') === 'true') {
                    alert('✅ Data berhasil disimpan!');
                }
            }, 100);
        });
    }
    
    // ✅ PANGGIL BALITA HANDLER UNTUK CALCULATION (SUDAH LOADED DI INPUT-PEMERIKSAAN)
    console.log('📊 Calling balita handler initialization...');
    if (window.initializeBalitaHandler) {
        window.initializeBalitaHandler();
        console.log('✅ Balita handler initialized successfully');
    } else {
        console.log('⚠️ Balita handler not found, will be initialized by observer');
    }
});
</script>