@extends('layouts.pasien')

@section('pasien-content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Telekonsultasi</h1>
            <p class="text-sm text-gray-600">Konsultasikan keluhan Anda dengan mahasiswa kedokteran</p>
        </div>
        <a href="{{ route('pasien.konsultasi.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md text-sm transition flex items-center justify-center w-full md:w-auto">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Buat Konsultasi Baru
        </a>
    </div>
</div>

<!-- Notifikasi akan ditampilkan melalui JavaScript -->

<!-- Dashboard Ringkasan -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-md p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold">Konsultasi Aktif</h3>
                <p class="text-2xl font-bold mt-2">{{ $konsultasiAktif->where('status', 'Menunggu')->count() + $konsultasiAktif->where('status', 'Terkonfirmasi')->count() }}</p>
            </div>
            <div class="bg-white bg-opacity-30 p-3 rounded-full">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4 text-sm text-blue-100">
            Konsultasi yang sedang menunggu atau terkonfirmasi
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-md p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold">Konsultasi Selesai</h3>
                <p class="text-2xl font-bold mt-2">{{ $riwayatKonsultasi->where('status', 'Selesai')->count() }}</p>
            </div>
            <div class="bg-white bg-opacity-30 p-3 rounded-full">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4 text-sm text-green-100">
            Konsultasi yang telah selesai dilakukan
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-md p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold">Total Konsultasi</h3>
                <p class="text-2xl font-bold mt-2">{{ $konsultasiAktif->count() + $riwayatKonsultasi->count() }}</p>
            </div>
            <div class="bg-white bg-opacity-30 p-3 rounded-full">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4 text-sm text-purple-100">
            Total keseluruhan konsultasi Anda
        </div>
    </div>
</div>

<!-- Tab Navigation -->
<div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
    <div class="border-b">
        <nav class="flex">
            <button class="tab-button px-6 py-4 text-sm font-medium text-blue-600 border-b-2 border-blue-600" data-tab="active">
                Konsultasi Aktif
            </button>
            <button class="tab-button px-6 py-4 text-sm font-medium text-gray-600 hover:text-gray-800" data-tab="inactive">
                Konsultasi Tidak Aktif
            </button>
        </nav>
    </div>
</div>

<!-- Konsultasi Aktif (Menunggu dan Terkonfirmasi) -->
<div id="tab-active" class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Konsultasi Aktif
        </h2>
        <p class="text-sm text-gray-500">Konsultasi dengan status Menunggu dan Terkonfirmasi</p>
    </div>
    
    @php
        $konsultasiAktifFiltered = $konsultasiAktif->filter(function($item) {
            return in_array($item->status, ['Menunggu', 'Terkonfirmasi']);
        });
    @endphp
    
    @if($konsultasiAktifFiltered->isEmpty())
    <div class="p-6 text-center">
        <div class="py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum Ada Konsultasi Aktif</h3>
            <p class="mt-1 text-sm text-gray-500">Anda belum memiliki jadwal konsultasi yang aktif.</p>
            <div class="mt-6">
                <a href="{{ route('pasien.konsultasi.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Buat Konsultasi Baru
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keluhan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($konsultasiAktifFiltered as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold">
                                    {{ strtoupper(substr($item->mahasiswa->name ?? 'M', 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->mahasiswa->name ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $item->tanggal->isoFormat('D MMMM Y') }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ substr($item->jam_mulai, 0, 5) }} - {{ substr($item->jam_selesai, 0, 5) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 line-clamp-2">{{ $item->keluhan }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
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
                            <div class="flex flex-col sm:flex-row gap-2">
                                @if($item->status === 'Terkonfirmasi')
                                    @php
                                        $now = \Carbon\Carbon::now();
                                        $jadwalMulai = \Carbon\Carbon::parse($item->tanggal->format('Y-m-d') . ' ' . $item->jam_mulai);
                                        $jadwalSelesai = \Carbon\Carbon::parse($item->tanggal->format('Y-m-d') . ' ' . $item->jam_selesai);
                                        $isWaktuKonsultasi = $now->between($jadwalMulai, $jadwalSelesai);
                                        $selisihWaktu = $now->diffForHumans($jadwalMulai);
                                    @endphp
                                    
                                    @if($isWaktuKonsultasi)
                                        <a href="{{ route('chat.create', $item->id) }}" class="bg-green-600 hover:bg-green-700 text-white rounded-md px-2 py-1 text-center transition">
                                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            Masuk Chat
                                        </a>
                                    @else
                                        <div class="text-gray-600 rounded-md px-2 py-1 border border-gray-300 bg-gray-50 flex items-center">
                                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            @if($jadwalMulai->isFuture())
                                                <span class="countdown-timer" data-target="{{ $jadwalMulai->timestamp * 1000 }}">Tersisa <span class="time-remaining">--:--:--</span> untuk konsultasi</span>
                                            @else
                                                Konsultasi sudah berakhir
                                            @endif
                                        </div>
                                    @endif
                                @elseif($item->status === 'Menunggu')
                                    <form action="{{ route('pasien.konsultasi.batalkan', $item->id) }}" method="POST" class="inline" id="form-batalkan-{{ $item->id }}">
                                        @csrf
                                        <button type="button" class="text-red-600 hover:text-red-900 border border-red-300 rounded-md px-2 py-1 text-center hover:bg-red-50 transition" onclick="konfirmasiBatalkan({{ $item->id }}, '{{ $item->tanggal->isoFormat('D MMMM Y') }}', '{{ substr($item->jam_mulai, 0, 5) }} - {{ substr($item->jam_selesai, 0, 5) }}')">
                                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Batalkan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<!-- Konsultasi Tidak Aktif (Dibatalkan dan Terlambat) -->
<div id="tab-inactive" class="bg-white rounded-lg shadow-md overflow-hidden mb-6 hidden">
    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Konsultasi Tidak Aktif
        </h2>
        <p class="text-sm text-gray-500">Konsultasi dengan status Dibatalkan dan Terlambat</p>
    </div>
    
    @php
        $konsultasiTidakAktif = $riwayatKonsultasi->filter(function($item) {
            return in_array($item->status, ['Dibatalkan', 'Terlambat']);
        });
    @endphp
    
    @if($konsultasiTidakAktif->isEmpty())
    <div class="p-6 text-center">
        <div class="py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum Ada Konsultasi Tidak Aktif</h3>
            <p class="mt-1 text-sm text-gray-500">Anda belum memiliki konsultasi yang dibatalkan atau terlambat.</p>
        </div>
    </div>
    @else
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keluhan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($konsultasiTidakAktif as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold">
                                    {{ strtoupper(substr($item->mahasiswa->name ?? 'M', 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->mahasiswa->name ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 line-clamp-2">{{ $item->keluhan }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($item->status === 'Dibatalkan')
                                    bg-gray-100 text-gray-800
                                @elseif($item->status === 'Terlambat')
                                    bg-orange-100 text-orange-800
                                @endif
                            ">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex flex-col sm:flex-row gap-2">
                                <a href="{{ route('pasien.konsultasi.create') }}" class="text-blue-600 hover:text-blue-900 border border-blue-300 rounded-md px-2 py-1 hover:bg-blue-50 transition inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Buat Ulang
                                </a>
                                <button type="button" onclick="hapusKonsultasi({{ $item->id }})" class="text-red-600 hover:text-red-900 border border-red-300 rounded-md px-2 py-1 hover:bg-red-50 transition inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            // Hide all tabs
            document.querySelectorAll('[id^="tab-"]').forEach(tab => {
                tab.classList.add('hidden');
            });
            
            // Show selected tab
            document.getElementById('tab-' + tabId).classList.remove('hidden');
            
            // Update active tab button
            tabButtons.forEach(btn => {
                btn.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                btn.classList.add('text-gray-600');
            });
            
            this.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
            this.classList.remove('text-gray-600');
        });
    });
    
    // Countdown timer functionality
    function updateCountdowns() {
        const countdownElements = document.querySelectorAll('.countdown-timer');
        const now = new Date().getTime();
        
        countdownElements.forEach(element => {
            const targetTime = parseInt(element.getAttribute('data-target'));
            const timeRemainingElement = element.querySelector('.time-remaining');
            
            if (!targetTime) return;
            
            const distance = targetTime - now;
            
            if (distance <= 0) {
                timeRemainingElement.textContent = "Waktunya konsultasi!";
                element.classList.add('bg-green-50', 'text-green-700', 'border-green-200');
                element.classList.remove('bg-gray-50', 'text-gray-600', 'border-gray-200');
                    return;
                }
                
            const hours = Math.floor(distance / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            timeRemainingElement.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        });
    }
    
    // Update countdowns immediately and then every second
    updateCountdowns();
    setInterval(updateCountdowns, 1000);
    
    // Tampilkan notifikasi jika ada flash message
    @if(session('success'))
        showNotification("{{ session('success') }}", 'success');
    @endif
    
    @if(session('error'))
        showNotification("{{ session('error') }}", 'error');
    @endif
    
    @if(session('warning'))
        showNotification("{{ session('warning') }}", 'warning');
    @endif
    
    @if(session('info'))
        showNotification("{{ session('info') }}", 'info');
    @endif
});

// Fungsi untuk menampilkan notifikasi menarik
function showNotification(message, type = 'success') {
    // Hapus notifikasi lama jika ada
    const oldNotification = document.getElementById('healsai-notification');
    if (oldNotification) {
        oldNotification.remove();
    }
    
    // Tentukan warna berdasarkan tipe
    let bgColor, iconSvg;
    if (type === 'success') {
        bgColor = 'from-emerald-500 to-green-500';
        iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
    } else if (type === 'error') {
        bgColor = 'from-red-500 to-rose-500';
        iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
    } else if (type === 'warning') {
        bgColor = 'from-amber-500 to-yellow-500';
        iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
    } else if (type === 'info') {
        bgColor = 'from-blue-500 to-indigo-500';
        iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
    }
    
    // Buat elemen notifikasi
    const notification = document.createElement('div');
    notification.id = 'healsai-notification';
    notification.className = 'fixed top-4 right-4 z-50 flex items-center p-4 mb-4 rounded-xl shadow-lg text-white bg-gradient-to-r ' + bgColor + ' transition-all duration-500 transform translate-x-full opacity-0';
    notification.innerHTML = `
        <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-lg bg-white/25 mr-3">
            ${iconSvg}
        </div>
        <div class="text-sm font-medium">${message}</div>
        <button type="button" class="ml-4 -mx-1.5 -my-1.5 rounded-lg p-1.5 inline-flex h-8 w-8 bg-white/10 hover:bg-white/20" onclick="this.parentElement.remove()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    `;
    
    // Tambahkan ke DOM
    document.body.appendChild(notification);
    
    // Tampilkan dengan animasi
    setTimeout(() => {
        notification.classList.remove('translate-x-full', 'opacity-0');
    }, 10);
    
    // Sembunyikan setelah beberapa detik
    setTimeout(() => {
        notification.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            notification.remove();
        }, 500);
    }, 5000);
}

    function konfirmasiBatalkan(id, tanggal, jam) {
        Swal.fire({
        title: 'Konfirmasi Pembatalan',
        html: `Apakah Anda yakin ingin membatalkan konsultasi pada<br><strong>${tanggal}</strong> pukul <strong>${jam}</strong>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Tidak',
        reverseButtons: true,
        customClass: {
            container: 'swal-custom',
            popup: 'rounded-lg',
            title: 'text-lg font-semibold text-gray-800',
            htmlContainer: 'text-base text-gray-600',
            confirmButton: 'rounded-md text-sm',
            cancelButton: 'rounded-md text-sm'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Alasan Pembatalan',
                input: 'textarea',
                inputLabel: 'Mohon berikan alasan pembatalan:',
                inputPlaceholder: 'Tuliskan alasan pembatalan di sini...',
                inputAttributes: {
                    'aria-label': 'Tuliskan alasan pembatalan di sini'
                },
            showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Kirim',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            customClass: {
                    input: 'text-sm',
                    inputLabel: 'text-gray-700'
                },
                preConfirm: (alasan) => {
                if (!alasan) {
                        Swal.showValidationMessage('Alasan pembatalan harus diisi')
                    }
                    return alasan
            }
        }).then((result) => {
            if (result.isConfirmed) {
                    const form = document.getElementById('form-batalkan-' + id);
                const alasanInput = document.createElement('input');
                alasanInput.type = 'hidden';
                alasanInput.name = 'alasan_batal';
                alasanInput.value = result.value;
                form.appendChild(alasanInput);
                form.submit();
            }
        });
            }
        });
    }

function hapusKonsultasi(id) {
        Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus konsultasi ini? Tindakan ini tidak dapat dibatalkan.',
        icon: 'warning',
            showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true,
            customClass: {
            popup: 'rounded-lg',
            title: 'text-lg font-semibold text-gray-800',
            htmlContainer: 'text-base text-gray-600',
            confirmButton: 'rounded-md text-sm',
            cancelButton: 'rounded-md text-sm'
            }
        }).then((result) => {
            if (result.isConfirmed) {
            // Implementasi penghapusan konsultasi
                Swal.fire({
                title: 'Fitur Dalam Pengembangan',
                text: 'Fitur penghapusan konsultasi belum diimplementasikan.',
                icon: 'info',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
            }
        });
    }
    
// Tambahkan style kustom untuk SweetAlert
document.head.insertAdjacentHTML('beforeend', `
    <style>
        .swal2-popup {
            font-family: 'Inter', sans-serif;
        }
        .swal2-title {
            font-weight: 600 !important;
        }
        .swal2-html-container {
            font-size: 0.95rem !important;
        }
        .swal2-confirm, .swal2-cancel {
            font-weight: 500 !important;
            padding: 0.5rem 1.5rem !important;
        }
        .swal2-textarea {
            border-radius: 0.375rem !important;
            border-color: #e2e8f0 !important;
            padding: 0.5rem !important;
            font-size: 0.875rem !important;
        }
        .swal2-textarea:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25) !important;
        }
    </style>
`);
</script>

@endsection 