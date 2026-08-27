@extends('layout.main')

@section('page_title', 'Riwayat Kenaikan Tingkat')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Riwayat Kenaikan Tingkat</h4>
                </div>
                <div class="card-body">
                    <p>Halaman riwayat kenaikan tingkat. Tambahkan daftar riwayat dan opsi cetak di sini.</p>
                    <a href="{{ route('admin.laporan') }}" class="btn btn-secondary">Kembali ke Laporan</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection