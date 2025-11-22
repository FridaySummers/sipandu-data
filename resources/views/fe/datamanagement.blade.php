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
          <h3>Daftar Data</h3>
          <div class="card-actions" style="gap:8px;display:flex;align-items:center;">
            <div class="search-box compact"><i class="fas fa-search"></i><input id="dm-search" type="text" placeholder="Cari data..."></div>
            <select id="dm-status-filter" class="form-control" style="min-width:140px"><option value="">Semua Status</option><option value="Approved">Approved</option><option value="In Review">In Review</option><option value="Pending">Pending</option></select>
            <button class="btn btn-outline btn-sm" id="dm-filter"><i class="fas fa-filter"></i> Filter</button>
            <button class="btn btn-outline btn-sm" id="dm-export"><i class="fas fa-file-export"></i> Export</button>
            <button class="btn btn-primary btn-sm" id="dm-add-open"><i class="fas fa-plus"></i> Tambah Data</button>
          </div>
        </div>
        <div class="card-body">
          <div class="filter-panel" id="dm-filter-panel" style="display:none">
            <div class="filter-grid">
              <div class="form-row"><label>OPD</label><select id="dm-filter-opd" class="form-control"><option value="">Semua OPD</option></select></div>
              <div class="form-row"><label>Kategori</label><select id="dm-filter-category" class="form-control"><option value="">Semua Kategori</option><option value="Harga">Harga</option><option value="Produksi">Produksi</option><option value="SDM">SDM</option></select></div>
            </div>
          </div>
          <table class="table table-compact" id="dm-table"></table>
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
            <div class="dm-grid">
              <div>
                <div class="form-row"><label>Dinas</label><select id="dm-add-opd" class="form-control"><option value="">Pilih OPD</option></select></div>
                <div class="form-row"><label>Kategori</label><select id="dm-add-category" class="form-control"><option value="Harga">Harga</option><option value="Produksi">Produksi</option><option value="SDM">SDM</option></select></div>
                <div class="form-row"><label>Nama Data</label><input id="dm-add-name" class="form-control" placeholder="Contoh: Data Inflasi dan Harga Bahan Pokok"></div>
                <div class="form-row"><label>Periode</label><input id="dm-add-period" class="form-control" placeholder="Nov 2025"></div>
                <div class="form-row"><label>Penanggung Jawab</label><input id="dm-add-pic" class="form-control" placeholder="Nama penanggung jawab"></div>
                <div class="dropzone" id="dm-add-dropzone" style="margin-top:12px">
                  <div class="dropzone-inner"><i class="fas fa-upload"></i><div>Tarik & letakkan file di sini</div></div>
                  <div class="upload-actions"><button id="dm-add-select" class="btn btn-outline btn-sm" type="button"><i class="fas fa-file-upload"></i> Pilih File</button></div>
                  <input id="dm-add-files" type="file" multiple>
                </div>
              </div>
              <div>
                <div class="card" style="margin-top:8px">
                  <div class="card-header"><h3>Form Khusus OPD</h3></div>
                  <div class="card-body" id="dm-add-schema"></div>
                </div>
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
</style>
@endpush
