@extends('layout.main')

@section('page_title', 'Dashboard Siswa')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard Siswa</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('siswa.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="callout callout-info">
            <h5 class="mb-1">Selamat datang, {{ $user->name }}!</h5>
            <p class="mb-0 text-muted">
                Gunakan menu di samping untuk melihat profil, hasil evaluasi, dan riwayat tingkat Anda.
            </p>
        </div>

        <div class="row">
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-layer-group"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Tingkat Saat Ini</span>
                        <span class="info-box-number text-truncate" style="font-size:1.1rem;">{{ $tingkatSaatIni }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chart-line"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Evaluasi</span>
                        <span class="info-box-number">{{ $totalEvaluasi }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-history"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Riwayat Kenaikan</span>
                        <span class="info-box-number">{{ $totalRiwayat }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if($hasPendingPengajuan)
        <div class="row">
            <div class="col-12">
                <div class="alert alert-warning">
                    Anda memiliki pengajuan mengulang tingkat yang menunggu persetujuan pelatih.
                    <a href="{{ route('siswa.evaluasi') }}" class="alert-link">Lihat di Hasil Evaluasi</a>
                </div>
            </div>
        </div>
        @elseif($isInMandatoryRepeat ?? false)
        <div class="row">
            <div class="col-12">
                <div class="alert alert-warning">
                    <i class="fas fa-lock mr-1"></i>
                    Anda sedang <strong>mengulang wajib</strong> tingkat {{ $tingkatSaatIni }}.
                    Selesaikan evaluasi ulang terlebih dahulu — pengajuan pengulangan sukarela belum tersedia.
                    <a href="{{ route('siswa.evaluasi') }}" class="alert-link">Lihat Hasil Evaluasi</a>
                </div>
            </div>
        </div>
        @endif

        @if($evaluasiTerakhir)
        <div class="row">
            <div class="col-12">
                <div class="alert alert-light border">
                    <strong>Evaluasi terakhir:</strong>
                    {{ $evaluasiTerakhir['tingkat_nama'] }}
                    ({{ $evaluasiTerakhir['tahun_periode'] }}) —
                    Nilai {{ number_format($evaluasiTerakhir['nilai_akhir'], 2) }},
                    keputusan <strong>{{ $evaluasiTerakhir['keputusan_label'] }}</strong>
                    <a href="{{ route('siswa.evaluasi') }}" class="alert-link ml-2">Lihat semua</a>
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-md-4">
                <div class="card card-outline card-primary h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-id-card fa-3x text-primary mb-3"></i>
                        <h5>Profil Saya</h5>
                        <p class="text-muted small">Data pribadi, kontak, dan informasi orang tua.</p>
                        <a href="{{ route('siswa.profil') }}" class="btn btn-primary btn-sm">Buka Halaman</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-outline card-success h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-line fa-3x text-success mb-3"></i>
                        <h5>Hasil Evaluasi</h5>
                        <p class="text-muted small">{{ $totalEvaluasi }} evaluasi kenaikan tingkat tercatat.</p>
                        <a href="{{ route('siswa.evaluasi') }}" class="btn btn-success btn-sm">Buka Halaman</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-outline card-warning h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-history fa-3x text-warning mb-3"></i>
                        <h5>Riwayat Tingkat</h5>
                        <p class="text-muted small">{{ $totalRiwayat }} riwayat kenaikan tingkat.</p>
                        <a href="{{ route('siswa.riwayat') }}" class="btn btn-warning btn-sm">Buka Halaman</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
