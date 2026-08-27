@extends('layout.main')

@section('page_title', 'Data Pelatih')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Data Pelatih</h4>
                </div>
                <div class="card-body">
                    <p>Halaman untuk melihat data pelatih.</p>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection