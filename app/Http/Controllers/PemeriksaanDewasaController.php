<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PemeriksaanDewasa;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class PemeriksaanDewasaController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|exists:users,nik',
            'tanggal_pemeriksaan' => 'required|date',
            'bb' => 'required|numeric',
            'tb' => 'required|numeric',
            'lingkar_perut' => 'required|numeric',
            'sistole' => 'required|integer',
            'diastole' => 'required|integer',
            'gula_darah' => 'required|numeric',
            'imt' => 'nullable|numeric',
            'kesimpulan_imt' => 'nullable|string',
            'kesimpulan_sistole' => 'nullable|string',
            'kesimpulan_diastole' => 'nullable|string',
            'kesimpulan_td' => 'nullable|string',
            'kesimpulan_gula_darah' => 'nullable|string',
            'tes_jari_kanan' => 'required|string',
            'tes_jari_kiri' => 'required|string',
            'pemeriksa' => 'required|string|max:255',
            'tes_berbisik_kanan' => 'required|string',
            'tes_berbisik_kiri' => 'required|string',
            'puma_jk' => 'nullable|integer',
            'puma_usia' => 'nullable|integer',
            'puma_rokok' => 'nullable|integer',
            'puma_napas' => 'nullable|integer',
            'puma_dahak' => 'nullable|integer',
            'puma_batuk' => 'nullable|integer',
            'puma_spirometri' => 'nullable|integer',
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

        PemeriksaanDewasa::create($validated);

        return redirect()->back()->with('success', 'Data pemeriksaan dewasa berhasil disimpan!');
    }

    public function skriningTahunanDewasa($userId)
    {
        $user = User::findOrFail($userId);
        return view('admin-page.pemeriksaan-form.skrining-tahunan-dewasa', compact('user'));
    }

    // ✅ DEWASA HOME METHOD
    public function dewasaHome()
    {
        $user = Auth::user();

        // Ambil data pemeriksaan dewasa untuk user ini
        $dataPemeriksaan = PemeriksaanDewasa::where('nik', $user->nik)
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->get();

        // Pemeriksaan terakhir
        $pemeriksaanTerakhir = $dataPemeriksaan->first();

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

        // ✅ HITUNG KONTROL BERIKUTNYA BERDASARKAN STATUS KESEHATAN
        $kontrolBerikutnya = $this->calculateNextControl($pemeriksaanTerakhir, $statusKesehatan);

        return view('admin-page.dewasa.dewasa-home', compact(
            'user',
            'dataPemeriksaan',
            'pemeriksaanTerakhir',
            'totalPemeriksaan',
            'statusKesehatan',
            'progressBB',
            'kontrolBerikutnya'
        ));
    }

    // ✅ HITUNG KONTROL BERIKUTNYA BERDASARKAN KONDISI
    private function calculateNextControl($pemeriksaan, $statusKesehatan)
    {
        if (!$pemeriksaan) {
            return [
                'interval' => 0,
                'label' => 'Segera',
                'date' => null,
                'days' => 0,
                'color' => 'danger'
            ];
        }

        $interval = 1;
        $nextDate = Carbon::parse($pemeriksaan->tanggal_pemeriksaan)->addMonth();
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
        } elseif ($daysLeft <= 7) {
            $color = 'warning';
        } elseif ($daysLeft <= 14) {
            $color = 'info';
        }

        return [
            'interval' => $interval,
            'label' => 'Kontrol Bulanan',
            'date' => $nextDate,
            'days' => $daysLeft,
            'color' => $color
        ];
    }

    // ✅ LABEL INTERVAL
    private function getIntervalLabel($interval)
    {
        switch ($interval) {
            case 1:
                return 'Kontrol Bulanan';
            case 2:
                return 'Kontrol 2 Bulanan';
            case 3:
                return 'Kontrol 3 Bulanan';
            case 6:
                return 'Kontrol 6 Bulanan';
            default:
                return 'Kontrol Rutin';
        }
    }

    // ✅ HITUNG STATUS KESEHATAN DEWASA
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

        // ✅ EVALUASI KONDISI KRITIS
        // Hipertensi Stage 2
        if ($pemeriksaan->sistole >= 140 || $pemeriksaan->diastole >= 90) {
            $criticalConditions++;
        }

        // Diabetes
        if ($pemeriksaan->gula_darah >= 200) {
            $criticalConditions++;
        }

        // Obesitas
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

        // PUMA Positif
        if (($pemeriksaan->skor_puma ?? 0) > 6) {
            $criticalConditions++;
        }

        // ✅ EVALUASI FAKTOR RISIKO
        // Pre-hipertensi
        if ($pemeriksaan->sistole >= 130 && $pemeriksaan->sistole < 140) {
            $riskFactors++;
        }

        // Pre-diabetes
        if ($pemeriksaan->gula_darah >= 140 && $pemeriksaan->gula_darah < 200) {
            $riskFactors++;
        }

        // Overweight
        if (($pemeriksaan->imt ?? 0) >= 25 && $pemeriksaan->imt < 30) {
            $riskFactors++;
        }

        // Masalah pendengaran
        if (
            ($pemeriksaan->tes_jari_kanan ?? 'Normal') !== 'Normal' ||
            ($pemeriksaan->tes_jari_kiri ?? 'Normal') !== 'Normal' ||
            ($pemeriksaan->tes_berbisik_kanan ?? 'Normal') !== 'Normal' ||
            ($pemeriksaan->tes_berbisik_kiri ?? 'Normal') !== 'Normal'
        ) {
            $riskFactors++;
        }

        // ✅ TENTUKAN STATUS BERDASARKAN KONDISI
        if ($criticalConditions >= 2) {
            return [
                'status' => 'Perlu Rujukan Segera',
                'category' => 'danger',
                'score' => 90 + ($criticalConditions * 5),
                'badge' => 'bg-danger',
                'icon' => 'exclamation-triangle-fill',
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
                'description' => 'Ada kondisi yang memerlukan perhatian medis'
            ];
        }

        if ($riskFactors >= 3) {
            return [
                'status' => 'Perlu Perhatian',
                'category' => 'warning',
                'score' => 50 + ($riskFactors * 5),
                'badge' => 'bg-warning',
                'icon' => 'exclamation-circle',
                'description' => 'Beberapa faktor risiko perlu diperhatikan'
            ];
        }

        if ($riskFactors >= 1) {
            return [
                'status' => 'Perlu Perhatian',
                'category' => 'info',
                'score' => 30 + ($riskFactors * 10),
                'badge' => 'bg-info',
                'icon' => 'info-circle',
                'description' => 'Ada faktor risiko yang perlu dipantau'
            ];
        }

        return [
            'status' => 'Sehat',
            'category' => 'success',
            'score' => 100,
            'badge' => 'bg-success',
            'icon' => 'shield-check',
            'description' => 'Kondisi kesehatan baik, pertahankan pola hidup sehat'
        ];
    }
}
