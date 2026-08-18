<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen User') }}
        </h2>
    </x-slot>

    {{-- 1. LOAD LIBRARY SWEETALERT (Sama seperti di Dashboard) --}}
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Notifikasi Sukses --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- CARD: Tabel User --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Daftar Pengguna Sistem
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        No
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Nama
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{-- PERBAIKAN: Rumus Nomor Urut agar tidak reset di halaman 2 --}}
                                            {{ $users->firstItem() + $loop->index }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $user->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex flex-wrap gap-1.5">
                                                @forelse ($user->roles as $role)
                                                    @php
                                                        $rName = strtolower($role->name);
                                                        if (str_contains($rName, 'admin')) {
                                                            $badgeStyle = 'bg-purple-50 text-purple-700 border-purple-200 ring-1 ring-purple-500/20';
                                                            $dotStyle = 'bg-purple-500';
                                                        } elseif (str_contains($rName, 'kepala')) {
                                                            $badgeStyle = 'bg-blue-50 text-blue-700 border-blue-200 ring-1 ring-blue-500/20';
                                                            $dotStyle = 'bg-blue-500';
                                                        } elseif (str_contains($rName, 'terapis')) {
                                                            $badgeStyle = 'bg-emerald-50 text-emerald-700 border-emerald-200 ring-1 ring-emerald-500/20';
                                                            $dotStyle = 'bg-emerald-500';
                                                        } elseif (str_contains($rName, 'pasien')) {
                                                            $badgeStyle = 'bg-amber-50 text-amber-700 border-amber-200 ring-1 ring-amber-500/20';
                                                            $dotStyle = 'bg-amber-500';
                                                        } else {
                                                            $badgeStyle = 'bg-gray-50 text-gray-700 border-gray-200 ring-1 ring-gray-500/20';
                                                            $dotStyle = 'bg-gray-400';
                                                        }
                                                    @endphp
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold border {{ $badgeStyle }}">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $dotStyle }}"></span>
                                                        {{ ucfirst($role->name) }}
                                                    </span>
                                                @empty
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-50 text-gray-500 border border-gray-200">
                                                        Tanpa Role
                                                    </span>
                                                @endforelse
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if ($user->hasRole('admin'))
                                                <span class="text-gray-400 text-xs italic">Admin (Tidak bisa diubah)</span>
                                            @else
                                                <div class="flex space-x-2">
                                                    {{-- Tombol UBAH --}}
                                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                                        class="inline-flex items-center px-3 py-1.5 bg-gray-700 border border-transparent rounded text-xs font-bold text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                                                        UBAH
                                                    </a>

                                                    {{-- Tombol HAPUS --}}
                                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        
                                                        <button type="submit"
                                                            onclick="confirmDelete(event, '{{ addslashes($user->name) }}')"
                                                            class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded text-xs font-bold text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                                                            HAPUS
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- TAMBAHAN: Tombol Pindah Halaman (Pagination Links) --}}
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- 2. SCRIPT JAVASCRIPT (Diadaptasi dari Dashboard) --}}
    <script>
        function confirmDelete(event, userName) {
            // Mencegah form langsung terkirim
            event.preventDefault();
            
            // Mencari form terdekat dari tombol yang diklik
            const form = event.target.closest('form');

            // Memunculkan Popup SweetAlert
            Swal.fire({
                title: 'Hapus User?',
                text: "Apakah Anda yakin ingin menghapus user " + userName + "? Tindakan ini tidak bisa dibatalkan.",
                icon: 'error', 
                showCancelButton: true,
                confirmButtonColor: '#dc2626', 
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Kembali / Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>