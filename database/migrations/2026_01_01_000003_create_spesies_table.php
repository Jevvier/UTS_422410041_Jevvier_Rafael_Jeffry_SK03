<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spesies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_spesies_id')
                ->constrained('kategori_spesies')
                ->restrictOnDelete();
            $table->string('nama_ilmiah', 150)->unique();
            $table->string('nama_umum', 150);
            $table->string('status_konservasi', 4)->default('LC')->index();
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->index('nama_umum');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spesies');
    }
};
