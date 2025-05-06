<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    // Kolom yang dapat diisi secara massal (mass assignment)
    protected $fillable = [
        'gallery_item', // Ubah nama kolom sesuai dengan nama baru
    ];
}
