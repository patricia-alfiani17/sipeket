<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RiwayatTingkat extends Model
{
    use HasFactory;

    protected $table = 'riwayat_tingkat';

    protected $fillable = [
        'siswa_id',
        'tingkat_awal_id',
        'tingkat_akhir_id',
        'tanggal_naik',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function tingkatAwal()
    {
        return $this->belongsTo(Tingkat::class, 'tingkat_awal_id');
    }

    public function tingkatAkhir()
    {
        return $this->belongsTo(Tingkat::class, 'tingkat_akhir_id');
    }

    public function isMengulang(): bool
    {
        return (int) $this->tingkat_awal_id === (int) $this->tingkat_akhir_id;
    }
}