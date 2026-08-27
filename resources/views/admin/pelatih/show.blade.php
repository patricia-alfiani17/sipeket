@extends('layout.main')

@section('title', 'Detail Pelatih')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Pelatih</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pelatih.index') }}">Data Pelatih</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Detail Pelatih: {{ $pelatih->name }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.pelatih.edit', $pelatih) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('admin.pelatih.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-3">ID</dt>
                            <dd class="col-sm-9">{{ $pelatih->id }}</dd>

                            <dt class="col-sm-3">Nama Lengkap</dt>
                            <dd class="col-sm-9">{{ $pelatih->name }}</dd>

                            <dt class="col-sm-3">Email</dt>
                            <dd class="col-sm-9">{{ $pelatih->email }}</dd>

                            <dt class="col-sm-3">Username</dt>
                            <dd class="col-sm-9">{{ $pelatih->username }}</dd>

                            <dt class="col-sm-3">Role</dt>
                            <dd class="col-sm-9">{{ ucfirst($pelatih->role) }}</dd>

                            <dt class="col-sm-3">Status</dt>
                            <dd class="col-sm-9">
                                <span class="badge badge-{{ $pelatih->status == 'aktif' ? 'success' : 'danger' }}">
                                    {{ ucfirst($pelatih->status) }}
                                </span>
                            </dd>

                            <dt class="col-sm-3">No HP</dt>
                            <dd class="col-sm-9">{{ $pelatih->pelatihProfile?->no_hp ?: 'Tidak ada no HP' }}</dd>

                            <dt class="col-sm-3">Alamat</dt>
                            <dd class="col-sm-9">{{ $pelatih->pelatihProfile?->alamat ?: 'Tidak ada alamat' }}</dd>

                            <dt class="col-sm-3">Hak Akses Tingkatan</dt>
                            <dd class="col-sm-9">
                                @if($pelatih->pelatihProfile?->tingkats?->isNotEmpty())
                                    @foreach($pelatih->pelatihProfile->tingkats as $tingkat)
                                        <span class="badge badge-info mr-1 mb-1">{{ $tingkat->nama_tingkat }}</span>
                                    @endforeach
                                @else
                                    <span class="badge badge-success">Semua Tingkatan (Akses Penuh)</span>
                                @endif
                            </dd>

                            <dt class="col-sm-3">Dibuat Pada</dt>
                            <dd class="col-sm-9">{{ $pelatih->created_at->format('d M Y H:i') }}</dd>

                            <dt class="col-sm-3">Diupdate Pada</dt>
                            <dd class="col-sm-9">{{ $pelatih->updated_at->format('d M Y H:i') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection