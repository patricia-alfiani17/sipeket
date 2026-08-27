@extends('layout.main')

@php
    $homeRoute = match(auth()->user()->role) {
        'admin' => route('admin.dashboard'),
        'pelatih' => route('pelatih.dashboard'),
        default => route('siswa.dashboard'),
    };
@endphp

@section('page_title', 'Ubah Password')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Ubah Password</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ $homeRoute }}">Home</a></li>
                    @if(auth()->user()->role === 'siswa')
                        <li class="breadcrumb-item"><a href="{{ route('siswa.profil') }}">Profil Saya</a></li>
                    @endif
                    <li class="breadcrumb-item active">Ubah Password</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Ganti Password</h3>
                        @if(auth()->user()->role === 'siswa')
                        <div class="card-tools">
                            <a href="{{ route('siswa.profil') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali ke Profil
                            </a>
                        </div>
                        @endif
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('profil.password') }}">
                            @csrf

                            <div class="form-group">
                                <label for="old_password">Password Lama</label>
                                <input id="old_password" name="old_password" type="password" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="new_password">Password Baru</label>
                                <input id="new_password" name="new_password" type="password" class="form-control" required minlength="6">
                            </div>

                            <div class="form-group">
                                <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                                <input id="new_password_confirmation" name="new_password_confirmation" type="password" class="form-control" required minlength="6">
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-key"></i> Simpan Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
