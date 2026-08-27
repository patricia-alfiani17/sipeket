@extends('layout.main')

@section('title', 'Data Siswa')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Siswa</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('pelatih.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Data Siswa</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Data Siswa</h3>
                    </div>
                    <div class="card-body">
                        <!-- Filter Section -->
                        <div class="mb-4 pb-3 border-bottom">
                            <form method="GET" action="{{ route('pelatih.data-siswa') }}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="search">Nama Siswa:</label>
                                            <input type="text" class="form-control" id="search" name="search" placeholder="Cari nama siswa" value="{{ $search ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="tingkat_id">Tingkat:</label>
                                            <select class="form-control" id="tingkat_id" name="tingkat_id">
                                                <option value="">- Pilih Tingkat -</option>
                                                @foreach($tingkats as $tingkat)
                                                <option value="{{ $tingkat->id }}" {{ $tingkat_id == $tingkat->id ? 'selected' : '' }}>
                                                    {{ $tingkat->nama_tingkat }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="status">Status:</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="">- Pilih Status -</option>
                                                <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                <option value="nonaktif" {{ $status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search"></i> Cari</button>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <a href="{{ route('pelatih.data-siswa') }}" class="btn btn-secondary btn-block"><i class="fas fa-redo"></i> Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Table Section -->
                        <table id="siswaTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Siswa</th>
                                    <th>Tingkat</th>
                                    <th>Status</th>
                                    <th width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($siswas as $siswa)
                                <tr>
                                    <td>{{ $siswa->name }}</td>
                                    <td>{{ $siswa->siswaProfile?->tingkat?->nama_tingkat ?? '-' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $siswa->status == 'aktif' ? 'success' : 'danger' }}">
                                            {{ ucfirst($siswa->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('pelatih.siswa.show', $siswa) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Tidak ada data siswa</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        $('#siswaTable').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#siswaTable_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection