<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Siswa;
use App\Models\Tingkat;
use App\Models\User;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = User::where('role', 'siswa')
            ->with(['pendaftaran.tingkat', 'siswaProfile.tingkat'])
            ->get();
        return view('admin.siswa.index', compact('siswas'));
    }

    public function create()
    {
        $tingkats = Tingkat::all();
        return view('admin.siswa.create', compact('tingkats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'nama_panggilan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:15',
            'asal_sekolah' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
            'nama_orangtua' => 'required|string|max:255',
            'pekerjaan_orangtua' => 'required|string|max:255',
            'kontak_orangtua' => 'required|string|max:15',
            'alamat_orangtua' => 'required|string',
            'tingkat_id' => 'required|exists:tingkat,id',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'username' => strtolower(str_replace(' ', '', $data['name'])) . rand(100, 999),
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => 'siswa',
            'status' => 'aktif',
        ]);

        $user->pendaftaran()->create([
            'nama_lengkap' => $data['name'],
            'email' => $data['email'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'tempat_lahir' => $data['tempat_lahir'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'nama_panggilan' => $data['nama_panggilan'],
            'asal_sekolah' => $data['asal_sekolah'],
            'kelas' => $data['kelas'],
            'kontak_aktif' => '+62' . $data['no_hp'],
            'akta_kelahiran' => null,
            'alamat' => $data['alamat'],
            'tingkat_id' => $data['tingkat_id'],
            'nama_orangtua' => $data['nama_orangtua'],
            'pekerjaan_orangtua' => $data['pekerjaan_orangtua'],
            'kontak_orangtua' => '+62' . $data['kontak_orangtua'],
            'alamat_orangtua' => $data['alamat_orangtua'],
            'tanggal_daftar' => now(),
            'status' => 'diterima',
        ]);

        $user->siswaProfile()->create([
            'nama_lengkap' => $data['name'],
            'nama_panggilan' => $data['nama_panggilan'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'tempat_lahir' => $data['tempat_lahir'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'alamat' => $data['alamat'],
            'no_hp' => '+62' . $data['no_hp'],
            'tingkat_id' => $data['tingkat_id'],
            'status' => 'aktif',
            'asal_sekolah' => $data['asal_sekolah'],
            'kelas' => $data['kelas'],
            'nama_orangtua' => $data['nama_orangtua'],
            'pekerjaan_orangtua' => $data['pekerjaan_orangtua'],
            'kontak_orangtua' => '+62' . $data['kontak_orangtua'],
            'alamat_orangtua' => $data['alamat_orangtua'],
        ]);

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function show(User $siswa)
    {
        // Ensure this is a siswa user
        if ($siswa->role !== 'siswa') {
            abort(404);
        }

        $siswa->load(['pendaftaran.tingkat', 'siswaProfile.tingkat']);
        return view('admin.siswa.show', compact('siswa'));
    }

    public function edit(User $siswa)
    {
        // Ensure this is a siswa user
        if ($siswa->role !== 'siswa') {
            abort(404);
        }

        $tingkats = Tingkat::all();
        $siswa->load(['pendaftaran', 'siswaProfile']);
        return view('admin.siswa.edit', compact('siswa', 'tingkats'));
    }

    public function update(Request $request, User $siswa)
    {
        // Ensure this is a siswa user
        if ($siswa->role !== 'siswa') {
            abort(404);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $siswa->id,
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'nama_panggilan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:15',
            'asal_sekolah' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
            'nama_orangtua' => 'required|string|max:255',
            'pekerjaan_orangtua' => 'required|string|max:255',
            'kontak_orangtua' => 'required|string|max:15',
            'alamat_orangtua' => 'required|string',
            'tingkat_id' => 'required|exists:tingkat,id',
        ]);

        $oldEmail = $siswa->email;

        $siswa->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        $pendaftaran = Pendaftaran::where('email', $oldEmail)->first();
        if ($pendaftaran) {
            $pendaftaran->update([
                'nama_lengkap' => $data['name'],
                'email' => $data['email'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'tempat_lahir' => $data['tempat_lahir'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'nama_panggilan' => $data['nama_panggilan'],
                'asal_sekolah' => $data['asal_sekolah'],
                'kelas' => $data['kelas'],
                'kontak_aktif' => '+62' . $data['no_hp'],
                'alamat' => $data['alamat'],
                'tingkat_id' => $data['tingkat_id'],
                'nama_orangtua' => $data['nama_orangtua'],
                'pekerjaan_orangtua' => $data['pekerjaan_orangtua'],
                'kontak_orangtua' => '+62' . $data['kontak_orangtua'],
                'alamat_orangtua' => $data['alamat_orangtua'],
            ]);
        } else {
            $siswa->pendaftaran()->create([
                'nama_lengkap' => $data['name'],
                'email' => $data['email'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'tempat_lahir' => $data['tempat_lahir'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'nama_panggilan' => $data['nama_panggilan'],
                'asal_sekolah' => $data['asal_sekolah'],
                'kelas' => $data['kelas'],
                'kontak_aktif' => '+62' . $data['no_hp'],
                'akta_kelahiran' => null,
                'alamat' => $data['alamat'],
                'tingkat_id' => $data['tingkat_id'],
                'nama_orangtua' => $data['nama_orangtua'],
                'pekerjaan_orangtua' => $data['pekerjaan_orangtua'],
                'kontak_orangtua' => '+62' . $data['kontak_orangtua'],
                'alamat_orangtua' => $data['alamat_orangtua'],
                'tanggal_daftar' => now(),
                'status' => 'diterima',
            ]);
        }

        $siswa->siswaProfile()->updateOrCreate(
            ['user_id' => $siswa->id],
            [
                'nama_lengkap' => $data['name'],
                'nama_panggilan' => $data['nama_panggilan'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'alamat' => $data['alamat'],
                'no_hp' => '+62' . $data['no_hp'],
                'tingkat_id' => $data['tingkat_id'],
                'status' => 'aktif',
                'asal_sekolah' => $data['asal_sekolah'],
                'kelas' => $data['kelas'],
                'nama_orangtua' => $data['nama_orangtua'],
                'pekerjaan_orangtua' => $data['pekerjaan_orangtua'],
                'kontak_orangtua' => '+62' . $data['kontak_orangtua'],
                'alamat_orangtua' => $data['alamat_orangtua'],
            ]
        );

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil diperbarui.');
    }

    public function destroy(User $siswa)
    {
        if ($siswa->role !== 'siswa') {
            abort(404);
        }

        if ($siswa->siswaProfile) {
            $siswa->siswaProfile->delete();
        }

        if ($siswa->pendaftaran) {
            $siswa->pendaftaran->delete();
        }

        $siswa->delete();

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil dihapus.');
    }
}