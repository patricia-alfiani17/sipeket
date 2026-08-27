@extends('layout.main')

@section('page_title', 'Data Tingkat')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Data Tingkat</h4>
                </div>
                <div class="card-body">
                    <p>Halaman untuk melihat data tingkat.</p>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection