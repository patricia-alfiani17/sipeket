<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvaluasiTingkat extends Model
{
    use HasFactory;

    protected $table = 'evaluasi_tingkat';

    public const STATUS_NAIK = 'naik';
    public const STATUS_DIPERTIMBANGKAN = 'dipertimbangkan';
    public const STATUS_TIDAK_NAIK = 'tidak_naik';

    protected $fillable = [
        'siswa_id',
        'tingkat_id',
        'tahun_periode',
        'rata_rata_nilai',
        'status_kelulusan',
        'status',
        'keputusan_manual',
        'pelatih_id',
        'tanggal_evaluasi',
        'rekap_nilai_harian_id',
        'rekap_nilai_ujian_id',
    ];

    protected $casts = [
        'keputusan_manual' => 'boolean',
        'tanggal_evaluasi' => 'date',
        'rata_rata_nilai' => 'decimal:2',
    ];

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

    public function rekapNilaiHarian()
    {
        return $this->belongsTo(RekapNilaiHarian::class);
    }

    public function rekapNilaiUjian()
    {
        return $this->belongsTo(RekapNilaiUjian::class);
    }
}