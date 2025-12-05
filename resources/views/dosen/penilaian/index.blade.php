@extends('layouts.dosen')

@section('title', 'Penilaian Konsultasi')

@section('dosen-content')
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
    
    /* Custom range slider styles */
    input[type="range"] {
        -webkit-appearance: none;
        height: 6px;
        border-radius: 5px;
        background: linear-gradient(to right, #3b82f6 0%, #3b82f6 50%, #e5e7eb 50%, #e5e7eb 100%);
        outline: none;
    }
    
    input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #3b82f6;
        cursor: pointer;
        box-shadow: 0 0 5px rgba(59, 130, 246, 0.3);
        transition: all 0.2s ease;
    }
    
    input[type="range"]::-moz-range-thumb {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #3b82f6;
        cursor: pointer;
        box-shadow: 0 0 5px rgba(59, 130, 246, 0.3);
        transition: all 0.2s ease;
        border: none;
    }
    
    input[type="range"]::-webkit-slider-thumb:hover {
        background: #2563eb;
        box-shadow: 0 0 8px rgba(59, 130, 246, 0.5);
        transform: scale(1.1);
    }
    
    input[type="range"]::-moz-range-thumb:hover {
        background: #2563eb;
        box-shadow: 0 0 8px rgba(59, 130, 246, 0.5);
        transform: scale(1.1);
    }
    
    /* Modal animation */
    @keyframes modalFadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes modalFadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(20px); }
    }
</style>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Penilaian Konsultasi</h1>
    <p class="text-sm text-gray-600">Kelola dan nilai konsultasi yang telah selesai</p>
</div>

<!-- Filter and Search -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
        <h2 class="text-lg font-medium text-gray-800">Filter Konsultasi</h2>
        <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-3">
            <div class="relative">
                <input type="text" id="search-input" placeholder="Cari pasien atau dokter..." class="w-full md:w-64 px-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <div class="absolute right-3 top-2.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="custom-select relative">
                <div class="select-selected px-4 py-2 text-sm bg-white border border-gray-300 rounded-lg cursor-pointer hover:border-indigo-400 transition-all duration-200 flex items-center justify-between min-w-[200px] group">
                    <span class="text-gray-700" id="selected-dokter-text">Semua Dokter</span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 transform group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
                <div class="select-items absolute z-10 w-full py-1 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden" id="dokter-list">
                    <div class="px-4 py-2 text-sm text-gray-700 hover:text-indigo-600 cursor-pointer transition-colors duration-150 hover:bg-indigo-50 dokter-option" data-value="">Semua Dokter</div>
                    @foreach(\App\Models\Dokter::all() as $dokter)
                        <div class="px-4 py-2 text-sm text-gray-700 hover:text-indigo-600 cursor-pointer transition-colors duration-150 hover:bg-indigo-50 dokter-option" data-value="{{ $dokter->user_id }}">
                            {{ $dokter->nama }}
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="relative">
                <input type="date" id="tanggal-filter" class="w-full md:w-48 px-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    </div>
</div>

<!-- Tab Navigation -->
<div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
    <div class="border-b">
        <nav class="flex">
            <button class="tab-button px-6 py-4 text-sm font-medium text-blue-600 border-b-2 border-blue-600" data-tab="belum-dinilai">
                Belum Dinilai
            </button>
            <button class="tab-button px-6 py-4 text-sm font-medium text-gray-600 hover:text-gray-800" data-tab="sudah-dinilai">
                Sudah Dinilai
            </button>
        </nav>
    </div>
</div>

