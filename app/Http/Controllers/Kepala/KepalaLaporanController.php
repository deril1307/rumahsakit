<?php

namespace App\Http\Controllers\Kepala;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KepalaLaporanController extends Controller
{
    public function index()
    {
        // Untuk sekarang, kita pakai data dummy
        $stats = [
            'total_sesi' => '1,240',
            'total_pasien' => '320',
            'sesi_selesai' => '1,180',
            'sesi_dibatalkan' => '60',
        ];

        $riwayatTerapi = [
            (object)['pasien' => 'Budi Santoso', 'terapis' => 'Dr. Anisa', 'jenis_terapi' => 'Fisioterapi', 'tanggal' => '28/05/2024', 'status' => 'Selesai'],
            (object)['pasien' => 'Citra Lestari', 'terapis' => 'Dr. Budi', 'jenis_terapi' => 'Okupasi', 'tanggal' => '28/05/2024', 'status' => 'Selesai'],
            (object)['pasien' => 'Dewi Anggraini', 'terapis' => 'Dr. Anisa', 'jenis_terapi' => 'Fisioterapi', 'tanggal' => '27/05/2024', 'status' => 'Dibatalkan'],
            (object)['pasien' => 'Eko Prasetyo', 'terapis' => 'Dr. Chandra', 'jenis_terapi' => 'Wicara', 'tanggal' => '27/05/2024', 'status' => 'Selesai'],
            (object)['pasien' => 'Fajar Nugroho', 'terapis' => 'Dr. Budi', 'jenis_terapi' => 'Fisioterapi', 'tanggal' => '26/05/2024', 'status' => 'Selesai'],
        ];

        // Kirim data ke view 'kepala.laporan'
        return view('kepala.laporan', [
            'stats' => $stats,
            'riwayat' => $riwayatTerapi
        ]);
    }
}
