@extends('layout.main')

@section('title', 'Edit Siswa')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Siswa</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.siswa.index') }}">Data Siswa</a></li>
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
                        <h3 class="card-title">Form Edit Siswa</h3>
                    </div>
                    <form action="{{ route('admin.siswa.update', $siswa) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $siswa->name) }}" placeholder="Masukkan nama lengkap" required>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $siswa->email) }}" placeholder="Masukkan email" required>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="nama_panggilan">Nama Panggilan</label>
                                <input type="text" class="form-control @error('nama_panggilan') is-invalid @enderror" id="nama_panggilan" name="nama_panggilan" value="{{ old('nama_panggilan', $siswa->nama_panggilan) }}" placeholder="Masukkan nama panggilan" required>
                                @error('nama_panggilan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="tanggal_lahir">Tanggal Lahir</label>
                                <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('Y-m-d') : '') }}" required>
                                @error('tanggal_lahir')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="tempat_lahir">Tempat Lahir</label>
                                <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}" placeholder="Masukkan tempat lahir" required>
                                @error('tempat_lahir')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="jenis_kelamin">Jenis Kelamin</label>
                                <select class="form-control @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat" required>{{ old('alamat', $siswa->alamat) }}</textarea>
                                @error('alamat')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="no_hp">No HP</label>
                                <div class="input-group">
                                    <span class="input-group-text">+62</span>
                                    <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp', ltrim($siswa->no_hp ?? '', '+62')) }}" placeholder="81234567890" pattern="[0-9]+" maxlength="13" oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                                </div>
                                @error('no_hp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="asal_sekolah">Asal Sekolah</label>
                                <input type="text" class="form-control @error('asal_sekolah') is-invalid @enderror" id="asal_sekolah" name="asal_sekolah" value="{{ old('asal_sekolah', $siswa->asal_sekolah) }}" placeholder="Masukkan asal sekolah" required>
                                @error('asal_sekolah')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="kelas">Kelas</label>
                                <input type="text" class="form-control @error('kelas') is-invalid @enderror" id="kelas" name="kelas" value="{{ old('kelas', $siswa->kelas) }}" placeholder="Contoh: 5 SD / 8 SMP / 11 SMA" required>
                                @error('kelas')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="nama_orangtua">Nama Orang Tua</label>
                                <input type="text" class="form-control @error('nama_orangtua') is-invalid @enderror" id="nama_orangtua" name="nama_orangtua" value="{{ old('nama_orangtua', $siswa->nama_orangtua) }}" placeholder="Masukkan nama orang tua" required>
                                @error('nama_orangtua')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="pekerjaan_orangtua">Pekerjaan Orang Tua</label>
                                <input type="text" class="form-control @error('pekerjaan_orangtua') is-invalid @enderror" id="pekerjaan_orangtua" name="pekerjaan_orangtua" value="{{ old('pekerjaan_orangtua', $siswa->pekerjaan_orangtua) }}" placeholder="Masukkan pekerjaan orang tua" required>
                                @error('pekerjaan_orangtua')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="kontak_orangtua">Kontak Orang Tua</label>
                                <div class="input-group">
                                    <span class="input-group-text">+62</span>
                                    <input type="text" class="form-control @error('kontak_orangtua') is-invalid @enderror" id="kontak_orangtua" name="kontak_orangtua" value="{{ old('kontak_orangtua', ltrim($siswa->kontak_orangtua ?? '', '+62')) }}" placeholder="81234567890" pattern="[0-9]+" maxlength="13" oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                                </div>
                                @error('kontak_orangtua')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="alamat_orangtua">Alamat Orang Tua</label>
                                <textarea class="form-control @error('alamat_orangtua') is-invalid @enderror" id="alamat_orangtua" name="alamat_orangtua" rows="3" placeholder="Masukkan alamat orang tua" required>{{ old('alamat_orangtua', $siswa->alamat_orangtua) }}</textarea>
                                @error('alamat_orangtua')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="tingkat_id">Tingkat</label>
                                <select class="form-control @error('tingkat_id') is-invalid @enderror" id="tingkat_id" name="tingkat_id" required>
                                    <option value="">Pilih Tingkat</option>
                                    @foreach($tingkats as $tingkat)
                                    <option value="{{ $tingkat->id }}" {{ old('tingkat_id', $siswa->pendaftaran ? $siswa->pendaftaran->tingkat_id : '') == $tingkat->id ? 'selected' : '' }}>
                                        {{ $tingkat->nama_tingkat }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('tingkat_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection