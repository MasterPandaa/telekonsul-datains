@extends('layouts.dokter')
@section('dokter-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Dokter</h1>
    <p class="text-sm text-gray-600">Selamat datang di sistem Telekonsultasi</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card Konsultasi Aktif -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 mr-4">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Konsultasi Aktif</p>
                <p class="text-2xl font-bold text-gray-800">{{ $konsultasiAktifCount ?? 0 }}</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('dokter.konsultasi.index') }}" class="text-blue-500 hover:text-blue-600 text-sm font-medium">Lihat Detail &rarr;</a>
        </div>
    </div>

    <!-- Card Konsultasi Selesai -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 mr-4">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Chat Selesai</p>
                <p class="text-2xl font-bold text-gray-800">{{ $konsultasiSelesaiCount ?? 0 }}</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('dokter.riwayat.index') }}" class="text-green-500 hover:text-green-600 text-sm font-medium">Lihat Detail &rarr;</a>
        </div>
    </div>

    <!-- Card Nilai -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 mr-4">
                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Nilai Rata-rata</p>
                <p class="text-2xl font-bold text-gray-800">{{ $nilaiAvg ?? 0 }}</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('dokter.riwayat.index') }}" class="text-yellow-500 hover:text-yellow-600 text-sm font-medium">Lihat Detail &rarr;</a>
        </div>
    </div>

    <!-- Card Rating Pasien -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 mr-4">
                <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Rating Rata-rata</p>
                <p class="text-2xl font-bold text-gray-800">{{ $ratingAvg ?? 0 }}</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('dokter.riwayat.index') }}" class="text-purple-500 hover:text-purple-600 text-sm font-medium">Lihat Detail &rarr;</a>
        </div>
    </div>
</div>

<!-- Jadwal Konsultasi Terbaru -->
<div class="bg-white rounded-lg shadow-md mb-8">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Jadwal Konsultasi Terbaru</h2>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pasien</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($jadwalKonsultasi ?? [] as $jadwal)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $jadwal['tanggal'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $jadwal['jam'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full object-cover bg-gray-100" src="{{ $jadwal['pasien_foto_url'] ?? asset('img/BW_ASSRI.png') }}" alt="{{ $jadwal['pasien_nama'] }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $jadwal['pasien_nama'] }}</div>
                                    <div class="text-sm text-gray-500">{{ $jadwal['pasien_gender'] }}, {{ $jadwal['pasien_usia'] }} tahun</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($jadwal['status'] === 'Terkonfirmasi')
                                    bg-green-100 text-green-800
                                @elseif($jadwal['status'] === 'Menunggu')
                                    bg-yellow-100 text-yellow-800
                                @endif
                            ">
                                {{ $jadwal['status'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <a href="{{ route('dokter.konsultasi.index') }}" class="text-blue-600 hover:text-blue-900 mr-3">Lihat</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada jadwal konsultasi aktif saat ini
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
        // Show welcome notification when page loads
        Swal.fire({
            title: 'Selamat Datang!',
            text: 'Selamat datang di Dashboard Dokter Telekonsultasi',
            icon: 'success',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });

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