<?php

namespace App\Exports;

use App\Models\EvaluasiTingkat;
use App\Services\EvaluasiKenaikanTingkatService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class EvaluasiExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    protected ?int $lastSiswaId = null;
    protected int $no = 0;

    public function collection()
    {
        return EvaluasiTingkat::with(['siswa.user', 'tingkat'])
            ->get()
            ->sort(function ($a, $b) {
                // Urutkan berdasarkan nama siswa
                $namaA = $a->siswa?->user?->name
                    ?? $a->siswa?->nama_lengkap
                    ?? '';

                $namaB = $b->siswa?->user?->name
                    ?? $b->siswa?->nama_lengkap
                    ?? '';

                $namaCompare = strcasecmp($namaA, $namaB);

                if ($namaCompare !== 0) {
                    return $namaCompare;
                }

                // Jika siswa sama, urutkan berdasarkan urutan tingkat
                return ($a->tingkat?->urutan ?? 0)
                    <=> ($b->tingkat?->urutan ?? 0);
            })
            ->values();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'Periode',
            'Tingkat',
            'Jenis Penilaian',
            'Nilai Akhir',
            'Status Kelulusan',
            'Keputusan',
            'Tanggal Evaluasi',
        ];
    }

    public function map($evaluasi): array
    {
        $siswaId = $evaluasi->siswa_id;

        $siswaBaru = $this->lastSiswaId !== $siswaId;

        if ($siswaBaru) {
            $this->no++;
            $this->lastSiswaId = $siswaId;
        }

        $tingkat = $evaluasi->tingkat;
        $statusKelulusan = $evaluasi->status_kelulusan ?? '';

        $service = app(EvaluasiKenaikanTingkatService::class);

        return [
            $siswaBaru ? $this->no : '',
            $siswaBaru
                ? ($evaluasi->siswa?->user?->name
                    ?? $evaluasi->siswa?->nama_lengkap
                    ?? '-')
                : '',
            $siswaBaru ? ($evaluasi->tahun_periode ?? '-') : '',
            $tingkat?->nama_tingkat ?? '-',
            $evaluasi->rekap_nilai_ujian_id ? 'Ujian' : 'Harian',
            (float) $evaluasi->rata_rata_nilai,
            $tingkat
                ? $tingkat->labelKelulusan($statusKelulusan)
                : '-',
            $service->labelKeputusan(
                $evaluasi->status,
                $statusKelulusan,
                $tingkat
            ),
            $evaluasi->tanggal_evaluasi?->format('d/m/Y') ?? '-',
        ];
    }

    public function title(): string
    {
        return 'Hasil Evaluasi';
    }
}