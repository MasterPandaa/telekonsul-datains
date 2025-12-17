@extends('layouts.admin')

@section('admin-content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Detail Dosen</h1>
        <p class="text-sm text-gray-600">Informasi lengkap dosen</p>
    </div>
    <div class="flex gap-2">
        <button type="button" onclick="konfirmasiHapus()" class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
            Hapus
        </button>
        <a href="{{ route('admin.dosen.edit', $dosen->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
            </svg>
            Edit
        </a>
        <a href="{{ route('admin.dosen.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="md:flex">
        <div class="md:w-1/4 bg-gray-50 p-6 border-r border-gray-200">
            <div class="flex flex-col items-center">
                <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-100 mb-4 flex items-center justify-center text-3xl font-semibold text-blue-700">
                    <img src="{{ $dosen->foto_url }}" alt="{{ $dosen->nama }}" class="w-full h-full object-cover">
                </div>
                <h3 class="text-xl font-bold text-gray-800 text-center">{{ $dosen->nama }}</h3>
                <p class="text-blue-600 font-medium text-center">{{ $dosen->nip }}</p>
            </div>
        </div>

        <div class="md:w-3/4 p-0">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Dosen</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Email</h4>
                        <p class="text-gray-800">{{ $dosen->email }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Jenis Kelamin</h4>
                        <p class="text-gray-800">{{ $dosen->jenis_kelamin ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Nomor Telepon</h4>
                        <p class="text-gray-800">{{ $dosen->no_hp ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Alamat</h4>
                        <p class="text-gray-800">{{ $dosen->alamat ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Terdaftar Pada</h4>
                        <p class="text-gray-800">{{ $dosen->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Terakhir Diperbarui</h4>
                        <p class="text-gray-800">{{ $dosen->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="form-hapus" action="{{ route('admin.dosen.destroy', $dosen) }}" method="POST" class="hidden">
    @csrf @method('DELETE')
</form>

@push('scripts')
<script>
    function konfirmasiHapus() {
        Swal.fire({
            title: 'Anda yakin?',
            html: `
                <div class="text-left mb-4">
                    <p class="mb-2">Apakah Anda yakin ingin menghapus data dosen:</p>
                    <div class="flex items-center p-3 bg-red-50 rounded-lg border border-red-200">
                        <img src="{{ $dosen->foto_url ?? \App\Support\ProfilePhoto::transparentDataUrl() }}" class="w-12 h-12 rounded-full object-cover bg-gray-100 mr-3">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $dosen->nama }}</p>
                            <p class="text-sm text-gray-600">{{ $dosen->nip }}</p>
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
 