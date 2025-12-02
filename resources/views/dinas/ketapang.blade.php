@extends('layouts.app')
@section('title', 'Ketapang')
@section('body-class', 'dashboard-page force-light')

@push('styles')
<style>
.kt-card{border-radius:16px;overflow:hidden;box-shadow:0 8px 24px rgba(37,99,235,0.08);background:#fff}
.kt-card .card-header{padding:22px 22px;background:linear-gradient(90deg,#60a5fa,#3b82f6);color:#ffffff;display:flex;align-items:center;justify-content:space-between;overflow:visible}
.kt-card .card-header{position:relative;z-index:1}
.kt-card .card-sub{display:block;font-size:14px;opacity:.9;margin-top:6px}
.kt-card .card-header .kt-actions{margin-top:4px}
.kt-head-left{display:flex;align-items:center;gap:12px}
.kt-actions{display:flex;align-items:center;gap:12px;pointer-events:auto;position:relative;z-index:2}
.kt-actions .btn{border-radius:8px;height:34px;padding:0 12px;pointer-events:auto}
.kt-badge{display:inline-flex;align-items:center;gap:8px;border:1px solid #93c5fd;color:#1e3a8a;background:#eaf2ff;border-radius:12px;padding:8px 12px;font-weight:600}
.kt-badge i{color:#2563eb}
.kt-panel{margin:16px 16px 22px;border-radius:16px;padding:16px;border:1px solid #cfe0ff;background:#f8fbff}
.kt-panel .form-title{font-weight:700;color:#1e3a8a;margin-bottom:8px}
.kt-panel .row{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-top:12px}
.kt-panel .year-group{display:flex;flex-direction:column;gap:6px}
.kt-panel .year-label{font-size:12px;color:#0f172a;font-weight:600}
.kt-panel .form-control{border:1px solid #93c5fd;background:#ffffff;border-radius:12px;padding:10px 12px}
.kt-table-wrap{border:1px solid #60a5fa;border-radius:12px;padding:6px;background:#f8fbff}
.kt-table{width:100%;border-collapse:separate;border-spacing:0;overflow:hidden;border-radius:12px;table-layout:fixed}
.kt-table thead th{background:#dbeafe;color:#1e3a8a;font-weight:600;padding:10px;border-bottom:1px solid #60a5fa;border-right:1px solid #60a5fa}
.kt-table thead tr th:first-child{border-left:1px solid #60a5fa}
.kt-table td{padding:8px;border-bottom:1px solid #60a5fa;border-right:1px solid #60a5fa}
.kt-table tbody tr td:first-child{border-left:1px solid #60a5fa}
.kt-table tbody tr:nth-child(even){background:#fff}
.kt-table tbody tr:nth-child(odd){background:#fff}
.kt-table td:first-child,.kt-table th:first-child{text-align:left}
.kt-table td:not(:first-child),.kt-table th:not(:first-child){text-align:center}
.kt-table th,.kt-table td{font-size:13px}
.kt-info{margin-top:12px;background:#eaf2ff;border:1px solid #93c5fd;color:#1e3a8a;border-radius:12px;padding:12px}
.edit-cell{border:1px solid #93c5fd;background:#ffffff;border-radius:10px;padding:8px 10px}
#ketapang-page .btn-orange{background:#f97316;border-color:#f97316;color:#fff}
#ketapang-page .btn-primary{background:#2563eb;border-color:#2563eb;color:#fff}
#ketapang-page .btn-outline{background:#fff;border:1px solid #93c5fd;color:#2563eb;border-radius:8px}
.btn-secondary{background:#e5e7eb;color:#111827}
</style>
@endpush

@section('content')
    <div class="page active" id="ketapang-page">
        <div class="page-header"><h1>Data Ketahanan Pangan</h1><p>Capaian kinerja urusan pangan 2019–2023 di Kabupaten Kolaka Utara</p></div>
        <div class="card kt-card">
        <div class="card-header"><div class="kt-head-left"><div><h3>Capaian Kinerja Sasaran Daerah yang Terkait Urusan Pangan</h3><span class="card-sub">Data Tahun 2019 - 2023</span></div></div><div class="kt-actions"><button class="btn btn-outline btn-sm" id="kt-export"><i class="fas fa-download"></i> Export Data</button><button type="button" class="btn btn-outline btn-sm" id="kt-add" style="position:relative;z-index:10;pointer-events:auto" onclick="toggleKtPanel()">Ajukan Indikator</button></div></div>
        <div class="card-body">
          <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 12px"><div class="kt-badge"><i class="fas fa-chart-line"></i><span id="kt-total">Total: 0 Indikator</span></div></div>

          <div class="kt-panel" id="kt-panel" style="display:none;">
            <form id="kt-form">
            <div class="form-title">Form Ajukan Indikator Baru</div>
            <div class="form-group"><label>Nama Data</label><input type="text" id="kt-uraian" class="form-control" placeholder="Masukkan nama data..."></div>
            <div class="row">
              <div class="year-group"><div class="year-label">Tahun 2019</div><input type="text" id="kt-2019" class="form-control" placeholder="0"></div>
              <div class="year-group"><div class="year-label">Tahun 2020</div><input type="text" id="kt-2020" class="form-control" placeholder="0"></div>
              <div class="year-group"><div class="year-label">Tahun 2021</div><input type="text" id="kt-2021" class="form-control" placeholder="0"></div>
              <div class="year-group"><div class="year-label">Tahun 2022</div><input type="text" id="kt-2022" class="form-control" placeholder="0"></div>
              <div class="year-group"><div class="year-label">Tahun 2023</div><input type="text" id="kt-2023" class="form-control" placeholder="0"></div>
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
                  <th rowspan="2">Aksi</th>
                </tr>
                <tr>
                  <th>2019</th>
                  <th>2020</th>
                  <th>2021</th>
                  <th>2022</th>
                  <th>2023</th>
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
function mapRows(data){return (Array.isArray(data)?data:[]).map(function(r){var v=r.values||{};return {id:r.id,uraian:r.uraian,y2019:v.y2019||'',y2020:v.y2020||'',y2021:v.y2021||'',y2022:v.y2022||'',y2023:v.y2023||''};});}
function render(){var tb=document.getElementById('kt-tbody');if(!tb)return;tb.innerHTML=ktRows.map(function(r,i){return '<tr><td class="c-uraian">'+r.uraian+'</td><td class="c-y2019">'+(r.y2019||'-')+'</td><td class="c-y2020">'+(r.y2020||'-')+'</td><td class="c-y2021">'+(r.y2021||'-')+'</td><td class="c-y2022">'+(r.y2022||'-')+'</td><td class="c-y2023">'+(r.y2023||'-')+'</td><td><button class="btn btn-outline btn-sm action-btn" data-kt-ed="'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-kt-del="'+i+'"><i class="fas fa-trash"></i></button></td></tr>';}).join('');document.getElementById('kt-total').textContent='Total: '+ktRows.length+' Indikator';}
async function fetchRows(){try{var res=await fetch('/ketahanan-pangan/rows',{headers:{'Accept':'application/json'}});var data=await res.json();ktRows=mapRows(data);render();}catch(_){ktRows=[];render();}}
document.addEventListener('DOMContentLoaded',function(){fetchRows()});
function toggleKtPanel(){var p=document.getElementById('kt-panel');var show=window.getComputedStyle(p).display==='none';p.style.display=show?'block':'none';var b=document.getElementById('kt-add');if(b){b.textContent=show?'− Tutup Form':'+ Ajukan Indikator';}}
;(function(){var c=document.getElementById('kt-cancel');if(c){c.addEventListener('click',function(){document.getElementById('kt-panel').style.display='none';var b=document.getElementById('kt-add');if(b){b.textContent='+ Ajukan Indikator';}});}})();

async function submitDM(judul,desc,year){try{await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({opd:opdName,dinas_id:dinasId,judul_data:judul,deskripsi:desc||null,file_path:'ketahanan_pangan_inline',tahun_perencanaan:year})});}catch(_){}}
;(function(){var f=document.getElementById('kt-form');if(f){f.addEventListener('submit',async function(e){e.preventDefault();var ura=document.getElementById('kt-uraian').value.trim();if(!ura){Utils.showToast('Isi Nama Data','error');return;}var vals={y2019:document.getElementById('kt-2019').value.trim(),y2020:document.getElementById('kt-2020').value.trim(),y2021:document.getElementById('kt-2021').value.trim(),y2022:document.getElementById('kt-2022').value.trim(),y2023:document.getElementById('kt-2023').value.trim()};var hasVal=(vals.y2019||vals.y2020||vals.y2021||vals.y2022||vals.y2023);if(!hasVal){Utils.showToast('Isi minimal salah satu nilai tahun','error');return;}var year=(vals.y2023||'').replace(/[^0-9]/g,'').slice(0,4)||new Date().getFullYear().toString();var isUser=(window.USER_ROLE==='user');try{if(!isUser){var url=ktEditId?('/ketahanan-pangan/rows/'+ktEditId):'/ketahanan-pangan/rows';var method=ktEditId?'PUT':'POST';var payload={uraian:ura,values:vals};var res=await fetch(url,{method:method,headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify(payload)});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows();}await submitDM(ura,null,year);document.getElementById('kt-panel').style.display='none';var b=document.getElementById('kt-add');if(b){b.textContent='+ Ajukan Indikator';}ktEditId='';Utils.showToast('Data disimpan','success');}catch(e){Utils.showToast('Gagal menyimpan','error');}});}})();
;(function(){var tb=document.getElementById('kt-tbody');if(tb){tb.addEventListener('dblclick',function(e){var td=e.target.closest('td');if(!td)return;if(!td.className.match(/^c\-/))return;var val=td.textContent;var controls='<div style=\"display:flex;align-items:center;gap:6px\"><input class=\"edit-cell\" value=\"'+val+'\" style=\"flex:1\"><button class=\"btn btn-orange btn-xs ok\"><i class=\"fas fa-check\"></i></button><button class=\"btn btn-outline btn-xs cancel\"><i class=\"fas fa-times\"></i></button></div>';td.setAttribute('data-prev',val);td.innerHTML=controls;});tb.addEventListener('click',function(e){var ok=e.target.closest('.ok');var cancel=e.target.closest('.cancel');if(!ok&&!cancel)return;var td=e.target.closest('td');var tr=td.closest('tr');var idx=Array.prototype.indexOf.call(tr.parentNode.children,tr);var r=ktRows[idx];if(ok){var val=td.querySelector('input').value;var cls=td.className;switch(true){case /c-uraian/.test(cls): r.uraian=val; break;case /c-y2019/.test(cls): r.y2019=val; break;case /c-y2020/.test(cls): r.y2020=val; break;case /c-y2021/.test(cls): r.y2021=val; break;case /c-y2022/.test(cls): r.y2022=val; break;case /c-y2023/.test(cls): r.y2023=val; break;}var isUser=(window.USER_ROLE==='user');if(isUser){Utils.showToast('Hanya admin yang bisa mengubah','error');td.textContent=td.getAttribute('data-prev');return;}td.textContent=val;var payload={uraian:r.uraian,values:{y2019:r.y2019,y2020:r.y2020,y2021:r.y2021,y2022:r.y2022,y2023:r.y2023}};fetch('/ketahanan-pangan/rows/'+r.id,{method:'PUT',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify(payload)}).then(async function(res){if(res.ok){await fetchRows();Utils.showToast('Data diperbarui','success');}else{Utils.showToast('Gagal menyimpan','error');}}).catch(function(){Utils.showToast('Gagal menyimpan','error');});}else{td.textContent=td.getAttribute('data-prev');}});}})();
document.addEventListener('click',function(e){var ed=e.target.closest('[data-kt-ed]');if(!ed)return;var i=parseInt(ed.getAttribute('data-kt-ed'),10);var r=ktRows[i];document.getElementById('kt-uraian').value=r.uraian;document.getElementById('kt-2019').value=r.y2019;document.getElementById('kt-2020').value=r.y2020;document.getElementById('kt-2021').value=r.y2021;document.getElementById('kt-2022').value=r.y2022;document.getElementById('kt-2023').value=r.y2023;document.getElementById('kt-panel').style.display='block';ktEditId=r.id;var b=document.getElementById('kt-add');if(b){b.textContent='− Tutup Form';}});
document.addEventListener('click',function(e){var del=e.target.closest('[data-kt-del]');if(!del)return;var i=parseInt(del.getAttribute('data-kt-del'),10);var r=ktRows[i];Utils.confirm('Hapus baris ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(function(yes){if(!yes)return;var isUser=(window.USER_ROLE==='user');if(isUser){Utils.showToast('Hanya admin yang bisa menghapus','error');return;}fetch('/ketahanan-pangan/rows/'+r.id,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrfToken,'Accept':'application/json'}}).then(async function(res){if(res.ok){await fetchRows();Utils.showToast('Baris dihapus','success');}else{Utils.showToast('Gagal menghapus','error');}}).catch(function(){Utils.showToast('Gagal menghapus','error');});});});
function exportCsv(filename, headers, rows){var csv=[headers].concat(rows).map(function(row){return row.map(function(v){var s=(''+(v==null?'':v)).replace(/"/g,'""');return '"'+s+'"';}).join(',');}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download=filename;document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url);} 
;(function(){var ex=document.getElementById('kt-export');if(ex){ex.addEventListener('click',function(){var h=['Uraian','2019','2020','2021','2022','2023'];var rows=ktRows.map(function(r){return [r.uraian,r.y2019,r.y2020,r.y2021,r.y2022,r.y2023]});exportCsv('ketahanan-pangan.csv',h,rows)});}})();
</script>
@endpush
