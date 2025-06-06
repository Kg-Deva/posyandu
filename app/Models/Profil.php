<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dengan nama model
    // protected $table = 'profil';

    // Tentukan kolom yang dapat diisi secara massal
    protected $fillable = [
        'tujuan',
        'visi',
        'misi',
        'gambar',
    ];

    // Jika Anda ingin custom primary key
    // protected $primaryKey = 'profil_id';

    // Jika tabel tidak menggunakan timestamps (created_at, updated_at)
    // public $timestamps = false;
}
