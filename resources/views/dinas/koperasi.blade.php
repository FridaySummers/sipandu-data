@extends('layouts.app')
@section('title', 'Koperasi')
@section('body-class', 'dashboard-page force-light')
@push('styles')
<style>
.kop-card{border-radius:16px;overflow:hidden}
.kop-card .card-header{background:linear-gradient(90deg,#60a5fa,#3b82f6);color:#ffffff;border-bottom:1px solid transparent;padding:16px 20px;display:flex;align-items:center;justify-content:space-between}
.kop-card .card-header .sub{font-size:13px;opacity:.9;color:#fff}
.kop-card .card-actions .btn{background:#fff;border-color:#fff;color:#111827;border-radius:8px;height:34px;padding:0 12px}
.kop-table{width:100%;border:1px solid #93c5fd;border-radius:12px;overflow:hidden;border-collapse:separate;border-spacing:0;table-layout:fixed}
.kop-table thead th{background:#dbeafe;color:#1e3a8a;font-weight:600}
.kop-table th,.kop-table td{border-bottom:1px solid #93c5fd;border-right:1px solid #93c5fd;padding:10px 12px}
.kop-table tr#kop-addrow td{background:#fff7ed}
.kop-table tr#kop-addrow input{border:1px solid #93c5fd;width:100%;box-sizing:border-box;padding:8px 10px;border-radius:10px}
.kop-table thead tr:first-child th{border-top:1px solid #93c5fd}
.kop-table thead tr th:first-child{border-left:1px solid #93c5fd}
.kop-table tbody tr td:first-child{border-left:1px solid #93c5fd}
.kop-table thead tr:nth-child(2) th{border-top:1px solid #93c5fd}
.kop-table th,.kop-table td{vertical-align:middle}
.kop-table th:last-child,.kop-table td:last-child{text-align:center}
.kop-table td:last-child{display:table-cell !important;text-align:center !important;white-space:nowrap}
.kop-table tbody tr:last-child td{border-bottom:1px solid #93c5fd}
.kop-table th:nth-child(1),.kop-table th:nth-child(2),.kop-table td:nth-child(1),.kop-table td:nth-child(2){text-align:left}
.kop-table th:not(:nth-child(1)):not(:nth-child(2)),.kop-table td:not(:nth-child(1)):not(:nth-child(2)){text-align:center}
.kop-inline-tip{margin-top:12px;background:#eff6ff;border:1px solid #bfdbfe;color:#1e3a8a;border-radius:12px;padding:12px}
.kop-inline-actions{display:flex;gap:8px}
.btn-kop-save{background:#16a34a;border-color:#16a34a;color:#fff}
.btn-kop-cancel{background:#e5e7eb;color:#111827}
.edit-input{border:1px solid #93c5fd;background:#ffffff;border-radius:10px;padding:6px 10px}
</style>
@endpush

@section('content')
    <div class="page active" id="koperasi-page">
      <div class="page-header"><h1>Perkembangan Perkoperasian</h1><p>Tahun 2019-2023 di Kabupaten Kolaka Utara</p></div>
      <div class="card kop-card">
        <div class="card-header"><div><h3>Data Koperasi</h3><div class="sub">Perkembangan koperasi tahun 2019–2023</div></div><div class="card-actions"><button class="btn btn-outline btn-sm" id="kop-export"><i class="fas fa-download"></i> Export Data</button> <button class="btn btn-outline btn-sm" id="kop-toggle"><i class="fas fa-plus"></i> Ajukan Baris</button></div></div>
        <div class="card-body">
          <div class="table-wrap">
            <table class="table table-compact kop-table">
              <thead>
                <tr>
                  <th rowspan="2">No</th>
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
              <tbody id="kop-tbody"></tbody>
            </table>
          </div>
          <div class="kop-inline-tip">Tips: Klik tombol "Edit" untuk mengubah data, atau "Ajukan Baris" untuk menambah data baru secara inline.</div>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
var csrfToken=document.querySelector('meta[name="csrf-token"]')?.content||'';window.USER_ROLE=document.body.dataset.userRole||'';
var opdName='Dinas Koperasi dan UKM';var tableKey='koperasi_perkembangan';
var dinasId=(document.body.dataset.dinasId||'')||null;
var kopRows=[];
function renderKop(){var tb=document.getElementById('kop-tbody');if(!tb)return;var addRow='<tr id="kop-addrow" class="add-row" style="display:none;"><td>+</td><td><input class="edit-input" id="ka-uraian" placeholder="Uraian baru..."></td><td><input class="edit-input" id="ka-2019" placeholder="0"></td><td><input class="edit-input" id="ka-2020" placeholder="0"></td><td><input class="edit-input" id="ka-2021" placeholder="0"></td><td><input class="edit-input" id="ka-2022" placeholder="0"></td><td><input class="edit-input" id="ka-2023" placeholder="0"></td><td class="kop-inline-actions"><button class="btn btn-kop-save" id="ka-save"><i class="fas fa-floppy-disk"></i></button><button class="btn btn-kop-cancel" id="ka-cancel">Batal</button></td></tr>';var rows=kopRows.map(function(r,idx){var v=r.values||{};return '<tr data-row="'+(idx+1)+'"><td>'+ (idx+1) +'</td><td class="c-uraian">'+r.uraian+'</td><td class="c-2019">'+(v.y2019||'-')+'</td><td class="c-2020">'+(v.y2020||'-')+'</td><td class="c-2021">'+(v.y2021||'-')+'</td><td class="c-2022">'+(v.y2022||'-')+'</td><td class="c-2023">'+(v.y2023||'-')+'</td><td><button class="btn btn-outline btn-sm edit-btn action-btn" data-ed="'+idx+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-del="'+idx+'"><i class="fas fa-trash"></i></button></td></tr>';}).join('');tb.innerHTML=addRow+rows;}
async function fetchRows(){try{var res=await fetch('/koperasi/rows',{headers:{'Accept':'application/json'}});var data=await res.json();kopRows=(Array.isArray(data)?data:[]);renderKop();}catch(_){kopRows=[];renderKop();}}
document.addEventListener('DOMContentLoaded',fetchRows);
var toggleBtn=document.getElementById('kop-toggle');
toggleBtn?.addEventListener('click',function(){var addRowEl=document.getElementById('kop-addrow');if(!addRowEl){renderKop();addRowEl=document.getElementById('kop-addrow');}
  var isHidden=(addRowEl.style.display==='none' || getComputedStyle(addRowEl).display==='none');
  addRowEl.style.display=isHidden?'table-row':'none';
  toggleBtn.innerHTML=isHidden?'Tutup Form':'<i class="fas fa-plus"></i> Ajukan Baris';});
function exportCsv(filename, headers, rows){var csv=[headers].concat(rows).map(function(row){return row.map(function(v){var s=(''+(v==null?'':v)).replace(/"/g,'""');return '"'+s+'"';}).join(',');}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download=filename;document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url);} 
  document.getElementById('kop-export')?.addEventListener('click',function(){var h=['No','Uraian','2019','2020','2021','2022','2023'];var rows=kopRows.map(function(r,i){var v=r.values||{};return [i+1,r.uraian,v.y2019,v.y2020,v.y2021,v.y2022,v.y2023]});exportCsv('koperasi.csv',h,rows)});
  document.getElementById('kop-tbody')?.addEventListener('click',function(e){var btn=e.target.closest('button');if(!btn)return;if(btn.id==='ka-save'){var ura=document.getElementById('ka-uraian').value.trim();if(!ura){Utils.showToast('Isi Uraian','error');return;}var vals={y2019:document.getElementById('ka-2019').value.trim(),y2020:document.getElementById('ka-2020').value.trim(),y2021:document.getElementById('ka-2021').value.trim(),y2022:document.getElementById('ka-2022').value.trim(),y2023:document.getElementById('ka-2023').value.trim()};var isUser=(window.USER_ROLE==='user');(async function(){try{if(!isUser){var res=await fetch('/opd/rows',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({opd:opdName,key:tableKey,uraian:ura,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows();}var year=(vals.y2023||'').replace(/[^0-9]/g,'').slice(0,4)||new Date().getFullYear().toString();await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({opd:opdName,dinas_id:dinasId,judul_data:ura,deskripsi:null,file_path:'koperasi_inline',tahun_perencanaan:year})});var addRowEl=document.getElementById('kop-addrow');if(addRowEl){addRowEl.style.display='none';}var t=document.getElementById('kop-toggle');if(t){t.innerHTML='<i class=\"fas fa-plus\"></i> Ajukan Baris';}Utils.showToast('Data disimpan','success');}catch(e){Utils.showToast('Gagal menyimpan','error');}})();return;}if(btn.id==='ka-cancel'){var addRowEl=document.getElementById('kop-addrow');if(addRowEl){addRowEl.style.display='none';}var t=document.getElementById('kop-toggle');if(t){t.innerHTML='<i class=\"fas fa-plus\"></i> Ajukan Baris';}return;}if(btn.classList.contains('edit-btn')){var tr=btn.closest('tr');var idx=parseInt(btn.getAttribute('data-ed'),10);var r=kopRows[idx];var v=r.values||{};tr.querySelector('.c-uraian').innerHTML='<input class=\"edit-input\" value=\"'+r.uraian+'\">';tr.querySelector('.c-2019').innerHTML='<input class=\"edit-input\" value=\"'+(v.y2019||'')+'\">';tr.querySelector('.c-2020').innerHTML='<input class=\"edit-input\" value=\"'+(v.y2020||'')+'\">';tr.querySelector('.c-2021').innerHTML='<input class=\"edit-input\" value=\"'+(v.y2021||'')+'\">';tr.querySelector('.c-2022').innerHTML='<input class=\"edit-input\" value=\"'+(v.y2022||'')+'\">';tr.querySelector('.c-2023').innerHTML='<input class=\"edit-input\" value=\"'+(v.y2023||'')+'\">';btn.textContent='Simpan';btn.classList.add('saving');btn.setAttribute('data-edx',idx);return;}if(btn.dataset.del!=null){var i=parseInt(btn.dataset.del,10);var id=kopRows[i]?.id;Utils.confirm('Hapus baris ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(async function(yes){if(!yes)return;try{var res=await fetch('/opd/rows/'+id',{method:'DELETE',headers:{'X-CSRF-TOKEN':csrfToken}});if(res.ok){await fetchRows();Utils.showToast('Baris dihapus','success');}else{Utils.showToast('Gagal menghapus','error');}}catch(e){Utils.showToast('Gagal menghapus','error');}});}});
document.getElementById('kop-tbody')?.addEventListener('click',function(e){var btn=e.target.closest('.saving');if(!btn)return;var tr=btn.closest('tr');var idx=parseInt(btn.getAttribute('data-edx'),10);var inputs=tr.querySelectorAll('input');var payload={uraian:inputs[0].value.trim(),values:{y2019:inputs[1].value.trim(),y2020:inputs[2].value.trim(),y2021:inputs[3].value.trim(),y2022:inputs[4].value.trim(),y2023:inputs[5].value.trim()}};(async function(){try{var id=kopRows[idx]?.id;var res=await fetch('/koperasi/rows/'+id,{method:'PUT',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify(payload)});if(res.ok){await fetchRows();Utils.showToast('Data diperbarui','success');}else{Utils.showToast('Gagal menyimpan','error');}}catch(e){Utils.showToast('Gagal menyimpan','error');}})();});
</script>
@endpush
