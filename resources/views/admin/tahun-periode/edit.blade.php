@extends('layout.main')

@section('title', 'Edit Tahun Periode')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Tahun Periode</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.tahun-periode.index') }}">Data Tahun Periode</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.tahun-periode.update', $tahunPeriode) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="periode">Periode</label>
                        <input type="text" class="form-control @error('periode') is-invalid @enderror" id="periode" name="periode" value="{{ old('periode', $tahunPeriode->periode) }}" required>
                        @error('periode')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1" {{ old('is_default', $tahunPeriode->is_default) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_default">Jadikan default</label>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.tahun-periode.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
