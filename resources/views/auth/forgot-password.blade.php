<x-guest-layout>
    <h2 class="text-xl font-bold text-gray-900 mb-4">Lupa Kata Sandi</h2>

    <div class="mb-6 text-base text-gray-700 leading-normal">
        {{ __('Lupa kata sandi Anda? Tidak masalah. Silakan masukkan alamat email yang terdaftar, dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.') }}
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-6">
            <x-input-label for="email" :value="__('Alamat Email')" class="text-base font-semibold text-gray-800 mb-2" />
            
            <x-text-input 
                id="email" 
                class="block mt-1 w-full p-3 text-lg border border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 rounded-md shadow-sm" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                placeholder="Contoh: namaEmail@gmail.com"
            />
            
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="flex flex-col items-center mt-8">
            
            <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-md shadow-sm text-lg font-bold text-white bg-[#198746] hover:bg-[#146c38] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                {{ __('Kirim Link Ganti Password') }}
            </button>

            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 underline text-sm pt-4">
                {{ __('Kembali ke Halaman Login') }}
            </a>
        </div>
    </form>
</x-guest-layout>