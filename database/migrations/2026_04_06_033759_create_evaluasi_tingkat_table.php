<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('evaluasi_tingkat', function (Blueprint $table) {
            $table->id();

            $table->foreignId('siswa_id')
                  ->constrained('siswa')
                  ->cascadeOnDelete();

            $table->foreignId('tingkat_id')
                  ->constrained('tingkat')
                  ->cascadeOnDelete();

            $table->decimal('rata_rata_nilai', 5, 2);

            $table->enum('status', [
                'naik',
                'dipertimbangkan',
                'tidak_naik'
            ]);

            $table->boolean('keputusan_manual')
                  ->default(false);

            $table->foreignId('pelatih_id')
                  ->constrained('pelatih')
                  ->cascadeOnDelete();

            $table->date('tanggal_evaluasi');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluasi_tingkat');
    }
};