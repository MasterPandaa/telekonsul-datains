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

<!-- Success alert will be shown via SweetAlert2 -->

<!-- Dashboard Ringkasan -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-md p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold">Konsultasi Aktif</h3>
                <p class="text-2xl font-bold mt-2">{{ $konsultasiAktif->count() }}</p>
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

<!-- Konsultasi Aktif -->
<div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Konsultasi Aktif
        </h2>
    </div>
    
    @if($konsultasiAktif->isEmpty())
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
                    @foreach($konsultasiAktif as $item)
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
                                @elseif($item->status === 'Ditolak')
                                    bg-red-100 text-red-800
                                @elseif($item->status === 'Dibatalkan')
                                    bg-gray-100 text-gray-800
                                @elseif($item->status === 'Selesai')
                                    bg-blue-100 text-blue-800
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
                                @elseif($item->status === 'Ditolak')
                                    <button type="button" class="text-blue-600 hover:text-blue-900 border border-blue-300 rounded-md px-2 py-1 text-center hover:bg-blue-50 transition" onclick="lihatAlasanDitolak({{ $item->id }}, '{{ $item->alasan_tolak ?? 'Tidak ada alasan yang diberikan' }}')">
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Lihat Alasan
                                    </button>
                                @elseif($item->status === 'Dibatalkan')
                                    <button type="button" class="text-blue-600 hover:text-blue-900 border border-blue-300 rounded-md px-2 py-1 text-center hover:bg-blue-50 transition" onclick="lihatAlasanBatal({{ $item->id }}, '{{ $item->alasan_batal ?? 'Tidak ada alasan yang diberikan' }}')">
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Lihat Alasan
                                    </button>
                                @elseif($item->status === 'Terlambat')
                                    <button type="button" class="text-orange-600 hover:text-orange-900 border border-orange-300 rounded-md px-2 py-1 text-center hover:bg-orange-50 transition" onclick="buatKonsultasiBaru()">
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Buat Konsultasi Baru
                                    </button>
                                @elseif($item->status === 'Selesai')
                                    <a href="{{ route('chat.create', $item->id) }}" class="text-blue-600 hover:text-blue-900 border border-blue-300 rounded-md px-2 py-1 text-center hover:bg-blue-50 transition">
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        Lihat Chat
                                    </a>
                                @elseif($item->status === 'Pergantian Sesi')
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <button type="button" class="text-green-600 hover:text-green-900 border border-green-300 rounded-md px-2 py-1 text-center hover:bg-green-50 transition" onclick="terimaGantiSesi({{ $item->id }})">
                                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Terima
                                        </button>
                                        <button type="button" class="text-red-600 hover:text-red-900 border border-red-300 rounded-md px-2 py-1 text-center hover:bg-red-50 transition" onclick="tolakGantiSesi({{ $item->id }})">
                                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Tolak
                                        </button>
                                    </div>
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

