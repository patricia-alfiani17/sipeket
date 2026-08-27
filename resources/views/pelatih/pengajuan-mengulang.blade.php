@extends('layout.main')

@section('page_title', 'Pengajuan Mengulang Tingkat')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Pengajuan Mengulang Tingkat</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('pelatih.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Pengajuan Mengulang</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
        @endif

        <div class="card card-outline card-warning mb-4">
            <div class="card-header">
                <h3 class="card-title">Menunggu Persetujuan ({{ $pendingPengajuan->count() }})</h3>
            </div>
            <div class="card-body p-0">
                @if($pendingPengajuan->isNotEmpty())
                <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Siswa</th>
                            <th>Tingkat Diajukan</th>
                            <th>Tingkat Saat Ini</th>
                            <th>Alasan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingPengajuan as $item)
                        <tr>
                            <td>{{ $item->tanggal_pengajuan->format('d/m/Y') }}</td>
                            <td>{{ $item->siswa?->user?->name ?? $item->siswa?->nama_lengkap ?? '-' }}</td>
                            <td>{{ $item->tingkat?->nama_tingkat ?? '-' }}</td>
                            <td>{{ $item->tingkatSaatPengajuan?->nama_tingkat ?? '-' }}</td>
                            <td>{{ Str::limit($item->alasan, 100) }}</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                    data-target="#modalSetujui{{ $item->id }}">Setujui</button>
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                    data-target="#modalTolak{{ $item->id }}">Tolak</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>

                @foreach($pendingPengajuan as $item)
                <div class="modal fade" id="modalSetujui{{ $item->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('pelatih.pengajuan-mengulang.setujui', $item) }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Setujui Pengajuan</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <p>Setujui pengajuan <strong>{{ $item->siswa?->user?->name }}</strong> untuk mengulang
                                        <strong>{{ $item->tingkat?->nama_tingkat }}</strong>?</p>
                                    <p class="text-muted small">Siswa akan dipindahkan ke tingkat tersebut dan penilaian tingkat ini direset untuk periode default.</p>
                                    <div class="form-group mb-0">
                                        <label for="catatan_setujui_{{ $item->id }}">Catatan (opsional)</label>
                                        <textarea name="catatan_pelatih" id="catatan_setujui_{{ $item->id }}" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-success">Setujui</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalTolak{{ $item->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('pelatih.pengajuan-mengulang.tolak', $item) }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Tolak Pengajuan</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <p>Tolak pengajuan <strong>{{ $item->siswa?->user?->name }}</strong>?</p>
                                    <div class="form-group mb-0">
                                        <label for="catatan_tolak_{{ $item->id }}">Catatan (opsional)</label>
                                        <textarea name="catatan_pelatih" id="catatan_tolak_{{ $item->id }}" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Tolak</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <div class="p-3 text-muted">Tidak ada pengajuan yang menunggu persetujuan.</div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Keputusan</h3>
            </div>
            <div class="card-body p-0">
                @if($historyPengajuan->isNotEmpty())
                <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Tanggal Keputusan</th>
                            <th>Nama Siswa</th>
                            <th>Tingkat Diajukan</th>
                            <th>Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($historyPengajuan as $item)
                        <tr>
                            <td>{{ $item->tanggal_keputusan?->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ $item->siswa?->user?->name ?? '-' }}</td>
                            <td>{{ $item->tingkat?->nama_tingkat ?? '-' }}</td>
                            <td>
                                @if($item->status === \App\Models\PengajuanMengulangTingkat::STATUS_DISETUJUI)
                                <span class="badge badge-success">{{ $item->statusLabel() }}</span>
                                @else
                                <span class="badge badge-danger">{{ $item->statusLabel() }}</span>
                                @endif
                            </td>
                            <td>{{ $item->catatan_pelatih ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                @else
                <div class="p-3 text-muted">Belum ada riwayat keputusan.</div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
