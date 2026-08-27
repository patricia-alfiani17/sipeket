<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelatih;
use App\Models\Tingkat;
use App\Models\User;
use Illuminate\Http\Request;

class PelatihController extends Controller
{
    public function index()
    {
        $pelatihs = User::where('role', 'pelatih')->with(['pelatihProfile.tingkats'])->get();
        return view('admin.pelatih.index', compact('pelatihs'));
    }

    public function create()
    {
        $tingkats = Tingkat::orderBy('urutan')->get();
        return view('admin.pelatih.create', compact('tingkats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
            'tingkat_ids' => 'nullable|array',
            'tingkat_ids.*' => 'exists:tingkat,id',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'username' => strtolower(str_replace(' ', '', $data['name'])) . rand(100, 999),
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => 'pelatih',
            'status' => 'aktif',
        ]);

        $profile = $user->pelatihProfile()->create([
            'nama_lengkap' => $data['name'],
            'no_hp' => '+62' . $data['no_hp'],
            'alamat' => $data['alamat'],
        ]);

        $profile->tingkats()->sync($data['tingkat_ids'] ?? []);

        return redirect()->route('admin.pelatih.index')->with('success', 'Pelatih berhasil ditambahkan.');
    }

    public function show(User $pelatih)
    {
        if ($pelatih->role !== 'pelatih') {
            abort(404);
        }

        $pelatih->load(['pelatihProfile.tingkats']);
        return view('admin.pelatih.show', compact('pelatih'));
    }

    public function edit(User $pelatih)
    {
        if ($pelatih->role !== 'pelatih') {
            abort(404);
        }

        $pelatih->load(['pelatihProfile.tingkats']);
        $tingkats = Tingkat::orderBy('urutan')->get();
        $assignedTingkatIds = $pelatih->pelatihProfile?->tingkats->pluck('id')->all() ?? [];

        return view('admin.pelatih.edit', compact('pelatih', 'tingkats', 'assignedTingkatIds'));
    }

    public function update(Request $request, User $pelatih)
    {
        if ($pelatih->role !== 'pelatih') {
            abort(404);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $pelatih->id,
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
            'tingkat_ids' => 'nullable|array',
            'tingkat_ids.*' => 'exists:tingkat,id',
        ]);

        $pelatih->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        $profile = $pelatih->pelatihProfile()->updateOrCreate(
            ['user_id' => $pelatih->id],
            [
                'nama_lengkap' => $data['name'],
                'no_hp' => '+62' . $data['no_hp'],
                'alamat' => $data['alamat'],
            ]
        );

        $profile->tingkats()->sync($data['tingkat_ids'] ?? []);

        return redirect()->route('admin.pelatih.index')->with('success', 'Pelatih berhasil diperbarui.');
    }

    public function destroy(User $pelatih)
    {
        if ($pelatih->role !== 'pelatih') {
            abort(404);
        }

        if ($pelatih->pelatihProfile) {
            $pelatih->pelatihProfile->tingkats()->detach();
            $pelatih->pelatihProfile->delete();
        }

        $pelatih->delete();

        return redirect()->route('admin.pelatih.index')->with('success', 'Pelatih berhasil dihapus.');
    }
}
