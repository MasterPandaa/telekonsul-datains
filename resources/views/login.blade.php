@extends('layouts.base')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white shadow-lg rounded-3xl overflow-hidden w-full max-w-6xl flex mx-4">
        <!-- Bagian Kiri: Judul dan Form Login -->
        <div class="w-full md:w-1/2 p-10 flex flex-col justify-center">
            <div class="mb-10">
                <!-- Logo -->
                <div class="flex items-center mb-2">
                    <div class="w-3 h-3 bg-blue-400 rounded-full mr-2"></div>
                    <span class="text-sm font-medium text-blue-400">SIM Telekonsultasi</span>
                </div>
                
                <!-- Header -->
                <h1 class="text-3xl font-bold text-gray-900 mt-6">
                    Halo,
                    <span class="block">Selamat Datang Kembali</span>
                </h1>
                <p class="text-gray-500 text-sm mt-2">Silahkan Login untuk Mengakses Aplikasi</p>
            </div>
            
            <form method="POST" action="{{ url('/login') }}" class="space-y-4">
                @csrf
                <div>
                    <input type="email" name="email" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-300 focus:border-blue-300" required autofocus value="{{ old('email') }}" placeholder="user@example.com">
                    @error('email')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <input type="password" name="password" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-300 focus:border-blue-300" required placeholder="••••••••••••">
                    @error('password')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-blue-400 focus:ring-blue-300 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-600">Ingat Saya</label>
                    <a href="#" class="text-sm text-gray-500 ml-auto hover:text-blue-400">Lupa Password?</a>
                </div>
                
                <div>
                    <button type="submit" class="w-full bg-blue-400 hover:bg-blue-500 text-white font-medium py-3 rounded-md transition-all">
                        Login
                    </button>
                </div>
            </form>
            
            <div class="mt-6 text-center text-sm text-gray-600">
                <p>Tidak Punya Akun? <a href="#" class="text-blue-400 font-medium">Daftar</a></p>
            </div>
        </div>
        
        <!-- Bagian Kanan: Ilustrasi -->
        <div class="hidden md:block md:w-1/2 relative overflow-hidden">
            <img src="{{ asset('img/animasi-login.png') }}" alt="Login Illustration" class="w-full h-full object-cover">
        </div>
    </div>
</div>

<style>
/* Animasi untuk elemen pada halaman login */
@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0px); }
}

/* Efek hover pada tombol login */
button[type="submit"] {
    box-shadow: 0 4px 12px rgba(96, 165, 250, 0.3);
}
button[type="submit"]:hover {
    box-shadow: 0 6px 16px rgba(96, 165, 250, 0.4);
    transform: translateY(-2px);
}
</style>
@endsection 