<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spesies_id')->constrained('spesies')->restrictOnDelete();
            $table->foreignId('lokasi_id')->constrained('lokasi')->restrictOnDelete();
            $table->date('tanggal_rekam')->index();
            $table->time('waktu_mulai')->nullable();
            $table->unsignedInteger('durasi_detik');
            $table->unsignedInteger('sample_rate')->default(48000);
            $table->string('format', 10)->default('WAV');
            $table->string('kualitas', 10)->default('Baik')->index();
            $table->string('kondisi_cuaca', 60)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekaman');
    }
};
