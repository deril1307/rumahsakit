<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Jadwal Terapi Pasien</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            line-height: 1.5;
        }
        .header-title {
            text-transform: uppercase;
            font-weight: bold;
            font-size: 18px;
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 3px double #333;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .info-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            width: 130px;
        }
        .separator {
            width: 15px;
            text-align: center;
        }
        
        /* Styling Tabel Jadwal */
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-top: 15px;
        }
        .schedule-table th {
            border: 1px solid #333;
            background-color: #f0f0f0;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        .schedule-table td {
            border: 1px solid #999;
            padding: 8px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        /* Status Color simulation for PDF */
        .status-selesai { color: #15803d; font-weight: bold; } /* Hijau Tua */
        .status-batal { color: #b91c1c; font-weight: bold; }   /* Merah Tua */
        .status-terjadwal { color: #333; }

        .footer {
            margin-top: 40px;
            font-size: 11px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            display: flex;
            justify-content: space-between;
        }
        .ttd-area {
            margin-top: 30px;
            float: right;
            text-align: center;
            width: 200px;
        }
        .ttd-line {
            margin-top: 60px;
            border-bottom: 1px solid #333;
        }
    </style>
</head>
<body>

    <div class="header-title">
        JADWAL KONSULTASI & TERAPI
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Pasien</td>
            <td class="separator">:</td>
            <td>{{ $pasien->nama ?? '-' }}</td>
            
            <td class="label">No. Rekam Medis</td>
            <td class="separator">:</td>
            <td>{{ $pasien->no_rm ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Terapi</td>
            <td class="separator">:</td>
            <td>{{ $jenis_terapi }}</td>

            <td class="label">Terapis</td>
            <td class="separator">:</td>
            <td>{{ $terapis->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Periode Jadwal</td>
            <td class="separator">:</td>
            <td colspan="4">
                {{ $periode }}
            </td>
        </tr>
    </table>

    <table class="schedule-table">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 20%;">Tanggal</th>
                <th style="width: 15%;">Hari</th>
                <th style="width: 20%;">Waktu</th>
                <th style="width: 25%;">Ruangan</th>
                <th style="width: 15%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($listJadwal as $index => $jadwal)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l') }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - 
                    {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }} WIB
                </td>
                <td>{{ $jadwal->ruangan ?? '-' }}</td>
                <td>
                    @if($jadwal->status == 'selesai')
                        <span class="status-selesai">Selesai</span>
                    @elseif($jadwal->status == 'batal')
                        <span class="status-batal">Batal</span>
                    @else
                        <span class="status-terjadwal">Terjadwal</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 20px;">
                    Tidak ada jadwal ditemukan untuk periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="ttd-area">
        <p>Mengetahui,<br>Terapis / Admin</p>
        <div class="ttd-line"></div>
        <p>{{ $terapis->name ?? '.......................' }}</p>
    </div>

    <div style="clear: both;"></div>

    <div class="footer">
        <div>
            Dicetak otomatis oleh Sistem.
        </div>
        <div class="text-right">
            Waktu Cetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB
        </div>
    </div>

</body>
</html>