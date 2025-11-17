<x-app-layout>
    {{-- Header Halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Terapis') }}
        </h2>
    </x-slot>

    {{-- WADAH UTAMA ALPINE.JS (PENTING: x-data HARUS DI SINI) --}}
    <div x-data="{ showModal: false }">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                {{-- Pesan Sukses --}}
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex flex-wrap justify-between items-center gap-4">
                            
                            <div class="flex-grow sm:flex-grow-0 sm:w-1/2 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <x-text-input type="text" class="w-full pl-10" placeholder="Cari terapis..." />
                            </div>
                            
                            <button @click="showModal = true" type="button" class="inline-flex items-center px-4 py-2 bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-800 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Terapis Baru
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Spesialisasi</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No. Telp</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    {{-- Loop data $terapisList dari Controller --}}
                                    @foreach ($terapisList as $terapis)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $terapis->name }}
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $terapis->spesialisasi ?? '-' }}
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $terapis->no_telp ?? '-' }}
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @if ($terapis->status == 'Aktif')
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                        Aktif
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                                        Nonaktif
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-3">
                                                    {{-- Saya tambahkan @click di sini juga agar modal terbuka --}}
                                                    <button @click="showModal = true" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                        </svg>
                                                    </button>

                                                    <button class="text-red-600 hover:text-red-900" title="Hapus">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 flex justify-between items-center text-sm text-gray-500">
                            <span>Menampilkan {{ count($terapisList) }} Terapis</span>
                            <div class="flex space-x-1">
                                <button class="px-3 py-1 border rounded-md text-gray-400 bg-white hover:bg-gray-50">Previous</button>
                                <button class="px-3 py-1 border rounded-md bg-green-600 text-white">1</button>
                                <button class="px-3 py-1 border rounded-md text-gray-600 bg-white hover:bg-gray-50">2</button>
                                <button class="px-3 py-1 border rounded-md text-gray-600 bg-white hover:bg-gray-50">Next</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- =============== MODAL FORM =============== --}}
        {{-- ========================================== --}}
        {{-- Pastikan x-show terhubung dengan showModal --}}
        <div x-show="showModal" 
            class="fixed inset-0 z-50 overflow-y-auto" 
            style="display: none;">
            
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900">Tambah Terapis Baru</h3>
                            <button @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form method="POST" action="{{ route('admin.terapis.store') }}">
                            @csrf
                            
                            <div class="mb-4">
                                <x-input-label for="name" :value="__('Nama Lengkap')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" placeholder="Masukkan nama lengkap" required />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="nip" :value="__('Nomor Induk Pegawai (NIP)')" />
                                <x-text-input id="nip" class="block mt-1 w-full" type="text" name="nip" placeholder="Masukkan NIP" required />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="spesialisasi" :value="__('Spesialisasi')" />
                                <select id="spesialisasi" name="spesialisasi" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Pilih Spesialisasi</option>
                                    @foreach($spesialisasiOptions as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <x-input-label for="no_telp" :value="__('Nomor Telepon')" />
                                <x-text-input id="no_telp" class="block mt-1 w-full" type="text" name="no_telp" placeholder="Contoh: 081234567890" required />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" placeholder="Contoh: terapis@mail.com" required />
                            </div>

                            {{-- Note Password --}}
                            <p class="text-xs text-gray-500 mt-2 mb-4">
                                * Password default untuk akun baru adalah: <strong>12345678</strong>
                            </p>

                            <div class="flex justify-end space-x-2 mt-6">
                                <button type="button" @click="showModal = false" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-md font-semibold text-xs uppercase tracking-widest shadow-sm hover:bg-gray-50">
                                    Batal
                                </button>
                                <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-green-800">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div> {{-- Penutup div x-data --}}

</x-app-layout>