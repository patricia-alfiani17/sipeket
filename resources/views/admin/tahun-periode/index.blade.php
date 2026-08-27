@extends('layout.main')

@section('title', 'Data Tahun Periode')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Tahun Periode</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Data Tahun Periode</li>
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
                        <h3 class="card-title">Daftar Tahun Periode</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.tahun-periode.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah Tahun Periode
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table id="periodeTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Periode</th>
                                    <th>Default</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($periodes as $periode)
                                <tr>
                                    <td>{{ $periode->periode }}</td>
                                    <td>
                                        <span class="badge badge-{{ $periode->is_default ? 'success' : 'secondary' }}">
                                            {{ $periode->is_default ? 'Ya' : 'Tidak' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.tahun-periode.edit', $periode) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.tahun-periode.destroy', $periode) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus tahun periode ini?')">
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
        $('#periodeTable').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#periodeTable_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection
