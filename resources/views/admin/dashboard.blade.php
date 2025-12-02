<x-app-layout>
    {{-- 1. Header Halaman diubah --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Jadwal Terapi (Penjadwalan)') }}
        </h2>
    </x-slot>

    {{-- 2. Konten Halaman (Manajemen User diganti total) --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- 
                Setiap 'div' dengan 'bg-white' di bawah ini 
                adalah 'card' terpisah seperti di gambar Anda. 
            --}}

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Pilih Pasien
                    </h3>

                    <div class="flex border-b border-gray-200">
                        <button class="py-2 px-4 text-green-600 border-b-2 border-green-600 font-semibold">
                            Ambil dari Teramedik
                        </button>
                        <button class="py-2 px-4 text-gray-500 hover:text-gray-700">
                            Input Manual
                        </button>
                    </div>

                    <div class="mt-4">
                        <x-text-input type="text" class="w-full" placeholder="Cari Nama Pasien atau No. RM..." />
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Atur Jadwal
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <x-input-label for="jenis_terapi" :value="__('Jenis Terapi')" />
                            <select id="jenis_terapi"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option>Fisioterapi</option>
                                <option>Terapi Okupasi</option>
                                <option>Terapi Wicara</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="tanggal_mulai" :value="__('Tanggal Mulai')" />
                            <x-text-input id="tanggal_mulai" class="block mt-1 w-full" type="date"
                                value="2023-10-25" />
                        </div>

                        <div>
                            <x-input-label for="frekuensi" :value="__('Frekuensi')" />
                            <select id="frekuensi"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option>2x / minggu (Total 8 sesi)</option>
                                <option>1x / minggu (Total 4 sesi)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Kalender Ketersediaan
                        </h3>
                        <div class="flex items-center space-x-2">
                            <button class="text-gray-500 hover:text-gray-700">&lt;</button>
                            <span class="font-semibold">Oktober 2023</span>
                            <button class="text-gray-500 hover:text-gray-700">&gt;</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-7 gap-px border border-gray-200 bg-gray-200">
                        <div class="text-center py-2 bg-gray-100 text-xs font-medium text-gray-500 uppercase">Min</div>
                        <div class="text-center py-2 bg-gray-100 text-xs font-medium text-gray-500 uppercase">Sen</div>
                        <div class="text-center py-2 bg-gray-100 text-xs font-medium text-gray-500 uppercase">Sel</div>
                        <div class="text-center py-2 bg-gray-100 text-xs font-medium text-gray-500 uppercase">Rab</div>
                        <div class="text-center py-2 bg-gray-100 text-xs font-medium text-gray-500 uppercase">Kam</div>
                        <div class="text-center py-2 bg-gray-100 text-xs font-medium text-gray-500 uppercase">Jum</div>
                        <div class="text-center py-2 bg-gray-100 text-xs font-medium text-gray-500 uppercase">Sab</div>

                        <div class="py-4 px-2 bg-red-100 text-gray-700 text-sm">4</div>
                        <div class="py-4 px-2 bg-white text-gray-700 text-sm">5</div>
                        <div class="py-4 px-2 bg-white text-gray-700 text-sm">6</div>
                        <div class="py-4 px-2 bg-white text-gray-700 text-sm">2</div>
                        <div class="py-4 px-2 bg-white text-gray-700 text-sm">1</div>
                        <div class="py-4 px-2 bg-white text-gray-700 text-sm">2</div>
                        <div class="py-4 px-2 bg-white text-gray-700 text-sm">3</div>
                        <div class="py-4 px-2 bg-red-100 text-gray-700 text-sm">11</div>
                        <div class="py-4 px-2 bg-white text-gray-700 text-sm">12</div>
                        <div class="py-4 px-2 bg-white text-gray-700 text-sm">13</div>
                        <div class="py-4 px-2 bg-white text-gray-700 text-sm">14</div>
                        <div class="py-4 px-2 bg-white text-gray-700 text-sm">15</div>
                        <div class="py-4 px-2 bg-white text-gray-700 text-sm">16</div>
                        <div class="py-4 px-2 bg-white text-gray-700 text-sm">17</div>
                        <div class="py-4 px-2 bg-red-100 text-gray-700 text-sm">18</div>
                        <div class="py-4 px-2 bg-white text-gray-700 text-sm">19</div>
                        <div class="py-4 px-2 bg-white text-gray-700 text-sm">20</div>
                        <div class="py-4 px-2 bg-white text-gray-700 text-sm">21</div>
                        <div class="py-4 px-2 bg-white text-gray-700 text-sm">22</div>
                        <div class="py-4 px-2 bg-white text-gray-700 text-sm">23</div>
                        <div class="py-4 px-2 bg-white text-gray-700 text-sm">24</div>
                        <div class="py-4 px-2 bg-green-600 text-white text-sm font-semibold">25</div>
                        <div class="py-4 px-2 bg-white text-gray-700 text-sm">26</div>
                        <div class="py-4 px-2 bg-green-600 text-white text-sm font-semibold">27</div>
                        <div class="py-4 px-2 bg-white text-gray-700 text-sm">28</div>
                        <div class="py-4 px-2 bg-white text-gray-700 text-sm">29</div>
                        <div class="py-4 px-2 bg-green-600 text-white text-sm font-semibold">30</div>
                        <div class="py-4 px-2 bg-white text-gray-700 text-sm">31</div>
                    </div>

                    <div class="mt-4 flex space-x-4 text-sm">
                        <div class="flex items-center"><span class="w-4 h-4 bg-green-600 rounded-full mr-2"></span>
                            Tersedia</div>
                        <div class="flex items-center"><span
                                class="w-4 h-4 bg-red-100 rounded-full mr-2 border border-red-300"></span> Tidak
                            Tersedia</div>
                        <div class="flex items-center"><span class="w-4 h-4 bg-green-800 rounded-full mr-2"></span>
                            Jadwal Dipilih</div>
                    </div>

                    <div class="mt-4 text-xs text-blue-600">
                        <p class="font-bold">Aturan Penjadwalan:</p>
                        <ul class="list-disc list-inside">
                            <li>BPJS: Minimal 3 hari setelah tanggal periksa dokter, interval antar terapi 3 hari.</li>
                            <li>UMUM: Interval antar terapi minimal 1 hari.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Preview Jadwal (Oktober - November 2023)
                    </h3>

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
                                    Terapis</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">Rabu, 25 Okt 2023</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">10:00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">Ahmad Yani</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Generated</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">Jumat, 27 Okt 2023</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">14:00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">Siti Hajar</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Generated</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">Senin, 30 Okt 2023</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">09:00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">Ahmad Yani</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Generated</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">Rabu, 1 Nov 2023</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">10:00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">Ahmad Yani</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Generated</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">Jumat, 3 Nov 2023</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">14:00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">Siti Hajar</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Generated</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>



        </div>
    </div>
</x-app-layout>
