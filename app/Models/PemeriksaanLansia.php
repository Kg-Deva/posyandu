<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemeriksaanLansia extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaan_lansia';

    protected $fillable = [
        'nik', // ✅ TAMBAH ini
        'tanggal_pemeriksaan',
        'bb',
        'tb',
        'lingkar_perut',
        'sistole',
        'diastole',
        'gula_darah',
        'imt',
        'kesimpulan_imt',
        'kesimpulan_sistole',
        'kesimpulan_diastole',
        'kesimpulan_td',
        'kesimpulan_gula_darah',
        'tes_jari_kanan',
        'tes_jari_kiri',
        'tes_berbisik_kanan',
        'tes_berbisik_kiri',
        'puma_jk',
        'puma_usia',
        'puma_rokok',
        'puma_napas',
        'puma_dahak',
        'puma_batuk',
        'puma_spirometri',
        'skor_puma',
        'status_puma',
        'tbc_batuk',
        'tbc_demam',
        'tbc_bb_turun',
        'tbc_kontak',
        'status_tbc',
        'alat_kontrasepsi',
        'edukasi',
        'pemeriksa',
        'created_by',
    ];

    // ✅ TAMBAH RELASI INI - INI YANG MISSING!
    public function user()
    {
        // return $this->belongsTo(User::class, 'user_id', 'id');
        return $this->belongsTo(User::class, 'nik', 'nik'); // ✅ GANTI iniFIX ini
    }
}
