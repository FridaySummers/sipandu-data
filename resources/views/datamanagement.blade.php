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
                <button class="btn btn-primary btn-wide" id="dm-add-open"><i class="fas fa-plus"></i> Tambah Data</button>
              </div>
            </div>
            <div class="controls-row">
              <div class="search-box compact"><i class="fas fa-search"></i><input id="dm-search" type="text" placeholder="Cari data..."></div>
              <select id="dm-status-filter" class="form-control"><option value="">Semua Status</option><option value="Approved">Approved</option><option value="In Review">In Review</option><option value="Pending">Pending</option></select>
              <select id="dm-opd-filter" class="form-control"><option value="">Semua Dinas</option></select>
              <select id="dm-priority-filter" class="form-control"><option value="">Semua Priority</option><option value="High">High</option><option value="Medium">Medium</option><option value="Low">Low</option></select>
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
          <div class="modal-header"><h3>Tambah Data Baru</h3><button class="btn btn-outline btn-sm" id="dm-add-close"><i class="fas fa-times"></i></button></div>
          <div class="modal-body">
            <div class="tip-bar"><i class="fas fa-lightbulb"></i> Tips: Pastikan semua field wajib (*) sudah terisi sebelum menyimpan.</div>
            <div class="dm-grid">
              <div class="dm-row-2">
                <div class="form-row"><label>Dinas *</label><select id="dm-add-opd" class="form-control"><option value="">Pilih Dinas</option></select></div>
                <div class="form-row"><label>Kategori *</label><select id="dm-add-category" class="form-control"><option value="Harga">Harga</option><option value="Produksi">Produksi</option><option value="SDM">SDM</option></select></div>
              </div>
              <div class="form-row"><label>Nama Data *</label><input id="dm-add-name" class="form-control" placeholder="Contoh: Data Produksi Tahun 2024"></div>
              <div class="dm-row-2">
                <div class="form-row"><label>Periode</label><input id="dm-add-period" class="form-control" placeholder="Contoh: 2024, Q1 2024"></div>
                <div class="form-row"><label>Penanggung Jawab</label><input id="dm-add-pic" class="form-control" placeholder="Nama PJ"></div>
              </div>
              <div class="dm-row-3">
                <div class="form-row"><label>Status</label><select id="dm-add-status" class="form-control"><option value="Pending">Draft</option><option value="In Review">In Review</option><option value="Approved">Approved</option></select></div>
                <div class="form-row"><label>Priority</label><select id="dm-add-priority" class="form-control"><option value="Medium">Medium Priorit</option><option value="High">High</option><option value="Low">Low</option></select></div>
                <div class="form-row"><label>Progress (%)</label><input id="dm-add-progress" class="form-control" type="number" min="0" max="100" value="0"></div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" id="dm-add-cancel">Batal</button>
            <button class="btn btn-primary" id="dm-add-save"><i class="fas fa-save"></i> Tambah Data</button>
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
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/datamanagement.js') }}"></script>
<script>
  function closeModal(){ const m=document.getElementById('submission-modal'); if(m) m.style.display='none'; }
  const newBtn=document.getElementById('new-submission-btn'); if(newBtn) newBtn.addEventListener('click',()=>{ const m=document.getElementById('submission-modal'); if(m) m.style.display='flex'; });
  window.addEventListener('click',function(e){ const modal=document.getElementById('submission-modal'); if(e.target===modal){ closeModal(); } });
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
