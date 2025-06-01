@extends('layouts.admin')
@section('admin-content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard Admin</h1>
    <p class="text-gray-600 mt-1">Selamat datang kembali, {{ $user->name }}</p>
</div>

<!-- Statistik -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 mr-4">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 0 0-3-3.87M9 20H4v-2a4 4 0 0 1 3-3.87m9-4V7a4 4 0 1 0-8 0v3m8 0a4 4 0 1 1-8 0"></path>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Mahasiswa</p>
                <p class="text-2xl font-bold text-gray-800">
                    @if(class_exists('\App\Models\Mahasiswa'))
                        {{ \App\Models\Mahasiswa::count() }}
                    @else
                        0
                    @endif
                </p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.mahasiswa.index') }}" class="text-blue-500 hover:text-blue-600 text-sm font-medium">Lihat Detail &rarr;</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 mr-4">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 1 0 0 8 4 4 0 0 0 0-8zm0 10a5.5 5.5 0 0 0-5.5 5.5v.5h11v-.5a5.5 5.5 0 0 0-5.5-5.5z"></path>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Dosen</p>
                <p class="text-2xl font-bold text-gray-800">
                    @if(class_exists('\App\Models\Dosen'))
                        {{ \App\Models\Dosen::count() }}
                    @else
                        0
                    @endif
                </p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.dosen.index') }}" class="text-green-500 hover:text-green-600 text-sm font-medium">Lihat Detail &rarr;</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 mr-4">
                <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 0 0-3-3.87M9 20H4v-2a4 4 0 0 1 3-3.87m9-4V7a4 4 0 1 0-8 0v3m8 0a4 4 0 1 1-8 0"></path>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Pasien</p>
                <p class="text-2xl font-bold text-gray-800">
                    @if(class_exists('\App\Models\Pasien'))
                        {{ \App\Models\Pasien::count() }}
                    @else
                        0
                    @endif
                </p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.pasien.index') }}" class="text-purple-500 hover:text-purple-600 text-sm font-medium">Lihat Detail &rarr;</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 mr-4">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Aktivitas Sistem</p>
                <p class="text-2xl font-bold text-gray-800">
                    @php
                        try {
                            $logsCount = \App\Models\Log::count();
                        } catch (\Exception $e) {
                            $logsCount = 0;
                        }
                    @endphp
                    {{ $logsCount }}
                </p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.log.system') }}" class="text-red-500 hover:text-red-600 text-sm font-medium">Lihat Detail &rarr;</a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Grafik Aktivitas -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Pengguna</h2>
        <div class="h-64 flex items-center justify-center">
            <div class="text-gray-400 text-center">
                <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <p>Data grafik akan ditampilkan di sini</p>
                <p class="text-sm">Anda bisa mengintegrasikan Chart.js atau library grafik lainnya</p>
            </div>
        </div>
    </div>

    <!-- Ringkasan User -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Sistem</h2>
        <div class="space-y-4">
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                <div class="font-medium">Versi Sistem</div>
                <div class="text-gray-600">1.0.0</div>
            </div>
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                <div class="font-medium">PHP Version</div>
                <div class="text-gray-600">{{ phpversion() }}</div>
            </div>
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                <div class="font-medium">Laravel Version</div>
                <div class="text-gray-600">{{ app()->version() }}</div>
            </div>
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                <div class="font-medium">Database</div>
                <div class="text-gray-600">{{ config('database.connections.mysql.database') }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Aktivitas Terbaru -->
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h2>
        <a href="{{ route('admin.log.system') }}" class="text-blue-500 hover:text-blue-600 text-sm">Lihat Semua</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @php
                    try {
                        $logs = \App\Models\Log::with('user')
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    } catch (\Exception $e) {
                        $logs = collect([]);
                    }
                @endphp
                
                @forelse($logs as $log)
                <tr>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y H:i') }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                        {{ $log->user->name ?? 'User tidak ditemukan' }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                        {{ $log->action }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                        {{ Str::limit($log->description, 50) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-3 text-center text-gray-500">
                        Tidak ada aktivitas terbaru
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pengumuman dan Informasi -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Pengumuman -->
    <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-2">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Pengumuman</h2>
        <div class="space-y-4">
            <div class="p-4 border border-blue-100 rounded-lg bg-blue-50">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="font-medium text-blue-800">Pembaruan Sistem</h3>
                </div>
                <p class="text-sm text-blue-700">Sistem telah diperbarui ke versi terbaru. Beberapa fitur baru telah ditambahkan dan bug telah diperbaiki.</p>
                <p class="text-xs text-blue-600 mt-2">2 hari yang lalu</p>
            </div>
            
            <div class="p-4 border border-green-100 rounded-lg bg-green-50">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="font-medium text-green-800">Pelatihan Pengguna</h3>
                </div>
                <p class="text-sm text-green-700">Sesi pelatihan pengguna akan diadakan pada tanggal 15 Juni 2023. Silakan daftar melalui email.</p>
                <p class="text-xs text-green-600 mt-2">1 minggu yang lalu</p>
            </div>
        </div>
    </div>
    
    <!-- Link Cepat -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Link Cepat</h2>
        <div class="space-y-3">
            <a href="{{ route('admin.mahasiswa.create') }}" class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="text-gray-700">Tambah Mahasiswa Baru</span>
            </a>
            <a href="{{ route('admin.dosen.create') }}" class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="text-gray-700">Tambah Dosen Baru</span>
            </a>
            <a href="{{ route('admin.pasien.create') }}" class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="text-gray-700">Tambah Pasien Baru</span>
            </a>
            <a href="{{ route('admin.log.system') }}" class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="text-gray-700">Lihat Log Sistem</span>
            </a>
        </div>
    </div>
</div>
@endsection 