<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

use PDF;

class AdminDashboardController extends Controller
{
    /**
     * Tampilkan dashboard admin (PENJADWALAN).
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    public function usersIndex()
    {
        // Ambil semua user, sama seperti sebelumnya
        $users = User::with('roles')->get();
        return view('admin.users-index', [
            'users' => $users
        ]);
    }


    /**
     * Tampilkan form untuk mengedit user.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.edit-user', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    /**
     * Update data user (role-nya).
     */
    public function update(Request $request, User $user)
    {
        $request->validate(['role' => 'required|exists:roles,name']);
        $user->syncRoles($request->role);

        // Redirect-nya kembali ke halaman daftar user
        return redirect()->route('admin.users.index')
                         ->with('success', 'Role user berhasil diupdate.');
    }

    /**
     * Hapus user dari database.
     */
    public function destroy(User $user)
    {
        $user->delete();
        
        // Redirect-nya kembali ke halaman daftar user
        return redirect()->route('admin.users.index')
                         ->with('success', 'User berhasil dihapus.');
    }


    // fungsi untuk menangani cetak jadwal (dummy)
    public function cetakJadwal($id_pasien)
    {
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
                [
                    'tanggal' => 'Rabu, 30 Okt 2024',
                    'jenis' => 'Terapi Okupasi',
                    'terapis' => 'Citra Lestari, A.Md.OT',
                    'jam' => '11:00 - 12:00',
                    'status' => 'Terjadwal',
                ],
                [
                    'tanggal' => 'Senin, 21 Okt 2024',
                    'jenis' => 'Fisioterapi Neurologi',
                    'terapis' => 'Budi Santoso, S.Ft',
                    'jam' => '09:00 - 10:00',
                    'status' => 'Selesai',
                ]
            ]
        ];

        // Muat view PDF dengan data
        $pdf = PDF::loadView('pdf.jadwal-pasien', $data);

        // Tampilkan di browser (atau 'download' untuk langsung unduh)
        // 'stream' lebih baik untuk preview
        return $pdf->stream('jadwal-pasien-'.$data['no_rm'].'.pdf');
    }

    // fungsi untuk menangani laporan (dummy)
    public function laporanIndex()
    {
        // Data dummy ini diambil persis dari gambar Anda
        $laporanData = [
            (object)['tanggal' => '14 Okt 2024', 'jam' => '09:00', 'nama_pasien' => 'Siti Aminah', 'no_rm' => 'RM001', 'jenis_terapi' => 'Fisioterapi', 'nama_terapis' => 'Dr. Budi S.', 'status' => 'Selesai'],
            (object)['tanggal' => '14 Okt 2024', 'jam' => '10:00', 'nama_pasien' => 'Bambang Wijoyo', 'no_rm' => 'RM002', 'jenis_terapi' => 'Terapi Okupasi', 'nama_terapis' => 'Dr. Citra L.', 'status' => 'Selesai'],
            (object)['tanggal' => '13 Okt 2024', 'jam' => '11:00', 'nama_pasien' => 'Rina Martina', 'no_rm' => 'RM003', 'jenis_terapi' => 'Fisioterapi', 'nama_terapis' => 'Dr. Budi S.', 'status' => 'Dibatalkan'],
            (object)['tanggal' => '12 Okt 2024', 'jam' => '14:00', 'nama_pasien' => 'Agus Setiawan', 'no_rm' => 'RM004', 'jenis_terapi' => 'Fisioterapi', 'nama_terapis' => 'Dr. Ahmad D.', 'status' => 'Selesai'],
            (object)['tanggal' => '11 Okt 2024', 'jam' => '15:00', 'nama_pasien' => 'Dewi Sartika', 'no_rm' => 'RM005', 'jenis_terapi' => 'Terapi Okupasi', 'nama_terapis' => 'Dr. Citra L.', 'status' => 'Selesai'],
        ];

        // Kita akan kirim data ini ke view 'admin.laporan' yang baru
        return view('admin.laporan', [
            'laporan' => $laporanData
        ]);
    }


    // fungsi untuk menangani pasien (dummy)
    public function pasienIndex()
    {
        // Data dummy dari gambar Anda
        $pasienData = [
            (object)['nama' => 'Amelia Tan', 'no_rm' => '00123456', 'no_telp' => '081234567890', 'status' => 'Aktif'],
            (object)['nama' => 'Budi Santoso', 'no_rm' => '00123457', 'no_telp' => '081234567891', 'status' => 'Aktif'],
            (object)['nama' => 'Citra Dewi', 'no_rm' => '00123458', 'no_telp' => '081234567892', 'status' => 'Nonaktif'],
        ];

        return view('admin.pasien-index', [
            'pasienList' => $pasienData
        ]);
    }

    // fungsi untuk menangani terapis (on progress)
    public function terapisIndex()
    {
        // 1. Ambil data terapis dari DB
        $terapisList = User::role('terapis')->get();
        // 2. Siapkan opsi Spesialisasi (Bisa dari DB atau Array statis dulu)
        $spesialisasiOptions = [
            'Fisioterapi',
            'Terapi Okupasi',
            'Terapi Wicara',
            'Fisioterapi Anak',
            'Fisioterapi Stroke'
        ];

        return view('admin.terapis-index', [
            'terapisList' => $terapisList,
            'spesialisasiOptions' => $spesialisasiOptions 
        ]);
    }

    /**
     * Simpan Terapis Baru 
     */
    public function terapisStore(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nip' => 'required|string|max:20',
            'spesialisasi' => 'required|string',
            'no_telp' => 'required|string|max:15',
        ]);

        // 2. Buat User Baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'spesialisasi' => $request->spesialisasi,
            'no_telp' => $request->no_telp,
            'status' => 'Aktif',
            'password' => Hash::make('12345678'), // <--- PASSWORD DEFAULT
        ]);
        // 3. Berikan Role Terapis
        $user->assignRole('terapis');
        // 4. Redirect kembali
        return redirect()->route('admin.terapis.index')
                         ->with('success', 'Terapis berhasil ditambahkan!');
    }
}