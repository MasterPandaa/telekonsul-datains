@extends('layouts.admin')
@section('admin-content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Tambah Mahasiswa Baru</h1>
        <p class="text-sm text-gray-600">Lengkapi form berikut untuk menambahkan data mahasiswa baru</p>
    </div>
    <a href="{{ route('admin.mahasiswa.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali
    </a>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6">
        <form action="{{ route('admin.mahasiswa.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required 
                           class="w-full px-3 py-2 border @error('nama') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- NIM -->
                <div>
                    <label for="nim" class="block text-sm font-medium text-gray-700 mb-1">NIM <span class="text-red-500">*</span></label>
                    <input type="text" name="nim" id="nim" value="{{ old('nim') }}" required 
                           class="w-full px-3 py-2 border @error('nim') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('nim')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                       class="w-full px-3 py-2 border @error('email') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- No HP -->
            <div>
                <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" 
                       class="w-full px-3 py-2 border @error('no_hp') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('no_hp')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Format: 08xxxxxxxxxx</p>
            </div>
            
            <!-- Alamat -->
            <div>
                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <textarea name="alamat" id="alamat" rows="3" class="w-full px-3 py-2 border @error('alamat') border-red-300 @else border-gray-300 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="pt-4 border-t border-gray-200">
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.mahasiswa.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Simpan Data
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection 