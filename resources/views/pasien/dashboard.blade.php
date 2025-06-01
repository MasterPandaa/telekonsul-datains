@extends('layouts.pasien')

@section('pasien-content')
@if(session('success'))
<div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg animate__animated animate__fadeIn">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    </div>
</div>
@endif

<!-- Hero Section dengan Gradient -->
<div class="relative overflow-hidden bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl shadow-lg mb-8 p-8">
    <div class="relative z-10">
        <h1 class="text-4xl font-bold text-white mb-2">Selamat Datang, {{ Auth::user()->name }}</h1>
        <p class="text-white/90">Selamat datang di TeleKonsul. Kami siap membantu kesehatan Anda.</p>
    </div>
    <div class="absolute right-0 bottom-0 opacity-10">
        <svg class="w-64 h-64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19 3H5C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"/>
            <path d="M12 8V16M8 12H16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"/>
        </svg>
    </div>
</div>

<!-- Statistik Kesehatan -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 transform hover:scale-105 transition-transform duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Konsultasi Selesai</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $konsultasiSelesai }}</h3>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            @if($persenKonsultasi > 0)
            <div class="flex items-center">
                <span class="text-green-500 text-sm flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                    </svg>
                    {{ $persenKonsultasi }}%
                </span>
                <span class="text-gray-400 text-sm ml-2">vs bulan lalu</span>
            </div>
            @elseif($persenKonsultasi < 0)
            <div class="flex items-center">
                <span class="text-red-500 text-sm flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                    {{ abs($persenKonsultasi) }}%
                </span>
                <span class="text-gray-400 text-sm ml-2">vs bulan lalu</span>
            </div>
            @else
            <div class="flex items-center">
                <span class="text-gray-400 text-sm">Tidak ada perubahan</span>
            </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 transform hover:scale-105 transition-transform duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Jadwal Mendatang</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $jadwalMendatang }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            @if($persenJadwal > 0)
            <div class="flex items-center">
                <span class="text-blue-500 text-sm flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                    </svg>
                    {{ $persenJadwal }}%
                </span>
                <span class="text-gray-400 text-sm ml-2">vs minggu lalu</span>
            </div>
            @elseif($persenJadwal < 0)
            <div class="flex items-center">
                <span class="text-red-500 text-sm flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                    {{ abs($persenJadwal) }}%
                </span>
                <span class="text-gray-400 text-sm ml-2">vs minggu lalu</span>
            </div>
            @else
            <div class="flex items-center">
                <span class="text-blue-500 text-sm">Minggu ini</span>
            </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 transform hover:scale-105 transition-transform duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Interaksi Chatbot</p>
                <h3 class="text-2xl font-bold text-gray-800" id="interaksiChatbot">{{ $interaksiChatbot }}</h3>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex items-center" id="persenChatbotContainer">
                @if($persenChatbot > 0)
                <span class="text-purple-500 text-sm flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                    </svg>
                    <span id="persenChatbotValue">{{ $persenChatbot }}</span>%
                </span>
                <span class="text-gray-400 text-sm ml-2">vs minggu lalu</span>
                @elseif($persenChatbot < 0)
                <span class="text-red-500 text-sm flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                    <span id="persenChatbotValue">{{ abs($persenChatbot) }}</span>%
                </span>
                <span class="text-gray-400 text-sm ml-2">vs minggu lalu</span>
                @else
                <span class="text-gray-400 text-sm">Belum ada perubahan</span>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 transform hover:scale-105 transition-transform duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Tingkat Kepuasan</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $tingkatKepuasan }}/5</h3>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex items-center">
                @if($tingkatKepuasan > 0)
                <span class="text-yellow-500 text-sm">{{ $keteranganKepuasan }}</span>
                @else
                <span class="text-gray-400 text-sm">Belum ada rating</span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Layanan Utama -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Kartu Telekonsultasi -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow group">
        <div class="p-6">
            <div class="w-16 h-16 bg-white/20 backdrop-blur-xl rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">Telekonsultasi</h3>
            <p class="text-white/80 mb-4">Konsultasi online dengan mahasiswa kedokteran yang terlatih.</p>
            <a href="{{ route('pasien.konsultasi.index') }}" class="inline-flex items-center text-white hover:underline group-hover:translate-x-2 transition-transform">
                Mulai Konsultasi
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Kartu Chatbot AI -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow group">
        <div class="p-6">
            <div class="w-16 h-16 bg-white/20 backdrop-blur-xl rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">Chatbot AI</h3>
            <p class="text-white/80 mb-4">Dapatkan informasi kesehatan cepat dari asisten AI.</p>
            <a href="{{ route('pasien.chatbot.index') }}" class="inline-flex items-center text-white hover:underline group-hover:translate-x-2 transition-transform">
                Tanya AI
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Kartu Profil -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow group">
        <div class="p-6">
            <div class="w-16 h-16 bg-white/20 backdrop-blur-xl rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">Profil Saya</h3>
            <p class="text-white/80 mb-4">Kelola informasi dan riwayat kesehatan Anda.</p>
            <a href="{{ route('pasien.profil.index') }}" class="inline-flex items-center text-white hover:underline group-hover:translate-x-2 transition-transform">
                Lihat Profil
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Kartu Riwayat -->
    <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow group">
        <div class="p-6">
            <div class="w-16 h-16 bg-white/20 backdrop-blur-xl rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">Riwayat</h3>
            <p class="text-white/80 mb-4">Lihat riwayat konsultasi dan rekam medis.</p>
            <a href="{{ route('pasien.riwayat.index') }}" class="inline-flex items-center text-white hover:underline group-hover:translate-x-2 transition-transform">
                Lihat Riwayat
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- Jadwal Konsultasi Mendatang -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden mb-10">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-xl font-semibold text-gray-800">Jadwal Konsultasi Mendatang</h2>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($konsultasiMendatang as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center">
                                        <span class="text-white font-medium">{{ strtoupper(substr($item->mahasiswa->name ?? 'M', 0, 1)) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->mahasiswa->name ?? 'Mahasiswa' }}</div>
                                    <div class="text-sm text-gray-500">Dokter Umum</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->tanggal->isoFormat('D MMMM Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ substr($item->jam_mulai, 0, 5) }} - {{ substr($item->jam_selesai, 0, 5) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($item->status === 'Terkonfirmasi')
                                    bg-green-100 text-green-800
                                @elseif($item->status === 'Menunggu')
                                    bg-yellow-100 text-yellow-800
                                @endif
                            ">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('pasien.konsultasi.index') }}" class="text-blue-600 hover:text-blue-900 flex items-center">
                                Detail
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            <div class="py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada jadwal konsultasi</h3>
                                <p class="mt-1 text-sm text-gray-500">Anda belum memiliki jadwal konsultasi mendatang.</p>
                                <div class="mt-6">
                                    <a href="{{ route('pasien.konsultasi.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Buat Konsultasi Baru
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tips Kesehatan -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-xl font-semibold text-gray-800">Tips Kesehatan Terkini</h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="group bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                <div class="h-48 bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-16 h-16 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <div class="p-6">
                    <h3 class="font-semibold text-lg mb-2 group-hover:text-blue-600 transition-colors">Jaga Kesehatan Jantung</h3>
                    <p class="text-gray-600 text-sm">Konsumsi makanan sehat, olahraga teratur, dan hindari stres berlebihan untuk menjaga kesehatan jantung Anda.</p>
                    <a href="#" class="mt-4 inline-flex items-center text-blue-600 hover:text-blue-700 text-sm font-medium">
                        Baca selengkapnya
                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="group bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                <div class="h-48 bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-16 h-16 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"></path>
                    </svg>
                </div>
                <div class="p-6">
                    <h3 class="font-semibold text-lg mb-2 group-hover:text-green-600 transition-colors">Nutrisi Seimbang</h3>
                    <p class="text-gray-600 text-sm">Pastikan asupan nutrisi seimbang dengan mengonsumsi buah, sayur, protein, dan karbohidrat dalam jumlah yang tepat.</p>
                    <a href="#" class="mt-4 inline-flex items-center text-green-600 hover:text-green-700 text-sm font-medium">
                        Baca selengkapnya
                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="group bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                <div class="h-48 bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-16 h-16 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                </div>
                <div class="p-6">
                    <h3 class="font-semibold text-lg mb-2 group-hover:text-purple-600 transition-colors">Tidur Berkualitas</h3>
                    <p class="text-gray-600 text-sm">Tidur 7-8 jam sehari dengan kualitas baik dapat meningkatkan kesehatan fisik dan mental Anda.</p>
                    <a href="#" class="mt-4 inline-flex items-center text-purple-600 hover:text-purple-700 text-sm font-medium">
                        Baca selengkapnya
                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        @endif

        // Hitung interaksi chatbot dari localStorage
        function hitungInteraksiChatbot() {
            try {
                // Ambil data chat histories dari localStorage
                const savedHistories = localStorage.getItem('healsai_chat_histories');
                let totalInteraksi = 0;
                let interaksiMingguIni = 0;
                let interaksiMingguLalu = 0;
                
                const today = new Date();
                const oneWeekAgo = new Date(today);
                oneWeekAgo.setDate(today.getDate() - 7);
                
                const twoWeeksAgo = new Date(today);
                twoWeeksAgo.setDate(today.getDate() - 14);
                
                if (savedHistories) {
                    const chatHistories = JSON.parse(savedHistories);
                    
                    // Hitung jumlah pesan user di semua riwayat chat
                    chatHistories.forEach(chat => {
                        if (chat.messages && Array.isArray(chat.messages)) {
                            // Hitung hanya pesan dari user (role: 'user')
                            const userMessages = chat.messages.filter(msg => msg.role === 'user');
                            totalInteraksi += userMessages.length;
                            
                            // Hitung interaksi untuk minggu ini dan minggu lalu
                            userMessages.forEach(msg => {
                                // Pesan mungkin tidak memiliki timestamp, jadi gunakan timestamp chat
                                const msgTimestamp = chat.timestamp;
                                if (msgTimestamp) {
                                    const msgDate = new Date(msgTimestamp);
                                    
                                    // Pesan dalam minggu ini
                                    if (msgDate >= oneWeekAgo && msgDate <= today) {
                                        interaksiMingguIni++;
                                    } 
                                    // Pesan dalam minggu sebelumnya
                                    else if (msgDate >= twoWeeksAgo && msgDate < oneWeekAgo) {
                                        interaksiMingguLalu++;
                                    }
                                }
                            });
                        }
                    });
                }
                
                // Jika tidak ada interaksi yang tercatat, gunakan nilai default dari server
                if (totalInteraksi === 0) {
                    totalInteraksi = {{ $interaksiChatbot }};
                }
                
                // Perbarui tampilan di dashboard
                const interaksiChatbotElement = document.getElementById('interaksiChatbot');
                if (interaksiChatbotElement) {
                    interaksiChatbotElement.textContent = totalInteraksi;
                }
                
                // Hitung persentase perubahan
                let persenPerubahan = 0;
                if (interaksiMingguLalu > 0) {
                    persenPerubahan = Math.round(((interaksiMingguIni - interaksiMingguLalu) / interaksiMingguLalu) * 100);
                } else if (interaksiMingguIni > 0) {
                    persenPerubahan = 100; // Jika minggu lalu 0 dan minggu ini ada, berarti naik 100%
                }
                
                // Perbarui tampilan persentase perubahan
                const persenChatbotContainer = document.getElementById('persenChatbotContainer');
                if (persenChatbotContainer) {
                    let html = '';
                    
                    if (persenPerubahan > 0) {
                        html = `
                            <span class="text-purple-500 text-sm flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                </svg>
                                ${persenPerubahan}%
                            </span>
                            <span class="text-gray-400 text-sm ml-2">vs minggu lalu</span>
                        `;
                    } else if (persenPerubahan < 0) {
                        html = `
                            <span class="text-red-500 text-sm flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                </svg>
                                ${Math.abs(persenPerubahan)}%
                            </span>
                            <span class="text-gray-400 text-sm ml-2">vs minggu lalu</span>
                        `;
                    } else {
                        html = `<span class="text-gray-400 text-sm">Belum ada perubahan</span>`;
                    }
                    
                    persenChatbotContainer.innerHTML = html;
                }
            } catch (e) {
                console.error('Gagal menghitung interaksi chatbot:', e);
            }
        }
        
        // Jalankan fungsi untuk menghitung interaksi
        hitungInteraksiChatbot();
    });
</script>
@endpush 