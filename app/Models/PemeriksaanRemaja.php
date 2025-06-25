<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PemeriksaanRemaja extends Model
{
    protected $table = 'pemeriksaan_remaja';

    protected $fillable = [
        'nik',
        'tanggal_pemeriksaan',
        'bb',
        'tb',
        'lingkar_perut',
        'sistole',
        'diastole',
        'hb',
        'nilai_imt',
        'kesimpulan_imt',
        'kesimpulan_sistole',
        'kesimpulan_diastole',
        'status_anemia',
        'kategori_tekanan_darah',
        'batuk_terus_menerus',
        'demam_2_minggu',
        'bb_tidak_naik',
        'kontak_tbc',
        'jumlah_gejala_tbc',
        'rujuk_puskesmas',
        'nyaman_dirumah',
        'masalah_pendidikan',
        'masalah_pola_makan',
        'masalah_aktivitas',
        'masalah_obat',
        'masalah_kesehatan_seksual',
        'masalah_keamanan',
        'masalah_kesehatan_mental',
        'edukasi',
        'pemeriksa',
        'jenis_kelamin'
    ];

    protected $casts = [
        'tanggal_pemeriksaan' => 'date',
        'bb' => 'decimal:2',
        'tb' => 'decimal:1',
        'lingkar_perut' => 'decimal:1',
        'hb' => 'decimal:1',
        'nilai_imt' => 'decimal:1', // ✅ TAMBAH INI
        'batuk_terus_menerus' => 'boolean',
        'demam_2_minggu' => 'boolean',
        'bb_tidak_naik' => 'boolean',
        'kontak_tbc' => 'boolean'
    ];

    /**
     * Relationship dengan User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'nik', 'nik');
    }

    /**
     * Accessor untuk IMT
     */
    public function getImtAttribute()
    {
        if ($this->bb && $this->tb) {
            return round($this->bb / pow($this->tb / 100, 2), 1);
        }
        return null;
    }

    /**
     * Accessor untuk umur saat pemeriksaan (dari user)
     */
    public function getUmurAttribute()
    {
        if ($this->user && $this->user->tanggal_lahir) {
            $lahir = Carbon::parse($this->user->tanggal_lahir);
            $pemeriksaan = Carbon::parse($this->tanggal_pemeriksaan);
            return $pemeriksaan->diffInYears($lahir);
        }
        return null;
    }

    /**
     * Scope untuk filter bulan ini
     */
    public function scopeBulanIni($query)
    {
        return $query->whereYear('tanggal_pemeriksaan', now()->year)
            ->whereMonth('tanggal_pemeriksaan', now()->month);
    }

    /**
     * Scope untuk filter yang perlu rujukan
     */
    public function scopePerluRujukan($query)
    {
        return $query->where('rujuk_puskesmas', 'like', '%RUJUK%');
    }
}
