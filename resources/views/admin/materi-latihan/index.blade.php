@extends('layout.main')

@section('title', 'Data Materi Latihan')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Materi Latihan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Data Materi Latihan</li>
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
                        <h3 class="card-title">Daftar Materi Latihan</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.materi-latihan.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah Materi
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table id="materiTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">Urutan</th>
                                    <th>Nama Materi</th>
                                    <th>Tingkat</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($materis as $materi)
                                <tr>
                                    <td>{{ $materi->urutan }}</td>
                                    <td>{{ $materi->nama }}</td>
                                    <td>{{ $materi->tingkat?->nama_tingkat ?? '-' }}</td>
                                    <td>{{ $materi->deskripsi }}</td>
                                    <td>
                                        <a href="{{ route('admin.materi-latihan.edit', $materi) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.materi-latihan.destroy', $materi) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
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
        $('#materiTable').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#materiTable_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection
