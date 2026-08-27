<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NilaiUjian extends Model
{
    use HasFactory;

    protected $table = 'nilai_ujian';

    protected $fillable = [
        'siswa_id',
        'pelatih_id',
        'nilai',
        'tanggal_ujian',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function pelatih()
    {
        return $this->belongsTo(Pelatih::class);
    }
}