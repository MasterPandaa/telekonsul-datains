@extends('layouts.dokter')
@section('dokter-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Profil Dokter</h1>
    <p class="text-sm text-gray-600">Kelola informasi profil Anda</p>
</div>

<!-- We'll handle alerts with SweetAlert instead of the static alert divs -->

@php
    $fotoUrl = $dokter ? $dokter->foto_url : asset('img/dokter/default.jpg');
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    <!-- Kolom Profil dan Foto -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="p-6 flex flex-col items-center text-center space-y-4">
            <div class="relative">
                <div class="w-40 h-40 rounded-full overflow-hidden ring-4 ring-blue-50 shadow-sm bg-white">
                    <img src="{{ $fotoUrl }}" alt="{{ $profil['nama'] }}" class="w-full h-full object-cover cursor-pointer" onclick="openFotoModal(this.src)">
                </div>
                <button onclick="document.getElementById('uploadFotoModal').classList.remove('hidden')" class="absolute bottom-2 right-2 bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full shadow-md transition" title="Ubah foto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                </button>
            </div>
            <div class="space-y-2">
                <h2 class="text-xl font-bold text-gray-900">{{ $profil['nama'] }}</h2>
                <p class="text-sm text-gray-600">SIP: {{ $profil['no_sip'] }}</p>
                <p class="text-sm text-gray-600">STR: {{ $profil['no_str'] }}</p>
                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full font-medium">
                    {{ $profil['spesialisasi'] }}
                </span>
            </div>

            <div class="w-full pt-4 border-t border-gray-100">
                <button onclick="document.getElementById('uploadFotoModal').classList.remove('hidden')" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md text-sm transition">
                    Ubah Foto Profil
                </button>
            </div>
        </div>
    </div>
    
    <!-- Kolom Informasi Utama -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-5 gap-x-8">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Nama Lengkap</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $profil['nama'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">No. SIP</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $profil['no_sip'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">No. STR</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $profil['no_str'] }}</p>
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
        
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Informasi Profesional</h3>
                    <button onclick="document.getElementById('editAkademikModal').classList.remove('hidden')" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                        Edit
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-5 gap-x-8">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Spesialisasi</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $profil['spesialisasi'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Sub Spesialisasi</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $profil['sub_spesialisasi'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Universitas</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $profil['universitas'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Tahun Lulus</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $profil['tahun_lulus'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Tempat Praktik</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $profil['tempat_praktik'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Rumah Sakit</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $profil['rumah_sakit'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Status</h4>
                        <p class="mt-1 text-sm text-gray-800">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $profil['status'] }}
                            </span>
                        </p>
                    </div>
                    <div class="md:col-span-2">
                        <h4 class="text-sm font-medium text-gray-500">Pengalaman</h4>
                        <p class="mt-1 text-sm text-gray-800">{{ $profil['pengalaman'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="viewFotoModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="absolute inset-0 bg-gray-800 opacity-70" onclick="closeFotoModal()"></div>
    <div class="relative top-16 mx-auto p-4 w-11/12 max-w-2xl">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3 border-b">
                <h3 class="text-sm font-semibold text-gray-800">Foto Profil</h3>
                <button type="button" class="text-gray-500 hover:text-gray-800" onclick="closeFotoModal()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4 bg-gray-50">
                <img id="viewFotoModalImg" src="" alt="Foto Profil" class="w-full max-h-[75vh] object-contain rounded-lg bg-white">
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
                <form action="{{ route('dokter.profil.update-foto') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="w-40 h-40 mx-auto bg-gray-200 mb-4 overflow-hidden rounded-full">
                        <img id="preview-foto" src="{{ $fotoUrl }}" alt="Preview" class="w-full h-full object-cover">
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
            <form action="{{ route('dokter.profil.update-informasi') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" value="{{ $profil['nama'] }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label for="no_sip" class="block text-sm font-medium text-gray-700">Nomor SIP</label>
                        <input type="text" name="no_sip" id="no_sip" value="{{ $profil['no_sip'] }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label for="no_str" class="block text-sm font-medium text-gray-700">Nomor STR</label>
                        <input type="text" name="no_str" id="no_str" value="{{ $profil['no_str'] }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
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
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ $profil['tanggal_lahir'] ? date('Y-m-d', strtotime($profil['tanggal_lahir'])) : '' }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
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

<!-- Modal Edit Akademik/Profesional -->
<div id="editAkademikModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="absolute inset-0 bg-gray-800 opacity-50" onclick="document.getElementById('editAkademikModal').classList.add('hidden')"></div>
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Edit Informasi Profesional</h3>
            <form action="{{ route('dokter.profil.update-akademik') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="spesialisasi" class="block text-sm font-medium text-gray-700">Spesialisasi</label>
                        <input type="text" name="spesialisasi" id="spesialisasi" value="{{ $profil['spesialisasi'] }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label for="sub_spesialisasi" class="block text-sm font-medium text-gray-700">Sub Spesialisasi</label>
                        <input type="text" name="sub_spesialisasi" id="sub_spesialisasi" value="{{ $profil['sub_spesialisasi'] }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="universitas" class="block text-sm font-medium text-gray-700">Universitas</label>
                        <input type="text" name="universitas" id="universitas" value="{{ $profil['universitas'] }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label for="tahun_lulus" class="block text-sm font-medium text-gray-700">Tahun Lulus</label>
                        <input type="number" name="tahun_lulus" id="tahun_lulus" value="{{ $profil['tahun_lulus'] }}" min="1950" max="{{ date('Y') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label for="tempat_praktik" class="block text-sm font-medium text-gray-700">Tempat Praktik</label>
                        <input type="text" name="tempat_praktik" id="tempat_praktik" value="{{ $profil['tempat_praktik'] }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label for="rumah_sakit" class="block text-sm font-medium text-gray-700">Rumah Sakit</label>
                        <input type="text" name="rumah_sakit" id="rumah_sakit" value="{{ $profil['rumah_sakit'] }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            <option value="Aktif" {{ $profil['status'] == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Cuti" {{ $profil['status'] == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                            <option value="Tidak Praktik" {{ $profil['status'] == 'Tidak Praktik' ? 'selected' : '' }}>Tidak Praktik</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label for="pengalaman" class="block text-sm font-medium text-gray-700">Pengalaman</label>
                        <textarea name="pengalaman" id="pengalaman" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ $profil['pengalaman'] }}</textarea>
                    </div>
                </div>
                <div class="mt-5 flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('editAkademikModal').classList.add('hidden')" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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
    function openFotoModal(url) {
        const modal = document.getElementById('viewFotoModal');
        const img = document.getElementById('viewFotoModalImg');
        if (modal && img) {
            img.src = url;
            modal.classList.remove('hidden');
        }
    }

    function closeFotoModal() {
        const modal = document.getElementById('viewFotoModal');
        const img = document.getElementById('viewFotoModalImg');
        if (modal) {
            modal.classList.add('hidden');
        }
        if (img) {
            img.src = '';
        }
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById('preview-foto').src = e.target.result;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // Display SweetAlert notifications if there are any messages
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#3085d6'
        });
        @endif
        
        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: "{{ session('error') }}",
            confirmButtonColor: '#3085d6'
        });
        @endif
        
        @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            html: "{!! implode('<br>', $errors->all()) !!}",
            confirmButtonColor: '#3085d6'
        });
        @endif
    });
</script>
@endpush
@endsection 