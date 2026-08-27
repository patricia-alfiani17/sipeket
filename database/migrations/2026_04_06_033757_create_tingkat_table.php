<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tingkat', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tingkat');
            $table->enum('jenis_penilaian', ['harian','ujian']);
            $table->integer('kkm')->default(75);
            $table->unsignedTinyInteger('ambang_tidak_lulus')->default(70)->comment('Nilai <= ambang ini dianggap tidak lulus');
            $table->unsignedTinyInteger('ambang_pertimbangan_min')->default(71)->comment('Batas bawah rentang pertimbangan');
            $table->unsignedTinyInteger('ambang_pertimbangan_max')->default(74)->comment('Batas atas rentang pertimbangan');
            $table->integer('urutan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tingkat');
    }
};