<!-- Riwayat Konsultasi -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Riwayat Konsultasi
        </h2>
        <a href="{{ route('pasien.riwayat.index') }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
            Lihat Semua
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>
    
    @if($riwayatKonsultasi->isEmpty())
    <div class="p-6 text-center">
        <div class="py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum Ada Riwayat Konsultasi</h3>
            <p class="mt-1 text-sm text-gray-500">Riwayat konsultasi Anda akan muncul di sini setelah selesai berkonsultasi.</p>
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnosa</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($riwayatKonsultasi as $item)
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
                                @endif
                            ">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($item->status === 'Selesai')
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
                                    <button type="button" class="text-blue-600 hover:text-blue-900 border border-blue-300 rounded-md px-2 py-1 text-center hover:bg-blue-50 transition" onclick="beriRating({{ $item->id }})">
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                        Beri Rating
                                    </button>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($item->status === 'Selesai')
                                <a href="{{ route('chat.create', $item->id) }}" class="text-blue-600 hover:text-blue-900 border border-blue-300 rounded-md px-2 py-1 hover:bg-blue-50 transition inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    Lihat Chat
                                </a>
                            @elseif($item->status === 'Ditolak')
                                <button type="button" class="text-blue-600 hover:text-blue-900 border border-blue-300 rounded-md px-2 py-1 text-center hover:bg-blue-50 transition" onclick="lihatAlasanDitolak({{ $item->id }}, '{{ $item->alasan_tolak ?? 'Tidak ada alasan yang diberikan' }}')">
                                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Lihat Alasan
                                </button>
                            @elseif($item->status === 'Dibatalkan')
                                <button type="button" class="text-blue-600 hover:text-blue-900 border border-blue-300 rounded-md px-2 py-1 text-center hover:bg-blue-50 transition" onclick="lihatAlasanBatal({{ $item->id }}, '{{ $item->alasan_batal ?? 'Tidak ada alasan yang diberikan' }}')">
                                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                    Lihat Alasan
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

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

        // Update countdown timers
        const countdownTimers = document.querySelectorAll('.countdown-timer');
        
        function updateTimers() {
            countdownTimers.forEach(function(timer) {
                const targetTime = parseInt(timer.dataset.target);
                const now = new Date().getTime();
                const difference = targetTime - now;
                
                if (difference <= 0) {
                    timer.innerHTML = 'Konsultasi sudah dimulai';
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
            });
        }
        
        // Update timers every second
        setInterval(updateTimers, 1000);
        updateTimers(); // Initial update
    });

    // Fungsi untuk konfirmasi pembatalan konsultasi
    function konfirmasiBatalkan(id, tanggal, jam) {
        Swal.fire({
            title: '<div class="flex items-center"><svg class="w-6 h-6 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>Batalkan Konsultasi</div>',
            html: `
                <div class="bg-red-50 p-4 rounded-lg border-l-4 border-red-500 mb-4">
                    <div class="text-left text-red-700">
                        <p class="font-medium">Anda akan membatalkan konsultasi pada:</p>
                        <p class="mt-2 font-semibold">${tanggal}</p>
                        <p class="font-semibold">${jam}</p>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-left text-sm font-medium text-gray-700 mb-1">Alasan Pembatalan:</label>
                    <textarea id="alasan-batal" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500" rows="3" placeholder="Masukkan alasan pembatalan..."></textarea>
                </div>
                <div class="text-xs text-gray-500 mt-2">Pembatalan konsultasi akan memberitahu mahasiswa yang bersangkutan.</div>
            `,
            icon: false,
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: '<span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Ya, Batalkan</span>',
            cancelButtonText: '<span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>Kembali</span>',
            customClass: {
                popup: 'rounded-lg shadow-xl border border-gray-200',
                title: 'text-lg font-bold text-gray-800 border-b pb-3',
                content: 'pt-4',
                confirmButton: 'rounded-md',
                cancelButton: 'rounded-md'
            },
            preConfirm: () => {
                const alasan = document.getElementById('alasan-batal').value;
                if (!alasan) {
                    Swal.showValidationMessage('<div class="flex items-center text-red-600"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>Anda harus memberikan alasan pembatalan</div>');
                    return false;
                }
                return alasan;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById(`form-batalkan-${id}`);
                
                // Tambahkan input alasan
                const alasanInput = document.createElement('input');
                alasanInput.type = 'hidden';
                alasanInput.name = 'alasan_batal';
                alasanInput.value = result.value;
                
                form.appendChild(alasanInput);
                form.submit();
            }
        });
    }

    // Fungsi untuk melihat alasan ditolak
    function lihatAlasanDitolak(id, alasan) {
        Swal.fire({
            title: '<div class="flex items-center"><svg class="w-6 h-6 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>Alasan Penolakan</div>',
            html: `
                <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-red-500 mb-4">
                    <div class="text-left text-gray-700">
                        <p class="font-semibold mb-2">Detail Penolakan:</p>
                        <p class="italic">"${alasan}"</p>
                    </div>
                </div>
                <div class="text-xs text-gray-500 mt-2">Permintaan konsultasi ini telah ditolak oleh mahasiswa</div>
            `,
            icon: false,
            showConfirmButton: true,
            confirmButtonColor: '#3B82F6',
            confirmButtonText: '<span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Saya Mengerti</span>',
            customClass: {
                popup: 'rounded-lg shadow-xl border border-gray-200',
                title: 'text-lg font-bold text-gray-800 border-b pb-3',
                content: 'pt-4',
                confirmButton: 'rounded-md'
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
                <div class="text-xs text-gray-500 mt-2">Permintaan konsultasi ini telah dibatalkan</div>
            `,
            icon: false,
            showConfirmButton: true,
            confirmButtonColor: '#3B82F6',
            confirmButtonText: '<span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Saya Mengerti</span>',
            customClass: {
                popup: 'rounded-lg shadow-xl border border-gray-200',
                title: 'text-lg font-bold text-gray-800 border-b pb-3',
                content: 'pt-4',
                confirmButton: 'rounded-md'
            }
        });
    }

    // Fungsi untuk membuat konsultasi baru
    function buatKonsultasiBaru() {
        Swal.fire({
            title: '<div class="flex items-center"><svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>Buat Konsultasi Baru?</div>',
            html: `
                <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500 mb-4">
                    <div class="text-left text-blue-700">
                        <p class="font-medium">Apakah Anda ingin membuat permintaan konsultasi baru?</p>
                        <p class="text-sm mt-2">Anda akan diarahkan ke halaman pembuatan konsultasi baru.</p>
                    </div>
                </div>
            `,
            icon: false,
            showCancelButton: true,
            confirmButtonColor: '#F97316',
            cancelButtonColor: '#6B7280',
            confirmButtonText: '<span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Ya, Buat Baru</span>',
            cancelButtonText: '<span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>Tidak</span>',
            customClass: {
                popup: 'rounded-lg shadow-xl border border-gray-200',
                title: 'text-lg font-bold text-gray-800 border-b pb-3',
                content: 'pt-4',
                confirmButton: 'rounded-md',
                cancelButton: 'rounded-md'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ route("pasien.konsultasi.create") }}';
            }
        });
    }

    // Fungsi untuk menerima pergantian sesi
    function terimaGantiSesi(id) {
                Swal.fire({
            title: '<div class="flex items-center"><svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>Terima Pergantian Sesi</div>',
            html: `
                <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500 mb-4">
                    <div class="text-left text-green-700">
                        <p class="font-medium">Anda akan menerima permintaan pergantian sesi konsultasi.</p>
                        <p class="text-sm mt-2">Jadwal konsultasi akan diperbarui sesuai dengan waktu yang diminta oleh mahasiswa.</p>
                    </div>
                </div>
                <div class="text-xs text-gray-500 mt-2">Pastikan Anda tersedia pada jadwal baru yang diminta.</div>
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
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ url("pasien/konsultasi") }}/' + id + '/terima-ganti-sesi';
                
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

    // Fungsi untuk menolak pergantian sesi
    function tolakGantiSesi(id) {
        Swal.fire({
            title: '<div class="flex items-center"><svg class="w-6 h-6 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>Tolak Pergantian Sesi</div>',
            html: `
                <div class="bg-red-50 p-4 rounded-lg border-l-4 border-red-500 mb-4">
                    <div class="text-left text-red-700">
                        <p class="font-medium">Anda akan menolak permintaan pergantian sesi konsultasi.</p>
                        <p class="text-sm mt-2">Konsultasi akan dibatalkan dan mahasiswa akan diberitahu.</p>
                    </div>
                </div>
                <div class="text-xs text-gray-500 mt-2">Penolakan pergantian sesi akan membatalkan konsultasi ini secara permanen.</div>
            `,
            icon: false,
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: '<span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Ya, Tolak</span>',
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
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ url("pasien/konsultasi") }}/' + id + '/tolak-ganti-sesi';
                
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
    
    // Fungsi untuk memberikan rating
    function beriRating(id) {
        let currentRating = 0;
        
        Swal.fire({
            title: '<div class="flex items-center"><svg class="w-6 h-6 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>Berikan Rating</div>',
            html: `
                <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500 mb-4">
                    <div class="text-left text-blue-700">
                        <p class="font-medium">Berikan penilaian untuk konsultasi ini</p>
                        <p class="text-sm mt-2">Rating Anda akan membantu meningkatkan kualitas layanan kami</p>
                    </div>
                </div>
                <div class="flex justify-center my-4" id="star-rating">
                    <svg class="w-8 h-8 mx-1 cursor-pointer text-gray-300 hover:text-yellow-400" data-rating="1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <svg class="w-8 h-8 mx-1 cursor-pointer text-gray-300 hover:text-yellow-400" data-rating="2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <svg class="w-8 h-8 mx-1 cursor-pointer text-gray-300 hover:text-yellow-400" data-rating="3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <svg class="w-8 h-8 mx-1 cursor-pointer text-gray-300 hover:text-yellow-400" data-rating="4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <svg class="w-8 h-8 mx-1 cursor-pointer text-gray-300 hover:text-yellow-400" data-rating="5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                </div>
                <div class="text-center mb-4">
                    <span id="rating-text" class="text-lg font-medium">Pilih rating</span>
                </div>
                <div class="mb-4">
                    <label class="block text-left text-sm font-medium text-gray-700 mb-1">Komentar (opsional):</label>
                    <textarea id="komentar-rating" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" rows="3" placeholder="Berikan komentar tentang konsultasi ini..."></textarea>
                </div>
            `,
            icon: false,
            showCancelButton: true,
            confirmButtonColor: '#3B82F6',
            cancelButtonColor: '#6B7280',
            confirmButtonText: '<span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Kirim Rating</span>',
            cancelButtonText: '<span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>Batal</span>',
            customClass: {
                popup: 'rounded-lg shadow-xl border border-gray-200',
                title: 'text-lg font-bold text-gray-800 border-b pb-3',
                content: 'pt-4',
                confirmButton: 'rounded-md',
                cancelButton: 'rounded-md'
            },
            didOpen: () => {
                const stars = document.querySelectorAll('#star-rating svg');
                const ratingText = document.getElementById('rating-text');
                const ratingTexts = ['Sangat Buruk', 'Buruk', 'Cukup', 'Baik', 'Sangat Baik'];
                
                stars.forEach(star => {
                    star.addEventListener('click', function() {
                        const rating = parseInt(this.getAttribute('data-rating'));
                        currentRating = rating;
                        
                        // Update tampilan bintang
                        stars.forEach((s, index) => {
                            if (index < rating) {
                                s.classList.remove('text-gray-300');
                                s.classList.add('text-yellow-400');
                            } else {
                                s.classList.remove('text-yellow-400');
                                s.classList.add('text-gray-300');
                            }
                        });
                        
                        // Update teks rating
                        ratingText.textContent = ratingTexts[rating - 1];
                    });
                    
                    star.addEventListener('mouseover', function() {
                        const rating = parseInt(this.getAttribute('data-rating'));
                        
                        // Highlight bintang saat hover
                        stars.forEach((s, index) => {
                            if (index < rating) {
                                s.classList.add('text-yellow-400');
                                s.classList.remove('text-gray-300');
                            }
                        });
                    });
                    
                    star.addEventListener('mouseout', function() {
                        // Kembalikan ke status sebelumnya saat mouse keluar
                        stars.forEach((s, index) => {
                            if (index < currentRating) {
                                s.classList.add('text-yellow-400');
                                s.classList.remove('text-gray-300');
                            } else {
                                s.classList.remove('text-yellow-400');
                                s.classList.add('text-gray-300');
                            }
                        });
                    });
                });
            },
            preConfirm: () => {
                if (currentRating === 0) {
                    Swal.showValidationMessage('<div class="flex items-center text-red-600"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>Silakan pilih rating terlebih dahulu</div>');
                    return false;
                }
                
                const komentar = document.getElementById('komentar-rating').value;
                return { rating: currentRating, komentar: komentar };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form untuk memberikan rating
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ url("pasien/konsultasi") }}/' + id + '/rating';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const ratingInput = document.createElement('input');
                ratingInput.type = 'hidden';
                ratingInput.name = 'rating';
                ratingInput.value = result.value.rating;
                
                const komentarInput = document.createElement('input');
                komentarInput.type = 'hidden';
                komentarInput.name = 'komentar_rating';
                komentarInput.value = result.value.komentar;
                
                form.appendChild(csrfToken);
                form.appendChild(ratingInput);
                form.appendChild(komentarInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush
@endsection 