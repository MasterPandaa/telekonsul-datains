@extends('layouts.dosen')

@section('title', 'Rekap Data Konsultasi')

@section('dosen-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Rekap Data Konsultasi</h1>
    <p class="text-sm text-gray-600">Lihat statistik dan rekap data konsultasi</p>
</div>

<!-- Statistik -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 mr-4">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Konsultasi</p>
                <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Konsultasi::where('status', 'Selesai')->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 mr-4">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Sudah Dinilai</p>
                <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Konsultasi::where('status', 'Selesai')->whereNotNull('nilai_dosen')->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 mr-4">
                <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Rata-rata Nilai</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ number_format(\App\Models\Konsultasi::where('status', 'Selesai')->whereNotNull('nilai_dosen')->avg('nilai_dosen'), 1) }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Rekap Dokter -->
<div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Rekap Nilai per Dokter</h2>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Dokter</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Konsultasi</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sudah Dinilai</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rata-rata Nilai</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $dokters = \App\Models\Dokter::all();
                        $no = 1;
                    @endphp
                    
                    @foreach($dokters as $dokter)
                    @php
                        $totalKonsultasi = \App\Models\Konsultasi::where('dokter_id', $dokter->user_id)
                            ->where('status', 'Selesai')
                            ->count();
                            
                        $sudahDinilai = \App\Models\Konsultasi::where('dokter_id', $dokter->user_id)
                            ->where('status', 'Selesai')
                            ->whereNotNull('nilai_dosen')
                            ->count();
                            
                        $rataRata = \App\Models\Konsultasi::where('dokter_id', $dokter->user_id)
                            ->where('status', 'Selesai')
                            ->whereNotNull('nilai_dosen')
                            ->avg('nilai_dosen');
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $no++ }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($dokter->nama) }}&background=4F46E5&color=fff" alt="{{ $dokter->nama }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $dokter->nama }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $totalKonsultasi }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $sudahDinilai }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($rataRata)
                                <span class="font-medium">{{ number_format($rataRata, 1) }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <a href="{{ route('dosen.rekap.dokter', $dokter->id) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                    
                    @if($dokters->isEmpty())
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data dokter
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Grafik Rata-rata Nilai -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Grafik Rata-rata Nilai</h2>
    </div>
    <div class="p-6">
        <div class="h-80">
            <canvas id="nilaiChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        
        // Chart initialization
        const ctx = document.getElementById('nilaiChart').getContext('2d');
        
        // Data untuk grafik
        const dokters = @json($dokters->pluck('nama'));
        const rataRataNilai = @json($dokters->map(function($dokter) {
            return \App\Models\Konsultasi::where('dokter_id', $dokter->user_id)
                ->where('status', 'Selesai')
                ->whereNotNull('nilai_dosen')
                ->avg('nilai_dosen') ?? 0;
        }));
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dokters,
                datasets: [{
                    label: 'Rata-rata Nilai',
                    data: rataRataNilai,
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection 