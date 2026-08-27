@extends('layout.main')

@section('page_title', 'Laporan')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Laporan</h4>
                    <p class="mb-0">Unduh laporan dalam format Excel.</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-primary">
                                <div class="card-body">
                                    <h5 class="card-title">Cetak Data Siswa</h5>
                                    <p class="card-text">Unduh ringkasan data semua siswa dalam format Excel.</p>
                                    <a href="{{ route('admin.laporan.export.siswa') }}" class="btn btn-primary">
                                        <i class="fas fa-file-excel"></i> Download Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-success">
                                <div class="card-body">
                                    <h5 class="card-title">Cetak Hasil Evaluasi</h5>
                                    <p class="card-text">Unduh ringkasan hasil evaluasi kenaikan tingkat semua siswa.</p>
                                    <a href="{{ route('admin.laporan.export.evaluasi') }}" class="btn btn-success">
                                        <i class="fas fa-file-excel"></i> Download Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-info">
                                <div class="card-body">
                                    <h5 class="card-title">Riwayat Kenaikan Tingkat</h5>
                                    <p class="card-text">Unduh riwayat kenaikan dan pengulangan tingkat semua siswa.</p>
                                    <a href="{{ route('admin.laporan.export.riwayat') }}" class="btn btn-info">
                                        <i class="fas fa-file-excel"></i> Download Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
