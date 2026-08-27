<?php

namespace App\Http\Controllers;

use App\Models\EvaluasiTingkat;
use App\Models\MateriLatihan;
use App\Models\NilaiHarian;
use App\Models\NilaiUjianMateri;
use App\Models\NilaiUjianPenguji;
use App\Models\PengajuanMengulangTingkat;
use App\Models\Pelatih;
use App\Models\RekapNilaiHarian;
use App\Models\RekapNilaiUjian;
use App\Models\Tingkat;
use App\Models\TahunPeriode;
use App\Models\User;
use App\Services\EvaluasiKenaikanTingkatService;
use App\Services\PengajuanMengulangService;
use App\Services\PelatihTingkatAccessService;
use App\Services\RekapNilaiHarianService;
use App\Services\RekapNilaiUjianService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PelatihController extends Controller
{
    public function __construct(
        protected RekapNilaiHarianService $rekapService,
        protected RekapNilaiUjianService $rekapUjianService,
        protected EvaluasiKenaikanTingkatService $evaluasiKenaikanService,
        protected PelatihTingkatAccessService $tingkatAccessService,
        protected PengajuanMengulangService $pengajuanMengulangService
    ) {}

    public function dashboard()
    {
        $pelatih = $this->authPelatihProfile();
        $accessibleTingkats = $this->tingkatAccessService->accessibleTingkats($pelatih);
        $allowedIds = $this->tingkatAccessService->accessibleTingkatIds($pelatih);

        $totalSiswaAktif = $allowedIds->isEmpty()
            ? 0
            : User::where('role', 'siswa')
                ->where('status', 'aktif')
                ->whereHas('siswaProfile', fn ($q) => $q->whereIn('tingkat_id', $allowedIds))
                ->count();

        $totalPengajuanPending = $this->pengajuanMengulangService->countPendingForPelatih($pelatih);

        return view('pelatih.dashboard', compact(
            'totalSiswaAktif',
            'accessibleTingkats',
            'totalPengajuanPending'
        ));
    }

    public function dataSiswa(Request $request)
    {
        $pelatih = $this->authPelatihProfile();
        $search = $request->query('search');
        $tingkat_id = $request->query('tingkat_id');
        $status = $request->query('status');

        $query = User::where('role', 'siswa')
            ->with(['siswaProfile.tingkat']);

        $this->tingkatAccessService->scopeUsersInAccessibleTingkat($query, $pelatih);

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($tingkat_id) {
            if (!$this->tingkatAccessService->canAccessTingkat($pelatih, (int) $tingkat_id)) {
                abort(403, 'Anda tidak memiliki akses ke tingkat ini.');
            }

            $query->whereHas('siswaProfile', function ($q) use ($tingkat_id) {
                $q->where('tingkat_id', $tingkat_id);
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $siswas = $query->get();
        $tingkats = $this->tingkatAccessService->accessibleTingkats($pelatih);

        return view('pelatih.data-siswa', compact('siswas', 'tingkats', 'search', 'tingkat_id', 'status'));
    }

    public function inputNilaiHarian(Request $request)
    {
        $pelatih = $this->authPelatihProfile();
        $tingkat_id = $request->query('tingkat_id');
        $tahun_periode_id = $request->query('tahun_periode_id');
        $materi_latihan_id = $request->query('materi_latihan_id');

        $tingkats = $this->tingkatAccessService->filterTingkats(
            Tingkat::where('jenis_penilaian', 'harian')->orderBy('urutan')->get(),
            $pelatih
        );

        if ($tingkat_id && !$tingkats->contains('id', (int) $tingkat_id)) {
            $tingkat_id = null;
            $materi_latihan_id = null;
        }

        $tahunPeriodes = TahunPeriode::orderByDesc('is_default')->orderBy('periode')->get();
        $canSelectMateri = !empty($tingkat_id) && !empty($tahun_periode_id);
        $materiLatihans = collect();

        if ($canSelectMateri) {
            $materiLatihans = MateriLatihan::where('tingkat_id', $tingkat_id)
                ->orderBy('urutan')
                ->get();
        }

        $defaultTahunPeriode = TahunPeriode::where('is_default', true)->first();
        $selectedTahunPeriode = $tahun_periode_id ? TahunPeriode::find($tahun_periode_id) : $defaultTahunPeriode;
        $selectedMateriLatihan = $materi_latihan_id ? MateriLatihan::find($materi_latihan_id) : null;
        $selectedTahunPeriodeId = optional($selectedTahunPeriode)->id;
        $selectedMateriLatihanId = $selectedMateriLatihan ? $selectedMateriLatihan->id : null;
        $isApplied = !empty($tingkat_id) && !empty($tahun_periode_id) && !empty($materi_latihan_id) && $selectedTahunPeriode && $selectedMateriLatihan;
        $showRekap = !empty($tingkat_id) && $selectedTahunPeriode;

        $siswas = collect();
        $nilaiHarians = collect();
        $materiColumns = collect();
        $rekapNilai = collect();

        if ($tingkat_id) {
            $siswas = User::where('role', 'siswa')
                ->with(['siswaProfile.tingkat', 'nilaiHarian'])
                ->whereHas('siswaProfile', function ($q) use ($tingkat_id) {
                    $q->where('tingkat_id', $tingkat_id);
                })
                ->get();
        }

        if ($showRekap) {
            $materiMaster = $this->rekapService->getMateriMasterForTingkat((int) $tingkat_id);
            $materiColumns = $materiMaster->pluck('nama');

            $nilaiHarians = NilaiHarian::with('user')
                ->whereIn('user_id', $siswas->pluck('id'))
                ->where('tingkat_id', $tingkat_id)
                ->where('tahun_periode', $selectedTahunPeriode->periode)
                ->when($materiColumns->isNotEmpty(), fn ($q) => $q->whereIn('materi_latihan', $materiColumns))
                ->get();

            $rekapNilai = $siswas->map(
                fn ($siswa) => $this->rekapService->buildPreviewRow($siswa, $materiMaster, $nilaiHarians)
            );
        }

        return view('pelatih.input-nilai-harian', compact(
            'tingkats',
            'tingkat_id',
            'tahun_periode_id',
            'canSelectMateri',
            'selectedTahunPeriodeId',
            'selectedMateriLatihanId',
            'tahunPeriodes',
            'materiLatihans',
            'selectedTahunPeriode',
            'selectedMateriLatihan',
            'defaultTahunPeriode',
            'isApplied',
            'showRekap',
            'siswas',
            'nilaiHarians',
            'materiColumns',
            'rekapNilai'
        ));
    }

    public function storeNilaiHarian(Request $request)
    {
        $validated = $request->validate([
            'tingkat_id' => [
                'required',
                Rule::exists('tingkat', 'id')->where(fn ($query) => $query->where('jenis_penilaian', 'harian')),
            ],
            'tahun_periode_id' => 'required|exists:tahun_periode,id',
            'materi_latihan_id' => 'required|exists:materi_latihan,id',
            'wiraga' => 'required|array',
            'wirasa' => 'required|array',
            'wirama' => 'required|array',
            'wiraga.*' => ['required', 'numeric', 'min:0', 'max:100', 'decimal:0,2'],
            'wirasa.*' => ['required', 'numeric', 'min:0', 'max:100', 'decimal:0,2'],
            'wirama.*' => ['required', 'numeric', 'min:0', 'max:100', 'decimal:0,2'],
        ], [
            'wiraga.*.min' => 'Nilai wiraga minimal 0.',
            'wiraga.*.max' => 'Nilai wiraga maksimal 100.',
            'wirasa.*.min' => 'Nilai wirasa minimal 0.',
            'wirasa.*.max' => 'Nilai wirasa maksimal 100.',
            'wirama.*.min' => 'Nilai wirama minimal 0.',
            'wirama.*.max' => 'Nilai wirama maksimal 100.',
            'wiraga.*.decimal' => 'Nilai wiraga maksimal 2 angka di belakang koma.',
            'wirasa.*.decimal' => 'Nilai wirasa maksimal 2 angka di belakang koma.',
            'wirama.*.decimal' => 'Nilai wirama maksimal 2 angka di belakang koma.',
        ]);

        $pelatih = $this->authPelatihProfile();
        $pelatih_id = $pelatih->id;
        $this->tingkatAccessService->assertCanAccessTingkat($pelatih, (int) $validated['tingkat_id']);

        $tingkat_id = $validated['tingkat_id'];
        $tahunPeriode = TahunPeriode::findOrFail($validated['tahun_periode_id']);
        $materiLatihan = MateriLatihan::findOrFail($validated['materi_latihan_id']);
        $wiraga = $validated['wiraga'];
        $wirasa = $validated['wirasa'];
        $wirama = $validated['wirama'];

        foreach ($wiraga as $user_id => $nilai) {
            $siswa = User::with('siswaProfile')->find($user_id);
            if (!$siswa || !$siswa->siswaProfile) {
                continue;
            }

            $wiragaValue = $this->normalizePenilaianScore($nilai);
            $wirasaValue = $this->normalizePenilaianScore($wirasa[$user_id] ?? 0);
            $wiramaValue = $this->normalizePenilaianScore($wirama[$user_id] ?? 0);
            $averageValue = round(($wiragaValue + $wirasaValue + $wiramaValue) / 3, 2);

            NilaiHarian::updateOrCreate(
                [
                    'user_id' => $user_id,
                    'tingkat_id' => $tingkat_id,
                    'tahun_periode' => $tahunPeriode->periode,
                    'materi_latihan' => $materiLatihan->nama,
                ],
                [
                    'siswa_id' => $siswa->siswaProfile->id,
                    'pelatih_id' => $pelatih_id,
                    'wiraga' => $wiragaValue,
                    'wirasa' => $wirasaValue,
                    'wirama' => $wiramaValue,
                    'nilai' => $averageValue,
                    'tanggal' => now()->toDateString(),
                    'tahun_periode' => $tahunPeriode->periode,
                    'materi_latihan' => $materiLatihan->nama,
                ]
            );
        }

        $this->rekapService->syncRekapForTingkatUsers(
            array_keys($wiraga),
            (int) $tingkat_id,
            $tahunPeriode->periode,
            $pelatih_id
        );

        return redirect()->route('pelatih.input-nilai-harian', [
            'tingkat_id' => $tingkat_id,
            'tahun_periode_id' => $tahunPeriode->id,
            'materi_latihan_id' => $materiLatihan->id,
        ])->with('success', 'Nilai harian berhasil disimpan.');
    }

    public function inputNilaiUjian(Request $request)
    {
        $pelatih = $this->authPelatihProfile();
        $tingkat_id = $request->query('tingkat_id');
        $tahun_periode_id = $request->query('tahun_periode_id');
        $materi_latihan_id = $request->query('materi_latihan_id');
        $user_id = $request->query('user_id');

        $tingkats = $this->tingkatAccessService->filterTingkats(
            Tingkat::where('jenis_penilaian', 'ujian')->orderBy('urutan')->get(),
            $pelatih
        );

        if ($tingkat_id && !$tingkats->contains('id', (int) $tingkat_id)) {
            $tingkat_id = null;
            $materi_latihan_id = null;
            $user_id = null;
        }

        $tahunPeriodes = TahunPeriode::orderByDesc('is_default')->orderBy('periode')->get();
        $canSelectMateri = !empty($tingkat_id) && !empty($tahun_periode_id);
        $materiLatihans = collect();

        if ($canSelectMateri) {
            $materiLatihans = MateriLatihan::where('tingkat_id', $tingkat_id)
                ->orderBy('urutan')
                ->get();
        }

        $selectedTahunPeriode = $tahun_periode_id ? TahunPeriode::find($tahun_periode_id) : null;
        $selectedMateriLatihan = $materi_latihan_id ? MateriLatihan::find($materi_latihan_id) : null;

        $isLastMateri = false;
        if ($selectedMateriLatihan) {
            $maxUrutan = $materiLatihans->max('urutan');
            $isLastMateri = ($selectedMateriLatihan->urutan == $maxUrutan);
        }

        $isApplied = !empty($tingkat_id) && !empty($tahun_periode_id) && !empty($materi_latihan_id) && $selectedTahunPeriode && $selectedMateriLatihan;
        $showRekap = !empty($tingkat_id) && $selectedTahunPeriode;

        $siswas = collect();
        $pengujiScores = [];
        $rekapNilai = collect();
        $selectedUser = null;
        $nilaiHarians = collect();

        if ($isApplied) {
            if ($isLastMateri) {
                $siswas = User::where('role', 'siswa')
                    ->with(['siswaProfile.tingkat'])
                    ->whereHas('siswaProfile', function ($q) use ($tingkat_id) {
                        $q->where('tingkat_id', $tingkat_id);
                    })
                    ->orderBy('name')
                    ->get()
                    ->filter(function ($siswa) use ($tingkat_id, $selectedTahunPeriode) {
                        if (!$siswa->siswaProfile) {
                            return false;
                        }

                        return $this->rekapUjianService->canInputUjian(
                            $siswa->siswaProfile->id,
                            (int) $tingkat_id,
                            $selectedTahunPeriode->periode
                        );
                    })
                    ->values();

                if ($user_id) {
                    $selectedUser = User::with('siswaProfile')->find($user_id);
                    if ($selectedUser && $selectedUser->siswaProfile) {
                        $pengujiScores = $this->rekapUjianService->getPengujiScoresForForm(
                            $selectedUser->siswaProfile->id,
                            (int) $tingkat_id,
                            $selectedTahunPeriode->periode,
                            $selectedMateriLatihan->nama
                        );
                    }
                }
            } else {
                $siswas = User::where('role', 'siswa')
                    ->with(['siswaProfile.tingkat'])
                    ->whereHas('siswaProfile', function ($q) use ($tingkat_id) {
                        $q->where('tingkat_id', $tingkat_id);
                    })
                    ->orderBy('name')
                    ->get();

                $nilaiHarians = NilaiUjianPenguji::where('tingkat_id', $tingkat_id)
                    ->where('tahun_periode', $selectedTahunPeriode->periode)
                    ->where('materi_latihan', $selectedMateriLatihan->nama)
                    ->where('nomor_penguji', 1)
                    ->get();
            }
        }

        if ($showRekap) {
            $materiMaster = $this->rekapUjianService->getMateriMasterForTingkat((int) $tingkat_id);

            $siswaList = User::where('role', 'siswa')
                ->with(['siswaProfile.tingkat'])
                ->whereHas('siswaProfile', function ($q) use ($tingkat_id) {
                    $q->where('tingkat_id', $tingkat_id);
                })
                ->get();

            $nilaiMateri = NilaiUjianMateri::where('tingkat_id', $tingkat_id)
                ->where('tahun_periode', $selectedTahunPeriode->periode)
                ->whereIn('user_id', $siswaList->pluck('id'))
                ->get();

            $rekapNilai = $siswaList->map(
                fn ($siswa) => $this->rekapUjianService->buildPreviewRow($siswa, $materiMaster, $nilaiMateri)
            );
        }

        return view('pelatih.input-nilai-ujian', compact(
            'tingkats',
            'tingkat_id',
            'tahun_periode_id',
            'materi_latihan_id',
            'user_id',
            'canSelectMateri',
            'materiLatihans',
            'tahunPeriodes',
            'selectedTahunPeriode',
            'selectedMateriLatihan',
            'isLastMateri',
            'isApplied',
            'showRekap',
            'siswas',
            'pengujiScores',
            'rekapNilai',
            'selectedUser',
            'nilaiHarians'
        ));
    }

    public function storeNilaiUjian(Request $request)
    {
        $tingkatId = (int) $request->input('tingkat_id');
        $materiLatihanId = $request->input('materi_latihan_id');

        $materiLatihan = MateriLatihan::findOrFail($materiLatihanId);

        $maxUrutan = MateriLatihan::where('tingkat_id', $tingkatId)->max('urutan');
        $isLastMateri = ($materiLatihan->urutan == $maxUrutan);

        if ($isLastMateri) {
            $validated = $request->validate([
                'tingkat_id' => [
                    'required',
                    Rule::exists('tingkat', 'id')->where(fn ($query) => $query->where('jenis_penilaian', 'ujian')),
                ],
                'tahun_periode_id' => 'required|exists:tahun_periode,id',
                'user_id' => 'required|exists:users,id',
                'materi_latihan_id' => 'required|exists:materi_latihan,id',
                'penguji' => 'required|array',
                'penguji.1' => 'required|array',
                'penguji.2' => 'required|array',
                'penguji.3' => 'required|array',
                'penguji.*.wiraga' => ['required', 'numeric', 'min:0', 'max:100', 'decimal:0,2'],
                'penguji.*.wirama' => ['required', 'numeric', 'min:0', 'max:100', 'decimal:0,2'],
                'penguji.*.wirasa' => ['required', 'numeric', 'min:0', 'max:100', 'decimal:0,2'],
            ], [
                'penguji.*.wiraga.max' => 'Nilai wiraga maksimal 100.',
                'penguji.*.wirama.max' => 'Nilai wirama maksimal 100.',
                'penguji.*.wirasa.max' => 'Nilai wirasa maksimal 100.',
                'penguji.*.wiraga.decimal' => 'Nilai wiraga maksimal 2 angka di belakang koma.',
                'penguji.*.wirama.decimal' => 'Nilai wirama maksimal 2 angka di belakang koma.',
                'penguji.*.wirasa.decimal' => 'Nilai wirasa maksimal 2 angka di belakang koma.',
            ]);

            $pelatih = $this->authPelatihProfile();
            $pelatih_id = $pelatih->id;
            $this->tingkatAccessService->assertCanAccessTingkat($pelatih, $tingkatId);

            $tahunPeriode = TahunPeriode::findOrFail($validated['tahun_periode_id']);
            $siswa = User::with('siswaProfile')->findOrFail($validated['user_id']);
            $materiLabel = $materiLatihan->nama;

            if (!$siswa->siswaProfile) {
                return back()->with('error', 'Profil siswa tidak ditemukan.');
            }

            if (!$this->rekapUjianService->canInputUjian(
                $siswa->siswaProfile->id,
                $tingkatId,
                $tahunPeriode->periode
            )) {
                return back()->with('error', 'Siswa ini sudah memiliki nilai ujian lengkap untuk periode ini.');
            }

            $tanggalUjian = now()->toDateString();

            foreach ([1, 2, 3] as $nomor) {
                $p = $validated['penguji'][$nomor];
                $wiraga = $this->normalizePenilaianScore($p['wiraga']);
                $wirama = $this->normalizePenilaianScore($p['wirama']);
                $wirasa = $this->normalizePenilaianScore($p['wirasa']);
                $rata = $this->rekapUjianService->hitungRataPenguji($wiraga, $wirama, $wirasa);

                NilaiUjianPenguji::updateOrCreate(
                    [
                        'siswa_id' => $siswa->siswaProfile->id,
                        'tingkat_id' => $tingkatId,
                        'tahun_periode' => $tahunPeriode->periode,
                        'materi_latihan' => $materiLabel,
                        'nomor_penguji' => $nomor,
                    ],
                    [
                        'user_id' => $siswa->id,
                        'pelatih_id' => $pelatih_id,
                        'wiraga' => $wiraga,
                        'wirama' => $wirama,
                        'wirasa' => $wirasa,
                        'rata_penguji' => $rata,
                        'tanggal_ujian' => $tanggalUjian,
                    ]
                );
            }

            $this->rekapUjianService->syncMateriFromPenguji(
                $siswa,
                $tingkatId,
                $tahunPeriode->periode,
                $materiLabel,
                $pelatih_id
            );

            $this->rekapUjianService->syncRekapForSiswa(
                $siswa,
                $tingkatId,
                $tahunPeriode->periode,
                $pelatih_id
            );

            return redirect()->route('pelatih.input-nilai-ujian', [
                'tingkat_id' => $tingkatId,
                'tahun_periode_id' => $tahunPeriode->id,
                'materi_latihan_id' => $materiLatihan->id,
                'user_id' => $siswa->id,
            ])->with('success', 'Nilai ujian berhasil disimpan.');
        } else {
            $validated = $request->validate([
                'tingkat_id' => [
                    'required',
                    Rule::exists('tingkat', 'id')->where(fn ($query) => $query->where('jenis_penilaian', 'ujian')),
                ],
                'tahun_periode_id' => 'required|exists:tahun_periode,id',
                'materi_latihan_id' => 'required|exists:materi_latihan,id',
                'wiraga' => 'required|array',
                'wirasa' => 'required|array',
                'wirama' => 'required|array',
                'wiraga.*' => ['required', 'numeric', 'min:0', 'max:100', 'decimal:0,2'],
                'wirasa.*' => ['required', 'numeric', 'min:0', 'max:100', 'decimal:0,2'],
                'wirama.*' => ['required', 'numeric', 'min:0', 'max:100', 'decimal:0,2'],
            ], [
                'wiraga.*.min' => 'Nilai wiraga minimal 0.',
                'wiraga.*.max' => 'Nilai wiraga maksimal 100.',
                'wirasa.*.min' => 'Nilai wirasa minimal 0.',
                'wirasa.*.max' => 'Nilai wirasa maksimal 100.',
                'wirama.*.min' => 'Nilai wirama minimal 0.',
                'wirama.*.max' => 'Nilai wirama maksimal 100.',
                'wiraga.*.decimal' => 'Nilai wiraga maksimal 2 angka di belakang koma.',
                'wirasa.*.decimal' => 'Nilai wirasa maksimal 2 angka di belakang koma.',
                'wirama.*.decimal' => 'Nilai wirama maksimal 2 angka di belakang koma.',
            ]);

            $pelatih = $this->authPelatihProfile();
            $pelatih_id = $pelatih->id;
            $this->tingkatAccessService->assertCanAccessTingkat($pelatih, $tingkatId);

            $tahunPeriode = TahunPeriode::findOrFail($validated['tahun_periode_id']);
            $materiLabel = $materiLatihan->nama;
            $wiraga = $validated['wiraga'];
            $wirasa = $validated['wirasa'];
            $wirama = $validated['wirama'];

            foreach ($wiraga as $user_id => $nilai) {
                $siswa = User::with('siswaProfile')->find($user_id);
                if (!$siswa || !$siswa->siswaProfile) {
                    continue;
                }

                $wiragaValue = $this->normalizePenilaianScore($nilai);
                $wirasaValue = $this->normalizePenilaianScore($wirasa[$user_id] ?? 0);
                $wiramaValue = $this->normalizePenilaianScore($wirama[$user_id] ?? 0);
                $rata = $this->rekapUjianService->hitungRataPenguji($wiragaValue, $wiramaValue, $wirasaValue);

                NilaiUjianPenguji::updateOrCreate(
                    [
                        'siswa_id' => $siswa->siswaProfile->id,
                        'tingkat_id' => $tingkatId,
                        'tahun_periode' => $tahunPeriode->periode,
                        'materi_latihan' => $materiLabel,
                        'nomor_penguji' => 1,
                    ],
                    [
                        'user_id' => $user_id,
                        'pelatih_id' => $pelatih_id,
                        'wiraga' => $wiragaValue,
                        'wirama' => $wiramaValue,
                        'wirasa' => $wirasaValue,
                        'rata_penguji' => $rata,
                        'tanggal_ujian' => now()->toDateString(),
                    ]
                );

                $this->rekapUjianService->syncMateriFromPenguji(
                    $siswa,
                    $tingkatId,
                    $tahunPeriode->periode,
                    $materiLabel,
                    $pelatih_id
                );

                $this->rekapUjianService->syncRekapForSiswa(
                    $siswa,
                    $tingkatId,
                    $tahunPeriode->periode,
                    $pelatih_id
                );
            }

            return redirect()->route('pelatih.input-nilai-ujian', [
                'tingkat_id' => $tingkatId,
                'tahun_periode_id' => $tahunPeriode->id,
                'materi_latihan_id' => $materiLatihan->id,
            ])->with('success', 'Nilai latihan harian berhasil disimpan.');
        }
    }

    public function evaluasiKenaikanTingkat(Request $request)
    {
        $pelatih = $this->authPelatihProfile();
        $tingkat_id = $request->query('tingkat_id');
        $tahun_periode_id = $request->query('tahun_periode_id');
        $jenis_penilaian = $request->query('jenis_penilaian');
        $allowedIds = $this->tingkatAccessService->accessibleTingkatIds($pelatih);

        if ($tingkat_id && !$this->tingkatAccessService->canAccessTingkat($pelatih, (int) $tingkat_id)) {
            abort(403, 'Anda tidak memiliki akses ke tingkat ini.');
        }

        $tingkats = $this->tingkatAccessService->accessibleTingkats($pelatih);

        $tahunPeriodes = TahunPeriode::orderByDesc('is_default')->orderBy('periode')->get();

        $selectedTahunPeriode = $tahun_periode_id
            ? TahunPeriode::find($tahun_periode_id)
            : TahunPeriode::where('is_default', true)->first();

        $rekapRows = collect();
        $periode = $selectedTahunPeriode?->periode;

        if (!$jenis_penilaian || $jenis_penilaian === 'harian') {
            $harianQuery = RekapNilaiHarian::with(['user', 'tingkat', 'siswa.tingkat'])
                ->where('status', RekapNilaiHarian::STATUS_SIAP_EVALUASI)
                ->where('evaluasi_selesai', false);

            if ($this->tingkatAccessService->hasRestrictedAccess($pelatih)) {
                $harianQuery->whereIn('tingkat_id', $allowedIds);
            }

            if ($tingkat_id) {
                $harianQuery->where('tingkat_id', $tingkat_id);
            }
            if ($periode) {
                $harianQuery->where('tahun_periode', $periode);
            }
            $harianQuery->whereHas('tingkat', fn ($q) => $q->where('jenis_penilaian', 'harian'));

            $rekapRows = $rekapRows->merge(
                $harianQuery->get()->map(fn ($rekap) => $this->mapBarisMenungguEvaluasi($rekap, 'harian', $request))
            );
        }

        if (!$jenis_penilaian || $jenis_penilaian === 'ujian') {
            $ujianQuery = RekapNilaiUjian::with(['user', 'tingkat', 'siswa.tingkat'])
                ->where('status', RekapNilaiUjian::STATUS_SIAP_EVALUASI)
                ->where('evaluasi_selesai', false);

            if ($this->tingkatAccessService->hasRestrictedAccess($pelatih)) {
                $ujianQuery->whereIn('tingkat_id', $allowedIds);
            }

            if ($tingkat_id) {
                $ujianQuery->where('tingkat_id', $tingkat_id);
            }
            if ($periode) {
                $ujianQuery->where('tahun_periode', $periode);
            }
            $ujianQuery->whereHas('tingkat', fn ($q) => $q->where('jenis_penilaian', 'ujian'));

            $rekapRows = $rekapRows->merge(
                $ujianQuery->get()->map(fn ($rekap) => $this->mapBarisMenungguEvaluasi($rekap, 'ujian', $request))
            );
        }

        $evaluasiQuery = EvaluasiTingkat::with(['siswa.user', 'siswa.tingkat', 'tingkat']);

        if ($this->tingkatAccessService->hasRestrictedAccess($pelatih)) {
            $evaluasiQuery->whereIn('tingkat_id', $allowedIds);
        }

        $evaluasiQuery->orderByDesc('tanggal_evaluasi')
            ->orderByDesc('id');

        if ($tingkat_id) {
            $evaluasiQuery->where('tingkat_id', $tingkat_id);
        }

        if ($selectedTahunPeriode) {
            $evaluasiQuery->where('tahun_periode', $selectedTahunPeriode->periode);
        }

        if ($jenis_penilaian && in_array($jenis_penilaian, ['harian', 'ujian'], true)) {
            $evaluasiQuery->whereHas('tingkat', fn ($q) => $q->where('jenis_penilaian', $jenis_penilaian));
        }

        $evaluasiTersimpanRows = $evaluasiQuery->get()->map(
            fn (EvaluasiTingkat $evaluasi) => $this->mapBarisEvaluasiTersimpan($evaluasi)
        );

        return view('pelatih.evaluasi-kenaikan-tingkat', compact(
            'tingkats',
            'tahunPeriodes',
            'tingkat_id',
            'tahun_periode_id',
            'jenis_penilaian',
            'selectedTahunPeriode',
            'rekapRows',
            'evaluasiTersimpanRows'
        ));
    }

    public function tetapkanEvaluasiKenaikanTingkat(Request $request)
    {
        $validated = $request->validate([
            'rekap_id' => 'required|integer',
            'jenis_rekap' => 'required|in:harian,ujian',
            'keputusan' => 'required|in:naik,tidak_naik',
        ]);

        $rekap = $this->findRekapMenunggu($validated['jenis_rekap'], (int) $validated['rekap_id']);
        if (!$rekap) {
            return back()->with('error', 'Data rekap tidak ditemukan.');
        }

        $this->tingkatAccessService->assertCanAccessTingkat(
            $this->authPelatihProfile(),
            (int) $rekap->tingkat_id
        );

        $average = (float) $rekap->average;
        if ($rekap->tingkat->klasifikasiKelulusan($average) !== Tingkat::KELULUSAN_TOLERANSI) {
            return back()->with('error', 'Keputusan manual hanya untuk status toleransi.');
        }

        $request->session()->put(
            $this->draftSessionKey($validated['jenis_rekap'], $rekap->id),
            $validated['keputusan']
        );

        return redirect()
            ->route('pelatih.evaluasi-kenaikan-tingkat', $request->only(['tingkat_id', 'tahun_periode_id', 'jenis_penilaian']))
            ->with('success', 'Keputusan sementara berhasil ditetapkan. Klik Simpan untuk menyimpan final.');
    }

    public function storeEvaluasiKenaikanTingkat(Request $request)
    {
        $pelatih = $this->authPelatihProfile();
        $pelatih_id = $pelatih->id;

        $validated = $request->validate([
            'rekap_id' => 'required|array|min:1',
            'jenis_rekap' => 'required|array',
            'jenis_rekap.*' => 'in:harian,ujian',
            'keputusan' => 'required|array',
            'keputusan.*' => 'nullable|in:naik,tidak_naik',
        ]);

        $saved = 0;

        DB::transaction(function () use ($validated, $pelatih_id, $pelatih, &$saved, $request) {
            foreach ($validated['rekap_id'] as $index => $rekapId) {
                $jenisRekap = $validated['jenis_rekap'][$index] ?? 'harian';
                $rekap = $this->findRekapMenunggu($jenisRekap, (int) $rekapId);
                if (!$rekap) {
                    continue;
                }

                if (!$this->tingkatAccessService->canAccessTingkat($pelatih, (int) $rekap->tingkat_id)) {
                    continue;
                }

                $tingkat = $rekap->tingkat;
                $average = (float) $rekap->average;
                $statusKelulusan = $tingkat->klasifikasiKelulusan($average);
                $keputusanInput = $validated['keputusan'][$index]
                    ?? $request->session()->get($this->draftSessionKey($jenisRekap, $rekap->id));

                $keputusan = $this->evaluasiKenaikanService->resolveKeputusan(
                    $statusKelulusan,
                    $keputusanInput
                );

                if (!$keputusan) {
                    continue;
                }

                $keputusanManual = $this->evaluasiKenaikanService->isKeputusanManual($statusKelulusan);

                $evalData = [
                    'rata_rata_nilai' => $average,
                    'status_kelulusan' => $statusKelulusan,
                    'status' => $keputusan,
                    'keputusan_manual' => $keputusanManual,
                    'pelatih_id' => $pelatih_id,
                    'tanggal_evaluasi' => now()->toDateString(),
                    'rekap_nilai_harian_id' => null,
                    'rekap_nilai_ujian_id' => null,
                ];

                if ($jenisRekap === 'ujian') {
                    $evalData['rekap_nilai_ujian_id'] = $rekap->id;
                } else {
                    $evalData['rekap_nilai_harian_id'] = $rekap->id;
                }

                EvaluasiTingkat::updateOrCreate(
                    [
                        'siswa_id' => $rekap->siswa_id,
                        'tingkat_id' => $rekap->tingkat_id,
                        'tahun_periode' => $rekap->tahun_periode,
                    ],
                    $evalData
                );

                $this->evaluasiKenaikanService->applyKeputusan($rekap, $tingkat, $keputusan);

                $rekap->update(['evaluasi_selesai' => true]);
                $request->session()->forget($this->draftSessionKey($jenisRekap, $rekap->id));
                $saved++;
            }
        });

        if ($saved === 0) {
            return redirect()
                ->route('pelatih.evaluasi-kenaikan-tingkat', $request->only(['tingkat_id', 'tahun_periode_id', 'jenis_penilaian']))
                ->with('error', 'Tidak ada evaluasi tersimpan. Pastikan siswa toleransi sudah ditetapkan keputusannya.');
        }

        return redirect()
            ->route('pelatih.evaluasi-kenaikan-tingkat', $request->only(['tingkat_id', 'tahun_periode_id', 'jenis_penilaian']))
            ->with('success', "{$saved} evaluasi kenaikan tingkat berhasil disimpan.");
    }

    private function authPelatihProfile(): Pelatih
    {
        $pelatih = Auth::user()->pelatihProfile?->load('tingkats');

        if (!$pelatih) {
            abort(403, 'Akses pelatih diperlukan.');
        }

        return $pelatih;
    }

    private function findRekapMenunggu(string $jenisRekap, int $rekapId): RekapNilaiHarian|RekapNilaiUjian|null
    {
        if ($jenisRekap === 'ujian') {
            $rekap = RekapNilaiUjian::with(['tingkat', 'siswa'])->find($rekapId);
            $statusSiap = RekapNilaiUjian::STATUS_SIAP_EVALUASI;
        } else {
            $rekap = RekapNilaiHarian::with(['tingkat', 'siswa'])->find($rekapId);
            $statusSiap = RekapNilaiHarian::STATUS_SIAP_EVALUASI;
        }

        if (!$rekap || $rekap->evaluasi_selesai || $rekap->status !== $statusSiap) {
            return null;
        }

        return $rekap;
    }

    private function mapBarisMenungguEvaluasi(RekapNilaiHarian|RekapNilaiUjian $rekap, string $jenisRekap, Request $request): array
    {
        $tingkat = $rekap->tingkat;
        $average = (float) $rekap->average;
        $statusKelulusan = $tingkat->klasifikasiKelulusan($average);
        $sessionDraft = $request->session()->get($this->draftSessionKey($jenisRekap, $rekap->id));
        $draftKeputusan = $this->evaluasiKenaikanService->resolveKeputusan($statusKelulusan, $sessionDraft);

        $evaluasiSebelumnya = EvaluasiTingkat::where('siswa_id', $rekap->siswa_id)
            ->where('tingkat_id', $rekap->tingkat_id)
            ->where('tahun_periode', $rekap->tahun_periode)
            ->first();

        return [
            'rekap' => $rekap,
            'jenis_rekap' => $jenisRekap,
            'is_evaluasi_ulang' => $evaluasiSebelumnya?->status === EvaluasiTingkat::STATUS_TIDAK_NAIK,
            'nama' => $rekap->user?->name ?? $rekap->siswa?->nama_lengkap ?? '-',
            'tingkat_nama' => $tingkat?->nama_tingkat ?? '-',
            'tingkat_saat_ini' => $rekap->siswa?->tingkat?->nama_tingkat ?? $tingkat?->nama_tingkat ?? '-',
            'nilai_akhir' => $average,
            'status_kelulusan' => $statusKelulusan,
            'status_kelulusan_label' => $tingkat->labelKelulusan($statusKelulusan),
            'keputusan' => $draftKeputusan,
            'keputusan_label' => $this->evaluasiKenaikanService->labelKeputusan(
                $draftKeputusan,
                $statusKelulusan,
                $tingkat
            ),
            'perlu_tetapkan' => $statusKelulusan === Tingkat::KELULUSAN_TOLERANSI && !$draftKeputusan,
            'tanggal_evaluasi' => null,
        ];
    }

    private function mapBarisEvaluasiTersimpan(EvaluasiTingkat $evaluasi): array
    {
        $tingkat = $evaluasi->tingkat;
        $statusKelulusan = $evaluasi->status_kelulusan;

        return [
            'evaluasi' => $evaluasi,
            'nama' => $evaluasi->siswa?->user?->name ?? $evaluasi->siswa?->nama_lengkap ?? '-',
            'tingkat_nama' => $tingkat?->nama_tingkat ?? '-',
            'tingkat_saat_ini' => $evaluasi->siswa?->tingkat?->nama_tingkat ?? '-',
            'nilai_akhir' => (float) $evaluasi->rata_rata_nilai,
            'status_kelulusan' => $statusKelulusan,
            'status_kelulusan_label' => $tingkat
                ? $tingkat->labelKelulusan($statusKelulusan)
                : ucfirst(str_replace('_', ' ', $statusKelulusan)),
            'keputusan' => $evaluasi->status,
            'keputusan_label' => $this->evaluasiKenaikanService->labelKeputusan(
                $evaluasi->status,
                $statusKelulusan,
                $tingkat
            ),
            'perlu_tetapkan' => false,
            'tanggal_evaluasi' => $evaluasi->tanggal_evaluasi?->format('d/m/Y'),
            'keputusan_manual' => $evaluasi->keputusan_manual,
        ];
    }

    private function normalizePenilaianScore(mixed $value): float
    {
        $score = round((float) $value, 2);

        return (float) min(100, max(0, $score));
    }

    private function draftSessionKey(string $jenisRekap, int $rekapId): string
    {
        return "evaluasi_draft_{$jenisRekap}_{$rekapId}";
    }

    public function showSiswa(User $siswa)
    {
        if ($siswa->role !== 'siswa') {
            abort(404);
        }

        $pelatih = $this->authPelatihProfile();
        $siswa->load(['pendaftaran.tingkat', 'siswaProfile.tingkat']);

        $tingkatId = $siswa->siswaProfile?->tingkat_id;
        if ($tingkatId && !$this->tingkatAccessService->canAccessTingkat($pelatih, (int) $tingkatId)) {
            abort(403, 'Anda tidak memiliki akses ke siswa ini.');
        }

        return view('pelatih.siswa.show', compact('siswa'));
    }

    public function pengajuanMengulang()
    {
        $pelatih = $this->authPelatihProfile();

        $pendingPengajuan = $this->pengajuanMengulangService->pendingForPelatih($pelatih);
        $historyPengajuan = $this->pengajuanMengulangService->historyForPelatih($pelatih);

        return view('pelatih.pengajuan-mengulang', compact(
            'pendingPengajuan',
            'historyPengajuan'
        ));
    }

    public function setujuiPengajuanMengulang(Request $request, PengajuanMengulangTingkat $pengajuan)
    {
        $pelatih = $this->authPelatihProfile();

        if (!$this->tingkatAccessService->canAccessTingkat($pelatih, (int) $pengajuan->tingkat_id)) {
            abort(403, 'Anda tidak memiliki akses ke tingkat pengajuan ini.');
        }

        $validated = $request->validate([
            'catatan_pelatih' => 'nullable|string|max:1000',
        ]);

        try {
            $this->pengajuanMengulangService->approve(
                $pengajuan,
                $pelatih,
                $validated['catatan_pelatih'] ?? null
            );
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('pelatih.pengajuan-mengulang')
            ->with('success', 'Pengajuan mengulang tingkat berhasil disetujui. Siswa telah dipindahkan ke tingkat yang diajukan.');
    }

    public function tolakPengajuanMengulang(Request $request, PengajuanMengulangTingkat $pengajuan)
    {
        $pelatih = $this->authPelatihProfile();

        if (!$this->tingkatAccessService->canAccessTingkat($pelatih, (int) $pengajuan->tingkat_id)) {
            abort(403, 'Anda tidak memiliki akses ke tingkat pengajuan ini.');
        }

        $validated = $request->validate([
            'catatan_pelatih' => 'nullable|string|max:1000',
        ]);

        try {
            $this->pengajuanMengulangService->reject(
                $pengajuan,
                $pelatih,
                $validated['catatan_pelatih'] ?? null
            );
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('pelatih.pengajuan-mengulang')
            ->with('success', 'Pengajuan mengulang tingkat telah ditolak.');
    }
}
