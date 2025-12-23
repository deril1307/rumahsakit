<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Terapis') }}
        </h2>
    </x-slot>

    {{--
    LOGIKA ALPINE.JS (Satu Modal untuk Tambah & Edit + Modal Hapus)
    --}}
    <div x-data="{ 
        showModal: {{ $errors->any() ? 'true' : 'false' }},
        showDeleteModal: false,
        isEdit: false,
        formAction: '{{ route('admin.terapis.store') }}',
        deleteUrl: '',
        deleteName: '',
        form: {
            name: '',
            nip: '',
            spesialisasi: '',
            no_telp: '',
            email: '',
            status: 'Aktif'
        },

        openAddModal() {
            this.isEdit = false;
            this.formAction = '{{ route('admin.terapis.store') }}';
            // Reset form
            this.form = { 
                name: '', nip: '', spesialisasi: '', no_telp: '', email: '', status: 'Aktif' 
            };
            this.showModal = true;
        },

        openEditModal(url, data) {
            this.isEdit = true;
            this.formAction = url;
            this.form = data; // Isi form dengan data baris
            this.showModal = true;
        },

        openDeleteModal(url, name) {
            this.deleteUrl = url;
            this.deleteName = name;
            this.showDeleteModal = true;
        }
    }">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                {{-- Notifikasi --}}
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        {{ session('success') }}
                        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">
                            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                        </button>
                    </div>
                @endif
                @if(session('updated'))
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4">
                        {{ session('updated') }}
                        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">
                            <svg class="fill-current h-6 w-6 text-blue-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                        </button>
                    </div>
                @endif
                @if(session('deleted'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        {{ session('deleted') }}
                        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">
                            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                        </button>
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
                            <a href="{{ route('admin.terapis.index', array_merge(request()->except(['alpha', 'page']))) }}"
                               class="px-2 py-1 text-xs border rounded transition-colors {{ !request('alpha') ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
                               Semua
                            </a>
                            
                            {{-- Loop A-Z --}}
                            @foreach(range('A', 'Z') as $char)
                                <a href="{{ route('admin.terapis.index', array_merge(request()->except('page'), ['alpha' => $char])) }}"
                                   class="px-2 py-1 text-xs border rounded transition-colors {{ request('alpha') == $char ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
                                   {{ $char }}
                                </a>
                            @endforeach
                        </div>

                        <hr class="mb-4 border-gray-200">

                        {{-- 2. Form Filter, Pencarian & Tombol --}}
                        <div class="flex flex-col sm:flex-row justify-between gap-4">

                            {{-- Form Search + Filter Status --}}
                            <form method="GET" action="{{ route('admin.terapis.index') }}" class="flex flex-wrap gap-2 w-full sm:w-auto flex-grow">
                                
                                {{-- Input Hidden untuk menjaga filter Abjad saat menekan tombol cari --}}
                                @if(request('alpha'))
                                    <input type="hidden" name="alpha" value="{{ request('alpha') }}">
                                @endif

                                {{-- Dropdown Status --}}
                                <select name="status" 
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm py-2"
                                        onchange="this.form.submit()">
                                    <option value="">- Semua Status -</option>
                                    <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Nonaktif" {{ request('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>

                                {{-- Input Text Pencarian --}}
                                <div class="relative flex-grow min-w-[200px]">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="w-full pl-10 pr-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm py-2"
                                        placeholder="Cari Nama / NIP / Spesialisasi..." />
                                    
                                    {{-- Tombol X (Clear Search) --}}
                                    @if (request('search'))
                                        <a href="{{ route('admin.terapis.index', request()->except(['search', 'page'])) }}"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500"
                                            title="Hapus pencarian">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>

                                {{-- Tombol CARI (ENTER) --}}
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Cari
                                </button>

                            </form>

                            {{-- Tombol Tambah Terapis --}}
                            <div class="flex-shrink-0">
                                <button @click="openAddModal()"
                                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-800 focus:bg-green-700 transition ease-in-out duration-150 py-2">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Terapis Baru
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

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        @if ($terapisList->isEmpty())
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data terapis</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    @if (request('search') || request('alpha') || request('status'))
                                        Tidak ditemukan data dengan filter yang dipilih.
                                    @else
                                        Mulai dengan menambahkan terapis baru.
                                    @endif
                                </p>
                                @if (request('search') || request('alpha') || request('status'))
                                    <div class="mt-6">
                                        <a href="{{ route('admin.terapis.index') }}"
                                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                            Reset Filter
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                No</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                Nama</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                NIP</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                Spesialisasi</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                No. Telp</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                Status</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($terapisList as $terapis)
                                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $terapisList->firstItem() + $loop->index }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $terapis->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $terapis->nip ?? '-' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $terapis->spesialisasi ?? '-' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $terapis->no_telp ?? '-' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <span
                                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $terapis->status == 'Aktif' ? 'bg-green-100 text-green-800 border-green-200' : 'bg-gray-100 text-gray-800 border-gray-200' }} border">
                                                        {{ $terapis->status }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-3">
                                                        {{-- Tombol Edit --}}
                                                        <button @click="openEditModal('{{ route('admin.terapis.update', $terapis->id) }}', {
                                                                        name: '{{ addslashes($terapis->name) }}',
                                                                        nip: '{{ $terapis->nip }}',
                                                                        spesialisasi: '{{ $terapis->spesialisasi }}',
                                                                        no_telp: '{{ $terapis->no_telp }}',
                                                                        email: '{{ $terapis->email }}',
                                                                        status: '{{ $terapis->status }}'
                                                                    })" class="text-yellow-600 hover:text-yellow-900 transition-colors"
                                                            title="Edit">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                            </svg>
                                                        </button>

                                                        {{-- Tombol Hapus --}}
                                                        <button
                                                            @click="openDeleteModal('{{ route('admin.terapis.destroy', $terapis->id) }}', '{{ addslashes($terapis->name) }}')"
                                                            class="text-red-600 hover:text-red-900 transition-colors" title="Hapus">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                {{ $terapisList->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- ========= MODAL FORM (ADD/EDIT) ========== --}}
        {{-- ========================================== --}}
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false"></div>
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900"
                                x-text="isEdit ? 'Edit Data Terapis' : 'Tambah Terapis Baru'"></h3>
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

                            <div class="mb-4">
                                <x-input-label for="name" :value="__('Nama Lengkap')" />
                                <x-text-input x-model="form.name" id="name" class="block mt-1 w-full" type="text"
                                    name="name" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="nip" :value="__('Nomor Induk Pegawai (NIP)')" />
                                <x-text-input x-model="form.nip" id="nip" class="block mt-1 w-full" type="text"
                                    name="nip" required />
                                <x-input-error :messages="$errors->get('nip')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="spesialisasi" :value="__('Spesialisasi')" />
                                <select x-model="form.spesialisasi" id="spesialisasi" name="spesialisasi"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Pilih Spesialisasi</option>
                                    @foreach($spesialisasiOptions as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('spesialisasi')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="no_telp" :value="__('Nomor Telepon')" />
                                <x-text-input x-model="form.no_telp" id="no_telp" class="block mt-1 w-full" type="text"
                                    name="no_telp" required />
                                <x-input-error :messages="$errors->get('no_telp')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input x-model="form.email" id="email" class="block mt-1 w-full" type="email"
                                    name="email" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div class="mb-4" x-show="isEdit">
                                <x-input-label for="status" :value="__('Status Akun')" />
                                <select x-model="form.status" id="status" name="status"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="Aktif">Aktif</option>
                                    <option value="Nonaktif">Nonaktif</option>
                                </select>
                            </div>

                            <p x-show="!isEdit" class="text-xs text-gray-500 mt-2 mb-4">
                                * Password default untuk akun baru adalah: <strong>12345678</strong>
                            </p>

                            <div class="flex justify-end space-x-2 mt-6">
                                <button type="button" @click="showModal = false"
                                    class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-md font-semibold text-xs uppercase tracking-widest shadow-sm hover:bg-gray-50 transition duration-150">Batal</button>
                                <button type="submit"
                                    class="px-4 py-2 bg-green-700 text-white rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-green-800 transition duration-150"
                                    x-text="isEdit ? 'Simpan Perubahan' : 'Simpan'"></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- ======== MODAL KONFIRMASI HAPUS ========== --}}
        {{-- ========================================== --}}
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showDeleteModal = false">
            </div>
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900">Konfirmasi Hapus Terapis</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Apakah Anda yakin ingin menghapus data terapis <strong
                                            x-text="deleteName"></strong>? Tindakan ini tidak dapat dibatalkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <form method="POST" :action="deleteUrl">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition">Hapus</button>
                        </form>
                        <button type="button" @click="showDeleteModal = false"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition">Batal</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- CSS untuk x-cloak (mencegah flash of unstyled content) --}}
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>