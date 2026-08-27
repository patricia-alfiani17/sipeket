<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengajuan_mengulang_tingkat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('tingkat_id')->constrained('tingkat')->cascadeOnDelete();
            $table->foreignId('tingkat_saat_pengajuan_id')->constrained('tingkat')->cascadeOnDelete();
            $table->string('tahun_periode')->nullable();
            $table->text('alasan');
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->foreignId('pelatih_id')->nullable()->constrained('pelatih')->nullOnDelete();
            $table->text('catatan_pelatih')->nullable();
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_keputusan')->nullable();
            $table->timestamps();

            $table->index(['siswa_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_mengulang_tingkat');
    }
};
