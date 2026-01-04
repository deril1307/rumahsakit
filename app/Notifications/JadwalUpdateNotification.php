<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class JadwalUpdateNotification extends Notification
{
    use Queueable;

    public $jadwal;

    public function __construct($jadwal)
    {
        $this->jadwal = $jadwal;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            // PENTING: ID ini digunakan Route untuk cek apakah data masih ada
            'jadwal_id' => $this->jadwal->id,
            
            'title' => 'Perubahan Jadwal',
            'message' => 'Admin telah memperbarui jadwal pasien ' . $this->jadwal->pasien->nama . ' pada tanggal ' . $this->jadwal->tanggal,
            'url' => route('terapis.jadwal.edit', $this->jadwal->id), 
            'type' => 'warning'
        ];
    }
}