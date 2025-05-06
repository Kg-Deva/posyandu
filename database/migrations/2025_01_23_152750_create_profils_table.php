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
        Schema::create('profils', function (Blueprint $table) {
            $table->id();
            $table->text('tujuan');  // Kolom untuk menyimpan tujuan
            $table->text('visi');  // Kolom untuk visi
            $table->text('misi');    // Kolom untuk misi
            $table->string('gambar'); // Kolom untuk gambar, wajib diisi
            $table->timestamps();    // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profils');
    }
};
