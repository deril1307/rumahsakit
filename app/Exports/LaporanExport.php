<?php

namespace App\Exports;

use App\Models\Jadwal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;
    protected $terapisId;
    protected $jenisTerapi;

    public function __construct($startDate, $endDate, $terapisId, $jenisTerapi)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->terapisId = $terapisId;
        $this->jenisTerapi = $jenisTerapi;
    }

    public function collection()
    {
        $query = Jadwal::with(['pasien', 'terapis'])
            ->whereBetween('tanggal', [$this->startDate, $this->endDate]);

        // Filter Terapis
        if ($this->terapisId && $this->terapisId != 'Semua Terapis') {
            $query->where('user_id', $this->terapisId);
        }

        // Filter Jenis Terapi
        if ($this->jenisTerapi && $this->jenisTerapi != 'Semua Jenis') {
            $query->where('jenis_terapi', $this->jenisTerapi);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return ['Tanggal', 'Jam', 'Nama Pasien', 'No RM', 'Jenis Terapi', 'Terapis', 'Status'];
    }

    public function map($jadwal): array
    {
        return [
            $jadwal->tanggal ? $jadwal->tanggal->format('d/m/Y') : '-',
            ($jadwal->jam_mulai ? $jadwal->jam_mulai->format('H:i') : '-') . ' - ' . ($jadwal->jam_selesai ? $jadwal->jam_selesai->format('H:i') : '-'),
            $jadwal->pasien->nama ?? '-',
            $jadwal->pasien->no_rm ?? '-',
            $jadwal->jenis_terapi,
            $jadwal->terapis->name ?? '-',
            $jadwal->status,
        ];
    }
}