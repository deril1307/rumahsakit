<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard Kepala Instalasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-8 border-blue-600">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">
                        Selamat Datang, Bapak/Ibu {{ Auth::user()->name }}
                    </h3>
                    <p class="text-lg text-gray-700">
                        Berikut adalah ringkasan kinerja Instalasi Rehabilitasi Medik untuk bulan ini:
                        <span class="font-bold text-blue-700 bg-blue-50 px-2 py-1 rounded">
                            {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg border border-gray-200">
                    <div class="p-6 flex flex-col items-center justify-center text-center">
                        <div class="p-4 rounded-full bg-blue-100 text-blue-700 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $totalSesi }}</div>
                        <div class="text-base font-semibold text-gray-600 uppercase tracking-wide">Total Sesi Terapi
                        </div>
                    </div>
                    <div class="bg-blue-50 px-6 py-3 border-t border-blue-100">
                        <p class="text-sm text-blue-800 text-center">Jumlah seluruh jadwal bulan ini</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg border border-gray-200">
                    <div class="p-6 flex flex-col items-center justify-center text-center">
                        <div class="p-4 rounded-full bg-green-100 text-green-700 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $sesiSelesai }}</div>
                        <div class="text-base font-semibold text-gray-600 uppercase tracking-wide">Terapi Selesai</div>
                    </div>
                    <div class="bg-green-50 px-6 py-3 border-t border-green-100">
                        <p class="text-sm text-green-800 text-center">Pasien yang sudah dilayani</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg border border-gray-200">
                    <div class="p-6 flex flex-col items-center justify-center text-center">
                        <div class="p-4 rounded-full bg-red-100 text-red-700 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $sesiBatal }}</div>
                        <div class="text-base font-semibold text-gray-600 uppercase tracking-wide">Dibatalkan</div>
                    </div>
                    <div class="bg-red-50 px-6 py-3 border-t border-red-100">
                        <p class="text-sm text-red-800 text-center">Jadwal yang batal dilaksanakan</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg border border-gray-200">
                    <div class="p-6 flex flex-col items-center justify-center text-center">
                        <div class="p-4 rounded-full bg-purple-100 text-purple-700 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $totalPasien }}</div>
                        <div class="text-base font-semibold text-gray-600 uppercase tracking-wide">Total Pasien</div>
                    </div>
                    <div class="bg-purple-50 px-6 py-3 border-t border-purple-100">
                        <p class="text-sm text-purple-800 text-center">Jumlah pasien aktif saat ini</p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
