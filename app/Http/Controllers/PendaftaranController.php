<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Tingkat;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PendaftaranController extends Controller
{
    public function create()
    {
        $tingkat = Tingkat::allowedForPendaftaran();

        return view('pendaftaran.create', compact('tingkat'));
    }

    public function store(Request $request)
    {
        $allowedIds = Tingkat::allowedForPendaftaranIds();

        $request->validate([
            'nama_lengkap' => 'required',
            'email' => 'required|email|unique:pendaftaran,email',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'nama_panggilan' => 'required|string|max:255',
            'asal_sekolah' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
            'kontak_aktif' => 'required|numeric|digits_between:9,13',
            'akta_kelahiran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'alamat' => 'required|string',
            'tingkat_id' => ['required', Rule::in($allowedIds)],
            'nama_orangtua' => 'required|string|max:255',
            'pekerjaan_orangtua' => 'required|string|max:255',
            'kontak_orangtua' => 'required|numeric|digits_between:9,13',
            'alamat_orangtua' => 'required|string',
        ], [
            'tingkat_id.in' => 'Tingkat yang dipilih tidak valid. Pilih Tingkat Pradasar, Tingkat Dasar 1.1, atau Tingkat Lanjut.',
        ]);

        $upload = app(CloudinaryService::class)
            ->upload($request->file('akta_kelahiran'), 'akta_kelahiran');

        Pendaftaran::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'tanggal_lahir' => $request->tanggal_lahir,
            'tempat_lahir' => $request->tempat_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nama_panggilan' => $request->nama_panggilan,
            'asal_sekolah' => $request->asal_sekolah,
            'kelas' => $request->kelas,
            'kontak_aktif' => '+62'.$request->kontak_aktif,
            'akta_kelahiran' => $upload['path'],
            'akta_kelahiran_url' => $upload['url'],
            'alamat' => $request->alamat,
            'tingkat_id' => $request->tingkat_id,
            'nama_orangtua' => $request->nama_orangtua,
            'pekerjaan_orangtua' => $request->pekerjaan_orangtua,
            'kontak_orangtua' => '+62'.$request->kontak_orangtua,
            'alamat_orangtua' => $request->alamat_orangtua,
            'tanggal_daftar' => now(),
            'status' => 'pending',
        ]);

        return redirect()->back()
            ->with('success', 'Pendaftaran berhasil! Silakan tunggu verifikasi admin.');
    }
}
