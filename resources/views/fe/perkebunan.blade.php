@extends('layouts.app')
@section('title', 'Perkebunan')
@section('body-class', 'dashboard-page force-light')

@push('styles')
<style>
.pk-toolbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px}
.pk-tabs{display:flex;gap:14px;background:#f7fafc;border:1px solid #e5e7eb;border-radius:9999px;padding:10px 14px;width:100%;justify-content:space-between}
.pk-tabs .btn{border-radius:9999px;padding:10px 22px;font-weight:600;transition:all .2s ease;font-size:14px;flex:1 1 auto;text-align:center;min-width:120px}
.pk-tabs .btn-primary{background:#ffffff;color:#065f46;border:1px solid #a7f3d0;box-shadow:0 2px 6px rgba(134,239,172,.25)}
.pk-tabs .btn-outline{background:#f7fafc;color:#065f46;border:1px solid #e5e7eb}
.pk-tabs .btn:hover{transform:translateY(-0.5px)}
.pk-tabs .btn-outline:hover{background:#e7f0ff}
.pk-card .card-actions .btn{border-radius:9999px;padding:8px 14px;font-weight:600}
.btn-green{background:#22c55e;border-color:#22c55e;color:#fff}
.pk-card{border-radius:16px;overflow:hidden;margin-bottom:18px}
.pk-card .card-header{padding:14px 18px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #e5e7eb;color:#fff}
.pk-panel{margin:16px;border-radius:16px;padding:16px;border:1px solid #e5e7eb;background:#f8fafc}
.pk-panel .form-group{display:flex;flex-direction:column;gap:8px;margin-bottom:8px}
.pk-grid{display:grid;gap:12px}
.pk-grid-5{grid-template-columns:repeat(5,1fr)}
.pk-grid-6{grid-template-columns:repeat(6,1fr)}
.th-group-year{background:#eaf2ff;font-weight:600}
.year-group{display:flex;flex-direction:column;gap:6px}
.theme-green .year-label{font-size:12px;color:#065f46;font-weight:600}
.theme-amber .year-label{font-size:12px;color:#92400e;font-weight:600}
.theme-teal .year-label{font-size:12px;color:#115e59;font-weight:600}
.pk-table{border:1px solid #e5e7eb;border-radius:12px;overflow:hidden}
.pk-table thead th{color:#111827}
.pk-table th,.pk-table td{border-bottom:1px solid #e5e7eb;border-right:1px solid #e5e7eb}
.pk-table tr th:first-child,.pk-table tr td:first-child{border-left:1px solid #e5e7eb}
.pk-table th{font-weight:600;text-align:center}
.pk-table td,.pk-table th{padding:10px 12px}
.pk-table td:nth-child(1),.pk-table td:nth-child(2),.pk-table th:nth-child(1),.pk-table th:nth-child(2){text-align:left}
.pk-table tbody tr:nth-child(even){background:#f8fafc}
.pk-table tbody tr:hover{background:#f1f5f9}
.pk-table td:not(:nth-child(1)):not(:nth-child(2)){text-align:center}
.pk-table-wrap{border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;background:#fff;box-shadow:0 1px 6px rgba(0,0,0,0.04)}
.edit-cell{border:1px solid #cbd5e1;background:#ffffff;border-radius:10px;padding:8px 10px}
.form-control{border:1px solid #cbd5e1;background:#ffffff;border-radius:12px;padding:10px 12px}
.form-control:focus{outline:none;border-color:#86efac;box-shadow:0 0 0 3px rgba(134,239,172,0.35)}
.theme-green .card-header{background:#ecfdf5;color:#065f46}
.theme-green .pk-panel{background:#ecfdf5;border-color:#a7f3d0}
.theme-amber .card-header{background:#fef3c7;color:#92400e}
.theme-amber .pk-panel{background:#fef3c7;border-color:#fde68a}
.theme-teal .card-header{background:#f0fdfa;color:#115e59}
.theme-teal .pk-panel{background:#f0fdfa;border-color:#99f6e4}
.theme-green .pk-table thead th{background:#ecfdf5}
.theme-amber .pk-table thead th{background:#fef3c7}
.theme-teal .pk-table thead th{background:#f0fdfa}
</style>
@endpush

@section('content')
    <div class="page active" id="perkebunan-page">
      <div class="page-header"><h1>Perkebunan</h1><p>Populasi Ternak, Produksi Tanaman, dan Luas Areal (2019-2023)</p></div>
      <div class="pk-toolbar"><div class="pk-tabs"><button class="btn btn-primary btn-sm" id="pk-tab-pop">Populasi Ternak</button><button class="btn btn-outline btn-sm" id="pk-tab-prod">Produksi Tanaman</button><button class="btn btn-outline btn-sm" id="pk-tab-luas">Luas Areal</button></div><div></div></div>

      <div id="pk-pop">
        <div class="card pk-card theme-green">
          <div class="card-header"><h3>Populasi Ternak (2019-2023)</h3><div class="card-actions"><button class="btn btn-green btn-sm" id="pk-pop-add">+ Tambah Baris</button> <button class="btn btn-outline btn-sm" id="pk-pop-export"><i class="fas fa-download"></i> Export Data</button></div></div>
          <div class="card-body">
            <div class="pk-panel" id="pk-pop-panel" style="display:none;">
              <div class="form-group"><label>Uraian</label><input type="text" id="pop-uraian" class="form-control" placeholder="Contoh: Sapi Potong"></div>
              <div class="pk-grid pk-grid-6">
                <div class="year-group"><div class="year-label">2019</div><input class="form-control" id="pop-2019" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2020</div><input class="form-control" id="pop-2020" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2021</div><input class="form-control" id="pop-2021" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2022</div><input class="form-control" id="pop-2022" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2023</div><input class="form-control" id="pop-2023" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">Trend (%)</div><input class="form-control" id="pop-trend" placeholder="0.00"></div>
              </div>
              <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px"><button class="btn btn-secondary" id="pk-pop-cancel">Batal</button><button class="btn btn-primary" id="pk-pop-save">Simpan</button></div>
            </div>
            <div class="pk-table-wrap"><table class="table table-compact pk-table"><thead><tr><th rowspan="2">No.</th><th rowspan="2">Uraian</th><th class="th-group-year" colspan="5">Tahun</th><th rowspan="2">Trend (%)</th></tr><tr><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody id="pk-pop-tbody"></tbody></table></div>
          </div>
        </div>
      </div>

      <div id="pk-prod" style="display:none;">
        <div class="card pk-card theme-amber">
          <div class="card-header"><h3>Produksi Tanaman Perkebunan (2019-2023)</h3><div class="card-actions"><button class="btn btn-green btn-sm" id="pk-prod-add">+ Tambah Komoditas</button> <button class="btn btn-outline btn-sm" id="pk-prod-export"><i class="fas fa-download"></i> Export Data</button></div></div>
          <div class="card-body">
            <div class="pk-panel" id="pk-prod-panel" style="display:none;">
              <div class="form-group"><label>Komoditas</label><input type="text" id="prod-uraian" class="form-control" placeholder="Contoh: Kakao"></div>
              <div class="pk-grid pk-grid-5">
                <div class="year-group"><div class="year-label">2019</div><input class="form-control" id="prod-2019" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2020</div><input class="form-control" id="prod-2020" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2021</div><input class="form-control" id="prod-2021" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2022</div><input class="form-control" id="prod-2022" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2023</div><input class="form-control" id="prod-2023" placeholder="0.00"></div>
              </div>
              <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px"><button class="btn btn-secondary" id="pk-prod-cancel">Batal</button><button class="btn btn-primary" id="pk-prod-save">Simpan</button></div>
            </div>
            <div class="pk-table-wrap"><table class="table table-compact pk-table"><thead><tr><th>No.</th><th>Komoditas</th><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody id="pk-prod-tbody"></tbody></table></div>
          </div>
        </div>
      </div>

      <div id="pk-luas" style="display:none;">
        <div class="card pk-card theme-teal">
          <div class="card-header"><h3>Luas Areal Tanaman Perkebunan (ha)</h3><div class="card-actions"><button class="btn btn-green btn-sm" id="pk-luas-add">+ Tambah Komoditas</button> <button class="btn btn-outline btn-sm" id="pk-luas-export"><i class="fas fa-download"></i> Export Data</button></div></div>
          <div class="card-body">
            <div class="pk-panel" id="pk-luas-panel" style="display:none;">
              <div class="form-group"><label>Komoditas</label><input type="text" id="luas-uraian" class="form-control" placeholder="Contoh: Kelapa"></div>
              <div class="pk-grid pk-grid-5">
                <div class="year-group"><div class="year-label">2019</div><input class="form-control" id="luas-2019" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2020</div><input class="form-control" id="luas-2020" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2021</div><input class="form-control" id="luas-2021" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2022</div><input class="form-control" id="luas-2022" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2023</div><input class="form-control" id="luas-2023" placeholder="0.00"></div>
              </div>
              <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px"><button class="btn btn-secondary" id="pk-luas-cancel">Batal</button><button class="btn btn-primary" id="pk-luas-save">Simpan</button></div>
            </div>
            <div class="pk-table-wrap"><table class="table table-compact pk-table"><thead><tr><th>No.</th><th>Komoditas</th><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody id="pk-luas-tbody"></tbody></table></div>
          </div>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
var popData=[{no:1,uraian:"Sapi Potong",y2019:"",y2020:"",y2021:"",y2022:"",y2023:"",trend:""},{no:2,uraian:"Kambing",y2019:"",y2020:"",y2021:"",y2022:"",y2023:"",trend:""}];
var prodData=[{no:1,uraian:"Kakao",y2019:"",y2020:"",y2021:"",y2022:"",y2023:""},{no:2,uraian:"Kopi",y2019:"",y2020:"",y2021:"",y2022:"",y2023:""},{no:3,uraian:"Kelapa",y2019:"",y2020:"",y2021:"",y2022:"",y2023:""}];
var luasData=[{no:1,uraian:"Kakao",y2019:"",y2020:"",y2021:"",y2022:"",y2023:""},{no:2,uraian:"Cengkeh",y2019:"",y2020:"",y2021:"",y2022:"",y2023:""},{no:3,uraian:"Kopi",y2019:"",y2020:"",y2021:"",y2022:"",y2023:""}];
function renderRows(data,tb,includeTrend){tb.innerHTML=data.map(function(r){var cells='<td>'+r.no+'</td><td class="c-uraian">'+r.uraian+'</td><td class="c-y2019">'+r.y2019+'</td><td class="c-y2020">'+r.y2020+'</td><td class="c-y2021">'+r.y2021+'</td><td class="c-y2022">'+r.y2022+'</td><td class="c-y2023">'+r.y2023+'</td>';if(includeTrend){cells+='<td class="c-trend">'+(r.trend||'')+'</td>';}return '<tr data-row="'+r.no+'">'+cells+'</tr>';}).join('');}
function init(){renderRows(popData,document.getElementById('pk-pop-tbody'),true);renderRows(prodData,document.getElementById('pk-prod-tbody'),false);renderRows(luasData,document.getElementById('pk-luas-tbody'),false);}
init();
function toggleTab(active){['pk-pop','pk-prod','pk-luas'].forEach(function(id){document.getElementById(id).style.display=id===active?'block':'none';});var tp=document.getElementById('pk-tab-pop');var td=document.getElementById('pk-tab-prod');var tl=document.getElementById('pk-tab-luas');[tp,td,tl].forEach(function(b){b.classList.add('btn-outline');b.classList.remove('btn-primary');});if(active==='pk-pop'){tp.classList.add('btn-primary');tp.classList.remove('btn-outline');}else if(active==='pk-prod'){td.classList.add('btn-primary');td.classList.remove('btn-outline');}else{tl.classList.add('btn-primary');tl.classList.remove('btn-outline');}}
document.getElementById('pk-tab-pop')?.addEventListener('click',function(){toggleTab('pk-pop');});
document.getElementById('pk-tab-prod')?.addEventListener('click',function(){toggleTab('pk-prod');});
document.getElementById('pk-tab-luas')?.addEventListener('click',function(){toggleTab('pk-luas');});

function toggle(btn,panel){var open=panel.style.display!=='none';panel.style.display=open?'none':'block';btn.textContent=open?'+ Tambah Baris':'− Tutup Form';}
document.getElementById('pk-pop-add')?.addEventListener('click',function(){toggle(this,document.getElementById('pk-pop-panel'));});
document.getElementById('pk-prod-add')?.addEventListener('click',function(){toggle(this,document.getElementById('pk-prod-panel'));});
document.getElementById('pk-luas-add')?.addEventListener('click',function(){toggle(this,document.getElementById('pk-luas-panel'));});

document.getElementById('pk-pop-cancel')?.addEventListener('click',function(){document.getElementById('pk-pop-panel').style.display='none';document.getElementById('pk-pop-add').textContent='+ Tambah Baris';});
document.getElementById('pk-prod-cancel')?.addEventListener('click',function(){document.getElementById('pk-prod-panel').style.display='none';document.getElementById('pk-prod-add').textContent='+ Tambah Komoditas';});
document.getElementById('pk-luas-cancel')?.addEventListener('click',function(){document.getElementById('pk-luas-panel').style.display='none';document.getElementById('pk-luas-add').textContent='+ Tambah Komoditas';});

document.getElementById('pk-pop-save')?.addEventListener('click',function(){popData.push({no:popData.length+1,uraian:document.getElementById('pop-uraian').value,y2019:document.getElementById('pop-2019').value,y2020:document.getElementById('pop-2020').value,y2021:document.getElementById('pop-2021').value,y2022:document.getElementById('pop-2022').value,y2023:document.getElementById('pop-2023').value,trend:document.getElementById('pop-trend').value});renderRows(popData,document.getElementById('pk-pop-tbody'));document.getElementById('pk-pop-panel').style.display='none';document.getElementById('pk-pop-add').textContent='+ Tambah Baris';});
document.getElementById('pk-prod-save')?.addEventListener('click',function(){prodData.push({no:prodData.length+1,uraian:document.getElementById('prod-uraian').value,y2019:document.getElementById('prod-2019').value,y2020:document.getElementById('prod-2020').value,y2021:document.getElementById('prod-2021').value,y2022:document.getElementById('prod-2022').value,y2023:document.getElementById('prod-2023').value});renderRows(prodData,document.getElementById('pk-prod-tbody'),false);document.getElementById('pk-prod-panel').style.display='none';document.getElementById('pk-prod-add').textContent='+ Tambah Komoditas';});
document.getElementById('pk-luas-save')?.addEventListener('click',function(){luasData.push({no:luasData.length+1,uraian:document.getElementById('luas-uraian').value,y2019:document.getElementById('luas-2019').value,y2020:document.getElementById('luas-2020').value,y2021:document.getElementById('luas-2021').value,y2022:document.getElementById('luas-2022').value,y2023:document.getElementById('luas-2023').value});renderRows(luasData,document.getElementById('pk-luas-tbody'),false);document.getElementById('pk-luas-panel').style.display='none';document.getElementById('pk-luas-add').textContent='+ Tambah Komoditas';});

function enableInlineEdit(tbody,data){tbody?.addEventListener('dblclick',function(e){var td=e.target.closest('td');if(!td)return;if(!td.className.match(/^c\-/))return;var val=td.textContent;var controls='<div style="display:flex;align-items:center;gap:6px"><input class="edit-cell" value="'+val+'" style="flex:1"><button class="btn btn-primary btn-xs ok"><i class="fas fa-check"></i></button><button class="btn btn-outline btn-xs cancel"><i class="fas fa-times"></i></button></div>';td.setAttribute('data-prev',val);td.innerHTML=controls;});tbody?.addEventListener('click',function(e){var ok=e.target.closest('.ok');var cancel=e.target.closest('.cancel');if(!ok&&!cancel)return;var td=e.target.closest('td');var tr=td.closest('tr');var idx=parseInt(tr.dataset.row,10)-1;var r=data[idx];if(ok){var val=td.querySelector('input').value;var cls=td.className;switch(true){case /c-uraian/.test(cls): r.uraian=val; break;case /c-y2019/.test(cls): r.y2019=val; break;case /c-y2020/.test(cls): r.y2020=val; break;case /c-y2021/.test(cls): r.y2021=val; break;case /c-y2022/.test(cls): r.y2022=val; break;case /c-y2023/.test(cls): r.y2023=val; break;case /c-trend/.test(cls): r.trend=val; break;}td.textContent=val;}else{td.textContent=td.getAttribute('data-prev');}});}
enableInlineEdit(document.getElementById('pk-pop-tbody'),popData);
enableInlineEdit(document.getElementById('pk-prod-tbody'),prodData);
enableInlineEdit(document.getElementById('pk-luas-tbody'),luasData);

function exportCsv(filename, headers, rows){var csv=[headers].concat(rows).map(function(row){return row.map(function(v){var s=(''+(v==null?'':v)).replace(/"/g,'""');return '"'+s+'"';}).join(',');}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download=filename;document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url);} 
document.getElementById('pk-pop-export')?.addEventListener('click',function(){var headers=["No","Uraian","2019","2020","2021","2022","2023","Trend (%)"];var rows=popData.map(function(r){return [r.no,r.uraian,r.y2019,r.y2020,r.y2021,r.y2022,r.y2023,r.trend];});exportCsv('perkebunan-populasi.csv',headers,rows);});
document.getElementById('pk-prod-export')?.addEventListener('click',function(){var headers=["No","Komoditas","2019","2020","2021","2022","2023"];var rows=prodData.map(function(r){return [r.no,r.uraian,r.y2019,r.y2020,r.y2021,r.y2022,r.y2023];});exportCsv('perkebunan-produksi.csv',headers,rows);});
document.getElementById('pk-luas-export')?.addEventListener('click',function(){var headers=["No","Komoditas","2019","2020","2021","2022","2023"];var rows=luasData.map(function(r){return [r.no,r.uraian,r.y2019,r.y2020,r.y2021,r.y2022,r.y2023];});exportCsv('perkebunan-luas.csv',headers,rows);});
</script>
@endpush
