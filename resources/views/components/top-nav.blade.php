<nav class="top-nav">
    <div class="nav-left">
        <button class="sidebar-toggle" id="sidebar-toggle"><i class="fas fa-bars"></i></button>
        <div class="nav-logo"><img src="https://commons.wikimedia.org/wiki/Special:FilePath/Lambang_Kabupaten_Kolaka_Utara.svg" alt="Kolaka Utara" class="logo-img" /><span>SIPANDU DATA</span></div>
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
                <a href="{{ route('dashboard') }}" class="dropdown-item"><i class="fas fa-home"></i> Dashboard</a>
                @auth
                    <form method="POST" action="{{ route('logout') }}" style="padding:0; margin:0">
                        @csrf
                        <button type="submit" class="dropdown-item" style="width:100%; text-align:left"><i class="fas fa-sign-out-alt"></i> Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="dropdown-item"><i class="fas fa-sign-in-alt"></i> Masuk</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
