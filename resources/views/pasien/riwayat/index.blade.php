@extends('layouts.pasien')

@section('pasien-content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Riwayat Konsultasi</h1>
            <p class="text-sm text-gray-600">Riwayat konsultasi dan rekam medis Anda</p>
        </div>
        <a href="{{ route('pasien.konsultasi.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md text-sm transition flex items-center justify-center w-full md:w-auto">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Buat Konsultasi Baru
        </a>
    </div>
</div>

<style>
    /* Custom Select Styling */
    select {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1.2em;
    }
    
    select:focus {
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%233B82F6' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    }

    /* Hover effect for select options */
    select option {
        padding: 1rem;
        cursor: pointer;
        font-size: 0.875rem;
    }

    select option:checked {
        background: linear-gradient(to right, #3B82F6, #4F46E5);
        color: white;
    }

    select option:hover {
        background-color: #EFF6FF;
    }

    /* Custom scrollbar for select dropdown */
    select::-webkit-scrollbar {
        width: 8px;
    }

    select::-webkit-scrollbar-track {
        background: #F3F4F6;
        border-radius: 4px;
    }

    select::-webkit-scrollbar-thumb {
        background: #CBD5E1;
        border-radius: 4px;
    }

    select::-webkit-scrollbar-thumb:hover {
        background: #94A3B8;
    }
</style>

<!-- Filter dan Pencarian -->
<div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
    <div class="p-6">
        <form action="{{ route('pasien.riwayat.index') }}" method="GET" class="space-y-4">
            <div class="flex flex-col lg:flex-row lg:items-center lg:space-x-4 space-y-4 lg:space-y-0">
                <!-- Search Bar -->
                <div class="flex-grow">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-200 bg-gray-50 text-gray-800 placeholder-gray-400"
                            placeholder="Cari berdasarkan keluhan atau diagnosa...">
                        <div class="absolute left-4 top-3.5 text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Filter Mahasiswa -->
                <div class="w-full lg:w-64">
                    <div class="relative">
                        <select name="mahasiswa" class="w-full pl-4 pr-12 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-200 bg-gray-50 text-gray-800 hover:bg-gray-100 cursor-pointer">
                            <option value="">Semua Mahasiswa</option>
                            @foreach($mahasiswas ?? [] as $mahasiswa)
                                <option value="{{ $mahasiswa->id }}" {{ request('mahasiswa') == $mahasiswa->id ? 'selected' : '' }}>
                                    {{ $mahasiswa->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Filter Periode -->
                <div class="w-full lg:w-48">
                    <div class="relative">
                        <select name="period" class="w-full pl-4 pr-12 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-200 bg-gray-50 text-gray-800 hover:bg-gray-100 cursor-pointer">
                            <option value="">Semua Waktu</option>
                            <option value="7" {{ request('period') == '7' ? 'selected' : '' }}>7 Hari Terakhir</option>
                            <option value="30" {{ request('period') == '30' ? 'selected' : '' }}>30 Hari Terakhir</option>
                            <option value="90" {{ request('period') == '90' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                            <option value="180" {{ request('period') == '180' ? 'selected' : '' }}>6 Bulan Terakhir</option>
                        </select>
                    </div>
                </div>

                <!-- Tombol Filter -->
                <div class="w-full lg:w-auto">
                    <button type="submit" class="w-full lg:w-auto bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-xl transition-all duration-200 flex items-center justify-center space-x-2 shadow-sm hover:shadow transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <span>Terapkan Filter</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Riwayat Konsultasi -->
<div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-500 to-indigo-600">
        <h2 class="text-xl font-semibold text-white flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Riwayat Konsultasi
        </h2>
        <p class="text-sm text-blue-100 mt-1">Konsultasi dengan status Selesai</p>
    </div>
    
    @if($riwayatKonsultasi->isEmpty())
    <div class="p-6 text-center">
        <div class="py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum Ada Riwayat Konsultasi</h3>
            <p class="mt-1 text-sm text-gray-500">Riwayat konsultasi Anda akan muncul di sini setelah konsultasi selesai.</p>
            <div class="mt-6">
                <a href="{{ route('pasien.konsultasi.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Konsultasi
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="p-0">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Mahasiswa</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Jadwal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Keluhan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Diagnosa</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Rating</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $riwayatKonsultasiSelesai = $riwayatKonsultasi->filter(function($item) {
                            return $item->status === 'Selesai';
                        });
                    @endphp
                    
                    @foreach($riwayatKonsultasiSelesai as $item)
                    <tr class="hover:bg-gray-50 transition" id="konsultasi-{{ $item->id }}">
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
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $item->diagnosa ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($item->rating)
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $item->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                    <span class="ml-1 text-sm text-gray-600">({{ $item->rating }}/5)</span>
                                </div>
                            @else
                                <button onclick="berikanRating({{ $item->id }})" class="inline-flex items-center px-3 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                    Beri Rating
                                </button>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('chat.create', $item->id) }}" class="text-blue-600 hover:text-blue-900 border border-blue-300 rounded-md px-2 py-1 hover:bg-blue-50 transition inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                Lihat Chat
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-6 mt-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div class="text-sm text-gray-500">
                Menampilkan {{ $riwayatKonsultasi->firstItem() ?? 0 }}-{{ $riwayatKonsultasi->lastItem() ?? 0 }} dari {{ $riwayatKonsultasi->total() }} hasil
            </div>
            <div class="flex flex-wrap justify-center md:justify-end gap-2">
                @if($riwayatKonsultasi->onFirstPage())
                <button class="px-3 py-1 border rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                @else
                <a href="{{ $riwayatKonsultasi->previousPageUrl() }}" class="px-3 py-1 border rounded-md bg-white text-gray-700 hover:bg-gray-50 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Sebelumnya
                </a>
                @endif
                
                <div class="flex">
                    @foreach($riwayatKonsultasi->getUrlRange(max($riwayatKonsultasi->currentPage() - 2, 1), min($riwayatKonsultasi->currentPage() + 2, $riwayatKonsultasi->lastPage())) as $page => $url)
                        <a href="{{ $url }}" class="px-3 py-1 border-t border-b border-r first:border-l {{ $page == $riwayatKonsultasi->currentPage() ? 'bg-blue-50 text-blue-600 font-medium border-blue-300' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300' }}">
                            {{ $page }}
                        </a>
                    @endforeach
                </div>
                
                @if($riwayatKonsultasi->hasMorePages())
                <a href="{{ $riwayatKonsultasi->nextPageUrl() }}" class="px-3 py-1 border rounded-md bg-white text-gray-700 hover:bg-gray-50 flex items-center">
                    Berikutnya
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                @else
                <button class="px-3 py-1 border rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal untuk Beri Rating -->
<div id="ratingModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="absolute inset-0 bg-gray-800 opacity-50" onclick="hideRatingModal()"></div>
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    Berikan Rating
                </h3>
                <button onclick="hideRatingModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="formRating" method="POST" action="">
                @csrf
            <div class="mt-4">
                    <div class="mb-4 text-center">
                        <p class="text-sm text-gray-600 mb-2">Bagaimana pengalaman konsultasi Anda?</p>
                        <div class="flex justify-center space-x-2">
                            <input type="hidden" id="ratingValue" name="rating" value="0">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" onclick="setRating({{ $i }})" class="rating-star w-10 h-10 focus:outline-none">
                                    <svg class="w-full h-full text-gray-300" id="star-{{ $i }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </button>
                            @endfor
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="komentar" class="block text-sm font-medium text-gray-700 mb-1">Komentar (opsional)</label>
                        <textarea id="komentar" name="komentar" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Berikan komentar tentang pengalaman konsultasi Anda..."></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="hideRatingModal()" class="mr-2 px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Kirim Rating
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function berikanRating(id) {
        // Reset rating
        document.getElementById('ratingValue').value = 0;
        for (let i = 1; i <= 5; i++) {
            document.getElementById('star-' + i).classList.remove('text-yellow-400');
            document.getElementById('star-' + i).classList.add('text-gray-300');
            }
            
        // Set action form
        document.getElementById('formRating').action = '/pasien/konsultasi/' + id + '/rating';
        
        // Show modal
        document.getElementById('ratingModal').classList.remove('hidden');
    }
    
    function hideRatingModal() {
        document.getElementById('ratingModal').classList.add('hidden');
    }
    
    function setRating(value) {
        document.getElementById('ratingValue').value = value;
            
        // Update star colors
        for (let i = 1; i <= 5; i++) {
            if (i <= value) {
                document.getElementById('star-' + i).classList.remove('text-gray-300');
                document.getElementById('star-' + i).classList.add('text-yellow-400');
            } else {
                document.getElementById('star-' + i).classList.remove('text-yellow-400');
                document.getElementById('star-' + i).classList.add('text-gray-300');
            }
        }
}
</script>
@endsection 