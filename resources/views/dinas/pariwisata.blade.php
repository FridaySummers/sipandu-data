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
          <div class="card-actions"><button type="button" class="btn btn-outline btn-sm" id="pw-export" onclick="pwExport()"><i class="fas fa-download"></i> Export Data</button> <button type="button" class="btn btn-outline btn-sm" id="pw-add" onclick="pwToggleAdd()"><i class="fas fa-plus"></i> Ajukan Data</button></div>
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
              <div class="form-group"><label>Nama Data</label><input class="form-control" id="pem-uraian" placeholder="Contoh: Nama Data"></div>
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
var csrfToken=document.querySelector('meta[name=\"csrf-token\"]')?.content||'';window.USER_ROLE=document.body.dataset.userRole||'';var opdName='Dinas Pariwisata';
var dinasId=(document.body.dataset.dinasId||'')||null;
var akoRows=[],wisRows=[],jenRows=[],kecRows=[],pemRows=[];
function mapAko(data){return (Array.isArray(data)?data:[]).map(function(r,i){var v=r.values||{};return {id:r.id,no:i+1,kecamatan:r.uraian,h22:v.h22||'',k22:v.k22||'',t22:v.t22||'',h23:v.h23||'',k23:v.k23||'',t23:v.t23||''};});}
function mapWis(data){return (Array.isArray(data)?data:[]).map(function(r){var v=r.values||{};return {id:r.id,tahun:v.tahun||'',manca:v.manca||'',dom:v.dom||'',jumlah:v.jumlah||''};});}
function mapJen(data){return (Array.isArray(data)?data:[]).map(function(r){var v=r.values||{};return {id:r.id,jenis:r.uraian,kom:v.kom||'',bel:v.bel||'',jumlah:v.jumlah||''};});}
function mapKec(data){return (Array.isArray(data)?data:[]).map(function(r,i){var v=r.values||{};return {id:r.id,no:i+1,kecamatan:r.uraian,kom22:v.kom22||'',kom23:v.kom23||'',bel22:v.bel22||'',bel23:v.bel23||'',jumlah:v.jumlah||''};});}
function mapPem(data){return (Array.isArray(data)?data:[]).map(function(r,i){var v=r.values||{};return {id:r.id,no:i+1,uraian:r.uraian,y2019:v.y2019||'',y2020:v.y2020||'',y2021:v.y2021||'',y2022:v.y2022||'',y2023:v.y2023||''};});}

