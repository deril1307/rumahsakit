<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Jadwal Terapi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- Link Kembali -->
            <a href="{{ route('admin.jadwal.index') }}"
                class="inline-flex items-center mb-4 text-sm text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-medium text-gray-900 mb-6">Edit Data Jadwal</h3>

                    <!-- Validasi Error -->
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
                            <ul class="list-disc pl-5 text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST">
                        @csrf
                        @method('PUT') <!-- Method PUT wajib untuk update -->

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- 1. Pasien -->
                            <div class="col-span-2">
                                <x-input-label for="pasien_id" :value="__('Pasien')" />
                                <select id="pasien_id" name="pasien_id"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    @foreach ($pasiens as $pasien)
                                        <option value="{{ $pasien->id }}"
                                            {{ old('pasien_id', $jadwal->pasien_id) == $pasien->id ? 'selected' : '' }}>
                                            {{ $pasien->nama }} (RM: {{ $pasien->no_rm }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('pasien_id')" class="mt-2" />
                            </div>

                            <!-- 2. Terapis (DIPINDAH KE ATAS) -->
                            <!-- Script akan membaca data-spesialisasi dari sini -->
                            <div>
                                <x-input-label for="user_id" :value="__('Terapis')" />
                                <select id="user_id" name="user_id"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    @foreach ($terapis as $t)
                                        <option value="{{ $t->id }}" data-spesialisasi="{{ $t->spesialisasi }}"
                                            {{ old('user_id', $jadwal->user_id) == $t->id ? 'selected' : '' }}>
                                            {{ $t->name }} ({{ $t->spesialisasi ?? 'Umum' }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                            </div>

                            <!-- 3. Jenis Terapi (Otomatis & Readonly) -->
                            <div>
                                <x-input-label for="jenis_terapi" :value="__('Jenis Terapi (Otomatis)')" />
                                <select id="jenis_terapi" name="jenis_terapi"
                                    class="mt-1 block w-full border-gray-300 bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm cursor-not-allowed pointer-events-none"
                                    tabindex="-1" aria-readonly="true">
                                    @foreach ($jenisTerapis as $jenis)
                                        <option value="{{ $jenis->nama_terapi }}"
                                            {{ old('jenis_terapi', $jadwal->jenis_terapi) == $jenis->nama_terapi ? 'selected' : '' }}>
                                            {{ $jenis->nama_terapi }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('jenis_terapi')" class="mt-2" />
                            </div>

                            <!-- 4. Tanggal -->
                            <div class="col-span-2 md:col-span-1">
                                <x-input-label for="tanggal" :value="__('Tanggal')" />
                                <x-text-input id="tanggal" class="block mt-1 w-full" type="date" name="tanggal"
                                    :value="old(
                                        'tanggal',
                                        $jadwal->tanggal ? $jadwal->tanggal->format('Y-m-d') : '',
                                    )" required />
                                <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                            </div>

                            <!-- 5. Ruangan -->
                            <div class="col-span-2 md:col-span-1">
                                <x-input-label for="ruangan" :value="__('Ruangan')" />
                                <x-text-input id="ruangan" class="block mt-1 w-full" type="text" name="ruangan"
                                    :value="old('ruangan', $jadwal->ruangan)" />
                                <x-input-error :messages="$errors->get('ruangan')" class="mt-2" />
                            </div>

                            <!-- 6. Jam Mulai -->
                            <div>
                                <x-input-label for="jam_mulai" :value="__('Jam Mulai')" />
                                <x-text-input id="jam_mulai" class="block mt-1 w-full" type="time" name="jam_mulai"
                                    :value="old(
                                        'jam_mulai',
                                        \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i'),
                                    )" required />
                                <x-input-error :messages="$errors->get('jam_mulai')" class="mt-2" />
                            </div>

                            <!-- 7. Jam Selesai -->
                            <div>
                                <x-input-label for="jam_selesai" :value="__('Jam Selesai')" />
                                <x-text-input id="jam_selesai" class="block mt-1 w-full" type="time"
                                    name="jam_selesai" :value="old(
                                        'jam_selesai',
                                        \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i'),
                                    )" required />
                                <x-input-error :messages="$errors->get('jam_selesai')" class="mt-2" />
                            </div>

                            <!-- 8. Status Terapi -->
                            <div class="col-span-2 bg-yellow-50 p-4 rounded-md border border-yellow-200">
                                <x-input-label for="status" :value="__('Status Terapi')" />
                                <select id="status" name="status"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="terjadwal" {{ $jadwal->status == 'terjadwal' ? 'selected' : '' }}>
                                        Terjadwal</option>
                                    <option value="selesai" {{ $jadwal->status == 'selesai' ? 'selected' : '' }}>
                                        Selesai</option>
                                    <option value="batal" {{ $jadwal->status == 'batal' ? 'selected' : '' }}>Batal
                                    </option>
                                    <option value="pending" {{ $jadwal->status == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Update Jadwal') }}
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

            // Fungsi untuk update jenis terapi
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
                }
            }

            // Jalankan saat user memilih terapis
            terapisSelect.addEventListener('change', updateJenisTerapi);
        });
    </script>
</x-app-layout>
