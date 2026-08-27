<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('materi_latihan', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->text('deskripsi')->nullable();
            $table->integer('urutan')->default(1);
            $table->timestamps();
        });

        DB::table('materi_latihan')->insertOrIgnore([
            'nama' => 'Latihan Kuda-Kuda',
            'deskripsi' => 'Materi dasar yang umum digunakan untuk evaluasi nilai harian.',
            'urutan' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('materi_latihan');
    }
};
