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

        <script>
            function updateClock() {
                const clockElement = document.getElementById('realtime-clock');
                const now = new Date();
                const jakartaTime = new Date(now.toLocaleString('en-US', { timeZone: 'Asia/Jakarta' }));
                const options = { 
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                };
                clockElement.textContent = jakartaTime.toLocaleDateString('id-ID', options).replace(',', '');
            }

            setInterval(updateClock, 1000);
            updateClock();
        </script>
        
        <!-- Notifications Dropdown -->
        <div x-data="notificationSystem()" class="relative">
            <button @click="toggleNotifications(); localStorage.setItem('lastNotifVisitAdmin', Date.now());" class="flex items-center text-gray-600 focus:outline-none">
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
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center text-gray-700 hover:text-blue-600 focus:outline-none">
                <img class="h-8 w-8 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=3b82f6&color=fff" alt="Avatar">
                <span class="ml-2 text-sm font-medium hidden md:block">{{ Auth::user()->name }}</span>
                <svg class="ml-1 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            
            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" style="display: none;">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Bottom Navigation -->
<div class="md:hidden fixed bottom-0 left-0 right-0 z-20 bg-white border-t shadow">
    <div class="grid grid-cols-4 gap-1">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center py-3 @if(request()->routeIs('dashboard')) text-blue-600 @else text-gray-600 @endif">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="text-xs mt-1">Home</span>
        </a>
        <a href="{{ route('admin.mahasiswa.index') }}" class="flex flex-col items-center justify-center py-3 @if(request()->routeIs('admin.mahasiswa.*')) text-blue-600 @else text-gray-600 @endif">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 0 0-3-3.87M9 20H4v-2a4 4 0 0 1 3-3.87m9-4V7a4 4 0 1 0-8 0v3m8 0a4 4 0 1 1-8 0"></path>
            </svg>
            <span class="text-xs mt-1">Mahasiswa</span>
        </a>
        <a href="{{ route('admin.dosen.index') }}" class="flex flex-col items-center justify-center py-3 @if(request()->routeIs('admin.dosen.*')) text-blue-600 @else text-gray-600 @endif">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8 4 4 0 010-8zm0 10a5.5 5.5 0 00-5.5 5.5v.5h11v-.5a5.5 5.5 0 00-5.5-5.5z"></path>
            </svg>
            <span class="text-xs mt-1">Dosen</span>
        </a>
        <a href="{{ route('admin.pasien.index') }}" class="flex flex-col items-center justify-center py-3 @if(request()->routeIs('admin.pasien.*')) text-blue-600 @else text-gray-600 @endif">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span class="text-xs mt-1">Pasien</span>
        </a>
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
            lastVisit: localStorage.getItem('lastNotifVisitAdmin') || 0,
            
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