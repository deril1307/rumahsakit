<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

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


    // fungsi cetak jadwal (masih dummy)
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

    public function terapisIndex()
    {
        // Untuk sementara, kita tampilkan view-nya saja
        // Nanti Anda bisa isi dengan data dummy terapis
        return view('admin.terapis-index');
    }
}