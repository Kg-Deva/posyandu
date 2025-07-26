<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PemeriksaanIbuHamil;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PemeriksaanIbuHamilController extends Controller
{
    /**
     * ✅ SIMPAN PEMERIKSAAN IBU HAMIL - SESUAI FORM
     */
    public function simpanPemeriksaanIbuHamil(Request $request)
    {
        try {
            // ✅ VALIDASI SESUAI FORM FIELDS
            $validationRules = [
                'nik' => 'required|string|size:16',
                'tanggal_pemeriksaan' => 'required|date|before_or_equal:today',
                'usia_kehamilan_minggu' => 'required|integer|min:1|max:42',
                'bb' => 'required|numeric|min:30|max:200',
                'lila' => 'required|numeric|min:15|max:50',
                'sistole' => 'required|integer|min:70|max:250',
                'diastole' => 'required|integer|min:40|max:150',
                'pemeriksa' => 'required|string|max:255',

                // ✅ TBC SCREENING - SESUAI FORM
                'batuk_terus_menerus' => 'nullable|boolean',
                'demam_2_minggu' => 'nullable|boolean',
                'bb_tidak_naik' => 'nullable|boolean',
                'kontak_tbc' => 'nullable|boolean',

                // ✅ SUPLEMENTASI - SESUAI FORM
                'jumlah_tablet_fe' => 'required|integer|min:0',
                'konsumsi_tablet_fe' => 'required|in:Setiap hari,Tidak setiap hari',
                'jumlah_porsi_mt' => 'required|integer|min:0',
                'konsumsi_mt_bumilkek' => 'nullable|in:Setiap hari,Tidak setiap hari',

                // ✅ KELAS IBU - SESUAI FORM
                'mengikuti_kelas_ibu' => 'required|in:Ya,Tidak',

                // ✅ EDUKASI
                'edukasi' => 'nullable|string|max:1000',
            ];

            // ✅ VALIDASI DINAMIS BERDASARKAN INPUT
            // Jika MT > 0, konsumsi MT wajib
            if ($request->jumlah_porsi_mt > 0) {
                $validationRules['konsumsi_mt_bumilkek'] = 'required|in:Setiap hari,Tidak setiap hari';
            }

            // ✅ CUSTOM ERROR MESSAGES
            $customMessages = [
                'tanggal_pemeriksaan.before_or_equal' => 'Tanggal pemeriksaan tidak boleh lebih dari hari ini.',
                'usia_kehamilan_minggu.min' => 'Usia kehamilan minimal 1 minggu.',
                'usia_kehamilan_minggu.max' => 'Usia kehamilan maksimal 42 minggu.',
                'konsumsi_tablet_fe.required' => 'Konsumsi Tablet Fe wajib dipilih.',
                'konsumsi_mt_bumilkek.required' => 'Konsumsi MT wajib dipilih karena ada porsi yang diberikan.',
                'mengikuti_kelas_ibu.required' => 'Status mengikuti kelas ibu wajib dipilih.',
            ];

            $request->validate($validationRules, $customMessages);

            // ✅ CEK USER IBU HAMIL
            $user = User::where('nik', $request->nik)->where('level', 'ibu hamil')->first();
            if (!$user) {
                return redirect()->back()->with('error', 'Data ibu hamil dengan NIK tersebut tidak ditemukan');
            }

            // ✅ HITUNG STATUS BERDASARKAN INPUT FORM

            // STATUS LILA
            $statusLILA = $request->lila >= 23.5 ? 'Normal' : 'KEK';

            // STATUS TEKANAN DARAH
            $kategoriTekananDarah = $this->getKategoriTekananDarah($request->sistole, $request->diastole);

            // KESIMPULAN SISTOLE & DIASTOLE
            $kesimpulanSistole = $this->getKesimpulanSistole($request->sistole);
            $kesimpulanDiastole = $this->getKesimpulanDiastole($request->diastole);

            // STATUS TD SESUAI KIA
            $statusTdKIA = $this->getStatusTdKIA($request->sistole, $request->diastole);

            // ✅ HITUNG GEJALA TBC
            $gejalaTBC = 0;
            $gejalaList = [];

            if ($request->boolean('batuk_terus_menerus')) {
                $gejalaTBC++;
                $gejalaList[] = 'Batuk terus menerus';
            }
            if ($request->boolean('demam_2_minggu')) {
                $gejalaTBC++;
                $gejalaList[] = 'Demam >2 minggu';
            }
            if ($request->boolean('bb_tidak_naik')) {
                $gejalaTBC++;
                $gejalaList[] = 'BB tidak naik/turun';
            }
            if ($request->boolean('kontak_tbc')) {
                $gejalaTBC++;
                $gejalaList[] = 'Kontak erat TBC';
            }

            // ✅ FIX: HANYA ≥2 GEJALA YANG RUJUK
            $rujukPuskesmasTBC = $gejalaTBC >= 2 ? 'Ya' : 'Tidak';

            // ✅ HITUNG STATUS RUJUKAN UTAMA
            $perluRujukan = false;
            $alasanRujukan = [];
            $statusRujukan = '';

            // Cek LILA
            if ($request->lila < 23.5) {
                $perluRujukan = true;
                $alasanRujukan[] = 'KEK (LILA < 23.5 cm)';
            }

            // Cek Tekanan Darah
            if ($request->sistole >= 140 || $request->diastole >= 90) {
                $perluRujukan = true;
                $alasanRujukan[] = 'Hipertensi';
            }

            if ($request->sistole < 90 || $request->diastole < 60) {
                $perluRujukan = true;
                $alasanRujukan[] = 'Hipotensi';
            }

            // ✅ FIX: CEK TBC - HANYA ≥2 GEJALA YANG RUJUK
            if ($gejalaTBC >= 2) {
                $perluRujukan = true;
                $alasanRujukan[] = "Gejala TBC ({$gejalaTBC} gejala)";
            }

            // Status Rujukan Final
            if ($perluRujukan) {
                $statusRujukan = 'PERLU RUJUKAN - ' . implode(', ', $alasanRujukan);
            } else {
                $statusRujukan = 'TIDAK PERLU RUJUKAN';
            }

            // ✅ SIAPKAN DATA UNTUK DISIMPAN
            $data = [
                'nik' => $request->nik,
                'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
                'usia_kehamilan' => $request->usia_kehamilan_minggu,
                'bb' => $request->bb,
                'tb' => $user->tinggi_badan ?? 160, // Default dari user atau 160
                'lila' => $request->lila,
                'sistole' => $request->sistole,
                'diastole' => $request->diastole,

                // ✅ STATUS FIELDS SESUAI FORM
                'status_lila' => $statusLILA,
                'kategori_tekanan_darah' => $kategoriTekananDarah,
                'kesimpulan_sistole' => $kesimpulanSistole,
                'kesimpulan_diastole' => $kesimpulanDiastole,
                'status_td_kia' => $statusTdKIA,
                'status_rujukan' => $statusRujukan,

                // ✅ TBC SCREENING
                'batuk_terus_menerus' => $request->boolean('batuk_terus_menerus'),
                'demam_2_minggu' => $request->boolean('demam_2_minggu'),
                'bb_tidak_naik' => $request->boolean('bb_tidak_naik'),
                'kontak_tbc' => $request->boolean('kontak_tbc'),
                'jumlah_gejala_tbc' => $gejalaTBC,
                'rujuk_puskesmas_tbc' => $rujukPuskesmasTBC,

                // ✅ SUPLEMENTASI
                'jumlah_tablet_fe' => $request->jumlah_tablet_fe,
                'konsumsi_tablet_fe' => $request->konsumsi_tablet_fe,
                'jumlah_porsi_mt' => $request->jumlah_porsi_mt,
                'konsumsi_mt_bumilkek' => $request->konsumsi_mt_bumilkek,
                'mendapat_mt_bumil_kek' => $request->jumlah_porsi_mt > 0,

                // ✅ KELAS IBU
                'mengikuti_kelas_ibu' => $request->mengikuti_kelas_ibu,

                // ✅ RUJUKAN UTAMA
                'perlu_rujukan' => $perluRujukan,
                'alasan_rujukan' => $perluRujukan ? implode(', ', $alasanRujukan) : null,

                // ✅ EDUKASI & PEMERIKSA
                'edukasi' => $request->edukasi,
                'pemeriksa' => $request->pemeriksa,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // ✅ SIMPAN DATA
            PemeriksaanIbuHamil::create($data);

            return redirect()->back()->with('success', 'Data pemeriksaan ibu hamil berhasil disimpan!');
        } catch (\Exception $e) {
            Log::error('Error saving ibu hamil examination: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * ✅ DASHBOARD IBU HAMIL
     */
    public function ibuHamilHome()
    {
        $user = Auth::user();

        if ($user->level !== 'ibu hamil') {
            return redirect('/')->with('error', 'Akses ditolak');
        }

        // Ambil data pemeriksaan ibu hamil untuk user ini
        $dataPemeriksaan = PemeriksaanIbuHamil::where('nik', $user->nik)
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->limit(12)
            ->get();

        // Pemeriksaan terbaru & sebelumnya
        $pemeriksaanTerbaru = $dataPemeriksaan->first();
        $pemeriksaanSebelumnya = $dataPemeriksaan->skip(1)->first();
        $totalPemeriksaan = $dataPemeriksaan->count();

        // ✅ PROGRESS DATA LENGKAP - SESUAI PROGRESS CARDS
        $progressBB = 0;
        $progressSistole = 0;
        $progressDiastole = 0;
        $progressLILA = 0;
        $progressUsiaKehamilan = 0;

        if ($pemeriksaanTerbaru && $pemeriksaanSebelumnya) {
            $progressBB = round($pemeriksaanTerbaru->bb - $pemeriksaanSebelumnya->bb, 1);
            $progressSistole = $pemeriksaanTerbaru->sistole - $pemeriksaanSebelumnya->sistole;
            $progressDiastole = $pemeriksaanTerbaru->diastole - $pemeriksaanSebelumnya->diastole;
            $progressLILA = round($pemeriksaanTerbaru->lila - $pemeriksaanSebelumnya->lila, 1);
            $progressUsiaKehamilan = $pemeriksaanTerbaru->usia_kehamilan - $pemeriksaanSebelumnya->usia_kehamilan;
        }

        // ✅ HITUNG STATUS KESEHATAN LENGKAP
        $statusKesehatan = $this->calculateStatusKesehatanIbuHamil($pemeriksaanTerbaru);

        // ✅ HITUNG KONTROL BERIKUTNYA - KHUSUS IBU HAMIL
        $kontrolBerikutnya = $this->calculateNextControlIbuHamil($pemeriksaanTerbaru, $statusKesehatan);

        // ✅ STATISTIK KEHAMILAN
        $statistikKehamilan = $this->generateStatistikKehamilan($dataPemeriksaan, $pemeriksaanTerbaru);

        return view('admin-page.ibu-hamil.ibu-hamil-home', compact(
            'user',
            'dataPemeriksaan',
            'pemeriksaanTerbaru',
            'pemeriksaanSebelumnya',
            'totalPemeriksaan',
            'progressBB',
            'progressSistole',
            'progressDiastole',
            'progressLILA',
            'progressUsiaKehamilan',
            'statusKesehatan',
            'kontrolBerikutnya',
            'statistikKehamilan'
        ));
    }

    /**
     * ✅ CALCULATE STATUS KESEHATAN
     */
    private function calculateStatusKesehatanIbuHamil($pemeriksaan)
    {
        if (!$pemeriksaan) {
            return [
                'status' => 'Belum Ada Data',
                'category' => 'secondary',
                'score' => 0,
                'badge' => 'bg-secondary',
                'icon' => 'question-circle',
                'keterangan' => 'Belum ada pemeriksaan yang dilakukan',
                'perlu_rujukan' => false,
                'description' => 'Segera lakukan pemeriksaan ANC pertama'
            ];
        }

        $riskFactors = 0;
        $criticalConditions = 0;
        $riskList = [];

        // ✅ EVALUASI KONDISI KRITIS
        if ($pemeriksaan->lila < 23.5) {
            $criticalConditions++;
            $riskList[] = 'KEK (LILA < 23.5 cm)';
        }

        if ($pemeriksaan->sistole >= 140 || $pemeriksaan->diastole >= 90) {
            $criticalConditions++;
            $riskList[] = 'Hipertensi';
        }

        if ($pemeriksaan->sistole < 90 || $pemeriksaan->diastole < 60) {
            $criticalConditions++;
            $riskList[] = 'Hipotensi';
        }

        if ($pemeriksaan->jumlah_gejala_tbc >= 2) {
            $criticalConditions++;
            $riskList[] = 'Suspek TBC';
        }

        // ✅ EVALUASI FAKTOR RISIKO
        if ($pemeriksaan->lila >= 23.5 && $pemeriksaan->lila < 25) {
            $riskFactors++;
            $riskList[] = 'LILA borderline';
        }

        if ($pemeriksaan->sistole >= 130 && $pemeriksaan->sistole < 140) {
            $riskFactors++;
            $riskList[] = 'Pre-hipertensi';
        }

        if ($pemeriksaan->jumlah_gejala_tbc == 1) {
            $riskFactors++;
            $riskList[] = '1 gejala TBC';
        }

        if ($pemeriksaan->konsumsi_tablet_fe == 'Tidak setiap hari') {
            $riskFactors++;
            $riskList[] = 'Konsumsi Fe tidak teratur';
        }

        if ($pemeriksaan->mengikuti_kelas_ibu == 'Tidak') {
            $riskFactors++;
            $riskList[] = 'Tidak mengikuti kelas ibu';
        }

        // ✅ TENTUKAN STATUS BERDASARKAN KONDISI
        if ($criticalConditions >= 2) {
            return [
                'status' => 'Perlu Rujukan Segera',
                'category' => 'danger',
                'score' => 90 + ($criticalConditions * 5),
                'badge' => 'bg-danger',
                'icon' => 'exclamation-triangle-fill',
                'keterangan' => implode(', ', $riskList),
                'perlu_rujukan' => true,
                'description' => 'Ada beberapa kondisi serius yang memerlukan penanganan medis segera'
            ];
        }

        if ($criticalConditions >= 1) {
            return [
                'status' => 'Perlu Rujukan',
                'category' => 'warning',
                'score' => 70 + ($criticalConditions * 10),
                'badge' => 'bg-warning',
                'icon' => 'exclamation-triangle',
                'keterangan' => implode(', ', $riskList),
                'perlu_rujukan' => true,
                'description' => 'Ada kondisi yang memerlukan perhatian medis'
            ];
        }

        if ($riskFactors >= 2) {
            return [
                'status' => 'Perlu Perhatian',
                'category' => 'info',
                'score' => 50 + ($riskFactors * 5),
                'badge' => 'bg-info',
                'icon' => 'exclamation-circle',
                'keterangan' => implode(', ', $riskList),
                'perlu_rujukan' => false,
                'description' => 'Beberapa faktor risiko perlu dipantau'
            ];
        }

        if ($riskFactors >= 1) {
            return [
                'status' => 'Perlu Perhatian',
                'category' => 'info',
                'score' => 30 + ($riskFactors * 8),
                'badge' => 'bg-info',
                'icon' => 'info-circle',
                'keterangan' => implode(', ', $riskList),
                'perlu_rujukan' => false,
                'description' => 'Ada faktor risiko yang perlu dipantau'
            ];
        }

        return [
            'status' => 'Sehat',
            'category' => 'success',
            'score' => 95,
            'badge' => 'bg-success',
            'icon' => 'shield-check',
            'keterangan' => 'Semua parameter dalam batas normal',
            'perlu_rujukan' => false,
            'description' => 'Kondisi kehamilan sehat, lanjutkan ANC rutin'
        ];
    }

    /**
     * ✅ HELPER METHODS UNTUK FORM CALCULATIONS
     */
    private function getKategoriTekananDarah($sistole, $diastole)
    {
        if ($sistole >= 140 || $diastole >= 90) {
            return 'Hipertensi';
        } elseif ($sistole < 90 || $diastole < 60) {
            return 'Hipotensi';
        } else {
            return 'Normal';
        }
    }

    private function getKesimpulanSistole($sistole)
    {
        if ($sistole >= 140) {
            return 'Tinggi';
        } elseif ($sistole < 90) {
            return 'Rendah';
        } else {
            return 'Normal';
        }
    }

    private function getKesimpulanDiastole($diastole)
    {
        if ($diastole >= 90) {
            return 'Tinggi';
        } elseif ($diastole < 60) {
            return 'Rendah';
        } else {
            return 'Normal';
        }
    }

    private function getStatusTdKIA($sistole, $diastole)
    {
        if ($sistole >= 140 || $diastole >= 90) {
            return 'Hipertensi - Rujuk ke Puskesmas';
        } elseif ($sistole < 90 || $diastole < 60) {
            return 'Hipotensi - Rujuk ke Puskesmas';
        } else {
            return 'Normal - Tidak perlu rujukan';
        }
    }

    /**
     * ✅ OTHER METHODS (RIWAYAT, STATS, dll)
     */
    public function riwayatPemeriksaan($nik = null)
    {
        $query = PemeriksaanIbuHamil::with('user');

        if ($nik) {
            $query->where('nik', $nik);
        }

        $riwayat = $query->orderBy('tanggal_pemeriksaan', 'desc')->get();

        return view('admin-page.kader.riwayat-pemeriksaan-ibu-hamil', compact('riwayat'));
    }

    public function detailPemeriksaan($id)
    {
        $pemeriksaan = PemeriksaanIbuHamil::with('user')->findOrFail($id);
        return view('admin-page.kader.detail-pemeriksaan-ibu-hamil', compact('pemeriksaan'));
    }

    public function getStats()
    {
        try {
            $stats = [
                'total_pemeriksaan' => PemeriksaanIbuHamil::count(),
                'bulan_ini' => PemeriksaanIbuHamil::whereMonth('tanggal_pemeriksaan', now()->month)
                    ->whereYear('tanggal_pemeriksaan', now()->year)
                    ->count(),
                'perlu_rujukan' => PemeriksaanIbuHamil::where('perlu_rujukan', true)->count(),
                'kek' => PemeriksaanIbuHamil::where('lila', '<', 23.5)->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getData(Request $request)
    {
        try {
            $query = PemeriksaanIbuHamil::with('user')
                ->orderBy('tanggal_pemeriksaan', 'desc');

            if ($request->search) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nik', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('nama', 'like', "%{$search}%");
                        });
                });
            }

            $perPage = 10;
            $data = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $data->items(),
                'pagination' => [
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDetail($id)
    {
        try {
            $data = PemeriksaanIbuHamil::with('user')->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * ✅ METHOD BARU: HITUNG KONTROL BERIKUTNYA KHUSUS IBU HAMIL
     */
    private function calculateNextControlIbuHamil($pemeriksaan, $statusKesehatan)
    {
        if (!$pemeriksaan) {
            return [
                'interval' => 0,
                'label' => 'Segera ANC',
                'date' => null,
                'days' => 0,
                'color' => 'danger'
            ];
        }

        // ✅ INTERVAL KONTROL BERDASARKAN USIA KEHAMILAN
        $usiaKehamilan = $pemeriksaan->usia_kehamilan ?? 0;
        $interval = 4; // Default 4 minggu

        if ($usiaKehamilan < 28) {
            // Trimester 1-2: 4 minggu
            $interval = 4;
        } elseif ($usiaKehamilan < 36) {
            // Trimester 3 awal: 2 minggu
            $interval = 2;
        } else {
            // Trimester 3 akhir: 1 minggu
            $interval = 1;
        }

        // ✅ SESUAIKAN BERDASARKAN STATUS KESEHATAN
        if ($statusKesehatan['perlu_rujukan']) {
            $interval = 1; // 1 minggu untuk kondisi perlu rujukan
        }

        // ✅ SESUAIKAN BERDASARKAN KONDISI KHUSUS
        if ($pemeriksaan->lila < 23.5) {
            $interval = min($interval, 2); // Maksimal 2 minggu untuk KEK
        }

        if ($pemeriksaan->sistole >= 140 || $pemeriksaan->diastole >= 90) {
            $interval = 1; // 1 minggu untuk hipertensi
        }

        $nextDate = Carbon::parse($pemeriksaan->tanggal_pemeriksaan)->addWeeks($interval);
        $now = Carbon::now();

        // ✅ HITUNG SELISIH HARI
        $daysLeft = $nextDate->diffInDays($now);

        // ✅ CEK APAKAH SUDAH LEWAT ATAU BELUM
        if ($nextDate->isPast()) {
            $daysLeft = -$daysLeft; // Negatif kalau sudah lewat
        }

        // Tentukan warna badge
        $color = 'success';
        if ($daysLeft <= 0) {
            $color = 'danger';
        } elseif ($daysLeft <= 3) {
            $color = 'warning';
        } elseif ($daysLeft <= 7) {
            $color = 'info';
        }

        return [
            'interval' => $interval,
            'label' => $this->getIntervalLabelIbuHamil($interval, $usiaKehamilan),
            'date' => $nextDate,
            'days' => $daysLeft,
            'color' => $color
        ];
    }

    /**
     * ✅ METHOD BARU: LABEL INTERVAL KHUSUS IBU HAMIL
     */
    private function getIntervalLabelIbuHamil($interval, $usiaKehamilan)
    {
        $trimester = $usiaKehamilan < 14 ? 'Trimester 1' : ($usiaKehamilan < 28 ? 'Trimester 2' : 'Trimester 3');

        switch ($interval) {
            case 1:
                return "ANC Mingguan ($trimester)";
            case 2:
                return "ANC 2 Minggu ($trimester)";
            case 4:
                return "ANC Bulanan ($trimester)";
            default:
                return "ANC Rutin ($trimester)";
        }
    }

    /**
     * ✅ METHOD BARU: STATISTIK KEHAMILAN
     */
    private function generateStatistikKehamilan($dataPemeriksaan, $pemeriksaanTerbaru)
    {
        if ($dataPemeriksaan->isEmpty()) {
            return [
                'trimester' => 'Belum ditentukan',
                'riwayat_kek' => 0,
                'riwayat_hipertensi' => 0,
                'riwayat_tbc' => 0,
                'trend_bb' => 'stabil',
                'trend_lila' => 'stabil',
                'total_tablet_fe' => 0,
                'total_porsi_mt' => 0,
                'mengikuti_kelas_ibu' => 'Belum diketahui'
            ];
        }

        // ✅ TENTUKAN TRIMESTER
        $usiaKehamilan = $pemeriksaanTerbaru->usia_kehamilan ?? 0;
        $trimester = 'Trimester 1';
        if ($usiaKehamilan >= 28) {
            $trimester = 'Trimester 3';
        } elseif ($usiaKehamilan >= 14) {
            $trimester = 'Trimester 2';
        }

        // ✅ HITUNG RIWAYAT MASALAH
        $riwayatKEK = $dataPemeriksaan->where('lila', '<', 23.5)->count();
        $riwayatHipertensi = $dataPemeriksaan->where('sistole', '>=', 140)->count();
        $riwayatTBC = $dataPemeriksaan->where('jumlah_gejala_tbc', '>=', 2)->count();

        // ✅ HITUNG TREND
        $trendBB = $this->calculateTrend($dataPemeriksaan->take(3)->pluck('bb'));
        $trendLILA = $this->calculateTrend($dataPemeriksaan->take(3)->pluck('lila'));

        // ✅ TOTAL SUPLEMENTASI
        $totalTabletFe = $dataPemeriksaan->sum('jumlah_tablet_fe');
        $totalPorsiMT = $dataPemeriksaan->sum('jumlah_porsi_mt');

        // ✅ STATUS KELAS IBU TERBARU
        $mengikutiKelasIbu = $pemeriksaanTerbaru->mengikuti_kelas_ibu ?? 'Belum diketahui';

        return [
            'trimester' => $trimester,
            'usia_kehamilan' => $usiaKehamilan,
            'riwayat_kek' => $riwayatKEK,
            'riwayat_hipertensi' => $riwayatHipertensi,
            'riwayat_tbc' => $riwayatTBC,
            'trend_bb' => $trendBB,
            'trend_lila' => $trendLILA,
            'total_tablet_fe' => $totalTabletFe,
            'total_porsi_mt' => $totalPorsiMT,
            'mengikuti_kelas_ibu' => $mengikutiKelasIbu,
            'total_pemeriksaan' => $dataPemeriksaan->count(),
            'pemeriksaan_terakhir' => $pemeriksaanTerbaru->tanggal_pemeriksaan ?? null
        ];
    }

    /**
     * ✅ METHOD BARU: HITUNG TREND
     */
    private function calculateTrend($data)
    {
        $values = $data->filter()->values();

        if ($values->count() < 2) {
            return 'stabil';
        }

        $first = $values->first();
        $last = $values->last();
        $diff = $last - $first;

        if ($diff > 0) {
            return 'naik';
        } elseif ($diff < 0) {
            return 'turun';
        } else {
            return 'stabil';
        }
    }

    /**
     * ✅ METHOD BARU: SKRINING TAHUNAN
     */
    public function skriningTahunan($userId)
    {
        $user = User::findOrFail($userId);
        return view('admin-page.pemeriksaan-form.skrining-tahunan-ibu-hamil', compact('user'));
    }

    /**
     * ✅ SHOW DETAIL UNTUK ADMIN
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        $dataPemeriksaan = PemeriksaanIbuHamil::where('nik', $user->nik)
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->get();

        $pemeriksaanTerbaru = $dataPemeriksaan->first();
        $pemeriksaanSebelumnya = $dataPemeriksaan->skip(1)->first();
        $totalPemeriksaan = $dataPemeriksaan->count();

        // Hitung progress
        $progressBB = 0;
        $progressSistole = 0;
        $progressDiastole = 0;
        $progressLILA = 0;

        if ($pemeriksaanTerbaru && $pemeriksaanSebelumnya) {
            $progressBB = round($pemeriksaanTerbaru->bb - $pemeriksaanSebelumnya->bb, 1);
            $progressSistole = $pemeriksaanTerbaru->sistole - $pemeriksaanSebelumnya->sistole;
            $progressDiastole = $pemeriksaanTerbaru->diastole - $pemeriksaanSebelumnya->diastole;
            $progressLILA = round($pemeriksaanTerbaru->lila - $pemeriksaanSebelumnya->lila, 1);
        }

        $statusKesehatan = $this->calculateStatusKesehatanIbuHamil($pemeriksaanTerbaru);
        $kontrolBerikutnya = $this->calculateNextControlIbuHamil($pemeriksaanTerbaru, $statusKesehatan);
        $statistikKehamilan = $this->generateStatistikKehamilan($dataPemeriksaan, $pemeriksaanTerbaru);

        return view('admin-page.ibu-hamil.ibu-hamil-home', compact(
            'user',
            'dataPemeriksaan',
            'pemeriksaanTerbaru',
            'pemeriksaanSebelumnya',
            'totalPemeriksaan',
            'progressBB',
            'progressSistole',
            'progressDiastole',
            'progressLILA',
            'statusKesehatan',
            'kontrolBerikutnya',
            'statistikKehamilan'
        ));
    }
}
