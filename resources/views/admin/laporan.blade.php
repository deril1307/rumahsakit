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

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <x-input-label for="rentang_tanggal" :value="__('Rentang Tanggal')" />
                            <x-text-input id="rentang_tanggal" class="block mt-1 w-full" type="text" value="01 Okt 2024 - 15 Okt 2024" />
                        </div>

                        <div>
                            <x-input-label for="nama_terapis" :value="__('Nama Terapis')" />
                            <select id="nama_terapis" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option>Semua Terapis</option>
                                <option>Dr. Budi S.</option>
                                <option>Dr. Citra L.</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="jenis_terapi" :value="__('Jenis Terapi')" />
                            <select id="jenis_terapi" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option>Semua Jenis</option>
                                <option>Fisioterapi</option>
                                <option>Terapi Okupasi</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-end mb-4 space-x-2">
                        <a href="#" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                            Ekspor ke Excel
                        </a>
                        <a href="#" class="px-4 py-2 bg-orange-500 text-white text-sm font-medium rounded-md hover:bg-orange-600">
                            Ekspor ke PDF
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pasien</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. RM</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Terapi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Terapis</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Akhir</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                {{-- Loop data $laporan dari Controller --}}
                                @foreach ($laporan as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->tanggal }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->jam }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->nama_pasien }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->no_rm }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->jenis_terapi }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->nama_terapis }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if ($item->status == 'Selesai')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Selesai
                                                </span>
                                            @elseif ($item->status == 'Dibatalkan')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Dibatalkan
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="#" class="text-sm font-medium text-green-600 hover:text-green-500">
                            Muat lebih banyak
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>