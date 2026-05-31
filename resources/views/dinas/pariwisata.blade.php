@extends('layouts.app')
@section('title', 'Pariwisata')
@section('body-class', 'dashboard-page force-light')

@push('styles')
<style>
.pw-card{border-radius:20px;overflow:hidden;margin-bottom:20px;box-shadow:0 4px 24px rgba(0,0,0,0.06);border:1px solid #e2e8f0;background:#fff}
.pw-card .card-header{padding:20px 24px;display:flex;align-items:center;justify-content:space-between;background:linear-gradient(135deg,#1d4ed8,#3b82f6);color:#ffffff;border-radius:20px 20px 0 0;position:relative;z-index:5;overflow:visible}
.pw-card .card-header h3{display:flex;flex-direction:column;line-height:1.3;font-size:1.1rem;font-weight:700;margin:0}
.pw-card .card-header h3 span{font-weight:500;font-size:13px;opacity:.85;margin-top:2px}
.pw-card .card-actions .btn{border-radius:10px;height:36px;padding:0 14px;font-weight:600;pointer-events:auto;position:relative;z-index:2;font-size:13px;transition:all .2s}
.pw-card .card-header .btn.btn-outline{background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.4);backdrop-filter:blur(4px)}
.pw-card .card-header .btn.btn-outline:hover{background:rgba(255,255,255,0.25)}
.pw-card .card-header .btn.btn-primary{background:#fff;color:#1d4ed8;border:none;font-weight:700}
.pw-card .card-header .btn.btn-primary:hover{box-shadow:0 4px 12px rgba(255,255,255,0.3);transform:translateY(-1px)}
.pw-panel{margin:16px 20px;border-radius:14px;padding:18px;border:1px solid #e2e8f0;background:#f8fafc}
.pw-panel .form-group{display:flex;flex-direction:column;gap:8px;margin-bottom:10px}
.pw-panel label{font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.04em}
.pw-sub{font-size:13px;color:#64748b;margin:4px 0 12px;padding:0 20px}
.pw-grid{display:grid;gap:12px}
.pw-grid-2{grid-template-columns:repeat(2,1fr)}
.pw-grid-3{grid-template-columns:repeat(3,1fr)}
.pw-grid-5{grid-template-columns:repeat(5,1fr)}
.year-group{display:flex;flex-direction:column;gap:6px}
.year-label{font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.04em}
.form-control{border:1.5px solid #e2e8f0;background:#ffffff;border-radius:10px;padding:10px 14px;font-size:14px;transition:all .2s}
.form-control:focus{outline:none;border-color:#3b82f6;box-shadow:0 0 0 4px rgba(59,130,246,0.12)}
.form-control:hover:not(:focus){border-color:#9ca3af}
.table-wrap{border-radius:14px;overflow:hidden;border:1px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,0.04);margin:0 0 4px}
.pw-table{width:100%;border-collapse:separate;border-spacing:0;table-layout:fixed}
.pw-table thead th{background:linear-gradient(135deg,#eff6ff,#dbeafe);color:#1e3a8a;font-weight:700;font-size:13px;border-bottom:2px solid #bfdbfe;padding:12px 14px}
.pw-table th,.pw-table td{border:1px solid #cbd5e1;border-right:1px solid #f1f5f9;padding:11px 14px;word-break:break-word;white-space:normal}
.pw-table thead tr th:first-child{border-left:none}
.pw-table tbody tr td:first-child{border-left:none}
.pw-table th{font-weight:600;text-align:center}
.pw-table td:nth-child(1),.pw-table td:nth-child(2),.pw-table th:nth-child(1),.pw-table th:nth-child(2){text-align:left}
.pw-table th:last-child,.pw-table td:last-child{text-align:center}
.pw-table td:last-child{display:table-cell !important;text-align:center !important;white-space:nowrap}
.pw-table thead th:last-child{width:100px !important}
.pw-table tbody tr{transition:background .15s ease}
.pw-table tbody tr:hover{background:#f0f9ff}
@media (max-width: 640px){.pw-table thead th:last-child{width:auto !important}.pw-table td:last-child{white-space:normal !important}}
.status-badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600}
.status-approved{background:#dcfce7;color:#166534}
.status-rejected{background:#fee2e2;color:#7f1d1d}
.status-menunggu{background:#fffbeb;color:#92400e}
.row-actions .fa-pen{color:#3b82f6}
.btn-outline{background:#fff;border:1.5px solid #e2e8f0;color:#374151;border-radius:10px;font-weight:600;transition:all .2s}
.btn-outline:hover{background:#f8fafc;border-color:#93c5fd;color:#2563eb}
.btn-primary{background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff;border:none;border-radius:10px;font-weight:600;transition:all .2s}
.btn-primary:hover{box-shadow:0 4px 14px rgba(37,99,235,0.3);transform:translateY(-1px)}
.btn-secondary{background:#f1f5f9;color:#374151;border:1px solid #e2e8f0;border-radius:10px;font-weight:600;transition:all .2s}
.btn-secondary:hover{background:#e2e8f0}
.segmented{display:inline-flex;gap:6px;background:#f1f5f9;padding:5px;border-radius:12px;margin:12px 20px 4px}
.segmented .btn{height:34px;border-radius:8px;font-weight:600;border:none;transition:all .2s}
.segmented .btn.btn-primary{background:#fff;color:#1d4ed8;box-shadow:0 2px 6px rgba(0,0,0,0.08)}
.segmented .btn.btn-outline{background:transparent;border:none;color:#64748b}
.segmented .btn.btn-outline:hover{background:rgba(255,255,255,0.6);color:#374151}
.card-body h4{font-size:1rem;font-weight:700;color:#0f172a;margin:16px 20px 4px}
</style>
@endpush

@section('content')
    <div class="page active" id="pariwisata-page">
      <div class="page-header"><h1>Data Pariwisata</h1><p>Akomodasi, wisatawan, objek, kecamatan, dan pemandu</p></div>
      <div class="card pw-card">
        <div class="card-header" style="position:relative;z-index:1">
          <h3>Data Pariwisata Lengkap<span>Kabupaten Kolaka Utara</span></h3>
          <div class="card-actions"><button type="button" class="btn btn-primary btn-sm" id="pw-export" onclick="pwExport()"><i class="fas fa-download"></i> Export Data</button> <button type="button" class="btn btn-primary btn-sm" id="pw-add" onclick="pwToggleAdd()"><i class="fas fa-plus"></i> Ajukan Data</button></div>
        </div>
        <div class="card-body">
          <div class="segmented"><button type="button" class="btn btn-primary btn-sm" id="pw-tab-ako" onclick="toggleTab('pw-ako')">Akomodasi</button><button type="button" class="btn btn-outline btn-sm" id="pw-tab-wis" onclick="toggleTab('pw-wis')">Wisatawan</button><button type="button" class="btn btn-outline btn-sm" id="pw-tab-jen" onclick="toggleTab('pw-jen')">Objek (Jenis)</button><button type="button" class="btn btn-outline btn-sm" id="pw-tab-kec" onclick="toggleTab('pw-kec')">Objek (Kecamatan)</button><button type="button" class="btn btn-outline btn-sm" id="pw-tab-pem" onclick="toggleTab('pw-pem')">Pemandu</button></div>

          <div id="pw-ako">
            <h4>Data Akomodasi Hotel Menurut Kecamatan 2025-2029</h4>
            <div class="pw-sub">Jumlah hotel, kamar, dan tempat tidur per kecamatan 2025–2029</div>
            <div class="pw-panel" id="pw-ako-panel" style="display:none;">
              <div class="form-group"><label>Kecamatan</label><input class="form-control" id="ako-kec" placeholder="Nama Kecamatan"></div>
              <div class="pw-grid pw-grid-5">
                <div>
                  <div class="year-label">Tahun 2025</div>
                  <div class="pw-grid pw-grid-3" style="margin-top:6px">
                    <input class="form-control" id="ako-25-hotel" placeholder="Jumlah Hotel">
                    <input class="form-control" id="ako-25-kamar" placeholder="Jumlah Kamar">
                    <input class="form-control" id="ako-25-tidur" placeholder="Tempat Tidur">
                  </div>
                </div>
                <div>
                  <div class="year-label">Tahun 2026</div>
                  <div class="pw-grid pw-grid-3" style="margin-top:6px">
                    <input class="form-control" id="ako-26-hotel" placeholder="Jumlah Hotel">
                    <input class="form-control" id="ako-26-kamar" placeholder="Jumlah Kamar">
                    <input class="form-control" id="ako-26-tidur" placeholder="Tempat Tidur">
                  </div>
                </div>
                <div>
                  <div class="year-label">Tahun 2027</div>
                  <div class="pw-grid pw-grid-3" style="margin-top:6px">
                    <input class="form-control" id="ako-27-hotel" placeholder="Jumlah Hotel">
                    <input class="form-control" id="ako-27-kamar" placeholder="Jumlah Kamar">
                    <input class="form-control" id="ako-27-tidur" placeholder="Tempat Tidur">
                  </div>
                </div>
                <div>
                  <div class="year-label">Tahun 2028</div>
                  <div class="pw-grid pw-grid-3" style="margin-top:6px">
                    <input class="form-control" id="ako-28-hotel" placeholder="Jumlah Hotel">
                    <input class="form-control" id="ako-28-kamar" placeholder="Jumlah Kamar">
                    <input class="form-control" id="ako-28-tidur" placeholder="Tempat Tidur">
                  </div>
                </div>
                <div>
                  <div class="year-label">Tahun 2029</div>
                  <div class="pw-grid pw-grid-3" style="margin-top:6px">
                    <input class="form-control" id="ako-29-hotel" placeholder="Jumlah Hotel">
                    <input class="form-control" id="ako-29-kamar" placeholder="Jumlah Kamar">
                    <input class="form-control" id="ako-29-tidur" placeholder="Tempat Tidur">
                  </div>
                </div>
              </div>
              <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px"><button class="btn btn-secondary" id="ako-cancel">Batal</button><button class="btn btn-primary" id="ako-save">Simpan</button></div>
            </div>
            
            <div class="table-wrap"><table class="table table-compact pw-table"><thead><tr><th rowspan="2">No</th><th rowspan="2">Kecamatan</th><th colspan="3">Tahun 2025</th><th colspan="3">Tahun 2026</th><th colspan="3">Tahun 2027</th><th colspan="3">Tahun 2028</th><th colspan="3">Tahun 2029</th><th rowspan="2">Status</th><th rowspan="2">Aksi</th></tr><tr><th>Hotel</th><th>Kamar</th><th>Tempat Tidur</th><th>Hotel</th><th>Kamar</th><th>Tempat Tidur</th><th>Hotel</th><th>Kamar</th><th>Tempat Tidur</th><th>Hotel</th><th>Kamar</th><th>Tempat Tidur</th><th>Hotel</th><th>Kamar</th><th>Tempat Tidur</th></tr></thead><tbody id="pw-ako-tbody"></tbody></table></div>
          </div>

          <div id="pw-wis" style="display:none;">
            <h4>Jumlah Wisatawan Mancanegara dan Domestik (2025-2029)</h4>
            <div class="pw-sub">Jumlah wisatawan mancanegara dan domestik per tahun</div>
            <div class="pw-panel" id="pw-wis-panel" style="display:none;">
              <div class="pw-grid pw-grid-5"><div class="form-group"><label>Tahun</label><input class="form-control" id="wis-tahun" placeholder="2024"></div><div class="form-group"><label>Mancanegara</label><input class="form-control" id="wis-manca" placeholder="0"></div><div class="form-group"><label>Domestic</label><input class="form-control" id="wis-dom" placeholder="0"></div><div class="form-group"><label>Jumlah</label><input class="form-control" id="wis-total" placeholder="0"></div></div>
              <div style="display:flex;gap:8px;justify-content:flex-end"><button class="btn btn-secondary" id="wis-cancel">Batal</button><button class="btn btn-primary" id="wis-save">Simpan</button></div>
            </div>
            
            <div class="table-wrap"><table class="table table-compact pw-table"><thead><tr><th rowspan="2">Tahun</th><th colspan="2">Wisatawan</th><th rowspan="2">Jumlah</th><th rowspan="2">Status</th><th rowspan="2">Aksi</th></tr><tr><th>Mancanegara</th><th>Domestic</th></tr></thead><tbody id="pw-wis-tbody"></tbody></table></div>
          </div>

          <div id="pw-jen" style="display:none;">
            <h4>Jumlah Objek Wisata Menurut Jenis dan Statusnya</h4>
            <div class="pw-sub">Jumlah objek wisata menurut jenis dan status (komersil/belum)</div>
            <div class="pw-panel" id="pw-jen-panel" style="display:none;">
              <div class="form-group"><label>Jenis Objek Wisata</label><input class="form-control" id="jen-jenis" placeholder="Contoh: Wisata Alam, Wisata Budaya"></div>
              <div class="pw-grid pw-grid-2"><div class="form-group"><label>Dikomersilkan</label><input class="form-control" id="jen-kom" placeholder="0"></div><div class="form-group"><label>Belum Dikomersilkan</label><input class="form-control" id="jen-belum" placeholder="0"></div></div>
              <div class="form-group"><label>Jumlah Total</label><input class="form-control" id="jen-total" placeholder="0"></div>
              <div style="display:flex;gap:8px;justify-content:flex-end"><button class="btn btn-secondary" id="jen-cancel">Batal</button><button class="btn btn-primary" id="jen-save">Simpan</button></div>
            </div>
            
            <div class="table-wrap"><table class="table table-compact pw-table"><thead><tr><th rowspan="2">Jenis Objek Wisata</th><th colspan="2">Status Objek Wisata</th><th rowspan="2">Jumlah</th><th rowspan="2">Status</th><th rowspan="2">Aksi</th></tr><tr><th>Dikomersilkan</th><th>Belum Dikomersilkan</th></tr></thead><tbody id="pw-jen-tbody"></tbody></table></div>
          </div>

          <div id="pw-kec" style="display:none;">
            <h4>Jumlah Objek Wisata Menurut Kecamatan dan Statusnya (2025-2029)</h4>
            <div class="pw-sub">Jumlah objek wisata per kecamatan dan status 2025–2029</div>
            <div class="pw-panel" id="pw-kec-panel" style="display:none;">
              <div class="form-group"><label>Kecamatan</label><input class="form-control" id="kec-nama" placeholder="Nama Kecamatan"></div>
              <div class="pw-grid pw-grid-2">
                <div>
                  <div class="year-label">Dikomersilkan</div>
                  <div class="pw-grid pw-grid-5" style="margin-top:6px"><input class="form-control" id="kec-kom-25" placeholder="2025"><input class="form-control" id="kec-kom-26" placeholder="2026"><input class="form-control" id="kec-kom-27" placeholder="2027"><input class="form-control" id="kec-kom-28" placeholder="2028"><input class="form-control" id="kec-kom-29" placeholder="2029"></div>
                </div>
                <div>
                  <div class="year-label">Belum Dikomersilkan</div>
                  <div class="pw-grid pw-grid-5" style="margin-top:6px"><input class="form-control" id="kec-bel-25" placeholder="2025"><input class="form-control" id="kec-bel-26" placeholder="2026"><input class="form-control" id="kec-bel-27" placeholder="2027"><input class="form-control" id="kec-bel-28" placeholder="2028"><input class="form-control" id="kec-bel-29" placeholder="2029"></div>
                </div>
              </div>
              <div style="display:flex;gap:8px;justify-content:flex-end"><button class="btn btn-secondary" id="kec-cancel">Batal</button><button class="btn btn-primary" id="kec-save">Simpan</button></div>
            </div>
            
            <div class="table-wrap"><table class="table table-compact pw-table"><thead><tr><th rowspan="2">No</th><th rowspan="2">Kecamatan</th><th colspan="5">Dikomersilkan</th><th colspan="5">Belum Dikomersilkan</th><th rowspan="2">Jumlah</th><th rowspan="2">Status</th><th rowspan="2">Aksi</th></tr><tr><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th></tr></thead><tbody id="pw-kec-tbody"></tbody></table></div>
          </div>

          <div id="pw-pem" style="display:none;">
            <h4>Data Pemandu Wisata (2025-2029)</h4>
            <div class="pw-sub">Jumlah pemandu wisata dan tenaga terlatih 2025–2029</div>
            <div class="pw-panel" id="pw-pem-panel" style="display:none;">
              <div class="form-group"><label>Nama Data</label><input class="form-control" id="pem-uraian" placeholder="Contoh: Nama Data"></div>
              <div class="pw-grid pw-grid-5"><div class="year-group"><div class="year-label">Tahun 2025</div><input class="form-control" id="pem-2025" placeholder="0"></div><div class="year-group"><div class="year-label">Tahun 2026</div><input class="form-control" id="pem-2026" placeholder="0"></div><div class="year-group"><div class="year-label">Tahun 2027</div><input class="form-control" id="pem-2027" placeholder="0"></div><div class="year-group"><div class="year-label">Tahun 2028</div><input class="form-control" id="pem-2028" placeholder="0"></div><div class="year-group"><div class="year-label">Tahun 2029</div><input class="form-control" id="pem-2029" placeholder="0"></div></div>
              <div style="display:flex;gap:8px;justify-content:flex-end"><button class="btn btn-secondary" id="pem-cancel">Batal</button><button class="btn btn-primary" id="pem-save">Simpan</button></div>
            </div>
            
            <div class="table-wrap"><table class="table table-compact pw-table"><thead><tr><th>No</th><th>Uraian</th><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th><th>Status</th><th>Aksi</th></tr></thead><tbody id="pw-pem-tbody"></tbody></table></div>
          </div>

        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
var csrfToken=document.querySelector('meta[name=\"csrf-token\"]')?.content||'';window.USER_ROLE=document.body.dataset.userRole||'';var opdName='Dinas Pariwisata';
var dinasId=(document.body.dataset.dinasId||'')||null;
var akoRows=[],wisRows=[],jenRows=[],kecRows=[],pemRows=[];
var dmStatuses={};function statusLabel(s){if(s==='Disetujui')return 'Disetujui';if(s==='Ditolak')return 'Ditolak';if(s==='Menunggu Persetujuan')return 'Menunggu Persetujuan';return '-'}function statusClass(s){if(s==='Disetujui')return 'status-approved';if(s==='Ditolak')return 'status-rejected';if(s==='Menunggu Persetujuan')return 'status-menunggu';return ''}
async function fetchDmStatuses(){try{var res=await fetch('/data-management/records',{headers:{'Accept':'application/json'},credentials:'same-origin'});var data=await res.json();dmStatuses={};(Array.isArray(data)?data:[]).forEach(function(rec){if(rec.opd===opdName){dmStatuses[rec.name]=rec.status;}});}catch(_){dmStatuses={}}}
function mapAko(data){return (Array.isArray(data)?data:[]).map(function(r,i){var v=r.values||{};return {id:r.id,no:i+1,kecamatan:r.uraian,h25:v.h25||'',k25:v.k25||'',t25:v.t25||'',h26:v.h26||'',k26:v.k26||'',t26:v.t26||'',h27:v.h27||'',k27:v.k27||'',t27:v.t27||'',h28:v.h28||'',k28:v.k28||'',t28:v.t28||'',h29:v.h29||'',k29:v.k29||'',t29:v.t29||''};});}
function mapWis(data){return (Array.isArray(data)?data:[]).map(function(r){var v=r.values||{};return {id:r.id,tahun:v.tahun||'',manca:v.manca||'',dom:v.dom||'',jumlah:v.jumlah||''};});}
function mapJen(data){return (Array.isArray(data)?data:[]).map(function(r){var v=r.values||{};return {id:r.id,jenis:r.uraian,kom:v.kom||'',bel:v.bel||'',jumlah:v.jumlah||''};});}
function mapKec(data){return (Array.isArray(data)?data:[]).map(function(r,i){var v=r.values||{};return {id:r.id,no:i+1,kecamatan:r.uraian,kom25:v.kom25||'',kom26:v.kom26||'',kom27:v.kom27||'',kom28:v.kom28||'',kom29:v.kom29||'',bel25:v.bel25||'',bel26:v.bel26||'',bel27:v.bel27||'',bel28:v.bel28||'',bel29:v.bel29||'',jumlah:v.jumlah||''};});}
function mapPem(data){return (Array.isArray(data)?data:[]).map(function(r,i){var v=r.values||{};return {id:r.id,no:i+1,uraian:r.uraian,y2025:v.y2025||'',y2026:v.y2026||'',y2027:v.y2027||'',y2028:v.y2028||'',y2029:v.y2029||''};});}

function renderAko(){var tb=document.getElementById('pw-ako-tbody');if(!tb)return;tb.innerHTML=akoRows.map(function(r,i){var st=dmStatuses['Akomodasi '+(r.kecamatan||r.uraian)||r.kecamatan];var canEdit=(window.USER_ROLE!=='user'||st==='Menunggu Persetujuan');var actions=canEdit?((window.USER_ROLE==='user')?('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-pw-ed="ako:'+i+'"><i class="fas fa-pen"></i></button></div>'):('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-pw-ed="ako:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-pw-del="ako:'+i+'"><i class="fas fa-trash"></i></button></div>')):'<div class="row-actions"></div>';return '<tr><td>'+r.no+'</td><td>'+r.kecamatan+'</td><td>'+r.h25+'</td><td>'+r.k25+'</td><td>'+r.t25+'</td><td>'+r.h26+'</td><td>'+r.k26+'</td><td>'+r.t26+'</td><td>'+r.h27+'</td><td>'+r.k27+'</td><td>'+r.t27+'</td><td>'+r.h28+'</td><td>'+r.k28+'</td><td>'+r.t28+'</td><td>'+r.h29+'</td><td>'+r.k29+'</td><td>'+r.t29+'</td><td class="c-status"><span class="status-badge '+statusClass(st)+'">'+statusLabel(st)+'</span></td><td>'+actions+'</td></tr>';}).join('');}
function renderWis(){var tb=document.getElementById('pw-wis-tbody');if(!tb)return;tb.innerHTML=wisRows.map(function(r,i){var st=dmStatuses['Wisatawan '+(r.tahun||'')];var canEdit=(window.USER_ROLE!=='user'||st==='Menunggu Persetujuan');var actions=canEdit?((window.USER_ROLE==='user')?('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-pw-ed="wis:'+i+'"><i class="fas fa-pen"></i></button></div>'):('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-pw-ed="wis:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-pw-del="wis:'+i+'"><i class="fas fa-trash"></i></button></div>')):'<div class="row-actions"></div>';return '<tr><td>'+r.tahun+'</td><td>'+r.manca+'</td><td>'+r.dom+'</td><td>'+r.jumlah+'</td><td class="c-status"><span class="status-badge '+statusClass(st)+'">'+statusLabel(st)+'</span></td><td>'+actions+'</td></tr>';}).join('');}
function renderJen(){var tb=document.getElementById('pw-jen-tbody');if(!tb)return;tb.innerHTML=jenRows.map(function(r,i){var st=dmStatuses['Objek Wisata '+(r.jenis||r.uraian||'')];var canEdit=(window.USER_ROLE!=='user'||st==='Menunggu Persetujuan');var actions=canEdit?((window.USER_ROLE==='user')?('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-pw-ed="jen:'+i+'"><i class="fas fa-pen"></i></button></div>'):('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-pw-ed="jen:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-pw-del="jen:'+i+'"><i class="fas fa-trash"></i></button></div>')):'<div class="row-actions"></div>';return '<tr><td>'+r.jenis+'</td><td>'+r.kom+'</td><td>'+r.bel+'</td><td>'+r.jumlah+'</td><td class="c-status"><span class="status-badge '+statusClass(st)+'">'+statusLabel(st)+'</span></td><td>'+actions+'</td></tr>';}).join('');}
function renderKec(){var tb=document.getElementById('pw-kec-tbody');if(!tb)return;tb.innerHTML=kecRows.map(function(r,i){var st=dmStatuses['Objek Wisata '+(r.kecamatan||'')];var canEdit=(window.USER_ROLE!=='user'||st==='Menunggu Persetujuan');var actions=canEdit?((window.USER_ROLE==='user')?('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-pw-ed="kec:'+i+'"><i class="fas fa-pen"></i></button></div>'):('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-pw-ed="kec:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-pw-del="kec:'+i+'"><i class="fas fa-trash"></i></button></div>')):'<div class="row-actions"></div>';return '<tr><td>'+r.no+'</td><td>'+r.kecamatan+'</td><td>'+r.kom25+'</td><td>'+r.kom26+'</td><td>'+r.kom27+'</td><td>'+r.kom28+'</td><td>'+r.kom29+'</td><td>'+r.bel25+'</td><td>'+r.bel26+'</td><td>'+r.bel27+'</td><td>'+r.bel28+'</td><td>'+r.bel29+'</td><td>'+r.jumlah+'</td><td class="c-status"><span class="status-badge '+statusClass(st)+'">'+statusLabel(st)+'</span></td><td>'+actions+'</td></tr>';}).join('');}
function renderPem(){var tb=document.getElementById('pw-pem-tbody');if(!tb)return;tb.innerHTML=pemRows.map(function(r,i){var st=dmStatuses['Pemandu '+(r.uraian||'')];var canEdit=(window.USER_ROLE!=='user'||st==='Menunggu Persetujuan');var actions=canEdit?((window.USER_ROLE==='user')?('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-pw-ed="pem:'+i+'"><i class="fas fa-pen"></i></button></div>'):('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-pw-ed="pem:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-pw-del="pem:'+i+'"><i class="fas fa-trash"></i></button></div>')):'<div class="row-actions"></div>';return '<tr><td>'+r.no+'</td><td>'+r.uraian+'</td><td>'+r.y2025+'</td><td>'+r.y2026+'</td><td>'+r.y2027+'</td><td>'+r.y2028+'</td><td>'+r.y2029+'</td><td class="c-status"><span class="status-badge '+statusClass(st)+'">'+statusLabel(st)+'</span></td><td>'+actions+'</td></tr>';}).join('');}
async function fetchAko(){try{var res=await fetch('/pariwisata/ako',{headers:{'Accept':'application/json'}});akoRows=mapAko(await res.json());renderAko();}catch(_){akoRows=[];renderAko();}}
async function fetchWis(){try{var res=await fetch('/pariwisata/wis',{headers:{'Accept':'application/json'}});wisRows=mapWis(await res.json());renderWis();}catch(_){wisRows=[];renderWis();}}
async function fetchJen(){try{var res=await fetch('/pariwisata/jen',{headers:{'Accept':'application/json'}});jenRows=mapJen(await res.json());renderJen();}catch(_){jenRows=[];renderJen();}}
async function fetchKec(){try{var res=await fetch('/pariwisata/kec',{headers:{'Accept':'application/json'}});kecRows=mapKec(await res.json());renderKec();}catch(_){kecRows=[];renderKec();}}
async function fetchPem(){try{var res=await fetch('/pariwisata/pem',{headers:{'Accept':'application/json'}});pemRows=mapPem(await res.json());renderPem();}catch(_){pemRows=[];renderPem();}}
document.addEventListener('DOMContentLoaded',async function(){fetchAko();fetchWis();fetchJen();fetchKec();fetchPem();await fetchDmStatuses();renderAko();renderWis();renderJen();renderKec();renderPem();});

var activeTab='pw-ako';
;(function(){var _f=window.fetch;window.fetch=function(u,o){if(o&&(o.method==='POST'||o.method==='PUT'||o.method==='DELETE')){o.credentials=o.credentials||'same-origin';}return _f(u,o);};})();
function toggleTab(active){activeTab=active;['pw-ako','pw-wis','pw-jen','pw-kec','pw-pem'].forEach(function(id){document.getElementById(id).style.display=id===active?'block':'none';});var ids=['pw-tab-ako','pw-tab-wis','pw-tab-jen','pw-tab-kec','pw-tab-pem'];ids.forEach(function(i){var b=document.getElementById(i);b.classList.add('btn-outline');b.classList.remove('btn-primary');});var map={'pw-ako':'pw-tab-ako','pw-wis':'pw-tab-wis','pw-jen':'pw-tab-jen','pw-kec':'pw-tab-kec','pw-pem':'pw-tab-pem'};var act=document.getElementById(map[active]);act.classList.add('btn-primary');act.classList.remove('btn-outline');}
document.getElementById('pw-tab-ako')?.addEventListener('click',function(){toggleTab('pw-ako');});
document.getElementById('pw-tab-wis')?.addEventListener('click',function(){toggleTab('pw-wis');});
document.getElementById('pw-tab-jen')?.addEventListener('click',function(){toggleTab('pw-jen');});
document.getElementById('pw-tab-kec')?.addEventListener('click',function(){toggleTab('pw-kec');});
document.getElementById('pw-tab-pem')?.addEventListener('click',function(){toggleTab('pw-pem');});
toggleTab('pw-ako');

function pwToggleAdd(){var current=document.querySelector('.pw-card .pw-panel:not([style*="display:none"])');if(current){current.style.display='none';}
  var active=['pw-ako','pw-wis','pw-jen','pw-kec','pw-pem'].find(function(id){return document.getElementById(id).style.display!=='none';})||'pw-ako';
  var panelId={pw_ako:'pw-ako-panel',pw_wis:'pw-wis-panel',pw_jen:'pw-jen-panel',pw_kec:'pw-kec-panel',pw_pem:'pw-pem-panel'};var pid=panelId[active.replace('-', '_')];document.getElementById(pid).style.display='block';}
document.getElementById('pw-add')?.addEventListener('click',pwToggleAdd);

document.addEventListener('click',function(e){var b=e.target.closest('button');if(!b)return;var id=b.id;if(id==='ako-cancel')document.getElementById('pw-ako-panel').style.display='none';else if(id==='wis-cancel')document.getElementById('pw-wis-panel').style.display='none';else if(id==='jen-cancel')document.getElementById('pw-jen-panel').style.display='none';else if(id==='kec-cancel')document.getElementById('pw-kec-panel').style.display='none';else if(id==='pem-cancel')document.getElementById('pw-pem-panel').style.display='none';});
document.addEventListener('click',async function(e){var b=e.target.closest('button');if(!b)return;var id=b.id;var isUser=(window.USER_ROLE==='user');try{
  if(id==='ako-save'){
    var kec=document.getElementById('ako-kec').value.trim();var vals={h25:document.getElementById('ako-25-hotel').value.trim(),k25:document.getElementById('ako-25-kamar').value.trim(),t25:document.getElementById('ako-25-tidur').value.trim(),h26:document.getElementById('ako-26-hotel').value.trim(),k26:document.getElementById('ako-26-kamar').value.trim(),t26:document.getElementById('ako-26-tidur').value.trim(),h27:document.getElementById('ako-27-hotel').value.trim(),k27:document.getElementById('ako-27-kamar').value.trim(),t27:document.getElementById('ako-27-tidur').value.trim(),h28:document.getElementById('ako-28-hotel').value.trim(),k28:document.getElementById('ako-28-kamar').value.trim(),t28:document.getElementById('ako-28-tidur').value.trim(),h29:document.getElementById('ako-29-hotel').value.trim(),k29:document.getElementById('ako-29-kamar').value.trim(),t29:document.getElementById('ako-29-tidur').value.trim()};
    if(!isUser){var el=document.querySelector('[data-pw-ed^="ako:"]');var idx=el?parseInt(el.getAttribute('data-pw-ed').split(':')[1],10):NaN;var idRow=(isNaN(idx)?null:(akoRows[idx]?.id));var url=idRow?('/pariwisata/ako/'+idRow):'/pariwisata/ako';var method=idRow?'PUT':'POST';var res=await fetch(url,{method:method,headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:kec,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchAko();}
    await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({opd:opdName,dinas_id:dinasId,judul_data:'Akomodasi '+kec,file_path:'pariwisata_akomodasi',tahun_perencanaan:(new Date().getFullYear()).toString()})});
    dmStatuses['Akomodasi '+kec]='Menunggu Persetujuan';
    document.getElementById('pw-ako-panel').style.display='none';Utils.showToast('Data disimpan','success');
  } else if(id==='wis-save'){
    var th=document.getElementById('wis-tahun').value.trim();var vals={tahun:th,manca:document.getElementById('wis-manca').value.trim(),dom:document.getElementById('wis-dom').value.trim(),jumlah:document.getElementById('wis-total').value.trim()};
    if(!isUser){var el=document.querySelector('[data-pw-ed^="wis:"]');var idx=el?parseInt(el.getAttribute('data-pw-ed').split(':')[1],10):NaN;var idRow=(isNaN(idx)?null:(wisRows[idx]?.id));var url=idRow?('/pariwisata/wis/'+idRow):'/pariwisata/wis';var method=idRow?'PUT':'POST';var res=await fetch(url,{method:method,headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:'Wisatawan '+th,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchWis();}
    await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({opd:opdName,dinas_id:dinasId,judul_data:'Wisatawan '+th,file_path:'pariwisata_wisatawan',tahun_perencanaan:th.slice(0,4)|| (new Date().getFullYear()).toString()})});
    dmStatuses['Wisatawan '+th]='Menunggu Persetujuan';
    document.getElementById('pw-wis-panel').style.display='none';Utils.showToast('Data disimpan','success');
  } else if(id==='jen-save'){
    var jenis=document.getElementById('jen-jenis').value.trim();var vals={kom:document.getElementById('jen-kom').value.trim(),bel:document.getElementById('jen-belum').value.trim(),jumlah:document.getElementById('jen-total').value.trim()};
    if(!isUser){var el=document.querySelector('[data-pw-ed^="jen:"]');var idx=el?parseInt(el.getAttribute('data-pw-ed').split(':')[1],10):NaN;var idRow=(isNaN(idx)?null:(jenRows[idx]?.id));var url=idRow?('/pariwisata/jen/'+idRow):'/pariwisata/jen';var method=idRow?'PUT':'POST';var res=await fetch(url,{method:method,headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:jenis,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchJen();}
    await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({opd:opdName,dinas_id:dinasId,judul_data:'Objek Wisata '+jenis,file_path:'pariwisata_jenis',tahun_perencanaan:(new Date().getFullYear()).toString()})});
    dmStatuses['Objek Wisata '+jenis]='Menunggu Persetujuan';
    document.getElementById('pw-jen-panel').style.display='none';Utils.showToast('Data disimpan','success');
  } else if(id==='kec-save'){
    var kec=document.getElementById('kec-nama').value.trim();var vals={kom25:document.getElementById('kec-kom-25').value.trim(),kom26:document.getElementById('kec-kom-26').value.trim(),kom27:document.getElementById('kec-kom-27').value.trim(),kom28:document.getElementById('kec-kom-28').value.trim(),kom29:document.getElementById('kec-kom-29').value.trim(),bel25:document.getElementById('kec-bel-25').value.trim(),bel26:document.getElementById('kec-bel-26').value.trim(),bel27:document.getElementById('kec-bel-27').value.trim(),bel28:document.getElementById('kec-bel-28').value.trim(),bel29:document.getElementById('kec-bel-29').value.trim(),jumlah:''};
    if(!isUser){var el=document.querySelector('[data-pw-ed^="kec:"]');var idx=el?parseInt(el.getAttribute('data-pw-ed').split(':')[1],10):NaN;var idRow=(isNaN(idx)?null:(kecRows[idx]?.id));var url=idRow?('/pariwisata/kec/'+idRow):'/pariwisata/kec';var method=idRow?'PUT':'POST';var res=await fetch(url,{method:method,headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:kec,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchKec();}
    await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({opd:opdName,dinas_id:dinasId,judul_data:'Objek Wisata '+kec,file_path:'pariwisata_kecamatan',tahun_perencanaan:(new Date().getFullYear()).toString()})});
    dmStatuses['Objek Wisata '+kec]='Menunggu Persetujuan';
    document.getElementById('pw-kec-panel').style.display='none';Utils.showToast('Data disimpan','success');
  } else if(id==='pem-save'){
    var ura=document.getElementById('pem-uraian').value.trim();var vals={y2025:document.getElementById('pem-2025').value.trim(),y2026:document.getElementById('pem-2026').value.trim(),y2027:document.getElementById('pem-2027').value.trim(),y2028:document.getElementById('pem-2028').value.trim(),y2029:document.getElementById('pem-2029').value.trim()};
    if(!isUser){var el=document.querySelector('[data-pw-ed^="pem:"]');var idx=el?parseInt(el.getAttribute('data-pw-ed').split(':')[1],10):NaN;var idRow=(isNaN(idx)?null:(pemRows[idx]?.id));var url=idRow?('/pariwisata/pem/'+idRow):'/pariwisata/pem';var method=idRow?'PUT':'POST';var res=await fetch(url,{method:method,headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:ura,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchPem();}
    await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({opd:opdName,dinas_id:dinasId,judul_data:'Pemandu '+ura,file_path:'pariwisata_pemandu',tahun_perencanaan:(new Date().getFullYear()).toString()})});
    dmStatuses['Pemandu '+ura]='Menunggu Persetujuan';
    document.getElementById('pw-pem-panel').style.display='none';Utils.showToast('Data disimpan','success');
  }
}catch(err){Utils.showToast('Gagal menyimpan','error');}});
document.addEventListener('click',function(e){var ed=e.target.closest('[data-pw-ed]');if(!ed)return;var p=ed.getAttribute('data-pw-ed').split(':');var key=p[0],i=parseInt(p[1],10);var set=key==='ako'?akoRows:key==='wis'?wisRows:key==='jen'?jenRows:key==='kec'?kecRows:pemRows;var r=set[i];window._pwLastEd={key:key,id:(r&&r.id)||null,index:i};});
document.addEventListener('click',function(e){var ed=e.target.closest('[data-pw-ed]');if(!ed)return;var p=ed.getAttribute('data-pw-ed').split(':');var key=p[0],i=parseInt(p[1],10);var set=key==='ako'?akoRows:key==='wis'?wisRows:key==='jen'?jenRows:key==='kec'?kecRows:pemRows;var panelId={'ako':'pw-ako-panel','wis':'pw-wis-panel','jen':'pw-jen-panel','kec':'pw-kec-panel','pem':'pw-pem-panel'}[key];var r=set[i];if(key==='ako'){document.getElementById('ako-kec').value=r.kecamatan;document.getElementById('ako-25-hotel').value=r.h25;document.getElementById('ako-25-kamar').value=r.k25;document.getElementById('ako-25-tidur').value=r.t25;document.getElementById('ako-26-hotel').value=r.h26;document.getElementById('ako-26-kamar').value=r.k26;document.getElementById('ako-26-tidur').value=r.t26;document.getElementById('ako-27-hotel').value=r.h27;document.getElementById('ako-27-kamar').value=r.k27;document.getElementById('ako-27-tidur').value=r.t27;document.getElementById('ako-28-hotel').value=r.h28;document.getElementById('ako-28-kamar').value=r.k28;document.getElementById('ako-28-tidur').value=r.t28;document.getElementById('ako-29-hotel').value=r.h29;document.getElementById('ako-29-kamar').value=r.k29;document.getElementById('ako-29-tidur').value=r.t29}else if(key==='wis'){document.getElementById('wis-tahun').value=r.tahun;document.getElementById('wis-manca').value=r.manca;document.getElementById('wis-dom').value=r.dom;document.getElementById('wis-total').value=r.jumlah}else if(key==='jen'){document.getElementById('jen-jenis').value=r.jenis;document.getElementById('jen-kom').value=r.kom;document.getElementById('jen-belum').value=r.bel;document.getElementById('jen-total').value=r.jumlah}else if(key==='kec'){document.getElementById('kec-nama').value=r.kecamatan;document.getElementById('kec-kom-25').value=r.kom25;document.getElementById('kec-kom-26').value=r.kom26;document.getElementById('kec-kom-27').value=r.kom27;document.getElementById('kec-kom-28').value=r.kom28;document.getElementById('kec-kom-29').value=r.kom29;document.getElementById('kec-bel-25').value=r.bel25;document.getElementById('kec-bel-26').value=r.bel26;document.getElementById('kec-bel-27').value=r.bel27;document.getElementById('kec-bel-28').value=r.bel28;document.getElementById('kec-bel-29').value=r.bel29}else{document.getElementById('pem-uraian').value=r.uraian;document.getElementById('pem-2025').value=r.y2025;document.getElementById('pem-2026').value=r.y2026;document.getElementById('pem-2027').value=r.y2027;document.getElementById('pem-2028').value=r.y2028;document.getElementById('pem-2029').value=r.y2029}document.getElementById(panelId).style.display='block';});
document.addEventListener('click',function(e){var del=e.target.closest('[data-pw-del]');if(!del)return;var p=del.getAttribute('data-pw-del').split(':');var key=p[0],i=parseInt(p[1],10);var set=key==='ako'?akoRows:key==='wis'?wisRows:key==='jen'?jenRows:key==='kec'?kecRows:pemRows;Utils.confirm('Hapus baris ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(async function(yes){if(!yes)return;var isUser=(window.USER_ROLE==='user');if(isUser){Utils.showToast('Hanya admin yang bisa menghapus','error');return;}var id=set[i]?.id;var endpoint=key==='ako'?'/pariwisata/ako':key==='wis'?'/pariwisata/wis':key==='jen'?'/pariwisata/jen':key==='kec'?'/pariwisata/kec':'/pariwisata/pem';try{var res=await fetch(endpoint+'/'+id,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrfToken,'Accept':'application/json'}});if(res.ok){if(key==='ako')await fetchAko();else if(key==='wis')await fetchWis();else if(key==='jen')await fetchJen();else if(key==='kec')await fetchKec();else await fetchPem();Utils.showToast('Baris dihapus','success');}else{Utils.showToast('Gagal menghapus','error');}}catch(e){Utils.showToast('Gagal menghapus','error');}});});
function exportCsv(filename, headers, rows){var csv=[headers].concat(rows).map(function(row){return row.map(function(v){var s=(''+(v==null?'':v)).replace(/"/g,'""');return '"'+s+'"';}).join(',');}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download=filename;document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url);} 
function pwExport(){
  if(activeTab==='pw-ako'){
    var h=['No','Kecamatan','2025 Hotel','2025 Kamar','2025 Tidur','2026 Hotel','2026 Kamar','2026 Tidur','2027 Hotel','2027 Kamar','2027 Tidur','2028 Hotel','2028 Kamar','2028 Tidur','2029 Hotel','2029 Kamar','2029 Tidur'];
    var rows=akoRows.map(function(r){return [r.no,r.kecamatan,r.h25,r.k25,r.t25,r.h26,r.k26,r.t26,r.h27,r.k27,r.t27,r.h28,r.k28,r.t28,r.h29,r.k29,r.t29]});
    exportCsv('pariwisata-akomodasi.csv',h,rows);
  }else if(activeTab==='pw-wis'){
    var h2=['Tahun','Mancanegara','Domestic','Jumlah'];
    var rows2=wisRows.map(function(r){return [r.tahun,r.manca,r.dom,r.jumlah]});
    exportCsv('pariwisata-wisatawan.csv',h2,rows2);
  }else if(activeTab==='pw-jen'){
    var h3=['Jenis','Dikomersilkan','Belum Dikomersilkan','Jumlah'];
    var rows3=jenRows.map(function(r){return [r.jenis,r.kom,r.bel,r.jumlah]});
    exportCsv('pariwisata-jenis.csv',h3,rows3);
  }else if(activeTab==='pw-kec'){
    var h4=['No','Kecamatan','Komersil 2025','Komersil 2026','Komersil 2027','Komersil 2028','Komersil 2029','Belum 2025','Belum 2026','Belum 2027','Belum 2028','Belum 2029','Jumlah'];
    var rows4=kecRows.map(function(r){return [r.no,r.kecamatan,r.kom25,r.kom26,r.kom27,r.kom28,r.kom29,r.bel25,r.bel26,r.bel27,r.bel28,r.bel29,r.jumlah]});
    exportCsv('pariwisata-kecamatan.csv',h4,rows4);
  }else{
    var h5=['No','Uraian','2025','2026','2027','2028','2029'];
    var rows5=pemRows.map(function(r){return [r.no,r.uraian,r.y2025,r.y2026,r.y2027,r.y2028,r.y2029]});
    exportCsv('pariwisata-pemandu.csv',h5,rows5);
  }
}

document.getElementById('ako-save')?.addEventListener('click',function(){document.getElementById('pw-ako-panel').style.display='none';});
document.getElementById('wis-save')?.addEventListener('click',function(){document.getElementById('pw-wis-panel').style.display='none';});
document.getElementById('jen-save')?.addEventListener('click',function(){document.getElementById('pw-jen-panel').style.display='none';});
document.getElementById('kec-save')?.addEventListener('click',function(){document.getElementById('pw-kec-panel').style.display='none';});
document.getElementById('pem-save')?.addEventListener('click',function(){document.getElementById('pw-pem-panel').style.display='none';});
</script>
@endpush

