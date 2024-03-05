<nav class="sidebar-nav scroll-sidebar" data-simplebar="">
    <ul id="sidebarnav">
        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Home</span>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->is('home') ? 'active' : '' }}" href="{{ route('home') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
            </a>
        </li>
        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">MASTER</span>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->is('category') || request()->is('category/*') ? 'active' : '' }}" href="{{ route('category.index') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-article"></i>
                </span>
                <span class="hide-menu">Kategori</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->is('organization') || request()->is('organization/*') ? 'active' : '' }}" href="{{ route('organization.index') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-building-community"></i>
                </span>
                <span class="hide-menu">Instansi</span>
            </a>
        </li>
        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">REPORT</span>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->is('report-daily') || request()->is('report-daily-result') ? 'active' : '' }}" href="{{ route('report-daily') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-file-report"></i>
                </span>
                <span class="hide-menu">Harian</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->is('report-monthly') || request()->is('report-monthly-result') ? 'active' : '' }}" href="{{ route('report-monthly') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-file-report"></i>
                </span>
                <span class="hide-menu">Bulanan</span>
            </a>
        </li>
        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">USER</span>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->is('account') || request()->is('account/*') ? 'active' : '' }}" href="{{ route('account.index') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-users"></i>
                </span>
                <span class="hide-menu">Akun</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->is('user-admin') || request()->is('user-admin/*') ? 'active' : '' }}" href="{{ route('user-admin.index') }}" aria-expanded="false">
                <span>
                    <i class="ti ti-user"></i>
                </span>
                <span class="hide-menu">User Admin</span>
            </a>
        </li>
    </ul>
</nav>
