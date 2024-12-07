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
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pegawai_id');
            $table->bigInteger('pengaturan_id');
            $table->bigInteger('tempat_kerja_id');
            $table->string('berkas');
            $table->date('tanggal');
            $table->time('waktu');
            $table->string('koordinat');
            $table->string('tipe');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
