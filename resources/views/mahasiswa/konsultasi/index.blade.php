@extends('layouts.mahasiswa')

@section('mahasiswa-content')
@php
    $konsultasiAktifFiltered = array_filter($konsultasiAktif ?? [], function($item) {
        return in_array($item['status'], ['Terkonfirmasi', 'Menunggu']);
    });
    $konsultasiTidakAktif = array_filter($konsultasiSelesai ?? [], function($item) {
        return in_array($item['status'], ['Dibatalkan', 'Terlambat']);
    });
    
    $uniquePasienAktif = collect($konsultasiAktifFiltered)->unique('pasien_nama')->values();
    $uniquePasienTidakAktif = collect($konsultasiTidakAktif)->unique('pasien_nama')->values();
@endphp

<style>
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideUp {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-10px); }
    }
    .custom-select {
        position: relative;
        display: inline-block;
    }
    .custom-select select {
        display: none;
    }
    .select-selected {
        position: relative;
        cursor: pointer;
    }
    .select-items {
        animation: slideDown 0.2s ease-out;
    }
    .select-items div:hover {
        @apply bg-indigo-50;
    }
    .select-hide {
        animation: slideUp 0.2s ease-out;
    }
</style>

<div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Permintaan Konsultasi</h1>
        <p class="text-sm text-gray-600">Kelola permintaan konsultasi dengan pasien Anda</p>
</div>

<!-- Filter and Search -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
        <h2 class="text-lg font-medium text-gray-800">Filter Permintaan</h2>
        <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-3">
            <div class="relative">
                <input type="text" id="search-input" placeholder="Cari pasien..." class="w-full md:w-64 px-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <div class="absolute right-3 top-2.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="custom-select relative">
                <div class="select-selected px-4 py-2 text-sm bg-white border border-gray-300 rounded-lg cursor-pointer hover:border-indigo-400 transition-all duration-200 flex items-center justify-between min-w-[200px] group">
                    <span class="text-gray-700">Semua Pasien</span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 transform group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
                <div class="select-items absolute z-10 w-full py-1 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden" id="pasien-list">
                    <div class="px-4 py-2 text-sm text-gray-700 hover:text-indigo-600 cursor-pointer transition-colors duration-150 hover:bg-indigo-50" data-value="">Semua Pasien</div>
                    @foreach($uniquePasienAktif as $pasien)
                        <div class="px-4 py-2 text-sm text-gray-700 hover:text-indigo-600 cursor-pointer transition-colors duration-150 hover:bg-indigo-50" data-value="{{ $pasien['id'] }}">
                            {{ $pasien['pasien_nama'] }}
                        </div>
                    @endforeach
                </div>
            </div>
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

