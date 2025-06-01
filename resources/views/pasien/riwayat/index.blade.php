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

<!-- Filter dan Pencarian -->
<div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
    <div class="p-6">
        <form action="{{ route('pasien.riwayat.index') }}" method="GET" class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-grow">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan keluhan atau diagnosa..." class="w-full pl-10 pr-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div class="absolute left-3 top-2.5">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-4">
                <div>
                    <select name="mahasiswa" class="border rounded-md py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                        <option value="">Semua Mahasiswa</option>
                        @foreach($mahasiswas ?? [] as $mahasiswa)
                            <option value="{{ $mahasiswa->id }}" {{ request('mahasiswa') == $mahasiswa->id ? 'selected' : '' }}>{{ $mahasiswa->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="period" class="border rounded-md py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                        <option value="">Semua Waktu</option>
                        <option value="7" {{ request('period') == '7' ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="30" {{ request('period') == '30' ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option value="90" {{ request('period') == '90' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                        <option value="180" {{ request('period') == '180' ? 'selected' : '' }}>6 Bulan Terakhir</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md text-sm transition flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Riwayat Konsultasi -->
<div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            Daftar Riwayat Konsultasi
        </h2>
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
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keluhan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnosa</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($riwayatKonsultasi as $item)
                    <tr class="hover:bg-gray-50 transition" id="konsultasi-{{ $item->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $item->tanggal->isoFormat('D MMMM Y') }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ substr($item->jam_mulai, 0, 5) }} - {{ substr($item->jam_selesai, 0, 5) }}</div>
                        </td>
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
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $item->diagnosa ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($item->status === 'Selesai')
                                    bg-blue-100 text-blue-800
                                @elseif($item->status === 'Dibatalkan')
                                    bg-gray-100 text-gray-800
                                @elseif($item->status === 'Ditolak')
                                    bg-red-100 text-red-800
                                @elseif($item->status === 'Terlambat')
                                    bg-orange-100 text-orange-800
                                @elseif($item->status === 'Berlangsung')
                                    bg-purple-100 text-purple-800 animate-pulse
                                @endif
                            ">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="showDetailModal({{ $item->id }})" class="text-blue-600 hover:text-blue-900 border border-blue-300 rounded-md px-2 py-1 hover:bg-blue-50 transition inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Detail
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
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

<!-- Modal Detail Konsultasi -->
<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="absolute inset-0 bg-gray-800 opacity-50" onclick="hideDetailModal()"></div>
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m-6-8h6M5 5h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"></path>
                    </svg>
                    Detail Konsultasi
                </h3>
                <button onclick="hideDetailModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="mt-4">
                <div class="flex flex-col md:flex-row gap-4 mb-6">
                    <div class="w-full md:w-1/2 bg-blue-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-blue-800 mb-2">Informasi Konsultasi</h4>
                        <div class="grid grid-cols-1 gap-3">
                            <div>
                                <h5 class="text-xs font-medium text-gray-500">Tanggal</h5>
                                <p id="modal-tanggal" class="text-sm text-gray-800 font-medium">-</p>
                            </div>
                            <div>
                                <h5 class="text-xs font-medium text-gray-500">Waktu</h5>
                                <p id="modal-waktu" class="text-sm text-gray-800 font-medium">-</p>
                            </div>
                            <div>
                                <h5 class="text-xs font-medium text-gray-500">Status</h5>
                                <p id="modal-status" class="text-sm">
                                    <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">-</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="w-full md:w-1/2 bg-green-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-green-800 mb-2">Informasi Mahasiswa</h4>
                        <div class="flex items-start space-x-3">
                            <div id="modal-mahasiswa-avatar" class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold">
                                M
                            </div>
                            <div class="flex-1">
                                <p id="modal-mahasiswa" class="text-sm text-gray-800 font-medium">-</p>
                                <p id="modal-mahasiswa-info" class="text-xs text-gray-500 mt-1">-</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="md:col-span-2">
                        <h4 class="text-sm font-semibold text-gray-800 mb-2 pb-1 border-b">Keluhan</h4>
                        <p id="modal-keluhan" class="text-sm text-gray-800 whitespace-pre-line">-</p>
                    </div>
                    <div class="md:col-span-2">
                        <h4 class="text-sm font-semibold text-gray-800 mb-2 pb-1 border-b">Diagnosa</h4>
                        <p id="modal-diagnosa" class="text-sm text-gray-800">-</p>
                    </div>
                    <div class="md:col-span-2">
                        <h4 class="text-sm font-semibold text-gray-800 mb-2 pb-1 border-b">Catatan Medis</h4>
                        <p id="modal-catatan" class="text-sm text-gray-800 whitespace-pre-line">-</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800 mb-2 pb-1 border-b">Resep</h4>
                        <div id="modal-resep" class="text-sm text-gray-800">-</div>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800 mb-2 pb-1 border-b">Saran</h4>
                        <div id="modal-saran" class="text-sm text-gray-800">-</div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <button onclick="hideDetailModal()" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none transition flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Scroll ke konsultasi yang dipilih jika ada parameter konsultasi_id di URL
    const urlParams = new URLSearchParams(window.location.search);
    const konsultasiId = urlParams.get('konsultasi_id');
    
    if (konsultasiId) {
        const element = document.getElementById('konsultasi-' + konsultasiId);
        if (element) {
            element.scrollIntoView({ behavior: 'smooth', block: 'center' });
            element.classList.add('bg-blue-50');
            setTimeout(() => {
                element.classList.remove('bg-blue-50');
                element.classList.add('bg-white');
            }, 3000);
            
            // Tampilkan detail konsultasi
            showDetailModal(konsultasiId);
        }
    }
});

function showDetailModal(id) {
    console.log('showDetailModal called with id:', id);
    
    // Tampilkan loading
    Swal.fire({
        title: 'Memuat data...',
        html: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Mengambil data terlebih dahulu untuk cek status
    const headers = {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    };
    
    fetch(`/api/konsultasi/${id}`, {
        method: 'GET',
        headers: headers,
        credentials: 'same-origin' // Mengirim cookies untuk autentikasi
    })
        .then(response => {
            console.log('API response status:', response.status);
            
            // Cek apakah response adalah HTML (kemungkinan redirect ke login)
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('text/html')) {
                window.location.href = '/login';
                throw new Error('Sesi Anda telah berakhir. Silakan login kembali.');
            }
            
            if (!response.ok) {
                // Jika status 401 (Unauthorized), artinya pengguna perlu login ulang
                if (response.status === 401) {
                    window.location.href = '/login';
                    throw new Error('Sesi Anda telah berakhir. Silakan login kembali.');
                }
                throw new Error(`API Error: ${response.status} - ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('API response data:', data);
            // Tutup loading
            Swal.close();
            
            // Jika status Selesai atau Terlambat, buka chat room
            if (data.status === 'Selesai' || data.status === 'Terlambat') {
                console.log('Navigating to chat room:', `/chat/${id}`);
                window.location.href = `/chat/${id}`;
                return;
            }
            
            // Jika bukan, tampilkan modal detail seperti biasa
            console.log('Showing detail modal for konsultasi:', id);
            document.getElementById('detailModal').classList.remove('hidden');
            
            // Isi modal dengan data yang diterima
            document.getElementById('modal-tanggal').textContent = data.tanggal_formatted;
            document.getElementById('modal-waktu').textContent = `${data.jam_mulai} - ${data.jam_selesai}`;
            
            // Tampilkan avatar mahasiswa
            const mahasiswaAvatar = document.getElementById('modal-mahasiswa-avatar');
            if (data.mahasiswa) {
                const initial = data.mahasiswa.name.charAt(0).toUpperCase();
                mahasiswaAvatar.textContent = initial;
            }
            
            // Tampilkan informasi mahasiswa
            document.getElementById('modal-mahasiswa').textContent = data.mahasiswa ? data.mahasiswa.name : '-';
            document.getElementById('modal-mahasiswa-info').textContent = data.mahasiswa && data.mahasiswa.fakultas ? data.mahasiswa.fakultas : 'Mahasiswa Kedokteran';
            document.getElementById('modal-keluhan').textContent = data.keluhan;
            document.getElementById('modal-diagnosa').textContent = data.diagnosa || '-';
            document.getElementById('modal-catatan').textContent = data.catatan || '-';
            
            // Tampilkan status konsultasi dengan warna sesuai
            const statusElement = document.getElementById('modal-status');
            let statusClass = 'bg-blue-100 text-blue-800';
            
            if (data.status === 'Dibatalkan') {
                statusClass = 'bg-gray-100 text-gray-800';
            } else if (data.status === 'Ditolak') {
                statusClass = 'bg-red-100 text-red-800';
            } else if (data.status === 'Selesai') {
                statusClass = 'bg-green-100 text-green-800';
            }
            
            statusElement.innerHTML = `<span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">${data.status}</span>`;
            
            // Isi resep jika ada
            const resepContainer = document.getElementById('modal-resep');
            resepContainer.innerHTML = '';
            if (data.resep && data.resep.length > 0 && data.resep[0] !== '') {
                const ul = document.createElement('ul');
                ul.className = 'list-disc pl-5 space-y-1';
                data.resep.forEach(item => {
                    if (item.trim()) {
                        const li = document.createElement('li');
                        li.textContent = item;
                        ul.appendChild(li);
                    }
                });
                resepContainer.appendChild(ul);
            } else {
                resepContainer.textContent = 'Tidak ada resep';
            }
            
            // Isi saran jika ada
            const saranContainer = document.getElementById('modal-saran');
            saranContainer.innerHTML = '';
            if (data.saran && data.saran.length > 0 && data.saran[0] !== '') {
                const ul = document.createElement('ul');
                ul.className = 'list-disc pl-5 space-y-1';
                data.saran.forEach(item => {
                    if (item.trim()) {
                        const li = document.createElement('li');
                        li.textContent = item;
                        ul.appendChild(li);
                    }
                });
                saranContainer.appendChild(ul);
            } else {
                saranContainer.textContent = 'Tidak ada saran';
            }
        })
        .catch(error => {
            console.error('Error fetching konsultasi details:', error);
            
            // Tutup loading dan tampilkan error
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: 'Tidak dapat memuat detail konsultasi. ' + error.message,
                confirmButtonText: 'Kembali',
                confirmButtonColor: '#3085d6',
                showCancelButton: false,
            }).then((result) => {
                // Jika pesan error berisi "Sesi Anda telah berakhir", arahkan ke halaman login
                if (error.message && error.message.includes('Sesi Anda telah berakhir')) {
                    window.location.href = '/login';
                }
            });
        });
}

function hideDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}
</script>
@endsection 