<!-- Konsultasi Belum Dinilai -->
<div id="tab-belum-dinilai" class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            Konsultasi Belum Dinilai
        </h2>
        <p class="text-sm text-gray-500">Konsultasi yang telah selesai namun belum diberikan penilaian</p>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Dokter
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Pasien
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tanggal
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Keluhan
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="belum-dinilai-tbody">
                @php
                    $konsultasisBelumDinilai = \App\Models\Konsultasi::where('status', 'Selesai')
                        ->whereNull('nilai_dosen')
                        ->orderBy('updated_at', 'desc')
                        ->paginate(10, ['*'], 'belum_page');
                @endphp
                
                @forelse($konsultasisBelumDinilai as $konsultasi)
                <tr class="hover:bg-gray-50 transition konsultasi-row" 
                    data-dokter="{{ $konsultasi->dokter->name }}"
                    data-pasien="{{ $konsultasi->pasien->nama ?? 'Tidak ada nama' }}"
                    data-dokter-id="{{ $konsultasi->dokter_id }}"
                    data-tanggal="{{ $konsultasi->tanggal->format('Y-m-d') }}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $konsultasi->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($konsultasi->dokter->name) }}&background=4F46E5&color=fff" alt="{{ $konsultasi->dokter->name }}">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $konsultasi->dokter->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($konsultasi->pasien->nama ?? 'Tidak ada nama') }}&background=4F46E5&color=fff" alt="{{ $konsultasi->pasien->nama ?? 'Tidak ada nama' }}">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $konsultasi->pasien->nama ?? 'Tidak ada nama' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $konsultasi->tanggal_indonesia }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ \Illuminate\Support\Str::limit($konsultasi->keluhan, 30) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                        <div class="flex space-x-2 justify-center">
                            <!-- Tombol Detail (mengarah ke chatroom) -->
                            <a href="{{ $konsultasi->chatRoom ? route('chat.room', $konsultasi->chatRoom) : '#' }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-700 rounded hover:bg-indigo-200 transition {{ !$konsultasi->chatRoom ? 'opacity-50 cursor-not-allowed' : '' }}"
                               {{ !$konsultasi->chatRoom ? 'onclick="return false;"' : 'target="_blank"' }}>
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Detail
                            </a>
                            
                            <!-- Tombol Nilai -->
                            <button type="button" onclick="openNilaiModal('{{ $konsultasi->id }}')" class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                                Nilai
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr id="belum-dinilai-empty-row">
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        Belum ada riwayat konsultasi selesai
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination untuk Belum Dinilai -->
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        <div class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
                @if($konsultasisBelumDinilai->total() > 0)
                    <a href="{{ $konsultasisBelumDinilai->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 {{ $konsultasisBelumDinilai->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                        Sebelumnya
                    </a>
                    <a href="{{ $konsultasisBelumDinilai->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 {{ !$konsultasisBelumDinilai->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
                        Selanjutnya
                    </a>
                @endif
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    @if($konsultasisBelumDinilai->total() > 0)
                        <p class="text-sm text-gray-700">
                            Menampilkan <span class="font-medium">{{ $konsultasisBelumDinilai->firstItem() }}</span> sampai <span class="font-medium">{{ $konsultasisBelumDinilai->lastItem() }}</span> dari <span class="font-medium">{{ $konsultasisBelumDinilai->total() }}</span> hasil
                        </p>
                    @else
                        <p class="text-sm text-gray-500 italic">Belum ada konsultasi selesai</p>
                    @endif
                </div>
                @if($konsultasisBelumDinilai->total() > 0)
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <a href="{{ $konsultasisBelumDinilai->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 {{ $konsultasisBelumDinilai->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                                <span class="sr-only">Sebelumnya</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <span class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">{{ $konsultasisBelumDinilai->currentPage() }}</span>
                            <a href="{{ $konsultasisBelumDinilai->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 {{ !$konsultasisBelumDinilai->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
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

