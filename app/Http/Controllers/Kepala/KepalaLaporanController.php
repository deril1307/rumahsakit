<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class KepalaLaporanController extends Controller
{
    /**
     * Halaman Utama Laporan
     */
    public function index(Request $request)
    {
        // 1. Ambil Input Filter
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $terapisId = $request->input('terapis_id');
        $status = $request->input('status');

        // 2. Query Data Jadwal
        $query = Jadwal::with(['pasien', 'terapis'])
            ->whereBetween('tanggal', [$startDate, $endDate]);

        // Filter by Terapis (jika dipilih)
        if ($terapisId) {
            $query->where('user_id', $terapisId);
        }

        // Filter by Status (jika dipilih)
        if ($status) {
            $query->where('status', $status);
        }

        // Urutkan data
        $laporan = $query->orderBy('tanggal', 'asc')->get();

        // 3. Data Pendukung untuk Dropdown Filter
        $listTerapis = User::role('terapis')->get();

        // 4. Hitung Statistik Ringkas untuk Header Laporan
        $totalSesi = $laporan->count();
        $totalSelesai = $laporan->where('status', 'selesai')->count();
        $totalBatal = $laporan->where('status', 'batal')->count();

        return view('kepala.laporan', compact(
            'laporan', 
            'listTerapis', 
            'startDate', 
            'endDate', 
            'terapisId', 
            'status',
            'totalSesi',
            'totalSelesai',
            'totalBatal'
        ));
    }

    /**
     * Cetak Laporan ke PDF
     */
    public function cetakPdf(Request $request)
    {
        // Ambil filter yang sama persis dengan index
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $terapisId = $request->input('terapis_id');
        $status = $request->input('status');

        $query = Jadwal::with(['pasien', 'terapis'])
            ->whereBetween('tanggal', [$startDate, $endDate]);

        if ($terapisId) {
            $query->where('user_id', $terapisId);
        }
        if ($status) {
            $query->where('status', $status);
        }

        $laporan = $query->orderBy('tanggal', 'asc')->get();

        // Siapkan data untuk view PDF
        $data = [
            'laporan' => $laporan,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalSesi' => $laporan->count(),
            'totalSelesai' => $laporan->where('status', 'selesai')->count(),
        ];

        // Generate PDF
        $pdf = Pdf::loadView('kepala.laporan_pdf', $data);
        $pdf->setPaper('a4', 'landscape'); // Landscape agar muat banyak kolom

        return $pdf->stream('Laporan-Rehab-Medik.pdf');
    }
}