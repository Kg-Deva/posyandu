<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SkriningLansia;
use Carbon\Carbon;

class SkriningLansiaController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal_pemeriksaan' => 'required|date',
            // AKS
            'bab' => 'required|integer',
            'bak' => 'required|integer',
            'bersih_diri' => 'required|integer',
            'wc' => 'required|integer',
            'makan' => 'required|integer',
            'bergerak' => 'required|integer',
            'pakaian' => 'required|integer',
            'tangga' => 'required|integer',
            'mandi' => 'required|integer',
            'jalan_rata' => 'required|integer',
            'total_skor' => 'required|integer',
            'status_kemandirian' => 'required|string',
            'edukasi' => 'nullable|string',
            // Mental
            'mental' => 'required|array',
            'total_skor_mental' => 'required|integer',
            // SKILAS
            'skilas' => 'required|array',
            'status_rujukan' => 'required|string',
        ]);

        // CEK SUDAH PERNAH ISI DI TAHUN INI
        $tahun = Carbon::parse($validated['tanggal_pemeriksaan'])->year;
        $sudahAda = SkriningLansia::where('user_id', $validated['user_id'])
            ->whereYear('tanggal_pemeriksaan', $tahun)
            ->exists();

        if ($sudahAda) {
            return redirect()->back()->with('error', 'Skrining tahunan lansia untuk tahun ini sudah pernah diisi.');
        }

        SkriningLansia::create([
            'user_id' => $validated['user_id'],
            'tanggal_pemeriksaan' => $validated['tanggal_pemeriksaan'],
            'bab' => $validated['bab'],
            'bak' => $validated['bak'],
            'bersih_diri' => $validated['bersih_diri'],
            'wc' => $validated['wc'],
            'makan' => $validated['makan'],
            'bergerak' => $validated['bergerak'],
            'pakaian' => $validated['pakaian'],
            'tangga' => $validated['tangga'],
            'mandi' => $validated['mandi'],
            'jalan_rata' => $validated['jalan_rata'],
            'total_skor' => $validated['total_skor'],
            'status_kemandirian' => $validated['status_kemandirian'],
            'edukasi' => $validated['edukasi'] ?? null,
            'mental' => $validated['mental'],   // TANPA json_encode
            'total_skor_mental' => $validated['total_skor_mental'],
            'skilas' => $validated['skilas'],   // TANPA json_encode
            'status_rujukan' => $validated['status_rujukan'],
        ]);

        return redirect()->back()->with('success', 'Skrining lansia berhasil disimpan!');
    }
}
