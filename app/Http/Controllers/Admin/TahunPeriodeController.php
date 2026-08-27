<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunPeriode;
use Illuminate\Http\Request;

class TahunPeriodeController extends Controller
{
    public function index()
    {
        $periodes = TahunPeriode::orderByDesc('is_default')->orderBy('periode')->get();
        return view('admin.tahun-periode.index', compact('periodes'));
    }

    public function create()
    {
        return view('admin.tahun-periode.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'periode' => 'required|string|max:255|unique:tahun_periode,periode',
            'is_default' => 'sometimes|boolean',
        ]);

        if (!empty($data['is_default'])) {
            TahunPeriode::query()->update(['is_default' => false]);
        }

        TahunPeriode::create([
            'periode' => $data['periode'],
            'is_default' => !empty($data['is_default']),
        ]);

        return redirect()->route('admin.tahun-periode.index')->with('success', 'Tahun periode berhasil ditambahkan.');
    }

    public function edit(TahunPeriode $tahunPeriode)
    {
        return view('admin.tahun-periode.edit', compact('tahunPeriode'));
    }

    public function update(Request $request, TahunPeriode $tahunPeriode)
    {
        $data = $request->validate([
            'periode' => 'required|string|max:255|unique:tahun_periode,periode,' . $tahunPeriode->id,
            'is_default' => 'sometimes|boolean',
        ]);

        if (!empty($data['is_default'])) {
            TahunPeriode::query()->update(['is_default' => false]);
        }

        $tahunPeriode->update([
            'periode' => $data['periode'],
            'is_default' => !empty($data['is_default']),
        ]);

        return redirect()->route('admin.tahun-periode.index')->with('success', 'Tahun periode berhasil diperbarui.');
    }

    public function destroy(TahunPeriode $tahunPeriode)
    {
        $tahunPeriode->delete();

        return redirect()->route('admin.tahun-periode.index')->with('success', 'Tahun periode berhasil dihapus.');
    }
}
