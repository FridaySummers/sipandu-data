@extends('layouts.app')
@section('title', 'Pariwisata')
@section('body-class', 'dashboard-page force-light')

@push('styles')
<style>
.pw-card{border-radius:16px;overflow:hidden;margin-bottom:18px}
.pw-card .card-header{padding:18px 22px;display:flex;align-items:center;justify-content:space-between;background:linear-gradient(90deg,#60a5fa,#3b82f6);color:#ffffff;border-radius:16px 16px 0 0;position:relative;z-index:5;overflow:visible}
.pw-card .card-header h3{display:flex;flex-direction:column;line-height:1.25}
.pw-card .card-header h3 span{font-weight:500;font-size:13px;opacity:.9}
.pw-card .card-actions .btn{border-radius:8px;height:34px;padding:0 12px;font-weight:600;pointer-events:auto;position:relative;z-index:2}
.pw-card .card-header .btn.btn-outline{background:#ffffff;color:#2563eb;border:1px solid #93c5fd;box-shadow:0 2px 6px rgba(29,78,216,.12)}
.pw-panel{margin:16px;border-radius:16px;padding:16px;border:1px solid #cfe0ff;background:#f8fbff}
.pw-panel .form-group{display:flex;flex-direction:column;gap:8px;margin-bottom:8px}
.pw-sub{font-size:13px;color:#1e3a8a;opacity:.9;margin-top:4px}
.pw-grid{display:grid;gap:12px}
.pw-grid-2{grid-template-columns:repeat(2,1fr)}
.pw-grid-3{grid-template-columns:repeat(3,1fr)}
.pw-grid-5{grid-template-columns:repeat(5,1fr)}
.year-group{display:flex;flex-direction:column;gap:6px}
.year-label{font-size:12px;color:#0f172a;font-weight:600}
.form-control{border:1px solid #93c5fd;background:#ffffff;border-radius:12px;padding:10px 12px}
.form-control:focus{outline:none;border-color:#7dd3fc;box-shadow:0 0 0 3px rgba(125,211,252,0.35)}
.table-wrap{border-radius:12px;overflow:hidden;background:#fff;box-shadow:0 1px 6px rgba(0,0,0,0.04)}
.pw-table{width:100%;border:1px solid #60a5fa;border-radius:12px;overflow:hidden;border-collapse:separate;border-spacing:0;table-layout:fixed}
.pw-table thead th{background:#dbeafe;color:#1e3a8a;font-weight:600}
.pw-table th,.pw-table td{border-bottom:1px solid #60a5fa;border-right:1px solid #60a5fa;padding:10px 12px}
.pw-table thead tr th:first-child{border-left:1px solid #60a5fa}
.pw-table tbody tr td:first-child{border-left:1px solid #60a5fa}
.pw-table th{font-weight:600;text-align:center}
.pw-table td:nth-child(1),.pw-table td:nth-child(2),.pw-table th:nth-child(1),.pw-table th:nth-child(2){text-align:left}
.pw-table th:last-child,.pw-table td:last-child{text-align:center}
.pw-table td:last-child{display:table-cell !important;text-align:center !important;white-space:nowrap}
.pw-table thead th:last-child{width:120px !important}
.toolbar{display:flex;align-items:center;justify-content:flex-end;margin-bottom:10px}
.btn-outline{background:#fff;border:1px solid #93c5fd;color:#2563eb;border-radius:12px}
.btn-primary{background:#2563eb;border-color:#2563eb;color:#fff}
.btn-secondary{background:#f1f5f9;color:#111827;border:1px solid #e5e7eb}
</style>
@endpush

@section('content')
    <div class="page active" id="pariwisata-page">
      <div class="page-header"><h1>Data Pariwisata</h1><p>Akomodasi, wisatawan, objek, kecamatan, dan pemandu</p></div>
      <div class="card pw-card">
        <div class="card-header" style="position:relative;z-index:1">
          <h3>Data Pariwisata Lengkap<span>Kabupaten Kolaka Utara</span></h3>
          <div class="card-actions"><button type="button" class="btn btn-outline btn-sm" id="pw-export" onclick="pwExport()"><i class="fas fa-download"></i> Export Data</button> <button type="button" class="btn btn-outline btn-sm" id="pw-add" onclick="pwToggleAdd()"><i class="fas fa-plus"></i> Tambah Data</button></div>
        </div>
        <div class="card-body">
          <div class="segmented"><button type="button" class="btn btn-primary btn-sm" id="pw-tab-ako" onclick="toggleTab('pw-ako')">Akomodasi</button><button type="button" class="btn btn-outline btn-sm" id="pw-tab-wis" onclick="toggleTab('pw-wis')">Wisatawan</button><button type="button" class="btn btn-outline btn-sm" id="pw-tab-jen" onclick="toggleTab('pw-jen')">Objek (Jenis)</button><button type="button" class="btn btn-outline btn-sm" id="pw-tab-kec" onclick="toggleTab('pw-kec')">Objek (Kecamatan)</button><button type="button" class="btn btn-outline btn-sm" id="pw-tab-pem" onclick="toggleTab('pw-pem')">Pemandu</button></div>

          <div id="pw-ako">
            <h4>Data Akomodasi Hotel Menurut Kecamatan 2022-2023</h4>
            <div class="pw-sub">Jumlah hotel, kamar, dan tempat tidur per kecamatan 2022–2023</div>
            <div class="pw-panel" id="pw-ako-panel" style="display:none;">
              <div class="form-group"><label>Kecamatan</label><input class="form-control" id="ako-kec" placeholder="Nama Kecamatan"></div>
              <div class="pw-grid pw-grid-2">
                <div>
                  <div class="year-label">Tahun 2022</div>
                  <div class="pw-grid pw-grid-3" style="margin-top:6px">
                    <input class="form-control" id="ako-22-hotel" placeholder="Jumlah Hotel">
                    <input class="form-control" id="ako-22-kamar" placeholder="Jumlah Kamar">
                    <input class="form-control" id="ako-22-tidur" placeholder="Tempat Tidur">
                  </div>
                </div>
                <div>
                  <div class="year-label">Tahun 2023</div>
                  <div class="pw-grid pw-grid-3" style="margin-top:6px">
                    <input class="form-control" id="ako-23-hotel" placeholder="Jumlah Hotel">
                    <input class="form-control" id="ako-23-kamar" placeholder="Jumlah Kamar">
                    <input class="form-control" id="ako-23-tidur" placeholder="Tempat Tidur">
                  </div>
                </div>
              </div>
              <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px"><button class="btn btn-secondary" id="ako-cancel">Batal</button><button class="btn btn-primary" id="ako-save">Simpan</button></div>
            </div>
            
            <div class="table-wrap"><table class="table table-compact pw-table"><thead><tr><th rowspan="2">No</th><th rowspan="2">Kecamatan</th><th colspan="3">Tahun 2022</th><th colspan="3">Tahun 2023</th><th rowspan="2">Aksi</th></tr><tr><th>Hotel</th><th>Kamar</th><th>Tempat Tidur</th><th>Hotel</th><th>Kamar</th><th>Tempat Tidur</th></tr></thead><tbody id="pw-ako-tbody"></tbody></table></div>
          </div>

          <div id="pw-wis" style="display:none;">
            <h4>Jumlah Wisatawan Mancanegara dan Domestik (2018-2023)</h4>
            <div class="pw-sub">Jumlah wisatawan mancanegara dan domestik per tahun</div>
            <div class="pw-panel" id="pw-wis-panel" style="display:none;">
              <div class="pw-grid pw-grid-5"><div class="form-group"><label>Tahun</label><input class="form-control" id="wis-tahun" placeholder="2024"></div><div class="form-group"><label>Mancanegara</label><input class="form-control" id="wis-manca" placeholder="0"></div><div class="form-group"><label>Domestic</label><input class="form-control" id="wis-dom" placeholder="0"></div><div class="form-group"><label>Jumlah</label><input class="form-control" id="wis-total" placeholder="0"></div></div>
              <div style="display:flex;gap:8px;justify-content:flex-end"><button class="btn btn-secondary" id="wis-cancel">Batal</button><button class="btn btn-primary" id="wis-save">Simpan</button></div>
            </div>
            
            <div class="table-wrap"><table class="table table-compact pw-table"><thead><tr><th rowspan="2">Tahun</th><th colspan="2">Wisatawan</th><th rowspan="2">Jumlah</th><th rowspan="2">Aksi</th></tr><tr><th>Mancanegara</th><th>Domestic</th></tr></thead><tbody id="pw-wis-tbody"></tbody></table></div>
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
            
            <div class="table-wrap"><table class="table table-compact pw-table"><thead><tr><th rowspan="2">Jenis Objek Wisata</th><th colspan="2">Status Objek Wisata</th><th rowspan="2">Jumlah</th><th rowspan="2">Aksi</th></tr><tr><th>Dikomersilkan</th><th>Belum Dikomersilkan</th></tr></thead><tbody id="pw-jen-tbody"></tbody></table></div>
          </div>

          <div id="pw-kec" style="display:none;">
            <h4>Jumlah Objek Wisata Menurut Kecamatan dan Statusnya (2022-2023)</h4>
            <div class="pw-sub">Jumlah objek wisata per kecamatan dan status 2022–2023</div>
            <div class="pw-panel" id="pw-kec-panel" style="display:none;">
              <div class="form-group"><label>Kecamatan</label><input class="form-control" id="kec-nama" placeholder="Nama Kecamatan"></div>
              <div class="pw-grid pw-grid-2">
                <div>
                  <div class="year-label">Dikomersilkan</div>
                  <div class="pw-grid pw-grid-2" style="margin-top:6px"><input class="form-control" id="kec-kom-22" placeholder="2022"><input class="form-control" id="kec-kom-23" placeholder="2023"></div>
                </div>
                <div>
                  <div class="year-label">Belum Dikomersilkan</div>
                  <div class="pw-grid pw-grid-2" style="margin-top:6px"><input class="form-control" id="kec-bel-22" placeholder="2022"><input class="form-control" id="kec-bel-23" placeholder="2023"></div>
                </div>
              </div>
              <div style="display:flex;gap:8px;justify-content:flex-end"><button class="btn btn-secondary" id="kec-cancel">Batal</button><button class="btn btn-primary" id="kec-save">Simpan</button></div>
            </div>
            
            <div class="table-wrap"><table class="table table-compact pw-table"><thead><tr><th rowspan="2">No</th><th rowspan="2">Kecamatan</th><th colspan="2">Dikomersilkan</th><th colspan="2">Belum Dikomersilkan</th><th rowspan="2">Jumlah</th><th rowspan="2">Aksi</th></tr><tr><th>2022</th><th>2023</th><th>2022</th><th>2023</th></tr></thead><tbody id="pw-kec-tbody"></tbody></table></div>
          </div>

          <div id="pw-pem" style="display:none;">
            <h4>Data Pemandu Wisata (2019-2023)</h4>
            <div class="pw-sub">Jumlah pemandu wisata dan tenaga terlatih 2019–2023</div>
            <div class="pw-panel" id="pw-pem-panel" style="display:none;">
              <div class="form-group"><label>Uraian</label><input class="form-control" id="pem-uraian" placeholder="Contoh: Jumlah Pemandu Wisata"></div>
              <div class="pw-grid pw-grid-5"><div class="year-group"><div class="year-label">Tahun 2019</div><input class="form-control" id="pem-2019" placeholder="0"></div><div class="year-group"><div class="year-label">Tahun 2020</div><input class="form-control" id="pem-2020" placeholder="0"></div><div class="year-group"><div class="year-label">Tahun 2021</div><input class="form-control" id="pem-2021" placeholder="0"></div><div class="year-group"><div class="year-label">Tahun 2022</div><input class="form-control" id="pem-2022" placeholder="0"></div><div class="year-group"><div class="year-label">Tahun 2023</div><input class="form-control" id="pem-2023" placeholder="0"></div></div>
              <div style="display:flex;gap:8px;justify-content:flex-end"><button class="btn btn-secondary" id="pem-cancel">Batal</button><button class="btn btn-primary" id="pem-save">Simpan</button></div>
            </div>
            
            <div class="table-wrap"><table class="table table-compact pw-table"><thead><tr><th>No</th><th>Uraian</th><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th><th>Aksi</th></tr></thead><tbody id="pw-pem-tbody"></tbody></table></div>
          </div>

        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
var akoRows=[{no:1,kecamatan:'Ranteangin',h22:'-',k22:'-',t22:'-',h23:'-',k23:'-',t23:'-'},{no:2,kecamatan:'Lambai',h22:'-',k22:'-',t22:'-',h23:'-',k23:'-',t23:'-'},{no:3,kecamatan:'Wawo',h22:'-',k22:'-',t22:'-',h23:'-',k23:'-',t23:'-'},{no:4,kecamatan:'Lasusua',h22:'16',k22:'231',t22:'342',h23:'17',k23:'243',t23:'356'}];
var wisRows=[{tahun:2018,manca:'60',dom:'16,037',jumlah:'16,097'},{tahun:2019,manca:'12',dom:'22,266',jumlah:'22,278'},{tahun:2020,manca:'0',dom:'20,364',jumlah:'20,364'},{tahun:2021,manca:'0',dom:'100,163',jumlah:'100,163'},{tahun:2022,manca:'1',dom:'100,163',jumlah:'100,163'},{tahun:2023,manca:'0',dom:'342,719',jumlah:'342,719'}];
var jenRows=[{jenis:'Wisata Bahari/Pantai',kom:3,bel:3,jumlah:6},{jenis:'Wisata Goa',kom:3,bel:14,jumlah:14},{jenis:'Wisata Danau/Air',kom:'-',bel:13,jumlah:13},{jenis:'Wisata Panorama',kom:'-',bel:5,jumlah:5},{jenis:'Wisata Sejarah',kom:'-',bel:8,jumlah:8},{jenis:'Wisata Edukasi',kom:'-',bel:2,jumlah:2},{jenis:'Wisata Minat Kolaka Utara',kom:'-',bel:1,jumlah:1}];
var kecRows=[{no:1,kecamatan:'Ranteangin',kom22:'-',kom23:'-',bel22:'2',bel23:'3',jumlah:''},{no:2,kecamatan:'Lambai',kom22:'-',kom23:'-',bel22:'3',bel23:'3',jumlah:''},{no:3,kecamatan:'Wawo',kom22:'1',kom23:'2',bel22:'6',bel23:'5',jumlah:''},{no:4,kecamatan:'Lasusua',kom22:'1',kom23:'1',bel22:'9',bel23:'8',jumlah:''},{no:5,kecamatan:'Katoi',kom22:'-',kom23:'-',bel22:'4',bel23:'4',jumlah:''}];
var pemRows=[{no:1,uraian:'Jumlah Pemandu Wisata',y2019:'0',y2020:'0',y2021:'18',y2022:'15',y2023:'2'},{no:2,uraian:'Jumlah tenaga pemandu wisata terlatih berlisensi',y2019:'0',y2020:'0',y2021:'0',y2022:'0',y2023:'2'}];

function renderAko(){var tb=document.getElementById('pw-ako-tbody');if(!tb)return;tb.innerHTML=akoRows.map(function(r,i){return '<tr><td>'+r.no+'</td><td>'+r.kecamatan+'</td><td>'+r.h22+'</td><td>'+r.k22+'</td><td>'+r.t22+'</td><td>'+r.h23+'</td><td>'+r.k23+'</td><td>'+r.t23+'</td><td><button class="btn btn-outline btn-sm action-btn" data-pw-ed="ako:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-pw-del="ako:'+i+'"><i class="fas fa-trash"></i></button></td></tr>';}).join('');}
function renderWis(){var tb=document.getElementById('pw-wis-tbody');if(!tb)return;tb.innerHTML=wisRows.map(function(r,i){return '<tr><td>'+r.tahun+'</td><td>'+r.manca+'</td><td>'+r.dom+'</td><td>'+r.jumlah+'</td><td><button class="btn btn-outline btn-sm action-btn" data-pw-ed="wis:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-pw-del="wis:'+i+'"><i class="fas fa-trash"></i></button></td></tr>';}).join('');}
function renderJen(){var tb=document.getElementById('pw-jen-tbody');if(!tb)return;tb.innerHTML=jenRows.map(function(r,i){return '<tr><td>'+r.jenis+'</td><td>'+r.kom+'</td><td>'+r.bel+'</td><td>'+r.jumlah+'</td><td><button class="btn btn-outline btn-sm action-btn" data-pw-ed="jen:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-pw-del="jen:'+i+'"><i class="fas fa-trash"></i></button></td></tr>';}).join('');}
function renderKec(){var tb=document.getElementById('pw-kec-tbody');if(!tb)return;tb.innerHTML=kecRows.map(function(r,i){return '<tr><td>'+r.no+'</td><td>'+r.kecamatan+'</td><td>'+r.kom22+'</td><td>'+r.kom23+'</td><td>'+r.bel22+'</td><td>'+r.bel23+'</td><td>'+r.jumlah+'</td><td><button class="btn btn-outline btn-sm action-btn" data-pw-ed="kec:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-pw-del="kec:'+i+'"><i class="fas fa-trash"></i></button></td></tr>';}).join('');}
function renderPem(){var tb=document.getElementById('pw-pem-tbody');if(!tb)return;tb.innerHTML=pemRows.map(function(r,i){return '<tr><td>'+r.no+'</td><td>'+r.uraian+'</td><td>'+r.y2019+'</td><td>'+r.y2020+'</td><td>'+r.y2021+'</td><td>'+r.y2022+'</td><td>'+r.y2023+'</td><td><button class="btn btn-outline btn-sm action-btn" data-pw-ed="pem:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-pw-del="pem:'+i+'"><i class="fas fa-trash"></i></button></td></tr>';}).join('');}
function init(){renderAko();renderWis();renderJen();renderKec();renderPem();}
init();

var activeTab='pw-ako';
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
document.addEventListener('click',function(e){var ed=e.target.closest('[data-pw-ed]');if(!ed)return;var p=ed.getAttribute('data-pw-ed').split(':');var key=p[0],i=parseInt(p[1],10);var set=key==='ako'?akoRows:key==='wis'?wisRows:key==='jen'?jenRows:key==='kec'?kecRows:pemRows;var panelId={'ako':'pw-ako-panel','wis':'pw-wis-panel','jen':'pw-jen-panel','kec':'pw-kec-panel','pem':'pw-pem-panel'}[key];var r=set[i];if(key==='ako'){document.getElementById('ako-kec').value=r.kecamatan;document.getElementById('ako-22-hotel').value=r.h22;document.getElementById('ako-22-kamar').value=r.k22;document.getElementById('ako-22-tidur').value=r.t22;document.getElementById('ako-23-hotel').value=r.h23;document.getElementById('ako-23-kamar').value=r.k23;document.getElementById('ako-23-tidur').value=r.t23}else if(key==='wis'){document.getElementById('wis-tahun').value=r.tahun;document.getElementById('wis-manca').value=r.manca;document.getElementById('wis-dom').value=r.dom;document.getElementById('wis-total').value=r.jumlah}else if(key==='jen'){document.getElementById('jen-jenis').value=r.jenis;document.getElementById('jen-kom').value=r.kom;document.getElementById('jen-belum').value=r.bel;document.getElementById('jen-total').value=r.jumlah}else if(key==='kec'){document.getElementById('kec-nama').value=r.kecamatan;document.getElementById('kec-kom-22').value=r.kom22;document.getElementById('kec-kom-23').value=r.kom23;document.getElementById('kec-bel-22').value=r.bel22;document.getElementById('kec-bel-23').value=r.bel23}else{document.getElementById('pem-uraian').value=r.uraian;document.getElementById('pem-2019').value=r.y2019;document.getElementById('pem-2020').value=r.y2020;document.getElementById('pem-2021').value=r.y2021;document.getElementById('pem-2022').value=r.y2022;document.getElementById('pem-2023').value=r.y2023}document.getElementById(panelId).style.display='block';});
document.addEventListener('click',function(e){var del=e.target.closest('[data-pw-del]');if(!del)return;var p=del.getAttribute('data-pw-del').split(':');var key=p[0],i=parseInt(p[1],10);var set=key==='ako'?akoRows:key==='wis'?wisRows:key==='jen'?jenRows:key==='kec'?kecRows:pemRows;Utils.confirm('Hapus baris ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(function(yes){if(!yes)return;set.splice(i,1);set.forEach(function(r,idx){if(r.no!=null)r.no=idx+1});var renderMap={ako:renderAko,wis:renderWis,jen:renderJen,kec:renderKec,pem:renderPem};renderMap[key]();Utils.showToast('Baris dihapus','success');});});
function exportCsv(filename, headers, rows){var csv=[headers].concat(rows).map(function(row){return row.map(function(v){var s=(''+(v==null?'':v)).replace(/"/g,'""');return '"'+s+'"';}).join(',');}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download=filename;document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url);} 
function pwExport(){
  if(activeTab==='pw-ako'){
    var h=['No','Kecamatan','2022 Hotel','2022 Kamar','2022 Tidur','2023 Hotel','2023 Kamar','2023 Tidur'];
    var rows=akoRows.map(function(r){return [r.no,r.kecamatan,r.h22,r.k22,r.t22,r.h23,r.k23,r.t23]});
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
    var h4=['No','Kecamatan','Komersil 2022','Komersil 2023','Belum 2022','Belum 2023','Jumlah'];
    var rows4=kecRows.map(function(r){return [r.no,r.kecamatan,r.kom22,r.kom23,r.bel22,r.bel23,r.jumlah]});
    exportCsv('pariwisata-kecamatan.csv',h4,rows4);
  }else{
    var h5=['No','Uraian','2019','2020','2021','2022','2023'];
    var rows5=pemRows.map(function(r){return [r.no,r.uraian,r.y2019,r.y2020,r.y2021,r.y2022,r.y2023]});
    exportCsv('pariwisata-pemandu.csv',h5,rows5);
  }
}

document.getElementById('ako-save')?.addEventListener('click',function(){akoRows.push({no:akoRows.length+1,kecamatan:document.getElementById('ako-kec').value,h22:document.getElementById('ako-22-hotel').value,k22:document.getElementById('ako-22-kamar').value,t22:document.getElementById('ako-22-tidur').value,h23:document.getElementById('ako-23-hotel').value,k23:document.getElementById('ako-23-kamar').value,t23:document.getElementById('ako-23-tidur').value});renderAko();document.getElementById('pw-ako-panel').style.display='none';});
document.getElementById('wis-save')?.addEventListener('click',function(){wisRows.push({tahun:document.getElementById('wis-tahun').value,manca:document.getElementById('wis-manca').value,dom:document.getElementById('wis-dom').value,jumlah:document.getElementById('wis-total').value});renderWis();document.getElementById('pw-wis-panel').style.display='none';});
document.getElementById('jen-save')?.addEventListener('click',function(){jenRows.push({jenis:document.getElementById('jen-jenis').value,kom:document.getElementById('jen-kom').value,bel:document.getElementById('jen-belum').value,jumlah:document.getElementById('jen-total').value});renderJen();document.getElementById('pw-jen-panel').style.display='none';});
document.getElementById('kec-save')?.addEventListener('click',function(){kecRows.push({no:kecRows.length+1,kecamatan:document.getElementById('kec-nama').value,kom22:document.getElementById('kec-kom-22').value,kom23:document.getElementById('kec-kom-23').value,bel22:document.getElementById('kec-bel-22').value,bel23:document.getElementById('kec-bel-23').value,jumlah:''});renderKec();document.getElementById('pw-kec-panel').style.display='none';});
document.getElementById('pem-save')?.addEventListener('click',function(){pemRows.push({no:pemRows.length+1,uraian:document.getElementById('pem-uraian').value,y2019:document.getElementById('pem-2019').value,y2020:document.getElementById('pem-2020').value,y2021:document.getElementById('pem-2021').value,y2022:document.getElementById('pem-2022').value,y2023:document.getElementById('pem-2023').value});renderPem();document.getElementById('pw-pem-panel').style.display='none';});
</script>
@endpush
