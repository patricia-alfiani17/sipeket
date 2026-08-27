<div class="table-responsive">
    <table class="table table-striped mb-0">
        <thead class="thead-light">
            <tr>
                <th style="width:40px;">#</th>
                <th>Jenis</th>
                <th>Dari Tingkat</th>
                <th>Ke Tingkat</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayatRows as $index => $riwayat)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    @if($riwayat->isMengulang())
                        <span class="badge badge-warning">Mengulang</span>
                    @else
                        <span class="badge badge-success">Naik</span>
                    @endif
                </td>
                <td>{{ $riwayat->tingkatAwal?->nama_tingkat ?? '-' }}</td>
                <td><strong>{{ $riwayat->tingkatAkhir?->nama_tingkat ?? '-' }}</strong></td>
                <td>{{ $riwayat->tanggal_naik ? \Carbon\Carbon::parse($riwayat->tanggal_naik)->format('d M Y') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted py-4">
                    Belum ada riwayat kenaikan tingkat.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
