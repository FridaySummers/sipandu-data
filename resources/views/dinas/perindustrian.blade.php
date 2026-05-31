@extends('layouts.app')
@section('title', 'Perindustrian')
@section('body-class', 'dashboard-page force-light')
@push('styles')
<style>
.industry-card{border-radius:20px;overflow:hidden;margin-bottom:24px;box-shadow:0 4px 24px rgba(0,0,0,0.06);border:1px solid #e2e8f0;background:#fff}
.industry-card .card-header{padding:20px 24px;display:flex;align-items:center;justify-content:space-between;color:#fff}
.industry-card .card-header h3{font-size:1.1rem;font-weight:700;margin:0}
.industry-card .card-header .sub{font-size:13px;opacity:.85;margin-top:4px}
.industry-card .card-actions .btn{background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.4);color:#fff;border-radius:10px;height:36px;padding:0 14px;display:flex;align-items:center;gap:6px;font-weight:600;font-size:13px;transition:all .2s;backdrop-filter:blur(4px)}
.industry-card .card-actions .btn:hover{background:rgba(255,255,255,0.25)}
.industry-card .card-actions .btn:last-child{background:#fff;border:none}
.industry-card .card-actions .btn:last-child:hover{box-shadow:0 4px 12px rgba(255,255,255,0.3);transform:translateY(-1px)}
.theme-purple .card-actions .btn:last-child{color:#6d28d9}
.theme-pink .card-actions .btn:last-child{color:#be185d}
.theme-orange .card-actions .btn:last-child{color:#c2410c}
.panel{margin:20px;border-radius:14px;padding:20px;border:1px solid #e2e8f0;background:#f8fafc}
.panel .form-title{font-weight:700;margin-bottom:12px;font-size:1rem;color:#0f172a}
.panel .form-group{display:flex;flex-direction:column;gap:6px;margin-bottom:12px}
.panel .form-group label{font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.04em}
.panel .form-control{border:1.5px solid #e2e8f0;background:#fff;border-radius:10px;padding:10px 14px;font-size:14px;transition:all .2s;width:100%;box-sizing:border-box}
.panel .form-control::placeholder{color:#9ca3af}
.panel .form-control:focus{border-color:#3b82f6;box-shadow:0 0 0 4px rgba(59,130,246,0.12);outline:none}
.panel .row-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:12px}
.year-group{display:flex;flex-direction:column;gap:6px}
.year-label{font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.04em}
.theme-purple .year-label{color:#6d28d9}
.theme-pink .year-label{color:#be185d}
.theme-orange .year-label{color:#c2410c}
.btn-purple{background:linear-gradient(135deg,#8b5cf6,#6d28d9);color:#fff;border:none;border-radius:10px;font-weight:600}
.btn-pink{background:linear-gradient(135deg,#ec4899,#be185d);color:#fff;border:none;border-radius:10px;font-weight:600}
.btn-orange{background:linear-gradient(135deg,#f97316,#ea580c);color:#fff;border:none;border-radius:10px;font-weight:600}
.table-wrap{overflow-x:auto;margin:0 20px 20px;border-radius:14px;border:1px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,0.04)}
.table-industry{width:100%;border-collapse:separate;border-spacing:0;background:#fff}
.table-industry thead th{font-weight:700;font-size:13px;padding:12px 14px}
.table-industry th,.table-industry td{border:1px solid #cbd5e1;border-right:1px solid #f1f5f9;padding:11px 14px;word-break:break-word;white-space:normal;vertical-align:middle}
.table-industry tbody tr{transition:background .15s ease}
.table-industry tbody tr:hover{background:#f8fafc}
.table-industry th:last-child,.table-industry td:last-child{text-align:center;border-right:none}
.table-industry td:last-child{display:table-cell !important;white-space:nowrap}
.table-industry th:nth-child(1),.table-industry th:nth-child(2),.table-industry td:nth-child(1),.table-industry td:nth-child(2){text-align:left}
.table-industry th:not(:nth-child(1)):not(:nth-child(2)){text-align:center}
.status-badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600}
.status-approved{background:#dcfce7;color:#166534}
.table-industry thead th:last-child{width:100px !important}
@media (max-width: 640px){.table-industry thead th:last-child{width:auto !important}.table-industry td:last-child{white-space:normal !important}}
.status-rejected{background:#fee2e2;color:#991b1b}
.status-menunggu{background:#fef3c7;color:#92400e}
.theme-purple .card-header{background:linear-gradient(135deg,#8b5cf6,#6d28d9)}
.theme-purple .panel{background:#faf5ff;border-color:#e9d5ff}
.theme-purple .table-industry thead th{background:linear-gradient(135deg,#faf5ff,#f3e8ff);color:#4c1d95;border-bottom:2px solid #e9d5ff}
.theme-pink .card-header{background:linear-gradient(135deg,#ec4899,#be185d)}
.theme-pink .panel{background:#fdf2f8;border-color:#fbcfe8}
.theme-pink .table-industry thead th{background:linear-gradient(135deg,#fdf2f8,#fce7f3);color:#831843;border-bottom:2px solid #fbcfe8}
.theme-orange .card-header{background:linear-gradient(135deg,#f97316,#c2410c)}
.theme-orange .panel{background:#fff7ed;border-color:#fed7aa}
.theme-orange .table-industry thead th{background:linear-gradient(135deg,#fff7ed,#ffedd5);color:#7c2d12;border-bottom:2px solid #fed7aa}
.row-actions{display:flex;gap:6px;justify-content:center}
.action-btn{border-radius:8px;width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#f8fafc;border:1px solid #e2e8f0;transition:all .2s;color:#374151}
.action-btn:hover{background:#eff6ff;border-color:#93c5fd;color:#2563eb}
.btn-secondary{background:#f1f5f9;color:#374151;border:1px solid #e2e8f0;border-radius:10px;font-weight:600;transition:all .2s}
.btn-secondary:hover{background:#e2e8f0}
</style>
@endpush

@section('content')
    <div class="page active" id="perindustrian-page">
      <div class="page-header"><h1>Data Perindustrian</h1><p>Kontribusi Sektor Industri dan Pertumbuhan Industri di Kabupaten Kolaka Utara</p></div>
      <div class="card industry-card theme-purple">
        <div class="card-header"><div><h3>Kontribusi Sektor Industri terhadap PDRB (HB) tahun 2025 s.d 2029</h3><div class="sub">Data kontribusi industri terhadap PDRB harga berlaku</div></div><div class="card-actions"><button class="btn btn-primary btn-sm" id="hb-export"><i class="fas fa-download"></i> Export Data</button><button class="btn btn-primary btn-sm" id="hb-add"><i class="fas fa-plus"></i> Ajukan Data</button></div></div>
        <div class="card-body">
          <div class="panel" id="hb-panel" style="display:none;">
            <div class="form-title">Ajukan Data Baru</div>
            <div class="form-group"><label>Nama Data</label><input type="text" id="hb-uraian" class="form-control" placeholder="Contoh: Nama Data"></div>
            <div class="row-grid">
              <div class="year-group"><div class="year-label">2025</div><input type="text" id="hb-2025" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2026</div><input type="text" id="hb-2026" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2027</div><input type="text" id="hb-2027" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2028</div><input type="text" id="hb-2028" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2029</div><input type="text" id="hb-2029" class="form-control" placeholder="0.00"></div>
            </div>
            <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px;"><button class="btn btn-secondary" id="hb-cancel">Batal</button><button class="btn btn-purple" id="hb-save"><i class="fas fa-floppy-disk"></i> Simpan</button></div>
          </div>
          <div class="table-wrap"><table class="table table-compact table-industry"><thead><tr><th rowspan="2">No</th><th rowspan="2">Nama Data</th><th colspan="5">Tahun</th><th rowspan="2">Status</th><th rowspan="2">Aksi</th></tr><tr><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th></tr></thead><tbody id="hb-tbody"></tbody></table></div>
        </div>
      </div>

      <div class="card industry-card theme-pink">
        <div class="card-header"><div><h3>Kontribusi Sektor Industri terhadap PDRB (HK) tahun 2025 s/d 2029</h3><div class="sub">Data kontribusi industri terhadap PDRB harga konstan</div></div><div class="card-actions"><button class="btn btn-primary btn-sm" id="hk-export"><i class="fas fa-download"></i> Export Data</button><button class="btn btn-primary btn-sm" id="hk-add"><i class="fas fa-plus"></i> Ajukan Data</button></div></div>
        <div class="card-body">
          <div class="panel" id="hk-panel" style="display:none;">
            <div class="form-title">Ajukan Data Baru</div>
            <div class="form-group"><label>Nama Data</label><input type="text" id="hk-uraian" class="form-control" placeholder="Contoh: Nama Data"></div>
            <div class="row-grid">
              <div class="year-group"><div class="year-label">2025</div><input type="text" id="hk-2025" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2026</div><input type="text" id="hk-2026" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2027</div><input type="text" id="hk-2027" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2028</div><input type="text" id="hk-2028" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2029</div><input type="text" id="hk-2029" class="form-control" placeholder="0.00"></div>
            </div>
            <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px;"><button class="btn btn-secondary" id="hk-cancel">Batal</button><button class="btn btn-pink" id="hk-save"><i class="fas fa-floppy-disk"></i> Simpan</button></div>
          </div>
          <div class="table-wrap"><table class="table table-compact table-industry"><thead><tr><th rowspan="2">No</th><th rowspan="2">Nama Data</th><th colspan="5">Tahun</th><th rowspan="2">Status</th><th rowspan="2">Aksi</th></tr><tr><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th></tr></thead><tbody id="hk-tbody"></tbody></table></div>
        </div>
      </div>

      <div class="card industry-card theme-orange">
        <div class="card-header"><div><h3>Pertumbuhan Industri Menurut jenis dari tahun 2025 s/d 2029</h3><div class="sub">Ringkasan pertumbuhan industri menurut kategori</div></div><div class="card-actions"><button class="btn btn-primary btn-sm" id="gr-export"><i class="fas fa-download"></i> Export Data</button><button class="btn btn-primary btn-sm" id="gr-add"><i class="fas fa-plus"></i> Ajukan Data</button></div></div>
        <div class="card-body">
          <div class="panel" id="gr-panel" style="display:none;">
            <div class="form-title">Ajukan Data Baru</div>
            <div class="form-group"><label>Nama Data</label><input type="text" id="gr-uraian" class="form-control" placeholder="Contoh: Nama Data"></div>
            <div class="row-grid">
              <div class="year-group"><div class="year-label">2025</div><input type="text" id="gr-2025" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2026</div><input type="text" id="gr-2026" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2027</div><input type="text" id="gr-2027" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2028</div><input type="text" id="gr-2028" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2029</div><input type="text" id="gr-2029" class="form-control" placeholder="0.00"></div>
            </div>
            <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px;"><button class="btn btn-secondary" id="gr-cancel">Batal</button><button class="btn btn-orange" id="gr-save"><i class="fas fa-floppy-disk"></i> Simpan</button></div>
          </div>
          <div class="table-wrap"><table class="table table-compact table-industry"><thead><tr><th rowspan="2">No</th><th rowspan="2">Nama Data</th><th colspan="5">Tahun</th><th rowspan="2">Status</th><th rowspan="2">Aksi</th></tr><tr><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th></tr></thead><tbody id="gr-tbody"></tbody></table></div>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
window.USER_ROLE = document.body.dataset.userRole || '';
var opdName = 'Perindustrian';
var dinasId=(document.body.dataset.dinasId||'')||null;
var tableKeys = { hb: 'perindustrian_hb', hk: 'perindustrian_hk', gr: 'perindustrian_growth' };
var hbRows = [], hkRows = [], grRows = [];
var dmStatuses={};
function statusLabel(s){if(s==='Disetujui')return 'Disetujui';if(s==='Ditolak')return 'Ditolak';if(s==='Menunggu Persetujuan')return 'Menunggu Persetujuan';return '-'}
function statusClass(s){if(s==='Disetujui')return 'status-approved';if(s==='Ditolak')return 'status-rejected';if(s==='Menunggu Persetujuan')return 'status-menunggu';return ''}
async function fetchDmStatuses(){try{var res=await fetch('/data-management/records',{headers:{'Accept':'application/json'},credentials:'same-origin'});var data=await res.json();dmStatuses={};(Array.isArray(data)?data:[]).forEach(function(rec){if(rec.opd===opdName){dmStatuses[rec.name]=rec.status;}});}catch(_){dmStatuses={}}}
;(function(){var _f=window.fetch;window.fetch=function(u,o){if(o&&(o.method==='POST'||o.method==='PUT'||o.method==='DELETE')){o.credentials=o.credentials||'same-origin';}return _f(u,o);};})();

function mapRows(data){return (Array.isArray(data)?data:[]).map(function(r,i){var v=r.values||{};return { id:r.id, no:i+1, uraian:r.uraian, y2025:v.y2025||'', y2026:v.y2026||'', y2027:v.y2027||'', y2028:v.y2028||'', y2029:v.y2029||'' }; });}

async function fetchRows(key){try{var url= key==='hb' ? '/perindustrian/hb' : (key==='hk' ? '/perindustrian/hk' : '/perindustrian/gr'); var res=await fetch(url,{headers:{'Accept':'application/json'}});var data=await res.json();var mapped=mapRows(data);if(key==='hb'){hbRows=mapped;}else if(key==='hk'){hkRows=mapped;}else{grRows=mapped;}await fetchDmStatuses();if(key==='hb'){renderRows(hbRows,document.getElementById('hb-tbody'),'hb');}else if(key==='hk'){renderRows(hkRows,document.getElementById('hk-tbody'),'hk');}else{renderRows(grRows,document.getElementById('gr-tbody'),'gr');}}catch(_){await fetchDmStatuses();}}

function renderRows(data,tb,key){tb.innerHTML=data.map(function(r,i){var st=dmStatuses[r.uraian];var canEdit=(window.USER_ROLE!=='user'||st==='Menunggu Persetujuan');var actions=canEdit?((window.USER_ROLE==='user')?('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-ed="'+key+':'+i+'"><i class="fas fa-pen"></i></button></div>'):('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-ed="'+key+':'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-del="'+key+':'+i+'"><i class="fas fa-trash"></i></button></div>')):'<div class="row-actions"></div>';return '<tr><td>'+r.no+'</td><td>'+r.uraian+'</td><td>'+(r.y2025||'-')+'</td><td>'+(r.y2026||'-')+'</td><td>'+(r.y2027||'-')+'</td><td>'+(r.y2028||'-')+'</td><td>'+(r.y2029||'-')+'</td><td class="c-status"><span class="status-badge '+statusClass(st)+'">'+statusLabel(st)+'</span></td><td>'+actions+'</td></tr>';}).join('');
  tb.querySelectorAll('button[data-ed]').forEach(function(b){b.onclick=function(){var p=b.getAttribute('data-ed').split(':');var i=parseInt(p[1],10);var set=key==='hb'?hbRows:key==='hk'?hkRows:grRows;var r=set[i];var panelId=key+'-panel';document.getElementById(key+'-uraian').value=r.uraian;document.getElementById(key+'-2025').value=r.y2025;document.getElementById(key+'-2026').value=r.y2026;document.getElementById(key+'-2027').value=r.y2027;document.getElementById(key+'-2028').value=r.y2028;document.getElementById(key+'-2029').value=r.y2029;document.getElementById(panelId).style.display='block';document.getElementById(key+'-add').innerHTML='<i class="fas fa-times"></i> Tutup Form';set._editId=r.id;};});
  tb.querySelectorAll('button[data-del]').forEach(function(b){b.onclick=function(){var p=b.getAttribute('data-del').split(':');var i=parseInt(p[1],10);var set=key==='hb'?hbRows:key==='hk'?hkRows:grRows;var id=set[i]?.id;Utils.confirm('Hapus baris ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(async function(yes){if(!yes)return;try{var url = key==='hb'?('/perindustrian/hb/'+id):(key==='hk'?('/perindustrian/hk/'+id):('/perindustrian/gr/'+id)); var res=await fetch(url,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrfToken}});if(res.ok){await fetchRows(key);Utils.showToast('Baris dihapus','success');}else{Utils.showToast('Gagal menghapus','error');}}catch(e){Utils.showToast('Gagal menghapus','error');}})};});}

function init(){fetchRows('hb');fetchRows('hk');fetchRows('gr');}
init();

function toggle(btn,panel){var open=panel.style.display!=='none';panel.style.display=open?'none':'block';btn.innerHTML=open?'<i class="fas fa-plus"></i> Ajukan Data':'<i class="fas fa-times"></i> Tutup Form'}
document.getElementById('hb-add')?.addEventListener('click',function(){toggle(document.getElementById('hb-add'),document.getElementById('hb-panel'));});
document.getElementById('hk-add')?.addEventListener('click',function(){toggle(document.getElementById('hk-add'),document.getElementById('hk-panel'));});
document.getElementById('gr-add')?.addEventListener('click',function(){toggle(document.getElementById('gr-add'),document.getElementById('gr-panel'));});
document.getElementById('hb-cancel')?.addEventListener('click',function(){document.getElementById('hb-panel').style.display='none';document.getElementById('hb-add').innerHTML='<i class="fas fa-plus"></i> Ajukan Data';hbRows._editId=null;});
document.getElementById('hk-cancel')?.addEventListener('click',function(){document.getElementById('hk-panel').style.display='none';document.getElementById('hk-add').innerHTML='<i class="fas fa-plus"></i> Ajukan Data';hkRows._editId=null;});
document.getElementById('gr-cancel')?.addEventListener('click',function(){document.getElementById('gr-panel').style.display='none';document.getElementById('gr-add').innerHTML='<i class="fas fa-plus"></i> Ajukan Data';grRows._editId=null;});

async function saveKey(key){var ura=document.getElementById(key+'-uraian').value.trim();if(!ura){Utils.showToast('Isi Nama Data','error');return;}var vals={y2025:document.getElementById(key+'-2025').value.trim(),y2026:document.getElementById(key+'-2026').value.trim(),y2027:document.getElementById(key+'-2027').value.trim(),y2028:document.getElementById(key+'-2028').value.trim(),y2029:document.getElementById(key+'-2029').value.trim()};var editId=(key==='hb'?hbRows:(key==='hk'?hkRows:grRows))._editId;var isUser=(window.USER_ROLE==='user');try{if(!isUser){var payload={uraian:ura,values:vals};var url= editId ? (key==='hb'?('/perindustrian/hb/'+editId):(key==='hk'?('/perindustrian/hk/'+editId):('/perindustrian/gr/'+editId))) : (key==='hb'?'/perindustrian/hb':(key==='hk'?'/perindustrian/hk':'/perindustrian/gr'));var method= editId ? 'PUT' : 'POST';var res=await fetch(url,{method:method,headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify(payload)});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows(key);Utils.showToast(editId?'Data diperbarui':'Data ditambahkan','success');}var _yrRaw=(vals.y2029||'').replace(/[^0-9]/g,'');var year=(_yrRaw&&_yrRaw.length===4)?_yrRaw:new Date().getFullYear().toString();try{var fp = key==='hb' ? 'perindustrian_hb' : (key==='hk' ? 'perindustrian_hk' : 'perindustrian_growth'); var dmRes=await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({opd:opdName,dinas_id:dinasId,judul_data:ura,deskripsi:null,file_path:fp,tahun_perencanaan:year})}); dmStatuses[ura]='Menunggu Persetujuan'; await fetchDmStatuses(); if(isUser && dmRes.ok){Utils.showToast('Pengajuan dikirim ke Data Management','success');}}catch(_){ }document.getElementById(key+'-panel').style.display='none';document.getElementById(key+'-add').innerHTML='<i class=\"fas fa-plus\"></i> Ajukan Data';(key==='hb'?hbRows:key==='hk'?hkRows:grRows)._editId=null;}catch(e){Utils.showToast('Gagal menyimpan','error');}}

document.getElementById('hb-save')?.addEventListener('click',function(){saveKey('hb')});
document.getElementById('hk-save')?.addEventListener('click',function(){saveKey('hk')});
document.getElementById('gr-save')?.addEventListener('click',function(){saveKey('gr')});

function exportCsv(filename, headers, rows){var csv=[headers].concat(rows).map(function(row){return row.map(function(v){var s=(''+(v==null?'':v)).replace(/"/g,'""');return '"'+s+'"';}).join(',');}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download=filename;document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url);} 
document.getElementById('hb-export')?.addEventListener('click',function(){var h=['No','Nama Data','2025','2026','2027','2028','2029'];var rows=hbRows.map(function(r){return [r.no,r.uraian,r.y2025,r.y2026,r.y2027,r.y2028,r.y2029]});exportCsv('perindustrian-hb.csv',h,rows)});
document.getElementById('hk-export')?.addEventListener('click',function(){var h=['No','Nama Data','2025','2026','2027','2028','2029'];var rows=hkRows.map(function(r){return [r.no,r.uraian,r.y2025,r.y2026,r.y2027,r.y2028,r.y2029]});exportCsv('perindustrian-hk.csv',h,rows)});
document.getElementById('gr-export')?.addEventListener('click',function(){var h=['No','Nama Data','2025','2026','2027','2028','2029'];var rows=grRows.map(function(r){return [r.no,r.uraian,r.y2025,r.y2026,r.y2027,r.y2028,r.y2029]});exportCsv('perindustrian-pertumbuhan.csv',h,rows)});
</script>
@endpush

