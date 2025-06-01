@extends('layouts.admin')
@section('admin-content')
<h1 class="text-2xl font-bold text-blue-700 mb-6">Edit Pasien</h1>
<form action="{{ route('admin.pasien.update', $pasien) }}" method="POST" class="bg-white rounded shadow p-6 max-w-lg">
    @csrf @method('PUT')
    <div class="mb-4">
        <label class="block text-gray-700">Nama</label>
        <input type="text" name="nama" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-300" required value="{{ old('nama', $pasien->nama) }}">
        @error('nama')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">NIK</label>
        <input type="text" name="nik" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-300" required value="{{ old('nik', $pasien->nik) }}">
        @error('nik')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Email</label>
        <input type="email" name="email" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-300" required value="{{ old('email', $pasien->email) }}">
        @error('email')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Alamat</label>
        <input type="text" name="alamat" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-300" value="{{ old('alamat', $pasien->alamat) }}">
    </div>
    <div class="mb-6">
        <label class="block text-gray-700">No HP</label>
        <input type="text" name="no_hp" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-300" value="{{ old('no_hp', $pasien->no_hp) }}">
    </div>
    <div class="flex justify-end space-x-2">
        <a href="{{ route('admin.pasien.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">Batal</a>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Update</button>
    </div>
</form>
@endsection 