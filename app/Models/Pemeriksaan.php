<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    use HasFactory;
    
    protected $table = 'pemeriksaan';
    
    protected $fillable = [
        'user_id',
        'tanggal_pemeriksaan',
        'data',
        'catatan'
    ];
    
    protected $casts = [
        'tanggal_pemeriksaan' => 'date',
        'data' => 'array'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}