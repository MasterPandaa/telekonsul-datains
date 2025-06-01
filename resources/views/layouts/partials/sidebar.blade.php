<aside class="w-64 bg-white shadow-lg flex flex-col py-6 px-4 min-h-screen hidden md:flex">
    <!-- User Info -->
    <div class="px-4 py-3 mb-6 bg-blue-50 rounded-lg">
        <div class="flex items-center">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=3b82f6&color=fff" class="w-10 h-10 rounded-full border-2 border-white">
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500">{{ ucfirst(Auth::user()->role) }}</p>
            </div>
        </div>
    </div>
    
    <nav class="flex-1">
        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 px-4">Menu Utama</div>
        <ul class="space-y-1">
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('dashboard')) bg-blue-100 font-medium text-blue-700 @endif">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>
            </li>
        </ul>

        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 px-4">Manajemen Data</div>
        <ul class="space-y-1">
            <li>
                <a href="{{ route('admin.mahasiswa.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('admin.mahasiswa.*')) bg-blue-100 font-medium text-blue-700 @endif">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 0 0-3-3.87M9 20H4v-2a4 4 0 0 1 3-3.87m9-4V7a4 4 0 1 0-8 0v3m8 0a4 4 0 1 1-8 0"/></svg>
                    Data Mahasiswa
                </a>
            </li>
            <li>
                <a href="{{ route('admin.dosen.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('admin.dosen.*')) bg-blue-100 font-medium text-blue-700 @endif">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    Data Dosen
                </a>
            </li>
            <li>
                <a href="{{ route('admin.pasien.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('admin.pasien.*')) bg-blue-100 font-medium text-blue-700 @endif">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Data Pasien
                </a>
            </li>
        </ul>

        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 px-4">Monitoring</div>
        <ul class="space-y-1">
            <li x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center w-full px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition focus:outline-none @if(request()->routeIs('admin.log.*')) bg-blue-100 font-medium text-blue-700 @endif">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Log & Aktivitas
                    <svg class="w-4 h-4 ml-auto" :class="{'transform rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <ul x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="pl-10 mt-1 space-y-1">
                    <li><a href="{{ route('admin.log.database') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 transition rounded-lg @if(request()->routeIs('admin.log.database')) font-medium text-blue-700 @endif">Database Log</a></li>
                    <li><a href="{{ route('admin.log.system') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 transition rounded-lg @if(request()->routeIs('admin.log.system')) font-medium text-blue-700 @endif">System Log</a></li>
                </ul>
            </li>
        </ul>

        <div class="border-t border-gray-200 my-6"></div>
    </nav>
    
    <div class="mt-auto px-4 py-2">
        <div class="bg-blue-50 rounded-lg p-3">
            <p class="text-xs text-center text-gray-600">Versi Sistem 1.0.0</p>
        </div>
    </div>
</aside>

<!-- Sidebar Mobile -->
<div id="sidebar-mobile" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-gray-800 opacity-50" onclick="document.getElementById('sidebar-mobile').classList.add('hidden')"></div>
    <div class="absolute inset-y-0 left-0 w-64 bg-white shadow-lg py-6 px-4 overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=3b82f6&color=fff" class="w-10 h-10 rounded-full border-2 border-white mr-3">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ ucfirst(Auth::user()->role) }}</p>
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
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('dashboard')) bg-blue-100 font-medium text-blue-700 @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                </li>
            </ul>

            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 px-2">Manajemen Data</div>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.mahasiswa.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('admin.mahasiswa.*')) bg-blue-100 font-medium text-blue-700 @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 0 0-3-3.87M9 20H4v-2a4 4 0 0 1 3-3.87m9-4V7a4 4 0 1 0-8 0v3m8 0a4 4 0 1 1-8 0"/></svg>
                        Data Mahasiswa
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.dosen.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('admin.dosen.*')) bg-blue-100 font-medium text-blue-700 @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        Data Dosen
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.pasien.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('admin.pasien.*')) bg-blue-100 font-medium text-blue-700 @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Data Pasien
                    </a>
                </li>
            </ul>

            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 px-2">Monitoring</div>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.log.database') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('admin.log.database')) bg-blue-100 font-medium text-blue-700 @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Database Log
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.log.system') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('admin.log.system')) bg-blue-100 font-medium text-blue-700 @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        System Log
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