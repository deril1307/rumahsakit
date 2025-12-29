<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class JadwalBaruNotification extends Notification
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
            'jadwal_id' => $this->jadwal->id,
            'title' => 'Jadwal Baru',
            'message' => 'Anda memiliki jadwal baru dengan pasien ' . $this->jadwal->pasien->nama . ' pada tanggal ' . $this->jadwal->tanggal,
            // PERBAIKAN DISINI: Arahkan langsung ke halaman edit/detail jadwal spesifik
            'url' => route('terapis.jadwal.edit', $this->jadwal->id),
            'type' => 'info'
        ];
    }
}