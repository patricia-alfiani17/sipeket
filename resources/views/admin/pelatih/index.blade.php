@extends('layout.main')

@section('title', 'Data Pelatih')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Pelatih</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Data Pelatih</li>
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
                        <h3 class="card-title">Daftar Pelatih</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.pelatih.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah Pelatih
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table id="pelatihTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>No HP</th>
                                    <th>Alamat</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pelatihs as $pelatih)
                                <tr>
                                    <td>{{ $pelatih->name }}</td>
                                    <td>{{ $pelatih->email }}</td>
                                    <td>{{ $pelatih->no_hp ? '+62' . ltrim(ltrim($pelatih->no_hp, '+62'), '0') : '-' }}</td>
                                    <td>{{ $pelatih->alamat ?: '-' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $pelatih->status == 'aktif' ? 'success' : 'danger' }}">
                                            {{ ucfirst($pelatih->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.pelatih.show', $pelatih) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.pelatih.edit', $pelatih) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.pelatih.destroy', $pelatih) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pelatih ini?')">
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
        $('#pelatihTable').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#pelatihTable_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection