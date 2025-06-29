<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PemeriksaanDewasa;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class PemeriksaanDewasaController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
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
}
