<header class="bg-white shadow-md">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center">
                <a href="{{ route('pasien.dashboard') }}" class="text-2xl font-bold text-blue-600">TeleKonsul</a>
                <span class="ml-2 text-sm text-gray-500">Pasien</span>
            </div>
            
            <div class="hidden md:flex space-x-6">
                <a href="{{ route('pasien.dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium {{ request()->routeIs('pasien.dashboard') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('pasien.konsultasi.index') }}" class="text-gray-700 hover:text-blue-600 font-medium {{ request()->routeIs('pasien.konsultasi.*') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                    Telekonsultasi
                </a>
                <a href="{{ route('pasien.chatbot.index') }}" class="text-gray-700 hover:text-blue-600 font-medium {{ request()->routeIs('pasien.chatbot.*') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                    Chatbot AI
                </a>
                <a href="{{ route('pasien.riwayat.index') }}" class="text-gray-700 hover:text-blue-600 font-medium {{ request()->routeIs('pasien.riwayat.*') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                    Riwayat
                </a>
                <a href="{{ route('pasien.profil.index') }}" class="text-gray-700 hover:text-blue-600 font-medium {{ request()->routeIs('pasien.profil.*') ? 'text-blue-600 border-b-2 border-blue-600' : '' }}">
                    Profil Saya
                </a>
            </div>
            
            <div class="flex items-center">
                <div class="relative" x-data="{ isOpen: false }">
                    <button @click="isOpen = !isOpen" class="flex items-center focus:outline-none group">
                        @php
                            $pasien = Auth::user()->pasien;
                            $displayName = $pasien && $pasien->nama ? $pasien->nama : Auth::user()->name;
                            $namaParts = explode(' ', $displayName);
                            $displayName = count($namaParts) > 2 ? $namaParts[0] . ' ' . $namaParts[1] : $displayName;
                            $hasFoto = $pasien && $pasien->foto && $pasien->foto != 'default.jpg';
                        @endphp
                        
                        @if($hasFoto)
                            <div class="w-9 h-9 rounded-full bg-cover bg-center shadow-sm border-2 border-white group-hover:border-blue-200 transition-all duration-200" style="background-image: url('{{ asset('img/pasien/' . $pasien->foto) }}')"></div>
                        @else
                            <div class="w-9 h-9 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold shadow-sm border-2 border-white group-hover:border-blue-200 transition-all duration-200">
                                {{ strtoupper(substr($displayName, 0, 1)) }}
                            </div>
                        @endif
                        
                        <span class="ml-2 hidden md:block font-medium text-gray-700 group-hover:text-blue-600 transition-colors duration-200">{{ $displayName }}</span>
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
                         class="absolute right-0 mt-3 w-56 bg-white rounded-lg shadow-lg py-2 z-10 border border-gray-100">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-800">{{ $displayName }}</p>
                        </div>
                        <a href="{{ route('pasien.pengaturan.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-150">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Pengaturan
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-150">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Mobile menu button -->
                <button type="button" class="md:hidden ml-3 text-gray-500 focus:outline-none" x-data="{}" @click="$dispatch('toggle-mobile-menu')">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile menu -->
    <div class="md:hidden hidden" x-data="{ open: false }" x-show="open" @toggle-mobile-menu.window="open = !open">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="{{ route('pasien.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('pasien.dashboard') ? 'bg-blue-100 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                Beranda
            </a>
            <a href="{{ route('pasien.konsultasi.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('pasien.konsultasi.*') ? 'bg-blue-100 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                Telekonsultasi
            </a>
            <a href="{{ route('pasien.chatbot.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('pasien.chatbot.*') ? 'bg-blue-100 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                Chatbot AI
            </a>
            <a href="{{ route('pasien.riwayat.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('pasien.riwayat.*') ? 'bg-blue-100 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                Riwayat
            </a>
            <a href="{{ route('pasien.profil.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('pasien.profil.*') ? 'bg-blue-100 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                Profil Saya
            </a>
        </div>
    </div>
</header>

<script>
    // Mobile menu toggle
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
    });
</script>

@push('scripts')
<script>
    function notificationSystem() {
        return {
            open: false,
            notifications: [],
            unreadCount: 0,
            hasNewNotifications: false,
            lastVisit: localStorage.getItem('lastNotifVisitPasien') || 0,
            
            init() {
                this.fetchNotifications();
                
                // Polling setiap 30 detik
                setInterval(() => {
                    this.fetchNotifications();
                }, 30000);
            },
            
            toggleNotifications() {
                this.open = !this.open;
                if (this.open) {
                    this.fetchNotifications();
                }
            },
            
            fetchNotifications() {
                fetch('{{ route('notifications.getLatest') }}')
                    .then(response => response.json())
                    .then(data => {
                        this.notifications = data.notifications;
                        this.unreadCount = data.unreadCount;
                        
                        // Cek apakah ada notifikasi baru sejak kunjungan terakhir
                        if (this.unreadCount > 0 && this.notifications.length > 0) {
                            const latestNotifTime = new Date(this.notifications[0].created_at).getTime();
                            this.hasNewNotifications = latestNotifTime > this.lastVisit;
                        } else {
                            this.hasNewNotifications = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching notifications:', error);
                    });
            },
            
            formatDate(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diffInSeconds = Math.floor((now - date) / 1000);
                
                if (diffInSeconds < 60) {
                    return 'Baru saja';
                } else if (diffInSeconds < 3600) {
                    const minutes = Math.floor(diffInSeconds / 60);
                    return `${minutes} menit yang lalu`;
                } else if (diffInSeconds < 86400) {
                    const hours = Math.floor(diffInSeconds / 3600);
                    return `${hours} jam yang lalu`;
                } else {
                    const days = Math.floor(diffInSeconds / 86400);
                    return `${days} hari yang lalu`;
                }
            }
        };
    }
</script>
@endpush 