@extends('layouts.dokter')
@section('dokter-content')

@php
    $riwayatKonsultasiSelesai = array_filter($konsultasiSelesai ?? [], function($item) {
        return $item['status'] === 'Selesai';
    });
    $uniquePasien = collect($riwayatKonsultasiSelesai)->unique('pasien_nama')->values();
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
    <h1 class="text-2xl font-bold text-gray-800">Riwayat Konsultasi</h1>
    <p class="text-sm text-gray-600">Lihat riwayat konsultasi pasien dan nilai yang diperoleh</p>
</div>

<!-- Filter and Search -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
        <h2 class="text-lg font-medium text-gray-800">Filter Riwayat</h2>
        <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-3">
            <div class="relative">
                <input type="text" id="search-input" placeholder="Cari pasien..." class="w-full md:w-64 px-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <div class="absolute right-3 top-2.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0118 0z"></path>
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
                <div class="select-items absolute z-10 w-full py-1 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden">
                    <div class="px-4 py-2 text-sm text-gray-700 hover:text-indigo-600 cursor-pointer transition-colors duration-150 hover:bg-indigo-50" data-value="">Semua Pasien</div>
                    @foreach($uniquePasien as $pasien)
                        <div class="px-4 py-2 text-sm text-gray-700 hover:text-indigo-600 cursor-pointer transition-colors duration-150 hover:bg-indigo-50" data-value="{{ $pasien['id'] }}">
                            {{ $pasien['pasien_nama'] }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const customSelect = document.querySelector('.custom-select');
    const selectSelected = customSelect.querySelector('.select-selected');
    const selectItems = customSelect.querySelector('.select-items');
    const searchInput = document.getElementById('search-input');
    const tableRows = document.querySelectorAll('tbody tr');
    
    let selectedValue = '';
    let searchTerm = '';

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
        tableRows.forEach(row => {
            if (row.classList.contains('no-data-row')) return;
            
            const pasienName = row.querySelector('.text-gray-900').textContent.toLowerCase();
            const pasienId = row.getAttribute('data-pasien-id');
            
            const matchesSearch = !searchTerm || pasienName.includes(searchTerm);
            const matchesFilter = !selectedValue || pasienId === selectedValue;
            
            row.classList.toggle('hidden', !(matchesSearch && matchesFilter));
        });

        // Show/hide no results message
        const hasVisibleRows = Array.from(tableRows).some(row => !row.classList.contains('hidden'));
        const noDataRow = document.querySelector('.no-data-row');
        
        if (noDataRow) {
            noDataRow.classList.toggle('hidden', hasVisibleRows);
        }
    }

    // Show notification for session messages if any
    @if(session('success'))
    Swal.fire({
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        icon: 'success',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
    });
    @endif

    @if(session('error'))
    Swal.fire({
        title: 'Error!',
        text: "{{ session('error') }}",
        icon: 'error',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
    });
    @endif
});
</script>

