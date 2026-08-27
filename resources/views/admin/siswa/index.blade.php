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
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Siswa</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah Siswa
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">  

                        <table id="siswaTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Tingkat</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswas as $siswa)
                                <tr>
                                    <td>{{ $siswa->name }}</td>
                                    <td>{{ $siswa->email }}</td>
                                    <td>{{ $siswa->siswaProfile?->tingkat?->nama_tingkat ?? '-' }}</td>
                                    <td>{{ $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d M Y') : '-' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $siswa->status == 'aktif' ? 'success' : 'danger' }}">
                                            {{ ucfirst($siswa->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.siswa.show', $siswa) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.siswa.edit', $siswa) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.siswa.destroy', $siswa) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus siswa ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
</div> 
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