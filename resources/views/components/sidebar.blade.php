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
            {{-- Dashboard --}}
            <li class="nav-item {{ Route::is('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
                </a>
            </li>

            {{-- Data Management --}}
            @php($role = auth()->user()->role ?? null)
            @if($role !== 'user')
            <li class="nav-item {{ Route::is('datamanagement') ? 'active' : '' }}">
                <a href="{{ route('datamanagement') }}" class="nav-link">
                    <i class="fas fa-database"></i><span>Data Management</span>
                </a>
            </li>
            @endif

            {{-- Laporan --}}
            <li class="nav-item {{ Route::is('reports') ? 'active' : '' }}">
                <a href="{{ route('reports') }}" class="nav-link">
                    <i class="fas fa-chart-bar"></i><span>Laporan & Analisis</span>
                </a>
            </li>

            {{-- Forum --}}
            <li class="nav-item {{ Route::is('forum') ? 'active' : '' }}">
                <a href="{{ route('forum') }}" class="nav-link">
                    <i class="fas fa-comments"></i><span>Forum Diskusi</span>
                </a>
            </li>

            {{-- Calendar --}}
            <li class="nav-item {{ Route::is('calendar') ? 'active' : '' }}">
                <a href="{{ route('calendar') }}" class="nav-link">
                    <i class="fas fa-calendar"></i><span>Agenda & Kalender</span>
                </a>
            </li>

            {{-- Status Dinas --}}
            <li class="nav-item {{ Route::is('dinas-status') ? 'active' : '' }}">
                <a href="{{ route('dinas-status') }}" class="nav-link">
                    <i class="fas fa-building"></i><span>Status Dinas</span>
                </a>
            </li>

            {{-- Pengaturan --}}
            <li class="nav-item {{ Route::is('settings') ? 'active' : '' }}">
                <a href="{{ route('settings') }}" class="nav-link">
                    <i class="fas fa-cog"></i><span>Pengaturan</span>
                </a>
            </li>

            {{-- Menu Dinas (Tetap statis dulu tidak apa-apa, atau bisa dihapus jika belum ada routenya) --}}
            <li class="nav-header">Dinas Terkait</li>
            @php($role = auth()->user()->role ?? null)
            @php($userDinas = auth()->user()->dinas_id ? \App\Models\Dinas::find(auth()->user()->dinas_id) : null)
            @php($routeMap = [
                'dpmptsp' => ['name'=>'DPMPTSP','route'=>'dinas.dpmptsp','icon'=>'fas fa-handshake'],
                'dinas-perdagangan' => ['name'=>'Perdagangan','route'=>'dinas.perdagangan','icon'=>'fas fa-store'],
                'dinas-perindustrian' => ['name'=>'Perindustrian','route'=>'dinas.perindustrian','icon'=>'fas fa-industry'],
                'dinas-koperasi-dan-ukm' => ['name'=>'Koperasi','route'=>'dinas.koperasi','icon'=>'fas fa-users'],
                'dinas-pertanian-tanaman-pangan' => ['name'=>'Tanaman Pangan','route'=>'dinas.tanaman-pangan','icon'=>'fas fa-seedling'],
                'dinas-perkebunan-dan-peternakan' => ['name'=>'Perkebunan','route'=>'dinas.perkebunan','icon'=>'fas fa-tree'],
                'dinas-perikanan' => ['name'=>'Perikanan','route'=>'dinas.perikanan','icon'=>'fas fa-fish'],
                'dinas-ketahanan-pangan' => ['name'=>'Ketahanan Pangan','route'=>'dinas.ketapang','icon'=>'fas fa-wheat-awn'],
                'dinas-pariwisata' => ['name'=>'Pariwisata','route'=>'dinas.pariwisata','icon'=>'fas fa-map-marked-alt'],
                'dinas-lingkungan-hidup' => ['name'=>'DLH','route'=>'dinas.dlh','icon'=>'fas fa-leaf'],
                'badan-pendapatan-daerah' => ['name'=>'Bapenda','route'=>'dinas.bapenda','icon'=>'fas fa-coins'],
            ])
            @if($role === 'super_admin')
                @foreach($routeMap as $key => $conf)
                    @php($isActive = Route::is($conf['route']))
                    <li class="nav-item {{ $isActive ? 'active' : '' }}">
                        <a href="{{ route($conf['route']) }}" class="nav-link"><i class="{{ $conf['icon'] }}"></i><span>{{ $conf['name'] }}</span></a>
                    </li>
                @endforeach
            @elseif(($role === 'admin_dinas' || $role === 'user') && $userDinas && isset($routeMap[$userDinas->kode_dinas]))
                @php($conf = $routeMap[$userDinas->kode_dinas])
                @php($isActive = Route::is($conf['route']))
                <li class="nav-item {{ $isActive ? 'active' : '' }}">
                    <a href="{{ route($conf['route']) }}" class="nav-link"><i class="{{ $conf['icon'] }}"></i><span>{{ $conf['name'] }}</span></a>
                </li>
            @endif
        </ul>
</nav>
</aside>
<style>
#sidebar .nav-menu .nav-item .nav-link{border-radius:12px}
#sidebar .nav-menu .nav-item.active .nav-link{background:linear-gradient(90deg,#60a5fa,#3b82f6);color:#ffffff}
#sidebar .nav-menu .nav-item.active .nav-link i{color:#ffffff}
</style>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var sb=document.getElementById('sidebar');
  try{var s=localStorage.getItem('sidebar-scroll');if(s!=null){sb.scrollTop=parseInt(s,10)||0;}}catch(e){}
  document.querySelectorAll('#sidebar .nav-menu .nav-link').forEach(function(a){a.addEventListener('click',function(){try{localStorage.setItem('sidebar-scroll',document.getElementById('sidebar').scrollTop);}catch(e){}});});
});
</script>
