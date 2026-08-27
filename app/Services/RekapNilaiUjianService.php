<?php

namespace App\Services;

use App\Models\EvaluasiTingkat;
use App\Models\MateriLatihan;
use App\Models\NilaiUjianMateri;
use App\Models\NilaiUjianPenguji;
use App\Models\RekapNilaiUjian;
use App\Models\Tingkat;
use App\Models\User;
use Illuminate\Support\Collection;

class RekapNilaiUjianService
{
    public const PENGUJI_COUNT = 3;

    public const MATERI_UJIAN_LABEL = 'Ujian';

    public function getMateriLabelForTingkat(int $tingkatId): string
    {
        $tingkat = Tingkat::find($tingkatId);
        if ($tingkat && $tingkat->jenis_penilaian === 'ujian') {
            $lastMateri = MateriLatihan::where('tingkat_id', $tingkatId)
                ->orderBy('urutan')
                ->get()
                ->last();

            if ($lastMateri) {
                return $lastMateri->nama;
            }
        }

        return self::MATERI_UJIAN_LABEL;
    }

    public function getMateriMasterForTingkat(int $tingkatId): Collection
    {
        return MateriLatihan::where('tingkat_id', $tingkatId)
            ->orderBy('urutan')
            ->get();
    }

    public function isMateriUjianTerakhir(int $tingkatId, string $materiName): bool
    {
        $materis = MateriLatihan::where('tingkat_id', $tingkatId)->orderBy('urutan')->get();
        if ($materis->isEmpty()) {
            return true;
        }
        return $materis->last()->nama === $materiName;
    }

    public function hitungRataPenguji(float $wiraga, float $wirama, float $wirasa): float
    {
        return round(($wiraga + $wirama + $wirasa) / 3, 2);
    }

    public function hitungNilaiFix(array $rataPenguji): ?float
    {
        if (count($rataPenguji) < self::PENGUJI_COUNT) {
            return null;
        }

        return round(array_sum($rataPenguji) / self::PENGUJI_COUNT, 2);
    }

    public function syncMateriFromPenguji(
        User $siswa,
        int $tingkatId,
        string $tahunPeriode,
        string $materiLatihan,
        int $pelatihId
    ): ?NilaiUjianMateri {
        $pengujiRows = NilaiUjianPenguji::where('siswa_id', $siswa->siswaProfile->id)
            ->where('tingkat_id', $tingkatId)
            ->where('tahun_periode', $tahunPeriode)
            ->where('materi_latihan', $materiLatihan)
            ->orderBy('nomor_penguji')
            ->get();

        $pengujiTerisi = $pengujiRows->count();
        $rataList = $pengujiRows->pluck('rata_penguji')->map(fn ($v) => (float) $v)->all();
        
        if ($this->isMateriUjianTerakhir($tingkatId, $materiLatihan)) {
            $nilaiFix = $this->hitungNilaiFix($rataList);
        } else {
            $nilaiFix = count($rataList) > 0 ? $rataList[0] : null;
        }

        return NilaiUjianMateri::updateOrCreate(
            [
                'siswa_id' => $siswa->siswaProfile->id,
                'tingkat_id' => $tingkatId,
                'tahun_periode' => $tahunPeriode,
                'materi_latihan' => $materiLatihan,
            ],
            [
                'user_id' => $siswa->id,
                'pelatih_id' => $pelatihId,
                'nilai_fix' => $nilaiFix,
                'penguji_terisi' => $pengujiTerisi,
                'tanggal_ujian' => $pengujiRows->first()?->tanggal_ujian ?? now()->toDateString(),
            ]
        );
    }

