<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Terapis') }}
        </h2>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <h3 class="text-lg font-bold text-gray-900">
                            Jadwal Terapi: <span class="text-indigo-600">{{ $labelFilter }}</span>
                        </h3>

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

                                        <td class="px-6 py-2 whitespace-nowrap">
                                            <div class="text-sm font-bold text-indigo-700">
                                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} -
                                                {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                            </div>
                                        </td>

                                        <td class="px-6 py-2 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $jadwal->pasien->nama ?? 'Nama Pasien' }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                RM: {{ $jadwal->pasien->no_rm ?? '000000' }}
                                            </div>
                                        </td>

                                        <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $jadwal->jenis_terapi }}
                                        </td>

                                        <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">
                                            {{ $jadwal->ruangan ?? '-' }}
                                        </td>

                                        <td class="px-6 py-2 whitespace-nowrap">
                                            @if ($jadwal->status == 'terjadwal')
                                                <span
                                                    class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                    Terjadwal
                                                </span>
                                            @elseif($jadwal->status == 'selesai')
                                                <span
                                                    class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                    Selesai
                                                </span>
                                            @elseif($jadwal->status == 'batal')
                                                <span
                                                    class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                                    Batal
                                                </span>
                                            @elseif($jadwal->status == 'pending')
                                                <span
                                                    class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800 border border-orange-200">
                                                    Pending
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-2 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-1">
                                                
                                                {{-- Tombol Edit --}}
                                                <a href="{{ route('terapis.jadwal.edit', $jadwal->id) }}"
                                                    class="inline-flex items-center px-2 py-1 bg-gray-600 hover:bg-gray-700 text-white text-xs font-bold rounded shadow transition uppercase"
                                                    title="Ubah Jadwal">
                                                    Ubah
                                                </a>

                                                @if ($jadwal->status == 'terjadwal' || $jadwal->status == 'pending')
                                                    
                                                    <form action="{{ route('terapis.jadwal.updateStatus', $jadwal->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="selesai">
                                                        <button type="submit"
                                                            class="inline-flex items-center px-2 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded shadow transition uppercase"
                                                            onclick="confirmAction(event, 'selesai')">
                                                            Selesai
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('terapis.jadwal.updateStatus', $jadwal->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="pending">
                                                        <button type="submit"
                                                            class="inline-flex items-center px-2 py-1 bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold rounded shadow transition uppercase"
                                                            onclick="confirmAction(event, 'tunda')">
                                                            Tunda
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('terapis.jadwal.updateStatus', $jadwal->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="batal">
                                                        <button type="submit"
                                                            class="inline-flex items-center px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded shadow transition uppercase"
                                                            onclick="confirmAction(event, 'batal')">
                                                            Batal
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <p class="text-base font-medium">Tidak ada jadwal untuk periode ini.</p>
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

    <script>
        function confirmAction(event, type) {
            // Mencegah form langsung terkirim
            event.preventDefault();
            
            // Mencari form terdekat dari tombol yang diklik
            const form = event.target.closest('form');

            // Konfigurasi pesan berdasarkan tombol yang ditekan
            let titleText = '';
            let bodyText = '';
            let confirmBtnText = '';
            let confirmBtnColor = '';
            let iconType = '';

            if (type === 'selesai') {
                titleText = 'Konfirmasi Selesai?';
                bodyText = 'Pastikan terapi sudah benar-benar selesai dilakukan.';
                confirmBtnText = 'Ya, Selesai!';
                confirmBtnColor = '#16a34a'; // Hijau (Tailwind green-600)
                iconType = 'success';
            } else if (type === 'tunda') {
                titleText = 'Tunda Jadwal?';
                bodyText = 'Pasien akan masuk status Pending/Tunda.';
                confirmBtnText = 'Ya, Tunda';
                confirmBtnColor = '#f97316'; // Orange (Tailwind orange-500)
                iconType = 'warning';
            } else if (type === 'batal') {
                titleText = 'Batalkan Sesi?';
                bodyText = 'Tindakan ini tidak bisa dibatalkan jika sudah disimpan.';
                confirmBtnText = 'Ya, Batalkan';
                confirmBtnColor = '#dc2626'; // Merah (Tailwind red-600)
                iconType = 'error';
            }

            // Memunculkan Popup SweetAlert
            Swal.fire({
                title: titleText,
                text: bodyText,
                icon: iconType,
                showCancelButton: true,
                confirmButtonColor: confirmBtnColor,
                cancelButtonColor: '#6b7280', // Abu-abu
                confirmButtonText: confirmBtnText,
                cancelButtonText: 'Kembali / Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika user klik tombol YA, baru submit form
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>