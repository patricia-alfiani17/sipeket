<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {

            // Data Calon Siswa
            $table->date('tanggal_lahir')->after('nama_lengkap');
            $table->string('nama_panggilan')->after('tanggal_lahir');
            $table->string('asal_sekolah')->after('nama_panggilan');
            $table->string('kontak_aktif')->after('asal_sekolah');
            $table->string('akta_kelahiran')->nullable()->after('kontak_aktif');
            $table->text('alamat')->change();
            $table->foreignId('tingkat_id')
                  ->nullable()
                  ->constrained('tingkat')
                  ->nullOnDelete();

            // Data Orang Tua
            $table->string('nama_orangtua')->after('tingkat_id');
            $table->string('pekerjaan_orangtua')->after('nama_orangtua');
            $table->string('kontak_orangtua')->after('pekerjaan_orangtua');
            $table->text('alamat_orangtua')->nullable()->after('kontak_orangtua');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal_lahir',
                'nama_panggilan',
                'asal_sekolah',
                'kontak_aktif',
                'akta_kelahiran',
                'tingkat_id',
                'nama_orangtua',
                'pekerjaan_orangtua',
                'kontak_orangtua',
                'alamat_orangtua'
            ]);
        });
    }
};