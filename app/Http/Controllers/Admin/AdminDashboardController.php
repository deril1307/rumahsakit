<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pasien;
use App\Models\Jadwal;
use App\Models\JenisTerapi;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
// TAMBAHAN: Import Facade Artisan agar bisa menjalankan perintah command
use Illuminate\Support\Facades\Artisan; 
use Barryvdh\DomPDF\Facade\Pdf; 
use Carbon\Carbon;

// === TAMBAHAN BARU: Import untuk Excel ===
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class AdminDashboardController extends Controller
{
    /**
     * Tampilkan dashboard admin (PENJADWALAN).
     */
    public function index()
    {
        // ============================================================
        // === TAMBAHAN: AUTO BATALKAN JADWAL SAAT MEMBUKA DASHBOARD ===
        // ============================================================
        // Ini akan menjalankan perintah 'jadwal:auto-batal' di background
        // setiap kali Admin membuka halaman ini.
        Artisan::call('jadwal:auto-batal');
        // ============================================================


        // --- PERBAIKAN DISINI: Mengambil Data Real untuk Dashboard ---

        // 1. Statistik Ringkas
        $totalPasien = Pasien::count();
        $totalTerapis = User::role('terapis')->count();
        $jadwalHariIni = Jadwal::whereDate('tanggal', Carbon::today())->count();

        // 2. Data untuk Form Cepat (Dropdown Jenis Terapi)
        $jenisTerapis = JenisTerapi::all(); 

        // 3. Data Jadwal Terbaru (Preview 5 Terakhir)
        $jadwalTerbaru = Jadwal::with(['pasien', 'terapis'])
                            ->orderBy('tanggal', 'desc')
                            ->orderBy('jam_mulai', 'asc')
                            ->limit(5)
                            ->get();

        // 4. Data Kalender Sederhana (Opsional: Jadwal Minggu Ini)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $jadwalMingguIni = Jadwal::whereBetween('tanggal', [$startOfWeek, $endOfWeek])->get();

        // Kirim data ke view 'admin.dashboard'
        return view('admin.dashboard', compact(
            'totalPasien',
            'totalTerapis',
            'jadwalHariIni',
            'jenisTerapis', 
            'jadwalTerbaru',
            'jadwalMingguIni'
        ));
    }

    // ============================================
    // ========== MANAJEMEN USERS =================
    // ============================================

    public function usersIndex()
    {
        $users = User::with('roles')->get();
        return view('admin.users-index', [
            'users' => $users
        ]);
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.edit-user', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate(['role' => 'required|exists:roles,name']);
        $user->syncRoles($request->role);

        return redirect()->route('admin.users.index')
            ->with('success', 'Role user berhasil diupdate.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    // ============================================
    // ========== MANAJEMEN PASIEN ================
    // ============================================

    public function pasienIndex(Request $request)
    {
        try {
            $search = $request->input('search');
            $alpha  = $request->input('alpha');  // Input baru untuk abjad
            $status = $request->input('status'); // Input baru untuk status

            $pasienList = Pasien::query()
                // 1. Filter Pencarian Teks (Search)
                ->when($search, function ($query, $search) {
                    return $query->where(function($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('no_rm', 'like', "%{$search}%")
                        ->orWhere('no_telp', 'like', "%{$search}%");
                    });
                })
                // 2. Filter Abjad (A-Z) berdasarkan Nama
                ->when($alpha, function ($query, $alpha) {
                    return $query->where('nama', 'like', "{$alpha}%");
                })
                // 3. Filter Status (Aktif/Nonaktif)
                ->when($status, function ($query, $status) {
                    return $query->where('status', $status);
                })
                ->latest()
                ->paginate(10)
                ->withQueryString(); // Agar filter tidak hilang saat pindah halaman (pagination)

            return view('admin.pasien-index', compact('pasienList'));

        } catch (\Exception $e) {
            Log::error('Error in pasienIndex: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data pasien.');
        }
    }

    public function pasienStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'no_rm' => ['required', 'string', 'unique:pasiens,no_rm', 'regex:/^\d{6}$/'],
                'tgl_lahir' => ['required', 'date', 'before:today', 'after:' . now()->subYears(150)->format('Y-m-d')],
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'no_telp' => ['required', 'regex:/^(08|62)[0-9]{9,12}$/'],
                'alamat' => 'required|string',
                'riwayat_medis' => 'nullable|string',
            ]);

            $validated['status'] = 'Aktif';
            Pasien::create($validated);

            Log::info('Pasien created', ['user_id' => auth()->id(), 'no_rm' => $validated['no_rm']]);
            return redirect()->back()->with('success', 'Data Pasien berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating pasien: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function pasienUpdate(Request $request, $id)
    {
        try {
            $pasien = Pasien::findOrFail($id);
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'no_rm' => ['required', 'string', 'unique:pasiens,no_rm,' . $pasien->id, 'regex:/^\d{6}$/'],
                'tgl_lahir' => ['required', 'date', 'before:today', 'after:' . now()->subYears(150)->format('Y-m-d')],
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'no_telp' => ['required', 'regex:/^(08|62)[0-9]{9,12}$/'],
                'alamat' => 'required|string',
                'riwayat_medis' => 'nullable|string',
                'status' => 'required|in:Aktif,Nonaktif',
            ]);

            $pasien->update($validated);
            Log::info('Pasien updated', ['user_id' => auth()->id(), 'pasien_id' => $pasien->id]);
            return redirect()->back()->with('updated', 'Data Pasien berhasil diperbarui.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating pasien: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function pasienDestroy($id)
    {
        try {
            $pasien = Pasien::findOrFail($id);
            $pasien->delete();
            return redirect()->back()->with('deleted', 'Data Pasien berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting pasien: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data pasien.');
        }
    }

    // ============================================
    // ========== MANAJEMEN TERAPIS ===============
    // ============================================

    public function terapisIndex(Request $request)
    {
        try {
            $search = $request->input('search');
            $alpha  = $request->input('alpha');  // Filter Abjad
            $status = $request->input('status'); // Filter Status

            $terapisList = User::role('terapis')
                // 1. Filter Pencarian Teks
                ->when($search, function ($query, $search) {
                    return $query->where(function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('spesialisasi', 'like', "%{$search}%");
                    });
                })
                // 2. Filter Abjad (A-Z) pada Nama
                ->when($alpha, function ($query, $alpha) {
                    return $query->where('name', 'like', "{$alpha}%");
                })
                // 3. Filter Status
                ->when($status, function ($query, $status) {
                    return $query->where('status', $status);
                })
                ->latest()
                ->paginate(10)
                ->withQueryString();

            $spesialisasiOptions = ['Fisioterapi', 'Terapi Okupasi', 'Terapi Wicara', 'Fisioterapi Anak', 'Fisioterapi Stroke'];
            
            return view('admin.terapis-index', compact('terapisList', 'spesialisasiOptions'));

        } catch (\Exception $e) {
            Log::error('Error in terapisIndex: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data terapis.');
        }
    }

    public function terapisStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'nip' => ['required', 'string', 'max:20', 'unique:users,nip'],
                'spesialisasi' => 'required|string',
                'no_telp' => ['required', 'regex:/^(08|62)[0-9]{9,12}$/'],
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'nip' => $validated['nip'],
                'spesialisasi' => $validated['spesialisasi'],
                'no_telp' => $validated['no_telp'],
                'status' => 'Aktif',
                'password' => Hash::make('12345678'),
            ]);
            $user->assignRole('terapis');
            return redirect()->route('admin.terapis.index')->with('success', 'Terapis berhasil ditambahkan!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating terapis: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function terapisEdit($id)
    {
        try {
            $terapis = User::findOrFail($id);
            $spesialisasiOptions = ['Fisioterapi', 'Terapi Okupasi', 'Terapi Wicara', 'Fisioterapi Anak', 'Fisioterapi Stroke'];
            return view('admin.terapis-edit', compact('terapis', 'spesialisasiOptions'));
        } catch (\Exception $e) {
            Log::error('Error in terapisEdit: ' . $e->getMessage());
            return redirect()->route('admin.terapis.index')->with('error', 'Terapis tidak ditemukan.');
        }
    }

    public function terapisUpdate(Request $request, $id)
    {
        try {
            $terapis = User::findOrFail($id);
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $terapis->id,
                'nip' => ['required', 'string', 'max:20', 'unique:users,nip,' . $terapis->id],
                'spesialisasi' => 'required|string',
                'no_telp' => ['required', 'regex:/^(08|62)[0-9]{9,12}$/'],
                'status' => 'required|in:Aktif,Nonaktif',
            ]);

            $terapis->update($validated);
            return redirect()->route('admin.terapis.index')->with('updated', 'Data Terapis berhasil diperbarui.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating terapis: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function terapisDestroy($id)
    {
        try {
            $terapis = User::findOrFail($id);
            $terapis->delete();
            return redirect()->route('admin.terapis.index')->with('deleted', 'Terapis berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting terapis: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data terapis.');
        }
    }

    // ============================================
    // ========== CETAK JADWAL (FITUR LAMA) =======
    // ============================================
    public function cetakJadwal($id_pasien)
    {
        // Biarkan seperti kodingan lama Anda (Dummy Data)
        $data = [
            'nama_pasien' => 'Shidqul Mariska',
            'no_rm' => '123-456-789',
            'no_telp' => '0812-3456-7890',
            'jadwal_list' => [
                [
                    'tanggal' => 'Senin, 28 Okt 2024',
                    'jenis' => 'Fisioterapi Neurologi',
                    'terapis' => 'Budi Santoso, S.Ft',
                    'jam' => '09:00 - 10:00',
                    'status' => 'Terjadwal',
                ],
                // ... data dummy lainnya
            ]
        ];

        $pdf = Pdf::loadView('pdf.jadwal-pasien', $data);
        return $pdf->stream('jadwal-pasien-' . $data['no_rm'] . '.pdf');
    }

    // ============================================
    // ========== LAPORAN & PDF (UPDATE BARU) =====
    // ============================================

    /**
     * MENAMPILKAN HALAMAN LAPORAN ADMIN
     * Update: Sekarang menggunakan Data Real Database agar sinkron dengan PDF
     */
    public function laporanIndex(Request $request)
    {
        // 1. Ambil Filter (Default: Bulan Ini)
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $terapisId = $request->input('terapis_id');
        $jenisTerapi = $request->input('jenis_terapi');

        // 2. Query Data Real Database (BUKAN DUMMY LAGI)
        $query = Jadwal::with(['pasien', 'terapis'])
            ->whereBetween('tanggal', [$startDate, $endDate]);

        if ($terapisId) {
            $query->where('user_id', $terapisId);
        }
        if ($jenisTerapi) {
            $query->where('jenis_terapi', $jenisTerapi);
        }

        // Urutkan berdasarkan tanggal
        $laporan = $query->orderBy('tanggal', 'asc')->get();

        // 3. Data Pendukung Dropdown
        $listTerapis = User::role('terapis')->get();
        $listJenisTerapi = JenisTerapi::all();

        return view('admin.laporan', compact(
            'laporan', 'listTerapis', 'listJenisTerapi',
            'startDate', 'endDate', 'terapisId', 'jenisTerapi'
        ));
    }

    /**
     * EKSPOR LAPORAN KE PDF (METHOD BARU)
     * Ini yang Anda minta ditambahkan
     */
    public function exportPdf(Request $request)
    {
        // 1. Ambil Filter (Sama persis dengan laporanIndex)
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $terapisId = $request->input('terapis_id');
        $jenisTerapi = $request->input('jenis_terapi');

        // 2. Query Data
        $query = Jadwal::with(['pasien', 'terapis'])
            ->whereBetween('tanggal', [$startDate, $endDate]);

        if ($terapisId) {
            $query->where('user_id', $terapisId);
        }
        if ($jenisTerapi) {
            $query->where('jenis_terapi', $jenisTerapi);
        }

        $laporan = $query->orderBy('tanggal', 'asc')->get();

        // 3. Siapkan Data untuk View PDF
        // Kita gunakan view yang sudah ada (milik Kepala Instalasi) agar efisien
        $data = [
            'laporan' => $laporan,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalSesi' => $laporan->count(),
            'totalSelesai' => $laporan->where('status', 'selesai')->count(),
        ];

        // 4. Generate PDF
        $pdf = Pdf::loadView('kepala.laporan_pdf', $data);
        $pdf->setPaper('a4', 'landscape');

        // 5. Stream PDF ke Browser
        return $pdf->stream('Laporan-Jadwal-Admin.pdf');
    }

    /**
     * EKSPOR KE EXCEL (METHOD TAMBAHAN BARU)
     * Menghubungkan ke file App\Exports\LaporanExport
     */
    public function exportExcel(Request $request)
    {
        // 1. Ambil Filter dari URL (Sama seperti PDF/Index)
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // 2. Download Excel
        // Parameter: (Class Exportnya, Nama File Download)
        return Excel::download(new LaporanExport(
            $startDate, 
            $endDate, 
            $request->terapis_id, 
            $request->jenis_terapi
        ), 'Laporan-Jadwal-RS.xlsx');
    }
}