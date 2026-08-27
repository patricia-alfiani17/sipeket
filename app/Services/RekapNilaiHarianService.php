<?php

namespace App\Services;

use App\Models\MateriLatihan;
use App\Models\NilaiHarian;
use App\Models\RekapNilaiHarian;
use App\Models\User;
use Illuminate\Support\Collection;

class RekapNilaiHarianService
{
    public function getMateriMasterForTingkat(int $tingkatId): Collection
    {
        return MateriLatihan::where('tingkat_id', $tingkatId)
            ->orderBy('urutan')
            ->get();
    }

    public function buildNilaiPerMateri(User $siswa, Collection $materiMaster, Collection $nilaiHarians): array
    {
        $nilaiPerMateri = [];

        foreach ($materiMaster as $materi) {
            $nilai = $nilaiHarians->first(function ($record) use ($siswa, $materi) {
                return $record->user_id === $siswa->id && $record->materi_latihan === $materi->nama;
            });

            $nilaiPerMateri[$materi->nama] = $nilai
                ? round((($nilai->wiraga ?? 0) + ($nilai->wirasa ?? 0) + ($nilai->wirama ?? 0)) / 3, 1)
                : null;
        }

        return $nilaiPerMateri;
    }

    public function calculateRekap(array $nilaiPerMateri, int $materiCount): array
    {
        $filledValues = array_filter($nilaiPerMateri, fn ($value) => !is_null($value));
        $filledCount = count($filledValues);

        $average = $filledCount > 0
            ? round(array_sum($filledValues) / $filledCount, 1)
            : null;

        $status = ($materiCount > 0 && $filledCount === $materiCount)
            ? RekapNilaiHarian::STATUS_SIAP_EVALUASI
            : RekapNilaiHarian::STATUS_BELUM_LENGKAP;

        return [
            'average' => $average,
            'status' => $status,
            'materi_count' => $materiCount,
            'filled_count' => $filledCount,
        ];
    }

    public function buildPreviewRow(User $siswa, Collection $materiMaster, Collection $nilaiHarians): array
    {
        $nilaiPerMateri = $this->buildNilaiPerMateri($siswa, $materiMaster, $nilaiHarians);
        $rekap = $this->calculateRekap($nilaiPerMateri, $materiMaster->count());

        return [
            'siswa' => $siswa,
            'nilaiPerMateri' => $nilaiPerMateri,
            'average' => $rekap['average'],
            'status' => $rekap['status'],
            'materi_count' => $rekap['materi_count'],
            'filled_count' => $rekap['filled_count'],
        ];
    }

    public function syncRekapForUser(
        User $siswa,
        int $tingkatId,
        string $tahunPeriode,
        int $pelatihId,
        Collection $nilaiHarians
    ): RekapNilaiHarian {
        $materiMaster = $this->getMateriMasterForTingkat($tingkatId);
        $nilaiPerMateri = $this->buildNilaiPerMateri($siswa, $materiMaster, $nilaiHarians);
        $rekap = $this->calculateRekap($nilaiPerMateri, $materiMaster->count());

        $evaluasiService = app(EvaluasiKenaikanTingkatService::class);
        $evaluasiSelesai = $evaluasiService->computeEvaluasiSelesai(
            $siswa->siswaProfile->id,
            $tingkatId,
            $tahunPeriode,
            $rekap['status'],
            RekapNilaiHarian::STATUS_SIAP_EVALUASI
        );

        return RekapNilaiHarian::updateOrCreate(
            [
                'user_id' => $siswa->id,
                'tingkat_id' => $tingkatId,
                'tahun_periode' => $tahunPeriode,
            ],
            [
                'siswa_id' => $siswa->siswaProfile->id,
                'pelatih_id' => $pelatihId,
                'average' => $rekap['average'],
                'status' => $rekap['status'],
                'materi_count' => $rekap['materi_count'],
                'filled_count' => $rekap['filled_count'],
                'evaluasi_selesai' => $evaluasiSelesai,
            ]
        );
    }

    public function syncRekapForTingkatUsers(
        iterable $userIds,
        int $tingkatId,
        string $tahunPeriode,
        int $pelatihId
    ): void {
        $nilaiHarians = NilaiHarian::where('tingkat_id', $tingkatId)
            ->where('tahun_periode', $tahunPeriode)
            ->whereIn('user_id', $userIds)
            ->get();

        $processed = [];

        foreach ($nilaiHarians->groupBy('user_id') as $user_id => $records) {
            $siswa = User::with('siswaProfile')->find($user_id);
            if (!$siswa || !$siswa->siswaProfile) {
                continue;
            }

            $this->syncRekapForUser($siswa, $tingkatId, $tahunPeriode, $pelatihId, $records);
            $processed[] = $user_id;
        }

        foreach ($userIds as $userId) {
            if (in_array($userId, $processed, true)) {
                continue;
            }

            $siswa = User::with('siswaProfile')->find($userId);
            if (!$siswa || !$siswa->siswaProfile) {
                continue;
            }

            $this->syncRekapForUser($siswa, $tingkatId, $tahunPeriode, $pelatihId, collect());
        }
    }
}