<!-- Konsultasi Sudah Dinilai -->
<div id="tab-sudah-dinilai" class="bg-white rounded-lg shadow-md overflow-hidden mb-6 hidden">
    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Konsultasi Sudah Dinilai
        </h2>
        <p class="text-sm text-gray-500">Konsultasi yang telah selesai dan sudah diberikan penilaian</p>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Dokter
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Pasien
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tanggal
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nilai
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="sudah-dinilai-tbody">
                @php
                    $konsultasisSudahDinilai = \App\Models\Konsultasi::where('status', 'Selesai')
                        ->whereNotNull('nilai_dosen')
                        ->orderBy('updated_at', 'desc')
                        ->paginate(10, ['*'], 'sudah_page');
                @endphp
                
                @forelse($konsultasisSudahDinilai as $konsultasi)
                <tr class="hover:bg-gray-50 transition konsultasi-row" 
                    data-dokter="{{ $konsultasi->dokter->name }}"
                    data-pasien="{{ $konsultasi->pasien->nama ?? 'Tidak ada nama' }}"
                    data-dokter-id="{{ $konsultasi->dokter_id }}"
                    data-tanggal="{{ $konsultasi->tanggal->format('Y-m-d') }}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $konsultasi->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($konsultasi->dokter->name) }}&background=4F46E5&color=fff" alt="{{ $konsultasi->dokter->name }}">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $konsultasi->dokter->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($konsultasi->pasien->nama ?? 'Tidak ada nama') }}&background=4F46E5&color=fff" alt="{{ $konsultasi->pasien->nama ?? 'Tidak ada nama' }}">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $konsultasi->pasien->nama ?? 'Tidak ada nama' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $konsultasi->tanggal_indonesia }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            {{ $konsultasi->nilai_dosen }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                        <a href="{{ $konsultasi->chatRoom ? route('chat.room', $konsultasi->chatRoom) : '#' }}"
                           class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-700 rounded hover:bg-indigo-200 transition {{ !$konsultasi->chatRoom ? 'opacity-50 cursor-not-allowed' : '' }}"
                           {{ !$konsultasi->chatRoom ? 'onclick="return false;"' : '' }}>
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Lihat Chat
                        </a>
                    </td>
                </tr>
                @empty
                <tr id="sudah-dinilai-empty-row">
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        Belum ada riwayat konsultasi selesai
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination untuk Sudah Dinilai -->
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        <div class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
                @if($konsultasisSudahDinilai->total() > 0)
                    <a href="{{ $konsultasisSudahDinilai->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 {{ $konsultasisSudahDinilai->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                        Sebelumnya
                    </a>
                    <a href="{{ $konsultasisSudahDinilai->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 {{ !$konsultasisSudahDinilai->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
                        Selanjutnya
                    </a>
                @endif
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    @if($konsultasisSudahDinilai->total() > 0)
                        <p class="text-sm text-gray-700">
                            Menampilkan <span class="font-medium">{{ $konsultasisSudahDinilai->firstItem() }}</span> sampai <span class="font-medium">{{ $konsultasisSudahDinilai->lastItem() }}</span> dari <span class="font-medium">{{ $konsultasisSudahDinilai->total() }}</span> hasil
                        </p>
                    @else
                        <p class="text-sm text-gray-500 italic">Belum ada konsultasi dinilai</p>
                    @endif
                </div>
                @if($konsultasisSudahDinilai->total() > 0)
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <a href="{{ $konsultasisSudahDinilai->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 {{ $konsultasisSudahDinilai->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                                <span class="sr-only">Sebelumnya</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <span class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">{{ $konsultasisSudahDinilai->currentPage() }}</span>
                            <a href="{{ $konsultasisSudahDinilai->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 {{ !$konsultasisSudahDinilai->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
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

