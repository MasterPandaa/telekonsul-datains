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
            <button class="tab-button px-6 py-4 text-sm font-medium text-blue-600 border-b-2 border-blue-600" data-tab="all">
                Semua Permintaan ({{ $total }})
            </button>
            <button class="tab-button px-6 py-4 text-sm font-medium text-gray-600 hover:text-gray-800" data-tab="active">
                Aktif ({{ $totalAktif }})
            </button>
            <button class="tab-button px-6 py-4 text-sm font-medium text-gray-600 hover:text-gray-800" data-tab="completed">
                Selesai ({{ $totalSelesai }})
            </button>
        </nav>
    </div>

    <!-- Permintaan Konsultasi List -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Pasien
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tanggal & Waktu
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Keluhan
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Rating
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <!-- Daftar Konsultasi Aktif -->
                @foreach($konsultasiAktif as $item)
                <tr class="konsultasi-item" data-status="{{ $item['status'] }}">
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
                            @elseif($item['status'] === 'Ditolak')
                                bg-red-100 text-red-800
                            @elseif($item['status'] === 'Dibatalkan')
                                bg-gray-100 text-gray-800
                            @elseif($item['status'] === 'Selesai')
                                bg-blue-100 text-blue-800
                            @elseif($item['status'] === 'Terlambat')
                                bg-orange-100 text-orange-800
                            @endif
                        ">
                            {{ $item['status'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if(isset($item['rating']) && $item['rating'])
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $item['rating'] ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                                <span class="ml-1 text-sm text-gray-600">({{ $item['rating'] }}/5)</span>
                            </div>
                            @if(isset($item['komentar_rating']) && $item['komentar_rating'])
                                <button type="button" onclick="lihatDetailRating({{ $item['id'] }}, {{ $item['rating'] }}, '{{ addslashes($item['komentar_rating']) }}')" class="text-blue-600 hover:text-blue-900 text-sm mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Lihat Komentar
                                </button>
                            @endif
                        @else
                            <span class="text-gray-500">-</span>
                        @endif
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
                        @elseif($item['status'] == 'Ditolak')
                            <button type="button" onclick="lihatAlasanTolak({{ $item['id'] }}, '{{ $item['alasan_tolak'] ?? 'Tidak ada alasan yang diberikan' }}')" class="text-blue-600 hover:text-blue-900">Lihat Alasan</button>
                        @elseif($item['status'] == 'Dibatalkan')
                            <button type="button" onclick="lihatAlasanBatal({{ $item['id'] }}, '{{ $item['alasan_batal'] ?? 'Tidak ada alasan yang diberikan' }}')" class="text-blue-600 hover:text-blue-900">Lihat Alasan</button>
                        @elseif($item['status'] == 'Terlambat')
                            <button type="button" onclick="mintaPergantianSesi({{ $item['id'] }})" class="text-orange-600 hover:text-orange-900">Minta Ganti Sesi</button>
                        @elseif($item['status'] == 'Selesai')
                            <a href="{{ route('chat.create', $item['id']) }}" class="text-blue-600 hover:text-blue-900">Lihat Chat</a>
                        @endif
                    </td>
                </tr>
                @endforeach

                <!-- Daftar Konsultasi Selesai -->
                @foreach($konsultasiSelesai as $item)
                <tr class="konsultasi-item" data-status="{{ $item['status'] }}">
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
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($item['status'] === 'Selesai')
                                bg-blue-100 text-blue-800
                            @elseif($item['status'] === 'Ditolak')
                                bg-red-100 text-red-800
                            @elseif($item['status'] === 'Dibatalkan')
                                bg-gray-100 text-gray-800
                            @elseif($item['status'] === 'Terlambat')
                                bg-orange-100 text-orange-800
                            @else
                                bg-gray-100 text-gray-800
                            @endif
                        ">
                            {{ $item['status'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if(isset($item['rating']) && $item['rating'])
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $item['rating'] ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                                <span class="ml-1 text-sm text-gray-600">({{ $item['rating'] }}/5)</span>
                            </div>
                            @if(isset($item['komentar_rating']) && $item['komentar_rating'])
                                <button type="button" onclick="lihatDetailRating({{ $item['id'] }}, {{ $item['rating'] }}, '{{ addslashes($item['komentar_rating']) }}')" class="text-blue-600 hover:text-blue-900 text-sm mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Lihat Komentar
                                </button>
                            @endif
                        @else
                            <span class="text-gray-500">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($item['status'] === 'Selesai')
                            <a href="{{ route('chat.create', $item['id']) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                        @elseif($item['status'] === 'Ditolak')
                            <button type="button" onclick="lihatAlasanTolak({{ $item['id'] }}, '{{ $item['alasan_tolak'] ?? 'Tidak ada alasan yang diberikan' }}')" class="text-blue-600 hover:text-blue-900">Detail</button>
                        @elseif($item['status'] === 'Dibatalkan')
                            <button type="button" onclick="lihatAlasanBatal({{ $item['id'] }}, '{{ $item['alasan_batal'] ?? 'Tidak ada alasan yang diberikan' }}')" class="text-blue-600 hover:text-blue-900">Detail</button>
                        @elseif($item['status'] === 'Terlambat')
                            <button type="button" onclick="mintaPergantianSesi({{ $item['id'] }})" class="text-blue-600 hover:text-blue-900">Detail</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        <div class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
                <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Sebelumnya
                </a>
                <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Selanjutnya
                </a>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium">{{ $total }}</span> dari <span class="font-medium">{{ $total }}</span> hasil
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Sebelumnya</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" aria-current="page" class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                            1
                        </a>
                        <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Selanjutnya</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab navigation
        const tabButtons = document.querySelectorAll('.tab-button');
        const konsultasiItems = document.querySelectorAll('.konsultasi-item');
        
        // Update countdown timers
        const countdownTimers = document.querySelectorAll('.countdown-timer');
        
        function updateTimers() {
            countdownTimers.forEach(function(timer) {
                const targetTime = parseInt(timer.dataset.target);
                const now = new Date().getTime();
                const difference = targetTime - now;
                
                if (difference <= 0) {
                    timer.innerHTML = `
                        <svg class="w-5 h-5 mr-1.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-green-600">Konsultasi sudah dapat dimulai</span>
                    `;
                    timer.classList.remove('bg-gray-50', 'border-gray-200');
                    timer.classList.add('bg-green-50', 'border-green-200');
                    
                    // Reload halaman untuk memperbarui status bisa_dimulai
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                    return;
                }
                
                const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((difference % (1000 * 60)) / 1000);
                
                const formattedTime = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                const timeRemaining = timer.querySelector('.time-remaining');
                if (timeRemaining) {
                    timeRemaining.textContent = formattedTime;
                }
                
                // Ubah warna timer saat mendekati waktu konsultasi
                if (difference < 300000) { // kurang dari 5 menit
                    timer.classList.remove('bg-gray-50', 'border-gray-200');
                    timer.classList.add('bg-orange-50', 'border-orange-200');
                    timeRemaining.classList.add('text-orange-600');
                } else if (difference < 900000) { // kurang dari 15 menit
                    timer.classList.remove('bg-gray-50', 'border-gray-200');
                    timer.classList.add('bg-blue-50', 'border-blue-200');
                    timeRemaining.classList.add('text-blue-600');
                }
            });
        }
        
        // Update timers every second
        updateTimers();
        setInterval(updateTimers, 1000);
        
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                tabButtons.forEach(btn => {
                    btn.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                    btn.classList.add('text-gray-600');
                });
                
                // Add active class to clicked button
                this.classList.remove('text-gray-600');
                this.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
                
                const tabName = this.getAttribute('data-tab');
                
                // Show/hide konsultasi items based on tab
                if (tabName === 'all') {
                    konsultasiItems.forEach(item => {
                        item.style.display = '';
                    });
                } else if (tabName === 'active') {
                    konsultasiItems.forEach(item => {
                        const status = item.getAttribute('data-status');
                        if (status === 'Menunggu' || status === 'Terkonfirmasi') {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                } else if (tabName === 'completed') {
                    konsultasiItems.forEach(item => {
                        const status = item.getAttribute('data-status');
                        if (status === 'Selesai' || status === 'Ditolak' || status === 'Dibatalkan' || status === 'Terlambat') {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                }
            });
        });
    });

    // Fungsi untuk konfirmasi terima konsultasi
    function konfirmasiTerima(id) {
        Swal.fire({
            title: '<div class="flex items-center"><svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>Konfirmasi Permintaan</div>',
            html: `
                <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500 mb-4">
                    <div class="text-left text-green-700">
                        <p class="font-medium">Anda akan menerima permintaan konsultasi ini.</p>
                        <p class="text-sm mt-2">Setelah menerima, Anda akan terjadwal untuk melakukan konsultasi dengan pasien sesuai waktu yang ditentukan.</p>
                    </div>
                </div>
                <div class="text-xs text-gray-500 mt-2">Pastikan Anda siap untuk melakukan konsultasi pada jadwal yang ditentukan.</div>
            `,
            icon: false,
            showCancelButton: true,
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#6B7280',
            confirmButtonText: '<span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Ya, Terima</span>',
            cancelButtonText: '<span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>Batal</span>',
            customClass: {
                popup: 'rounded-lg shadow-xl border border-gray-200',
                title: 'text-lg font-bold text-gray-800 border-b pb-3',
                content: 'pt-4',
                confirmButton: 'rounded-md',
                cancelButton: 'rounded-md'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form untuk konfirmasi
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ url("mahasiswa/konsultasi") }}/' + id + '/konfirmasi';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Fungsi untuk konfirmasi tolak konsultasi
    function konfirmasiTolak(id) {
        Swal.fire({
            title: '<div class="flex items-center"><svg class="w-6 h-6 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>Tolak Permintaan</div>',
            html: `
                <div class="bg-red-50 p-4 rounded-lg border-l-4 border-red-500 mb-4">
                    <div class="text-left text-red-700">
                        <p class="font-medium">Anda akan menolak permintaan konsultasi ini.</p>
                        <p class="text-sm mt-2">Harap berikan alasan penolakan yang jelas untuk pasien.</p>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-left text-sm font-medium text-gray-700 mb-1">Alasan Penolakan:</label>
                    <textarea id="alasan-tolak" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500" rows="3" placeholder="Masukkan alasan penolakan..."></textarea>
                </div>
            `,
            icon: false,
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: '<span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Tolak Permintaan</span>',
            cancelButtonText: '<span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>Batal</span>',
            customClass: {
                popup: 'rounded-lg shadow-xl border border-gray-200',
                title: 'text-lg font-bold text-gray-800 border-b pb-3',
                content: 'pt-4',
                confirmButton: 'rounded-md',
                cancelButton: 'rounded-md'
            },
            preConfirm: () => {
                const alasan = document.getElementById('alasan-tolak').value;
                if (!alasan) {
                    Swal.showValidationMessage('<div class="flex items-center text-red-600"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>Anda harus memberikan alasan penolakan!</div>');
                    return false;
                }
                return alasan;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form untuk tolak dengan alasan
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ url("mahasiswa/konsultasi") }}/' + id + '/tolak';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const alasanInput = document.createElement('input');
                alasanInput.type = 'hidden';
                alasanInput.name = 'alasan_tolak';
                alasanInput.value = result.value;
                
                form.appendChild(csrfToken);
                form.appendChild(alasanInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Fungsi untuk melihat alasan penolakan
    function lihatAlasanTolak(id, alasan) {
        Swal.fire({
            title: '<div class="flex items-center"><svg class="w-6 h-6 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>Alasan Penolakan</div>',
            html: `
                <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-red-500 mb-4">
                    <div class="text-left text-gray-700">
                        <p class="font-semibold mb-2">Detail Penolakan:</p>
                        <p class="italic">"${alasan}"</p>
                    </div>
                </div>
                <div class="text-xs text-gray-500 mt-2">Permintaan konsultasi ini telah ditolak oleh Anda</div>
            `,
            icon: false,
            showConfirmButton: true,
            confirmButtonColor: '#3B82F6',
            confirmButtonText: '<span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Saya Mengerti</span>',
            customClass: {
                popup: 'rounded-lg shadow-xl border border-gray-200',
                title: 'text-lg font-bold text-gray-800 border-b pb-3',
                content: 'pt-4'
            }
        });
    }

    // Fungsi untuk melihat alasan pembatalan
    function lihatAlasanBatal(id, alasan) {
        Swal.fire({
            title: '<div class="flex items-center"><svg class="w-6 h-6 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>Alasan Pembatalan</div>',
            html: `
                <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-gray-500 mb-4">
                    <div class="text-left text-gray-700">
                        <p class="font-semibold mb-2">Detail Pembatalan:</p>
                        <p class="italic">"${alasan}"</p>
                    </div>
                </div>
                <div class="text-xs text-gray-500 mt-2">Permintaan konsultasi ini telah dibatalkan oleh pasien</div>
            `,
            icon: false,
            showConfirmButton: true,
            confirmButtonColor: '#3B82F6',
            confirmButtonText: '<span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Saya Mengerti</span>',
            customClass: {
                popup: 'rounded-lg shadow-xl border border-gray-200',
                title: 'text-lg font-bold text-gray-800 border-b pb-3',
                content: 'pt-4'
            }
        });
    }

    // Fungsi untuk melihat detail rating
    function lihatDetailRating(id, rating, komentar) {
        const stars = [];
        for (let i = 1; i <= 5; i++) {
            const starClass = i <= rating ? 'text-yellow-400' : 'text-gray-300';
            stars.push(`<svg class="w-6 h-6 ${starClass}" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
            </svg>`);
        }
        
        Swal.fire({
            title: '<div class="flex items-center"><svg class="w-6 h-6 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>Detail Rating</div>',
            html: `
                <div class="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-500 mb-4">
                    <div class="text-left text-gray-700">
                        <p class="font-semibold mb-2">Rating dari Pasien:</p>
                        <div class="flex items-center mb-3">
                            ${stars.join('')}
                            <span class="ml-2 font-medium">${rating}/5</span>
                        </div>
                        ${komentar ? `
                        <p class="font-semibold mb-1">Komentar:</p>
                        <p class="italic">"${komentar}"</p>
                        ` : '<p class="text-gray-500">Tidak ada komentar</p>'}
                    </div>
                </div>
            `,
            icon: false,
            showConfirmButton: true,
            confirmButtonColor: '#3B82F6',
            confirmButtonText: '<span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Tutup</span>',
            customClass: {
                popup: 'rounded-lg shadow-xl border border-gray-200',
                title: 'text-lg font-bold text-gray-800 border-b pb-3',
                content: 'pt-4'
            }
        });
    }

    // Fungsi untuk minta pergantian sesi
    function mintaPergantianSesi(id) {
        Swal.fire({
            title: '<div class="flex items-center"><svg class="w-6 h-6 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>Minta Pergantian Sesi</div>',
            html: `
                <div class="bg-orange-50 p-4 rounded-lg border-l-4 border-orange-500 mb-4">
                    <p class="text-left text-orange-700 font-medium mb-2">Konsultasi ini telah terlambat. Anda dapat meminta pergantian jadwal.</p>
                </div>
                <div class="space-y-4">
                    <div class="mb-4">
                        <label class="block text-left text-sm font-medium text-gray-700 mb-1">Alasan Keterlambatan:</label>
                        <textarea id="alasan-terlambat" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" rows="3" placeholder="Masukkan alasan keterlambatan..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-left text-sm font-medium text-gray-700 mb-1">Pilih Tanggal Baru:</label>
                        <input type="date" id="tanggal-baru" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" min="${new Date().toISOString().split('T')[0]}">
                    </div>
                    <div>
                        <label class="block text-left text-sm font-medium text-gray-700 mb-1">Pilih Sesi Jam:</label>
                        <select id="sesi-jam" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih sesi jam</option>
                            <option value="08:00:00-09:00:00">08:00 - 09:00</option>
                            <option value="09:00:00-10:00:00">09:00 - 10:00</option>
                            <option value="10:00:00-11:00:00">10:00 - 11:00</option>
                            <option value="13:00:00-14:00:00">13:00 - 14:00</option>
                            <option value="14:00:00-15:00:00">14:00 - 15:00</option>
                            <option value="15:00:00-16:00:00">15:00 - 16:00</option>
                        </select>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonColor: '#F97316',
            cancelButtonColor: '#6B7280',
            confirmButtonText: '<span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Kirim Permintaan</span>',
            cancelButtonText: '<span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>Batal</span>',
            customClass: {
                popup: 'rounded-lg shadow-xl border border-gray-200',
                title: 'text-lg font-bold text-gray-800 border-b pb-3',
                content: 'pt-4',
                confirmButton: 'rounded-md',
                cancelButton: 'rounded-md'
            },
            preConfirm: () => {
                const alasan = document.getElementById('alasan-terlambat').value;
                const tanggal = document.getElementById('tanggal-baru').value;
                const sesiJam = document.getElementById('sesi-jam').value;
                
                if (!alasan) {
                    Swal.showValidationMessage('<div class="flex items-center text-red-600"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>Alasan keterlambatan harus diisi</div>');
                    return false;
                }
                
                if (!tanggal) {
                    Swal.showValidationMessage('<div class="flex items-center text-red-600"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>Tanggal baru harus dipilih</div>');
                    return false;
                }
                
                if (!sesiJam) {
                    Swal.showValidationMessage('<div class="flex items-center text-red-600"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>Sesi jam harus dipilih</div>');
                    return false;
                }
                
                return { alasan, tanggal, sesiJam };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form untuk minta pergantian sesi
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ url("mahasiswa/konsultasi") }}/' + id + '/ganti-sesi';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const alasanInput = document.createElement('input');
                alasanInput.type = 'hidden';
                alasanInput.name = 'alasan_terlambat';
                alasanInput.value = result.value.alasan;
                
                const tanggalInput = document.createElement('input');
                tanggalInput.type = 'hidden';
                tanggalInput.name = 'tanggal_baru';
                tanggalInput.value = result.value.tanggal;
                
                const sesiJamInput = document.createElement('input');
                sesiJamInput.type = 'hidden';
                sesiJamInput.name = 'sesi_jam';
                sesiJamInput.value = result.value.sesiJam;
                
                form.appendChild(csrfToken);
                form.appendChild(alasanInput);
                form.appendChild(tanggalInput);
                form.appendChild(sesiJamInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush
@endsection 