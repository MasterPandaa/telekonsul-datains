@extends('layouts.dosen')
@section('dosen-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Profil Dosen</h1>
    <p class="text-sm text-gray-600">Kelola informasi profil Anda</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Kolom Profil dan Foto -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden h-full">
        <div class="p-6 text-center flex flex-col h-full">
            <div class="w-40 h-40 mx-auto bg-gray-200 mb-4 overflow-hidden rounded-full">
                @if ($dosen->foto)
                    <img src="{{ asset('storage/img/dosen/' . $dosen->foto) }}" alt="{{ $dosen->nama }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-blue-100 text-blue-500">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                @endif
            </div>
            <div class="flex-grow">
                <h2 class="text-xl font-bold text-gray-800">{{ $dosen->nama }}</h2>
                <p class="text-sm text-gray-600 mt-1">NIP: {{ $dosen->nip }}</p>
                <div class="mt-2 inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                    Dosen Pembimbing
                </div>
            </div>
            
            <div class="mt-6 border-t pt-4">
                <button onclick="document.getElementById('uploadFotoModal').classList.remove('hidden')" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md text-sm transition">
                    Ubah Foto Profil
                </button>
            </div>
        </div>
    </div>
    
    <!-- Kolom Informasi Utama -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6 h-full">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Informasi Dasar</h3>
                    <button onclick="document.getElementById('editInformasiModal').classList.remove('hidden')" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                        Edit
                    </button>
                </div>
            </div>
            <div class="p-6 flex-grow">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Nama Lengkap</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $dosen->nama }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">NIP</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $dosen->nip }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Email</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $dosen->email }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">No. HP</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $dosen->no_hp ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <h4 class="text-sm font-medium text-gray-500">Alamat</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $dosen->alamat ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Foto -->
<div id="uploadFotoModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="absolute inset-0 bg-gray-800 opacity-50" onclick="document.getElementById('uploadFotoModal').classList.add('hidden')"></div>
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Upload Foto Profil</h3>
            <div class="mt-2 px-7 py-3">
                <form action="{{ route('dosen.profil.update-foto') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="w-40 h-40 mx-auto bg-gray-200 mb-4 overflow-hidden rounded-full">
                        <img id="preview-foto" src="{{ $dosen->foto ? asset('storage/img/dosen/' . $dosen->foto) : asset('img/dokter/default.jpg') }}" alt="Preview" class="w-full h-full object-cover">
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Foto
                        </label>
                        <input type="file" name="foto" id="foto" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" onchange="previewImage(this);">
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, JPEG, PNG. Maks: 2MB</p>
                    </div>
                    <div class="mt-5 flex justify-center space-x-3">
                        <button type="button" onclick="document.getElementById('uploadFotoModal').classList.add('hidden')" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Informasi -->
<div id="editInformasiModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="absolute inset-0 bg-gray-800 opacity-50" onclick="document.getElementById('editInformasiModal').classList.add('hidden')"></div>
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Edit Informasi Dasar</h3>
            <form action="{{ route('dosen.profil.update-informasi') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" value="{{ $dosen->nama }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label for="nip" class="block text-sm font-medium text-gray-700">NIP</label>
                        <input type="text" name="nip" id="nip" value="{{ $dosen->nip }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ $dosen->email }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-gray-700">No. HP</label>
                        <input type="text" name="no_hp" id="no_hp" value="{{ $dosen->no_hp }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ $dosen->alamat }}</textarea>
                    </div>
                </div>
                <div class="mt-5 flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('editInformasiModal').classList.add('hidden')" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
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

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-foto').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection 