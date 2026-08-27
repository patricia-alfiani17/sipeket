<div class="table-responsive">
    <table class="table table-striped table-hover mb-0">
        <thead class="thead-light">
            <tr>
                <th>Tahun Periode</th>
                <th>Tingkat Dievaluasi</th>
                <th>Jenis</th>
                <th>Nilai Akhir</th>
                <th>Status Kelulusan</th>
                <th>Keputusan</th>
                <th>Tanggal Evaluasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($evaluasiRows as $row)
            @php
                $isEligibleRow = isset($eligibleTingkatId)
                    && (int) $row['tingkat_id'] === (int) $eligibleTingkatId
                    && ($row['keputusan'] ?? '') === \App\Models\EvaluasiTingkat::STATUS_NAIK;
                $isPendingRow = $pendingPengajuan
                    && (int) $pendingPengajuan->tingkat_id === (int) $row['tingkat_id'];
            @endphp
            <tr>
                <td>{{ $row['tahun_periode'] }}</td>
                <td>{{ $row['tingkat_nama'] }}</td>
                <td>
                    <span class="badge badge-{{ $row['jenis_penilaian'] === 'Ujian' ? 'dark' : 'info' }}">
                        {{ $row['jenis_penilaian'] }}
                    </span>
                </td>
                <td>{{ number_format($row['nilai_akhir'], 2) }}</td>
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
                    @if($row['keputusan_manual'])
                        <span class="badge badge-info ml-1">Manual</span>
                    @endif
                </td>
                <td>{{ $row['tanggal_evaluasi'] }}</td>
                <td>
                    @if($isPendingRow)
                        <span class="badge badge-warning">Menunggu Persetujuan</span>
                    @elseif($isEligibleRow && ($canSubmitPengajuan['eligible'] ?? false))
                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#modalAjukanMengulang">
                            <i class="fas fa-redo"></i> Ajukan Mengulang
                        </button>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-muted py-4">
                    Belum ada hasil evaluasi kenaikan tingkat.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
