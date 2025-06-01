@extends('layouts.mahasiswa')
@section('mahasiswa-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Profil Saya</h1>
    <p class="text-sm text-gray-600">Informasi data diri dan akademik mahasiswa</p>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if ($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
    <ul class="list-disc pl-5">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Kolom Profil dan Foto -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 text-center">
            <div class="w-32 h-40 mx-auto bg-gray-200 mb-4 overflow-hidden">
                @if ($profil['foto'] && file_exists(public_path($profil['foto'])))
                    <img src="{{ asset($profil['foto']) }}" alt="{{ $profil['nama'] }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-blue-100 text-blue-500">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                @endif
            </div>
            <h2 class="text-xl font-bold text-gray-800">{{ $profil['nama'] }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ $profil['nim'] }}</p>
            <div class="mt-2 inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                {{ $profil['spesialisasi'] }}
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
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
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
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Nama Lengkap</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $profil['nama'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">NIM</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $profil['nim'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Jenis Kelamin</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $profil['jenis_kelamin'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Tempat, Tanggal Lahir</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $profil['tempat_lahir'] }}, {{ $profil['tanggal_lahir_tampil'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Email</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $profil['email'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Telepon</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $profil['telepon'] }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <h4 class="text-sm font-medium text-gray-500">Alamat</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $profil['alamat'] }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Informasi Akademik</h3>
                    <button onclick="document.getElementById('editAkademikModal').classList.remove('hidden')" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                        Edit
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Spesialisasi</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $profil['spesialisasi'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Semester</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $profil['semester'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Tahun Masuk</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $profil['tahun_masuk'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">IPK</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $profil['ipk'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Status</h4>
                        <p class="mt-1 text-sm text-gray-800">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $profil['status'] }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Dosen Pembimbing</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $profil['pembimbing'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Keahlian & Prestasi -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Keahlian Khusus</h3>
                <button onclick="document.getElementById('editKeahlianModal').classList.remove('hidden')" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Edit
                </button>
            </div>
        </div>
        <div class="p-6">
            <ul class="space-y-2">
                @foreach($profil['keahlian'] as $keahlian)
                <li class="flex items-center text-sm text-gray-700">
                    <svg class="flex-shrink-0 mr-2 w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $keahlian }}
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6 lg:col-span-2">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Prestasi & Penghargaan</h3>
                <button onclick="document.getElementById('editPrestasiModal').classList.remove('hidden')" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Edit
                </button>
            </div>
        </div>
        <div class="p-6">
            <ul class="space-y-4">
                @foreach($profil['prestasi'] as $index => $prestasi)
                <li class="flex">
                    <div class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-semibold text-sm mr-3">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-grow">
                        <p class="text-sm text-gray-800">{{ $prestasi['nama'] }}</p>
                        @if($prestasi['tahun'])
                            <p class="text-xs text-gray-500 mt-1">Tahun: {{ $prestasi['tahun'] }}</p>
                        @endif
                    </div>
                </li>
                @endforeach
            </ul>
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
                <form action="{{ route('mahasiswa.profil.update-foto') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <input type="file" name="foto" id="foto" class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100" required>
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Maks: 2MB</p>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none" onclick="document.getElementById('uploadFotoModal').classList.add('hidden')">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none">
                            Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Informasi Dasar -->
<div id="editInformasiModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="absolute inset-0 bg-gray-800 opacity-50" onclick="document.getElementById('editInformasiModal').classList.add('hidden')"></div>
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center">Edit Informasi Dasar</h3>
            <div class="mt-4">
                <form action="{{ route('mahasiswa.profil.update-informasi') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" value="{{ $profil['nama'] }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="nim" class="block text-sm font-medium text-gray-700">NIM</label>
                            <input type="text" name="nim" id="nim" value="{{ $profil['nim'] }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                <option value="Laki-laki" {{ $profil['jenis_kelamin'] == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ $profil['jenis_kelamin'] == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label for="tempat_lahir" class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ $profil['tempat_lahir'] }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ $profil['tanggal_lahir'] }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ $profil['email'] }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="no_hp" class="block text-sm font-medium text-gray-700">Telepon</label>
                            <input type="text" name="no_hp" id="no_hp" value="{{ $profil['telepon'] }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        <div class="md:col-span-2">
                            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea name="alamat" id="alamat" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>{{ $profil['alamat'] }}</textarea>
                        </div>
                    </div>
                    <div class="mt-5 flex justify-end space-x-3">
                        <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none" onclick="document.getElementById('editInformasiModal').classList.add('hidden')">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Akademik -->
<div id="editAkademikModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="absolute inset-0 bg-gray-800 opacity-50" onclick="document.getElementById('editAkademikModal').classList.add('hidden')"></div>
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center">Edit Informasi Akademik</h3>
            <div class="mt-4">
                <form action="{{ route('mahasiswa.profil.update-akademik') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="spesialisasi" class="block text-sm font-medium text-gray-700">Spesialisasi</label>
                            <input type="text" name="spesialisasi" id="spesialisasi" value="{{ $profil['spesialisasi'] }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="semester" class="block text-sm font-medium text-gray-700">Semester</label>
                            <input type="number" name="semester" id="semester" value="{{ $profil['semester'] }}" min="1" max="14" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="tahun_masuk" class="block text-sm font-medium text-gray-700">Tahun Masuk</label>
                            <input type="number" name="tahun_masuk" id="tahun_masuk" value="{{ $profil['tahun_masuk'] }}" min="2000" max="{{ date('Y') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="ipk" class="block text-sm font-medium text-gray-700">IPK</label>
                            <input type="number" name="ipk" id="ipk" value="{{ $profil['ipk'] }}" min="0" max="4" step="0.01" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                <option value="Aktif" {{ $profil['status'] == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Cuti" {{ $profil['status'] == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                                <option value="Lulus" {{ $profil['status'] == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                            </select>
                        </div>
                        <div>
                            <label for="pembimbing" class="block text-sm font-medium text-gray-700">Dosen Pembimbing</label>
                            <input type="text" name="pembimbing" id="pembimbing" value="{{ $profil['pembimbing'] }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                    </div>
                    <div class="mt-5 flex justify-end space-x-3">
                        <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none" onclick="document.getElementById('editAkademikModal').classList.add('hidden')">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Keahlian -->
<div id="editKeahlianModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="absolute inset-0 bg-gray-800 opacity-50" onclick="document.getElementById('editKeahlianModal').classList.add('hidden')"></div>
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center">Edit Keahlian Khusus</h3>
            <div class="mt-4">
                <form action="{{ route('mahasiswa.profil.update-keahlian') }}" method="POST">
                    @csrf
                    <div id="keahlianContainer">
                        @foreach($profil['keahlian'] as $index => $keahlian)
                        <div class="flex items-center mb-3">
                            <input type="text" name="keahlian[]" value="{{ $keahlian }}" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            <button type="button" class="ml-2 text-red-600 hover:text-red-800" onclick="this.parentElement.remove()">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-3">
                        <button type="button" class="text-sm text-blue-600 hover:text-blue-800 flex items-center" onclick="addKeahlian()">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Keahlian
                        </button>
                    </div>
                    <div class="mt-5 flex justify-end space-x-3">
                        <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none" onclick="document.getElementById('editKeahlianModal').classList.add('hidden')">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Prestasi -->
<div id="editPrestasiModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="absolute inset-0 bg-gray-800 opacity-50" onclick="document.getElementById('editPrestasiModal').classList.add('hidden')"></div>
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center">Edit Prestasi & Penghargaan</h3>
            <div class="mt-4">
                <form action="{{ route('mahasiswa.profil.update-prestasi') }}" method="POST">
                    @csrf
                    <div id="prestasiContainer">
                        @foreach($profil['prestasi'] as $index => $prestasi)
                        <div class="flex items-center mb-3">
                            <div class="flex-grow grid grid-cols-4 gap-2">
                                <div class="col-span-3">
                                    <input type="text" name="prestasi[]" value="{{ $prestasi['nama'] }}" placeholder="Nama prestasi" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                </div>
                                <div>
                                    <input type="text" name="tahun[]" value="{{ $prestasi['tahun'] }}" placeholder="Tahun" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                            </div>
                            <button type="button" class="ml-2 text-red-600 hover:text-red-800" onclick="this.parentElement.remove()">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-3">
                        <button type="button" class="text-sm text-blue-600 hover:text-blue-800 flex items-center" onclick="addPrestasi()">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Prestasi
                        </button>
                    </div>
                    <div class="mt-5 flex justify-end space-x-3">
                        <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none" onclick="document.getElementById('editPrestasiModal').classList.add('hidden')">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function addKeahlian() {
    const container = document.getElementById('keahlianContainer');
    const div = document.createElement('div');
    div.className = 'flex items-center mb-3';
    div.innerHTML = `
        <input type="text" name="keahlian[]" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
        <button type="button" class="ml-2 text-red-600 hover:text-red-800" onclick="this.parentElement.remove()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </button>
    `;
    container.appendChild(div);
}

function addPrestasi() {
    const container = document.getElementById('prestasiContainer');
    const div = document.createElement('div');
    div.className = 'flex items-center mb-3';
    div.innerHTML = `
        <div class="flex-grow grid grid-cols-4 gap-2">
            <div class="col-span-3">
                <input type="text" name="prestasi[]" placeholder="Nama prestasi" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
            </div>
            <div>
                <input type="text" name="tahun[]" placeholder="Tahun" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
        </div>
        <button type="button" class="ml-2 text-red-600 hover:text-red-800" onclick="this.parentElement.remove()">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </button>
    `;
    container.appendChild(div);
}
</script>
@endsection 