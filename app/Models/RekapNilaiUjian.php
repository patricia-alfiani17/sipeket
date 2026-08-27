<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapNilaiUjian extends Model
{
    use HasFactory;

    public const STATUS_BELUM_LENGKAP = 'Belum Lengkap';
    public const STATUS_SIAP_EVALUASI = 'Siap Evaluasi';

    protected $table = 'rekap_nilai_ujian';

    protected $fillable = [
        'user_id',
        'siswa_id',
        'pelatih_id',
        'tingkat_id',
        'tahun_periode',
        'average',
        'status',
        'materi_count',
        'filled_count',
        'evaluasi_selesai',
    ];

    protected $casts = [
        'average' => 'decimal:2',
        'evaluasi_selesai' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function tingkat()
    {
        return $this->belongsTo(Tingkat::class);
    }

    public function pelatih()
    {
        return $this->belongsTo(Pelatih::class);
    }

    public function evaluasiTingkat()
    {
        return $this->hasOne(EvaluasiTingkat::class);
    }
}
