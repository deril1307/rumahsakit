<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informasi Data Diri') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Silakan perbarui nama profil, nomor telepon, dan alamat email Anda di sini.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                required autofocus autocomplete="name" placeholder="Masukkan nama lengkap Anda" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="no_telp" :value="__('Nomor Telepon / WhatsApp')" />
            <x-text-input id="no_telp" name="no_telp" type="text" class="mt-1 block w-full" 
                :value="old('no_telp', $user->no_telp)" 
                placeholder="Contoh: 081234567890" />
            <x-input-error class="mt-2" :messages="$errors->get('no_telp')" />
        </div>

        @if(!empty($user->spesialisasi))
        <div>
            <x-input-label for="spesialisasi" :value="__('Spesialisasi (Tidak dapat diubah)')" />
            <x-text-input id="spesialisasi" name="spesialisasi" type="text" 
                class="mt-1 block w-full bg-gray-100 text-gray-500 cursor-not-allowed border-gray-200" 
                :value="$user->spesialisasi" 
                readonly 
                disabled 
            />
            <p class="text-xs text-gray-500 mt-1">
                *Hubungi admin jika ingin mengubah spesialisasi.
            </p>
        </div>
        @endif

        <div>
            <x-input-label for="email" :value="__('Alamat Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" placeholder="contoh: nama@email.com" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Alamat email Anda belum diverifikasi.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Data berhasil disimpan.') }}</p>
            @endif
        </div>
    </form>
</section>