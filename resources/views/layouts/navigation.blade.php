<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('img/logoRS.png') }}" alt="Logo RS Al-Islam" class="block h-9 w-auto">
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    
                    {{-- === MENU ADMIN (5 LINK) === --}}
                    @if(Auth::user()->hasRole('admin'))
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Penjadwalan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.pasien.index')" :active="request()->routeIs('admin.pasien.index')">
                            {{ __('Manajemen Pasien') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.terapis.index')" :active="request()->routeIs('admin.terapis.index')">
                            {{ __('Manajemen Terapis') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.laporan.index')" :active="request()->routeIs('admin.laporan.index')">
                            {{ __('Laporan Harian') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index') || request()->routeIs('admin.users.edit')">
                            {{ __('Manajemen Users') }}
                        </x-nav-link>
                    @endif

                    {{-- === MENU TERAPIS (1 LINK) === --}}
                    @if(Auth::user()->hasRole('terapis'))
                        <x-nav-link :href="route('terapis.dashboard')" :active="request()->routeIs('terapis.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    @endif

                    {{-- === MENU KEPALA (2 LINK) === --}}
                    @if(Auth::user()->hasRole('kepala'))
                        <x-nav-link :href="route('kepala.dashboard')" :active="request()->routeIs('kepala.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('kepala.laporan')" :active="request()->routeIs('kepala.laporan')">
                            {{ __('Laporan Kepala Instalasi') }}
                        </x-nav-link>
                    @endif

                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            
                            <div class="text-start">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-400">
                                    {{ ucfirst(Auth::user()->getRoleNames()->first()) }}
                                </div>
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        
        <div class="pt-2 pb-3 space-y-1">
            
            {{-- === MENU ADMIN (MOBILE) === --}}
            @if(Auth::user()->hasRole('admin'))
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Penjadwalan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.pasien.index')" :active="request()->routeIs('admin.pasien.index')">
                    {{ __('Manajemen Pasien') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.terapis.index')" :active="request()->routeIs('admin.terapis.index')">
                    {{ __('Manajemen Terapis') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.laporan.index')" :active="request()->routeIs('admin.laporan.index')">
                    {{ __('Laporan Harian') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index') || request()->routeIs('admin.users.edit')">
                    {{ __('Manajemen User') }}
                </x-responsive-nav-link>
            @endif

            {{-- === MENU TERAPIS (MOBILE) === --}}
            @if(Auth::user()->hasRole('terapis'))
                <x-responsive-nav-link :href="route('terapis.dashboard')" :active="request()->routeIs('terapis.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @endif
            
            {{-- === MENU KEPALA (MOBILE) === --}}
            @if(Auth::user()->hasRole('kepala'))
                <x-responsive-nav-link :href="route('kepala.dashboard')" :active="request()->routeIs('kepala.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('kepala.laporan')" :active="request()->routeIs('kepala.laporan')">
                    {{ __('Laporan Kepala Instalasi') }}
                </x-responsive-nav-link>
            @endif
        </div>
        
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">
                    {{ ucfirst(Auth::user()->getRoleNames()->first()) }}
                </div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>