@extends('layouts.app')
@section('title', 'Laporan & Analisis')
@section('body-class', 'dashboard-page force-light')

@section('content')
    <div class="page active" id="reports-page">
        <div class="page-header"><h1>Laporan & Analisis</h1><p>Visualisasi dan analisis data perencanaan</p></div>

        <div class="kpi-grid">
            <div class="kpi-card"><div class="kpi-icon"><i class="fas fa-file-alt"></i></div><div class="kpi-content"><div class="kpi-value" id="rep-kpi-total">0</div><div class="kpi-label">Total Laporan</div></div><div class="kpi-delta" id="rep-kpi-total-delta">+0</div></div>
            <div class="kpi-card"><div class="kpi-icon success"><i class="fas fa-chart-line"></i></div><div class="kpi-content"><div class="kpi-value" id="rep-kpi-avg">0%</div><div class="kpi-label">Avg. Completion</div></div><div class="kpi-delta" id="rep-kpi-avg-delta">+0</div></div>
            <div class="kpi-card"><div class="kpi-icon info"><i class="fas fa-database"></i></div><div class="kpi-content"><div class="kpi-value" id="rep-kpi-points">0</div><div class="kpi-label">Data Points</div></div><div class="kpi-delta" id="rep-kpi-points-delta">+0</div></div>
            <div class="kpi-card"><div class="kpi-icon warning"><i class="fas fa-list"></i></div><div class="kpi-content"><div class="kpi-value" id="rep-kpi-active">0</div><div class="kpi-label">Active Reports</div></div><div class="kpi-delta" id="rep-kpi-active-delta">+0</div></div>
        </div>

        <div class="toolbar">
            <div class="toolbar-left">
                <select id="rep-opd-filter"><option value="">Semua OPD</option></select>
                <select id="rep-year-filter"><option value="2025">2025</option><option value="2024">2024</option></select>
            </div>
            <div class="toolbar-right">
                <button id="rep-export-pdf" class="btn btn-outline btn-sm"><i class="fas fa-file-pdf"></i> Export PDF</button>
                <button id="rep-export-xlsx" class="btn btn-outline btn-sm"><i class="fas fa-file-excel"></i> Export XLSX</button>
                <button id="rep-export-csv" class="btn btn-outline btn-sm"><i class="fas fa-file-csv"></i> Export CSV</button>
            </div>
        </div>

        <div class="charts-grid">
            <div class="chart-card">
                <div class="card-header">
                    <h3>Tren Penyelesaian Data</h3>
                    <div class="card-actions">
                        <button class="btn btn-outline btn-sm" id="rep-type-toggle"><i class="fas fa-chart-bar"></i></button>
                    </div>
                </div>
                <div class="card-body"><canvas id="rep-monthly-chart"></canvas></div>
            </div>
            <div class="chart-card">
                <div class="card-header"><h3>Status Data per OPD</h3></div>
                <div class="card-body"><canvas id="rep-status-chart"></canvas></div>
            </div>
            <div class="chart-card">
                <div class="card-header"><h3>Kategori Data</h3></div>
                <div class="card-body"><canvas id="rep-category-chart"></canvas></div>
            </div>
        </div>

        <div class="bottom-grid" style="margin-top: var(--space-6);">
            <div class="chart-card">
                <div class="card-header"><h3>Distribusi Data per Kategori</h3></div>
                <div class="card-body"><canvas id="rep-distribution-chart"></canvas></div>
            </div>
            <div class="chart-card">
                <div class="card-header"><h3>Performance Dinas</h3></div>
                <div class="card-body" id="perf-list"></div>
            </div>
        </div>

        <div class="card" style="margin-top: var(--space-6);">
            <div class="card-header"><h3>Laporan Tersedia</h3></div>
            <div class="card-body" id="report-list"></div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  UIComponents.renderNotifications();

  const opdFilter = document.getElementById('rep-opd-filter');
  const fillOPD = () => {
    if (!opdFilter) return;
    const src = (typeof window!== 'undefined' && window.dinasData) ? window.dinasData : (typeof dinasData !== 'undefined' ? dinasData : []);
    if (opdFilter.options.length <= 1) {
      src.forEach(d => { const o = document.createElement('option'); o.value = d.name; o.textContent = d.name; opdFilter.appendChild(o); });
    }
    if (window.CUSTOM_SELECT_ENABLED) {
      if (opdFilter.dataset.csEnhanced !== '1' && typeof window.enhanceCustomSelect === 'function') { window.enhanceCustomSelect(opdFilter); }
      if (typeof window.refreshCustomSelect === 'function') { window.refreshCustomSelect(opdFilter); }
    }
  };
  // run after DOM and enhancement
  setTimeout(fillOPD, 0);

  const setKPI = () => {
    document.getElementById('rep-kpi-total').textContent = '156';
    document.getElementById('rep-kpi-avg').textContent = '87%';
    document.getElementById('rep-kpi-points').textContent = '12.5K';
    document.getElementById('rep-kpi-active').textContent = '24';
  };
  setKPI();

  const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
  const mCanvas = document.getElementById('rep-monthly-chart');
  let monthlyChart = Chart.getChart(mCanvas) || null;
  if (monthlyChart) monthlyChart.destroy();
  let currentType = 'bar';
  const monthlyData = {
    labels: months,
    datasets: [
      { label:'Complete', data:[60,78,85,92,88,96,90,94,95,97,96,98], backgroundColor:'#22c55e', borderColor:'#22c55e' },
      { label:'In Progress', data:[25,18,12,10,9,7,8,7,6,5,5,4], backgroundColor:'#f59e0b', borderColor:'#f59e0b' },
      { label:'Pending', data:[10,6,3,2,3,1,2,1,1,1,1,1], backgroundColor:'#ef4444', borderColor:'#ef4444' }
    ]
  };
  const buildMonthly = (type) => new Chart(mCanvas, {
    type,
    data: monthlyData,
    options: { responsive:true, maintainAspectRatio:false, scales:{ x:{ stacked:type==='bar' }, y:{ stacked:type==='bar', beginAtZero:true, max:100 } }, plugins:{ legend:{ position:'bottom' } }, elements: { line: { tension:0.3, fill:true } } }
  });
  monthlyChart = buildMonthly(currentType);

  const sCanvas = document.getElementById('rep-status-chart');
  let statusChart = Chart.getChart(sCanvas) || null; if (statusChart) statusChart.destroy();
  const complete = dinasData.filter(d=> d.status==='Complete').length;
  const progress = dinasData.filter(d=> d.status==='Progress' || d.status==='In Review').length;
  const pending = dinasData.filter(d=> d.status==='Pending').length;
  statusChart = new Chart(sCanvas,{ type:'doughnut', data:{ labels:['Complete','Progress','Pending'], datasets:[{ data:[complete,progress,pending], backgroundColor:['#22c55e','#f59e0b','#ef4444'], hoverOffset:8 }] }, options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom' } }, cutout:'60%' } });

  const cCanvas = document.getElementById('rep-category-chart');
  let categoryChart = Chart.getChart(cCanvas) || null; if (categoryChart) categoryChart.destroy();
  categoryChart = new Chart(cCanvas,{ type:'doughnut', data:{ labels:['Keuangan','Produksi','SDM','Konsumsi'], datasets:[{ data:[30,25,20,25], backgroundColor:['#3b82f6','#10b981','#f59e0b','#64748b'] }] }, options:{ responsive:true, maintainAspectRatio:false } });

  const distCanvas = document.getElementById('rep-distribution-chart');
  let distChart = Chart.getChart(distCanvas) || null; if (distChart) distChart.destroy();
  distChart = new Chart(distCanvas,{ type:'pie', data:{ labels:['Kesehatan','Ekonomi','Pendidikan','Pertanian','Infrastruktur','Sosial'], datasets:[{ data:[23,15,18,14,17,13], backgroundColor:['#3b82f6','#10b981','#f59e0b','#22c55e','#64748b','#06b6d4'] }] }, options:{ responsive:true, maintainAspectRatio:false } });

  const perfList = document.getElementById('perf-list');
  const perfData = [ {name:'Perdagangan',val:95}, {name:'Kesehatan',val:89}, {name:'Pendidikan',val:82}, {name:'Pertanian',val:76}, {name:'Perhubungan',val:85}, {name:'Sosial',val:79} ];
  perfList.innerHTML = perfData.map((p,i)=>`<div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;"><div style="width:24px;color:var(--gray-500);">${i+1}.</div><div style="flex:1"><div style="display:flex;justify-content:space-between;margin-bottom:4px;"><span>${p.name}</span><span style="color:#16a34a;font-weight:600;">${p.val}%</span></span></div><div style="height:8px;background:#e5e7eb;border-radius:9999px;overflow:hidden;"><div style="width:${p.val}%;height:8px;background:#22c55e;"></div></div></div></div>`).join('');

  const reportList = document.getElementById('report-list');
  const reports = [
    {title:'Laporan Komprehensif RKPD 2025 - Q1',tag:'final',cat:'Quarterly',date:'2025-03-31',size:'2.4 MB'},
    {title:'Analisis Data Ekonomi Regional',tag:'final',cat:'Ekonomi',date:'2025-03-15',size:'1.8 MB'},
    {title:'Evaluasi Program Kesehatan',tag:'final',cat:'Kesehatan',date:'2025-03-10',size:'3.1 MB'},
    {title:'Progress Report Infrastruktur',tag:'draft',cat:'Infrastruktur',date:'2025-03-05',size:'2.7 MB'}
  ];
  reportList.innerHTML = reports.map(r=>`<div class="file-item"><div><div class="thread-title">${r.title}</div><div class="thread-meta">${r.cat} • ${DateUtils.formatDate(r.date,{day:'2-digit',month:'short',year:'numeric'})} • ${r.size} • <span style="color:${r.tag==='final'?'#16a34a':'#f59e0b'};font-weight:600;">${r.tag}</span></div></div><button class="btn btn-outline btn-sm"><i class="fas fa-download"></i> Download</button></div>`).join('');

  const typeToggle = document.getElementById('rep-type-toggle');
  if (typeToggle) {
    typeToggle.addEventListener('click', () => {
      currentType = currentType === 'bar' ? 'line' : 'bar';
      if (monthlyChart) monthlyChart.destroy();
      monthlyChart = buildMonthly(currentType);
      typeToggle.innerHTML = currentType === 'bar' ? '<i class="fas fa-chart-bar"></i>' : '<i class="fas fa-chart-line"></i>';
    });
  }

  const exportPdf = document.getElementById('rep-export-pdf');
  const exportXlsx = document.getElementById('rep-export-xlsx');
  const exportCsv = document.getElementById('rep-export-csv');
  if (exportPdf) exportPdf.onclick = () => Utils.showToast('Export PDF dibuat', 'success');
  if (exportXlsx) exportXlsx.onclick = () => Utils.showToast('Export XLSX dibuat', 'success');
  if (exportCsv) exportCsv.onclick = () => Utils.showToast('Export CSV dibuat', 'success');
});
</script>
@endpush
