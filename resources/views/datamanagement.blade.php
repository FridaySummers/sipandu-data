@extends('layouts.app')

@section('title', 'Manajemen Data')
@section('body-class', 'dashboard-page force-light')

@section('content')
    <div class="page active" id="data-management-page">
      <div class="page-header"><h1>Manajemen Data</h1><p>Kelola data perencanaan dari semua dinas - RKPD 2025</p></div>
      

      @php($role = auth()->user()->role ?? null)
      @if(in_array($role, ['super_admin','admin_dinas']))
      <div class="card" style="margin-top:16px">
        <div class="card-header">
          <div style="display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:12px;">
              <div>
                <h3>Daftar Pengajuan</h3>
                <div class="thread-meta">Total Data: {{ ($pendingSubmissions ?? collect())->count() }}</div>
              </div>
            </div>
            <div style="display:flex;gap:12px;align-items:center;">
            </div>
          </div>
        </div>
        <div class="card-body" style="padding-top:12px">
          <div class="table-controls" style="display:flex;justify-content:flex-end;gap:10px;margin-bottom:8px">
            <input id="pending-search" type="text" class="form-control" placeholder="Cari ID atau OPD">
            <select id="pending-dinas" class="form-control"><option value="">Semua Dinas</option>@foreach(($allDinas ?? collect()) as $d)<option value="{{ $d->nama_dinas }}">{{ $d->nama_dinas }}</option>@endforeach</select>
          </div>
          <div class="table-wrap"><table class="table table-compact" id="pending-table">
            <thead><tr><th>ID Data</th><th>Tanggal Pengajuan</th><th>Nama Data</th><th>Dinas</th><th>Status</th><th class="col-actions">Aksi</th></tr></thead>
            <tbody>
              @foreach(($pendingSubmissions ?? collect()) as $s)
                <tr data-opd="{{ optional($s->dinas)->nama_dinas }}" data-id="{{ $s->id }}">
                  <td>{{ $s->id }}</td>
                  <td>{{ optional($s->created_at)->format('d/m/Y') }}</td>
                  <td>{{ $s->judul_data }}</td>
                  <td>{{ optional($s->dinas)->nama_dinas ?? '-' }}</td>
                  <td><span class="status-badge status-menunggu">Menunggu Persetujuan</span></td>
                  <td>
                    <div class="row-actions">
                      <button class="btn btn-outline btn-sm btn-icon-circle approve-btn" data-id="{{ $s->id }}" title="Setujui"><i class="fas fa-check"></i></button>
                      <button class="btn btn-outline btn-sm btn-icon-circle reject-btn" data-id="{{ $s->id }}" title="Tolak"><i class="fas fa-times"></i></button>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table></div>
          <div class="table-footer" style="display:flex;justify-content:space-between;align-items:center;margin-top:8px">
            <div id="pending-page-text">Menampilkan 0 - 0 dari 0 data</div>
            <div class="pager">
              <button class="btn btn-outline btn-sm" id="pending-prev"><i class="fas fa-chevron-left"></i></button>
              <button class="btn btn-outline btn-sm" id="pending-next"><i class="fas fa-chevron-right"></i></button>
            </div>
          </div>
        </div>
      </div>
      @endif


      

      



      <div class="feature-grid" style="display:none">
        <div class="feature-card">
          <div class="feature-icon" style="background:#f5d0fe;color:#7c3aed"><i class="fas fa-industry"></i></div>
          <div class="feature-title">Data Perindustrian</div>
          <div class="feature-desc">Kontribusi sektor industri dan pertumbuhan</div>
          <a href="#section-perindustrian" class="feature-link">Kelola Data</a>
        </div>
        <div class="feature-card">
          <div class="feature-icon" style="background:#fef3c7;color:#f59e0b"><i class="fas fa-people-group"></i></div>
          <div class="feature-title">Data Koperasi</div>
          <div class="feature-desc">Perkembangan perkoperasian 2019–2023</div>
          <a href="#section-koperasi" class="feature-link">Kelola Data</a>
        </div>
        <div class="feature-card">
          <div class="feature-icon" style="background:#dcfce7;color:#16a34a"><i class="fas fa-seedling"></i></div>
          <div class="feature-title">Tanaman Pangan</div>
          <div class="feature-desc">Luas panen dan produksi 2019–2023</div>
          <a href="#section-tanaman-pangan" class="feature-link">Kelola Data</a>
        </div>
        
      </div>

      
    </div>
