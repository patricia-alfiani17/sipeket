<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('nilai_harian', function (Blueprint $table) {
            // Add user_id to link directly to users table for easier reference
            $table->foreignId('user_id')->nullable()->after('siswa_id')->constrained('users')->nullOnDelete();
            
            // Add new columns for the three value components
            $table->decimal('wiraga', 5, 2)->nullable()->after('nilai');
            $table->decimal('wirasa', 5, 2)->nullable()->after('wiraga');
            $table->decimal('wirama', 5, 2)->nullable()->after('wirasa');
            
            // Add tingkat_id for tracking level
            $table->foreignId('tingkat_id')->nullable()->after('wirama')->constrained('tingkat')->nullOnDelete();
            
            // Add tahun_periode for tracking academic period
            $table->string('tahun_periode')->nullable()->after('tingkat_id');
            
            // Add materi_latihan for tracking training material
            $table->string('materi_latihan')->nullable()->after('tahun_periode');
        });
    }

    public function down(): void
    {
        Schema::table('nilai_harian', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'wiraga', 'wirasa', 'wirama', 'tingkat_id', 'tahun_periode', 'materi_latihan']);
        });
    }
};
