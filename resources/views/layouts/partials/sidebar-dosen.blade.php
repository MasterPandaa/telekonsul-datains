<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dosen.dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/BLUE_ASSRI.png') }}" alt="Logo" style="width: 40px;">
        </div>
        <div class="sidebar-brand-text mx-3">ASSRI</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dosen.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Supervisi
    </div>

    <!-- Nav Item - Supervisi -->
    <li class="nav-item {{ request()->routeIs('dosen.supervisi.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dosen.supervisi.index') }}">
            <i class="fas fa-fw fa-clipboard-check"></i>
            <span>Supervisi Konsultasi</span>
        </a>
    </li>

    <!-- Nav Item - Penilaian -->
    <li class="nav-item {{ request()->routeIs('dosen.penilaian.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dosen.penilaian.index') }}">
            <i class="fas fa-fw fa-star"></i>
            <span>Penilaian</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Pengaturan
    </div>

    <!-- Nav Item - Profil -->
    <li class="nav-item {{ request()->routeIs('dosen.profil.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dosen.profil.index') }}">
            <i class="fas fa-fw fa-user"></i>
            <span>Profil</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar --> 