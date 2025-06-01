@extends('layouts.pasien')

@section('pasien-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Profil Saya</h1>
    <p class="text-sm text-gray-600">Informasi data diri dan riwayat kesehatan</p>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
    <span class="block sm:inline">{{ session('error') }}</span>
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
            <div class="w-32 h-32 mx-auto bg-gray-200 mb-4 overflow-hidden rounded-full">
                @if($pasien->foto)
                    <img src="{{ asset('img/pasien/' . $pasien->foto) }}" alt="Foto Profil" class="w-full h-full object-cover">
                @else
                    <img src="{{ asset('img/pasien/default.jpg') }}" alt="Foto Profil" class="w-full h-full object-cover">
                @endif
            </div>
            <h2 class="text-xl font-bold text-gray-800">{{ $pasien->nama }}</h2>
            <p class="text-sm text-gray-600 mt-1">ID: P{{ str_pad($pasien->id, 5, '0', STR_PAD_LEFT) }}</p>
            <div class="mt-2 inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                Aktif
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Nama Lengkap</p>
                        <p class="font-medium">{{ $pasien->nama }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Jenis Kelamin</p>
                        <p class="font-medium">{{ $pasien->jenis_kelamin ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Tempat, Tanggal Lahir</p>
                        <p class="font-medium">
                            @if($pasien->tempat_lahir && $pasien->tanggal_lahir)
                                @php
                                    $tanggal_lahir = is_string($pasien->tanggal_lahir) ? $pasien->tanggal_lahir : $pasien->tanggal_lahir->format('d M Y');
                                @endphp
                                {{ $pasien->tempat_lahir }}, {{ $tanggal_lahir }}
                            @else
                                Belum diisi
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Usia</p>
                        <p class="font-medium">{{ $pasien->usia ? $pasien->usia . ' tahun' : 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Email</p>
                        <p class="font-medium">{{ $pasien->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Telepon</p>
                        <p class="font-medium">{{ $pasien->no_hp ?? 'Belum diisi' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-500 mb-1">Alamat</p>
                        <p class="font-medium">{{ $pasien->alamat ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">NIK</p>
                        <p class="font-medium">{{ $pasien->nik ?? 'Belum diisi' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Informasi Medis</h3>
                    <button onclick="document.getElementById('editMedisModal').classList.remove('hidden')" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                        Edit
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Tinggi Badan</p>
                        <p class="font-medium">{{ $pasien->tinggi_badan ? $pasien->tinggi_badan . ' cm' : 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Berat Badan</p>
                        <p class="font-medium">{{ $pasien->berat_badan ? $pasien->berat_badan . ' kg' : 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">BMI (Indeks Massa Tubuh)</p>
                        <p class="font-medium">
                            @if($pasien->bmi)
                                {{ $pasien->bmi }} ({{ $pasien->kategori_bmi }})
                            @else
                                Belum diisi
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Tekanan Darah</p>
                        <p class="font-medium">{{ $pasien->tekanan_darah ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Riwayat Alergi</p>
                        <p class="font-medium">{{ $pasien->alergi ?? 'Tidak ada' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-500 mb-1">Riwayat Penyakit</p>
                        <p class="font-medium">{{ $pasien->riwayat_penyakit ?? 'Tidak ada riwayat penyakit kronis' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Riwayat Konsultasi Terbaru -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6 lg:col-span-3">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Riwayat Konsultasi Terbaru</h3>
                <a href="{{ route('pasien.riwayat.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    Lihat Semua
                </a>
            </div>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keluhan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnosa</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">10 Mei 2023</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">Dr. Mahasiswa 1</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Sakit kepala dan demam</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Demam tifoid</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Selesai
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">28 April 2023</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">Dr. Mahasiswa 2</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Batuk dan pilek</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">ISPA</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Selesai
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
                <form action="{{ route('pasien.profil.upload-foto') }}" method="POST" enctype="multipart/form-data">
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
                <form action="{{ route('pasien.profil.update-informasi') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" value="{{ $pasien->nama }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                <option value="Laki-laki" {{ $pasien->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ $pasien->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label for="tempat_lahir" class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ $pasien->tempat_lahir }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ $pasien->tanggal_lahir ? (is_string($pasien->tanggal_lahir) ? $pasien->tanggal_lahir : $pasien->tanggal_lahir->format('Y-m-d')) : '' }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ $pasien->email }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label for="no_hp" class="block text-sm font-medium text-gray-700">Telepon</label>
                            <input type="text" name="no_hp" id="no_hp" value="{{ $pasien->no_hp }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea name="alamat" id="alamat" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ $pasien->alamat }}</textarea>
                        </div>
                        <div>
                            <label for="nik" class="block text-sm font-medium text-gray-700">NIK</label>
                            <input type="text" name="nik" id="nik" value="{{ $pasien->nik }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500">Masukkan 16 digit NIK Anda</p>
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

<!-- Modal Edit Informasi Medis -->
<div id="editMedisModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="absolute inset-0 bg-gray-800 opacity-50" onclick="document.getElementById('editMedisModal').classList.add('hidden')"></div>
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center">Edit Informasi Medis</h3>
            <div class="mt-4">
                <form action="{{ route('pasien.profil.update-medis') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="tinggi_badan" class="block text-sm font-medium text-gray-700">Tinggi Badan (cm)</label>
                            <input type="number" name="tinggi_badan" id="tinggi_badan" value="{{ $pasien->tinggi_badan }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="berat_badan" class="block text-sm font-medium text-gray-700">Berat Badan (kg)</label>
                            <input type="number" name="berat_badan" id="berat_badan" value="{{ $pasien->berat_badan }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="tekanan_darah" class="block text-sm font-medium text-gray-700">Tekanan Darah</label>
                            <input type="text" name="tekanan_darah" id="tekanan_darah" value="{{ $pasien->tekanan_darah }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Contoh: 120/80">
                        </div>
                        <div>
                            <label for="alergi" class="block text-sm font-medium text-gray-700">Riwayat Alergi</label>
                            <input type="text" name="alergi" id="alergi" value="{{ $pasien->alergi }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Contoh: Seafood, Debu, dll">
                        </div>
                        <div class="md:col-span-2">
                            <label for="riwayat_penyakit" class="block text-sm font-medium text-gray-700">Riwayat Penyakit</label>
                            <textarea name="riwayat_penyakit" id="riwayat_penyakit" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Contoh: Diabetes, Hipertensi, dll">{{ $pasien->riwayat_penyakit }}</textarea>
                        </div>
                    </div>
                    <div class="mt-5 flex justify-end space-x-3">
                        <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none" onclick="document.getElementById('editMedisModal').classList.add('hidden')">
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
@endsection 