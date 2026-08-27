<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tahun_periode', function (Blueprint $table) {
            $table->id();
            $table->string('periode')->unique();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        $currentYear = now()->year;
        $nextYear = $currentYear + 1;
        $periode = "$currentYear/$nextYear";

        DB::table('tahun_periode')->insertOrIgnore([
            'periode' => $periode,
            'is_default' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('tahun_periode');
    }
};
