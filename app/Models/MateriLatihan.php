<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriLatihan extends Model
{
    use HasFactory;

    protected $table = 'materi_latihan';

    protected $fillable = [
        'nama',
        'deskripsi',
        'tingkat_id',
        'urutan',
    ];

    public function tingkat()
    {
        return $this->belongsTo(Tingkat::class);
    }
}
