<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rekap_nilai_harian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('pelatih_id')->constrained('pelatih')->cascadeOnDelete();
            $table->foreignId('tingkat_id')->constrained('tingkat')->cascadeOnDelete();
            $table->string('tahun_periode');
            $table->decimal('average', 5, 2)->nullable();
            $table->string('status')->nullable();
            $table->integer('materi_count')->default(0);
            $table->integer('filled_count')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'tingkat_id', 'tahun_periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekap_nilai_harian');
    }
};
