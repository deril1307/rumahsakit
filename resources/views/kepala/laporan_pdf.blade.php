<!DOCTYPE html>
<html>

<head>
    <title>Laporan Rehabilitasi Medik</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            text-transform: uppercase;
            font-size: 16pt;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 11pt;
        }

        .periode {
            text-align: center;
            font-size: 11pt;
            margin-bottom: 20px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
            font-size: 9pt;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }

        .summary {
            float: right;
            width: 40%;
            margin-top: 10px;
            page-break-inside: avoid;
        }

        .summary table {
            border: none;
        }

        .summary td {
            border: none;
            padding: 2px;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
            page-break-inside: avoid;
        }

        .signature-line {
            border-top: 1px solid #333;
            width: 200px;
            display: inline-block;
            margin-top: 60px;
        }

        /* Status Colors (Optional, might not show on all PDF readers) */
        .status-selesai {
            color: inherit;
            font-weight: normal;
        }

        .status-batal {
            color: inherit;
            font-weight: normal;
        }

    </style>
</head>

<body>
    <div class="header">
        <h2>Laporan Kinerja Rehabilitasi Medik</h2>
        <p>RS Al-Islam Bandung</p>
    </div>

    <div class="periode">
        Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} -
        {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 12%">Tanggal</th>
                <th style="width: 10%">Jam</th>
                <th style="width: 20%">Pasien</th>
                <th style="width: 20%">Terapis</th>
                <th style="width: 15%">Layanan</th>
                <th style="width: 10%">Status</th>
                <th style="width: 8%">Ruangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $row)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td style="text-align: center;">{{ $row->tanggal->format('d/m/Y') }}</td>
                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($row->jam_mulai)->format('H:i') }}</td>
                    <td>
                        {{ $row->pasien->nama }}<br>
                        <small style="color: #555;">RM: {{ $row->pasien->no_rm }}</small>
                    </td>
                    <td>{{ $row->terapis->name }}</td>
                    <td>{{ $row->jenis_terapi }}</td>
                    <td style="text-align: center;">
                        <span class="status-{{ $row->status }}">
                            {{ ucfirst($row->status) }}
                        </span>
                    </td>
                    <td>{{ $row->ruangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">Tidak ada data pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <h3>Ringkasan Kinerja:</h3>
        <table style="width: 100%;">
            <tr>
                <td style="width: 60%;">Total Jadwal</td>
                <td style="width: 5%;">:</td>
                <td style="text-align: right;">{{ $totalSesi }}</td>
            </tr>
            <tr>
                <td>Selesai Dilakukan</td>
                <td>:</td>
                <td style="text-align: right;">{{ $totalSelesai }}</td>
            </tr>
            <tr>
                <td>Persentase Kehadiran</td>
                <td>:</td>
                <td style="text-align: right;">{{ $totalSesi > 0 ? round(($totalSelesai / $totalSesi) * 100) : 0 }}%
                </td>
            </tr>
        </table>
    </div>

    <div style="clear: both;"></div>

    <div class="footer">
        <p>Bandung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p>Kepala Instalasi Rehabilitasi Medik</p>
        <div class="signature-line"></div>
        <p>( ...................................................... )</p>
    </div>
</body>

</html>
