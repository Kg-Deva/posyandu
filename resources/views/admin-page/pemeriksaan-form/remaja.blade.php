{{-- filepath: c:\laragon\www\posyandu\resources\views\admin-page\pemeriksaan-form\remaja.blade.php --}}

{{-- ✅ TAMBAHKAN CUSTOM STYLE UNTUK CARD --}}
<style>
.remaja-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
    padding: 24px;
    margin-bottom: 20px;
}

.remaja-card h6 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 16px;
}

.card-section {
    background: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 16px;
    border: 1px solid #dee2e6;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.psycho-item {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 10px;
    border-left: 4px solid #6c757d;
}

.psycho-item.concern {
    border-left-color: #dc3545;
    background: #fff5f5;
}

.psycho-item.safe {
    border-left-color: #198754;
    background: #f0fff4;
}
</style>

<form id="form-remaja" action="/simpan-pemeriksaan-remaja" method="POST">
    @csrf
    
    <input type="hidden" id="nik_remaja" name="nik" value="{{ $user->nik ?? '' }}">
    <input type="hidden" name="pemeriksa" value="{{ auth()->user()->name ?? 'System' }}">
    <input type="hidden" id="jenis_kelamin" name="jenis_kelamin" value="{{ $user->jenis_kelamin ?? '' }}">
    
    <!-- ✅ CARD IDENTITAS DENGAN STYLE BARU -->
    @if(isset($user))
    <div class="data mb-3">
        <div class="mb-2">
            <strong>👤 Data Remaja</strong>
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
        
        <!-- ✅ TAMBAH JENIS KELAMIN DI DISPLAY -->
        <div class="info-row">
            <span class="label">Kelamin</span>
            <span class="colon">:</span>
            <span class="value">{{ $user->jenis_kelamin ?? 'Belum diisi' }}</span>
        </div>
        
        <div class="info-row">
            <span class="label">Pemeriksa</span>
            <span class="colon">:</span>
            <span class="value">{{ auth()->user()->name ?? 'System' }}</span>
        </div>
    </div>
    @endif
    
    <!-- ✅ CARD FORM FIELDS UTAMA -->
    <div class="remaja-card">
        <h6>📋 Pemeriksaan Fisik</h6>
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
                    <input type="number" step="0.1" class="form-control" id="bb" name="bb" 
                           placeholder="Contoh: 65.5" required>
                </div>
                
                <div class="mb-3">
                    <label for="tb" class="form-label">Tinggi Badan (cm)</label>
                    <input type="number" step="0.1" class="form-control" id="tb" name="tb" 
                           placeholder="Contoh: 170.5" required>
                </div>

                <div class="mb-3">
                    <label for="lingkar_perut" class="form-label">Lingkar Perut (cm)</label>
                    <input type="number" step="0.1" class="form-control" id="lingkar_perut" name="lingkar_perut" 
                           placeholder="Contoh: 85.0" required>
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Ukur di bagian terkecil pinggang
                    </small>
                </div>

                <div class="mb-3">
                    <label for="sistole" class="form-label">Tekanan Darah Sistole (mmHg)</label>
                    <input type="number" class="form-control" id="sistole" name="sistole" 
                           placeholder="Contoh: 120" required>
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Tekanan darah atas
                    </small>
                </div>

                <div class="mb-3">
                    <label for="diastole" class="form-label">Tekanan Darah Diastole (mmHg)</label>
                    <input type="number" class="form-control" id="diastole" name="diastole" 
                           placeholder="Contoh: 80" required>
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Tekanan darah bawah
                    </small>
                </div>

                <div class="mb-3">
                    <label for="hb" class="form-label">Kadar Hemoglobin (mg/dl)</label>
                    <input type="number" step="0.1" class="form-control" id="hb" name="hb" 
                           placeholder="Contoh: 12.5" required>
                   
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Kesimpulan IMT</label>
                    <input type="text" class="form-control bg-light" id="kesimpulan_imt" name="kesimpulan_imt" readonly>
                    <div class="form-text">Otomatis terisi berdasarkan BB dan TB</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nilai IMT</label>
                    <input type="text" class="form-control bg-light" id="nilai_imt" name="nilai_imt" readonly>
                    <div class="form-text">BMI = BB (kg) / (TB (m))²</div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Kesimpulan Sistole</label>
                    <input type="text" class="form-control bg-light" id="kesimpulan_sistole" name="kesimpulan_sistole" readonly>
                    <div class="form-text">Status tekanan darah sistole</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kesimpulan Diastole</label>
                    <input type="text" class="form-control bg-light" id="kesimpulan_diastole" name="kesimpulan_diastole" readonly>
                    <div class="form-text">Status tekanan darah diastole</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status Anemia</label>
                    <input type="text" class="form-control bg-light" id="status_anemia" name="status_anemia" readonly>
                    <div class="form-text">Berdasarkan kadar Hemoglobin</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategori Tekanan Darah</label>
                    <input type="text" class="form-control bg-light" id="kategori_tekanan_darah" name="kategori_tekanan_darah" readonly>
                    <div class="form-text">Kombinasi sistole dan diastole</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ SKRINING TBC SECTION (4 ITEMS SEPERTI BALITA) -->
    <div class="remaja-card">
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
                    <label class="form-check-label" for="bb_tidak_naik">BB tidak naik/turun berturut-turut</label>
                </div>
                
                <div class="form-check mb-2">
                    <input class="form-check-input skrining-tbc" type="checkbox" id="kontak_tbc" 
                           name="kontak_tbc" value="1">
                    <label class="form-check-label" for="kontak_tbc">Kontak erat dengan pasien TBC</label>
                </div>
            </div>
        </div>
        
        <!-- HASIL TBC SIMPLE -->
        <div class="row mt-3">
            <div class="col-md-6">
                <label class="form-label">Jumlah Gejala TBC</label>
                <input type="text" class="form-control" id="jumlah_gejala_tbc" 
                       name="jumlah_gejala_tbc" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Status Rujukan</label>
                <input type="text" class="form-control" id="rujuk_puskesmas" 
                       name="rujuk_puskesmas" readonly>
            </div>
        </div>
    </div>

    <!-- ✅ NEW! SKRINING PSIKO-SOSIAL REMAJA -->
    <div class="remaja-card">
        <h6>🧠 Skrining Psiko-Sosial Remaja</h6>
        <p class="text-muted small mb-3">
            <i class="bi bi-info-circle me-1"></i>
            Jawab dengan jujur untuk evaluasi kesehatan mental dan sosial yang lebih baik
        </p>
        
        <div class="row">
            <div class="col-md-6">
                <!-- 1. Kenyamanan di rumah -->
                <div class="psycho-item mb-3">
                    <label class="form-label fw-semibold mb-2">
                        🏠 Apakah kamu merasa nyaman di rumah?
                    </label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input psycho-check" type="radio" 
                                   name="nyaman_dirumah" id="nyaman_ya" value="Ya">
                            <label class="form-check-label text-success" for="nyaman_ya">
                                <i class="bi bi-check-circle me-1"></i>Ya
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input psycho-check" type="radio" 
                                   name="nyaman_dirumah" id="nyaman_tidak" value="Tidak">
                            <label class="form-check-label text-danger" for="nyaman_tidak">
                                <i class="bi bi-x-circle me-1"></i>Tidak
                            </label>
                        </div>
                    </div>
                </div>

                <!-- 2. Masalah pendidikan/pekerjaan -->
                <div class="psycho-item mb-3">
                    <label class="form-label fw-semibold mb-2">
                        🎓 Memiliki masalah dengan pendidikan atau pekerjaan?
                    </label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input psycho-check" type="radio" 
                                   name="masalah_pendidikan" id="pendidikan_ya" value="Ya">
                            <label class="form-check-label text-warning" for="pendidikan_ya">
                                <i class="bi bi-exclamation-triangle me-1"></i>Ya
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input psycho-check" type="radio" 
                                   name="masalah_pendidikan" id="pendidikan_tidak" value="Tidak">
                            <label class="form-check-label text-success" for="pendidikan_tidak">
                                <i class="bi bi-check-circle me-1"></i>Tidak
                            </label>
                        </div>
                    </div>
                </div>

                <!-- 3. Masalah pola makan -->
                <div class="psycho-item mb-3">
                    <label class="form-label fw-semibold mb-2">
                        🍽️ Memiliki masalah dengan pola makan?
                    </label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input psycho-check" type="radio" 
                                   name="masalah_pola_makan" id="makan_ya" value="Ya">
                            <label class="form-check-label text-warning" for="makan_ya">
                                <i class="bi bi-exclamation-triangle me-1"></i>Ya
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input psycho-check" type="radio" 
                                   name="masalah_pola_makan" id="makan_tidak" value="Tidak">
                            <label class="form-check-label text-success" for="makan_tidak">
                                <i class="bi bi-check-circle me-1"></i>Tidak
                            </label>
                        </div>
                    </div>
                </div>

                <!-- 4. Masalah aktivitas -->
                <div class="psycho-item mb-3">
                    <label class="form-label fw-semibold mb-2">
                        🏃‍♂️ Memiliki masalah dengan aktivitas?
                    </label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input psycho-check" type="radio" 
                                   name="masalah_aktivitas" id="aktivitas_ya" value="Ya">
                            <label class="form-check-label text-warning" for="aktivitas_ya">
                                <i class="bi bi-exclamation-triangle me-1"></i>Ya
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input psycho-check" type="radio" 
                                   name="masalah_aktivitas" id="aktivitas_tidak" value="Tidak">
                            <label class="form-check-label text-success" for="aktivitas_tidak">
                                <i class="bi bi-check-circle me-1"></i>Tidak
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <!-- 5. Masalah obat-obatan -->
                <div class="psycho-item mb-3">
                    <label class="form-label fw-semibold mb-2">
                        💊 Memiliki masalah dengan obat-obatan?
                    </label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input psycho-check" type="radio" 
                                   name="masalah_obat" id="obat_ya" value="Ya">
                            <label class="form-check-label text-danger" for="obat_ya">
                                <i class="bi bi-exclamation-circle me-1"></i>Ya
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input psycho-check" type="radio" 
                                   name="masalah_obat" id="obat_tidak" value="Tidak">
                            <label class="form-check-label text-success" for="obat_tidak">
                                <i class="bi bi-check-circle me-1"></i>Tidak
                            </label>
                        </div>
                    </div>
                </div>

                <!-- 6. Masalah kesehatan seksual -->
                <div class="psycho-item mb-3">
                    <label class="form-label fw-semibold mb-2">
                        ❤️ Memiliki masalah dengan kesehatan seksual?
                    </label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input psycho-check" type="radio" 
                                   name="masalah_kesehatan_seksual" id="seksual_ya" value="Ya">
                            <label class="form-check-label text-warning" for="seksual_ya">
                                <i class="bi bi-exclamation-triangle me-1"></i>Ya
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input psycho-check" type="radio" 
                                   name="masalah_kesehatan_seksual" id="seksual_tidak" value="Tidak">
                            <label class="form-check-label text-success" for="seksual_tidak">
                                <i class="bi bi-check-circle me-1"></i>Tidak
                            </label>
                        </div>
                    </div>
                </div>

                <!-- 7. Masalah keamanan -->
                <div class="psycho-item mb-3">
                    <label class="form-label fw-semibold mb-2">
                        🛡️ Memiliki masalah dengan keamanan?
                    </label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input psycho-check" type="radio" 
                                   name="masalah_keamanan" id="keamanan_ya" value="Ya">
                            <label class="form-check-label text-danger" for="keamanan_ya">
                                <i class="bi bi-exclamation-circle me-1"></i>Ya
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input psycho-check" type="radio" 
                                   name="masalah_keamanan" id="keamanan_tidak" value="Tidak">
                            <label class="form-check-label text-success" for="keamanan_tidak">
                                <i class="bi bi-check-circle me-1"></i>Tidak
                            </label>
                        </div>
                    </div>
                </div>
                <div class="psycho-item mb-3">
                    <label class="form-label fw-semibold mb-2">
                        ⚠️ Memiliki keinginan bunuh diri/depresi?
                    </label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input psycho-check" type="radio" 
                                   name="masalah_keamanan" id="keamanan_ya" value="Ya">
                            <label class="form-check-label text-danger" for="keamanan_ya">
                                <i class="bi bi-exclamation-circle me-1"></i>Ya
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input psycho-check" type="radio" 
                                   name="masalah_keamanan" id="keamanan_tidak" value="Tidak">
                            <label class="form-check-label text-success" for="keamanan_tidak">
                                <i class="bi bi-check-circle me-1"></i>Tidak
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
    </div>

    <!-- ✅ CARD CATATAN -->
    <div class="remaja-card">
        <h6>📝 Edukasi / Konseling</h6>
        <div class="mb-3">
            <label for="catatan" class="form-label">Edukasi / Konseling (Opsional)</label>
            <textarea class="form-control" id="edukasi" name="edukasi" rows="3" 
                      placeholder="Tambahkan Edukasi atau Konseling jika diperlukan..."></textarea>
        </div>
    </div>

    <!-- ✅ CARD BUTTONS -->
    <div class="remaja-card">
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Simpan Data Pemeriksaan
            </button>
            <button type="reset" class="btn btn-secondary">
                <i class="bi bi-arrow-clockwise"></i> Reset Form
            </button>
        </div>
    </div>
</form>

<!-- ✅ STYLE -->
<link rel="stylesheet" href="{{ asset('css/input-pemeriksaan.css') }}">

