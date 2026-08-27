<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * @property string $role
     * @property string $status
     */

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    // =========================
    // RELASI
    // =========================

    public function siswaProfile()
    {
        return $this->hasOne(Siswa::class);
    }

    public function pelatihProfile()
    {
        return $this->hasOne(Pelatih::class);
    }

    public function getProfileAttribute()
    {
        if ($this->role === 'siswa') {
            return $this->siswaProfile;
        }

        if ($this->role === 'pelatih') {
            return $this->pelatihProfile;
        }

        return null;
    }

    public function getTanggalLahirAttribute($value)
    {
        return $value ?: $this->profile?->tanggal_lahir;
    }

    public function getJenisKelaminAttribute($value)
    {
        return $value ?: $this->profile?->jenis_kelamin;
    }

    public function getTempatLahirAttribute($value)
    {
        return $value ?: $this->profile?->tempat_lahir;
    }

    public function getNamaPanggilanAttribute($value)
    {
        return $value ?: $this->profile?->nama_panggilan;
    }

    public function getAlamatAttribute($value)
    {
        return $value ?: $this->profile?->alamat;
    }

    public function getNoHpAttribute($value)
    {
        return $value ?: $this->profile?->no_hp;
    }

    public function getAsalSekolahAttribute($value)
    {
        return $value ?: $this->profile?->asal_sekolah;
    }

    public function getKelasAttribute($value)
    {
        return $value ?: $this->profile?->kelas;
    }

    public function getNamaOrangtuaAttribute($value)
    {
        return $value ?: $this->profile?->nama_orangtua;
    }

    public function getPekerjaanOrangtuaAttribute($value)
    {
        return $value ?: $this->profile?->pekerjaan_orangtua;
    }

    public function getKontakOrangtuaAttribute($value)
    {
        return $value ?: $this->profile?->kontak_orangtua;
    }

    public function getAlamatOrangtuaAttribute($value)
    {
        return $value ?: $this->profile?->alamat_orangtua;
    }

    public function getTingkatIdAttribute($value)
    {
        return $value ?: $this->profile?->tingkat_id;
    }

    public function pendaftaran()
    {
        return $this->hasOne(Pendaftaran::class, 'email', 'email');
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

    public function riwayatTingkat()
    {
        return $this->hasMany(RiwayatTingkat::class);
    }
}
