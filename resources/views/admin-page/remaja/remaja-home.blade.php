{{-- filepath: c:\laragon\www\posyandu\resources\views\admin-page\remaja\remaja-home.blade.php --}}

@extends('admin-layouts.main')
@section('title', 'Dashboard Kesehatan - ' . $user->nama)
<link rel="stylesheet" href="{{ asset('css/input-pemeriksaan.css') }}">

@section('content')
<div class="container-fluid px-4">
    <!-- ✅ HEADER UNTUK REMAJA -->
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
                                                <span class="text-white-50 me-2 fw-normal">🧑‍🎓 Umur:</span>
                                                <span class="fw-semibold">
                                                    @if($user->tanggal_lahir)
                                                        {{ \Carbon\Carbon::parse($user->tanggal_lahir)->age }} tahun
                                                    @else
                                                        Belum diisi
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
                                    <strong>Hai {{ $user->nama }}!</strong> Monitor kesehatanmu di sini. 
                                    Jaga pola makan, olahraga teratur, dan jangan lupa cek kesehatan rutin ya! 💪
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center d-none d-md-block">
                            <div class="bg-white bg-opacity-10 rounded-circle p-4 d-inline-block">
                                <i class="bi bi-person-standing fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ RINGKASAN KESEHATAN REMAJA -->
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

        <!-- Progress Berat Badan -->
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
                            Turun, perhatikan pola makan 
                        @elseif($pemeriksaanTerakhir && $pemeriksaanTerakhir->bb)
                            Stabil
                        @else 
                            Data pertama 
                        @endif
                    </small>
                </div>
            </div>
        </div>

        <!-- IMT Status -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 bg-light">
                <div class="card-body text-center p-3 p-lg-4">
                    @php
                        $imtClass = 'info';
                        $imtIcon = 'calculator';
                        $imtText = 'Belum Ada';
                        
                        if ($pemeriksaanTerakhir && $pemeriksaanTerakhir->kesimpulan_imt) {
                            $imt = $pemeriksaanTerakhir->kesimpulan_imt;
                            if ($imt === 'Normal') {
                                $imtClass = 'success';
                                $imtIcon = 'check-circle';
                                $imtText = 'Normal';
                            } elseif (in_array($imt, ['Kurus', 'Gemuk'])) {
                                $imtClass = 'warning';
                                $imtIcon = 'exclamation-triangle';
                                $imtText = $imt;
                            } else {
                                $imtClass = 'danger';
                                $imtIcon = 'exclamation-circle';
                                $imtText = $imt;
                            }
                        }
                    @endphp
                    
                    <div class="bg-{{ $imtClass }} rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-{{ $imtIcon }} text-white fs-4"></i>
                    </div>
                    <h2 class="fw-bold text-{{ $imtClass }} mb-1">{{ $imtText }}</h2>
                    <p class="mb-1 text-dark fw-semibold">Status IMT</p>
                    <small class="text-muted">
                        @if($pemeriksaanTerakhir && $pemeriksaanTerakhir->nilai_imt)
                            {{ $pemeriksaanTerakhir->nilai_imt }} kg/m²
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
                            'Sehat' => 'heart-fill',
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
        <!-- GRAFIK KESEHATAN REMAJA -->
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title fw-bold text-dark mb-1 d-flex align-items-center">
                                <p class="text-muted">📊 Grafik Kesehatan Remaja</p>
                            </h4>
                            <p class="text-muted mb-0">Pantau perkembangan BB, TB, dan IMT dari waktu ke waktu</p>
                        </div>
                        <div class="text-end">
                            <small class="badge bg-info text-white px-3 py-2">
                                <i class="bi bi-lightbulb me-1"></i>
                                Tips: Konsisten = Sehat!
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info border-0 mb-4">
                        <div class="d-flex">
                            <i class="bi bi-info-circle-fill me-2 mt-1"></i>
                            <div>
                                <strong>Grafik ini menunjukkan:</strong> Perkembangan berat badan dan tinggi badan dari setiap pemeriksaan. 
                                Hover di titik grafik untuk melihat perubahan dari pemeriksaan sebelumnya!
                                <div class="mt-2">
                                    <span class="badge bg-primary ms-0">Biru = Berat Badan</span>
                                    <span class="badge bg-success ms-2">Hijau = Tinggi Badan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- TAMBAH SUMMARY CARDS DI ATAS GRAFIK -->
                    @if($pemeriksaanTerakhir)
                    <!-- SUMMARY CARDS DENGAN TREND ANALYSIS -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="bg-primary bg-opacity-10 rounded p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="fw-bold text-primary mb-1">{{ $pemeriksaanTerakhir->bb }} kg</h5>
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
                        
                        <div class="col-md-6">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="fw-bold text-success mb-1">{{ $pemeriksaanTerakhir->tb }} cm</h5>
                                        <small class="text-muted">Tinggi Badan Saat Ini</small>
                                    </div>
                                    <div class="text-end">
                                        @if($progressTB > 0)
                                            <div class="badge bg-success px-3 py-2">
                                                <i class="bi bi-arrow-up me-1"></i>+{{ $progressTB }} cm
                                            </div>
                                            <div><small class="text-success">Tumbuh dari pemeriksaan lalu</small></div>
                                        @elseif($progressTB < 0)
                                            <div class="badge bg-warning px-3 py-2">
                                                <i class="bi bi-arrow-down me-1"></i>{{ $progressTB }} cm
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
                    </div>
                    @endif
                    
                    <div class="chart-container position-relative bg-white rounded-3 p-3" style="height: 400px;">
                        <canvas id="remajaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- STATUS KESEHATAN DETAIL -->
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-clipboard-heart me-2"></i>
                        Status Kesehatan Detail
                    </h6>
                </div>
                <div class="card-body p-4">
                    @if($pemeriksaanTerakhir)
                        <!-- Tekanan Darah -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold">🩺 Tekanan Darah</span>
                                @php
                                    $tdClass = 'success';
                                    if (strpos($pemeriksaanTerakhir->kategori_tekanan_darah ?? '', 'Hipertensi') !== false) {
                                        $tdClass = 'danger';
                                    } elseif (strpos($pemeriksaanTerakhir->kategori_tekanan_darah ?? '', 'Hipotensi') !== false) {
                                        $tdClass = 'warning';
                                    }
                                @endphp
                                <span class="badge bg-{{ $tdClass }}">
                                    {{ $pemeriksaanTerakhir->sistole }}/{{ $pemeriksaanTerakhir->diastole }} mmHg
                                </span>
                            </div>
                            <small class="text-muted">
                                {{ $pemeriksaanTerakhir->kategori_tekanan_darah ?? 'Normal' }}
                            </small>
                        </div>

                        <!-- Anemia -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold">🩸 Status Anemia</span>
                                <span class="badge bg-{{ $pemeriksaanTerakhir->status_anemia === 'Ya' ? 'danger' : 'success' }}">
                                    {{ $pemeriksaanTerakhir->status_anemia === 'Ya' ? 'Anemia' : 'Normal' }}
                                </span>
                            </div>
                            <small class="text-muted">
                                Hb: {{ $pemeriksaanTerakhir->hb }} mg/dl
                            </small>
                        </div>

                        <!-- Masalah Psikososial -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold">🧠 Psikososial</span>
                                <span class="badge bg-{{ $masalahPsikososial == 0 ? 'success' : ($masalahPsikososial >= 3 ? 'danger' : 'warning') }}">
                                    {{ $masalahPsikososial }} masalah
                                </span>
                            </div>
                            <small class="text-muted">
                                @if($masalahPsikososial == 0)
                                    Kondisi psikososial baik
                                @elseif($masalahPsikososial < 3)
                                    Ada beberapa hal yang perlu diperhatikan
                                @else
                                    Perlu konsultasi lebih lanjut
                                @endif
                            </small>
                        </div>

                        <!-- TBC Screening -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold">🫁 Skrining TBC</span>
                                <span class="badge bg-{{ $gejalaTBC == 0 ? 'success' : ($gejalaTBC >= 2 ? 'danger' : 'warning') }}">
                                    {{ $gejalaTBC }} gejala
                                </span>
                            </div>
                            <small class="text-muted">
                                @if($gejalaTBC == 0)
                                    Tidak ada gejala TBC
                                @elseif($gejalaTBC >= 2)
                                    Perlu pemeriksaan lebih lanjut
                                @else
                                    Ada gejala ringan, perlu dipantau
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

                        <!-- Tanggal Pemeriksaan Terakhir -->
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

    <!-- Tips Kesehatan Remaja -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-lightbulb me-2"></i>
                        Tips Kesehatan Remaja
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="text-center p-3">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3 d-inline-block mb-2">
                                    <i class="bi bi-apple text-success fs-4"></i>
                                </div>
                                <h6 class="fw-semibold">Pola Makan Sehat</h6>
                                <small class="text-muted">3x makan utama + 2x snack sehat. Perbanyak sayur & buah!</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-2">
                                    <i class="bi bi-bicycle text-primary fs-4"></i>
                                </div>
                                <h6 class="fw-semibold">Olahraga Rutin</h6>
                                <small class="text-muted">30 menit/hari, 5x seminggu. Bisa jalan kaki, berenang, atau bersepeda!</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3">
                                <div class="bg-info bg-opacity-10 rounded-circle p-3 d-inline-block mb-2">
                                    <i class="bi bi-moon-stars text-info fs-4"></i>
                                </div>
                                <h6 class="fw-semibold">Tidur Cukup</h6>
                                <small class="text-muted">8-9 jam per malam. Tidur cukup = pikiran fresh & tubuh sehat!</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-3 d-inline-block mb-2">
                                    <i class="bi bi-shield-check text-warning fs-4"></i>
                                </div>
                                <h6 class="fw-semibold">Hindari Rokok & Narkoba</h6>
                                <small class="text-muted">Jaga masa depanmu! Rokok & narkoba merusak kesehatan.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TREND ANALYSIS YANG LEBIH DETAIL -->
    @if($dataPemeriksaan->count() >= 2)
    {{-- <div class="row g-2 mt-4">
        <div class="col-12">
            <div class="bg-light rounded p-4">
                <h6 class="fw-semibold mb-3">
                    <i class="bi bi-graph-up-arrow me-2 text-primary"></i>
                    Analisis Perkembangan Dibanding Bulan Sebelumnya
                </h6>
                
                <div class="row g-3">
                    <!-- Progress BB -->
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary rounded-circle p-2 me-3">
                                <i class="bi bi-{{ $progressBB > 0 ? 'arrow-up' : ($progressBB < 0 ? 'arrow-down' : 'dash') }} text-white"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Berat Badan</div>
                                <small class="text-muted">
                                    @if($progressBB > 0)
                                        Naik <strong>{{ $progressBB }} kg</strong> - Bagus! Terus jaga pola makan sehat
                                    @elseif($progressBB < 0)
                                        Turun <strong>{{ abs($progressBB) }} kg</strong> - Perhatikan asupan nutrisi ya
                                    @else
                                        Stabil - Pertahankan pola hidup sehat
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Progress TB -->
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="bg-success rounded-circle p-2 me-3">
                                <i class="bi bi-{{ $progressTB > 0 ? 'arrow-up' : ($progressTB < 0 ? 'arrow-down' : 'dash') }} text-white"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Tinggi Badan</div>
                                <small class="text-muted">
                                    @if($progressTB > 0)
                                        Tumbuh <strong>{{ $progressTB }} cm</strong> - Excellent! Masih dalam masa pertumbuhan
                                    @elseif($progressTB < 0)
                                        Berkurang <strong>{{ abs($progressTB) }} cm</strong> - Kemungkinan kesalahan pengukuran
                                    @else
                                        Stabil - Normal untuk usia remaja akhir
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
                        @if($progressBB > 2)
                            Kenaikan BB signifikan sejak bulan lalu. Pastikan kenaikan dari massa otot, bukan lemak. Perbanyak olahraga!
                        @elseif($progressBB < -2)
                            Penurunan BB perlu diperhatikan. Konsultasikan dengan dokter dan perbaiki pola makan.
                        @else
                            Pertumbuhan dalam batas normal. Terus jaga pola hidup sehat dengan makan bergizi dan olahraga teratur.
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div> --}}
    @endif
