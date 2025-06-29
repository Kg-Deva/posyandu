<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemeriksaanDewasaTable extends Migration
{
    public function up()
    {
        Schema::create('pemeriksaan_dewasa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('tanggal_pemeriksaan');
            $table->float('bb');
            $table->float('tb');
            $table->float('lingkar_perut');
            $table->integer('sistole');
            $table->integer('diastole');
            $table->float('gula_darah');
            $table->float('imt')->nullable();
            $table->string('kesimpulan_imt')->nullable();
            $table->string('kesimpulan_sistole')->nullable();
            $table->string('kesimpulan_diastole')->nullable();
            $table->string('kesimpulan_td')->nullable();
            $table->string('kesimpulan_gula_darah')->nullable();
            $table->string('tes_jari_kanan');
            $table->string('tes_jari_kiri');
            $table->string('tes_berbisik_kanan');
            $table->string('tes_berbisik_kiri');
            // PUMA
            $table->tinyInteger('puma_jk')->nullable();
            $table->tinyInteger('puma_usia')->nullable();
            $table->tinyInteger('puma_rokok')->nullable();
            $table->tinyInteger('puma_napas')->nullable();
            $table->tinyInteger('puma_dahak')->nullable();
            $table->tinyInteger('puma_batuk')->nullable();
            $table->tinyInteger('puma_spirometri')->nullable();
            $table->tinyInteger('skor_puma')->nullable();
            $table->string('status_puma')->nullable();
            // TBC
            $table->boolean('tbc_batuk')->default(false);
            $table->boolean('tbc_demam')->default(false);
            $table->boolean('tbc_bb_turun')->default(false);
            $table->boolean('tbc_kontak')->default(false);
            $table->string('status_tbc')->nullable();
            // Kontrasepsi & Edukasi
            $table->tinyInteger('alat_kontrasepsi');
            $table->text('edukasi')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pemeriksaan_dewasa');
    }
}
