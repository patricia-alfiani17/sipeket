<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tingkat extends Model
{
    use HasFactory;

    protected $table = 'tingkat';

    protected $fillable = [
        'nama_tingkat',
        'jenis_penilaian',
        'kkm',
        'ambang_tidak_lulus',
        'ambang_pertimbangan_min',
        'ambang_pertimbangan_max',
        'urutan',
    ];

    protected $casts = [
        'kkm' => 'integer',
        'ambang_tidak_lulus' => 'integer',
        'ambang_pertimbangan_min' => 'integer',
        'ambang_pertimbangan_max' => 'integer',
        'urutan' => 'integer',
    ];

    // =========================
    // RELASI
    // =========================

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    public function evaluasi()
    {
        return $this->hasMany(EvaluasiTingkat::class);
    }

    public function materiLatihans()
    {
        return $this->hasMany(MateriLatihan::class);
    }

    public function pelatihs()
    {
        return $this->belongsToMany(Pelatih::class, 'pelatih_tingkat', 'tingkat_id', 'pelatih_id')
            ->withTimestamps();
    }

    public static function allowedForPendaftaran()
    {
        return static::whereIn('nama_tingkat', [
            'Tingkat Pradasar',
            'Tingkat Dasar 1.1',
            'Tingkat Lanjut',
        ])->orderBy('urutan')->get();
    }

    public static function allowedForPendaftaranIds(): array
    {
        return static::allowedForPendaftaran()->pluck('id')->all();
    }

    public const KELULUSAN_LULUS = 'lulus';
    public const KELULUSAN_TOLERANSI = 'toleransi';
    public const KELULUSAN_TIDAK_LULUS = 'tidak_lulus';

    public function klasifikasiKelulusan(float $nilai): string
    {
        if ($nilai <= $this->ambang_tidak_lulus) {
            return self::KELULUSAN_TIDAK_LULUS;
        }

        if ($nilai >= $this->ambang_pertimbangan_min && $nilai <= $this->ambang_pertimbangan_max) {
            return self::KELULUSAN_TOLERANSI;
        }

        if ($nilai >= $this->kkm) {
            return self::KELULUSAN_LULUS;
        }

        return self::KELULUSAN_TOLERANSI;
    }

    public function labelKelulusan(string $klasifikasi): string
    {
        return match ($klasifikasi) {
            self::KELULUSAN_LULUS => 'Lulus',
            self::KELULUSAN_TOLERANSI => 'Toleransi',
            self::KELULUSAN_TIDAK_LULUS => 'Tidak Lulus',
            default => '-',
        };
    }

    public function keputusanDefault(string $klasifikasi): ?string
    {
        return match ($klasifikasi) {
            self::KELULUSAN_LULUS => 'naik',
            self::KELULUSAN_TIDAK_LULUS => 'tidak_naik',
            default => null,
        };
    }

    public function tingkatBerikutnya(): ?self
    {
        return self::where('urutan', '>', $this->urutan)->orderBy('urutan')->first();
    }

    public function isTingkatTertinggi(): bool
    {
        $maxUrutan = (int) self::max('urutan');

        return (int) $this->urutan >= $maxUrutan;
    }
}