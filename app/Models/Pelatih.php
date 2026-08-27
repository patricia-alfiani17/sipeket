<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pelatih extends Model
{
    use HasFactory;

    protected $table = 'pelatih';

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'no_hp',
        'alamat',
    ];

    // =========================
    // RELASI
    // =========================

    public function user()
    {
        return $this->belongsTo(User::class);
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

    public function tingkats()
    {
        return $this->belongsToMany(Tingkat::class, 'pelatih_tingkat', 'pelatih_id', 'tingkat_id')
            ->withTimestamps();
    }
}