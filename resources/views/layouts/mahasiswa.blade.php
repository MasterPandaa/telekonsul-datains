@extends('layouts.base')
@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar -->
    @include('layouts.partials.mahasiswa-sidebar')
    <div class="flex-1 flex flex-col min-h-screen">
        <!-- Navbar -->
        <nav class="bg-white shadow-sm flex items-center justify-between px-6 py-3">
            <div class="flex items-center space-x-4 w-full">
                <button class="md:hidden focus:outline-none text-blue-700" onclick="document.getElementById('sidebar-mobile').classList.toggle('hidden')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                
                <div class="relative flex-1 max-w-xl">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" placeholder="Cari data..." class="block w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 bg-gray-50"/>
                </div>
            </div>
            
            <div class="flex items-center space-x-6">
                <!-- Tanggal dan Waktu -->
                <div class="hidden md:flex items-center text-gray-600 text-sm whitespace-nowrap">
                    <div class="flex items-center">
                        <span id="realtime-clock">{{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('d M Y H:i:s') }}</span>
                    </div>
                </div>
                
                <!-- Notifications Dropdown -->
                <div x-data="notificationSystem()" class="relative">
                    <button @click="toggleNotifications(); localStorage.setItem('lastNotifVisitMahasiswa', Date.now());" class="flex items-center text-gray-600 focus:outline-none">
                        <div class="relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6.002 6.002 0 0 0-4-5.659V4a2 2 0 1 0-4 0v1.341C7.67 7.165 6 9.388 6 12v2.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9"></path>
                            </svg>
                            <span x-show="hasNewNotifications" class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                        </div>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="absolute right-0 mt-3 w-80 bg-white rounded-lg shadow-lg py-2 z-10" style="display: none;">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 py-2 border-b">Notifikasi</h3>
                        <div class="max-h-60 overflow-y-auto">
                            <template x-if="notifications.length === 0">
                                <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                    Tidak ada notifikasi
                                </div>
                            </template>
                            
                            <template x-for="notification in notifications" :key="notification.id">
                                <a :href="`{{ url('notifications') }}/${notification.id}/read`" class="block px-4 py-3 hover:bg-gray-50 transition border-b" :class="{'bg-blue-50': !notification.is_read}">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 bg-blue-100 rounded-full p-2">
                                            <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900" x-text="notification.message"></p>
                                            <p class="text-xs text-gray-500 mt-1" x-text="formatDate(notification.created_at)"></p>
                                        </div>
                                    </div>
                                </a>
                            </template>
                        </div>
                        <div class="px-4 py-2 border-t">
                            <div class="flex justify-between">
                                <a href="{{ route('notifications.index') }}" class="text-xs text-blue-600 hover:underline">Lihat semua</a>
                                <form action="{{ route('notifications.readAll') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs text-gray-600 hover:underline">Tandai semua dibaca</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- User Dropdown -->
                <div class="relative" x-data="{ isOpen: false }">
                    <button @click="isOpen = !isOpen" class="flex items-center focus:outline-none group">
                        @php
                            $mahasiswa = Auth::user()->mahasiswa;
                            $displayName = $mahasiswa && $mahasiswa->nama ? $mahasiswa->nama : Auth::user()->name;
                            // Ambil hanya kata pertama jika nama terlalu panjang
                            $displayName = explode(' ', $displayName)[0];
                            
                            // Gunakan accessor getFotoUrlAttribute dari model Mahasiswa jika tersedia
                            if ($mahasiswa) {
                                $fotoUrl = $mahasiswa->foto_url;
                            } else {
                                $fotoUrl = null;
                            }
                        @endphp
                        
                        @if($fotoUrl && $fotoUrl != asset('img/mahasiswa/default.jpg'))
                            <div class="w-9 h-9 rounded-full bg-cover bg-center shadow-sm border-2 border-white group-hover:border-blue-200 transition-all duration-200" style="background-image: url('{{ $fotoUrl }}')"></div>
                        @else
                            <div class="w-9 h-9 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold shadow-sm border-2 border-white group-hover:border-blue-200 transition-all duration-200">
                                {{ strtoupper(substr($displayName, 0, 1)) }}
                            </div>
                        @endif
                        
                        <span class="ml-2 hidden md:block font-medium text-gray-700 group-hover:text-blue-600 transition-colors duration-200 truncate max-w-[100px]">{{ $displayName }}</span>
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
                            <p class="text-sm font-medium text-gray-800">{{ $mahasiswa && $mahasiswa->nama ? $mahasiswa->nama : Auth::user()->name }}</p>
                        </div>
                        <a href="{{ route('mahasiswa.pengaturan.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-150">
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
            </div>
        </nav>
        
        <main class="flex-1 p-6 md:p-10">
            @yield('mahasiswa-content')
        </main>
    </div>
</div>

@push('scripts')
<script>
    function notificationSystem() {
        return {
            open: false,
            notifications: [],
            unreadCount: 0,
            hasNewNotifications: false,
            lastVisit: localStorage.getItem('lastNotifVisitMahasiswa') || 0,
            
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
@endsection 