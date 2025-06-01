@extends('layouts.base')
@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar -->
    @include('layouts.partials.sidebar')
    <div class="flex-1 flex flex-col min-h-screen">
        <!-- Navbar -->
        @include('layouts.partials.navbar')
        <main class="flex-1 p-6 md:p-10">
            @yield('admin-content')
        </main>
    </div>
</div>
@endsection 