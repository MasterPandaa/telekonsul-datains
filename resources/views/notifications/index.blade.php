@extends('layouts.base')

@section('content')
<div class="min-h-screen bg-gray-50">
    @if(Auth::user()->role === 'pasien')
        @include('layouts.partials.pasien-header')
    @elseif(Auth::user()->role === 'mahasiswa')
        @include('layouts.partials.mahasiswa-sidebar')
    @endif
    
    <main class="container mx-auto px-4 py-8 md:py-12 max-w-5xl">
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h1 class="text-xl font-semibold text-gray-800">Notifikasi</h1>
                <form action="{{ route('notifications.readAll') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                        Tandai semua dibaca
                    </button>
                </form>
            </div>
            
            <div class="divide-y divide-gray-200">
                @forelse($notifications as $notification)
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150 {{ $notification->is_read ? '' : 'bg-blue-50' }}">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                @switch($notification->type)
                                    @case('konsultasi_baru')
                                        <div class="bg-blue-100 rounded-full p-2">
                                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                        </div>
                                        @break
                                    @case('konsultasi_ditolak')
                                        <div class="bg-red-100 rounded-full p-2">
                                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        @break
                                    @case('konsultasi_terkonfirmasi')
                                        <div class="bg-green-100 rounded-full p-2">
                                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        @break
                                    @case('konsultasi_akan_dimulai')
                                        <div class="bg-yellow-100 rounded-full p-2">
                                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        @break
                                    @case('rating_baru')
                                        <div class="bg-yellow-100 rounded-full p-2">
                                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                            </svg>
                                        </div>
                                        @break
                                    @case('diagnosis_baru')
                                        <div class="bg-purple-100 rounded-full p-2">
                                            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                        </div>
                                        @break
                                    @default
                                        <div class="bg-gray-100 rounded-full p-2">
                                            <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                @endswitch
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex justify-between">
                                    <p class="text-sm font-medium text-gray-900">{{ $notification->message }}</p>
                                    <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                @if(!$notification->is_read)
                                    <div class="mt-2">
                                        <a href="{{ route('notifications.read', $notification->id) }}" class="text-xs text-blue-600 hover:text-blue-800 hover:underline">
                                            Tandai dibaca
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="mt-4 text-gray-500 text-lg">Tidak ada notifikasi</p>
                        <p class="mt-2 text-gray-400 text-sm">Notifikasi akan muncul di sini ketika ada aktivitas baru</p>
                    </div>
                @endforelse
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $notifications->links() }}
            </div>
        </div>
    </main>
</div>
@endsection 