<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PemeriksaanRemaja;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PemeriksaanRemajaController extends Controller
{
    /**
     * Store pemeriksaan remaja data
     */
    public function simpanPemeriksaanRemaja(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'nik' => 'required|string',
                'tanggal_pemeriksaan' => 'required|date',
                'bb' => 'required|numeric|min:0',
                'tb' => 'required|numeric|min:0',
                'lingkar_perut' => 'required|numeric|min:0',
                'sistole' => 'required|integer|min:0',
                'diastole' => 'required|integer|min:0',
                'hb' => 'required|numeric|min:0',
                'pemeriksa' => 'required|string',
                'jenis_kelamin' => 'required|string',

                // Validasi skrining psiko-sosial (semua opsional)
                'nyaman_dirumah' => 'nullable|in:Ya,Tidak',
                'masalah_pendidikan' => 'nullable|in:Ya,Tidak',
                'masalah_pola_makan' => 'nullable|in:Ya,Tidak',
                'masalah_aktivitas' => 'nullable|in:Ya,Tidak',
                'masalah_obat' => 'nullable|in:Ya,Tidak',
                'masalah_kesehatan_seksual' => 'nullable|in:Ya,Tidak',
                'masalah_keamanan' => 'nullable|in:Ya,Tidak',
                'masalah_kesehatan_mental' => 'nullable|in:Ya,Tidak',

                'edukasi' => 'nullable|string'
            ]);

            // Cek apakah user remaja ada
            $user = User::where('nik', $request->nik)->where('level', 'remaja')->first();
            if (!$user) {
                return redirect()->back()->with('error', 'Data remaja dengan NIK tersebut tidak ditemukan');
            }

            // Siapkan data untuk disimpan
            $data = [
                'nik' => $request->nik,
                'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
                'bb' => $request->bb,
                'tb' => $request->tb,
                'lingkar_perut' => $request->lingkar_perut,
                'sistole' => $request->sistole,
                'diastole' => $request->diastole,
                'hb' => $request->hb,
                'pemeriksa' => $request->pemeriksa,
                'jenis_kelamin' => $request->jenis_kelamin,

                // Auto-calculated fields dari form
                'nilai_imt' => $request->nilai_imt, // ✅ TAMBAH INI
                'kesimpulan_imt' => $request->kesimpulan_imt,
                'kesimpulan_sistole' => $request->kesimpulan_sistole,
                'kesimpulan_diastole' => $request->kesimpulan_diastole,
                'status_anemia' => $request->status_anemia,
                'kategori_tekanan_darah' => $request->kategori_tekanan_darah,

                // TBC screening
                'batuk_terus_menerus' => $request->has('batuk_terus_menerus'),
                'demam_2_minggu' => $request->has('demam_2_minggu'),
                'bb_tidak_naik' => $request->has('bb_tidak_naik'),
                'kontak_tbc' => $request->has('kontak_tbc'),
                'jumlah_gejala_tbc' => $request->jumlah_gejala_tbc,
                'rujuk_puskesmas' => $request->rujuk_puskesmas,

                // Psiko-sosial
                'nyaman_dirumah' => $request->nyaman_dirumah,
                'masalah_pendidikan' => $request->masalah_pendidikan,
                'masalah_pola_makan' => $request->masalah_pola_makan,
                'masalah_aktivitas' => $request->masalah_aktivitas,
                'masalah_obat' => $request->masalah_obat,
                'masalah_kesehatan_seksual' => $request->masalah_kesehatan_seksual,
                'masalah_keamanan' => $request->masalah_keamanan,
                'masalah_kesehatan_mental' => $request->masalah_kesehatan_mental,

                'edukasi' => $request->edukasi
            ];

            // Simpan data
            $pemeriksaan = PemeriksaanRemaja::create($data);

            return redirect()->back()->with('success', 'Data pemeriksaan remaja berhasil disimpan!');
        } catch (\Exception $e) {
            Log::error('Error saving remaja examination: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi. Error: ' . $e->getMessage());
        }
    }

    /**
     * Get remaja examination history
     */
    public function riwayatPemeriksaan($nik = null)
    {
        $query = PemeriksaanRemaja::with('user');

        if ($nik) {
            $query->where('nik', $nik);
        }

        $riwayat = $query->orderBy('tanggal_pemeriksaan', 'desc')->get();

        return view('admin-page.kader.riwayat-pemeriksaan-remaja', compact('riwayat'));
    }

    /**
     * Get detail pemeriksaan
     */
    public function detailPemeriksaan($id)
    {
        $pemeriksaan = PemeriksaanRemaja::with('user')->findOrFail($id);

        return view('admin-page.kader.detail-pemeriksaan-remaja', compact('pemeriksaan'));
    }

    /**
     * Dashboard stats untuk remaja
     */
    public function getStats()
    {
        try {
            $stats = [
                'total_pemeriksaan' => PemeriksaanRemaja::count(),
                'bulan_ini' => PemeriksaanRemaja::bulanIni()->count(),
                'perlu_rujukan' => PemeriksaanRemaja::perluRujukan()->count(),
                'anemia' => PemeriksaanRemaja::where('status_anemia', 'Ya')->count(),
                'hipertensi' => PemeriksaanRemaja::where('kategori_tekanan_darah', 'like', '%Hipertensi%')->count(),
                'masalah_psikososial' => PemeriksaanRemaja::where(function ($q) {
                    $q->where('masalah_pendidikan', 'Ya')
                        ->orWhere('masalah_kesehatan_mental', 'Ya')
                        ->orWhere('nyaman_dirumah', 'Tidak');
                })->count()
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

    /**
     * Dashboard remaja - menampilkan ringkasan kesehatan dan riwayat pemeriksaan
     */
    public function remajaHome()
    {
        $user = Auth::user();

        // ✅ PASTIKAN USER ADALAH REMAJA
        if ($user->level !== 'remaja') {
            return redirect('/')->with('error', 'Akses ditolak');
        }

        // ✅ AMBIL DATA PEMERIKSAAN REMAJA
        $dataPemeriksaan = PemeriksaanRemaja::where('nik', $user->nik)
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->get();

        $pemeriksaanTerakhir = $dataPemeriksaan->first();
        $totalPemeriksaan = $dataPemeriksaan->count();

        // ✅ HITUNG PROGRESS BB & TB
        $progressBB = 0;
        $progressTB = 0;

        if ($dataPemeriksaan->count() >= 2) {
            $terbaru = $dataPemeriksaan->first();
            $sebelumnya = $dataPemeriksaan->skip(1)->first();

            $progressBB = round($terbaru->bb - $sebelumnya->bb, 1);
            $progressTB = round($terbaru->tb - $sebelumnya->tb, 1);
        }

        // ✅ HITUNG STATUS KESEHATAN REMAJA
        $statusKesehatan = $this->calculateRemajaHealthStatus($pemeriksaanTerakhir);

        // ✅ HITUNG MASALAH PSIKOSOSIAL
        $masalahPsikososial = 0;
        if ($pemeriksaanTerakhir) {
            $psikososialFields = [
                'nyaman_dirumah' => 'Tidak',
                'masalah_pendidikan' => 'Ya',
                'masalah_pola_makan' => 'Ya',
                'masalah_aktivitas' => 'Ya',
                'masalah_obat' => 'Ya',
                'masalah_kesehatan_seksual' => 'Ya',
                'masalah_keamanan' => 'Ya',
                'masalah_kesehatan_mental' => 'Ya'
            ];

            foreach ($psikososialFields as $field => $problemValue) {
                if ($pemeriksaanTerakhir->$field === $problemValue) {
                    $masalahPsikososial++;
                }
            }
        }

        // ✅ HITUNG GEJALA TBC
        $gejalaTBC = 0;
        if ($pemeriksaanTerakhir) {
            $tbcFields = ['batuk_terus_menerus', 'demam_2_minggu', 'bb_tidak_naik', 'kontak_tbc'];
            foreach ($tbcFields as $field) {
                if ($pemeriksaanTerakhir->$field) {
                    $gejalaTBC++;
                }
            }
        }

        return view('admin-page.remaja.remaja-home', compact(
            'user',
            'dataPemeriksaan',
            'pemeriksaanTerakhir',
            'totalPemeriksaan',
            'progressBB',
            'progressTB',
            'statusKesehatan',
            'masalahPsikososial',
            'gejalaTBC'
        ));
    }

    /**
     * Calculate remaja health status
     */
    private function calculateRemajaHealthStatus($pemeriksaan)
    {
        if (!$pemeriksaan) {
            return [
                'status' => 'Belum Ada Data',
                'score' => 0,
                'description' => 'Belum ada pemeriksaan'
            ];
        }

        $score = 100;
        $issues = [];

        // ✅ CEK IMT
        if ($pemeriksaan->kesimpulan_imt) {
            if (
                strpos($pemeriksaan->kesimpulan_imt, 'Sangat Kurus') !== false ||
                strpos($pemeriksaan->kesimpulan_imt, 'Obesitas') !== false
            ) {
                $score -= 30;
                $issues[] = 'IMT tidak normal';
            } elseif (
                strpos($pemeriksaan->kesimpulan_imt, 'Kurus') !== false ||
                strpos($pemeriksaan->kesimpulan_imt, 'Gemuk') !== false
            ) {
                $score -= 15;
                $issues[] = 'IMT perlu perhatian';
            }
        }

        // ✅ CEK ANEMIA
        if ($pemeriksaan->status_anemia === 'Ya') {
            $score -= 25;
            $issues[] = 'Anemia';
        }

        // ✅ CEK TEKANAN DARAH
        if ($pemeriksaan->kategori_tekanan_darah) {
            if (strpos($pemeriksaan->kategori_tekanan_darah, 'Hipertensi') !== false) {
                $score -= 30;
                $issues[] = 'Hipertensi';
            } elseif (strpos($pemeriksaan->kategori_tekanan_darah, 'Hipotensi') !== false) {
                $score -= 20;
                $issues[] = 'Hipotensi';
            }
        }

        // ✅ CEK TBC
        $gejalaTBC = 0;
        $tbcFields = ['batuk_terus_menerus', 'demam_2_minggu', 'bb_tidak_naik', 'kontak_tbc'];
        foreach ($tbcFields as $field) {
            if ($pemeriksaan->$field) {
                $gejalaTBC++;
            }
        }

        if ($gejalaTBC >= 2) {
            $score -= 40;
            $issues[] = 'Suspek TBC';
        } elseif ($gejalaTBC >= 1) {
            $score -= 10;
            $issues[] = 'Gejala TBC ringan';
        }

        // ✅ CEK PSIKOSOSIAL
        $masalahPsikososial = 0;
        $psikososialFields = [
            'nyaman_dirumah' => 'Tidak',
            'masalah_pendidikan' => 'Ya',
            'masalah_pola_makan' => 'Ya',
            'masalah_aktivitas' => 'Ya',
            'masalah_obat' => 'Ya',
            'masalah_kesehatan_seksual' => 'Ya',
            'masalah_keamanan' => 'Ya',
            'masalah_kesehatan_mental' => 'Ya'
        ];

        foreach ($psikososialFields as $field => $problemValue) {
            if ($pemeriksaan->$field === $problemValue) {
                $masalahPsikososial++;
            }
        }

        if ($masalahPsikososial >= 3) {
            $score -= 25;
            $issues[] = 'Masalah psikososial berat';
        } elseif ($masalahPsikososial >= 1) {
            $score -= 10;
            $issues[] = 'Masalah psikososial ringan';
        }

        // ✅ TENTUKAN STATUS
        if ($score >= 80) {
            $status = 'Sehat';
        } elseif ($score >= 60) {
            $status = 'Perlu Perhatian';
        } else {
            $status = 'Perlu Rujukan';
        }

        return [
            'status' => $status,
            'score' => $score,
            'issues' => $issues,
            'description' => implode(', ', $issues)
        ];
    }
}