@endsection

@push('styles')
<style>
#data-management-page #dm-table thead tr{background:#eaf2ff}
  #data-management-page #dm-table thead th{background:transparent;color:#0b3a82;border-bottom:1px solid #c7d2fe;font-weight:600;letter-spacing:0.01em}
  #data-management-page #pending-table{border-collapse:collapse;width:100%}
  #data-management-page #pending-table thead tr{background:#f8fafc}
  #data-management-page #pending-table th,#data-management-page #pending-table td{border:1px solid #e5e7eb;padding:10px 12px}
  #data-management-page #pending-table th{color:#0b3a82;font-weight:600}
  #data-management-page #pending-table tbody tr:hover{background:#f1f5f9}
  #data-management-page .row-actions{display:flex;gap:8px;justify-content:flex-end;flex-wrap:wrap}
  #data-management-page .row-actions .btn:hover{transform:translateY(-1px)}
.review-list{display:flex;flex-direction:column;gap:10px}
.review-item{display:flex;align-items:center;justify-content:space-between;border:1px solid #e5e7eb;border-radius:12px;padding:10px 12px;background:#ffffff;box-shadow:var(--shadow-sm)}
.review-title{font-weight:600;color:#111827}
.review-meta{color:#6b7280;font-size:14px}
.review-actions{display:flex;gap:8px}
.chip-row{display:flex;gap:6px;margin-top:6px;flex-wrap:wrap}
.chip{display:inline-flex;align-items:center;padding:4px 8px;border-radius:999px;font-size:12px;border:1px solid #e5e7eb;background:#fff;color:#374151}
.chip-warning{background:#fff7ed;border-color:#fdba74;color:#9a3412}
.chip-light{background:#f8fafc;border-color:#e5e7eb;color:#334155}
.review-detail-panel{border:1px dashed #e5e7eb;border-radius:12px;padding:10px 12px;background:#fcfdff;margin-top:6px}
.detail-grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px}
.detail-label{font-weight:600;color:#374151}
.detail-value{color:#111827}
.snackbar{position:fixed;left:50%;transform:translateX(-50%);bottom:18px;background:#111827;color:#fff;padding:10px 12px;border-radius:12px;box-shadow:var(--shadow-md);z-index:9999}
</style>
@endpush

@push('scripts')
<script>window.DM_BASE = "{{ url('/data-management/submissions') }}";</script>
<script>
  function closeModal(){ const m=document.getElementById('submission-modal'); if(m) m.style.display='none'; }
  const newBtn=document.getElementById('new-submission-btn'); if(newBtn) newBtn.addEventListener('click',()=>{ const m=document.getElementById('submission-modal'); if(m) m.style.display='flex'; });
  window.addEventListener('click',function(e){ const modal=document.getElementById('submission-modal'); if(e.target===modal){ closeModal(); } });
  (function(){
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const table = document.getElementById('pending-table'); if (!table) return;
    const tbody = table.querySelector('tbody');
    const input = document.getElementById('pending-search');
    const sel = document.getElementById('pending-dinas');
    const pageText = document.getElementById('pending-page-text');
    const prevBtn = document.getElementById('pending-prev');
    const nextBtn = document.getElementById('pending-next');
    let data = [];
    function statusLabel(s){ if(s==='Disetujui') return 'Disetujui'; if(s==='Ditolak') return 'Ditolak'; if(s==='Menunggu Persetujuan') return 'Menunggu Persetujuan'; return s||'-'; }
    function statusClass(s){ if(s==='Disetujui') return 'status-approved'; if(s==='Ditolak') return 'status-rejected'; if(s==='Menunggu Persetujuan') return 'status-menunggu'; return ''; }
    table.querySelectorAll('tbody tr').forEach(function(tr){
      data.push({
        id: tr.children[0].textContent.trim(),
        date: tr.children[1].textContent.trim(),
        name: tr.children[2].textContent.trim(),
        opd: tr.children[3].textContent.trim(),
        status: 'Menunggu Persetujuan'
      });
    });
    tbody.innerHTML = '';
    let q = '', opd = '', page = 1, size = 10;
    function filtered(){
      return data.filter(function(d){
        const matchQ = !q || d.id.includes(q) || (d.opd||'').toLowerCase().includes(q);
        const matchOpd = !opd || d.opd === opd;
        return matchQ && matchOpd;
      });
    }
    async function openView(id){
      const modal = document.getElementById('dm-view-modal');
      const body = document.getElementById('dm-view-body');
      if (!modal || !body) return;
      try {
        const res = await fetch((window.DM_BASE||'') + '/' + id, { headers: { 'Accept': 'application/json' } });
        const json = await res.json();
        const fields = json.fields || {};
        const basics = [
          ['ID', json.id],
          ['Tanggal Pengajuan', json.created_at],
          ['OPD', json.opd],
          ['Status', statusLabel(json.status)],
          ['Nama Data', json.judul_data]
        ];
        const fieldHtml = Object.keys(fields).map(function(k){ return `<div><div class=\"detail-label\">${k}</div><div class=\"detail-value\">${fields[k] ?? '-'}</div></div>`; }).join('');
        const hist = Array.isArray(json.history) ? json.history : [];
        const histHtml = hist.length ? hist.map(function(h){ return `<div class=\"file-item\"><div>${h.id}</div><div class=\"file-meta\"><span>${h.opd||'-'}</span><span>${h.created_at||'-'}</span><span>${statusLabel(h.status)||'-'}</span></div></div>`; }).join('') : '<div class=\"file-item\"><div>Tidak ada riwayat perubahan</div></div>';
        body.innerHTML = `
          <div class=\"detail-grid\">${basics.map(function(pair){ return `<div><div class=\"detail-label\">${pair[0]}</div><div class=\"detail-value\">${pair[1] ?? '-'}</div></div>`; }).join('')}${fieldHtml}</div>
          <div class=\"review-detail-panel\"><div class=\"detail-label\">Riwayat Perubahan</div>${histHtml}</div>
        `;
        modal.style.display = 'flex';
      } catch(_){
        body.innerHTML = '<div class=\"file-item\"><div>Gagal memuat detail</div></div>';
        modal.style.display = 'flex';
      }
    }
    function render(){
      const list = filtered();
      const total = list.length;
      const maxPage = Math.max(1, Math.ceil(total/size));
      if (page > maxPage) page = maxPage;
      const start = (page-1)*size;
      const end = Math.min(start+size, total);
      const pageItems = list.slice(start, end);
      tbody.innerHTML = pageItems.map(function(d){
        return `<tr data-opd="${d.opd}" data-id="${d.id}">`
          + `<td>${d.id}</td>`
          + `<td>${d.date}</td>`
          + `<td>${d.name}</td>`
          + `<td>${d.opd||'-'}</td>`
          + `<td><span class="status-badge ${statusClass(d.status)}">${statusLabel(d.status)}</span></td>`
          + `<td><div class="row-actions"><button class="btn btn-outline btn-sm btn-icon-circle approve-btn" data-id="${d.id}" title="Setujui"><i class="fas fa-check"></i></button>`
          + ` <button class="btn btn-outline btn-sm btn-icon-circle reject-btn" data-id="${d.id}" title="Tolak"><i class="fas fa-times"></i></button></div></td>`
          + `</tr>`;
      }).join('');
      if (pageText) pageText.textContent = `Menampilkan ${total ? (start+1) : 0} - ${end} dari ${total} data`;
      if (prevBtn) prevBtn.disabled = page<=1;
      if (nextBtn) nextBtn.disabled = page>=maxPage;
      tbody.querySelectorAll('.approve-btn').forEach(function(btn){
        btn.addEventListener('click', async function(){
          const id = btn.dataset.id; try {
            const res = await fetch((window.DM_BASE||'') + '/' + id + '/approve', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken } });
            if (res.ok) { data = data.filter(function(x){ return x.id !== id; }); render(); Utils.showToast('Data disetujui','success'); }
            else { Utils.showToast('Gagal menyetujui data','error'); }
          } catch(e){ Utils.showToast('Gagal menyetujui data','error'); }
        });
      });
      tbody.querySelectorAll('.reject-btn').forEach(function(btn){
        btn.addEventListener('click', async function(){
          const id = btn.dataset.id; try {
            const form = new FormData(); form.append('catatan_revisi','');
            const res = await fetch((window.DM_BASE||'') + '/' + id + '/reject', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken }, body: form });
            if (res.ok) { data = data.filter(function(x){ return x.id !== id; }); render(); Utils.showToast('Data ditolak','success'); }
            else { Utils.showToast('Gagal menolak data','error'); }
          } catch(e){ Utils.showToast('Gagal menolak data','error'); }
        });
      });
      
    }
    render();
    if (input) input.addEventListener('input', function(){ q = input.value.trim().toLowerCase(); page=1; render(); });
    if (sel) sel.addEventListener('change', function(){ opd = sel.value; page=1; render(); });
    if (prevBtn) prevBtn.addEventListener('click', function(){ if(page>1){ page--; render(); } });
    if (nextBtn) nextBtn.addEventListener('click', function(){ page++; render(); });
  })();
  
  (function(){
    try {
      var key='sipandu_dm_records';
      var cur = []; try{ cur = JSON.parse(localStorage.getItem(key)) || []; }catch(_){ cur = []; }
      var el = document.getElementById('dm-approved-json');
      var srv = []; try{ srv = JSON.parse((el && el.textContent) ? el.textContent : '[]'); }catch(_){ srv = []; }
      var merged = srv.concat(cur.filter(function(c){ return !srv.some(function(s){ return s.name===c.name && s.period===c.period && s.opd===c.opd; }); }));
      localStorage.setItem(key, JSON.stringify(merged));
    } catch(_){ }
  })();
</script>
@endpush

@push('styles')
<style>
  .feature-grid { margin-top: 16px; }
  #data-management-page .controls-row{display:grid;grid-template-columns:1fr auto auto auto;gap:10px;margin-top:12px}
  #data-management-page .controls-row .search-box.compact{position:relative;background:#ffffff;border:1px solid #93c5fd;border-radius:12px;padding:0 12px;height:36px;box-shadow:var(--shadow-sm)}
  #data-management-page .controls-row .search-box.compact i{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#6b7280}
  #data-management-page .controls-row .search-box.compact input{border:none;outline:none;background:transparent;width:100%;padding:8px 10px 8px 34px;color:#111827}
  #data-management-page .controls-row .search-box.compact input::placeholder{color:#374151;opacity:1}
  #data-management-page .controls-row .search-box.compact:focus-within{box-shadow:0 0 0 3px rgba(37,99,235,0.12);border-color:#93c5fd}
  #data-management-page .controls-row .form-control{height:36px;border:1px solid #93c5fd;background:#ffffff;border-radius:12px;padding:0 32px 0 12px;appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24'%3E%3Cpath fill='%236b7280' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 10px center;background-size:16px}
  #data-management-page .controls-row .form-control:focus{outline:none;border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,0.12)}
  #data-management-page .card-header .btn{height:36px;border-radius:8px;border:1px solid #93c5fd}
  #data-management-page .card-header .btn.btn-primary{background:#2563eb;color:#fff;border-color:#2563eb}
  #data-management-page .card-header .btn.btn-outline{background:#fff;color:#1e3a8a}
  #data-management-page .dm-hero .btn.btn-primary.btn-wide{background:#ffffff !important;color:#111827 !important;border-color:#e5e7eb !important}
  #data-management-page .dm-hero .btn.btn-primary.btn-wide i{color:#111827 !important}
  #data-management-page .table-footer .btn{height:32px;border-radius:8px;border:1px solid #93c5fd;color:#1e3a8a;background:#fff}
  #data-management-page .kpi-grid .kpi-card:nth-child(1) .kpi-icon{background:#3b82f6}
  #data-management-page .kpi-grid .kpi-card:nth-child(2) .kpi-icon{background:#10b981}
  #data-management-page .kpi-grid .kpi-card:nth-child(3) .kpi-icon{background:#f59e0b}
  #data-management-page .kpi-grid .kpi-card:nth-child(4) .kpi-icon{background:#7c3aed}
  #dm-modal .modal{border-radius:16px;box-shadow:var(--shadow-xl);border:1px solid #e5e7eb}
  #dm-modal .modal{width:540px;max-width:92vw}
  #dm-modal .modal-header{padding:16px;border-bottom:1px solid #e5e7eb}
  #dm-modal .modal-header h3{font-weight:600;color:#111827}
  #dm-modal .modal-body{padding:16px}
  #dm-modal .modal-footer{padding:12px 16px;border-top:1px solid #e5e7eb;display:flex;gap:8px;justify-content:flex-end}
  #dm-modal .tip-bar{display:flex;align-items:center;gap:8px;padding:10px 12px;background:#ecfeff;color:#0e7490;border:1px solid #a5f3fc;border-radius:12px;margin-bottom:12px}
  #dm-modal .form-row{display:flex;flex-direction:column;gap:6px;margin-bottom:10px;align-items:flex-start}
  #dm-modal .form-row label{color:#374151;font-weight:600;text-align:left}
  #dm-modal .form-row .form-control{height:40px;border:1px solid #93c5fd;background:#ffffff;border-radius:12px;padding:0 12px}
  #dm-modal .form-row .form-control:hover{background:#ffffff}
  #dm-modal .form-row .form-control:focus{outline:none;border-color:#93c5fd;box-shadow:none;background:#ffffff}
  #dm-modal .form-row .form-control::placeholder{color:#6b7280}
  #dm-modal .form-row select.form-control{height:40px;appearance:none;-webkit-appearance:none;padding-right:38px;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24'%3E%3Cpath fill='%236b7280' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;background-size:16px}
  #dm-modal .dm-row-2{display:grid;grid-template-columns:1fr 1fr;gap:12px;align-items:start}
  #dm-modal .dm-row-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;align-items:start}
  #dm-modal .btn.btn-secondary{background:#fff;color:#111827;border-color:#e5e7eb}
  #dm-modal .btn.btn-primary{background:#2563eb;border-color:#2563eb;color:#fff}
  /* Stat pill for pending count */
  #data-management-page .stat-pill{display:inline-flex;align-items:center;gap:8px;background:#eff6ff;border:1px solid #bfdbfe;color:#0b3a82;border-radius:999px;padding:6px 10px;font-weight:600}
  #data-management-page .stat-pill i{color:#2563eb}
  #data-management-page .table-wrap{overflow-x:hidden;-webkit-overflow-scrolling:touch}
  #data-management-page .btn-icon-circle{width:32px;height:32px;border-radius:9999px;padding:0;display:inline-flex;align-items:center;justify-content:center}
  #data-management-page .row-actions .btn-icon-circle:hover{background:#f1f5f9;transform:translateY(-1px)}
  @media (max-width: 640px){
    #data-management-page #pending-table th,#data-management-page #pending-table td{padding:8px 10px;font-size:13px}
    #data-management-page .row-actions{gap:6px}
    #data-management-page #pending-table .col-actions{width:auto !important}
    #data-management-page #pending-table td:last-child{white-space:normal !important}
  }
  #data-management-page .card-header h3{font-weight:600;color:#0b3a82}
  #data-management-page .card-header .thread-meta{color:#475569}
  #data-management-page .table-wrap{margin-top:6px}
  #data-management-page .status-badge{display:inline-flex;align-items:center;padding:4px 8px;border-radius:999px;font-size:12px;border:1px solid #e5e7eb;background:#fff;color:#334155}
  #data-management-page .status-badge.status-menunggu{background:#fff7ed;border-color:#fdba74;color:#9a3412}
</style>
@endpush
<div id="dm-view-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.35);align-items:center;justify-content:center;z-index:1000">
  <div class="modal" style="background:#fff">
    <div class="modal-header" style="display:flex;align-items:center;justify-content:space-between">
      <h3>Detail Pengajuan</h3>
      <button class="btn btn-outline btn-sm" id="dm-view-close">Tutup</button>
    </div>
    <div class="modal-body" id="dm-view-body"></div>
    <div class="modal-footer"><button class="btn btn-secondary" id="dm-view-cancel">Tutup</button></div>
  </div>
</div>
<script>
  (function(){
    var modal=document.getElementById('dm-view-modal');
    var close=document.getElementById('dm-view-close');
    var cancel=document.getElementById('dm-view-cancel');
    if (modal) modal.addEventListener('click',function(e){ if(e.target===modal) modal.style.display='none'; });
    if (close) close.addEventListener('click',function(){ if(modal) modal.style.display='none'; });
    if (cancel) cancel.addEventListener('click',function(){ if(modal) modal.style.display='none'; });
  })();
</script>
