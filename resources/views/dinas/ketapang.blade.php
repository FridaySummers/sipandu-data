@extends('layouts.app')
@section('title', 'Ketapang')
@section('body-class', 'dashboard-page force-light')

@push('styles')
<style>
.kt-card{border-radius:20px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.06);background:#fff;border:1px solid #e2e8f0}
.kt-card .card-header{padding:20px 24px;background:linear-gradient(135deg,#1d4ed8,#3b82f6);color:#ffffff;display:flex;align-items:center;justify-content:space-between;position:relative;z-index:1}
.kt-card .card-header h3{font-size:1.1rem;font-weight:700;margin:0;color:#fff}
.kt-card .card-sub{display:block;font-size:13px;opacity:.85;margin-top:4px;color:#fff}
.kt-head-left{display:flex;align-items:center;gap:12px}
.kt-actions{display:flex;align-items:center;gap:10px;pointer-events:auto;position:relative;z-index:2}
.kt-actions .btn{border-radius:10px;height:36px;padding:0 14px;font-weight:600;font-size:13px;transition:all .2s}
.kt-actions .btn:first-child{background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.4);backdrop-filter:blur(4px)}
.kt-actions .btn:first-child:hover{background:rgba(255,255,255,0.25)}
.kt-actions .btn:last-child{background:#fff;color:#1d4ed8;border:none}
.kt-actions .btn:last-child:hover{box-shadow:0 4px 12px rgba(255,255,255,0.3);transform:translateY(-1px)}
.kt-badge{display:inline-flex;align-items:center;gap:8px;border:1px solid #bfdbfe;color:#1d4ed8;background:#eff6ff;border-radius:12px;padding:8px 14px;font-weight:600;font-size:13px}
.kt-badge i{color:#2563eb}
.kt-panel{margin:16px 20px 24px;border-radius:14px;padding:20px;border:1px solid #e2e8f0;background:#f8fafc}
.kt-panel .form-title{font-weight:700;color:#0f172a;margin-bottom:12px;font-size:1rem}
.kt-panel .form-group label{font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.04em;margin-bottom:6px;display:block}
.kt-panel .row{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-top:16px}
.kt-panel .year-group{display:flex;flex-direction:column;gap:6px}
.kt-panel .year-label{font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.04em}
.kt-panel .form-control{border:1.5px solid #e2e8f0;background:#ffffff;border-radius:10px;padding:10px 14px;font-size:14px;transition:all .2s;width:100%;box-sizing:border-box}
.kt-panel .form-control:focus{border-color:#3b82f6;box-shadow:0 0 0 4px rgba(59,130,246,0.12);outline:none}
.kt-table-wrap{border-radius:14px;padding:0;background:#fff;overflow-x:auto;border:1px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,0.04);margin:0 20px}
.kt-table{width:100%;border-collapse:separate;border-spacing:0;table-layout:fixed;margin:0}
.kt-table thead th{background:linear-gradient(135deg,#eff6ff,#dbeafe);color:#1e3a8a;font-weight:700;font-size:13px;padding:12px 14px;border-bottom:2px solid #bfdbfe}
.kt-table td{padding:11px 14px;border:1px solid #cbd5e1;border-right:1px solid #f1f5f9;vertical-align:middle}
.kt-table th{border-right:1px solid #f1f5f9}
.kt-table th:last-child,.kt-table td:last-child{border-right:none}
.kt-table tbody tr{transition:background .15s ease}
.kt-table tbody tr:hover{background:#f0f9ff}
.kt-table td:first-child,.kt-table th:first-child{text-align:left}
.kt-table td:not(:first-child),.kt-table th:not(:first-child){text-align:center}
.kt-table th,.kt-table td{word-break:break-word;white-space:normal}
.kt-info{margin:20px;background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;border-radius:12px;padding:14px 16px;display:flex;align-items:center;gap:10px;font-size:13px;font-weight:500}
.kt-info i{color:#22c55e;font-size:1.1rem}
.edit-cell{border:1.5px solid #bfdbfe;background:#ffffff;border-radius:8px;padding:7px 10px;width:100%;box-sizing:border-box;transition:all .2s}
.edit-cell:focus{border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,0.12);outline:none}
#ketapang-page .btn-orange{background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border:none}
#ketapang-page .btn-primary{background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff;border:none}
#ketapang-page .btn-outline{background:#fff;border:1.5px solid #e2e8f0;color:#374151;border-radius:10px;font-weight:600}
#ketapang-page .btn-outline:hover{background:#f8fafc;border-color:#93c5fd;color:#2563eb}
.btn-secondary{background:#f1f5f9;color:#374151;border:1px solid #e2e8f0;border-radius:10px;font-weight:600;transition:all .2s}
.btn-secondary:hover{background:#e2e8f0}
.status-badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600}
.status-approved{background:#dcfce7;color:#166534}
.status-rejected{background:#fee2e2;color:#991b1b}
.status-menunggu{background:#fef3c7;color:#92400e}
.row-actions{display:flex;gap:6px;justify-content:center}
.action-btn{border-radius:8px;width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#f8fafc;border:1px solid #e2e8f0;transition:all .2s}
.action-btn:hover{background:#eff6ff;border-color:#93c5fd;color:#2563eb}
.action-btn .fa-pen{color:#3b82f6}
.kt-table thead th:last-child{width:100px !important}
@media (max-width: 640px){.kt-table thead th:last-child{width:auto !important}.kt-table td:last-child{white-space:normal !important}}
</style>
@endpush

@section('content')
    <div class="page active" id="ketapang-page">
        <div class="page-header"><h1>Data Ketahanan Pangan</h1><p>Capaian kinerja urusan pangan 2025–2029 di Kabupaten Kolaka Utara</p></div>
        <div class="card kt-card">
        <div class="card-header"><div class="kt-head-left"><div><h3>Capaian Kinerja Sasaran Daerah yang Terkait Urusan Pangan</h3><span class="card-sub">Data Tahun 2025 - 2029</span></div></div><div class="kt-actions"><button class="btn btn-primary btn-sm" id="kt-export"><i class="fas fa-download"></i> Export Data</button><button type="button" class="btn btn-primary btn-sm" id="kt-add" style="position:relative;z-index:10;pointer-events:auto" onclick="toggleKtPanel()">Ajukan Data</button></div></div>
        <div class="card-body">
          <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 12px"><div class="kt-badge"><i class="fas fa-chart-line"></i><span id="kt-total">Total: 0 Indikator</span></div></div>

          <div class="kt-panel" id="kt-panel" style="display:none;">
            <form id="kt-form">
            <div class="form-title">Form Ajukan Indikator Baru</div>
            <div class="form-group"><label>Nama Data</label><input type="text" id="kt-uraian" class="form-control" placeholder="Masukkan nama data..."></div>
            <div class="row">
              <div class="year-group"><div class="year-label">Tahun 2025</div><input type="text" id="kt-2025" class="form-control" placeholder="0"></div>
              <div class="year-group"><div class="year-label">Tahun 2026</div><input type="text" id="kt-2026" class="form-control" placeholder="0"></div>
              <div class="year-group"><div class="year-label">Tahun 2027</div><input type="text" id="kt-2027" class="form-control" placeholder="0"></div>
              <div class="year-group"><div class="year-label">Tahun 2028</div><input type="text" id="kt-2028" class="form-control" placeholder="0"></div>
              <div class="year-group"><div class="year-label">Tahun 2029</div><input type="text" id="kt-2029" class="form-control" placeholder="0"></div>
            </div>
            <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:10px"><button type="button" class="btn btn-secondary" id="kt-cancel">Batal</button><button type="submit" class="btn btn-primary" id="kt-save">Ajukan Data</button></div>
            </form>
          </div>

          
          <div class="kt-table-wrap">
            <table class="kt-table">
              <thead>
                <tr>
                  <th rowspan="2">Uraian</th>
                  <th colspan="5">Tahun</th>
                  <th rowspan="2">Status</th>
                  <th rowspan="2">Aksi</th>
                </tr>
                <tr>
                  <th>2025</th>
                  <th>2026</th>
                  <th>2027</th>
                  <th>2028</th>
                  <th>2029</th>
                </tr>
              </thead>
              <tbody id="kt-tbody"></tbody>
            </table>
          </div>

          <div class="kt-info"><i class="fas fa-square-poll-horizontal"></i> Indikator capaian kinerja sasaran daerah yang terkait dengan urusan pangan di wilayah Kabupaten Kolaka Utara</div>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
var csrfToken=document.querySelector('meta[name="csrf-token"]')?.content||'';window.USER_ROLE=document.body.dataset.userRole||'';
var opdName='Dinas Ketahanan Pangan';
var dinasId=(document.body.dataset.dinasId||'')||null;
var tableKey='ketahanan_pangan_capaian';
var ktRows=[];var ktEditId='';
var dmStatuses={};
function statusLabel(s){if(s==='Disetujui')return 'Disetujui';if(s==='Ditolak')return 'Ditolak';if(s==='Menunggu Persetujuan')return 'Menunggu Persetujuan';return '-'}
function statusClass(s){if(s==='Disetujui')return 'status-approved';if(s==='Ditolak')return 'status-rejected';if(s==='Menunggu Persetujuan')return 'status-menunggu';return ''}
async function fetchDmStatuses(){try{var res=await fetch('/data-management/records',{headers:{'Accept':'application/json'},credentials:'same-origin'});var data=await res.json();dmStatuses={};(Array.isArray(data)?data:[]).forEach(function(rec){if(rec.opd===opdName){dmStatuses[rec.name]=rec.status;}});}catch(_){dmStatuses={}}}
function mapRows(data){return (Array.isArray(data)?data:[]).map(function(r){var v=r.values||{};return {id:r.id,uraian:r.uraian,y2025:v.y2025||'',y2026:v.y2026||'',y2027:v.y2027||'',y2028:v.y2028||'',y2029:v.y2029||''};});}
function render(){var tb=document.getElementById('kt-tbody');if(!tb)return;tb.innerHTML=ktRows.map(function(r,i){var st=dmStatuses[r.uraian];var canEdit=(window.USER_ROLE!=='user'||st==='Menunggu Persetujuan');var actions=canEdit?((window.USER_ROLE==='user')?('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-kt-ed="'+i+'"><i class="fas fa-pen"></i></button></div>'):('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-kt-ed="'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-kt-del="'+i+'"><i class="fas fa-trash"></i></button></div>')):'<div class="row-actions"></div>';return '<tr><td class="c-uraian">'+r.uraian+'</td><td class="c-y2025">'+(r.y2025||'-')+'</td><td class="c-y2026">'+(r.y2026||'-')+'</td><td class="c-y2027">'+(r.y2027||'-')+'</td><td class="c-y2028">'+(r.y2028||'-')+'</td><td class="c-y2029">'+(r.y2029||'-')+'</td><td class="c-status"><span class="status-badge '+statusClass(st)+'">'+statusLabel(st)+'</span></td><td>'+actions+'</td></tr>';}).join('');document.getElementById('kt-total').textContent='Total: '+ktRows.length+' Indikator';}
async function fetchRows(){try{var res=await fetch('/ketahanan-pangan/rows',{headers:{'Accept':'application/json'},credentials:'same-origin'});var data=await res.json();ktRows=mapRows(data);await fetchDmStatuses();render();}catch(_){ktRows=[];await fetchDmStatuses();render();}}
document.addEventListener('DOMContentLoaded',function(){fetchRows()});
function toggleKtPanel(){var p=document.getElementById('kt-panel');var show=window.getComputedStyle(p).display==='none';p.style.display=show?'block':'none';var b=document.getElementById('kt-add');if(b){b.textContent=show?'− Tutup Form':'+ Ajukan Data';}}
;(function(){var c=document.getElementById('kt-cancel');if(c){c.addEventListener('click',function(){document.getElementById('kt-panel').style.display='none';var b=document.getElementById('kt-add');if(b){b.textContent='+ Ajukan Data';}});}})();

async function submitDM(judul,desc,year){
  var pic='';
  try{var cu=authHandler.getCurrentUser(); pic=(cu&&cu.name)||'';}catch(__){pic='';}
  var payload={opd:opdName,judul_data:judul,deskripsi:desc||null,file_path:'ketahanan_pangan_inline',tahun_perencanaan:year};
  if(dinasId && /^\d+$/.test(String(dinasId))){ payload.dinas_id = parseInt(dinasId,10); }
  var rec={id:Date.now(),opd:opdName,name:judul,period:year,status:'Menunggu Persetujuan',pic:pic,createdAt:new Date().toISOString()};
  try{
    var res=await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify(payload)});
    if(!res.ok){throw new Error('x');}
    try{var cur=JSON.parse(localStorage.getItem('sipandu_dm_records')||'[]');if(!Array.isArray(cur))cur=[];cur.push(rec);localStorage.setItem('sipandu_dm_records',JSON.stringify(cur));}catch(_){}
  }catch(_){
    try{var cur2=JSON.parse(localStorage.getItem('sipandu_dm_records')||'[]');if(!Array.isArray(cur2))cur2=[];cur2.push(rec);localStorage.setItem('sipandu_dm_records',JSON.stringify(cur2));}catch(__){}
  }
  dmStatuses[judul]='Menunggu Persetujuan';
}
;(function(){var f=document.getElementById('kt-form');if(f){f.addEventListener('submit',async function(e){e.preventDefault();var ura=document.getElementById('kt-uraian').value.trim();if(!ura){Utils.showToast('Isi Nama Data','error');return;}var vals={y2025:document.getElementById('kt-2025').value.trim(),y2026:document.getElementById('kt-2026').value.trim(),y2027:document.getElementById('kt-2027').value.trim(),y2028:document.getElementById('kt-2028').value.trim(),y2029:document.getElementById('kt-2029').value.trim()};var hasVal=(vals.y2025||vals.y2026||vals.y2027||vals.y2028||vals.y2029);if(!hasVal){Utils.showToast('Isi minimal salah satu nilai tahun','error');return;}var _yrRaw=(vals.y2029||'').replace(/[^0-9]/g,'');var year=(_yrRaw&&_yrRaw.length===4)?_yrRaw:new Date().getFullYear().toString();var isUser=(window.USER_ROLE==='user');try{if(!isUser){var url=ktEditId?('/ketahanan-pangan/rows/'+ktEditId):'/ketahanan-pangan/rows';var method=ktEditId?'PUT':'POST';var payload={uraian:ura,values:vals};var res=await fetch(url,{method:method,headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify(payload)});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows();}await submitDM(ura,null,year);document.getElementById('kt-panel').style.display='none';var b=document.getElementById('kt-add');if(b){b.textContent='+ Ajukan Indikator';}ktEditId='';Utils.showToast('Data disimpan','success');}catch(e){Utils.showToast('Gagal menyimpan','error');}});}})();
;(function(){var tb=document.getElementById('kt-tbody');if(tb){tb.addEventListener('dblclick',function(e){var td=e.target.closest('td');if(!td)return;if(!td.className.match(/^c\-/))return;var val=td.textContent;var controls='<div style=\"display:flex;align-items:center;gap:6px\"><input class=\"edit-cell\" value=\"'+val+'\" style=\"flex:1\"><button class=\"btn btn-orange btn-xs ok\"><i class=\"fas fa-check\"></i></button><button class=\"btn btn-outline btn-xs cancel\"><i class=\"fas fa-times\"></i></button></div>';td.setAttribute('data-prev',val);td.innerHTML=controls;});tb.addEventListener('click',function(e){var ok=e.target.closest('.ok');var cancel=e.target.closest('.cancel');if(!ok&&!cancel)return;var td=e.target.closest('td');var tr=td.closest('tr');var idx=Array.prototype.indexOf.call(tr.parentNode.children,tr);var r=ktRows[idx];if(ok){var val=td.querySelector('input').value;var cls=td.className;switch(true){case /c-uraian/.test(cls): r.uraian=val; break;case /c-y2025/.test(cls): r.y2025=val; break;case /c-y2026/.test(cls): r.y2026=val; break;case /c-y2027/.test(cls): r.y2027=val; break;case /c-y2028/.test(cls): r.y2028=val; break;case /c-y2029/.test(cls): r.y2029=val; break;}var isUser=(window.USER_ROLE==='user');if(isUser){Utils.showToast('Hanya admin yang bisa mengubah','error');td.textContent=td.getAttribute('data-prev');return;}td.textContent=val;var payload={uraian:r.uraian,values:{y2025:r.y2025,y2026:r.y2026,y2027:r.y2027,y2028:r.y2028,y2029:r.y2029}};fetch('/ketahanan-pangan/rows/'+r.id,{method:'PUT',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify(payload)}).then(async function(res){if(res.ok){await fetchRows();Utils.showToast('Data diperbarui','success');}else{Utils.showToast('Gagal menyimpan','error');}}).catch(function(){Utils.showToast('Gagal menyimpan','error');});}else{td.textContent=td.getAttribute('data-prev');}});}})();
document.addEventListener('click',function(e){var ed=e.target.closest('[data-kt-ed]');if(!ed)return;var i=parseInt(ed.getAttribute('data-kt-ed'),10);var r=ktRows[i];document.getElementById('kt-uraian').value=r.uraian;document.getElementById('kt-2025').value=r.y2025;document.getElementById('kt-2026').value=r.y2026;document.getElementById('kt-2027').value=r.y2027;document.getElementById('kt-2028').value=r.y2028;document.getElementById('kt-2029').value=r.y2029;document.getElementById('kt-panel').style.display='block';ktEditId=r.id;var b=document.getElementById('kt-add');if(b){b.textContent='− Tutup Form';}});
document.addEventListener('click',function(e){var del=e.target.closest('[data-kt-del]');if(!del)return;var i=parseInt(del.getAttribute('data-kt-del'),10);var r=ktRows[i];Utils.confirm('Hapus baris ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(function(yes){if(!yes)return;var isUser=(window.USER_ROLE==='user');if(isUser){Utils.showToast('Hanya admin yang bisa menghapus','error');return;}fetch('/ketahanan-pangan/rows/'+r.id,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin'}).then(async function(res){if(res.ok){await fetchRows();Utils.showToast('Baris dihapus','success');}else{Utils.showToast('Gagal menghapus','error');}}).catch(function(){Utils.showToast('Gagal menghapus','error');});});});
function exportCsv(filename, headers, rows){var csv=[headers].concat(rows).map(function(row){return row.map(function(v){var s=(''+(v==null?'':v)).replace(/"/g,'""');return '"'+s+'"';}).join(',');}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download=filename;document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url);} 
;(function(){var ex=document.getElementById('kt-export');if(ex){ex.addEventListener('click',function(){var h=['Uraian','2025','2026','2027','2028','2029'];var rows=ktRows.map(function(r){return [r.uraian,r.y2025,r.y2026,r.y2027,r.y2028,r.y2029]});exportCsv('ketahanan-pangan.csv',h,rows)});}})();
</script>
@endpush

