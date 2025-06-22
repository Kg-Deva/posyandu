<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemeriksaanBalita extends Model
{
    protected $table = 'pemeriksaan_balita';

    protected $fillable = [
        'nik',
        'bb',
        'tb',
        'lingkar_kepala',
        'lila', // ✅ TAMBAH INI YANG MISSING
        'umur',
        'kesimpulan_bbu',
        'kesimpulan_tbuu',
        'kesimpulan_bbtb',
        'kesimpulan_lingkar_kepala',
        'kesimpulan_lila', // ✅ TAMBAH INI YANG MISSING
        'status_perubahan_bb',
        'tanggal_pemeriksaan',
        'pemeriksa',
        'asi_eksklusif',
        'mp_asi',
        'imunisasi',
        'vitamin_a',
        'obat_cacing',
        'mt_pangan_lokal',
        'ada_gejala_sakit',
        'sebutkan_gejala',
        'mp_asi_protein_hewani',
        'batuk_terus_menerus',
        'demam_2_minggu',
        'bb_tidak_naik',
        'kontak_tbc',
        'jumlah_gejala_tbc',
        'rujuk_puskesmas'
    ];

    protected $casts = [
        'tanggal_pemeriksaan' => 'date',
        'bb' => 'decimal:2',
        'tb' => 'decimal:1',
        'lingkar_kepala' => 'decimal:1',
        'lila' => 'decimal:1', // ✅ TAMBAH INI
        'asi_eksklusif' => 'boolean',
        'mp_asi' => 'boolean',
        'imunisasi' => 'boolean',
        'vitamin_a' => 'boolean',
        'obat_cacing' => 'boolean',
        'mt_pangan_lokal' => 'boolean',
        'ada_gejala_sakit' => 'boolean',
        'batuk_terus_menerus' => 'boolean',
        'demam_2_minggu' => 'boolean',
        'bb_tidak_naik' => 'boolean',
        'kontak_tbc' => 'boolean',
        'jumlah_gejala_tbc' => 'integer'
    ];

    // ✅ FIX RELATIONSHIP - GANTI NAMA METHOD
    public function user()
    {
        return $this->belongsTo(User::class, 'nik', 'nik');
    }
}
