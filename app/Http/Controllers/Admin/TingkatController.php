<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tingkat;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TingkatController extends Controller
{
    public function index()
    {
        $tingkats = Tingkat::orderBy('urutan')->get();
        return view('admin.tingkat.index', compact('tingkats'));
    }

    public function create()
    {
        return view('admin.tingkat.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateTingkat($request);

        Tingkat::create($data);

        return redirect()->route('admin.tingkat.index')->with('success', 'Tingkat berhasil ditambahkan.');
    }

    public function show(Tingkat $tingkat)
    {
        return view('admin.tingkat.show', compact('tingkat'));
    }

    public function edit(Tingkat $tingkat)
    {
        return view('admin.tingkat.edit', compact('tingkat'));
    }

    public function update(Request $request, Tingkat $tingkat)
    {
        $data = $this->validateTingkat($request);

        $tingkat->update($data);

        return redirect()->route('admin.tingkat.index')->with('success', 'Tingkat berhasil diperbarui.');
    }

    public function destroy(Tingkat $tingkat)
    {
        $tingkat->delete();

        return redirect()->route('admin.tingkat.index')->with('success', 'Tingkat berhasil dihapus.');
    }

    private function validateTingkat(Request $request): array
    {
        return $request->validate([
            'nama_tingkat' => 'required|string|max:255',
            'jenis_penilaian' => 'required|in:harian,ujian',
            'kkm' => 'required|integer|min:0|max:100',
            'ambang_tidak_lulus' => 'required|integer|min:0|max:100',
            'ambang_pertimbangan_min' => 'required|integer|min:0|max:100',
            'ambang_pertimbangan_max' => 'required|integer|min:0|max:100|gte:ambang_pertimbangan_min',
            'urutan' => 'required|integer|min:1',
        ], [
            'ambang_pertimbangan_max.gte' => 'Batas atas pertimbangan harus lebih besar atau sama dengan batas bawah.',
        ], [
            'ambang_tidak_lulus' => 'ambang tidak lulus',
            'ambang_pertimbangan_min' => 'batas bawah pertimbangan',
            'ambang_pertimbangan_max' => 'batas atas pertimbangan',
        ]);

        if ($data['ambang_tidak_lulus'] >= $data['ambang_pertimbangan_min']) {
            throw ValidationException::withMessages([
                'ambang_tidak_lulus' => 'Ambang tidak lulus harus lebih kecil dari batas bawah pertimbangan.',
            ]);
        }

        if ($data['ambang_pertimbangan_max'] >= $data['kkm']) {
            throw ValidationException::withMessages([
                'ambang_pertimbangan_max' => 'Batas atas pertimbangan harus lebih kecil dari KKM.',
            ]);
        }

        return $data;
    }
}