<!-- skrining-tahunan-lansia.blade.php -->
<form id="form-skrining-tahunan-lansia" action="/simpan-skrining-tahunan-lansia" method="POST">
    @csrf
    <input type="hidden" name="user_id" value="{{ $user->id }}">
    <input type="hidden" name="status_rujukan" id="status-rujukan" value="Tidak Dirujuk">

    <div class="modal-header">
        <h5 class="modal-title">Skrining Tahunan Lansia</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
            <h5 class="modal-title">Aktifitas Kehidupan Sehari-hari (AKS)</h5>
        <div class="border rounded p-3">
            <div class="mb-3">
                <label class="form-label">Tanggal Pemeriksaan</label>
                <input type="date" class="form-control" name="tanggal_pemeriksaan" required max="{{ date('Y-m-d') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Mengendalikan rangsangan BAB</label>
                <select class="form-select skor-input" name="bab" required>
                    <option value="">-- Pilih --</option>
                    <option value="0">Tidak terkendali </option>
                    <option value="1">Kadang terkendali </option>
                    <option value="2">Terkendali </option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Mengendalikan rangsangan buang air kecil</label>
                <select class="form-select skor-input" name="bak" required>
                    <option value="">-- Pilih --</option>
                    <option value="0">Tidak terkendali </option>
                    <option value="1">Kadang-kadang tak terkendali </option>
                    <option value="2">Terkendali </option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Membersihkan diri (mencuci wajah, menyikat rambut, mencukur kumis, sikat gigi)</label>
                <select class="form-select skor-input" name="bersih_diri" required>
                    <option value="">-- Pilih --</option>
                    <option value="0">Butuh pertolongan orang lain </option>
                    <option value="1">Mandiri </option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Penggunaan WC (keluar masuk, menyiram, mengenakan celana, cebok)</label>
                <select class="form-select skor-input" name="wc" required>
                    <option value="">-- Pilih --</option>
                    <option value="0">Butuh pertolongan orang lain </option>
                    <option value="1">Beberapa kegiatan perlu pertolongan </option>
                    <option value="2">Mandiri </option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Makan minum (jika makan harus berupa potongan, dianggap dibantu)</label>
                <select class="form-select skor-input" name="makan" required>
                    <option value="">-- Pilih --</option>
                    <option value="0">Tidak mampu </option>
                    <option value="1">Perlu ditolong memotong makanan </option>
                    <option value="2">Mandiri </option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Bergerak dari kursi roda ke tempat tidur dan sebaliknya</label>
                <select class="form-select skor-input" name="bergerak" required>
                    <option value="">-- Pilih --</option>
                    <option value="0">Tidak mampu </option>
                    <option value="1">Perlu banyak bantuan orang </option>
                    <option value="2">Hanya butuh bantuan 1 orang </option>
                    <option value="3">Mandiri</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Berpakaian (termasuk memakai sepatu, mengencangkan sabuk)</label>
                <select class="form-select skor-input" name="pakaian" required>
                    <option value="">-- Pilih --</option>
                    <option value="0">Dibantu orang lain </option>
                    <option value="1">Sebagian kegiatan dibantu </option>
                    <option value="2">Mandiri </option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Naik turun tangga</label>
                <select class="form-select skor-input" name="tangga" required>
                    <option value="">-- Pilih --</option>
                    <option value="0">Tidak mampu </option>
                    <option value="1">Butuh pertolongan </option>
                    <option value="2">Mandiri </option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Mandi</label>
                <select class="form-select skor-input" name="mandi" required>
                    <option value="">-- Pilih --</option>
                    <option value="0">Dibantu orang lain </option>
                    <option value="1">Mandiri </option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Berjalan di jalan rata (atau jika tidak bisa berjalan, menjalankan kursi roda)</label>
                <select class="form-select skor-input" name="jalan_rata" required>
                    <option value="">-- Pilih --</option>
                    <option value="0">Tidak mampu </option>
                    <option value="1">Bisa (pindah dengan kursi roda) </option>
                    <option value="2">Berjalan dengan bantuan 1 orang </option>
                    <option value="3">Mandiri</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Total Skor</label>
                <input type="text" class="form-control" id="total-skor" name="total_skor" readonly>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Status Kemandirian</label>
                <input type="text" class="form-control" id="status-kemandirian" name="status_kemandirian" readonly>
            </div>
            <div class="mb-3" id="rujuk-field" style="display:none;">
                <label class="form-label text-danger">Status Rujukan</label>
                <input type="text" class="form-control" value="Dirujuk" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Edukasi/Konseling</label>
                <textarea class="form-control" name="edukasi" rows="2" placeholder="Isi edukasi/konseling yang diberikan..."></textarea>
            </div>
        </div>
    </div>

        
    {{-- SECTION KESEHATAN MENTAL --}}
    <div class="modal-body">
            <h5 class="modal-title">Kesehatan Mental</h5>
        <div class="border rounded p-3">
            @php
                $mentalQuestions = [
                    'sering_sakit_kepala' => 'Apakah Anda sering merasa sakit kepala?',
                    'kehilangan_nafsu_makan' => 'Apakah Anda kehilangan nafsu makan?',
                    'tidur_nyenyak' => 'Apakah tidur Anda nyenyak?',
                    'mudah_takut' => 'Apakah Anda mudah merasa takut?',
                    'tangan_gemetar' => 'Apakah tangan Anda gemetar?',
                    'gangguan_pencernaan' => 'Apakah Anda mengalami gangguan pencernaan?',
                    'sulit_berpikir_jernih' => 'Apakah Anda merasa sulit berpikir jernih?',
                    'tidak_bahagia' => 'Apakah Anda merasa tidak bahagia?',
                    'sering_menangis' => 'Apakah Anda lebih sering menangis?',
                    'sulit_menikmati_aktivitas' => 'Apakah Anda merasa sulit untuk menikmati aktivitas sehari-hari?',
                    'sulit_ambil_keputusan' => 'Apakah Anda mengalami kesulitan saat mengambil keputusan?',
                    'tugas_terbengkalai' => 'Apakah aktivitas atau tugas sehari-hari Anda terbengkalai?',
                    'tidak_mampu_berperan' => 'Apakah Anda merasa tidak mampu berperan dalam kehidupan ini?',
                    'kehilangan_minat' => 'Apakah Anda kehilangan minat terhadap banyak hal?',
                    'tidak_berharga' => 'Apakah Anda merasa tidak berharga?',
                    'pikiran_akhiri_hidup' => 'Apakah Anda mempunyai pikiran untuk mengakhiri hidup Anda?',
                    'lelah_sepanjang_waktu' => 'Apakah Anda merasa lelah sepanjang waktu?',
                    'tidak_enak_perut' => 'Apakah Anda merasa tidak enak di perut?',
                    'mudah_lelah' => 'Apakah Anda mudah lelah?',
                ];
            @endphp

            @foreach($mentalQuestions as $name => $label)
            <div class="mb-2">
                <label class="form-label">{{ $label }}</label>
                <select class="form-select skor-mental" name="mental[{{ $name }}]" required>
                    <option value="">-- Pilih --</option>
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
            </div>
            @endforeach

            <div class="mb-3">
                <label class="form-label fw-bold">Total Skor Kesehatan Mental</label>
                <input type="text" class="form-control" id="total-skor-mental" name="total_skor_mental" readonly>
            </div>
            <div class="mb-3" id="rujuk-mental-field" style="display:none;">
                <label class="form-label text-danger">Status Rujukan</label>
                <input type="text" class="form-control" value="Rujuk ke Puskesmas" readonly>
            </div>
        </div>
    </div>


