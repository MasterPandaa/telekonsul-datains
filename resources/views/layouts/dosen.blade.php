@extends('layouts.base')

@section('body')
<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    @include('layouts.partials.sidebar-dosen')
    
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Navbar -->
        @include('layouts.partials.navbar-dosen')
        
        <!-- Main content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4">
            <div class="container mx-auto px-4">
                @yield('dosen-content')
            </div>
        </main>
    </div>
</div>
@endsection 