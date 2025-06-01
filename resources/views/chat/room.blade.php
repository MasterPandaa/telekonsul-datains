<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telekonsultasi - {{ Auth::user()->role === 'mahasiswa' ? $konsultasi->pasien->nama : $konsultasi->mahasiswa->name }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            overflow: hidden;
        }
        .chat-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .chat-area {
            flex: 1;
            overflow-y: auto;
        }
        .message-bubble-left {
            border-radius: 20px 20px 20px 4px;
        }
        .message-bubble-right {
            border-radius: 20px 20px 4px 20px;
        }
        .typing-indicator {
            display: inline-flex;
        }
        .typing-indicator span {
            width: 8px;
            height: 8px;
            margin: 0 1px;
            background-color: #9CA3AF;
            border-radius: 50%;
            display: inline-block;
            opacity: 0.6;
        }
        .typing-indicator span:nth-child(1) {
            animation: bouncing 1s infinite 0.2s;
        }
        .typing-indicator span:nth-child(2) {
            animation: bouncing 1s infinite 0.4s;
        }
        .typing-indicator span:nth-child(3) {
            animation: bouncing 1s infinite 0.6s;
        }
        @keyframes bouncing {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        .pulse-border {
            animation: pulse-border 0.5s;
        }
        @keyframes pulse-border {
            0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.5); }
            70% { box-shadow: 0 0 0 5px rgba(59, 130, 246, 0); }
            100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
        }
        .counter-badge {
            position: absolute;
            right: 10px;
            bottom: 10px;
            font-size: 0.7rem;
            color: #9CA3AF;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 2px 8px;
            border-radius: 10px;
            pointer-events: none;
            transition: all 0.2s;
        }
        textarea:focus + .counter-badge {
            background-color: rgba(59, 130, 246, 0.1);
            color: rgba(59, 130, 246, 0.8);
        }
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #c5c5c5;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>
<body class="antialiased bg-gradient-to-br from-blue-50 to-indigo-50">
<div class="chat-container">
        <div class="bg-white shadow-lg h-full flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                @if(isset($isTerlambat) && $isTerlambat)
                <div class="bg-yellow-500/20 border-l-4 border-yellow-400 px-4 py-2 mb-3 rounded-r-lg animate__animated animate__fadeIn">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-yellow-300 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white">Anda terlambat masuk ke sesi konsultasi</p>
                            <p class="text-xs text-yellow-100 mt-1">Sisa waktu konsultasi: {{ $sisaWaktu }} menit. Status telah diubah menjadi "Terlambat".</p>
                        </div>
                    </div>
                </div>
                @endif
                
                @if($konsultasi->status === 'Selesai' || $konsultasi->status === 'Terlambat')
                <div class="bg-blue-500/20 border-l-4 border-blue-400 px-4 py-2 mb-3 rounded-r-lg animate__animated animate__fadeIn">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-blue-300 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <p class="ml-3 text-sm text-white">
                            Konsultasi ini telah selesai. Anda hanya dapat melihat riwayat pesan.
                        </p>
                    </div>
                </div>
                @endif

                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr(Auth::user()->role === 'mahasiswa' ? $konsultasi->pasien->nama : $konsultasi->mahasiswa->name, 0, 1)) }}
                        </div>
                    <div>
                            <h2 class="text-xl font-semibold">Konsultasi dengan {{ Auth::user()->role === 'mahasiswa' ? $konsultasi->pasien->nama : $konsultasi->mahasiswa->name }}</h2>
                            <div class="flex items-center text-sm text-blue-100">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ $konsultasi->tanggal->isoFormat('D MMMM Y') }}</span>
                                <span class="mx-2">|</span>
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ $konsultasi->jam_mulai }} - {{ $konsultasi->jam_selesai }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div id="timer" class="px-4 py-2 bg-white/10 rounded-full text-sm font-medium flex items-center">
                            <svg class="w-4 h-4 mr-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Sisa waktu: <span id="time-remaining" class="ml-1 font-bold">15:00</span>
                        </div>
                        <div class="flex justify-end">
                            @if($konsultasi->status === 'Selesai' || $konsultasi->status === 'Terlambat')
                                <a href="{{ Auth::user()->role === 'mahasiswa' ? route('mahasiswa.konsultasi.index') : route('pasien.konsultasi.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali
                                </a>
                            @else
                        <form action="{{ route('chat.end', $chatRoom) }}" method="POST">
                            @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors shadow-sm {{ $konsultasi->status === 'Berlangsung' ? '' : 'opacity-50 cursor-not-allowed' }}" {{ $konsultasi->status === 'Berlangsung' ? '' : 'disabled' }}>
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                Akhiri Konsultasi
                            </button>
                        </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col md:flex-row flex-1 overflow-hidden">
                <!-- Sidebar Informasi -->
                <div class="w-full md:w-72 p-4 bg-gray-50 border-r border-gray-100 overflow-y-auto flex-shrink-0">
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-2">Detail Konsultasi</h3>
                        <div class="bg-white rounded-lg p-3 shadow-sm">
                            <div class="mb-2">
                                <div class="text-xs text-gray-500">Keluhan:</div>
                                <div class="text-sm font-medium">{{ $konsultasi->keluhan }}</div>
                            </div>
                            <div class="mb-2">
                                <div class="text-xs text-gray-500">Status:</div>
                                <div class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                    @if($konsultasi->status === 'Terkonfirmasi')
                                        bg-green-100 text-green-800
                                    @elseif($konsultasi->status === 'Terlambat')
                                        bg-orange-100 text-orange-800
                                    @elseif($konsultasi->status === 'Berlangsung')
                                        bg-purple-100 text-purple-800
                                    @else
                                        bg-blue-100 text-blue-800
                                    @endif">
                                    {{ $konsultasi->status }}
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="text-xs text-gray-500">Keterangan Tambahan:</div>
                                @if($konsultasi->keterangan)
                                <div class="text-sm mt-1 bg-blue-50 p-2 rounded-md border-l-2 border-blue-300">
                                    <div class="flex">
                                        <svg class="w-4 h-4 text-blue-500 mt-0.5 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-gray-700">{{ $konsultasi->keterangan }}</span>
                                    </div>
                                </div>
                                @else
                                <div class="text-sm text-gray-500 italic">Tidak ada keterangan tambahan</div>
                                @endif
                            </div>
                            @if($konsultasi->status === 'Terlambat')
                            <div class="mb-0 mt-3 bg-orange-50 p-2 rounded-md">
                                <div class="text-xs text-orange-700 flex items-start">
                                    <svg class="w-4 h-4 text-orange-500 mr-1 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Anda terlambat memasuki room chat. Waktu konsultasi telah dikurangi.</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($konsultasi->status === 'Berlangsung')
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-2">Panduan Singkat</h3>
                        <div class="bg-white rounded-lg p-3 shadow-sm">
                            <ul class="text-xs text-gray-600 space-y-2">
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-green-500 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Sesi konsultasi berlangsung sesuai jadwal.</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-green-500 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Keterlambatan > 15 menit akan mengubah status menjadi "Terlambat".</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-green-500 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Pesan akan otomatis diperbarui setiap beberapa detik.</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-green-500 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Tekan Enter untuk mengirim pesan.</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-green-500 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Konsultasi berakhir saat waktu habis atau tombol "Akhiri Konsultasi" ditekan.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @endif
                    
                    @if(Auth::user()->role === 'mahasiswa' && ($konsultasi->status === 'Selesai' || $konsultasi->status === 'Terlambat'))
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-2">HASIL DIAGNOSA</h3>
                        <div class="bg-green-50 p-4 rounded-lg shadow-sm border border-green-100 animate__animated animate__fadeIn">
                            <div class="flex items-center mb-3">
                                <div class="bg-green-500 p-1.5 rounded-full mr-2 shadow-sm">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-800">Diagnosa Mahasiswa</h3>
                            </div>
                            
                            <form action="{{ route('mahasiswa.konsultasi.diagnosa', $konsultasi->id) }}" method="POST" class="space-y-3">
                                @csrf
                                <div class="mb-3">
                                    <div class="text-xs font-medium text-gray-600 mb-1 flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                        Diagnosa:
                                    </div>
                                    <div class="relative">
                                        <textarea id="diagnosa" name="diagnosa" rows="3" maxlength="500" class="w-full rounded-lg border-green-200 bg-white shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-sm transition-shadow duration-200" placeholder="Masukkan diagnosa pasien..." required>{{ $konsultasi->diagnosa }}</textarea>
                                        <span id="diagnosa-counter" class="counter-badge">{{ strlen($konsultasi->diagnosa ?? '') }}/500</span>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="text-xs font-medium text-gray-600 mb-1 flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Catatan Medis:
                                    </div>
                                    <div class="relative">
                                        <textarea id="catatan" name="catatan" rows="3" maxlength="1000" class="w-full rounded-lg border-green-200 bg-white shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-sm transition-shadow duration-200" placeholder="Masukkan catatan medis jika ada...">{{ $konsultasi->catatan }}</textarea>
                                        <span id="catatan-counter" class="counter-badge">{{ strlen($konsultasi->catatan ?? '') }}/1000</span>
                                    </div>
                                </div>
                                
                                <div class="flex justify-end pt-1">
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm transition-all duration-300">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $konsultasi->diagnosa ? 'Update Diagnosa' : 'Simpan Diagnosa' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                    
                    @if(Auth::user()->role === 'pasien' && $konsultasi->diagnosa)
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-2">Hasil Diagnosa</h3>
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-lg shadow-md border border-green-100 animate__animated animate__fadeIn">
                            <div class="flex items-center mb-3">
                                <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-1.5 rounded-full mr-2 shadow-md">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-800">Diagnosa Mahasiswa</h3>
                            </div>
                            
                            <div class="mb-3">
                                <div class="text-xs font-medium text-gray-600 mb-1 flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    Diagnosa:
                                </div>
                                <div class="bg-white text-sm text-gray-700 p-3 rounded border border-green-200 whitespace-pre-wrap">{{ $konsultasi->diagnosa }}</div>
                            </div>
                            
                            @if($konsultasi->catatan)
                            <div>
                                <div class="text-xs font-medium text-gray-600 mb-1 flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Catatan Medis:
                                </div>
                                <div class="bg-white text-sm text-gray-700 p-3 rounded border border-green-200 whitespace-pre-wrap">{{ $konsultasi->catatan }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <div class="w-full flex flex-col flex-1 overflow-hidden">
            <!-- Chat Messages -->
                    <div class="flex-1 overflow-y-auto p-6 bg-gradient-to-r from-blue-50 to-indigo-50" id="chat-messages">
                <!-- Messages will be loaded here -->
                        <div class="flex justify-center mb-4">
                            <div class="bg-gray-200 text-gray-600 text-xs px-3 py-1 rounded-full inline-block">
                                Sesi konsultasi dimulai
                            </div>
                        </div>
            </div>

            <!-- Message Input -->
                    <div class="px-6 py-4 border-t border-gray-200 bg-white">
                        @if($konsultasi->status === 'Selesai')
                            <div class="rounded-full bg-gray-100 px-4 py-3 text-center text-gray-500">
                                <p class="text-sm">Konsultasi telah selesai. Anda tidak dapat mengirim pesan baru.</p>
                            </div>
                        @elseif($konsultasi->status === 'Terlambat')
                            <div class="rounded-full bg-orange-100 px-4 py-3 text-center text-orange-700">
                                <p class="text-sm">Konsultasi terlambat. Anda tidak dapat mengirim pesan baru.</p>
                            </div>
                        @else
                        <form id="message-form" class="flex items-center space-x-3">
                            <button type="button" id="emoji-button" class="text-gray-500 hover:text-blue-500 focus:outline-none transition-colors p-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </button>
                            <div class="flex-1 relative">
                                <input type="text" id="message-input" class="w-full px-4 py-3 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pr-10 shadow-sm" placeholder="Ketik pesan...">
                                <div id="typing-indicator" class="absolute left-4 bottom-3 text-xs text-gray-500 hidden">
                                    <div class="typing-indicator">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="inline-flex items-center px-5 py-3 border border-transparent text-sm font-medium rounded-full text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-md transition-colors">
                                <span class="hidden md:inline mr-1.5">Kirim</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@4.6.2/dist/index.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chat-messages');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const timeRemaining = document.getElementById('time-remaining');
    const typingIndicator = document.getElementById('typing-indicator');
    
    // Set timer for 15 minutes
    let timeLeft = 15 * 60; // 15 minutes in seconds
    
    // Jika waktu tersisa dari server tersedia, gunakan waktu tersebut
    @if(isset($sisaWaktu))
        timeLeft = {{ $sisaWaktu }} * 60; // convert menit ke detik
    @endif
    
    // Jika status konsultasi sudah selesai, nonaktifkan timer
    @if($konsultasi->status === 'Selesai' || $konsultasi->status === 'Terlambat')
    timeRemaining.textContent = 'Konsultasi selesai';
    document.getElementById('timer').classList.add('bg-gray-500/20');
    @else
    // Tampilkan sisa waktu awal
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    timeRemaining.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    
    // Jika terlambat, tampilkan indikator visual
    @if(isset($isTerlambat) && $isTerlambat)
    timeRemaining.classList.add('text-orange-300');
    document.getElementById('timer').classList.add('bg-orange-500/20');
    @endif
    
    const timer = setInterval(() => {
        timeLeft--;
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timeRemaining.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        // Animate timer when less than 1 minute
        if (timeLeft <= 60) {
            timeRemaining.classList.add('text-red-300');
            if (!document.getElementById('timer').classList.contains('animate-pulse')) {
                document.getElementById('timer').classList.add('bg-red-500/20');
            }
        }
        
        if (timeLeft <= 0) {
            clearInterval(timer);
            @if(isset($messageForm) && $messageForm)
            messageForm.style.display = 'none';
            @endif
            timeRemaining.textContent = 'Waktu habis';
            
            // Tampilkan pesan waktu habis
            const timeUpMessage = document.createElement('div');
            timeUpMessage.className = 'flex justify-center my-4 animate__animated animate__fadeIn';
            timeUpMessage.innerHTML = `
                <div class="bg-red-100 text-red-800 text-sm px-4 py-2 rounded-lg inline-block">
                    <p class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Waktu konsultasi telah berakhir
                    </p>
                </div>
            `;
            chatMessages.appendChild(timeUpMessage);
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            // Automatically end chat when time is up
            setTimeout(() => {
            fetch(`{{ route('chat.end', $chatRoom) }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => {
                window.location.href = '{{ Auth::user()->role === "mahasiswa" ? route("mahasiswa.konsultasi.index") : route("pasien.konsultasi.index") }}';
            });
            }, 3000);
        }
    }, 1000);
    @endif

    // Emoji picker
    const emojiButton = document.getElementById('emoji-button');
    const picker = new EmojiButton({
        position: 'top-start',
        theme: 'light',
        autoHide: false,
        rows: 4,
        recentsCount: 16,
        emojiSize: '1.5rem'
    });

    picker.on('emoji', emoji => {
        messageInput.value += emoji;
        messageInput.focus();
    });

    emojiButton.addEventListener('click', () => {
        picker.togglePicker(emojiButton);
    });
    
    // Load messages
    let lastMessageId = 0;
    function loadMessages() {
        fetch(`{{ route('chat.messages', $chatRoom) }}`)
            .then(response => response.json())
            .then(messages => {
                // Check if we have new messages
                if (messages.length > 0 && messages[messages.length-1].id !== lastMessageId) {
                    const isFirstLoad = lastMessageId === 0;
                    lastMessageId = messages[messages.length-1].id;
                    
                    let html = '';
                    if (isFirstLoad && messages.length > 0) {
                        html = '<div class="flex justify-center mb-4"><div class="bg-gray-200 text-gray-600 text-xs px-3 py-1 rounded-full inline-block">Sesi konsultasi dimulai</div></div>';
                    }
                    
                    html += messages.map(message => {
                        const isCurrentUser = message.user_id === {{ Auth::id() }};
                        const time = new Date(message.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                        const animationClass = message.id === lastMessageId && !isFirstLoad ? 'animate__animated animate__fadeIn' : '';
                        
                        return `
                            <div class="mb-6 ${isCurrentUser ? 'flex justify-end' : 'flex justify-start'} ${animationClass}">
                                <div class="flex flex-col ${isCurrentUser ? 'items-end' : 'items-start'} max-w-[80%]">
                                    <div class="flex items-end ${isCurrentUser ? 'flex-row-reverse' : ''}">
                                        ${!isCurrentUser ? `
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-400 to-blue-500 flex-shrink-0 flex items-center justify-center text-white text-xs font-bold mr-2 shadow-md">
                                            ${message.user.name.charAt(0).toUpperCase()}
                                        </div>
                                        ` : ''}
                                        <div class="px-4 py-3 ${isCurrentUser ? 'message-bubble-right bg-gradient-to-r from-blue-500 to-indigo-600 text-white' : 'message-bubble-left bg-white text-gray-800 border border-gray-100'} shadow-md">
                                            <p class="text-sm whitespace-pre-wrap leading-relaxed">${message.message}</p>
                                        </div>
                                        ${isCurrentUser ? `
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex-shrink-0 flex items-center justify-center text-white text-xs font-bold ml-2 shadow-md">
                                            ${message.user.name.charAt(0).toUpperCase()}
                                        </div>
                                        ` : ''}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1.5 ${isCurrentUser ? 'text-right mr-11' : 'ml-11'} flex items-center ${isCurrentUser ? 'justify-end' : ''}">
                                        <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        ${time}
                                    </div>
                        </div>
                    </div>
                        `;
                    }).join('');
                    
                    if (!isFirstLoad) {
                        // Jika bukan load pertama, tambahkan ke akhir chat
                        const lastMessages = messages.filter(m => m.id > lastMessageId);
                        lastMessages.forEach(message => {
                            const isCurrentUser = message.user_id === {{ Auth::id() }};
                            const time = new Date(message.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                            
                            const messageDiv = document.createElement('div');
                            messageDiv.className = `mb-4 ${isCurrentUser ? 'flex justify-end' : 'flex justify-start'} animate__animated animate__fadeIn`;
                            
                            // Isi messageDiv dengan HTML untuk pesan
                            // Sama seperti HTML dalam template literal di atas
                        });
                    } else {
                        // Jika load pertama, set semua konten
                        chatMessages.innerHTML = html;
                    }
                    
                    // Scroll ke bawah untuk melihat pesan terbaru
                chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            });
    }
    
    // Typing indicator
    let typingTimeout;
    messageInput.addEventListener('input', function() {
        // Tampilkan indikator mengetik saat user mengetik
        // Implementasi hanya visual karena tidak ada API realtime
        clearTimeout(typingTimeout);
        typingTimeout = setTimeout(() => {
            typingIndicator.classList.add('hidden');
        }, 1000);
    });
    
    // Send message
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = messageInput.value.trim();
        
        if (message) {
            // Tambahkan animasi loading saat mengirim pesan
            const sendButton = this.querySelector('button[type="submit"]');
            const originalText = sendButton.innerHTML;
            sendButton.disabled = true;
            sendButton.innerHTML = `<svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>`;
            
            fetch(`{{ route('chat.send', $chatRoom) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Terjadi kesalahan saat mengirim pesan');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    messageInput.value = '';
                    loadMessages();
                    
                    // Tambahkan pesan kita sendiri ke chat untuk tampilan instan
                    const now = new Date();
                    const time = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                    
                    const messageDiv = document.createElement('div');
                    messageDiv.className = 'mb-4 flex justify-end animate__animated animate__fadeIn';
                    messageDiv.innerHTML = `
                        <div class="flex flex-col items-end max-w-[80%]">
                            <div class="flex items-end flex-row-reverse">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex-shrink-0 flex items-center justify-center text-white text-xs font-bold ml-2 shadow-md">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="px-4 py-3 message-bubble-right bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-md">
                                    <p class="text-sm whitespace-pre-wrap leading-relaxed">${message}</p>
                                </div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1.5 text-right mr-11 flex items-center justify-end">
                                <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                ${time}
                            </div>
                        </div>
                    `;
                    chatMessages.appendChild(messageDiv);
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
                
                // Kembalikan tombol ke semula
                sendButton.disabled = false;
                sendButton.innerHTML = originalText;
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Tampilkan pesan error di chatroom
                const errorDiv = document.createElement('div');
                errorDiv.className = 'flex justify-center my-4 animate__animated animate__fadeIn';
                errorDiv.innerHTML = `
                    <div class="bg-red-100 text-red-800 text-sm px-4 py-2 rounded-lg inline-block">
                        <p class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            ${error.message || 'Terjadi kesalahan saat mengirim pesan'}
                        </p>
                    </div>
                `;
                chatMessages.appendChild(errorDiv);
                chatMessages.scrollTop = chatMessages.scrollHeight;
                
                // Kembalikan tombol ke semula
                sendButton.disabled = false;
                sendButton.innerHTML = originalText;
            });
        }
    });
    
    // Handle Enter key to send message
    messageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            messageForm.dispatchEvent(new Event('submit'));
        }
    });
    
    // Load messages pada awalnya dan setiap 3 detik
    loadMessages();
    setInterval(loadMessages, 3000);

    // Enhance form interactivity
    const enhanceDiagnosaForm = () => {
        const diagnosaTextarea = document.getElementById('diagnosa');
        const catatanTextarea = document.getElementById('catatan');
        const diagnosaCounter = document.getElementById('diagnosa-counter');
        const catatanCounter = document.getElementById('catatan-counter');
        
        if (diagnosaTextarea && diagnosaCounter) {
            diagnosaTextarea.addEventListener('input', function() {
                const charCount = this.value.length;
                const maxLength = 500;
                const remaining = maxLength - charCount;
                
                diagnosaCounter.textContent = `${charCount}/${maxLength}`;
                
                if (remaining < 50) {
                    diagnosaCounter.classList.add('text-red-500');
                } else {
                    diagnosaCounter.classList.remove('text-red-500');
                }
                
                // Add subtle animation on input
                this.classList.add('pulse-border');
                setTimeout(() => {
                    this.classList.remove('pulse-border');
                }, 300);
            });
        }
        
        if (catatanTextarea && catatanCounter) {
            catatanTextarea.addEventListener('input', function() {
                const charCount = this.value.length;
                const maxLength = 1000;
                const remaining = maxLength - charCount;
                
                catatanCounter.textContent = `${charCount}/${maxLength}`;
                
                if (remaining < 100) {
                    catatanCounter.classList.add('text-red-500');
                } else {
                    catatanCounter.classList.remove('text-red-500');
                }
                
                // Add subtle animation on input
                this.classList.add('pulse-border');
                setTimeout(() => {
                    this.classList.remove('pulse-border');
                }, 300);
            });
        }
    }

    // Initialize diagnosa form enhancements
    enhanceDiagnosaForm();
});
</script>
</body>
</html>