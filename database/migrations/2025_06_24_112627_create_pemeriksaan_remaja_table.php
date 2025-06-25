<?php
// Run command: php artisan make:migration create_pemeriksaan_remaja_table

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
        Schema::create('pemeriksaan_remaja', function (Blueprint $table) {
            $table->id();
            $table->string('nik'); // NIK remaja
            $table->date('tanggal_pemeriksaan');

            // Data pemeriksaan fisik
            $table->decimal('bb', 5, 2); // Berat badan (kg)
            $table->decimal('tb', 5, 1); // Tinggi badan (cm)
            $table->decimal('lingkar_perut', 5, 1)->nullable(); // Lingkar perut (cm)
            $table->integer('sistole'); // Tekanan darah sistole
            $table->integer('diastole'); // Tekanan darah diastole
            $table->decimal('hb', 4, 1); // Hemoglobin (mg/dl)

            // Kesimpulan otomatis
            $table->decimal('nilai_imt', 4, 1)->nullable(); // ✅ TAMBAH INI
            $table->string('kesimpulan_imt', 100)->nullable();
            $table->string('kesimpulan_sistole', 100)->nullable();
            $table->string('kesimpulan_diastole', 100)->nullable();
            $table->string('status_anemia', 50)->nullable();
            $table->string('kategori_tekanan_darah', 100)->nullable();

            // Skrining TBC
            $table->boolean('batuk_terus_menerus')->default(false);
            $table->boolean('demam_2_minggu')->default(false);
            $table->boolean('bb_tidak_naik')->default(false);
            $table->boolean('kontak_tbc')->default(false);
            $table->string('jumlah_gejala_tbc', 200)->nullable();
            $table->string('rujuk_puskesmas', 200)->nullable();

            // Skrining psiko-sosial remaja
            $table->string('nyaman_dirumah', 10)->nullable(); // Ya/Tidak
            $table->string('masalah_pendidikan', 10)->nullable(); // Ya/Tidak
            $table->string('masalah_pola_makan', 10)->nullable(); // Ya/Tidak
            $table->string('masalah_aktivitas', 10)->nullable(); // Ya/Tidak
            $table->string('masalah_obat', 10)->nullable(); // Ya/Tidak
            $table->string('masalah_kesehatan_seksual', 10)->nullable(); // Ya/Tidak
            $table->string('masalah_keamanan', 10)->nullable(); // Ya/Tidak
            $table->string('masalah_kesehatan_mental', 10)->nullable(); // Ya/Tidak

            // Edukasi/konseling
            $table->text('edukasi')->nullable();

            // Metadata
            $table->string('pemeriksa'); // Nama kader/petugas
            $table->string('jenis_kelamin', 20); // Untuk perhitungan anemia

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
        Schema::dropIfExists('pemeriksaan_remaja');
    }
};
