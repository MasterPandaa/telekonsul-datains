<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Telekonsultasi') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Styles -->
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div id="app">
        @include('layouts.partials.navbar')
        
        <main>
            @yield('content')
        </main>
    </div>
    
    @stack('scripts')
</body>
</html> 