<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'level',
        'email',
        'password',
        'status',
        'type',
        'data_lengkap',
        'nama',
        'jenis_kelamin',
        'nik',
        'tanggal_lahir',
        'umur',
        'alamat',
        'no_hp',
        'dusun',
        'rt',
        'rw',
        'kecamatan',
        'wilayah',
        'berat_badan_lahir',
        'panjang_badan_lahir',
        'nama_ayah',
        'nama_ibu',
        'status_perkawinan',
        'pekerjaan',
        'riwayat_keluarga',
        'riwayat_diri',
        'perilaku_beresiko',
        'jarak_kehamilan_tahun',
        'jarak_kehamilan_bulan',
        'berat_badan_ibu',
        'hamil_ke',
        'tinggi_badan_ibu',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function pemeriksaanBalita()
    {
        return $this->hasMany(PemeriksaanBalita::class, 'nik', 'nik');
    }

    // ✅ ADD THIS NEW RELATIONSHIP
    public function pemeriksaanRemaja()
    {
        return $this->hasMany(PemeriksaanRemaja::class, 'nik', 'nik');
    }
}
