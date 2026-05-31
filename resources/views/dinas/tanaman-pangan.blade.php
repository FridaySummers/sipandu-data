@extends('layouts.app')
@section('title', 'Tanaman Pangan')
@section('body-class', 'dashboard-page force-light')
@push('styles')
<style>
.tp-toolbar{display:flex;align-items:center;justify-content:flex-start;margin-bottom:20px;gap:12px}
.segmented{display:inline-flex;gap:6px;background:#f1f5f9;padding:6px;border-radius:12px}
.segmented .btn{height:36px;border-radius:8px;font-weight:600;font-size:13px;padding:0 16px;transition:all .2s;border:none}
.segmented .btn-primary{background:#fff;color:#1d4ed8;box-shadow:0 2px 8px rgba(0,0,0,0.08)}
.segmented .btn-outline{background:transparent;color:#64748b}
.segmented .btn-outline:hover{background:rgba(255,255,255,0.6);color:#374151}
.tp-card{border-radius:20px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.06);border:1px solid #e2e8f0;background:#fff}
.tp-card .card-header{padding:20px 24px;display:flex;align-items:center;justify-content:space-between;background:linear-gradient(135deg,#1d4ed8,#3b82f6);color:#ffffff}
.tp-card .card-header h3{font-size:1.1rem;font-weight:700;margin:0}
.tp-card .card-header .sub{font-size:13px;opacity:.85;color:#ffffff;margin-top:4px}
.tp-card .card-actions{display:flex;gap:10px}
.tp-card .card-actions .btn{border-radius:10px;height:36px;padding:0 14px;font-weight:600;font-size:13px;transition:all .2s;display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.4);backdrop-filter:blur(4px)}
.tp-card .card-actions .btn:hover{background:rgba(255,255,255,0.25)}
.tp-card .card-actions .btn:last-child{background:#fff;color:#1d4ed8;border:none}
.tp-card .card-actions .btn:last-child:hover{box-shadow:0 4px 12px rgba(255,255,255,0.3);transform:translateY(-1px)}
.tp-group{margin:20px 20px 24px;border:1px solid #e2e8f0;border-radius:14px;box-shadow:0 2px 8px rgba(0,0,0,0.04);background:#fff}
.tp-group .group-header{display:flex;align-items:center;justify-content:space-between;background:#f8fafc;color:#0f172a;border:1px solid #cbd5e1;border-radius:14px 14px 0 0;padding:14px 18px;font-weight:600}
.tp-group .badge{background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff;border-radius:8px;width:28px;height:28px;display:inline-flex;align-items:center;justify-content:center;font-weight:700;margin-right:12px;font-size:13px}
.tp-group .panel{border-radius:0 0 14px 14px;margin:0;border:none}
.tp-panel{margin:20px;border-radius:14px;padding:20px;border:1px solid #e2e8f0;background:#f8fafc}
.tp-panel .form-group{display:flex;flex-direction:column;gap:6px;margin-bottom:16px}
.tp-panel .form-group label{font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.04em}
.tp-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:12px}
.year-group{display:flex;flex-direction:column;gap:6px}
.year-label{font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.04em}
.tp-table{width:100%;border-collapse:separate;border-spacing:0;background:#fff;margin:0}
.tp-table thead th{font-weight:700;font-size:13px;border-bottom:2px solid #bfdbfe;padding:12px 14px}
.tp-table.pangan thead th{background:linear-gradient(135deg,#eff6ff,#dbeafe);color:#1e3a8a}
.tp-table.sayur{border-color:#f1f5f9}
.tp-table.sayur thead th{background:linear-gradient(135deg,#f0fdf4,#dcfce7);color:#14532d;border-bottom-color:#bbf7d0}
.tp-table.sayur thead .year{background:#f0fdf4}
.tp-panel.sayur{background:#f0fdf4;border-color:#bbf7d0}
.tp-panel.sayur .year-label{color:#166534}
.tp-table th,.tp-table td{border:1px solid #cbd5e1;border-right:1px solid #f1f5f9;padding:11px 14px;word-break:break-word;white-space:normal;vertical-align:middle}
.tp-table tbody tr{transition:background .15s ease}
.tp-table tbody tr:hover{background:#f8fafc}
.tp-table th{text-align:center}
.tp-table td:nth-child(1),.tp-table td:nth-child(2),.tp-table th:nth-child(1),.tp-table th:nth-child(2){text-align:left}
.tp-table td:not(:nth-child(1)):not(:nth-child(2)){text-align:center}
.tp-table th:last-child,.tp-table td:last-child{border-right:none}
.tp-table-wrap{border-radius:14px;overflow-x:auto;border:1px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,0.04);margin:0 20px 20px}
.tp-group .tp-table-wrap{margin:0;border:none;border-radius:0 0 14px 14px;box-shadow:none}
.edit-cell{border:1.5px solid #bfdbfe;background:#ffffff;border-radius:8px;padding:7px 10px;width:100%;box-sizing:border-box;transition:all .2s}
.edit-cell:focus{border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,0.12);outline:none}
.btn-green{background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;border:none;border-radius:10px;font-weight:600;transition:all .2s}
.btn-green:hover{box-shadow:0 4px 14px rgba(22,163,74,0.3);transform:translateY(-1px)}
.btn-secondary{background:#f1f5f9;color:#374151;border:1px solid #e2e8f0;border-radius:10px;font-weight:600;transition:all .2s}
.btn-secondary:hover{background:#e2e8f0}
.form-control{border:1.5px solid #e2e8f0;background:#ffffff;border-radius:10px;padding:10px 14px;font-size:14px;transition:all .2s;width:100%;box-sizing:border-box}
.form-control:focus{outline:none;border-color:#3b82f6;box-shadow:0 0 0 4px rgba(59,130,246,0.12)}
.status-badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600}
.status-approved{background:#dcfce7;color:#166534}
.status-rejected{background:#fee2e2;color:#991b1b}
.status-menunggu{background:#fef3c7;color:#92400e}
.tp-table thead th:last-child{width:100px !important}
@media (max-width: 640px){.tp-table thead th:last-child{width:auto !important}.tp-table td:last-child{white-space:normal !important}}
.theme-sayur .card-header{background:linear-gradient(135deg,#0d9488,#14b8a6)}
.theme-sayur .card-actions .btn:last-child{color:#0f766e}
.row-actions{display:flex;gap:6px;justify-content:center}
.action-btn{border-radius:8px;width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#f8fafc;border:1px solid #e2e8f0;transition:all .2s;color:#374151}
.action-btn:hover{background:#eff6ff;border-color:#93c5fd;color:#2563eb}
.btn-outline{background:#fff;border:1px solid #e2e8f0;color:#374151;border-radius:8px;font-weight:600;width:32px;height:32px;padding:0;display:inline-flex;align-items:center;justify-content:center}
.btn-outline:hover{background:#fee2e2;border-color:#fca5a5;color:#ef4444}
</style>
@endpush

@section('content')
    <div class="page active" id="tanaman-pangan-page">
      <div class="page-header"><h1>Data Tanaman Pangan & Sayuran</h1><p>Kabupaten Kolaka Utara Tahun 2025 - 2029</p></div>
      <div class="tp-toolbar"><div class="segmented"><button class="btn btn-primary btn-sm" id="tp-tab-pangan">Tanaman Pangan (Accordion)</button><button class="btn btn-outline btn-sm" id="tp-tab-sayur">Sayuran (Tabel)</button></div></div>
      <div id="tp-pangan">
        <div class="card tp-card theme-pangan">
          <div class="card-header"><div><h3>Ajukan Jenis Tanaman Baru</h3><div class="sub">Rekap luas panen, produksi, dan produktivitas 2025–2029</div></div><div class="card-actions"><button class="btn btn-primary btn-sm" id="tp-export"><i class="fas fa-download"></i> Export Data</button><button class="btn btn-primary btn-sm" id="tp-add-pangan"><i class="fas fa-plus"></i> Ajukan Data</button></div></div>
          <div class="card-body">
            <div class="tp-panel pangan" id="tp-panel-pangan" style="display:none;">
              <div class="form-group"><label>Jenis Tanaman</label><input type="text" id="tp-jenis" class="form-control" placeholder="Contoh: Ubi Kayu"></div>
              <div class="form-group"><label>i. Luas Panen (ha)</label><div class="tp-grid"><div class="year-group"><div class="year-label">2025</div><input class="form-control" id="luas-2025" placeholder="0.00"></div><div class="year-group"><div class="year-label">2026</div><input class="form-control" id="luas-2026" placeholder="0.00"></div><div class="year-group"><div class="year-label">2027</div><input class="form-control" id="luas-2027" placeholder="0.00"></div><div class="year-group"><div class="year-label">2028</div><input class="form-control" id="luas-2028" placeholder="0.00"></div><div class="year-group"><div class="year-label">2029</div><input class="form-control" id="luas-2029" placeholder="0.00"></div></div></div>
              <div class="form-group"><label>ii. Produksi (ton)</label><div class="tp-grid"><div class="year-group"><div class="year-label">2025</div><input class="form-control" id="prod-2025" placeholder="0.00"></div><div class="year-group"><div class="year-label">2026</div><input class="form-control" id="prod-2026" placeholder="0.00"></div><div class="year-group"><div class="year-label">2027</div><input class="form-control" id="prod-2027" placeholder="0.00"></div><div class="year-group"><div class="year-label">2028</div><input class="form-control" id="prod-2028" placeholder="0.00"></div><div class="year-group"><div class="year-label">2029</div><input class="form-control" id="prod-2029" placeholder="0.00"></div></div></div>
              <div class="form-group"><label>iii. Produktivitas (ku/ha)</label><div class="tp-grid"><div class="year-group"><div class="year-label">2025</div><input class="form-control" id="pv-2025" placeholder="0.00"></div><div class="year-group"><div class="year-label">2026</div><input class="form-control" id="pv-2026" placeholder="0.00"></div><div class="year-group"><div class="year-label">2027</div><input class="form-control" id="pv-2027" placeholder="0.00"></div><div class="year-group"><div class="year-label">2028</div><input class="form-control" id="pv-2028" placeholder="0.00"></div><div class="year-group"><div class="year-label">2029</div><input class="form-control" id="pv-2029" placeholder="0.00"></div></div></div>
              <div style="display:flex;gap:8px;justify-content:flex-end"><button class="btn btn-secondary" id="tp-cancel-pangan">Batal</button><button class="btn btn-green" id="tp-save-pangan">Simpan Tanaman</button></div>
            </div>
            
            <div class="tp-table-wrap" id="tp-accordion"></div>
          </div>
        </div>
      </div>
      <div id="tp-sayur" style="display:none;">
        <div class="card tp-card theme-sayur">
          <div class="card-header"><div><h3>Ajukan Data Sayuran</h3><div class="sub">Luas tanaman dan produksi sayuran 2025–2029</div></div><div class="card-actions"><button class="btn btn-primary btn-sm" id="tp-export-sayur"><i class="fas fa-download"></i> Export Data</button><button class="btn btn-primary btn-sm" id="tp-add-sayur"><i class="fas fa-plus"></i> Ajukan Data</button></div></div>
          <div class="card-body">
            <div class="tp-panel sayur" id="tp-panel-sayur" style="display:none;">
              <div class="form-group"><label>Jenis Komoditas</label><input type="text" id="syur-jenis" class="form-control" placeholder="Contoh: Tomat"></div>
              <div class="tp-grid">
                <div class="year-group"><div class="year-label">Tahun 2025</div><input class="form-control" id="syur-luas-2025" placeholder="Luas (ha)"><input class="form-control" id="syur-prod-2025" placeholder="Produksi (Kw)"></div>
                <div class="year-group"><div class="year-label">Tahun 2026</div><input class="form-control" id="syur-luas-2026" placeholder="Luas (ha)"><input class="form-control" id="syur-prod-2026" placeholder="Produksi (Kw)"></div>
                <div class="year-group"><div class="year-label">Tahun 2027</div><input class="form-control" id="syur-luas-2027" placeholder="Luas (ha)"><input class="form-control" id="syur-prod-2027" placeholder="Produksi (Kw)"></div>
                <div class="year-group"><div class="year-label">Tahun 2028</div><input class="form-control" id="syur-luas-2028" placeholder="Luas (ha)"><input class="form-control" id="syur-prod-2028" placeholder="Produksi (Kw)"></div>
                <div class="year-group"><div class="year-label">Tahun 2029</div><input class="form-control" id="syur-luas-2029" placeholder="Luas (ha)"><input class="form-control" id="syur-prod-2029" placeholder="Produksi (Kw)"></div>
              </div>
              <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px"><button class="btn btn-secondary" id="tp-cancel-sayur">Batal</button><button class="btn btn-green" id="tp-save-sayur">Simpan</button></div>
            </div>
            <div class="tp-table-wrap"><table class="table table-compact tp-table sayur"><thead><tr><th rowspan="2">No</th><th rowspan="2">Jenis Komoditas</th><th class="year" colspan="2">Tahun 2025</th><th class="year" colspan="2">Tahun 2026</th><th class="year" colspan="2">Tahun 2027</th><th class="year" colspan="2">Tahun 2028</th><th class="year" colspan="2">Tahun 2029</th><th rowspan="2">Status</th><th rowspan="2">Aksi</th></tr><tr><th>Luas Tanaman (ha)</th><th>Produksi (Kw)</th><th>Luas Tanaman (ha)</th><th>Produksi (Kw)</th><th>Luas Tanaman (ha)</th><th>Produksi (Kw)</th><th>Luas Tanaman (ha)</th><th>Produksi (Kw)</th><th>Luas Tanaman (ha)</th><th>Produksi (Kw)</th></tr></thead><tbody id="syur-tbody"></tbody></table></div>
          </div>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
var csrfToken=document.querySelector('meta[name="csrf-token"]')?.content||'';window.USER_ROLE=document.body.dataset.userRole||'';var opdName='Dinas Pertanian Tanaman Pangan';var dinasId=(document.body.dataset.dinasId||'')||null;
var panganRaw=[];var panganGroups=[];
var dmStatuses={};
;(function(){var _f=window.fetch;window.fetch=function(u,o){if(o&&(o.method==='POST'||o.method==='PUT'||o.method==='DELETE')){o.credentials=o.credentials||'same-origin';}return _f(u,o);};})();
function statusLabel(s){if(s==='Disetujui')return 'Disetujui';if(s==='Ditolak')return 'Ditolak';if(s==='Menunggu Persetujuan')return 'Menunggu Persetujuan';return '-'}
function statusClass(s){if(s==='Disetujui')return 'status-approved';if(s==='Ditolak')return 'status-rejected';if(s==='Menunggu Persetujuan')return 'status-menunggu';return ''}
async function fetchDmStatuses(){try{var res=await fetch('/data-management/records',{headers:{'Accept':'application/json'},credentials:'same-origin'});var data=await res.json();dmStatuses={};(Array.isArray(data)?data:[]).forEach(function(rec){if(rec.opd===opdName){dmStatuses[rec.name]=rec.status;}});}catch(_){dmStatuses={}}}
function groupPangan(data){var map={};(Array.isArray(data)?data:[]).forEach(function(r){var v=r.values||{};var g=v.group||'-';if(!map[g])map[g]=[];map[g].push({id:r.id,label:r.uraian,y2025:v.y2025||'',y2026:v.y2026||'',y2027:v.y2027||'',y2028:v.y2028||'',y2029:v.y2029||''});});return Object.keys(map).map(function(name){var rows=map[name].map(function(r,idx){return {id:r.id,no:idx+1,label:r.label,y2025:r.y2025,y2026:r.y2026,y2027:r.y2027,y2028:r.y2028,y2029:r.y2029}});return {name:name,rows:rows};});}
  function renderAccordion(){var wrap=document.getElementById('tp-accordion');if(!wrap)return;wrap.innerHTML=panganGroups.map(function(g,i){var delBtn=(window.USER_ROLE==='user')?'':'<button class="btn btn-outline btn-sm" data-del="'+i+'" title="Hapus"><i class="fas fa-trash"></i></button>';var header='<div class="group-header"><div><span class="badge">'+(i+1)+'</span>'+g.name+'</div><div>'+delBtn+'</div></div>';var st=dmStatuses['Tanaman Pangan '+g.name];var table='<div class="panel" style="background:#eff6ff;border-color:#bfdbfe"><div class="tp-table-wrap"><table class="table table-compact tp-table pangan"><thead><tr><th>No.</th><th>Jenis Tanaman</th><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th><th>Status</th></tr></thead><tbody>'+g.rows.map(function(r){return '<tr data-group="'+i+'" data-row="'+(r.no-1)+'"><td>'+r.no+'</td><td>'+r.label+'</td><td class="c-2025">'+r.y2025+'</td><td class="c-2026">'+r.y2026+'</td><td class="c-2027">'+r.y2027+'</td><td class="c-2028">'+r.y2028+'</td><td class="c-2029">'+r.y2029+'</td><td class="c-status"><span class="status-badge '+statusClass(st)+'">'+statusLabel(st)+'</span></td></tr>'}).join('')+'</tbody></table></div></div>';return '<div class="tp-group">'+header+table+'</div>';}).join('');}
async function fetchPangan(){try{var res=await fetch('/tanaman-pangan/pangan',{headers:{'Accept':'application/json'}});var data=await res.json();panganRaw=data;panganGroups=groupPangan(data);await fetchDmStatuses();renderAccordion();}catch(_){panganRaw=[];panganGroups=[];await fetchDmStatuses();renderAccordion();}}
document.addEventListener('DOMContentLoaded',function(){fetchPangan()});
document.getElementById('tp-tab-pangan')?.addEventListener('click',function(){document.getElementById('tp-tab-pangan').classList.add('btn-primary');document.getElementById('tp-tab-pangan').classList.remove('btn-outline');document.getElementById('tp-tab-sayur').classList.add('btn-outline');document.getElementById('tp-tab-sayur').classList.remove('btn-primary');document.getElementById('tp-pangan').style.display='block';document.getElementById('tp-sayur').style.display='none';});
document.getElementById('tp-tab-sayur')?.addEventListener('click',function(){document.getElementById('tp-tab-sayur').classList.add('btn-primary');document.getElementById('tp-tab-sayur').classList.remove('btn-outline');document.getElementById('tp-tab-pangan').classList.add('btn-outline');document.getElementById('tp-tab-pangan').classList.remove('btn-primary');document.getElementById('tp-pangan').style.display='none';document.getElementById('tp-sayur').style.display='block';});
document.getElementById('tp-add-pangan')?.addEventListener('click',function(){var p=document.getElementById('tp-panel-pangan');var show=p.style.display==='none';p.style.display=show?'block':'none';this.innerHTML=show?'Tutup Form':'<i class="fas fa-plus"></i> Ajukan Data';});
document.getElementById('tp-cancel-pangan')?.addEventListener('click',function(){document.getElementById('tp-panel-pangan').style.display='none';document.getElementById('tp-add-pangan').innerHTML='<i class="fas fa-plus"></i> Ajukan Data';});
document.getElementById('tp-save-pangan')?.addEventListener('click',async function(){var jenis=document.getElementById('tp-jenis').value.trim();if(!jenis){Utils.showToast('Isi Jenis Tanaman','error');return;}var valsL={group:jenis,y2025:document.getElementById('luas-2025').value.trim(),y2026:document.getElementById('luas-2026').value.trim(),y2027:document.getElementById('luas-2027').value.trim(),y2028:document.getElementById('luas-2028').value.trim(),y2029:document.getElementById('luas-2029').value.trim()};var valsP={group:jenis,y2025:document.getElementById('prod-2025').value.trim(),y2026:document.getElementById('prod-2026').value.trim(),y2027:document.getElementById('prod-2027').value.trim(),y2028:document.getElementById('prod-2028').value.trim(),y2029:document.getElementById('prod-2029').value.trim()};var valsV={group:jenis,y2025:document.getElementById('pv-2025').value.trim(),y2026:document.getElementById('pv-2026').value.trim(),y2027:document.getElementById('pv-2027').value.trim(),y2028:document.getElementById('pv-2028').value.trim(),y2029:document.getElementById('pv-2029').value.trim()};var hasVal=(valsL.y2025||valsL.y2026||valsL.y2027||valsL.y2028||valsL.y2029||valsP.y2025||valsP.y2026||valsP.y2027||valsP.y2028||valsP.y2029||valsV.y2025||valsV.y2026||valsV.y2027||valsV.y2028||valsV.y2029);if(!hasVal){Utils.showToast('Isi minimal salah satu nilai tahun','error');return;}var isUser=(window.USER_ROLE==='user');try{if(!isUser){var r1=await fetch('/tanaman-pangan/pangan',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:'Luas Panen (ha)',values:valsL})});if(!r1.ok){Utils.showToast('Gagal menyimpan','error');return;}var r2=await fetch('/tanaman-pangan/pangan',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:'Produksi (ton)',values:valsP})});if(!r2.ok){Utils.showToast('Gagal menyimpan','error');return;}var r3=await fetch('/tanaman-pangan/pangan',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:'Produktivitas (ku/ha)',values:valsV})});if(!r3.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchPangan();}
await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({opd:opdName,dinas_id:dinasId,judul_data:'Tanaman Pangan '+jenis,file_path:'tanaman_pangan_kelompok',tahun_perencanaan:(new Date().getFullYear()).toString()})});
dmStatuses['Tanaman Pangan '+jenis]='Menunggu Persetujuan';
document.getElementById('tp-panel-pangan').style.display='none';document.getElementById('tp-add-pangan').textContent='+ Ajukan Data';Utils.showToast('Data disimpan','success');}catch(e){Utils.showToast('Gagal menyimpan','error');}});
document.getElementById('tp-accordion')?.addEventListener('dblclick', function(e) {
  var td = e.target.closest('td');
  if (!td) return;
  if (
    !td.classList.contains('c-2025') &&
    !td.classList.contains('c-2026') &&
    !td.classList.contains('c-2027') &&
    !td.classList.contains('c-2028') &&
    !td.classList.contains('c-2029')
  ) return;
  var tr=td.closest('tr');var gi=parseInt(tr.dataset.group,10);var st=dmStatuses['Tanaman Pangan '+(panganGroups[gi]?.name||'')];var isUser=(window.USER_ROLE==='user');if(isUser && !(st==='Menunggu Persetujuan')) return;
  var val = td.textContent;
  var controls =
    '<div style="display:flex;align-items:center;gap:6px">' +
      '<input class="edit-cell" value="' + val + '" style="flex:1">' +
      '<button class="btn btn-green btn-xs ok"><i class="fas fa-check"></i></button>' +
      '<button class="btn btn-outline btn-xs cancel"><i class="fas fa-times"></i></button>' +
    '</div>';
  td.setAttribute('data-prev', val);
  td.innerHTML = controls;
});
document.getElementById('tp-accordion')?.addEventListener('click',function(e){
  var delBtn=e.target.closest('[data-del]');
  if(delBtn){
    var i=parseInt(delBtn.getAttribute('data-del'),10);
    if(!isNaN(i)){
      Utils.confirm('Hapus kelompok ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(async function(yes){
        if(!yes) return;
        var isUser=(window.USER_ROLE==='user');
        if(isUser){
          Utils.showToast('Hanya admin yang bisa menghapus','error');
          return;
        }
        var ids=(panganGroups[i]?.rows||[]).map(function(r){return r.id}).filter(Boolean);
        try{
          await Promise.all(ids.map(function(id){
            return fetch('/tanaman-pangan/pangan/'+id,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrfToken,'Accept':'application/json'}});
          }));
          await fetchPangan();
          Utils.showToast('Kelompok dihapus','success');
        }catch(_){
          Utils.showToast('Gagal menghapus','error');
        }
      });
    }
    return;
  }
  var ok=e.target.closest('.ok');
  var cancel=e.target.closest('.cancel');
  if(!ok&&!cancel) return;
  var td=e.target.closest('td');
  var tr=td.closest('tr');
  var gi=parseInt(tr.dataset.group,10);
  var ri=parseInt(tr.dataset.row,10);
  var year=td.className.split('-')[1];
  var key='y'+year;
  if(ok){
    var val=td.querySelector('input').value;
    var row=panganGroups[gi].rows[ri];
    var st=dmStatuses['Tanaman Pangan '+(panganGroups[gi]?.name||'')];var isUser=(window.USER_ROLE==='user');if(isUser && !(st==='Menunggu Persetujuan')){Utils.showToast('Data tidak bisa diubah','error');td.textContent=td.getAttribute('data-prev');return;}
    td.textContent=val;
    var payload={
      uraian:row.label,
      values:{
        group:panganGroups[gi].name,
        y2025:row.y2025,
        y2026:row.y2026,
        y2027:row.y2027,
        y2028:row.y2028,
        y2029:row.y2029
      }
    };
    payload.values[key]=val;
    fetch('/tanaman-pangan/pangan/'+row.id,{
      method:'PUT',
      headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},
      body:JSON.stringify(payload)
    }).then(async function(res){
      if(res.ok){
        await fetchPangan();
        Utils.showToast('Data diperbarui','success');
      }else{
        Utils.showToast('Gagal menyimpan','error');
      }
    }).catch(function(){
      Utils.showToast('Gagal menyimpan','error');
    });
  }else{
    td.textContent=td.getAttribute('data-prev');
  }
});
var syurRows=[];
function renderSayur(){var tb=document.getElementById('syur-tbody');if(!tb)return;tb.innerHTML=syurRows.map(function(r,i){var st=dmStatuses['Sayuran '+r.jenis];var canEdit=(window.USER_ROLE!=='user'||st==='Menunggu Persetujuan');var actions=canEdit?((window.USER_ROLE==='user')?('<div class="row-actions"><button class="btn btn-outline btn-sm sy-edit-row action-btn"><i class="fas fa-pen"></i></button></div>'):('<div class="row-actions"><button class="btn btn-outline btn-sm sy-edit-row action-btn"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-sy-del="'+i+'"><i class="fas fa-trash"></i></button></div>')):'<div class="row-actions"></div>';return '<tr data-row="'+r.no+'"><td>'+r.no+'</td><td class="c-jenis">'+r.jenis+'</td><td class="c-l2025">'+r.l2025+'</td><td class="c-p2025">'+r.p2025+'</td><td class="c-l2026">'+r.l2026+'</td><td class="c-p2026">'+r.p2026+'</td><td class="c-l2027">'+r.l2027+'</td><td class="c-p2027">'+r.p2027+'</td><td class="c-l2028">'+r.l2028+'</td><td class="c-p2028">'+r.p2028+'</td><td class="c-l2029">'+r.l2029+'</td><td class="c-p2029">'+r.p2029+'</td><td class="c-status"><span class="status-badge '+statusClass(st)+'">'+statusLabel(st)+'</span></td><td>'+actions+'</td></tr>';}).join('');}
async function fetchSayur(){try{var res=await fetch('/tanaman-pangan/sayur',{headers:{'Accept':'application/json'}});var data=await res.json();syurRows=(Array.isArray(data)?data:[]).map(function(r,i){var v=r.values||{};return {id:r.id,no:i+1,jenis:r.uraian,l2025:v.l2025||'',p2025:v.p2025||'',l2026:v.l2026||'',p2026:v.p2026||'',l2027:v.l2027||'',p2027:v.p2027||'',l2028:v.l2028||'',p2028:v.p2028||'',l2029:v.l2029||'',p2029:v.p2029||''};});await fetchDmStatuses();renderSayur();}catch(_){syurRows=[];await fetchDmStatuses();renderSayur();}}
document.addEventListener('DOMContentLoaded',function(){fetchSayur()});
document.getElementById('tp-add-sayur')?.addEventListener('click',function(){var p=document.getElementById('tp-panel-sayur');var show=p.style.display==='none';p.style.display=show?'block':'none';this.innerHTML=show?'Tutup Form':'<i class="fas fa-plus"></i> Ajukan Data';});
document.getElementById('tp-cancel-sayur')?.addEventListener('click',function(){document.getElementById('tp-panel-sayur').style.display='none';document.getElementById('tp-add-sayur').innerHTML='<i class="fas fa-plus"></i> Ajukan Data';});
document.getElementById('tp-save-sayur')?.addEventListener('click',async function(){var jenis=document.getElementById('syur-jenis').value.trim();if(!jenis){Utils.showToast('Isi Jenis Komoditas','error');return;}var vals={l2025:document.getElementById('syur-luas-2025').value.trim(),p2025:document.getElementById('syur-prod-2025').value.trim(),l2026:document.getElementById('syur-luas-2026').value.trim(),p2026:document.getElementById('syur-prod-2026').value.trim(),l2027:document.getElementById('syur-luas-2027').value.trim(),p2027:document.getElementById('syur-prod-2027').value.trim(),l2028:document.getElementById('syur-luas-2028').value.trim(),p2028:document.getElementById('syur-prod-2028').value.trim(),l2029:document.getElementById('syur-luas-2029').value.trim(),p2029:document.getElementById('syur-prod-2029').value.trim()};var hasVal=(vals.l2025||vals.p2025||vals.l2026||vals.p2026||vals.l2027||vals.p2027||vals.l2028||vals.p2028||vals.l2029||vals.p2029);if(!hasVal){Utils.showToast('Isi minimal salah satu nilai tahun','error');return;}var isUser=(window.USER_ROLE==='user');try{if(!isUser){var res=await fetch('/tanaman-pangan/sayur',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:jenis,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchSayur();}
await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({opd:opdName,judul_data:'Sayuran '+jenis,file_path:'tanaman_pangan_sayur',tahun_perencanaan:(new Date().getFullYear()).toString()})});
dmStatuses['Sayuran '+jenis]='Menunggu Persetujuan';
document.getElementById('tp-panel-sayur').style.display='none';document.getElementById('tp-add-sayur').textContent='+ Ajukan Data';Utils.showToast('Data disimpan','success');}catch(e){Utils.showToast('Gagal menyimpan','error');}});
document.getElementById('syur-tbody')?.addEventListener('dblclick',function(e){var td=e.target.closest('td');if(!td)return;if(!td.className.match(/^c\-/))return;var tr=td.closest('tr');var idx=parseInt(tr.dataset.row,10)-1;var r=syurRows[idx];var st=dmStatuses['Sayuran '+(r?.jenis||'')];var isUser=(window.USER_ROLE==='user');if(isUser && !(st==='Menunggu Persetujuan')) return;var val=td.textContent;var controls='<div style="display:flex;align-items:center;gap:6px"><input class="edit-cell" value="'+val+'" style="flex:1"><button class="btn btn-green btn-xs ok"><i class="fas fa-check"></i></button><button class="btn btn-outline btn-xs cancel"><i class="fas fa-times"></i></button></div>';td.setAttribute('data-prev',val);td.innerHTML=controls;});
document.getElementById('syur-tbody')?.addEventListener('click',function(e){var ok=e.target.closest('.ok');var cancel=e.target.closest('.cancel');if(!ok&&!cancel)return;var td=e.target.closest('td');var tr=td.closest('tr');var idx=parseInt(tr.dataset.row,10)-1;var r=syurRows[idx];if(ok){var val=td.querySelector('input').value;var cls=td.className;switch(true){case /c-jenis/.test(cls): r.jenis=val; break;case /c-l2025/.test(cls): r.l2025=val; break;case /c-p2025/.test(cls): r.p2025=val; break;case /c-l2026/.test(cls): r.l2026=val; break;case /c-p2026/.test(cls): r.p2026=val; break;case /c-l2027/.test(cls): r.l2027=val; break;case /c-p2027/.test(cls): r.p2027=val; break;case /c-l2028/.test(cls): r.l2028=val; break;case /c-p2028/.test(cls): r.p2028=val; break;case /c-l2029/.test(cls): r.l2029=val; break;case /c-p2029/.test(cls): r.p2029=val; break;}var st=dmStatuses['Sayuran '+(r?.jenis||'')];var isUser=(window.USER_ROLE==='user');if(isUser && !(st==='Menunggu Persetujuan')){Utils.showToast('Data tidak bisa diubah','error');td.textContent=td.getAttribute('data-prev');return;}td.textContent=val;var payload={uraian:r.jenis,values:{l2025:r.l2025,p2025:r.p2025,l2026:r.l2026,p2026:r.p2026,l2027:r.l2027,p2027:r.p2027,l2028:r.l2028,p2028:r.p2028,l2029:r.l2029,p2029:r.p2029}};fetch('/tanaman-pangan/sayur/'+r.id,{method:'PUT',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify(payload)}).then(async function(res){if(res.ok){await fetchSayur();Utils.showToast('Data diperbarui','success');}else{Utils.showToast('Gagal menyimpan','error');}}).catch(function(){Utils.showToast('Gagal menyimpan','error');});}else{td.textContent=td.getAttribute('data-prev');}});
document.getElementById('syur-tbody')?.addEventListener('click',function(e){var del=e.target.closest('[data-sy-del]');if(!del)return;var i=parseInt(del.getAttribute('data-sy-del'),10);Utils.confirm('Hapus baris ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(async function(yes){if(!yes)return;var isUser=(window.USER_ROLE==='user');if(isUser){Utils.showToast('Hanya admin yang bisa menghapus','error');return;}var id=syurRows[i]?.id;try{var res=await fetch('/tanaman-pangan/sayur/'+id,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrfToken,'Accept':'application/json'}});if(res.ok){await fetchSayur();Utils.showToast('Baris dihapus','success');}else{Utils.showToast('Gagal menghapus','error');}}catch(_){Utils.showToast('Gagal menghapus','error');}});});
function exportCsv(filename, headers, rows){var csv=[headers].concat(rows).map(function(row){return row.map(function(v){var s=(''+(v==null?'':v)).replace(/"/g,'""');return '"'+s+'"';}).join(',');}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download=filename;document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url);} 
document.getElementById('tp-export')?.addEventListener('click',function(){var headers=['Kelompok','No','Label','2025','2026','2027','2028','2029'];var rows=[];panganGroups.forEach(function(g){g.rows.forEach(function(r){rows.push([g.name,r.no,r.label,r.y2025,r.y2026,r.y2027,r.y2028,r.y2029])})});exportCsv('tanaman-pangan.csv',headers,rows)});
document.getElementById('tp-export-sayur')?.addEventListener('click',function(){var headers=['No','Jenis','L2025','P2025','L2026','P2026','L2027','P2027','L2028','P2028','L2029','P2029'];var rows=syurRows.map(function(r){return [r.no,r.jenis,r.l2025,r.p2025,r.l2026,r.p2026,r.l2027,r.p2027,r.l2028,r.p2028,r.l2029,r.p2029]});exportCsv('tanaman-sayur.csv',headers,rows)});
</script>
@endpush

