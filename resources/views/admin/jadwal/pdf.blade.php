<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Jadwal Terapi Pasien</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
        }
        .header-title {
            text-transform: uppercase;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 13px;
        }
        .info-table td {
            padding: 3px;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            width: 120px;
        }
        
        /* Table Style seperti di Gambar */
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-top: 10px;
        }
        .schedule-table th {
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            text-align: left;
            padding: 10px 5px;
            background-color: #f4f4f4;
        }
        .schedule-table td {
            border-bottom: 1px solid #eee;
            padding: 10px 5px;
        }
        .text-center { text-align: center; }
        
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
            font-style: italic;
        }
    </style>
</head>
<body>

    <div class="header-title">
        JADWAL TERAPI KELAS PASIEN
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Pasien</td>
            <td>: {{ $pasien->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">No. RM</td>
            <td>: {{ $pasien->no_rm ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Terapi</td>
            <td>: {{ $jenis_terapi }}</td>
        </tr>
        <tr>
            <td class="label">Terapis</td>
            <td>: {{ $terapis->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Periode</td>
            <td>: {{ $periode }}</td>
        </tr>
    </table>

    <table class="schedule-table">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Tanggal</th>
                <th>Hari</th>
                <th>Jam</th>
                <th>Jenis Terapi</th>
                <th>Terapis</th>
                <th>Status</th> </tr>
        </thead>
        <tbody>
            @forelse($listJadwal as $index => $jadwal)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l') }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H.i') }} - 
                    {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H.i') }}
                </td>
                <td>{{ $jadwal->jenis_terapi }}</td>
                <td>{{ $jadwal->terapis->name ?? '-' }}</td>
                <td>
                    @if($jadwal->status == 'selesai')
                        <span style="color: green;">Selesai</span>
                    @elseif($jadwal->status == 'batal')
                        <span style="color: red;">Batal</span>
                    @else
                        Terjadwal
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada jadwal pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d F Y H:i') }}
    </div>

</body>
</html>