<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pemeriksaan;

class PemeriksaanController extends Controller
{
    public function cariPasien()
    {
        return view('admin-page.kader.cari-pasien');
    }

    public function hasilCariPasien(Request $request)
    {
        $request->validate([
            'nik' => 'required|string'
        ]);

        $pasien = User::where('nik', $request->nik)->first();

        if (!$pasien) {
            return redirect()->back()->with('error', 'Pasien dengan NIK tersebut tidak ditemukan');
        }

        // Redirect ke form pemeriksaan sesuai level pasien
        return redirect()->route('pemeriksaan.form', ['id' => $pasien->id]);
    }

    public function formPemeriksaan($id)
    {
        $pasien = User::findOrFail($id);

        // Pilih view berdasarkan level pasien
        $view = 'admin-page.kader.pemeriksaan';

        switch ($pasien->level) {
            case 'balita':
                $view .= 'balita';
                break;
            case 'remaja':
                $view .= 'remaja';
                break;
            case 'dewasa':
                $view .= 'dewasa';
                break;
            case 'ibu hamil':
                $view .= 'ibu-hamil';
                break;
            case 'lansia':
                $view .= 'lansia';
                break;
            default:
                $view .= 'umum';
        }

        return view($view, compact('pasien'));
    }

    public function simpanPemeriksaan(Request $request, $id)
    {
        $pasien = User::findOrFail($id);

        // Validasi sesuai level pasien
        $rules = [
            'tanggal_pemeriksaan' => 'required|date',
            'catatan' => 'nullable|string'
        ];

        // Tambahkan validasi khusus per level
        switch ($pasien->level) {
            case 'balita':
                $rules = array_merge($rules, [
                    'berat_badan' => 'required|numeric',
                    'tinggi_badan' => 'required|numeric',
                    'lingkar_kepala' => 'required|numeric'
                ]);
                break;
            case 'remaja':
                $rules = array_merge($rules, [
                    'berat_badan' => 'required|numeric',
                    'tinggi_badan' => 'required|numeric',
                    'tekanan_darah' => 'required|string'
                ]);
                break;
            case 'dewasa':
            case 'lansia':
                $rules = array_merge($rules, [
                    'berat_badan' => 'required|numeric',
                    'tinggi_badan' => 'required|numeric',
                    'tekanan_darah' => 'required|string',
                    'gula_darah' => 'nullable|numeric',
                    'kolesterol' => 'nullable|numeric'
                ]);
                break;
            case 'ibu hamil':
                $rules = array_merge($rules, [
                    'berat_badan' => 'required|numeric',
                    'tinggi_badan' => 'required|numeric',
                    'tekanan_darah' => 'required|string',
                    'usia_kehamilan' => 'required|numeric',
                    'tinggi_fundus' => 'nullable|numeric',
                    'denyut_jantung_janin' => 'nullable|numeric'
                ]);
                break;
        }

        $request->validate($rules);

        // Simpan data pemeriksaan
        $pemeriksaan = new Pemeriksaan();
        $pemeriksaan->user_id = $pasien->id;
        $pemeriksaan->tanggal_pemeriksaan = $request->tanggal_pemeriksaan;
        $pemeriksaan->catatan = $request->catatan;

        // Simpan data sesuai level
        $data = $request->except(['_token', 'tanggal_pemeriksaan', 'catatan']);
        $pemeriksaan->data = json_encode($data);

        $pemeriksaan->save();

        return redirect()->route('pemeriksaan.riwayat')->with('success', 'Data pemeriksaan berhasil disimpan');
    }

    public function riwayatPemeriksaan()
    {
        $pemeriksaan = Pemeriksaan::with('user')->orderBy('tanggal_pemeriksaan', 'desc')->get();
        return view('admin-page.kader.riwayat-pemeriksaan', compact('pemeriksaan'));
    }

    public function detailPemeriksaan($id)
    {
        $pemeriksaan = Pemeriksaan::with('user')->findOrFail($id);
        return view('admin-page.kader.detail-pemeriksaan', compact('pemeriksaan'));
    }
}
