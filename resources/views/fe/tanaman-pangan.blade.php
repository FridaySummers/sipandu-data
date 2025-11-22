@extends('layouts.app')
@section('title', 'Tanaman Pangan')
@section('body-class', 'dashboard-page force-light')
@push('styles')
<style>
.tp-toolbar{display:flex;align-items:center;justify-content:flex-start;margin-bottom:12px;gap:12px}
.tp-tabs{display:flex;gap:14px;background:#f7fafc;border:1px solid #e5e7eb;border-radius:9999px;padding:10px 14px;width:100%;justify-content:space-between}
.tp-tabs .btn{border-radius:9999px;padding:10px 22px;font-weight:600;transition:all .2s ease;font-size:14px;flex:1 1 auto;text-align:center;min-width:120px}
.tp-tabs .btn-primary{background:#ffffff;color:#065f46;border:1px solid #a7f3d0}
.tp-tabs .btn-outline{background:#f7fafc;color:#065f46;border:1px solid #e5e7eb}
.tp-tabs .btn:hover{transform:translateY(-0.5px)}
.tp-tabs .btn-outline:hover{background:#e7f0ff}
.tp-card{border-radius:16px;overflow:hidden}
.tp-card .card-header{padding:14px 18px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #e5e7eb;background:#ffffff;color:#111827}
.tp-group{margin:14px 0 22px}
.tp-group .group-header{display:flex;align-items:center;justify-content:space-between;background:#d1fae5;color:#064e3b;border:1px solid #a7f3d0;border-radius:12px 12px 0 0;padding:10px 12px}
.tp-group .badge{background:#10b981;color:#fff;border-radius:9999px;width:24px;height:24px;display:inline-flex;align-items:center;justify-content:center;font-weight:700;margin-right:8px}
.tp-group .panel{border-radius:0 0 12px 12px;margin:0}
.tp-panel{margin:16px;border-radius:16px;padding:16px;border:1px solid #e5e7eb;background:#f8fafc}
.tp-panel .form-group{display:flex;flex-direction:column;gap:8px;margin-bottom:8px}
.tp-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:12px}
.year-group{display:flex;flex-direction:column;gap:6px}
.year-label{font-size:12px;color:#065f46;font-weight:600}
.tp-table{width:100%;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;border-collapse:separate;border-spacing:0;table-layout:fixed}
.tp-table thead th{background:#ffffff;color:#111827;font-weight:600}
.tp-table.pangan thead th{background:#ffffff}
.tp-table.sayur{border-color:#84cc16}
.tp-table.sayur th,.tp-table.sayur td{border-color:#84cc16}
.tp-table.sayur thead th{background:#dcfce7;color:#064e3b}
.tp-table.sayur thead .year{background:#dcfce7}
.tp-panel.sayur{background:#ecfdf5;border-color:#86efac}
.tp-panel.sayur .year-label{color:#065f46}
.tp-table th,.tp-table td{border-bottom:1px solid #e5e7eb;border-right:1px solid #e5e7eb;padding:10px 12px}
.tp-table thead tr th:first-child{border-left:1px solid #e5e7eb}
.tp-table tbody tr td:first-child{border-left:1px solid #e5e7eb}
.tp-table th{font-weight:600;text-align:center}
.tp-table td,.tp-table th{padding:10px 12px}
.tp-table td:nth-child(1),.tp-table td:nth-child(2),.tp-table th:nth-child(1),.tp-table th:nth-child(2){text-align:left}
.tp-table tbody tr:nth-child(even){background:#f8fafc}
.tp-table tbody tr:hover{background:#f1f5f9}
.tp-table td:not(:nth-child(1)):not(:nth-child(2)){text-align:center}
.tp-table-wrap{border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;background:#fff;box-shadow:0 1px 6px rgba(0,0,0,0.04)}
.edit-cell{border:1px solid #cbd5e1;background:#ffffff;border-radius:10px;padding:8px 10px}
.btn-green{background:#22c55e;border-color:#22c55e;color:#fff}
.btn-lime{background:#84cc16;border-color:#84cc16;color:#fff}
.form-control{border:1px solid #cbd5e1;background:#ffffff;border-radius:12px;padding:10px 12px}
.form-control:focus{outline:none;border-color:#86efac;box-shadow:0 0 0 3px rgba(134,239,172,0.35)}
</style>
@endpush

@section('content')
    <div class="page active" id="tanaman-pangan-page">
      <div class="page-header"><h1>Data Tanaman Pangan & Sayuran</h1><p>Kabupaten Kolaka Utara Tahun 2019 - 2023</p></div>
      <div class="tp-toolbar"><div class="tp-tabs"><button class="btn btn-primary btn-sm" id="tp-tab-pangan">Tanaman Pangan (Accordion)</button><button class="btn btn-outline btn-sm" id="tp-tab-sayur">Sayuran (Tabel)</button></div></div>
      <div id="tp-pangan">
        <div class="card tp-card theme-pangan">
          <div class="card-header"><h3>Tambah Jenis Tanaman Baru</h3><div class="card-actions"><button class="btn btn-outline btn-sm" id="tp-add-pangan">+ Tambah Jenis Tanaman</button></div></div>
          <div class="card-body">
            <div class="tp-panel pangan" id="tp-panel-pangan" style="display:none;">
              <div class="form-group"><label>Jenis Tanaman</label><input type="text" id="tp-jenis" class="form-control" placeholder="Contoh: Ubi Kayu"></div>
              <div class="form-group"><label>i. Luas Panen (ha)</label><div class="tp-grid"><div class="year-group"><div class="year-label">2019</div><input class="form-control" id="luas-2019" placeholder="0.00"></div><div class="year-group"><div class="year-label">2020</div><input class="form-control" id="luas-2020" placeholder="0.00"></div><div class="year-group"><div class="year-label">2021</div><input class="form-control" id="luas-2021" placeholder="0.00"></div><div class="year-group"><div class="year-label">2022</div><input class="form-control" id="luas-2022" placeholder="0.00"></div><div class="year-group"><div class="year-label">2023</div><input class="form-control" id="luas-2023" placeholder="0.00"></div></div></div>
              <div class="form-group"><label>ii. Produksi (ton)</label><div class="tp-grid"><div class="year-group"><div class="year-label">2019</div><input class="form-control" id="prod-2019" placeholder="0.00"></div><div class="year-group"><div class="year-label">2020</div><input class="form-control" id="prod-2020" placeholder="0.00"></div><div class="year-group"><div class="year-label">2021</div><input class="form-control" id="prod-2021" placeholder="0.00"></div><div class="year-group"><div class="year-label">2022</div><input class="form-control" id="prod-2022" placeholder="0.00"></div><div class="year-group"><div class="year-label">2023</div><input class="form-control" id="prod-2023" placeholder="0.00"></div></div></div>
              <div class="form-group"><label>iii. Produktivitas (ku/ha)</label><div class="tp-grid"><div class="year-group"><div class="year-label">2019</div><input class="form-control" id="pv-2019" placeholder="0.00"></div><div class="year-group"><div class="year-label">2020</div><input class="form-control" id="pv-2020" placeholder="0.00"></div><div class="year-group"><div class="year-label">2021</div><input class="form-control" id="pv-2021" placeholder="0.00"></div><div class="year-group"><div class="year-label">2022</div><input class="form-control" id="pv-2022" placeholder="0.00"></div><div class="year-group"><div class="year-label">2023</div><input class="form-control" id="pv-2023" placeholder="0.00"></div></div></div>
              <div style="display:flex;gap:8px;justify-content:flex-end"><button class="btn btn-secondary" id="tp-cancel-pangan">Batal</button><button class="btn btn-green" id="tp-save-pangan">Simpan Tanaman</button></div>
            </div>
            <div class="toolbar" style="margin-bottom:8px"><div class="toolbar-right"><button class="btn btn-outline btn-sm" id="tp-export"><i class="fas fa-download"></i> Export Data</button></div></div>
            <div class="tp-table-wrap" id="tp-accordion"></div>
          </div>
        </div>
      </div>
      <div id="tp-sayur" style="display:none;">
        <div class="card tp-card theme-sayur">
          <div class="card-header"><h3>Tambah Data Sayuran</h3><div class="card-actions"><button class="btn btn-outline btn-sm" id="tp-add-sayur">+ Tambah Komoditas</button></div></div>
          <div class="card-body">
            <div class="tp-panel sayur" id="tp-panel-sayur" style="display:none;">
              <div class="form-group"><label>Jenis Komoditas</label><input type="text" id="syur-jenis" class="form-control" placeholder="Contoh: Tomat"></div>
              <div class="tp-grid">
                <div class="year-group"><div class="year-label">Tahun 2019</div><input class="form-control" id="syur-luas-2019" placeholder="Luas (ha)"><input class="form-control" id="syur-prod-2019" placeholder="Produksi (Kw)"></div>
                <div class="year-group"><div class="year-label">Tahun 2020</div><input class="form-control" id="syur-luas-2020" placeholder="Luas (ha)"><input class="form-control" id="syur-prod-2020" placeholder="Produksi (Kw)"></div>
                <div class="year-group"><div class="year-label">Tahun 2021</div><input class="form-control" id="syur-luas-2021" placeholder="Luas (ha)"><input class="form-control" id="syur-prod-2021" placeholder="Produksi (Kw)"></div>
                <div class="year-group"><div class="year-label">Tahun 2022</div><input class="form-control" id="syur-luas-2022" placeholder="Luas (ha)"><input class="form-control" id="syur-prod-2022" placeholder="Produksi (Kw)"></div>
                <div class="year-group"><div class="year-label">Tahun 2023</div><input class="form-control" id="syur-luas-2023" placeholder="Luas (ha)"><input class="form-control" id="syur-prod-2023" placeholder="Produksi (Kw)"></div>
              </div>
              <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px"><button class="btn btn-secondary" id="tp-cancel-sayur">Batal</button><button class="btn btn-green" id="tp-save-sayur">Simpan</button></div>
            </div>
            <div class="tp-table-wrap"><table class="table table-compact tp-table sayur"><thead><tr><th rowspan="2">No</th><th rowspan="2">Jenis Komoditas</th><th class="year" colspan="2">Tahun 2019</th><th class="year" colspan="2">Tahun 2020</th><th class="year" colspan="2">Tahun 2021</th><th class="year" colspan="2">Tahun 2022</th><th class="year" colspan="2">Tahun 2023</th><th rowspan="2">Aksi</th></tr><tr><th>Luas Tanaman (ha)</th><th>Produksi (Kw)</th><th>Luas Tanaman (ha)</th><th>Produksi (Kw)</th><th>Luas Tanaman (ha)</th><th>Produksi (Kw)</th><th>Luas Tanaman (ha)</th><th>Produksi (Kw)</th><th>Luas Tanaman (ha)</th><th>Produksi (Kw)</th></tr></thead><tbody id="syur-tbody"></tbody></table></div>
          </div>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
var panganGroups=[{name:'Padi',rows:[{no:1,label:'Luas Panen (ha)',y2019:'3,560.00',y2020:'2,680.00',y2021:'2,742.50',y2022:'2,742.50',y2023:'1,629.00'},{no:2,label:'Produksi (ton)',y2019:'16,635.88',y2020:'',y2021:'12,341.25',y2022:'12,341.25',y2023:'7,742'},{no:3,label:'Produktivitas (ku/ha)',y2019:'46.73',y2020:'56.74',y2021:'45',y2022:'45',y2023:'42.06'}]},{name:'Jagung',rows:[{no:1,label:'Luas Panen (ha)',y2019:'',y2020:'',y2021:'',y2022:'',y2023:''}]}];
function renderAccordion(){var wrap=document.getElementById('tp-accordion');if(!wrap)return;wrap.innerHTML=panganGroups.map(function(g,i){var header='<div class="group-header"><div><span class="badge">'+(i+1)+'</span>'+g.name+'</div><div><button class="btn btn-outline btn-sm" data-del="'+i+'" title="Hapus"><i class="fas fa-trash"></i></button></div></div>';var table='<div class="panel" style="background:#ecfeff;border-color:#a7f3d0"><div class="tp-table-wrap"><table class="table table-compact tp-table pangan"><thead><tr><th>No.</th><th>Jenis Tanaman</th><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody>'+g.rows.map(function(r){return '<tr data-group="'+i+'" data-row="'+(r.no-1)+'"><td>'+r.no+'</td><td>'+r.label+'</td><td class="c-2019">'+r.y2019+'</td><td class="c-2020">'+r.y2020+'</td><td class="c-2021">'+r.y2021+'</td><td class="c-2022">'+r.y2022+'</td><td class="c-2023">'+r.y2023+'</td></tr>'}).join('')+'</tbody></table></div></div>';return '<div class="tp-group">'+header+table+'</div>';}).join('');}
renderAccordion();
document.getElementById('tp-tab-pangan')?.addEventListener('click',function(){document.getElementById('tp-tab-pangan').classList.add('btn-primary');document.getElementById('tp-tab-pangan').classList.remove('btn-outline');document.getElementById('tp-tab-sayur').classList.add('btn-outline');document.getElementById('tp-tab-sayur').classList.remove('btn-primary');document.getElementById('tp-pangan').style.display='block';document.getElementById('tp-sayur').style.display='none';});
document.getElementById('tp-tab-sayur')?.addEventListener('click',function(){document.getElementById('tp-tab-sayur').classList.add('btn-primary');document.getElementById('tp-tab-sayur').classList.remove('btn-outline');document.getElementById('tp-tab-pangan').classList.add('btn-outline');document.getElementById('tp-tab-pangan').classList.remove('btn-primary');document.getElementById('tp-pangan').style.display='none';document.getElementById('tp-sayur').style.display='block';});
document.getElementById('tp-add-pangan')?.addEventListener('click',function(){var p=document.getElementById('tp-panel-pangan');var show=p.style.display==='none';p.style.display=show?'block':'none';this.textContent=show?'− Tutup Form':'+ Tambah Jenis Tanaman';});
document.getElementById('tp-cancel-pangan')?.addEventListener('click',function(){document.getElementById('tp-panel-pangan').style.display='none';document.getElementById('tp-add-pangan').textContent='+ Tambah Jenis Tanaman';});
document.getElementById('tp-save-pangan')?.addEventListener('click',function(){var g={name:document.getElementById('tp-jenis').value,rows:[{no:1,label:'Luas Panen (ha)',y2019:document.getElementById('luas-2019').value,y2020:document.getElementById('luas-2020').value,y2021:document.getElementById('luas-2021').value,y2022:document.getElementById('luas-2022').value,y2023:document.getElementById('luas-2023').value},{no:2,label:'Produksi (ton)',y2019:document.getElementById('prod-2019').value,y2020:document.getElementById('prod-2020').value,y2021:document.getElementById('prod-2021').value,y2022:document.getElementById('prod-2022').value,y2023:document.getElementById('prod-2023').value},{no:3,label:'Produktivitas (ku/ha)',y2019:document.getElementById('pv-2019').value,y2020:document.getElementById('pv-2020').value,y2021:document.getElementById('pv-2021').value,y2022:document.getElementById('pv-2022').value,y2023:document.getElementById('pv-2023').value}]};panganGroups.push(g);renderAccordion();document.getElementById('tp-panel-pangan').style.display='none';document.getElementById('tp-add-pangan').textContent='+ Tambah Jenis Tanaman';});
document.getElementById('tp-accordion')?.addEventListener('dblclick',function(e){var td=e.target.closest('td');if(!td)return;if(!td.classList.contains('c-2019')&&!td.classList.contains('c-2020')&&!td.classList.contains('c-2021')&&!td.classList.contains('c-2022')&&!td.classList.contains('c-2023'))return;var val=td.textContent;var controls='<div style="display:flex;align-items:center;gap:6px"><input class="edit-cell" value="'+val+'" style="flex:1"><button class="btn btn-green btn-xs ok"><i class="fas fa-check"></i></button><button class="btn btn-outline btn-xs cancel"><i class="fas fa-times"></i></button></div>';td.setAttribute('data-prev',val);td.innerHTML=controls;});
document.getElementById('tp-accordion')?.addEventListener('click',function(e){var ok=e.target.closest('.ok');var cancel=e.target.closest('.cancel');if(!ok&&!cancel)return;var td=e.target.closest('td');var tr=td.closest('tr');var gi=parseInt(tr.dataset.group,10);var ri=parseInt(tr.dataset.row,10);var year=td.className.split('-')[1];var key='y'+year;if(ok){var val=td.querySelector('input').value;panganGroups[gi].rows[ri][key]=val;td.textContent=val;}else{td.textContent=td.getAttribute('data-prev');}});
var syurRows=[{no:1,jenis:'Bawang Merah',l2019:'21',p2019:'755',l2020:'52',p2020:'976',l2021:'20',p2021:'745',l2022:'18',p2022:'666',l2023:'12',p2023:'370'}];
function renderSayur(){var tb=document.getElementById('syur-tbody');if(!tb)return;tb.innerHTML=syurRows.map(function(r){return '<tr data-row="'+r.no+'"><td>'+r.no+'</td><td class="c-jenis">'+r.jenis+'</td><td class="c-l2019">'+r.l2019+'</td><td class="c-p2019">'+r.p2019+'</td><td class="c-l2020">'+r.l2020+'</td><td class="c-p2020">'+r.p2020+'</td><td class="c-l2021">'+r.l2021+'</td><td class="c-p2021">'+r.p2021+'</td><td class="c-l2022">'+r.l2022+'</td><td class="c-p2022">'+r.p2022+'</td><td class="c-l2023">'+r.l2023+'</td><td class="c-p2023">'+r.p2023+'</td><td><button class="btn btn-outline btn-sm sy-edit-row">Edit</button></td></tr>';}).join('');}
renderSayur();
document.getElementById('tp-add-sayur')?.addEventListener('click',function(){var p=document.getElementById('tp-panel-sayur');var show=p.style.display==='none';p.style.display=show?'block':'none';this.textContent=show?'− Tutup Form':'+ Tambah Komoditas';});
document.getElementById('tp-cancel-sayur')?.addEventListener('click',function(){document.getElementById('tp-panel-sayur').style.display='none';document.getElementById('tp-add-sayur').textContent='+ Tambah Komoditas';});
document.getElementById('tp-save-sayur')?.addEventListener('click',function(){syurRows.push({no:syurRows.length+1,jenis:document.getElementById('syur-jenis').value,l2019:document.getElementById('syur-luas-2019').value,l2020:document.getElementById('syur-luas-2020').value,l2021:document.getElementById('syur-luas-2021').value,l2022:document.getElementById('syur-luas-2022').value,l2023:document.getElementById('syur-luas-2023').value,p2019:document.getElementById('syur-prod-2019').value,p2020:document.getElementById('syur-prod-2020').value,p2021:document.getElementById('syur-prod-2021').value,p2022:document.getElementById('syur-prod-2022').value,p2023:document.getElementById('syur-prod-2023').value});renderSayur();document.getElementById('tp-panel-sayur').style.display='none';document.getElementById('tp-add-sayur').textContent='+ Tambah Komoditas';});
document.getElementById('syur-tbody')?.addEventListener('dblclick',function(e){var td=e.target.closest('td');if(!td)return;if(!td.className.match(/^c\-/))return;var val=td.textContent;var controls='<div style="display:flex;align-items:center;gap:6px"><input class="edit-cell" value="'+val+'" style="flex:1"><button class="btn btn-green btn-xs ok"><i class="fas fa-check"></i></button><button class="btn btn-outline btn-xs cancel"><i class="fas fa-times"></i></button></div>';td.setAttribute('data-prev',val);td.innerHTML=controls;});
document.getElementById('syur-tbody')?.addEventListener('click',function(e){var ok=e.target.closest('.ok');var cancel=e.target.closest('.cancel');if(!ok&&!cancel)return;var td=e.target.closest('td');var tr=td.closest('tr');var idx=parseInt(tr.dataset.row,10)-1;var r=syurRows[idx];if(ok){var val=td.querySelector('input').value;var cls=td.className;switch(true){case /c-jenis/.test(cls): r.jenis=val; break;case /c-l2019/.test(cls): r.l2019=val; break;case /c-p2019/.test(cls): r.p2019=val; break;case /c-l2020/.test(cls): r.l2020=val; break;case /c-p2020/.test(cls): r.p2020=val; break;case /c-l2021/.test(cls): r.l2021=val; break;case /c-p2021/.test(cls): r.p2021=val; break;case /c-l2022/.test(cls): r.l2022=val; break;case /c-p2022/.test(cls): r.p2022=val; break;case /c-l2023/.test(cls): r.l2023=val; break;case /c-p2023/.test(cls): r.p2023=val; break;}td.textContent=val;}else{td.textContent=td.getAttribute('data-prev');}}
);
</script>
@endpush
