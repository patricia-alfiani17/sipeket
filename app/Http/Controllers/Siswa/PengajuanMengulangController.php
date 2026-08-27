<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Siswa\Concerns\MapsSiswaEvaluasi;
use App\Services\PengajuanMengulangService;
use Illuminate\Http\Request;
use InvalidArgumentException;

class PengajuanMengulangController extends Controller
{
    use MapsSiswaEvaluasi;

    public function __construct(
        protected PengajuanMengulangService $pengajuanService
    ) {}

    public function store(Request $request)
    {
        $user = $this->authSiswaUser();
        $siswa = $user->siswaProfile;

        if (!$siswa) {
            abort(403, 'Profil siswa tidak ditemukan.');
        }

        $validated = $request->validate([
            'alasan' => 'required|string|min:20|max:2000',
        ], [
            'alasan.min' => 'Alasan pengajuan minimal 20 karakter.',
        ]);

        try {
            $this->pengajuanService->submit($siswa, $validated['alasan']);
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('siswa.evaluasi')
            ->with('success', 'Pengajuan mengulang tingkat berhasil dikirim. Menunggu persetujuan pelatih.');
    }
}
