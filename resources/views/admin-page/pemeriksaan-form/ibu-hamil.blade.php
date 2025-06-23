{{-- filepath: c:\laragon\www\posyandu\resources\views\admin-page\pemeriksaan-form\ibu-hamil.blade.php --}}

{{-- ✅ TAMBAHKAN CUSTOM STYLE UNTUK CARD --}}
<style>
.bumil-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
    padding: 24px;
    margin-bottom: 20px;
}

.bumil-card h6 {
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

.status-hijau {
    background-color: #d4edda !important;
    border-color: #c3e6cb !important;
    color: #155724 !important;
}

.status-merah {
    background-color: #f8d7da !important;
    border-color: #f5c6cb !important;
    color: #721c24 !important;
}
</style>

<form id="form-bumil" action="/simpan-pemeriksaan-bumil" method="POST">
    @csrf
    
    <input type="hidden" id="nik_bumil" name="nik" value="{{ $user->nik ?? '' }}">
    <input type="hidden" name="pemeriksa" value="{{ auth()->user()->name ?? 'System' }}">
    
    <!-- ✅ CARD IDENTITAS DENGAN STYLE BARU -->
    @if(isset($user))
    <div class="data mb-3">
        <div class="mb-2">
            <strong>🤱 Data Ibu Hamil</strong>
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
    @endif
    
    <!-- ✅ CARD FORM FIELDS UTAMA -->
    <div class="bumil-card">
        <h6>🩺 Pemeriksaan Ibu Hamil</h6>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="tanggal_pemeriksaan" class="form-label">Tanggal Pemeriksaan</label>
                    <input type="date" class="form-control" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan" 
                           value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                    <div class="form-text">Tidak boleh lebih dari hari ini</div>
                </div>
                
                <div class="mb-3">
                    <label for="usia_kehamilan_minggu" class="form-label">Usia Kehamilan (minggu ke-)</label>
                    <input type="number" class="form-control" id="usia_kehamilan_minggu" name="usia_kehamilan_minggu" 
                           placeholder="Contoh: 20" min="1" max="42" required>
                    <div class="form-text">Masukkan dalam satuan minggu (1-42)</div>
                </div>
                
                <div class="mb-3">
                    <label for="bb" class="form-label">Berat Badan (kg)</label>
                    <input type="number" step="0.1" class="form-control" id="bb" name="bb" 
                           placeholder="Contoh: 65.5" required>
                </div>

                <div class="mb-3">
                    <label for="lila" class="form-label">Lingkar Lengan Atas - LILA (cm)</label>
                    <input type="number" step="0.1" class="form-control" id="lila" name="lila" 
                           placeholder="Contoh: 24.5" required>
                    <div class="form-text">Standar: ≥ 23.5 cm (Normal), < 23.5 cm (Kurang Gizi)</div>
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
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Status BB Sesuai Kurva Buku KIA</label>
                    <input type="text" class="form-control" id="status_bb_kia" name="status_bb_kia" readonly>
                    <div class="form-text">Otomatis berdasarkan BB dan usia kehamilan</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status LILA</label>
                    <input type="text" class="form-control" id="status_lila" name="status_lila" readonly>
                    <div class="form-text">≥ 23.5 cm = Normal, < 23.5 cm = Kurang Gizi</div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Kesimpulan Sistole</label>
                    <input type="text" class="form-control" id="kesimpulan_sistole" name="kesimpulan_sistole" readonly>
                    <div class="form-text">Status tekanan darah sistole</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kesimpulan Diastole</label>
                    <input type="text" class="form-control" id="kesimpulan_diastole" name="kesimpulan_diastole" readonly>
                    <div class="form-text">Status tekanan darah diastole</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status Tekanan Darah Sesuai KIA</label>
                    <input type="text" class="form-control" id="status_td_kia" name="status_td_kia" readonly>
                    <div class="form-text">Sesuai kurva buku KIA</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status Rujukan</label>
                    <input type="text" class="form-control" id="status_rujukan" name="status_rujukan" readonly>
                    <div class="form-text">Jika merah = Rujuk ke Puskesmas</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ SKRINING TBC SECTION (4 ITEMS SEPERTI REMAJA) -->
    <div class="bumil-card">
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
                <label class="form-label">Status Rujukan TBC</label>
                <input type="text" class="form-control" id="rujuk_puskesmas_tbc" 
                       name="rujuk_puskesmas_tbc" readonly>
            </div>
        </div>
    </div>
<div class="bumil-card">
        <h6>💊 Pemberian Tablet Tambah Darah (TTD)</h6>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="jumlah_tablet_fe" class="form-label">Jumlah Tablet yang Diberikan</label>
                    <input type="number" class="form-control" id="jumlah_tablet_fe" name="jumlah_tablet_fe" 
                           placeholder="Contoh: 30" min="0">
                    <div class="form-text">Isikan 0 jika tidak diberikan tablet</div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="konsumsi_tablet_fe" class="form-label">Konsumsi Tablet Tambah Darah</label>
                    <select class="form-select" id="konsumsi_tablet_fe" name="konsumsi_tablet_fe">
                        <option value="">-- Pilih Kepatuhan Konsumsi --</option>
                        <option value="Setiap hari">Setiap hari</option>
                        <option value="Tidak setiap hari">Tidak setiap hari</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- EDUKASI TTD OTOMATIS -->
        <div class="row">
            <div class="col-md-12">
                <label class="form-label">Edukasi Tablet Tambah Darah</label>
                <textarea class="form-control bg-light" id="edukasi_tablet_fe" name="edukasi_tablet_fe" 
                          rows="2" readonly 
                          placeholder="Edukasi akan muncul otomatis berdasarkan pilihan konsumsi..."></textarea>
            </div>
        </div>
    </div>

    <!-- ✅ NEW! PEMBERIAN MAKANAN TAMBAHAN (MT) BUMIL KEK -->
    <div class="bumil-card">
        <h6>🍽️ Pemberian Makanan Tambahan (MT) Bumil KEK</h6>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="komposisi_mt_bumilkek" class="form-label">Komposisi MT Bumil KEK</label>
                    <textarea class="form-control" id="komposisi_mt_bumilkek" name="komposisi_mt_bumilkek" 
                              rows="2" placeholder="Contoh: Biskuit tinggi protein, susu formula ibu hamil, bubur kacang hijau"></textarea>
                    <div class="form-text">Sebutkan jenis makanan tambahan yang diberikan</div>
                </div>
                
                <div class="mb-3">
                    <label for="jumlah_porsi_mt" class="form-label">Jumlah Porsi yang Diberikan</label>
                    <input type="number" class="form-control" id="jumlah_porsi_mt" name="jumlah_porsi_mt" 
                           placeholder="Contoh: 7" min="0">
                    <div class="form-text">Jumlah porsi/kemasan (isikan 0 jika tidak diberikan)</div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="konsumsi_mt_bumilkek" class="form-label">Konsumsi MT Bumil KEK</label>
                    <select class="form-select" id="konsumsi_mt_bumilkek" name="konsumsi_mt_bumilkek">
                        <option value="">-- Pilih Kepatuhan Konsumsi --</option>
                        <option value="Setiap hari">Setiap hari</option>
                        <option value="Tidak setiap hari">Tidak setiap hari</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Edukasi MT Bumil KEK</label>
                    <textarea class="form-control bg-light" id="edukasi_mt_bumilkek" name="edukasi_mt_bumilkek" 
                              rows="2" readonly 
                              placeholder="Edukasi akan muncul otomatis berdasarkan pilihan konsumsi..."></textarea>
                </div>
            </div>
        </div>
    </div>
    <!-- ✅ CARD EDUKASI/KONSELING -->
    <div class="bumil-card">
        <h6>📝 Edukasi / Konseling</h6>
        <div class="mb-3">
            <label for="edukasi" class="form-label">Edukasi / Konseling (Opsional)</label>
            <textarea class="form-control" id="edukasi" name="edukasi" rows="3" 
                      placeholder="Tambahkan edukasi atau konseling untuk ibu hamil jika diperlukan..."></textarea>
        </div>
    </div>

    <!-- ✅ CARD BUTTONS -->
    <div class="bumil-card">
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

<!-- ✅ REFERENSI STANDAR MEDIS -->
<div class="card-section">
    <h6>📚 Referensi Standar Medis:</h6>
    <ul class="small mb-0">
        <li><strong>Berat Badan:</strong> Kurva pertambahan BB ibu hamil sesuai Buku KIA Kemenkes</li>
        <li><strong>LILA:</strong> WHO 2011 - Standar minimal 23.5 cm untuk ibu hamil</li>
        <li><strong>Tekanan Darah:</strong> Pedoman ANC Kemenkes 2020 (Normal: <140/90 mmHg)</li>
        <li><strong>Skrining TBC:</strong> Pedoman Kemenkes 2020 untuk ibu hamil</li>
        <li><strong>Rujukan:</strong> Jika ada parameter merah, segera rujuk ke Puskesmas</li>
    </ul>
</div>