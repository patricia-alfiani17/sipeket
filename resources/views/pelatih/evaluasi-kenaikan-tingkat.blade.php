@extends('layout.main')

@section('page_title', 'Evaluasi Kenaikan Tingkat')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Evaluasi Kenaikan Tingkat</h1>
                <p class="text-muted mb-0 mt-1" style="font-size: 13px;">Tetapkan keputusan kenaikan tingkat berdasarkan penilaian siswa</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('pelatih.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Evaluasi Kenaikan Tingkat</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="alert alert-secondary small mb-3">
            <strong>Aturan kenaikan tingkat:</strong>
            Nilai &le; ambang tidak lulus → siswa <strong>mengulang tingkat yang sama</strong>.
            Nilai lulus (≥ KKM) → <strong>naik</strong> ke tingkat berikutnya (berurutan hingga tingkat tertinggi).
            Nilai toleransi → pelatih menetapkan naik atau mengulang.
            Siswa yang <strong>mengulang</strong> dapat dinilai ulang di Input Nilai; setelah status <strong>Siap Evaluasi</strong> kembali,
            simpan evaluasi lagi di sini (data evaluasi periode yang sama akan diperbarui).
        </div>

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
                <form method="GET" action="{{ route('pelatih.evaluasi-kenaikan-tingkat') }}" id="formFilterEvaluasi">
                    <div class="row align-items-end">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label for="tingkat_id">Filter Tingkat</label>
                            <select class="form-control" id="tingkat_id" name="tingkat_id">
                                <option value="">Semua Tingkat</option>
                                @foreach($tingkats as $tingkat)
                                <option value="{{ $tingkat->id }}" {{ (string) $tingkat_id === (string) $tingkat->id ? 'selected' : '' }}>
                                    {{ $tingkat->nama_tingkat }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label for="tahun_periode_id">Filter Tahun Periode</label>
                            <select class="form-control" id="tahun_periode_id" name="tahun_periode_id">
                                <option value="">Semua Periode</option>
                                @foreach($tahunPeriodes as $periode)
                                <option value="{{ $periode->id }}" {{ (string) $tahun_periode_id === (string) $periode->id ? 'selected' : '' }}>
                                    {{ $periode->periode }}{{ $periode->is_default ? ' (Default)' : '' }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label for="jenis_penilaian">Jenis Penilaian</label>
                            <select class="form-control" id="jenis_penilaian" name="jenis_penilaian">
                                <option value="">Semua Jenis</option>
                                <option value="harian" {{ $jenis_penilaian === 'harian' ? 'selected' : '' }}>Harian</option>
                                <option value="ujian" {{ $jenis_penilaian === 'ujian' ? 'selected' : '' }}>Ujian</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-block">Terapkan Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Antrean: belum disimpan --}}
        <form method="POST" action="{{ route('pelatih.evaluasi-kenaikan-tingkat.store') }}">
            @csrf
            <input type="hidden" name="tingkat_id" value="{{ $tingkat_id }}">
            <input type="hidden" name="tahun_periode_id" value="{{ $tahun_periode_id }}">
            <input type="hidden" name="jenis_penilaian" value="{{ $jenis_penilaian }}">

            <div class="card card-outline card-warning mb-4">
                <div class="card-header">
                    <h3 class="card-title mb-0">Menunggu Evaluasi</h3>
                    <span class="badge badge-warning ml-2">{{ $rekapRows->count() }}</span>
                </div>
                <div class="card-body p-0">
                    @if($rekapRows->isEmpty())
                    <div class="p-4 text-center text-muted">
                        Tidak ada siswa yang menunggu evaluasi untuk filter ini.
                    </div>
                    @else
                    @include('pelatih.partials.evaluasi-kenaikan-tabel', [
                        'rows' => $rekapRows,
                        'mode' => 'menunggu',
                    ])
                    @endif
                </div>
                @if($rekapRows->isNotEmpty())
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                @endif
            </div>
        </form>

        {{-- Riwayat: sudah disimpan --}}
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title mb-0">Evaluasi Tersimpan</h3>
                <span class="badge badge-success ml-2">{{ $evaluasiTersimpanRows->count() }}</span>
            </div>
            <div class="card-body p-0">
                @if($evaluasiTersimpanRows->isEmpty())
                <div class="p-4 text-center text-muted">
                    Belum ada data evaluasi tersimpan untuk filter ini.
                </div>
                @else
                @include('pelatih.partials.evaluasi-kenaikan-tabel', [
                    'rows' => $evaluasiTersimpanRows,
                    'mode' => 'tersimpan',
                ])
                @endif
            </div>
        </div>

    </div>
</section>

<div class="modal fade" id="modalTetapkan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('pelatih.evaluasi-kenaikan-tingkat.tetapkan') }}" id="formTetapkan">
                @csrf
                <input type="hidden" name="tingkat_id" value="{{ $tingkat_id }}">
                <input type="hidden" name="tahun_periode_id" value="{{ $tahun_periode_id }}">
                <input type="hidden" name="jenis_penilaian" value="{{ $jenis_penilaian }}">
                <input type="hidden" name="rekap_id" id="tetapkan_rekap_id">
                <input type="hidden" name="jenis_rekap" id="tetapkan_jenis_rekap">

                <div class="modal-header">
                    <h5 class="modal-title">Tetapkan Keputusan</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Siswa: <strong id="tetapkan_nama"></strong></p>
                    <p class="mb-3">Nilai akhir: <strong id="tetapkan_nilai"></strong> (Toleransi)</p>
                    <div class="form-group">
                        <label>Keputusan Kenaikan Tingkat</label>
                        <select class="form-control" name="keputusan" required>
                            <option value="">-- Pilih --</option>
                            <option value="naik">Lulus — Naik ke Tingkat Berikutnya</option>
                            <option value="tidak_naik">Belum Lulus — Mengulang Tingkat Ini</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Keputusan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-tetapkan').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.getElementById('tetapkan_rekap_id').value = btn.dataset.rekapId;
                document.getElementById('tetapkan_jenis_rekap').value = btn.dataset.jenisRekap || 'harian';
                document.getElementById('tetapkan_nama').textContent = btn.dataset.nama;
                document.getElementById('tetapkan_nilai').textContent = btn.dataset.nilai;
            });
        });

        const filterForm = document.getElementById('formFilterEvaluasi');
        if (filterForm) {
            ['tingkat_id', 'tahun_periode_id', 'jenis_penilaian'].forEach(function (id) {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('change', function () {
                        filterForm.submit();
                    });
                }
            });
        }
    });
</script>
@endsection