</div>

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
<!-- ✅ CHART SCRIPT UNTUK REMAJA - FOKUS BB & TB -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('remajaChart');
    if (ctx) {
        // Data dari Laravel
        const chartData = @json($dataPemeriksaan->reverse()->values());
        
        const labels = chartData.map(item => {
            const date = new Date(item.tanggal_pemeriksaan);
            return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: '2-digit' });
        });
        
        const bbData = chartData.map(item => item.bb);
        const tbData = chartData.map(item => item.tb);

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
                        pointRadius: 8,
                        pointHoverRadius: 12,
                        pointBackgroundColor: '#0d6efd',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 3,
                        borderWidth: 3,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Tinggi Badan (cm)',
                        data: tbData,
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
                        tension: 0.3,
                        fill: false,
                        pointRadius: 8,
                        pointHoverRadius: 12,
                        pointBackgroundColor: '#198754',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 3,
                        borderWidth: 3,
                        yAxisID: 'y1'
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
                                size: 14,
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
                                        
                                        // Tambah trend info
                                        const dataIndex = context.dataIndex;
                                        if (dataIndex > 0) {
                                            const currentValue = context.parsed.y;
                                            const previousValue = bbData[dataIndex - 1];
                                            const diff = (currentValue - previousValue).toFixed(1);
                                            if (diff > 0) {
                                                label += ` (↗️ +${diff} kg)`;
                                            } else if (diff < 0) {
                                                label += ` (↘️ ${diff} kg)`;
                                            } else {
                                                label += ` (→ stabil)`;
                                            }
                                        }
                                    } else if (context.dataset.label.includes('Tinggi')) {
                                        label += ' cm';
                                        
                                        // Tambah trend info
                                        const dataIndex = context.dataIndex;
                                        if (dataIndex > 0) {
                                            const currentValue = context.parsed.y;
                                            const previousValue = tbData[dataIndex - 1];
                                            const diff = (currentValue - previousValue).toFixed(1);
                                            if (diff > 0) {
                                                label += ` (↗️ +${diff} cm)`;
                                            } else if (diff < 0) {
                                                label += ` (↘️ ${diff} cm)`;
                                            } else {
                                                label += ` (→ stabil)`;
                                            }
                                        }
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
                                size: 14,
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
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Tinggi Badan (cm)',
                            color: '#198754',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                        ticks: {
                            color: '#198754',
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>

@endsection