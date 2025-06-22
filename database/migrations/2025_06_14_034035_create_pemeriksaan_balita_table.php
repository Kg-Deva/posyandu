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
            $table->decimal('lingkar_kepala', 4, 1)->nullable(); // Lingkar kepala (cm)
            $table->decimal('lila', 4, 1)->nullable(); // ✅ TAMBAH INI - LILA (cm)
            $table->integer('umur'); // Umur dalam bulan
            $table->text('kesimpulan_bbu')->nullable(); // Kesimpulan BB/U
            $table->text('kesimpulan_tbuu')->nullable(); // Kesimpulan TB/U
            $table->text('kesimpulan_bbtb')->nullable(); // Kesimpulan BB/TB
            $table->string('kesimpulan_lingkar_kepala', 50)->nullable(); // Normal/Melebihi normal/Kurang dari normal
            $table->string('kesimpulan_lila', 100)->nullable(); // ✅ TAMBAH INI - Kesimpulan LILA
            $table->string('status_perubahan_bb', 1000)->nullable(); // Status perubahan BB
            $table->date('tanggal_pemeriksaan');
            $table->string('pemeriksa'); // Nama kader/petugas

            // ✅ BALITA MENDAPATKAN
            $table->boolean('asi_eksklusif')->default(false);
            $table->boolean('mp_asi')->default(false);
            $table->boolean('imunisasi')->default(false);
            $table->boolean('vitamin_a')->default(false);
            $table->boolean('obat_cacing')->default(false);
            $table->boolean('mt_pangan_lokal')->default(false);

            // ✅ GEJALA SAKIT
            $table->boolean('ada_gejala_sakit')->default(false);
            $table->text('sebutkan_gejala')->nullable();

            // ✅ EDUKASI
            $table->text('mp_asi_protein_hewani')->nullable();

            // ✅ SKRINING TBC
            $table->boolean('batuk_terus_menerus')->default(false);
            $table->boolean('demam_2_minggu')->default(false);
            $table->boolean('bb_tidak_naik')->default(false);
            $table->boolean('kontak_tbc')->default(false);
            $table->integer('jumlah_gejala_tbc')->default(0);
            $table->string('rujuk_puskesmas', 100)->nullable();

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
