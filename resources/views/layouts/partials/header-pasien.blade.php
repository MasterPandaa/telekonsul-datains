@php
    $pasien = Auth::user()->pasien;
    $displayName = $pasien && $pasien->nama ? $pasien->nama : Auth::user()->name;
    
    // Foto pasien
    if ($pasien && $pasien->foto) {
        $fotoUrl = asset($pasien->foto);
    } else {
        $fotoUrl = asset('img/pasien/default.jpg');
    }
@endphp

<header class="bg-white shadow">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16 md:h-20">
            <!-- Logo (Desktop) -->
            <div class="flex items-center">
                <a href="{{ route('pasien.dashboard') }}" class="flex items-center no-underline">
                    <img src="{{ asset('img/Blue_ASSRI.png') }}" alt="ASSRI Logo" class="h-6 md:h-8">
                </a>
            </div>
            
            <!-- Navigation (Desktop) -->
            <nav class="hidden md:flex items-center space-x-1">
                <a href="{{ route('pasien.dashboard') }}" class="px-3 py-2 text-base font-medium rounded-md {{ request()->routeIs('pasien.dashboard') ? 'text-blue-700 bg-blue-50' : 'text-gray-700 hover:text-blue-700 hover:bg-blue-50' }}">
                    Dashboard
                </a>
                
                <a href="{{ route('pasien.konsultasi.index') }}" class="px-3 py-2 text-base font-medium rounded-md {{ request()->routeIs('pasien.konsultasi.*') ? 'text-blue-700 bg-blue-50' : 'text-gray-700 hover:text-blue-700 hover:bg-blue-50' }}">
                    Telekonsultasi
                </a>
                
                <a href="{{ route('pasien.riwayat.index') }}" class="px-3 py-2 text-base font-medium rounded-md {{ request()->routeIs('pasien.riwayat.*') ? 'text-blue-700 bg-blue-50' : 'text-gray-700 hover:text-blue-700 hover:bg-blue-50' }}">
                    Riwayat
                </a>
                
                <a href="{{ route('pasien.profil.index') }}" class="px-3 py-2 text-base font-medium rounded-md {{ request()->routeIs('pasien.profil.*') ? 'text-blue-700 bg-blue-50' : 'text-gray-700 hover:text-blue-700 hover:bg-blue-50' }}">
                    Profile Saya
                </a>
            </nav>
            
            <div class="flex items-center space-x-4">
                <!-- Notification dropdown -->
                <div x-data="{ open: false, notifications: [], unreadCount: 0 }" x-init="
                    fetch('{{ route('notifications.getLatest') }}')
                    .then(response => response.json())
                    .then(data => {
                        notifications = data.notifications;
                        unreadCount = data.unreadCount;
                    })
                    .catch(error => console.error('Error:', error));
                " class="relative">
                    <button @click="open = !open" class="relative p-1 text-gray-600 hover:text-blue-600 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 7.165 6 9.388 6 12v2.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span x-show="unreadCount > 0" class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg overflow-hidden z-50 border border-gray-200" style="z-index: 9999;">
                        <div class="p-3 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Notifikasi</h3>
                        </div>
                        <div class="max-h-60 overflow-y-auto">
                            <template x-for="notification in notifications" :key="notification.id">
                                <a :href="`{{ url('notifications') }}/${notification.id}/read`" class="block p-4 border-b border-gray-200 hover:bg-gray-50" :class="{'bg-blue-50': !notification.is_read}">
                                    <div class="flex">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-500 flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-gray-800" x-text="notification.message"></p>
                                            <p class="text-xs text-gray-500 mt-1" x-text="new Date(notification.created_at).toLocaleString('id-ID')"></p>
                                        </div>
                                    </div>
                                </a>
                            </template>
                            <div x-show="notifications.length === 0" class="p-4 text-center text-gray-500 text-sm">
                                Tidak ada notifikasi
                            </div>
                        </div>
                        <div class="p-3 border-t border-gray-200 bg-gray-50 text-right">
                            <a href="{{ route('notifications.index') }}" class="text-xs text-blue-600 hover:underline">Lihat Semua Notifikasi</a>
                        </div>
                    </div>
                </div>
                
                <!-- User dropdown -->
                <div class="relative" x-data="{ isOpen: false }">
                    <button @click="isOpen = !isOpen" class="flex items-center focus:outline-none group">
                        <div class="relative w-9 h-9 overflow-hidden bg-gray-200 rounded-full border-2 border-white group-hover:border-blue-200 transition-all duration-200">
                            @if($fotoUrl && file_exists(public_path(str_replace(asset(''), '', $fotoUrl))))
                                <img src="{{ $fotoUrl }}" alt="{{ $displayName }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium">
                                    {{ strtoupper(substr($displayName, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <span class="ml-2 hidden md:block text-sm font-medium text-gray-700 group-hover:text-blue-600 transition-colors duration-200">{{ $displayName }}</span>
                        <svg class="ml-1 h-5 w-5 text-gray-400 group-hover:text-blue-500 transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    
                    <div x-show="isOpen" 
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 transform scale-95" 
                         x-transition:enter-end="opacity-100 transform scale-100" 
                         x-transition:leave="transition ease-in duration-150" 
                         x-transition:leave-start="opacity-100 transform scale-100" 
                         x-transition:leave-end="opacity-0 transform scale-95" 
                         @click.away="isOpen = false" 
                         class="absolute right-0 mt-3 w-56 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-100" style="z-index: 9999;">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-800">{{ $displayName }}</p>
                            <p class="text-xs text-gray-500">Pasien</p>
                        </div>
                        <a href="{{ route('pasien.profil.index') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-150">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>Profil Saya</span>
                            </div>
                        </a>
                        <a href="{{ route('pasien.pengaturan.index') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-150">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>Pengaturan</span>
                            </div>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-200 mt-1">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-150">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span>Keluar</span>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Mobile menu button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" x-data="{ mobileMenuOpen: false }" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-blue-700 hover:bg-blue-50 focus:outline-none">
                    <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    
                    <!-- Mobile Navigation Menu -->
                    <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" x-cloak class="absolute top-full right-0 left-0 bg-white shadow-md border-t border-gray-200 z-50" style="z-index: 9999;">
                        <div class="px-2 pt-2 pb-3 space-y-1">
                            <a href="{{ route('pasien.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('pasien.dashboard') ? 'text-blue-700 bg-blue-50' : 'text-gray-700 hover:text-blue-700 hover:bg-blue-50' }}">
                                Dashboard
                            </a>
                            
                            <a href="{{ route('pasien.konsultasi.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('pasien.konsultasi.*') ? 'text-blue-700 bg-blue-50' : 'text-gray-700 hover:text-blue-700 hover:bg-blue-50' }}">
                                Telekonsultasi
                            </a>
                            
                            <a href="{{ route('pasien.riwayat.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('pasien.riwayat.*') ? 'text-blue-700 bg-blue-50' : 'text-gray-700 hover:text-blue-700 hover:bg-blue-50' }}">
                                Riwayat
                            </a>
                            
                            <a href="{{ route('pasien.profil.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('pasien.profil.*') ? 'text-blue-700 bg-blue-50' : 'text-gray-700 hover:text-blue-700 hover:bg-blue-50' }}">
                                Profile Saya
                            </a>
                        </div>
                    </div>
                </button>
            </div>
        </div>
    </div>
</header> 