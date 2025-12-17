@extends('layouts.admin')
@section('admin-content')

<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Detail Pasien</h1>
        <p class="text-sm text-gray-600">Informasi lengkap data pasien</p>
    </div>
    <div class="flex space-x-3">
        <form id="form-hapus" action="{{ route('admin.pasien.destroy', $pasien) }}" method="POST" class="hidden">
            @csrf @method('DELETE')
        </form>
        <button 
            type="button"
            onclick="konfirmasiHapus()"
            class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
            Hapus
        </button>
        <a href="{{ route('admin.pasien.edit', $pasien) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
            Edit
        </a>
        <a href="{{ route('admin.pasien.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            Kembali
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="md:flex">
        <!-- Profil / Sidebar -->
        <div class="md:w-1/4 bg-gray-50 p-6 border-r border-gray-200">
            <div class="flex flex-col items-center">
                <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-100 mb-4">
                    <img src="{{ $pasien->foto_url }}" alt="{{ $pasien->nama }}" class="w-full h-full object-cover">
                </div>
                <h3 class="text-xl font-bold text-gray-800 text-center">{{ $pasien->nama }}</h3>
                
                <div class="mt-8 w-full flex justify-center">
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                         {{ $pasien->user && $pasien->user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <span class="w-2 h-2 rounded-full {{ $pasien->user && $pasien->user->is_active ? 'bg-green-600' : 'bg-red-600' }} mr-2"></span>
                        Status: {{ $pasien->user && $pasien->user->is_active ? 'Aktif' : 'Non-aktif' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Information -->
        <div class="md:w-3/4 p-0">
            <div class="border-b border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pribadi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">NIK</h4>
                            <p class="text-gray-800">{{ $pasien->nik }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Email</h4>
                            <p class="text-gray-800">{{ $pasien->email }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Nomor Telepon</h4>
                            <p class="text-gray-800">{{ $pasien->no_hp ?? 'Belum diisi' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Tempat, Tanggal Lahir</h4>
                            <p class="text-gray-800">
                                @if($pasien->tempat_lahir || $pasien->tanggal_lahir)
                                    {{ $pasien->tempat_lahir ?? '-' }}, 
                                    @if($pasien->tanggal_lahir)
                                        @if(is_string($pasien->tanggal_lahir))
                                            {{ date('d F Y', strtotime($pasien->tanggal_lahir)) }}
                                        @else
                                            {{ $pasien->tanggal_lahir->format('d F Y') }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                @else
                                    Belum diisi
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Medis</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Tinggi Badan</h4>
                        <p class="text-gray-800">{{ $pasien->tinggi_badan ? $pasien->tinggi_badan . ' cm' : 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Berat Badan</h4>
                        <p class="text-gray-800">{{ $pasien->berat_badan ? $pasien->berat_badan . ' kg' : 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Tekanan Darah</h4>
                        <p class="text-gray-800">{{ $pasien->tekanan_darah ?? 'Belum diisi' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Riwayat Alergi</h4>
                        <p class="text-gray-800">{{ $pasien->alergi ?? 'Tidak ada' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Riwayat Penyakit</h4>
                        <p class="text-gray-800">{{ $pasien->riwayat_penyakit ?? 'Tidak ada' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function konfirmasiHapus() {
        Swal.fire({
            title: 'Anda yakin?',
            html: `
                <div class="text-left mb-4">
                    <p class="mb-2">Apakah Anda yakin ingin menghapus data pasien:</p>
                    <div class="flex items-center p-3 bg-red-50 rounded-lg border border-red-200">
                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mr-3">
                            <img src="{{ $pasien->foto_url }}" class="w-12 h-12 rounded-full object-cover" alt="{{ $pasien->nama }}">
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $pasien->nama }}</p>
                            <p class="text-sm text-gray-600">{{ $pasien->nik }}</p>
                        </div>
                    </div>
                </div>
                <p class="text-sm text-red-600 font-medium">Tindakan ini tidak dapat dibatalkan!</p>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Data',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            focusCancel: true,
            iconColor: '#ef4444',
            background: '#ffffff',
            padding: '1.5rem',
            customClass: {
                confirmButton: 'px-4 py-2 rounded text-white text-sm font-medium',
                cancelButton: 'px-4 py-2 rounded text-white text-sm font-medium',
                title: 'text-xl text-gray-800 font-bold mb-3',
                popup: 'rounded-xl shadow-md'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-hapus').submit();
            }
        });
    }
</script>
@endpush

@endsection 