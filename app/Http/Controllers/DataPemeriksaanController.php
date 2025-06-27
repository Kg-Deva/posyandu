<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\PemeriksaanBalita;
use App\Models\User;
use App\Models\PemeriksaanRemaja;
use App\Models\PemeriksaanIbuHamil;
use App\Exports\DataPemeriksaanExport;
use Maatwebsite\Excel\Facades\Excel;


class DataPemeriksaanController extends Controller
{
    public function index(Request $request)
    {
        return view('admin-page.kader.data-pemeriksaan');
    }

    public function getData(Request $request)
    {
        try {
            $role = $request->get('role', ''); // Get role filter

            // ✅ COLLECT DATA DARI SEMUA MODEL
            $allData = collect();

            // BALITA DATA
            if (empty($role) || $role === 'balita') {
                $balitaQuery = PemeriksaanBalita::with(['user' => function ($q) {
                    $q->select('nik', 'nama', 'rw', 'level', 'alamat', 'tanggal_lahir', 'jenis_kelamin');
                }]);

                // Apply filters for balita
                $this->applyFilters($balitaQuery, $request);

                $balitaData = $balitaQuery->get()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'tanggal_pemeriksaan' => $item->tanggal_pemeriksaan,
                        'nik' => $item->nik,
                        'bb' => $item->bb,
                        'tb' => $item->tb,
                        'lingkar_kepala' => $item->lingkar_kepala,
                        'lila' => $item->lila,
                        'umur' => $item->umur,
                        'rujuk_puskesmas' => $item->rujuk_puskesmas,
                        'pemeriksa' => $item->pemeriksa,
                        'user' => $item->user,
                        'jenis_pemeriksaan' => 'balita',
                        'model_type' => 'balita'
                    ];
                });

