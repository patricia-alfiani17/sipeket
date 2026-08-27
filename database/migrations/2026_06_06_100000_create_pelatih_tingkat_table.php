<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pelatih_tingkat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelatih_id')->constrained('pelatih')->cascadeOnDelete();
            $table->foreignId('tingkat_id')->constrained('tingkat')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['pelatih_id', 'tingkat_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelatih_tingkat');
    }
};
