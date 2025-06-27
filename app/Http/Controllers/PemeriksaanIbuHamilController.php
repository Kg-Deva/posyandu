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

            $rujukPuskesmasTBC = $gejalaTBC >= 1 ? 'Ya' : 'Tidak';

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

            // Cek TBC
            if ($gejalaTBC >= 1) {
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

        $dataPemeriksaan = PemeriksaanIbuHamil::where('nik', $user->nik)
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->get();

        $pemeriksaanTerbaru = $dataPemeriksaan->first();
        $totalPemeriksaan = $dataPemeriksaan->count();

        // Progress BB
        $progressBB = 0;
        if ($dataPemeriksaan->count() >= 2) {
            $terbaru = $dataPemeriksaan->first();
            $sebelumnya = $dataPemeriksaan->skip(1)->first();
            $progressBB = round($terbaru->bb - $sebelumnya->bb, 1);
        }

        $statusKesehatan = $this->calculateStatusKesehatanIbuHamil($pemeriksaanTerbaru);

        return view('admin-page.ibu-hamil.ibu-hamil-home', compact(
            'user',
            'dataPemeriksaan',
            'pemeriksaanTerbaru',
            'totalPemeriksaan',
            'progressBB',
            'statusKesehatan'
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
                'keterangan' => 'Belum ada pemeriksaan yang dilakukan',
                'perlu_rujukan' => false
            ];
        }

        if ($pemeriksaan->perlu_rujukan) {
            return [
                'status' => 'Perlu Rujukan',
                'keterangan' => $pemeriksaan->alasan_rujukan ?? 'Perlu rujukan ke Puskesmas',
                'perlu_rujukan' => true
            ];
        }

        return [
            'status' => 'Sehat',
            'keterangan' => 'Semua parameter dalam batas normal',
            'perlu_rujukan' => false
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
}
