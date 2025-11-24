<?php



namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;

use App\Models\User;

use App\Models\Pasien;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Log;

use Barryvdh\DomPDF\Facade\Pdf; // ← FIX: Import PDF facade yang benar



class AdminDashboardController extends Controller

{

    /**

     * Tampilkan dashboard admin (PENJADWALAN).

     */

    public function index()

    {

        return view('admin.dashboard');

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



    /**

     * INDEX PASIEN dengan SEARCH & PAGINATION

     */

    public function pasienIndex(Request $request)

    {

        try {

            $search = $request->input('search');

           

            $pasienList = Pasien::query()

                ->when($search, function($query, $search) {

                    return $query->where('nama', 'like', "%{$search}%")

                                ->orWhere('no_rm', 'like', "%{$search}%")

                                ->orWhere('no_telp', 'like', "%{$search}%");

                })

                ->latest()

                ->paginate(10)

                ->withQueryString();

           

            return view('admin.pasien-index', compact('pasienList'));

           

        } catch (\Exception $e) {

            Log::error('Error in pasienIndex: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data pasien.');

        }

    }



    /**

     * STORE PASIEN dengan VALIDASI LENGKAP

     */

    public function pasienStore(Request $request)

    {

        try {

            $validated = $request->validate([

                'nama' => 'required|string|max:255',

                'no_rm' => [

                    'required',

                    'string',

                    'unique:pasiens,no_rm',

                    'regex:/^\d{6}$/'

                ],

                'tgl_lahir' => [

                    'required',

                    'date',

                    'before:today',

                    'after:' . now()->subYears(150)->format('Y-m-d')

                ],

                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',

                'no_telp' => [

                    'required',

                    'regex:/^(08|62)[0-9]{9,12}$/'

                ],

                'alamat' => 'required|string',

                'riwayat_medis' => 'nullable|string',

            ], [

                'no_rm.regex' => 'No. RM harus 6 digit angka (contoh: 012345)',

                'no_rm.unique' => 'No. RM sudah terdaftar',

                'tgl_lahir.before' => 'Tanggal lahir tidak boleh di masa depan',

                'tgl_lahir.after' => 'Tanggal lahir tidak valid',

                'no_telp.regex' => 'Format nomor telepon tidak valid. Contoh: 081234567890',

            ]);



            // Set default status jika tidak ada

            $validated['status'] = 'Aktif';



            Pasien::create($validated);

           

            Log::info('Pasien created', [

                'user_id' => auth()->id(),

                'no_rm' => $validated['no_rm']

            ]);

           

            return redirect()->back()->with('success', 'Data Pasien berhasil ditambahkan.');

           

        } catch (\Illuminate\Validation\ValidationException $e) {

            return redirect()->back()

                ->withErrors($e->validator)

                ->withInput();

        } catch (\Exception $e) {

            Log::error('Error creating pasien: ' . $e->getMessage());

            return redirect()->back()

                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())

                ->withInput();

        }

    }



    /**

     * UPDATE PASIEN dengan VALIDASI LENGKAP

     */

    public function pasienUpdate(Request $request, $id)

