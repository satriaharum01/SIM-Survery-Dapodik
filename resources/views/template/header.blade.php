<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon">
            <img style="width:auto; height:50px;" src="{{ asset('landing/login/img/logo-dikdasmen.png') }}" alt="logo">
        </div>
        <div class="sidebar-brand-text mx-3">
            SI Survey
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    @if(Auth::user()->level == 'Admin')
    <li class="nav-item {{ (request()->is('admin/dashboard')) ? 'active' : '' }}">
        <a class="nav-link" href="{{route('admin.dashboard')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Master Data
    </div>
    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item {{ (request()->is('admin/sekolah')) ? 'active' : '' }}{{ (request()->is('admin/sekolah/*')) ? 'active' : '' }}">
        <a class="nav-link collapsed" href="{{url('admin/sekolah')}}">
            <i class="fas fa-fw fa-landmark"></i>
            <span>Data Sekolah</span>
        </a>
    </li>
    <li class="nav-item {{ (request()->is('admin/survey')) ? 'active' : '' }}{{ (request()->is('admin/survey/*')) ? 'active' : '' }}">
        <a class="nav-link collapsed" href="{{url('admin/survey')}}">
            <i class="fas fa-fw fa-book"></i>
            <span>Data Survey</span>
        </a>
    </li>
    <li class="nav-item {{ (request()->is('admin/pengguna')) ? 'active' : '' }}{{ (request()->is('admin/pengguna/*')) ? 'active' : '' }}">
        <a class="nav-link collapsed" href="{{url('admin/pengguna')}}">
            <i class="fas fa-fw fa-users"></i>
            <span>Data Pengguna</span>
        </a>
    </li>
    <div class="sidebar-heading">
        Content
    </div>
    <li class="nav-item {{ (request()->is('admin/section')) ? 'active' : '' }}">
        <a class="nav-link collapsed" href="{{route('admin.section')}}">
            <i class="fas fa-fw fa-list"></i>
            <span>Data Section</span>
        </a>
    </li>
    <li class="nav-item {{ (request()->is('admin/pertanyaan')) ? 'active' : '' }}">
        <a class="nav-link collapsed" href="{{route('admin.pertanyaan')}}">
            <i class="fas fa-fw fa-list"></i>
            <span>Data Pertanyaan</span>
        </a>
    </li>
    <li class="nav-item {{ (request()->is('admin/jawaban')) ? 'active' : '' }}">
        <a class="nav-link collapsed" href="{{route('admin.jawaban')}}">
            <i class="fas fa-fw fa-list"></i>
            <span>Data Penilaian</span>
        </a>
    </li>
    @else
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Master Data
    </div>
    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item {{ (request()->is('operator/sekolah')) ? 'active' : '' }}{{ (request()->is('operator/sekolah/*')) ? 'active' : '' }}">
        <a class="nav-link collapsed" href="{{url('operator/sekolah')}}">
            <i class="fas fa-fw fa-landmark"></i>
            <span>Data Sekolah</span>
        </a>
    </li>
    <li class="nav-item {{ (request()->is('operator/survey')) ? 'active' : '' }}{{ (request()->is('operator/survey/*')) ? 'active' : '' }}">
        <a class="nav-link collapsed" href="{{url('operator/survey')}}">
            <i class="fas fa-fw fa-book"></i>
            <span>Data Survey</span>
        </a>
    </li>
    @endif
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->