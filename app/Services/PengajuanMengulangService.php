<?php

namespace App\Services;

use App\Models\EvaluasiTingkat;
use App\Models\PengajuanMengulangTingkat;
use App\Models\Pelatih;
use App\Models\RekapNilaiHarian;
use App\Models\RekapNilaiUjian;
use App\Models\RiwayatTingkat;
use App\Models\Siswa;
use App\Models\TahunPeriode;
use App\Models\Tingkat;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PengajuanMengulangService
{
    public function __construct(
        protected EvaluasiKenaikanTingkatService $evaluasiKenaikanService
    ) {}

    public function getTingkatEligible(Siswa $siswa): ?Tingkat
    {
        $siswa->loadMissing('tingkat');

        $evaluasiNaik = EvaluasiTingkat::where('siswa_id', $siswa->id)
            ->where('status', EvaluasiTingkat::STATUS_NAIK)
            ->with('tingkat')
            ->get()
            ->filter(fn ($evaluasi) => $evaluasi->tingkat !== null)
            ->sortByDesc(fn ($evaluasi) => $evaluasi->tingkat->urutan);

        $tingkatTerakhirLulus = $evaluasiNaik->first()?->tingkat;

        if (!$tingkatTerakhirLulus || !$siswa->tingkat) {
            return null;
        }

        if ((int) $tingkatTerakhirLulus->urutan >= (int) $siswa->tingkat->urutan) {
            return null;
        }

        return $tingkatTerakhirLulus;
    }

    public function canSubmit(Siswa $siswa): array
    {
        $siswa->loadMissing('tingkat');

        $hasPending = PengajuanMengulangTingkat::where('siswa_id', $siswa->id)
            ->where('status', PengajuanMengulangTingkat::STATUS_PENDING)
            ->exists();

        if ($hasPending) {
            return [
                'eligible' => false,
                'reason' => 'Anda masih memiliki pengajuan yang menunggu persetujuan pelatih.',
                'tingkat' => null,
            ];
        }

        if ($this->evaluasiKenaikanService->isInMandatoryRepeat($siswa)) {
            $namaTingkat = $siswa->tingkat?->nama_tingkat ?? 'saat ini';

            return [
                'eligible' => false,
                'reason' => "Anda sedang dalam proses mengulang wajib tingkat {$namaTingkat}. Selesaikan evaluasi ulang terlebih dahulu sebelum mengajukan pengulangan sukarela.",
                'tingkat' => null,
            ];
        }

        $tingkatEligible = $this->getTingkatEligible($siswa);

        if (!$tingkatEligible) {
            return [
                'eligible' => false,
                'reason' => 'Belum ada tingkat yang dapat diajukan untuk pengulangan. Anda harus pernah naik tingkat dan berada di tingkat lebih tinggi dari tingkat terakhir yang diselesaikan.',
                'tingkat' => null,
            ];
        }

        return [
            'eligible' => true,
            'reason' => '',
            'tingkat' => $tingkatEligible,
        ];
    }

    public function submit(Siswa $siswa, string $alasan): PengajuanMengulangTingkat
    {
        $check = $this->canSubmit($siswa);

        if (!$check['eligible'] || !$check['tingkat']) {
            throw new InvalidArgumentException($check['reason']);
        }

        $siswa->loadMissing('tingkat');

        return PengajuanMengulangTingkat::create([
            'siswa_id' => $siswa->id,
            'tingkat_id' => $check['tingkat']->id,
            'tingkat_saat_pengajuan_id' => $siswa->tingkat_id,
            'alasan' => $alasan,
            'status' => PengajuanMengulangTingkat::STATUS_PENDING,
            'tanggal_pengajuan' => now()->toDateString(),
        ]);
    }

    public function approve(
        PengajuanMengulangTingkat $pengajuan,
        Pelatih $pelatih,
        ?string $catatan = null
    ): PengajuanMengulangTingkat {
        if (!$pengajuan->isPending()) {
            throw new InvalidArgumentException('Pengajuan ini sudah diproses.');
        }

        $defaultPeriode = TahunPeriode::where('is_default', true)->first();
        if (!$defaultPeriode) {
            throw new InvalidArgumentException('Periode default belum dikonfigurasi.');
        }

        return DB::transaction(function () use ($pengajuan, $pelatih, $catatan, $defaultPeriode) {
            $pengajuan->load(['siswa', 'tingkat']);

            $siswa = $pengajuan->siswa;
            $tingkatAwal = (int) $siswa->tingkat_id;
            $tingkatTujuan = (int) $pengajuan->tingkat_id;
            $periode = $defaultPeriode->periode;

            $siswa->update(['tingkat_id' => $tingkatTujuan]);

            RiwayatTingkat::create([
                'siswa_id' => $siswa->id,
                'tingkat_awal_id' => $tingkatAwal,
                'tingkat_akhir_id' => $tingkatTujuan,
                'tanggal_naik' => now()->toDateString(),
            ]);

            $this->resetRekapForTingkat($siswa, $tingkatTujuan, $periode);

            $latestNaikEval = EvaluasiTingkat::where('siswa_id', $siswa->id)
                ->where('tingkat_id', $tingkatTujuan)
                ->where('status', EvaluasiTingkat::STATUS_NAIK)
                ->orderByDesc('tanggal_evaluasi')
                ->orderByDesc('id')
                ->first();

            EvaluasiTingkat::updateOrCreate(
                [
                    'siswa_id' => $siswa->id,
                    'tingkat_id' => $tingkatTujuan,
                    'tahun_periode' => $periode,
                ],
                [
                    'rata_rata_nilai' => $latestNaikEval?->rata_rata_nilai ?? 0,
                    'status_kelulusan' => $latestNaikEval?->status_kelulusan ?? Tingkat::KELULUSAN_LULUS,
                    'status' => EvaluasiTingkat::STATUS_TIDAK_NAIK,
                    'keputusan_manual' => false,
                    'pelatih_id' => $pelatih->id,
                    'tanggal_evaluasi' => $latestNaikEval?->tanggal_evaluasi ?? now()->toDateString(),
                ]
            );

            $pengajuan->update([
                'status' => PengajuanMengulangTingkat::STATUS_DISETUJUI,
                'pelatih_id' => $pelatih->id,
                'catatan_pelatih' => $catatan,
                'tahun_periode' => $periode,
                'tanggal_keputusan' => now()->toDateString(),
            ]);

            return $pengajuan->fresh(['siswa.user', 'tingkat', 'tingkatSaatPengajuan', 'pelatih']);
        });
    }

    public function reject(
        PengajuanMengulangTingkat $pengajuan,
        Pelatih $pelatih,
        ?string $catatan = null
    ): PengajuanMengulangTingkat {
        if (!$pengajuan->isPending()) {
            throw new InvalidArgumentException('Pengajuan ini sudah diproses.');
        }

        $pengajuan->update([
            'status' => PengajuanMengulangTingkat::STATUS_DITOLAK,
            'pelatih_id' => $pelatih->id,
            'catatan_pelatih' => $catatan,
            'tanggal_keputusan' => now()->toDateString(),
        ]);

        return $pengajuan->fresh(['siswa.user', 'tingkat', 'tingkatSaatPengajuan', 'pelatih']);
    }

    public function pendingForPelatih(Pelatih $pelatih): Collection
    {
        $query = PengajuanMengulangTingkat::with(['siswa.user', 'tingkat', 'tingkatSaatPengajuan'])
            ->where('status', PengajuanMengulangTingkat::STATUS_PENDING);

        $accessService = app(PelatihTingkatAccessService::class);
        if ($accessService->hasRestrictedAccess($pelatih)) {
            $query->whereIn('tingkat_id', $accessService->accessibleTingkatIds($pelatih));
        }

        return $query->orderByDesc('tanggal_pengajuan')
            ->orderByDesc('created_at')
            ->get();
    }

    public function historyForPelatih(Pelatih $pelatih): Collection
    {
        $query = PengajuanMengulangTingkat::with(['siswa.user', 'tingkat', 'tingkatSaatPengajuan', 'pelatih'])
            ->where('status', '!=', PengajuanMengulangTingkat::STATUS_PENDING);

        $accessService = app(PelatihTingkatAccessService::class);
        if ($accessService->hasRestrictedAccess($pelatih)) {
            $query->whereIn('tingkat_id', $accessService->accessibleTingkatIds($pelatih));
        }

        return $query->orderByDesc('tanggal_keputusan')
            ->orderByDesc('created_at')
            ->get();
    }

    public function countPendingForPelatih(Pelatih $pelatih): int
    {
        return $this->pendingForPelatih($pelatih)->count();
    }

    private function resetRekapForTingkat(Siswa $siswa, int $tingkatId, string $periode): void
    {
        RekapNilaiHarian::where('siswa_id', $siswa->id)
            ->where('tingkat_id', $tingkatId)
            ->where('tahun_periode', $periode)
            ->update([
                'average' => null,
                'status' => RekapNilaiHarian::STATUS_BELUM_LENGKAP,
                'filled_count' => 0,
                'evaluasi_selesai' => false,
            ]);

        RekapNilaiUjian::where('siswa_id', $siswa->id)
            ->where('tingkat_id', $tingkatId)
            ->where('tahun_periode', $periode)
            ->update([
                'average' => null,
                'status' => RekapNilaiUjian::STATUS_BELUM_LENGKAP,
                'filled_count' => 0,
                'evaluasi_selesai' => false,
            ]);
    }
}
