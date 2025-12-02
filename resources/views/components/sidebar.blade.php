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

            {{-- Dinas Terkait Accordion Menu --}}
            <li class="nav-item">
                <a href="#" class="nav-link accordion-toggle" id="dinasAccordion">
                    <i class="fas fa-building"></i><span>Dinas Terkait</span><i class="fas fa-chevron-down accordion-icon"></i>
                </a>
                <ul class="nav-submenu" id="dinasSubmenu" style="display: none;">
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
                                <a href="{{ route($conf['route']) }}" class="nav-link sub-item"><i class="{{ $conf['icon'] }}"></i><span>{{ $conf['name'] }}</span></a>
                            </li>
                        @endforeach
                    @elseif(($role === 'admin_dinas' || $role === 'user') && $userDinas && isset($routeMap[$userDinas->kode_dinas]))
                        @php($conf = $routeMap[$userDinas->kode_dinas])
                        @php($isActive = Route::is($conf['route']))
                        <li class="nav-item {{ $isActive ? 'active' : '' }}">
                            <a href="{{ route($conf['route']) }}" class="nav-link sub-item"><i class="{{ $conf['icon'] }}"></i><span>{{ $conf['name'] }}</span></a>
                        </li>
                    @else
                        <li class="nav-item disabled"><span class="nav-link sub-item disabled">Tidak ada akses</span></li>
                    @endif
                </ul>
            </li>
        </ul>
</nav>
</aside>
<style>
#sidebar .nav-menu .nav-item .nav-link{border-radius:12px}
#sidebar .nav-menu .nav-item.active .nav-link{background:linear-gradient(90deg,#60a5fa,#3b82f6);color:#ffffff}
#sidebar .nav-menu .nav-item.active .nav-link i{color:#ffffff}

/* Accordion styles for Dinas Terkait */
.accordion-toggle {
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.accordion-icon {
    transition: transform 0.3s ease;
    margin-left: auto;
}

.accordion-toggle.expanded .accordion-icon {
    transform: rotate(180deg);
}

.nav-submenu {
    padding-left: 24px !important;
    margin-top: 5px;
    margin-bottom: 5px;
    background: rgba(239, 246, 255, 0.4);
    border-radius: 0 0 12px 12px;
}

.nav-submenu .nav-link {
    padding: 8px 12px 8px 32px;
    font-size: 0.9em;
    border-radius: 0 !important;
    border-left: 2px solid transparent;
}

.nav-submenu .nav-link:hover {
    background: rgba(226, 232, 240, 0.5);
    border-left: 2px solid #60a5fa;
}

.nav-submenu .nav-item.active .nav-link {
    background: rgba(219, 234, 254, 0.6);
    border-left: 2px solid #3b82f6;
    color: #1e3a8a;
    font-weight: 600;
}

.sub-item {
    padding: 8px 12px 8px 32px;
    border-radius: 0 !important;
    border-left: 2px solid transparent;
}

.sub-item:hover {
    background: rgba(226, 232, 240, 0.5);
    border-left: 2px solid #60a5fa;
}

.nav-submenu .nav-item.active .sub-item {
    background: rgba(219, 234, 254, 0.6);
    border-left: 2px solid #3b82f6;
    color: #1e3a8a;
    font-weight: 600;
}
</style>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var sb=document.getElementById('sidebar');
  try{var s=localStorage.getItem('sidebar-scroll');if(s!=null){sb.scrollTop=parseInt(s,10)||0;}}catch(e){}
  document.querySelectorAll('#sidebar .nav-menu .nav-link').forEach(function(a){a.addEventListener('click',function(){try{localStorage.setItem('sidebar-scroll',document.getElementById('sidebar').scrollTop);}catch(e){}});});

  // Accordion functionality
  const accordionToggle = document.getElementById('dinasAccordion');
  const submenu = document.getElementById('dinasSubmenu');

  if (accordionToggle && submenu) {
    accordionToggle.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();

      // Toggle submenu visibility
      const isExpanded = submenu.style.display !== 'none';
      submenu.style.display = isExpanded ? 'none' : 'block';
      accordionToggle.classList.toggle('expanded', !isExpanded);
    });
  }
});
</script>
