@extends('layouts.pasien')

@section('pasien-content')
<style>
    /* Modern profile styles */
    .profile-container {
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.1), 0 8px 10px -6px rgba(59, 130, 246, 0.1);
        transition: all 0.3s ease;
    }
    
    .profile-header {
        background: linear-gradient(120deg, #4f46e5, #3b82f6);
        padding: 0.75rem 1rem;
        position: relative;
        overflow: hidden;
    }
    
    .profile-header::before {
        content: "";
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        pointer-events: none;
    }
    
    .profile-avatar {
        background: linear-gradient(120deg, #c7d2fe, #a5b4fc);
        border: 3px solid rgba(255, 255, 255, 0.7);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }
    
    .profile-avatar:hover {
        transform: scale(1.05);
    }
    
    .profile-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .profile-card:hover {
        border-left-color: #3b82f6;
        transform: translateX(3px);
    }
    
    .edit-button {
        transition: all 0.3s ease;
    }
    
    .edit-button:hover {
        transform: scale(1.05);
    }
    
    .info-label {
        color: #6b7280;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }
    
    .info-value {
        color: #1f2937;
        font-weight: 500;
    }
    
    .status-badge {
        background: linear-gradient(120deg, #dcfce7, #bbf7d0);
        color: #15803d;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
</style>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
        <span class="bg-gradient-to-r from-indigo-600 to-blue-500 bg-clip-text text-transparent">Profil Saya</span>
        <span class="ml-3 px-3 py-1 bg-gradient-to-r from-indigo-100 to-blue-100 text-indigo-800 text-xs rounded-full font-medium">Personal Info</span>
    </h1>
    <p class="text-sm text-gray-600 mt-1">Kelola informasi profil dan data kesehatan Anda</p>
</div>

<!-- Notifikasi akan ditampilkan melalui JavaScript -->

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Kolom Profil dan Foto -->
    <div class="profile-container bg-white">
        <div class="profile-header text-white">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-lg">Foto Profil</h3>
                <div class="status-badge">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Aktif</span>
                </div>
            </div>
        </div>
        <div class="p-6 text-center">
            <div class="w-32 h-32 mx-auto profile-avatar rounded-full overflow-hidden mb-4">
                @if($pasien->foto)
                    <img src="{{ asset('img/pasien/' . $pasien->foto) }}" alt="Foto Profil" class="w-full h-full object-cover">
                @else
                    <img src="{{ asset('img/pasien/default.jpg') }}" alt="Foto Profil" class="w-full h-full object-cover">
                @endif
            </div>
            <h2 class="text-xl font-bold text-gray-800">{{ $pasien->nama }}</h2>
            <p class="text-sm text-gray-600 mt-1">ID: P{{ str_pad($pasien->id, 5, '0', STR_PAD_LEFT) }}</p>
            
            <div class="mt-6 border-t pt-4">
                <button onclick="document.getElementById('uploadFotoModal').classList.remove('hidden')" 
                        class="w-full bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600 text-white font-medium py-2 px-4 rounded-lg text-sm transition-all duration-300 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <div class="flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Ubah Foto Profil
                    </div>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Kolom Informasi Utama -->
    <div class="lg:col-span-2 space-y-6">
        <div class="profile-container bg-white">
            <div class="profile-header text-white">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Informasi Dasar</h3>
                    <button onclick="document.getElementById('editInformasiModal').classList.remove('hidden')" 
                            class="edit-button bg-white bg-opacity-20 text-white text-sm px-3 py-1 rounded-full flex items-center transition-all duration-300">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                        Edit
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="profile-card p-3 rounded-lg hover:bg-gray-50">
                        <p class="info-label">Nama Lengkap</p>
                        <p class="info-value">{{ $pasien->nama }}</p>
                    </div>
                    <div class="profile-card p-3 rounded-lg hover:bg-gray-50">
                        <p class="info-label">Jenis Kelamin</p>
                        <p class="info-value">{{ $pasien->jenis_kelamin ?? 'Belum diisi' }}</p>
                    </div>
                    <div class="profile-card p-3 rounded-lg hover:bg-gray-50">
                        <p class="info-label">Tempat, Tanggal Lahir</p>
                        <p class="info-value">
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
                    <div class="profile-card p-3 rounded-lg hover:bg-gray-50">
                        <p class="info-label">Usia</p>
                        <p class="info-value">{{ $pasien->usia ? $pasien->usia . ' tahun' : 'Belum diisi' }}</p>
                    </div>
                    <div class="profile-card p-3 rounded-lg hover:bg-gray-50">
                        <p class="info-label">Email</p>
                        <p class="info-value">{{ $pasien->email }}</p>
                    </div>
                    <div class="profile-card p-3 rounded-lg hover:bg-gray-50">
                        <p class="info-label">Telepon</p>
                        <p class="info-value">{{ $pasien->no_hp ?? 'Belum diisi' }}</p>
                    </div>
                    <div class="profile-card p-3 rounded-lg hover:bg-gray-50 md:col-span-2">
                        <p class="info-label">Alamat</p>
                        <p class="info-value">{{ $pasien->alamat ?? 'Belum diisi' }}</p>
                    </div>
                    <div class="profile-card p-3 rounded-lg hover:bg-gray-50">
                        <p class="info-label">NIK</p>
                        <p class="info-value">{{ $pasien->nik ?? 'Belum diisi' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="profile-container bg-white">
            <div class="profile-header text-white">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Informasi Medis</h3>
                    <button onclick="document.getElementById('editMedisModal').classList.remove('hidden')" 
                            class="edit-button bg-white bg-opacity-20 text-white text-sm px-3 py-1 rounded-full flex items-center transition-all duration-300">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                        Edit
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="profile-card p-3 rounded-lg hover:bg-gray-50">
                        <p class="info-label">Tinggi Badan</p>
                        <p class="info-value">{{ $pasien->tinggi_badan ? $pasien->tinggi_badan . ' cm' : 'Belum diisi' }}</p>
                    </div>
                    <div class="profile-card p-3 rounded-lg hover:bg-gray-50">
                        <p class="info-label">Berat Badan</p>
                        <p class="info-value">{{ $pasien->berat_badan ? $pasien->berat_badan . ' kg' : 'Belum diisi' }}</p>
                    </div>
                    <div class="profile-card p-3 rounded-lg hover:bg-gray-50">
                        <p class="info-label">BMI (Indeks Massa Tubuh)</p>
                        <p class="info-value">
                            @if($pasien->bmi)
                                {{ $pasien->bmi }} ({{ $pasien->kategori_bmi }})
                            @else
                                Belum diisi
                            @endif
                        </p>
                    </div>
                    <div class="profile-card p-3 rounded-lg hover:bg-gray-50">
                        <p class="info-label">Tekanan Darah</p>
                        <p class="info-value">{{ $pasien->tekanan_darah ?? 'Belum diisi' }}</p>
                    </div>
                    <div class="profile-card p-3 rounded-lg hover:bg-gray-50">
                        <p class="info-label">Riwayat Alergi</p>
                        <p class="info-value">{{ $pasien->alergi ?? 'Tidak ada' }}</p>
                    </div>
                    <div class="profile-card p-3 rounded-lg hover:bg-gray-50 md:col-span-2">
                        <p class="info-label">Riwayat Penyakit</p>
                        <p class="info-value">{{ $pasien->riwayat_penyakit ?? 'Tidak ada riwayat penyakit kronis' }}</p>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tampilkan notifikasi jika ada flash message
    @if(session('success'))
        showNotification("{{ session('success') }}", 'success');
    @endif
    
    @if(session('error'))
        showNotification("{{ session('error') }}", 'error');
    @endif
    
    @if(session('warning'))
        showNotification("{{ session('warning') }}", 'warning');
    @endif
    
    @if(session('info'))
        showNotification("{{ session('info') }}", 'info');
    @endif

    // Tampilkan notifikasi untuk error validasi jika ada
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            showNotification("{{ $error }}", 'error');
        @endforeach
    @endif
});

// Fungsi untuk menampilkan notifikasi menarik
function showNotification(message, type = 'success') {
    // Hapus notifikasi lama jika ada
    const oldNotification = document.getElementById('healsai-notification');
    if (oldNotification) {
        oldNotification.remove();
    }
    
    // Tentukan warna berdasarkan tipe
    let bgColor, iconSvg;
    if (type === 'success') {
        bgColor = 'from-emerald-500 to-green-500';
        iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
    } else if (type === 'error') {
        bgColor = 'from-red-500 to-rose-500';
        iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
    } else if (type === 'warning') {
        bgColor = 'from-amber-500 to-yellow-500';
        iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
    } else if (type === 'info') {
        bgColor = 'from-blue-500 to-indigo-500';
        iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
    }
    
    // Buat elemen notifikasi
    const notification = document.createElement('div');
    notification.id = 'healsai-notification';
    notification.className = 'fixed top-4 right-4 z-50 flex items-center p-4 mb-4 rounded-xl shadow-lg text-white bg-gradient-to-r ' + bgColor + ' transition-all duration-500 transform translate-x-full opacity-0';
    notification.innerHTML = `
        <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-lg bg-white/25 mr-3">
            ${iconSvg}
        </div>
        <div class="text-sm font-medium">${message}</div>
        <button type="button" class="ml-4 -mx-1.5 -my-1.5 rounded-lg p-1.5 inline-flex h-8 w-8 bg-white/10 hover:bg-white/20" onclick="this.parentElement.remove()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    `;
    
    // Tambahkan ke DOM
    document.body.appendChild(notification);
    
    // Tampilkan dengan animasi
    setTimeout(() => {
        notification.classList.remove('translate-x-full', 'opacity-0');
    }, 10);
    
    // Sembunyikan setelah beberapa detik
    setTimeout(() => {
        notification.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            notification.remove();
        }, 500);
    }, 5000);
}
</script>
@endpush
@endsection 