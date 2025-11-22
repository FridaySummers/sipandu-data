@extends('layouts.app')
@section('title', 'DPMPTSP')
@section('body-class', 'dashboard-page force-light')

@section('content')
    <div class="page active" id="dpmptsp-page">
      <div class="card dpm-card">
        <div class="card-header dpm-header">
          <div class="head-left">
            <h3>Tabel Realisasi Investasi 2025–2029</h3>
            <div class="sub">Data perkembangan PMDN & PMA</div>
          </div>
          <div class="head-actions"><button class="btn btn-outline btn-sm" id="dpm-export"><i class="fas fa-download"></i> Export</button><button class="btn btn-primary btn-sm" id="dpm-add"><i class="fas fa-plus"></i> Tambah Data</button></div>
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
          <div class="modal-header"><h3>Tambah Data DPMPTSP</h3><button class="btn btn-outline btn-sm" id="dpm-close">✕</button></div>
          <div class="modal-body">
            <div class="tip-bar">💡 <span>Anda bisa menambahkan lebih dari 1 indikator. Klik "Tambah Row".</span></div>
            <div id="dpm-rows"></div>
            <div class="form-actions" style="margin-top:10px"><button class="btn btn-outline btn-sm" id="dpm-add-row">+ Tambah Row Baru</button></div>
          </div>
          <div class="modal-footer"><div class="hint" id="dpm-save-hint">Akan menyimpan 0 data</div><button class="btn btn-secondary" id="dpm-cancel">Batal</button><button class="btn btn-primary" id="dpm-save">Simpan Semua (0 data)</button></div>
        </div>
      </div>
    </div>
@endsection

