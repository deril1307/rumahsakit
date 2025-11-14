    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{-- Tampilkan nama user yang sedang diedit --}}
                {{ __('Edit User: ') }} {{ $user->name }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        
                        {{-- Formulir untuk update --}}
                        {{-- Arahkan ke route 'admin.users.update' --}}
                        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                            @csrf
                            @method('PUT') 

                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="$user->name" required readonly disabled />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="$user->email" required readonly disabled />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="role" :value="__('Role')" />
                                
                                <select name="role" id="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    {{-- Loop semua role yang ada --}}
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}" 
                                            {{-- 
                                            Beri 'selected' jika user sudah punya role itu.
                                            $user->hasRole($role->name) akan cek apakah user punya role tsb.
                                            --}}
                                            @if($user->hasRole($role->name)) selected @endif
                                        >
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button>
                                    {{ __('Update Role') }}
                                </x-primary-button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>