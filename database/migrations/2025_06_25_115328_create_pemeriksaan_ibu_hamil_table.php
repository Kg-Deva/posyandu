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
        Schema::create('pemeriksaan_ibu_hamil', function (Blueprint $table) {
            $table->id();
            $table->string('nik'); // NIK ibu hamil
            $table->date('tanggal_pemeriksaan');

            // Data pemeriksaan fisik
            $table->decimal('bb', 5, 2); // Berat badan sekarang (kg)
            $table->decimal('tb', 5, 1); // Tinggi badan (cm)
            $table->decimal('bb_pra_hamil', 5, 2)->nullable(); // Berat badan pra hamil (kg)
            $table->decimal('lila', 4, 1); // Lingkar lengan atas (cm)
            $table->integer('sistole'); // Tekanan darah sistole
            $table->integer('diastole'); // Tekanan darah diastole
            $table->decimal('hb', 4, 1)->nullable(); // Hemoglobin (mg/dl)
            $table->integer('usia_kehamilan'); // Usia kehamilan (minggu)

            // ✅ TAMBAH: Auto-calculated fields dari JavaScript
            $table->string('status_bb_kia', 200)->nullable(); // Ya/Tidak - Status BB sesuai kurva
            $table->string('status_lila', 200)->nullable(); // Ya/Tidak - Status LILA
            $table->string('kesimpulan_sistole', 100)->nullable(); // Normal/Tinggi/Rendah
            $table->string('kesimpulan_diastole', 100)->nullable(); // Normal/Tinggi/Rendah
            $table->string('status_td_kia', 200)->nullable(); // Ya/Tidak - Status TD sesuai KIA
            $table->string('status_rujukan', 200)->nullable(); // Rujuk/Tidak Rujuk

            // Kesimpulan otomatis - Status BB (backend calculation)
            $table->decimal('nilai_imt_pra_hamil', 4, 1)->nullable();
            $table->string('kategori_imt_pra_hamil', 50)->nullable(); // Underweight/Normal/Overweight/Obesitas
            $table->decimal('kenaikan_bb', 4, 1)->nullable(); // BB sekarang - BB pra hamil
            $table->string('status_kenaikan_bb', 100)->nullable(); // Sesuai/Berlebih/Kurang

            // Kesimpulan otomatis - Tekanan Darah (backend calculation)
            $table->string('kategori_tekanan_darah', 100)->nullable(); // Normal/Tinggi/Rendah

            // Kesimpulan otomatis - Anemia
            $table->string('status_anemia', 50)->nullable(); // Normal/Anemia Ringan/Sedang/Berat

            // Skrining TBC
            $table->boolean('batuk_terus_menerus')->default(false);
            $table->boolean('demam_2_minggu')->default(false);
            $table->boolean('bb_tidak_naik')->default(false);
            $table->boolean('kontak_tbc')->default(false);
            $table->string('jumlah_gejala_tbc', 200)->nullable();
            $table->string('rujuk_puskesmas_tbc', 200)->nullable(); // Status rujukan TBC

            // ✅ TAMBAH: Suplementasi TTD - sesuai form
            $table->integer('jumlah_tablet_fe')->nullable(); // Jumlah tablet yang diberikan
            $table->string('konsumsi_tablet_fe', 50)->nullable(); // Setiap hari/Tidak setiap hari
            $table->string('status_tablet_fe', 100)->nullable(); // Cukup/Kurang

            // ✅ TAMBAH: MT Bumil KEK - sesuai form
            $table->text('komposisi_mt_bumilkek')->nullable(); // Komposisi MT
            $table->integer('jumlah_porsi_mt')->nullable(); // Jumlah porsi yang diberikan
            $table->string('konsumsi_mt_bumilkek', 50)->nullable(); // Setiap hari/Tidak setiap hari
            $table->boolean('mendapat_mt_bumil_kek')->default(false); // Otomatis dari jumlah_porsi_mt

            // ✅ TAMBAH: Kelas Ibu - field baru sesuai form (TANPA frekuensi)
            $table->enum('mengikuti_kelas_ibu', ['Ya', 'Tidak'])->nullable(); // Mengikuti kelas ibu

            // Status Rujukan Otomatis
            $table->boolean('perlu_rujukan')->default(false);
            $table->text('alasan_rujukan')->nullable();

            // Edukasi/konseling
            $table->text('edukasi')->nullable();

            // Metadata
            $table->string('pemeriksa'); // Nama kader/petugas

            $table->timestamps();

            // Index untuk query cepat
            $table->index('nik');
            $table->index('tanggal_pemeriksaan');
            $table->index('usia_kehamilan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_ibu_hamil');
    }
};
