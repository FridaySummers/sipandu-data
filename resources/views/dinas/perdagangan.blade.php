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
          <div class="head-actions"><button class="btn btn-outline btn-sm" id="pdrb-export"><i class="fas fa-download"></i> Export</button><button class="btn btn-outline btn-sm" id="pdrb-add"><i class="fas fa-plus"></i> Tambah Data</button><button class="btn btn-outline btn-sm" id="pdrb-close" style="display:none">Tutup Form</button></div>
        </div>
        <div class="card-body">
          <div class="inline-panel panel-green" id="pdrb-panel" style="display:none">
            <div class="panel-inner">
              <div class="form-row"><label class="form-label">Uraian</label><input type="text" id="pdrb-uraian" class="form-control" placeholder="Contoh: PDRB Sektor Perdagangan HB"></div>
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
          <div class="head-actions"><button class="btn btn-outline btn-sm" id="eks-export"><i class="fas fa-download"></i> Export</button><button class="btn btn-outline btn-sm" id="eks-add"><i class="fas fa-plus"></i> Tambah Data</button><button class="btn btn-outline btn-sm" id="eks-close" style="display:none">Tutup Form</button></div>
        </div>
        <div class="card-body">
          <div class="inline-panel panel-blue" id="eks-panel" style="display:none">
            <div class="panel-inner">
              <div class="form-row"><label class="form-label">Uraian</label><input type="text" id="eks-uraian" class="form-control" placeholder="Contoh: Nilai Ekspor ADHB"></div>
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
  var k1='sipandu_trade_pdrb',k2='sipandu_trade_eks';
  function load(k){try{return JSON.parse(localStorage.getItem(k))||[]}catch(e){return []}}
  function save(k,d){localStorage.setItem(k,JSON.stringify(d))}
  var pdrbRows=load(k1);if(!pdrbRows.length){pdrbRows=[{uraian:'PDRB Sektor Perdagangan HB',y2025:'1,126.22',y2026:'1,128.71',y2027:'1,198.31',y2028:'1,364.10',y2029:'1,400.15'}];save(k1,pdrbRows)}
  var eksRows=load(k2);if(!eksRows.length){eksRows=[{uraian:'Nilai Ekspor ADHB',y2019:'2,498.40',y2020:'2,850.98',y2021:'3,118.61',y2022:'3,842.44',y2023:'4,152.55'}];save(k2,eksRows)}
  function renderPdrb(){var tb=document.getElementById('pdrb-tbody');if(!tb)return;tb.innerHTML=pdrbRows.map(function(r,i){return '<tr><td>'+ (i+1) +'</td><td>'+ r.uraian +'</td><td>'+ r.y2025 +'</td><td>'+ r.y2026 +'</td><td>'+ r.y2027 +'</td><td>'+ r.y2028 +'</td><td>'+ r.y2029 +'</td><td><button class="btn btn-outline btn-sm action-btn" data-pdrb-edit="'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-pdrb-del="'+i+'"><i class="fas fa-trash"></i></button></td></tr>'}).join('');bindPdrbRowActions()}
  function renderEks(){var tb=document.getElementById('eks-tbody');if(!tb)return;tb.innerHTML=eksRows.map(function(r,i){return '<tr><td>'+ (i+1) +'</td><td>'+ r.uraian +'</td><td>'+ r.y2019 +'</td><td>'+ r.y2020 +'</td><td>'+ r.y2021 +'</td><td>'+ r.y2022 +'</td><td>'+ r.y2023 +'</td><td><button class="btn btn-outline btn-sm action-btn" data-eks-edit="'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-eks-del="'+i+'"><i class="fas fa-trash"></i></button></td></tr>'}).join('');bindEksRowActions()}
  renderPdrb();renderEks();
  function exportCsv(filename, headers, rows){var csv=[headers].concat(rows).map(function(row){return row.map(function(v){var s=(''+(v==null?'':v)).replace(/"/g,'""');return '"'+s+'"';}).join(',');}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download=filename;document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url);} 
  document.getElementById('pdrb-export')?.addEventListener('click',function(){var headers=['No','Uraian','2025','2026','2027','2028','2029'];var rows=pdrbRows.map(function(r,i){return [i+1,r.uraian,r.y2025,r.y2026,r.y2027,r.y2028,r.y2029];});exportCsv('perdagangan-pdrb.csv',headers,rows)});
  document.getElementById('eks-export')?.addEventListener('click',function(){var headers=['No','Uraian','2019','2020','2021','2022','2023'];var rows=eksRows.map(function(r,i){return [i+1,r.uraian,r.y2019,r.y2020,r.y2021,r.y2022,r.y2023];});exportCsv('perdagangan-ekspor.csv',headers,rows)});
  // Toggle Add buttons: change label to Tutup Form saat panel dibuka
  function toggleAdd(btn, panel){var show=panel.style.display==='none';panel.style.display=show?'block':'none';btn.innerHTML=show?'Tutup Form':'<i class="fas fa-plus"></i> Tambah Data'}
  document.getElementById('pdrb-add')?.addEventListener('click',function(){pdrbEditIndex=-1;toggleAdd(this,document.getElementById('pdrb-panel'))});
  document.getElementById('eks-add')?.addEventListener('click',function(){eksEditIndex=-1;toggleAdd(this,document.getElementById('eks-panel'))});
  document.getElementById('pdrb-save')?.addEventListener('click',function(){var row={uraian:document.getElementById('pdrb-uraian').value,y2025:document.getElementById('pdrb-2025').value,y2026:document.getElementById('pdrb-2026').value,y2027:document.getElementById('pdrb-2027').value,y2028:document.getElementById('pdrb-2028').value,y2029:document.getElementById('pdrb-2029').value};if(pdrbEditIndex>=0){pdrbRows[pdrbEditIndex]=row}else{pdrbRows.push(row)}save(k1,pdrbRows);renderPdrb();document.getElementById('pdrb-panel').style.display='none';var addBtn=document.getElementById('pdrb-add');addBtn.style.display='inline-flex';addBtn.innerHTML='<i class="fas fa-plus"></i> Tambah Data'});
  document.getElementById('eks-save')?.addEventListener('click',function(){var row={uraian:document.getElementById('eks-uraian').value,y2019:document.getElementById('eks-2019').value,y2020:document.getElementById('eks-2020').value,y2021:document.getElementById('eks-2021').value,y2022:document.getElementById('eks-2022').value,y2023:document.getElementById('eks-2023').value};if(eksEditIndex>=0){eksRows[eksEditIndex]=row}else{eksRows.push(row)}save(k2,eksRows);renderEks();document.getElementById('eks-panel').style.display='none';var addBtn=document.getElementById('eks-add');addBtn.style.display='inline-flex';addBtn.innerHTML='<i class="fas fa-plus"></i> Tambah Data'});
  document.getElementById('pdrb-cancel')?.addEventListener('click',function(){pdrbEditIndex=-1;document.getElementById('pdrb-panel').style.display='none';var addBtn=document.getElementById('pdrb-add');addBtn.style.display='inline-flex';addBtn.innerHTML='<i class="fas fa-plus"></i> Tambah Data'});
  document.getElementById('eks-cancel')?.addEventListener('click',function(){eksEditIndex=-1;document.getElementById('eks-panel').style.display='none';var addBtn=document.getElementById('eks-add');addBtn.style.display='inline-flex';addBtn.innerHTML='<i class="fas fa-plus"></i> Tambah Data'});
  document.getElementById('trade-tab-pdrb')?.addEventListener('click',function(){document.getElementById('trade-tab-pdrb').classList.add('btn-primary');document.getElementById('trade-tab-pdrb').classList.remove('btn-outline');document.getElementById('trade-tab-eks').classList.add('btn-outline');document.getElementById('trade-tab-eks').classList.remove('btn-primary');document.getElementById('pdrb-section').style.display='block';document.getElementById('eks-section').style.display='none'});
  document.getElementById('trade-tab-eks')?.addEventListener('click',function(){document.getElementById('trade-tab-eks').classList.add('btn-primary');document.getElementById('trade-tab-eks').classList.remove('btn-outline');document.getElementById('trade-tab-pdrb').classList.add('btn-outline');document.getElementById('trade-tab-pdrb').classList.remove('btn-primary');document.getElementById('pdrb-section').style.display='none';document.getElementById('eks-section').style.display='block'});
  var pdrbEditIndex=-1, eksEditIndex=-1;
  function openPdrbEditor(idx){pdrbEditIndex=idx;var r=pdrbRows[idx];document.getElementById('pdrb-uraian').value=r.uraian;document.getElementById('pdrb-2025').value=r.y2025;document.getElementById('pdrb-2026').value=r.y2026;document.getElementById('pdrb-2027').value=r.y2027;document.getElementById('pdrb-2028').value=r.y2028;document.getElementById('pdrb-2029').value=r.y2029;document.getElementById('pdrb-panel').style.display='block';document.getElementById('pdrb-add').style.display='none';document.getElementById('pdrb-close').style.display='inline-flex'}
  function bindPdrbRowActions(){document.querySelectorAll('[data-pdrb-edit]').forEach(function(btn){btn.addEventListener('click',function(){openPdrbEditor(parseInt(this.getAttribute('data-pdrb-edit'),10))})});document.querySelectorAll('[data-pdrb-del]').forEach(function(btn){btn.addEventListener('click',function(){var i=parseInt(this.getAttribute('data-pdrb-del'),10);Utils.confirm('Hapus baris ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(function(yes){if(!yes)return;pdrbRows.splice(i,1);save(k1,pdrbRows);renderPdrb();Utils.showToast('Baris dihapus','success');});})})}
  function openEksEditor(idx){eksEditIndex=idx;var r=eksRows[idx];document.getElementById('eks-uraian').value=r.uraian;document.getElementById('eks-2019').value=r.y2019;document.getElementById('eks-2020').value=r.y2020;document.getElementById('eks-2021').value=r.y2021;document.getElementById('eks-2022').value=r.y2022;document.getElementById('eks-2023').value=r.y2023;document.getElementById('eks-panel').style.display='block';document.getElementById('eks-add').style.display='none';document.getElementById('eks-close').style.display='inline-flex'}
  function bindEksRowActions(){document.querySelectorAll('[data-eks-edit]').forEach(function(btn){btn.addEventListener('click',function(){openEksEditor(parseInt(this.getAttribute('data-eks-edit'),10))})});document.querySelectorAll('[data-eks-del]').forEach(function(btn){btn.addEventListener('click',function(){var i=parseInt(this.getAttribute('data-eks-del'),10);Utils.confirm('Hapus baris ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(function(yes){if(!yes)return;eksRows.splice(i,1);save(k2,eksRows);renderEks();Utils.showToast('Baris dihapus','success');});})})}
</script>
@endpush
