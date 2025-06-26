<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <!-- Nav Item - Notifications -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter notification-count">0</span>
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Notifikasi
                </h6>
                <div class="notification-items">
                    <!-- Notifications will be loaded here -->
                </div>
                <a class="dropdown-item text-center small text-gray-500" href="{{ route('notifications.index') }}">
                    Tampilkan Semua
                </a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->dosen->nama }}</span>
                <img class="img-profile rounded-circle" src="{{ asset('storage/img/dosen/' . auth()->user()->dosen->foto) }}">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('dosen.profil.index') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profil
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Keluar
                </a>
            </div>
        </li>
    </ul>
</nav>
<!-- End of Topbar -->

@push('scripts')
<script>
    // Function to load notifications
    function loadNotifications() {
        $.get('{{ route("notifications.getLatest") }}', function(response) {
            $('.notification-count').text(response.unread_count);
            
            let notificationHtml = '';
            if (response.notifications.length > 0) {
                response.notifications.forEach(function(notification) {
                    notificationHtml += `
                        <a class="dropdown-item d-flex align-items-center" href="${notification.url}">
                            <div class="mr-3">
                                <div class="icon-circle bg-primary">
                                    <i class="fas ${notification.icon} text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">${notification.created_at}</div>
                                <span class="${notification.read_at ? 'text-gray-500' : 'font-weight-bold'}">${notification.message}</span>
                            </div>
                        </a>
                    `;
                });
            } else {
                notificationHtml = '<div class="dropdown-item text-center">Tidak ada notifikasi</div>';
            }
            
            $('.notification-items').html(notificationHtml);
        });
    }

    // Load notifications on page load
    loadNotifications();

    // Reload notifications every 30 seconds
    setInterval(loadNotifications, 30000);
</script>
@endpush 