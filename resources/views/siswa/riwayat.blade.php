@extends('layout.main')

@section('page_title', 'Riwayat Tingkat')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Riwayat Kenaikan Tingkat</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('siswa.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Riwayat Tingkat</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-history mr-1"></i> Riwayat Perjalanan Tingkat</h3>
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
                @include('siswa.partials.tabel-riwayat')
            </div>
            @if($riwayatRows->isNotEmpty())
            <div class="card-footer text-muted small">
                Riwayat naik dicatat saat lulus dan naik tingkat. Riwayat mengulang dicatat saat siswa tidak lulus dan harus mengulangi tingkat yang sama.
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
