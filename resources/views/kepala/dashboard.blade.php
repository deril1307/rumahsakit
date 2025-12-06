<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Kepala Instalasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- WELCOME CARD -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                <h3 class="text-lg font-bold text-gray-900">Selamat Datang, {{ Auth::user()->name }}!</h3>
                <p class="text-gray-600 mt-1">
                    Berikut adalah ringkasan kinerja Instalasi Rehabilitasi Medik untuk bulan
                    <span class="font-bold text-indigo-600">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</span>.
                </p>
            </div>

            <!-- STATISTIK GRID -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                <!-- Card 1: Total Kunjungan -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-blue-500 hover:shadow-md transition">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500 uppercase">Total Sesi</div>
                            <div class="text-2xl font-bold text-gray-900">{{ $totalSesi }}</div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Selesai -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-green-500 hover:shadow-md transition">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500 uppercase">Terapi Selesai</div>
                            <div class="text-2xl font-bold text-gray-900">{{ $sesiSelesai }}</div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Batal -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-red-500 hover:shadow-md transition">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500 uppercase">Dibatalkan</div>
                            <div class="text-2xl font-bold text-gray-900">{{ $sesiBatal }}</div>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Pasien Unik -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-purple-500 hover:shadow-md transition">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500 uppercase">Pasien Aktif</div>
                            <div class="text-2xl font-bold text-gray-900">{{ $totalPasien }}</div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- QUICK ACTION -->
            <div class="flex justify-end mt-6">
                <a href="{{ route('kepala.laporan') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Lihat Laporan Lengkap &rarr;
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
