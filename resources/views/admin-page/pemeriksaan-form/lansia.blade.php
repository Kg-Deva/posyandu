{{-- filepath: resources/views/admin-page/pemeriksaan-form/lansia.blade.php --}}

<style>
.lansia-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
    padding: 24px;
    margin-bottom: 20px;
}
.lansia-card h6 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 16px;
}
</style>

<form id="form-lansia" action="/simpan-pemeriksaan-lansia" method="POST">
    @csrf

    @if(isset($user))
    
        <div class="data mb-3">
            <div class="mb-2">
                <strong>🧓 Data lansia</strong>
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
        <input type="hidden" id="jenis_kelamin" name="jenis_kelamin" value="{{ $user->jenis_kelamin ?? '' }}">
        <input type="hidden" name="nik" value="{{ $user->nik }}"> <!-- ✅ TAMBAH ini -->
        <input type="hidden" name="pemeriksa" value="{{ auth()->user()->name ?? 'System' }}">
        <div class="lansia-card">
            <h6>🩺 Pemeriksaan Fisik lansia</h6>
            <div class="row">
                <div class="col-md-6">
                    <!-- Tanggal Pemeriksaan -->
                    <div class="mb-3">
                        <label for="tanggal_pemeriksaan" class="form-label">Tanggal Pemeriksaan</label>
                        <input type="date" class="form-control" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan"
                            value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                        <div class="form-text">Tidak boleh lebih dari hari ini</div>
                    </div>
                    <!-- Berat Badan -->
                    <div class="mb-3">
                        <label for="bb" class="form-label">Berat Badan (kg)</label>
                        <input type="number" step="0.1" class="form-control" id="bb" name="bb" required>
                    </div>
                    <!-- Tinggi Badan -->
                    <div class="mb-3">
                        <label for="tb" class="form-label">Tinggi Badan (cm)</label>
                        <input type="number" step="0.1" class="form-control" id="tb" name="tb" required>
                    </div>
                    <!-- Lingkar Perut -->
                    <div class="mb-3">
                        <label for="lingkar_perut" class="form-label">Lingkar Perut (cm)</label>
                        <input type="number" step="0.1" class="form-control" id="lingkar_perut" name="lingkar_perut" required>
                    </div>
                    <!-- Tekanan Darah -->
                    <div class="mb-3">
                        <label for="sistole" class="form-label">Tekanan Darah Sistole (mmHg)</label>
                        <input type="number" class="form-control" id="sistole" name="sistole" required>
                    </div>
                
                    <div class="mb-3">
                        <label for="diastole" class="form-label">Tekanan Darah Diastole (mmHg)</label>
                        <input type="number" class="form-control" id="diastole" name="diastole" required>
                    </div>
                
                    <!-- Gula Darah -->
                    <div class="mb-3">
                        <label for="gula_darah" class="form-label">Gula Darah (mg/dL)</label>
                        <input type="number" step="0.1" class="form-control" id="gula_darah" name="gula_darah" required>
                        <div class="form-text">Isi hasil pemeriksaan gula darah sewaktu</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- IMT -->
                    <div class="mb-3">
                        <label class="form-label">IMT (Indeks Massa Tubuh)</label>
                        <input type="text" class="form-control bg-light" id="imt" name="imt" readonly>
                        <div class="form-text">Otomatis: BB (kg) / (TB (m))²</div>
                    </div>
                    <!-- Kesimpulan IMT -->
                    <div class="mb-3">
                        <label class="form-label">Kesimpulan IMT</label>
                        <input type="text" class="form-control bg-light" id="kesimpulan_imt" name="kesimpulan_imt" readonly>
                        <div class="form-text">Sangat Kurus, Kurus, Normal, Gemuk, Obesitas</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kesimpulan Sistole</label>
                        <input type="text" class="form-control bg-light" id="kesimpulan_sistole" name="kesimpulan_sistole" readonly>
                        <div class="form-text">Rendah, Normal, Tinggi</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kesimpulan Diastole</label>
                        <input type="text" class="form-control bg-light" id="kesimpulan_diastole" name="kesimpulan_diastole" readonly>
                        <div class="form-text">Rendah, Normal, Tinggi</div>
                    </div>
                    <!-- Kesimpulan Tekanan Darah -->
                    <div class="mb-3">
                        <label class="form-label">Kesimpulan Tekanan Darah</label>
                        <input type="text" class="form-control bg-light" id="kesimpulan_td" name="kesimpulan_td" readonly>
                        <div class="form-text">Hipotensi, Normal, Hipertensi</div>
                    </div>
                    <!-- Kesimpulan Gula Darah -->
                    <div class="mb-3">
                        <label class="form-label">Kesimpulan Gula Darah</label>
                        <input type="text" class="form-control bg-light" id="kesimpulan_gula_darah" name="kesimpulan_gula_darah" readonly>
                        <div class="form-text">Rendah, Normal, Tinggi</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lansia-card">
            <h6>👁️ Tes Hitung Jari & Tes Berbisik</h6>
            <div class="row">
                <div class="col-md-6">
                    <!-- Tes Hitung Jari -->
                    <div class="mb-3">
                        <label class="form-label">Tes Hitung Jari (Mata Kanan)</label>
                        <select class="form-select" id="tes_jari_kanan" name="tes_jari_kanan" required>
                            <option value="">-- Pilih Hasil --</option>
                            <option value="Normal">Normal</option>
                            <option value="Gangguan">Gangguan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tes Hitung Jari (Mata Kiri)</label>
                        <select class="form-select" id="tes_jari_kiri" name="tes_jari_kiri" required>
                            <option value="">-- Pilih Hasil --</option>
                            <option value="Normal">Normal</option>
                            <option value="Gangguan">Gangguan</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Tes Berbisik -->
                    <div class="mb-3">
                        <label class="form-label">Tes Berbisik (Telinga Kanan)</label>
                        <select class="form-select" id="tes_berbisik_kanan" name="tes_berbisik_kanan" required>
                            <option value="">-- Pilih Hasil --</option>
                            <option value="Normal">Normal</option>
                            <option value="Gangguan">Gangguan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tes Berbisik (Telinga Kiri)</label>
                        <select class="form-select" id="tes_berbisik_kiri" name="tes_berbisik_kiri" required>
                            <option value="">-- Pilih Hasil --</option>
                            <option value="Normal">Normal</option>
                            <option value="Gangguan">Gangguan</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

      


    <div class="lansia-card">
        <h6>📝 Skrining PUMA (Pneumonia & Penyakit Paru)</h6>
        <div class="row">
            <div class="col-md-6">
                <!-- Jenis Kelamin -->
                <div class="mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <input type="text" class="form-control bg-light" value="{{ $user->jenis_kelamin }}" readonly>
                    <input type="hidden" id="puma_jk" name="puma_jk" value="{{ ($user->jenis_kelamin ?? '') == 'Laki-laki' ? 1 : 0 }}">
                </div>
                <!-- Usia -->
                <div class="mb-3">
                    <label class="form-label">Usia</label>
                    <select class="form-select" id="puma_usia" name="puma_usia" required>
                        <option value="">-- Pilih --</option>
                        <option value="0">40-49 tahun</option>
                        <option value="1">50-59 tahun</option>
                        <option value="2">≥ 60 tahun</option>
                    </select>
                </div>
                <!-- Merokok -->
                <div class="mb-3">
                    <label class="form-label">Kebiasaan Merokok</label>
                    <select class="form-select" id="puma_rokok" name="puma_rokok" required>
                        <option value="">-- Pilih --</option>
                        <option value="0">Tidak</option>
                        <option value="0">Kurang dari 20 bungkus</option>
                        <option value="1">20-30 bungkus</option>
                        <option value="2">≥ 30 bungkus</option>
                    </select>
                </div>
                <!-- Napas Pendek -->
                <div class="mb-3">
                    <label class="form-label">Pernah merasa napas pendek saat berjalan lebih cepat di jalan datar/menanjak?</label>
                    <select class="form-select" id="puma_napas" name="puma_napas" required>
                        <option value="">-- Pilih --</option>
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Dahak Paru -->
                <div class="mb-3">
                    <label class="form-label">Punya dahak dari paru atau kesulitan mengeluarkan dahak saat tidak flu?</label>
                    <select class="form-select" id="puma_dahak" name="puma_dahak" required>
                        <option value="">-- Pilih --</option>
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                    </select>
                </div>
                <!-- Batuk tanpa flu -->
                <div class="mb-3">
                    <label class="form-label">Batuk saat tidak sedang flu?</label>
                    <select class="form-select" id="puma_batuk" name="puma_batuk" required>
                        <option value="">-- Pilih --</option>
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                    </select>
                </div>
                <!-- Pemeriksaan spirometri/peakflow -->
                <div class="mb-3">
                    <label class="form-label">Pernah diminta tes spirometri/peakflow meter?</label>
                    <select class="form-select" id="puma_spirometri" name="puma_spirometri" required>
                        <option value="">-- Pilih --</option>
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                    </select>
                </div>
                <!-- Skor & Status -->
                <div class="mb-3">
                    <label class="form-label">Skor PUMA</label>
                    <input type="text" class="form-control bg-light" id="skor_puma" name="skor_puma" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status PUMA</label>
                    <input type="text" class="form-control bg-light" id="status_puma" name="status_puma" readonly>
                    <div class="form-text">Jika skor &gt; 6: Rujuk ke Puskesmas, jika ≤ 6: Aman</div>
                </div>
            </div>
        </div>
    </div>
    <div class="lansia-card">
        <h6>🦠 Skrining TBC lansia</h6>
        <div class="row">
            <div class="col-md-6">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="tbc_batuk" name="tbc_batuk">
                    <label class="form-check-label" for="tbc_batuk">Batuk terus menerus</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="tbc_demam" name="tbc_demam">
                    <label class="form-check-label" for="tbc_demam">Demam lebih dari 2 minggu</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="tbc_bb_turun" name="tbc_bb_turun">
                    <label class="form-check-label" for="tbc_bb_turun">BB tidak naik/turun 2 bulan berturut-turut</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="tbc_kontak" name="tbc_kontak">
                    <label class="form-check-label" for="tbc_kontak">Kontak erat dengan pasien TBC</label>
                </div>
            </div>
        </div>
        <div class="mb-2 mt-2">
            <label class="form-label">Status Skrining TBC</label>
            <input type="text" class="form-control bg-light" id="status_tbc" name="status_tbc" readonly>
            <div class="form-text">Jika ≥2 gejala: Rujuk ke Puskesmas</div>
        </div>
    </div>
    <div class="lansia-card">
        <h6>💊 Penggunaan Alat Kontrasepsi</h6>
        <div class="mb-3">
            <label class="form-label">Apakah menggunakan alat kontrasepsi (pil/kondom/lainnya)?</label>
            <select class="form-select" id="alat_kontrasepsi" name="alat_kontrasepsi" required>
                <option value="">-- Pilih --</option>
                <option value="1">Ya</option>
                <option value="0">Tidak</option>
            </select>
        </div>
    </div>
    <div class="lansia-card">
        <h6>📚 Edukasi/Konseling</h6>
        <div class="mb-3">
            <label for="edukasi" class="form-label">Edukasi/Konseling yang Diberikan</label>
            <textarea class="form-control" id="edukasi" name="edukasi" rows="2"
                placeholder="Isi edukasi/konseling yang diberikan..."></textarea>
            <div class="form-text">Wajib diisi jika status rujuk</div>
        </div>
    </div>
      <div class="lansia-card">
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
{{-- SKRINING TAHUNAN --}}

