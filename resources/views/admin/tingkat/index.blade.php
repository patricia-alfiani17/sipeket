@extends('layout.main')

@section('title', 'Data Tingkat')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Tingkat</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Data Tingkat</li>
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
                        <h3 class="card-title">Daftar Tingkat</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.tingkat.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah Tingkat
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table id="tingkatTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Tingkat</th>
                                    <th>Jenis Penilaian</th>
                                    <th>KKM</th>
                                    <th>Tidak Lulus (&le;)</th>
                                    <th>Pertimbangan</th>
                                    <th>Urutan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tingkats as $tingkat)
                                <tr>
                                    <td>{{ $tingkat->nama_tingkat }}</td>
                                    <td>
                                        <span class="badge badge-{{ $tingkat->jenis_penilaian == 'harian' ? 'info' : 'success' }}">
                                            {{ ucfirst($tingkat->jenis_penilaian) }}
                                        </span>
                                    </td>
                                    <td>{{ $tingkat->kkm }}</td>
                                    <td>{{ $tingkat->ambang_tidak_lulus }}</td>
                                    <td>{{ $tingkat->ambang_pertimbangan_min }} – {{ $tingkat->ambang_pertimbangan_max }}</td>
                                    <td>{{ $tingkat->urutan }}</td>
                                    <td>
                                        <a href="{{ route('admin.tingkat.edit', $tingkat) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.tingkat.destroy', $tingkat) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Hapus tingkat \'{{ $tingkat->nama_tingkat }}\'?\n\nAmbang: tidak lulus ≤ {{ $tingkat->ambang_tidak_lulus }}, pertimbangan {{ $tingkat->ambang_pertimbangan_min }}–{{ $tingkat->ambang_pertimbangan_max }}, KKM {{ $tingkat->kkm }}.')">
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
        $('#tingkatTable').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#tingkatTable_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection