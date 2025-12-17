<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telekonsultasi - {{ isset($isDosenView) ? 'Supervisi Dosen' : (Auth::user()->role === 'dokter' ? $konsultasi->pasien->nama : $konsultasi->dokter->name) }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            border-radius: 4px 20px 20px 20px;
        }
        .message-bubble-right {
            border-radius: 20px 4px 20px 20px;
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
        textarea {
            min-height: 40px;
            overflow: hidden;
            resize: none;
            transition: height 0.2s ease;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function autoResize(textarea) {
                textarea.style.height = 'auto';
                textarea.style.height = textarea.scrollHeight + 'px';
            }

            const textareas = document.querySelectorAll('textarea');
            textareas.forEach(textarea => {
                autoResize(textarea);
                textarea.addEventListener('input', function() {
                    autoResize(this);
                });
            });
        });
    </script>
</head>
<body class="antialiased bg-gradient-to-br from-blue-50 to-indigo-50">
<div class="chat-container">
        <div class="bg-white shadow-lg h-full flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                @if(isset($isTerlambat) && $isTerlambat && $konsultasi->status !== 'Selesai')
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
                
                @if(isset($isDosenView))
                <div class="bg-indigo-500/20 border-l-4 border-indigo-400 px-4 py-2 mb-3 rounded-r-lg animate__animated animate__fadeIn">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-indigo-300 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <p class="ml-3 text-sm text-white">
                            Anda melihat riwayat percakapan sebagai dosen supervisor. Anda hanya dapat melihat riwayat pesan.
                        </p>
                    </div>
                </div>
                @elseif($konsultasi->status === 'Selesai' || $konsultasi->status === 'Terlambat')
                <div class="bg-blue-500/20 border-l-4 border-blue-400 px-4 py-2 mb-3 rounded-r-lg animate__animated animate__fadeIn hidden" id="konsultasi-selesai-info">
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
                            @if(isset($isDosenView))
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            @else
                                {{ strtoupper(substr(Auth::user()->role === 'dokter' ? $konsultasi->pasien->nama : $konsultasi->dokter->name, 0, 1)) }}
                            @endif
                        </div>
                    <div>
                            <h2 class="text-xl font-semibold">
                                @if(isset($isDosenView))
                                    Supervisi: {{ $konsultasi->dokter->name }} dengan {{ $konsultasi->pasien->nama }}
                                @else
                                    Konsultasi dengan {{ Auth::user()->role === 'dokter' ? $konsultasi->pasien->nama : $konsultasi->dokter->name }}
                                @endif
                            </h2>
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
                            Sisa waktu: <span id="time-remaining" class="ml-1 font-bold">--:--</span>
                        </div>
                        <div class="flex justify-end">
                            @if(isset($isDosenView))
                                <a href="{{ route('dosen.penilaian.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali ke Penilaian
                                </a>
                            @elseif($konsultasi->status === 'Selesai' || $konsultasi->status === 'Terlambat')
                                <a href="{{ Auth::user()->role === 'dokter' ? route('dokter.konsultasi.index') : route('pasien.konsultasi.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali
                                </a>
                            @else
                        <form action="{{ route('chat.end', $chatRoom) }}" method="POST" id="end-chat-form">
                            @csrf
                                    <button type="button" id="end-chat-button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors shadow-sm {{ in_array($konsultasi->status, ['Berlangsung', 'Terkonfirmasi']) ? '' : 'opacity-50 cursor-not-allowed' }}" {{ in_array($konsultasi->status, ['Berlangsung', 'Terkonfirmasi']) ? '' : 'disabled' }}>
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
                <div class="w-full md:w-80 p-4 bg-gradient-to-b from-gray-50 to-blue-50/30 border-r border-gray-100 overflow-y-auto flex-shrink-0">
                    <div class="mb-4 transform transition-all hover:scale-[1.01] duration-300">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="text-sm font-bold text-indigo-700 uppercase tracking-wider">Detail Konsultasi</h3>
                        </div>
                        <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition-shadow duration-300 border border-indigo-100/50">
                            <div class="mb-3">
                                <div class="flex items-center mb-1">
                                    <svg class="w-4 h-4 text-indigo-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                    <div class="text-xs font-semibold text-indigo-600">Keluhan:</div>
                                </div>
                                <div class="text-sm text-gray-700 p-3 rounded-lg bg-blue-50 border border-blue-100 shadow-sm">{{ $konsultasi->keluhan }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="flex items-center mb-1">
                                    <svg class="w-4 h-4 text-indigo-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="text-xs font-semibold text-indigo-600">Status:</div>
                                </div>
                                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium shadow-sm 
                                    @if($konsultasi->status === 'Terkonfirmasi')
                                        bg-gradient-to-r from-green-400 to-green-500 text-white
                                    @elseif($konsultasi->status === 'Terlambat')
                                        bg-gradient-to-r from-orange-400 to-orange-500 text-white
                                    @elseif($konsultasi->status === 'Berlangsung')
                                        bg-gradient-to-r from-purple-400 to-purple-500 text-white
                                    @else
                                        bg-gradient-to-r from-blue-400 to-blue-500 text-white
                                    @endif">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="7" />
                                    </svg>
                                    {{ $konsultasi->status }}
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="flex items-center mb-1">
                                    <svg class="w-4 h-4 text-indigo-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="text-xs font-semibold text-indigo-600">Keterangan Tambahan:</div>
                                </div>
                                @if($konsultasi->keterangan)
                                <div class="text-sm mt-1 bg-gradient-to-r from-blue-50 to-indigo-50 p-3 rounded-lg border border-blue-200 shadow-sm">
                                    <div class="flex">
                                        <span class="text-gray-700 line-clamp-2">{{ $konsultasi->keterangan }}</span>
                                    </div>
                                </div>
                                @else
                                <div class="text-sm text-gray-500 italic p-2">Tidak ada keterangan tambahan</div>
                                @endif
                            </div>
                            @if($konsultasi->status === 'Terlambat' && $konsultasi->status !== 'Selesai')
                            <div class="mb-0 mt-3 bg-gradient-to-r from-orange-50 to-orange-100 p-3 rounded-lg shadow-sm animate__animated animate__pulse animate__infinite animate__slower">
                                <div class="text-xs text-orange-700 flex items-start">
                                    <svg class="w-4 h-4 text-orange-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-medium">Anda terlambat memasuki room chat. Waktu konsultasi telah dikurangi.</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($konsultasi->status === 'Berlangsung')
                    <div class="mb-4 transform transition-all hover:scale-[1.01] duration-300">
                        <div class="flex items-center mb-2">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-500 p-1.5 rounded-full shadow-md mr-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-indigo-700 uppercase tracking-wider">Panduan Singkat</h3>
                        </div>
                        
                        <div class="bg-gradient-to-br from-white to-blue-50 p-4 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 border border-indigo-100/50">
                            <ul class="text-sm text-gray-600 space-y-3">
                                <li class="flex items-start bg-blue-50 p-2 rounded-lg mb-2 hover:bg-blue-100/50 transition-colors duration-200">
                                    <div class="bg-blue-100 p-1 rounded-full mr-2">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    </div>
                                    <span class="font-medium">Sesi konsultasi berlangsung sesuai jadwal</span>
                                </li>
                                
                                <li class="flex items-start bg-yellow-50 p-2 rounded-lg mb-2 hover:bg-yellow-100/50 transition-colors duration-200">
                                    <div class="bg-yellow-100 p-1 rounded-full mr-2">
                                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    </div>
                                    <span class="font-medium">Keterlambatan > 15 menit akan mengubah status menjadi "Terlambat"</span>
                                </li>
                                
                                <li class="flex items-start bg-green-50 p-2 rounded-lg mb-2 hover:bg-green-100/50 transition-colors duration-200">
                                    <div class="bg-green-100 p-1 rounded-full mr-2">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    </div>
                                    <span class="font-medium">Pesan akan otomatis diperbarui setiap beberapa detik</span>
                                </li>
                                
                                <li class="flex items-start bg-indigo-50 p-2 rounded-lg mb-2 hover:bg-indigo-100/50 transition-colors duration-200">
                                    <div class="bg-indigo-100 p-1 rounded-full mr-2">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                    </svg>
                                    </div>
                                    <span class="font-medium">Tekan Enter untuk mengirim pesan</span>
                                </li>
                                
                                <li class="flex items-start bg-red-50 p-2 rounded-lg hover:bg-red-100/50 transition-colors duration-200">
                                    <div class="bg-red-100 p-1 rounded-full mr-2">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    </div>
                                    <span class="font-medium">Konsultasi berakhir saat waktu habis atau tombol "Akhiri Konsultasi" ditekan</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @endif
                    
                    @if(Auth::user()->role === 'dokter' && ($konsultasi->status === 'Selesai' || $konsultasi->status === 'Terlambat'))
                    <div class="mb-4 transform transition-all hover:scale-[1.01] duration-300">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                    </svg>
                            <div class="text-sm font-bold text-indigo-700 uppercase tracking-wider">Hasil Diagnosa</div>
                            </div>
                            
                        <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition-shadow duration-300 border border-indigo-100/50 space-y-4">
                            <form action="{{ route('dokter.konsultasi.diagnosa', $konsultasi->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <div class="flex items-center mb-1">
                                        <svg class="w-4 h-4 text-indigo-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                        <div class="text-xs font-semibold text-indigo-600">Diagnosa:</div>
                                    </div>
                                    <div class="relative">
                                        <textarea id="diagnosa" name="diagnosa" maxlength="500" 
                                            class="w-full text-sm text-gray-700 p-3 rounded-lg bg-blue-50 border border-blue-100 shadow-sm focus:border-indigo-400 
                                            focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-300 min-h-[40px] resize-none" 
                                            placeholder="Masukkan diagnosa pasien berdasarkan hasil konsultasi..." 
                                            required>{{ $konsultasi->diagnosa }}</textarea>
                                        <span id="diagnosa-counter" class="counter-badge bg-indigo-100/80 text-indigo-700">
                                            {{ strlen($konsultasi->diagnosa ?? '') }}/500
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="flex items-center mb-1">
                                        <svg class="w-4 h-4 text-indigo-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <div class="text-xs font-semibold text-indigo-600">Catatan Medis:</div>
                                    </div>
                                    <div class="relative">
                                        <textarea id="catatan" name="catatan" maxlength="1000" 
                                            class="w-full text-sm text-gray-700 p-3 rounded-lg bg-blue-50 border border-blue-100 shadow-sm focus:border-indigo-400 
                                            focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-300 min-h-[40px] resize-none" 
                                            placeholder="Tambahkan catatan medis atau rekomendasi tambahan jika diperlukan...">{{ $konsultasi->catatan }}</textarea>
                                        <span id="catatan-counter" class="counter-badge bg-indigo-100/80 text-indigo-700">
                                            {{ strlen($konsultasi->catatan ?? '') }}/1000
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="flex justify-center mt-4">
                                    <button type="submit" 
                                        class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-medium 
                                        rounded-lg text-white bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 
                                        hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 
                                        shadow-md transition-all duration-300 transform hover:scale-[1.02]">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $konsultasi->diagnosa ? 'Update Diagnosa' : 'Simpan Diagnosa' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                    
                    @if(Auth::user()->role === 'pasien' || Auth::user()->role === 'dosen' || isset($isDosenView))
                    <div class="mb-4 transform transition-all hover:scale-[1.01] duration-300">
                        <div class="flex items-center mb-2">
                            <svg class="w-4 h-4 text-indigo-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                            <div class="text-xs font-semibold text-indigo-600">Hasil Diagnosa & Rekomendasi Dokter</div>
                        </div>

                        <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition-shadow duration-300 border border-indigo-100/50 space-y-4">
                            <div>
                                <div class="flex items-center mb-1">
                                    <svg class="w-4 h-4 text-indigo-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    <div class="text-xs font-semibold text-indigo-600">Diagnosa Dokter</div>
                                </div>
                                <div class="text-sm text-gray-700 p-3 rounded-lg bg-blue-50 border border-blue-100 shadow-sm">
                                    {{ $konsultasi->diagnosa ?? 'Belum ada diagnosa yang diisi oleh dokter.' }}
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center mb-1">
                                    <svg class="w-4 h-4 text-indigo-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <div class="text-xs font-semibold text-indigo-600">Catatan Medis / Rekomendasi</div>
                                </div>
                                <div class="text-sm text-gray-700 p-3 rounded-lg bg-blue-50 border border-blue-100 shadow-sm">
                                    {{ $konsultasi->catatan ?? 'Belum ada catatan atau rekomendasi tambahan.' }}
                                </div>
                            </div>

                        </div>
                    </div>
                    @endif

                @if(Auth::user()->role === 'dokter' && $konsultasi->status === 'Selesai')
                    <div class="mb-4 transform transition-all hover:scale-[1.01] duration-300">
                        <div class="flex items-center mb-2">
                            <svg class="w-4 h-4 text-indigo-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <div class="text-xs font-semibold text-indigo-600">Aspek Penilaian</div>
                        </div>
                        
                        <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition-shadow duration-300 border border-indigo-100/50">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-2 md:col-span-1">
                                    <div class="mb-3">
                                        <div class="flex items-center mb-1">
                                            <svg class="w-4 h-4 text-indigo-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            <div class="text-xs font-semibold text-indigo-600">Komunikasi:</div>
                                        </div>
                                        <div class="text-sm text-gray-700 p-3 rounded-lg bg-blue-50 border border-blue-100 shadow-sm">
                                            {{ $konsultasi->nilai_komunikasi ?? '-' }} / 100
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-span-2 md:col-span-1">
                                    <div class="mb-3">
                                        <div class="flex items-center mb-1">
                                            <svg class="w-4 h-4 text-indigo-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                            </svg>
                                            <div class="text-xs font-semibold text-indigo-600">Anamnesis:</div>
                                        </div>
                                        <div class="text-sm text-gray-700 p-3 rounded-lg bg-blue-50 border border-blue-100 shadow-sm">
                                            {{ $konsultasi->nilai_anamnesis ?? '-' }} / 100
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-span-2 md:col-span-1">
                                    <div class="mb-3">
                                        <div class="flex items-center mb-1">
                                            <svg class="w-4 h-4 text-indigo-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                            </svg>
                                            <div class="text-xs font-semibold text-indigo-600">Diagnosa:</div>
                                        </div>
                                        <div class="text-sm text-gray-700 p-3 rounded-lg bg-blue-50 border border-blue-100 shadow-sm">
                                            {{ $konsultasi->nilai_diagnosa ?? '-' }} / 100
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-span-2 md:col-span-1">
                                    <div class="mb-3">
                                        <div class="flex items-center mb-1">
                                            <svg class="w-4 h-4 text-indigo-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                            <div class="text-xs font-semibold text-indigo-600">Empati:</div>
                                        </div>
                                        <div class="text-sm text-gray-700 p-3 rounded-lg bg-blue-50 border border-blue-100 shadow-sm">
                                            {{ $konsultasi->nilai_empati ?? '-' }} / 100
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-span-2">
                                    <div class="mb-3">
                                        <div class="flex items-center mb-1">
                                            <svg class="w-4 h-4 text-indigo-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                            </svg>
                                            <div class="text-xs font-semibold text-indigo-600">Nilai Rata-Rata:</div>
                                        </div>
                                        <div class="text-sm text-gray-700 p-3 rounded-lg bg-blue-50 border border-blue-100 shadow-sm font-semibold">
                                            @php
                                                $nilai_rata = 0;
                                                $count = 0;
                                                
                                                if($konsultasi->nilai_komunikasi !== null) {
                                                    $nilai_rata += $konsultasi->nilai_komunikasi;
                                                    $count++;
                                                }
                                                
                                                if($konsultasi->nilai_anamnesis !== null) {
                                                    $nilai_rata += $konsultasi->nilai_anamnesis;
                                                    $count++;
                                                }
                                                
                                                if($konsultasi->nilai_diagnosa !== null) {
                                                    $nilai_rata += $konsultasi->nilai_diagnosa;
                                                    $count++;
                                                }
                                                
                                                if($konsultasi->nilai_empati !== null) {
                                                    $nilai_rata += $konsultasi->nilai_empati;
                                                    $count++;
                                                }
                                                
                                                $nilai_rata = $count > 0 ? round($nilai_rata / $count, 1) : 0;
                                            @endphp
                                            {{ $nilai_rata }} / 100
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const currentUserId = {{ Auth::id() }};
    const doctorUserId = {{ $konsultasi->dokter_id ?? 'null' }};
    const isDosenView = {{ isset($isDosenView) ? 'true' : 'false' }};
    
    // Menampilkan pesan konsultasi selesai hanya sekali
    const konsultasiSelesaiInfo = document.getElementById('konsultasi-selesai-info');
    if (konsultasiSelesaiInfo) {
        const konsultasiId = {{ $konsultasi->id }};
        const infoShownKey = `konsultasi_selesai_info_shown_${konsultasiId}`;
        
        // Periksa apakah pesan sudah pernah ditampilkan
        if (!localStorage.getItem(infoShownKey)) {
            konsultasiSelesaiInfo.classList.remove('hidden');
            
            // Tandai bahwa pesan sudah ditampilkan
            localStorage.setItem(infoShownKey, 'true');
            
            // Sembunyikan setelah 5 detik
            setTimeout(() => {
                konsultasiSelesaiInfo.classList.add('animate__fadeOut');
                setTimeout(() => {
                    konsultasiSelesaiInfo.classList.add('hidden');
                }, 1000);
            }, 5000);
        }
    }
    
    // Debug info
    console.log('Chat room initialized');
    
    // Simple function to load messages
    function loadMessages() {
        console.log('Loading messages...');
        fetch(`{{ route('chat.messages', $chatRoom) }}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(messages => {
                console.log('Messages loaded:', messages);
                // Clear chat first
                chatMessages.innerHTML = '<div class="flex justify-center mb-4"><div class="bg-gray-200 text-gray-600 text-xs px-3 py-1 rounded-full inline-block">Sesi konsultasi dimulai</div></div>';
                
                // Add all messages
                messages.forEach(message => {
                    const isPerspectiveOwner = isDosenView
                        ? (doctorUserId !== null && message.user_id === doctorUserId)
                        : (message.user_id === currentUserId);
                    const time = new Date(message.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

                    const wrapperAlignment = isPerspectiveOwner ? 'justify-end' : 'justify-start';
                    const contentAlignment = isPerspectiveOwner ? 'flex-row-reverse text-right' : 'text-left';
                    const avatarClass = isPerspectiveOwner
                        ? 'bg-gradient-to-br from-blue-500 to-indigo-600 text-white'
                        : 'bg-gradient-to-br from-indigo-400 to-blue-500 text-white';
                    const bubbleClass = isPerspectiveOwner
                        ? 'message-bubble-right bg-gradient-to-r from-blue-500 to-indigo-600 text-white'
                        : 'message-bubble-left bg-white text-gray-800 border border-gray-100';
                    const timeAlignment = isPerspectiveOwner ? 'justify-end' : 'justify-start';

                    const html = `
                        <div class="mb-4 flex ${wrapperAlignment}">
                            <div class="flex items-start ${contentAlignment} max-w-[80%] gap-2">
                                <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center text-sm font-semibold shadow-md ${avatarClass}">
                                    ${message.user ? message.user.name.charAt(0).toUpperCase() : 'U'}
                                </div>
                                <div>
                                    <div class="px-4 py-3 ${bubbleClass} shadow-md">
                                        <p class="text-sm whitespace-pre-wrap leading-relaxed">${message.message}</p>
                                    </div>
                                    <div class="mt-1.5 text-xs text-gray-500 flex items-center ${timeAlignment}">
                                        <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        ${time}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    chatMessages.insertAdjacentHTML('beforeend', html);
                });
                
                // Scroll to bottom
                chatMessages.scrollTop = chatMessages.scrollHeight;
            })
            .catch(error => {
                console.error('Error loading messages:', error);
                showError('Gagal memuat pesan');
            });
    }
    
    // Send message
    if (messageForm) {
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const message = messageInput.value.trim();
            
            if (message) {
                console.log('Sending message:', message);
                // Save the message before clearing input
                const messageText = message;
                
                // Clear input immediately for better UX
                messageInput.value = '';
                
                fetch(`{{ route('chat.send', $chatRoom) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        message: messageText,
                        _token: csrfToken
                    })
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        // Reload all messages
                        loadMessages();
                    } else {
                        messageInput.value = messageText; // Restore the message if failed
                        showError(data.message || 'Gagal mengirim pesan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    messageInput.value = messageText; // Restore the message if failed
                    showError('Terjadi kesalahan saat mengirim pesan');
                });
            }
        });
    }
    
    // Show error message
    function showError(message) {
        console.error('Error message:', message);
        const errorDiv = document.createElement('div');
        errorDiv.className = 'flex justify-center my-4';
        errorDiv.innerHTML = `
            <div class="bg-red-100 text-red-800 text-sm px-4 py-2 rounded-lg inline-block">
                <p class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    ${message}
                </p>
            </div>
        `;
        chatMessages.appendChild(errorDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // Load messages on page load
    loadMessages();
    
    // Refresh messages every 3 seconds
    setInterval(loadMessages, 3000);
    
    // Handle Enter key to send message
    if (messageInput) {
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                messageForm.dispatchEvent(new Event('submit'));
            }
        });
    }
    
    // Set timer for consultation
    let timeLeft = 15 * 60; // 15 minutes in seconds
    
    // If time remaining is provided from server, use it
    @if(isset($sisaWaktu))
        timeLeft = {{ max(0, $sisaWaktu) }} * 60; // convert minutes to seconds
    @endif

    timeLeft = Math.max(0, Math.floor(timeLeft));

    const formatTime = (totalSeconds) => {
        const minutes = Math.floor(totalSeconds / 60);
        const seconds = Math.floor(totalSeconds % 60);
        return minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
    };

    // If consultation is already finished, disable the timer
    @if($konsultasi->status === 'Selesai' || $konsultasi->status === 'Terlambat')
        timeRemaining.textContent = 'Konsultasi selesai';
        document.getElementById('timer').classList.add('bg-gray-500/20');
    @else
        // Show initial time
        timeRemaining.textContent = formatTime(timeLeft);
        
        // Start countdown
        const timer = setInterval(() => {
            timeLeft = Math.max(0, timeLeft - 1);
            timeRemaining.textContent = formatTime(timeLeft);
            
            // Highlight timer when less than 1 minute
            if (timeLeft <= 60) {
                timeRemaining.classList.add('text-red-300');
                document.getElementById('timer').classList.add('bg-red-500/20');
            }
            
            if (timeLeft <= 0) {
                clearInterval(timer);
                timeRemaining.textContent = 'Waktu habis';
                
                // Display time up message
                const timeUpMessage = document.createElement('div');
                timeUpMessage.className = 'flex justify-center my-4';
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
                
                // End chat automatically
                setTimeout(() => {
                    fetch(`{{ route('chat.end', $chatRoom) }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    }).then(() => {
                        window.location.href = '{{ Auth::user()->role === "dokter" ? route("dokter.konsultasi.index") : route("pasien.konsultasi.index") }}';
                    });
                }, 3000);
            }
        }, 1000);
    @endif
    
    // End chat confirmation
    const endChatButton = document.getElementById('end-chat-button');
    const endChatForm = document.getElementById('end-chat-form');
    
    if (endChatButton && endChatForm) {
        endChatButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Akhiri Konsultasi?',
                text: 'Anda yakin ingin mengakhiri konsultasi ini? Tindakan ini tidak dapat dibatalkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Akhiri',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    endChatForm.submit();
                }
            });
        });
    }
});
</script>
</body>
</html>