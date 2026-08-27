@extends('layout.main')

@section('title', 'Detail Siswa')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Siswa</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('pelatih.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pelatih.data-siswa') }}">Data Siswa</a></li>
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
                        <h3 class="card-title">Detail Siswa: {{ $siswa->name }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('pelatih.data-siswa') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-3">Nama Lengkap</dt>
                            <dd class="col-sm-9">{{ $siswa->name }}</dd>

                            <dt class="col-sm-3">Nama Panggilan</dt>
                            <dd class="col-sm-9">{{ $siswa->nama_panggilan ?: '-' }}</dd>

                            <dt class="col-sm-3">Tempat Lahir</dt>
                            <dd class="col-sm-9">{{ $siswa->tempat_lahir ?: '-' }}</dd>

                            <dt class="col-sm-3">Email</dt>
                            <dd class="col-sm-9">{{ $siswa->email }}</dd>

                            <dt class="col-sm-3">Username</dt>
                            <dd class="col-sm-9">{{ $siswa->username }}</dd>

                            <dt class="col-sm-3">Status</dt>
                            <dd class="col-sm-9">
                                <span class="badge badge-{{ $siswa->status == 'aktif' ? 'success' : 'danger' }}">
                                    {{ ucfirst($siswa->status) }}
                                </span>
                            </dd>

                            <dt class="col-sm-3">Tanggal Lahir</dt>
                            <dd class="col-sm-9">{{ $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d M Y') : '-' }}</dd>

                            <dt class="col-sm-3">Jenis Kelamin</dt>
                            <dd class="col-sm-9">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : ($siswa->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</dd>

                            <dt class="col-sm-3">Alamat</dt>
                            <dd class="col-sm-9">{{ $siswa->alamat ?: 'Tidak ada alamat' }}</dd>

                            <dt class="col-sm-3">No HP</dt>
                            <dd class="col-sm-9">{{ $siswa->no_hp ? '+62' . ltrim(ltrim($siswa->no_hp, '+62'), '0') : 'Tidak ada no HP' }}</dd>

                            <dt class="col-sm-3">Asal Sekolah</dt>
                            <dd class="col-sm-9">{{ $siswa->asal_sekolah ?: 'Tidak ada data' }}</dd>

                            <dt class="col-sm-3">Kelas</dt>
                            <dd class="col-sm-9">{{ $siswa->kelas ?: 'Tidak ada data' }}</dd>

                            <dt class="col-sm-3">Tingkat</dt>
                            <dd class="col-sm-9">{{ $siswa->siswaProfile?->tingkat?->nama_tingkat ?? '-' }}</dd>

                            <dt class="col-sm-3">Nama Orang Tua</dt>
                            <dd class="col-sm-9">{{ $siswa->nama_orangtua ?: 'Tidak ada data' }}</dd>

                            <dt class="col-sm-3">Pekerjaan Orang Tua</dt>
                            <dd class="col-sm-9">{{ $siswa->pekerjaan_orangtua ?: 'Tidak ada data' }}</dd>

                            <dt class="col-sm-3">Kontak Orang Tua</dt>
                            <dd class="col-sm-9">{{ $siswa->kontak_orangtua ? '+62' . ltrim(ltrim($siswa->kontak_orangtua, '+62'), '0') : 'Tidak ada data' }}</dd>

                            <dt class="col-sm-3">Alamat Orang Tua</dt>
                            <dd class="col-sm-9">{{ $siswa->alamat_orangtua ?: 'Tidak ada data' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