<!-- Modal Penilaian -->
<div id="nilaiModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black opacity-50 transition-opacity" id="modal-backdrop" onclick="closeNilaiModal()"></div>
        
        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all w-full max-w-lg z-50 animate__animated animate__fadeInUp animate__faster">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-white" id="modal-title">Penilaian Konsultasi</h3>
                    <button type="button" onclick="closeNilaiModal()" class="text-white hover:text-gray-200 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <form id="penilaianForm" method="POST" class="p-6" onsubmit="return prepareFormSubmission()">
                @csrf
                <div class="space-y-6">
                    <!-- Hidden fields to store actual values -->
                    <input type="hidden" name="nilai_komunikasi" id="nilai_komunikasi_hidden">
                    <input type="hidden" name="nilai_anamnesis" id="nilai_anamnesis_hidden">
                    <input type="hidden" name="nilai_diagnosa" id="nilai_diagnosa_hidden">
                    <input type="hidden" name="nilai_empati" id="nilai_empati_hidden">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Komunikasi</label>
                        <div class="flex items-center">
                            <input type="range" id="nilai_komunikasi" name="nilai_komunikasi_slider" min="1" max="100" value="75" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            <input type="number" min="1" max="100" id="nilai_komunikasi_display" value="75" class="ml-3 text-sm font-medium text-gray-700 w-16 text-center border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Anamnesis</label>
                        <div class="flex items-center">
                            <input type="range" id="nilai_anamnesis" name="nilai_anamnesis_slider" min="1" max="100" value="75" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            <input type="number" min="1" max="100" id="nilai_anamnesis_display" value="75" class="ml-3 text-sm font-medium text-gray-700 w-16 text-center border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Diagnosa</label>
                        <div class="flex items-center">
                            <input type="range" id="nilai_diagnosa" name="nilai_diagnosa_slider" min="1" max="100" value="75" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            <input type="number" min="1" max="100" id="nilai_diagnosa_display" value="75" class="ml-3 text-sm font-medium text-gray-700 w-16 text-center border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Empati</label>
                        <div class="flex items-center">
                            <input type="range" id="nilai_empati" name="nilai_empati_slider" min="1" max="100" value="75" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            <input type="number" min="1" max="100" id="nilai_empati_display" value="75" class="ml-3 text-sm font-medium text-gray-700 w-16 text-center border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    

                    
                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <div class="flex items-center">
                            <div class="text-lg font-semibold text-gray-800">Nilai Rata-rata:</div>
                            <div class="ml-2 text-lg font-bold text-blue-600" id="nilai_rata_rata">75</div>
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" onclick="closeNilaiModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                Simpan Penilaian
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show SweetAlert notifications for session messages
        @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            icon: 'success',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        @endif

        @if(session('error'))
        Swal.fire({
            title: 'Error!',
            text: "{{ session('error') }}",
            icon: 'error',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        @endif
        
        // Tab switching
        const tabButtons = document.querySelectorAll('.tab-button');
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                
                // Remove active class from all tab buttons
                tabButtons.forEach(btn => {
                    btn.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                    btn.classList.add('text-gray-600');
                });
                
                // Add active class to clicked tab button
                this.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
                this.classList.remove('text-gray-600');
                
                // Hide all tab contents
                document.querySelectorAll('[id^="tab-"]').forEach(tab => {
                    tab.classList.add('hidden');
                });
                
                // Show selected tab content
                document.getElementById('tab-' + tabId).classList.remove('hidden');
            });
        });
        
        // Custom select dropdown for dokter filter
        const selectSelected = document.querySelector('.select-selected');
        const dokterList = document.getElementById('dokter-list');
        const dokterOptions = document.querySelectorAll('.dokter-option');
        
        selectSelected.addEventListener('click', function() {
            dokterList.classList.toggle('hidden');
        });
        
        dokterOptions.forEach(option => {
            option.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                const text = this.textContent.trim();
                
                // Update selected text
                document.getElementById('selected-dokter-text').textContent = text;
                
                // Mark this option as selected and others as not selected
                dokterOptions.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                
                // Hide dropdown
                dokterList.classList.add('hidden');
                
                // Filter rows based on selected dokter
                filterRows();
            });
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!selectSelected.contains(e.target) && !dokterList.contains(e.target)) {
                dokterList.classList.add('hidden');
            }
        });
        
        // Search functionality
        const searchInput = document.getElementById('search-input');
        const tanggalFilter = document.getElementById('tanggal-filter');
        
        searchInput.addEventListener('input', filterRows);
        tanggalFilter.addEventListener('change', filterRows);
        
        function filterRows() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedOption = document.querySelector('.dokter-option.selected');
            const selectedDokterId = selectedOption ? selectedOption.getAttribute('data-value') : '';
            const selectedTanggal = tanggalFilter.value;
            
            console.log('Filtering with:', { 
                searchTerm, 
                selectedDokterId, 
                selectedTanggal 
            });
            
            // Filter belum dinilai rows
            const belumDinilaiRows = document.querySelectorAll('#belum-dinilai-tbody .konsultasi-row');
            let belumDinilaiVisible = 0;
            
            belumDinilaiRows.forEach(row => {
                const dokterName = row.getAttribute('data-dokter').toLowerCase();
                const pasienName = row.getAttribute('data-pasien').toLowerCase();
                const dokterId = row.getAttribute('data-dokter-id');
                const tanggal = row.getAttribute('data-tanggal');
                
                const matchesSearch = dokterName.includes(searchTerm) || pasienName.includes(searchTerm);
                const matchesDokter = selectedDokterId === '' || dokterId === selectedDokterId;
                const matchesTanggal = selectedTanggal === '' || tanggal === selectedTanggal;
                
                if (matchesSearch && matchesDokter && matchesTanggal) {
                    row.classList.remove('hidden');
                    belumDinilaiVisible++;
                } else {
                    row.classList.add('hidden');
                }
            });
            
            // Show/hide empty message for belum dinilai
            const belumDinilaiEmptyRow = document.getElementById('belum-dinilai-empty-row');
            if (belumDinilaiEmptyRow) {
                if (belumDinilaiVisible === 0) {
                    belumDinilaiEmptyRow.classList.remove('hidden');
                } else {
                    belumDinilaiEmptyRow.classList.add('hidden');
                }
            }
            
            // Filter sudah dinilai rows
            const sudahDinilaiRows = document.querySelectorAll('#sudah-dinilai-tbody .konsultasi-row');
            let sudahDinilaiVisible = 0;
            
            sudahDinilaiRows.forEach(row => {
                const dokterName = row.getAttribute('data-dokter').toLowerCase();
                const pasienName = row.getAttribute('data-pasien').toLowerCase();
                const dokterId = row.getAttribute('data-dokter-id');
                const tanggal = row.getAttribute('data-tanggal');
                
                const matchesSearch = dokterName.includes(searchTerm) || pasienName.includes(searchTerm);
                const matchesDokter = selectedDokterId === '' || dokterId === selectedDokterId;
                const matchesTanggal = selectedTanggal === '' || tanggal === selectedTanggal;
                
                if (matchesSearch && matchesDokter && matchesTanggal) {
                    row.classList.remove('hidden');
                    sudahDinilaiVisible++;
                } else {
                    row.classList.add('hidden');
                }
            });
            
            // Show/hide empty message for sudah dinilai
            const sudahDinilaiEmptyRow = document.getElementById('sudah-dinilai-empty-row');
            if (sudahDinilaiEmptyRow) {
                if (sudahDinilaiVisible === 0) {
                    sudahDinilaiEmptyRow.classList.remove('hidden');
                } else {
                    sudahDinilaiEmptyRow.classList.add('hidden');
                }
            }
        }
        
        // Mark dokter option as selected when clicked
        dokterOptions.forEach(option => {
            option.addEventListener('click', function() {
                dokterOptions.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
            });
        });
        
        // Setup range sliders
        const sliders = ['nilai_komunikasi', 'nilai_anamnesis', 'nilai_diagnosa', 'nilai_empati'];
        sliders.forEach(slider => {
            const input = document.getElementById(slider);
            const display = document.getElementById(slider + '_display');
            
            // Update display when slider changes
            input.addEventListener('input', function() {
                display.value = this.value;
                updateRataRata();
                updateSliderBackground(this);
            });
            
            // Update slider when display input changes
            display.addEventListener('input', function() {
                // Ensure value is between 1-100
                let value = parseInt(this.value);
                if (isNaN(value)) value = 1;
                if (value < 1) value = 1;
                if (value > 100) value = 100;
                
                this.value = value;
                input.value = value;
                updateRataRata();
                updateSliderBackground(input);
            });
            
            // Set initial background
            updateSliderBackground(input);
        });
        
        function updateRataRata() {
            const komunikasi = parseInt(document.getElementById('nilai_komunikasi_display').value);
            const anamnesis = parseInt(document.getElementById('nilai_anamnesis_display').value);
            const diagnosa = parseInt(document.getElementById('nilai_diagnosa_display').value);
            const empati = parseInt(document.getElementById('nilai_empati_display').value);
            
            const rataRata = Math.round((komunikasi + anamnesis + diagnosa + empati) / 4);
            document.getElementById('nilai_rata_rata').textContent = rataRata;
        }
        
        function updateSliderBackground(slider) {
            const value = (slider.value - slider.min) / (slider.max - slider.min) * 100;
            slider.style.background = `linear-gradient(to right, #3b82f6 0%, #3b82f6 ${value}%, #e5e7eb ${value}%, #e5e7eb 100%)`;
        }
    });
    
    // Prepare form submission - transfer values from display inputs to hidden inputs
    function prepareFormSubmission() {
        // Get values from display inputs
        const komunikasi = document.getElementById('nilai_komunikasi_display').value;
        const anamnesis = document.getElementById('nilai_anamnesis_display').value;
        const diagnosa = document.getElementById('nilai_diagnosa_display').value;
        const empati = document.getElementById('nilai_empati_display').value;
        
        // Validate values
        if (!komunikasi || !anamnesis || !diagnosa || !empati) {
            Swal.fire({
                title: 'Error!',
                text: 'Semua nilai harus diisi',
                icon: 'error',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            return false;
        }
        
        // Validate range
        if (komunikasi < 1 || komunikasi > 100 || 
            anamnesis < 1 || anamnesis > 100 || 
            diagnosa < 1 || diagnosa > 100 || 
            empati < 1 || empati > 100) {
            Swal.fire({
                title: 'Error!',
                text: 'Nilai harus antara 1-100',
                icon: 'error',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            return false;
        }
        
        // Set values to hidden inputs
        document.getElementById('nilai_komunikasi_hidden').value = komunikasi;
        document.getElementById('nilai_anamnesis_hidden').value = anamnesis;
        document.getElementById('nilai_diagnosa_hidden').value = diagnosa;
        document.getElementById('nilai_empati_hidden').value = empati;
        
        console.log('Form values:', {
            komunikasi,
            anamnesis,
            diagnosa,
            empati
        });
        
        return true;
    }
    
    // Modal functions
    let currentKonsultasiId = null;
    
    function openNilaiModal(konsultasiId) {
        currentKonsultasiId = konsultasiId;
        document.getElementById('nilaiModal').classList.remove('hidden');
        document.getElementById('penilaianForm').action = "{{ route('dosen.penilaian.store', ['id' => '__ID__']) }}".replace('__ID__', konsultasiId);
        
        // Reset form
        document.getElementById('nilai_komunikasi').value = 75;
        document.getElementById('nilai_anamnesis').value = 75;
        document.getElementById('nilai_diagnosa').value = 75;
        document.getElementById('nilai_empati').value = 75;
        document.getElementById('catatan_dosen').value = '';
        
        // Update displays
        document.getElementById('nilai_komunikasi_display').value = 75;
        document.getElementById('nilai_anamnesis_display').value = 75;
        document.getElementById('nilai_diagnosa_display').value = 75;
        document.getElementById('nilai_empati_display').value = 75;
        document.getElementById('nilai_rata_rata').textContent = 75;
        
        // Add animation
        const modalContent = document.querySelector('#nilaiModal .bg-white');
        modalContent.classList.add('animate__animated', 'animate__fadeInUp', 'animate__faster');
        
        // Prevent body scrolling
        document.body.style.overflow = 'hidden';
    }
    
    function closeNilaiModal() {
        const modalContent = document.querySelector('#nilaiModal .bg-white');
        modalContent.classList.remove('animate__fadeInUp');
        modalContent.classList.add('animate__fadeOutDown');
        
        setTimeout(() => {
            document.getElementById('nilaiModal').classList.add('hidden');
            modalContent.classList.remove('animate__fadeOutDown');
            modalContent.classList.add('animate__fadeInUp');
            
            // Re-enable body scrolling
            document.body.style.overflow = 'auto';
        }, 300);
    }
</script>
@endpush
@endsection 