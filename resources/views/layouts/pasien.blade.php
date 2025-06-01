@extends('layouts.base')

@section('content')
<div class="min-h-screen bg-gray-50">
    @include('layouts.partials.pasien-header')
    
    <main class="container mx-auto px-4 py-6">
        @yield('pasien-content')
    </main>
    
    @include('layouts.partials.footer')
</div>
@endsection 