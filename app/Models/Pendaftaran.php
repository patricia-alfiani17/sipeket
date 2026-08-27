<?php

namespace App\Models;

use App\Services\CloudinaryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'tanggal_lahir',
        'tempat_lahir',
        'jenis_kelamin',
        'nama_panggilan',
        'asal_sekolah',
        'kelas',
        'kontak_aktif',
        'akta_kelahiran',
        'akta_kelahiran_url',
        'alamat',
        'tingkat_id',
        'nama_orangtua',
        'pekerjaan_orangtua',
        'kontak_orangtua',
        'alamat_orangtua',
        'tanggal_daftar',
        'status',
        'catatan_admin',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Pendaftaran $pendaftaran) {
            app(CloudinaryService::class)->delete($pendaftaran->akta_kelahiran);
        });
    }

    public function getAktaKelahiranUrlAttribute(): ?string
    {
        if ($this->attributes['akta_kelahiran_url'] ?? null) {
            return $this->attributes['akta_kelahiran_url'];
        }

        return $this->akta_kelahiran
            ? app(CloudinaryService::class)->url($this->akta_kelahiran)
            : null;
    }

    public function tingkat()
    {
        return $this->belongsTo(Tingkat::class);
    }
}
