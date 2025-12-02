<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Tiket Jadwal Terapi</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11pt;
            /* Sedikit lebih besar agar terbaca */
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .container {
            width: 100%;
            border: 2px solid #2c3e50;
            /* Warna biru tua elegan */
            border-radius: 8px;
            /* Sudut melengkung */
            padding: 20px;
            box-sizing: border-box;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header h2 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
            color: #2c3e50;
            letter-spacing: 1px;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 11px;
            color: #7f8c8d;
        }

        .title-box {
            text-align: center;
            margin-bottom: 30px;
        }

        .title-box h3 {
            background-color: #ecf0f1;
            color: #2c3e50;
            padding: 8px 20px;
            display: inline-block;
            border-radius: 4px;
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
            border: 1px solid #bdc3c7;
        }

        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .content-table td {
            padding: 8px 5px;
            vertical-align: top;
        }

        .label {
            width: 140px;
            font-weight: bold;
            color: #555;
        }

        .value {
            font-weight: 500;
            color: #000;
        }

        .separator {
            width: 10px;
            text-align: center;
        }

        .divider {
            border-top: 1px dashed #bdc3c7;
            margin: 15px 0;
        }

        .status-box {
            float: right;
            border: 2px solid #27ae60;
            /* Warna hijau sukses */
            color: #27ae60;
            padding: 5px 15px;
            font-weight: bold;
            font-size: 14px;
            border-radius: 4px;
            text-transform: uppercase;
            margin-top: -10px;
            /* Geser sedikit ke atas agar sejajar */
        }

        /* Status khusus jika batal/pending */
        .status-box.batal {
            border-color: #c0392b;
            color: #c0392b;
        }

        .status-box.pending {
            border-color: #f39c12;
            color: #f39c12;
        }

        .footer {
            margin-top: 50px;
            font-size: 10px;
            color: #7f8c8d;
            border-top: 1px solid #eee;
            padding-top: 10px;
            display: table;
            width: 100%;
        }

        .footer-left {
            display: table-cell;
            text-align: left;
            width: 50%;
        }

        .footer-right {
            display: table-cell;
            text-align: right;
            width: 50%;
        }

        .signature-area {
            margin-top: 40px;
            text-align: right;
        }

        .signature-line {
            display: inline-block;
            width: 180px;
            border-top: 1px solid #333;
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- KOP SURAT -->
        <div class="header">
            <h2>RS AL-ISLAM BANDUNG</h2>
            <p>Instalasi Rehabilitasi Medik</p>
            <p>Jl. Soekarno Hatta No. 644, Bandung | Telp: (022) 7565588</p>
        </div>

        <!-- JUDUL -->
        <div class="title-box">
            <h3>Bukti Jadwal Terapi</h3>
        </div>

        <!-- TABEL KONTEN -->
        <table class="content-table">
            <tr>
                <td class="label">Nama Pasien</td>
                <td class="separator">:</td>
                <td class="value">{{ $jadwal->pasien->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">No. Rekam Medis</td>
                <td class="separator">:</td>
                <td class="value">{{ $jadwal->pasien->no_rm ?? '-' }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <table class="content-table">
            <tr>
                <td class="label">Layanan Terapi</td>
                <td class="separator">:</td>
                <td class="value">{{ $jadwal->jenis_terapi }}</td>
            </tr>
            <tr>
                <td class="label">Terapis</td>
                <td class="separator">:</td>
                <td class="value">{{ $jadwal->terapis->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Hari, Tanggal</td>
                <td class="separator">:</td>
                <td class="value">{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Waktu</td>
                <td class="separator">:</td>
                <td class="value">
                    {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} -
                    {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }} WIB
                </td>
            </tr>
            <tr>
                <td class="label">Ruangan</td>
                <td class="separator">:</td>
                <td class="value">{{ $jadwal->ruangan ?? '-' }}</td>
            </tr>
        </table>

        <!-- STATUS BOX (Dinamis warna) -->
        @php
            $statusClass = '';
            if ($jadwal->status == 'batal') {
                $statusClass = 'batal';
            }
            if ($jadwal->status == 'pending') {
                $statusClass = 'pending';
            }
        @endphp
        <div style="margin-top: 20px; overflow: hidden;">
            <div class="status-box {{ $statusClass }}">
                {{ strtoupper($jadwal->status) }}
            </div>
        </div>

        <!-- AREA TANDA TANGAN -->
        <div class="signature-area">
            <p style="margin-bottom: 5px;">Bandung, {{ date('d F Y') }}</p>
            <p>Petugas Pendaftaran,</p>
            <div class="signature-line"></div>
            <p style="font-size: 10px; margin-top: 5px;">(Tanda Tangan & Nama Terang)</p>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <div class="footer-left">
                <i>*Harap membawa bukti ini saat datang terapi.</i>
            </div>
            <div class="footer-right">
                Dicetak: {{ now()->format('d/m/Y H:i') }} | ID: #{{ $jadwal->id }}
            </div>
        </div>
    </div>
</body>

</html>
