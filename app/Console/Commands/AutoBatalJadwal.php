<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AutoBatalJadwal extends Command
{
    /**
     * Nama perintah untuk dijalankan di terminal/scheduler
     */
    protected $signature = 'jadwal:auto-batal';

    /**
     * Deskripsi perintah
     */
    protected $description = 'Membatalkan jadwal otomatis jika pasien belum datang saat jam mulai terlewat';

    /**
     * Eksekusi logika
     */
    public function handle()
    {
        // 1. Ambil Waktu Indonesia Barat (WIB) agar akurat
        // PENTING: Pakai Asia/Jakarta agar tidak selisih 7 jam dengan server
        $now = Carbon::now()->setTimezone('Asia/Jakarta');
        
        $today = $now->format('Y-m-d');
        $currentTime = $now->format('H:i:s');

        // 2. Logika Pembatalan Strict (Tepat Waktu)
        $affectedRows = Jadwal::whereIn('status', ['terjadwal', 'pending'])
            ->where(function ($query) use ($today, $currentTime) {
                
                // KONDISI A: Tanggal jadwal sudah lewat (kemarin, lusa, dst)
                $query->where('tanggal', '<', $today)
                
                // KONDISI B: Tanggal HARI INI, tapi JAM MULAI sudah lewat
                // BUG FIX: Di sini kita pakai 'jam_mulai', BUKAN 'jam_selesai'
                      ->orWhere(function ($q) use ($today, $currentTime) {
                          $q->where('tanggal', '=', $today)
                            ->where('jam_mulai', '<', $currentTime); 
                      });
            })
            ->update(['status' => 'batal']);

        // 3. Simpan Log
        if ($affectedRows > 0) {
            $this->info("Sukses: {$affectedRows} jadwal dibatalkan karena terlambat datang.");
            Log::info("AutoBatal: {$affectedRows} jadwal dibatalkan. (Waktu Server: {$currentTime})");
        } else {
            $this->info("Aman: Tidak ada jadwal yang perlu dibatalkan saat ini.");
        }
    }
}