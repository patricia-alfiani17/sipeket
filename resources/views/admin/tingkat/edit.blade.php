@extends('layout.main')

@section('title', 'Edit Tingkat')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Tingkat</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.tingkat.index') }}">Data Tingkat</a></li>
                    <li class="breadcrumb-item active">Edit</li>
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
                        <h3 class="card-title">Form Edit Tingkat</h3>
                    </div>
                    <form action="{{ route('admin.tingkat.update', $tingkat) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nama_tingkat">Nama Tingkat</label>
                                <input type="text" class="form-control @error('nama_tingkat') is-invalid @enderror" id="nama_tingkat" name="nama_tingkat" value="{{ old('nama_tingkat', $tingkat->nama_tingkat) }}" placeholder="Masukkan nama tingkat" required>
                                @error('nama_tingkat')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="jenis_penilaian">Jenis Penilaian</label>
                                <select class="form-control @error('jenis_penilaian') is-invalid @enderror" id="jenis_penilaian" name="jenis_penilaian" required>
                                    <option value="">Pilih jenis penilaian</option>
                                    <option value="harian" {{ old('jenis_penilaian', $tingkat->jenis_penilaian) == 'harian' ? 'selected' : '' }}>Harian</option>
                                    <option value="ujian" {{ old('jenis_penilaian', $tingkat->jenis_penilaian) == 'ujian' ? 'selected' : '' }}>Ujian</option>
                                </select>
                                @error('jenis_penilaian')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="kkm">KKM (Kriteria Ketuntasan Minimal)</label>
                                <input type="number" class="form-control @error('kkm') is-invalid @enderror" id="kkm" name="kkm" value="{{ old('kkm', $tingkat->kkm) }}" min="0" max="100" placeholder="Masukkan KKM" required>
                                <small class="form-text text-muted">Nilai &ge; KKM dianggap lulus.</small>
                                @error('kkm')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="card card-outline card-secondary mb-3">
                                <div class="card-header">
                                    <h3 class="card-title mb-0">Ambang Penilaian</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="ambang_tidak_lulus">Ambang Tidak Lulus</label>
                                        <input type="number" class="form-control @error('ambang_tidak_lulus') is-invalid @enderror" id="ambang_tidak_lulus" name="ambang_tidak_lulus" value="{{ old('ambang_tidak_lulus', $tingkat->ambang_tidak_lulus) }}" min="0" max="100" required>
                                        <small class="form-text text-muted">Nilai &le; ambang ini dianggap tidak lulus.</small>
                                        @error('ambang_tidak_lulus')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="ambang_pertimbangan_min">Rentang Pertimbangan (Bawah)</label>
                                                <input type="number" class="form-control @error('ambang_pertimbangan_min') is-invalid @enderror" id="ambang_pertimbangan_min" name="ambang_pertimbangan_min" value="{{ old('ambang_pertimbangan_min', $tingkat->ambang_pertimbangan_min) }}" min="0" max="100" required>
                                                @error('ambang_pertimbangan_min')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-0">
                                                <label for="ambang_pertimbangan_max">Rentang Pertimbangan (Atas)</label>
                                                <input type="number" class="form-control @error('ambang_pertimbangan_max') is-invalid @enderror" id="ambang_pertimbangan_max" name="ambang_pertimbangan_max" value="{{ old('ambang_pertimbangan_max', $tingkat->ambang_pertimbangan_max) }}" min="0" max="100" required>
                                                @error('ambang_pertimbangan_max')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Nilai antara batas bawah dan atas (inklusif) masuk kategori pertimbangan.</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="urutan">Urutan</label>
                                <input type="number" class="form-control @error('urutan') is-invalid @enderror" id="urutan" name="urutan" value="{{ old('urutan', $tingkat->urutan) }}" min="1" placeholder="Masukkan urutan" required>
                                @error('urutan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('admin.tingkat.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection