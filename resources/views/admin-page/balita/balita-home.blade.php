@extends('admin-layouts.main')
@section('title', 'Catatan Kesehatan - ' . $user->nama)
<link rel="stylesheet" href="{{ asset('css/input-pemeriksaan.css') }}">

@section('content')
<div class="container-fluid px-4">
    <!-- ✅ HEADER UNTUK ORANG TUA -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="position-relative overflow-hidden rounded-4 shadow-lg bg-gradient-primary text-white">
                <div class="p-4 p-md-5">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-3">
                                <div>
                                    <h1 class="mb-1 fw-bold">Dashboard Kesehatan {{ $user->nama }}</h1>
                                    <div class="mb-0 opacity-90 fs-5">
                                        <div class="d-flex flex-column flex-md-row gap-2 gap-md-4">
                                            <div class="d-flex align-items-center">
                                                <span class="text-white-50 me-2 fw-normal">👶 Umur:</span>
                                                <span class="fw-semibold">{{ $pemeriksaanTerakhir ? $pemeriksaanTerakhir->umur  . ' bulan' : $user->umur }}</span>
                                            </div>
                                            {{-- <div class="d-flex align-items-center">
                                                <span class="text-white-50 me-2 fw-normal">📋 NIK:</span>
                                                <span class="fw-semibold">{{ $user->nik }}</span>
                                            </div> --}}
                                            <div class="d-flex align-items-center">
                                                <span class="text-white-50 me-2 fw-normal">📅 Periksa Terakhir:</span>
                                                <span class="fw-semibold">{{ $pemeriksaanTerakhir->tanggal_pemeriksaan->format('d M Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white bg-opacity-10 rounded-3 p-3 mb-3">
                                <p class="mb-0">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Untuk Orang Tua:</strong> Pantau perkembangan kesehatan dan pertumbuhan si kecil di sini. 
                                    Grafik ini membantu Anda memahami apakah anak berkembang dengan baik.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center d-none d-md-block">
                            <div class="bg-white bg-opacity-10 rounded-circle p-4 d-inline-block">
                                <i class="bi bi-graph-up-arrow fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ RINGKASAN UNTUK ORANG TUA -->
    <div class="row g-3 g-lg-4 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 bg-light">
                <div class="card-body text-center p-3 p-lg-4">
                    <div class="bg-primary rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-clipboard-check text-white fs-4"></i>
                    </div>
                    <h2 class="fw-bold text-primary mb-1">{{ $totalPemeriksaan }}</h2>
                    <p class="mb-1 text-dark fw-semibold">Kali Periksa</p>
                    <small class="text-muted">Total pemeriksaan yang sudah dilakukan</small>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 bg-light">
                <div class="card-body text-center p-3 p-lg-4">
                    <div class="bg-{{ $progressBB > 0 ? 'success' : ($progressBB < 0 ? 'warning' : 'info') }} rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-{{ $progressBB > 0 ? 'arrow-up' : ($progressBB < 0 ? 'arrow-down' : 'dash-lg') }} text-white fs-4"></i>
                    </div>
                    <h2 class="fw-bold mb-1">
                        @if($progressBB > 0)
                            <span class="text-success">+{{ $progressBB }} kg</span>
                        @elseif($progressBB < 0)
                            <span class="text-warning">{{ $progressBB }} kg</span>
                        @elseif($pemeriksaanTerakhir && $pemeriksaanTerakhir->bb)
                            <span class="text-info">{{ $pemeriksaanTerakhir->bb }} kg</span>
                        @else
                            <span class="text-muted">Belum Ada</span>
                        @endif
                    </h2>
                    <p class="mb-1 text-dark fw-semibold">Berat Badan</p>
                    <small class="text-muted">
                        @if($progressBB > 0) 
                            Bertambah baik! 
                        @elseif($progressBB < 0) 
                            Perlu perhatian 
                        @elseif($pemeriksaanTerakhir && $pemeriksaanTerakhir->bb)
                            Berat tetap
                        @else 
                            Data pertama 
                        @endif
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 bg-light">
                <div class="card-body text-center p-3 p-lg-4">
                    <div class="bg-{{ $progressTB > 0 ? 'success' : ($progressTB < 0 ? 'warning' : 'info') }} rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-{{ $progressTB > 0 ? 'arrow-up' : ($progressTB < 0 ? 'arrow-down' : 'dash-lg') }} text-white fs-4"></i>
                    </div>
                    <h2 class="fw-bold mb-1">
                        @if($progressTB > 0)
                            <span class="text-success">+{{ $progressTB }} cm</span>
                        @elseif($progressTB < 0)
                            <span class="text-warning">{{ $progressTB }} cm</span>
                        @elseif($pemeriksaanTerakhir && $pemeriksaanTerakhir->tb)
                            <span class="text-info">{{ $pemeriksaanTerakhir->tb }} cm</span>
                        @else
                            <span class="text-muted">Belum Ada</span>
                        @endif
                    </h2>
                    <p class="mb-1 text-dark fw-semibold">Tinggi Badan</p>
                    <small class="text-muted">
                        @if($progressTB > 0) 
                            Tumbuh baik! 
                        @elseif($progressTB < 0) 
                            Perlu perhatian 
                        @elseif($pemeriksaanTerakhir && $pemeriksaanTerakhir->tb)
                            Tetap sama
                        @else 
                            Data pertama 
                        @endif
                    </small>
                </div>
            </div>
        </div>
        

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 bg-light">
                <div class="card-body text-center p-3 p-lg-4">
                    @php
                        $statusClass = match($statusKesehatan['status']) {
                            'Sehat' => 'success',
                            'Perlu Perhatian' => 'warning',
                            'Belum Ada Data' => 'secondary',
                            default => 'danger'
                        };
                        
                        $statusIcon = match($statusKesehatan['status']) {
                            'Sehat' => 'check-circle-fill',
                            'Perlu Perhatian' => 'exclamation-triangle',
                            'Belum Ada Data' => 'question-circle',
                            default => 'exclamation-triangle-fill'
                        };
                        
                        // ✅ BIKIN TULISAN PENDEK & KONSISTEN
                        $statusText = match($statusKesehatan['status']) {
                            'Sehat' => 'Sehat',
                            'Perlu Perhatian' => 'Perhatian',
                            'Belum Ada Data' => 'Belum Ada',
                            default => 'Darurat'
                        };
                    @endphp
                    
                    <div class="bg-{{ $statusClass }} rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-{{ $statusIcon }} text-white fs-4"></i>
                    </div>
                    
                    <!-- ✅ PAKAI UKURAN FONT YANG SAMA SEPERTI CARD LAIN -->
                    <h2 class="fw-bold text-{{ $statusClass }} mb-1">{{ $statusText }}</h2>
                    <p class="mb-1 text-dark fw-semibold">Kondisi Saat Ini</p>
                    <small class="text-muted">
                        @switch($statusKesehatan['status'])
                            @case('Sehat')
                                Alhamdulillah sehat
                                @break
                            @case('Perlu Perhatian')
                                 Pantau lebih ketat ya
                                @break
                            @case('Belum Ada Data')
                                Belum ada pemeriksaan
                                @break
                            @default
                                Segera ke Puskesmas
                        @endswitch
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ GRAFIK YANG MUDAH DIPAHAMI ORANG TUA -->
    <div class="row g-4 mb-4">
        <!-- GRAFIK PERTUMBUHAN -->
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title fw-bold text-dark mb-1 d-flex align-items-center">
                                {{-- <i class="bi bi-graph-up text-primary me-2 fs-4"></i> --}}
                                <p class="text-muted">   📈 Grafik Pertumbuhan Anak</p>
                            </h4>
                            <p class="text-muted mb-0">Lihat bagaimana si kecil tumbuh dari waktu ke waktu</p>
                        </div>
                        <div class="text-end">
                            <small class="badge bg-info text-white px-3 py-2">
                                <i class="bi bi-lightbulb me-1"></i>
                                Tips: Garis naik = Bagus!
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- PENJELASAN UNTUK ORANG TUA -->
                    <div class="alert alert-info border-0 mb-4">
                        <div class="d-flex">
                            <i class="bi bi-info-circle-fill me-2 mt-1"></i>
                            <div>
                                <strong>Cara Membaca Grafik:</strong>
                                <ul class="mb-0 mt-2">
                                    <li><span class="badge bg-primary me-2">●</span><strong>Garis Biru</strong> = Berat badan anak (semakin naik semakin baik)</li>
                                    <li><span class="badge bg-danger me-2">●</span><strong>Garis Merah</strong> = Tinggi badan anak (harus terus bertambah)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="chart-container position-relative bg-white rounded-3 p-3" style="height: 350px;">
                        <canvas id="growthChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex align-items-center text-white">
                        <i class="bi bi-lungs me-2"></i>
                        <h6 class="mb-0 fw-semibold">Screening TBC</h6>
                    </div>
                </div>
                    <div class="card-body p-3">
                        @if($pemeriksaanTerakhir)
                            @php 
                                $gejalaTBC = $pemeriksaanTerakhir->jumlah_gejala_tbc ?? 0;
                                $kontakTBC = $pemeriksaanTerakhir->kontak_tbc ?? false;
                                $statusClass = $gejalaTBC == 0 && !$kontakTBC ? 'success' : ($gejalaTBC >= 2 || ($gejalaTBC >= 1 && $kontakTBC) ? 'danger' : 'warning');
                            @endphp
                            
                            {{-- Status Badge --}}
                            <div class="text-center mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center bg-{{ $statusClass }} bg-opacity-10 border border-{{ $statusClass }} border-opacity-25 rounded-circle" style="width: 60px; height: 60px;">
                                    <i class="bi bi-{{ $statusClass == 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill' }} text-{{ $statusClass }} fs-3"></i>
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-{{ $statusClass }} fs-6">
                                        @if($statusClass == 'success')
                                            Hasil Normal
                                        @elseif($statusClass == 'danger')
                                            Waspada TBC, Segera ke Puskesmas
                                        @else
                                            Perlu Pantau
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            {{-- Gejala Count --}}
                            <div class="text-center mb-3">
                                <div class="fs-2 fw-bold text-{{ $statusClass }}">{{ $gejalaTBC }}</div>
                                <small class="text-muted">dari 4 gejala TBC</small>
                            </div>
                            
                            {{-- Quick Checklist --}}
                            <div class="space-y-2">
                                @php
                                    // ✅ INDIVIDUAL SYMPTOMS CHECK
                                    $symptoms = [
                                        'batuk' => $pemeriksaanTerakhir->batuk_terus_menerus ?? str_contains(strtolower($pemeriksaanTerakhir->catatan ?? ''), 'batuk'),
                                        'demam' => $pemeriksaanTerakhir->demam_2_minggu ?? str_contains(strtolower($pemeriksaanTerakhir->catatan ?? ''), 'demam'),
                                        'bb' => $pemeriksaanTerakhir->bb_tidak_naik ?? ($progressBB <= 0),
                                        'kontak' => $pemeriksaanTerakhir->kontak_tbc ?? false
                                    ];
                                @endphp
                                
                                <div class="d-flex align-items-center justify-content-between py-2 px-3 rounded-2 {{ $symptoms['batuk'] ? 'bg-warning bg-opacity-10 border border-warning border-opacity-25' : 'bg-light' }} mb-2">
                                    <small class="text-muted">Batuk >2 minggu</small>
                                    <i class="bi bi-{{ $symptoms['batuk'] ? 'check-circle-fill text-warning' : 'x-circle text-muted' }}"></i>
                                </div>
                                <div class="d-flex align-items-center justify-content-between py-2 px-3 rounded-2 {{ $symptoms['demam'] ? 'bg-warning bg-opacity-10 border border-warning border-opacity-25' : 'bg-light' }} mb-2">
                                    <small class="text-muted">Demam lama</small>
                                    <i class="bi bi-{{ $symptoms['demam'] ? 'check-circle-fill text-warning' : 'x-circle text-muted' }}"></i>
                                </div>
                                <div class="d-flex align-items-center justify-content-between py-2 px-3 rounded-2 {{ $symptoms['bb'] ? 'bg-warning bg-opacity-10 border border-warning border-opacity-25' : 'bg-light' }} mb-2">
                                    <small class="text-muted">BB tidak naik</small>
                                    <i class="bi bi-{{ $symptoms['bb'] ? 'check-circle-fill text-warning' : 'x-circle text-muted' }}"></i>
                                </div>
                                <div class="d-flex align-items-center justify-content-between py-2 px-3 rounded-2 {{ $symptoms['kontak'] ? 'bg-warning bg-opacity-10 border border-warning border-opacity-25' : 'bg-light' }}">
                                    <small class="text-muted">Kontak TBC</small>
                                    <i class="bi bi-{{ $symptoms['kontak'] ? 'check-circle-fill text-warning' : 'x-circle text-muted' }}"></i>
                                </div>
                            </div>


                            {{-- Action Button --}}
                            {{-- <div class="text-center mt-3">
                                <a href="#" class="btn btn-primary">Tindak Lanjut</a>
                            </div> --}}
                        @else
                            {{-- No Data State --}}
                            <div class="text-center py-4">
                                <i class="bi bi-clipboard-pulse text-muted fs-1"></i>
                                <h6 class="text-muted mt-3 mb-2">Belum Ada Data</h6>
                                <p class="text-muted small mb-0">Screening TBC belum dilakukan</p>
                            </div>
                        @endif
                </div>
            </div>
        </div>
        
        <!-- STATUS PROGRAM -->
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-0">
                    <h4 class="card-title fw-bold text-dark mb-1 d-flex align-items-center">
                       <p class="text-muted"> ✅ Program Kesehatan</p>
                    </h4>
                    <p class="text-muted mb-0">Yang sudah diterima anak</p>
                </div>
                <div class="card-body p-4">
                    @if($pemeriksaanTerakhir)
                    <div class="row g-3">
                        {{-- ASI Eksklusif --}}
                        <div class="col-12">
                            <div class="d-flex align-items-center justify-content-between p-3 rounded-3 {{ $pemeriksaanTerakhir->asi_eksklusif ? 'bg-success bg-opacity-10 border border-success border-opacity-25' : 'bg-warning bg-opacity-10 border border-warning border-opacity-25' }}">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-{{ $pemeriksaanTerakhir->asi_eksklusif ? 'check-circle-fill text-success' : 'exclamation-triangle-fill text-warning' }} me-3 fs-5"></i>
                                    <div>
                                        <div class="fw-semibold">ASI Eksklusif</div>
                                        <small class="text-muted">
                                            {{ $pemeriksaanTerakhir->asi_eksklusif ? 'Nutrisi terbaik untuk bayi' : 'Berikan ASI eksklusif 0-6 bulan' }}
                                        </small>
                                    </div>
                                </div>
                                <span class="badge bg-{{ $pemeriksaanTerakhir->asi_eksklusif ? 'success' : 'warning' }}">
                                    {{ $pemeriksaanTerakhir->asi_eksklusif ? 'Sudah' : 'Belum' }}
                                </span>
                            </div>
                        </div>
                        
                        {{-- MP ASI --}}
                        <div class="col-12">
                            <div class="d-flex align-items-center justify-content-between p-3 rounded-3 {{ $pemeriksaanTerakhir->mp_asi ? 'bg-success bg-opacity-10 border border-success border-opacity-25' : 'bg-warning bg-opacity-10 border border-warning border-opacity-25' }}">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-{{ $pemeriksaanTerakhir->mp_asi ? 'check-circle-fill text-success' : 'exclamation-triangle-fill text-warning' }} me-3 fs-5"></i>
                                    <div>
                                        <div class="fw-semibold">Makanan Pendamping</div>
                                        <small class="text-muted">
                                            {{ $pemeriksaanTerakhir->mp_asi ? 'MPASI sesuai umur' : 'Mulai MPASI di usia 6 bulan' }}
                                        </small>
                                    </div>
                                </div>
                                <span class="badge bg-{{ $pemeriksaanTerakhir->mp_asi ? 'success' : 'warning' }}">
                                    {{ $pemeriksaanTerakhir->mp_asi ? 'Sudah' : 'Belum' }}
                                </span>
                            </div>
                        </div>
                        
                        {{-- Imunisasi --}}
                        <div class="col-12">
                            <div class="d-flex align-items-center justify-content-between p-3 rounded-3 {{ $pemeriksaanTerakhir->imunisasi ? 'bg-success bg-opacity-10 border border-success border-opacity-25' : 'bg-danger bg-opacity-10 border border-danger border-opacity-25' }}">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-{{ $pemeriksaanTerakhir->imunisasi ? 'check-circle-fill text-success' : 'x-circle-fill text-danger' }} me-3 fs-5"></i>
                                    <div>
                                        <div class="fw-semibold">Imunisasi</div>
                                        <small class="text-muted">
                                            {{ $pemeriksaanTerakhir->imunisasi ? 'Perlindungan dari penyakit' : 'PENTING! Segera lengkapi imunisasi' }}
                                        </small>
                                    </div>
                                </div>
                                <span class="badge bg-{{ $pemeriksaanTerakhir->imunisasi ? 'success' : 'danger' }}">
                                    {{ $pemeriksaanTerakhir->imunisasi ? 'Sudah' : 'Belum' }}
                                </span>
                            </div>
                        </div>
                        
                        {{-- Vitamin A --}}
                        <div class="col-12">
                            <div class="d-flex align-items-center justify-content-between p-3 rounded-3 {{ $pemeriksaanTerakhir->vitamin_a ? 'bg-success bg-opacity-10 border border-success border-opacity-25' : 'bg-info bg-opacity-10 border border-info border-opacity-25' }}">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-{{ $pemeriksaanTerakhir->vitamin_a ? 'check-circle-fill text-success' : 'info-circle-fill text-info' }} me-3 fs-5"></i>
                                    <div>
                                        <div class="fw-semibold">Vitamin A</div>
                                        <small class="text-muted">
                                            {{ $pemeriksaanTerakhir->vitamin_a ? 'Untuk mata dan daya tahan' : 'Diberikan 2x per tahun (Februari & Agustus)' }}
                                        </small>
                                    </div>
                                </div>
                                <span class="badge bg-{{ $pemeriksaanTerakhir->vitamin_a ? 'success' : 'info' }}">
                                    {{ $pemeriksaanTerakhir->vitamin_a ? 'Sudah' : 'Belum' }}
                                </span>
                            </div>
                        </div>
                        
                        {{-- Obat Cacing --}}
                        <div class="col-12">
                            <div class="d-flex align-items-center justify-content-between p-3 rounded-3 {{ $pemeriksaanTerakhir->obat_cacing ? 'bg-success bg-opacity-10 border border-success border-opacity-25' : 'bg-info bg-opacity-10 border border-info border-opacity-25' }}">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-{{ $pemeriksaanTerakhir->obat_cacing ? 'check-circle-fill text-success' : 'info-circle-fill text-info' }} me-3 fs-5"></i>
                                    <div>
                                        <div class="fw-semibold">Obat Cacing</div>
                                        <small class="text-muted">
                                            {{ $pemeriksaanTerakhir->obat_cacing ? 'Pemberian rutin setiap 6 bulan' : 'Untuk anak di atas 1 tahun, setiap 6 bulan' }}
                                        </small>
                                    </div>
                                </div>
                                <span class="badge bg-{{ $pemeriksaanTerakhir->obat_cacing ? 'success' : 'info' }}">
                                    {{ $pemeriksaanTerakhir->obat_cacing ? 'Sudah' : 'Belum' }}
                                </span>
                            </div>
                        </div>
                        
                        {{-- MT Pangan Lokal --}}
                        <div class="col-12">
                            <div class="d-flex align-items-center justify-content-between p-3 rounded-3 {{ $pemeriksaanTerakhir->mt_pangan_lokal ? 'bg-success bg-opacity-10 border border-success border-opacity-25' : 'bg-light' }}">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-{{ $pemeriksaanTerakhir->mt_pangan_lokal ? 'check-circle-fill text-success' : 'circle text-muted' }} me-3 fs-5"></i>
                                    <div>
                                        <div class="fw-semibold">MT Pangan Lokal</div>
                                        <small class="text-muted">
                                            {{ $pemeriksaanTerakhir->mt_pangan_lokal ? 'Makanan tambahan untuk pemulihan' : 'Makanan tambahan jika diperlukan' }}
                                        </small>
                                    </div>
                                </div>
                                <span class="badge bg-{{ $pemeriksaanTerakhir->mt_pangan_lokal ? 'success' : 'secondary' }}">
                                    {{ $pemeriksaanTerakhir->mt_pangan_lokal ? 'Sudah' : 'Optional' }}
                                </span>
                            </div>
                        </div>
                        
                        {{-- ✅ TAMBAHAN: LINGKAR KEPALA & LILA --}}
                        @php
                            // 📏 DEFINISI SEMUA VARIABLE YANG DIPERLUKAN
                            $umurBulan = $pemeriksaanTerakhir->umur ?? 12;
                            $lilaValue = $pemeriksaanTerakhir->lila ?? null;
                            $lingkarKepalaValue = $pemeriksaanTerakhir->lingkar_kepala ?? null;
                            $kesimpulanLila = $pemeriksaanTerakhir->kesimpulan_lila ?? null;
                            $kesimpulanLingkarKepala = $pemeriksaanTerakhir->kesimpulan_lingkar_kepala ?? null;
                            
                            // 🚨 LOGIKA STATUS KURANG
                            $lilaKurang = $kesimpulanLila && (
                                strpos(strtolower($kesimpulanLila), 'kurang') !== false || 
                                strpos(strtolower($kesimpulanLila), 'merah') !== false ||
                                strpos(strtolower($kesimpulanLila), 'buruk') !== false
                            );
                            
                            $lingkarKepalaKurang = $kesimpulanLingkarKepala && (
                                strpos(strtolower($kesimpulanLingkarKepala), 'kurang') !== false || 
                                strpos(strtolower($kesimpulanLingkarKepala), 'kecil') !== false ||
                                strpos(strtolower($kesimpulanLingkarKepala), 'mikro') !== false
                            );
                        @endphp
                        <br>
                        <div class="col-12 mt-3 mb-2">
                            <h6 class="text-muted fw-semibold mb-0">📏 LILA & Lingkar Kepala</h6>
                        </div>
                            {{-- LINGKAR KEPALA (Untuk Semua Umur) --}}
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between p-3 rounded-3 {{ $lingkarKepalaKurang ? 'bg-warning bg-opacity-10 border border-warning border-opacity-25' : ($lingkarKepalaValue ? 'bg-success bg-opacity-10 border border-success border-opacity-25' : 'bg-light') }}">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-{{ $lingkarKepalaKurang ? 'exclamation-triangle-fill text-warning' : ($lingkarKepalaValue ? 'check-circle-fill text-success' : 'circle text-muted') }} me-3 fs-5"></i>
                                        <div>
                                            <div class="fw-semibold">Lingkar Kepala</div>
                                            <small class="text-muted">
                                                @if($lingkarKepalaValue)
                                                    {{ $lingkarKepalaValue }} cm - {{ $kesimpulanLingkarKepala ?: 'Normal' }}
                                                @else
                                                    Belum diukur pada pemeriksaan ini
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <span class="badge bg-{{ $lingkarKepalaKurang ? 'warning' : ($lingkarKepalaValue ? 'success' : 'secondary') }}">
                                        @if($lingkarKepalaKurang)
                                            Perhatian
                                        @elseif($lingkarKepalaValue)
                                            Normal
                                        @else
                                            Belum Ada
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            {{-- LILA (Hanya Untuk Umur ≥6 Bulan) --}}
                            @if($umurBulan >= 6)
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between p-3 rounded-3 {{ $lilaKurang ? 'bg-warning bg-opacity-10 border border-warning border-opacity-25' : ($lilaValue ? 'bg-success bg-opacity-10 border border-success border-opacity-25' : 'bg-light') }}">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-{{ $lilaKurang ? 'exclamation-triangle-fill text-warning' : ($lilaValue ? 'check-circle-fill text-success' : 'circle text-muted') }} me-3 fs-5"></i>
                                        <div>
                                            <div class="fw-semibold">LILA (Lingkar Lengan)</div>
                                            <small class="text-muted">
                                                @if($lilaValue)
                                                    {{ $lilaValue }} cm - {{ $kesimpulanLila ?: 'Normal' }}
                                                @else
                                                    Belum diukur pada pemeriksaan ini
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <span class="badge bg-{{ $lilaKurang ? 'warning' : ($lilaValue ? 'success' : 'secondary') }}">
                                        @if($lilaKurang)
                                            Perhatian
                                        @elseif($lilaValue)
                                            Normal
                                        @else
                                            Belum Ada
                                        @endif
                                    </span>
                                </div>
                            </div>
                    @endif
                </div>
                    
                    {{-- ✅ SMART REMINDER SYSTEM (UPDATE DENGAN LILA & LINGKAR KEPALA) --}}
                        @if($pemeriksaanTerakhir)
                            @php
                                $criticalTasks = [];
                                $importantTasks = [];
                                $infoTasks = [];
                                // $umurBulan, $lilaKurang, $lingkarKepalaKurang sudah didefinisikan di atas
                                
                                // 🚨 CRITICAL
                                if (!$pemeriksaanTerakhir->imunisasi && $umurBulan >= 2) {
                                    $criticalTasks[] = "Segera lengkapi imunisasi di puskesmas/posyandu";
                                }
                                
                                // ✅ TAMBAHKAN CRITICAL UNTUK LILA & LINGKAR KEPALA
                                if ($lilaKurang && $lingkarKepalaKurang) {
                                    $criticalTasks[] = "URGENT: LILA dan lingkar kepala kurang - segera ke Puskesmas!";
                                }
                                
                                // ⚠️ IMPORTANT  
                                if (!$pemeriksaanTerakhir->asi_eksklusif && $umurBulan <= 6) {
                                    $importantTasks[] = "Berikan hanya ASI sampai usia 6 bulan";
                                }
                                if (!$pemeriksaanTerakhir->mp_asi && $umurBulan >= 6) {
                                    $importantTasks[] = "Mulai berikan MPASI sesuai usia " . $umurBulan . " bulan";
                                }
                                if (!$pemeriksaanTerakhir->vitamin_a && $umurBulan >= 6) {
                                    $importantTasks[] = "Tanyakan jadwal pemberian Vitamin A ke petugas";
                                }
                                
                                // ✅ TAMBAHKAN IMPORTANT UNTUK LILA & LINGKAR KEPALA
                                if ($lilaKurang && !$lingkarKepalaKurang) {
                                    $importantTasks[] = "LILA kurang - tingkatkan asupan protein dan kalori";
                                }
                                if ($lingkarKepalaKurang && !$lilaKurang) {
                                    $importantTasks[] = "Pantau lingkar kepala - konsultasi stimulasi tumbuh kembang";
                                }
                                
                                // ℹ️ INFO
                                if (!$pemeriksaanTerakhir->obat_cacing && $umurBulan >= 12) {
                                    $infoTasks[] = "Jadwalkan pemberian obat cacing (setiap 6 bulan)";
                                }
                                
                                // ✅ TAMBAHKAN INFO UNTUK LILA & LINGKAR KEPALA
                                if ($umurBulan >= 6 && !$lilaValue) {
                                    $infoTasks[] = "Pastikan LILA diukur pada pemeriksaan berikutnya";
                                }
                                if (!$lingkarKepalaValue) {
                                    $infoTasks[] = "Pastikan lingkar kepala diukur pada pemeriksaan berikutnya";
                                }
                                
                                $allTasks = array_merge($criticalTasks, $importantTasks, $infoTasks);
                            @endphp
                            
                            @if(count($allTasks) > 0)
                            <div class="mt-4">
                                <div class="card border-0 bg-gradient" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                    <div class="card-body p-3 text-white">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-clipboard-check me-2"></i>
                                            <h6 class="mb-0 fw-semibold">Yang Perlu Dilakukan</h6>
                                        </div>
                                        
                                        @if(count($criticalTasks) > 0)
                                            @foreach($criticalTasks as $task)
                                            <div class="d-flex align-items-start mb-2 p-2 rounded bg-white bg-opacity-20">
                                                <i class="bi bi-exclamation-triangle-fill me-2 mt-1 text-warning"></i>
                                                <small class="text-muted">{{ $task }}</small>
                                            </div>
                                            @endforeach
                                        @endif
                                        
                                        @if(count($importantTasks) > 0)
                                            @foreach($importantTasks as $task)
                                        <div class="d-flex align-items-start mb-2 p-2 rounded bg-white bg-opacity-20">
                                                <i class="bi bi-exclamation-triangle-fill me-2 mt-1 text-warning"></i>
                                                <small class="text-muted">{{ $task }}</small>
                                            </div>
                                            @endforeach
                                        @endif
                                        
                                        @if(count($infoTasks) > 0)
                                            @foreach($infoTasks as $task)
                                            <div class="d-flex align-items-start mb-1 opacity-75">
                                                <i class="bi bi-info-circle me-2 mt-1"></i>
                                                <small class="text-muted">{{ $task }}</small>
                                            </div>
                                            @endforeach
                                        @endif
                                        
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="mt-4">
                                <div class="alert alert-success border-0" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-trophy-fill me-2 text-warning"></i>
                                        <div>
                                            {{-- <div class="fw-semibold">Excellent! Semua Program Terlaksana</div> --}}
                                            <small class="fw-semibold">Terus pertahankan dan kontrol rutin setiap bulan</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            {{-- Next Control Schedule --}}
                            <div class="mt-3">
                                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar3 me-2 text-primary"></i>
                                        <div>
                                            <small class="fw-semibold">Kontrol Berikutnya</small>
                                            <div class="small text-muted">
                                                @if($pemeriksaanTerakhir->tanggal_pemeriksaan)
                                                    {{ \Carbon\Carbon::parse($pemeriksaanTerakhir->tanggal_pemeriksaan)->addMonth()->format('d F Y') }}
                                                @else
                                                    Jadwalkan segera
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <span class="badge bg-primary px-3 py-2">
                                        @php
                                            if ($pemeriksaanTerakhir->tanggal_pemeriksaan) {
                                                $nextDate = \Carbon\Carbon::parse($pemeriksaanTerakhir->tanggal_pemeriksaan)->addMonth();
                                                $daysLeft = $nextDate->diffInDays(\Carbon\Carbon::now());
                                                echo $daysLeft > 0 ? $daysLeft . ' hari lagi' : 'Sudah waktunya';
                                            } else {
                                                echo 'Segera';
                                            }
                                        @endphp
                                    </span>
                                </div>
                            </div>
                        @endif
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-clipboard-x fs-1 text-muted mb-3"></i>
                        <p class="text-muted">Belum ada data program</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ DETAIL PEMERIKSAAN TERAKHIR -->
    @if($pemeriksaanTerakhir)
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-light border-0">
                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                        {{-- <div>
                            <h4 class="card-title fw-bold text-dark mb-1 d-flex align-items-center">
                                <p class="text-muted mb-0"> Hasil Pemeriksaan Terakhir</p>
                            </h4>
                            <p class="text-muted mb-0">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ \Carbon\Carbon::parse($pemeriksaanTerakhir->tanggal_pemeriksaan)->translatedFormat('l, d F Y') }}
                            </p>
                        </div> --}}
                        {{-- <div class="text-end">
                            <span class="badge bg-primary px-3 py-2">
                                <i class="bi bi-clock me-1"></i>
                                @if(\Carbon\Carbon::parse($pemeriksaanTerakhir->tanggal_pemeriksaan)->isToday())
                                    Hari ini
                                @elseif(\Carbon\Carbon::parse($pemeriksaanTerakhir->tanggal_pemeriksaan)->isYesterday())
                                    Kemarin  
                                @else
                                    {{ \Carbon\Carbon::parse($pemeriksaanTerakhir->tanggal_pemeriksaan)->diffForHumans() }}
                                @endif
                            </span>
                        </div> --}}
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- DATA PENGUKURAN -->
                        <div class="col-lg-4">
                            <div class="bg-primary bg-opacity-5 rounded-4 p-4 h-100 border border-primary border-opacity-10">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary rounded-circle p-2 me-3">
                                        <i class="bi bi-rulers text-white"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-0">Ukuran Anak</h5>
                                </div>
                                
                                <div class="mb-3 p-3 bg-white rounded-3 shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="text-muted">Berat Badan</span>
                                            <div class="fw-bold text-primary fs-4">{{ $pemeriksaanTerakhir->bb }} kg</div>
                                        </div>
                                        <i class="bi bi-speedometer2 text-primary fs-3"></i>
                                    </div>
                                </div>
                                
                                <div class="mb-3 p-3 bg-white rounded-3 shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="text-muted">Tinggi Badan</span>
                                            <div class="fw-bold text-info fs-4">{{ $pemeriksaanTerakhir->tb }} cm</div>
                                        </div>
                                        <i class="bi bi-arrow-up text-info fs-3"></i>
                                    </div>
                                </div>
                                
                                <div class="p-3 bg-white rounded-3 shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="text-muted">Umur Saat Periksa</span>
                                            <div class="fw-bold text-warning fs-4">{{ $pemeriksaanTerakhir->umur }} bulan</div>
                                        </div>
                                        <i class="bi bi-calendar-heart text-warning fs-3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- STATUS GIZI -->
                        <div class="col-lg-4">
                            <div class="bg-success bg-opacity-5 rounded-4 p-4 h-100 border border-success border-opacity-10">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-success rounded-circle p-2 me-3">
                                        <i class="bi bi-heart-pulse text-white"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-0">Status Gizi</h5>
                                </div>
                                
                                <!-- MIRIP DATA PENGUKURAN TAPI DENGAN ICON SENDIRI -->
                                <div class="mb-3 p-3 bg-white rounded-3 shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="text-muted">BB sesuai Umur</span>
                                            <div class="fw-bold text-success fs-4">
                                                {{ $pemeriksaanTerakhir->kesimpulan_bbu ?: 'Dihitung...' }}
                                            </div>
                                        </div>
                                        <i class="bi bi-heart-fill text-success fs-3"></i>
                                    </div>
                                </div>
                                
                                <div class="mb-3 p-3 bg-white rounded-3 shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="text-muted">TB sesuai Umur</span>
                                            <div class="fw-bold text-info fs-4">
                                                {{ $pemeriksaanTerakhir->kesimpulan_tbuu ?: 'Dihitung...' }}
                                            </div>
                                        </div>
                                        <i class="bi bi-activity text-info fs-3"></i>
                                    </div>
                                </div>
                                
                                <div class="p-3 bg-white rounded-3 shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="text-muted">BB sesuai TB</span>
                                            <div class="fw-bold text-warning fs-4">
                                                {{ $pemeriksaanTerakhir->kesimpulan_bbtb ?: 'Dihitung...' }}
                                            </div>
                                        </div>
                                        <i class="bi bi-shield-fill-check text-warning fs-3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
             
                        <div class="col-lg-4">
                            @php
                                // ✅ PASTIKAN COLOR CLASS SYNC DENGAN STATUS KESEHATAN
                                $statusClass = $statusKesehatan['class'] ?? 'secondary';
                                
                                // 🔄 MAPPING YANG BENAR
                                $colorMapping = [
                                    'Sehat' => 'success',
                                    'Perlu Perhatian' => 'warning', 
                                    'Butuh Penanganan Segera' => 'danger',
                                    'Belum Ada Data' => 'secondary'
                                ];
                                
                                // ✅ OVERRIDE DENGAN MAPPING YANG BENAR
                                $correctClass = $colorMapping[$statusKesehatan['status']] ?? $statusClass;
                            @endphp
                            
                            <div class="bg-light rounded-4 p-4 h-100 border border-{{ $correctClass }} border-3 shadow-sm">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-{{ $correctClass }} rounded-circle p-2 me-3">
                                        <i class="bi bi-lightbulb text-white"></i>
                                    </div>
                                    <h5 class="text-muted">Saran untuk Orang Tua</h5>
                                </div>
                                
                                @if($statusKesehatan['status'] == 'Sehat')
                                <div class="alert alert-success border-0 mb-3">
                                    <div class="d-flex">
                                        <i class="bi bi-check-circle-fill me-2 mt-1"></i>
                                        <div>
                                            <strong>Alhamdulillah!</strong><br>
                                            Anak Anda dalam kondisi sehat. Terus berikan:
                                            <ul class="mb-0 mt-2">
                                                <li>Makanan bergizi seimbang</li>
                                                <li>ASI/susu sesuai umur</li>
                                                <li>Imunisasi rutin</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @elseif($statusKesehatan['status'] == 'Perlu Perhatian')
                                <div class="alert alert-warning border-0 mb-3">
                                    <div class="d-flex">
                                        <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                                        <div>
                                            <strong>Perlu Perhatian</strong><br>
                                            Konsultasikan dengan dokter:
                                            <ul class="mb-0 mt-2">
                                                <li>Gejala yang dialami anak</li>
                                                <li>Pola makan dan tidur</li>
                                                <li>Rencana pemeriksaan lanjutan</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @elseif($statusKesehatan['status'] == 'Belum Ada Data')
                                <div class="alert alert-info border-0 mb-3">
                                    <div class="d-flex">
                                        <i class="bi bi-info-circle-fill me-2 mt-1"></i>
                                        <div>
                                            <strong>Belum Ada Data</strong><br>
                                            Mulai pantau kesehatan anak:
                                            <ul class="mb-0 mt-2">
                                                <li>Lakukan pemeriksaan pertama</li>
                                                <li>Catat berat dan tinggi badan</li>
                                                <li>Tanyakan jadwal imunisasi</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="alert alert-danger border-0 mb-3">
                                    <div class="d-flex">
                                        <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                                        <div>
                                            <strong>Segera ke Puskesmas!</strong><br>
                                            Anak memerlukan pemeriksaan lanjutan:
                                            <ul class="mb-0 mt-2">
                                                <li>Bawa kartu kesehatan anak</li>
                                                <li>Catat semua gejala</li>
                                                <li>Jangan tunda pemeriksaan</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                {{-- <div class="bg-white rounded-3 p-3 shadow-sm mt-3 border border-{{ $correctClass }} border-2">
                                    <div class="text-center">
                                        <i class="bi bi-telephone text-{{ $correctClass }} fs-4 mb-2"></i>
                                        <div class="text-muted">Butuh Bantuan?</div>
                                        <small class="text-muted">Hubungi Kader Posyandu</small>
                                    </div>
                                </div> --}}
                                <div class="bg-white rounded-3 p-3 shadow-sm mt-3 border border-{{ $correctClass }} border-2">
                                    <div class="text-center">
                                        <i class="bi bi-telephone text-{{ $correctClass }} fs-4 mb-2"></i>
                                        <div class="text-muted">Butuh Bantuan?</div>
                                        <small class="text-muted">Hubungi Puskesmas</small>
                                        <div class="fw-bold text-{{ $correctClass }} mt-2">
                                            📞 024 8445809
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- EMPTY STATE UNTUK ORANG TUA -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="bg-light rounded-circle p-4 d-inline-flex align-items-center justify-content-center mb-4">
                        <i class="bi bi-calendar-plus fs-1 text-primary"></i>
                    </div>
                    <h3 class="text-dark fw-bold mb-3">👶 Belum Ada Catatan Kesehatan</h3>
                    <p class="text-muted mb-4 fs-5">
                        Untuk memulai pemantauan kesehatan si kecil, silakan lakukan pemeriksaan pertama.<br>
                        <strong>Pemeriksaan rutin penting untuk tumbuh kembang anak!</strong>
                    </p>
                    
                    <div class="bg-light rounded-4 p-4 mb-4">
                        <h5 class="text-muted">📋 Yang Akan Diperiksa:</h5>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <i class="bi bi-speedometer2 text-primary fs-3 mb-2"></i>
                                    <div class="fw-semibold">Berat Badan</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <i class="bi bi-arrows-vertical text-info fs-3 mb-2"></i>
                                    <div class="fw-semibold">Tinggi Badan</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <i class="bi bi-heart-pulse text-success fs-3 mb-2"></i>
                                    <div class="fw-semibold">Status Gizi</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <i class="bi bi-shield-check text-warning fs-3 mb-2"></i>
                                    <div class="fw-semibold">Imunisasi</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                   
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- ✅ MODERN STYLES -->
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
}

