<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pelatih;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        // Pelatih user
        $pelatihUser = User::create([
            'name' => 'Pelatih Satu',
            'username' => 'pelatih',
            'email' => 'pelatih@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'pelatih',
            'status' => 'aktif',
        ]);

        Pelatih::create([
            'user_id' => $pelatihUser->id,
            'nama_lengkap' => $pelatihUser->name,
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Pelatih No. 1',
        ]);

        // Siswa user
        $siswaUser = User::create([
            'name' => 'Siswa Satu',
            'username' => 'siswa',
            'email' => 'siswa@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'siswa',
            'status' => 'aktif',
        ]);

        Siswa::create([
            'user_id' => $siswaUser->id,
            'nis' => 'SISWA001',
            'nama_lengkap' => $siswaUser->name,
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2012-01-01',
            'alamat' => 'Jl. Siswa No. 1',
            'no_hp' => '081234567891',
            'tingkat_id' => 1,
            'status' => 'aktif',
            'asal_sekolah' => 'SD Negeri 1',
            'kelas' => '5 SD',
            'nama_orangtua' => 'Bapak Siswa',
            'pekerjaan_orangtua' => 'Pegawai Negeri',
            'kontak_orangtua' => '081234567892',
            'alamat_orangtua' => 'Jl. Orang Tua No. 1',
        ]);
    }
}
