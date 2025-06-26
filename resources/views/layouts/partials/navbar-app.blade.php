<nav class="bg-white shadow-sm">
    <div class="container mx-auto px-4">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="/" class="flex items-center">
                    <img src="{{ asset('img/Blue_ASSRI.png') }}" alt="ASSRI Logo" class="h-8">
                </a>
                <div class="hidden space-x-8 sm:ml-10 sm:flex">
                    <a href="/" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Beranda
                    </a>
                </div>
            </div>
            <div class="flex items-center">
                @if (Route::has('login'))
                    <div class="flex space-x-4">
                        @auth
                            @if(Auth::user()->hasRole('admin'))
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Dashboard
                                </a>
                            @elseif(Auth::user()->hasRole('dokter'))
                                <a href="{{ route('dokter.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Dashboard
                                </a>
                            @elseif(Auth::user()->hasRole('pasien'))
                                <a href="{{ route('pasien.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Dashboard
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                                Login
                            </a>
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </div>
</nav> 