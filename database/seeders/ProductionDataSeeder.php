<?php

namespace Database\Seeders;

use App\Models\Pendaftaran;
use App\Models\Pelatih;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductionDataSeeder extends Seeder
{
    /**
     * Run the database seeds to match production environment.
     * Adds additional students, instructors, and registrations.
     */
    public function run(): void
    {
        // Add additional pelatih/instructors to reach total of 4
        // (1 already exists from UserSeeder)
        
        $pelatih2User = User::create([
            'name' => 'Pelatih Dua',
            'username' => 'pelatih2',
            'email' => 'pelatih2@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'pelatih',
            'status' => 'aktif',
        ]);
        Pelatih::create([
            'user_id' => $pelatih2User->id,
            'nama_lengkap' => $pelatih2User->name,
            'no_hp' => '082345678901',
            'alamat' => 'Jl. Pelatih No. 2',
        ]);

        $pelatih3User = User::create([
            'name' => 'Pelatih Tiga',
            'username' => 'pelatih3',
            'email' => 'pelatih3@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'pelatih',
            'status' => 'aktif',
        ]);
        Pelatih::create([
            'user_id' => $pelatih3User->id,
            'nama_lengkap' => $pelatih3User->name,
            'no_hp' => '083345678902',
            'alamat' => 'Jl. Pelatih No. 3',
        ]);

        $pelatih4User = User::create([
            'name' => 'Pelatih Empat',
            'username' => 'pelatih4',
            'email' => 'pelatih4@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'pelatih',
            'status' => 'aktif',
        ]);
        Pelatih::create([
            'user_id' => $pelatih4User->id,
            'nama_lengkap' => $pelatih4User->name,
            'no_hp' => '084345678903',
            'alamat' => 'Jl. Pelatih No. 4',
        ]);

        // Add additional siswa to reach total of 5
        // (1 already exists from UserSeeder)
        $siswaNames = ['Budi', 'Siti', 'Ahmad', 'Dewi'];
        $siswaEmails = ['siswa2@example.com', 'siswa3@example.com', 'siswa4@example.com', 'siswa5@example.com'];
        
        for ($i = 0; $i < 4; $i++) {
            $siswaUser = User::create([
                'name' => $siswaNames[$i],
                'username' => 'siswa' . ($i + 2),
                'email' => $siswaEmails[$i],
                'password' => Hash::make('12345678'),
                'role' => 'siswa',
                'status' => 'aktif',
            ]);

            Siswa::create([
                'user_id' => $siswaUser->id,
                'nis' => 'SISWA' . str_pad(($i + 2), 3, '0', STR_PAD_LEFT),
                'nama_lengkap' => $siswaUser->name,
                'jenis_kelamin' => $i % 2 == 0 ? 'L' : 'P',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2012-01-' . str_pad(($i + 5), 2, '0', STR_PAD_LEFT),
                'alamat' => 'Jl. Siswa No. ' . ($i + 2),
                'no_hp' => '08123456789' . $i,
                'tingkat_id' => (($i % 6) + 1),
                'status' => 'aktif',
                'asal_sekolah' => 'SD Negeri ' . ($i + 2),
                'kelas' => ($i % 3 + 4) . ' SD',
                'nama_orangtua' => 'Orang Tua ' . $siswaNames[$i],
                'pekerjaan_orangtua' => ['Petani', 'Guru', 'Pegawai Swasta', 'Wiraswasta'][$i],
                'kontak_orangtua' => '08124567890' . $i,
                'alamat_orangtua' => 'Jl. Orang Tua No. ' . ($i + 2),
            ]);
        }

        // Add additional pendaftaran to reach total of 7
        // (2 already exist from PendaftaranSeeder)
        $pendaftaranData = [
            [
                'nama_lengkap' => 'Karina',
                'email' => 'karina@example.com',
                'nama_panggilan' => 'Karina',
                'tingkat_id' => 1,
                'status' => 'diterima',
            ],
            [
                'nama_lengkap' => 'Andini',
                'email' => 'andini@example.com',
                'nama_panggilan' => 'Andini',
                'tingkat_id' => 1,
                'status' => 'diterima',
            ],
            [
                'nama_lengkap' => 'Yohana',
                'email' => 'yohana@example.com',
                'nama_panggilan' => 'Yohana',
                'tingkat_id' => 2,
                'status' => 'diterima',
            ],
            [
                'nama_lengkap' => 'Rina',
                'email' => 'rina@example.com',
                'nama_panggilan' => 'Rina',
                'tingkat_id' => 1,
                'status' => 'pending',
            ],
            [
                'nama_lengkap' => 'Dina',
                'email' => 'dina@example.com',
                'nama_panggilan' => 'Dina',
                'tingkat_id' => 2,
                'status' => 'pending',
            ],
        ];

        foreach ($pendaftaranData as $data) {
            Pendaftaran::create(array_merge([
                'tanggal_lahir' => '2012-05-12',
                'asal_sekolah' => 'SD Negeri 1',
                'kelas' => '5 SD',
                'kontak_aktif' => '081234567890',
                'alamat' => 'Jalan Mawar 10',
                'nama_orangtua' => 'Bapak/Ibu',
                'pekerjaan_orangtua' => 'Karyawan',
                'kontak_orangtua' => '081234567891',
                'alamat_orangtua' => 'Jalan Melati 5',
                'tanggal_daftar' => now()->toDateString(),
                'catatan_admin' => null,
            ], $data));
        }
    }
}