<!-- Riwayat Konsultasi List -->
<div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Riwayat Konsultasi
        </h2>
        <p class="text-sm text-gray-500">Daftar konsultasi yang telah selesai, dibatalkan, atau ditolak</p>
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
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nilai
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
                @if(count($konsultasiSelesai) > 0)
                    @php $count = 0; @endphp
                    @foreach($konsultasiSelesai as $item)
                    @php if($count >= 10) break; $count++; @endphp
                    <tr class="hover:bg-gray-50 transition" data-pasien-id="{{ $item['pasien_id'] }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full object-cover bg-gray-100" src="{{ $item['pasien_foto_url'] ?? \App\Support\ProfilePhoto::transparentDataUrl() }}" alt="{{ $item['pasien_nama'] }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item['pasien_nama'] }}</div>
                                    <div class="text-sm text-gray-500">{{ $item['pasien_gender'] }}, {{ $item['pasien_usia'] }} tahun</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item['tanggal'] }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $item['jam'] }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 line-clamp-2">{{ $item['keluhan'] }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 line-clamp-2">{{ $item['status'] }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 line-clamp-2">{{ $item['nilai'] ?? '-' }}</div>
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
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <a href="{{ route('chat.create', $item['id']) }}" class="text-blue-600 hover:text-blue-900 border border-blue-300 rounded-md px-2 py-1 hover:bg-blue-50 transition inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                Lihat Chat
                            </a>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr class="no-data-row">
                        <td colspan="6" class="px-6 py-10 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-gray-500 font-medium mb-1">Tidak ada riwayat konsultasi</p>
                                <p class="text-gray-400 text-sm max-w-md">Semua riwayat konsultasi yang telah selesai akan muncul di sini. Saat ini belum ada konsultasi yang telah selesai.</p>
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        <div class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
                @if($konsultasiPaginator && $konsultasiPaginator->total() > 0)
                    <a href="{{ $konsultasiPaginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 {{ $konsultasiPaginator->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                        Sebelumnya
                    </a>
                    <a href="{{ $konsultasiPaginator->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 {{ !$konsultasiPaginator->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
                        Selanjutnya
                    </a>
                @endif
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    @if($konsultasiPaginator && $konsultasiPaginator->total() > 0)
                        <p class="text-sm text-gray-700">
                            Menampilkan <span class="font-medium">{{ $konsultasiPaginator->firstItem() }}</span> sampai <span class="font-medium">{{ $konsultasiPaginator->lastItem() }}</span> dari <span class="font-medium">{{ $konsultasiPaginator->total() }}</span> hasil
                        </p>
                    @else
                        <p class="text-sm text-gray-500 italic">
                            Belum ada riwayat konsultasi selesai
                        </p>
                    @endif
                </div>
                @if($konsultasiPaginator && $konsultasiPaginator->total() > 0)
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <a href="{{ $konsultasiPaginator->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 {{ $konsultasiPaginator->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                                <span class="sr-only">Sebelumnya</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="#" aria-current="page" class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                {{ $konsultasiPaginator->currentPage() }}
                            </a>
                            <a href="{{ $konsultasiPaginator->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 {{ !$konsultasiPaginator->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
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

<!-- Summary of Nilai -->
<div class="mt-8 bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Rekap Nilai Konsultasi</h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-green-700 font-medium">Nilai Rata-rata</p>
                        <p class="text-3xl font-bold text-green-800 mt-1">{{ $nilaiRata ?? '0' }}</p>
                    </div>
                    <div class="bg-green-100 p-2 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-blue-700 font-medium">Konsultasi Selesai</p>
                        <p class="text-3xl font-bold text-blue-800 mt-1">{{ count($riwayatKonsultasiSelesai) }}</p>
                    </div>
                    <div class="bg-blue-100 p-2 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-purple-700 font-medium">Nilai Tertinggi</p>
                        <p class="text-3xl font-bold text-purple-800 mt-1">{{ $nilaiTertinggi ?? '0' }}</p>
                    </div>
                    <div class="bg-purple-100 p-2 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-yellow-700 font-medium">Rating Rata-rata</p>
                        <p class="text-3xl font-bold text-yellow-800 mt-1">{{ $ratingRata ?? '0' }}</p>
                    </div>
                    <div class="bg-yellow-100 p-2 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6 pt-6 border-t border-gray-200">
            <h3 class="text-md font-medium text-gray-700 mb-4">Distribusi Rating</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    @if($totalRating > 0)
                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                        <h4 class="text-sm font-medium text-gray-700 mb-5">Jumlah Rating</h4>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center w-2/5">
                                    <div class="flex">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="ml-2 text-sm text-gray-600">5 bintang</span>
                                </div>
                                <div class="flex items-center w-3/5">
                                    <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $totalRating > 0 ? ($rating5 / $totalRating * 100) : 0 }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 w-6 text-right">{{ $rating5 }}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center w-2/5">
                                    <div class="flex">
                                        @for($i = 1; $i <= 4; $i++)
                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                        <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    </div>
                                    <span class="ml-2 text-sm text-gray-600">4 bintang</span>
                                </div>
                                <div class="flex items-center w-3/5">
                                    <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $totalRating > 0 ? ($rating4 / $totalRating * 100) : 0 }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 w-6 text-right">{{ $rating4 }}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center w-2/5">
                                    <div class="flex">
                                        @for($i = 1; $i <= 3; $i++)
                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                        @for($i = 1; $i <= 2; $i++)
                                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="ml-2 text-sm text-gray-600">3 bintang</span>
                                </div>
                                <div class="flex items-center w-3/5">
                                    <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $totalRating > 0 ? ($rating3 / $totalRating * 100) : 0 }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 w-6 text-right">{{ $rating3 }}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center w-2/5">
                                    <div class="flex">
                                        @for($i = 1; $i <= 2; $i++)
                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                        @for($i = 1; $i <= 3; $i++)
                                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="ml-2 text-sm text-gray-600">2 bintang</span>
                                </div>
                                <div class="flex items-center w-3/5">
                                    <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $totalRating > 0 ? ($rating2 / $totalRating * 100) : 0 }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 w-6 text-right">{{ $rating2 }}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center w-2/5">
                                    <div class="flex">
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        @for($i = 1; $i <= 4; $i++)
                                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="ml-2 text-sm text-gray-600 mb-1">1 bintang</span>
                                </div>
                                <div class="flex items-center w-3/5">
                                    <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $totalRating > 0 ? ($rating1 / $totalRating * 100) : 0 }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 w-6 text-right">{{ $rating1 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="bg-white p-6 rounded-lg border border-gray-200 text-center">
                        <p class="text-gray-500">Belum ada rating dari pasien</p>
                    </div>
                    @endif
                </div>
                
                <div>
                    @if(($totalSelesai + $totalDitolak + $totalDibatalkan + $totalTerlambat) > 0)
                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Statistik Konsultasi</h4>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700 w-2/5">Selesai</span>
                                    <span class="text-sm font-medium text-gray-700 w-6 text-right">{{ $totalSelesai }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($totalSelesai + $totalDitolak + $totalDibatalkan + $totalTerlambat) > 0 ? ($totalSelesai / ($totalSelesai + $totalDitolak + $totalDibatalkan + $totalTerlambat) * 100) : 0 }}%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700 w-2/5">Ditolak</span>
                                    <span class="text-sm font-medium text-gray-700 w-6 text-right">{{ $totalDitolak }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-red-500 h-2 rounded-full" style="width: {{ ($totalSelesai + $totalDitolak + $totalDibatalkan + $totalTerlambat) > 0 ? ($totalDitolak / ($totalSelesai + $totalDitolak + $totalDibatalkan + $totalTerlambat) * 100) : 0 }}%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700 w-2/5">Dibatalkan</span>
                                    <span class="text-sm font-medium text-gray-700 w-6 text-right">{{ $totalDibatalkan }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-orange-500 h-2 rounded-full" style="width: {{ ($totalSelesai + $totalDitolak + $totalDibatalkan + $totalTerlambat) > 0 ? ($totalDibatalkan / ($totalSelesai + $totalDitolak + $totalDibatalkan + $totalTerlambat) * 100) : 0 }}%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700 w-2/5">Terlambat</span>
                                    <span class="text-sm font-medium text-gray-700 w-6 text-right">{{ $totalTerlambat }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ ($totalSelesai + $totalDitolak + $totalDibatalkan + $totalTerlambat) > 0 ? ($totalTerlambat / ($totalSelesai + $totalDitolak + $totalDibatalkan + $totalTerlambat) * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="bg-white p-6 rounded-lg border border-gray-200 text-center">
                        <p class="text-gray-500">Belum ada konsultasi</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Aspek Penilaian -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <h3 class="text-md font-medium text-gray-700 mb-4">Aspek Penilaian</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="mb-4">
                        <div class="flex justify-between mb-1 items-center">
                            <div class="text-sm font-medium text-gray-700">Komunikasi</div>
                            <div class="text-sm font-medium text-gray-700">85%</div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="flex justify-between mb-1 items-center">
                            <div class="text-sm font-medium text-gray-700">Anamnesis</div>
                            <div class="text-sm font-medium text-gray-700">80%</div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 80%"></div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="mb-4">
                        <div class="flex justify-between mb-1 items-center">
                            <div class="text-sm font-medium text-gray-700">Nilai</div>
                            <div class="text-sm font-medium text-gray-700">75%</div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 75%"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="flex justify-between mb-1 items-center">
                            <div class="text-sm font-medium text-gray-700">Empati</div>
                            <div class="text-sm font-medium text-gray-700">90%</div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 90%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 