@extends('layout.main')

@section('page_title', 'Hasil Evaluasi')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Hasil Evaluasi Kenaikan Tingkat</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('siswa.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Hasil Evaluasi</li>
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

        @if($pendingPengajuan)
        <div class="alert alert-warning">
            Pengajuan mengulang <strong>{{ $pendingPengajuan->tingkat?->nama_tingkat ?? '-' }}</strong>
            sedang menunggu persetujuan pelatih (diajukan {{ $pendingPengajuan->tanggal_pengajuan->format('d M Y') }}).
        </div>
        @elseif($isInMandatoryRepeat ?? false)
        <div class="alert alert-warning">
            <i class="fas fa-lock mr-1"></i>
            Anda sedang <strong>mengulang wajib</strong> tingkat saat ini setelah keputusan tidak lulus dari pelatih.
            Pengajuan pengulangan sukarela akan tersedia setelah Anda menyelesaikan evaluasi ulang dan naik tingkat.
        </div>
        @elseif(($canSubmitPengajuan['eligible'] ?? false) && ($canSubmitPengajuan['tingkat'] ?? null))
        <div class="alert alert-info">
            Anda dapat mengajukan pengulangan untuk tingkat terakhir yang sudah diselesaikan:
            <strong>{{ $canSubmitPengajuan['tingkat']->nama_tingkat }}</strong>.
            Klik tombol <strong>Ajukan Mengulang</strong> pada baris tingkat tersebut di tabel.
        </div>
        @elseif(!($canSubmitPengajuan['eligible'] ?? false) && ($canSubmitPengajuan['reason'] ?? ''))
        <div class="alert alert-light border text-muted small mb-3">
            {{ $canSubmitPengajuan['reason'] }}
        </div>
        @endif

        <div class="card">
            <div class="card-header border-transparent">
                <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> Daftar Evaluasi</h3>
                <div class="card-tools">
                    <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Dashboard
                    </a>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                @include('siswa.partials.tabel-evaluasi')
            </div>
            @if($evaluasiRows->isNotEmpty())
            <div class="card-footer text-muted small">
                Hasil evaluasi dicatat oleh pelatih setelah penilaian harian atau ujian selesai direkap.
                Pengajuan mengulang hanya tersedia untuk tingkat terakhir yang sudah lulus (naik tingkat).
            </div>
            @endif
        </div>
    </div>
</section>

@if(($canSubmitPengajuan['eligible'] ?? false) && ($canSubmitPengajuan['tingkat'] ?? null) && !$pendingPengajuan)
<div class="modal fade" id="modalAjukanMengulang" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('siswa.pengajuan-mengulang.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ajukan Mengulang Tingkat</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">
                        Anda akan mengajukan pengulangan untuk
                        <strong>{{ $canSubmitPengajuan['tingkat']->nama_tingkat }}</strong>.
                        Pengajuan akan ditinjau oleh pelatih.
                    </p>
                    <div class="form-group mb-0">
                        <label for="alasan">Alasan Pengajuan <span class="text-danger">*</span></label>
                        <textarea name="alasan" id="alasan" rows="4"
                            class="form-control @error('alasan') is-invalid @enderror"
                            placeholder="Jelaskan alasan Anda ingin mengulang tingkat ini (minimal 20 karakter)..."
                            required minlength="20">{{ old('alasan') }}</textarea>
                        @error('alasan')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
@if($errors->has('alasan'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#modalAjukanMengulang').modal('show');
    });
</script>
@endif
@endsection
