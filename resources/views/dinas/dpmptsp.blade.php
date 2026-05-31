@extends('layouts.app')
@section('title', 'DPMPTSP')
@section('body-class', 'dashboard-page force-light')

@section('content')
    <div class="page active" id="dpmptsp-page" data-opd-name="{{ optional(optional(auth()->user())->dinas)->nama_dinas ?? 'DPMPTSP' }}">
      <div class="page-header"><h1>Data DPMPTSP</h1><p>Realisasi Investasi PMDN & PMA Tahun 2025–2029</p></div>
      <div class="card dpm-card">
        <div class="card-header dpm-header">
          <div class="head-left">
            <h3>Tabel Realisasi Investasi 2025–2029</h3>
            <div class="sub">Data perkembangan PMDN & PMA</div>
          </div>
          <div class="head-actions"><button class="btn btn-outline btn-sm" id="dpm-export"><i class="fas fa-download"></i> Export</button><button class="btn btn-primary btn-sm" id="dpm-add"><i class="fas fa-plus"></i> Ajukan Data</button></div>
        </div>
        <div class="card-body">
          <div class="table-wrap">
            <table class="table table-compact dpm-table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Indikator</th>
                  <th>Tipe</th>
                  <th>2025</th>
                  <th>2026</th>
                  <th>2027</th>
                  <th>2028</th>
                  <th>2029</th>
                  <th>Status</th>
                  <th class="col-actions">Aksi</th>
                </tr>
              </thead>
              <tbody id="dpm-tbody"></tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="modal-overlay" id="dpm-modal" style="display:none;">
        <div class="modal dpm-modal">
          <div class="modal-header"><h3>Ajukan Data DPMPTSP</h3><button class="btn btn-outline btn-sm" id="dpm-close">✕</button></div>
          <div class="modal-body">
            <div class="tip-bar">💡 <span>Anda bisa menambahkan lebih dari 1 indikator. Klik "Tambah Row".</span></div>
            <div id="dpm-rows"></div>
            <div class="form-actions" style="margin-top:10px"><button class="btn btn-outline btn-sm" id="dpm-add-row">Tambah Row Baru</button></div>
          </div>
          <div class="modal-footer"><div class="hint" id="dpm-save-hint">Akan menyimpan 0 data</div><button class="btn btn-secondary" id="dpm-cancel">Batal</button><button class="btn btn-primary" id="dpm-save">Simpan Semua (0 data)</button></div>
        </div>
      </div>
    </div>
@endsection

