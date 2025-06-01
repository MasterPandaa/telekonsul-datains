@extends('layouts.admin')
@section('admin-content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Detail Mahasiswa</h1>
        <p class="text-sm text-gray-600">Informasi lengkap data mahasiswa</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('admin.mahasiswa.edit', $mahasiswa) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
            </svg>
            Edit Data
        </a>
        <a href="{{ route('admin.mahasiswa.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="md:flex">
        <!-- Profil Gambar & Info Utama -->
        <div class="md:w-1/3 bg-blue-50 p-6 flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-gray-200">
            <div class="w-32 h-32 rounded-full bg-blue-100 mb-4 overflow-hidden">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($mahasiswa->nama) }}&background=3b82f6&color=fff&size=128" alt="{{ $mahasiswa->nama }}" class="w-full h-full object-cover">
            </div>
            
            <h2 class="text-xl font-bold text-gray-800 text-center">{{ $mahasiswa->nama }}</h2>
            <p class="text-blue-600 font-medium mb-4">{{ $mahasiswa->nim }}</p>
            
            <div class="w-full mt-4 space-y-3">
                <div class="flex items-center justify-center text-sm">
                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ $mahasiswa->email }}</span>
                </div>
                <div class="flex items-center justify-center text-sm">
                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <span>{{ $mahasiswa->no_hp ?? 'Belum diisi' }}</span>
                </div>
            </div>
        </div>
        
        <!-- Informasi Detail -->
        <div class="md:w-2/3 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Informasi Lengkap</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-1">Nama Lengkap</h4>
                    <p class="text-gray-800">{{ $mahasiswa->nama }}</p>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-1">NIM</h4>
                    <p class="text-gray-800">{{ $mahasiswa->nim }}</p>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-1">Email</h4>
                    <p class="text-gray-800">{{ $mahasiswa->email }}</p>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-1">Nomor Telepon</h4>
                    <p class="text-gray-800">{{ $mahasiswa->no_hp ?? 'Belum diisi' }}</p>
                </div>
                
                <div class="md:col-span-2">
                    <h4 class="text-sm font-medium text-gray-500 mb-1">Alamat</h4>
                    <p class="text-gray-800">{{ $mahasiswa->alamat ?? 'Belum diisi' }}</p>
                </div>
            </div>
            
            <div class="mt-8 pt-4 border-t">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Sistem</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Terakhir diperbarui:</span>
                        <span class="text-gray-700 ml-2">{{ $mahasiswa->updated_at->diffForHumans() }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Didaftarkan pada:</span>
                        <span class="text-gray-700 ml-2">{{ $mahasiswa->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Danger Zone -->
<div class="mt-8">
    <h3 class="text-lg font-semibold text-red-600 mb-4">Danger Zone</h3>
    <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-red-500">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-medium text-gray-800">Hapus Data Mahasiswa</h4>
                    <p class="text-sm text-gray-600">Data yang dihapus tidak dapat dikembalikan.</p>
                </div>
                <button 
                    type="button"
                    onclick="konfirmasiHapus()"
                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                    Hapus Permanen
                </button>
                <form id="form-hapus" action="{{ route('admin.mahasiswa.destroy', $mahasiswa) }}" method="POST" class="hidden">
                    @csrf @method('DELETE')
                </form>
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
                    <p class="mb-2">Apakah Anda yakin ingin menghapus data mahasiswa:</p>
                    <div class="flex items-center p-3 bg-red-50 rounded-lg border border-red-200">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($mahasiswa->nama) }}&background=ef4444&color=fff&size=48" class="w-12 h-12 rounded-full mr-3">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $mahasiswa->nama }}</p>
                            <p class="text-sm text-gray-600">{{ $mahasiswa->nim }}</p>
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