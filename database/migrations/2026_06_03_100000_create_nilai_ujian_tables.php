<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('nilai_ujian_penguji', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('pelatih_id')->constrained('pelatih')->cascadeOnDelete();
            $table->foreignId('tingkat_id')->constrained('tingkat')->cascadeOnDelete();
            $table->string('tahun_periode');
            $table->string('materi_latihan');
            $table->unsignedTinyInteger('nomor_penguji');
            $table->decimal('wiraga', 5, 2)->default(0);
            $table->decimal('wirama', 5, 2)->default(0);
            $table->decimal('wirasa', 5, 2)->default(0);
            $table->decimal('rata_penguji', 5, 2)->default(0);
            $table->date('tanggal_ujian');
            $table->timestamps();

            $table->unique(
                ['siswa_id', 'tingkat_id', 'tahun_periode', 'materi_latihan', 'nomor_penguji'],
                'nilai_ujian_penguji_unique'
            );
        });

        Schema::create('nilai_ujian_materi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('pelatih_id')->constrained('pelatih')->cascadeOnDelete();
            $table->foreignId('tingkat_id')->constrained('tingkat')->cascadeOnDelete();
            $table->string('tahun_periode');
            $table->string('materi_latihan');
            $table->decimal('nilai_fix', 5, 2)->nullable();
            $table->unsignedTinyInteger('penguji_terisi')->default(0);
            $table->date('tanggal_ujian')->nullable();
            $table->timestamps();

            $table->unique(
                ['siswa_id', 'tingkat_id', 'tahun_periode', 'materi_latihan'],
                'nilai_ujian_materi_unique'
            );
        });

        Schema::create('rekap_nilai_ujian', function (Blueprint $table) {
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
            $table->boolean('evaluasi_selesai')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'tingkat_id', 'tahun_periode'], 'rekap_nilai_ujian_unique');
        });

        Schema::table('evaluasi_tingkat', function (Blueprint $table) {
            $table->foreignId('rekap_nilai_ujian_id')
                ->nullable()
                ->after('rekap_nilai_harian_id')
                ->constrained('rekap_nilai_ujian')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('evaluasi_tingkat', function (Blueprint $table) {
            $table->dropConstrainedForeignId('rekap_nilai_ujian_id');
        });

        Schema::dropIfExists('rekap_nilai_ujian');
        Schema::dropIfExists('nilai_ujian_materi');
        Schema::dropIfExists('nilai_ujian_penguji');
    }
};
