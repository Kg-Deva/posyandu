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
        'umur',
        'kesimpulan_bbu',
        'kesimpulan_tbuu',
        'kesimpulan_bbtb',
        'status_perubahan_bb',  // ✅ TAMBAH INI
        'tanggal_pemeriksaan',
        'pemeriksa'
    ];

    protected $casts = [
        'tanggal_pemeriksaan' => 'date',
        'bb' => 'decimal:2',
        'tb' => 'decimal:1'
    ];

    // Relationship dengan User (balita)
    public function balita()
    {
        return $this->belongsTo(User::class, 'nik', 'nik');
    }
}