    {

        try {

            $pasien = Pasien::findOrFail($id);



            $validated = $request->validate([

                'nama' => 'required|string|max:255',

                'no_rm' => [

                    'required',

                    'string',

                    'unique:pasiens,no_rm,' . $pasien->id,

                    'regex:/^\d{6}$/'

                ],

                'tgl_lahir' => [

                    'required',

                    'date',

                    'before:today',

                    'after:' . now()->subYears(150)->format('Y-m-d')

                ],

                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',

                'no_telp' => [

                    'required',

                    'regex:/^(08|62)[0-9]{9,12}$/'

                ],

                'alamat' => 'required|string',

                'riwayat_medis' => 'nullable|string',

                'status' => 'required|in:Aktif,Nonaktif',

            ], [

                'no_rm.regex' => 'No. RM harus 6 digit angka (contoh: 012345)',

                'no_telp.regex' => 'Format nomor telepon tidak valid. Contoh: 081234567890',

            ]);



            $pasien->update($validated);

           

            Log::info('Pasien updated', [

                'user_id' => auth()->id(),

                'pasien_id' => $pasien->id

            ]);



            return redirect()->back()->with('updated', 'Data Pasien berhasil diperbarui.');

           

        } catch (\Illuminate\Validation\ValidationException $e) {

            return redirect()->back()

                ->withErrors($e->validator)

                ->withInput();

        } catch (\Exception $e) {

            Log::error('Error updating pasien: ' . $e->getMessage());

            return redirect()->back()

                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())

                ->withInput();

        }

    }



    /**

     * DELETE PASIEN

     */

    public function pasienDestroy($id)

    {

        try {

            $pasien = Pasien::findOrFail($id);

            $nama = $pasien->nama;

           

            $pasien->delete();

           

            Log::info('Pasien deleted', [

                'user_id' => auth()->id(),

                'pasien_id' => $id,

                'nama' => $nama

            ]);



            return redirect()->back()->with('deleted', 'Data Pasien berhasil dihapus.');

           

        } catch (\Exception $e) {

            Log::error('Error deleting pasien: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal menghapus data pasien.');

        }

    }



    // ============================================

    // ========== MANAJEMEN TERAPIS ===============

    // ============================================



    /**

     * INDEX TERAPIS dengan SEARCH & PAGINATION

     */

    public function terapisIndex(Request $request)

    {

        try {

            $search = $request->input('search');

           

            $terapisList = User::role('terapis')

                ->when($search, function($query, $search) {

                    return $query->where('name', 'like', "%{$search}%")

                                ->orWhere('nip', 'like', "%{$search}%")

                                ->orWhere('email', 'like', "%{$search}%")

                                ->orWhere('spesialisasi', 'like', "%{$search}%");

                })

                ->latest()

                ->paginate(10)

                ->withQueryString();



            $spesialisasiOptions = [

                'Fisioterapi',

                'Terapi Okupasi',

                'Terapi Wicara',

                'Fisioterapi Anak',

                'Fisioterapi Stroke'

            ];



            return view('admin.terapis-index', compact('terapisList', 'spesialisasiOptions'));

           

        } catch (\Exception $e) {

            Log::error('Error in terapisIndex: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data terapis.');

        }

    }



    /**

     * STORE TERAPIS dengan VALIDASI LENGKAP

     */

    public function terapisStore(Request $request)

    {

        try {

            $validated = $request->validate([

                'name' => 'required|string|max:255',

                'email' => 'required|string|email|max:255|unique:users',

                'nip' => [

                    'required',

                    'string',

                    'max:20',

                    'unique:users,nip'

                ],

                'spesialisasi' => 'required|string',

                'no_telp' => [

                    'required',

                    'regex:/^(08|62)[0-9]{9,12}$/'

                ],

            ], [

                'nip.unique' => 'NIP sudah terdaftar',

                'email.unique' => 'Email sudah terdaftar',

                'no_telp.regex' => 'Format nomor telepon tidak valid. Contoh: 081234567890',

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

           

            Log::info('Terapis created', [

                'user_id' => auth()->id(),

                'terapis_id' => $user->id

            ]);



            return redirect()->route('admin.terapis.index')

                             ->with('success', 'Terapis berhasil ditambahkan!');

                             

        } catch (\Illuminate\Validation\ValidationException $e) {

            return redirect()->back()

                ->withErrors($e->validator)

                ->withInput();

        } catch (\Exception $e) {

            Log::error('Error creating terapis: ' . $e->getMessage());

            return redirect()->back()

                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())

                ->withInput();

        }

    }



    /**

     * EDIT TERAPIS

     */

    public function terapisEdit($id)

    {

        try {

            $terapis = User::findOrFail($id);



            $spesialisasiOptions = [

                'Fisioterapi',

                'Terapi Okupasi',

                'Terapi Wicara',

                'Fisioterapi Anak',

                'Fisioterapi Stroke'

            ];



            return view('admin.terapis-edit', compact('terapis', 'spesialisasiOptions'));

           

        } catch (\Exception $e) {

            Log::error('Error in terapisEdit: ' . $e->getMessage());

            return redirect()->route('admin.terapis.index')

                ->with('error', 'Terapis tidak ditemukan.');

        }

    }



    /**

     * UPDATE TERAPIS dengan VALIDASI LENGKAP

     */

    public function terapisUpdate(Request $request, $id)

    {

        try {

            $terapis = User::findOrFail($id);



            $validated = $request->validate([

                'name' => 'required|string|max:255',

                'email' => 'required|string|email|max:255|unique:users,email,' . $terapis->id,

                'nip' => [

                    'required',

                    'string',

                    'max:20',

                    'unique:users,nip,' . $terapis->id

                ],

                'spesialisasi' => 'required|string',

                'no_telp' => [

                    'required',

                    'regex:/^(08|62)[0-9]{9,12}$/'

                ],

                'status' => 'required|in:Aktif,Nonaktif',

            ], [

                'nip.unique' => 'NIP sudah terdaftar',

                'no_telp.regex' => 'Format nomor telepon tidak valid. Contoh: 081234567890',

            ]);



            $terapis->update($validated);

           

            Log::info('Terapis updated', [

                'user_id' => auth()->id(),

                'terapis_id' => $terapis->id

            ]);



            return redirect()->route('admin.terapis.index')

                             ->with('updated', 'Data Terapis berhasil diperbarui.');

                             

        } catch (\Illuminate\Validation\ValidationException $e) {

            return redirect()->back()

                ->withErrors($e->validator)

                ->withInput();

        } catch (\Exception $e) {

            Log::error('Error updating terapis: ' . $e->getMessage());

            return redirect()->back()

                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())

                ->withInput();

        }

    }



    /**

     * DELETE TERAPIS

     */

    public function terapisDestroy($id)

    {

        try {

            $terapis = User::findOrFail($id);

            $nama = $terapis->name;

           

            $terapis->delete();

           

            Log::info('Terapis deleted', [

                'user_id' => auth()->id(),

                'terapis_id' => $id,

                'nama' => $nama

            ]);



            return redirect()->route('admin.terapis.index')

                             ->with('deleted', 'Terapis berhasil dihapus.');

                             

        } catch (\Exception $e) {

            Log::error('Error deleting terapis: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal menghapus data terapis.');

        }

    }



    // ============================================

    // ========== LAPORAN & PDF ===================

    // ============================================



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



        $pdf = Pdf::loadView('pdf.jadwal-pasien', $data);

        return $pdf->stream('jadwal-pasien-'.$data['no_rm'].'.pdf');

    }



    public function laporanIndex()

    {

        $laporanData = [

            (object)['tanggal' => '14 Okt 2024', 'jam' => '09:00', 'nama_pasien' => 'Siti Aminah', 'no_rm' => 'RM001', 'jenis_terapi' => 'Fisioterapi', 'nama_terapis' => 'Dr. Budi S.', 'status' => 'Selesai'],

            (object)['tanggal' => '14 Okt 2024', 'jam' => '10:00', 'nama_pasien' => 'Bambang Wijoyo', 'no_rm' => 'RM002', 'jenis_terapi' => 'Terapi Okupasi', 'nama_terapis' => 'Dr. Citra L.', 'status' => 'Selesai'],

            (object)['tanggal' => '13 Okt 2024', 'jam' => '11:00', 'nama_pasien' => 'Rina Martina', 'no_rm' => 'RM003', 'jenis_terapi' => 'Fisioterapi', 'nama_terapis' => 'Dr. Budi S.', 'status' => 'Dibatalkan'],

            (object)['tanggal' => '12 Okt 2024', 'jam' => '14:00', 'nama_pasien' => 'Agus Setiawan', 'no_rm' => 'RM004', 'jenis_terapi' => 'Fisioterapi', 'nama_terapis' => 'Dr. Ahmad D.', 'status' => 'Selesai'],

            (object)['tanggal' => '11 Okt 2024', 'jam' => '15:00', 'nama_pasien' => 'Dewi Sartika', 'no_rm' => 'RM005', 'jenis_terapi' => 'Terapi Okupasi', 'nama_terapis' => 'Dr. Citra L.', 'status' => 'Selesai'],

        ];



        return view('admin.laporan', [

            'laporan' => $laporanData

        ]);

    }

}



