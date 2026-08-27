<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NilaiHarian extends Model
{
    use HasFactory;

    protected $table = 'nilai_harian';

    protected $fillable = [
        'user_id',
        'siswa_id',
        'pelatih_id',
        'nilai',
        'tanggal',
        'keterangan',
        'wiraga',
        'wirasa',
        'wirama',
        'tingkat_id',
        'tahun_periode',
        'materi_latihan',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pelatih()
    {
        return $this->belongsTo(Pelatih::class);
    }
}