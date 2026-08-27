<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('materi_latihan', function (Blueprint $table) {
            $table->foreignId('tingkat_id')->nullable()->after('id')->constrained('tingkat')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('materi_latihan', function (Blueprint $table) {
            $table->dropForeign(['tingkat_id']);
            $table->dropColumn('tingkat_id');
        });
    }
};
