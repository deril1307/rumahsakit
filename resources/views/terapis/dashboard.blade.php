<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Terapis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- INFO STATISTIK -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500 p-6">
                    <div class="text-blue-500 text-sm font-bold uppercase">Total Pasien Hari Ini</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $totalPasienHariIni ?? 0 }}</div>
                </div>
                <div class="bg-green-50 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500 p-6">
                    <div class="text-green-600 text-sm font-bold uppercase">Selesai Ditangani</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $pasienSelesai ?? 0 }}</div>
                </div>
                <div class="bg-yellow-50 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-yellow-500 p-6">
                    <div class="text-yellow-600 text-sm font-bold uppercase">Menunggu / Pending</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $pasienMenunggu ?? 0 }}</div>
                </div>
            </div>

            <!-- CARD: DAFTAR JADWAL -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <h3 class="text-lg font-bold text-gray-900">
                            Jadwal Terapi: <span class="text-indigo-600">{{ $labelFilter }}</span>
                        </h3>

                        <!-- FORM FILTER JADWAL -->
                        <form method="GET" action="{{ route('terapis.dashboard') }}" class="flex items-center">
                            <label for="filter" class="mr-2 text-sm text-gray-600 font-semibold">Tampilkan:</label>
                            <select name="filter" id="filter" onchange="this.form.submit()"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2 pl-3 pr-10">
                                <option value="today" {{ $filter == 'today' ? 'selected' : '' }}>Hari Ini</option>
                                <option value="week" {{ $filter == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                                <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                                <option value="3months" {{ $filter == '3months' ? 'selected' : '' }}>3 Bulan Ke Depan
                                </option>
                                <option value="6months" {{ $filter == '6months' ? 'selected' : '' }}>6 Bulan Ke Depan
                                </option>
                            </select>
                        </form>
                    </div>

                    <!-- TABEL JADWAL -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Tanggal & Jam
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Pasien
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Jenis Terapi
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Ruangan
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($jadwalList as $jadwal)
                                    <tr class="hover:bg-gray-50 transition">

                                        <!-- Tanggal & Jam -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-indigo-700">
                                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} -
                                                {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                            </div>
                                        </td>

                                        <!-- Pasien -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $jadwal->pasien->nama ?? 'Nama Pasien' }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                RM: {{ $jadwal->pasien->no_rm ?? '000000' }}
                                            </div>
                                        </td>

                                        <!-- Jenis Terapi -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $jadwal->jenis_terapi }}
                                        </td>

                                        <!-- Ruangan -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $jadwal->ruangan ?? '-' }}
                                        </td>

                                        <!-- Status -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($jadwal->status == 'terjadwal')
                                                <span
                                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                                    Terjadwal
                                                </span>
                                            @elseif($jadwal->status == 'selesai')
                                                <span
                                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                    Selesai
                                                </span>
                                            @elseif($jadwal->status == 'batal')
                                                <span
                                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                                    Batal
                                                </span>
                                            @elseif($jadwal->status == 'pending')
                                                <span
                                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                    Pending
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Aksi -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">

                                            <div class="flex space-x-2 items-center">

                                                {{-- Tombol Edit --}}
                                                <a href="{{ route('terapis.jadwal.edit', $jadwal->id) }}"
                                                    class="text-yellow-600 hover:text-yellow-900"
                                                    title="Edit Status / Koreksi">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                    </svg>
                                                </a>

                                                {{-- Tombol Aksi Cepat (Hanya jika belum selesai/batal) --}}
                                                @if ($jadwal->status == 'terjadwal' || $jadwal->status == 'pending')
                                                    <!-- Tombol Selesai -->
                                                    <form
                                                        action="{{ route('terapis.jadwal.updateStatus', $jadwal->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="selesai">
                                                        <button type="submit"
                                                            class="text-green-600 hover:text-green-900 font-bold"
                                                            onclick="return confirm('Tandai sesi ini sebagai SELESAI?')">
                                                            ✅
                                                        </button>
                                                    </form>

                                                    <!-- Tombol Pending/Tunda -->
                                                    <form
                                                        action="{{ route('terapis.jadwal.updateStatus', $jadwal->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="pending">
                                                        <button type="submit"
                                                            class="text-orange-600 hover:text-orange-900 font-bold"
                                                            onclick="return confirm('Tandai sesi ini sebagai PENDING?')">
                                                            ⏳
                                                        </button>
                                                    </form>

                                                    <!-- Tombol Batal -->
                                                    <form
                                                        action="{{ route('terapis.jadwal.updateStatus', $jadwal->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="batal">
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-900 font-bold"
                                                            onclick="return confirm('Batalkan sesi ini?')">
                                                            ❌
                                                        </button>
                                                    </form>
                                                @else
                                                    <!-- Tombol Reset Status (BARU) -->
                                                    <form
                                                        action="{{ route('terapis.jadwal.updateStatus', $jadwal->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="terjadwal">
                                                        <button type="submit"
                                                            class="text-blue-600 hover:text-blue-900 font-bold flex items-center"
                                                            onclick="return confirm('Reset status menjadi TERJADWAL?')">
                                                            <span class="text-lg mr-1">📅</span>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <!-- Jika Tidak Ada Jadwal -->
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <p class="text-base font-medium">Tidak ada jadwal untuk periode ini.
                                                </p>
                                                <p class="text-sm">Coba ubah filter tanggal di atas.</p>
                                            </div>
                                        </td>
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
