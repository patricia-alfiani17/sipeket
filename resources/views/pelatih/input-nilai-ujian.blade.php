@extends('layout.main')

@section('page_title', 'Input Nilai Ujian')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Input Nilai Ujian</h1>
                <p class="text-muted mb-0 mt-1" style="font-size: 13px;">
                    Penilaian Akhir Oleh Penguji
                    @if($selectedTahunPeriode)
                    — Tahun Ajaran {{ $selectedTahunPeriode->periode }}
                    @endif
                </p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('pelatih.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Input Nilai Ujian</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
        @endif

        <div class="card card-outline card-primary mb-3">
            <div class="card-body">
                <p class="text-muted mb-3" style="font-size: 13px;">Penilaian tingkat ujian dengan multi materi latihan — materi terakhir dinilai oleh 3 penguji, materi lainnya dinilai oleh 1 pelatih.</p>
                @if($tingkats->isEmpty())
                <div class="alert alert-warning">
                    Belum ada data tingkat dengan jenis penilaian ujian. Hubungi admin untuk menambahkan tingkat.
                </div>
                @endif
                <form method="GET" action="{{ route('pelatih.input-nilai-ujian') }}" id="formFilterUjian">
                    <div class="row align-items-end">
                        <div class="col-md-3 mb-3">
                            <label for="tingkat_id">Tingkat</label>
                            <select class="form-control" id="tingkat_id" name="tingkat_id" required {{ ($isApplied || $tingkats->isEmpty()) ? 'disabled' : '' }}>
                                <option value="">- Pilih Tingkat -</option>
                                @foreach($tingkats as $tingkat)
                                <option value="{{ $tingkat->id }}" {{ (string) $tingkat_id === (string) $tingkat->id ? 'selected' : '' }}>
                                    {{ $tingkat->nama_tingkat }}
                                </option>
                                @endforeach
                            </select>
                            @if($isApplied)
                                <input type="hidden" name="tingkat_id" value="{{ $tingkat_id }}">
                            @endif
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="tahun_periode_id">Periode</label>
                            <select class="form-control" id="tahun_periode_id" name="tahun_periode_id" {{ $isApplied ? 'disabled' : '' }}>
                                <option value="">- Pilih Periode -</option>
                                @foreach($tahunPeriodes as $periode)
                                <option value="{{ $periode->id }}" {{ (string) $tahun_periode_id === (string) $periode->id ? 'selected' : '' }}>
                                    {{ $periode->periode }}{{ $periode->is_default ? ' (Default)' : '' }}
                                </option>
                                @endforeach
                            </select>
                            @if($isApplied)
                                <input type="hidden" name="tahun_periode_id" value="{{ $tahun_periode_id }}">
                            @endif
                        </div>
                        @if($canSelectMateri)
                        <div class="col-md-3 mb-3" id="materiLatihanWrapper">
                            <label for="materi_latihan_id">Materi Latihan</label>
                            <select class="form-control" id="materi_latihan_id" name="materi_latihan_id" required {{ $isApplied ? 'disabled' : '' }}>
                                <option value="">- Pilih Materi Latihan -</option>
                                @foreach($materiLatihans as $materi)
                                <option value="{{ $materi->id }}" {{ (string) $materi_latihan_id === (string) $materi->id ? 'selected' : '' }}>
                                    {{ $materi->nama }} {{ $loop->last ? '(Ujian Utama)' : '(Latihan)' }}
                                </option>
                                @endforeach
                            </select>
                            @if($isApplied)
                                <input type="hidden" name="materi_latihan_id" value="{{ $materi_latihan_id }}">
                            @endif
                        </div>
                        @endif
                        @if($isApplied && $isLastMateri)
                        <div class="col-md-3 mb-3">
                            <label for="user_id">Nama Siswa</label>
                            <select class="form-control" id="user_id" name="user_id" {{ $user_id ? 'disabled' : '' }}>
                                <option value="">- Pilih Siswa -</option>
                                @foreach($siswas as $siswa)
                                <option value="{{ $siswa->id }}" {{ (string) $user_id === (string) $siswa->id ? 'selected' : '' }}>
                                    {{ $siswa->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="col-md-12 mb-3">
                            <button type="submit" class="btn btn-primary" {{ ($isApplied && (!$isLastMateri || $user_id)) ? 'disabled' : '' }}>Terapkan</button>
                            <a href="{{ route('pelatih.input-nilai-ujian') }}" class="btn btn-secondary ml-2">Reset</a>
                        </div>
                    </div>
                    @if($isApplied)
                        @if($isLastMateri && !$user_id)
                        <div class="alert alert-info mt-2 mb-0">
                            Pilihan diterapkan. Silakan pilih nama siswa untuk memulai penilaian ujian oleh 3 penguji.
                        </div>
                        @else
                        <div class="alert alert-success mt-2 mb-0">
                            Pilihan diterapkan. Isi penilaian lalu klik Simpan.
                        </div>
                        @endif
                    @elseif($canSelectMateri)
                    <div class="alert alert-info mt-2 mb-0">
                        Pilih materi latihan, lalu klik Terapkan.
                    </div>
                    @elseif($tingkat_id)
                    <div class="alert alert-info mt-2 mb-0">
                        Pilih periode terlebih dahulu.
                    </div>
                    @endif
                </form>
            </div>
        </div>

        {{-- Form Penilaian Ujian Utama (3 Penguji) --}}
        @if($isApplied && $isLastMateri && $selectedUser)
        <form method="POST" action="{{ route('pelatih.nilai-ujian.store') }}">
            @csrf
            <input type="hidden" name="tingkat_id" value="{{ $tingkat_id }}">
            <input type="hidden" name="tahun_periode_id" value="{{ $tahun_periode_id }}">
            <input type="hidden" name="materi_latihan_id" value="{{ $materi_latihan_id }}">
            <input type="hidden" name="user_id" value="{{ $user_id }}">

            <p class="text-muted small mb-3">Skor wiraga, wirama, dan wirasa setiap penguji: <strong>0,00 – 100,00</strong> (maks. 2 desimal).</p>
            <div class="row">
                @for($n = 1; $n <= 3; $n++)
                <div class="col-md-4 mb-3">
                    <div class="card card-outline card-secondary h-100">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Penilaian Penguji {{ $n }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Wiraga</label>
                                <input type="number" class="form-control penguji-input penilaian-score-input" name="penguji[{{ $n }}][wiraga]"
                                    data-penguji="{{ $n }}" min="0" max="100" step="0.01" inputmode="decimal"
                                    title="Nilai 0,00 – 100,00 (maks. 2 desimal)"
                                    value="{{ old('penguji.'.$n.'.wiraga', number_format((float) ($pengujiScores[$n]['wiraga'] ?? 0), 2, '.', '')) }}" required>
                            </div>
                            <div class="form-group">
                                <label>Wirama</label>
                                <input type="number" class="form-control penguji-input penilaian-score-input" name="penguji[{{ $n }}][wirama]"
                                    data-penguji="{{ $n }}" min="0" max="100" step="0.01" inputmode="decimal"
                                    title="Nilai 0,00 – 100,00 (maks. 2 desimal)"
                                    value="{{ old('penguji.'.$n.'.wirama', number_format((float) ($pengujiScores[$n]['wirama'] ?? 0), 2, '.', '')) }}" required>
                            </div>
                            <div class="form-group mb-0">
                                <label>Wirasa</label>
                                <input type="number" class="form-control penguji-input penilaian-score-input" name="penguji[{{ $n }}][wirasa]"
                                    data-penguji="{{ $n }}" min="0" max="100" step="0.01" inputmode="decimal"
                                    title="Nilai 0,00 – 100,00 (maks. 2 desimal)"
                                    value="{{ old('penguji.'.$n.'.wirasa', number_format((float) ($pengujiScores[$n]['wirasa'] ?? 0), 2, '.', '')) }}" required>
                            </div>
                            <p class="text-muted small mt-2 mb-0">
                                Rata-rata: <strong id="rataPenguji{{ $n }}">{{ number_format($pengujiScores[$n]['rata'] ?? 0, 2) }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
                @endfor
            </div>

            <div class="card card-outline card-info mb-3">
                <div class="card-header">
                    <h3 class="card-title mb-0">Rekapitulasi Hasil Ujian Utama</h3>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Rata-rata Penguji 1</p>
                            <h4 id="rekapPenguji1">{{ number_format($pengujiScores[1]['rata'] ?? 0, 2) }}</h4>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Rata-rata Penguji 2</p>
                            <h4 id="rekapPenguji2">{{ number_format($pengujiScores[2]['rata'] ?? 0, 2) }}</h4>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Rata-rata Penguji 3</p>
                            <h4 id="rekapPenguji3">{{ number_format($pengujiScores[3]['rata'] ?? 0, 2) }}</h4>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Nilai Akhir Ujian Utama</p>
                            <h4 class="text-primary" id="nilaiFixMateri">0.00</h4>
                            <small class="text-muted">Rata-rata ketiga penguji</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
        @endif

        {{-- Form Penilaian Materi Latihan Ujian (1 Pelatih - Seperti Harian) --}}
        @if($isApplied && !$isLastMateri && count($siswas) > 0)
        <div class="card mb-3">
            <div class="card-body p-0">
                <p class="text-muted small px-3 pt-3 mb-0">Skor wiraga, wirama, dan wirasa: <strong>0,00 – 100,00</strong> (maks. 2 desimal).</p>
                <form id="formNilaiHarianUjian" action="{{ route('pelatih.nilai-ujian.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tingkat_id" value="{{ $tingkat_id }}">
                    <input type="hidden" name="tahun_periode_id" value="{{ $tahun_periode_id }}">
                    <input type="hidden" name="materi_latihan_id" value="{{ $materi_latihan_id }}">
                    <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Wiraga</th>
                                <th>Wirasa</th>
                                <th>Wirama</th>
                                <th>Rata-Rata</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswas as $siswa)
                            @php
                                $nilaiHarian = $nilaiHarians->where('user_id', $siswa->id)->first();
                                $wiraga = $nilaiHarian?->wiraga ?? 0;
                                $wirasa = $nilaiHarian?->wirasa ?? 0;
                                $wirama = $nilaiHarian?->wirama ?? 0;
                                $rata = ($wiraga + $wirasa + $wirama) / 3;
                            @endphp
                            <tr id="row-{{ $siswa->id }}">
                                <td>{{ $siswa->name }}</td>
                                <td class="nilai-cell">
                                    <span class="nilai-text">{{ number_format((float) $wiraga, 2) }}</span>
                                    <input type="number" class="form-control form-control-sm nilai-input penilaian-score-input d-none"
                                        name="wiraga[{{ $siswa->id }}]" value="{{ number_format((float) $wiraga, 2, '.', '') }}"
                                        min="0" max="100" step="0.01" inputmode="decimal"
                                        title="Nilai 0,00 – 100,00 (maks. 2 desimal)">
                                </td>
                                <td class="nilai-cell">
                                    <span class="nilai-text">{{ number_format((float) $wirasa, 2) }}</span>
                                    <input type="number" class="form-control form-control-sm nilai-input penilaian-score-input d-none"
                                        name="wirasa[{{ $siswa->id }}]" value="{{ number_format((float) $wirasa, 2, '.', '') }}"
                                        min="0" max="100" step="0.01" inputmode="decimal"
                                        title="Nilai 0,00 – 100,00 (maks. 2 desimal)">
                                </td>
                                <td class="nilai-cell">
                                    <span class="nilai-text">{{ number_format((float) $wirama, 2) }}</span>
                                    <input type="number" class="form-control form-control-sm nilai-input penilaian-score-input d-none"
                                        name="wirama[{{ $siswa->id }}]" value="{{ number_format((float) $wirama, 2, '.', '') }}"
                                        min="0" max="100" step="0.01" inputmode="decimal"
                                        title="Nilai 0,00 – 100,00 (maks. 2 desimal)">
                                </td>
                                <td class="rata-rata">{{ number_format($rata, 1) }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning btn-edit" data-row="{{ $siswa->id }}">Edit</button>
                                    <button type="button" class="btn btn-sm btn-success btn-save d-none" data-row="{{ $siswa->id }}">Simpan</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    <div class="d-flex justify-content-end p-2">
                        <button type="submit" class="btn btn-primary">Simpan Semua</button>
                    </div>
                </form>
            </div>
        </div>
        @elseif($isApplied && !$isLastMateri && count($siswas) === 0)
        <div class="alert alert-info">
            Tidak ada siswa untuk tingkat yang dipilih.
        </div>
        @endif

        {{-- Tabel Rekap Nilai Ujian --}}
        <h5 class="mb-2">Rekap Nilai Ujian</h5>
        <div class="card mb-3">
            <div class="card-body p-0">
                @if($showRekap && $rekapNilai->isNotEmpty())
                <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Nama Siswa</th>
                            @foreach($materiLatihans as $materi)
                            <th>{{ $materi->nama }}</th>
                            @endforeach
                            <th>Nilai Akhir</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekapNilai as $row)
                        <tr>
                            <td>{{ $row['siswa']->name }}</td>
                            @foreach($materiLatihans as $materi)
                            <td>
                                @if(!is_null($row['nilaiPerMateri'][$materi->nama] ?? null))
                                    {{ number_format($row['nilaiPerMateri'][$materi->nama], 1) }}
                                @else
                                    -
                                @endif
                            </td>
                            @endforeach
                            <td>
                                @if(!is_null($row['average']))
                                    {{ number_format($row['average'], 1) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($row['status'] === \App\Models\RekapNilaiUjian::STATUS_SIAP_EVALUASI)
                                <span class="badge badge-success">{{ $row['status'] }}</span>
                                @else
                                <span class="badge badge-secondary">{{ $row['status'] }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @elseif($showRekap)
                </div>
                <div class="p-3">
                    <p class="mb-0">Belum ada data nilai ujian untuk tingkat dan periode ini.</p>
                </div>
                @else
                <div class="p-3">
                    <p class="mb-0">Pilih tingkat dan periode untuk melihat rekap.</p>
                </div>
                @endif
                <div class="p-3 border-top bg-light">
                    <p class="mb-0 text-muted small">
                        Siswa <strong>Siap Evaluasi</strong> otomatis muncul di
                        <a href="{{ route('pelatih.evaluasi-kenaikan-tingkat', ['jenis_penilaian' => 'ujian']) }}">Evaluasi Kenaikan Tingkat</a> (filter jenis ujian).
                        Siswa yang <strong>mengulang tingkat</strong> dapat dinilai ulang; setelah siap evaluasi, simpan evaluasi ulang di halaman tersebut.
                    </p>
                </div>
            </div>
        </div>

    </div>
</section>

<div id="inputNilaiUjianData" data-is-applied="{{ $isApplied ? '1' : '0' }}" data-is-last-materi="{{ $isLastMateri ? '1' : '0' }}" data-user-id="{{ $user_id ? '1' : '0' }}" style="display:none"></div>
@endsection

@section('scripts')
@include('pelatih.partials.penilaian-score-script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dataEl = document.getElementById('inputNilaiUjianData');
        const isApplied = dataEl?.dataset?.isApplied === '1';
        const isLastMateri = dataEl?.dataset?.isLastMateri === '1';
        const hasUserId = dataEl?.dataset?.userId === '1';

        const filterForm = document.getElementById('formFilterUjian');

        if (filterForm) {
            // Auto submit filter form on change if filters are not fully applied
            ['tingkat_id', 'tahun_periode_id', 'materi_latihan_id'].forEach(function (id) {
                const el = document.getElementById(id);
                if (el && !isApplied) {
                    el.addEventListener('change', function () {
                        // Reset downstream selects if parent changes
                        if (id === 'tingkat_id') {
                            const matSelect = document.getElementById('materi_latihan_id');
                            if (matSelect) matSelect.value = '';
                        }
                        filterForm.submit();
                    });
                }
            });

            // Student selection auto-submit
            const userSelect = document.getElementById('user_id');
            if (userSelect && !hasUserId) {
                userSelect.addEventListener('change', function () {
                    if (userSelect.value) {
                        filterForm.submit();
                    }
                });
            }
        }

        // Logic for 3 Examiners (Last Material)
        if (isLastMateri) {
            function hitungRata(w, r, s) {
                const clamp = window.SipeketPenilaian ? window.SipeketPenilaian.clamp : (v) => parseFloat(v) || 0;
                return (clamp(w) + clamp(r) + clamp(s)) / 3;
            }

            function updateRekapitulasi() {
                const ratas = [];
                for (let n = 1; n <= 3; n++) {
                    const byName = (part) => {
                        const el = document.querySelector('[name="penguji[' + n + '][' + part + ']"]');
                        return el ? el.value : 0;
                    };
                    const w = byName('wiraga');
                    const r = byName('wirama');
                    const s = byName('wirasa');
                    const rata = hitungRata(w, r, s);
                    ratas.push(rata);
                    const elRata = document.getElementById('rataPenguji' + n);
                    const elRekap = document.getElementById('rekapPenguji' + n);
                    if (elRata) elRata.textContent = rata.toFixed(2);
                    if (elRekap) elRekap.textContent = rata.toFixed(2);
                }
                const nilaiFix = ratas.length === 3
                    ? (ratas.reduce((a, b) => a + b, 0) / 3)
                    : 0;
                const elFix = document.getElementById('nilaiFixMateri');
                if (elFix) elFix.textContent = nilaiFix.toFixed(2);
            }

            document.querySelectorAll('.penguji-input').forEach(function (input) {
                input.addEventListener('input', updateRekapitulasi);
            });
            updateRekapitulasi();
        }

        // Logic for Daily-like inline grading (Non-Last Material)
        if (!isLastMateri && isApplied) {
            // Tombol Edit per baris
            document.querySelectorAll('.btn-edit').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const row = this.dataset.row;
                    const tr = document.getElementById('row-' + row);

                    tr.querySelectorAll('.nilai-text').forEach(el => el.classList.add('d-none'));
                    tr.querySelectorAll('.nilai-input').forEach(el => el.classList.remove('d-none'));
                    if (window.SipeketPenilaian) {
                        window.SipeketPenilaian.bind(tr);
                    }

                    tr.querySelector('.btn-edit').classList.add('d-none');
                    tr.querySelector('.btn-save').classList.remove('d-none');
                });
            });

            // Tombol Simpan per baris (inline save)
            document.querySelectorAll('.btn-save').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const row = this.dataset.row;
                    const tr = document.getElementById('row-' + row);

                    const inputs = tr.querySelectorAll('.nilai-input');
                    let sum = 0;
                    inputs.forEach(function(input) {
                        const val = window.SipeketPenilaian
                            ? window.SipeketPenilaian.clamp(input.value)
                            : (parseFloat(input.value) || 0);
                        input.value = window.SipeketPenilaian
                            ? window.SipeketPenilaian.format(val)
                            : val.toFixed(2);
                        sum += val;
                        input.previousElementSibling.textContent = input.value;
                    });

                    const rata = (sum / inputs.length).toFixed(2);
                    tr.querySelector('.rata-rata').textContent = rata;

                    tr.querySelectorAll('.nilai-text').forEach(el => el.classList.remove('d-none'));
                    tr.querySelectorAll('.nilai-input').forEach(el => el.classList.add('d-none'));

                    tr.querySelector('.btn-edit').classList.remove('d-none');
                    tr.querySelector('.btn-save').classList.add('d-none');
                });
            });
        }
    });
</script>
@endsection
