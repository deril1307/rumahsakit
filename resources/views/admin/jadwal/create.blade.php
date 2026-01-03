<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Jadwal Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ route('admin.jadwal.index') }}"
                class="inline-flex items-center mb-4 text-sm text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Jadwal
            </a>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-medium text-gray-900 mb-6">Form Penjadwalan Pasien</h3>

                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">
                                        Gagal menyimpan jadwal. Periksa pesan error di bawah inputan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('admin.jadwal.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div class="col-span-2">
                                <x-input-label for="pasien_id" :value="__('Pilih Pasien')" />
                                <select id="pasien_id" name="pasien_id"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">-- Cari Pasien --</option>
                                    @foreach ($pasiens as $pasien)
                                        <option value="{{ $pasien->id }}"
                                            {{ old('pasien_id') == $pasien->id ? 'selected' : '' }}>
                                            {{ $pasien->nama }} (RM: {{ $pasien->no_rm }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('pasien_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="user_id" :value="__('Pilih Terapis')" />
                                <select id="user_id" name="user_id"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">-- Pilih Terapis --</option>
                                    @foreach ($terapis as $terapisItem)
                                        <option value="{{ $terapisItem->id }}"
                                            data-spesialisasi="{{ $terapisItem->spesialisasi }}"
                                            {{ old('user_id') == $terapisItem->id ? 'selected' : '' }}>
                                            {{ $terapisItem->name }} ({{ $terapisItem->spesialisasi ?? 'Umum' }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="jenis_terapi" :value="__('Jenis Terapi (Otomatis)')" />
                                <select id="jenis_terapi" name="jenis_terapi"
                                    class="mt-1 block w-full border-gray-300 bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm cursor-not-allowed pointer-events-none"
                                    tabindex="-1" aria-readonly="true">
                                    <option value="">-- Pilih Terapis Dahulu --</option>
                                    @foreach ($jenisTerapis as $jenis)
                                        <option value="{{ $jenis->nama_terapi }}"
                                            {{ old('jenis_terapi') == $jenis->nama_terapi ? 'selected' : '' }}>
                                            {{ $jenis->nama_terapi }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('jenis_terapi')" class="mt-2" />
                            </div>

                            <div class="col-span-2 md:col-span-1">
                                <x-input-label for="tanggal" :value="__('Tanggal Mulai')" />
                                <x-text-input id="tanggal" class="block mt-1 w-full" type="date" name="tanggal"
                                    :value="old('tanggal')" required />
                                <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                            </div>

                            <div class="col-span-2 md:col-span-1">
                                <x-input-label for="ruangan" :value="__('Ruangan (Wajib beda jika jam sama)')" />
                                <x-text-input id="ruangan" class="block mt-1 w-full" type="text" name="ruangan"
                                    :value="old('ruangan')" placeholder="Contoh: Ruang A" />
                                <x-input-error :messages="$errors->get('ruangan')" class="mt-2" />
                            </div>

                            <div class="col-span-2 md:col-span-1">
                                <x-input-label for="jam_mulai" :value="__('Jam Mulai')" />
                                <x-text-input id="jam_mulai" class="block mt-1 w-full" type="time" name="jam_mulai"
                                    :value="old('jam_mulai')" required />
                                <x-input-error :messages="$errors->get('jam_mulai')" class="mt-2" />
                            </div>

                            <div class="col-span-2 md:col-span-1">
                                <x-input-label for="jam_selesai" :value="__('Jam Selesai')" />
                                <x-text-input id="jam_selesai" class="block mt-1 w-full" type="time"
                                    name="jam_selesai" :value="old('jam_selesai')" required />
                                <x-input-error :messages="$errors->get('jam_selesai')" class="mt-2" />
                            </div>

                            <div class="col-span-2 mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="generate_bulan" name="generate_bulan" type="checkbox" value="1"
                                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                            {{ old('generate_bulan') ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="generate_bulan" class="font-medium text-gray-700">Generate Jadwal
                                            Otomatis untuk 1 Bulan?</label>
                                        <p class="text-gray-500">Jika dicentang, sistem akan membuat 8 jadwal.</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-secondary-button type="button" onclick="window.history.back()" class="mr-3">
                                Batal
                            </x-secondary-button>

                            <x-primary-button class="ml-4">
                                {{ __('Simpan & Generate Jadwal') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT OTOMATISASI JENIS TERAPI --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const terapisSelect = document.getElementById('user_id');
            const jenisTerapiSelect = document.getElementById('jenis_terapi');

            function updateJenisTerapi() {
                const selectedOption = terapisSelect.options[terapisSelect.selectedIndex];
                const spesialisasi = selectedOption.getAttribute('data-spesialisasi');

                if (spesialisasi) {
                    for (let i = 0; i < jenisTerapiSelect.options.length; i++) {
                        if (jenisTerapiSelect.options[i].value === spesialisasi) {
                            jenisTerapiSelect.selectedIndex = i;
                            break;
                        }
                    }
                } else {
                    jenisTerapiSelect.selectedIndex = 0;
                }
            }
            terapisSelect.addEventListener('change', updateJenisTerapi);
            if (terapisSelect.value) {
                updateJenisTerapi();
            }
        });
    </script>
</x-app-layout>