{{-- SECTION SKRINING LANSIA SEDERHANA (SKILAS) --}}
<div class="modal-body">
    <h5 class="modal-title">Skrining Lansia Sederhana (SKILAS)</h5>
    <div class="border rounded p-3">

        {{-- Penurunan Kognitif --}}
        <div class="mb-3">
            <label class="fw-bold">Penurunan Kognitif</label>
            <div class="mb-2">
                <label class="form-label">Orientasi waktu dan tempat</label>
                <select class="form-select skor-skilas" name="skilas[orientasi_waktu_tempat]" required>
                    <option value="">-- Pilih --</option>
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
            </div>
            <div class="mb-2">
                <label class="form-label">Mengulang ketiga kata</label>
                <select class="form-select skor-skilas" name="skilas[mengulang_ketiga_kata]" required>
                    <option value="">-- Pilih --</option>
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
            </div>
        </div>

        {{-- Keterbatasan Mobilisasi --}}
        <div class="mb-3">
            <label class="fw-bold">Keterbatasan Mobilisasi</label>
            <div class="mb-2">
                <label class="form-label">Tes berdiri dari kursi</label>
                <select class="form-select skor-skilas" name="skilas[tes_berdiri_kursi]" required>
                    <option value="">-- Pilih --</option>
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
            </div>
        </div>

        {{-- Malnutrisi --}}
        <div class="mb-3">
            <label class="fw-bold">Malnutrisi</label>
            <div class="mb-2">
                <label class="form-label">BB berkurang 3 kg dalam 3 bulan terakhir atau pakaian jadi lebih longgar</label>
                <select class="form-select skor-skilas" name="skilas[bb_berkurang]" required>
                    <option value="">-- Pilih --</option>
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
            </div>
            <div class="mb-2">
                <label class="form-label">Hilang nafsu makan / kesulitan makan</label>
                <select class="form-select skor-skilas" name="skilas[hilang_nafsu_makan]" required>
                    <option value="">-- Pilih --</option>
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
            </div>
            <div class="mb-2">
                <label class="form-label">LILA &lt; 21 cm</label>
                <select class="form-select skor-skilas" name="skilas[lila_kurang_21]" required>
                    <option value="">-- Pilih --</option>
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
            </div>
        </div>

        {{-- Gangguan Penglihatan --}}
        <div class="mb-3">
            <label class="fw-bold">Gangguan Penglihatan</label>
            <div class="mb-2">
                <label class="form-label">Masalah pada mata</label>
                <select class="form-select skor-skilas" name="skilas[masalah_mata]" required>
                    <option value="">-- Pilih --</option>
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
            </div>
            <div class="mb-2">
                <label class="form-label">Gangguan pada tes melihat</label>
                <select class="form-select skor-skilas" name="skilas[gangguan_tes_melihat]" required>
                    <option value="">-- Pilih --</option>
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
            </div>
        </div>

        {{-- Gangguan Pendengaran --}}
        <div class="mb-3">
            <label class="fw-bold">Gangguan Pendengaran</label>
            <div class="mb-2">
                <label class="form-label">Gangguan pada tes bisik</label>
                <select class="form-select skor-skilas" name="skilas[gangguan_tes_bisik]" required>
                    <option value="">-- Pilih --</option>
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
            </div>
        </div>

        {{-- Gejala Depresi --}}
        <div class="mb-3">
            <label class="fw-bold">Gejala Depresi</label>
            <div class="mb-2">
                <label class="form-label">Gejala depresi (dalam 2 minggu terakhir)</label>
                <select class="form-select skor-skilas" name="skilas[gejala_depresi]" required>
                    <option value="">-- Pilih --</option>
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
            </div>
        </div>

        <div class="mb-3" id="rujuk-skilas-field" style="display:none;">
            <label class="form-label text-danger">Status Rujukan</label>
            <input type="text" class="form-control" value="Rujuk ke Puskesmas" readonly>
        </div>
    </div>
</div>




    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Simpan Skrining</button>
    </div>
</form>
