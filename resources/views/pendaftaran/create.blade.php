<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pendaftaran Siswa | Sanggar Tari Dharmo Yuwono</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo1.png') }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }

        .header-bar {
            background-color: #0b3d2e;
            color: white;
            padding: 20px 0;
        }

        .card-custom {
            border: none;
            border-radius: 8px;
        }

        .section-title {
            font-size: 15px;
            font-weight: 600;
            color: #0b3d2e;
            border-bottom: 2px solid #0b3d2e;
            padding-bottom: 6px;
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #0b3d2e;
            border: none;
        }

        .btn-primary:hover {
            background-color: #145c43;
        }

        .btn-outline-secondary {
            border-radius: 20px;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header-bar">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="{{ asset('images/logo1.png') }}" width="50" class="me-3">
                <div>
                    <h5 class="mb-0 fw-bold">Sanggar Tari Dharmo Yuwono</h5>
                    <small>Purwokerto</small>
                </div>
            </div>

            <a href="{{ route('welcome') }}" class="btn btn-light btn-sm">
                ← Kembali ke Beranda
            </a>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                <div class="card shadow-sm card-custom">
                    <div class="card-body p-4">

                        <h4 class="text-center fw-semibold mb-4">
                            Formulir Pendaftaran Calon Siswa
                        </h4>

                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('pendaftaran.store') }}" enctype="multipart/form-data">
                            @csrf

                            <!-- ================= DATA SISWA ================= -->
                            <div class="section-title">A. Data Calon Siswa</div>

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap"
                                        class="form-control @error('nama_lengkap') is-invalid @enderror"
                                        value="{{ old('nama_lengkap') }}">
                                    @error('nama_lengkap')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}">
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Panggilan</label>
                                    <input type="text" name="nama_panggilan"
                                        class="form-control @error('nama_panggilan') is-invalid @enderror"
                                        value="{{ old('nama_panggilan') }}" required>
                                    @error('nama_panggilan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir"
                                        class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                        value="{{ old('tanggal_lahir') }}" required>
                                    @error('tanggal_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir"
                                        class="form-control @error('tempat_lahir') is-invalid @enderror"
                                        value="{{ old('tempat_lahir') }}" required>
                                    @error('tempat_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                                        <option value="">-- Pilih Jenis Kelamin --</option>
                                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Asal Sekolah</label>
                                    <input type="text" name="asal_sekolah"
                                        class="form-control @error('asal_sekolah') is-invalid @enderror"
                                        value="{{ old('asal_sekolah') }}" placeholder="Contoh: SD Negeri 1" required>
                                    @error('asal_sekolah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kelas</label>
                                    <input type="text" name="kelas"
                                        class="form-control @error('kelas') is-invalid @enderror"
                                        value="{{ old('kelas') }}" placeholder="Contoh: 5 SD / 8 SMP / 11 SMA" required>
                                    @error('kelas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kontak Aktif</label>
                                    <div class="input-group">
                                        <span class="input-group-text">+62</span>
                                        <input type="text"
                                            name="kontak_aktif"
                                            class="form-control @error('kontak_aktif') is-invalid @enderror"
                                            value="{{ old('kontak_aktif') }}"
                                            pattern="[0-9]+"
                                            maxlength="13"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                                    </div>
                                    @error('kontak_aktif')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pilih Tingkat</label>
                                    <select name="tingkat_id" class="form-select @error('tingkat_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Tingkat --</option>
                                        @foreach($tingkat as $t)
                                        <option value="{{ $t->id }}"
                                            {{ old('tingkat_id') == $t->id ? 'selected' : '' }}>
                                            {{ $t->nama_tingkat }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('tingkat_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Upload Akta Kelahiran</label>
                                    <input type="file"
                                        name="akta_kelahiran"
                                        class="form-control @error('akta_kelahiran') is-invalid @enderror"
                                        accept=".jpg,.jpeg,.png,.pdf" required>
                                    @error('akta_kelahiran')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        Format: JPG, JPEG, PNG, PDF | Maksimal 2 MB
                                    </small>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat"
                                        class="form-control @error('alamat') is-invalid @enderror"
                                        rows="2" required>{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                            <!-- ================= DATA ORANG TUA ================= -->
                            <div class="section-title mt-4">B. Data Orang Tua / Wali</div>

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" name="nama_orangtua"
                                        class="form-control @error('nama_orangtua') is-invalid @enderror"
                                        value="{{ old('nama_orangtua') }}" required>
                                    @error('nama_orangtua')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pekerjaan</label>
                                    <input type="text" name="pekerjaan_orangtua"
                                        class="form-control @error('pekerjaan_orangtua') is-invalid @enderror"
                                        value="{{ old('pekerjaan_orangtua') }}" required>
                                    @error('pekerjaan_orangtua')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kontak Orang Tua</label>
                                    <div class="input-group">
                                        <span class="input-group-text">+62</span>
                                        <input type="text"
                                            name="kontak_orangtua"
                                            class="form-control @error('kontak_orangtua') is-invalid @enderror"
                                            value="{{ old('kontak_orangtua') }}"
                                            pattern="[0-9]+"
                                            maxlength="13"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                                    </div>
                                    @error('kontak_orangtua')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Alamat Orang Tua</label>
                                    <textarea name="alamat_orangtua"
                                        class="form-control"
                                        rows="2">{{ old('alamat_orangtua') }}</textarea>
                                </div>

                            </div>

                            <!-- SUBMIT -->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary px-5">
                                    Kirim Formulir Pendaftaran
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

                <div class="text-center mt-4 text-muted small">
                    © {{ date('Y') }} Sanggar Tari Dharmo Yuwono Purwokerto
                </div>

            </div>
        </div>
    </div>

</body>
</html>