@extends('admin-layouts.main')
@section('title', 'Dashboard Kesehatan - ' . $user->nama)
<link rel="stylesheet" href="{{ asset('css/input-pemeriksaan.css') }}">

@section('content')
<div class="container-fluid px-4">
    <!-- ✅ HEADER UNTUK IBU HAMIL -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="position-relative overflow-hidden rounded-4 shadow-lg bg-gradient-primary text-white">
                <div class="p-4 p-md-5">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-3">
                                <div>
                                    <h1 class="mb-1 fw-bold">🤱 Dashboard Kesehatan {{ $user->nama }}</h1>
                                    <div class="mb-0 opacity-90 fs-5">
                                        <div class="d-flex flex-column flex-md-row gap-2 gap-md-4">
                                            <div class="d-flex align-items-center">
                                                <span class="text-white-50 me-2 fw-normal">🤰 Usia Kehamilan:</span>
                                                <span class="fw-semibold">
                                                    {{ $pemeriksaanTerbaru ? $pemeriksaanTerbaru->usia_kehamilan . ' minggu' : 'Belum ada data' }}
                                                </span>
                                            </div>
                                            @if($pemeriksaanTerbaru)
                                            <div class="d-flex align-items-center">
                                                <span class="text-white-50 me-2 fw-normal">📅 Periksa Terakhir:</span>
                                                <span class="fw-semibold">{{ $pemeriksaanTerbaru->tanggal_pemeriksaan->format('d M Y') }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white bg-opacity-10 rounded-3 p-3 mb-3">
                                <p class="mb-0">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Dashboard Kehamilan:</strong> Pantau kesehatan ibu dan janin selama masa kehamilan dengan pemeriksaan ANC rutin.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center d-none d-md-block">
                            <div class="bg-white bg-opacity-10 rounded-circle p-4 d-inline-block">
                                <i class="bi bi-heart-pulse fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ RINGKASAN KESEHATAN IBU HAMIL -->
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
                    <small class="text-muted">Total pemeriksaan yang sudah dilakukan</small>
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
                        @elseif($pemeriksaanTerbaru && $pemeriksaanTerbaru->bb)
                            <span class="text-info">{{ $pemeriksaanTerbaru->bb }} kg</span>
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
                        @elseif($pemeriksaanTerbaru && $pemeriksaanTerbaru->bb)
                            Berat stabil
                        @else 
                            Data pertama 
                        @endif
                    </small>
                </div>
            </div>
        </div>

        <!-- Status LILA -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 bg-light">
                <div class="card-body text-center p-3 p-lg-4">
                    @php
                        $lila = $pemeriksaanTerbaru ? $pemeriksaanTerbaru->lila : null;
                        $lilaClass = 'info';
                        $lilaIcon = 'rulers';
                        $lilaText = 'Belum Ada';
                        
                        if ($lila) {
                            if ($lila >= 23.5) {
                                $lilaClass = 'success';
                                $lilaIcon = 'check-circle';
                                $lilaText = 'Normal';
                            } else {
                                $lilaClass = 'warning';
                                $lilaIcon = 'exclamation-triangle';
                                $lilaText = 'Kurang Gizi';
                            }
                        }
                    @endphp
                    
                    <div class="bg-{{ $lilaClass }} rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-{{ $lilaIcon }} text-white fs-4"></i>
                    </div>
                    <h2 class="fw-bold text-{{ $lilaClass }} mb-1">{{ $lilaText }}</h2>
                    <p class="mb-1 text-dark fw-semibold">Status LILA</p>
                    <small class="text-muted">
                        @if($lila)
                            LILA: {{ $lila }} cm
                            <br><span class="text-info">Lingkar Lengan Atas</span>
                        @else
                            Belum diukur
                            <br><span class="text-info">Lingkar Lengan Atas</span>
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
        <!-- GRAFIK KESEHATAN IBU HAMIL -->
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title fw-bold text-dark mb-1 d-flex align-items-center">
                                <p class="text-muted">📊 Grafik Kesehatan Kehamilan</p>
                            </h4>
                            <p class="text-muted mb-0">Pantau perkembangan BB, TD, dan status gizi dari waktu ke waktu</p>
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
                                <strong>Grafik ini menunjukkan:</strong> Perkembangan berat badan dan tekanan darah dari setiap pemeriksaan ANC. 
                                Hover di titik grafik untuk melihat perubahan dari pemeriksaan sebelumnya!
                                <div class="mt-2">
                                    <small class="text-muted">
                                        💡 <strong>Tips:</strong> Kenaikan BB ideal 0.3-0.5 kg per minggu di trimester 2-3, TD normal <140/90 mmHg
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- SUMMARY CARDS DI ATAS GRAFIK -->
                    @if($pemeriksaanTerbaru)
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="bg-primary bg-opacity-10 rounded p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-semibold text-primary">Berat Badan Terakhir</div>
                                        <div class="text-muted small">{{ $pemeriksaanTerbaru->bb }} kg</div>
                                    </div>
                                    <div class="text-primary">
                                        <i class="bi bi-arrow-{{ $progressBB > 0 ? 'up' : ($progressBB < 0 ? 'down' : 'right') }} fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="bg-success bg-opacity-10 rounded p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-semibold text-success">Tekanan Darah</div>
                                        <div class="text-muted small">{{ $pemeriksaanTerbaru->sistole }}/{{ $pemeriksaanTerbaru->diastole }} mmHg</div>
                                    </div>
                                    <div class="text-success">
                                        <i class="bi bi-heart-pulse fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="chart-container position-relative bg-white rounded-3 p-3" style="height: 400px;">
                        <canvas id="ibuHamilChart"></canvas>
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
                    @if($pemeriksaanTerbaru)
                        <!-- Tekanan Darah -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold">Tekanan Darah</span>
                                @php
                                    $tdClass = $pemeriksaanTerbaru->kategori_tekanan_darah == 'Normal' ? 'success' : 'warning';
                                @endphp
                                <span class="badge bg-{{ $tdClass }}">
                                    {{ $pemeriksaanTerbaru->sistole }}/{{ $pemeriksaanTerbaru->diastole }}
                                </span>
                            </div>
                            <small class="text-muted">
                                {{ $pemeriksaanTerbaru->kategori_tekanan_darah ?? 'Normal' }}
                            </small>
                        </div>

                        <!-- Status Tekanan Darah Sesuai KIA -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold">Status Tekanan Darah Sesuai KIA</span>
                                @php
                                    $kiaText = strtolower($pemeriksaanTerbaru->status_td_kia ?? '');
                                    if(str_contains($kiaText, 'normal')) {
                                        $kiaClass = 'success';
                                    } elseif(str_contains($kiaText, 'rujuk')) {
                                        $kiaClass = 'danger';
                                    } elseif(str_contains($kiaText, 'hipo') || str_contains($kiaText, 'tinggi')) {
                                        $kiaClass = 'warning';
                                    } else {
                                        $kiaClass = 'secondary';
                                    }
                                @endphp
                                <span class="badge bg-{{ $kiaClass }}">
                                    {{ $kiaClass == 'success' ? 'Normal' : ($pemeriksaanTerbaru->status_td_kia ?? '-') }}
                                </span>
                            </div>
                            <small class="text-muted">
                                {{ $pemeriksaanTerbaru->status_td_kia ?? '-' }}
                            </small>
                        </div>

                        <!-- Suplementasi -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold">Tablet Tambah Darah</span>
                                <span class="badge bg-{{ ($pemeriksaanTerbaru->jumlah_tablet_fe ?? 0) > 0 ? 'success' : 'secondary' }}">
                                    {{ ($pemeriksaanTerbaru->jumlah_tablet_fe ?? 0) > 0 ? 'Mendapat' : 'Belum Mendapat' }}
                                </span>
                            </div>
                            <small class="text-muted">
                                TTD: {{ $pemeriksaanTerbaru->jumlah_tablet_fe ?? 0 }} tablet
                                @if($pemeriksaanTerbaru->mendapat_mt_bumil_kek)
                                    <br>MT KEK: {{ $pemeriksaanTerbaru->jumlah_porsi_mt ?? 0 }} porsi
                                @endif
                            </small>
                        </div>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold">Kelas Ibu</span>
                                <span class="badge bg-{{ ($pemeriksaanTerbaru->mengikuti_kelas_ibu ?? 'Tidak') === 'Ya' ? 'success' : 'secondary' }}">
                                    {{ ($pemeriksaanTerbaru->mengikuti_kelas_ibu ?? 'Tidak') === 'Ya' ? 'Mengikuti' : 'Belum Mengikuti' }}
                                </span>
                            </div>
                            <small class="text-muted">
                                Partisipasi: {{ $pemeriksaanTerbaru->mengikuti_kelas_ibu ?? 'Tidak' }}
                                @if($pemeriksaanTerbaru->frekuensi_kelas_ibu && $pemeriksaanTerbaru->mengikuti_kelas_ibu === 'Ya')
                                    <br>Frekuensi: {{ $pemeriksaanTerbaru->frekuensi_kelas_ibu }}
                                @endif
                                @if($pemeriksaanTerbaru->mengikuti_kelas_ibu === 'Ya')
                                    <br><span class="text-success">✅ Sangat baik! Terus ikuti kelasnya</span>
                                @else
                                    <br><span class="text-warning">⚠️ Disarankan mengikuti kelas ibu</span>
                                @endif
                            </small>
                        </div>
                        <!-- ✅ FIX TBC FIELD NAMES - SESUAI DATABASE -->
                        <!-- TBC Screening -->
                       <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold">Skrining TBC</span>
                                @php
                                    $gejalaTBC = 0;
                                    $daftarGejala = [];
                                    if ($pemeriksaanTerbaru->batuk_terus_menerus ?? false) {
                                        $gejalaTBC++;
                                        $daftarGejala[] = 'Batuk terus-menerus > 2 minggu';
                                    }
                                    if ($pemeriksaanTerbaru->demam_2_minggu ?? false) {
                                        $gejalaTBC++;
                                        $daftarGejala[] = 'Demam > 2 minggu';
                                    }
                                    if ($pemeriksaanTerbaru->bb_tidak_naik ?? false) {
                                        $gejalaTBC++;
                                        $daftarGejala[] = 'Berat badan tidak naik';
                                    }
                                    if ($pemeriksaanTerbaru->kontak_tbc ?? false) {
                                        $gejalaTBC++;
                                        $daftarGejala[] = 'Kontak dengan penderita TBC';
                                    }
                                @endphp
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

                                @if($gejalaTBC > 0)
                                    <br><span class="text-warning">Gejala: {{ implode(', ', $daftarGejala) }}</span>
                                @endif
                            </small>
                        </div>
                        
                        <!-- ✅ TAMBAH EDUKASI/KONSELING -->
                        @if($pemeriksaanTerbaru && $pemeriksaanTerbaru->edukasi)
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <span class="fw-semibold">Edukasi & Konseling</span>
                            </div>
                            <div class="bg-light rounded p-3">
                                <small class="text-muted">
                                    {{ $pemeriksaanTerbaru->edukasi }}
                                </small>
                            </div>
                            <small class="text-muted mt-1">
                                <i class="bi bi-person-check me-1"></i>
                                Oleh: {{ $pemeriksaanTerbaru->pemeriksa }}
                            </small>
                        </div>
                        @endif

                        <!-- Tanggal Pemeriksaan Terakhir -->
                        <!-- Kontrol Berikutnya -->
                        <div class="mt-3">
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar3 me-2 text-primary"></i>
                                    <div>
                                        <small class="fw-semibold">Kontrol Berikutnya</small>
                                        <div class="small text-muted">
                                            @if($pemeriksaanTerbaru->tanggal_pemeriksaan)
                                                {{ \Carbon\Carbon::parse($pemeriksaanTerbaru->tanggal_pemeriksaan)->addMonth()->format('d F Y') }}
                                            @else
                                                Jadwalkan segera
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <span class="badge bg-primary px-3 py-2">
                                    @php
                                        if ($pemeriksaanTerbaru->tanggal_pemeriksaan) {
                                            $nextDate = \Carbon\Carbon::parse($pemeriksaanTerbaru->tanggal_pemeriksaan)->addMonth();
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
                                Silakan lakukan pemeriksaan ANC pertama untuk melihat status kesehatan lengkap
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tips Kesehatan Ibu Hamil -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-lightbulb me-2"></i>
                        Tips Kesehatan Ibu Hamil
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
                                <small class="text-muted">Makan makanan bergizi, perbanyak sayur & buah, minum vitamin!</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-2">
                                    <i class="bi bi-person-walking text-primary fs-4"></i>
                                </div>
                                <h6 class="fw-semibold">Olahraga Ringan</h6>
                                <small class="text-muted">Jalan santai, senam hamil, atau yoga. Konsultasi dokter dulu ya!</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3">
                                <div class="bg-info bg-opacity-10 rounded-circle p-3 d-inline-block mb-2">
                                    <i class="bi bi-moon-stars text-info fs-4"></i>
                                </div>
                                <h6 class="fw-semibold">Istirahat Cukup</h6>
                                <small class="text-muted">8-9 jam tidur malam, plus istirahat siang jika lelah!</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-3 d-inline-block mb-2">
                                    <i class="bi bi-shield-check text-warning fs-4"></i>
                                </div>
                                <h6 class="fw-semibold">Hindari Bahaya</h6>
                                <small class="text-muted">No rokok, alkohol, obat sembarangan. Jaga kebersihan!</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TREND ANALYSIS YANG LEBIH DETAIL -->
    @if($dataPemeriksaan->count() >= 2)
    <div class="row g-2 mt-4">
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
                                        Naik {{ $progressBB }} kg sejak pemeriksaan terakhir
                                    @elseif($progressBB < 0)
                                        Turun {{ abs($progressBB) }} kg sejak pemeriksaan terakhir
                                    @else
                                        Tidak ada perubahan
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status LILA -->
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="bg-success rounded-circle p-2 me-3">
                                <i class="bi bi-rulers text-white"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Status LILA</div>
                                <small class="text-muted">
                                    @if($pemeriksaanTerbaru && $pemeriksaanTerbaru->lila >= 23.5)
                                        Normal ({{ $pemeriksaanTerbaru->lila }} cm)
                                    @elseif($pemeriksaanTerbaru)
                                        KEK ({{ $pemeriksaanTerbaru->lila }} cm)
                                    @else
                                        Belum diukur
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
                        @if($progressBB > 1)
                            Kenaikan BB baik! Pastikan dari nutrisi sehat, bukan makanan berlebihan. Tetap jaga pola makan seimbang.
                        @elseif($progressBB < -1)
                            Penurunan BB perlu diperhatikan. Konsultasikan dengan dokter dan perbaiki asupan gizi.
                        @else
                            Pertumbuhan dalam batas normal. Terus jaga pola hidup sehat dengan makan bergizi dan kontrol rutin.
                        @endif
                    </small>
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

<!-- ✅ CHART SCRIPT UNTUK IBU HAMIL - FOKUS BB & TD -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('ibuHamilChart');
    if (ctx) {
        // Data dari Laravel
        const chartData = @json($dataPemeriksaan->reverse()->values());
        
        const labels = chartData.map(item => {
            const date = new Date(item.tanggal_pemeriksaan);
            return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: '2-digit' });
        });
        
        const bbData = chartData.map(item => item.bb);
        const sistoleData = chartData.map(item => item.sistole || 120); // ✅ KEMBALIKAN KE SISTOLE

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
                        label: 'Tekanan Darah Sistole (mmHg)', // ✅ KEMBALIKAN LABEL
                        data: sistoleData, // ✅ KEMBALIKAN DATA
                        borderColor: '#dc3545', // ✅ WARNA MERAH
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        tension: 0.3,
                        fill: false,
                        pointRadius: 8,
                        pointHoverRadius: 12,
                        pointBackgroundColor: '#dc3545',
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
                                return 'Pemeriksaan ANC: ' + context[0].label;
                            },
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y;
                                    label += context.dataset.label.includes('Berat') ? ' kg' : ' mmHg'; // ✅ KEMBALIKAN UNIT
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
                            text: 'Tanggal Pemeriksaan ANC',
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
                        },
                        min: 40,
                        max: 100
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Tekanan Darah Sistole (mmHg)', // ✅ KEMBALIKAN LABEL
                            color: '#dc3545', // ✅ WARNA MERAH
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                        ticks: {
                            color: '#dc3545', // ✅ WARNA MERAH
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        },
                        min: 80, // ✅ RANGE TD SISTOLE
                        max: 180
                    }
                }
            }
        });
    }
});
</script>
@endsection