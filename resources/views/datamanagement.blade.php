@extends('layouts.app')

@section('title', 'Data Management')
@section('body-class', 'dashboard-page force-light')

@section('content')
    <div class="page active" id="data-management-page">
      <div class="page-header"><h1>Data Management</h1><p>Kelola data perencanaan dari semua dinas - RKPD 2025</p></div>
      <div class="kpi-grid">
        <div class="kpi-card" id="kpi-total"><div class="kpi-icon"><i class="fas fa-database"></i></div><div class="kpi-content"><div class="kpi-value">0</div><div class="kpi-label">Total Data</div></div><div class="kpi-delta" id="kpi-total-delta">+0</div></div>
        <div class="kpi-card" id="kpi-complete"><div class="kpi-icon"><i class="fas fa-check-circle"></i></div><div class="kpi-content"><div class="kpi-value">0</div><div class="kpi-label">Complete</div></div><div class="kpi-delta" id="kpi-complete-delta">+0</div></div>
        <div class="kpi-card" id="kpi-progress"><div class="kpi-icon"><i class="fas fa-hourglass-half"></i></div><div class="kpi-content"><div class="kpi-value">0</div><div class="kpi-label">In Progress</div></div><div class="kpi-delta" id="kpi-progress-delta">+0</div></div>
        <div class="kpi-card" id="kpi-pending"><div class="kpi-icon"><i class="fas fa-exclamation-circle"></i></div><div class="kpi-content"><div class="kpi-value">0</div><div class="kpi-label">Pending Review</div></div><div class="kpi-delta" id="kpi-pending-delta">+0</div></div>
      </div>

      @php($role = auth()->user()->role ?? null)
      @if(in_array($role, ['super_admin','admin_dinas']))
      <div class="card" style="margin-top:16px">
        <div class="card-header">
          <div class="dm-hero" style="width:100%">
            <div style="display:flex;align-items:center;gap:12px;">
              <div class="user-avatar" style="width:36px;height:36px;background:#f59e0b"><i class="fas fa-bell"></i></div>
              <div>
                <h3>Notifikasi Review</h3>
                <div class="thread-meta">Pengajuan menunggu persetujuan</div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">
          @if(($pendingSubmissions ?? collect())->count() === 0)
            <div class="file-item"><div>Tidak ada pengajuan pending</div></div>
          @else
            <div class="review-list">
              @foreach($pendingSubmissions as $s)
              <div class="review-item" data-id="{{ $s->id }}" data-title="{{ $s->judul_data }}">
                <div class="review-main">
                  <div class="review-title">{{ $s->judul_data }}</div>
                  <div class="review-meta">{{ optional($s->dinas)->nama_dinas }} • {{ $s->tahun_perencanaan }}</div>
                  <div class="chip-row">
                    <span class="chip chip-warning">Pending</span>
                    <span class="chip chip-light">File: {{ Str::limit($s->file_path, 24) }}</span>
                  </div>
                </div>
                <div class="review-actions">
                  <button class="btn btn-primary btn-sm review-approve" data-id="{{ $s->id }}"
                          data-opd="{{ optional($s->dinas)->nama_dinas }}" data-name="{{ $s->judul_data }}"
                          data-category="Produksi" data-period="{{ $s->tahun_perencanaan }}" data-pic="{{ auth()->user()->name }}">
                    <i class="fas fa-check"></i> Terima
                  </button>
                  <button class="btn btn-outline btn-sm review-reject" data-id="{{ $s->id }}"><i class="fas fa-times"></i> Tolak</button>
                  <button class="btn btn-outline btn-sm review-detail"><i class="fas fa-eye"></i> Detail</button>
                </div>
              </div>
              <div class="review-detail-panel" id="detail-{{ $s->id }}" style="display:none">
                <div class="detail-grid">
                  <div><div class="detail-label">Deskripsi</div><div class="detail-value">{{ $s->deskripsi }}</div></div>
                  <div><div class="detail-label">File Path</div><div class="detail-value">{{ $s->file_path }}</div></div>
                  <div><div class="detail-label">Dibuat</div><div class="detail-value">{{ optional($s->created_at)->format('d/m/Y H:i') }}</div></div>
                </div>
              </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>
      @endif


      <div class="card">
        <div class="card-header">
          <div class="dm-hero" style="width:100%">
            <div style="display:flex;align-items:center;justify-content:space-between;">
              <div style="display:flex;align-items:center;gap:12px;">
                <div class="user-avatar" style="width:36px;height:36px;background:#1e3a8a"><i class="fas fa-database"></i></div>
                <div>
                  <h3>Daftar Data Terpadu</h3>
                  <div class="thread-meta">Manajemen & Monitoring Data Seluruh Dinas</div>
                </div>
              </div>
              <div style="display:flex;gap:10px;align-items:center;">
                <button class="btn btn-outline btn-wide" id="dm-export"><i class="fas fa-file-export"></i> Export</button>
                <button class="btn btn-primary btn-wide" id="dm-add-open"><i class="fas fa-paper-plane"></i> Ajukan Data</button>
              </div>
            </div>
            <div class="controls-row">
              <div class="search-box compact"><i class="fas fa-search"></i><input id="dm-search" type="text" placeholder="Cari data..."></div>
              <select id="dm-status-filter" class="form-control"><option value="">Semua Status</option><option value="Approved">Approved</option><option value="In Review">In Review</option><option value="Pending">Pending</option><option value="Rejected">Rejected</option></select>
              <select id="dm-opd-filter" class="form-control"><option value="">Semua Dinas</option></select>
            </div>
          </div>
        </div>
        <div class="card-body">
          
          <div class="table-wrap"><table class="table table-compact" id="dm-table"></table></div>
          <div class="table-footer">
            <div id="dm-page-text">Menampilkan 0 - 0 dari 0 data</div>
            <div class="pager">
              <button class="btn btn-outline btn-sm" id="dm-page-prev"><i class="fas fa-chevron-left"></i></button>
              <button class="btn btn-outline btn-sm" id="dm-page-next"><i class="fas fa-chevron-right"></i></button>
            </div>
          </div>
          
        </div>
      </div>

      <div class="modal-overlay" id="dm-modal" style="display:none;">
        <div class="modal">
          <div class="modal-header"><h3>Ajukan Data Baru</h3><button class="btn btn-outline btn-sm" id="dm-add-close"><i class="fas fa-times"></i></button></div>
          <div class="modal-body">
            <div class="tip-bar"><i class="fas fa-lightbulb"></i> Tips: Pastikan semua field wajib (*) sudah terisi sebelum menyimpan.</div>
            <div class="dm-grid">
              <div class="dm-row-2">
                <div class="form-row"><label>Dinas *</label><select id="dm-add-opd" class="form-control"><option value="">Pilih Dinas</option></select></div>
              </div>
              <div class="form-row"><label>Nama Data *</label><input id="dm-add-name" class="form-control" placeholder="Contoh: Data Produksi Tahun 2024"></div>
              <div class="dm-row-2">
                <div class="form-row"><label>Periode</label><input id="dm-add-period" class="form-control" placeholder="Contoh: 2024, Q1 2024"></div>
                <div class="form-row"><label>Penanggung Jawab</label><input id="dm-add-pic" class="form-control" placeholder="Nama PJ"></div>
              </div>
              <div class="dm-row-3">
                <div class="form-row"><label>Status</label><select id="dm-add-status" class="form-control"><option value="Pending">Draft</option><option value="In Review">In Review</option><option value="Approved">Approved</option></select></div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" id="dm-add-cancel">Batal</button>
            <button class="btn btn-primary" id="dm-add-save"><i class="fas fa-save"></i> Ajukan Data</button>
          </div>
        </div>
      </div>



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
<script src="{{ asset('js/datamanagement.js') }}"></script>
<script type="application/json" id="dm-approved-json">{!! json_encode(($approvedRecords ?? collect())->map(function($r){ return [
  'id' => $r->id,
  'opd' => optional($r->dinas)->nama_dinas ?? '-',
  'category' => '-',
  'name' => $r->name,
  'period' => $r->period ?? '-',
  'status' => $r->status,
  'pic' => $r->pic ?? '',
  'priority' => 'Medium',
  'createdAt' => optional($r->created_at)->toIso8601String(),
  'files' => [],
  'schema' => [],
]; })->toArray()) !!}</script>
<script>window.DM_BASE = "{{ url('/data-management/submissions') }}";</script>
<script id="dm-flash" type="application/json">{!! json_encode(session('success') ?? session('error') ?? '') !!}</script>
<script>
  function closeModal(){ const m=document.getElementById('submission-modal'); if(m) m.style.display='none'; }
  const newBtn=document.getElementById('new-submission-btn'); if(newBtn) newBtn.addEventListener('click',()=>{ const m=document.getElementById('submission-modal'); if(m) m.style.display='flex'; });
  window.addEventListener('click',function(e){ const modal=document.getElementById('submission-modal'); if(e.target===modal){ closeModal(); } });
  (function(){
    const overlayId='reject-overlay';
    let ov=document.getElementById(overlayId);
    if(!ov){
      ov=document.createElement('div');
      ov.id=overlayId; ov.className='modal-overlay'; ov.style.display='none';
      ov.innerHTML=`<div class="modal"><div class="modal-header"><h3>Tolak Pengajuan</h3><button class="btn btn-outline btn-sm" id="rj-close">✕</button></div><form id="rj-form" method="POST"><input type="hidden" name="_token"><div class="modal-body"><div class="form-row"><label>Catatan</label><textarea name="catatan_revisi" class="form-control" rows="3" placeholder="Alasan penolakan"></textarea></div></div><div class="modal-footer"><button type="button" class="btn btn-secondary" id="rj-cancel">Batal</button><button type="submit" class="btn btn-primary"><i class="fas fa-times"></i> Tolak</button></div></form></div>`;
      document.body.appendChild(ov);
      const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
      const inp = ov.querySelector('input[name="_token"]'); if (inp) inp.value = token;
    }
    const open=(id)=>{ const f=document.getElementById('rj-form'); if(f){ f.action=(window.DM_BASE||'')+'/'+id+'/reject'; } ov.style.display='flex'; };
    const close=()=>{ ov.style.display='none'; };
    document.querySelectorAll('.review-reject').forEach(b=> b.addEventListener('click',()=> open(b.dataset.id)) );
    ov.querySelector('#rj-close').onclick=close; ov.querySelector('#rj-cancel').onclick=close; ov.addEventListener('click',(e)=>{ if(e.target===ov) close(); });
  })();
  (function(){
    const overlayId='approve-overlay';
    let ov=document.getElementById(overlayId);
    if(!ov){
      ov=document.createElement('div');
      ov.id=overlayId; ov.className='modal-overlay'; ov.style.display='none';
      ov.innerHTML=`<div class="modal"><div class="modal-header"><h3>Terima Pengajuan</h3><button class="btn btn-outline btn-sm" id="ap-close">✕</button></div><form id="ap-form" method="POST"><input type="hidden" name="_token"><div class="modal-body"><div class="tip-bar"><i class="fas fa-lightbulb"></i> Pastikan data sesuai sebelum menyetujui.</div><div class="form-row"><label>Catatan (opsional)</label><input name="catatan_revisi" class="form-control" placeholder="Catatan persetujuan"></div></div><div class="modal-footer"><button type="button" class="btn btn-secondary" id="ap-cancel">Batal</button><button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Terima</button></div></form></div>`;
      document.body.appendChild(ov);
      const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
      const inp = ov.querySelector('input[name="_token"]'); if (inp) inp.value = token;
    }
    const open=(id)=>{ const f=document.getElementById('ap-form'); if(f){ f.action=(window.DM_BASE||'')+'/'+id+'/approve'; } ov.style.display='flex'; };
    const close=()=>{ ov.style.display='none'; };
    document.querySelectorAll('.review-approve').forEach(b=> b.addEventListener('click',()=> open(b.dataset.id)) );
    ov.querySelector('#ap-close').onclick=close; ov.querySelector('#ap-cancel').onclick=close; ov.addEventListener('click',(e)=>{ if(e.target===ov) close(); });
  })();
  (function(){
    document.querySelectorAll('.review-detail').forEach(btn=>{
      btn.addEventListener('click',()=>{
        const item=btn.closest('.review-item'); if(!item) return; const id=item.dataset.id; const panel=document.getElementById('detail-'+id); if(!panel) return; panel.style.display= panel.style.display==='none' ? 'block' : 'none';
      });
    });
  })();
  (function(){
    try {
      var n = document.getElementById('dm-flash');
      var msg = n ? JSON.parse(n.textContent || '""') : '';
      if(msg){ var s=document.createElement('div'); s.className='snackbar'; s.textContent=msg; document.body.appendChild(s); setTimeout(function(){ s.remove(); }, 3000); }
    } catch(_){ }
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
</style>
@endpush
