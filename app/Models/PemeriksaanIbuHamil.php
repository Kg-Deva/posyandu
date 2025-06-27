<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PemeriksaanIbuHamil extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaan_ibu_hamil';

    protected $fillable = [
        'nik',
        'tanggal_pemeriksaan',
        'bb',
        'tb',
        'bb_pra_hamil',
        'lila',
        'sistole',
        'diastole',
        // 'hb',
        'usia_kehamilan',
        // ✅ TAMBAH: Auto-calculated fields dari JavaScript
        'status_bb_kia',
        'status_lila',
        'kesimpulan_sistole',
        'kesimpulan_diastole',
        'status_td_kia',
        'status_rujukan',
        // Backend calculated fields
        'nilai_imt_pra_hamil',
        'kategori_imt_pra_hamil',
        'kenaikan_bb',
        'status_kenaikan_bb',
        'kategori_tekanan_darah',
        'status_anemia',
        'batuk_terus_menerus',
        'demam_2_minggu',
        'bb_tidak_naik',
        'kontak_tbc',
        'jumlah_gejala_tbc',
        'rujuk_puskesmas_tbc',
        // ✅ TAMBAH: Suplementasi fields
        'jumlah_tablet_fe',
        'konsumsi_tablet_fe',
        'status_tablet_fe',
        'komposisi_mt_bumilkek',
        'jumlah_porsi_mt',
        'konsumsi_mt_bumilkek',
        'mendapat_mt_bumil_kek',
        // ✅ TAMBAH: Kelas Ibu fields - field baru
        'mengikuti_kelas_ibu',

        'perlu_rujukan',
        'alasan_rujukan',
        'edukasi',
        'pemeriksa'
    ];

    protected $casts = [
        'tanggal_pemeriksaan' => 'date',
        'mendapat_mt_bumil_kek' => 'boolean',
        'batuk_terus_menerus' => 'boolean',
        'demam_2_minggu' => 'boolean',
        'bb_tidak_naik' => 'boolean',
        'kontak_tbc' => 'boolean',
        'perlu_rujukan' => 'boolean',
    ];

    /**
     * Relasi ke User berdasarkan NIK
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'nik', 'nik');
    }

    /**
     * Scope untuk filter berdasarkan bulan dan tahun
     */
    public function scopeByMonth($query, $month, $year)
    {
        return $query->whereMonth('tanggal_pemeriksaan', $month)
            ->whereYear('tanggal_pemeriksaan', $year);
    }

    /**
     * Scope untuk data terbaru berdasarkan NIK
     */
    public function scopeLatestByNik($query, $nik)
    {
        return $query->where('nik', $nik)
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->first();
    }

    /**
     * Hitung IMT Pra Hamil
     */
    public static function hitungIMTPraHamil($bbPraHamil, $tb)
    {
        if (!$bbPraHamil || !$tb) return null;

        $tbMeter = $tb / 100;
        return round($bbPraHamil / ($tbMeter * $tbMeter), 1);
    }

    /**
     * Tentukan kategori IMT Pra Hamil
     */
    public static function kategoriIMTPraHamil($imt)
    {
        if ($imt < 18.5) return 'Underweight';
        if ($imt < 25) return 'Normal';
        if ($imt < 30) return 'Overweight';
        return 'Obesitas';
    }

    /**
     * Tentukan status kenaikan BB berdasarkan kategori IMT dan usia kehamilan
     */
    public static function statusKenaikanBB($kategoriIMT, $usiaKehamilan, $kenaikanBB)
    {
        // Rekomendasi kenaikan BB berdasarkan Institute of Medicine (IOM)
        $rekomendasi = [
            'Underweight' => ['min' => 12.5, 'max' => 18.0],
            'Normal' => ['min' => 11.5, 'max' => 16.0],
            'Overweight' => ['min' => 7.0, 'max' => 11.5],
            'Obesitas' => ['min' => 5.0, 'max' => 9.0]
        ];

        if (!isset($rekomendasi[$kategoriIMT])) return 'Data tidak valid';

        $min = $rekomendasi[$kategoriIMT]['min'];
        $max = $rekomendasi[$kategoriIMT]['max'];

        // Perhitungan proporsional berdasarkan usia kehamilan (40 minggu = full term)
        $proporsi = $usiaKehamilan / 40;
        $minProporsi = $min * $proporsi;
        $maxProporsi = $max * $proporsi;

        if ($kenaikanBB < $minProporsi) return 'Kurang';
        if ($kenaikanBB > $maxProporsi) return 'Berlebih';
        return 'Sesuai';
    }

    /**
     * Tentukan status LILA
     */
    public static function statusLILA($lila)
    {
        return $lila < 23.5 ? 'KEK' : 'Normal';
    }

    /**
     * Tentukan kategori tekanan darah
     */
    public static function kategoriTekananDarah($sistole, $diastole)
    {
        if ($sistole >= 140 || $diastole >= 90) return 'Hipertensi';
        if ($sistole < 90 || $diastole < 60) return 'Hipotensi';
        return 'Normal';
    }

    /**
     * Tentukan status anemia untuk ibu hamil
     */
    public static function statusAnemia($hb)
    {
        if ($hb >= 11.0) return 'Normal';
        if ($hb >= 10.0) return 'Anemia Ringan';
        if ($hb >= 7.0) return 'Anemia Sedang';
        return 'Anemia Berat';
    }

    /**
     * ✅ UPDATE: Tentukan status TTD berdasarkan konsumsi
     */
    public static function statusTTD($konsumsiTTD)
    {
        if ($konsumsiTTD === 'Setiap hari') return 'Cukup';
        if ($konsumsiTTD === 'Tidak setiap hari') return 'Kurang';
        return 'Tidak diketahui';
    }

    /**
     * Tentukan apakah perlu rujukan
     */
    public static function perlurujukan($data)
    {
        $alasan = [];

        // Cek hipertensi berat
        if ($data['sistole'] >= 160 || $data['diastole'] >= 110) {
            $alasan[] = 'Hipertensi berat';
        }

        // ✅ UPDATE: Cek hipertensi sedang
        if ($data['sistole'] >= 140 || $data['diastole'] >= 90) {
            $alasan[] = 'Hipertensi';
        }

        // ✅ UPDATE: Cek hipotensi
        if ($data['sistole'] < 90 || $data['diastole'] < 60) {
            $alasan[] = 'Hipotensi';
        }

        // Cek anemia berat
        if (isset($data['hb']) && $data['hb'] < 7.0) {
            $alasan[] = 'Anemia berat';
        }

        // Cek gejala TBC (≥2 gejala)
        $gejalaTBC = 0;
        if ($data['batuk_terus_menerus']) $gejalaTBC++;
        if ($data['demam_2_minggu']) $gejalaTBC++;
        if ($data['bb_tidak_naik']) $gejalaTBC++;
        if ($data['kontak_tbc']) $gejalaTBC++;

        if ($gejalaTBC >= 2) {
            $alasan[] = 'Suspek TBC (≥2 gejala)';
        }

        // ✅ UPDATE: Cek KEK (LILA)
        if (isset($data['lila']) && $data['lila'] < 23.5) {
            $alasan[] = 'KEK (LILA < 23.5 cm)';
        }

        // Cek kenaikan BB berlebih atau sangat kurang
        if (
            isset($data['status_kenaikan_bb']) &&
            ($data['status_kenaikan_bb'] === 'Berlebih' || $data['status_kenaikan_bb'] === 'Kurang')
        ) {
            $alasan[] = 'Kenaikan berat badan tidak sesuai';
        }

        return [
            'perlu_rujukan' => count($alasan) > 0,
            'alasan_rujukan' => implode(', ', $alasan)
        ];
    }
}
