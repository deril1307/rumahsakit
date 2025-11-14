<!DOCTYPE html>
<html lang="id">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Jadwal Terapi Pasien</title>

<style>

@page { size: A4 portrait; margin: 12mm 12mm; }
html, body { margin:0; padding:0; background:#fff; }
body { font-family: DejaVu Sans, sans-serif; font-size:10.5pt; color:#333; line-height:1.4; }

/* Kertas utama */
.page { width:100%; }

/* ==== HEADER BARU (logo di kiri sejajar text) ==== */
.header-table{
    width:100%;
    border-bottom:0.3mm solid #eee;
    padding-bottom:6mm;
    margin-bottom:8mm;
    border-collapse:collapse;
}

.header-logo{
    width:22mm;
    vertical-align:middle;
}

.header-logo img{
    width:20mm;
    display:block;
}

.header-identity{
    vertical-align:middle;
    padding-left:4mm;
}

.header-name{
    font-size:14pt;
    font-weight:700;
    color:#2A7B3E;
    margin:0;
    line-height:1.15;
}

.header-sub{
    font-size:9pt;
    color:#555;
    margin-top:1mm;
}

.header-title{
    width:40%;
    text-align:right;
    vertical-align:middle;
    font-size:13pt;
    font-weight:700;
    color:#2A7B3E;
}

/* ==== SECTION TITLE ==== */
.section-title{
    font-size:11.5pt;
    font-weight:700;
    margin:6mm 0 3mm;
}

/* ==== IDENTITAS PASIEN ==== */
.info-table{
    width:100%;
    font-size:10.5pt;
    margin-bottom:6mm;
    border-collapse:collapse;
}
.info-table td{ padding:1mm 0; }
.info-label{ width:28mm; color:#666; }
.info-value{ font-weight:600; }

/* ==== TABEL JADWAL ==== */
.schedule-header{
    background:#f0fdfa;
    border:0.3mm solid #a7f3d0;
    border-bottom:none;
    padding:3mm;
    border-radius:2mm 2mm 0 0;
}

.schedule-header table{
    width:100%;
    font-size:10pt;
    font-weight:700;
    color:#2A7B3E;
}

.schedule-row{
    background:#fff;
    border:0.3mm solid #eee;
    border-top:none;
    padding:3mm;
}

.schedule-row-last{
    border-radius:0 0 2mm 2mm;
}

.schedule-row table{
    width:100%;
    font-size:10pt;
    border-collapse:collapse;
}

.col-1{ width:22%; }
.col-2{ width:23%; }
.col-3{ width:25%; }
.col-4{ width:15%; }
.col-5{ width:15%; text-align:center; }

/* ==== BADGE STATUS ==== */
.status-badge{
    border-radius:12px;
    padding:1mm 3mm;
    font-size:8.5pt;
    font-weight:700;
    white-space:nowrap;
}

.status-terjadwal{
    background:#fffbeb;
    color:#b45309;
    border:0.3mm solid #fde68a;
}

.status-selesai{
    background:#f0fdfa;
    color:#2A7B3E;
    border:0.3mm solid #a7f3d0;
}

/* ==== CATATAN ==== */
.notes{
    font-size:9pt;
    color:#555;
    margin-top:8mm;
}

.notes ul{
    padding-left:4mm;
    margin:0;
}

/* ==== FOOTER ==== */
.footer-table{
    width:100%;
    margin-top:10mm;
    padding-top:3mm;
    border-top:0.3mm solid #eee;
    font-size:9pt;
    color:#777;
}

.footer-text{ vertical-align:bottom; }
.footer-qr{ text-align:right; vertical-align:top; }

.footer-qr img{
    width:18mm;
    height:18mm;
}

.qr-text{
    font-size:8pt;
    color:#555;
}

</style>
</head>

<body>
<div class="page">

    <!-- ========================= HEADER ========================= -->
    <table class="header-table">
        <tr>
            <!-- Logo -->
            <td class="header-logo">
                <img src="{{ public_path('img/logoRS.png') }}" alt="Logo RS">
            </td>

            <!-- Identitas RS -->
            <td class="header-identity">
                <div class="header-name">RS Al-Islam Bandung</div>
                <div class="header-sub">Pusat Rehabilitasi Medik</div>
            </td>

            <!-- Judul -->
            <td class="header-title">
                Jadwal Terapi Pasien
            </td>
        </tr>
    </table>

    <!-- ========================= IDENTITAS PASIEN ========================= -->
    <div class="section-title">Identitas Pasien</div>

    <table class="info-table">
        <tr>
            <td class="info-label">Nama Pasien</td>
            <td class="info-value">: {{ $nama_pasien ?? 'John Doe' }}</td>
        </tr>
        <tr>
            <td class="info-label">No. RM</td>
            <td class="info-value">: {{ $no_rm ?? '123-456-789' }}</td>
        </tr>
        <tr>
            <td class="info-label">No. Telp</td>
            <td class="info-value">: {{ $no_telp ?? '0812-3456-7890' }}</td>
        </tr>
    </table>

    <!-- ========================= JADWAL TERAPI ========================= -->
    <div class="section-title">Jadwal Terapi</div>

    <div class="schedule-header">
        <table>
            <tr>
                <td class="col-1">Tanggal</td>
                <td class="col-2">Jenis Terapi</td>
                <td class="col-3">Terapis</td>
                <td class="col-4">Jam Terapi</td>
                <td class="col-5">Status</td>
            </tr>
        </table>
    </div>

    @foreach ($jadwal_list as $jadwal)
    <div class="schedule-row {{ $loop->last ? 'schedule-row-last' : '' }}">
        <table>
            <tr>
                <td class="col-1">{{ $jadwal['tanggal'] }}</td>
                <td class="col-2">{{ $jadwal['jenis'] }}</td>
                <td class="col-3">{{ $jadwal['terapis'] }}</td>
                <td class="col-4">{{ $jadwal['jam'] }}</td>
                <td class="col-5">
                    @if ($jadwal['status'] === 'Terjadwal')
                        <span class="status-badge status-terjadwal">Terjadwal</span>
                    @else
                        <span class="status-badge status-selesai">Selesai</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>
    @endforeach
    <div class="notes">
        <div class="section-title" style="margin-bottom:2mm;">Catatan Penting:</div>
        <ul>
            <li>Mohon datang 15 menit sebelum jadwal terapi Anda dimulai.</li>
            <li>Harap segera hubungi bagian pendaftaran jika ingin membatalkan atau mengubah jadwal.</li>
            <li>Untuk keadaan darurat, hubungi IGD RS Al-Islam Bandung di (022) 123-4567.</li>
        </ul>
    </div>
    <table class="footer-table">
        <tr>
            <td class="footer-text">
                Dicetak oleh Petugas Rehabilitasi Medik<br>
                Tanggal cetak: {{ date('d F Y') }}
            </td>

            <td class="footer-qr">
                {{-- <img src="{{ public_path('images/qr-code.png') }}" alt="QR Code"> --}}
                <div class="qr-text">Verifikasi Digital</div>
            </td>
        </tr>
    </table>

</div>
</body>
</html>
