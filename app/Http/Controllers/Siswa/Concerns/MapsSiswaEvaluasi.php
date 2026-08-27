<?php

namespace App\Http\Controllers\Siswa\Concerns;

use App\Models\EvaluasiTingkat;
use App\Models\User;
use App\Services\EvaluasiKenaikanTingkatService;

trait MapsSiswaEvaluasi
{
    protected function authSiswaUser(): User
    {
        return auth()->user()->load('siswaProfile.tingkat');
    }

    protected function evaluasiRowsForUser(User $user)
    {
        $siswa = $user->siswaProfile;

        if (!$siswa) {
            return collect();
        }

        return $siswa->evaluasi()
            ->with('tingkat')
            ->orderByDesc('tanggal_evaluasi')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($evaluasi) => $this->mapEvaluasiRow($evaluasi));
    }

    protected function riwayatRowsForUser(User $user)
    {
        $siswa = $user->siswaProfile;

        if (!$siswa) {
            return collect();
        }

        return $siswa->riwayatTingkat()
            ->with(['tingkatAwal', 'tingkatAkhir'])
            ->orderByDesc('tanggal_naik')
            ->orderByDesc('created_at')
            ->get();
    }

    protected function mapEvaluasiRow(EvaluasiTingkat $evaluasi): array
    {
        $tingkat = $evaluasi->tingkat;
        $statusKelulusan = $evaluasi->status_kelulusan ?? '';

        return [
            'evaluasi_id' => $evaluasi->id,
            'tingkat_id' => $evaluasi->tingkat_id,
            'keputusan' => $evaluasi->status,
            'tahun_periode' => $evaluasi->tahun_periode ?? '-',
            'tingkat_nama' => $tingkat?->nama_tingkat ?? '-',
            'jenis_penilaian' => $evaluasi->rekap_nilai_ujian_id ? 'Ujian' : 'Harian',
            'nilai_akhir' => (float) $evaluasi->rata_rata_nilai,
            'status_kelulusan' => $statusKelulusan,
            'status_kelulusan_label' => $tingkat
                ? $tingkat->labelKelulusan($statusKelulusan)
                : ucfirst(str_replace('_', ' ', $statusKelulusan)),
            'keputusan_label' => app(EvaluasiKenaikanTingkatService::class)->labelKeputusan(
                $evaluasi->status,
                $statusKelulusan,
                $tingkat
            ),
            'keputusan_manual' => (bool) $evaluasi->keputusan_manual,
            'tanggal_evaluasi' => $evaluasi->tanggal_evaluasi?->format('d M Y') ?? '-',
        ];
    }

}
