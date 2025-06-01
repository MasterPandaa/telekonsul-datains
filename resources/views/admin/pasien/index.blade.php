@extends('layouts.admin')
@section('admin-content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-blue-700">Data Pasien</h1>
    <a href="{{ route('admin.pasien.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Tambah Pasien</a>
</div>
@if(session('success'))
    <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
@endif
<div class="bg-white rounded shadow p-4 overflow-x-auto">
    <table class="min-w-full">
        <thead>
            <tr class="bg-blue-50">
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">Nama</th>
                <th class="px-4 py-2">NIK</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Alamat</th>
                <th class="px-4 py-2">No HP</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pasiens as $psn)
            <tr class="border-b">
                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                <td class="px-4 py-2">{{ $psn->nama }}</td>
                <td class="px-4 py-2">{{ $psn->nik }}</td>
                <td class="px-4 py-2">{{ $psn->email }}</td>
                <td class="px-4 py-2">{{ $psn->alamat }}</td>
                <td class="px-4 py-2">{{ $psn->no_hp }}</td>
                <td class="px-4 py-2 flex space-x-2">
                    <a href="{{ route('admin.pasien.edit', $psn) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">Edit</a>
                    <form action="{{ route('admin.pasien.destroy', $psn) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 