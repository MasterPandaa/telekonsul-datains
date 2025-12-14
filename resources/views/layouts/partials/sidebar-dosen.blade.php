@php
    $dosen = Auth::user()->dosen;
    $displayName = $dosen && $dosen->nama ? $dosen->nama : Auth::user()->name;
    
    // Foto dosen
    if ($dosen && isset($dosen->foto)) {
        $fotoUrl = asset('storage/img/dosen/' . $dosen->foto);
    } else {
        $fotoUrl = asset('img/dokter/default.jpg');
    }
@endphp

<aside class="w-64 bg-white shadow-lg flex flex-col py-6 px-4 min-h-screen hidden md:flex">
    <!-- Logo -->
    <div class="px-4 mb-6 flex justify-center border-b-2 border-gray-100 pb-6">
        <img src="{{ asset('img/BLUE_ASSRI.png') }}" alt="ASSRI Logo" class="h-7 w-auto">
    </div>
    
    <nav class="flex-1">
        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 px-4">Menu Utama</div>
        <ul class="space-y-1">
            <li>
                <a href="{{ route('dosen.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('dosen.dashboard')) bg-blue-100 font-medium text-blue-700 @endif">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>
            </li>
        </ul>

        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 px-4">Supervisi</div>
        <ul class="space-y-1">
            <li>
                <a href="{{ route('dosen.penilaian.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('dosen.penilaian.*')) bg-blue-100 font-medium text-blue-700 @endif">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Penilaian Konsultasi
                </a>
            </li>
            <li>
                <a href="{{ route('dosen.rekap.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('dosen.rekap.*')) bg-blue-100 font-medium text-blue-700 @endif">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Rekap Data
                </a>
            </li>
        </ul>

        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 px-4">Pengaturan</div>
        <ul class="space-y-1">
            <li>
                <a href="{{ route('dosen.profil.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('dosen.profil.*')) bg-blue-100 font-medium text-blue-700 @endif">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Profil Saya
                </a>
            </li>
            <li>
                <a href="{{ route('dosen.pengaturan.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('dosen.pengaturan.*')) bg-blue-100 font-medium text-blue-700 @endif">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Pengaturan
                </a>
            </li>
        </ul>
    </nav>
</aside>

<!-- Mobile Sidebar -->
<div id="sidebar-mobile" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-gray-800 opacity-50" onclick="document.getElementById('sidebar-mobile').classList.add('hidden')"></div>
    <div class="absolute inset-y-0 left-0 w-64 bg-white shadow-lg py-6 px-4 overflow-y-auto">
        <!-- Logo for Mobile -->
        <div class="flex items-center justify-between mb-6 border-b-2 border-gray-300 pb-6">
            <img src="{{ asset('img/BLUE_ASSRI.png') }}" alt="ASSRI Logo" class="h-7 w-auto">
            <button class="text-gray-600" onclick="document.getElementById('sidebar-mobile').classList.add('hidden')">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 px-2">Menu Utama</div>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('dosen.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('dosen.dashboard')) bg-blue-100 font-medium text-blue-700 @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>
                </li>
            </ul>

            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 px-2">Supervisi</div>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('dosen.penilaian.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('dosen.penilaian.*')) bg-blue-100 font-medium text-blue-700 @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Penilaian Konsultasi
                    </a>
                </li>
                <li>
                    <a href="{{ route('dosen.rekap.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('dosen.rekap.*')) bg-blue-100 font-medium text-blue-700 @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Rekap Data
                    </a>
                </li>
            </ul>

            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 px-2">Pengaturan</div>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('dosen.profil.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('dosen.profil.*')) bg-blue-100 font-medium text-blue-700 @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Profil Saya
                    </a>
                </li>
                <li>
                    <a href="{{ route('dosen.pengaturan.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-blue-50 transition @if(request()->routeIs('dosen.pengaturan.*')) bg-blue-100 font-medium text-blue-700 @endif">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Pengaturan
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</div> 