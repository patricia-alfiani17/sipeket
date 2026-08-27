<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Siswa\Concerns\MapsSiswaEvaluasi;
use App\Models\PengajuanMengulangTingkat;
use App\Services\EvaluasiKenaikanTingkatService;
use App\Services\PengajuanMengulangService;

class DashboardController extends Controller
{
    use MapsSiswaEvaluasi;

    public function index()
    {
        $user = $this->authSiswaUser();
        $siswa = $user->siswaProfile;

        $evaluasiRows = $this->evaluasiRowsForUser($user);
        $riwayatRows = $this->riwayatRowsForUser($user);

        $tingkatSaatIni = $siswa?->tingkat?->nama_tingkat ?? '-';
        $totalEvaluasi = $evaluasiRows->count();
        $totalRiwayat = $riwayatRows->count();
        $evaluasiTerakhir = $evaluasiRows->first();

        $hasPendingPengajuan = false;
        $isInMandatoryRepeat = false;
        if ($siswa) {
            $hasPendingPengajuan = PengajuanMengulangTingkat::where('siswa_id', $siswa->id)
                ->where('status', PengajuanMengulangTingkat::STATUS_PENDING)
                ->exists();
            $isInMandatoryRepeat = app(EvaluasiKenaikanTingkatService::class)->isInMandatoryRepeat($siswa);
        }

        return view('siswa.dashboard', compact(
            'user',
            'tingkatSaatIni',
            'totalEvaluasi',
            'totalRiwayat',
            'evaluasiTerakhir',
            'hasPendingPengajuan',
            'isInMandatoryRepeat',
        ));
    }

    public function profil()
    {
        $user = $this->authSiswaUser();
        $siswa = $user->siswaProfile;
        $tingkatSaatIni = $siswa?->tingkat?->nama_tingkat ?? '-';

        return view('siswa.profil', compact('user', 'siswa', 'tingkatSaatIni'));
    }

    public function evaluasi()
    {
        $user = $this->authSiswaUser();
        $siswa = $user->siswaProfile;
        $evaluasiRows = $this->evaluasiRowsForUser($user);

        $canSubmitPengajuan = $siswa
            ? app(PengajuanMengulangService::class)->canSubmit($siswa)
            : ['eligible' => false, 'reason' => '', 'tingkat' => null];

        $pendingPengajuan = $siswa
            ? PengajuanMengulangTingkat::where('siswa_id', $siswa->id)
                ->where('status', PengajuanMengulangTingkat::STATUS_PENDING)
                ->with('tingkat')
                ->first()
            : null;

        $eligibleTingkatId = $canSubmitPengajuan['tingkat']?->id;

        $isInMandatoryRepeat = $siswa
            ? app(EvaluasiKenaikanTingkatService::class)->isInMandatoryRepeat($siswa)
            : false;

        return view('siswa.evaluasi', compact(
            'user',
            'evaluasiRows',
            'canSubmitPengajuan',
            'pendingPengajuan',
            'eligibleTingkatId',
            'isInMandatoryRepeat',
        ));
    }

    public function riwayat()
    {
        $user = $this->authSiswaUser();
        $riwayatRows = $this->riwayatRowsForUser($user);

        return view('siswa.riwayat', compact('user', 'riwayatRows'));
    }
}
