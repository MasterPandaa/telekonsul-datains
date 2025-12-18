@extends('layouts.dosen')

@section('title', 'Dashboard Dosen')

@section('dosen-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Dosen</h1>
    <p class="text-sm text-gray-600">Selamat datang di sistem Telekonsultasi</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card Konsultasi Perlu Dinilai -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 mr-4">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Perlu Dinilai</p>
                <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Konsultasi::where('status', 'Selesai')->whereNull('nilai_dosen')->count() }}</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('dosen.penilaian.index') }}" class="text-blue-500 hover:text-blue-600 text-sm font-medium">Lihat Detail &rarr;</a>
        </div>
    </div>

    <!-- Card Konsultasi Sudah Dinilai -->
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
        <div class="mt-4">
            <a href="{{ route('dosen.penilaian.index') }}" class="text-green-500 hover:text-green-600 text-sm font-medium">Lihat Detail &rarr;</a>
        </div>
    </div>

    <!-- Card Total Dokter -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 mr-4">
                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Dokter</p>
                <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Dokter::count() }}</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('dosen.rekap.index') }}" class="text-yellow-500 hover:text-yellow-600 text-sm font-medium">Lihat Detail &rarr;</a>
        </div>
    </div>

    <!-- Card Nilai Rata-rata -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 mr-4">
                <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Nilai Rata-rata</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ \App\Models\Konsultasi::where('status', 'Selesai')->whereNotNull('nilai_dosen')->avg('nilai_dosen') ? number_format(\App\Models\Konsultasi::where('status', 'Selesai')->whereNotNull('nilai_dosen')->avg('nilai_dosen'), 1) : 0 }}
                </p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('dosen.rekap.index') }}" class="text-purple-500 hover:text-purple-600 text-sm font-medium">Lihat Detail &rarr;</a>
        </div>
    </div>
</div>

<!-- Konsultasi Terbaru yang Selesai -->
<div class="bg-white rounded-lg shadow-md mb-8">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Konsultasi Terbaru yang Selesai</h2>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dokter</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse(\App\Models\Konsultasi::where('status', 'Selesai')->orderBy('updated_at', 'desc')->take(5)->get() as $konsultasi)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $konsultasi->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full object-cover bg-gray-100" src="{{ $konsultasi->dokter->dokter->foto_url ?? asset('img/default.jpg') }}" alt="{{ $konsultasi->dokter->name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $konsultasi->dokter->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full object-cover bg-gray-100" src="{{ $konsultasi->pasien->foto_url ?? asset('img/default.jpg') }}" alt="{{ $konsultasi->pasien->nama }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $konsultasi->pasien->nama }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $konsultasi->tanggal_indonesia }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $konsultasi->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($konsultasi->nilai_dosen)
                                <span class="font-medium">{{ $konsultasi->nilai_dosen }}</span>
                            @else
                                <span class="text-gray-400">Belum dinilai</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <a href="{{ route('dosen.penilaian.show', $konsultasi->id) }}" class="text-blue-600 hover:text-blue-900">Lihat</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada konsultasi yang selesai
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show notification for session messages if any
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
    });
</script>
@endpush
@endsection 