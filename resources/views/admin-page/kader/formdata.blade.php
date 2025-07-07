<form id="form-lengkapi-data" method="POST" action="{{ url('/lengkapi-data/'.$user->id) }}">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Lengkapi Data Diri</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <!-- Nama & Jenis Kelamin -->
        <div class="row mb-3">
            <div class="col">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="{{ $user->nama ?? '' }}" required>
            </div>
            <div class="col">
                <label>Jenis Kelamin</label><br>
                @if(isset($user->level) && $user->level === 'ibu hamil')
                    <input type="hidden" name="jenis_kelamin" value="Perempuan">
                    <span class="badge bg-info text-dark">Perempuan</span>
                @else
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="laki" value="Laki-laki" {{ (isset($user->jenis_kelamin) && $user->jenis_kelamin == 'Laki-laki') ? 'checked' : '' }} required>
                        <label class="form-check-label" for="laki">Laki-laki</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan" value="Perempuan" {{ (isset($user->jenis_kelamin) && $user->jenis_kelamin == 'Perempuan') ? 'checked' : '' }} required>
                        <label class="form-check-label" for="perempuan">Perempuan</label>
                    </div>
                @endif
            </div>
        </div>
        <!-- NIK -->
    {{-- NIK di formdata.blade.php sudah OK --}}
        <div class="mb-3">
            <label>NIK</label>
            <input type="number" name="nik" class="form-control" 
                value="{{ $user->nik ?? '' }}" 
                placeholder="Masukkan 16 digit NIK"
                min="0" 
                required>
            @error('nik')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
        <!-- Tanggal Lahir & Umur -->
        <div class="row mb-3">
            <div class="col">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control"
                    value="{{ $user->tanggal_lahir ?? '' }}"
                    onchange="hitungUmurOtomatis()"
                    max="{{ date('Y-m-d') }}" required>
            </div>
            <div class="col">
                <label>Umur</label>
                <input type="text" id="umur" name="umur" class="form-control" value="" readonly required>
            </div>
        </div>
        <div class="row mb-3">
        @if(isset($user->level) && $user->level === 'balita')
        <div class="row mb-3">
            <div class="col">
                <label for="berat_badan_lahir">Berat Badan Lahir (kg)</label>
                <input type="number" step="0.01" min="0" name="berat_badan_lahir" id="berat_badan_lahir" class="form-control"
                    value="{{ $user->berat_badan_lahir ?? '' }}" placeholder="Contoh: 3.2" required>
            </div>
            <div class="col">
                <label for="panjang_badan_lahir">Panjang Badan Lahir (cm)</label>
                <input type="number" step="0.1" min="0" name="panjang_badan_lahir" id="panjang_badan_lahir" class="form-control"
                    value="{{ $user->panjang_badan_lahir ?? '' }}" placeholder="Contoh: 49" required>
            </div>
        </div>
        @endif
        @if(isset($user->level) && ($user->level === 'balita' || $user->level === 'remaja'))
        <!-- Nama Ayah & Ibu -->
        <div class="row mb-3">
            <div class="col">
                <label for="nama_ayah">Nama Ayah</label>
                <input type="text" name="nama_ayah" id="nama_ayah" class="form-control" value="{{ $user->nama_ayah ?? '' }}" required>
            </div>
            <div class="col">
                <label for="nama_ibu">Nama Ibu</label>
                <input type="text" name="nama_ibu" id="nama_ibu" class="form-control" value="{{ $user->nama_ibu ?? '' }}" required>
            </div>
        </div>
        @endif
        <!-- Alamat -->
        <div class="mb-3">
            <label>Alamat</label>
            <input type="text" name="alamat" class="form-control" value="{{ $user->alamat ?? '' }}" required>
        </div>
        <!-- No HP -->
        <div class="mb-3">
            <label>No. HP</label>
            <input type="tel" name="no_hp" class="form-control" value="{{ $user->no_hp ?? '' }}" min="0" oninput="this.value=this.value.replace(/[^0-9]/g,'');" required>
        </div>
         {{-- Status Perkawinan & Pekerjaan: hanya untuk lansia & dewasa --}}
        @if(isset($user->level) && ($user->level === 'lansia' || $user->level === 'dewasa'))
            <!-- Status Perkawinan -->
            <div class="mb-3">
                <label>Status Perkawinan</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status_perkawinan" id="menikah" value="Menikah" {{ (isset($user->status_perkawinan) && $user->status_perkawinan == 'Menikah') ? 'checked' : '' }} required>
                    <label class="form-check-label" for="menikah">Menikah</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status_perkawinan" id="tidakmenikah" value="Tidak Menikah" {{ (isset($user->status_perkawinan) && $user->status_perkawinan == 'Tidak Menikah') ? 'checked' : '' }} required>
                    <label class="form-check-label" for="tidakmenikah">Tidak Menikah</label>
                </div>
            </div>
            <!-- Pekerjaan -->
            <div class="mb-3">
                <label>Pekerjaan</label>
                <input type="text" name="pekerjaan" class="form-control" value="{{ $user->pekerjaan ?? '' }}" required>
            </div>
        @endif
         <!-- Dusun, RT, RW -->
        <div class="row mb-3">
            <div class="col">
                <label>Dusun</label>
                <input type="text" name="dusun" class="form-control" value="{{ $user->dusun ?? '' }}" required>
            </div>
            <div class="col">
                <label>RT</label>
                <input type="number" name="rt" class="form-control" value="{{ $user->rt ?? '' }}" min="0" oninput="this.value=this.value.replace(/[^0-9]/g,'');" required>
            </div>
            <div class="col">
                <label>RW</label>
                <input type="number" name="rw" class="form-control" value="{{ $user->rw ?? '' }}" min="0" oninput="this.value=this.value.replace(/[^0-9]/g,'');" required>
            </div>
        </div>
        <!-- Desa/Kelurahan/Nagari -->
        <div class="mb-3">
            <label for="kecamatan">Desa/Kelurahan/Nagari</label>
            <input type="text" name="kecamatan" id="kecamatan" class="form-control" value="{{ $user->kecamatan ?? '' }}" required>
        </div>
        <!-- Kecamatan -->
        <div class="mb-3">
            <label for="wilayah">Kecamatan</label>
            <input type="text" name="wilayah" id="wilayah" class="form-control" value="{{ $user->wilayah ?? '' }}" required>
        </div>
        {{-- Riwayat Keluarga & Diri Sendiri: hanya untuk lansia, dewasa, remaja --}}
        @if(isset($user->level) && in_array($user->level, ['lansia', 'dewasa', 'remaja']))
            <!-- Riwayat Keluarga -->
            <div class="mb-3">
                <label>Riwayat Keluarga</label>
                <div class="d-flex flex-wrap gap-2">
                    @php
                        $riwayat_keluarga = explode(',', $user->riwayat_keluarga ?? '');
                        $opsi_keluarga = ['Hipertensi', 'DM', 'Stroke', 'Jantung', 'Kanker', 'Kolestrol Tinggi', 'Asma'];
                    @endphp
                    @foreach($opsi_keluarga as $item)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="riwayat_keluarga[]" id="keluarga_{{ $item }}" value="{{ $item }}"
                            {{ in_array($item, $riwayat_keluarga) ? 'checked' : '' }}>
                        <label class="form-check-label" for="keluarga_{{ $item }}">{{ $item }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
            <!-- Riwayat Diri Sendiri -->
            <div class="mb-3">
                <label>Riwayat Diri Sendiri</label>
                <div class="d-flex flex-wrap gap-2">
                    @php
                        $riwayat_diri = explode(',', $user->riwayat_diri ?? '');
                        $opsi_diri = ['Hipertensi', 'DM', 'Stroke', 'Jantung', 'Kanker', 'Kolestrol Tinggi', 'Asma'];
                    @endphp
                    @foreach($opsi_diri as $item)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="riwayat_diri[]" id="diri_{{ $item }}" value="{{ $item }}"
                            {{ in_array($item, $riwayat_diri) ? 'checked' : '' }}>
                        <label class="form-check-label" for="diri_{{ $item }}">{{ $item }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Perilaku Beresiko Diri Sendiri: hanya untuk lansia & dewasa --}}
        @if(isset($user->level) && ($user->level === 'lansia' || $user->level === 'dewasa'))
            <!-- Perilaku Beresiko Diri Sendiri -->
            <div class="mb-3">
                <label>Perilaku Beresiko Diri Sendiri</label>
                <div class="d-flex flex-wrap gap-2">
                    @php
                        $perilaku_beresiko = explode(',', $user->perilaku_beresiko ?? '');
                        $opsi_perilaku = ['Merokok', 'Konsumsi Tinggi Gula', 'Konsumsi Tinggi Garam', 'Konsumsi Tinggi Lemak'];
                    @endphp
                    @foreach($opsi_perilaku as $item)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="perilaku_beresiko[]" id="perilaku_{{ $item }}" value="{{ $item }}"
                            {{ in_array($item, $perilaku_beresiko) ? 'checked' : '' }}>
                        <label class="form-check-label" for="perilaku_{{ $item }}">{{ $item }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if(isset($user->level) && $user->level === 'ibu hamil')
    <div class="mb-3">
        <label for="nama_suami">Nama Suami</label>
        <input type="text" name="nama_suami" id="nama_suami" class="form-control" 
               value="{{ $user->nama_suami ?? '' }}" 
               placeholder="Masukkan nama suami" 
               required>
        <small class="text-muted">Nama lengkap suami</small>
    </div> 
    <!-- Jarak Anak Sebelumnya dengan Kehamilan Saat Ini -->
    <div class="mb-3">
        <label for="jarak_kehamilan">Jarak Anak Sebelumnya dengan Kehamilan Saat Ini</label>
        <div class="row">
            <div class="col-6 col-md-3">
                <input type="number" min="0" name="jarak_kehamilan_tahun" class="form-control" value="{{ $user->jarak_kehamilan_tahun ?? '' }}" placeholder="Tahun" required>
                <small class="text-muted">Tahun</small>
            </div>
            <div class="col-6 col-md-3">
                <input type="number" min="0" max="11" name="jarak_kehamilan_bulan" class="form-control" value="{{ $user->jarak_kehamilan_bulan ?? '' }}" placeholder="Bulan" required>
                <small class="text-muted">Bulan</small>
            </div>
        </div>
    </div>
    <!-- Berat Badan (kg) -->
    <div class="mb-3">
        <label for="berat_badan_ibu">Berat Badan (kg)</label>
        <input type="number" step="0.1" min="0" name="berat_badan_ibu" id="berat_badan_ibu" class="form-control" value="{{ $user->berat_badan_ibu ?? '' }}" placeholder="Contoh: 55.5" required>
    </div>
    <!-- Hamil Anak Ke -->
    <div class="mb-3">
        <label for="hamil_ke">Hamil Anak Ke</label>
        <input type="number" min="0" name="hamil_ke" id="hamil_ke" class="form-control" value="{{ $user->hamil_ke ?? '' }}" placeholder="Contoh: 2" required>
    </div>
    <!-- Tinggi Badan (cm) -->
    <div class="mb-3">
        <label for="tinggi_badan_ibu">Tinggi Badan (cm)</label>
        <input type="number" step="0.1" min="0" name="tinggi_badan_ibu" id="tinggi_badan_ibu" class="form-control" value="{{ $user->tinggi_badan_ibu ?? '' }}" placeholder="Contoh: 160" required>
    </div>
@endif
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-lengkapi-data');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Disable button untuk prevent double submit
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';
            
            // Let the form submit normally (no preventDefault)
            // Form akan submit ke controller dan redirect back dengan session
        });
    }
});
</script>