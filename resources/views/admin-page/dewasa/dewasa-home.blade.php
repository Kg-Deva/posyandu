@extends('admin-layouts.main')
@section('title', 'Dashboard Kesehatan - ' . $user->nama)
<link rel="stylesheet" href="{{ asset('css/input-pemeriksaan.css') }}">

@section('content')
<div class="container-fluid px-4">
    <!-- ✅ HEADER UNTUK DEWASA -->
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
                                                <span class="text-white-50 me-2 fw-normal">👤 Umur:</span>
                                                <span class="fw-semibold">
                                                    @if($user->tanggal_lahir)
                                                        {{ \Carbon\Carbon::parse($user->tanggal_lahir)->age }} tahun
                                                    @else
                                                        {{ $user->umur ?? 'Belum diisi' }} tahun
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="text-white-50 me-2 fw-normal">📅 Periksa Terakhir:</span>
                                                <span class="fw-semibold">
                                                    @if($pemeriksaanTerakhir && $pemeriksaanTerakhir->tanggal_pemeriksaan)
                                                        {{ \Carbon\Carbon::parse($pemeriksaanTerakhir->tanggal_pemeriksaan)->format('d M Y') }}
                                                    @else
                                                        Belum ada pemeriksaan
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white bg-opacity-10 rounded-3 p-3 mb-3">
                                <p class="mb-0">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Hai {{ $user->nama }}!</strong> Monitor kesehatan Anda di sini. 
                                    Jaga pola hidup sehat, kontrol rutin, dan cegah penyakit tidak menular! 💪
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center d-none d-md-block">
                            <div class="bg-white bg-opacity-10 rounded-circle p-4 d-inline-block">
                                <i class="bi bi-person-check fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ RINGKASAN KESEHATAN DEWASA -->
    <div class="row g-3 g-lg-4 mb-4">
        <!-- Total Pemeriksaan -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 bg-light">
                <div class="card-body text-center p-3 p-lg-4">
                    <div class="bg-primary rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-clipboard-check text-white fs-4"></i>
                    </div>
                    <h2 class="fw-bold text-primary mb-1">{{ $totalPemeriksaan }}</h2>
                    <p class="mb-1 text-dark fw-semibold">Kali Periksa</p>
                    <small class="text-muted">Total pemeriksaan kesehatan</small>
                </div>
            </div>
        </div>

        <!-- IMT Status -->
        <div class="col-6 col-lg-3">
    <div class="card border-0 shadow-sm h-100 bg-light">
        <div class="card-body text-center p-3 p-lg-4">
            @php
                $imtClass = 'info';
                $imtIcon = 'speedometer2';
                $imtText = 'Belum Ada';
                $imtValue = '';
                
                if ($pemeriksaanTerakhir && $pemeriksaanTerakhir->bb) {
                    $bb = $pemeriksaanTerakhir->bb;
                    $imtValue = $bb . ' kg';
                    
                    // Ambil kesimpulan IMT untuk menentukan status
                    $kesimpulanIMT = $pemeriksaanTerakhir->kesimpulan_imt ?? '';
                    
                    if ($kesimpulanIMT === 'Normal') {
                        $imtClass = 'success';
                        $imtIcon = 'check-circle';
                        $imtText = 'Normal';
                    } elseif (in_array($kesimpulanIMT, ['Kurus', 'Kurang Berat Badan'])) {
                        $imtClass = 'warning';
                        $imtIcon = 'arrow-down-circle';
                        $imtText = 'Kurang';
                    } elseif (in_array($kesimpulanIMT, ['Gemuk', 'Berlebihan'])) {
                        $imtClass = 'warning';
                        $imtIcon = 'arrow-up-circle';
                        $imtText = 'Berlebih';
                    } elseif (strpos($kesimpulanIMT, 'Obesitas') !== false) {
                        $imtClass = 'danger';
                        $imtIcon = 'exclamation-circle';
                        $imtText = 'Obesitas';
                    } else {
                        $imtClass = 'info';
                        $imtIcon = 'speedometer2';
                        $imtText = 'Perlu Cek';
                    }
                }
            @endphp
            
            <div class="bg-{{ $imtClass }} rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3">
                <i class="bi bi-{{ $imtIcon }} text-white fs-4"></i>
            </div>
            <h2 class="fw-bold text-{{ $imtClass }} mb-1">{{ $imtText }}</h2>
            <p class="mb-1 text-dark fw-semibold">Status Berat Badan</p>
            <small class="text-muted">
                @if($imtValue)
                    {{ $imtValue }} 
                    @if($pemeriksaanTerakhir && $pemeriksaanTerakhir->kesimpulan_imt)
                        ({{ $pemeriksaanTerakhir->kesimpulan_imt }})
                    @endif
                @else
                    Belum ada data
                @endif
            </small>
        </div>
    </div>