{{-- 
| sintaks untuk button disabled 
| jika sudah pernah skrining tahunan
--}}

@if(!$skriningTerakhir || \Carbon\Carbon::parse($skriningTerakhir->tanggal_pemeriksaan)->diffInDays(now()) >= 365)
    <button type="button" class="btn btn-success mb-3" id="btn-skrining-tahunan"
        data-user-id="{{ $user->id }}"
        data-terakhir="{{ $skriningTerakhir ? $skriningTerakhir->tanggal_pemeriksaan : '' }}">
        Skrining Tahunan
    </button>
    <div id="skrining-warning" class="text-danger small"></div>
@endif
@if($skriningTerakhir && \Carbon\Carbon::parse($skriningTerakhir->tanggal_pemeriksaan)->diffInDays(now()) < 365)
    <div class="alert alert-warning">
        Sudah skrining, bisa skrining lagi setelah {{ 365 - \Carbon\Carbon::parse($skriningTerakhir->tanggal_pemeriksaan)->diffInDays(now()) }} hari
    </div>
@endif
<div id="skrining-warning" class="text-danger small"></div>

{{-- 
| End of sintaks 
| untuk button disabled
--}}

{{-- btn untuk skrining --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('btn-skrining-tahunan');
    if (btn) {
        btn.addEventListener('click', function () {
            const userId = btn.dataset.userId;

            fetch(`/skrining-tahunan-lansia/${userId}`)
                .then(res => res.text())
                .then(html => {
                    const container = document.getElementById('modal-skrining-tahunan-content');
                    container.innerHTML = html;

                    // ✅ Aktifkan <script> di dalam konten HTML hasil fetch
                    const scripts = container.querySelectorAll('script');
                    scripts.forEach(oldScript => {
                        const newScript = document.createElement('script');
                        if (oldScript.src) {
                            newScript.src = oldScript.src;
                        } else {
                            newScript.textContent = oldScript.textContent;
                        }
                        document.body.appendChild(newScript);
                        // opsional: hapus script dari body setelah dijalankan
                    });

                    // ✅ Tampilkan modal
                    const modalEl = document.getElementById('skriningTahunanModal');
                    if (modalEl) {
                        const modal = new bootstrap.Modal(modalEl);
                        modal.show();

                        // ✅ Hapus backdrop setelah close agar klik background aktif lagi
                        modalEl.addEventListener('hidden.bs.modal', function () {
                            document.body.classList.remove('modal-open');
                            const backdrop = document.querySelector('.modal-backdrop');
                            if (backdrop) backdrop.remove();
                        });
                    }
                })
                .catch(err => {
                    alert('Gagal memuat skrining: ' + err.message);
                });
        });
    }
});
</script>

{{-- BATAS SCRIPT MODAL SKRINING --}}



<link rel="stylesheet" href="{{ asset('css/input-pemeriksaan.css') }}">
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ✅ FORM SUBMIT HANDLER
    const form = document.getElementById('form-lansia');
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

    // ✅ PANGGIL LANSIA HANDLER
    if (window.initializeLansiaHandler) {
        window.initializeLansiaHandler();
    }
});
</script>

{{-- script untuk hitung otomatis skrining --}}
