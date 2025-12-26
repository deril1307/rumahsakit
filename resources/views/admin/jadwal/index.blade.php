<x-app-layout>
    {{-- Inisialisasi Alpine.js untuk Modal --}}
    <div x-data="{ showDeleteModal: false, deleteUrl: '', deleteName: '' }">

        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Jadwal Terapi') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                        role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                        role="alert">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        {{-- HEADER: Judul, Form Filter & Pencarian & Tombol Tambah --}}
                        <div class="flex flex-col lg:flex-row gap-4 mb-6 items-center justify-between">

                            {{-- GROUP KIRI (Judul + Form Filter + Search) --}}
                            <div class="flex flex-col md:flex-row gap-4 w-full lg:w-auto flex-grow items-center">

                                {{-- Judul --}}
                                <h3 class="text-lg font-medium text-gray-900 whitespace-nowrap hidden lg:block">
                                    Daftar Jadwal
                                </h3>

                                {{-- FORM PENCARIAN & FILTER --}}
                                <form method="GET" action="{{ route('admin.jadwal.index') }}"
                                    class="w-full flex flex-col sm:flex-row gap-2">

                                    {{-- 1. DROPDOWN FILTER WAKTU --}}
                                    <select name="filter" onchange="this.form.submit()"
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm py-2 cursor-pointer">
                                        <option value="terbaru" {{ request('filter') == 'terbaru' ? 'selected' : '' }}>
                                            Urutkan: Terbaru
                                        </option>
                                        <option value="terlama" {{ request('filter') == 'terlama' ? 'selected' : '' }}>
                                            Urutkan: Terlama
                                        </option>
                                        <option disabled>──────────</option>
                                        <option value="hari_ini" {{ request('filter') == 'hari_ini' ? 'selected' : '' }}>
                                            📅 Hari Ini
                                        </option>
                                        <option value="minggu_ini" {{ request('filter') == 'minggu_ini' ? 'selected' : '' }}>
                                            📅 7 Hari Terakhir
                                        </option>
                                        <option value="bulan_ini" {{ request('filter') == 'bulan_ini' ? 'selected' : '' }}>
                                            📅 Bulan Ini
                                        </option>
                                        <option value="bulan_lalu" {{ request('filter') == 'bulan_lalu' ? 'selected' : '' }}>
                                            📅 Bulan Lalu
                                        </option>
                                        <option value="6_bulan" {{ request('filter') == '6_bulan' ? 'selected' : '' }}>
                                            📅 6 Bulan Terakhir
                                        </option>
                                    </select>

                                    {{-- 2. INPUT PENCARIAN --}}
                                    <div class="relative flex-grow w-full">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input type="text" name="search" value="{{ request('search') }}"
                                            class="w-full pl-10 pr-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                            placeholder="Cari Nama Pasien..." />

                                        {{-- Tombol X (Hapus Pencarian) --}}
                                        @if (request('search'))
                                            <a href="{{ route('admin.jadwal.index', request()->except(['search', 'page'])) }}"
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500"
                                                title="Hapus pencarian">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>

                                    {{-- TOMBOL CARI --}}
                                    <button type="submit"
                                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition duration-150 flex-shrink-0">
                                        Cari
                                    </button>
                                </form>
                            </div>

                            {{-- GROUP KANAN (Tombol Tambah) --}}
                            <a href="{{ route('admin.jadwal.create') }}"
                                class="w-full lg:w-auto bg-green-700 hover:bg-green-800 text-white font-bold py-2 px-4 rounded shadow text-center transition duration-150 whitespace-nowrap flex-shrink-0">
                                + Buat Jadwal Baru
                            </a>
                        </div>

                        {{-- Info Hasil Filter --}}
                        @if(request('search') || request('filter'))
                            <div class="mb-4 text-sm text-gray-600 bg-gray-50 p-2 rounded border border-gray-100 flex flex-wrap gap-2 items-center">
                                <span class="font-bold">Filter aktif:</span>
                                
                                @if(request('filter'))
                                    <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded-full text-xs">
                                        @switch(request('filter'))
                                            @case('terlama') Urutkan Terlama @break
                                            @case('hari_ini') Hari Ini @break
                                            @case('minggu_ini') 7 Hari Terakhir @break
                                            @case('bulan_ini') Bulan Ini @break
                                            @case('bulan_lalu') Bulan Lalu @break
                                            @case('6_bulan') 6 Bulan Terakhir @break
                                            @default Urutkan Terbaru
                                        @endswitch
                                    </span>
                                @endif

                                @if(request('search')) 
                                    <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded-full text-xs">
                                        Pencarian: "{{ request('search') }}"
                                    </span> 
                                @endif

                                <a href="{{ route('admin.jadwal.index') }}" class="text-xs text-red-600 hover:underline ml-2">Reset Filter</a>
                            </div>
                        @endif

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal & Jam
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pasien
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Terapis
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jenis Terapi
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ruangan
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($jadwals as $jadwal)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} -
                                                    {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $jadwal->pasien->nama ?? '-' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    RM: {{ $jadwal->pasien->no_rm ?? '-' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $jadwal->terapis->name ?? '-' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $jadwal->jenis_terapi }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $jadwal->ruangan ?? '-' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusClasses = [
                                                        'terjadwal' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                        'selesai' => 'bg-green-100 text-green-800 border-green-200',
                                                        'batal' => 'bg-red-100 text-red-800 border-red-200',
                                                        'pending' => 'bg-gray-100 text-gray-800 border-gray-200',
                                                    ];
                                                    $class = $statusClasses[$jadwal->status] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span
                                                    class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $class }}">
                                                    {{ ucfirst($jadwal->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <div class="flex justify-center space-x-2">

                                                    {{-- TOMBOL UBAH (Gray) --}}
                                                    <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}"
                                                        class="inline-flex items-center px-3 py-1.5 bg-gray-700 border border-transparent rounded text-xs font-bold text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm"
                                                        title="Edit Jadwal">
                                                        UBAH
                                                    </a>

                                                    {{-- TOMBOL CETAK (Indigo/Biru) --}}
                                                    <a href="{{ route('admin.jadwal.cetak', $jadwal->id) }}"
                                                        target="_blank"
                                                        class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded text-xs font-bold text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm"
                                                        title="Cetak PDF">
                                                        CETAK
                                                    </a>

                                                    {{-- TOMBOL HAPUS (Merah) --}}
                                                    <button type="button"
                                                        @click="showDeleteModal = true; 
                                                                deleteUrl = '{{ route('admin.jadwal.destroy', $jadwal->id) }}'; 
                                                                deleteName = '{{ $jadwal->pasien->nama ?? 'Tanpa Nama' }} ({{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }})'"
                                                        class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded text-xs font-bold text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm"
                                                        title="Hapus Jadwal">
                                                        HAPUS
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7"
                                                class="px-6 py-12 whitespace-nowrap text-center text-sm text-gray-500">
                                                @if (request('search') || request('filter'))
                                                    Tidak ditemukan jadwal dengan filter/pencarian ini.
                                                @else
                                                    Belum ada jadwal yang dibuat.
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- PAGINATION LINK --}}
                        <div class="mt-4">
                            @if (method_exists($jadwals, 'links'))
                                {{ $jadwals->links() }}
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL HAPUS --}}
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                @click="showDeleteModal = false">
            </div>
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900">Konfirmasi Hapus Jadwal
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Apakah Anda yakin ingin menghapus jadwal untuk <strong
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
                                class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition duration-150">
                                Hapus
                            </button>
                        </form>
                        <button type="button" @click="showDeleteModal = false"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition duration-150">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>