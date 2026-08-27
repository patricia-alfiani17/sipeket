<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class SiswaExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    public function collection()
    {
        return User::where('role', 'siswa')
            ->with(['siswaProfile.tingkat', 'pendaftaran'])
            ->orderBy('name')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Email',
            'Tingkat Saat Ini',
            'Tanggal Lahir',
            'Tempat Lahir',
            'Jenis Kelamin',
            'Asal Sekolah',
            'Kelas',
            'Status',
            'Kontak',
            'Nama Orang Tua',
        ];
    }

    public function map($siswa): array
    {
        static $no = 0;
        $no++;

        $profile = $siswa->siswaProfile;
        $jenisKelamin = $profile?->jenis_kelamin === 'L' ? 'Laki-laki' : ($profile?->jenis_kelamin === 'P' ? 'Perempuan' : '-');

        return [
            $no,
            $siswa->name,
            $siswa->email,
            $profile?->tingkat?->nama_tingkat ?? '-',
            $profile?->tanggal_lahir?->format('d/m/Y') ?? '-',
            $profile?->tempat_lahir ?? '-',
            $jenisKelamin,
            $profile?->asal_sekolah ?? '-',
            $profile?->kelas ?? '-',
            ucfirst($siswa->status ?? '-'),
            $profile?->no_hp ?? '-',
            $profile?->nama_orangtua ?? '-',
        ];
    }

    public function title(): string
    {
        return 'Data Siswa';
    }
}
