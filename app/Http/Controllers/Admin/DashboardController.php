<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function index()
    {
        // Total siswa aktif
        $totalSiswaAktif = User::where('role', 'siswa')->where('status', 'aktif')->count();

        // Total pendaftar
        $totalPendaftar = Pendaftaran::count();

        // Total pengajar
        $totalPengajar = User::where('role', 'pelatih')->count();

        // Pendaftar terbaru (ambil 3 terbaru)
        $pendaftarTerbaru = Pendaftaran::with('tingkat')->latest()->take(3)->get();

        return view('admin.dashboard', compact('totalSiswaAktif', 'totalPendaftar', 'totalPengajar', 'pendaftarTerbaru'));
    }

    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        return view('admin.users', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|in:admin,pelatih,siswa',
            'status' => 'required|string|in:aktif,nonaktif',
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|string|in:admin,pelatih,siswa',
            'status' => 'required|string|in:aktif,nonaktif',
        ]);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'User berhasil diperbarui.');
    }

    public function destroyUser(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users')->with('error', 'Anda tidak dapat menghapus akun yang sedang Anda gunakan.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus.');
    }

    public function pendaftaran(Request $request)
    {
        $search = $request->query('search');

        $totalPendaftar = Pendaftaran::count();
        $pendingCount = Pendaftaran::where('status', 'pending')->count();
        $processedCount = Pendaftaran::whereIn('status', ['diterima', 'ditolak'])->count();
        $pendaftaran = Pendaftaran::with('tingkat')
            ->when($search, function ($query, $search) {
                $query->where('nama_lengkap', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.pendaftaran', compact('totalPendaftar', 'pendingCount', 'processedCount', 'pendaftaran', 'search'));
    }

    public function showAkta(Pendaftaran $pendaftaran)
    {
        $url = $pendaftaran->akta_kelahiran_url;

        if (! $url) {
            abort(404);
        }

        return redirect()->away($url);
    }

    public function getCredentials(Pendaftaran $pendaftaran)
    {
        $user = User::where('email', $pendaftaran->email)->first();

        if (! $user) {
            return response()->json(['credentials' => null], 404);
        }

        return response()->json([
            'credentials' => [
                'username' => $user->username,
                'password' => '123456', // Default password
            ],
        ]);
    }

    public function updatePendaftaranStatus(Request $request, Pendaftaran $pendaftaran)
    {
        $request->validate([
            'status' => 'required|in:diterima,ditolak',
            'catatan_admin' => 'required_if:status,ditolak',
        ]);

        if ($request->status === 'diterima') {
            // Generate username and password for student
            $username = strtolower(str_replace(' ', '', substr($pendaftaran->nama_lengkap, 0, 3))).rand(1000, 9999);
            $password = '123456';

            // Create user account for student
            $user = User::create([
                'name' => $pendaftaran->nama_lengkap,
                'username' => $username,
                'email' => $pendaftaran->email,
                'password' => Hash::make($password),
                'role' => 'siswa',
                'status' => 'aktif',
            ]);

            Siswa::create([
                'user_id' => $user->id,
                'nama_lengkap' => $pendaftaran->nama_lengkap,
                'nama_panggilan' => $pendaftaran->nama_panggilan,
                'jenis_kelamin' => $pendaftaran->jenis_kelamin,
                'tempat_lahir' => $pendaftaran->tempat_lahir,
                'tanggal_lahir' => $pendaftaran->tanggal_lahir,
                'alamat' => $pendaftaran->alamat,
                'no_hp' => $pendaftaran->kontak_aktif ?? $pendaftaran->kontak_orangtua,
                'tingkat_id' => $pendaftaran->tingkat_id,
                'status' => 'aktif',
                'asal_sekolah' => $pendaftaran->asal_sekolah,
                'kelas' => $pendaftaran->kelas,
                'nama_orangtua' => $pendaftaran->nama_orangtua,
                'pekerjaan_orangtua' => $pendaftaran->pekerjaan_orangtua,
                'kontak_orangtua' => $pendaftaran->kontak_orangtua,
                'alamat_orangtua' => $pendaftaran->alamat_orangtua,
            ]);

            $pendaftaran->update([
                'status' => 'diterima',
                'catatan_admin' => $request->catatan_admin,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status pendaftar berhasil diperbarui.',
                    'credentials' => [
                        'username' => $username,
                        'password' => $password,
                    ],
                ]);
            }
        } else {
            $pendaftaran->update([
                'status' => 'ditolak',
                'catatan_admin' => $request->catatan_admin,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pendaftar berhasil ditolak.',
                ]);
            }
        }

        return redirect()->route('admin.pendaftaran')->with('success', 'Status pendaftar berhasil diperbarui.');
    }
}
