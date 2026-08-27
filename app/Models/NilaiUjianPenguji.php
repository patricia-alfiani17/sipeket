<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiUjianPenguji extends Model
{
    use HasFactory;

    protected $table = 'nilai_ujian_penguji';

    protected $fillable = [
        'user_id',
        'siswa_id',
        'pelatih_id',
        'tingkat_id',
        'tahun_periode',
        'materi_latihan',
        'nomor_penguji',
        'wiraga',
        'wirama',
        'wirasa',
        'rata_penguji',
        'tanggal_ujian',
    ];

    protected $casts = [
        'tanggal_ujian' => 'date',
        'wiraga' => 'decimal:2',
        'wirama' => 'decimal:2',
        'wirasa' => 'decimal:2',
        'rata_penguji' => 'decimal:2',
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
