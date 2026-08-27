<?php

namespace Database\Seeders;

use App\Models\Pendaftaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PendaftaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pendaftaran::insert([
            [
                'nama_lengkap' => 'Calon Siswa A',
                'email' => 'calon1@example.com',
                'tanggal_lahir' => '2012-05-12',
                'nama_panggilan' => 'Siswa A',
                'asal_sekolah' => 'SD Negeri 1',
                'kelas' => '5 SD',
                'kontak_aktif' => '081234567890',
                'akta_kelahiran' => null,
                'alamat' => 'Jalan Mawar 10',
                'tingkat_id' => 1,
                'nama_orangtua' => 'Bapak A',
                'pekerjaan_orangtua' => 'Petani',
                'kontak_orangtua' => '081234567891',
                'alamat_orangtua' => 'Jalan Melati 5',
                'tanggal_daftar' => now()->toDateString(),
                'status' => 'pending',
                'catatan_admin' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_lengkap' => 'Calon Siswa B',
                'email' => 'calon2@example.com',
                'tanggal_lahir' => '2011-10-03',
                'nama_panggilan' => 'Siswa B',
                'asal_sekolah' => 'SD Negeri 2',
                'kelas' => '6 SD',
                'kontak_aktif' => '081234567892',
                'akta_kelahiran' => null,
                'alamat' => 'Jalan Kenanga 7',
                'tingkat_id' => 2,
                'nama_orangtua' => 'Ibu B',
                'pekerjaan_orangtua' => 'Guru',
                'kontak_orangtua' => '081234567893',
                'alamat_orangtua' => 'Jalan Bougenville 12',
                'tanggal_daftar' => now()->toDateString(),
                'status' => 'pending',
                'catatan_admin' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
