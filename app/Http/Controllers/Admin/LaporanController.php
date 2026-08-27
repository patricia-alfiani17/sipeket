<?php

namespace App\Http\Controllers\Admin;

use App\Exports\EvaluasiExport;
use App\Exports\RiwayatTingkatExport;
use App\Exports\SiswaExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function exportSiswa()
    {
        $filename = 'laporan-data-siswa-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new SiswaExport, $filename);
    }

    public function exportEvaluasi()
    {
        $filename = 'laporan-hasil-evaluasi-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new EvaluasiExport, $filename);
    }

    public function exportRiwayat()
    {
        $filename = 'laporan-riwayat-kenaikan-tingkat-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new RiwayatTingkatExport, $filename);
    }
}
