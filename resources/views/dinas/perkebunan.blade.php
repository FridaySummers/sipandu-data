@extends('layouts.app')
@section('title', 'Perkebunan')
@section('body-class', 'dashboard-page force-light')

@push('styles')
<style>
.pk-toolbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px}
.pk-card .card-actions .btn{border-radius:8px;height:34px;padding:0 12px;font-weight:600}
.btn-green{background:#22c55e;border-color:#22c55e;color:#fff}
.pk-card{border-radius:16px;overflow:hidden;margin-bottom:18px}
.pk-card .card-header{padding:14px 18px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid transparent;color:#fff;overflow:visible}
.pk-card .card-actions{display:flex;gap:12px}
.pk-card .card-header .sub{font-size:13px;opacity:.9;color:#fff}
.pk-panel{margin:16px;border-radius:16px;padding:16px;border:1px solid #e5e7eb;background:#f8fafc}
.pk-panel .form-group{display:flex;flex-direction:column;gap:8px;margin-bottom:8px}
.pk-grid{display:grid;gap:12px}
.pk-grid-5{grid-template-columns:repeat(5,1fr)}
.pk-grid-6{grid-template-columns:repeat(6,1fr)}
.th-group-year{background:#eaf2ff;font-weight:600}
.year-group{display:flex;flex-direction:column;gap:6px}
.theme-green .year-label{font-size:12px;color:#1e3a8a;font-weight:600}
.theme-amber .year-label{font-size:12px;color:#1e3a8a;font-weight:600}
.theme-teal .year-label{font-size:12px;color:#1e3a8a;font-weight:600}
.pk-table{border:none;border-radius:12px;overflow:hidden;border-collapse:separate;border-spacing:0}
.pk-table thead th{background:#dbeafe !important;color:#1e3a8a !important;border-bottom:1px solid #93c5fd !important}
.pk-table th,.pk-table td{border-bottom:1px solid #93c5fd !important;border-right:1px solid #93c5fd !important}
.pk-table tr th:first-child,.pk-table tr td:first-child{border-left:1px solid #93c5fd !important}
.pk-table tbody tr:last-child td{border-bottom:1px solid #93c5fd !important}
.pk-table th:last-child,.pk-table td:last-child{text-align:center}
.pk-table td:last-child{display:table-cell !important;text-align:center !important;white-space:nowrap}
.pk-table th{font-weight:600;text-align:center}
.pk-table td,.pk-table th{padding:10px 12px}
.pk-table td:nth-child(1),.pk-table td:nth-child(2),.pk-table th:nth-child(1),.pk-table th:nth-child(2){text-align:left}
.pk-table tbody tr:nth-child(even){background:#f8fafc}
.pk-table tbody tr:hover{background:#f1f5f9}
.pk-table td:not(:nth-child(1)):not(:nth-child(2)){text-align:center}
.pk-table-wrap{border:1px solid #93c5fd;border-radius:12px;overflow:hidden;background:#fff;box-shadow:0 1px 6px rgba(0,0,0,0.04)}
.pk-table thead tr:first-child th{border-top:1px solid #93c5fd !important}
.pk-table thead tr:nth-child(2) th{border-top:1px solid #93c5fd !important}
.edit-cell{border:1px solid #cbd5e1;background:#ffffff;border-radius:10px;padding:8px 10px}
.form-control{border:1px solid #93c5fd;background:#ffffff;border-radius:12px;padding:10px 12px}
.form-control:focus{outline:none;border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,0.25)}
.theme-green .card-header{background:linear-gradient(90deg,#60a5fa,#3b82f6);color:#ffffff}
.theme-green .pk-panel{background:#eff6ff;border-color:#bfdbfe}
.theme-amber .card-header{background:linear-gradient(90deg,#60a5fa,#3b82f6);color:#ffffff}
.theme-amber .pk-panel{background:#eff6ff;border-color:#bfdbfe}
.theme-teal .card-header{background:linear-gradient(90deg,#60a5fa,#3b82f6);color:#ffffff}
.theme-teal .pk-panel{background:#eff6ff;border-color:#bfdbfe}
.theme-green .pk-table thead th{background:#dbeafe}
.theme-amber .pk-table thead th{background:#dbeafe}
.theme-teal .pk-table thead th{background:#dbeafe}
</style>
@endpush

@section('content')
    <div class="page active" id="perkebunan-page">
      <div class="page-header"><h1>Perkebunan</h1><p>Populasi Ternak, Produksi Tanaman, dan Luas Areal (2019-2023)</p></div>
      <div class="pk-toolbar"><div class="segmented"><button class="btn btn-primary btn-sm" id="pk-tab-pop">Populasi Ternak</button><button class="btn btn-outline btn-sm" id="pk-tab-prod">Produksi Tanaman</button><button class="btn btn-outline btn-sm" id="pk-tab-luas">Luas Areal</button></div><div></div></div>

      <div id="pk-pop">
        <div class="card pk-card theme-green">
          <div class="card-header"><div><h3>Populasi Ternak (2019-2023)</h3><div class="sub">Ringkasan populasi ternak perkebunan</div></div><div class="card-actions"><button class="btn btn-outline btn-sm" id="pk-pop-export"><i class="fas fa-download"></i> Export</button> <button class="btn btn-green btn-sm" id="pk-pop-add"><i class="fas fa-plus"></i> Ajukan Baris</button></div></div>
          <div class="card-body">
            <div class="pk-panel" id="pk-pop-panel" style="display:none;">
              <div class="form-group"><label>Nama Data</label><input type="text" id="pop-uraian" class="form-control" placeholder="Contoh: Nama Data"></div>
              <div class="pk-grid pk-grid-6">
                <div class="year-group"><div class="year-label">2019</div><input class="form-control" id="pop-2019" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2020</div><input class="form-control" id="pop-2020" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2021</div><input class="form-control" id="pop-2021" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2022</div><input class="form-control" id="pop-2022" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2023</div><input class="form-control" id="pop-2023" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">Trend (%)</div><input class="form-control" id="pop-trend" placeholder="0.00"></div>
              </div>
              <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px"><button class="btn btn-secondary" id="pk-pop-cancel">Batal</button><button class="btn btn-primary" id="pk-pop-save">Simpan</button></div>
            </div>
            <div class="pk-table-wrap"><table class="table table-compact pk-table"><thead><tr><th rowspan="2">No.</th><th rowspan="2">Uraian</th><th class="th-group-year" colspan="5">Tahun</th><th rowspan="2">Trend (%)</th><th rowspan="2">Aksi</th></tr><tr><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody id="pk-pop-tbody"></tbody></table></div>
          </div>
        </div>
      </div>

      <div id="pk-prod" style="display:none;">
        <div class="card pk-card theme-amber">
          <div class="card-header"><div><h3>Produksi Tanaman Perkebunan (2019-2023)</h3><div class="sub">Rekap produksi tiap komoditas</div></div><div class="card-actions"><button class="btn btn-outline btn-sm" id="pk-prod-export"><i class="fas fa-download"></i> Export</button> <button class="btn btn-green btn-sm" id="pk-prod-add"><i class="fas fa-plus"></i> Ajukan Komoditas</button></div></div>
          <div class="card-body">
            <div class="pk-panel" id="pk-prod-panel" style="display:none;">
              <div class="form-group"><label>Nama Data</label><input type="text" id="prod-uraian" class="form-control" placeholder="Contoh: Nama Data"></div>
              <div class="pk-grid pk-grid-5">
                <div class="year-group"><div class="year-label">2019</div><input class="form-control" id="prod-2019" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2020</div><input class="form-control" id="prod-2020" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2021</div><input class="form-control" id="prod-2021" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2022</div><input class="form-control" id="prod-2022" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2023</div><input class="form-control" id="prod-2023" placeholder="0.00"></div>
              </div>
              <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px"><button class="btn btn-secondary" id="pk-prod-cancel">Batal</button><button class="btn btn-primary" id="pk-prod-save">Simpan</button></div>
            </div>
            <div class="pk-table-wrap"><table class="table table-compact pk-table"><thead><tr><th>No.</th><th>Komoditas</th><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th><th>Aksi</th></tr></thead><tbody id="pk-prod-tbody"></tbody></table></div>
          </div>
        </div>
      </div>

      <div id="pk-luas" style="display:none;">
        <div class="card pk-card theme-teal">
          <div class="card-header"><div><h3>Luas Areal Tanaman Perkebunan (ha)</h3><div class="sub">Perkembangan luas areal komoditas</div></div><div class="card-actions"><button class="btn btn-outline btn-sm" id="pk-luas-export"><i class="fas fa-download"></i> Export</button> <button class="btn btn-green btn-sm" id="pk-luas-add"><i class="fas fa-plus"></i> Ajukan Komoditas</button></div></div>
          <div class="card-body">
            <div class="pk-panel" id="pk-luas-panel" style="display:none;">
              <div class="form-group"><label>Nama Data</label><input type="text" id="luas-uraian" class="form-control" placeholder="Contoh: Nama Data"></div>
              <div class="pk-grid pk-grid-5">
                <div class="year-group"><div class="year-label">2019</div><input class="form-control" id="luas-2019" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2020</div><input class="form-control" id="luas-2020" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2021</div><input class="form-control" id="luas-2021" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2022</div><input class="form-control" id="luas-2022" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2023</div><input class="form-control" id="luas-2023" placeholder="0.00"></div>
              </div>
              <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px"><button class="btn btn-secondary" id="pk-luas-cancel">Batal</button><button class="btn btn-primary" id="pk-luas-save">Simpan</button></div>
            </div>
            <div class="pk-table-wrap"><table class="table table-compact pk-table"><thead><tr><th>No.</th><th>Komoditas</th><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th><th>Aksi</th></tr></thead><tbody id="pk-luas-tbody"></tbody></table></div>
          </div>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
var csrfToken=document.querySelector('meta[name="csrf-token"]')?.content||'';window.USER_ROLE=document.body.dataset.userRole||'';
var opdName='Dinas Perkebunan';
var keys={pop:'perkebunan_populasi',prod:'perkebunan_produksi',luas:'perkebunan_luas'};
var dinasId=(document.body.dataset.dinasId||'')||null;
var popRows=[],prodRows=[],luasRows=[];
function mapPop(data){return (Array.isArray(data)?data:[]).map(function(r,i){var v=r.values||{};return {id:r.id,no:i+1,uraian:r.uraian,y2019:v.y2019||'',y2020:v.y2020||'',y2021:v.y2021||'',y2022:v.y2022||'',y2023:v.y2023||'',trend:v.trend||''};});}
function mapStd(data){return (Array.isArray(data)?data:[]).map(function(r,i){var v=r.values||{};return {id:r.id,no:i+1,uraian:r.uraian,y2019:v.y2019||'',y2020:v.y2020||'',y2021:v.y2021||'',y2022:v.y2022||'',y2023:v.y2023||''};});}
function renderRows(data,tb,includeTrend,key){tb.innerHTML=data.map(function(r,i){var cells='<td>'+r.no+'</td><td class="c-uraian">'+r.uraian+'</td><td class="c-y2019">'+(r.y2019||'-')+'</td><td class="c-y2020">'+(r.y2020||'-')+'</td><td class="c-y2021">'+(r.y2021||'-')+'</td><td class="c-y2022">'+(r.y2022||'-')+'</td><td class="c-y2023">'+(r.y2023||'-')+'</td>';if(includeTrend){cells+='<td class="c-trend">'+(r.trend||'-')+'</td>';}cells+='<td><button class="btn btn-outline btn-sm action-btn" data-pk-ed="'+key+':'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-pk-del="'+key+':'+i+'"><i class="fas fa-trash"></i></button></td>';return '<tr data-row="'+r.no+'">'+cells+'</tr>';}).join('');}
async function fetchRows(key){try{var url= key==='pop' ? '/perkebunan/pop' : (key==='prod' ? '/perkebunan/prod' : '/perkebunan/luas'); var res=await fetch(url,{headers:{'Accept':'application/json'}});var data=await res.json();if(key==='pop'){popRows=mapPop(data);renderRows(popRows,document.getElementById('pk-pop-tbody'),true,'pop');}else if(key==='prod'){prodRows=mapStd(data);renderRows(prodRows,document.getElementById('pk-prod-tbody'),false,'prod');}else{luasRows=mapStd(data);renderRows(luasRows,document.getElementById('pk-luas-tbody'),false,'luas');}}catch(_){if(key==='pop'){popRows=[];renderRows(popRows,document.getElementById('pk-pop-tbody'),true,'pop');}else if(key==='prod'){prodRows=[];renderRows(prodRows,document.getElementById('pk-prod-tbody'),false,'prod');}else{luasRows=[];renderRows(luasRows,document.getElementById('pk-luas-tbody'),false,'luas');}}}
document.addEventListener('DOMContentLoaded',function(){fetchRows('pop');fetchRows('prod');fetchRows('luas');});
function toggleTab(active){['pk-pop','pk-prod','pk-luas'].forEach(function(id){document.getElementById(id).style.display=id===active?'block':'none';});var tp=document.getElementById('pk-tab-pop');var td=document.getElementById('pk-tab-prod');var tl=document.getElementById('pk-tab-luas');[tp,td,tl].forEach(function(b){b.classList.add('btn-outline');b.classList.remove('btn-primary');});if(active==='pk-pop'){tp.classList.add('btn-primary');tp.classList.remove('btn-outline');}else if(active==='pk-prod'){td.classList.add('btn-primary');td.classList.remove('btn-outline');}else{tl.classList.add('btn-primary');tl.classList.remove('btn-outline');}}
document.getElementById('pk-tab-pop')?.addEventListener('click',function(){toggleTab('pk-pop');});
document.getElementById('pk-tab-prod')?.addEventListener('click',function(){toggleTab('pk-prod');});
document.getElementById('pk-tab-luas')?.addEventListener('click',function(){toggleTab('pk-luas');});
function toggle(btn,panel){var open=panel.style.display!=='none';panel.style.display=open?'none':'block';btn.innerHTML=open?'<i class="fas fa-plus"></i> '+(btn.id==='pk-pop-add'?'Ajukan Baris':'Ajukan Komoditas'):'Tutup Form';}
document.getElementById('pk-pop-add')?.addEventListener('click',function(){popEditIndex=-1;popEditId='';toggle(this,document.getElementById('pk-pop-panel'));});
document.getElementById('pk-prod-add')?.addEventListener('click',function(){prodEditIndex=-1;prodEditId='';toggle(this,document.getElementById('pk-prod-panel'));});
document.getElementById('pk-luas-add')?.addEventListener('click',function(){luasEditIndex=-1;luasEditId='';toggle(this,document.getElementById('pk-luas-panel'));});
document.getElementById('pk-pop-cancel')?.addEventListener('click',function(){popEditIndex=-1;popEditId='';document.getElementById('pk-pop-panel').style.display='none';document.getElementById('pk-pop-add').textContent='+ Ajukan Baris';});
document.getElementById('pk-prod-cancel')?.addEventListener('click',function(){prodEditIndex=-1;prodEditId='';document.getElementById('pk-prod-panel').style.display='none';document.getElementById('pk-prod-add').textContent='+ Ajukan Komoditas';});
document.getElementById('pk-luas-cancel')?.addEventListener('click',function(){luasEditIndex=-1;luasEditId='';document.getElementById('pk-luas-panel').style.display='none';document.getElementById('pk-luas-add').textContent='+ Ajukan Komoditas';});
async function submitDM(judul,year,key){try{var fp= key==='pop' ? 'perkebunan_pop' : (key==='prod' ? 'perkebunan_prod' : 'perkebunan_luas'); await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({opd:opdName,dinas_id:dinasId,judul_data:judul,deskripsi:null,file_path:fp,tahun_perencanaan:year})});}catch(_){}}
document.getElementById('pk-pop-save')?.addEventListener('click',async function(){var ura=document.getElementById('pop-uraian').value.trim();if(!ura){Utils.showToast('Isi Nama Data','error');return;}var vals={y2019:document.getElementById('pop-2019').value.trim(),y2020:document.getElementById('pop-2020').value.trim(),y2021:document.getElementById('pop-2021').value.trim(),y2022:document.getElementById('pop-2022').value.trim(),y2023:document.getElementById('pop-2023').value.trim(),trend:document.getElementById('pop-trend').value.trim()};var hasVal=(vals.y2019||vals.y2020||vals.y2021||vals.y2022||vals.y2023);if(!hasVal){Utils.showToast('Isi minimal salah satu nilai tahun','error');return;}var year=(vals.y2023||'').replace(/[^0-9]/g,'').slice(0,4)||new Date().getFullYear().toString();var isUser=(window.USER_ROLE==='user');try{if(popEditId){if(!isUser){var res=await fetch('/perkebunan/pop/'+popEditId,{method:'PUT',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:ura,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows('pop');}}else{if(!isUser){var res=await fetch('/perkebunan/pop',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:ura,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows('pop');}}await submitDM(ura,year,'pop');document.getElementById('pk-pop-panel').style.display='none';document.getElementById('pk-pop-add').innerHTML='<i class=\"fas fa-plus\"></i> Ajukan Baris';popEditIndex=-1;popEditId='';Utils.showToast('Data disimpan','success');}catch(e){Utils.showToast('Gagal menyimpan','error');}});
document.getElementById('pk-prod-save')?.addEventListener('click',async function(){var ura=document.getElementById('prod-uraian').value.trim();if(!ura){Utils.showToast('Isi Nama Data','error');return;}var vals={y2019:document.getElementById('prod-2019').value.trim(),y2020:document.getElementById('prod-2020').value.trim(),y2021:document.getElementById('prod-2021').value.trim(),y2022:document.getElementById('prod-2022').value.trim(),y2023:document.getElementById('prod-2023').value.trim()};var hasVal=(vals.y2019||vals.y2020||vals.y2021||vals.y2022||vals.y2023);if(!hasVal){Utils.showToast('Isi minimal salah satu nilai tahun','error');return;}var year=(vals.y2023||'').replace(/[^0-9]/g,'').slice(0,4)||new Date().getFullYear().toString();var isUser=(window.USER_ROLE==='user');try{if(prodEditId){if(!isUser){var res=await fetch('/perkebunan/prod/'+prodEditId,{method:'PUT',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:ura,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows('prod');}}else{if(!isUser){var res=await fetch('/perkebunan/prod',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:ura,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows('prod');}}await submitDM(ura,year,'prod');document.getElementById('pk-prod-panel').style.display='none';document.getElementById('pk-prod-add').innerHTML='<i class=\"fas fa-plus\"></i> Ajukan Komoditas';prodEditIndex=-1;prodEditId='';Utils.showToast('Data disimpan','success');}catch(e){Utils.showToast('Gagal menyimpan','error');}});
document.getElementById('pk-luas-save')?.addEventListener('click',async function(){var ura=document.getElementById('luas-uraian').value.trim();if(!ura){Utils.showToast('Isi Nama Data','error');return;}var vals={y2019:document.getElementById('luas-2019').value.trim(),y2020:document.getElementById('luas-2020').value.trim(),y2021:document.getElementById('luas-2021').value.trim(),y2022:document.getElementById('luas-2022').value.trim(),y2023:document.getElementById('luas-2023').value.trim()};var hasVal=(vals.y2019||vals.y2020||vals.y2021||vals.y2022||vals.y2023);if(!hasVal){Utils.showToast('Isi minimal salah satu nilai tahun','error');return;}var year=(vals.y2023||'').replace(/[^0-9]/g,'').slice(0,4)||new Date().getFullYear().toString();var isUser=(window.USER_ROLE==='user');try{if(luasEditId){if(!isUser){var res=await fetch('/perkebunan/luas/'+luasEditId',{method:'PUT',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:ura,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows('luas');}}else{if(!isUser){var res=await fetch('/perkebunan/luas',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:ura,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows('luas');}}await submitDM(ura,year,'luas');document.getElementById('pk-luas-panel').style.display='none';document.getElementById('pk-luas-add').innerHTML='<i class=\"fas fa-plus\"></i> Ajukan Komoditas';luasEditIndex=-1;luasEditId='';Utils.showToast('Data disimpan','success');}catch(e){Utils.showToast('Gagal menyimpan','error');}});
var popEditIndex=-1,prodEditIndex=-1,luasEditIndex=-1;var popEditId='',prodEditId='',luasEditId='';
function enableInlineEdit(tbody,data,key){tbody?.addEventListener('dblclick',function(e){var td=e.target.closest('td');if(!td)return;if(!td.className.match(/^c\-/))return;var val=td.textContent;var controls='<div style="display:flex;align-items:center;gap:6px"><input class="edit-cell" value="'+val+'" style="flex:1"><button class="btn btn-primary btn-xs ok"><i class="fas fa-check"></i></button><button class="btn btn-outline btn-xs cancel"><i class="fas fa-times"></i></button></div>';td.setAttribute('data-prev',val);td.innerHTML=controls;});tbody?.addEventListener('click',function(e){var ok=e.target.closest('.ok');var cancel=e.target.closest('.cancel');if(!ok&&!cancel)return;var td=e.target.closest('td');var tr=td.closest('tr');var idx=parseInt(tr.dataset.row,10)-1;var r=data[idx];if(ok){var val=td.querySelector('input').value;var cls=td.className;switch(true){case /c-uraian/.test(cls): r.uraian=val; break;case /c-y2019/.test(cls): r.y2019=val; break;case /c-y2020/.test(cls): r.y2020=val; break;case /c-y2021/.test(cls): r.y2021=val; break;case /c-y2022/.test(cls): r.y2022=val; break;case /c-y2023/.test(cls): r.y2023=val; break;case /c-trend/.test(cls): r.trend=val; break;}td.textContent=val;var isUser=(window.USER_ROLE==='user');if(isUser){Utils.showToast('Hanya admin yang bisa mengubah','error');td.textContent=td.getAttribute('data-prev');return;}var id=r.id;var payload={uraian:r.uraian,values:{y2019:r.y2019,y2020:r.y2020,y2021:r.y2021,y2022:r.y2022,y2023:r.y2023}};if(key==='pop'){payload.values.trend=r.trend||''}var url = key==='pop'?('/perkebunan/pop/'+id):(key==='prod'?('/perkebunan/prod/'+id):('/perkebunan/luas/'+id));fetch(url,{method:'PUT',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify(payload)}).then(async function(res){if(res.ok){await fetchRows(key);Utils.showToast('Data diperbarui','success');}else{Utils.showToast('Gagal menyimpan','error');}}).catch(function(){Utils.showToast('Gagal menyimpan','error');});}else{td.textContent=td.getAttribute('data-prev');}})}
enableInlineEdit(document.getElementById('pk-pop-tbody'),popRows,'pop');
enableInlineEdit(document.getElementById('pk-prod-tbody'),prodRows,'prod');
enableInlineEdit(document.getElementById('pk-luas-tbody'),luasRows,'luas');
document.addEventListener('click',function(e){
  var ed=e.target.closest('[data-pk-ed]');
  var del=e.target.closest('[data-pk-del]');
  if(!ed&&!del)return;
  var p=(ed||del).getAttribute(ed?'data-pk-ed':'data-pk-del').split(':');
  var key=p[0],i=parseInt(p[1],10);
  var set=key==='pop'?popRows:key==='prod'?prodRows:luasRows;
  var panelId=key==='pop'?'pk-pop-panel':key==='prod'?'pk-prod-panel':'pk-luas-panel';
  if(ed){
    var r=set[i];
    if(key==='pop'){
      popEditIndex=i;popEditId=r.id;
      document.getElementById('pop-uraian').value=r.uraian;
      document.getElementById('pop-2019').value=r.y2019;
      document.getElementById('pop-2020').value=r.y2020;
      document.getElementById('pop-2021').value=r.y2021;
      document.getElementById('pop-2022').value=r.y2022;
      document.getElementById('pop-2023').value=r.y2023;
      document.getElementById('pop-trend').value=r.trend||'';
    }else if(key==='prod'){
      prodEditIndex=i;prodEditId=r.id;
      document.getElementById('prod-uraian').value=r.uraian;
      document.getElementById('prod-2019').value=r.y2019;
      document.getElementById('prod-2020').value=r.y2020;
      document.getElementById('prod-2021').value=r.y2021;
      document.getElementById('prod-2022').value=r.y2022;
      document.getElementById('prod-2023').value=r.y2023;
    }else{
      luasEditIndex=i;luasEditId=r.id;
      document.getElementById('luas-uraian').value=r.uraian;
      document.getElementById('luas-2019').value=r.y2019;
      document.getElementById('luas-2020').value=r.y2020;
      document.getElementById('luas-2021').value=r.y2021;
      document.getElementById('luas-2022').value=r.y2022;
      document.getElementById('luas-2023').value=r.y2023;
    }
    document.getElementById(panelId).style.display='block';
  }else{
    var id=set[i]?.id;
    Utils.confirm('Hapus baris ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(async function(yes){
      if(!yes)return;
      try{
        var url = key==='pop'?('/perkebunan/pop/'+id):(key==='prod'?('/perkebunan/prod/'+id):('/perkebunan/luas/'+id));
        var res=await fetch(url,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrfToken}});
        if(res.ok){
          await fetchRows(key);
          Utils.showToast('Baris dihapus','success');
        }else{
          Utils.showToast('Gagal menghapus','error');
        }
      }catch(e){
        Utils.showToast('Gagal menghapus','error');
      }
    });
  }
});
function exportCsv(filename, headers, rows){var csv=[headers].concat(rows).map(function(row){return row.map(function(v){var s=(''+(v==null?'':v)).replace(/"/g,'""');return '"'+s+'"';}).join(',');}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download=filename;document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url);} 
document.getElementById('pk-pop-export')?.addEventListener('click',function(){var headers=["No","Uraian","2019","2020","2021","2022","2023","Trend (%)"];var rows=popRows.map(function(r){return [r.no,r.uraian,r.y2019,r.y2020,r.y2021,r.y2022,r.y2023,r.trend];});exportCsv('perkebunan-populasi.csv',headers,rows);});
document.getElementById('pk-prod-export')?.addEventListener('click',function(){var headers=["No","Komoditas","2019","2020","2021","2022","2023"];var rows=prodRows.map(function(r){return [r.no,r.uraian,r.y2019,r.y2020,r.y2021,r.y2022,r.y2023];});exportCsv('perkebunan-produksi.csv',headers,rows);});
document.getElementById('pk-luas-export')?.addEventListener('click',function(){var headers=["No","Komoditas","2019","2020","2021","2022","2023"];var rows=luasRows.map(function(r){return [r.no,r.uraian,r.y2019,r.y2020,r.y2021,r.y2022,r.y2023];});exportCsv('perkebunan-luas.csv',headers,rows);});
</script>
@endpush
