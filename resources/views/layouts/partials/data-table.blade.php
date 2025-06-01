@props(['title', 'createRoute' => null, 'createLabel' => 'Tambah Data', 'columns' => [], 'data' => [], 'pagination' => null])

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <!-- Table Header -->
    <div class="flex items-center justify-between p-4 border-b">
        <h2 class="text-lg font-medium text-gray-800">{{ $title }}</h2>
        
        <div class="flex items-center space-x-2">
            <!-- Filter & Search -->
            <div class="relative mr-2">
                <input type="text" placeholder="Cari..." class="w-48 px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500">
                <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            
            @if($createRoute)
            <a href="{{ $createRoute }}" class="px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                {{ $createLabel }}
            </a>
            @endif
        </div>
    </div>
    
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    @foreach($columns as $column)
                    <th class="px-4 py-3 border-b">{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                {{ $slot }}
            </tbody>
        </table>
    </div>
    
    <!-- Pagination or Empty State -->
    <div class="bg-white px-4 py-3 border-t">
        @if(count($data) === 0)
        <div class="flex flex-col items-center justify-center py-6 text-center">
            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <p class="text-gray-500">Tidak ada data yang tersedia</p>
            @if($createRoute)
            <a href="{{ $createRoute }}" class="mt-2 text-sm text-blue-600 hover:text-blue-800">
                Tambahkan data baru
            </a>
            @endif
        </div>
        @elseif($pagination)
        <div class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
                {{ $pagination->links() }}
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Menampilkan
                        <span class="font-medium">{{ $pagination->firstItem() }}</span>
                        sampai
                        <span class="font-medium">{{ $pagination->lastItem() }}</span>
                        dari
                        <span class="font-medium">{{ $pagination->total() }}</span>
                        hasil
                    </p>
                </div>
                <div>
                    {{ $pagination->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div> 