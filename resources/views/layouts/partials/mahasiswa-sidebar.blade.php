<aside class="w-64 bg-white shadow-lg flex flex-col py-6 px-4 min-h-screen hidden md:flex">
    <!-- User Info -->
    <div class="px-4 py-3 mb-6 bg-blue-50 rounded-lg">
        <div class="flex items-center">
            <div class="ml-1">
                <p class="text-sm font-medium text-gray-800">{{ Auth::user()->email }}</p>
                <p class="text-xs text-gray-500">Mahasiswa</p>
            </div>
        </div>
    </div>
    
    <nav class="flex-1">
        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 px-4">Menu Utama</div>
        <ul class="space-y-1">
            <li>
                <a href="{{ route('mahasiswa.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('mahasiswa.dashboard')) bg-blue-100 font-medium text-blue-700 @endif">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('mahasiswa.profil.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('mahasiswa.profil.*')) bg-blue-100 font-medium text-blue-700 @endif">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Profil Saya
                </a>
            </li>
        </ul>

        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 px-4">Telekonsultasi</div>
        <ul class="space-y-1">
            <li>
                <a href="{{ route('mahasiswa.konsultasi.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('mahasiswa.konsultasi.*')) bg-blue-100 font-medium text-blue-700 @endif" 
                   @click="localStorage.setItem('lastKonsultasiVisit', Date.now())">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    <span>Permintaan Konsultasi</span>
                    
                    <!-- Titik notifikasi -->
                    <span 
                        x-data="{ 
                            unreadCount: 0,
                            lastVisit: localStorage.getItem('lastKonsultasiVisit') || 0,
                            hasNewNotifications: false
                        }"
                        x-init="
                            fetch('{{ route('notifications.getLatest') }}')
                                .then(response => response.json())
                                .then(data => {
                                    unreadCount = data.notifications.filter(n => n.type === 'konsultasi_baru' && !n.is_read).length;
                                    
                                    // Cek apakah ada notifikasi baru sejak kunjungan terakhir
                                    if (unreadCount > 0) {
                                        const latestNotifTime = new Date(data.notifications.find(n => n.type === 'konsultasi_baru' && !n.is_read)?.created_at || 0).getTime();
                                        hasNewNotifications = latestNotifTime > lastVisit;
                                    }
                                });
                        "
                        x-show="hasNewNotifications"
                        class="ml-auto h-2 w-2 rounded-full bg-red-500"
                    ></span>
                </a>
            </li>
            <li>
                <a href="{{ route('mahasiswa.riwayat.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('mahasiswa.riwayat.*')) bg-blue-100 font-medium text-blue-700 @endif"
                   @click="localStorage.setItem('lastRiwayatVisit', Date.now())">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Riwayat & Nilai</span>
                    
                    <!-- Titik notifikasi -->
                    <span 
                        x-data="{ 
                            unreadCount: 0,
                            lastVisit: localStorage.getItem('lastRiwayatVisit') || 0,
                            hasNewNotifications: false
                        }"
                        x-init="
                            fetch('{{ route('notifications.getLatest') }}')
                                .then(response => response.json())
                                .then(data => {
                                    unreadCount = data.notifications.filter(n => n.type === 'rating_baru' && !n.is_read).length;
                                    
                                    // Cek apakah ada notifikasi baru sejak kunjungan terakhir
                                    if (unreadCount > 0) {
                                        const latestNotifTime = new Date(data.notifications.find(n => n.type === 'rating_baru' && !n.is_read)?.created_at || 0).getTime();
                                        hasNewNotifications = latestNotifTime > lastVisit;
                                    }
                                });
                        "
                        x-show="hasNewNotifications"
                        class="ml-auto h-2 w-2 rounded-full bg-red-500"
                    ></span>
                </a>
            </li>
        </ul>

        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 px-4">Pembelajaran</div>
        <ul class="space-y-1">
            <li>
                <a href="{{ route('mahasiswa.quiz.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('mahasiswa.quiz.*')) bg-blue-100 font-medium text-blue-700 @endif">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Quiz & Evaluasi
                </a>
            </li>
        </ul>

        <div class="border-t border-gray-200 my-6"></div>
    </nav>
    
    <div class="mt-auto px-4 py-2">
        <div class="bg-blue-50 rounded-lg p-3">
            <p class="text-xs text-center text-gray-600">Telekonsultasi v1.0</p>
        </div>
    </div>