    public function buildNilaiPerMateri(User $siswa, Collection $materiMaster, Collection $nilaiMateri): array
    {
        $nilaiPerMateri = [];

        foreach ($materiMaster as $materi) {
            $record = $nilaiMateri->first(function ($row) use ($siswa, $materi) {
                return $row->user_id === $siswa->id && $row->materi_latihan === $materi->nama;
            });

            $nilaiPerMateri[$materi->nama] = $record && $record->nilai_fix !== null
                ? round((float) $record->nilai_fix, 1)
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
            ? RekapNilaiUjian::STATUS_SIAP_EVALUASI
            : RekapNilaiUjian::STATUS_BELUM_LENGKAP;

        return [
            'average' => $average,
            'status' => $status,
            'materi_count' => $materiCount,
            'filled_count' => $filledCount,
        ];
    }

    public function buildPreviewRow(User $siswa, Collection $materiMaster, Collection $nilaiMateri): array
    {
        $nilaiPerMateri = $this->buildNilaiPerMateri($siswa, $materiMaster, $nilaiMateri);
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

    public function syncRekapForSiswa(
        User $siswa,
        int $tingkatId,
        string $tahunPeriode,
        int $pelatihId
    ): RekapNilaiUjian {
        $materiMaster = $this->getMateriMasterForTingkat($tingkatId);
        $materiNames = $materiMaster->pluck('nama');

        $nilaiMateri = NilaiUjianMateri::where('user_id', $siswa->id)
            ->where('tingkat_id', $tingkatId)
            ->where('tahun_periode', $tahunPeriode)
            ->when($materiNames->isNotEmpty(), fn ($q) => $q->whereIn('materi_latihan', $materiNames))
            ->get();

        $nilaiPerMateri = $this->buildNilaiPerMateri($siswa, $materiMaster, $nilaiMateri);
        $rekap = $this->calculateRekap($nilaiPerMateri, $materiMaster->count());

        $evaluasiService = app(EvaluasiKenaikanTingkatService::class);
        $evaluasiSelesai = $evaluasiService->computeEvaluasiSelesai(
            $siswa->siswaProfile->id,
            $tingkatId,
            $tahunPeriode,
            $rekap['status'],
            RekapNilaiUjian::STATUS_SIAP_EVALUASI
        );

        return RekapNilaiUjian::updateOrCreate(
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

    public function getPengujiScoresForForm(
        int $siswaId,
        int $tingkatId,
        string $tahunPeriode,
        ?string $materiLatihan = null
    ): array {
        $materiLatihan = $materiLatihan ?? $this->getMateriLabelForTingkat($tingkatId);

        $rows = NilaiUjianPenguji::where('siswa_id', $siswaId)
            ->where('tingkat_id', $tingkatId)
            ->where('tahun_periode', $tahunPeriode)
            ->where('materi_latihan', $materiLatihan)
            ->get()
            ->keyBy('nomor_penguji');

        $scores = [];
        for ($i = 1; $i <= self::PENGUJI_COUNT; $i++) {
            $row = $rows->get($i);
            $scores[$i] = [
                'wiraga' => $row?->wiraga ?? 0,
                'wirama' => $row?->wirama ?? 0,
                'wirasa' => $row?->wirasa ?? 0,
                'rata' => $row ? (float) $row->rata_penguji : 0,
            ];
        }

        return $scores;
    }

    public function hasCompleteUjian(int $siswaId, int $tingkatId, string $tahunPeriode): bool
    {
        $materiLabel = $this->getMateriLabelForTingkat($tingkatId);
        $count = NilaiUjianPenguji::where('siswa_id', $siswaId)
            ->where('tingkat_id', $tingkatId)
            ->where('tahun_periode', $tahunPeriode)
            ->where('materi_latihan', $materiLabel)
            ->count();

        return $count >= self::PENGUJI_COUNT;
    }

    public function canInputUjian(int $siswaId, int $tingkatId, string $tahunPeriode): bool
    {
        if (!$this->hasCompleteUjian($siswaId, $tingkatId, $tahunPeriode)) {
            return true;
        }

        $evaluasi = EvaluasiTingkat::where('siswa_id', $siswaId)
            ->where('tingkat_id', $tingkatId)
            ->where('tahun_periode', $tahunPeriode)
            ->first();

        return $evaluasi && $evaluasi->status === EvaluasiTingkat::STATUS_TIDAK_NAIK;
    }
}
