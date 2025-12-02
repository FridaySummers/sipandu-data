@extends('layouts.app')
@section('title', 'Perdagangan')
@section('body-class', 'dashboard-page force-light')

@section('content')
    <div class="page active" id="perdagangan-page">
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
                <tr><th>No.</th><th>Uraian</th><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th><th>Aksi</th></tr>
              </thead>
              <tbody id="pdrb-tbody"></tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="card section-card" id="eks-section" style="display:none">
        <div class="card-header header-blue">
          <div>
            <div class="title">Perkembangan Ekspor ADHB Tahun 2019–2023</div>
            <div class="sub">Data perkembangan ekspor atas dasar harga berlaku</div>
          </div>
          <div class="head-actions"><button class="btn btn-outline btn-sm" id="eks-export"><i class="fas fa-download"></i> Export</button><button class="btn btn-outline btn-sm" id="eks-add"><i class="fas fa-plus"></i> Ajukan Data</button><button class="btn btn-outline btn-sm" id="eks-close" style="display:none">Tutup Form</button></div>
        </div>
        <div class="card-body">
          <div class="inline-panel panel-blue" id="eks-panel" style="display:none">
            <div class="panel-inner">
              <div class="form-row"><label class="form-label">Nama Data</label><input type="text" id="eks-uraian" class="form-control" placeholder="Contoh: Nama Data"></div>
              <div class="year-grid">
                <div class="form-row"><label class="form-label">2019</label><input type="text" id="eks-2019" class="form-control" placeholder="0.00"></div>
                <div class="form-row"><label class="form-label">2020</label><input type="text" id="eks-2020" class="form-control" placeholder="0.00"></div>
                <div class="form-row"><label class="form-label">2021</label><input type="text" id="eks-2021" class="form-control" placeholder="0.00"></div>
                <div class="form-row"><label class="form-label">2022</label><input type="text" id="eks-2022" class="form-control" placeholder="0.00"></div>
                <div class="form-row"><label class="form-label">2023</label><input type="text" id="eks-2023" class="form-control" placeholder="0.00"></div>
              </div>
              <div class="panel-actions"><button class="btn btn-secondary" id="eks-cancel">Batal</button><button class="btn btn-blue" id="eks-save"><i class="fas fa-floppy-disk"></i> Simpan Data</button></div>
            </div>
          </div>
          <div class="table-wrap">
            <table class="table table-compact trade-table trade-blue">
              <thead>
                <tr><th>No.</th><th>Uraian</th><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th><th>Aksi</th></tr>
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
  .section-card{border-radius:12px;overflow:visible}
  .card-header{display:flex;align-items:center;justify-content:space-between;padding:16px 18px}
  .card-header .title{font-weight:600;color:#fff}
  .card-header .sub{font-size:13px;opacity:.9;color:#fff}
  .header-green{background:linear-gradient(90deg,#60a5fa,#3b82f6);color:#fff}
  .header-blue{background:linear-gradient(90deg,#60a5fa,#3b82f6);color:#fff}
  .head-actions{display:flex;gap:12px;margin-right:12px;pointer-events:auto}
  .head-actions .btn{display:inline-flex;align-items:center;gap:6px;pointer-events:auto;white-space:nowrap;flex-shrink:0}
  .head-actions .btn{border-radius:8px;height:34px;padding:0 12px}
  
  .inline-panel{margin:12px 0;border-radius:12px;border:1px solid}
  .panel-green{background:#eff6ff;border-color:#bfdbfe}
  .panel-blue{background:#eff6ff;border-color:#bfdbfe}
  .panel-inner{border:1px solid currentColor;border-color:inherit;border-radius:12px;padding:12px}
  .year-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:12px}
  .form-row{display:flex;flex-direction:column;gap:6px}
  .trade-table{width:100%;border:1px solid #93c5fd;border-radius:12px;overflow:hidden;border-collapse:separate;border-spacing:0}
  .trade-table thead th{font-weight:600;text-align:center;background:#dbeafe;color:#1e3a8a;border-bottom:1px solid #93c5fd}
  .trade-table thead th{border-right:1px solid #93c5fd}
  .trade-table thead th:first-child{border-left:1px solid #93c5fd}
  .trade-table th,.trade-table td{border:1px solid #93c5fd;padding:10px 12px}
  .trade-table th,.trade-table td{vertical-align:middle}
  .trade-table tbody tr + tr td{border-top:1px solid #bfdbfe}
  .trade-table tbody tr:last-child td{border-bottom:1px solid #93c5fd}
  .trade-table td:nth-child(1),.trade-table td:nth-child(2),.trade-table th:nth-child(1),.trade-table th:nth-child(2){text-align:left}
  .trade-table.trade-blue{border-color:#93c5fd}
  .trade-table.trade-blue th,.trade-table.trade-blue td{border-color:#93c5fd}
  .trade-table.trade-blue thead th{background:#dbeafe;color:#1e3a8a;border-bottom-color:#93c5fd}
  /* Force override global table borders untuk ketebalan moderat dan garis horizontal */
  #perdagangan-page .trade-table{border-top:1px solid #93c5fd !important;border-bottom:1px solid #93c5fd !important;border-left:1px solid #93c5fd !important;border-right:1px solid #93c5fd !important}
  #perdagangan-page .trade-table thead th{border-bottom:1px solid #93c5fd !important;border-right:1px solid #93c5fd !important}
  #perdagangan-page .trade-table thead th:first-child{border-left:1px solid #93c5fd !important}
  #perdagangan-page .trade-table th,#perdagangan-page .trade-table td{border:1px solid #93c5fd !important}
  #perdagangan-page .trade-table tbody tr + tr td{border-top:1px solid #bfdbfe !important}
  #perdagangan-page .trade-table tbody tr:last-child td{border-bottom:1px solid #93c5fd !important}
  .btn-green{background:#2563eb;color:#fff;border-color:#2563eb}
  .btn-blue{background:#2563eb;color:#fff;border-color:#2563eb}
  .form-label{color:#1e3a8a}
  .panel-green .form-label{color:#1e3a8a}
  .panel-blue .form-label{color:#1e3a8a}
  .panel-green .form-control{border:1px solid #93c5fd;background:#ffffff;border-radius:12px;padding:10px 12px}
  .panel-green .form-control:focus{outline:none;border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,0.25)}
  .panel-blue .form-control{border:1px solid #93c5fd;background:#ffffff;border-radius:12px;padding:10px 12px}
  .panel-blue .form-control:focus{outline:none;border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,0.25)}
  .panel-actions{display:flex;gap:8px;justify-content:flex-end;margin-top:8px}
  .action-btn{border-radius:8px;width:30px;height:30px;display:inline-flex;align-items:center;justify-content:center}
  .input-year{height:auto;padding:10px 12px;border-width:1px;text-align:left}
  .trade-table th:last-child,.trade-table td:last-child{text-align:center}
  .trade-table td:last-child{display:table-cell !important;text-align:center !important;white-space:nowrap}
</style>
@endpush

@push('scripts')
<script>
  var csrfToken=document.querySelector('meta[name="csrf-token"]')?.content||'';window.USER_ROLE=document.body.dataset.userRole||''; var dinasId=(document.body.dataset.dinasId||'')||null;
  var pdrbRows=[],eksRows=[];
  function renderPdrb(){var tb=document.getElementById('pdrb-tbody');if(!tb)return;tb.innerHTML=pdrbRows.map(function(r,i){var v=r.values||{};return '<tr><td>'+ (i+1) +'</td><td>'+ r.uraian +'</td><td>'+ (v.y2025||'-') +'</td><td>'+ (v.y2026||'-') +'</td><td>'+ (v.y2027||'-') +'</td><td>'+ (v.y2028||'-') +'</td><td>'+ (v.y2029||'-') +'</td><td><button class="btn btn-outline btn-sm action-btn" data-pdrb-edit="'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-pdrb-del="'+i+'"><i class="fas fa-trash"></i></button></td></tr>'}).join('');bindPdrbRowActions()}
  function renderEks(){var tb=document.getElementById('eks-tbody');if(!tb)return;tb.innerHTML=eksRows.map(function(r,i){var v=r.values||{};return '<tr><td>'+ (i+1) +'</td><td>'+ r.uraian +'</td><td>'+ (v.y2019||'-') +'</td><td>'+ (v.y2020||'-') +'</td><td>'+ (v.y2021||'-') +'</td><td>'+ (v.y2022||'-') +'</td><td>'+ (v.y2023||'-') +'</td><td><button class="btn btn-outline btn-sm action-btn" data-eks-edit="'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-eks-del="'+i+'"><i class="fas fa-trash"></i></button></td></tr>'}).join('');bindEksRowActions()}
  async function fetchRows(key){try{var url= key==='pdrb' ? '/perdagangan/pdrb' : '/perdagangan/eks'; var res=await fetch(url,{headers:{'Accept':'application/json'}});var data=await res.json();var mapped=(Array.isArray(data)?data:[]).map(function(r){return {id:r.id,uraian:r.uraian,values:r.values||{}}});if(key==='pdrb'){pdrbRows=mapped;renderPdrb();}else{eksRows=mapped;renderEks();}}catch(_){if(key==='pdrb'){pdrbRows=[];renderPdrb();}else{eksRows=[];renderEks();}}}
  document.addEventListener('DOMContentLoaded',function(){fetchRows('pdrb');fetchRows('eks');});
  function exportCsv(filename, headers, rows){var csv=[headers].concat(rows).map(function(row){return row.map(function(v){var s=(''+(v==null?'':v)).replace(/"/g,'""');return '"'+s+'"';}).join(',');}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download=filename;document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url);} 
  document.getElementById('pdrb-export')?.addEventListener('click',function(){var headers=['No','Uraian','2025','2026','2027','2028','2029'];var rows=pdrbRows.map(function(r,i){var v=r.values||{};return [i+1,r.uraian,v.y2025,v.y2026,v.y2027,v.y2028,v.y2029];});exportCsv('perdagangan-pdrb.csv',headers,rows)});
  document.getElementById('eks-export')?.addEventListener('click',function(){var headers=['No','Uraian','2019','2020','2021','2022','2023'];var rows=eksRows.map(function(r,i){var v=r.values||{};return [i+1,r.uraian,v.y2019,v.y2020,v.y2021,v.y2022,v.y2023];});exportCsv('perdagangan-ekspor.csv',headers,rows)});
  // Toggle Add buttons: change label to Tutup Form saat panel dibuka
  function toggleAdd(btn, panel){var show=panel.style.display==='none';panel.style.display=show?'block':'none';btn.innerHTML=show?'Tutup Form':'<i class="fas fa-plus"></i> Ajukan Data'}
  document.getElementById('pdrb-add')?.addEventListener('click',function(){pdrbEditIndex=-1;toggleAdd(this,document.getElementById('pdrb-panel'))});
  document.getElementById('eks-add')?.addEventListener('click',function(){eksEditIndex=-1;toggleAdd(this,document.getElementById('eks-panel'))});
  async function submitDM(judul,year,cat){try{var fp= cat==='pdrb' ? 'perdagangan_pdrb' : 'perdagangan_ekspor'; var res=await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({opd:'Dinas Perdagangan',dinas_id:dinasId,judul_data:judul,deskripsi:null,file_path:fp,tahun_perencanaan:year})}); if(!res.ok){Utils.showToast('Gagal ajukan ke Data Management','error');}}catch(_){Utils.showToast('Gagal ajukan ke Data Management','error');}}
  document.getElementById('pdrb-save')?.addEventListener('click',async function(){var ura=document.getElementById('pdrb-uraian').value.trim();if(!ura){Utils.showToast('Isi Nama Data','error');return;}var year=(document.getElementById('pdrb-2029').value||'').replace(/[^0-9]/g,'').slice(0,4)||new Date().getFullYear().toString();var isUser=(window.USER_ROLE==='user');try{if(!isUser){var vals={y2025:document.getElementById('pdrb-2025').value.trim(),y2026:document.getElementById('pdrb-2026').value.trim(),y2027:document.getElementById('pdrb-2027').value.trim(),y2028:document.getElementById('pdrb-2028').value.trim(),y2029:document.getElementById('pdrb-2029').value.trim()};var hasVal=(vals.y2025||vals.y2026||vals.y2027||vals.y2028||vals.y2029);if(!hasVal){Utils.showToast('Isi minimal salah satu nilai tahun','error');return;}var res=await fetch('/perdagangan/pdrb',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:ura,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows('pdrb');}
    await submitDM(ura,year,'pdrb');document.getElementById('pdrb-panel').style.display='none';document.getElementById('pdrb-add').innerHTML='<i class="fas fa-plus"></i> Ajukan Data';Utils.showToast('Data disimpan','success');}catch(e){Utils.showToast('Gagal menyimpan','error');}});
  document.getElementById('eks-save')?.addEventListener('click',async function(){var ura=document.getElementById('eks-uraian').value.trim();if(!ura){Utils.showToast('Isi Nama Data','error');return;}var year=(document.getElementById('eks-2023').value||'').replace(/[^0-9]/g,'').slice(0,4)||new Date().getFullYear().toString();var isUser=(window.USER_ROLE==='user');try{if(!isUser){var vals={y2019:document.getElementById('eks-2019').value.trim(),y2020:document.getElementById('eks-2020').value.trim(),y2021:document.getElementById('eks-2021').value.trim(),y2022:document.getElementById('eks-2022').value.trim(),y2023:document.getElementById('eks-2023').value.trim()};var hasVal=(vals.y2019||vals.y2020||vals.y2021||vals.y2022||vals.y2023);if(!hasVal){Utils.showToast('Isi minimal salah satu nilai tahun','error');return;}var res=await fetch('/perdagangan/eks',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:ura,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows('eks');}
    await submitDM(ura,year,'eks');document.getElementById('eks-panel').style.display='none';document.getElementById('eks-add').innerHTML='<i class=\"fas fa-plus\"></i> Ajukan Data';Utils.showToast('Data disimpan','success');}catch(e){Utils.showToast('Gagal menyimpan','error');}});
  document.getElementById('pdrb-cancel')?.addEventListener('click',function(){pdrbEditIndex=-1;document.getElementById('pdrb-panel').style.display='none';var addBtn=document.getElementById('pdrb-add');addBtn.style.display='inline-flex';addBtn.innerHTML='<i class="fas fa-plus"></i> Ajukan Data'});
  document.getElementById('eks-cancel')?.addEventListener('click',function(){eksEditIndex=-1;document.getElementById('eks-panel').style.display='none';var addBtn=document.getElementById('eks-add');addBtn.style.display='inline-flex';addBtn.innerHTML='<i class="fas fa-plus"></i> Ajukan Data'});
  document.getElementById('trade-tab-pdrb')?.addEventListener('click',function(){document.getElementById('trade-tab-pdrb').classList.add('btn-primary');document.getElementById('trade-tab-pdrb').classList.remove('btn-outline');document.getElementById('trade-tab-eks').classList.add('btn-outline');document.getElementById('trade-tab-eks').classList.remove('btn-primary');document.getElementById('pdrb-section').style.display='block';document.getElementById('eks-section').style.display='none'});
  document.getElementById('trade-tab-eks')?.addEventListener('click',function(){document.getElementById('trade-tab-eks').classList.add('btn-primary');document.getElementById('trade-tab-eks').classList.remove('btn-outline');document.getElementById('trade-tab-pdrb').classList.add('btn-outline');document.getElementById('trade-tab-pdrb').classList.remove('btn-primary');document.getElementById('pdrb-section').style.display='none';document.getElementById('eks-section').style.display='block'});
  var pdrbEditIndex=-1, eksEditIndex=-1;
  function openPdrbEditor(idx){pdrbEditIndex=idx;var r=pdrbRows[idx];var v=r.values||{};document.getElementById('pdrb-uraian').value=r.uraian;document.getElementById('pdrb-2025').value=v.y2025||'';document.getElementById('pdrb-2026').value=v.y2026||'';document.getElementById('pdrb-2027').value=v.y2027||'';document.getElementById('pdrb-2028').value=v.y2028||'';document.getElementById('pdrb-2029').value=v.y2029||'';document.getElementById('pdrb-panel').style.display='block';document.getElementById('pdrb-add').style.display='none';document.getElementById('pdrb-close').style.display='inline-flex'}
  function bindPdrbRowActions(){document.querySelectorAll('[data-pdrb-edit]').forEach(function(btn){btn.addEventListener('click',function(){openPdrbEditor(parseInt(this.getAttribute('data-pdrb-edit'),10))})});document.querySelectorAll('[data-pdrb-del]').forEach(function(btn){btn.addEventListener('click',function(){var i=parseInt(this.getAttribute('data-pdrb-del'),10);var id=pdrbRows[i]?.id;Utils.confirm('Hapus baris ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(async function(yes){if(!yes)return;try{var res=await fetch('/perdagangan/pdrb/'+id,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrfToken}});if(res.ok){await fetchRows('pdrb');Utils.showToast('Baris dihapus','success');}else{Utils.showToast('Gagal menghapus','error');}}catch(e){Utils.showToast('Gagal menghapus','error');}});})})}
  function openEksEditor(idx){eksEditIndex=idx;var r=eksRows[idx];var v=r.values||{};document.getElementById('eks-uraian').value=r.uraian;document.getElementById('eks-2019').value=v.y2019||'';document.getElementById('eks-2020').value=v.y2020||'';document.getElementById('eks-2021').value=v.y2021||'';document.getElementById('eks-2022').value=v.y2022||'';document.getElementById('eks-2023').value=v.y2023||'';document.getElementById('eks-panel').style.display='block';document.getElementById('eks-add').style.display='none';document.getElementById('eks-close').style.display='inline-flex'}
  function bindEksRowActions(){document.querySelectorAll('[data-eks-edit]').forEach(function(btn){btn.addEventListener('click',function(){openEksEditor(parseInt(this.getAttribute('data-eks-edit'),10))})});document.querySelectorAll('[data-eks-del]').forEach(function(btn){btn.addEventListener('click',function(){var i=parseInt(this.getAttribute('data-eks-del'),10);var id=eksRows[i]?.id;Utils.confirm('Hapus baris ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(async function(yes){if(!yes)return;try{var res=await fetch('/perdagangan/eks/'+id,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrfToken}});if(res.ok){await fetchRows('eks');Utils.showToast('Baris dihapus','success');}else{Utils.showToast('Gagal menghapus','error');}}catch(e){Utils.showToast('Gagal menghapus','error');}});})})}
</script>
@endpush
