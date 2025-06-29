<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SkriningDewasa;
use Carbon\Carbon;

class SkriningDewasaController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal_pemeriksaan' => 'required|date',
            'mental' => 'required|array',
            'total_skor_mental' => 'required|integer',
            'status_rujukan' => 'required|string',
        ]);

        // CEK SUDAH PERNAH ISI DI TAHUN INI
        $tahun = Carbon::parse($validated['tanggal_pemeriksaan'])->year;
        $sudahAda = SkriningDewasa::where('user_id', $validated['user_id'])
            ->whereYear('tanggal_pemeriksaan', $tahun)
            ->exists();

        if ($sudahAda) {
            return redirect()->back()->with('error', 'Skrining tahunan dewasa untuk tahun ini sudah pernah diisi.');
        }

        SkriningDewasa::create($validated);

        return redirect()->back()->with('success', 'Skrining tahunan dewasa berhasil disimpan!');
    }
}
