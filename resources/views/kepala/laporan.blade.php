<x-app-layout>
    {{-- Header Halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Kepala Instalasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-wrap justify-between items-end gap-4">
                        <div>
                            <x-input-label for="rentang_tanggal" :value="__('Pilih Rentang Tanggal')" />
                            <x-text-input id="rentang_tanggal" class="block mt-1 w-full sm:w-64" type="text" value="01 Mei 2024 - 31 Mei 2024" />
                        </div>
                        <div class="flex space-x-2">
                            <a href="#" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                                Ekspor ke Excel
                            </a>
                            <a href="#" class="px-4 py-2 bg-orange-500 text-white text-sm font-medium rounded-md hover:bg-orange-600">
                                Ekspor ke PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm font-medium text-gray-500">Total Sesi Terapi</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_sesi'] }}</p>
                    <p class="text-xs text-green-600 mt-1">+ 12% dari bulan lalu</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm font-medium text-gray-500">Total Pasien</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_pasien'] }}</p>
                    <p class="text-xs text-green-600 mt-1">+ 5% dari bulan lalu</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm font-medium text-gray-500">Sesi Selesai</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['sesi_selesai'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">95% tingkat penyelesaian</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm font-medium text-gray-500">Sesi Dibatalkan</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['sesi_dibatalkan'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">5% tingkat pembatalan</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Riwayat Sesi Terapi
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            {{-- Header Tabel --}}
                            <thead class="bg-white"> {{-- Di gambar, header-nya putih --}}
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terapis</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Terapi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                {{-- Loop data $riwayat dari Controller --}}
                                @foreach ($riwayat as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->pasien }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->terapis }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->jenis_terapi }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->tanggal }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if ($item->status == 'Selesai')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                                            @elseif ($item->status == 'Dibatalkan')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Dibatalkan</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex justify-between items-center text-sm text-gray-500">
                        <div>
                            <a href="#" class="text-gray-400 hover:text-gray-700">&lt; Previous</a>
                        </div>
                        <span>1 of 25</span>
                        <div>
                            <a href="#" class="text-gray-400 hover:text-gray-700">Next &gt;</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>