</div>

        <!-- Tekanan Darah -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 bg-light">
                <div class="card-body text-center p-3 p-lg-4">
                    @php
                        $tdClass = 'info';
                        $tdIcon = 'heart';
                        $tdText = 'Belum Ada';
                        $tdValue = '';
                        
                        if ($pemeriksaanTerakhir && $pemeriksaanTerakhir->sistole && $pemeriksaanTerakhir->diastole) {
                            $tdValue = $pemeriksaanTerakhir->sistole . '/' . $pemeriksaanTerakhir->diastole;
                            $kesimpulanTd = $pemeriksaanTerakhir->kesimpulan_td ?? '';
                            
                            if (strpos(strtolower($kesimpulanTd), 'normal') !== false) {
                                $tdClass = 'success';
                                $tdIcon = 'heart-fill';
                                $tdText = 'Normal';
                            } elseif (strpos(strtolower($kesimpulanTd), 'hipertensi') !== false) {
                                $tdClass = 'danger';
                                $tdIcon = 'exclamation-triangle';
                                $tdText = 'Hipertensi';
                            } else {
                                $tdClass = 'warning';
                                $tdIcon = 'exclamation-circle';
                                $tdText = 'Perhatian';
                            }
                        }
                    @endphp
                    
                    <div class="bg-{{ $tdClass }} rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-{{ $tdIcon }} text-white fs-4"></i>
                    </div>
                    <h2 class="fw-bold text-{{ $tdClass }} mb-1">{{ $tdText }}</h2>
                    <p class="mb-1 text-dark fw-semibold">Tekanan Darah</p>
                    <small class="text-muted">
                        @if($tdValue)
                            {{ $tdValue }} mmHg
                        @else
                            Belum diukur
                        @endif
                    </small>
                </div>
            </div>
        </div>

        <!-- Status Kesehatan Keseluruhan -->
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
                            'Sehat' => 'shield-check',
                            'Perlu Perhatian' => 'exclamation-triangle',
                            'Belum Ada Data' => 'question-circle',
                            default => 'exclamation-triangle-fill'
                        };
                        
                        $statusText = match($statusKesehatan['status']) {
                            'Sehat' => 'Sehat',
                            'Perlu Perhatian' => 'Perhatian',
                            'Belum Ada Data' => 'Belum Ada',
                            default => 'Rujukan'
                        };
                    @endphp
                    
                    <div class="bg-{{ $statusClass }} rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-{{ $statusIcon }} text-white fs-4"></i>
                    </div>
                    
                    <h2 class="fw-bold text-{{ $statusClass }} mb-1">{{ $statusText }}</h2>
                    <p class="mb-1 text-dark fw-semibold">Kondisi Saat Ini</p>
                    <small class="text-muted">
                        @switch($statusKesehatan['status'])
                            @case('Sehat')
                                Alhamdulillah sehat
                                @break
                            @case('Perlu Perhatian')
                                Ada yang perlu diperhatikan
                                @break
                            @case('Belum Ada Data')
                                Belum ada pemeriksaan
                                @break
                            @default
                                Segera konsultasi dokter
                        @endswitch
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- GRAFIK KESEHATAN DEWASA -->
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title fw-bold text-dark mb-1 d-flex align-items-center">
                                <p class="text-muted">📊 Grafik Kesehatan Dewasa</p>
                            </h4>
                            <p class="text-muted mb-0">Pantau perkembangan BB, TD, dan Gula Darah dari waktu ke waktu</p>
                        </div>
                        <div class="text-end">
                            <small class="badge bg-info text-white px-3 py-2">
                                <i class="bi bi-lightbulb me-1"></i>
                                Tips: Kontrol Rutin = Sehat!
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info border-0 mb-4">
                        <div class="d-flex">
                            <i class="bi bi-info-circle-fill me-2 mt-1"></i>
                            <div>
                                <strong>Grafik ini menunjukkan:</strong> Trend berat badan, tekanan darah, dan gula darah dari setiap pemeriksaan. 
                                Hover di titik grafik untuk melihat nilai detail dan status kesehatan!
                                <div class="mt-2">
                                    <span class="badge bg-primary ms-0">Biru = Berat Badan</span>
                                    <span class="badge bg-danger ms-2">Merah = Tekanan Darah</span>
                                    <span class="badge bg-warning ms-2">Kuning = Gula Darah</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SUMMARY CARDS DI ATAS GRAFIK -->
                   @if($pemeriksaanTerakhir)
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="bg-primary bg-opacity-10 rounded p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold text-primary mb-1">{{ $pemeriksaanTerakhir->bb }} kg</h6>
                                        <small class="text-muted">Berat Badan</small>
                                    </div>
                                    <div class="text-primary">
                                        <i class="bi bi-speedometer2 fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="bg-danger bg-opacity-10 rounded p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold text-danger mb-1">{{ $pemeriksaanTerakhir->sistole }}/{{ $pemeriksaanTerakhir->diastole }}</h6>
                                        <small class="text-muted">Tekanan Darah</small>
                                    </div>
                                    <div class="text-danger">
                                        <i class="bi bi-heart-pulse fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="bg-warning bg-opacity-10 rounded p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold text-warning mb-1">{{ $pemeriksaanTerakhir->gula_darah }} mg/dL</h6>
                                        <small class="text-muted">Gula Darah</small>
                                    </div>
                                    <div class="text-warning">
                                        <i class="bi bi-droplet fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ✅ GANTI CARDS STATIC JADI PROGRESS CARDS KAYAK REMAJA -->
                    {{-- <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="bg-primary bg-opacity-10 rounded p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold text-primary mb-1">{{ $pemeriksaanTerakhir->bb }} kg</h6>
                                        <small class="text-muted">Berat Badan Saat Ini</small>
                                    </div>
                                    <div class="text-end">
                                        @if($progressBB > 0)
                                            <div class="badge bg-success px-3 py-2">
                                                <i class="bi bi-arrow-up me-1"></i>+{{ $progressBB }} kg
                                            </div>
                                            <div><small class="text-success">Naik dari pemeriksaan lalu</small></div>
                                        @elseif($progressBB < 0)
                                            <div class="badge bg-warning px-3 py-2">
                                                <i class="bi bi-arrow-down me-1"></i>{{ $progressBB }} kg
                                            </div>
                                            <div><small class="text-warning">Turun dari pemeriksaan lalu</small></div>
                                        @else
                                            <div class="badge bg-info px-3 py-2">
                                                <i class="bi bi-dash me-1"></i>Stabil
                                            </div>
                                            <div><small class="text-info">Sama seperti pemeriksaan lalu</small></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="bg-danger bg-opacity-10 rounded p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold text-danger mb-1">{{ $pemeriksaanTerakhir->sistole }}/{{ $pemeriksaanTerakhir->diastole }}</h6>
                                        <small class="text-muted">Tekanan Darah Saat Ini</small>
                                    </div>
                                    <div class="text-end">
                                        @php
                                            $sistoleBefore = $pemeriksaanSebelumnya->sistole ?? $pemeriksaanTerakhir->sistole;
                                            $diastoleBefore = $pemeriksaanSebelumnya->diastole ?? $pemeriksaanTerakhir->diastole;
                                            $progressSistole = $pemeriksaanTerakhir->sistole - $sistoleBefore;
                                            $progressDiastole = $pemeriksaanTerakhir->diastole - $diastoleBefore;
                                        @endphp
                                        
                                        @if($progressSistole > 0 || $progressDiastole > 0)
                                            <div class="badge bg-warning px-3 py-2">
                                                <i class="bi bi-arrow-up me-1"></i>
                                                @if($progressSistole > 0) S+{{ $progressSistole }} @endif
                                                @if($progressDiastole > 0) D+{{ $progressDiastole }} @endif
                                            </div>
                                            <div><small class="text-warning">Naik dari pemeriksaan lalu</small></div>
                                        @elseif($progressSistole < 0 || $progressDiastole < 0)
                                            <div class="badge bg-success px-3 py-2">
                                                <i class="bi bi-arrow-down me-1"></i>
                                                @if($progressSistole < 0) S{{ $progressSistole }} @endif
                                                @if($progressDiastole < 0) D{{ $progressDiastole }} @endif
                                            </div>
                                            <div><small class="text-success">Turun dari pemeriksaan lalu</small></div>
                                        @else
                                            <div class="badge bg-info px-3 py-2">
                                                <i class="bi bi-dash me-1"></i>Stabil
                                            </div>
                                            <div><small class="text-info">Sama seperti pemeriksaan lalu</small></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="bg-warning bg-opacity-10 rounded p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold text-warning mb-1">{{ $pemeriksaanTerakhir->gula_darah }} mg/dL</h6>
                                        <small class="text-muted">Gula Darah Saat Ini</small>
                                    </div>
                                    <div class="text-end">
                                        @php
                                            $gulaBefore = $pemeriksaanSebelumnya->gula_darah ?? $pemeriksaanTerakhir->gula_darah;
                                            $progressGula = $pemeriksaanTerakhir->gula_darah - $gulaBefore;
                                        @endphp
                                        
                                        @if($progressGula > 0)
                                            <div class="badge bg-danger px-3 py-2">
                                                <i class="bi bi-arrow-up me-1"></i>+{{ $progressGula }} mg/dL
                                            </div>
                                            <div><small class="text-danger">Naik dari pemeriksaan lalu</small></div>
                                        @elseif($progressGula < 0)
                                            <div class="badge bg-success px-3 py-2">
                                                <i class="bi bi-arrow-down me-1"></i>{{ $progressGula }} mg/dL
                                            </div>
                                            <div><small class="text-success">Turun dari pemeriksaan lalu</small></div>
                                        @else
                                            <div class="badge bg-info px-3 py-2">
                                                <i class="bi bi-dash me-1"></i>Stabil
                                            </div>
                                            <div><small class="text-info">Sama seperti pemeriksaan lalu</small></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    @endif
                    
                    <div class="chart-container position-relative bg-white rounded-3 p-3" style="height: 400px;">
                        <canvas id="dewasaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- STATUS KESEHATAN DETAIL -->
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-0">
                    <h6 class="text-muted">
                        <i class="bi bi-clipboard-heart me-2"></i>
                        Status Kesehatan Detail
                    </h6>
                </div>
                <div class="card-body p-4">
                    @if($pemeriksaanTerakhir)
                        <!-- IMT Detail -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold">📏 Indeks Massa Tubuh</span>
                                @php
                                    $imtClass = 'success';
                                    if (strpos($pemeriksaanTerakhir->kesimpulan_imt ?? '', 'Gemuk') !== false) {
                                        $imtClass = 'warning';
                                    } elseif (strpos($pemeriksaanTerakhir->kesimpulan_imt ?? '', 'Obesitas') !== false) {
                                        $imtClass = 'danger';
                                    }
                                @endphp
                                <span class="badge bg-{{ $imtClass }}">
                                    {{ $pemeriksaanTerakhir->kesimpulan_imt ?? 'Normal' }}
                                </span>
                            </div>
                            <small class="text-muted">
                                IMT: {{ $pemeriksaanTerakhir->imt ? number_format($pemeriksaanTerakhir->imt, 1) : '-' }} kg/m²
                                (TB: {{ $pemeriksaanTerakhir->tb }}cm, BB: {{ $pemeriksaanTerakhir->bb }}kg)
                            </small>
                        </div>

                        <!-- Tekanan Darah Detail -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold">💓 Tekanan Darah</span>
                                @php
                                    $tdClass = 'success';
                                    if (strpos($pemeriksaanTerakhir->kesimpulan_td ?? '', 'Hipertensi') !== false) {
                                        $tdClass = 'danger';
                                    } elseif (strpos($pemeriksaanTerakhir->kesimpulan_td ?? '', 'Tinggi') !== false) {
                                        $tdClass = 'warning';
                                    }
                                @endphp
                                <span class="badge bg-{{ $tdClass }}">
                                    {{ $pemeriksaanTerakhir->sistole }}/{{ $pemeriksaanTerakhir->diastole }} mmHg
                                </span>
                            </div>
                            <small class="text-muted">
                                Status: {{ $pemeriksaanTerakhir->kesimpulan_td ?? 'Normal' }}
                                <br>Sistole: {{ $pemeriksaanTerakhir->kesimpulan_sistole ?? '-' }}
                                <br>Diastole: {{ $pemeriksaanTerakhir->kesimpulan_diastole ?? '-' }}
                            </small>
                        </div>

                        <!-- Gula Darah -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold">🩸 Gula Darah</span>
                                @php
                                    $gdClass = 'success';
                                    if (strpos($pemeriksaanTerakhir->kesimpulan_gula_darah ?? '', 'Diabetes') !== false) {
                                        $gdClass = 'danger';
                                    } elseif (strpos($pemeriksaanTerakhir->kesimpulan_gula_darah ?? '', 'Tinggi') !== false) {
                                        $gdClass = 'warning';
                                    }
                                @endphp
                                <span class="badge bg-{{ $gdClass }}">
                                    {{ $pemeriksaanTerakhir->gula_darah ?? '-' }} mg/dL
                                </span>
                            </div>
                            <small class="text-muted">
                                Status: {{ $pemeriksaanTerakhir->kesimpulan_gula_darah ?? 'Normal' }}
                            </small>
                        </div>

                        <!-- TBC Screening -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold">🫁 Skrining TBC</span>
                                @php
                                    $gejalaTBC = 0;
                                    if ($pemeriksaanTerakhir->tbc_batuk ?? false) $gejalaTBC++;
                                    if ($pemeriksaanTerakhir->tbc_demam ?? false) $gejalaTBC++;
                                    if ($pemeriksaanTerakhir->tbc_bb_turun ?? false) $gejalaTBC++;
                                    if ($pemeriksaanTerakhir->tbc_kontak ?? false) $gejalaTBC++;
                                @endphp
                                <span class="badge bg-{{ $gejalaTBC == 0 ? 'success' : ($gejalaTBC >= 2 ? 'danger' : 'warning') }}">
                                    {{ $gejalaTBC }} gejala
                                </span>
                            </div>
                            <small class="text-muted">
                                Status: {{ $pemeriksaanTerakhir->status_tbc ?? 'Normal' }}
                                @if($gejalaTBC > 0)
                                    <br>
                                    @if($pemeriksaanTerakhir->tbc_batuk) • Batuk >2 minggu @endif
                                    @if($pemeriksaanTerakhir->tbc_demam) • Demam lama @endif
                                    @if($pemeriksaanTerakhir->tbc_bb_turun) • BB turun @endif
                                    @if($pemeriksaanTerakhir->tbc_kontak) • Kontak TBC @endif
                                @endif
                            </small>
                        </div>

                        <!-- PUMA (Penyakit Paru Obstruktif Menahun) -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold">💨 Screening PUMA</span>
                                <span class="badge bg-{{ ($pemeriksaanTerakhir->skor_puma ?? 0) <= 6 ? 'success' : 'warning' }}">
                                    Skor: {{ $pemeriksaanTerakhir->skor_puma ?? 0 }}
                                </span>
                            </div>
                            <small class="text-muted">
                                Status: {{ $pemeriksaanTerakhir->status_puma ?? 'Normal' }}
                                @if($pemeriksaanTerakhir->skor_puma > 6)
                                    <br><span class="text-warning">⚠️ Perlu pemeriksaan lebih lanjut</span>
                                @endif
                            </small>
                        </div>

                        <!-- Tes Pendengaran -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold">👂 Tes Pendengaran</span>
                                @php
                                    $pendengaranOK = (
                                        $pemeriksaanTerakhir->tes_jari_kanan === 'Normal' &&
                                        $pemeriksaanTerakhir->tes_jari_kiri === 'Normal' &&
                                        $pemeriksaanTerakhir->tes_berbisik_kanan === 'Normal' &&
                                        $pemeriksaanTerakhir->tes_berbisik_kiri === 'Normal'
                                    );
                                @endphp
                                <span class="badge bg-{{ $pendengaranOK ? 'success' : 'warning' }}">
                                    {{ $pendengaranOK ? 'Normal' : 'Perlu Perhatian' }}
                                </span>
                            </div>
                            <small class="text-muted">
                                Jari Kanan: {{ $pemeriksaanTerakhir->tes_jari_kanan ?? '-' }}<br>
                                Jari Kiri: {{ $pemeriksaanTerakhir->tes_jari_kiri ?? '-' }}<br>
                                Berbisik Kanan: {{ $pemeriksaanTerakhir->tes_berbisik_kanan ?? '-' }}<br>
                                Berbisik Kiri: {{ $pemeriksaanTerakhir->tes_berbisik_kiri ?? '-' }}
                            </small>
                        </div>

                        <!-- Kontrasepsi -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold">💊 Kontrasepsi</span>
                                <span class="badge bg-{{ $pemeriksaanTerakhir->alat_kontrasepsi ? 'success' : 'secondary' }}">
                                    {{ $pemeriksaanTerakhir->alat_kontrasepsi ? 'Menggunakan' : 'Tidak Menggunakan' }}
                                </span>
                            </div>
                            <small class="text-muted">
                                @if($pemeriksaanTerakhir->alat_kontrasepsi)
                                    ✅ Sudah menggunakan alat kontrasepsi
                                @else
                                    ℹ️ Belum menggunakan alat kontrasepsi
                                @endif
                            </small>
                        </div>

                        <!-- ✅ TAMBAH EDUKASI/KONSELING -->
                        @if($pemeriksaanTerakhir && $pemeriksaanTerakhir->edukasi)
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <span class="fw-semibold">📝 Edukasi & Konseling</span>
                            </div>
                            <div class="bg-light rounded p-3">
                                <small class="text-muted">
                                    <i class="bi bi-quote text-primary me-1"></i>
                                    {{ $pemeriksaanTerakhir->edukasi }}
                                </small>
                            </div>
                            <small class="text-muted mt-1">
                                <i class="bi bi-person-check me-1"></i>
                                Oleh: {{ $pemeriksaanTerakhir->pemeriksa }}
                            </small>
                        </div>
                    @endif
                        <!-- Kontrol Berikutnya -->
                        <div class="mt-3">
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar3 me-2 text-primary"></i>
                                    <div>
                                        <small class="fw-semibold">Kontrol Berikutnya</small>
                                        <div class="small text-muted">
                                            @if($pemeriksaanTerakhir && $pemeriksaanTerakhir->tanggal_pemeriksaan)
                                                {{ \Carbon\Carbon::parse($pemeriksaanTerakhir->tanggal_pemeriksaan)->addMonth()->format('d F Y') }}
                                            @else
                                                Jadwalkan segera
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <span class="badge bg-primary px-3 py-2">
                                    @php
                                        if ($pemeriksaanTerakhir && $pemeriksaanTerakhir->tanggal_pemeriksaan) {
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
                        
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-clipboard-x text-muted mb-3" style="font-size: 3rem;"></i>
                            <h6 class="text-muted">Belum Ada Data Pemeriksaan</h6>
                            <p class="text-muted small mb-0">
                                Silakan lakukan pemeriksaan kesehatan pertama untuk melihat status kesehatan lengkap
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tips Kesehatan Dewasa -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-lightbulb me-2"></i>
                        Tips Kesehatan Dewasa
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="text-center p-3">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3 d-inline-block mb-2">
                                    <i class="bi bi-apple text-success fs-4"></i>
                                </div>
                                <h6 class="fw-semibold">Gizi Seimbang</h6>
                                <small class="text-muted">Konsumsi makanan bergizi, kurangi garam, gula, dan lemak berlebih!</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-2">
                                    <i class="bi bi-bicycle text-primary fs-4"></i>
                                </div>
                                <h6 class="fw-semibold">Aktivitas Fisik</h6>
                                <small class="text-muted">Olahraga minimal 30 menit, 3-5x seminggu. Jalan kaki, berenang, atau bersepeda!</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3">
                                <div class="bg-info bg-opacity-10 rounded-circle p-3 d-inline-block mb-2">
                                    <i class="bi bi-shield-check text-info fs-4"></i>
                                </div>
                                <h6 class="fw-semibold">Cegah PTM</h6>
                                <small class="text-muted">Kontrol rutin TD, gula darah, kolesterol. Deteksi dini = penanganan cepat!</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-3 d-inline-block mb-2">
                                    <i class="bi bi-ban text-warning fs-4"></i>
                                </div>
                                <h6 class="fw-semibold">Hindari Rokok</h6>
                                <small class="text-muted">Stop merokok, batasi alkohol, kelola stress dengan baik!</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TREND ANALYSIS -->
    @if($dataPemeriksaan->count() >= 2)
    {{-- <div class="row g-2 mt-4">
        <div class="col-12">
            <div class="bg-light rounded p-4">
                <h6 class="text-muted">
                    <i class="bi bi-graph-up-arrow me-2 text-primary"></i>
                    Analisis Perkembangan Dibanding Pemeriksaan Sebelumnya
                </h6>
                
                <div class="row g-3">
                    <!-- Progress BB -->
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary rounded-circle p-2 me-3">
                                <i class="bi bi-{{ $progressBB > 0 ? 'arrow-up' : ($progressBB < 0 ? 'arrow-down' : 'dash') }} text-white"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Berat Badan</div>
                                <small class="text-muted">
                                    @if($progressBB > 0)
                                        Naik {{ $progressBB }} kg - Pantau pola makan
                                    @elseif($progressBB < 0)
                                        Turun {{ abs($progressBB) }} kg - Perhatikan nutrisi
                                    @else
                                        Stabil - Pertahankan pola hidup sehat
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Progress TD -->
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-danger rounded-circle p-2 me-3">
                                <i class="bi bi-heart-pulse text-white"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Tekanan Darah</div>
                                <small class="text-muted">
                                    @if($pemeriksaanTerakhir && $pemeriksaanTerakhir->sistole <= 130)
                                        Normal - Terus jaga pola hidup sehat
                                    @elseif($pemeriksaanTerakhir && $pemeriksaanTerakhir->sistole <= 140)
                                        Sedikit tinggi - Kurangi garam & stress
                                    @else
                                        Tinggi - Segera konsultasi dokter
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Progress GD -->
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning rounded-circle p-2 me-3">
                                <i class="bi bi-droplet text-white"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Gula Darah</div>
                                <small class="text-muted">
                                    @if($pemeriksaanTerakhir && $pemeriksaanTerakhir->gula_darah <= 140)
                                        Normal - Jaga pola makan
                                    @elseif($pemeriksaanTerakhir && $pemeriksaanTerakhir->gula_darah <= 200)
                                        Sedikit tinggi - Kurangi gula
                                    @else
                                        Tinggi - Perlu kontrol ketat
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Rekomendasi -->
                <div class="mt-3 p-3 bg-white rounded border-start border-info border-4">
                    <div class="fw-semibold text-info mb-1">
                        <i class="bi bi-lightbulb me-1"></i>Rekomendasi:
                    </div>
                    <small class="text-muted">
                        @if($pemeriksaanTerakhir)
                            @if($pemeriksaanTerakhir->sistole > 140 || $pemeriksaanTerakhir->gula_darah > 200)
                                PENTING: Ada indikasi penyakit tidak menular. Segera konsultasi dokter untuk penanganan lebih lanjut.
                            @elseif($pemeriksaanTerakhir->sistole > 130 || $pemeriksaanTerakhir->gula_darah > 140)
                                Perlu perhatian khusus: Kurangi konsumsi garam dan gula, perbanyak olahraga, kelola stress dengan baik.
                            @else
                                Kondisi baik! Pertahankan pola hidup sehat dengan olahraga rutin, makan bergizi, dan kontrol kesehatan berkala.
                            @endif
                        @else
                            Lakukan pemeriksaan kesehatan rutin untuk deteksi dini penyakit tidak menular.
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div> --}}
    @endif
</div>

<!-- ✅ MODERN STYLES -->
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
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
    .chart-container { height: 400px; }
}

@media (min-width: 768px) and (max-width: 1199px) {
    .chart-container { height: 350px; }
}

@media (max-width: 767px) {
    .chart-container { height: 300px; }
}
</style>

<!-- ✅ CHART SCRIPT UNTUK DEWASA - FOKUS BB, TD, GULA DARAH -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('dewasaChart');
    if (ctx) {
        // Data dari Laravel
        const chartData = @json($dataPemeriksaan->reverse()->values());
        
        const labels = chartData.map(item => {
            const date = new Date(item.tanggal_pemeriksaan);
            return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: '2-digit' });
        });
        
        const bbData = chartData.map(item => item.bb);
        const sistoleData = chartData.map(item => item.sistole || 120);
        const gulaDarahData = chartData.map(item => item.gula_darah || 100);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Berat Badan (kg)',
                        data: bbData,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        tension: 0.3,
                        fill: false,
                        pointRadius: 6,
                        pointHoverRadius: 10,
                        pointBackgroundColor: '#0d6efd',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        borderWidth: 3,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Tekanan Darah Sistole (mmHg)',
                        data: sistoleData,
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        tension: 0.3,
                        fill: false,
                        pointRadius: 6,
                        pointHoverRadius: 10,
                        pointBackgroundColor: '#dc3545',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        borderWidth: 3,
                        yAxisID: 'y1'
                    },
                    {
                        label: 'Gula Darah (mg/dL)',
                        data: gulaDarahData,
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        tension: 0.3,
                        fill: false,
                        pointRadius: 6,
                        pointHoverRadius: 10,
                        pointBackgroundColor: '#ffc107',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        borderWidth: 3,
                        yAxisID: 'y2'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20,
                            font: {
                                size: 13,
                                weight: 'bold'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#ffffff',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            title: function(context) {
                                return 'Pemeriksaan: ' + context[0].label;
                            },
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y;
                                    if (context.dataset.label.includes('Berat')) {
                                        label += ' kg';
                                        // Status BB berdasarkan IMT
                                        if (context.parsed.y >= 80) label += ' (Perlu Perhatian)';
                                        else if (context.parsed.y >= 70) label += ' (Normal)';
                                        else label += ' (Kurang)';
                                    } else if (context.dataset.label.includes('Tekanan')) {
                                        label += ' mmHg';
                                        // Status TD
                                        if (context.parsed.y >= 140) label += ' (Hipertensi)';
                                        else if (context.parsed.y >= 130) label += ' (Sedikit Tinggi)';
                                        else label += ' (Normal)';
                                    } else if (context.dataset.label.includes('Gula')) {
                                        label += ' mg/dL';
                                        // Status Gula Darah
                                        if (context.parsed.y >= 200) label += ' (Diabetes)';
                                        else if (context.parsed.y >= 140) label += ' (Sedikit Tinggi)';
                                        else label += ' (Normal)';
                                    }
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Tanggal Pemeriksaan',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.1)',
                            borderDash: [2, 2]
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Berat Badan (kg)',
                            color: '#0d6efd',
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: 'rgba(13, 110, 253, 0.2)',
                            borderDash: [3, 3]
                        },
                        ticks: {
                            color: '#0d6efd',
                            font: {
                                size: 11,
                                weight: 'bold'
                            }
                        },
                        min: 40,
                        max: 120
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Tekanan Darah (mmHg)',
                            color: '#dc3545',
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                        ticks: {
                            color: '#dc3545',
                            font: {
                                size: 11,
                                weight: 'bold'
                            }
                        },
                        min: 80,
                        max: 200
                    },
                    y2: {
                        type: 'linear',
                        display: false, // Hidden untuk tidak terlalu ramai
                        position: 'right',
                        min: 80,
                        max: 300
                    }
                }
            }
        });
    }
});
</script>

@endsection