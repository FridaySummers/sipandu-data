@extends('layouts.app')
@section('title', 'Dashboard')
@section('body-class', 'dashboard-page force-light')

@section('content')
    <div class="page active" id="dashboard-page">
            <div class="page-header"><h1>Dashboard Overview</h1><p>Monitoring real-time SIPANDU DATA - RKPD 2025</p></div>
            <div class="kpi-grid">
                <div class="kpi-card"><div class="kpi-icon"><i class="fas fa-building"></i></div><div class="kpi-content"><div class="kpi-number" id="total-dinas">{{ $totalDinas ?? 0 }}</div><div class="kpi-label">Total Dinas</div></div></div>
                <div class="kpi-card"><div class="kpi-icon success"><i class="fas fa-database"></i></div><div class="kpi-content"><div class="kpi-number" id="total-data">{{ $totalDataSubmissions ?? 0 }}</div><div class="kpi-label">Total Data</div></div></div>
                <div class="kpi-card"><div class="kpi-icon warning"><i class="fas fa-chart-line"></i></div><div class="kpi-content"><div class="kpi-number" id="increase-percent">{{ isset($increasePercent) ? number_format($increasePercent,2) : '0.00' }}%</div><div class="kpi-label">Persentase Kenaikan Jumlah Data</div></div></div>
                <div class="kpi-card"><div class="kpi-icon info"><i class="fas fa-clock"></i></div><div class="kpi-content"><div class="kpi-number" id="pending-approvals">{{ $pendingData ?? 0 }}</div><div class="kpi-label">Menunggu Persetujuan</div></div></div>
            </div>
            <div class="charts-grid">
                <div class="chart-card"><div class="card-header"><h3>Trend Data Bulanan</h3><div class="card-actions"><select id="mp-year" class="year-filter"><option value="2025">2025</option><option value="2026">2026</option><option value="2027">2027</option></select></div></div><div class="card-body large"><canvas id="monthly-progress-chart"></canvas></div></div>
                <div class="chart-card"><div class="card-header"><h3>Perbandingan Data per Dinas</h3></div><div class="card-body large"><canvas id="dinas-status-chart"></canvas></div></div>
                <div class="chart-card"><div class="card-header"><h3>Kategori Data</h3></div><div class="card-body large"><canvas id="data-category-chart"></canvas></div></div>
            </div>
            <div class="bottom-grid">
                <div class="activity-card"><div class="card-header"><h3>Aktivitas Terbaru</h3><div class="card-actions"><select id="activity-opd" class="year-filter"><option value="">Semua Dinas</option></select></div></div><div class="activity-toolbar" style="display:flex;align-items:center;justify-content:space-between;padding:10px 12px"><div id="activity-page-text">Menampilkan 0 - 0 dari 0 aktivitas</div><div class="pager"><button class="btn btn-outline btn-sm" id="activity-prev"><i class="fas fa-chevron-left"></i></button><button class="btn btn-outline btn-sm" id="activity-next"><i class="fas fa-chevron-right"></i></button></div></div><div class="activity-list" id="activity-list"></div></div>
            </div>
        </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  var sel = document.getElementById('activity-opd');
  var list = document.getElementById('activity-list');
  var prev = document.getElementById('activity-prev');
  var next = document.getElementById('activity-next');
  var pageText = document.getElementById('activity-page-text');
  var page = 1, size = 10, opd = '';
  function fetchOps(){ fetch('/opd/rows?opd=&key=',{method:'GET'}).catch(function(){}) }
  function render(){
    var url = '/dashboard/activity?page='+page+'&size='+size+(opd?('&opd='+encodeURIComponent(opd)):'');
    fetch(url).then(function(res){ return res.json(); }).then(function(rows){
      list.innerHTML = rows.map(function(r){
        var icon = r.action==='create'?'plus-circle':(r.action==='update'?'pen-to-square':(r.action==='delete'?'trash':'check-circle'));
        var sub = r.approval_status ? (' • '+r.approval_status) : '';
        return '<div class="thread-item"><div class="thread-item-inner"><div class="thread-avatar"><i class="fas fa-'+icon+'"></i></div><div><div class="thread-title">'+(r.username||'-')+' ('+(r.role||'-')+')</div><div class="thread-subtitle">'+(r.opd||'-')+sub+'</div><div class="thread-meta">'+(new Date(r.timestamp)).toLocaleString('id-ID')+' • '+(r.entity||'')+' • ID '+(r.record_id||'-')+'</div></div><div class="thread-stats"><div><i class="fas fa-database"></i>'+ (r.metadata && r.metadata.name ? r.metadata.name : (r.metadata && r.metadata.judul_data ? r.metadata.judul_data : '-')) +'</div></div></div></div>';
      }).join('');
      pageText.textContent = 'Menampilkan '+(rows.length?(((page-1)*size+1)+' - '+((page-1)*size+rows.length)):'0 - 0')+' dari '+(((page-1)*size)+rows.length)+' aktivitas';
      prev.disabled = page<=1; next.disabled = rows.length < size;
    }).catch(function(){ list.innerHTML=''; });
  }
  render();
  if (sel) sel.addEventListener('change', function(){ opd = sel.value; page=1; render(); });
  if (prev) prev.addEventListener('click', function(){ if(page>1){ page--; render(); } });
  if (next) next.addEventListener('click', function(){ page++; render(); });
});
</script>
@endpush