<!-- Konsultasi Aktif -->
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
                        <div class="text-sm text-gray-900 line-clamp-2">{{ $item['keluhan'] }}</div>
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

    <!-- Pagination untuk Konsultasi Aktif -->
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        <div class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
                @if(count($konsultasiAktifFiltered) > 0)
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Sebelumnya
                    </a>
                    <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Selanjutnya
                    </a>
                @endif
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    @if(count($konsultasiAktifFiltered) > 0)
                        <p class="text-sm text-gray-700">
                            Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium">{{ count($konsultasiAktifFiltered) }}</span> dari <span class="font-medium">{{ count($konsultasiAktifFiltered) }}</span> hasil
                        </p>
                    @else
                        <p class="text-sm text-gray-500 italic">
                            Belum ada konsultasi aktif saat ini
                        </p>
                    @endif
                </div>
                @if(count($konsultasiAktifFiltered) > 0)
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
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Konsultasi Tidak Aktif -->
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
                    $konsultasiTidakAktif = array_filter($konsultasiSelesai ?? [], function($item) {
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
                        <div class="text-sm text-gray-900 line-clamp-2">{{ $item['keluhan'] }}</div>
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

    <!-- Pagination untuk Konsultasi Tidak Aktif -->
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        <div class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
                @if(count($konsultasiTidakAktif) > 0)
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Sebelumnya
                    </a>
                    <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Selanjutnya
                    </a>
                @endif
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    @if(count($konsultasiTidakAktif) > 0)
                        <p class="text-sm text-gray-700">
                            Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium">{{ count($konsultasiTidakAktif) }}</span> dari <span class="font-medium">{{ count($konsultasiTidakAktif) }}</span> hasil
                        </p>
                    @else
                        <p class="text-sm text-gray-500 italic">
                            Belum ada konsultasi tidak aktif saat ini
                        </p>
                    @endif
                </div>
                @if(count($konsultasiTidakAktif) > 0)
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
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const customSelect = document.querySelector('.custom-select');
    const selectSelected = customSelect.querySelector('.select-selected');
    const selectItems = customSelect.querySelector('.select-items');
    const pasienList = document.getElementById('pasien-list');
    const searchInput = document.getElementById('search-input');
    
    let selectedValue = '';
    let searchTerm = '';
    let currentTab = 'active'; // Default tab

    // Data pasien untuk kedua tab
    const pasienData = {
        active: @json($uniquePasienAktif),
        inactive: @json($uniquePasienTidakAktif)
    };

    // Toggle dropdown with animation
    selectSelected.addEventListener('click', function(e) {
        e.stopPropagation();
        const isOpen = !selectItems.classList.contains('hidden');
        
        if (!isOpen) {
            openSelect();
        } else {
            closeSelect();
        }
    });

    // Handle option selection with animation
    function initializeSelectItems() {
        selectItems.querySelectorAll('div').forEach(item => {
            item.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                const text = this.textContent.trim();
                
                selectSelected.querySelector('span').textContent = text;
                selectedValue = value;
                
                // Add selected styling
                selectItems.querySelectorAll('div').forEach(div => {
                    div.classList.remove('bg-indigo-50', 'text-indigo-600');
                });
                this.classList.add('bg-indigo-50', 'text-indigo-600');
                
                filterTable();
                closeSelect();
            });
        });
    }

    // Update pasien list based on current tab
    function updatePasienList() {
        const currentPasienData = pasienData[currentTab];
        let html = '<div class="px-4 py-2 text-sm text-gray-700 hover:text-indigo-600 cursor-pointer transition-colors duration-150 hover:bg-indigo-50" data-value="">Semua Pasien</div>';
        
        currentPasienData.forEach(pasien => {
            html += `
                <div class="px-4 py-2 text-sm text-gray-700 hover:text-indigo-600 cursor-pointer transition-colors duration-150 hover:bg-indigo-50" data-value="${pasien.id}">
                    ${pasien.pasien_nama}
                </div>
            `;
        });
        
        pasienList.innerHTML = html;
        initializeSelectItems();
        
        // Reset selection
        selectedValue = '';
        selectSelected.querySelector('span').textContent = 'Semua Pasien';
    }

    // Handle search input
    searchInput.addEventListener('input', function() {
        searchTerm = this.value.toLowerCase();
        filterTable();
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', closeSelect);

    function openSelect() {
        selectItems.classList.remove('hidden');
        selectSelected.querySelector('svg').classList.add('rotate-180');
        selectSelected.classList.add('border-indigo-500', 'ring-2', 'ring-indigo-200');
        selectItems.style.animation = 'slideDown 0.2s ease-out';
    }

    function closeSelect() {
        selectItems.style.animation = 'slideUp 0.2s ease-out';
        setTimeout(() => {
            selectItems.classList.add('hidden');
            selectSelected.querySelector('svg').classList.remove('rotate-180');
            selectSelected.classList.remove('border-indigo-500', 'ring-2', 'ring-indigo-200');
        }, 180);
    }

    function filterTable() {
        const activeTable = document.querySelector('#tab-active');
        const inactiveTable = document.querySelector('#tab-inactive');
        const currentTable = currentTab === 'active' ? activeTable : inactiveTable;
        const rows = currentTable.querySelectorAll('tbody tr');

        rows.forEach(row => {
            if (row.classList.contains('no-data-row')) return;
            
            const pasienName = row.querySelector('.text-gray-900')?.textContent.toLowerCase();
            const pasienId = row.getAttribute('data-pasien-id');
            
            const matchesSearch = !searchTerm || (pasienName && pasienName.includes(searchTerm));
            const matchesFilter = !selectedValue || pasienId === selectedValue;
            
            row.classList.toggle('hidden', !(matchesSearch && matchesFilter));
        });

        // Show/hide no results message
        const hasVisibleRows = Array.from(rows).some(row => !row.classList.contains('hidden'));
        const noDataRow = currentTable.querySelector('.no-data-row');
        
        if (noDataRow) {
            noDataRow.classList.toggle('hidden', hasVisibleRows);
        }
    }

    // Tab switching functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Update current tab
            currentTab = button.getAttribute('data-tab');
            
            // Update pasien list for the new tab
            updatePasienList();
            
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
            document.getElementById(`tab-${currentTab}`).classList.remove('hidden');
            
            // Reset and apply filters for the new tab
            filterTable();
        });
    });

    // Initialize select items for the default tab
    initializeSelectItems();

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
@endpush
@endsection 