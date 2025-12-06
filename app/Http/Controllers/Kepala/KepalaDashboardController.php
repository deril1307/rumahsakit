<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Pasien;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KepalaDashboardController extends Controller
{
    public function index()
    {
        // Kita ambil statistik untuk BULAN INI agar relevan
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // 1. Total Kunjungan/Sesi Terapi Bulan Ini
        $totalSesi = Jadwal::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->count();

        // 2. Status Terapi Bulan Ini
        $sesiSelesai = Jadwal::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                            ->where('status', 'selesai')->count();
        
        $sesiBatal = Jadwal::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                            ->where('status', 'batal')->count();
        
        $sesiPending = Jadwal::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                            ->whereIn('status', ['terjadwal', 'pending'])->count();

        // 3. Total Pasien Unik (Jumlah orang yang berbeda yang datang bulan ini)
        // Kita ambil pasien_id dari jadwal bulan ini, lalu hitung yang unik
        $totalPasien = Jadwal::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                            ->distinct('pasien_id')
                            ->count('pasien_id');

        return view('kepala.dashboard', compact(
            'totalSesi',
            'sesiSelesai',
            'sesiBatal',
            'sesiPending',
            'totalPasien'
        ));
    }
}