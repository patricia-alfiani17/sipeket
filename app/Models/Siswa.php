<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'user_id',
        'nis',
        'nama_panggilan',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'tingkat_id',
        'status',
        'asal_sekolah',
        'kelas',
        'nama_orangtua',
        'pekerjaan_orangtua',
        'kontak_orangtua',
        'alamat_orangtua',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // =========================
    // RELASI
    // =========================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tingkat()
    {
        return $this->belongsTo(Tingkat::class);
    }

    public function nilaiHarian()
    {
        return $this->hasMany(NilaiHarian::class);
    }

    public function nilaiUjian()
    {
        return $this->hasMany(NilaiUjian::class);
    }

    public function evaluasi()
    {
        return $this->hasMany(EvaluasiTingkat::class);
    }

    public function riwayatTingkat()
    {
        return $this->hasMany(RiwayatTingkat::class);
    }

    public function pengajuanMengulang()
    {
        return $this->hasMany(PengajuanMengulangTingkat::class);
    }
}