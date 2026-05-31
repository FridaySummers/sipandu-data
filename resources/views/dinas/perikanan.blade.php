@extends('layouts.app')
@section('title', 'Perikanan')
@section('body-class', 'dashboard-page force-light')

@push('styles')
<style>
  .fk-card{border-radius:20px;overflow:hidden;margin-bottom:20px;box-shadow:0 4px 24px rgba(0,0,0,0.06);border:1px solid #e2e8f0;background:#fff}
  .fk-card .card-header{padding:20px 24px;display:flex;align-items:center;justify-content:space-between;background:linear-gradient(135deg,#1d4ed8,#3b82f6);color:#ffffff;border-radius:20px 20px 0 0}
  .fk-card .card-header h3{display:flex;flex-direction:column;line-height:1.3;font-size:1.1rem;font-weight:700;margin:0}
  .fk-card .card-header h3 span{font-weight:500;font-size:13px;opacity:.85;margin-top:2px}
  .fk-card .card-actions .btn{border-radius:10px;padding:8px 16px;font-weight:600;font-size:13px;transition:all .2s;display:inline-flex;align-items:center;gap:6px}
  .fk-card .card-actions .btn-primary{background:#fff;color:#1d4ed8;border:none;box-shadow:0 2px 8px rgba(0,0,0,0.1)}
  .fk-card .card-actions .btn-primary:hover{transform:translateY(-1px);box-shadow:0 4px 12px rgba(255,255,255,0.3)}
  .fk-panel{margin:16px 20px;border-radius:14px;padding:20px;border:1px solid #e2e8f0;background:#f8fafc}
  .fk-panel .form-group{display:flex;flex-direction:column;gap:6px;margin-bottom:12px}
  .fk-panel .form-group label{font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.04em}
  .fk-sub{font-size:13px;color:#64748b;margin-top:4px;margin-bottom:16px;padding:0 20px}
  #perikanan-page h4{font-size:1.05rem;font-weight:700;color:#0f172a;margin:20px 20px 4px}
  .fk-grid{display:grid;gap:12px}
  .fk-grid-2{grid-template-columns:repeat(2,1fr)}
  .fk-grid-3{grid-template-columns:repeat(3,1fr)}
  .fk-grid-5{grid-template-columns:repeat(5,minmax(90px,1fr))}
  .form-control{border:1.5px solid #e2e8f0;background:#ffffff;border-radius:10px;padding:10px 14px;font-size:14px;transition:all .2s;width:100%;box-sizing:border-box}
  .form-control:focus{outline:none;border-color:#3b82f6;box-shadow:0 0 0 4px rgba(59,130,246,0.12)}
  .table-wrap{border-radius:14px;overflow-x:auto;background:#fff;border:1px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,0.04);margin:0 20px 20px}
  .fk-table{width:100%;border-collapse:separate;border-spacing:0;table-layout:fixed;margin:0}
  .fk-table thead th{background:linear-gradient(135deg,#eff6ff,#dbeafe);color:#1e3a8a;font-weight:700;font-size:13px;border-bottom:2px solid #bfdbfe;padding:12px 14px}
  .fk-table th,.fk-table td{border:1px solid #cbd5e1;border-right:1px solid #f1f5f9;padding:11px 14px;word-break:break-word;white-space:normal}
  .fk-table thead tr th:first-child{border-left:none}
  .fk-table tbody tr td:first-child{border-left:none}
  .fk-table th{font-weight:600;text-align:center}
  .fk-table td{text-align:center;vertical-align:middle}
  .fk-table tbody tr{transition:background .15s ease}
  .fk-table tbody tr:hover{background:#f0f9ff}
  .fk-table th:last-child,.fk-table td:last-child{text-align:center}
  .fk-table td:last-child{display:table-cell !important;text-align:center !important;white-space:nowrap}
  #perikanan-page .fk-table td:last-child .btn{margin-right:0 !important}
  .toolbar{display:none !important}
  .btn-outline{background:#fff;border:1.5px solid #e2e8f0;color:#374151;border-radius:10px;font-weight:600;transition:all .2s;display:inline-flex;align-items:center;justify-content:center}
  .btn-outline:hover{background:#f8fafc;border-color:#93c5fd;color:#2563eb}
  .btn-primary{background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff;border:none;font-weight:600;border-radius:10px;transition:all .2s}
  .btn-primary:hover{box-shadow:0 4px 14px rgba(37,99,235,0.3);transform:translateY(-1px)}
  .btn-secondary{background:#f1f5f9;color:#374151;border:1px solid #e2e8f0;border-radius:10px;font-weight:600;transition:all .2s}
  .btn-secondary:hover{background:#e2e8f0}
  .status-badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600}
  .status-approved{background:#dcfce7;color:#166534}
  .status-rejected{background:#fee2e2;color:#7f1d1d}
  .status-menunggu{background:#fffbeb;color:#92400e}
  .fk-table thead th:last-child{width:100px !important}
  @media (max-width: 640px){.fk-table thead th:last-child{width:auto !important}.fk-table td:last-child{white-space:normal !important}}
  .row-actions{display:flex;align-items:center;justify-content:center;gap:6px}
  .row-actions .fa-pen{color:#3b82f6}
  .segmented{display:inline-flex;gap:6px;background:#f1f5f9;padding:6px;border-radius:12px;margin:20px 20px 0}
  .segmented .btn{height:36px;border-radius:8px;font-weight:600;font-size:13px;padding:0 16px;transition:all .2s;border:none}
  .segmented .btn-primary{background:#fff;color:#1d4ed8;box-shadow:0 2px 8px rgba(0,0,0,0.08)}
  .segmented .btn-outline{background:transparent;color:#64748b}
  .segmented .btn-outline:hover{background:rgba(255,255,255,0.6);color:#374151}
  #perikanan-page .card .card-header{background:linear-gradient(135deg,#1d4ed8,#3b82f6);color:#ffffff;border-bottom:none;border-radius:20px 20px 0 0}
  #perikanan-page .card .card-header .card-actions .btn{border-radius:10px;height:36px;padding:0 14px;background:#fff;color:#1d4ed8;border:none}
  #perikanan-page .btn.btn-outline.btn-sm.action-btn{border-radius:8px;width:32px;height:32px;padding:0}
</style>
@endpush

@section('content')
    <div class="page active" id="perikanan-page">
      <div class="page-header"><h1>Data Perikanan</h1><p>Infrastruktur, alat tangkap, budidaya, produksi, dan bina kelompok</p></div>
      <style>
        #perikanan-page .card .card-header{background:linear-gradient(90deg,#60a5fa,#3b82f6);color:#ffffff;border-bottom:1px solid transparent}
        #perikanan-page .card .card-header .card-actions .btn{border-radius:8px;height:34px;padding:0 12px}
        #perikanan-page .btn.btn-outline.btn-sm.action-btn{border-radius:8px;width:30px;height:30px}
        #perikanan-page .toolbar{display:none !important}
      </style>
      <div class="card fk-card">
          <div class="card-header">
          <h3>Data Perikanan Lengkap<span>Kabupaten Kolaka Utara Tahun 2025 - 2029</span></h3>
          <div class="card-actions"><button class="btn btn-primary btn-sm" id="fk-export"><i class="fas fa-download"></i> Export Data</button> <button class="btn btn-primary btn-sm" id="fk-add"><i class="fas fa-plus"></i> Ajukan Data</button></div>
        </div>
        <div class="card-body">
          <div class="segmented"><button class="btn btn-primary btn-sm" id="fk-tab-inf">Infrastruktur</button><button class="btn btn-outline btn-sm" id="fk-tab-alt">Alat Tangkap</button><button class="btn btn-outline btn-sm" id="fk-tab-bud">Budidaya</button><button class="btn btn-outline btn-sm" id="fk-tab-pro">Produksi</button><button class="btn btn-outline btn-sm" id="fk-tab-bin">Bina Kelompok</button></div>

          <div id="fk-inf">
            <h4>Perkembangan Jumlah Rumah Tangga Nelayan dan Alat Penangkapan Ikan</h4>
            <div class="fk-sub">Perkembangan jumlah RT nelayan dan alat penangkapan 2025–2029</div>
            <div class="fk-panel" id="fk-inf-panel" style="display:none;">
              <div class="form-group"><label>Nama Data</label><input class="form-control" id="inf-uraian" placeholder="Contoh: Nama Data"></div>
              <div class="fk-grid fk-grid-5"><div class="form-group"><label>Tahun 2025</label><input class="form-control" id="inf-2025" placeholder="0"></div><div class="form-group"><label>Tahun 2026</label><input class="form-control" id="inf-2026" placeholder="0"></div><div class="form-group"><label>Tahun 2027</label><input class="form-control" id="inf-2027" placeholder="0"></div><div class="form-group"><label>Tahun 2028</label><input class="form-control" id="inf-2028" placeholder="0"></div><div class="form-group"><label>Tahun 2029</label><input class="form-control" id="inf-2029" placeholder="0"></div></div>
              <div style="display:flex;gap:8px;justify-content:flex-end"><button class="btn btn-secondary" id="inf-cancel">Batal</button><button class="btn btn-primary" id="inf-save">Simpan</button></div>
            </div>
            <div class="toolbar"></div>
            <div class="table-wrap"><table class="table table-compact fk-table"><thead><tr><th rowspan="2">Uraian Infrastruktur Perikanan Tangkap</th><th colspan="5">Jumlah (Unit)</th><th rowspan="2">Status</th><th rowspan="2">Aksi</th></tr><tr><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th></tr></thead><tbody id="fk-inf-tbody"></tbody></table></div>
          </div>

          <div id="fk-alt" style="display:none;">
            <h4>Perkembangan Alat Penangkapan Ikan Menurut Jenis</h4>
            <div class="fk-sub">Jumlah alat penangkapan menurut kategori dan jenis 2025–2029</div>
            <div class="fk-panel" id="fk-alt-panel" style="display:none;">
              <div class="fk-grid fk-grid-2"><div class="form-group"><label>Kategori (Pukat/Jaring/Perangkap/Pancing)</label><input class="form-control" id="alt-kat" placeholder="Contoh: Jaring"></div><div class="form-group"><label>Jenis Alat Penangkapan Ikan</label><input class="form-control" id="alt-jenis" placeholder="Contoh: Jaring insang tetap"></div></div>
              <div class="fk-grid fk-grid-5"><div class="form-group"><label>Tahun 2025</label><input class="form-control" id="alt-2025" placeholder="0"></div><div class="form-group"><label>Tahun 2026</label><input class="form-control" id="alt-2026" placeholder="0"></div><div class="form-group"><label>Tahun 2027</label><input class="form-control" id="alt-2027" placeholder="0"></div><div class="form-group"><label>Tahun 2028</label><input class="form-control" id="alt-2028" placeholder="0"></div><div class="form-group"><label>Tahun 2029</label><input class="form-control" id="alt-2029" placeholder="0"></div></div>
              
              <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:10px"><button class="btn btn-secondary" id="alt-cancel">Batal</button><button class="btn btn-primary" id="alt-save">Simpan Semua</button></div>
            </div>
            <div class="toolbar"></div>
            <div class="table-wrap"><table class="table table-compact fk-table"><thead><tr><th colspan="2">Uraian Jenis Alat Penangkapan Ikan</th><th colspan="5">Tahun (Unit)</th><th rowspan="2">Status</th><th rowspan="2">Aksi</th></tr><tr><th>Kategori</th><th>Jenis Alat</th><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th></tr></thead><tbody id="fk-alt-tbody"></tbody></table></div>
          </div>

          <div id="fk-bud" style="display:none;">
            <h4>Perkembangan Budidaya Perairan Menurut Jenis Pengolahan</h4>
            <div class="fk-sub">Ringkasan indikator budidaya perairan dan pengolahan 2025–2029</div>
            <div class="fk-panel" id="fk-bud-panel" style="display:none;">
              <div class="fk-grid fk-grid-2"><div class="form-group"><label>No</label><input class="form-control" id="bud-no" placeholder="1"></div><div class="form-group"><label>Nama Data</label><input class="form-control" id="bud-uraian" placeholder="Contoh: Nama Data"></div></div>
              <div class="fk-grid fk-grid-5"><div class="form-group"><label>Tahun 2025</label><input class="form-control" id="bud-2025" placeholder="0"></div><div class="form-group"><label>Tahun 2026</label><input class="form-control" id="bud-2026" placeholder="0"></div><div class="form-group"><label>Tahun 2027</label><input class="form-control" id="bud-2027" placeholder="0"></div><div class="form-group"><label>Tahun 2028</label><input class="form-control" id="bud-2028" placeholder="0"></div><div class="form-group"><label>Tahun 2029</label><input class="form-control" id="bud-2029" placeholder="0"></div></div>
              
              <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:10px"><button class="btn btn-secondary" id="bud-cancel">Batal</button><button class="btn btn-primary" id="bud-save">Simpan Semua</button></div>
            </div>
            <div class="toolbar"></div>
            <div class="table-wrap"><table class="table table-compact fk-table"><thead><tr><th rowspan="2">No</th><th rowspan="2">Uraian</th><th colspan="5">Tahun</th><th rowspan="2">Status</th><th rowspan="2">Aksi</th></tr><tr><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th></tr></thead><tbody id="fk-bud-tbody"></tbody></table></div>
          </div>

          <div id="fk-pro" style="display:none;">
            <h4>Produksi Perikanan di Kabupaten Kolaka Utara</h4>
            <div class="fk-sub">Produksi perikanan per kategori dan uraian 2025–2029</div>
            <div class="fk-panel" id="fk-pro-panel" style="display:none;">
              <div class="fk-grid fk-grid-2"><div class="form-group"><label>Kategori</label><input class="form-control" id="pro-kat" placeholder="Contoh: Kategori"></div><div class="form-group"><label>Nama Data</label><input class="form-control" id="pro-uraian" placeholder="Contoh: Nama Data"></div></div>
              <div class="fk-grid fk-grid-5"><div class="form-group"><label>Tahun 2025</label><input class="form-control" id="pro-2025" placeholder="0"></div><div class="form-group"><label>Tahun 2026</label><input class="form-control" id="pro-2026" placeholder="0"></div><div class="form-group"><label>Tahun 2027</label><input class="form-control" id="pro-2027" placeholder="0"></div><div class="form-group"><label>Tahun 2028</label><input class="form-control" id="pro-2028" placeholder="0"></div><div class="form-group"><label>Tahun 2029</label><input class="form-control" id="pro-2029" placeholder="0"></div></div>
              
              <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:10px"><button class="btn btn-secondary" id="pro-cancel">Batal</button><button class="btn btn-primary" id="pro-save">Simpan Semua</button></div>
            </div>
            <div class="toolbar"></div>
            <div class="table-wrap"><table class="table table-compact fk-table"><thead><tr><th rowspan="2">No</th><th rowspan="2">Uraian</th><th colspan="5">Tahun</th><th rowspan="2">Status</th><th rowspan="2">Aksi</th></tr><tr><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th></tr></thead><tbody id="fk-pro-tbody"></tbody></table></div>
          </div>

          <div id="fk-bin" style="display:none;">
            <h4>Cakupan Bina Kelompok Perikanan Tahun 2025 s.d 2029</h4>
            <div class="fk-sub">Ringkasan cakupan bina kelompok perikanan 2025–2029</div>
            <div class="fk-panel" id="fk-bin-panel" style="display:none;">
              <div class="fk-grid fk-grid-2"><div class="form-group"><label>No</label><input class="form-control" id="bin-no" placeholder="1"></div><div class="form-group"><label>Nama Data</label><input class="form-control" id="bin-uraian" placeholder="Contoh: Nama Data"></div></div>
              <div class="fk-grid fk-grid-5"><div class="form-group"><label>Tahun 2025</label><input class="form-control" id="bin-2025" placeholder="0"></div><div class="form-group"><label>Tahun 2026</label><input class="form-control" id="bin-2026" placeholder="0"></div><div class="form-group"><label>Tahun 2027</label><input class="form-control" id="bin-2027" placeholder="0"></div><div class="form-group"><label>Tahun 2028</label><input class="form-control" id="bin-2028" placeholder="0"></div><div class="form-group"><label>Tahun 2029</label><input class="form-control" id="bin-2029" placeholder="0"></div></div>
              <div style="display:flex;gap:8px;justify-content:flex-end"><button class="btn btn-secondary" id="bin-cancel">Batal</button><button class="btn btn-primary" id="bin-save">Simpan</button></div>
            </div>
            <div class="toolbar"></div>
            <div class="table-wrap"><table class="table table-compact fk-table"><thead><tr><th style="width:60px" rowspan="2">No</th><th style="width:40%" rowspan="2">Uraian</th><th colspan="5">Tahun</th><th rowspan="2">Status</th><th rowspan="2">Aksi</th></tr><tr><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th></tr></thead><tbody id="fk-bin-tbody"></tbody></table></div>
          </div>

        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
var csrfToken=document.querySelector('meta[name="csrf-token"]')?.content||'';window.USER_ROLE=document.body.dataset.userRole||'';
var opdName='Dinas Perikanan';
var dinasId=(document.body.dataset.dinasId||'')||null;
var infRows=[],altRows=[],budRows=[],proRows=[],binRows=[];var infIds=[],altIds=[],budIds=[],proIds=[],binIds=[];var edit={inf:null,alt:null,bud:null,pro:null,bin:null};
var dmStatuses={};
;(function(){var _f=window.fetch;window.fetch=function(u,o){if(o&&(o.method==='POST'||o.method==='PUT'||o.method==='DELETE')){o.credentials=o.credentials||'same-origin';}return _f(u,o);};})();
function statusLabel(s){if(s==='Disetujui')return 'Disetujui';if(s==='Ditolak')return 'Ditolak';if(s==='Menunggu Persetujuan')return 'Menunggu Persetujuan';return '-'}
function statusClass(s){if(s==='Disetujui')return 'status-approved';if(s==='Ditolak')return 'status-rejected';if(s==='Menunggu Persetujuan')return 'status-menunggu';return ''}
async function fetchDmStatuses(){try{var res=await fetch('/data-management/records',{headers:{'Accept':'application/json'},credentials:'same-origin'});var data=await res.json();dmStatuses={};(Array.isArray(data)?data:[]).forEach(function(rec){if(rec.opd===opdName){dmStatuses[rec.name]=rec.status;}});}catch(_){dmStatuses={}}}
function mapInf(data){return (Array.isArray(data)?data:[]).map(function(r){var v=r.values||{};return {id:r.id,uraian:r.uraian,y2025:v.y2025||'',y2026:v.y2026||'',y2027:v.y2027||'',y2028:v.y2028||'',y2029:v.y2029||''};});}
function mapAlt(data){return (Array.isArray(data)?data:[]).map(function(r){var v=r.values||{};return {id:r.id,kat:v.kat||'',jenis:v.jenis||'',y2025:v.y2025||'',y2026:v.y2026||'',y2027:v.y2027||'',y2028:v.y2028||'',y2029:v.y2029||''};});}
function mapStd(data){return (Array.isArray(data)?data:[]).map(function(r){var v=r.values||{};return {id:r.id,uraian:r.uraian,y2025:v.y2025||'',y2026:v.y2026||'',y2027:v.y2027||'',y2028:v.y2028||'',y2029:v.y2029||''};});}

function renderInf(){var tb=document.getElementById('fk-inf-tbody');if(!tb)return;tb.innerHTML=infRows.map(function(r,i){var st=dmStatuses[r.uraian];var canEdit=(window.USER_ROLE!=='user'||st==='Menunggu Persetujuan');var actions=canEdit?((window.USER_ROLE==='user')?('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-fk-ed="inf:'+i+'"><i class="fas fa-pen"></i></button></div>'):('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-fk-ed="inf:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-fk-del="inf:'+i+'"><i class="fas fa-trash"></i></button></div>')):'<div class="row-actions"></div>';return '<tr><td>'+r.uraian+'</td><td>'+ (r.y2025||'-') +'</td><td>'+ (r.y2026||'-') +'</td><td>'+ (r.y2027||'-') +'</td><td>'+ (r.y2028||'-') +'</td><td>'+ (r.y2029||'-') +'</td><td class="c-status"><span class="status-badge '+statusClass(st)+'">'+statusLabel(st)+'</span></td><td>'+actions+'</td></tr>';}).join('');}
function renderAlt(){var tb=document.getElementById('fk-alt-tbody');if(!tb)return;tb.innerHTML=altRows.map(function(r,i){var st=dmStatuses['Alat Tangkap '+(r.jenis||'')];var canEdit=(window.USER_ROLE!=='user'||st==='Menunggu Persetujuan');var actions=canEdit?((window.USER_ROLE==='user')?('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-fk-ed="alt:'+i+'"><i class="fas fa-pen"></i></button></div>'):('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-fk-ed="alt:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-fk-del="alt:'+i+'"><i class="fas fa-trash"></i></button></div>')):'<div class="row-actions"></div>';return '<tr><td>'+ (r.kat||'-') +'</td><td>'+ (r.jenis||'-') +'</td><td>'+ (r.y2025||'-') +'</td><td>'+ (r.y2026||'-') +'</td><td>'+ (r.y2027||'-') +'</td><td>'+ (r.y2028||'-') +'</td><td>'+ (r.y2029||'-') +'</td><td class="c-status"><span class="status-badge '+statusClass(st)+'">'+statusLabel(st)+'</span></td><td>'+actions+'</td></tr>';}).join('');}
function renderBud(){var tb=document.getElementById('fk-bud-tbody');if(!tb)return;tb.innerHTML=budRows.map(function(r,i){var st=dmStatuses['Budidaya '+(r.uraian||'')];var canEdit=(window.USER_ROLE!=='user'||st==='Menunggu Persetujuan');var actions=canEdit?((window.USER_ROLE==='user')?('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-fk-ed="bud:'+i+'"><i class="fas fa-pen"></i></button></div>'):('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-fk-ed="bud:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-fk-del="bud:'+i+'"><i class="fas fa-trash"></i></button></div>')):'<div class="row-actions"></div>';return '<tr><td>'+(i+1)+'</td><td>'+r.uraian+'</td><td>'+ (r.y2025||'-') +'</td><td>'+ (r.y2026||'-') +'</td><td>'+ (r.y2027||'-') +'</td><td>'+ (r.y2028||'-') +'</td><td>'+ (r.y2029||'-') +'</td><td class="c-status"><span class="status-badge '+statusClass(st)+'">'+statusLabel(st)+'</span></td><td>'+actions+'</td></tr>';}).join('');}
function renderPro(){var tb=document.getElementById('fk-pro-tbody');if(!tb)return;tb.innerHTML=proRows.map(function(r,i){var st=dmStatuses['Produksi '+(r.uraian||'')];var canEdit=(window.USER_ROLE!=='user'||st==='Menunggu Persetujuan');var actions=canEdit?((window.USER_ROLE==='user')?('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-fk-ed="pro:'+i+'"><i class="fas fa-pen"></i></button></div>'):('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-fk-ed="pro:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-fk-del="pro:'+i+'"><i class="fas fa-trash"></i></button></div>')):'<div class="row-actions"></div>';return '<tr><td>'+(i+1)+'</td><td>'+r.uraian+'</td><td>'+ (r.y2025||'-') +'</td><td>'+ (r.y2026||'-') +'</td><td>'+ (r.y2027||'-') +'</td><td>'+ (r.y2028||'-') +'</td><td>'+ (r.y2029||'-') +'</td><td class="c-status"><span class="status-badge '+statusClass(st)+'">'+statusLabel(st)+'</span></td><td>'+actions+'</td></tr>';}).join('');}
function renderBin(){var tb=document.getElementById('fk-bin-tbody');if(!tb)return;tb.innerHTML=binRows.map(function(r,i){var st=dmStatuses['Bina Kelompok '+(r.uraian||'')];var canEdit=(window.USER_ROLE!=='user'||st==='Menunggu Persetujuan');var actions=canEdit?((window.USER_ROLE==='user')?('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-fk-ed="bin:'+i+'"><i class="fas fa-pen"></i></button></div>'):('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-fk-ed="bin:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-fk-del="bin:'+i+'"><i class="fas fa-trash"></i></button></div>')):'<div class="row-actions"></div>';return '<tr><td>'+(i+1)+'</td><td>'+r.uraian+'</td><td>'+ (r.y2025||'-') +'</td><td>'+ (r.y2026||'-') +'</td><td>'+ (r.y2027||'-') +'</td><td>'+ (r.y2028||'-') +'</td><td>'+ (r.y2029||'-') +'</td><td class="c-status"><span class="status-badge '+statusClass(st)+'">'+statusLabel(st)+'</span></td><td>'+actions+'</td></tr>';}).join('');}
function endpointFor(key){return key==='inf'?'/perikanan/inf':key==='alt'?'/perikanan/alt':key==='bud'?'/perikanan/bud':key==='pro'?'/perikanan/pro':'/perikanan/bin'}
async function fetchRows(key){try{var url=endpointFor(key);var res=await fetch(url,{headers:{'Accept':'application/json'}});var data=await res.json();if(key==='inf'){infRows=mapInf(data);}else if(key==='alt'){altRows=mapAlt(data);}else if(key==='bud'){budRows=mapStd(data);}else if(key==='pro'){proRows=mapStd(data);}else{binRows=mapStd(data);}await fetchDmStatuses();if(key==='inf'){renderInf();}else if(key==='alt'){renderAlt();}else if(key==='bud'){renderBud();}else if(key==='pro'){renderPro();}else{renderBin();}}catch(_){await fetchDmStatuses();if(key==='inf'){infRows=[];renderInf();}else if(key==='alt'){altRows=[];renderAlt();}else if(key==='bud'){budRows=[];renderBud();}else if(key==='pro'){proRows=[];renderPro();}else{binRows=[];renderBin();}}}
document.addEventListener('DOMContentLoaded',function(){fetchRows('inf');fetchRows('alt');fetchRows('bud');fetchRows('pro');fetchRows('bin');});

function toggleTab(active){['fk-inf','fk-alt','fk-bud','fk-pro','fk-bin'].forEach(function(id){document.getElementById(id).style.display=id===active?'block':'none';});var ids=['fk-tab-inf','fk-tab-alt','fk-tab-bud','fk-tab-pro','fk-tab-bin'];ids.forEach(function(i){var b=document.getElementById(i);b.classList.add('btn-outline');b.classList.remove('btn-primary');});var map={'fk-inf':'fk-tab-inf','fk-alt':'fk-tab-alt','fk-bud':'fk-tab-bud','fk-pro':'fk-tab-pro','fk-bin':'fk-tab-bin'};var act=document.getElementById(map[active]);act.classList.add('btn-primary');act.classList.remove('btn-outline');}
document.getElementById('fk-tab-inf')?.addEventListener('click',function(){toggleTab('fk-inf');});
document.getElementById('fk-tab-alt')?.addEventListener('click',function(){toggleTab('fk-alt');});
document.getElementById('fk-tab-bud')?.addEventListener('click',function(){toggleTab('fk-bud');});
document.getElementById('fk-tab-pro')?.addEventListener('click',function(){toggleTab('fk-pro');});
document.getElementById('fk-tab-bin')?.addEventListener('click',function(){toggleTab('fk-bin');});

 

document.getElementById('fk-add')?.addEventListener('click',function(){var current=document.querySelector('.fk-card .fk-panel:not([style*="display:none"])');if(current){current.style.display='none';}
  var active=['fk-inf','fk-alt','fk-bud','fk-pro','fk-bin'].find(function(id){return document.getElementById(id).style.display!=='none';})||'fk-inf';
  edit.inf=edit.alt=edit.bud=edit.pro=edit.bin=null;
  var panelId={fk_inf:'fk-inf-panel',fk_alt:'fk-alt-panel',fk_bud:'fk-bud-panel',fk_pro:'fk-pro-panel',fk_bin:'fk-bin-panel'};var pid=panelId[active.replace('-', '_')];document.getElementById(pid).style.display='block';});

document.getElementById('inf-cancel')?.addEventListener('click',function(){document.getElementById('fk-inf-panel').style.display='none';});
document.getElementById('alt-cancel')?.addEventListener('click',function(){document.getElementById('fk-alt-panel').style.display='none';});
document.getElementById('bud-cancel')?.addEventListener('click',function(){document.getElementById('fk-bud-panel').style.display='none';});
document.getElementById('pro-cancel')?.addEventListener('click',function(){document.getElementById('fk-pro-panel').style.display='none';});
document.getElementById('bin-cancel')?.addEventListener('click',function(){document.getElementById('fk-bin-panel').style.display='none';});
document.addEventListener('click',function(e){var ed=e.target.closest('[data-fk-ed]');var del=e.target.closest('[data-fk-del]');if(!ed&&!del)return;var p=(ed||del).getAttribute(ed?'data-fk-ed':'data-fk-del').split(':');var key=p[0],i=parseInt(p[1],10);var set=key==='inf'?infRows:key==='alt'?altRows:key==='bud'?budRows:key==='pro'?proRows:binRows;var panelId={'inf':'fk-inf-panel','alt':'fk-alt-panel','bud':'fk-bud-panel','pro':'fk-pro-panel','bin':'fk-bin-panel'}[key];if(ed){var r=set[i];edit[key]=r.id;if(key==='inf'){document.getElementById('inf-uraian').value=r.uraian;document.getElementById('inf-2025').value=r.y2025;document.getElementById('inf-2026').value=r.y2026;document.getElementById('inf-2027').value=r.y2027;document.getElementById('inf-2028').value=r.y2028;document.getElementById('inf-2029').value=r.y2029}else if(key==='alt'){document.getElementById('alt-kat').value=r.kat;document.getElementById('alt-jenis').value=r.jenis;document.getElementById('alt-2025').value=r.y2025;document.getElementById('alt-2026').value=r.y2026;document.getElementById('alt-2027').value=r.y2027;document.getElementById('alt-2028').value=r.y2028;document.getElementById('alt-2029').value=r.y2029}else{document.getElementById(key+'-uraian').value=r.uraian;document.getElementById(key+'-2025').value=r.y2025;document.getElementById(key+'-2026').value=r.y2026;document.getElementById(key+'-2027').value=r.y2027;document.getElementById(key+'-2028').value=r.y2028;document.getElementById(key+'-2029').value=r.y2029}document.getElementById(panelId).style.display='block';} else {Utils.confirm('Hapus baris ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(async function(yes){if(!yes)return;var id=set[i]?.id;try{var res=await fetch(endpointFor(key)+'/'+id,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrfToken}});if(res.ok){await fetchRows(key);Utils.showToast('Baris dihapus','success');}else{Utils.showToast('Gagal menghapus','error');}}catch(e){Utils.showToast('Gagal menghapus','error');}});}});



 

 

async function submitDM(judul,deskripsi,year,key){
  try{
    var fp= key==='inf' ? 'perikanan_inf' : key==='alt' ? 'perikanan_alt' : key==='bud' ? 'perikanan_bud' : key==='pro' ? 'perikanan_pro' : key==='bin' ? 'perikanan_bin' : 'perikanan_inline';
    var res=await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify({opd:opdName,dinas_id:dinasId,judul_data:judul,deskripsi:deskripsi||null,file_path:fp,tahun_perencanaan:year})});
    if(res.ok){ dmStatuses[judul]='Menunggu Persetujuan'; }
  }catch(_){}
}
document.getElementById('inf-save')?.addEventListener('click',async function(){var ura=document.getElementById('inf-uraian').value.trim();if(!ura){Utils.showToast('Isi Nama Data','error');return;}var vals={y2025:document.getElementById('inf-2025').value.trim(),y2026:document.getElementById('inf-2026').value.trim(),y2027:document.getElementById('inf-2027').value.trim(),y2028:document.getElementById('inf-2028').value.trim(),y2029:document.getElementById('inf-2029').value.trim()};var hasVal=(vals.y2025||vals.y2026||vals.y2027||vals.y2028||vals.y2029);if(!hasVal){Utils.showToast('Isi minimal salah satu nilai tahun','error');return;}var _yrRaw=(vals.y2029||'').replace(/[^0-9]/g,'');var year=(_yrRaw&&_yrRaw.length===4)?_yrRaw:new Date().getFullYear().toString();var isUser=(window.USER_ROLE==='user');try{if(!isUser){var id=edit.inf;var url=id?(endpointFor('inf')+'/'+id):endpointFor('inf');var method=id?'PUT':'POST';var payload={uraian:ura,values:vals};var res=await fetch(url,{method:method,headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify(payload)});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows('inf');}await submitDM(ura,null,year,'inf');await fetchDmStatuses();renderInf();document.getElementById('fk-inf-panel').style.display='none';edit.inf=null;Utils.showToast('Data disimpan','success');}catch(e){Utils.showToast('Gagal menyimpan','error');}});
document.getElementById('alt-save')?.addEventListener('click',async function(){var kat=document.getElementById('alt-kat').value.trim();var jenis=document.getElementById('alt-jenis').value.trim();if(!jenis){Utils.showToast('Isi Jenis Alat','error');return;}var vals={kat:kat,jenis:jenis,y2025:document.getElementById('alt-2025').value.trim(),y2026:document.getElementById('alt-2026').value.trim(),y2027:document.getElementById('alt-2027').value.trim(),y2028:document.getElementById('alt-2028').value.trim(),y2029:document.getElementById('alt-2029').value.trim()};var hasVal=(vals.y2025||vals.y2026||vals.y2027||vals.y2028||vals.y2029);if(!hasVal){Utils.showToast('Isi minimal salah satu nilai tahun','error');return;}var _yrRaw=(vals.y2029||'').replace(/[^0-9]/g,'');var year=(_yrRaw&&_yrRaw.length===4)?_yrRaw:new Date().getFullYear().toString();var isUser=(window.USER_ROLE==='user');try{if(!isUser){var id=edit.alt;var url=id?(endpointFor('alt')+'/'+id):endpointFor('alt');var method=id?'PUT':'POST';var payload={uraian:jenis,values:vals};var res=await fetch(url,{method:method,headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify(payload)});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows('alt');}await submitDM('Alat Tangkap '+jenis,kat?('Kategori: '+kat):null,year,'alt');await fetchDmStatuses();renderAlt();document.getElementById('fk-alt-panel').style.display='none';edit.alt=null;Utils.showToast('Data disimpan','success');}catch(e){Utils.showToast('Gagal menyimpan','error');}});
document.getElementById('bud-save')?.addEventListener('click',async function(){var ura=document.getElementById('bud-uraian').value.trim();if(!ura){Utils.showToast('Isi Nama Data','error');return;}var vals={y2025:document.getElementById('bud-2025').value.trim(),y2026:document.getElementById('bud-2026').value.trim(),y2027:document.getElementById('bud-2027').value.trim(),y2028:document.getElementById('bud-2028').value.trim(),y2029:document.getElementById('bud-2029').value.trim()};var hasVal=(vals.y2025||vals.y2026||vals.y2027||vals.y2028||vals.y2029);if(!hasVal){Utils.showToast('Isi minimal salah satu nilai tahun','error');return;}var _yrRaw=(vals.y2029||'').replace(/[^0-9]/g,'');var year=(_yrRaw&&_yrRaw.length===4)?_yrRaw:new Date().getFullYear().toString();var isUser=(window.USER_ROLE==='user');try{if(!isUser){var id=edit.bud;var url=id?(endpointFor('bud')+'/'+id):endpointFor('bud');var method=id?'PUT':'POST';var payload={uraian:ura,values:vals};var res=await fetch(url,{method:method,headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify(payload)});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows('bud');}await submitDM('Budidaya '+ura,null,year,'bud');await fetchDmStatuses();renderBud();document.getElementById('fk-bud-panel').style.display='none';edit.bud=null;Utils.showToast('Data disimpan','success');}catch(e){Utils.showToast('Gagal menyimpan','error');}});
document.getElementById('pro-save')?.addEventListener('click',async function(){var ura=document.getElementById('pro-uraian').value.trim();if(!ura){Utils.showToast('Isi Nama Data','error');return;}var vals={y2025:document.getElementById('pro-2025').value.trim(),y2026:document.getElementById('pro-2026').value.trim(),y2027:document.getElementById('pro-2027').value.trim(),y2028:document.getElementById('pro-2028').value.trim(),y2029:document.getElementById('pro-2029').value.trim()};var hasVal=(vals.y2025||vals.y2026||vals.y2027||vals.y2028||vals.y2029);if(!hasVal){Utils.showToast('Isi minimal salah satu nilai tahun','error');return;}var _yrRaw=(vals.y2029||'').replace(/[^0-9]/g,'');var year=(_yrRaw&&_yrRaw.length===4)?_yrRaw:new Date().getFullYear().toString();var isUser=(window.USER_ROLE==='user');try{if(!isUser){var id=edit.pro;var url=id?(endpointFor('pro')+'/'+id):endpointFor('pro');var method=id?'PUT':'POST';var payload={uraian:ura,values:vals};var res=await fetch(url,{method:method,headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify(payload)});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows('pro');}await submitDM('Produksi '+ura,null,year,'pro');await fetchDmStatuses();renderPro();document.getElementById('fk-pro-panel').style.display='none';edit.pro=null;Utils.showToast('Data disimpan','success');}catch(e){Utils.showToast('Gagal menyimpan','error');}});
document.getElementById('bin-save')?.addEventListener('click',async function(){var ura=document.getElementById('bin-uraian').value.trim();if(!ura){Utils.showToast('Isi Nama Data','error');return;}var vals={y2025:document.getElementById('bin-2025').value.trim(),y2026:document.getElementById('bin-2026').value.trim(),y2027:document.getElementById('bin-2027').value.trim(),y2028:document.getElementById('bin-2028').value.trim(),y2029:document.getElementById('bin-2029').value.trim()};var hasVal=(vals.y2025||vals.y2026||vals.y2027||vals.y2028||vals.y2029);if(!hasVal){Utils.showToast('Isi minimal salah satu nilai tahun','error');return;}var year=(vals.y2029||'').replace(/[^0-9]/g,'').slice(0,4)||new Date().getFullYear().toString();var isUser=(window.USER_ROLE==='user');try{if(!isUser){var id=edit.bin;var url=id?(endpointFor('bin')+'/'+id):endpointFor('bin');var method=id?'PUT':'POST';var payload={uraian:ura,values:vals};var res=await fetch(url,{method:method,headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify(payload)});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows('bin');}await submitDM('Bina Kelompok '+ura,null,year,'bin');await fetchDmStatuses();renderBin();document.getElementById('fk-bin-panel').style.display='none';edit.bin=null;Utils.showToast('Data disimpan','success');}catch(e){Utils.showToast('Gagal menyimpan','error');}});
function exportCsv(filename, headers, rows){var csv=[headers].concat(rows).map(function(row){return row.map(function(v){var s=(''+(v==null?'':v)).replace(/"/g,'""');return '"'+s+'"';}).join(',');}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download=filename;document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url);} 
document.getElementById('fk-export')?.addEventListener('click',function(){var sections=['fk-inf','fk-alt','fk-bud','fk-pro','fk-bin'];var active=sections.find(function(id){return document.getElementById(id).style.display!=='none';});if(active==='fk-inf'){var h=['Uraian','2025','2026','2027','2028','2029'];var rows=infRows.map(function(r){return [r.uraian,r.y2025,r.y2026,r.y2027,r.y2028,r.y2029]});exportCsv('perikanan-infrastruktur.csv',h,rows);}else if(active==='fk-alt'){var h=['Kategori','Jenis','2025','2026','2027','2028','2029'];var rows=altRows.map(function(r){return [r.kat,r.jenis,r.y2025,r.y2026,r.y2027,r.y2028,r.y2029]});exportCsv('perikanan-alat-tangkap.csv',h,rows);}else if(active==='fk-bud'){var h=['No','Uraian','2025','2026','2027','2028','2029'];var rows=budRows.map(function(r,i){return [i+1,r.uraian,r.y2025,r.y2026,r.y2027,r.y2028,r.y2029]});exportCsv('perikanan-budidaya.csv',h,rows);}else if(active==='fk-pro'){var h=['No','Uraian','2025','2026','2027','2028','2029'];var rows=proRows.map(function(r,i){return [i+1,r.uraian,r.y2025,r.y2026,r.y2027,r.y2028,r.y2029]});exportCsv('perikanan-produksi.csv',h,rows);}else if(active==='fk-bin'){var h=['No','Uraian','2025','2026','2027','2028','2029'];var rows=binRows.map(function(r,i){return [i+1,r.uraian,r.y2025,r.y2026,r.y2027,r.y2028,r.y2029]});exportCsv('perikanan-bina-kelompok.csv',h,rows);}});
</script>
@endpush

