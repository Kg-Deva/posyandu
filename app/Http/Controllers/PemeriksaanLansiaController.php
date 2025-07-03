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
}
