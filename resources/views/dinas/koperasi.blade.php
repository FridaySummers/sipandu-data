@extends('layouts.app')
@section('title', 'Koperasi')
@section('body-class', 'dashboard-page force-light')
@push('styles')
<style>
.kop-card{border-radius:20px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.06);border:1px solid #e2e8f0;background:#fff}
.kop-card .card-header{background:linear-gradient(135deg,#1d4ed8,#3b82f6);color:#ffffff;padding:20px 24px;display:flex;align-items:center;justify-content:space-between}
.kop-card .card-header h3{color:#fff;font-size:1.1rem;font-weight:700;margin:0}
.kop-card .card-header .sub{font-size:13px;opacity:.85;color:#fff;margin-top:4px}
.kop-card .card-actions .btn{background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.4);color:#fff;border-radius:10px;height:36px;padding:0 14px;font-weight:600;font-size:13px;transition:all .2s}
.kop-card .card-actions .btn:hover{background:rgba(255,255,255,0.25)}
.kop-table{width:100%;border-collapse:separate;border-spacing:0;border-radius:12px;overflow:hidden;table-layout:fixed}
.kop-table thead th{background:linear-gradient(135deg,#eff6ff,#dbeafe);color:#1e3a8a;font-weight:700;font-size:13px;border-bottom:2px solid #bfdbfe;padding:13px 16px}
.kop-table th,.kop-table td{border:1px solid #cbd5e1;border-right:1px solid #f1f5f9;padding:12px 16px;word-break:break-word;white-space:normal}
.kop-table tr#kop-addrow td{background:#eff6ff}
.kop-table tr#kop-addrow input{border:1.5px solid #bfdbfe;width:100%;box-sizing:border-box;padding:8px 10px;border-radius:8px;transition:all .2s}
.kop-table tr#kop-addrow input:focus{border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,0.12);outline:none}
.kop-table thead tr:first-child th{border-top:none}
.kop-table thead tr th:first-child{border-left:none}
.kop-table tbody tr td:first-child{border-left:none}
.kop-table th,.kop-table td{vertical-align:middle}
.kop-table th:last-child,.kop-table td:last-child{text-align:center}
.kop-table td:last-child{display:table-cell !important;text-align:center !important;white-space:nowrap}
.kop-table tbody tr{transition:background .15s ease}
.kop-table tbody tr:hover{background:#f0f9ff}
.kop-inline-tip{margin:16px 0;background:#eff6ff;border:1px solid #bfdbfe;color:#1e3a8a;border-radius:12px;padding:14px 16px;font-size:13px}
.kop-inline-actions{display:flex;gap:8px}
.btn-kop-save{background:linear-gradient(135deg,#16a34a,#15803d);border:none;color:#fff;border-radius:8px;font-weight:600;transition:all .2s}
.btn-kop-save:hover{box-shadow:0 4px 12px rgba(22,163,74,0.3);transform:translateY(-1px)}
.btn-kop-cancel{background:#f1f5f9;color:#374151;border:1px solid #e2e8f0;border-radius:8px;font-weight:600;transition:all .2s}
.btn-kop-cancel:hover{background:#e2e8f0}
.edit-input{border:1.5px solid #bfdbfe;background:#ffffff;border-radius:8px;padding:7px 10px;transition:all .2s;width:100%;box-sizing:border-box}
.edit-input:focus{border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,0.12);outline:none}
.row-actions .fa-pen{color:#3b82f6}
.status-badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600}
.status-approved{background:#dcfce7;color:#166534}
.status-rejected{background:#fee2e2;color:#7f1d1d}
.status-menunggu{background:#fffbeb;color:#92400e}
.table-wrap{border-radius:14px;overflow:hidden;border:1px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,0.04)}
#koperasi-page .kop-table thead th:last-child{width:100px !important}
@media (max-width: 640px){#koperasi-page .kop-table thead th:last-child{width:auto !important}#koperasi-page .kop-table td:last-child{white-space:normal !important}}
#kop-toggle{transition:all .2s ease}
#kop-toggle:hover{filter:brightness(0.92);transform:translateY(-1px)}
.btn-kop-save[disabled]{opacity:.6;cursor:not-allowed}
.btn-primary{background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff;border:none;border-radius:10px;font-weight:600;transition:all .2s}
.btn-primary:hover{box-shadow:0 4px 14px rgba(37,99,235,0.3);transform:translateY(-1px)}
.btn-outline{background:#fff;border:1.5px solid #e2e8f0;color:#374151;border-radius:10px;font-weight:600;transition:all .2s}
.btn-outline:hover{background:#f8fafc;border-color:#93c5fd;color:#2563eb}
</style>
@endpush

@section('content')
    <div class="page active" id="koperasi-page" data-opd-name="{{ optional(optional(auth()->user())->dinas)->nama_dinas ?? 'Dinas Koperasi dan UKM' }}">
      <div class="page-header"><h1>Perkembangan Perkoperasian</h1><p>Tahun 2025-2029 di Kabupaten Kolaka Utara</p></div>
      <div class="card kop-card">
        <div class="card-header"><div><h3>Data Koperasi</h3><div class="sub">Perkembangan koperasi tahun 2025–2029</div></div><div class="card-actions"><button class="btn btn-primary btn-sm" id="kop-export"><i class="fas fa-download"></i> Export Data</button> <button class="btn btn-primary btn-sm" id="kop-toggle"><i class="fas fa-plus"></i> Ajukan Data</button></div></div>
        <div class="card-body">
          <div class="table-wrap">
            <table class="table table-compact kop-table">
              <thead>
                <tr>
                  <th rowspan="2">No</th>
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
              <tbody id="kop-tbody"></tbody>
            </table>
          </div>
          <div class="kop-inline-tip">Tips: Klik tombol "Edit" untuk mengubah data, atau "Ajukan Data" untuk menambah data baru secara inline.</div>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
var csrfToken=document.querySelector('meta[name="csrf-token"]')?.content||'';window.USER_ROLE=document.body.dataset.userRole||'';
var opdName=document.getElementById('koperasi-page').dataset.opdName||'Dinas Koperasi dan UKM';var tableKey='koperasi_perkembangan';
var dinasId=(document.body.dataset.dinasId||'')||null;
var kopRows=[];
var dmStatuses={};function statusLabel(s){if(s==='Disetujui')return 'Disetujui';if(s==='Ditolak')return 'Ditolak';if(s==='Menunggu Persetujuan')return 'Menunggu Persetujuan';return '-'}function statusClass(s){if(s==='Disetujui')return 'status-approved';if(s==='Ditolak')return 'status-rejected';if(s==='Menunggu Persetujuan')return 'status-menunggu';return ''}
async function fetchDmStatuses(){try{var res=await fetch('/data-management/records',{headers:{'Accept':'application/json'},credentials:'same-origin'});var data=await res.json();dmStatuses={};(Array.isArray(data)?data:[]).forEach(function(rec){if(rec.opd===opdName){dmStatuses[rec.name]=rec.status;}});}catch(_){dmStatuses={}}}
function renderKop(){var tb=document.getElementById('kop-tbody');if(!tb)return;var addRow='<tr id="kop-addrow" class="add-row" style="display:none;"><td>+</td><td><input class="edit-input" id="ka-uraian" placeholder="Uraian baru..."></td><td><input class="edit-input" id="ka-2025" placeholder="0"></td><td><input class="edit-input" id="ka-2026" placeholder="0"></td><td><input class="edit-input" id="ka-2027" placeholder="0"></td><td><input class="edit-input" id="ka-2028" placeholder="0"></td><td><input class="edit-input" id="ka-2029" placeholder="0"></td><td class="kop-inline-actions"><button class="btn btn-kop-save" id="ka-save"><i class="fas fa-floppy-disk"></i></button><button class="btn btn-kop-cancel" id="ka-cancel">Batal</button></td></tr>';var rows=kopRows.map(function(r,idx){var v=r.values||{};var st=dmStatuses[r.uraian];var actions=(window.USER_ROLE==='user')?('<div class="row-actions"><button class="btn btn-outline btn-sm edit-btn action-btn" data-ed="'+idx+'"><i class="fas fa-pen"></i></button></div>'):('<div class="row-actions"><button class="btn btn-outline btn-sm edit-btn action-btn" data-ed="'+idx+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-del="'+idx+'"><i class="fas fa-trash"></i></button></div>');return '<tr data-row="'+(idx+1)+'"><td>'+ (idx+1) +'</td><td class="c-uraian">'+r.uraian+'</td><td class="c-2025">'+(v.y2025||'-')+'</td><td class="c-2026">'+(v.y2026||'-')+'</td><td class="c-2027">'+(v.y2027||'-')+'</td><td class="c-2028">'+(v.y2028||'-')+'</td><td class="c-2029">'+(v.y2029||'-')+'</td><td class="c-status"><span class="status-badge '+statusClass(st)+'">'+statusLabel(st)+'</span></td><td>'+actions+'</td></tr>';}).join('');tb.innerHTML=addRow+rows;}
async function fetchRows(){try{var res=await fetch('/koperasi/rows',{headers:{'Accept':'application/json'},credentials:'same-origin'});var data=await res.json();kopRows=(Array.isArray(data)?data:[]);renderKop();}catch(_){kopRows=[];renderKop();}}
document.addEventListener('DOMContentLoaded',async function(){await fetchRows();await fetchDmStatuses();renderKop();});
var toggleBtn=document.getElementById('kop-toggle');
toggleBtn?.addEventListener('click',function(){var addRowEl=document.getElementById('kop-addrow');if(!addRowEl){renderKop();addRowEl=document.getElementById('kop-addrow');}
  var isHidden=(addRowEl.style.display==='none' || getComputedStyle(addRowEl).display==='none');
  addRowEl.style.display=isHidden?'table-row':'none';
  toggleBtn.innerHTML=isHidden?'Tutup Form':'<i class="fas fa-plus"></i> Ajukan Data';});
function exportCsv(filename, headers, rows){var csv=[headers].concat(rows).map(function(row){return row.map(function(v){var s=(''+(v==null?'':v)).replace(/"/g,'""');return '"'+s+'"';}).join(',');}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download=filename;document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url);} 
  document.getElementById('kop-export')?.addEventListener('click',function(){var h=['No','Uraian','2025','2026','2027','2028','2029','Status'];var rows=kopRows.map(function(r,i){var v=r.values||{};var st=dmStatuses[r.uraian];return [i+1,r.uraian,v.y2025,v.y2026,v.y2027,v.y2028,v.y2029,statusLabel(st)]});exportCsv('koperasi.csv',h,rows)});
document.getElementById('kop-tbody')?.addEventListener('click',function(e){var btn=e.target.closest('button');if(!btn)return;if(btn.id==='ka-save'){var ura=document.getElementById('ka-uraian').value.trim();if(!ura){Utils.showToast('Isi Uraian','error');return;}var vals={y2025:document.getElementById('ka-2025').value.trim(),y2026:document.getElementById('ka-2026').value.trim(),y2027:document.getElementById('ka-2027').value.trim(),y2028:document.getElementById('ka-2028').value.trim(),y2029:document.getElementById('ka-2029').value.trim()};var hasVal=(vals.y2025||vals.y2026||vals.y2027||vals.y2028||vals.y2029);if(!hasVal){Utils.showToast('Isi minimal salah satu nilai tahun','error');return;}var isUser=(window.USER_ROLE==='user');(async function(){try{if(!isUser){var res=await fetch('/opd/rows',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify({opd:opdName,key:tableKey,uraian:ura,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows();}var _yrRaw=(vals.y2029||'').replace(/[^0-9]/g,'');var year=(_yrRaw&&_yrRaw.length===4)?_yrRaw:new Date().getFullYear().toString();var did=(dinasId&&/^\d+$/.test(String(dinasId)))?parseInt(dinasId,10):null;var dmPayload={opd:opdName,judul_data:ura,deskripsi:null,file_path:'koperasi_inline',tahun_perencanaan:year};if(did)dmPayload.dinas_id=did;await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify(dmPayload)});dmStatuses[ura]='Menunggu Persetujuan';await fetchDmStatuses();var addRowEl=document.getElementById('kop-addrow');if(addRowEl){addRowEl.style.display='none';}var t=document.getElementById('kop-toggle');if(t){t.innerHTML='<i class="fas fa-plus"></i> Ajukan Data';}Utils.showToast('Data disimpan','success');}catch(e){Utils.showToast('Gagal menyimpan','error');}})();return;}if(btn.id==='ka-cancel'){var addRowEl=document.getElementById('kop-addrow');if(addRowEl){addRowEl.style.display='none';}var t=document.getElementById('kop-toggle');if(t){t.innerHTML='<i class="fas fa-plus"></i> Ajukan Data';}return;}if(btn.classList.contains('edit-btn')){var tr=btn.closest('tr');var idx=parseInt(btn.getAttribute('data-ed'),10);var r=kopRows[idx];var v=r.values||{};tr.querySelector('.c-uraian').innerHTML='<input class="edit-input" value="'+r.uraian+'">';tr.querySelector('.c-2025').innerHTML='<input class="edit-input" value="'+(v.y2025||'')+'">';tr.querySelector('.c-2026').innerHTML='<input class="edit-input" value="'+(v.y2026||'')+'">';tr.querySelector('.c-2027').innerHTML='<input class="edit-input" value="'+(v.y2027||'')+'">';tr.querySelector('.c-2028').innerHTML='<input class="edit-input" value="'+(v.y2028||'')+'">';tr.querySelector('.c-2029').innerHTML='<input class="edit-input" value="'+(v.y2029||'')+'">';btn.textContent='Simpan';btn.classList.add('saving');btn.setAttribute('data-edx',idx);return;}if(btn.dataset.del!=null){var i=parseInt(btn.dataset.del,10);var id=kopRows[i]?.id;Utils.confirm('Hapus baris ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(async function(yes){if(!yes)return;try{var res=await fetch('/opd/rows/'+id,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrfToken},credentials:'same-origin'});if(res.ok){await fetchRows();Utils.showToast('Baris dihapus','success');}else{Utils.showToast('Gagal menghapus','error');}}catch(e){Utils.showToast('Gagal menghapus','error');}});}});
  document.getElementById('kop-tbody')?.addEventListener('click',function(e){var btn=e.target.closest('.saving');if(!btn)return;var tr=btn.closest('tr');var idx=parseInt(btn.getAttribute('data-edx'),10);var inputs=tr.querySelectorAll('input');var payload={uraian:inputs[0].value.trim(),values:{y2025:inputs[1].value.trim(),y2026:inputs[2].value.trim(),y2027:inputs[3].value.trim(),y2028:inputs[4].value.trim(),y2029:inputs[5].value.trim()}};(async function(){try{var id=kopRows[idx]?.id;var res=await fetch('/koperasi/rows/'+id,{method:'PUT',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify(payload)});if(res.ok){await fetchRows();Utils.showToast('Data diperbarui','success');}else{Utils.showToast('Gagal menyimpan','error');}}catch(e){Utils.showToast('Gagal menyimpan','error');}})();});
</script>
<script>
// Unify Koperasi CRUD handlers: Ajukan Data + Edit + Hapus
document.addEventListener('DOMContentLoaded',function(){
  var csrf=document.querySelector('meta[name="csrf-token"]')?.content||'';
  var role=(window.USER_ROLE||document.body.dataset.userRole||'');
  var opd=(document.getElementById('koperasi-page')?.dataset.opdName)||'Dinas Koperasi dan UKM';
  var dinasId=(document.body.dataset.dinasId||'')||null;
  var kopEditIndex=-1;
  function ensureAddRow(){var el=document.getElementById('kop-addrow');if(el)return;var tb=document.getElementById('kop-tbody');if(!tb)return;tb.insertAdjacentHTML('afterbegin','<tr id="kop-addrow" class="add-row" style="display:none;"><td>+</td><td><input class="edit-input" id="ka-uraian" placeholder="Uraian baru..."></td><td><input class="edit-input" id="ka-2025" placeholder="0"></td><td><input class="edit-input" id="ka-2026" placeholder="0"></td><td><input class="edit-input" id="ka-2027" placeholder="0"></td><td><input class="edit-input" id="ka-2028" placeholder="0"></td><td><input class="edit-input" id="ka-2029" placeholder="0"></td><td class="kop-inline-actions"><button class="btn btn-kop-save" id="ka-save"><i class=\"fas fa-floppy-disk\"></i></button><button class="btn btn-kop-cancel" id="ka-cancel">Batal</button></td></tr>');}
  var toggle=document.getElementById('kop-toggle');
  toggle?.addEventListener('click',function(){ensureAddRow();var row=document.getElementById('kop-addrow');if(!row)return;var hide=(row.style.display==='none'||getComputedStyle(row).display==='none');row.style.display=hide?'table-row':'none';if(!hide){kopEditIndex=-1;['ka-uraian','ka-2025','ka-2026','ka-2027','ka-2028','ka-2029'].forEach(function(id){var el=document.getElementById(id);if(el)el.value='';});}this.innerHTML=hide?'Tutup Form':'<i class="fas fa-plus"></i> Ajukan Data';});
  var tbody=document.getElementById('kop-tbody');
  tbody?.addEventListener('click',async function(e){
    var save=e.target.closest('#ka-save');var cancel=e.target.closest('#ka-cancel');var ed=e.target.closest('[data-ed]');var del=e.target.closest('[data-del]');
    if(save){e.stopImmediatePropagation();var ura=document.getElementById('ka-uraian').value.trim();if(!ura){Utils?.showToast&&Utils.showToast('Isi Uraian','error');return;}var vals={y2025:document.getElementById('ka-2025').value.trim(),y2026:document.getElementById('ka-2026').value.trim(),y2027:document.getElementById('ka-2027').value.trim(),y2028:document.getElementById('ka-2028').value.trim(),y2029:document.getElementById('ka-2029').value.trim()};var hasVal=(vals.y2025||vals.y2026||vals.y2027||vals.y2028||vals.y2029);if(!hasVal){Utils?.showToast&&Utils.showToast('Isi minimal salah satu nilai tahun','error');return;}var prev=save.innerHTML;save.disabled=true;save.innerHTML='<i class=\"fas fa-spinner fa-spin\"></i>';
      try{localStorage.setItem('kop_backup',JSON.stringify(kopRows||[]));if(role&&role!=='user'){if(kopEditIndex>=0){var id=kopRows[kopEditIndex]?.id;var res=await fetch('/koperasi/rows/'+id,{method:'PUT',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify({uraian:ura,values:vals})});if(!res.ok){throw new Error('x');}}else{var res=await fetch('/koperasi/rows',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify({uraian:ura,values:vals})});if(!res.ok){throw new Error('x');}}}
        var yrRaw=(vals.y2029||'').replace(/[^0-9]/g,'');var year=(yrRaw&&yrRaw.length===4)?yrRaw:(new Date()).getFullYear().toString();var did=(dinasId&&/^\d+$/.test(String(dinasId)))?parseInt(dinasId,10):null;var dmPayload={opd:opd,judul_data:ura,deskripsi:null,file_path:'koperasi_inline',tahun_perencanaan:year};if(did)dmPayload.dinas_id=did;await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify(dmPayload)});await fetchRows();await fetchDmStatuses();var row=document.getElementById('kop-addrow');if(row){row.style.display='none';}toggle&& (toggle.innerHTML='<i class="fas fa-plus"></i> Ajukan Data');Utils?.showToast&&Utils.showToast('Data disimpan','success');kopEditIndex=-1;
      }catch(err){Utils?.showToast&&Utils.showToast('Gagal menyimpan','error');try{var b=localStorage.getItem('kop_backup');if(b){kopRows=JSON.parse(b);renderKop();}}catch(__){}}
      finally{save.disabled=false;save.innerHTML=prev;}
      return;
    }
    if(cancel){e.stopImmediatePropagation();kopEditIndex=-1;var row=document.getElementById('kop-addrow');if(row){row.style.display='none';}toggle&& (toggle.innerHTML='<i class="fas fa-plus"></i> Ajukan Data');return;}
    if(ed){var idx=parseInt(ed.getAttribute('data-ed'),10);kopEditIndex=idx;ensureAddRow();var row=document.getElementById('kop-addrow');if(row){row.style.display='table-row';}var r=kopRows[idx]||{};var v=r.values||{};['ka-uraian','ka-2025','ka-2026','ka-2027','ka-2028','ka-2029'].forEach(function(id,i){var el=document.getElementById(id);if(!el)return;var vals=[r.uraian||'',v.y2025||'',v.y2026||'',v.y2027||'',v.y2028||'',v.y2029||''];el.value=vals[i];});toggle&& (toggle.innerHTML='Tutup Form');Utils?.showToast&&Utils.showToast('Mode edit','info');return;}
    if(del){var idx=parseInt(del.getAttribute('data-del'),10);var id=kopRows[idx]?.id;if(!id)return;var yes=false;try{yes=await Utils.confirm('Hapus baris ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'});}catch(_){yes=false;}if(!yes)return;try{localStorage.setItem('kop_backup',JSON.stringify(kopRows||[]));var res=await fetch('/koperasi/rows/'+id,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'},credentials:'same-origin'});if(res.ok){await fetchRows();await fetchDmStatuses();Utils?.showToast&&Utils.showToast('Baris dihapus','success');}else{Utils?.showToast&&Utils.showToast('Gagal menghapus','error');}}catch(__){Utils?.showToast&&Utils.showToast('Gagal menghapus','error');try{var b=localStorage.getItem('kop_backup');if(b){kopRows=JSON.parse(b);renderKop();}}catch(___){}}return;}
  },true);
});
</script>
<script>
(function(){
if(window.KOP_FIX_READY){return;}window.KOP_FIX_READY=true;
var csrfToken=document.querySelector('meta[name="csrf-token"]')?.content||'';
window.USER_ROLE=window.USER_ROLE||document.body.dataset.userRole||'';
var opdName=document.getElementById('koperasi-page').dataset.opdName||'Dinas Koperasi dan UKM';
window.OPD_NAME=opdName;
var tableKey='koperasi_perkembangan';
var dinasId=(document.body.dataset.dinasId||'')||null;
function ensureAddRow(){var el=document.getElementById('kop-addrow');if(el)return;var tb=document.getElementById('kop-tbody');if(!tb)return;tb.insertAdjacentHTML('afterbegin','<tr id="kop-addrow" class="add-row" style="display:none;"><td>+</td><td><input class="edit-input" id="ka-uraian" placeholder="Uraian baru..."></td><td><input class="edit-input" id="ka-2025" placeholder="0"></td><td><input class="edit-input" id="ka-2026" placeholder="0"></td><td><input class="edit-input" id="ka-2027" placeholder="0"></td><td><input class="edit-input" id="ka-2028" placeholder="0"></td><td><input class="edit-input" id="ka-2029" placeholder="0"></td><td class="kop-inline-actions"><button class="btn btn-kop-save" id="ka-save"><i class=\"fas fa-floppy-disk\"></i></button><button class="btn btn-kop-cancel" id="ka-cancel">Batal</button></td></tr>');}
var tgl=document.getElementById('kop-toggle');
tgl?.addEventListener('click',function(){ensureAddRow();var row=document.getElementById('kop-addrow');if(!row)return;var hide=(row.style.display==='none'||getComputedStyle(row).display==='none');row.style.display=hide?'table-row':'none';this.innerHTML=hide?'Tutup Form':'<i class=\"fas fa-plus\"></i> Ajukan Data';});
document.getElementById('kop-tbody')?.addEventListener('click',async function(e){var btn=e.target.closest('button');if(!btn)return;if(btn.id==='ka-save'){var ura=document.getElementById('ka-uraian').value.trim();if(!ura){Utils.showToast('Isi Uraian','error');return;}var vals={y2025:document.getElementById('ka-2025').value.trim(),y2026:document.getElementById('ka-2026').value.trim(),y2027:document.getElementById('ka-2027').value.trim(),y2028:document.getElementById('ka-2028').value.trim(),y2029:document.getElementById('ka-2029').value.trim()};var hasVal=(vals.y2025||vals.y2026||vals.y2027||vals.y2028||vals.y2029);if(!hasVal){Utils.showToast('Isi minimal salah satu nilai tahun','error');return;}try{var res=await fetch('/opd/rows',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify({opd:opdName,key:tableKey,uraian:ura,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}var _yrRaw=(vals.y2029||'').replace(/[^0-9]/g,'');var year=(_yrRaw&&_yrRaw.length===4)?_yrRaw:new Date().getFullYear().toString();var did=(dinasId&&/^\d+$/.test(String(dinasId)))?parseInt(dinasId,10):null;var dmPayload={opd:opdName,judul_data:ura,deskripsi:null,file_path:'koperasi_inline',tahun_perencanaan:year};if(did)dmPayload.dinas_id=did;await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify(dmPayload)});dmStatuses[ura]='Menunggu Persetujuan';await fetchRows();await fetchDmStatuses();var row=document.getElementById('kop-addrow');if(row){row.style.display='none';}var t=document.getElementById('kop-toggle');if(t){t.innerHTML='<i class=\"fas fa-plus\"></i> Ajukan Data';}Utils.showToast('Data disimpan','success');}catch(e){Utils.showToast('Gagal menyimpan','error');}}else if(btn.id==='ka-cancel'){var row=document.getElementById('kop-addrow');if(row){row.style.display='none';}var t=document.getElementById('kop-toggle');if(t){t.innerHTML='<i class=\"fas fa-plus\"></i> Ajukan Data';}}});
})();
</script>
<script>
// Override submit handler untuk admin agar langsung simpan ke tabel utama
document.addEventListener('DOMContentLoaded',function(){
  var role=(window.USER_ROLE||document.body.dataset.userRole||'');
  if(role&&role!=='user'){
    var tbody=document.getElementById('kop-tbody');
    if(tbody){
      tbody.addEventListener('click',async function(e){
        var btn=e.target.closest('#ka-save');
        if(!btn)return;
        e.stopImmediatePropagation();
        var csrf=document.querySelector('meta[name="csrf-token"]')?.content||'';
        var ura=document.getElementById('ka-uraian').value.trim();
        if(!ura){Utils?.showToast&&Utils.showToast('Isi Uraian','error');return;}
        var vals={y2025:document.getElementById('ka-2025').value.trim(),y2026:document.getElementById('ka-2026').value.trim(),y2027:document.getElementById('ka-2027').value.trim(),y2028:document.getElementById('ka-2028').value.trim(),y2029:document.getElementById('ka-2029').value.trim()};
        var hasVal=(vals.y2025||vals.y2026||vals.y2027||vals.y2028||vals.y2029);
        if(!hasVal){Utils?.showToast&&Utils.showToast('Isi minimal salah satu nilai tahun','error');return;}
        try{
          var res=await fetch('/koperasi/rows',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify({uraian:ura,values:vals})});
          if(!res.ok){Utils?.showToast&&Utils.showToast('Gagal menyimpan','error');return;}
          await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify({opd:window.OPD_NAME,judul_data:ura,deskripsi:null,file_path:'koperasi_inline',tahun_perencanaan:(new Date()).getFullYear().toString()})});
dmStatuses[ura]='Menunggu Persetujuan';
          await fetchRows();
          await fetchDmStatuses();
          var row=document.getElementById('kop-addrow');if(row){row.style.display='none';}
          var t=document.getElementById('kop-toggle');if(t){t.innerHTML='<i class="fas fa-plus"></i> Ajukan Data';}
          Utils?.showToast&&Utils.showToast('Data disimpan','success');
        }catch(_){Utils?.showToast&&Utils.showToast('Gagal menyimpan','error')}
      },true);
    }
  }
});
</script>
@endpush

