<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beranda extends Model
{
    use HasFactory;

// Tentukan kolom yang dapat diisi secara massal
    protected $fillable = [
        'tagline',
        'deskripsi',
        'gambar',
        'maps',
    ];

    // Jika ingin custom nama primary key selain 'id', misalnya 'beranda_id'
    // protected $primaryKey = 'beranda_id';
}
