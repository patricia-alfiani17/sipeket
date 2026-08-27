<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            if (! Schema::hasColumn('pendaftaran', 'tempat_lahir')) {
                $table->string('tempat_lahir')->nullable()->after('tanggal_lahir');
            }

            if (! Schema::hasColumn('pendaftaran', 'jenis_kelamin')) {
                $table->enum('jenis_kelamin', ['L','P'])->nullable()->after('tempat_lahir');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            if (Schema::hasColumn('pendaftaran', 'jenis_kelamin')) {
                $table->dropColumn('jenis_kelamin');
            }
            if (Schema::hasColumn('pendaftaran', 'tempat_lahir')) {
                $table->dropColumn('tempat_lahir');
            }
        });
    }
};
