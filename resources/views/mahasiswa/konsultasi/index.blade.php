@extends('layouts.mahasiswa')
@section('mahasiswa-content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Permintaan Konsultasi</h1>
        <p class="text-sm text-gray-600">Kelola permintaan konsultasi dengan pasien Anda</p>
    </div>
    <!-- Tombol Buat Permintaan dihapus karena permintaan dibuat oleh pasien -->
</div>

<!-- Filter and Search -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
        <h2 class="text-lg font-medium text-gray-800">Filter Permintaan</h2>
        <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-3">
            <div class="relative">
                <input type="text" placeholder="Cari pasien..." class="w-full md:w-64 px-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <div class="absolute right-3 top-2.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            <select class="px-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Status</option>
                <option value="waiting">Menunggu</option>
                <option value="confirmed">Terkonfirmasi</option>
                <option value="completed">Selesai</option>
                <option value="cancelled">Dibatalkan</option>
            </select>
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

<!-- Konsultasi Aktif (Terkonfirmasi dan Menunggu) -->
<div id="tab-active" class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Konsultasi Aktif
        </h2>
        <p class="text-sm text-gray-500">Konsultasi dengan status Terkonfirmasi dan Menunggu</p>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Pasien
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Jadwal
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Keluhan
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    $konsultasiAktifFiltered = array_filter($konsultasiAktif, function($item) {
                        return in_array($item['status'], ['Terkonfirmasi', 'Menunggu']);
                    });
                @endphp
                
                @if(count($konsultasiAktifFiltered) > 0)
                    @foreach($konsultasiAktifFiltered as $item)
                    <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ str_replace(' ', '+', $item['pasien_nama']) }}&background=4F46E5&color=fff" alt="">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $item['pasien_nama'] }}</div>
                                <div class="text-sm text-gray-500">{{ $item['pasien_gender'] }}, {{ $item['pasien_usia'] }} tahun</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $item['tanggal_tampil'] instanceof \Carbon\Carbon ? $item['tanggal_tampil']->format('d F Y') : '-' }}</div>
                        <div class="text-sm text-gray-500">{{ $item['jam_mulai'] }} - {{ $item['jam_selesai'] }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $item['keluhan'] }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($item['status'] === 'Terkonfirmasi')
                                bg-green-100 text-green-800
                            @elseif($item['status'] === 'Menunggu')
                                bg-yellow-100 text-yellow-800
                            @endif
                        ">
                            {{ $item['status'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($item['status'] == 'Terkonfirmasi')
                            @if($item['bisa_dimulai'])
                                <a href="{{ route('chat.create', $item['id']) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    Masuk Chat
                                </a>
                            @else
                                <div class="rounded inline-flex items-center justify-center">
                                    <div class="text-center">
                                        <span class="countdown-timer inline-flex items-center text-sm text-gray-600 rounded-lg px-3 py-1.5 border border-gray-200 bg-gray-50 whitespace-nowrap" data-target="{{ $item['tanggal_timestamp'] }}">
                                            <svg class="w-5 h-5 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="time-remaining font-medium">--:--:--</span>
                                </span>
                                    </div>
                                </div>
                            @endif
                        @elseif($item['status'] == 'Menunggu')
                            <div class="flex justify-center space-x-2">
                                <button type="button" onclick="konfirmasiTerima({{ $item['id'] }})" class="text-green-600 hover:text-green-900 mr-2">Terima</button>
                                <button type="button" onclick="konfirmasiTolak({{ $item['id'] }})" class="text-red-600 hover:text-red-900">Tolak</button>
                            </div>
                        @endif
                    </td>
                </tr>
                @endforeach
                @else
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada konsultasi aktif saat ini
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
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
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Pasien
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Keluhan
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    $konsultasiTidakAktif = array_filter($konsultasiSelesai, function($item) {
                        return in_array($item['status'], ['Dibatalkan', 'Terlambat']);
                    });
                @endphp
                
                @if(count($konsultasiTidakAktif) > 0)
                    @foreach($konsultasiTidakAktif as $item)
                    <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ str_replace(' ', '+', $item['pasien_nama']) }}&background=4F46E5&color=fff" alt="">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $item['pasien_nama'] }}</div>
                                <div class="text-sm text-gray-500">{{ $item['pasien_gender'] }}, {{ $item['pasien_usia'] }} tahun</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $item['keluhan'] }}</div>
                    </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($item['status'] === 'Dibatalkan')
                                bg-gray-100 text-gray-800
                            @elseif($item['status'] === 'Terlambat')
                                bg-orange-100 text-orange-800
                            @endif
                        ">
                            {{ $item['status'] }}
                        </span>
                    </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('pasien.konsultasi.create') }}" class="text-blue-600 hover:text-blue-900 border border-blue-300 rounded-md px-2 py-1 hover:bg-blue-50 transition inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Buat Ulang
                                </a>
                                <button type="button" onclick="hapusKonsultasi({{ $item['id'] }})" class="text-red-600 hover:text-red-900 border border-red-300 rounded-md px-2 py-1 hover:bg-red-50 transition inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                    </td>
                </tr>
                @endforeach
                @else
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada konsultasi tidak aktif saat ini
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@section('scripts')
<script>
    // Tab switching functionality
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                tabButtons.forEach(btn => {
                    btn.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                    btn.classList.add('text-gray-600');
                });
                
                // Add active class to clicked button
                button.classList.remove('text-gray-600');
                button.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
                
                // Hide all tab contents
                document.querySelectorAll('[id^="tab-"]').forEach(tab => {
                    tab.classList.add('hidden');
                });
                
                // Show the selected tab content
                const tabId = button.getAttribute('data-tab');
                document.getElementById(`tab-${tabId}`).classList.remove('hidden');
            });
        });
        
        // Countdown timer functionality
        const countdownTimers = document.querySelectorAll('.countdown-timer');
        
        function updateCountdown() {
            const now = new Date().getTime();
            
            countdownTimers.forEach(timer => {
                const targetTime = parseInt(timer.getAttribute('data-target'));
                if (!targetTime) return;
                
                const timeRemaining = targetTime - now;
                
                if (timeRemaining <= 0) {
                    timer.querySelector('.time-remaining').textContent = "Waktunya konsultasi!";
                    // Auto-refresh the page when time is up
                    location.reload();
                    return;
                }
                
                // Calculate hours, minutes, seconds
                const hours = Math.floor(timeRemaining / (1000 * 60 * 60));
                const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);
                
                // Display the countdown
                timer.querySelector('.time-remaining').textContent = 
                    `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            });
        }
        
        // Update countdown every second
        if (countdownTimers.length > 0) {
            updateCountdown();
            setInterval(updateCountdown, 1000);
        }
    });
    
    // Konfirmasi terima konsultasi
    function konfirmasiTerima(id) {
        if (confirm('Apakah Anda yakin ingin menerima permintaan konsultasi ini?')) {
            window.location.href = `/mahasiswa/konsultasi/${id}/konfirmasi`;
            }
    }

    // Konfirmasi tolak konsultasi
    function konfirmasiTolak(id) {
        const alasan = prompt('Masukkan alasan penolakan konsultasi:', '');
        if (alasan !== null) {
            // Create form element
                const form = document.createElement('form');
                form.method = 'POST';
            form.action = `/mahasiswa/konsultasi/${id}/tolak`;
                
            // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Add alasan field
            const alasanField = document.createElement('input');
            alasanField.type = 'hidden';
            alasanField.name = 'alasan_tolak';
            alasanField.value = alasan;
            form.appendChild(alasanField);
            
            // Add form to body and submit
                document.body.appendChild(form);
                form.submit();
            }
    }

    // Hapus konsultasi
    function hapusKonsultasi(id) {
        if (confirm('Apakah Anda yakin ingin menghapus konsultasi ini dari riwayat?')) {
            // Create form element
                const form = document.createElement('form');
                form.method = 'POST';
            form.action = `/mahasiswa/konsultasi/${id}/hapus`;
                
            // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Add form to body and submit
                document.body.appendChild(form);
                form.submit();
            }
    }
</script>
@endsection 