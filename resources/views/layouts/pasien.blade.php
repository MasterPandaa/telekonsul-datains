@extends('layouts.base')

@section('content')
<div class="min-h-screen bg-gray-50">
    @include('layouts.partials.header-pasien')
    
    <main class="container mx-auto px-4 py-6">
        @yield('pasien-content')
    </main>
    
    @include('layouts.partials.footer')
</div>

@stack('scripts')

<script>
    // Countdown timer functionality
    document.addEventListener('DOMContentLoaded', function() {
        const countdownElements = document.querySelectorAll('.countdown-timer');
        
        countdownElements.forEach(element => {
            const targetTimestamp = parseInt(element.dataset.target);
            
            if (!isNaN(targetTimestamp)) {
                updateCountdown(element, targetTimestamp);
                
                // Update every second
                setInterval(() => {
                    updateCountdown(element, targetTimestamp);
                }, 1000);
            }
        });
        
        function updateCountdown(element, targetTimestamp) {
            const now = new Date().getTime();
            const timeRemaining = targetTimestamp - now;
            
            if (timeRemaining <= 0) {
                // Time's up, reload page to show the "Masuk Chat" button
                element.querySelector('.time-remaining').textContent = "00:00:00";
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                return;
            }
            
            // Calculate remaining time
            const hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);
            
            // Format time display
            const formattedTime = 
                (hours < 10 ? "0" + hours : hours) + ":" +
                (minutes < 10 ? "0" + minutes : minutes) + ":" +
                (seconds < 10 ? "0" + seconds : seconds);
            
            // Update the time remaining display
            element.querySelector('.time-remaining').textContent = formattedTime;
        }
    });
</script>

@endsection 