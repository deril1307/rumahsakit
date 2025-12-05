<?php

namespace App\Http\Controllers\Terapis;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TerapisDashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Terapis dengan Filter
     */
    public function index(Request $request)
    {
        // 1. Ambil Statistik Harian (Tetap fokus ke hari ini untuk Card Statistik Atas)
        // Ini penting agar terapis tetap tahu beban kerja HARI INI meskipun sedang melihat filter lain
        $jadwalNyataHariIni = Jadwal::where('user_id', Auth::id())
            ->whereDate('tanggal', Carbon::today())
            ->get();

        $totalPasienHariIni = $jadwalNyataHariIni->count();
        $pasienSelesai = $jadwalNyataHariIni->where('status', 'selesai')->count();
        $pasienMenunggu = $jadwalNyataHariIni->whereIn('status', ['terjadwal', 'pending'])->count();
        $pasienDitunda = $jadwalNyataHariIni->where('status', 'ditunda')->count(); // Statistik untuk status Ditunda

        // 2. Logika Filter untuk Tabel Jadwal
        $filter = $request->input('filter', 'today'); // Default 'today' jika tidak ada input
        $query = Jadwal::with('pasien')->where('user_id', Auth::id());
        $labelFilter = 'Hari Ini';

        switch ($filter) {
            case 'week':
                // Jadwal Minggu Ini (Senin - Minggu)
                $query->whereBetween('tanggal', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                $labelFilter = 'Minggu Ini';
                break;
            case 'month':
                // Jadwal Bulan Ini
                $query->whereMonth('tanggal', Carbon::now()->month)
                      ->whereYear('tanggal', Carbon::now()->year);
                $labelFilter = 'Bulan Ini (' . Carbon::now()->translatedFormat('F Y') . ')';
                break;
            case '3months':
                // Jadwal 3 Bulan Ke Depan
                $query->whereBetween('tanggal', [Carbon::now(), Carbon::now()->addMonths(3)]);
                $labelFilter = '3 Bulan Ke Depan';
                break;
            case '6months':
                // Jadwal 6 Bulan Ke Depan
                $query->whereBetween('tanggal', [Carbon::now(), Carbon::now()->addMonths(6)]);
                $labelFilter = '6 Bulan Ke Depan';
                break;
            case 'today':
            default:
                // Default: Jadwal Hari Ini
                $query->whereDate('tanggal', Carbon::today());
                $labelFilter = 'Hari Ini (' . Carbon::now()->translatedFormat('d M Y') . ')';
                break;
        }

        // Ambil data tabel sesuai filter, diurutkan berdasarkan tanggal dan jam
        $jadwalList = $query->orderBy('tanggal', 'asc')
                            ->orderBy('jam_mulai', 'asc')
                            ->get();

        // Kirim semua data ke view
        return view('terapis.dashboard', compact(
            'jadwalList',        // Data untuk tabel (bisa berubah sesuai filter)
            'totalPasienHariIni', // Data statistik (tetap hari ini)
            'pasienSelesai',      // Data statistik (tetap hari ini)
            'pasienMenunggu',     // Data statistik (tetap hari ini)
            'pasienDitunda',      // Data statistik (tetap hari ini - BARU)
            'filter',             // Untuk menjaga pilihan dropdown tetap terpilih
            'labelFilter'         // Untuk judul tabel yang dinamis
        ));
    }

    /**
     * Menampilkan form edit untuk perbaikan data (jika salah pencet)
     */
    public function edit($id)
    {
        $jadwal = Jadwal::where('user_id', Auth::id())->findOrFail($id);
        return view('terapis.edit', compact('jadwal'));
    }

    /**
     * Update Status Terapi (Tombol Selesai/Batal/Ditunda)
     */
    public function updateStatus(Request $request, $id)
    {
        // Pastikan jadwal yang diupdate adalah milik terapis yang login
        $jadwal = Jadwal::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            // Tambahkan 'ditunda' ke dalam validasi status
            'status' => 'required|in:terjadwal,selesai,batal,pending,ditunda',
            'catatan' => 'nullable|string'
        ]);

        $jadwal->update([
            'status' => $request->status,
            'catatan' => $request->catatan // Opsi jika nanti mau nambah catatan medis
        ]);

        return redirect()->back()->with('success', 'Status terapi berhasil diperbarui.');
    }
}