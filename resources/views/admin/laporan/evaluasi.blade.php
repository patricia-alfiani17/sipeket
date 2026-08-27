@extends('layout.main')

@section('page_title', 'Laporan Evaluasi')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Hasil Evaluasi</h4>
                </div>
                <div class="card-body">
                    <p>Halaman laporan hasil evaluasi siswa. Tambahkan filter, tabel, atau fitur cetak di sini.</p>
                    <a href="{{ route('admin.laporan') }}" class="btn btn-secondary">Kembali ke Laporan</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection