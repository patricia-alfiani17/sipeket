@extends('layout.main')

@section('page_title', 'Profil Saya')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Profil Saya</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('siswa.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Profil Saya</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center mb-3">
                            <span class="profile-user-img img-fluid img-circle d-inline-flex align-items-center justify-content-center bg-light"
                                style="width:100px;height:100px;font-size:2.5rem;color:#6c757d;">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                        <h3 class="profile-username text-center">{{ $user->name }}</h3>
                        <p class="text-muted text-center">{{ $user->username }}</p>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Tingkat</b>
                                <span class="float-right">{{ $tingkatSaatIni }}</span>
                            </li>
                            <li class="list-group-item">
                                <b>Status</b>
                                <span class="float-right">
                                    <span class="badge badge-{{ $user->status === 'aktif' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </span>
                            </li>
                            <li class="list-group-item">
                                <b>Email</b>
                                <span class="float-right text-break">{{ $user->email }}</span>
                            </li>
                        </ul>
                        <a href="{{ route('profil') }}" class="btn btn-outline-primary btn-block">
                            <i class="fas fa-key mr-1"></i> Ubah Password
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-user mr-1"></i> Data Profil Siswa</h3>
                        <div class="card-tools">
                            <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Dashboard
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('siswa.partials.profil-detail')
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
