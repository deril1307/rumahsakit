<x-app-layout>
    {{-- Ini adalah bagian untuk Header (Judul Halaman) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Terapis') }}
        </h2>
    </x-slot>

    {{-- Ini adalah bagian untuk Konten Halaman Anda --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Halo, ini Dashboard Terapis") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>