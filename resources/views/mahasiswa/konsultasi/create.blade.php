@extends('layouts.mahasiswa')
@section('mahasiswa-content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Buat Permintaan Konsultasi Baru</h1>
        <p class="text-sm text-gray-600">Lengkapi formulir untuk membuat jadwal konsultasi dengan pasien</p>
    </div>
    <a href="{{ route('mahasiswa.konsultasi.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali
    </a>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6">
        <form action="#" method="POST" class="space-y-6">
            @csrf
            
            <div class="border-b pb-4 mb-4">
                <h2 class="text-lg font-medium text-gray-800 mb-3">Informasi Pasien</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Pilih Pasien -->
                    <div>
                        <label for="pasien_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Pilih Pasien <span class="text-red-500">*</span>
                        </label>
                        <select id="pasien_id" name="pasien_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Pasien --</option>
                            <option value="1">Budi Santoso (45 tahun, Laki-laki)</option>
                            <option value="2">Siti Rahayu (38 tahun, Perempuan)</option>
                            <option value="3">Ahmad Hidayat (52 tahun, Laki-laki)</option>
                            <option value="4">Dewi Lestari (30 tahun, Perempuan)</option>
                            <option value="5">Joko Widodo (60 tahun, Laki-laki)</option>
                        </select>
                    </div>
                    
                    <!-- Daftar Pasien Baru -->
                    <div class="flex items-end">
                        <button type="button" class="inline-flex items-center px-4 py-2 text-sm text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Daftarkan Pasien Baru
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="border-b pb-4 mb-4">
                <h2 class="text-lg font-medium text-gray-800 mb-3">Jadwal Konsultasi</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tanggal -->
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Konsultasi <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="tanggal" name="tanggal" required class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <!-- Waktu -->
                    <div>
                        <label for="waktu" class="block text-sm font-medium text-gray-700 mb-1">
                            Waktu Konsultasi <span class="text-red-500">*</span>
                        </label>
                        <select id="waktu" name="waktu" required class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Waktu --</option>
                            <option value="09:00">09:00 - 10:00</option>
                            <option value="10:00">10:00 - 11:00</option>
                            <option value="11:00">11:00 - 12:00</option>
                            <option value="13:00">13:00 - 14:00</option>
                            <option value="14:00">14:00 - 15:00</option>
                            <option value="15:00">15:00 - 16:00</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Durasi konsultasi adalah 60 menit</p>
                    </div>
                </div>
            </div>
            
            <div class="border-b pb-4 mb-4">
                <h2 class="text-lg font-medium text-gray-800 mb-3">Detail Konsultasi</h2>
                
                <!-- Keluhan -->
                <div class="mb-4">
                    <label for="keluhan" class="block text-sm font-medium text-gray-700 mb-1">
                        Keluhan Utama <span class="text-red-500">*</span>
                    </label>
                    <textarea id="keluhan" name="keluhan" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Deskripsikan keluhan pasien..."></textarea>
                </div>
                
                <!-- Riwayat Medis -->
                <div class="mb-4">
                    <label for="riwayat_medis" class="block text-sm font-medium text-gray-700 mb-1">
                        Riwayat Medis
                    </label>
                    <textarea id="riwayat_medis" name="riwayat_medis" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Riwayat kesehatan pasien, jika ada..."></textarea>
                </div>
                
                <!-- Obat-obatan -->
                <div>
                    <label for="obat" class="block text-sm font-medium text-gray-700 mb-1">
                        Obat-obatan yang Dikonsumsi
                    </label>
                    <textarea id="obat" name="obat" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Obat-obatan yang saat ini dikonsumsi, jika ada..."></textarea>
                </div>
            </div>
            
            <div class="border-b pb-4 mb-4">
                <h2 class="text-lg font-medium text-gray-800 mb-3">Jenis Konsultasi</h2>
                
                <div class="space-y-3">
                    <div class="flex items-center">
                        <input id="type_video" name="jenis_konsultasi" type="radio" value="video" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" checked>
                        <label for="type_video" class="ml-3 block text-sm font-medium text-gray-700">
                            Video Call (Zoom/Google Meet)
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input id="type_chat" name="jenis_konsultasi" type="radio" value="chat" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <label for="type_chat" class="ml-3 block text-sm font-medium text-gray-700">
                            Chat (WhatsApp/Telegram)
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input id="type_voice" name="jenis_konsultasi" type="radio" value="voice" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <label for="type_voice" class="ml-3 block text-sm font-medium text-gray-700">
                            Voice Call
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="pt-4">
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('mahasiswa.konsultasi.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Simpan Permintaan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection 