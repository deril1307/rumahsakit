<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LaporanTerapisNotification extends Notification
{
    use Queueable;

    public $jadwal;

    public function __construct($jadwal)
    {
        $this->jadwal = $jadwal;
    }

    public function via($notifiable)
    {
        return ['database']; // Simpan ke database
    }

    public function toArray($notifiable)
    {
        return [
            'jadwal_id' => $this->jadwal->id,
            'title' => 'Laporan Status Terapi',
            'message' => 'Terapis ' . $this->jadwal->terapis->name . ' mengubah status pasien ' . $this->jadwal->pasien->nama . ' menjadi: ' . strtoupper($this->jadwal->status),
            // Saat diklik, Admin akan diarahkan ke halaman jadwal dengan pencarian nama pasien tersebut
            'url' => route('admin.jadwal.index', ['search' => $this->jadwal->pasien->nama]), 
            'type' => 'success'
        ];
    }
}