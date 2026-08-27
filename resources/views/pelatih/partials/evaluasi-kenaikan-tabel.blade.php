<div class="table-responsive">
<table class="table table-bordered table-striped mb-0">
    <thead class="thead-light">
        <tr>
            <th>Nama</th>
            <th>Tingkat Dievaluasi</th>
            <th>Tingkat Saat Ini</th>
            <th>Nilai Akhir</th>
            <th>Status Kelulusan</th>
            <th>Keputusan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $row)
        <tr class="{{ $mode === 'tersimpan' ? 'table-light' : '' }}">
            <td>
                {{ $row['nama'] }}
                @if($mode === 'menunggu' && !empty($row['is_evaluasi_ulang']))
                    <span class="badge badge-warning ml-1">Evaluasi Ulang</span>
                @endif
            </td>
            <td>{{ $row['tingkat_nama'] }}</td>
            <td>{{ $row['tingkat_saat_ini'] }}</td>
            <td>{{ number_format($row['nilai_akhir'], 1) }}</td>
            <td>
                @php
                    $badgeClass = match($row['status_kelulusan']) {
                        'lulus' => 'success',
                        'toleransi' => 'warning',
                        'tidak_lulus' => 'danger',
                        default => 'secondary',
                    };
                @endphp
                <span class="badge badge-{{ $badgeClass }}">{{ $row['status_kelulusan_label'] }}</span>
            </td>
            <td>
                {{ $row['keputusan_label'] }}
                @if($mode === 'tersimpan' && !empty($row['keputusan_manual']))
                <span class="badge badge-info ml-1">Manual</span>
                @endif
            </td>
            <td>
                @if($mode === 'menunggu')
                    <input type="hidden" name="rekap_id[]" value="{{ $row['rekap']->id }}">
                    <input type="hidden" name="jenis_rekap[]" value="{{ $row['jenis_rekap'] ?? 'harian' }}">
                    @if($row['keputusan'])
                    <input type="hidden" name="keputusan[]" value="{{ $row['keputusan'] }}">
                    @else
                    <input type="hidden" name="keputusan[]" value="">
                    @endif

                    @if($row['perlu_tetapkan'])
                    <button type="button" class="btn btn-sm btn-primary btn-tetapkan"
                        data-toggle="modal" data-target="#modalTetapkan"
                        data-rekap-id="{{ $row['rekap']->id }}"
                        data-jenis-rekap="{{ $row['jenis_rekap'] ?? 'harian' }}"
                        data-nama="{{ $row['nama'] }}"
                        data-nilai="{{ number_format($row['nilai_akhir'], 1) }}">
                        Tetapkan
                    </button>
                    @else
                    <span class="text-muted small">Otomatis</span>
                    @endif
                @else
                    <span class="badge badge-secondary">Tersimpan</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
