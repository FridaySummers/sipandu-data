@extends('layouts.app')
@section('title', 'Perdagangan')
@section('body-class', 'dashboard-page force-light')

@section('content')
    <div class="page active" id="perdagangan-page" data-opd-name="{{ optional(auth()->user()->dinas)->nama_dinas ?? 'Dinas Perdagangan' }}">
      <div class="page-header"><h1>Data Perdagangan</h1><p>Kontribusi Sektor Perdagangan dan Perkembangan Ekspor/Impor</p></div>
      <div class="segmented"><button class="btn btn-primary btn-sm" id="trade-tab-pdrb">Kontribusi Sektor Perdagangan</button><button class="btn btn-outline btn-sm" id="trade-tab-eks">Ekspor & Impor ADHB/ADHK</button></div>

      <div class="card section-card" id="pdrb-section">
        <div class="card-header header-green">
          <div>
            <div class="title">Kontribusi Sektor Perdagangan terhadap PDRB (HB) 2025–2029</div>
            <div class="sub">Data kontribusi perdagangan terhadap PDRB harga berlaku</div>
          </div>
          <div class="head-actions"><button class="btn btn-outline btn-sm" id="pdrb-export"><i class="fas fa-download"></i> Export</button><button class="btn btn-outline btn-sm" id="pdrb-add"><i class="fas fa-plus"></i> Ajukan Data</button><button class="btn btn-outline btn-sm" id="pdrb-close" style="display:none">Tutup Form</button></div>
        </div>
        <div class="card-body">
          <div class="inline-panel panel-green" id="pdrb-panel" style="display:none">
            <div class="panel-inner">
              <div class="form-row"><label class="form-label">Nama Data</label><input type="text" id="pdrb-uraian" class="form-control" placeholder="Contoh: Nama Data"></div>
              <div class="year-grid">
                <div class="form-row"><label class="form-label">2025</label><input type="text" id="pdrb-2025" class="form-control" placeholder="0.00"></div>
                <div class="form-row"><label class="form-label">2026</label><input type="text" id="pdrb-2026" class="form-control" placeholder="0.00"></div>
                <div class="form-row"><label class="form-label">2027</label><input type="text" id="pdrb-2027" class="form-control" placeholder="0.00"></div>
                <div class="form-row"><label class="form-label">2028</label><input type="text" id="pdrb-2028" class="form-control" placeholder="0.00"></div>
                <div class="form-row"><label class="form-label">2029</label><input type="text" id="pdrb-2029" class="form-control" placeholder="0.00"></div>
              </div>
              <div class="panel-actions"><button class="btn btn-secondary" id="pdrb-cancel">Batal</button><button class="btn btn-green" id="pdrb-save"><i class="fas fa-floppy-disk"></i> Simpan Data</button></div>
            </div>
          </div>
          <div class="table-wrap">
            <table class="table table-compact trade-table trade-blue">
              <thead>
                <tr><th>No.</th><th>Uraian</th><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th><th>Status</th><th>Aksi</th></tr>
              </thead>
              <tbody id="pdrb-tbody"></tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="card section-card" id="eks-section" style="display:none">
        <div class="card-header header-blue">
          <div>
            <div class="title">Perkembangan Ekspor ADHB Tahun 2025–2029</div>
            <div class="sub">Data perkembangan ekspor atas dasar harga berlaku</div>
          </div>
          <div class="head-actions"><button class="btn btn-outline btn-sm" id="eks-export"><i class="fas fa-download"></i> Export</button><button class="btn btn-outline btn-sm" id="eks-add"><i class="fas fa-plus"></i> Ajukan Data</button><button class="btn btn-outline btn-sm" id="eks-close" style="display:none">Tutup Form</button></div>
        </div>
        <div class="card-body">
          <div class="inline-panel panel-blue" id="eks-panel" style="display:none">
            <div class="panel-inner">
              <div class="form-row"><label class="form-label">Nama Data</label><input type="text" id="eks-uraian" class="form-control" placeholder="Contoh: Nama Data"></div>
              <div class="year-grid">
                <div class="form-row"><label class="form-label">2025</label><input type="text" id="eks-2025" class="form-control" placeholder="0.00"></div>
                <div class="form-row"><label class="form-label">2026</label><input type="text" id="eks-2026" class="form-control" placeholder="0.00"></div>
                <div class="form-row"><label class="form-label">2027</label><input type="text" id="eks-2027" class="form-control" placeholder="0.00"></div>
                <div class="form-row"><label class="form-label">2028</label><input type="text" id="eks-2028" class="form-control" placeholder="0.00"></div>
                <div class="form-row"><label class="form-label">2029</label><input type="text" id="eks-2029" class="form-control" placeholder="0.00"></div>
              </div>
              <div class="panel-actions"><button class="btn btn-secondary" id="eks-cancel">Batal</button><button class="btn btn-blue" id="eks-save"><i class="fas fa-floppy-disk"></i> Simpan Data</button></div>
            </div>
          </div>
          <div class="table-wrap">
            <table class="table table-compact trade-table trade-blue">
              <thead>
                <tr><th>No.</th><th>Uraian</th><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th><th>Status</th><th>Aksi</th></tr>
              </thead>
              <tbody id="eks-tbody"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