.bg-gradient-light {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.chart-container {
    position: relative;
    width: 100%;
}

.chart-container canvas {
    width: 100% !important;
    height: 100% !important;
}

/* Responsive chart heights */
@media (min-width: 1200px) {
    .chart-container { height: 350px; }
}

@media (min-width: 768px) and (max-width: 1199px) {
    .chart-container { height: 300px; }
}

@media (max-width: 767px) {
    .chart-container { height: 250px; }
}

/* Better readability for parents */
.alert {
    font-size: 0.95rem;
    line-height: 1.6;
}

.card-title {
    font-size: 1.1rem;
}

@media (max-width: 576px) {
    .card-title {
        font-size: 1rem;
    }
}
</style>

<!-- ✅ CHART.JS LIBRARY -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ✅ DATA DARI PHP
    const chartData = @json($chartData);
    
    // ✅ GRAFIK PERTUMBUHAN YANG MUDAH DIPAHAMI ORANG TUA
    const growthCtx = document.getElementById('growthChart');
    if (growthCtx && chartData.labels.length > 0) {
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: '⚖️ Berat Badan (kg)',
                        data: chartData.bb,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        borderWidth: 4,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: '#0d6efd',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 3,
                        pointRadius: 8,
                        pointHoverRadius: 12,
                        yAxisID: 'y'
                    },
                    {
                        label: '📏 Tinggi Badan (cm)',
                        data: chartData.tb,
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        borderWidth: 4,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: '#dc3545',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 3,
                        pointRadius: 8,
                        pointHoverRadius: 12,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 25,
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            color: '#333'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: {
                            size: 16,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 14
                        },
                        cornerRadius: 10,
                        displayColors: true,
                        padding: 15,
                        callbacks: {
                            title: function(context) {
                                return '📅 Bulan: ' + context[0].label;
                            },
                            label: function(context) {
                                if (context.datasetIndex === 0) {
                                    return '⚖️ Berat: ' + context.parsed.y + ' kg';
                                } else {
                                    return '📏 Tinggi: ' + context.parsed.y + ' cm';
                                }
                            },
                            afterBody: function() {
                                return '\n💡 Tip: Garis naik menunjukkan pertumbuhan yang baik!';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12,
                                weight: 'bold'
                            },
                            color: '#555'
                        },
                        title: {
                            display: true,
                            text: '📅 Waktu Pemeriksaan',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            color: '#333'
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: '⚖️ Berat Badan (kg)',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            color: '#0d6efd'
                        },
                        grid: {
                            color: 'rgba(13, 110, 253, 0.1)'
                        },
                        ticks: {
                            font: {
                                size: 12,
                                weight: 'bold'
                            },
                            color: '#0d6efd'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: '📏 Tinggi Badan (cm)',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            color: '#dc3545'
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                        ticks: {
                            font: {
                                size: 12,
                                weight: 'bold'
                            },
                            color: '#dc3545'
                        }
                    }
                }
            }
        });
    } else if (growthCtx) {
        // Show message for no data
        const ctx = growthCtx.getContext('2d');
        ctx.font = '16px Arial';
        ctx.fillStyle = '#6c757d';
        ctx.textAlign = 'center';
        ctx.fillText('Belum ada data untuk ditampilkan', ctx.canvas.width/2, ctx.canvas.height/2);
        ctx.fillText('Lakukan pemeriksaan pertama untuk melihat grafik', ctx.canvas.width/2, ctx.canvas.height/2 + 25);
    }
    
    // ✅ RESIZE HANDLER
    window.addEventListener('resize', function() {
        Chart.instances.forEach(chart => {
            chart.resize();
        });
    });
});
</script>

@endsection