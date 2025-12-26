<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard & Penjadwalan') }}
            </h2>
            <span class="text-sm text-gray-500 bg-white px-3 py-1 rounded shadow-sm">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- SECTION 1: STATISTIK CEPAT --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500 p-6 flex items-center justify-between">
                    <div>
                        <div class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Pasien</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $totalPasien ?? 0 }}</div>
                    </div>
                    <div class="p-3 rounded-full bg-blue-50 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-500 p-6 flex items-center justify-between">
                    <div>
                        <div class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Terapis</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $totalTerapis ?? 0 }}</div>
                    </div>
                    <div class="p-3 rounded-full bg-indigo-50 text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500 p-6 flex items-center justify-between">
                    <div>
                        <div class="text-gray-500 text-xs font-bold uppercase tracking-wider">Jadwal Hari Ini</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $jadwalHariIni ?? 0 }}</div>
                    </div>
                    <div class="p-3 rounded-full bg-green-50 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- SECTION: PREVIEW JADWAL TERBARU --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900">
                            Jadwal Terbaru (Preview)
                        </h3>
                        <a href="{{ route('admin.jadwal.index') }}"
                            class="text-sm text-indigo-600 hover:text-indigo-900 font-semibold">
                            Lihat Semua Jadwal &rarr;
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tanggal
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Jam</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Pasien
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Terapis
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($jadwalTerbaru as $jadwal)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('D, d M Y') }}
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-800">
                                            {{ $jadwal->pasien->nama ?? '-' }}
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600">
                                            {{ $jadwal->terapis->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 text-xs rounded-full font-bold
                                                {{ $jadwal->status == 'selesai'
                                                    ? 'bg-green-100 text-green-800'
                                                    : ($jadwal->status == 'batal'
                                                        ? 'bg-red-100 text-red-800'
                                                        : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($jadwal->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada
                                            jadwal yang dibuat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>