@endsection

@push('styles')
<style>
  .section-card{border-radius:20px;overflow:visible;margin-bottom:20px;box-shadow:0 4px 24px rgba(0,0,0,0.06);border:1px solid #e2e8f0;background:#fff}
  .card-header{display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-radius:20px 20px 0 0}
  .card-header .title{font-weight:700;color:#fff;font-size:1rem}
  .card-header .sub{font-size:13px;opacity:.85;color:#fff;margin-top:4px}
  .header-green{background:linear-gradient(135deg,#1d4ed8,#3b82f6);color:#fff}
  .header-blue{background:linear-gradient(135deg,#0e7490,#0ea5e9);color:#fff}
  .head-actions{display:flex;gap:10px;pointer-events:auto}
  .head-actions .btn{display:inline-flex;align-items:center;gap:6px;pointer-events:auto;white-space:nowrap;flex-shrink:0;font-size:13px}
  .head-actions .btn{border-radius:10px;height:36px;padding:0 14px;font-weight:600;transition:all .2s}
  .head-actions .btn-outline{background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.4);backdrop-filter:blur(4px)}
  .head-actions .btn-outline:hover{background:rgba(255,255,255,0.25)}
  .head-actions .btn-primary{background:#fff;color:#1d4ed8;border:none;font-weight:700}
  .head-actions .btn-primary:hover{box-shadow:0 4px 12px rgba(255,255,255,0.3);transform:translateY(-1px)}
  .inline-panel{margin:16px 0;border-radius:14px;border:1px solid #e2e8f0}
  .panel-green{background:#f8faff;border-color:#bfdbfe}
  .panel-blue{background:#f0fdff;border-color:#a5f3fc}
  .panel-inner{padding:18px}
  .year-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:12px}
  .form-row{display:flex;flex-direction:column;gap:6px;margin-bottom:12px}
  .form-label{font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.04em}
  .trade-table{width:100%;border-collapse:separate;border-spacing:0;border-radius:12px;overflow:hidden}
  .trade-table thead th{font-weight:700;text-align:center;border-bottom:2px solid #bfdbfe;padding:13px 16px;font-size:13px}
  .trade-table.trade-blue thead th{background:linear-gradient(135deg,#eff6ff,#dbeafe);color:#1e3a8a;border-bottom-color:#bfdbfe}
  .trade-table th,.trade-table td{border:1px solid #cbd5e1;border-right:1px solid #f1f5f9;padding:11px 14px;vertical-align:middle}
  .trade-table thead tr th:first-child{border-left:none}
  .trade-table tbody tr td:first-child{border-left:none}
  .trade-table tbody tr{transition:background .15s ease}
  .trade-table tbody tr:hover{background:#f0f9ff}
  .trade-table td:nth-child(1),.trade-table td:nth-child(2),.trade-table th:nth-child(1),.trade-table th:nth-child(2){text-align:left}
  .trade-table.trade-blue th,.trade-table.trade-blue td{border-color:#f1f5f9}
  .trade-table th:last-child,.trade-table td:last-child{text-align:center}
  .trade-table td:last-child{display:table-cell !important;text-align:center !important;white-space:nowrap}
  .trade-table thead th:last-child{width:100px !important}
  @media (max-width: 640px){.trade-table thead th:last-child{width:auto !important}.trade-table td:last-child{white-space:normal !important}}
  .btn-green{background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;border:none;border-radius:10px;font-weight:600;transition:all .2s}
  .btn-green:hover{box-shadow:0 4px 12px rgba(37,99,235,0.3);transform:translateY(-1px)}
  .btn-blue{background:linear-gradient(135deg,#0ea5e9,#0284c7);color:#fff;border:none;border-radius:10px;font-weight:600;transition:all .2s}
  .btn-blue:hover{box-shadow:0 4px 12px rgba(14,165,233,0.3);transform:translateY(-1px)}
  .panel-green .form-label,.panel-blue .form-label{color:#475569}
  .panel-green .form-control,.panel-blue .form-control{border:1.5px solid #e2e8f0;border-radius:10px;padding:10px 12px;font-size:14px;transition:all .2s;background:#fff}
  .panel-green .form-control:focus,.panel-blue .form-control:focus{border-color:#3b82f6;box-shadow:0 0 0 4px rgba(59,130,246,0.12);outline:none}
  .panel-actions{display:flex;gap:8px;justify-content:flex-end;margin-top:12px}
  .action-btn{border-radius:8px;width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#f8fafc;border:1px solid #e2e8f0;transition:all .2s}
  .action-btn:hover{background:#eff6ff;border-color:#93c5fd;color:#2563eb}
  .btn-secondary{background:#f1f5f9;color:#374151;border:1px solid #e2e8f0;border-radius:10px;font-weight:600;transition:all .2s}
  .btn-secondary:hover{background:#e2e8f0}
  .btn-primary{background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff;border:none;border-radius:10px;font-weight:600;transition:all .2s}
  .btn-primary:hover{box-shadow:0 4px 14px rgba(37,99,235,0.3);transform:translateY(-1px)}
  .btn-outline{background:#fff;border:1.5px solid #e2e8f0;color:#374151;border-radius:10px;font-weight:600;transition:all .2s}
  .btn-outline:hover{background:#f8fafc;border-color:#93c5fd;color:#2563eb}
  .status-badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600}
  .status-approved{background:#dcfce7;color:#166534}
  .status-rejected{background:#fee2e2;color:#7f1d1d}
  .status-menunggu{background:#fffbeb;color:#92400e}
  .row-actions .fa-pen{color:#3b82f6}
  .segmented{display:inline-flex;gap:6px;background:#f1f5f9;padding:5px;border-radius:12px;margin-bottom:16px}
  .segmented .btn{height:34px;border-radius:8px;font-weight:600;transition:all .2s}
  .segmented .btn-primary{background:#fff;color:#1d4ed8;box-shadow:0 2px 6px rgba(0,0,0,0.08);border:none}
  .segmented .btn-outline{background:transparent;border:none;color:#64748b}
  .segmented .btn-outline:hover{background:rgba(255,255,255,0.7);color:#374151}
  .table-wrap{border-radius:14px;overflow:hidden;border:1px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,0.04)}
</style>
@endpush

@push('scripts')
<script>
  var csrfToken=document.querySelector('meta[name="csrf-token"]')?.content||'';window.USER_ROLE=document.body.dataset.userRole||''; var dinasId=(document.body.dataset.dinasId||'')||null; var opdName=document.getElementById('perdagangan-page').dataset.opdName||'Dinas Perdagangan';
  var pdrbRows=[],eksRows=[];
  var dmStatuses={};
  function statusLabel(s){if(s==='Disetujui')return 'Disetujui';if(s==='Ditolak')return 'Ditolak';if(s==='Menunggu Persetujuan')return 'Menunggu Persetujuan';return '-'}
  function statusClass(s){if(s==='Disetujui')return 'status-approved';if(s==='Ditolak')return 'status-rejected';if(s==='Menunggu Persetujuan')return 'status-menunggu';return ''}
  async function fetchDmStatuses(){try{var res=await fetch('/data-management/records',{headers:{'Accept':'application/json'},credentials:'same-origin'});var data=await res.json();dmStatuses={};(Array.isArray(data)?data:[]).forEach(function(rec){if(rec.opd===opdName){dmStatuses[rec.name]=rec.status;}});}catch(_){dmStatuses={}}}
  function renderPdrb(){var tb=document.getElementById('pdrb-tbody');if(!tb)return;tb.innerHTML=pdrbRows.map(function(r,i){var v=r.values||{};var st=dmStatuses[r.uraian];var actions=(window.USER_ROLE==='user')?('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-pdrb-edit="'+i+'"><i class="fas fa-pen"></i></button></div>'):('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-pdrb-edit="'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-pdrb-del="'+i+'"><i class="fas fa-trash"></i></button></div>');return '<tr><td>'+ (i+1) +'</td><td>'+ r.uraian +'</td><td>'+ (v.y2025||'-') +'</td><td>'+ (v.y2026||'-') +'</td><td>'+ (v.y2027||'-') +'</td><td>'+ (v.y2028||'-') +'</td><td>'+ (v.y2029||'-') +'</td><td class="c-status"><span class="status-badge '+statusClass(st)+'">'+statusLabel(st)+'</span></td><td>'+actions+'</td></tr>'}).join('');bindPdrbRowActions()}
  function renderEks(){var tb=document.getElementById('eks-tbody');if(!tb)return;tb.innerHTML=eksRows.map(function(r,i){var v=r.values||{};var st=dmStatuses[r.uraian];var actions=(window.USER_ROLE==='user')?('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-eks-edit="'+i+'"><i class="fas fa-pen"></i></button></div>'):('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-eks-edit="'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-eks-del="'+i+'"><i class="fas fa-trash"></i></button></div>');return '<tr><td>'+ (i+1) +'</td><td>'+ r.uraian +'</td><td>'+ (v.y2025||'-') +'</td><td>'+ (v.y2026||'-') +'</td><td>'+ (v.y2027||'-') +'</td><td>'+ (v.y2028||'-') +'</td><td>'+ (v.y2029||'-') +'</td><td class="c-status"><span class="status-badge '+statusClass(st)+'">'+statusLabel(st)+'</span></td><td>'+actions+'</td></tr>'}).join('');bindEksRowActions()}
  async function refreshAll(){await fetchDmStatuses();renderPdrb();renderEks();}
  async function fetchRows(key){try{var url= key==='pdrb' ? '/perdagangan/pdrb' : '/perdagangan/eks'; var res=await fetch(url,{headers:{'Accept':'application/json'},credentials:'same-origin'});var data=await res.json();var mapped=(Array.isArray(data)?data:[]).map(function(r){return {id:r.id,uraian:r.uraian,values:r.values||{}}});if(key==='pdrb'){pdrbRows=mapped;}else{eksRows=mapped;}await refreshAll();}catch(_){if(key==='pdrb'){pdrbRows=[];}else{eksRows=[];}await refreshAll();}}
  document.addEventListener('DOMContentLoaded',function(){fetchRows('pdrb');fetchRows('eks');});
  function exportCsv(filename, headers, rows){var csv=[headers].concat(rows).map(function(row){return row.map(function(v){var s=(''+(v==null?'':v)).replace(/"/g,'""');return '"'+s+'"';}).join(',');}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download=filename;document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url);} 
  document.getElementById('pdrb-export')?.addEventListener('click',function(){var headers=['No','Uraian','2025','2026','2027','2028','2029'];var rows=pdrbRows.map(function(r,i){var v=r.values||{};return [i+1,r.uraian,v.y2025,v.y2026,v.y2027,v.y2028,v.y2029];});exportCsv('perdagangan-pdrb.csv',headers,rows)});
  document.getElementById('eks-export')?.addEventListener('click',function(){var headers=['No','Uraian','2025','2026','2027','2028','2029'];var rows=eksRows.map(function(r,i){var v=r.values||{};return [i+1,r.uraian,v.y2025,v.y2026,v.y2027,v.y2028,v.y2029];});exportCsv('perdagangan-ekspor.csv',headers,rows)});
  // Toggle Add buttons: change label to Tutup Form saat panel dibuka
  function toggleAdd(btn, panel){var show=panel.style.display==='none';panel.style.display=show?'block':'none';btn.innerHTML=show?'Tutup Form':'<i class="fas fa-plus"></i> Ajukan Data'}
  document.getElementById('pdrb-add')?.addEventListener('click',function(){pdrbEditIndex=-1;toggleAdd(this,document.getElementById('pdrb-panel'))});
  document.getElementById('eks-add')?.addEventListener('click',function(){eksEditIndex=-1;toggleAdd(this,document.getElementById('eks-panel'))});
  async function submitDM(judul,year,cat){try{var fp= cat==='pdrb' ? 'perdagangan_pdrb' : 'perdagangan_ekspor'; var payload={opd:opdName,judul_data:judul,deskripsi:null,file_path:fp,tahun_perencanaan:year}; var did=(dinasId&&/^\d+$/.test(String(dinasId)))?parseInt(dinasId,10):null; if(did)payload.dinas_id=did; var res=await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify(payload)}); if(!res.ok){Utils.showToast('Gagal ajukan ke Data Management','error');} dmStatuses[judul]='Menunggu Persetujuan'; await fetchDmStatuses(); renderPdrb(); renderEks();}catch(_){Utils.showToast('Gagal ajukan ke Data Management','error');}}
  document.getElementById('pdrb-save')?.addEventListener('click',async function(){var ura=document.getElementById('pdrb-uraian').value.trim();if(!ura){Utils.showToast('Isi Nama Data','error');return;}var _yrRaw=(document.getElementById('pdrb-2029').value||'').replace(/[^0-9]/g,'');var year=(_yrRaw&&_yrRaw.length===4)?_yrRaw:new Date().getFullYear().toString();var isUser=(window.USER_ROLE==='user');try{var vals={y2025:document.getElementById('pdrb-2025').value.trim(),y2026:document.getElementById('pdrb-2026').value.trim(),y2027:document.getElementById('pdrb-2027').value.trim(),y2028:document.getElementById('pdrb-2028').value.trim(),y2029:document.getElementById('pdrb-2029').value.trim()};var hasVal=(vals.y2025||vals.y2026||vals.y2027||vals.y2028||vals.y2029);if(!hasVal){Utils.showToast('Isi minimal salah satu nilai tahun','error');return;}if(isUser){var res=await fetch('/opd/rows',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify({opd:opdName,key:'perdagangan_pdrb',uraian:ura,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}}else{var id=(pdrbEditIndex>=0)&&(pdrbRows[pdrbEditIndex]?.id);var url=id?('/perdagangan/pdrb/'+id):'/perdagangan/pdrb';var method=id?'PUT':'POST';var res=await fetch(url,{method:method,headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify({uraian:ura,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows('pdrb');}
    await submitDM(ura,year,'pdrb');pdrbEditIndex=-1;document.getElementById('pdrb-panel').style.display='none';document.getElementById('pdrb-add').innerHTML='<i class="fas fa-plus"></i> Ajukan Data';Utils.showToast('Data disimpan','success');}catch(e){Utils.showToast('Gagal menyimpan','error');}});
  document.getElementById('eks-save')?.addEventListener('click',async function(){var ura=document.getElementById('eks-uraian').value.trim();if(!ura){Utils.showToast('Isi Nama Data','error');return;}var _yrRaw=(document.getElementById('eks-2029').value||'').replace(/[^0-9]/g,'');var year=(_yrRaw&&_yrRaw.length===4)?_yrRaw:new Date().getFullYear().toString();var isUser=(window.USER_ROLE==='user');try{var vals={y2025:document.getElementById('eks-2025').value.trim(),y2026:document.getElementById('eks-2026').value.trim(),y2027:document.getElementById('eks-2027').value.trim(),y2028:document.getElementById('eks-2028').value.trim(),y2029:document.getElementById('eks-2029').value.trim()};var hasVal=(vals.y2025||vals.y2026||vals.y2027||vals.y2028||vals.y2029);if(!hasVal){Utils.showToast('Isi minimal salah satu nilai tahun','error');return;}if(isUser){var res=await fetch('/opd/rows',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify({opd:opdName,key:'perdagangan_eks',uraian:ura,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}}else{var id=(eksEditIndex>=0)&&(eksRows[eksEditIndex]?.id);var url=id?('/perdagangan/eks/'+id):'/perdagangan/eks';var method=id?'PUT':'POST';var res=await fetch(url,{method:method,headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},credentials:'same-origin',body:JSON.stringify({uraian:ura,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows('eks');}
    await submitDM(ura,year,'eks');eksEditIndex=-1;document.getElementById('eks-panel').style.display='none';document.getElementById('eks-add').innerHTML='<i class=\"fas fa-plus\"></i> Ajukan Data';Utils.showToast('Data disimpan','success');}catch(e){Utils.showToast('Gagal menyimpan','error');}});
  document.getElementById('pdrb-cancel')?.addEventListener('click',function(){pdrbEditIndex=-1;document.getElementById('pdrb-panel').style.display='none';var addBtn=document.getElementById('pdrb-add');addBtn.style.display='inline-flex';addBtn.innerHTML='<i class="fas fa-plus"></i> Ajukan Data'});
  document.getElementById('eks-cancel')?.addEventListener('click',function(){eksEditIndex=-1;document.getElementById('eks-panel').style.display='none';var addBtn=document.getElementById('eks-add');addBtn.style.display='inline-flex';addBtn.innerHTML='<i class="fas fa-plus"></i> Ajukan Data'});
  document.getElementById('trade-tab-pdrb')?.addEventListener('click',function(){document.getElementById('trade-tab-pdrb').classList.add('btn-primary');document.getElementById('trade-tab-pdrb').classList.remove('btn-outline');document.getElementById('trade-tab-eks').classList.add('btn-outline');document.getElementById('trade-tab-eks').classList.remove('btn-primary');document.getElementById('pdrb-section').style.display='block';document.getElementById('eks-section').style.display='none'});
  document.getElementById('trade-tab-eks')?.addEventListener('click',function(){document.getElementById('trade-tab-eks').classList.add('btn-primary');document.getElementById('trade-tab-eks').classList.remove('btn-outline');document.getElementById('trade-tab-pdrb').classList.add('btn-outline');document.getElementById('trade-tab-pdrb').classList.remove('btn-primary');document.getElementById('pdrb-section').style.display='none';document.getElementById('eks-section').style.display='block'});
  var pdrbEditIndex=-1, eksEditIndex=-1;
  function openPdrbEditor(idx){pdrbEditIndex=idx;var r=pdrbRows[idx];var v=r.values||{};document.getElementById('pdrb-uraian').value=r.uraian;document.getElementById('pdrb-2025').value=v.y2025||'';document.getElementById('pdrb-2026').value=v.y2026||'';document.getElementById('pdrb-2027').value=v.y2027||'';document.getElementById('pdrb-2028').value=v.y2028||'';document.getElementById('pdrb-2029').value=v.y2029||'';document.getElementById('pdrb-panel').style.display='block';document.getElementById('pdrb-add').style.display='none';document.getElementById('pdrb-close').style.display='inline-flex'}
  function bindPdrbRowActions(){document.querySelectorAll('[data-pdrb-edit]').forEach(function(btn){btn.addEventListener('click',function(){openPdrbEditor(parseInt(this.getAttribute('data-pdrb-edit'),10))})});document.querySelectorAll('[data-pdrb-del]').forEach(function(btn){btn.addEventListener('click',function(){var i=parseInt(this.getAttribute('data-pdrb-del'),10);var id=pdrbRows[i]?.id;Utils.confirm('Hapus baris ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(async function(yes){if(!yes)return;try{var res=await fetch('/perdagangan/pdrb/'+id,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrfToken},credentials:'same-origin'});if(res.ok){await fetchRows('pdrb');Utils.showToast('Baris dihapus','success');}else{Utils.showToast('Gagal menghapus','error');}}catch(e){Utils.showToast('Gagal menghapus','error');}});})})}
  function openEksEditor(idx){eksEditIndex=idx;var r=eksRows[idx];var v=r.values||{};document.getElementById('eks-uraian').value=r.uraian;document.getElementById('eks-2025').value=v.y2025||'';document.getElementById('eks-2026').value=v.y2026||'';document.getElementById('eks-2027').value=v.y2027||'';document.getElementById('eks-2028').value=v.y2028||'';document.getElementById('eks-2029').value=v.y2029||'';document.getElementById('eks-panel').style.display='block';document.getElementById('eks-add').style.display='none';document.getElementById('eks-close').style.display='inline-flex'}
  function bindEksRowActions(){document.querySelectorAll('[data-eks-edit]').forEach(function(btn){btn.addEventListener('click',function(){openEksEditor(parseInt(this.getAttribute('data-eks-edit'),10))})});document.querySelectorAll('[data-eks-del]').forEach(function(btn){btn.addEventListener('click',function(){var i=parseInt(this.getAttribute('data-eks-del'),10);var id=eksRows[i]?.id;Utils.confirm('Hapus baris ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(async function(yes){if(!yes)return;try{var res=await fetch('/perdagangan/eks/'+id,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrfToken},credentials:'same-origin'});if(res.ok){await fetchRows('eks');Utils.showToast('Baris dihapus','success');}else{Utils.showToast('Gagal menghapus','error');}}catch(e){Utils.showToast('Gagal menghapus','error');}});})})}
</script>
@endpush

