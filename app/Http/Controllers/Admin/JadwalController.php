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
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan Library PDF di-import

class JadwalController extends Controller
{
    /**
     * Menampilkan daftar jadwal (Kalender/List)
     */
    public function index()
    {
        // Mengambil data jadwal dengan relasi, diurutkan berdasarkan tanggal terbaru
        $jadwals = Jadwal::with(['pasien', 'terapis'])
                    ->orderBy('tanggal', 'desc')
                    ->orderBy('jam_mulai', 'asc')
                    ->get();
        return view('admin.jadwal.index', compact('jadwals'));
    }

    /**
     * Menampilkan form tambah jadwal
     */
    public function create()
    {
        $pasiens = Pasien::all();
        $terapis = User::role('terapis')->get(); 
        $jenisTerapis = JenisTerapi::all();

        return view('admin.jadwal.create', compact('pasiens', 'terapis', 'jenisTerapis'));
    }

    /**
     * Menyimpan jadwal baru
     */
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
                
                // Cek Bentrok
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

                Jadwal::create([
                    'pasien_id' => $request->pasien_id,
                    'user_id' => $request->user_id,
                    'jenis_terapi' => $request->jenis_terapi,
                    'tanggal' => $tanggal->format('Y-m-d'),
                    'jam_mulai' => $jamMulai,
                    'jam_selesai' => $jamSelesai,
                    'ruangan' => $request->ruangan,
                    'status' => 'terjadwal',
                ]);

                $tanggal->addWeek();
            }

            DB::commit();
            return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
        }
    }

    /**
     * Menampilkan form edit jadwal
     */
    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $pasiens = Pasien::all();
        $terapis = User::role('terapis')->get(); 
        $jenisTerapis = JenisTerapi::all();

        return view('admin.jadwal.edit', compact('jadwal', 'pasiens', 'terapis', 'jenisTerapis'));
    }

    /**
     * Update jadwal yang sudah ada
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
            'status' => 'required|in:terjadwal,selesai,batal,pending', // Tambahan validasi status
        ]);

        // Cek Bentrok (Kecuali jadwal ini sendiri)
        $bentrok = Jadwal::where('user_id', $request->user_id)
            ->where('tanggal', $request->tanggal)
            ->where('id', '!=', $id) // PENTING: Jangan cek jadwal diri sendiri
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

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Menghapus jadwal
     */
    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return redirect()->back()->with('success', 'Jadwal berhasil dihapus.');
    }

    /**
     * Method Baru: Cetak PDF Tiket Jadwal
     * Ini akan mencetak data jadwal yang spesifik berdasarkan ID-nya.
     * ID yang digunakan adalah ID unik dari database, bukan nomor urut tabel.
     */
    public function cetak($id)
    {
        // Cari jadwal berdasarkan ID, jika tidak ketemu tampilkan 404
        $jadwal = Jadwal::with(['pasien', 'terapis'])->findOrFail($id);

        // Load view PDF (pastikan file resources/views/admin/jadwal/pdf.blade.php ada)
        $pdf = Pdf::loadView('admin.jadwal.pdf', compact('jadwal'));
        
        // Atur ukuran kertas struk/tiket (A5 Landscape agar hemat kertas dan pas jadi tiket)
        $pdf->setPaper('a5', 'landscape');

        // Tampilkan di browser dengan nama file yang relevan
        return $pdf->stream('Tiket-Jadwal-' . $jadwal->pasien->no_rm . '.pdf');
    }
}