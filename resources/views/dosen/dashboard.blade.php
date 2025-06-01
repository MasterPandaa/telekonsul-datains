@extends('layouts.base')
@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-blue-100 to-green-100">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-2xl mt-10">
        <div class="flex items-center mb-6">
            <img src="https://cdn-icons-png.flaticon.com/512/3774/3774299.png" class="w-14 mr-4">
            <div>
                <h2 class="text-2xl font-bold text-blue-700">Dashboard Dosen</h2>
                <p class="text-gray-600">Halo, <span class="font-semibold">{{ $user->name }}</span></p>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-6 mt-6">
            <a href="#" class="bg-blue-100 hover:bg-blue-200 p-6 rounded-lg flex flex-col items-center shadow transition">
                <svg class="w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 17l4 4 4-4m0-5V3a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v9m10 4h-4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2z"/></svg>
                <span class="font-semibold">Supervisi</span>
            </a>
            <a href="#" class="bg-green-100 hover:bg-green-200 p-6 rounded-lg flex flex-col items-center shadow transition">
                <svg class="w-8 h-8 text-green-600 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                <span class="font-semibold">Penilaian</span>
            </a>
            <a href="#" class="bg-yellow-100 hover:bg-yellow-200 p-6 rounded-lg flex flex-col items-center shadow transition">
                <svg class="w-8 h-8 text-yellow-600 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                <span class="font-semibold">Profil</span>
            </a>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-8 text-right">
            @csrf
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Logout</button>
        </form>
    </div>
</div>
@endsection 