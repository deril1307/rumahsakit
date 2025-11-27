<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sistem Penjadwalan Pasien RS Al-Islam Bandung</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="antialiased">

    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-center bg-white">

        @if (Route::has('login'))
            <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                @auth
                    <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900">Masuk</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900">Register</a>
                    @endif
                @endauth
            </div>
        @endif

        <div class="max-w-7xl mx-auto p-6 lg:p-8 flex flex-col items-center justify-center">

            {{-- ============================================= --}}
            {{-- == KODE BARU DIMULAI == --}}
            {{-- ============================================= --}}

            {{-- Logo RS Al-Islam --}}
            <div class="mb-8">
                <img src="{{ asset('img/RS-Al-Islam-Bandung.webp') }}" alt="Logo RS Al-Islam Bandung"
                    class="h-40 mx-auto">
            </div>

            {{-- Judul Proyek --}}
            <div class="flex justify-center">
                <h1 class="text-5xl font-bold text-gray-900 text-center">
                    Sistem Penjadwalan Pasien
                </h1>
            </div>

            {{-- Sub-Judul --}}
            <div class="flex justify-center mt-4">
                <p class="text-2xl text-gray-600 text-center">
                    Instalasi Rehabilitasi Medik<br>RS Al-Islam Bandung
                </p>
            </div>

        </div>
    </div>
</body>

</html>