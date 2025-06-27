@extends('layouts.base')
@section('content')
<div class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar -->
    @include('layouts.partials.sidebar-dokter')
    
    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-h-screen">
        <!-- Navbar -->
        @include('layouts.partials.navbar-dokter')
        
        <!-- Main Content Area -->
        <main class="flex-1 p-6 md:p-10">
            @yield('dokter-content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
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