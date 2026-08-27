<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tingkat;

class TingkatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ambang = [
            'ambang_tidak_lulus' => 69,
            'ambang_pertimbangan_min' => 70,
            'ambang_pertimbangan_max' => 74,
        ];

        Tingkat::insert([
            array_merge(['nama_tingkat' => 'Tingkat Pradasar', 'jenis_penilaian' => 'harian', 'kkm' => 75, 'urutan' => 1], $ambang),
            array_merge(['nama_tingkat' => 'Tingkat Dasar 1.1', 'jenis_penilaian' => 'harian', 'kkm' => 75, 'urutan' => 2], $ambang),
            array_merge(['nama_tingkat' => 'Tingkat Dasar 1.2', 'jenis_penilaian' => 'ujian', 'kkm' => 75, 'urutan' => 3], $ambang),
            array_merge(['nama_tingkat' => 'Tingkat Dasar 2.1', 'jenis_penilaian' => 'harian', 'kkm' => 75, 'urutan' => 4], $ambang),
            array_merge(['nama_tingkat' => 'Tingkat Dasar 2.2', 'jenis_penilaian' => 'ujian', 'kkm' => 75, 'urutan' => 5], $ambang),
            array_merge(['nama_tingkat' => 'Tingkat Terampil', 'jenis_penilaian' => 'ujian', 'kkm' => 75, 'urutan' => 6], $ambang),
            array_merge(['nama_tingkat' => 'Tingkat Lanjut', 'jenis_penilaian' => 'ujian', 'kkm' => 75, 'urutan' => 7], $ambang),
        ]);
    }
}
