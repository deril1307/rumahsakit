<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pasien') }}
        </h2>
    </x-slot>

    {{-- 1. LOAD SWEETALERT2 --}}
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>

    {{-- 
        LOGIKA ALPINE.JS (Hanya untuk Modal Tambah & Edit)
        Logika hapus dihapus dari sini dan diganti SweetAlert
    --}}
    <div x-data="{
        showModal: {{ $errors->any() ? 'true' : 'false' }},
        isEdit: false,
        formAction: '{{ route('admin.pasien.store') }}',
        form: {
            id: '',
            nama: '',
            no_rm: '',
            tgl_lahir: '',
            jenis_kelamin: 'Laki-laki',
            alamat: '',
            no_telp: '',
            riwayat_medis: '',
            status: 'Aktif'
        },
    
        openAddModal() {
            this.isEdit = false;
            this.formAction = '{{ route('admin.pasien.store') }}';
            this.form = {
                nama: '',
                no_rm: '',
                tgl_lahir: '',
                jenis_kelamin: 'Laki-laki',
                alamat: '',
                no_telp: '',
                riwayat_medis: '',
                status: 'Aktif'
            };
            this.showModal = true;
        },
    
        openEditModal(url, data) {
            this.isEdit = true;
            this.formAction = url;
            this.form = data;
            this.showModal = true;
        }
    }">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                {{-- Notifikasi Success --}}
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                {{-- Notifikasi Updated --}}
                @if (session('updated'))
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('updated') }}</span>
                    </div>
                @endif

                {{-- Notifikasi Deleted --}}
                @if (session('deleted'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('deleted') }}</span>
                    </div>
                @endif

                {{-- Notifikasi Error --}}
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                {{-- ============================================ --}}
                {{-- CARD: FILTER & PENCARIAN & TOMBOL TAMBAH --}}
                {{-- ============================================ --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        
                        {{-- 1. Filter Abjad (A-Z) --}}
                        <div class="mb-4 flex flex-wrap gap-1 items-center justify-center sm:justify-start">
                            <span class="mr-2 text-sm font-semibold text-gray-600">Filter Nama:</span>
                            
                            {{-- Tombol 'Semua' --}}
                            <a href="{{ route('admin.pasien.index', array_merge(request()->except(['alpha', 'page']))) }}"
                               class="px-2 py-1 text-xs border rounded transition-colors {{ !request('alpha') ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
                               Semua
                            </a>
                            
                            {{-- Loop A-Z --}}
                            @foreach(range('A', 'Z') as $char)
                                <a href="{{ route('admin.pasien.index', array_merge(request()->except('page'), ['alpha' => $char])) }}"
                                   class="px-2 py-1 text-xs border rounded transition-colors {{ request('alpha') == $char ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
                                   {{ $char }}
                                </a>
                            @endforeach
                        </div>

                        <hr class="mb-4 border-gray-200">

                        {{-- 2. Form Filter, Pencarian & Tombol --}}
                        <div class="flex flex-col sm:flex-row justify-between gap-4">

                            {{-- Form Search + Filter Status --}}
                            <form method="GET" action="{{ route('admin.pasien.index') }}" class="flex flex-wrap gap-2 w-full sm:w-auto flex-grow">
                                
                                @if(request('alpha'))
                                    <input type="hidden" name="alpha" value="{{ request('alpha') }}">
                                @endif

                                <select name="status" 
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm py-2"
                                        onchange="this.form.submit()">
                                    <option value="">- Semua Status -</option>
                                    <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Nonaktif" {{ request('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>

                                <div class="relative flex-grow min-w-[200px]">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="w-full pl-10 pr-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm py-2"
                                        placeholder="Cari Nama / No. RM / No. Telp..." />
                                    
                                    @if (request('search'))
                                        <a href="{{ route('admin.pasien.index', request()->except(['search', 'page'])) }}"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500"
                                            title="Hapus pencarian">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>

                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Cari
                                </button>

                            </form>

                            {{-- Tombol Tambah Pasien --}}
                            <div class="flex-shrink-0">
                                <button @click="openAddModal()"
                                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-800 focus:bg-green-700 transition ease-in-out duration-150 py-2">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Pasien
                                </button>
                            </div>

                        </div>

                        {{-- Info Hasil Filter --}}
                        @if(request('search') || request('alpha') || request('status'))
                            <div class="mt-3 text-sm text-gray-600 bg-gray-50 p-2 rounded border border-gray-100">
                                <span class="font-bold">Filter aktif:</span>
                                @if(request('alpha')) <span class="ml-2 px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded-full text-xs">Abjad: {{ request('alpha') }}</span> @endif
                                @if(request('status')) <span class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs">Status: {{ request('status') }}</span> @endif
                                @if(request('search')) <span class="ml-2 px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded-full text-xs">Cari: "{{ request('search') }}"</span> @endif
                            </div>
                        @endif

                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- CARD: TABEL DATA PASIEN --}}
                {{-- ============================================ --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        {{-- Jika tidak ada data --}}
                        @if ($pasienList->isEmpty())
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data pasien</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    @if (request('search') || request('alpha') || request('status'))
                                        Tidak ditemukan data dengan filter yang dipilih.
                                    @else
                                        Mulai dengan menambahkan pasien baru
                                    @endif
                                </p>
                                @if (request('search') || request('alpha') || request('status'))
                                    <div class="mt-6">
                                        <a href="{{ route('admin.pasien.index') }}"
                                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                            Reset Filter
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            {{-- Tabel --}}
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                No
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                Nama Pasien
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                No. RM
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                No. Telp
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($pasienList as $pasien)
                                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                {{-- Nomor Urut --}}
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $pasienList->firstItem() + $loop->index }}
                                                </td>

                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $pasien->nama }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $pasien->no_rm }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $pasien->no_telp }}
                                                </td>
                                                {{-- STATUS BOLD & NETRAL --}}
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $pasien->status }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                        {{-- Tombol UBAH --}}
                                                        <button @click="openEditModal('{{ route('admin.pasien.update', $pasien->id) }}', {
                                                                        id: '{{ $pasien->id }}',
                                                                        nama: '{{ $pasien->nama }}',
                                                                        no_rm: '{{ $pasien->no_rm }}',
                                                                        tgl_lahir: '{{ $pasien->tgl_lahir->format('Y-m-d') }}',
                                                                        jenis_kelamin: '{{ $pasien->jenis_kelamin }}',
                                                                        alamat: `{{ str_replace(["\r", "\n", "'"], ['', '', "\\'"], $pasien->alamat) }}`,
                                                                        no_telp: '{{ $pasien->no_telp }}',
                                                                        riwayat_medis: `{{ str_replace(["\r", "\n", "'"], ['', '', "\\'"], $pasien->riwayat_medis ?? '') }}`,
                                                                        status: '{{ $pasien->status }}'
                                                                    })"
                                                            class="inline-flex items-center px-3 py-1.5 bg-gray-700 border border-transparent rounded text-xs font-bold text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                                                            UBAH
                                                        </button>

                                                        {{-- Tombol HAPUS (DENGAN SWEETALERT) --}}
                                                        <form action="{{ route('admin.pasien.destroy', $pasien->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            
                                                            <button type="submit"
                                                                onclick="confirmDelete(event, '{{ $pasien->nama }}')"
                                                                class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded text-xs font-bold text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                                                                HAPUS
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            <div class="mt-4">
                                {{ $pasienList->links() }}
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        {{-- ========================================== --}}
        {{-- ========= MODAL FORM (ADD/EDIT) ========== --}}
        {{-- ========================================== --}}
        {{-- Modal ini tetap pakai Alpine.js karena butuh binding data form --}}
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false"></div>
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900"
                                x-text="isEdit ? 'Edit Data Pasien' : 'Tambah Pasien Baru'"></h3>
                            <button @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form method="POST" :action="formAction">
                            @csrf
                            <input type="hidden" name="_method" value="PUT" :disabled="!isEdit">

                            {{-- Nama Lengkap --}}
                            <div class="mb-4">
                                <x-input-label for="nama" :value="__('Nama Lengkap')" />
                                <x-text-input x-model="form.nama" id="nama" class="block mt-1 w-full" type="text"
                                    name="nama" required autofocus />
                                @error('nama') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- No. Rekam Medis --}}
                            <div class="mb-4">
                                <x-input-label for="no_rm" :value="__('No. Rekam Medis')" />
                                <x-text-input x-model="form.no_rm" id="no_rm" class="block mt-1 w-full" type="text"
                                    name="no_rm" required maxlength="6" placeholder="Contoh: 012345" />
                                <p class="mt-1 text-xs text-gray-500">*Harus 6 digit angka</p>
                                @error('no_rm') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                {{-- Tanggal Lahir --}}
                                <div>
                                    <x-input-label for="tgl_lahir" :value="__('Tanggal Lahir')" />
                                    <x-text-input x-model="form.tgl_lahir" id="tgl_lahir" class="block mt-1 w-full"
                                        type="date" name="tgl_lahir" required :max="date('Y-m-d')" />
                                    @error('tgl_lahir') <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                {{-- Jenis Kelamin --}}
                                <div>
                                    <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
                                    <select x-model="form.jenis_kelamin" id="jenis_kelamin" name="jenis_kelamin"
                                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Alamat --}}
                            <div class="mb-4">
                                <x-input-label for="alamat" :value="__('Alamat')" />
                                <textarea x-model="form.alamat" id="alamat" name="alamat" required
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    rows="2"></textarea>
                                @error('alamat') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Nomor Telepon --}}
                            <div class="mb-4">
                                <x-input-label for="no_telp" :value="__('Nomor Telepon')" />
                                <x-text-input x-model="form.no_telp" id="no_telp" class="block mt-1 w-full" type="text"
                                    name="no_telp" required placeholder="Contoh: 081234567890" />
                                <p class="mt-1 text-xs text-gray-500">*Format: 08xxxxxxxxxx</p>
                                @error('no_telp') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Riwayat Medis --}}
                            <div class="mb-4">
                                <x-input-label for="riwayat_medis" :value="__('Alergi/Riwayat Medis Penting')" />
                                <textarea x-model="form.riwayat_medis" id="riwayat_medis" name="riwayat_medis"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    rows="2" placeholder="Contoh: Alergi obat X, Riwayat diabetes"></textarea>
                            </div>

                            {{-- Status (hanya muncul saat Edit) --}}
                            <div class="mb-4" x-show="isEdit">
                                <x-input-label for="status" :value="__('Status')" />
                                <select x-model="form.status" id="status" name="status"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="Aktif">Aktif</option>
                                    <option value="Nonaktif">Nonaktif</option>
                                </select>
                            </div>

                            <div class="flex justify-end space-x-2 mt-6">
                                <button type="button" @click="showModal = false"
                                    class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-md font-semibold text-xs uppercase tracking-widest shadow-sm hover:bg-gray-50 transition duration-150">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-green-700 text-white rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-green-800 transition duration-150"
                                    x-text="isEdit ? 'Simpan Perubahan' : 'Simpan'"></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Hapus LAMA (Alpine) SUDAH DIHAPUS --}}

    </div>

    {{-- SCRIPT SWEETALERT (Sama persis dengan User & Terapis) --}}
    <script>
        function confirmDelete(event, name) {
            // Mencegah form langsung terkirim
            event.preventDefault();
            
            // Mencari form terdekat dari tombol yang diklik
            const form = event.target.closest('form');

            // Memunculkan Popup SweetAlert
            Swal.fire({
                title: 'Hapus Pasien?',
                text: "Apakah Anda yakin ingin menghapus data pasien " + name + "? Tindakan ini tidak dapat dibatalkan.",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#dc2626', // Merah
                cancelButtonColor: '#6b7280',  // Abu-abu
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Kembali / Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>