</aside>

<!-- Sidebar Mobile -->
<div id="sidebar-mobile" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-gray-800 opacity-50" onclick="document.getElementById('sidebar-mobile').classList.add('hidden')"></div>
    <div class="absolute inset-y-0 left-0 w-64 bg-white shadow-lg py-6 px-4 overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ Auth::user()->email }}</p>
                    <p class="text-xs text-gray-500">Mahasiswa</p>
                </div>
            </div>
            <button class="text-gray-600" onclick="document.getElementById('sidebar-mobile').classList.add('hidden')">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <!-- Copy all menu items from desktop sidebar -->
        <nav class="flex-1">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 px-2">Menu Utama</div>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('mahasiswa.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('mahasiswa.dashboard')) bg-blue-100 font-medium text-blue-700 @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('mahasiswa.profil.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('mahasiswa.profil.*')) bg-blue-100 font-medium text-blue-700 @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Profil Saya
                    </a>
                </li>
            </ul>

            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 px-2">Telekonsultasi</div>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('mahasiswa.konsultasi.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('mahasiswa.konsultasi.*')) bg-blue-100 font-medium text-blue-700 @endif" 
                       @click="localStorage.setItem('lastKonsultasiVisit', Date.now())">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        <span>Permintaan Konsultasi</span>
                        
                        <!-- Titik notifikasi -->
                        <span 
                            x-data="{ 
                                unreadCount: 0,
                                lastVisit: localStorage.getItem('lastKonsultasiVisit') || 0,
                                hasNewNotifications: false
                            }"
                            x-init="
                                fetch('{{ route('notifications.getLatest') }}')
                                    .then(response => response.json())
                                    .then(data => {
                                        unreadCount = data.notifications.filter(n => n.type === 'konsultasi_baru' && !n.is_read).length;
                                        
                                        // Cek apakah ada notifikasi baru sejak kunjungan terakhir
                                        if (unreadCount > 0) {
                                            const latestNotifTime = new Date(data.notifications.find(n => n.type === 'konsultasi_baru' && !n.is_read)?.created_at || 0).getTime();
                                            hasNewNotifications = latestNotifTime > lastVisit;
                                        }
                                    });
                            "
                            x-show="hasNewNotifications"
                            class="ml-auto h-2 w-2 rounded-full bg-red-500"
                        ></span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('mahasiswa.riwayat.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('mahasiswa.riwayat.*')) bg-blue-100 font-medium text-blue-700 @endif"
                       @click="localStorage.setItem('lastRiwayatVisit', Date.now())">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Riwayat & Nilai</span>
                        
                        <!-- Titik notifikasi -->
                        <span 
                            x-data="{ 
                                unreadCount: 0,
                                lastVisit: localStorage.getItem('lastRiwayatVisit') || 0,
                                hasNewNotifications: false
                            }"
                            x-init="
                                fetch('{{ route('notifications.getLatest') }}')
                                    .then(response => response.json())
                                    .then(data => {
                                        unreadCount = data.notifications.filter(n => n.type === 'rating_baru' && !n.is_read).length;
                                        
                                        // Cek apakah ada notifikasi baru sejak kunjungan terakhir
                                        if (unreadCount > 0) {
                                            const latestNotifTime = new Date(data.notifications.find(n => n.type === 'rating_baru' && !n.is_read)?.created_at || 0).getTime();
                                            hasNewNotifications = latestNotifTime > lastVisit;
                                        }
                                    });
                            "
                            x-show="hasNewNotifications"
                            class="ml-auto h-2 w-2 rounded-full bg-red-500"
                        ></span>
                    </a>
                </li>
            </ul>

            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 px-2">Pembelajaran</div>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('mahasiswa.quiz.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('mahasiswa.quiz.*')) bg-blue-100 font-medium text-blue-700 @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Quiz & Evaluasi
                    </a>
                </li>
            </ul>
            
            <div class="border-t border-gray-200 my-6"></div>
            
            <ul class="space-y-1 px-3">
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-red-50 hover:text-red-700 transition text-left">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</div> 