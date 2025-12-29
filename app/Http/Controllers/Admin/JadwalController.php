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

        // 1. Logika Pencarian Nama Pasien
        if ($search) {
            $query->whereHas('pasien', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
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
            'ruangan' => 'nullable|string',
            'generate_bulan' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $tanggal = Carbon::parse($request->tanggal);
            $jamMulai = $request->jam_mulai;
            $jamSelesai = $request->jam_selesai;
            $loops = $request->generate_bulan ? 4 : 1; 

            for ($i = 0; $i < $loops; $i++) {
                
                $bentrok = Jadwal::where('user_id', $request->user_id)
                    ->where('tanggal', $tanggal->format('Y-m-d'))
                    ->where(function ($query) use ($jamMulai, $jamSelesai) {
                        $query->whereBetween('jam_mulai', [$jamMulai, $jamSelesai])
                              ->orWhereBetween('jam_selesai', [$jamMulai, $jamSelesai])
                              ->orWhere(function ($q) use ($jamMulai, $jamSelesai) {
                                  $q->where('jam_mulai', '<=', $jamMulai)
                                    ->where('jam_selesai', '>=', $jamSelesai);
                              });
                    })
                    ->exists();

                if ($bentrok) {
                    DB::rollBack();
                    return back()->withErrors(['error' => "Jadwal bentrok untuk terapis tersebut pada tanggal " . $tanggal->format('d-m-Y')]);
                }

                $jadwalBaru = Jadwal::create([
                    'pasien_id' => $request->pasien_id,
                    'user_id' => $request->user_id,
                    'jenis_terapi' => $request->jenis_terapi,
                    'tanggal' => $tanggal->format('Y-m-d'),
                    'jam_mulai' => $jamMulai,
                    'jam_selesai' => $jamSelesai,
                    'ruangan' => $request->ruangan,
                    'status' => 'terjadwal',
                ]);

                // === NOTIFIKASI JADWAL BARU ===
                // Notifikasi ini akan membawa data ID jadwal agar bisa diredirect
                $terapis = User::find($request->user_id);
                if ($terapis) {
                    $terapis->notify(new JadwalBaruNotification($jadwalBaru));
                }
                // ==============================

                $tanggal->addWeek();
            }

            DB::commit();
            return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dibuat.');

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

        $bentrok = Jadwal::where('user_id', $request->user_id)
            ->where('tanggal', $request->tanggal)
            ->where('id', '!=', $id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('jam_mulai', '<=', $request->jam_mulai)
                            ->where('jam_selesai', '>=', $request->jam_selesai);
                      });
            })
            ->exists();

        if ($bentrok) {
            return back()->withErrors(['error' => "Jadwal bentrok dengan sesi lain."]);
        }

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

        // === NOTIFIKASI UPDATE JADWAL ===
        // Notifikasi ini akan membawa data ID jadwal agar bisa diredirect ke detailnya
        $terapis = User::find($request->user_id);
        if ($terapis) {
            $terapis->notify(new JadwalUpdateNotification($jadwal));
        }
        // ================================

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
        $jadwal = Jadwal::with(['pasien', 'terapis'])->findOrFail($id);
        $pdf = Pdf::loadView('admin.jadwal.pdf', compact('jadwal'));
        $pdf->setPaper('a5', 'landscape');
        return $pdf->stream('Tiket-Jadwal-' . $jadwal->pasien->no_rm . '.pdf');
    }
}