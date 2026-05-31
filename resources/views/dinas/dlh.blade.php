@extends('layouts.app')
@section('title', 'DLH')
@section('body-class', 'dashboard-page force-light')

@push('styles')
<style>
:root { --primary-blue: #2563eb; --accent-blue: #60a5fa; --bg-gray: #f8fafc; --text-dark: #1e293b; --border-color: #e2e8f0; }
.dlh-card { border-radius: 20px; border: 1px solid var(--border-color); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); overflow: hidden; background: #fff; margin-bottom: 24px; }
.dlh-card .card-header { padding: 20px 24px; display: flex; align-items: center; justify-content: space-between; background: #fff; border-bottom: 1px solid var(--border-color); }
.dlh-card .card-header h3 { font-size: 1.25rem; font-weight: 700; color: var(--text-dark); display: flex; align-items: center; gap: 12px; margin: 0; }
.dlh-card .card-header .icon { display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 12px; background: #eff6ff; color: var(--primary-blue); font-size: 1.2rem; }
.dlh-card .card-header .sub { font-size: 0.875rem; color: #64748b; margin-top: 4px; }
.dlh-card .card-actions .btn { border-radius: 10px; padding: 8px 16px; font-weight: 600; transition: all 0.2s; }
.dlh-card .btn-outline { border: 1px solid var(--border-color); background: #fff; color: var(--text-dark); }
.dlh-card .btn-outline:hover { background: #f1f5f9; border-color: #cbd5e1; }
.dlh-panel { margin: 20px; padding: 24px; background: var(--bg-gray); border-radius: 16px; border: 1px solid var(--border-color); }
.dlh-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 16px; margin: 16px 0; }
.year-label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 6px; }
.form-control { border: 1px solid var(--border-color); border-radius: 8px; padding: 10px 14px; width: 100%; transition: border-color 0.2s; }
.form-control:focus { outline: none; border-color: var(--primary-blue); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
.table-wrap { padding: 0 24px 24px; overflow-x: auto; }
.dlh-table { width: 100%; border-collapse: separate; border-spacing: 0; }
.dlh-table thead th { background: #f1f5f9; color: var(--text-dark); padding: 14px; font-weight: 600; border-bottom: 2px solid var(--border-color); white-space: nowrap; }
.dlh-table th:first-child { border-radius: 8px 0 0 8px; }
.dlh-table th:last-child { border-radius: 0 8px 8px 0; }
.dlh-table td { padding: 14px; border-bottom: 1px solid var(--border-color); color: #475569; }
.status-badge { display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
.status-approved { background: #dcfce7; color: #166534; }
.status-rejected { background: #fee2e2; color: #991b1b; }
.status-menunggu { background: #fef3c7; color: #92400e; }
.btn-primary { background: var(--primary-blue); color: #fff; border: none; }
.btn-primary:hover { background: #1d4ed8; }
</style>
@endpush

@section('content')
    <div class="page active" id="dlh-page" data-opd-name="{{ optional(auth()->user()->dinas)->nama_dinas ?? 'Dinas Lingkungan Hidup' }}">
      <div class="page-header"><h1>Data Lingkungan Hidup</h1><p>Pengelolaan persampahan dan daya tampung TPA di Kabupaten Kolaka Utara</p></div>
      <div class="card dlh-card">
        <div class="card-header" style="position:relative;z-index:1">
          <div><h3><span class="icon"><i class="fas fa-tree"></i></span> Daya Tampung TPA</h3><div class="sub">Daya tampung TPA dan jumlah sampah masuk 2019–2023</div></div>
          <div class="card-actions"><button class="btn btn-outline btn-sm" id="dlh-export"><i class="fas fa-download"></i> Export Data</button> <button class="btn btn-outline btn-sm" id="dlh-add" onclick="(function(e){e.preventDefault();e.stopPropagation();var p=document.getElementById('dlh-form-panel');var open=p.style.display!=='none';p.style.display=open?'none':'block';})(event)"><i class="fas fa-plus"></i> Ajukan Data</button></div>
        </div>
        <div class="card-body">
          <div class="dlh-panel" id="dlh-form-panel" style="display:none;">
            <div class="form-group"><label>Nama Data</label><input type="text" id="dlh-uraian" class="form-control" placeholder="Contoh: Nama Data"></div>
            <div class="form-group"><label>Satuan</label><input type="text" id="dlh-satuan" class="form-control" placeholder="Contoh: Ton, Ha, %"></div>
            <div class="dlh-grid">
              <div class="year-group"><div class="year-label">Tahun 2025</div><input class="form-control" id="dlh-2025" placeholder="0"></div>
              <div class="year-group"><div class="year-label">Tahun 2026</div><input class="form-control" id="dlh-2026" placeholder="0"></div>
              <div class="year-group"><div class="year-label">Tahun 2027</div><input class="form-control" id="dlh-2027" placeholder="0"></div>
              <div class="year-group"><div class="year-label">Tahun 2028</div><input class="form-control" id="dlh-2028" placeholder="0"></div>
              <div class="year-group"><div class="year-label">Tahun 2029</div><input class="form-control" id="dlh-2029" placeholder="0"></div>
            </div>
            <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px"><button type="button" class="btn btn-outline" id="dlh-cancel" onclick="dlhCancel()">Batal</button><button type="button" class="btn btn-primary" id="dlh-save" onclick="dlhSave()">Simpan</button></div>
          </div>
          
          <div class="table-wrap">
            <table class="table table-compact dlh-table">
              <thead>
                <tr>
                  <th rowspan="2">No</th>
                  <th rowspan="2">Uraian</th>
                  <th class="th-group-year" colspan="5">Tahun</th>
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
              <tbody id="dlh-tbody"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
var dlhRows=[];var dlhEditId=null;var csrfToken=document.querySelector('meta[name="csrf-token"]')?.content||'';window.USER_ROLE=document.body.dataset.userRole||'';var opdName=document.getElementById('dlh-page').dataset.opdName||'Dinas Lingkungan Hidup';
var dinasId=(document.body.dataset.dinasId||'')||null;
var dmStatuses={};function statusLabel(s){if(s==='Disetujui')return 'Disetujui';if(s==='Ditolak')return 'Ditolak';if(s==='Menunggu Persetujuan')return 'Menunggu Persetujuan';return '-'}function statusClass(s){if(s==='Disetujui')return 'status-approved';if(s==='Ditolak')return 'status-rejected';if(s==='Menunggu Persetujuan')return 'status-menunggu';return ''}
;(function(){var _f=window.fetch;window.fetch=function(u,o){if(o&&(o.method==='POST'||o.method==='PUT'||o.method==='DELETE')){o.credentials=o.credentials||'same-origin';}return _f(u,o);};})();
async function fetchDmStatuses(){try{var res=await fetch('/data-management/records',{headers:{'Accept':'application/json'},credentials:'same-origin'});var data=await res.json();dmStatuses={};(Array.isArray(data)?data:[]).forEach(function(rec){if(rec.opd===opdName){dmStatuses[rec.name]=rec.status;}});}catch(_){dmStatuses={}}}
function renderDlh(){var tb=document.getElementById('dlh-tbody');if(!tb)return;tb.innerHTML=dlhRows.map(function(r,i){var st=dmStatuses[r.uraian];var actions=(window.USER_ROLE==='user')?('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-dlh-ed="'+i+'"><i class="fas fa-pen"></i></button></div>'):('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-dlh-ed="'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-dlh-del="'+i+'"><i class="fas fa-trash"></i></button></div>');return '<tr><td>'+(i+1)+'</td><td>'+r.uraian+'</td><td>'+(r.y2025||'-')+'</td><td>'+(r.y2026||'-')+'</td><td>'+(r.y2027||'-')+'</td><td>'+(r.y2028||'-')+'</td><td>'+(r.y2029||'-')+'</td><td class="c-status"><span class="status-badge '+statusClass(st)+'">'+statusLabel(st)+'</span></td><td>'+actions+'</td></tr>';}).join('');}
async function fetchDlh(){try{var res=await fetch('/dinas/dlh/rows',{headers:{'Accept':'application/json'},credentials:'same-origin'});var data=await res.json();dlhRows=Array.isArray(data)?data:[];renderDlh();}catch(_){dlhRows=[];renderDlh();}}
document.addEventListener('DOMContentLoaded',async function(){await fetchDlh();await fetchDmStatuses();renderDlh();});
async function dlhSave(){var btn=document.getElementById('dlh-save');if(btn&&btn.dataset.busy==='1')return;if(btn)btn.dataset.busy='1';
  var payload={
    uraian:document.getElementById('dlh-uraian').value.trim(),
    satuan:document.getElementById('dlh-satuan').value.trim(),
    y2025:document.getElementById('dlh-2025').value.trim(),
    y2026:document.getElementById('dlh-2026').value.trim(),
    y2027:document.getElementById('dlh-2027').value.trim(),
    y2028:document.getElementById('dlh-2028').value.trim(),
    y2029:document.getElementById('dlh-2029').value.trim()
  };
  if(!payload.uraian){Utils.showToast('Isi Nama Data','error');if(btn)btn.dataset.busy='0';return;}
  try{
    var isUser=(window.USER_ROLE==='user');
    if(!isUser){
      var url= dlhEditId ? ('/dinas/dlh/rows/'+dlhRows[dlhEditId].id) : '/dinas/dlh/rows';
      var method= dlhEditId ? 'PUT' : 'POST';
      var res=await fetch(url,{method:method,headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify(payload)});
      if(!res.ok){Utils.showToast('Gagal menyimpan DLH','error');if(btn)btn.dataset.busy='0';return;}
      await fetchDlh();
      Utils.showToast(dlhEditId?'Data diperbarui':'Data ditambahkan','success');
      dlhEditId=null;
    }
    var _yrRaw = (payload.y2029||'').replace(/[^0-9]/g,'');
    var year = (_yrRaw && _yrRaw.length===4) ? _yrRaw : new Date().getFullYear().toString();
    var did = (dinasId && /^\d+$/.test(String(dinasId))) ? parseInt(dinasId,10) : null;
    var dmPayload = {opd:opdName,judul_data:payload.uraian,deskripsi:payload.satuan?('Satuan: '+payload.satuan):null,file_path:'dlh_inline',tahun_perencanaan:year};
    if (did) dmPayload.dinas_id = did;
    var dmRes = await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify(dmPayload)});
    dmStatuses[payload.uraian] = 'Menunggu Persetujuan';
    await fetchDmStatuses();
    if (!dmRes.ok) { Utils.showToast('Gagal mengirim ke Data Management','error'); if(btn)btn.dataset.busy='0'; return; }
    document.getElementById('dlh-form-panel').style.display='none';
    Utils.showToast('Pengajuan dikirim ke Data Management','success');
  }catch(e){Utils.showToast('Gagal menyimpan DLH','error');}
  if(btn)setTimeout(function(){btn.dataset.busy='0';},0);
}
function dlhCancel(){document.getElementById('dlh-form-panel').style.display='none';dlhEditId=null;}
window.dlhSave=dlhSave;window.dlhCancel=dlhCancel;
document.addEventListener('click', function(e) {
  var ed = e.target.closest('[data-dlh-ed]');
  var del = e.target.closest('[data-dlh-del]');
  if (!ed && !del) return;

  var i = parseInt((ed || del).getAttribute(ed ? 'data-dlh-ed' : 'data-dlh-del'), 10);

  if (ed) {
    var r = dlhRows[i]; dlhEditId = i;
    document.getElementById('dlh-uraian').value = r.uraian;
    document.getElementById('dlh-2025').value = r.y2025;
    document.getElementById('dlh-2026').value = r.y2026;
    document.getElementById('dlh-2027').value = r.y2027;
    document.getElementById('dlh-2028').value = r.y2028;
    document.getElementById('dlh-2029').value = r.y2029;
    document.getElementById('dlh-form-panel').style.display = 'block';
  } else {
    Utils.confirm('Hapus baris ini?', { okText: 'Hapus', cancelText: 'Batal', variant: 'danger' })
      .then(async function(yes) {
        if (!yes) return;
        try{var id = dlhRows[i]?.id; if(!id){Utils.showToast('ID tidak ditemukan','error'); return;} var res=await fetch('/dinas/dlh/rows/'+id,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrfToken},credentials:'same-origin'}); if(res.ok){await fetchDlh(); Utils.showToast('Baris dihapus','success');} else { Utils.showToast('Gagal menghapus','error'); } }catch(e){ Utils.showToast('Gagal menghapus','error'); }
      });
  }
});
function exportCsv(filename, headers, rows){var csv=[headers].concat(rows).map(function(row){return row.map(function(v){var s=(''+(v==null?'':v)).replace(/"/g,'""');return '"'+s+'"';}).join(',');}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download=filename;document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url);} 
;(function(){var ex=document.getElementById('dlh-export');if(ex){ex.addEventListener('click',function(){var h=['No','Uraian','2025','2026','2027','2028','2029'];var rows=dlhRows.map(function(r,i){return [i+1,r.uraian,r.y2025,r.y2026,r.y2027,r.y2028,r.y2029]});exportCsv('dlh.csv',h,rows)});}})();
</script>
@endpush

