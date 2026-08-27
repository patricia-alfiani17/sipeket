<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MateriLatihan;
use App\Models\Tingkat;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MateriLatihanController extends Controller
{
    public function index()
    {
        $materis = MateriLatihan::with('tingkat')->orderBy('urutan')->get();
        return view('admin.materi-latihan.index', compact('materis'));
    }

    public function create()
    {
        $tingkats = Tingkat::orderBy('urutan')->get();
        return view('admin.materi-latihan.create', compact('tingkats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255|unique:materi_latihan,nama',
            'deskripsi' => 'nullable|string',
            'urutan' => 'required|integer|min:1',
            'tingkat_id' => [
                'required',
                Rule::exists('tingkat', 'id')->where(fn ($query) => $query->whereIn('jenis_penilaian', ['harian', 'ujian'])),
            ],
        ], [
            'tingkat_id.exists' => 'Tingkat yang dipilih tidak valid.',
        ]);

        MateriLatihan::create($data);

        return redirect()->route('admin.materi-latihan.index')->with('success', 'Materi latihan berhasil ditambahkan.');
    }

    public function edit(MateriLatihan $materiLatihan)
    {
        $tingkats = Tingkat::orderBy('urutan')->get();
        return view('admin.materi-latihan.edit', compact('materiLatihan', 'tingkats'));
    }

    public function update(Request $request, MateriLatihan $materiLatihan)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255|unique:materi_latihan,nama,' . $materiLatihan->id,
            'deskripsi' => 'nullable|string',
            'urutan' => 'required|integer|min:1',
            'tingkat_id' => [
                'required',
                Rule::exists('tingkat', 'id')->where(fn ($query) => $query->whereIn('jenis_penilaian', ['harian', 'ujian'])),
            ],
        ], [
            'tingkat_id.exists' => 'Tingkat yang dipilih tidak valid.',
        ]);

        $materiLatihan->update($data);

        return redirect()->route('admin.materi-latihan.index')->with('success', 'Materi latihan berhasil diperbarui.');
    }

    public function destroy(MateriLatihan $materiLatihan)
    {
        $materiLatihan->delete();

        return redirect()->route('admin.materi-latihan.index')->with('success', 'Materi latihan berhasil dihapus.');
    }
}
