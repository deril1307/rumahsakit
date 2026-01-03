<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Laporan Kinerja Instalasi') }}
            </h2>
            {{-- Tombol Export PDF --}}
            <a href="{{ route('kepala.laporan.cetak', request()->all()) }}" target="_blank"
                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                Cetak PDF
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- SECTION 1: FILTER DATA --}}
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <form method="GET" action="{{ route('kepala.laporan') }}">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div>
                            <x-input-label for="start_date" :value="__('Dari Tanggal')" />
                            <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date"
                                :value="$startDate" />
                        </div>
                        <div>
                            <x-input-label for="end_date" :value="__('Sampai Tanggal')" />
                            <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date"
                                :value="$endDate" />
                        </div>

                        <div>
                            <x-input-label for="terapis_id" :value="__('Terapis')" />
                            <select name="terapis_id"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Semua Terapis</option>
                                @foreach ($listTerapis as $t)
                                    <option value="{{ $t->id }}" {{ $terapisId == $t->id ? 'selected' : '' }}>
                                        {{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex space-x-2">
                            <x-primary-button class="w-full justify-center">Filter</x-primary-button>
                            <a href="{{ route('kepala.laporan') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none transition ease-in-out duration-150">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- SECTION 2: TAMPILAN LAPORAN (KERTAS) --}}
            <div class="bg-white shadow-lg sm:rounded-lg overflow-hidden border border-gray-200">
                <div class="p-8">

                    {{-- KOP LAPORAN --}}
                    <div class="text-center mb-8 border-b-2 border-gray-800 pb-4">
                        <h2 class="text-2xl font-bold text-gray-800 uppercase tracking-wide">Laporan Kinerja
                            Rehabilitasi Medik</h2>
                        <p class="text-gray-600 text-sm mt-1">RS Al-Islam Bandung</p>
                        <p class="text-gray-500 text-xs mt-2 font-semibold">
                            Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} -
                            {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
                        </p>
                    </div>

                    {{-- TABEL DATA --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300 text-sm">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700 uppercase text-xs leading-normal">
                                    <th class="border border-gray-300 py-3 px-4 text-center w-16">No</th>
                                    <th class="border border-gray-300 py-3 px-4 text-left">Tanggal & Jam</th>
                                    <th class="border border-gray-300 py-3 px-4 text-left">Pasien</th>
                                    <th class="border border-gray-300 py-3 px-4 text-left">Terapis</th>
                                    <th class="border border-gray-300 py-3 px-4 text-left">Layanan</th>
                                    <th class="border border-gray-300 py-3 px-4 text-left">Ruangan</th>
                                    <th class="border border-gray-300 py-3 px-4 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 font-light">
                                @forelse($laporan as $row)
                                    <tr class="border-b border-gray-300 hover:bg-gray-50 transition">
                                        <td class="border border-gray-300 py-3 px-4 text-center font-bold">
                                            {{ $loop->iteration }}</td>
                                        <td class="border border-gray-300 py-3 px-4">
                                            <div class="font-bold text-gray-700">{{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d/m/Y') }}
                                            </div>
                                            <div class="text-xs">
                                                {{ \Carbon\Carbon::parse($row->jam_mulai)->format('H:i') }}</div>
                                        </td>
                                        <td class="border border-gray-300 py-3 px-4">
                                            <div class="font-bold text-gray-800">{{ $row->pasien->nama }}</div>
                                            <div class="text-xs text-gray-500">RM: {{ $row->pasien->no_rm }}</div>
                                        </td>
                                        
                                        {{-- Kolom Terapis --}}
                                        <td class="border border-gray-300 py-3 px-4">{{ $row->terapis->name }}</td>
                                        
                                        {{-- Kolom Layanan (Sudah Dihapus Warna Birunya) --}}
                                        <td class="border border-gray-300 py-3 px-4">
                                            {{ $row->jenis_terapi }}
                                        </td>

                                        <td class="border border-gray-300 py-3 px-4">{{ $row->ruangan ?? '-' }}</td>
                                        <td class="border border-gray-300 py-3 px-4 text-center">
                                            @php
                                                $statusColors = [
                                                    'selesai' => 'bg-green-100 text-green-700',
                                                    'batal' => 'bg-red-100 text-red-700',
                                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                                    'terjadwal' => 'bg-gray-100 text-gray-700',
                                                ];
                                                $class = $statusColors[$row->status] ?? 'bg-gray-100 text-gray-700';
                                            @endphp
                                            <span
                                                class="{{ $class }} py-1 px-3 rounded-full text-xs font-bold uppercase">
                                                {{ ucfirst($row->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7"
                                            class="py-6 px-4 text-center text-gray-500 italic bg-gray-50">
                                            Tidak ada data laporan pada periode ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- RINGKASAN / SUMMARY --}}
                    <div class="mt-8 flex justify-end">
                        <div class="w-full md:w-1/3 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-bold text-gray-800 mb-3 border-b pb-2">Ringkasan Kinerja</h3>
                            <table class="w-full text-sm">
                                <tr>
                                    <td class="py-1 text-gray-600">Total Jadwal</td>
                                    <td class="py-1 font-bold text-right">{{ $totalSesi }}</td>
                                </tr>
                                <tr>
                                    <td class="py-1 text-gray-600">Selesai</td>
                                    <td class="py-1 font-bold text-green-600 text-right">{{ $totalSelesai }}</td>
                                </tr>
                                <tr>
                                    <td class="py-1 text-gray-600">Dibatalkan</td>
                                    <td class="py-1 font-bold text-red-600 text-right">{{ $totalBatal }}</td>
                                </tr>
                                <tr class="border-t border-gray-300 mt-2">
                                    <td class="py-2 font-bold text-gray-800 pt-3">Persentase Kehadiran</td>
                                    <td class="py-2 font-bold text-blue-600 text-right pt-3">
                                        {{ $totalSesi > 0 ? round(($totalSelesai / $totalSesi) * 100) : 0 }}%
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- FOOTER LAPORAN --}}
                    <div class="mt-12 text-right">
                        {{-- Menggunakan Translated Format juga di Footer agar konsisten --}}
                        <p class="text-gray-600 text-sm">Bandung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                        <div class="h-16"></div>
                        <p class="font-bold text-gray-800 border-t border-gray-400 inline-block min-w-[200px] pt-2">
                            Kepala Instalasi Rehabilitasi Medik
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>