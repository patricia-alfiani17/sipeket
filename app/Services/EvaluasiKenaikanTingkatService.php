<?php

namespace App\Services;

use App\Models\EvaluasiTingkat;
use App\Models\RekapNilaiHarian;
use App\Models\RekapNilaiUjian;
use App\Models\RiwayatTingkat;
use App\Models\Siswa;
use App\Models\Tingkat;

class EvaluasiKenaikanTingkatService
{
    /**
     * Setelah mengulang tingkat, rekap yang kembali "Siap Evaluasi" harus bisa dievaluasi ulang.
     */
    public function computeEvaluasiSelesai(
        int $siswaId,
        int $tingkatId,
        string $tahunPeriode,
        string $rekapStatus,
        string $statusSiapEvaluasi
    ): bool {
        if ($rekapStatus !== $statusSiapEvaluasi) {
            return false;
        }

        $evaluasi = EvaluasiTingkat::where('siswa_id', $siswaId)
            ->where('tingkat_id', $tingkatId)
            ->where('tahun_periode', $tahunPeriode)
            ->first();

        if (!$evaluasi) {
            return false;
        }

        if ($evaluasi->status === EvaluasiTingkat::STATUS_TIDAK_NAIK) {
            return false;
        }

        return true;
    }

    public function resolveKeputusan(string $statusKelulusan, ?string $keputusanInput): ?string
    {
        return match ($statusKelulusan) {
            Tingkat::KELULUSAN_LULUS => EvaluasiTingkat::STATUS_NAIK,
            Tingkat::KELULUSAN_TIDAK_LULUS => EvaluasiTingkat::STATUS_TIDAK_NAIK,
            Tingkat::KELULUSAN_TOLERANSI => in_array($keputusanInput, [
                EvaluasiTingkat::STATUS_NAIK,
                EvaluasiTingkat::STATUS_TIDAK_NAIK,
            ], true) ? $keputusanInput : null,
            default => null,
        };
    }

    public function isKeputusanManual(string $statusKelulusan): bool
    {
        return $statusKelulusan === Tingkat::KELULUSAN_TOLERANSI;
    }

    /**
     * Terapkan dampak keputusan ke profil siswa dan riwayat tingkat.
     */
    public function applyKeputusan(
        RekapNilaiHarian|RekapNilaiUjian $rekap,
        Tingkat $tingkat,
        string $keputusan
    ): void {
        $siswa = $rekap->siswa;
        if (!$siswa) {
            return;
        }

        $tingkatDievaluasi = (int) $rekap->tingkat_id;

        if ($keputusan === EvaluasiTingkat::STATUS_TIDAK_NAIK) {
            $this->tetapkanMengulangTingkat($siswa, $tingkatDievaluasi);

            return;
        }

        if ($keputusan !== EvaluasiTingkat::STATUS_NAIK) {
            return;
        }

        $tingkatBerikutnya = $tingkat->tingkatBerikutnya();

        if (!$tingkatBerikutnya) {
            $this->tetapkanDiTingkatTertinggi($siswa, $tingkatDievaluasi);

            return;
        }

        $tingkatAwal = (int) $siswa->tingkat_id;
        $siswa->update(['tingkat_id' => $tingkatBerikutnya->id]);

        RiwayatTingkat::create([
            'siswa_id' => $siswa->id,
            'tingkat_awal_id' => $tingkatAwal,
            'tingkat_akhir_id' => $tingkatBerikutnya->id,
            'tanggal_naik' => now()->toDateString(),
        ]);
    }

    /**
     * Siswa tidak lulus / mengulang: tetap di tingkat yang dievaluasi.
     */
    private function tetapkanMengulangTingkat($siswa, int $tingkatDievaluasi): void
    {
        if ((int) $siswa->tingkat_id !== $tingkatDievaluasi) {
            $siswa->update(['tingkat_id' => $tingkatDievaluasi]);
        }

        RiwayatTingkat::create([
            'siswa_id' => $siswa->id,
            'tingkat_awal_id' => $tingkatDievaluasi,
            'tingkat_akhir_id' => $tingkatDievaluasi,
            'tanggal_naik' => now()->toDateString(),
        ]);
    }

    /**
     * Lulus di tingkat paling akhir: tidak ada tingkat berikutnya, posisi tetap di tingkat tersebut.
     */
    private function tetapkanDiTingkatTertinggi($siswa, int $tingkatDievaluasi): void
    {
        if ((int) $siswa->tingkat_id !== $tingkatDievaluasi) {
            $siswa->update(['tingkat_id' => $tingkatDievaluasi]);
        }
    }

    /**
     * Siswa sedang mengulang wajib setelah keputusan tidak naik dari pelatih.
     * Berbeda dengan pengulangan sukarela (riwayat turun tingkat, awal != akhir).
     */
    public function isInMandatoryRepeat(Siswa $siswa): bool
    {
        $siswa->loadMissing('tingkat');

        if (!$siswa->tingkat_id) {
            return false;
        }

        $evaluasiTerakhir = EvaluasiTingkat::where('siswa_id', $siswa->id)
            ->where('tingkat_id', $siswa->tingkat_id)
            ->orderByDesc('tanggal_evaluasi')
            ->orderByDesc('id')
            ->first();

        if (!$evaluasiTerakhir || $evaluasiTerakhir->status !== EvaluasiTingkat::STATUS_TIDAK_NAIK) {
            return false;
        }

        $riwayatTerakhir = RiwayatTingkat::where('siswa_id', $siswa->id)
            ->orderByDesc('tanggal_naik')
            ->orderByDesc('id')
            ->first();

        if (!$riwayatTerakhir || !$riwayatTerakhir->isMengulang()) {
            return false;
        }

        return (int) $riwayatTerakhir->tingkat_akhir_id === (int) $siswa->tingkat_id;
    }

    public function labelKeputusan(?string $keputusan, string $statusKelulusan, ?Tingkat $tingkat = null): string
    {
        if ($statusKelulusan === Tingkat::KELULUSAN_TOLERANSI && !$keputusan) {
            return 'Belum ditetapkan';
        }

        if ($keputusan === EvaluasiTingkat::STATUS_TIDAK_NAIK) {
            return 'Mengulang Tingkat';
        }

        if ($keputusan === EvaluasiTingkat::STATUS_NAIK) {
            if ($tingkat && $tingkat->isTingkatTertinggi()) {
                return 'Lulus (Tingkat Tertinggi)';
            }

            return 'Naik Tingkat';
        }

        return match ($keputusan) {
            EvaluasiTingkat::STATUS_DIPERTIMBANGKAN => 'Dipertimbangkan',
            default => '-',
        };
    }
}
