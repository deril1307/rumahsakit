<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Pasien;
use App\Models\User;
use App\Models\JenisTerapi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Artisan;
// PENTING: Import Kedua Class Notifikasi
use App\Notifications\JadwalBaruNotification;
use App\Notifications\JadwalUpdateNotification;

class JadwalController extends Controller
{
    /**
     * Menampilkan daftar jadwal (Kalender/List)
     */
    public function index(Request $request)
    {
        // Auto batalkan jadwal telat
        Artisan::call('jadwal:auto-batal');

        // Ambil input pencarian dan filter
        $search = $request->input('search');
        $filter = $request->input('filter'); // Input baru dari dropdown

        // Query Dasar
        $query = Jadwal::with(['pasien', 'terapis']);

        // 1. LOGIKA PENCARIAN (DIPERBARUI)
        // Sekarang mencari di Nama Pasien ATAU Jenis Terapi
        if ($search) {
            $query->where(function($q) use ($search) {
                // Cari berdasarkan Nama Pasien
                $q->whereHas('pasien', function ($subQ) use ($search) {
                    $subQ->where('nama', 'like', "%{$search}%");
                })
                // ATAU Cari berdasarkan Jenis Terapi
                ->orWhere('jenis_terapi', 'like', "%{$search}%");
            });
        }

        // 2. Logika Filter Waktu
        switch ($filter) {
            case 'hari_ini':
                $query->whereDate('tanggal', Carbon::today());
                break;
            case 'minggu_ini': // Seminggu ke belakang (Last 7 Days)
                $query->whereBetween('tanggal', [Carbon::today()->subDays(7), Carbon::today()]);
                break;
            case 'bulan_ini': // Bulan ini (Current Month)
                $query->whereMonth('tanggal', Carbon::now()->month)
                      ->whereYear('tanggal', Carbon::now()->year);
                break;
            case 'bulan_lalu': // Bulan kemarin (Last Month)
                 $query->whereMonth('tanggal', Carbon::now()->subMonth()->month)
                       ->whereYear('tanggal', Carbon::now()->subMonth()->year);
                break;
            case '6_bulan': // 6 Bulan terakhir
                $query->whereBetween('tanggal', [Carbon::today()->subMonths(6), Carbon::today()]);
                break;
            // Default tidak ada filter waktu khusus (menampilkan semua sejarah)
        }

        // 3. Logika Pengurutan (Sorting)
        if ($filter == 'terlama') {
            $query->orderBy('tanggal', 'asc')->orderBy('jam_mulai', 'asc');
        } else {
            // Default: Terbaru
            $query->orderBy('tanggal', 'desc')->orderBy('jam_mulai', 'asc');
        }

        // Eksekusi Pagination
        $jadwals = $query->paginate(10)->withQueryString();

        return view('admin.jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        $pasiens = Pasien::all();
        $terapis = User::role('terapis')->get(); 
        $jenisTerapis = JenisTerapi::all();

        return view('admin.jadwal.create', compact('pasiens', 'terapis', 'jenisTerapis'));
    }

   public function store(Request $request)
    {
        $request->validate([
            'pasien_id' => 'required|exists:pasiens,id',
            'user_id' => 'required|exists:users,id',
            'jenis_terapi' => 'required|string',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'ruangan' => 'nullable|string', // Ruangan opsional, tapi kalau diisi akan divalidasi
            'generate_bulan' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $tanggal = Carbon::parse($request->tanggal);
            $jamMulai = $request->jam_mulai;
            $jamSelesai = $request->jam_selesai;

            // --- LOGIKA: 8x Pertemuan (1 Paket) atau 1x Saja ---
            $totalPertemuan = $request->generate_bulan ? 8 : 1; 
            
            for ($i = 0; $i < $totalPertemuan; $i++) {
                $tanggalFormat = $tanggal->format('Y-m-d');

                // ---------------------------------------------------------
                // 1. CEK BENTROK TERAPIS
                // (Terapis tidak boleh menangani 2 pasien di jam yang sama)
                // ---------------------------------------------------------
                $bentrokTerapis = Jadwal::where('user_id', $request->user_id)
                    ->where('tanggal', $tanggalFormat)
                    ->where('status', '!=', 'batal') // Abaikan yang sudah batal
                    ->where(function ($query) use ($jamMulai, $jamSelesai) {
                        // Logika Overlap Modern & Akurat
                        $query->where('jam_mulai', '<', $jamSelesai)
                              ->where('jam_selesai', '>', $jamMulai);
                    })
                    ->exists();

                if ($bentrokTerapis) {
                    DB::rollBack();
                    return back()->withInput()->withErrors(['user_id' => "Terapis sudah ada jadwal lain pada tanggal " . $tanggal->format('d-m-Y') . " di jam tersebut."]);
                }

                // ---------------------------------------------------------
                // 2. CEK BENTROK RUANGAN (BARU)
                // (Ruangan tidak boleh dipakai 2 orang berbeda di jam sama)
                // ---------------------------------------------------------
                if ($request->filled('ruangan')) {
                    $bentrokRuangan = Jadwal::where('ruangan', $request->ruangan)
                        ->where('tanggal', $tanggalFormat)
                        ->where('status', '!=', 'batal')
                        ->where(function ($query) use ($jamMulai, $jamSelesai) {
                            $query->where('jam_mulai', '<', $jamSelesai)
                                  ->where('jam_selesai', '>', $jamMulai);
                        })
                        ->exists();

                    if ($bentrokRuangan) {
                        DB::rollBack();
                        return back()->withInput()->withErrors(['ruangan' => "Ruangan '$request->ruangan' sudah terpakai pada tanggal " . $tanggal->format('d-m-Y') . " jam segitu. Harap pilih ruangan lain."]);
                    }
                }

                // ---------------------------------------------------------
                // 3. SIMPAN JADWAL
                // ---------------------------------------------------------
                $jadwalBaru = Jadwal::create([
                    'pasien_id' => $request->pasien_id,
                    'user_id' => $request->user_id,
                    'jenis_terapi' => $request->jenis_terapi,
                    'tanggal' => $tanggalFormat,
                    'jam_mulai' => $jamMulai,
                    'jam_selesai' => $jamSelesai,
                    'ruangan' => $request->ruangan,
                    'status' => 'terjadwal',
                ]);

                // Notifikasi (SUDAH DIPERBAIKI: UNCOMMENT)
                $terapis = User::find($request->user_id);
                if ($terapis) {
                    $terapis->notify(new JadwalBaruNotification($jadwalBaru));
                }

                // LOGIKA UNTUK TANGGAL BERIKUTNYA (GENERATE BULAN)
                if ($request->generate_bulan) {
                    $tanggal->addDays(3); // Tambah 3 hari
                    if ($tanggal->isSunday()) {
                        $tanggal->addDay(); // Skip minggu
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dibuat (' . $totalPertemuan . ' sesi).');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
        }
    }


    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $pasiens = Pasien::all();
        $terapis = User::role('terapis')->get(); 
        $jenisTerapis = JenisTerapi::all();

        return view('admin.jadwal.edit', compact('jadwal', 'pasiens', 'terapis', 'jenisTerapis'));
    }

    /**
     * Memperbarui jadwal dengan validasi bentrok & waktu yang ketat
     */
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $request->validate([
            'pasien_id' => 'required|exists:pasiens,id',
            'user_id' => 'required|exists:users,id',
            'jenis_terapi' => 'required|string',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'ruangan' => 'nullable|string',
            'status' => 'required|in:terjadwal,selesai,batal,pending',
        ]);

        $waktuJadwal = Carbon::parse($request->tanggal . ' ' . $request->jam_mulai);
        
        // 1. Cek Masa Lalu
        if ($waktuJadwal->isPast() && $request->status == 'terjadwal') {
             // Izinkan edit jika hanya mengubah status (bukan mengubah jam jadi masa lalu)
        }

        // ---------------------------------------------------------
        // 2. CEK BENTROK TERAPIS
        // ---------------------------------------------------------
        $bentrokTerapis = Jadwal::where('user_id', $request->user_id)
            ->where('tanggal', $request->tanggal)
            ->where('id', '!=', $id)            // Abaikan diri sendiri
            ->where('status', '!=', 'batal')    // Abaikan jadwal batal
            ->where(function ($query) use ($request) {
                $query->where('jam_mulai', '<', $request->jam_selesai)
                      ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->exists();

        if ($bentrokTerapis) {
            return back()->withInput()->withErrors(['user_id' => "Jadwal bentrok! Terapis ini sibuk di jam tersebut."]);
        }

        // ---------------------------------------------------------
        // 3. CEK BENTROK RUANGAN (BARU)
        // ---------------------------------------------------------
        if ($request->filled('ruangan')) {
            $bentrokRuangan = Jadwal::where('ruangan', $request->ruangan)
                ->where('tanggal', $request->tanggal)
                ->where('id', '!=', $id)            // Abaikan diri sendiri
                ->where('status', '!=', 'batal')
                ->where(function ($query) use ($request) {
                    $query->where('jam_mulai', '<', $request->jam_selesai)
                          ->where('jam_selesai', '>', $request->jam_mulai);
                })
                ->exists();

            if ($bentrokRuangan) {
                return back()->withInput()->withErrors(['ruangan' => "Ruangan '$request->ruangan' sudah dipakai oleh sesi lain di jam tersebut."]);
            }
        }

        // Update
        $jadwal->update([
            'pasien_id' => $request->pasien_id,
            'user_id' => $request->user_id,
            'jenis_terapi' => $request->jenis_terapi,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'ruangan' => $request->ruangan,
            'status' => $request->status,
        ]);

        // Notifikasi Update (SUDAH DIPERBAIKI: UNCOMMENT)
        $terapis = User::find($request->user_id);
        if ($terapis) {
            $terapis->notify(new JadwalUpdateNotification($jadwal));
        }

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return redirect()->back()->with('success', 'Jadwal berhasil dihapus.');
    }

   public function cetak($id)
    {
        // 1. Ambil jadwal yang diklik sebagai acuan
        $jadwalDipilih = Jadwal::with(['pasien', 'terapis'])->findOrFail($id);
        
        $tanggalAcuan = \Carbon\Carbon::parse($jadwalDipilih->tanggal);

        // 2. LOGIKA BARU: SMART RANGE
        $startDate = $tanggalAcuan->copy()->subMonth()->startOfMonth(); // Awal bulan lalu
        $endDate   = $tanggalAcuan->copy()->addMonth()->endOfMonth();   // Akhir bulan depan

        $listJadwal = Jadwal::with(['pasien', 'terapis'])
            ->where('pasien_id', $jadwalDipilih->pasien_id) // Hanya pasien ini
            ->where('jenis_terapi', $jadwalDipilih->jenis_terapi) // Hanya jenis terapi ini
            ->whereBetween('tanggal', [$startDate, $endDate]) // Rentang waktu fleksibel
            ->where('status', '!=', 'batal') // Opsional: Jangan cetak yang batal
            ->orderBy('tanggal', 'asc')
            ->get();

        // 3. GENERATE LABEL PERIODE DINAMIS
        if ($listJadwal->count() > 0) {
            $firstDate = \Carbon\Carbon::parse($listJadwal->first()->tanggal);
            $lastDate  = \Carbon\Carbon::parse($listJadwal->last()->tanggal);

            if ($firstDate->format('F Y') == $lastDate->format('F Y')) {
                // Jika bulan dan tahun sama: "Januari 2026"
                $periodeLabel = $firstDate->translatedFormat('F Y');
            } elseif ($firstDate->year == $lastDate->year) {
                // Jika tahun sama beda bulan: "Januari - Februari 2026"
                $periodeLabel = $firstDate->translatedFormat('F') . ' - ' . $lastDate->translatedFormat('F Y');
            } else {
                // Jika beda tahun: "Desember 2025 - Januari 2026"
                $periodeLabel = $firstDate->translatedFormat('F Y') . ' - ' . $lastDate->translatedFormat('F Y');
            }
        } else {
            $periodeLabel = $tanggalAcuan->translatedFormat('F Y');
        }

        // 4. Siapkan data
        $data = [
            'pasien' => $jadwalDipilih->pasien,
            'terapis' => $jadwalDipilih->terapis,
            'jenis_terapi' => $jadwalDipilih->jenis_terapi,
            'periode' => $periodeLabel, // Label periode otomatis
            'listJadwal' => $listJadwal
        ];

        $pdf = Pdf::loadView('admin.jadwal.pdf', $data);
        $pdf->setPaper('a4', 'portrait'); 
        return $pdf->stream('Jadwal-Terapi-' . $jadwalDipilih->pasien->no_rm . '.pdf');
    }

    
}