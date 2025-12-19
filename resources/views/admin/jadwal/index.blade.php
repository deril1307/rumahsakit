<x-app-layout>
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
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- HEADER: Judul, Form Pencarian & Tombol Tambah --}}
                    {{-- PERBAIKAN: Layout diatur agar pencarian lebih leluasa --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        
                        {{-- Bagian Kiri: Judul & Search --}}
                        {{-- PERBAIKAN: Menghapus w-2/3 agar area ini bisa melebar sesuai isi (form search) --}}
                        <div class="flex flex-col sm:flex-row gap-4 items-center w-full sm:w-auto">
                            <h3 class="text-lg font-medium text-gray-900 whitespace-nowrap">Daftar Jadwal Pasien</h3>

                            {{-- FORM PENCARIAN --}}
                            {{-- PERBAIKAN: Mengganti 'sm:w-auto' menjadi 'sm:w-96' (lebih lebar/panjang ke kanan) --}}
                            <form method="GET" action="{{ route('admin.jadwal.index') }}" class="w-full sm:w-96 flex gap-2">
                                
                                {{-- Wrapper Input --}}
                                <div class="relative flex-grow">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="w-full pl-10 pr-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                        placeholder="Cari Nama Pasien..." />

                                    {{-- Tombol X (Hapus Pencarian) --}}
                                    @if (request('search'))
                                        <a href="{{ route('admin.jadwal.index') }}"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500"
                                            title="Hapus pencarian">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>

                                {{-- TOMBOL CARI --}}
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition duration-150">
                                    Cari
                                </button>
                            </form>
                        </div>

                        {{-- Bagian Kanan: Tombol Tambah --}}
                        <a href="{{ route('admin.jadwal.create') }}"
                            class="w-full sm:w-auto bg-green-700 hover:bg-green-800 text-white font-bold py-2 px-4 rounded shadow text-center transition duration-150">
                            + Buat Jadwal Baru
                        </a>
                    </div>

                    {{-- Info Hasil Pencarian --}}
                    @if(request('search'))
                        <div class="mb-4 text-sm text-gray-600 bg-gray-50 p-2 rounded border border-gray-100">
                            Menampilkan jadwal untuk pasien dengan nama: <strong>"{{ request('search') }}"</strong>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal & Jam
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pasien
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Terapis
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jenis Terapi
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ruangan
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                                            <div class="text-sm text-gray-900">{{ $jadwal->terapis->name ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                                {{ $jadwal->jenis_terapi }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $jadwal->ruangan ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClasses = [
                                                    'terjadwal' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                    'selesai'   => 'bg-green-100 text-green-800 border-green-200',
                                                    'batal'     => 'bg-red-100 text-red-800 border-red-200',
                                                    'pending'   => 'bg-gray-100 text-gray-800 border-gray-200',
                                                ];
                                                $class = $statusClasses[$jadwal->status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $class }}">
                                                {{ ucfirst($jadwal->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <div class="flex justify-center space-x-3">

                                                {{-- TOMBOL EDIT --}}
                                                <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}"
                                                    class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                    </svg>
                                                </a>

                                                {{-- TOMBOL CETAK PDF --}}
                                                <a href="{{ route('admin.jadwal.cetak', $jadwal->id) }}"
                                                    target="_blank" class="text-indigo-600 hover:text-indigo-900"
                                                    title="Cetak PDF">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                                                    </svg>
                                                </a>

                                                {{-- TOMBOL HAPUS --}}
                                                <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                                        title="Hapus">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 whitespace-nowrap text-center text-sm text-gray-500">
                                            @if(request('search'))
                                                Tidak ditemukan jadwal untuk pasien bernama "<strong>{{ request('search') }}</strong>"
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
                        @if(method_exists($jadwals, 'links'))
                            {{ $jadwals->links() }}
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>