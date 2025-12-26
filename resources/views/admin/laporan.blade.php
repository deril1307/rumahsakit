<x-app-layout>
    {{-- Header Halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Harian & Ekspor Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('admin.laporan.index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                            {{-- Filter Tanggal Mulai --}}
                            <div>
                                <x-input-label for="start_date" :value="__('Dari Tanggal')" />
                                <x-text-input id="start_date" name="start_date" type="date" class="block mt-1 w-full"
                                    value="{{ $startDate }}" />
                            </div>

                            {{-- Filter Tanggal Selesai --}}
                            <div>
                                <x-input-label for="end_date" :value="__('Sampai Tanggal')" />
                                <x-text-input id="end_date" name="end_date" type="date" class="block mt-1 w-full"
                                    value="{{ $endDate }}" />
                            </div>

                            {{-- Filter Terapis (Dinamis dari Database) --}}
                            <div>
                                <x-input-label for="terapis_id" :value="__('Nama Terapis')" />
                                <select name="terapis_id" id="terapis_id"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Semua Terapis</option>
                                    @foreach ($listTerapis as $terapis)
                                        <option value="{{ $terapis->id }}"
                                            {{ $terapisId == $terapis->id ? 'selected' : '' }}>
                                            {{ $terapis->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filter Jenis Terapi (Dinamis dari Database) --}}
                            <div>
                                <x-input-label for="jenis_terapi" :value="__('Jenis Terapi')" />
                                <select name="jenis_terapi" id="jenis_terapi"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Semua Jenis</option>
                                    @foreach ($listJenisTerapi as $jenis)
                                        <option value="{{ $jenis->nama_jenis }}"
                                            {{ $jenisTerapi == $jenis->nama_jenis ? 'selected' : '' }}>
                                            {{ $jenis->nama_jenis }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Tombol Submit Filter --}}
                        <div class="mt-4 flex justify-end">
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Filter Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-end mb-4 space-x-2">
                        {{-- TOMBOL EXCEL (SUDAH DIPERBAIKI) --}}
                        {{-- Menggunakan route('admin.laporan.excel') dan membawa parameter filter --}}
                        <a href="{{ route('admin.laporan.excel', request()->query()) }}"
                            class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                            Ekspor ke Excel
                        </a>

                        {{-- TOMBOL PDF --}}
                        <a href="{{ route('admin.laporan.pdf', request()->query()) }}" target="_blank"
                            class="px-4 py-2 bg-orange-500 text-white text-sm font-medium rounded-md hover:bg-orange-600">
                            Ekspor ke PDF
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jam</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Pasien</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No. RM</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jenis Terapi</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Terapis</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status Akhir</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($laporan as $item)
                                    <tr>
                                        {{-- Format Tanggal & Jam dari Database --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}
                                        </td>
                                        {{-- Mengambil Nama Pasien dari Relasi --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $item->pasien->nama ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $item->pasien->no_rm ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $item->jenis_terapi }}
                                        </td>
                                        {{-- Mengambil Nama Terapis dari Relasi --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $item->terapis->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if ($item->status == 'selesai')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Selesai
                                                </span>
                                            @elseif ($item->status == 'batal')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Dibatalkan
                                                </span>
                                            @elseif ($item->status == 'pending')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            @elseif ($item->status == 'terjadwal')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    Terjadwal
                                                </span>
                                            @else
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            Tidak ada data jadwal sesuai filter.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-center">
                        <p class="text-xs text-gray-500">Menampilkan hasil sesuai filter tanggal di atas.</p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
