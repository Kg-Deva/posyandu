<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Data umum
            $table->boolean('data_lengkap')->default(false); // <-- tambahkan ini
            $table->string('nama')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->string('nik')->nullable()->unique(); // ✅ NULLABLE tapi UNIQUE           
            $table->date('tanggal_lahir')->nullable(); // ✅ TAMBAH INI
            $table->string('umur')->nullable();
            $table->string('alamat')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('dusun')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('wilayah')->nullable();

            // Balita & Remaja
            $table->float('berat_badan_lahir')->nullable();
            $table->float('panjang_badan_lahir')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();

            // Lansia & Dewasa
            $table->string('status_perkawinan')->nullable();
            $table->string('pekerjaan')->nullable();

            // Riwayat & Perilaku
            $table->text('riwayat_keluarga')->nullable();
            $table->text('riwayat_diri')->nullable();
            $table->text('perilaku_beresiko')->nullable();

            // Ibu Hamil
            $table->integer('jarak_kehamilan_tahun')->nullable();
            $table->integer('jarak_kehamilan_bulan')->nullable();
            $table->float('berat_badan_ibu')->nullable();
            $table->integer('hamil_ke')->nullable();
            $table->float('tinggi_badan_ibu')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'data_lengkap',
                'nama',
                'jenis_kelamin',
                'nik',
                'tanggal_lahir', // ✅ TAMBAH INI JUGA
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
                'tinggi_badan_ibu'
            ]);
        });
    }
};
