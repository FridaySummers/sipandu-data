@php($fe = \Illuminate\Support\Str::startsWith(request()->path(), 'fe'))
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="user-info">
            <div class="user-avatar"><i class="fas fa-user-tie"></i></div>
            <div class="user-details">
                <h4 id="sidebar-user-name">{{ auth()->user()->name ?? 'User' }}</h4>
                <p id="sidebar-user-role">{{ auth()->user()->position ?? 'Role' }}</p>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav-menu">
            <li class="nav-item {{ $fe ? (request()->is('fe/dashboard') ? 'active' : '') : (Route::is('dashboard') ? 'active' : '') }}">
                <a href="{{ $fe ? url('/fe/dashboard') : route('dashboard') }}" class="nav-link"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
            </li>
            <li class="nav-item {{ $fe ? (request()->is('fe/datamanagement') ? 'active' : '') : (Route::is('datamanagement') ? 'active' : '') }}">
                <a href="{{ $fe ? url('/fe/datamanagement') : route('datamanagement') }}" class="nav-link"><i class="fas fa-database"></i><span>Data Management</span></a>
            </li>
            <li class="nav-item {{ $fe ? (request()->is('fe/reports') ? 'active' : '') : (Route::is('reports') ? 'active' : '') }}">
                <a href="{{ $fe ? url('/fe/reports') : route('reports') }}" class="nav-link"><i class="fas fa-chart-bar"></i><span>Laporan & Analisis</span></a>
            </li>
            <li class="nav-item {{ $fe ? (request()->is('fe/forum') ? 'active' : '') : (Route::is('forum') ? 'active' : '') }}">
                <a href="{{ $fe ? url('/fe/forum') : route('forum') }}" class="nav-link"><i class="fas fa-comments"></i><span>Forum Diskusi</span></a>
            </li>
            <li class="nav-item {{ $fe ? (request()->is('fe/calendar') ? 'active' : '') : (Route::is('calendar') ? 'active' : '') }}">
                <a href="{{ $fe ? url('/fe/calendar') : route('calendar') }}" class="nav-link"><i class="fas fa-calendar"></i><span>Agenda & Kalender</span></a>
            </li>
            <li class="nav-item {{ $fe ? (request()->is('fe/dinas-status') ? 'active' : '') : (Route::is('dinas-status') ? 'active' : '') }}">
                <a href="{{ $fe ? url('/fe/dinas-status') : route('dinas-status') }}" class="nav-link"><i class="fas fa-building"></i><span>Status Dinas</span></a>
            </li>
            <li class="nav-item {{ $fe ? (request()->is('fe/settings') ? 'active' : '') : (Route::is('settings') ? 'active' : '') }}">
                <a href="{{ $fe ? url('/fe/settings') : route('settings') }}" class="nav-link"><i class="fas fa-cog"></i><span>Pengaturan</span></a>
            </li>
            <li class="nav-item {{ $fe ? (request()->is('fe/dpmptsp') ? 'active' : '') : '' }}">
                <a href="{{ url('/fe/dpmptsp') }}" class="nav-link"><i class="fas fa-chart-line"></i><span>DPMPTSP</span></a>
            </li>
            <li class="nav-item {{ $fe ? (request()->is('fe/perdagangan') ? 'active' : '') : '' }}">
                <a href="{{ url('/fe/perdagangan') }}" class="nav-link"><i class="fas fa-cart-shopping"></i><span>Perdagangan</span></a>
            </li>
            <li class="nav-item {{ $fe ? (request()->is('fe/perindustrian') ? 'active' : '') : '' }}">
                <a href="{{ url('/fe/perindustrian') }}" class="nav-link"><i class="fas fa-industry"></i><span>Perindustrian</span></a>
            </li>
            <li class="nav-item {{ $fe ? (request()->is('fe/koperasi') ? 'active' : '') : '' }}">
                <a href="{{ url('/fe/koperasi') }}" class="nav-link"><i class="fas fa-people-group"></i><span>Koperasi</span></a>
            </li>
            <li class="nav-item {{ $fe ? (request()->is('fe/tanaman-pangan') ? 'active' : '') : '' }}">
                <a href="{{ url('/fe/tanaman-pangan') }}" class="nav-link"><i class="fas fa-seedling"></i><span>Tanaman Pangan</span></a>
            </li>
            <li class="nav-item {{ $fe ? (request()->is('fe/perkebunan') ? 'active' : '') : '' }}">
                <a href="{{ url('/fe/perkebunan') }}" class="nav-link"><i class="fas fa-leaf"></i><span>Perkebunan</span></a>
            </li>
            <li class="nav-item {{ $fe ? (request()->is('fe/ketapang') ? 'active' : '') : '' }}">
                <a href="{{ url('/fe/ketapang') }}" class="nav-link"><i class="fas fa-tree"></i><span>Ketapang</span></a>
            </li>
            <li class="nav-item {{ $fe ? (request()->is('fe/pariwisata') ? 'active' : '') : '' }}">
                <a href="{{ url('/fe/pariwisata') }}" class="nav-link"><i class="fas fa-umbrella-beach"></i><span>Pariwisata</span></a>
            </li>
            <li class="nav-item {{ $fe ? (request()->is('fe/dlh') ? 'active' : '') : '' }}">
                <a href="{{ url('/fe/dlh') }}" class="nav-link"><i class="fas fa-recycle"></i><span>DLH</span></a>
            </li>
            <li class="nav-item {{ $fe ? (request()->is('fe/perikanan') ? 'active' : '') : '' }}">
                <a href="{{ url('/fe/perikanan') }}" class="nav-link"><i class="fas fa-fish"></i><span>Perikanan</span></a>
            </li>
        </ul>
    </nav>
</aside>
