<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update Status Terapi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <!-- Link Kembali -->
            <a href="{{ route('terapis.dashboard') }}"
                class="inline-flex items-center mb-4 text-sm text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Dashboard
            </a>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-bold text-gray-900 mb-6 border-b pb-2">
                        Detail Sesi Terapi
                    </h3>

                    <!-- Info Pasien (Read Only / Tidak Bisa Diedit) -->
                    <div class="grid grid-cols-2 gap-4 mb-6 bg-gray-50 p-4 rounded-lg">
                        <div>
                            <span class="block text-xs text-gray-500 uppercase font-bold">Pasien</span>
                            <span class="text-gray-900 font-medium">{{ $jadwal->pasien->nama }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-gray-500 uppercase font-bold">No. RM</span>
                            <span class="text-gray-900 font-medium">{{ $jadwal->pasien->no_rm }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-gray-500 uppercase font-bold">Jenis Terapi</span>
                            <span class="text-gray-900 font-medium">{{ $jadwal->jenis_terapi }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-gray-500 uppercase font-bold">Waktu</span>
                            <span class="text-gray-900 font-medium">
                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }} <br>
                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                            </span>
                        </div>
                    </div>

                    <!-- Form Update Status -->
                    <form action="{{ route('terapis.jadwal.update', $jadwal->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">

                            <!-- Pilihan Status -->
                            <div>
                                <x-input-label for="status" :value="__('Pilih Status Terapi')" class="mb-3" />

                                <!-- Layout Grid 2 Kolom untuk 4 Pilihan -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                                    <!-- 1. SELESAI -->
                                    <label class="cursor-pointer relative">
                                        <input type="radio" name="status" value="selesai" class="peer sr-only"
                                            {{ $jadwal->status == 'selesai' ? 'checked' : '' }}>
                                        <div
                                            class="p-4 rounded-lg border-2 border-gray-200 hover:border-green-400 bg-white peer-checked:bg-green-50 peer-checked:border-green-600 peer-checked:text-green-800 transition-all h-full flex items-center">
                                            <span class="text-2xl mr-3">✅</span>
                                            <div>
                                                <div class="font-bold">Selesai</div>
                                                <div class="text-xs text-gray-500">Terapi tuntas</div>
                                            </div>
                                        </div>
                                        <div class="absolute top-2 right-2 hidden peer-checked:block text-green-600">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </label>

                                    <!-- 2. PENDING -->
                                    <label class="cursor-pointer relative">
                                        <input type="radio" name="status" value="pending" class="peer sr-only"
                                            {{ $jadwal->status == 'pending' ? 'checked' : '' }}>
                                        <div
                                            class="p-4 rounded-lg border-2 border-gray-200 hover:border-yellow-400 bg-white peer-checked:bg-yellow-50 peer-checked:border-yellow-500 peer-checked:text-yellow-800 transition-all h-full flex items-center">
                                            <span class="text-2xl mr-3">⏳</span>
                                            <div>
                                                <div class="font-bold">Pending</div>
                                                <div class="text-xs text-gray-500">Menunggu / Ditunda</div>
                                            </div>
                                        </div>
                                        <div class="absolute top-2 right-2 hidden peer-checked:block text-yellow-600">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </label>

                                    <!-- 3. BATAL -->
                                    <label class="cursor-pointer relative">
                                        <input type="radio" name="status" value="batal" class="peer sr-only"
                                            {{ $jadwal->status == 'batal' ? 'checked' : '' }}>
                                        <div
                                            class="p-4 rounded-lg border-2 border-gray-200 hover:border-red-400 bg-white peer-checked:bg-red-50 peer-checked:border-red-600 peer-checked:text-red-800 transition-all h-full flex items-center">
                                            <span class="text-2xl mr-3">❌</span>
                                            <div>
                                                <div class="font-bold">Batal</div>
                                                <div class="text-xs text-gray-500">Pasien tidak hadir</div>
                                            </div>
                                        </div>
                                        <div class="absolute top-2 right-2 hidden peer-checked:block text-red-600">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </label>

                                    <!-- 4. TERJADWAL (Reset) - BARU DITAMBAHKAN -->
                                    <label class="cursor-pointer relative">
                                        <input type="radio" name="status" value="terjadwal" class="peer sr-only"
                                            {{ $jadwal->status == 'terjadwal' ? 'checked' : '' }}>
                                        <div
                                            class="p-4 rounded-lg border-2 border-gray-200 hover:border-blue-400 bg-white peer-checked:bg-blue-50 peer-checked:border-blue-600 peer-checked:text-blue-800 transition-all h-full flex items-center">
                                            <span class="text-2xl mr-3">📅</span>
                                            <div>
                                                <div class="font-bold">Terjadwal</div>
                                                <div class="text-xs text-gray-500">Reset ke awal</div>
                                            </div>
                                        </div>
                                        <div class="absolute top-2 right-2 hidden peer-checked:block text-blue-600">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </label>

                                </div>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <!-- Catatan Medis (Opsional) -->
                            <div>
                                <x-input-label for="catatan" :value="__('Catatan Tambahan (Opsional)')" />
                                <textarea id="catatan" name="catatan" rows="4"
                                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                    placeholder="Tulis catatan perkembangan pasien atau alasan pembatalan disini...">{{ old('catatan', $jadwal->catatan) }}</textarea>
                                <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-6 pt-4 border-t">
                            <x-secondary-button type="button" onclick="window.history.back()" class="mr-3">
                                Batal
                            </x-secondary-button>
                            <x-primary-button class="px-6 py-3">
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
