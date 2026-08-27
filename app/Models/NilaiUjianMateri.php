<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiUjianMateri extends Model
{
    use HasFactory;

    protected $table = 'nilai_ujian_materi';

    protected $fillable = [
        'user_id',
        'siswa_id',
        'pelatih_id',
        'tingkat_id',
        'tahun_periode',
        'materi_latihan',
        'nilai_fix',
        'penguji_terisi',
        'tanggal_ujian',
    ];

    protected $casts = [
        'tanggal_ujian' => 'date',
        'nilai_fix' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function pelatih()
    {
        return $this->belongsTo(Pelatih::class);
    }

    public function tingkat()
    {
        return $this->belongsTo(Tingkat::class);
    }
}