@push('styles')
<style>
  .dpm-card{border-radius:20px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.06);border:1px solid #e2e8f0;background:#fff}
  .dpm-header{padding:20px 24px;display:flex;align-items:center;justify-content:space-between;background:linear-gradient(135deg,#1d4ed8,#3b82f6);color:#fff}
  .dpm-header h3{color:#fff;font-size:1.1rem;font-weight:700;margin:0}
  .dpm-header .sub{font-size:13px;opacity:.85;color:#fff;margin-top:4px}
  .dpm-table{width:100%;border-collapse:separate;border-spacing:0;border-radius:12px;overflow:hidden}
  .dpm-table thead th{background:linear-gradient(135deg,#eff6ff,#dbeafe);color:#1e3a8a;font-weight:700;font-size:13px;border-bottom:2px solid #bfdbfe;padding:13px 16px}
  .dpm-table th,.dpm-table td{border:1px solid #cbd5e1;border-right:1px solid #f1f5f9;padding:12px 16px}
  .dpm-table thead tr th:first-child{border-left:none}
  .dpm-table tbody tr td:first-child{border-left:none}
  .dpm-table tbody tr{transition:background 0.15s ease}
  .dpm-table tbody tr:hover{background:#f0f9ff}
  .dpm-table td:last-child{text-align:center;white-space:nowrap}
  .col-actions{width:100px}
  @media (max-width: 640px){.col-actions{width:auto !important}.dpm-table td:last-child{white-space:normal !important}}
  #dpmptsp-page .dpm-table .row-actions{display:flex;align-items:center;justify-content:center;gap:8px;margin-top:0}
  .status-badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600}
  .status-approved{background:#dcfce7;color:#166534}
  .status-rejected{background:#fee2e2;color:#7f1d1d}
  .status-menunggu{background:#fffbeb;color:#92400e}
  .chip{display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:8px;font-size:12px;font-weight:600}
  .chip.pmdn{background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff}
  .chip.pma{background:linear-gradient(135deg,#ec4899,#f43f5e);color:#fff}
  .action-btn{background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;color:#374151;transition:all .2s}
  .action-btn:hover{background:#eff6ff;border-color:#93c5fd;color:#2563eb}
  #dpmptsp-page .action-btn{background:transparent;border:none;box-shadow:none;width:auto;height:auto;padding:0}
  #dpmptsp-page .action-btn .fa-pen{color:#3b82f6}
  .dpm-header .head-actions{display:flex;gap:10px}
  .dpm-header .head-actions .btn{border-radius:10px;height:36px;padding:0 14px;font-weight:600;font-size:13px}
  .dpm-header .btn-outline{background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.4);backdrop-filter:blur(4px);transition:all .2s}
  .dpm-header .btn-outline:hover{background:rgba(255,255,255,0.25)}
  .dpm-header .btn-primary{background:#fff;color:#1d4ed8;border:none;font-weight:700;transition:all .2s}
  .dpm-header .btn-primary:hover{box-shadow:0 4px 12px rgba(255,255,255,0.3);transform:translateY(-1px)}
  .dpm-modal{max-width:720px;border-radius:20px}
  .tip-bar{display:flex;align-items:center;gap:8px;padding:12px 14px;background:#ecfeff;color:#0e7490;border:1px solid #a5f3fc;border-radius:10px;margin-bottom:14px;font-size:13px}
  .row-card{border:1px solid #e2e8f0;background:#f8fafc;border-radius:12px;padding:16px;margin-bottom:12px}
  .row-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px}
  .row-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:8px}
  .form-row{display:flex;flex-direction:column;gap:6px}
  .form-row label{font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.04em}
  .form-control{border:1.5px solid #e2e8f0;border-radius:10px;padding:9px 12px;font-size:14px;transition:all .2s;background:#fff}
  .form-control:focus{border-color:#3b82f6;box-shadow:0 0 0 4px rgba(59,130,246,0.12);outline:none}
  .year-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:8px}
  .row-actions{display:flex;gap:8px;justify-content:flex-end;margin-top:8px}
  .hint{margin-right:auto;color:#64748b;font-size:13px}
  .table-wrap{border-radius:14px;overflow:hidden;border:1px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,0.04)}
  .modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.4);backdrop-filter:blur(4px);display:flex;align-items:center;justify-content:center;z-index:1000}
  .modal{background:#fff;border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,0.2);max-height:90vh;overflow-y:auto;width:min(720px,95vw)}
  .modal-header{display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border:1px solid #cbd5e1}
  .modal-header h3{font-size:1.1rem;font-weight:700;color:#0f172a;margin:0}
  .modal-body{padding:20px 24px}
  .modal-footer{display:flex;align-items:center;padding:16px 24px;border-top:1px solid #f1f5f9;gap:10px}
  .btn-secondary{background:#f1f5f9;color:#374151;border:1px solid #e2e8f0;border-radius:10px;font-weight:600;transition:all .2s}
  .btn-secondary:hover{background:#e2e8f0}
  .btn-primary{background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff;border:none;border-radius:10px;font-weight:600;transition:all .2s}
  .btn-primary:hover{box-shadow:0 4px 14px rgba(37,99,235,0.3);transform:translateY(-1px)}
</style>
@endpush

@push('scripts')
<script>
  var _csrfEl=document.querySelector('meta[name="csrf-token"]');
  var csrfToken=_csrfEl?_csrfEl.content:'';
  var opdName=document.getElementById('dpmptsp-page').dataset.opdName||'DPMPTSP';
  var dinasId=(document.body.dataset.dinasId||'')||null;
  var tableKey='dpmptsp_investasi';
  var dpmRows=[];
  var dpmSaving=false;
  var dmStatuses={};
  function statusLabel(s){if(s==='Disetujui')return 'Disetujui';if(s==='Ditolak')return 'Ditolak';if(s==='Menunggu Persetujuan')return 'Menunggu Persetujuan';return '-'}
  function statusClass(s){if(s==='Disetujui')return 'status-approved';if(s==='Ditolak')return 'status-rejected';if(s==='Menunggu Persetujuan')return 'status-menunggu';return ''}
  async function fetchDmStatuses(){try{var res=await fetch('/data-management/records',{headers:{'Accept':'application/json'},credentials:'same-origin'});var data=await res.json();dmStatuses={};(Array.isArray(data)?data:[]).forEach(function(rec){if(rec.opd===opdName){dmStatuses[rec.name]=rec.status;}});}catch(_){dmStatuses={}}}
  function chipType(t){var cls=t==='PMDN'?'pmdn':'pma';return '<span class="chip '+cls+'">'+t+'</span>'}
  function renderTable(){var tb=document.getElementById('dpm-tbody');if(!tb)return;tb.innerHTML=dpmRows.map(function(r,i){var st=dmStatuses[r.indikator];var canEdit=(window.USER_ROLE!=='user'||st==='Menunggu Persetujuan');var actions=canEdit?((window.USER_ROLE==='user')?('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-edit="'+i+'"><i class="fas fa-pen"></i></button></div>'):('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-edit="'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-del="'+i+'"><i class="fas fa-trash"></i></button></div>')):'<div class="row-actions"></div>';return '<tr><td>'+(i+1)+'</td><td>'+r.indikator+'</td><td>'+chipType(r.tipe||'-')+'</td><td>'+(r.y2025||'-')+'</td><td>'+(r.y2026||'-')+'</td><td>'+(r.y2027||'-')+'</td><td>'+(r.y2028||'-')+'</td><td>'+(r.y2029||'-')+'</td><td class="c-status"><span class="status-badge '+statusClass(st)+'">'+statusLabel(st)+'</span></td><td>'+actions+'</td></tr>'}).join('')}
  function mapRows(data){return (Array.isArray(data)?data:[]).map(function(r){var v=r.values||{};return {id:r.id, indikator:r.uraian, tipe:v.tipe||'', y2025:v.y2025||'', y2026:v.y2026||'', y2027:v.y2027||'', y2028:v.y2028||'', y2029:v.y2029||''};});}
  async function fetchRows(){try{var url='/dpmptsp/rows';var res=await fetch(url,{headers:{'Accept':'application/json'},credentials:'same-origin'});var data=await res.json();dpmRows=mapRows(data);await fetchDmStatuses();renderTable();}catch(_){dpmRows=[];await fetchDmStatuses();renderTable();}}
  document.addEventListener('DOMContentLoaded',function(){fetchRows();});
  function rowTemplate(idx,data){data=data||{indikator:'',tipe:'PMDN',y2025:'',y2026:'',y2027:'',y2028:'',y2029:''};return '<div class="row-card" data-row="'+idx+'"><div class="row-head"><div>Row #'+(idx+1)+'</div><button class="btn btn-outline btn-sm" data-remove="'+idx+'">Hapus</button></div><div class="form-row"><label>Indikator *</label><input type="text" class="form-control" data-key="indikator" value="'+(data.indikator||'')+'" placeholder="Contoh: Jumlah investor Berskala"></div><div class="form-row"><label>Tipe Investasi *</label><select class="form-control" data-key="tipe"><option value="PMDN" '+(data.tipe==='PMDN'?'selected':'')+'>PMDN</option><option value="PMA" '+(data.tipe==='PMA'?'selected':'')+'>PMA</option></select></div><div class="year-grid"><div class="form-row"><label>2025</label><input type="text" class="form-control" data-key="y2025" value="'+(data.y2025||'')+'"></div><div class="form-row"><label>2026</label><input type="text" class="form-control" data-key="y2026" value="'+(data.y2026||'')+'"></div><div class="form-row"><label>2027</label><input type="text" class="form-control" data-key="y2027" value="'+(data.y2027||'')+'"></div><div class="form-row"><label>2028</label><input type="text" class="form-control" data-key="y2028" value="'+(data.y2028||'')+'"></div><div class="form-row"><label>2029</label><input type="text" class="form-control" data-key="y2029" value="'+(data.y2029||'')+'"></div></div></div>'}
  function openModal(prefill,editIdx){var m=document.getElementById('dpm-modal');m.style.display='flex';var rid='';if(editIdx!=null){var r=dpmRows[editIdx];rid=r&&r.id?r.id:'';}m.dataset.editId=rid;var wrap=document.getElementById('dpm-rows');wrap.innerHTML='';var rows=prefill&&prefill.length?prefill:[{}];rows.forEach(function(d,i){wrap.insertAdjacentHTML('beforeend',rowTemplate(i,d))});updateSaveCount();}
  function closeModal(){var m=document.getElementById('dpm-modal');m.style.display='none';m.dataset.editId='';}
  function addRow(){var wrap=document.getElementById('dpm-rows');var idx=wrap.children.length;wrap.insertAdjacentHTML('beforeend',rowTemplate(idx,{}));updateSaveCount()}
  function updateSaveCount(){var c=document.getElementById('dpm-rows').children.length;document.getElementById('dpm-save').textContent='Simpan Semua ('+c+' data)';document.getElementById('dpm-save-hint').textContent='Akan menyimpan '+c+' data'}
  (function(){var el=document.getElementById('dpm-add');if(el)el.addEventListener('click',function(){openModal(null,null)});})();
  (function(){var el=document.getElementById('dpm-cancel');if(el)el.addEventListener('click',function(){closeModal()});})();
  (function(){var el=document.getElementById('dpm-close');if(el)el.addEventListener('click',function(){closeModal()});})();
  (function(){var el=document.getElementById('dpm-add-row');if(el)el.addEventListener('click',function(){addRow()});})();
  (function(){var el=document.getElementById('dpm-rows');if(el)el.addEventListener('click',function(e){var btn=e.target.closest('button');if(!btn)return;if(btn.dataset.remove!=null){var idx=parseInt(btn.dataset.remove,10);var wrap=document.getElementById('dpm-rows');if(wrap.children[idx]){Utils.confirm('Hapus baris formulir ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(function(yes){if(!yes)return;wrap.children[idx].remove();updateSaveCount();Utils.showToast('Baris formulir dihapus','success');});}}});})();
  (function(){var el=document.getElementById('dpm-tbody');if(el)el.addEventListener('click',function(e){var b=e.target.closest('button');if(!b)return;if(b.dataset.edit){var i=parseInt(b.dataset.edit,10);openModal([{indikator:dpmRows[i].indikator,tipe:dpmRows[i].tipe,y2025:dpmRows[i].y2025,y2026:dpmRows[i].y2026,y2027:dpmRows[i].y2027,y2028:dpmRows[i].y2028,y2029:dpmRows[i].y2029}],i)}else if(b.dataset.del){var i=parseInt(b.dataset.del,10);var id=(dpmRows[i]&&dpmRows[i].id)?dpmRows[i].id:null;Utils.confirm('Hapus data DPMPTSP ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(async function(yes){if(!yes)return;try{var res=await fetch('/dpmptsp/rows/'+id,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrfToken},credentials:'same-origin'});if(res.ok){await fetchRows();Utils.showToast('Data DPMPTSP dihapus','success');}else{Utils.showToast('Gagal menghapus','error');}}catch(e){Utils.showToast('Gagal menghapus','error');}})}});})();
  async function submitDM(ura,tipe,year){var did=(dinasId&&/^\d+$/.test(String(dinasId)))?parseInt(dinasId,10):null;var payload={opd:opdName,judul_data:ura,deskripsi:tipe?('Tipe: '+tipe):null,file_path:'dpmptsp_rows',tahun_perencanaan:year};if(did)payload.dinas_id=did;var ok=false;try{var res=await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify(payload)});ok=!!res.ok;}catch(_){ok=false}try{var key='sipandu_dm_records';var cur=[];try{cur=JSON.parse(localStorage.getItem(key))||[]}catch(__){cur=[]}var _picEl=document.getElementById('user-name');var _pic=_picEl?_picEl.textContent:'';cur.push({id:'local-'+Date.now(),opd:opdName,name:ura,period:year,status:'Menunggu Persetujuan',pic:_pic});localStorage.setItem(key,JSON.stringify(cur));}catch(___){}if(ok){Utils.showToast('Dikirim ke Data Management','success');}else{Utils.showToast('Gagal mengirim ke Data Management','error');}}
  (function(){var el=document.getElementById('dpm-save');if(!el)return;el.addEventListener('click',async function(){
    if(dpmSaving) return;
    dpmSaving=true;
    var btnSave=document.getElementById('dpm-save');
    var btnCancel=document.getElementById('dpm-cancel');
    if(btnSave){btnSave.disabled=true;btnSave.textContent='Menyimpan...';}
    if(btnCancel){btnCancel.disabled=true;}
    var wrap=document.getElementById('dpm-rows');
    var toSave=[];
    Array.from(wrap.children).forEach(function(card){var obj={};card.querySelectorAll('[data-key]').forEach(function(inp){var k=inp.dataset.key;obj[k]=inp.value.trim()});if(obj.indikator&&obj.tipe)toSave.push(obj)});
    // Hilangkan duplikasi indikator+tipe dalam sekali simpan
    var seen=new Set();
    toSave=toSave.filter(function(it){var key=(it.indikator||'')+'|'+(it.tipe||'');if(seen.has(key)) return false; seen.add(key); return true;});
    var editId=document.getElementById('dpm-modal').dataset.editId||'';
    try{
      if(editId){
        var item=toSave[0];
        var payload={uraian:item.indikator,values:{tipe:item.tipe,y2025:item.y2025,y2026:item.y2026,y2027:item.y2027,y2028:item.y2028,y2029:item.y2029}};
        var res=await fetch('/dpmptsp/rows/'+editId,{method:'PUT',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify(payload)});
        if(!res.ok){Utils.showToast('Gagal menyimpan','error');}
        var _yrRaw=(item.y2029||'').replace(/[^0-9]/g,'');
        var year=(_yrRaw&&_yrRaw.length===4)?_yrRaw:new Date().getFullYear().toString();
        await submitDM(item.indikator,item.tipe,year);
      }else{
        for(var i=0;i<toSave.length;i++){
          var it=toSave[i];
          var payload={uraian:it.indikator,values:{tipe:it.tipe,y2025:it.y2025,y2026:it.y2026,y2027:it.y2027,y2028:it.y2028,y2029:it.y2029}};
          var res=await fetch('/dpmptsp/rows',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify(payload)});
          var _yrRaw=(it.y2029||'').replace(/[^0-9]/g,'');
          var year=(_yrRaw&&_yrRaw.length===4)?_yrRaw:new Date().getFullYear().toString();
          await submitDM(it.indikator,it.tipe,year);
        }
      }
      await fetchRows();
      closeModal();
      Utils.showToast('Data disimpan','success');
    }catch(e){
      Utils.showToast('Gagal menyimpan','error');
    }finally{
      dpmSaving=false;
      if(btnSave){btnSave.disabled=false;updateSaveCount();}
      if(btnCancel){btnCancel.disabled=false;}
    }
  });})();
  (function(){var el=document.getElementById('dpm-export');if(!el)return;el.addEventListener('click',function(){var rows=[["No","Indikator","Tipe","2025","2026","2027","2028","2029"]];dpmRows.forEach(function(r,i){rows.push([i+1,r.indikator,r.tipe,r.y2025,r.y2026,r.y2027,r.y2028,r.y2029])});var csv=rows.map(function(x){return x.map(function(v){var s=(''+(v==null?'':v)).replace(/\"/g,'\"\"');return '\"'+s+'\"'}).join(',')}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download='dpmptsp-investasi.csv';document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url)});})();
</script>
@endpush

