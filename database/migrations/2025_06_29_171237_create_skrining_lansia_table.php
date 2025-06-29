<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('skrining_lansia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('tanggal_pemeriksaan');
            // AKS
            $table->tinyInteger('bab');
            $table->tinyInteger('bak');
            $table->tinyInteger('bersih_diri');
            $table->tinyInteger('wc');
            $table->tinyInteger('makan');
            $table->tinyInteger('bergerak');
            $table->tinyInteger('pakaian');
            $table->tinyInteger('tangga');
            $table->tinyInteger('mandi');
            $table->tinyInteger('jalan_rata');
            $table->integer('total_skor');
            $table->string('status_kemandirian');
            $table->string('edukasi')->nullable();
            $table->string('status_rujukan')->nullable(); // Tambahan di sini
            // Mental
            $table->json('mental');
            $table->integer('total_skor_mental');
            // SKILAS
            $table->json('skilas');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skrining_lansia');
    }
};
