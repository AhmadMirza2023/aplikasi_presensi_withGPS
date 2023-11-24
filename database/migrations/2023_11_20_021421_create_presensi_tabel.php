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
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nik');
            $table->date('tgl_presensi');
            $table->date('jam_in');
            $table->date('jam_out');
            $table->string('foto_in');
            $table->string('foto_out');
            $table->text('lokasi_in');
            $table->text('lokasi_out');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
