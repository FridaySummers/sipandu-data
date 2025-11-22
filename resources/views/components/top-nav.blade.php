@php($fe = \Illuminate\Support\Str::startsWith(request()->path(), 'fe'))
<nav class="top-nav">
    <div class="nav-left">
        <button class="sidebar-toggle" id="sidebar-toggle"><i class="fas fa-bars"></i></button>
        <div class="nav-logo"><i class="fas fa-chart-line"></i><span>SIPANDU DATA</span></div>
    </div>
    <div class="nav-center">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Cari data, OPD, atau fitur" />
        </div>
    </div>
    <div class="nav-right">
        <div class="nav-item notifications" id="notifications" data-dropdown="notification-dropdown">
            <i class="fas fa-bell"></i>
            <span class="badge" id="notification-count">2</span>
            <div class="dropdown notification-dropdown" id="notification-dropdown">
                <div class="dropdown-header">
                    <h3>Notifikasi</h3>
                    <button class="mark-all-read">Tandai Semua Dibaca</button>
                </div>
                <div class="notification-list" id="notification-list"></div>
            </div>
        </div>
        <div class="nav-item user-menu" id="user-menu" data-dropdown="user-dropdown">
            <div class="user-avatar"><i class="fas fa-user"></i></div>
            <span class="user-name" id="user-name">{{ auth()->user()->name ?? 'User' }}</span>
            <i class="fas fa-chevron-down"></i>
            <div class="dropdown user-dropdown" id="user-dropdown">
                <a href="{{ $fe ? url('/fe/dashboard') : route('dashboard') }}" class="dropdown-item"><i class="fas fa-home"></i> Dashboard</a>
                @if($fe)
                    <a href="#" class="dropdown-item" id="logout-btn"><i class="fas fa-sign-out-alt"></i> Keluar</a>
                @else
                    <form method="POST" action="{{ route('logout') }}" class="dropdown-item" style="padding:0">
                        @csrf
                        <button type="submit" class="btn btn-link" style="width:100%; text-align:left"><i class="fas fa-sign-out-alt"></i> Keluar</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</nav>
