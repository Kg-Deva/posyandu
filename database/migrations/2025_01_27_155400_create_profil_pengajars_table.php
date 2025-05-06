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
        Schema::create('profil_pengajars', function (Blueprint $table) {
            $table->id();
            $table->string('pengajar');  // Kolom untuk nama pengajar
            $table->string('jabatan');        // Kolom untuk jabatan pengajar
            $table->string('gambar')->nullable(); // Kolom untuk gambar, nullable agar tidak wajib
            $table->timestamps();            // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_pengajars');
    }
};
