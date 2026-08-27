<?php

namespace App\Exports;

use App\Models\RiwayatTingkat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class RiwayatTingkatExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    public function collection()
    {
        return RiwayatTingkat::with(['siswa.user', 'tingkatAwal', 'tingkatAkhir'])
            ->orderBy('siswa_id')
            ->orderBy('tanggal_naik')
            ->orderBy('created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'Tingkat Awal',
            'Tingkat Akhir',
            'Tanggal',
            'Keterangan',
        ];
    }

    public function map($riwayat): array
    {
        static $no = 0;
        static $siswaTerakhir = null;

        $no++;

        $namaSiswa = $riwayat->siswa?->user?->name
            ?? $riwayat->siswa?->nama_lengkap
            ?? '-';

        $namaUntukExcel = $riwayat->siswa_id === $siswaTerakhir
            ? ''
            : $namaSiswa;

        $siswaTerakhir = $riwayat->siswa_id;

        return [
            $no,
            $namaUntukExcel,
            $riwayat->tingkatAwal?->nama_tingkat ?? '-',
            $riwayat->tingkatAkhir?->nama_tingkat ?? '-',
            $riwayat->tanggal_naik
                ? \Carbon\Carbon::parse($riwayat->tanggal_naik)->format('d/m/Y')
                : '-',
            $riwayat->isMengulang() ? 'Mengulang' : 'Naik Tingkat',
        ];
    }

    public function title(): string
    {
        return 'Riwayat Kenaikan Tingkat';
    }
}
