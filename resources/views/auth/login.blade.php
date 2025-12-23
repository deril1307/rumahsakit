<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Selamat Datang - RS Al-Islam Bandung</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-white">

        <div class="min-h-screen flex flex-col items-center justify-center pt-6 sm:pt-0">
            
            {{-- Logo Bulat di Tengah --}}
            <div class="flex flex-col items-center mb-6">
                <div class="w-24 h-24 bg-white rounded-full shadow-sm flex items-center justify-center border border-gray-200 p-2">
                     {{-- Pastikan gambar logo Anda berbentuk bulat atau transparan --}}
                    <img src="{{ asset('img/logoRS.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                
                <h1 class="text-3xl font-bold text-gray-900 mt-6">
                    Selamat Datang
                </h1>
            </div>
            <div class="w-full sm:max-w-md px-6 py-4 bg-transparent">
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="block font-bold text-sm text-gray-700 mb-1">Email</label>
                        <input id="email" 
                               class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 py-3 px-4 text-gray-600" 
                               type="email" 
                               name="email" 
                               :value="old('email')" 
                               placeholder="Masukkan email Anda"
                               required autofocus autocomplete="email" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mt-4 relative" x-data="{ show: false }">
                        <label for="password" class="block font-bold text-sm text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <input id="password" 
                                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 py-3 px-4 text-gray-600"
                                   :type="show ? 'text' : 'password'"
                                   name="password"
                                   placeholder="Masukkan password Anda"
                                   required autocomplete="current-password" />
                            
                            {{-- Ikon Mata (Show/Hide Password) --}}
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" @click="show = !show">
                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg x-show="show" style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-2">
                        @if (Route::has('password.request'))
                            <a class="text-sm text-orange-400 hover:text-orange-500 font-semibold" href="{{ route('password.request') }}">
                                {{ __('Lupa Password?') }}
                            </a>
                        @endif
                    </div>

                    <div class="mt-6">
                        <button class="w-full bg-green-700 hover:bg-green-800 text-white font-bold py-3 px-4 rounded-md shadow transition duration-150 ease-in-out">
                            {{ __('Masuk') }}
                        </button>
                    </div>
                </form>
            </div>
            
            {{-- Footer Copyright --}}
            <div class="mt-16 text-xs text-gray-400 text-center">
                &copy; RS Al-Islam Bandung - Sistem Penjadwalan Digital Rehabilitasi Medik
            </div>

        </div>
    </body>
</html>