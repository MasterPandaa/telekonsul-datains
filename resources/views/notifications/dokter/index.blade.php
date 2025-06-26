@extends('layouts.dokter')

@section('dokter-content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800">Notifikasi</h1>
            <p class="text-sm text-gray-600 mt-1">Daftar semua notifikasi yang Anda terima.</p>
        </div>

        <div class="p-6">
            <!-- Tombol Aksi -->
            <div class="flex justify-end space-x-4 mb-6">
                <form action="{{ route('notifications.readAll') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        Tandai Semua Dibaca
                    </button>
                </form>
                <form action="{{ route('notifications.deleteAll') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua notifikasi? Ini tidak dapat diurungkan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                        Hapus Semua Notifikasi
                    </button>
                </form>
            </div>

            <!-- Daftar Notifikasi -->
            <div class="space-y-4">
                @forelse ($notifications as $notification)
                    <div class="flex items-start p-4 rounded-lg border {{ $notification->is_read ? 'bg-white' : 'bg-blue-50 border-blue-200' }}">
                        <div class="flex-shrink-0 mr-4">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full {{ $notification->is_read ? 'bg-gray-100' : 'bg-blue-100' }}">
                                <svg class="h-5 w-5 {{ $notification->is_read ? 'text-gray-500' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="flex-grow">
                            <a href="{{ route('notifications.read', $notification->id) }}" class="hover:underline">
                                <p class="text-sm font-medium text-gray-900">{{ $notification->message }}</p>
                            </a>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ \Carbon\Carbon::parse($notification->created_at)->translatedFormat('d F Y \p\u\k\u\l H:i') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada notifikasi</h3>
                        <p class="mt-1 text-sm text-gray-500">Semua notifikasi Anda akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 