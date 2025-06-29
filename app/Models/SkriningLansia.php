<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkriningLansia extends Model
{
    protected $table = 'skrining_lansia';

    protected $fillable = [
        'user_id',
        'tanggal_pemeriksaan',
        'bab',
        'bak',
        'bersih_diri',
        'wc',
        'makan',
        'bergerak',
        'pakaian',
        'tangga',
        'mandi',
        'jalan_rata',
        'total_skor',
        'status_kemandirian',
        'edukasi',
        'mental',
        'total_skor_mental',
        'skilas',
        'status_rujukan', // <--- tambahkan ini
    ];

    protected $casts = [
        'mental' => 'array',
        'skilas' => 'array',
    ];
}
