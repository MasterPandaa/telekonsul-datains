@extends('layouts.admin')
@section('admin-content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Detail Dokter</h1>
        <p class="text-sm text-gray-600">Informasi lengkap data dokter</p>
    </div>
    <div class="flex space-x-3">
        <button 
            type="button"
            onclick="konfirmasiHapus()"
            class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
            Hapus
        </button>
        <a href="{{ route('admin.dokter.edit', $dokter) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
            Edit
        </a>
        <a href="{{ route('admin.dokter.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            Kembali
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="md:flex">
        <!-- Profil / Sidebar -->
        <div class="md:w-1/4 bg-gray-50 p-6 border-r border-gray-200">
            <div class="flex flex-col items-center">
                <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-100 mb-4 flex items-center justify-center text-3xl font-semibold text-blue-700">
                    @if($dokter->has_photo)
                        <img src="{{ $dokter->foto_url }}" alt="{{ $dokter->nama }}" class="w-full h-full object-cover">
                    @else
                        {{ $dokter->initials }}
                    @endif
                </div>
                <h3 class="text-xl font-bold text-gray-800 text-center">{{ $dokter->nama }}</h3>
                <p class="text-blue-600 font-medium text-center">{{ $dokter->spesialisasi }}</p>
                
                <div class="mt-8 w-full flex justify-center">
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <span class="w-2 h-2 rounded-full bg-blue-600 mr-2"></span>
                        {{ $dokter->status ?? 'Aktif' }}
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
                            <h4 class="text-sm font-medium text-gray-500 mb-1">No. SIP</h4>
                            <p class="text-gray-800">{{ $dokter->no_sip }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">No. STR</h4>
                            <p class="text-gray-800">{{ $dokter->no_str ?? 'Belum diisi' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Email</h4>
                            <p class="text-gray-800">{{ $dokter->email }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Nomor Telepon</h4>
                            <p class="text-gray-800">{{ $dokter->no_hp ?? 'Belum diisi' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Jenis Kelamin</h4>
                            <p class="text-gray-800">{{ $dokter->jenis_kelamin ?? 'Belum diisi' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Tempat, Tanggal Lahir</h4>
                            <p class="text-gray-800">
                                @if($dokter->tempat_lahir && $dokter->tanggal_lahir)
                                    {{ $dokter->tempat_lahir }}, 
                                    @if(is_string($dokter->tanggal_lahir))
                                        {{ date('d F Y', strtotime($dokter->tanggal_lahir)) }}
                                    @else
                                        {{ $dokter->tanggal_lahir->format('d F Y') }}
                                    @endif
                                @else
                                    Belum diisi
                                @endif
                            </p>
                        </div>
                        <div class="md:col-span-2">
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Alamat</h4>
                            <p class="text-gray-800">{{ $dokter->alamat ?? 'Belum diisi' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Profesional</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Spesialisasi</h4>
                        <p class="text-gray-800">{{ $dokter->spesialisasi ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Sub Spesialisasi</h4>
                        <p class="text-gray-800">{{ $dokter->sub_spesialisasi ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Universitas</h4>
                        <p class="text-gray-800">{{ $dokter->universitas ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Tahun Lulus</h4>
                        <p class="text-gray-800">{{ $dokter->tahun_lulus ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Tempat Praktik</h4>
                        <p class="text-gray-800">{{ $dokter->tempat_praktik ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Rumah Sakit</h4>
                        <p class="text-gray-800">{{ $dokter->rumah_sakit ?? 'Belum diisi' }}</p>
                    </div>
                    @if($dokter->pengalaman)
                    <div class="md:col-span-2">
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Pengalaman</h4>
                        <p class="text-gray-800 whitespace-pre-line">{{ $dokter->pengalaman }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<form id="form-hapus" action="{{ route('admin.dokter.destroy', $dokter) }}" method="POST" class="hidden">
    @csrf @method('DELETE')
</form>

@push('scripts')
<script>
    function konfirmasiHapus() {
        Swal.fire({
            title: 'Anda yakin?',
            html: `
                <div class="text-left mb-4">
                    <p class="mb-2">Apakah Anda yakin ingin menghapus data dokter:</p>
                    <div class="flex items-center p-3 bg-red-50 rounded-lg border border-red-200">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($dokter->nama) }}&background=ef4444&color=fff&size=48" class="w-12 h-12 rounded-full mr-3">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $dokter->nama }}</p>
                            <p class="text-sm text-gray-600">{{ $dokter->no_sip }}</p>
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