<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PemeriksaanLansia;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

class PemeriksaanLansiaController extends Controller
{
    public function store(Request $request)
    {
        // Debug: cek data yang masuk
        // dd($request->all());

        $validated = $request->validate([
            'nik' => 'required|exists:users,nik', // ✅ GANTI dari user_id
            'tanggal_pemeriksaan' => 'required|date',
            'bb' => 'required|numeric',
            'tb' => 'required|numeric',
            'lingkar_perut' => 'required|numeric',
            'sistole' => 'required|integer',
            'diastole' => 'required|integer',
            'gula_darah' => 'required|numeric',
            'imt' => 'nullable|numeric',
            'pemeriksa' => 'required|string|max:255',
            'kesimpulan_imt' => 'nullable|string',
            'kesimpulan_sistole' => 'nullable|string',
            'kesimpulan_diastole' => 'nullable|string',
            'kesimpulan_td' => 'nullable|string',
            'kesimpulan_gula_darah' => 'nullable|string',
            'tes_jari_kanan' => 'required|string',
            'tes_jari_kiri' => 'required|string',
            'tes_berbisik_kanan' => 'required|string',
            'tes_berbisik_kiri' => 'required|string',
            'puma_jk' => 'required|integer',
            'puma_usia' => 'required|integer',
            'puma_rokok' => 'required|integer',
            'puma_napas' => 'required|integer',
            'puma_dahak' => 'required|integer',
            'puma_batuk' => 'required|integer',
            'puma_spirometri' => 'required|integer',
            'skor_puma' => 'nullable|integer',
            'status_puma' => 'nullable|string',
            'tbc_batuk' => 'nullable|in:on',
            'tbc_demam' => 'nullable|in:on',
            'tbc_bb_turun' => 'nullable|in:on',
            'tbc_kontak' => 'nullable|in:on',
            'status_tbc' => 'nullable|string',
            'alat_kontrasepsi' => 'required|integer',
            'edukasi' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id();

        // Checkbox TBC jika tidak diceklis akan null, ubah ke false
        foreach (['tbc_batuk', 'tbc_demam', 'tbc_bb_turun', 'tbc_kontak'] as $field) {
            $validated[$field] = $request->has($field);
        }

        PemeriksaanLansia::create($validated);

        return redirect()->back()->with('success', 'Data pemeriksaan lansia berhasil disimpan!');
    }
    // Skrining Tahunan
    public function skriningTahunan($userId)
    {
        $user = User::findOrFail($userId);
        // Jika ingin kirim data lain, tambahkan di sini
        return view('admin-page.pemeriksaan-form.skrining-tahunan-lansia', compact('user'));
    }

    // ✅ TAMBAH METHOD LANSIA HOME
    public function lansiaHome()
    {
        $user = Auth::user();

        // Ambil data pemeriksaan lansia untuk user ini
        $dataPemeriksaan = PemeriksaanLansia::where('nik', $user->nik)
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->limit(12)
            ->get();

        // Pemeriksaan terakhir
        $pemeriksaanTerakhir = $dataPemeriksaan->first();
        $pemeriksaanSebelumnya = $dataPemeriksaan->skip(1)->first(); // ✅ TAMBAH

        // Total pemeriksaan
        $totalPemeriksaan = $dataPemeriksaan->count();

        // Hitung status kesehatan
        $statusKesehatan = $this->calculateHealthStatus($pemeriksaanTerakhir, $user);

        // Progress BB (bandingkan dengan pemeriksaan sebelumnya)
        $progressBB = 0;
        if ($dataPemeriksaan->count() >= 2) {
            $pemeriksaanSebelumnya = $dataPemeriksaan->get(1);
            $progressBB = $pemeriksaanTerakhir->bb - $pemeriksaanSebelumnya->bb;
        }

        // ✅ TAMBAH PROGRESS LAINNYA - TIDAK MENGGANGGU
        $progressSistole = 0;
        $progressDiastole = 0;
        $progressGula = 0;

        if ($pemeriksaanTerakhir && $pemeriksaanSebelumnya) {
            $progressSistole = $pemeriksaanTerakhir->sistole - $pemeriksaanSebelumnya->sistole;
            $progressDiastole = $pemeriksaanTerakhir->diastole - $pemeriksaanSebelumnya->diastole;
            $progressGula = $pemeriksaanTerakhir->gula_darah - $pemeriksaanSebelumnya->gula_darah;
        }

        return view('admin-page.lansia.lansia-home', compact(
            'user',
            'dataPemeriksaan',
            'pemeriksaanTerakhir',
            'pemeriksaanSebelumnya',    // ✅ TAMBAH
            'totalPemeriksaan',
            'statusKesehatan',
            'progressBB',
            'progressSistole',          // ✅ TAMBAH
            'progressDiastole',         // ✅ TAMBAH
            'progressGula'              // ✅ TAMBAH
        ));
    }

    // ✅ HITUNG STATUS KESEHATAN LANSIA
    private function calculateHealthStatus($pemeriksaan, $user)
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

        $riskFactors = 0;
        $criticalConditions = 0;
        $ageFactors = 0;

        // ✅ EVALUASI KONDISI KRITIS LANSIA
        // Hipertensi Stage 2 (lebih toleran untuk lansia)
        if ($pemeriksaan->sistole >= 150 || $pemeriksaan->diastole >= 95) {
            $criticalConditions++;
        }

        // Diabetes
        if ($pemeriksaan->gula_darah >= 200) {
            $criticalConditions++;
        }

        // Obesitas (IMT > 30)
        if ($pemeriksaan->imt >= 30) {
            $criticalConditions++;
        }

        // TBC Suspek (2+ gejala)
        $gejalaTBC = 0;
        if ($pemeriksaan->tbc_batuk) $gejalaTBC++;
        if ($pemeriksaan->tbc_demam) $gejalaTBC++;
        if ($pemeriksaan->tbc_bb_turun) $gejalaTBC++;
        if ($pemeriksaan->tbc_kontak) $gejalaTBC++;

        if ($gejalaTBC >= 2) {
            $criticalConditions++;
        }

        // PUMA Positif (skor > 6)
        if ($pemeriksaan->skor_puma > 6) {
            $criticalConditions++;
        }

        // ✅ EVALUASI FAKTOR RISIKO LANSIA
        // Hipertensi Grade 1 (lebih toleran untuk lansia)
        if ($pemeriksaan->sistole >= 140 && $pemeriksaan->sistole < 150) {
            $riskFactors++;
        }

        // Pre-diabetes
        if ($pemeriksaan->gula_darah >= 140 && $pemeriksaan->gula_darah < 200) {
            $riskFactors++;
        }

        // Overweight (toleran untuk lansia)
        if ($pemeriksaan->imt >= 25 && $pemeriksaan->imt < 30) {
            $riskFactors++;
        }

        // Masalah pendengaran (umum pada lansia)
        $masalahPendengaran = 0;
        if ($pemeriksaan->tes_jari_kanan !== 'Normal') $masalahPendengaran++;
        if ($pemeriksaan->tes_jari_kiri !== 'Normal') $masalahPendengaran++;
        if ($pemeriksaan->tes_berbisik_kanan !== 'Normal') $masalahPendengaran++;
        if ($pemeriksaan->tes_berbisik_kiri !== 'Normal') $masalahPendengaran++;

        if ($masalahPendengaran >= 2) {
            $riskFactors++;
        }

        // ✅ FAKTOR USIA LANSIA
        $umur = Carbon::parse($user->tanggal_lahir)->age;

        // Risiko tinggi umur > 70
        if ($umur > 70) {
            $ageFactors++;
        }

        // Risiko sangat tinggi umur > 80
        if ($umur > 80) {
            $ageFactors++;
        }

        // ✅ TENTUKAN STATUS BERDASARKAN KONDISI LANSIA
        if ($criticalConditions >= 2) {
            return [
                'status' => 'Perlu Rujukan Segera',
                'category' => 'danger',
                'score' => 85 + ($criticalConditions * 5),
                'badge' => 'bg-danger',
                'icon' => 'exclamation-triangle-fill',
                'description' => 'Ada beberapa kondisi serius yang memerlukan penanganan medis segera'
            ];
        }

        if ($criticalConditions >= 1) {
            return [
                'status' => 'Perlu Rujukan',
                'category' => 'warning',
                'score' => 65 + ($criticalConditions * 10),
                'badge' => 'bg-warning',
                'icon' => 'exclamation-triangle',
                'description' => 'Ada kondisi yang memerlukan perhatian medis'
            ];
        }

        if ($riskFactors >= 3 || ($riskFactors >= 2 && $ageFactors >= 1)) {
            return [
                'status' => 'Perlu Perhatian',
                'category' => 'warning',
                'score' => 45 + ($riskFactors * 5) + ($ageFactors * 3),
                'badge' => 'bg-warning',
                'icon' => 'exclamation-circle',
                'description' => 'Beberapa faktor risiko perlu diperhatikan mengingat usia lanjut'
            ];
        }

        if ($riskFactors >= 1 || $ageFactors >= 1) {
            return [
                'status' => 'Perlu Perhatian',
                'category' => 'info',
                'score' => 30 + ($riskFactors * 8) + ($ageFactors * 5),
                'badge' => 'bg-info',
                'icon' => 'info-circle',
                'description' => 'Ada faktor risiko yang perlu dipantau secara berkala'
            ];
        }

        return [
            'status' => 'Sehat',
            'category' => 'success',
            'score' => 95 - ($ageFactors * 5), // Skor dikurangi sesuai usia
            'badge' => 'bg-success',
            'icon' => 'shield-check',
            'description' => 'Kondisi kesehatan baik untuk usia lanjut, pertahankan pola hidup sehat'
        ];
    }

    // ✅ METHOD BARU - TIDAK MENGGANGGU YANG LAMA
    public function show($id)
    {
        $user = User::findOrFail($id);

        $dataPemeriksaan = PemeriksaanLansia::where('nik', $user->nik)
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->get();

        $pemeriksaanTerakhir = $dataPemeriksaan->first();
        $pemeriksaanSebelumnya = $dataPemeriksaan->skip(1)->first();

        return view('admin-page.lansia.detail-pemeriksaan', compact(
            'user',
            'dataPemeriksaan',
            'pemeriksaanTerakhir',
            'pemeriksaanSebelumnya'
        ));
    }
}
