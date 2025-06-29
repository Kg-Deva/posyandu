<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkriningDewasa extends Model
{
    protected $table = 'skrining_dewasa';

    protected $fillable = [
        'user_id',
        'tanggal_pemeriksaan',
        'mental',
        'total_skor_mental',
        'status_rujukan',
    ];

    protected $casts = [
        'mental' => 'array',
    ];
}