                $allData = $allData->merge($balitaData);
            }

            // REMAJA DATA
            if (empty($role) || $role === 'remaja') {
                $remajaQuery = PemeriksaanRemaja::with(['user' => function ($q) {
                    $q->select('nik', 'nama', 'rw', 'level', 'alamat', 'tanggal_lahir', 'jenis_kelamin');
                }]);

                $this->applyFilters($remajaQuery, $request);

                $remajaData = $remajaQuery->get()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'tanggal_pemeriksaan' => $item->tanggal_pemeriksaan,
                        'nik' => $item->nik,
                        'bb' => $item->bb,
                        'tb' => $item->tb,
                        'imt' => $item->imt,
                        'lila' => $item->lila,
                        'umur' => $item->umur,
                        'rujuk_puskesmas' => $item->rujuk_puskesmas,
                        'pemeriksa' => $item->pemeriksa,
                        'user' => $item->user,
                        'jenis_pemeriksaan' => 'remaja',
                        'model_type' => 'remaja'
                    ];
                });

                $allData = $allData->merge($remajaData);
            }

            // IBU HAMIL DATA
            if (empty($role) || $role === 'ibu-hamil') {
                $ibuHamilQuery = PemeriksaanIbuHamil::with(['user' => function ($q) {
                    $q->select('nik', 'nama', 'rw', 'level', 'alamat', 'tanggal_lahir', 'jenis_kelamin');
                }]);

                $this->applyFilters($ibuHamilQuery, $request);

                $ibuHamilData = $ibuHamilQuery->get()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'tanggal_pemeriksaan' => $item->tanggal_pemeriksaan,
                        'nik' => $item->nik,
                        'bb' => $item->bb,
                        'tb' => $item->tb,
                        'usia_kehamilan' => $item->usia_kehamilan,
                        'lila' => $item->lila,
                        'umur' => $item->umur,
                        'perlu_rujukan' => $item->perlu_rujukan,
                        'rujuk_puskesmas' => $item->perlu_rujukan ? 'Perlu Rujukan' : 'Normal',
                        'pemeriksa' => $item->pemeriksa,
                        'user' => $item->user,
                        'jenis_pemeriksaan' => 'ibu-hamil',
                        'model_type' => 'ibu-hamil'
                    ];
                });

                $allData = $allData->merge($ibuHamilData);
            }

            // ✅ SORT BY DATE DESC
            $allData = $allData->sortByDesc('tanggal_pemeriksaan')->values();

            // ✅ MANUAL PAGINATION
            $page = $request->get('page', 1);
            $perPage = 10;
            $total = $allData->count();
            $items = $allData->forPage($page, $perPage)->values();

            $pagination = [
                'current_page' => (int) $page,
                'last_page' => (int) ceil($total / $perPage),
                'per_page' => $perPage,
                'total' => $total,
                'from' => ($page - 1) * $perPage + 1,
                'to' => min($page * $perPage, $total)
            ];

            return response()->json([
                'success' => true,
                'data' => $items,
                'pagination' => $pagination,
                'stats' => $this->calculateUniversalStats($request)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // ✅ TAMBAH METHOD CALCULATE HEALTH STATUS
    private function calculateHealthStatus($pemeriksaan, $user = null)
    {
        if (!$pemeriksaan) {
            return [
                'status' => 'Belum Ada Data',
                'category' => 'info',
                'score' => 0,
                'badge' => 'bg-secondary',
                'icon' => 'question-circle',
                'description' => 'Belum ada pemeriksaan'
            ];
        }

        $user = $user ?: $pemeriksaan->user;
        $level = $user->level ?? 'umum';
        $umurBulan = $pemeriksaan->umur ?? 12;

        // Base score
        $score = 80;
        $criticalIssues = [];
        $warningIssues = [];

        // 1. RUJUKAN MEDIS (HIGHEST PRIORITY)
        if ($pemeriksaan->rujuk_puskesmas === 'Perlu Rujukan') {
            $score -= 50;
            $criticalIssues[] = 'Perlu rujukan ke puskesmas';
        }

        // 2. TBC SCREENING
        $gejalaTBC = $pemeriksaan->jumlah_gejala_tbc ?? 0;
        if ($gejalaTBC >= 2) {
            $score -= 30;
            $criticalIssues[] = 'Risiko TBC tinggi';
        } elseif ($gejalaTBC >= 1) {
            $score -= 15;
            $warningIssues[] = 'Perlu pantau gejala TBC';
        }

        // 3. ROLE-SPECIFIC HEALTH CHECKS
        switch ($level) {
            case 'balita':
                $score += $this->checkBalitaHealth($pemeriksaan, $umurBulan, $criticalIssues, $warningIssues);
                break;

            case 'ibuhamil':
                $score += $this->checkIbuHamilHealth($pemeriksaan, $umurBulan, $criticalIssues, $warningIssues);
                break;

            default:
                $score += $this->checkUmumHealth($pemeriksaan, $umurBulan, $criticalIssues, $warningIssues);
                break;
        }

        // 4. DETERMINE FINAL STATUS
        return $this->determineFinalStatus($score, $criticalIssues, $warningIssues);
    }

    // ✅ TAMBAH METHOD CHECK BALITA HEALTH (CODE LU)
    private function checkBalitaHealth($pemeriksaan, $umurBulan, &$criticalIssues, &$warningIssues)
    {
        $adjustment = 0;

        // 🚨 CRITICAL CHECKS (MERAH)
        if ($umurBulan >= 2 && !$pemeriksaan->imunisasi) {
            $adjustment -= 25;
            $criticalIssues[] = 'Imunisasi belum lengkap - URGENT!';
        }

        // ⚠️ WARNING CHECKS (KUNING)
        if ($umurBulan <= 6 && !$pemeriksaan->asi_eksklusif) {
            $adjustment -= 20;
            $warningIssues[] = 'ASI eksklusif belum diberikan';
        }

        if ($umurBulan >= 6 && !$pemeriksaan->mp_asi) {
            $adjustment -= 20;
            $warningIssues[] = 'MPASI belum diberikan';
        }

        // ✅ VITAMIN A JADI INFO AJA (TIDAK MENGURANGI SCORE)
        // Vitamin A tidak critical, hanya informasi
        // if ($umurBulan >= 6 && !$pemeriksaan->vitamin_a) {
        //     // Tidak mengurangi score, hanya informasi
        // }

        // 💊 OBAT CACING JUGA JADI INFO (TIDAK MENGURANGI SCORE)
        // if ($umurBulan >= 12 && !$pemeriksaan->obat_cacing) {
        //     // Tidak mengurangi score, hanya informasi
        // }

        // 🩺 LILA CHECK (JIKA ADA)
        if ($pemeriksaan->lila && $pemeriksaan->lila < 11.5) {
            $adjustment -= 25;
            $criticalIssues[] = 'LILA di bawah normal';
        }

        return $adjustment;
    }

    // ✅ TAMBAH METHOD CHECK IBU HAMIL HEALTH (CODE LU)
    private function checkIbuHamilHealth($pemeriksaan, $umurBulan, &$criticalIssues, &$warningIssues)
    {
        $adjustment = 0;

        // 🚨 LILA CHECK (CRITICAL FOR IBU HAMIL)
        if ($pemeriksaan->lila) {
            $lilaValue = floatval($pemeriksaan->lila);
            if ($lilaValue < 23.5) {
                $adjustment -= 30;
                $criticalIssues[] = 'KEK (Kurang Energi Kronis)';
            }
        } else {
            $adjustment -= 15;
            $warningIssues[] = 'LILA belum diukur';
        }

        // ⚠️ PROGRAM CHECKS
        // ✅ VITAMIN A JADI WARNING SAJA UNTUK IBU HAMIL
        if (!$pemeriksaan->vitamin_a) {
            $adjustment -= 10; // Lebih ringan
            $warningIssues[] = 'Vitamin A perlu diberikan';
        }

        if (!$pemeriksaan->imunisasi) {
            $adjustment -= 20;
            $warningIssues[] = 'Imunisasi belum lengkap';
        }

        return $adjustment;
    }

    // ✅ TAMBAH METHOD CHECK UMUM HEALTH (CODE LU)
    private function checkUmumHealth($pemeriksaan, $umurBulan, &$criticalIssues, &$warningIssues)
    {
        $adjustment = 5; // Bonus for general health

        // ✅ VITAMIN A & OBAT CACING TIDAK MENGURANGI SCORE
        // Hanya informasi saja

        return $adjustment;
    }

    // ✅ TAMBAH METHOD DETERMINE FINAL STATUS
    private function determineFinalStatus($score, $criticalIssues, $warningIssues)
    {
        if (count($criticalIssues) >= 2 || $score <= 30) {
            return [
                'status' => 'Perlu Perhatian Khusus',
                'category' => 'urgent',
                'score' => $score,
                'badge' => 'bg-danger',
                'icon' => 'exclamation-triangle-fill',
                'description' => 'Memerlukan penanganan segera',
                'issues' => $criticalIssues
            ];
        } elseif (count($criticalIssues) >= 1 || count($warningIssues) >= 2 || $score <= 60) {
            return [
                'status' => 'Perlu Pantau',
                'category' => 'warning',
                'score' => $score,
                'badge' => 'bg-warning',
                'icon' => 'exclamation-triangle',
                'description' => 'Perlu pemantauan lebih ketat',
                'issues' => array_merge($criticalIssues, $warningIssues)
            ];
        } else {
            return [
                'status' => 'Kondisi Baik',
                'category' => 'good',
                'score' => $score,
                'badge' => 'bg-success',
                'icon' => 'check-circle-fill',
                'description' => 'Kondisi kesehatan baik',
                'issues' => []
            ];
        }
    }

    // ✅ UPDATE METHOD calculateSmartStats() - SESUAIKAN DENGAN BLADE
    private function calculateSmartStats($request)
    {
        $baseQuery = PemeriksaanBalita::with('user');

        // Apply same filters...
        if ($request->filled('bulan')) {
            $baseQuery->whereMonth('tanggal_pemeriksaan', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $baseQuery->whereYear('tanggal_pemeriksaan', $request->tahun);
        }
        if ($request->filled('role')) {
            $baseQuery->whereHas('user', function ($q) use ($request) {
                $q->where('level', $request->role);
            });
        }
        if ($request->filled('rw')) {
            $baseQuery->whereHas('user', function ($q) use ($request) {
                $q->where('rw', $request->rw);
            });
        }
        if ($request->filled('rujukan')) {
            if ($request->rujukan === 'Tidak Perlu Rujukan') {
                $baseQuery->where(function ($q) {
                    $q->where('rujuk_puskesmas', '!=', 'Perlu Rujukan')
                        ->orWhereNull('rujuk_puskesmas')
                        ->orWhere('rujuk_puskesmas', 'Tidak Perlu Rujukan');
                });
            } else {
                $baseQuery->where('rujuk_puskesmas', $request->rujukan);
            }
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $baseQuery->where(function ($q) use ($search) {
                $q->where('nik', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($subQ) use ($search) {
                        $subQ->where('nama', 'LIKE', "%{$search}%");
                    });
            });
        }

        $allData = $baseQuery->get();

        // ✅ HITUNG STATS SESUAI BLADE
        $nonWarga = 0;
        $bulanIni = 0;
        $perluRujukan = 0;

        foreach ($allData as $item) {
            // ✅ NON WARGA (ASUMSI FIELD 'type' ATAU 'status_warga')
            if (
                $item->user &&
                (isset($item->user->type) && $item->user->type === 'bukan warga' ||
                    isset($item->user->status_warga) && $item->user->status_warga === 'bukan warga')
            ) {
                $nonWarga++;
            }

            // ✅ BULAN INI
            if (
                Carbon::parse($item->tanggal_pemeriksaan)->month === now()->month &&
                Carbon::parse($item->tanggal_pemeriksaan)->year === now()->year
            ) {
                $bulanIni++;
            }

            // ✅ PERLU RUJUKAN (DARI FIELD rujuk_puskesmas)
            if ($item->rujuk_puskesmas === 'Perlu Rujukan') {
                $perluRujukan++;
            }
        }

        return [
            'non_warga' => $nonWarga,        // ✅ SESUAI BLADE
            'bulan_ini' => $bulanIni,        // ✅ SESUAI BLADE  
            'perlu_rujukan' => $perluRujukan, // ✅ SESUAI BLADE
            'total_pemeriksaan' => $allData->count()
        ];
    }

    // ✅ EXISTING METHODS (NO CHANGE)
    public function getFilterOptions()
    {
        try {
            // ✅ AMBIL RW DARI SEMUA PEMERIKSAAN YANG ADA DATA
            $rwList = collect();

            // Ambil RW dari Balita
            if (PemeriksaanBalita::count() > 0) {
                $balitaRw = PemeriksaanBalita::with('user')
                    ->get()
                    ->pluck('user.rw')
                    ->filter();
                $rwList = $rwList->merge($balitaRw);
            }

            // Ambil RW dari Remaja  
            if (PemeriksaanRemaja::count() > 0) {
                $remajaRw = PemeriksaanRemaja::with('user')
                    ->get()
                    ->pluck('user.rw')
                    ->filter();
                $rwList = $rwList->merge($remajaRw);
            }

            // Ambil RW dari Ibu Hamil
            if (PemeriksaanIbuHamil::count() > 0) {
                $ibuHamilRw = PemeriksaanIbuHamil::with('user')
                    ->get()
                    ->pluck('user.rw')
                    ->filter();
                $rwList = $rwList->merge($ibuHamilRw);
            }

            $rwList = $rwList->unique()->sort()->values();

            // ✅ ROLE LIST BERDASARKAN DATA AKTUAL
            $roleList = collect();

            if (PemeriksaanBalita::count() > 0) {
                $roleList->push('balita');
            }

            if (PemeriksaanRemaja::count() > 0) {
                $roleList->push('remaja');
            }

            if (PemeriksaanIbuHamil::count() > 0) {
                $roleList->push('ibu-hamil'); // ✅ PAKAI DASH
            }

            // ✅ TAHUN DARI SEMUA PEMERIKSAAN
            $years = collect();

            if (PemeriksaanBalita::count() > 0) {
                $balitaYears = PemeriksaanBalita::selectRaw('YEAR(tanggal_pemeriksaan) as year')
                    ->distinct()->pluck('year');
                $years = $years->merge($balitaYears);
            }

            if (PemeriksaanRemaja::count() > 0) {
                $remajaYears = PemeriksaanRemaja::selectRaw('YEAR(tanggal_pemeriksaan) as year')
                    ->distinct()->pluck('year');
                $years = $years->merge($remajaYears);
            }

            if (PemeriksaanIbuHamil::count() > 0) {
                $ibuHamilYears = PemeriksaanIbuHamil::selectRaw('YEAR(tanggal_pemeriksaan) as year')
                    ->distinct()->pluck('year');
                $years = $years->merge($ibuHamilYears);
            }

            $years = $years->unique()->sort()->reverse()->values();

            return response()->json([
                'success' => true,
                'rw_list' => $rwList,
                'role_list' => $roleList,
                'years' => $years
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function detail($id)
    {
        try {
            $pemeriksaan = PemeriksaanBalita::with(['user:nik,nama,alamat,rw,level,tanggal_lahir,jenis_kelamin'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $pemeriksaan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data pemeriksaan tidak ditemukan'
            ], 404);
        }
    }

    public function getLastExamination($nik)
    {
        $currentDate = request()->get('current_date', date('Y-m-d'));

        $baselineExam = PemeriksaanBalita::where('nik', $nik)
            ->where('tanggal_pemeriksaan', '<', $currentDate)
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->first();

        return response()->json($baselineExam);
    }

    // ✅ HELPER METHOD UNTUK APPLY FILTERS
    private function applyFilters($query, $request)
    {
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_pemeriksaan', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_pemeriksaan', $request->tahun);
        }

        if ($request->filled('rw')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('rw', $request->rw);
            });
        }

        // ✅ TAMBAH FILTER RUJUKAN INI DI CONTROLLER
        if ($request->filled('rujukan')) {
            $modelClass = get_class($query->getModel());

            if ($request->rujukan === 'Perlu Rujukan') {
                if (strpos($modelClass, 'PemeriksaanIbuHamil') !== false) {
                    $query->where('perlu_rujukan', true);
                } else {
                    $query->where('rujuk_puskesmas', 'Perlu Rujukan');
                }
            } elseif ($request->rujukan === 'Tidak Perlu Rujukan') {
                if (strpos($modelClass, 'PemeriksaanIbuHamil') !== false) {
                    $query->where(function ($q) {
                        $q->where('perlu_rujukan', false)
                            ->orWhereNull('perlu_rujukan');
                    });
                } else {
                    $query->where(function ($q) {
                        $q->where('rujuk_puskesmas', '!=', 'Perlu Rujukan')
                            ->orWhereNull('rujuk_puskesmas')
                            ->orWhere('rujuk_puskesmas', 'Normal')
                            ->orWhere('rujuk_puskesmas', 'Tidak Perlu Rujukan');
                    });
                }
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nik', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($subQ) use ($search) {
                        $subQ->where('nama', 'LIKE', "%{$search}%");
                    });
            });
        }
    }

    // ✅ UNIVERSAL STATS
    private function calculateUniversalStats($request)
    {
        // Hitung stats dari semua model...
        $balitaCount = PemeriksaanBalita::with('user')->get();
        $remajaCount = PemeriksaanRemaja::with('user')->get();
        $ibuHamilCount = PemeriksaanIbuHamil::with('user')->get();

        $allData = collect()
            ->merge($balitaCount)
            ->merge($remajaCount)
            ->merge($ibuHamilCount);

        $nonWarga = $allData->filter(function ($item) {
            return !$item->user || !$item->user->rw;
        })->count();

        $bulanIni = $allData->filter(function ($item) {
            return now()->month === Carbon::parse($item->tanggal_pemeriksaan)->month &&
                now()->year === Carbon::parse($item->tanggal_pemeriksaan)->year;
        })->count();

        $perluRujukan = $allData->filter(function ($item) {
            return $item->rujuk_puskesmas === 'Perlu Rujukan' ||
                (isset($item->perlu_rujukan) && $item->perlu_rujukan);
        })->count();

        return [
            'non_warga' => $nonWarga,
            'bulan_ini' => $bulanIni,
            'perlu_rujukan' => $perluRujukan,
            'total_pemeriksaan' => $allData->count()
        ];
    }

    public function exportExcel(Request $request)
    {
        try {
            // Get all data with same filters as getData method
            $role = $request->get('role', '');
            $allData = collect();

            // BALITA DATA
            if (empty($role) || $role === 'balita') {
                $balitaQuery = PemeriksaanBalita::with(['user']);
                $this->applyFilters($balitaQuery, $request);

                $balitaData = $balitaQuery->get()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'tanggal_pemeriksaan' => $item->tanggal_pemeriksaan,
                        'nik' => $item->nik,
                        'bb' => $item->bb,
                        'tb' => $item->tb,
                        'lingkar_kepala' => $item->lingkar_kepala,
                        'lila' => $item->lila,
                        'umur' => $item->umur,
                        'rujuk_puskesmas' => $item->rujuk_puskesmas,
                        'pemeriksa' => $item->pemeriksa,
                        'user' => $item->user,
                        'jenis_pemeriksaan' => 'balita'
                    ];
                });

                $allData = $allData->merge($balitaData);
            }

            // REMAJA DATA
            if (empty($role) || $role === 'remaja') {
                $remajaQuery = PemeriksaanRemaja::with(['user']);
                $this->applyFilters($remajaQuery, $request);

                $remajaData = $remajaQuery->get()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'tanggal_pemeriksaan' => $item->tanggal_pemeriksaan,
                        'nik' => $item->nik,
                        'bb' => $item->bb,
                        'tb' => $item->tb,
                        'imt' => $item->imt,
                        'lila' => $item->lila,
                        'umur' => $item->umur,
                        'rujuk_puskesmas' => $item->rujuk_puskesmas,
                        'pemeriksa' => $item->pemeriksa,
                        'user' => $item->user,
                        'jenis_pemeriksaan' => 'remaja'
                    ];
                });

                $allData = $allData->merge($remajaData);
            }

            // IBU HAMIL DATA
            if (empty($role) || $role === 'ibu-hamil') {
                $ibuHamilQuery = PemeriksaanIbuHamil::with(['user']);
                $this->applyFilters($ibuHamilQuery, $request);

                $ibuHamilData = $ibuHamilQuery->get()->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'tanggal_pemeriksaan' => $item->tanggal_pemeriksaan,
                        'nik' => $item->nik,
                        'bb' => $item->bb,
                        'tb' => $item->tb,
                        'usia_kehamilan' => $item->usia_kehamilan,
                        'lila' => $item->lila,
                        'umur' => $item->umur,
                        'rujuk_puskesmas' => $item->perlu_rujukan ? 'Perlu Rujukan' : 'Normal',
                        'pemeriksa' => $item->pemeriksa,
                        'user' => $item->user,
                        'jenis_pemeriksaan' => 'ibu-hamil'
                    ];
                });

                $allData = $allData->merge($ibuHamilData);
            }

            // Sort by date desc
            $allData = $allData->sortByDesc('tanggal_pemeriksaan')->values();

            // Generate filename based on filters
            $filename = 'data-pemeriksaan';
            if ($request->filled('tahun')) {
                $filename .= '-' . $request->tahun;
            }
            if ($request->filled('bulan')) {
                $bulanNames = [
                    1 => 'januari',
                    2 => 'februari',
                    3 => 'maret',
                    4 => 'april',
                    5 => 'mei',
                    6 => 'juni',
                    7 => 'juli',
                    8 => 'agustus',
                    9 => 'september',
                    10 => 'oktober',
                    11 => 'november',
                    12 => 'desember'
                ];
                $filename .= '-' . $bulanNames[$request->bulan];
            }
            if ($request->filled('role')) {
                $filename .= '-' . $request->role;
            }
            if ($request->filled('rw')) {
                $filename .= '-rw' . $request->rw;
            }
            $filename .= '-' . now()->format('Y-m-d') . '.xlsx';

            return Excel::download(new DataPemeriksaanExport($allData, $request->all()), $filename);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error exporting data: ' . $e->getMessage()
            ], 500);
        }
    }
}
