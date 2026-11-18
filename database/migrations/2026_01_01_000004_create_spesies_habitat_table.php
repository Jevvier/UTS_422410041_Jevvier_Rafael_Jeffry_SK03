<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spesies_habitat', function (Blueprint $table) {
            $table->foreignId('spesies_id')->constrained('spesies')->cascadeOnDelete();
            $table->foreignId('habitat_id')->constrained('habitats')->cascadeOnDelete();
            $table->primary(['spesies_id', 'habitat_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spesies_habitat');
    }
};
