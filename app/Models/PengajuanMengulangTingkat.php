<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanMengulangTingkat extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_mengulang_tingkat';

    public const STATUS_PENDING = 'pending';
    public const STATUS_DISETUJUI = 'disetujui';
    public const STATUS_DITOLAK = 'ditolak';

    protected $fillable = [
        'siswa_id',
        'tingkat_id',
        'tingkat_saat_pengajuan_id',
        'tahun_periode',
        'alasan',
        'status',
        'pelatih_id',
        'catatan_pelatih',
        'tanggal_pengajuan',
        'tanggal_keputusan',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_keputusan' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function tingkat()
    {
        return $this->belongsTo(Tingkat::class);
    }

    public function tingkatSaatPengajuan()
    {
        return $this->belongsTo(Tingkat::class, 'tingkat_saat_pengajuan_id');
    }

    public function pelatih()
    {
        return $this->belongsTo(Pelatih::class);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Menunggu Persetujuan',
            self::STATUS_DISETUJUI => 'Disetujui',
            self::STATUS_DITOLAK => 'Ditolak',
            default => '-',
        };
    }
}
