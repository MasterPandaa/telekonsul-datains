@extends('layouts.dosen')

@section('title', 'Detail Konsultasi')

@section('dosen-content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Konsultasi #{{ $konsultasi->id }}</h1>
            <p class="text-sm text-gray-600">Lihat detail dan berikan penilaian</p>
        </div>
        <a href="{{ route('dosen.penilaian.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>
</div>
    
<!-- Informasi Konsultasi -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4">Informasi Konsultasi</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Tanggal:</span>
                <span class="font-medium">{{ $konsultasi->tanggal_indonesia }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Waktu:</span>
                <span class="font-medium">{{ $konsultasi->jam_mulai->format('H:i') }} - {{ $konsultasi->jam_selesai->format('H:i') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Status:</span>
                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ $konsultasi->status }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Rating Pasien:</span>
                <span class="font-medium">
                    @if($konsultasi->rating)
                        {{ $konsultasi->rating }}/5
                    @else
                        Belum ada rating
                    @endif
                </span>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4">Informasi Dokter & Pasien</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Dokter:</span>
                <span class="font-medium">{{ $konsultasi->dokter->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Pasien:</span>
                <span class="font-medium">{{ $konsultasi->pasien->nama }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Usia Pasien:</span>
                <span class="font-medium">{{ \Carbon\Carbon::parse($konsultasi->pasien->tanggal_lahir)->age }} tahun</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Jenis Kelamin:</span>
                <span class="font-medium">{{ $konsultasi->pasien->jenis_kelamin }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Keluhan dan Diagnosa -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-medium text-gray-800 mb-3">Keluhan Pasien</h3>
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-gray-700">{{ $konsultasi->keluhan }}</p>
            @if($konsultasi->keterangan)
                <div class="mt-3 pt-3 border-t border-gray-200">
                    <h4 class="text-sm font-medium text-gray-700 mb-1">Keterangan Tambahan:</h4>
                    <p class="text-gray-700">{{ $konsultasi->keterangan }}</p>
                </div>
            @endif
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-medium text-gray-800 mb-3">Diagnosa Dokter</h3>
        <div class="bg-gray-50 p-4 rounded-lg">
            @if($konsultasi->diagnosa)
                <p class="text-gray-700">{{ $konsultasi->diagnosa }}</p>
                @if($konsultasi->catatan)
                    <div class="mt-3 pt-3 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-700 mb-1">Catatan Tambahan:</h4>
                        <p class="text-gray-700">{{ $konsultasi->catatan }}</p>
                    </div>
                @endif
            @else
                <p class="text-gray-500 italic">Belum ada diagnosa</p>
            @endif
        </div>
    </div>
</div>

<!-- Riwayat Chat -->
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h3 class="text-lg font-medium text-gray-800 mb-3">Riwayat Percakapan</h3>
    <div class="bg-gray-50 p-4 rounded-lg max-h-80 overflow-y-auto">
        @if($konsultasi->chatRoom && $konsultasi->chatRoom->messages->count() > 0)
            <div class="space-y-4">
                @foreach($konsultasi->chatRoom->messages as $message)
                    <div class="flex {{ $message->sender_id == $konsultasi->dokter_id ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[70%] {{ $message->sender_id == $konsultasi->dokter_id ? 'bg-blue-100 text-blue-800' : 'bg-gray-200 text-gray-800' }} rounded-lg px-4 py-2">
                            <div class="text-xs text-gray-500 mb-1">
                                {{ $message->sender_id == $konsultasi->dokter_id ? 'Dokter' : 'Pasien' }} • {{ $message->created_at->format('H:i') }}
                            </div>
                            <p>{{ $message->content }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 italic">Tidak ada riwayat percakapan</p>
        @endif
    </div>
</div>

<!-- Form Penilaian -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-medium text-gray-800 mb-4">Penilaian Konsultasi</h3>
    <form action="{{ route('dosen.penilaian.store', $konsultasi->id) }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="nilai_dosen" class="block text-sm font-medium text-gray-700 mb-1">Nilai (1-100)</label>
            <input type="number" id="nilai_dosen" name="nilai_dosen" min="1" max="100" value="{{ old('nilai_dosen', $konsultasi->nilai_dosen) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
            @error('nilai_dosen')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label for="catatan_dosen" class="block text-sm font-medium text-gray-700 mb-1">Catatan Penilaian</label>
            <textarea id="catatan_dosen" name="catatan_dosen" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">{{ old('catatan_dosen', $konsultasi->catatan_dosen) }}</textarea>
            @error('catatan_dosen')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Simpan Penilaian
            </button>
        </div>
    </form>
</div>

@push('scripts')
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
    });
</script>
@endpush
@endsection 