@push('styles')
<style>
  .dpm-card{border-radius:16px;overflow:hidden}
  .dpm-header{padding:16px 20px;display:flex;align-items:center;justify-content:space-between;background:linear-gradient(90deg,#0ea5e9,#10b981);color:#fff}
  .dpm-header .sub{font-size:13px;opacity:.9}
  .dpm-table{width:100%;border:1px solid var(--gray-200);border-radius:12px;overflow:hidden;border-collapse:separate;border-spacing:0}
  .dpm-table thead th{background:#f8fafc;color:#111827;font-weight:600}
  .dpm-table th,.dpm-table td{border-bottom:1px solid var(--gray-200);border-right:1px solid var(--gray-200);padding:10px 12px}
  .dpm-table thead tr th:first-child{border-left:1px solid var(--gray-200)}
  .dpm-table tbody tr td:first-child{border-left:1px solid var(--gray-200)}
  .col-actions{width:120px}
  .chip{display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:9999px;font-size:12px;border:1px solid var(--gray-200);background:#fff;color:#374151}
  .chip.pmdn{background:#dcfce7;color:#166534;border-color:#bbf7d0}
  .chip.pma{background:#ede9fe;color:#6d28d9;border-color:#ddd6fe}
  .action-btn{background:#fff;border:1px solid var(--gray-300);border-radius:10px;width:28px;height:28px;display:inline-flex;align-items:center;justify-content:center;color:#374151}
  .action-btn:hover{background:#f8fafc}
  .dpm-modal{max-width:720px}
  .tip-bar{display:flex;align-items:center;gap:8px;padding:10px 12px;background:#ecfeff;color:#0e7490;border:1px solid #a5f3fc;border-radius:12px;margin-bottom:12px}
  .row-card{border:1px solid #a7f3d0;background:#f0fdf4;border-radius:12px;padding:12px;margin-bottom:10px}
  .row-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:10px}
  .row-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:8px}
  .form-row{display:flex;flex-direction:column;gap:6px}
  .year-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:8px}
  .row-actions{display:flex;gap:8px;justify-content:flex-end;margin-top:8px}
  .hint{margin-right:auto;color:#64748b}
</style>
@endpush

@push('scripts')
<script>
  var storageKey='sipandu_dmptsp';
  function loadRows(){try{return JSON.parse(localStorage.getItem(storageKey))||[]}catch(e){return []}}
  function saveRows(data){localStorage.setItem(storageKey,JSON.stringify(data))}
  var dpmRows=loadRows();
  if(!dpmRows.length){dpmRows=[
    {indikator:'Jumlah investor Berskala (nasional PMDN)',tipe:'PMDN',y2025:'12',y2026:'15',y2027:'18',y2028:'22',y2029:'25'},
    {indikator:'Nilai Investasi PMDN (Miliar Rupiah)',tipe:'PMDN',y2025:'250',y2026:'320',y2027:'400',y2028:'480',y2029:'550'},
    {indikator:'Jumlah investor Asing (PMA)',tipe:'PMA',y2025:'5',y2026:'8',y2027:'12',y2028:'15',y2029:'18'},
    {indikator:'Nilai Investasi PMA (Miliar Rupiah)',tipe:'PMA',y2025:'180',y2026:'240',y2027:'310',y2028:'390',y2029:'450'}
  ];saveRows(dpmRows)}
  function chipType(t){var cls=t==='PMDN'?'pmdn':'pma';return '<span class="chip '+cls+'">'+t+'</span>'}
  function renderTable(){var tb=document.getElementById('dpm-tbody');if(!tb)return;tb.innerHTML=dpmRows.map(function(r,i){return '<tr><td>'+(i+1)+'</td><td>'+r.indikator+'</td><td>'+chipType(r.tipe)+'</td><td>'+r.y2025+'</td><td>'+r.y2026+'</td><td>'+r.y2027+'</td><td>'+r.y2028+'</td><td>'+r.y2029+'</td><td><button class="action-btn" title="Edit" data-edit="'+i+'"><i class="fas fa-pen"></i></button> <button class="action-btn" title="Hapus" data-del="'+i+'"><i class="fas fa-trash"></i></button></td></tr>'}).join('')}
  renderTable();
  document.getElementById('dpm-tbody')?.addEventListener('click',function(e){var b=e.target.closest('button');if(!b)return;if(b.dataset.edit){var i=parseInt(b.dataset.edit,10);openModal([{indikator:dpmRows[i].indikator,tipe:dpmRows[i].tipe,y2025:dpmRows[i].y2025,y2026:dpmRows[i].y2026,y2027:dpmRows[i].y2027,y2028:dpmRows[i].y2028,y2029:dpmRows[i].y2029}],i)}else if(b.dataset.del){var i=parseInt(b.dataset.del,10);dpmRows.splice(i,1);saveRows(dpmRows);renderTable()}});
  function rowTemplate(idx,data){data=data||{indikator:'',tipe:'PMDN',y2025:'',y2026:'',y2027:'',y2028:'',y2029:''};return '<div class="row-card" data-row="'+idx+'"><div class="row-head"><div>Row #'+(idx+1)+'</div><button class="btn btn-outline btn-sm" data-remove="'+idx+'">Hapus</button></div><div class="form-row"><label>Indikator *</label><input type="text" class="form-control" data-key="indikator" value="'+(data.indikator||'')+'" placeholder="Contoh: Jumlah investor Berskala"></div><div class="form-row"><label>Tipe Investasi *</label><select class="form-control" data-key="tipe"><option '+(data.tipe==='PMDN'?'selected':'')+'>PMDN</option><option '+(data.tipe==='PMA'?'selected':'')+'>PMA</option></select></div><div class="year-grid"><div class="form-row"><label>2025</label><input type="text" class="form-control" data-key="y2025" value="'+(data.y2025||'')+'"></div><div class="form-row"><label>2026</label><input type="text" class="form-control" data-key="y2026" value="'+(data.y2026||'')+'"></div><div class="form-row"><label>2027</label><input type="text" class="form-control" data-key="y2027" value="'+(data.y2027||'')+'"></div><div class="form-row"><label>2028</label><input type="text" class="form-control" data-key="y2028" value="'+(data.y2028||'')+'"></div><div class="form-row"><label>2029</label><input type="text" class="form-control" data-key="y2029" value="'+(data.y2029||'')+'"></div></div></div>'}
  function openModal(prefill,editIdx){var m=document.getElementById('dpm-modal');m.style.display='flex';m.dataset.edit=editIdx!=null?editIdx:'';var wrap=document.getElementById('dpm-rows');wrap.innerHTML='';var rows=prefill&&prefill.length?prefill:[{}];rows.forEach(function(d,i){wrap.insertAdjacentHTML('beforeend',rowTemplate(i,d))});updateSaveCount();}
  function closeModal(){var m=document.getElementById('dpm-modal');m.style.display='none'}
  function addRow(){var wrap=document.getElementById('dpm-rows');var idx=wrap.children.length;wrap.insertAdjacentHTML('beforeend',rowTemplate(idx,{}));updateSaveCount()}
  function updateSaveCount(){var c=document.getElementById('dpm-rows').children.length;document.getElementById('dpm-save').textContent='Simpan Semua ('+c+' data)';document.getElementById('dpm-save-hint').textContent='Akan menyimpan '+c+' data'}
  document.getElementById('dpm-add')?.addEventListener('click',function(){openModal(null,null)});
  document.getElementById('dpm-add-row')?.addEventListener('click',function(){addRow()});
  document.getElementById('dpm-close')?.addEventListener('click',function(){closeModal()});
  document.getElementById('dpm-cancel')?.addEventListener('click',function(){closeModal()});
  document.getElementById('dpm-rows')?.addEventListener('click',function(e){var btn=e.target.closest('button');if(!btn)return;if(btn.dataset.remove!=null){var idx=parseInt(btn.dataset.remove,10);var wrap=document.getElementById('dpm-rows');if(wrap.children[idx]){wrap.children[idx].remove();updateSaveCount()}}});
  document.getElementById('dpm-save')?.addEventListener('click',function(){var wrap=document.getElementById('dpm-rows');var toSave=[];Array.from(wrap.children).forEach(function(card){var obj={};card.querySelectorAll('[data-key]').forEach(function(inp){var k=inp.dataset.key;obj[k]=inp.value});if(obj.indikator&&obj.tipe)toSave.push(obj)});var edit=document.getElementById('dpm-modal').dataset.edit;if(edit!==''){var i=parseInt(edit,10);dpmRows[i]=toSave[0]||dpmRows[i]}else{dpmRows=dpmRows.concat(toSave)}saveRows(dpmRows);renderTable();closeModal()});
  document.getElementById('dpm-export')?.addEventListener('click',function(){var rows=[['No','Indikator','Tipe','2025','2026','2027','2028','2029']];dpmRows.forEach(function(r,i){rows.push([i+1,r.indikator,r.tipe,r.y2025,r.y2026,r.y2027,r.y2028,r.y2029])});var csv=rows.map(function(x){return x.map(function(v){var s=(''+v).replace(/"/g,'""');return '"'+s+'"'}).join(',')}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download='dpmptsp-investasi.csv';document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url)});
</script>
@endpush
