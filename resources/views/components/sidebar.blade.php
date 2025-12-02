<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="user-info">
            <div class="user-avatar"><i class="fas fa-user-tie"></i></div>
            <div class="user-details">
                <h4 id="sidebar-user-name">{{ auth()->user()->name ?? 'User' }}</h4>
                <p id="sidebar-user-role">
                    @if(auth()->user()->role === 'super_admin')
                        Super Admin Bappeda
                    @elseif(auth()->user()->role === 'admin_dinas')
                        Admin {{ auth()->user()->dinas->nama_dinas ?? '' }}
                    @else
                        User {{ auth()->user()->dinas->nama_dinas ?? '' }}
                    @endif
                </p>
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
            <li class="nav-item {{ Route::is('datamanagement') ? 'active' : '' }}">
                <a href="{{ route('datamanagement') }}" class="nav-link">
                    <i class="fas fa-database"></i><span>Data Management</span>
                </a>
            </li>

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

            {{-- DINAS TERKAIT SECTION - MODIFIED FOR DYNAMIC SYSTEM --}}
            <li class="nav-header">Dinas Terkait</li>
            
            @auth
                @php
                    $role = auth()->user()->role;
                    $userDinas = auth()->user()->dinas;
                @endphp

                @if($role === 'super_admin')
                    {{-- Super Admin: Lihat semua dinas --}}
                    @foreach(\App\Models\Dinas::all() as $dinasItem)
                        <li class="nav-item {{ request()->is('dinas/'.$dinasItem->id) ? 'active' : '' }}">
                            <a href="{{ route('dinas.show', $dinasItem->id) }}" class="nav-link">
                                <i class="fas fa-building"></i>
                                <span>{{ $dinasItem->nama_dinas }}</span>
                            </a>
                        </li>
                    @endforeach
                @elseif(in_array($role, ['admin_dinas', 'user']) && $userDinas)
                    {{-- Admin Dinas & User: Hanya lihat dinas mereka --}}
                    <li class="nav-item {{ request()->is('dinas/'.$userDinas->id) ? 'active' : '' }}">
                        <a href="{{ route('dinas.show', $userDinas->id) }}" class="nav-link">
                            <i class="fas fa-building"></i>
                            <span>{{ $userDinas->nama_dinas }}</span>
                        </a>
                    </li>
                @endif
            @endauth
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