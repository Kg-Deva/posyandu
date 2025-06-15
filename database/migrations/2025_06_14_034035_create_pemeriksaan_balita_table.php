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

        Schema::create('pemeriksaan_balita', function (Blueprint $table) {
            $table->id();
            $table->string('nik'); // NIK balita
            $table->decimal('bb', 5, 2); // Berat badan (kg)
            $table->decimal('tb', 5, 1); // Tinggi badan (cm)
            $table->integer('umur'); // Umur dalam bulan
            $table->text('kesimpulan_bbu')->nullable(); // Kesimpulan BB/U
            $table->text('kesimpulan_tbuu')->nullable(); // Kesimpulan TB/U
            $table->text('kesimpulan_bbtb')->nullable(); // Kesimpulan BB/TB
            $table->string('status_perubahan_bb', 50)->nullable(); // ✅ TAMBAH INI
            $table->date('tanggal_pemeriksaan');
            $table->string('pemeriksa'); // Nama kader/petugas
            $table->timestamps();

            // Index untuk query cepat
            $table->index('nik');
            $table->index('tanggal_pemeriksaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_balita');
    }
};
