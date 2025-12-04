<?php

namespace App\Http\Controllers\Terapis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal; 
use Illuminate\Support\Facades\Auth;

class TerapisDashboardController extends Controller
{
    public function index()
    {
        // Ambil ID Terapis yang sedang login
        $terapisId = Auth::id();

        // Ambil jadwal di mana 'user_id' (terapis) adalah user yang login
        // Urutkan dari tanggal terdekat
        // Pastikan meload relasi 'pasien' agar bisa ambil nama & RM
        $jadwalSaya = Jadwal::with('pasien')
                        ->where('user_id', $terapisId)
                        ->whereDate('tanggal', '>=', now()) 
                        ->orderBy('jam_mulai', 'asc')
                        ->paginate(10);

        return view('terapis.dashboard', [
            'jadwalSaya' => $jadwalSaya
        ]);
    }
}