function renderAko(){var tb=document.getElementById('pw-ako-tbody');if(!tb)return;tb.innerHTML=akoRows.map(function(r,i){return '<tr><td>'+r.no+'</td><td>'+r.kecamatan+'</td><td>'+r.h22+'</td><td>'+r.k22+'</td><td>'+r.t22+'</td><td>'+r.h23+'</td><td>'+r.k23+'</td><td>'+r.t23+'</td><td><button class="btn btn-outline btn-sm action-btn" data-pw-ed="ako:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-pw-del="ako:'+i+'"><i class="fas fa-trash"></i></button></td></tr>';}).join('');}
function renderWis(){var tb=document.getElementById('pw-wis-tbody');if(!tb)return;tb.innerHTML=wisRows.map(function(r,i){return '<tr><td>'+r.tahun+'</td><td>'+r.manca+'</td><td>'+r.dom+'</td><td>'+r.jumlah+'</td><td><button class="btn btn-outline btn-sm action-btn" data-pw-ed="wis:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-pw-del="wis:'+i+'"><i class="fas fa-trash"></i></button></td></tr>';}).join('');}
function renderJen(){var tb=document.getElementById('pw-jen-tbody');if(!tb)return;tb.innerHTML=jenRows.map(function(r,i){return '<tr><td>'+r.jenis+'</td><td>'+r.kom+'</td><td>'+r.bel+'</td><td>'+r.jumlah+'</td><td><button class="btn btn-outline btn-sm action-btn" data-pw-ed="jen:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-pw-del="jen:'+i+'"><i class="fas fa-trash"></i></button></td></tr>';}).join('');}
function renderKec(){var tb=document.getElementById('pw-kec-tbody');if(!tb)return;tb.innerHTML=kecRows.map(function(r,i){return '<tr><td>'+r.no+'</td><td>'+r.kecamatan+'</td><td>'+r.kom22+'</td><td>'+r.kom23+'</td><td>'+r.bel22+'</td><td>'+r.bel23+'</td><td>'+r.jumlah+'</td><td><button class="btn btn-outline btn-sm action-btn" data-pw-ed="kec:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-pw-del="kec:'+i+'"><i class="fas fa-trash"></i></button></td></tr>';}).join('');}
function renderPem(){var tb=document.getElementById('pw-pem-tbody');if(!tb)return;tb.innerHTML=pemRows.map(function(r,i){return '<tr><td>'+r.no+'</td><td>'+r.uraian+'</td><td>'+r.y2019+'</td><td>'+r.y2020+'</td><td>'+r.y2021+'</td><td>'+r.y2022+'</td><td>'+r.y2023+'</td><td><button class="btn btn-outline btn-sm action-btn" data-pw-ed="pem:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-pw-del="pem:'+i+'"><i class="fas fa-trash"></i></button></td></tr>';}).join('');}
async function fetchAko(){try{var res=await fetch('/pariwisata/ako',{headers:{'Accept':'application/json'}});akoRows=mapAko(await res.json());renderAko();}catch(_){akoRows=[];renderAko();}}
async function fetchWis(){try{var res=await fetch('/pariwisata/wis',{headers:{'Accept':'application/json'}});wisRows=mapWis(await res.json());renderWis();}catch(_){wisRows=[];renderWis();}}
async function fetchJen(){try{var res=await fetch('/pariwisata/jen',{headers:{'Accept':'application/json'}});jenRows=mapJen(await res.json());renderJen();}catch(_){jenRows=[];renderJen();}}
async function fetchKec(){try{var res=await fetch('/pariwisata/kec',{headers:{'Accept':'application/json'}});kecRows=mapKec(await res.json());renderKec();}catch(_){kecRows=[];renderKec();}}
async function fetchPem(){try{var res=await fetch('/pariwisata/pem',{headers:{'Accept':'application/json'}});pemRows=mapPem(await res.json());renderPem();}catch(_){pemRows=[];renderPem();}}
document.addEventListener('DOMContentLoaded',function(){fetchAko();fetchWis();fetchJen();fetchKec();fetchPem();});

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
document.addEventListener('click',async function(e){var b=e.target.closest('button');if(!b)return;var id=b.id;var isUser=(window.USER_ROLE==='user');try{
  if(id==='ako-save'){
    var kec=document.getElementById('ako-kec').value.trim();var vals={h22:document.getElementById('ako-22-hotel').value.trim(),k22:document.getElementById('ako-22-kamar').value.trim(),t22:document.getElementById('ako-22-tidur').value.trim(),h23:document.getElementById('ako-23-hotel').value.trim(),k23:document.getElementById('ako-23-kamar').value.trim(),t23:document.getElementById('ako-23-tidur').value.trim()};
    if(!isUser){var url='/pariwisata/ako';var res=await fetch(url,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:kec,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchAko();}
    await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({opd:opdName,dinas_id:dinasId,judul_data:'Akomodasi '+kec,file_path:'pariwisata_akomodasi',tahun_perencanaan:(new Date().getFullYear()).toString()})});
    document.getElementById('pw-ako-panel').style.display='none';Utils.showToast('Data disimpan','success');
  } else if(id==='wis-save'){
    var th=document.getElementById('wis-tahun').value.trim();var vals={tahun:th,manca:document.getElementById('wis-manca').value.trim(),dom:document.getElementById('wis-dom').value.trim(),jumlah:document.getElementById('wis-total').value.trim()};
    if(!isUser){var url='/pariwisata/wis';var res=await fetch(url,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:'Wisatawan '+th,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchWis();}
    await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({opd:opdName,dinas_id:dinasId,judul_data:'Wisatawan '+th,file_path:'pariwisata_wisatawan',tahun_perencanaan:th.slice(0,4)|| (new Date().getFullYear()).toString()})});
    document.getElementById('pw-wis-panel').style.display='none';Utils.showToast('Data disimpan','success');
  } else if(id==='jen-save'){
    var jenis=document.getElementById('jen-jenis').value.trim();var vals={kom:document.getElementById('jen-kom').value.trim(),bel:document.getElementById('jen-belum').value.trim(),jumlah:document.getElementById('jen-total').value.trim()};
    if(!isUser){var url='/pariwisata/jen';var res=await fetch(url,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:jenis,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchJen();}
    await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({opd:opdName,dinas_id:dinasId,judul_data:'Objek Wisata '+jenis,file_path:'pariwisata_jenis',tahun_perencanaan:(new Date().getFullYear()).toString()})});
    document.getElementById('pw-jen-panel').style.display='none';Utils.showToast('Data disimpan','success');
  } else if(id==='kec-save'){
    var kec=document.getElementById('kec-nama').value.trim();var vals={kom22:document.getElementById('kec-kom-22').value.trim(),kom23:document.getElementById('kec-kom-23').value.trim(),bel22:document.getElementById('kec-bel-22').value.trim(),bel23:document.getElementById('kec-bel-23').value.trim(),jumlah:''};
    if(!isUser){var url='/pariwisata/kec';var res=await fetch(url,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:kec,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchKec();}
    await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({opd:opdName,dinas_id:dinasId,judul_data:'Objek Wisata '+kec,file_path:'pariwisata_kecamatan',tahun_perencanaan:(new Date().getFullYear()).toString()})});
    document.getElementById('pw-kec-panel').style.display='none';Utils.showToast('Data disimpan','success');
  } else if(id==='pem-save'){
    var ura=document.getElementById('pem-uraian').value.trim();var vals={y2019:document.getElementById('pem-2019').value.trim(),y2020:document.getElementById('pem-2020').value.trim(),y2021:document.getElementById('pem-2021').value.trim(),y2022:document.getElementById('pem-2022').value.trim(),y2023:document.getElementById('pem-2023').value.trim()};
    if(!isUser){var url='/pariwisata/pem';var res=await fetch(url,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:ura,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchPem();}
    await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({opd:opdName,dinas_id:dinasId,judul_data:'Pemandu '+ura,file_path:'pariwisata_pemandu',tahun_perencanaan:(new Date().getFullYear()).toString()})});
    document.getElementById('pw-pem-panel').style.display='none';Utils.showToast('Data disimpan','success');
  }
}catch(err){Utils.showToast('Gagal menyimpan','error');}});
document.addEventListener('click',function(e){var ed=e.target.closest('[data-pw-ed]');if(!ed)return;var p=ed.getAttribute('data-pw-ed').split(':');var key=p[0],i=parseInt(p[1],10);var set=key==='ako'?akoRows:key==='wis'?wisRows:key==='jen'?jenRows:key==='kec'?kecRows:pemRows;var panelId={'ako':'pw-ako-panel','wis':'pw-wis-panel','jen':'pw-jen-panel','kec':'pw-kec-panel','pem':'pw-pem-panel'}[key];var r=set[i];if(key==='ako'){document.getElementById('ako-kec').value=r.kecamatan;document.getElementById('ako-22-hotel').value=r.h22;document.getElementById('ako-22-kamar').value=r.k22;document.getElementById('ako-22-tidur').value=r.t22;document.getElementById('ako-23-hotel').value=r.h23;document.getElementById('ako-23-kamar').value=r.k23;document.getElementById('ako-23-tidur').value=r.t23}else if(key==='wis'){document.getElementById('wis-tahun').value=r.tahun;document.getElementById('wis-manca').value=r.manca;document.getElementById('wis-dom').value=r.dom;document.getElementById('wis-total').value=r.jumlah}else if(key==='jen'){document.getElementById('jen-jenis').value=r.jenis;document.getElementById('jen-kom').value=r.kom;document.getElementById('jen-belum').value=r.bel;document.getElementById('jen-total').value=r.jumlah}else if(key==='kec'){document.getElementById('kec-nama').value=r.kecamatan;document.getElementById('kec-kom-22').value=r.kom22;document.getElementById('kec-kom-23').value=r.kom23;document.getElementById('kec-bel-22').value=r.bel22;document.getElementById('kec-bel-23').value=r.bel23}else{document.getElementById('pem-uraian').value=r.uraian;document.getElementById('pem-2019').value=r.y2019;document.getElementById('pem-2020').value=r.y2020;document.getElementById('pem-2021').value=r.y2021;document.getElementById('pem-2022').value=r.y2022;document.getElementById('pem-2023').value=r.y2023}document.getElementById(panelId).style.display='block';});
document.addEventListener('click',function(e){var del=e.target.closest('[data-pw-del]');if(!del)return;var p=del.getAttribute('data-pw-del').split(':');var key=p[0],i=parseInt(p[1],10);var set=key==='ako'?akoRows:key==='wis'?wisRows:key==='jen'?jenRows:key==='kec'?kecRows:pemRows;Utils.confirm('Hapus baris ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(async function(yes){if(!yes)return;var isUser=(window.USER_ROLE==='user');if(isUser){Utils.showToast('Hanya admin yang bisa menghapus','error');return;}var id=set[i]?.id;var endpoint=key==='ako'?'/pariwisata/ako':key==='wis'?'/pariwisata/wis':key==='jen'?'/pariwisata/jen':key==='kec'?'/pariwisata/kec':'/pariwisata/pem';try{var res=await fetch(endpoint+'/'+id,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrfToken,'Accept':'application/json'}});if(res.ok){if(key==='ako')await fetchAko();else if(key==='wis')await fetchWis();else if(key==='jen')await fetchJen();else if(key==='kec')await fetchKec();else await fetchPem();Utils.showToast('Baris dihapus','success');}else{Utils.showToast('Gagal menghapus','error');}}catch(e){Utils.showToast('Gagal menghapus','error');}});});
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

document.getElementById('ako-save')?.addEventListener('click',function(){document.getElementById('pw-ako-panel').style.display='none';});
document.getElementById('wis-save')?.addEventListener('click',function(){document.getElementById('pw-wis-panel').style.display='none';});
document.getElementById('jen-save')?.addEventListener('click',function(){document.getElementById('pw-jen-panel').style.display='none';});
document.getElementById('kec-save')?.addEventListener('click',function(){document.getElementById('pw-kec-panel').style.display='none';});
document.getElementById('pem-save')?.addEventListener('click',function(){document.getElementById('pw-pem-panel').style.display='none';});
</script>
@endpush
