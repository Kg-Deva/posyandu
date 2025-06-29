<!-- skrining-tahunan-dewasa.blade.php -->
<form id="form-skrining-tahunan-dewasa" action="/simpan-skrining-tahunan-dewasa" method="POST">
    @csrf
    <input type="hidden" name="user_id" value="{{ $user->id }}">
    <input type="hidden" name="status_rujukan" id="status-rujukan" value="Tidak Dirujuk">

    <div class="modal-header">
        <h5 class="modal-title">Skrining Tahunan Dewasa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    
    {{-- SECTION KESEHATAN MENTAL --}}
    <div class="modal-body">
            <h5 class="modal-title">Kesehatan Mental</h5>
        <div class="border rounded p-3">
            <div class="mb-3">
                <label class="form-label">Tanggal Pemeriksaan</label>
                <input type="date" class="form-control" name="tanggal_pemeriksaan" required max="{{ date('Y-m-d') }}">
            </div>
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
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Simpan Skrining</button>
    </div>
</form>
