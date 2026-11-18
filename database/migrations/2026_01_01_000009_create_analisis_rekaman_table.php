<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analisis_rekaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekaman_id')->constrained('rekaman')->cascadeOnDelete();
            $table->string('jenis_analisis', 100);
            $table->decimal('frekuensi_dominan_hz', 8, 2)->nullable();
            $table->decimal('tingkat_keyakinan', 5, 2)->default(0);
            $table->text('hasil')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analisis_rekaman');
    }
};
