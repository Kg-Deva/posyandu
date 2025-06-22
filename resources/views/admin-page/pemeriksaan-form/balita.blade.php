<!-- filepath: c:\laragon\www\posyandu\resources\views\admin-page\pemeriksaan-form\balita.blade.php -->

<form id="form-balita" action="/simpan-pemeriksaan-balita" method="POST">
    @csrf
    
    <input type="hidden" id="nik_balita" name="nik" value="{{ $user->nik ?? '' }}">
    <!-- ✅ USE 'name' FIELD INSTEAD OF 'nama' -->
    <input type="hidden" name="pemeriksa" value="{{ auth()->user()->name ?? 'System' }}">
    
    <!-- ✅ CARD IDENTITAS TETAP BAGUS (JANGAN DIUBAH): -->
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

    <!-- ✅ COMPARISON CARD TETAP BAGUS (JANGAN DIUBAH): -->
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
    
    <!-- ✅ FORM FIELDS TETAP BAGUS (JANGAN DIUBAH): -->
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
            <div class="mb-3">
                <label for="umur" class="form-label">Umur Balita (bulan)</label>
                <input type="number" class="form-control" id="umur" name="umur" 
                       placeholder="Masukkan umur dalam bulan" required>
                {{-- <div class="form-text">Konversi dari "{{ $user->umur ?? 'Data umur' }}"</div> --}}
            </div>
            <div class="mb-3">
                <label for="lingkar_kepala" class="form-label">Lingkar Kepala (Cm)</label>
                <input type="number" step="0.01" class="form-control" id="lingkar_kepala" name="lingkar_kepala" required>
                <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i>
                    Ukur keliling kepala
                </small>
            </div>
            <div class="mb-3">
                <label for="lila" class="form-label">LILA - Lingkar Lengan Atas (Cm)</label>
                <input type="number" step="0.1" class="form-control" id="lila" name="lila">
                <small class="text-muted" id="lila-info">
                    <i class="bi bi-info-circle me-1"></i>
                    <span id="lila-status">Tunggu input umur untuk validasi...</span>
                </small>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Kesimpulan BB/U (0-5 tahun)</label>
                <input type="text" class="form-control bg-light" id="kesimpulan_bbu" name="kesimpulan_bbu" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Kesimpulan TB/U (0-5 tahun)</label>
                <input type="text" class="form-control bg-light" id="kesimpulan_tbuu" name="kesimpulan_tbuu" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Kesimpulan Hasil Pengukuran BB/TB</label>
                <input type="text" class="form-control bg-light" id="kesimpulan_bbtb" name="kesimpulan_bbtb" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Status Perubahan Berat Badan</label>
                <input type="text" class="form-control bg-light" id="status_perubahan_bb" name="status_perubahan_bb" readonly>
                <div class="form-text">Otomatis terisi berdasarkan perbandingan dengan pemeriksaan sebelumnya</div>
            </div>
             <div class="mb-3">
                <label class="form-label">Kesimpulan Lingkar Kepala</label>
                <input type="text" class="form-control bg-light" id="kesimpulan_lingkar_kepala" name="kesimpulan_lingkar_kepala" readonly>
                <div class="form-text"> Kesimpulan otomatis berdasarkan input lingkar kepala</div>
            </div>
            <div class="mb-3">
                <label class="form-label">Kesimpulan LILA</label>
                <input type="text" class="form-control bg-light" id="kesimpulan_lila" name="kesimpulan_lila" readonly>
                <div class="form-text" id="kesimpulan-lila-info">Kesimpulan otomatis berdasarkan input LILA</div>
            </div>
        </div>
    </div>

   

    <!-- ✅ 1. BALITA MENDAPATKAN SECTION (SIMPLE): -->
    <div class="mb-4">
        <h6>🍼 Balita Mendapatkan</h6>
        <div class="row">
            <div class="col-md-6">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="asi_eksklusif" name="asi_eksklusif" value="1">
                    <label class="form-check-label" for="asi_eksklusif">ASI Eksklusif</label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="mp_asi" name="mp_asi" value="1">
                    <label class="form-check-label" for="mp_asi">MP ASI</label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="imunisasi" name="imunisasi" value="1">
                    <label class="form-check-label" for="imunisasi">Imunisasi</label>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="vitamin_a" name="vitamin_a" value="1">
                    <label class="form-check-label" for="vitamin_a">Vitamin A</label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="obat_cacing" name="obat_cacing" value="1">
                    <label class="form-check-label" for="obat_cacing">Obat Cacing</label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="mt_pangan_lokal" name="mt_pangan_lokal" value="1">
                    <label class="form-check-label" for="mt_pangan_lokal">MT Pangan Lokal untuk Pemulihan</label>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ 2. GEJALA SAKIT SECTION (SIMPLE): -->
    <div class="mb-4">
        <h6>Gejala Sakit</h6>
        <div class="row">
            <div class="col-md-3">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="ada_gejala_sakit" name="ada_gejala_sakit" value="1">
                    <label class="form-check-label" for="ada_gejala_sakit">Ada Gejala Sakit</label>
                </div>
            </div>
            <div class="col-md-9">
                <div class="mb-3">
                    <label for="sebutkan_gejala" class="form-label">Sebutkan Gejala (jika ada)</label>
                    <textarea class="form-control" id="sebutkan_gejala" name="sebutkan_gejala" rows="2" 
                              placeholder="Centang 'Ada Gejala Sakit' untuk mengisi..." 
                              style="background-color: #f8f9fa; cursor: not-allowed;" 
                              disabled></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ 3. EDUKASI/KONSELING SECTION (SIMPLE): -->
    <div class="mb-4">
        <h6>📚 Edukasi/Konseling</h6>
        <div class="mb-3">
            <label for="mp_asi_protein_hewani" class="form-label">Jika memberikan MP ASI kaya protein hewani, sebutkan</label>
            <textarea class="form-control" id="mp_asi_protein_hewani" name="mp_asi_protein_hewani" rows="2" 
                      placeholder="Contoh: telur, ikan, daging ayam, hati ayam, dll..."></textarea>
            <div class="form-text">Kosongkan jika tidak memberikan MP ASI protein hewani</div>
        </div>
    </div>

    <!-- ✅ 4. SKRINING TBC SECTION (SIMPLE KAYAK YANG LAIN): -->
    <div class="mb-4">
        <h6>🔍 Skrining Gejala TBC</h6>
        <div class="row">
            <div class="col-md-6">
                <div class="form-check mb-2">
                    <input class="form-check-input skrining-tbc" type="checkbox" id="batuk_terus_menerus" 
                           name="batuk_terus_menerus" value="1">
                    <label class="form-check-label" for="batuk_terus_menerus">Batuk terus menerus</label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input skrining-tbc" type="checkbox" id="demam_2_minggu" 
                           name="demam_2_minggu" value="1">
                    <label class="form-check-label" for="demam_2_minggu">Demam lebih dari 2 minggu</label>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-check mb-2">
                    <input class="form-check-input skrining-tbc" type="checkbox" id="bb_tidak_naik" 
                           name="bb_tidak_naik" value="1">
                    <label class="form-check-label" for="bb_tidak_naik">BB tidak naik/turun 2 bulan berturut-turut</label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input skrining-tbc" type="checkbox" id="kontak_tbc" 
                           name="kontak_tbc" value="1">
                    <label class="form-check-label" for="kontak_tbc">Kontak erat dengan pasien TBC</label>
                </div>
            </div>
        </div>
        
        <!-- ✅ HASIL TBC SIMPLE: -->
        <div class="row mt-3">
            <div class="col-md-6">
                <label class="form-label">Jumlah Gejala TBC</label>
                <input type="text" class="form-control bg-light" id="jumlah_gejala_tbc" 
                       name="jumlah_gejala_tbc" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Status Rujukan</label>
                <input type="text" class="form-control bg-light" id="rujuk_puskesmas" 
                       name="rujuk_puskesmas" readonly>
            </div>
        </div>
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

{{-- ✅ SCRIPT MINIMAL - CUMA FORM SUBMIT & BALITA HANDLER: --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔧 Balita form script loading...');
    
    // ✅ FORM SUBMIT HANDLER SAJA
    const form = document.getElementById('form-balita');
    if (form) {
        let isSubmitting = false;
        form.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
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
    }
    
    // ✅ PANGGIL BALITA HANDLER SAJA
    if (window.initializeBalitaHandler) {
        window.initializeBalitaHandler();
    }
    
    console.log('🚀 Balita form script completed');
});
</script>

{{-- khusus untuk lingkar kepala --}}
