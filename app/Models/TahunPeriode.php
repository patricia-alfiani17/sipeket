<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunPeriode extends Model
{
    use HasFactory;

    protected $table = 'tahun_periode';

    protected $fillable = [
        'periode',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];
}
