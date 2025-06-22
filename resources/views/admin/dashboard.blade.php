@extends('layouts.admin')
@section('admin-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
    <p class="text-sm text-gray-600">Selamat datang kembali di Sistem Admin ASSRI</p>
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
        <div class="h-64">
            <canvas id="userActivityChart"></canvas>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fetch data aktivitas pengguna
        fetch('{{ route("admin.log.activity-data") }}')
            .then(response => response.json())
            .then(data => {
                // Inisialisasi grafik aktivitas berdasarkan tanggal
                const ctx = document.getElementById('userActivityChart').getContext('2d');
                const userActivityChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.dateLabels,
                        datasets: [{
                            label: 'Jumlah Aktivitas',
                            data: data.dateCounts,
                            backgroundColor: 'rgba(59, 130, 246, 0.6)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Aktivitas 7 Hari Terakhir',
                                font: {
                                    size: 14
                                }
                            },
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    title: function(tooltipItems) {
                                        return tooltipItems[0].label;
                                    },
                                    label: function(context) {
                                        return `${context.parsed.y} aktivitas`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error fetching user activity data:', error);
                document.getElementById('userActivityChart').parentNode.innerHTML = `
                    <div class="text-gray-400 text-center h-full flex flex-col items-center justify-center">
                        <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <p>Gagal memuat data grafik</p>
                        <p class="text-sm">Silakan refresh halaman atau coba lagi nanti</p>
                    </div>
                `;
            });
    });
</script>
@endpush
@endsection 