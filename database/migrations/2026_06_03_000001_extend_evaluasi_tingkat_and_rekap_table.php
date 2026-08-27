<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rekap_nilai_harian', function (Blueprint $table) {
            $table->boolean('evaluasi_selesai')->default(false)->after('filled_count');
        });

        Schema::table('evaluasi_tingkat', function (Blueprint $table) {
            $table->string('tahun_periode')->after('tingkat_id');
            $table->enum('status_kelulusan', ['lulus', 'toleransi', 'tidak_lulus'])->after('rata_rata_nilai');
            $table->foreignId('rekap_nilai_harian_id')
                ->nullable()
                ->after('status_kelulusan')
                ->constrained('rekap_nilai_harian')
                ->nullOnDelete();

            $table->unique(['siswa_id', 'tingkat_id', 'tahun_periode'], 'evaluasi_tingkat_siswa_tingkat_periode_unique');
        });
    }

    public function down(): void
    {
        Schema::table('evaluasi_tingkat', function (Blueprint $table) {
            $table->dropUnique('evaluasi_tingkat_siswa_tingkat_periode_unique');
            $table->dropConstrainedForeignId('rekap_nilai_harian_id');
            $table->dropColumn(['tahun_periode', 'status_kelulusan']);
        });

        Schema::table('rekap_nilai_harian', function (Blueprint $table) {
            $table->dropColumn('evaluasi_selesai');
        });
    }
};
