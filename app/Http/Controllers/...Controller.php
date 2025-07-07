<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PemeriksaanBalita;
use App\Models\PemeriksaanRemaja;
use App\Models\PemeriksaanDewasa;
use App\Models\PemeriksaanLansia;
use DB;

class PemeriksaanController extends Controller
{
    public function getData(Request $request)
    {
        // ✅ GET FILTER PARAMETERS
        $search = $request->search;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $role = $request->role;
        $rw = $request->rw;
        $rujukan = $request->rujukan;

        // ✅ BUILD QUERY DENGAN FILTER
        $query = $this->buildQuery($search, $bulan, $tahun, $role, $rw, $rujukan);

        // ✅ GET DATA WITH PAGINATION
        $data = $query->paginate(10);

        // ✅ GET STATS BERDASARKAN FILTER YANG SAMA
        $stats = $this->getFilteredStats($search, $bulan, $tahun, $role, $rw, $rujukan);

        return response()->json([
            'success' => true,
            'data' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem()
            ],
            'stats' => $stats
        ]);
    }

    private function getFilteredStats($search, $bulan, $tahun, $role, $rw, $rujukan)
    {
        // ✅ BUILD QUERY DENGAN FILTER YANG SAMA
        $baseQuery = $this->buildQuery($search, $bulan, $tahun, $role, $rw, $rujukan);

        // ✅ COUNT BERDASARKAN FILTER
        $total = $baseQuery->count();

        // ✅ COUNT PERLU RUJUKAN BERDASARKAN FILTER
        $perluRujukan = $this->buildQuery($search, $bulan, $tahun, $role, $rw, $rujukan)
            ->where('rujuk_puskesmas', 'Perlu Rujukan')
            ->count();

        // ✅ COUNT NON WARGA BERDASARKAN FILTER
        $nonWarga = $this->buildQuery($search, $bulan, $tahun, $role, $rw, $rujukan)
            ->whereHas('user', function ($q) {
                $q->where('status_warga', 'Non Warga');
            })
            ->count();

        return [
            'bulan_filter' => $total, // ✅ TOTAL BERDASARKAN FILTER
            'bulan_ini' => $total, // ✅ FALLBACK
            'perlu_rujukan' => $perluRujukan,
            'non_warga' => $nonWarga
        ];
    }

    private function buildQuery($search, $bulan, $tahun, $role, $rw, $rujukan)
    {
        // ✅ UNION QUERY UNTUK SEMUA KATEGORI
        $queries = [];

        // ✅ BALITA
        if (!$role || $role === 'balita') {
            $balitaQuery = PemeriksaanBalita::with('user')
                ->select('*', DB::raw("'balita' as jenis_pemeriksaan"));
            $queries[] = $balitaQuery;
        }

        // ✅ REMAJA
        if (!$role || $role === 'remaja') {
            $remajaQuery = PemeriksaanRemaja::with('user')
                ->select('*', DB::raw("'remaja' as jenis_pemeriksaan"));
            $queries[] = $remajaQuery;
        }

        // ✅ DEWASA
        if (!$role || $role === 'dewasa') {
            $dewasaQuery = PemeriksaanDewasa::with('user')
                ->select('*', DB::raw("'dewasa' as jenis_pemeriksaan"));
            $queries[] = $dewasaQuery;
        }

        // ✅ LANSIA
        if (!$role || $role === 'lansia') {
            $lansiaQuery = PemeriksaanLansia::with('user')
                ->select('*', DB::raw("'lansia' as jenis_pemeriksaan"));
            $queries[] = $lansiaQuery;
        }

        // ✅ UNION ALL QUERIES
        $finalQuery = $queries[0];
        for ($i = 1; $i < count($queries); $i++) {
            $finalQuery = $finalQuery->union($queries[$i]);
        }

        // ✅ APPLY FILTERS
        if ($search) {
            $finalQuery->where(function ($q) use ($search) {
                $q->where('nik', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($subQ) use ($search) {
                        $subQ->where('nama', 'LIKE', "%{$search}%");
                    });
            });
        }

        if ($bulan) {
            $finalQuery->whereMonth('tanggal_pemeriksaan', $bulan);
        }

        if ($tahun) {
            $finalQuery->whereYear('tanggal_pemeriksaan', $tahun);
        }

        if ($rw) {
            $finalQuery->whereHas('user', function ($q) use ($rw) {
                $q->where('rw', $rw);
            });
        }

        if ($rujukan) {
            $finalQuery->where('rujuk_puskesmas', $rujukan);
        }

        return $finalQuery;
    }
}
