<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekaman_peneliti', function (Blueprint $table) {
            $table->foreignId('rekaman_id')->constrained('rekaman')->cascadeOnDelete();
            $table->foreignId('peneliti_id')->constrained('peneliti')->cascadeOnDelete();
            $table->string('peran', 50)->default('Perekam');
            $table->primary(['rekaman_id', 'peneliti_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekaman_peneliti');
    }
};
