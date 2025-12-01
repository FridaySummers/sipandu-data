@extends('layouts.app')
@section('title', 'Perindustrian')
@section('body-class', 'dashboard-page force-light')
@push('styles')
<style>
.industry-card{border-radius:16px;overflow:hidden;margin-bottom:18px}
.industry-card .card-header{padding:16px 20px;display:flex;align-items:center;justify-content:space-between}
.industry-card .card-header .sub{font-size:13px;opacity:.9;color:#fff}
.industry-card .card-actions .btn{background:#fff;border-color:#fff;color:#111827;border-radius:8px;height:34px;padding:0 12px;display:flex;align-items:center;justify-content:center}
.panel{margin:16px;border-radius:16px;padding:16px;border:1px solid #e5e7eb}
.panel .form-title{font-weight:600;margin-bottom:8px}
.panel .form-group{display:flex;flex-direction:column;gap:8px;margin-bottom:8px}
.panel .form-control{border:1px solid #d1d5db;background:#f8fafc;border-radius:12px;padding:10px 12px}
.panel .form-control::placeholder{color:#9ca3af}
.panel .row-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:12px}
.year-group{display:flex;flex-direction:column;gap:6px}
.theme-purple .year-label{font-size:12px;color:#1e3a8a;font-weight:600}
.theme-pink .year-label{font-size:12px;color:#1e3a8a;font-weight:600}
.theme-orange .year-label{font-size:12px;color:#1e3a8a;font-weight:600}
.btn-purple{background:#2563eb;border-color:#2563eb;color:#fff}
.btn-pink{background:#2563eb;border-color:#2563eb;color:#fff}
.btn-orange{background:#2563eb;border-color:#2563eb;color:#fff}
.table-industry{width:100%;border:1px solid #93c5fd;border-radius:12px;overflow:hidden;border-collapse:separate;border-spacing:0;table-layout:fixed}
.table-industry thead th{background:#dbeafe;color:#1e3a8a;font-weight:600}
.table-industry th,.table-industry td{border-bottom:1px solid #93c5fd;border-right:1px solid #93c5fd;padding:10px 12px}
.table-industry thead tr th:first-child{border-left:1px solid #93c5fd}
.table-industry tbody tr td:first-child{border-left:1px solid #93c5fd}
.table-industry thead tr:first-child th{border-top:1px solid #93c5fd}
.table-industry th,.table-industry td{vertical-align:middle}
.table-industry th:last-child,.table-industry td:last-child{text-align:center}
.table-industry td:last-child{display:table-cell !important;text-align:center !important;white-space:nowrap}
.table-industry tbody tr:last-child td{border-bottom:1px solid #93c5fd}
.table-industry th:nth-child(1),.table-industry th:nth-child(2),.table-industry td:nth-child(1),.table-industry td:nth-child(2){text-align:left}
.table-industry th:not(:nth-child(1)):not(:nth-child(2)),.table-industry td:not(:nth-child(1)):not(:nth-child(2)){text-align:center}
.theme-purple .card-header{background:linear-gradient(90deg,#60a5fa,#3b82f6);color:#ffffff}
.theme-purple .panel{background:#eff6ff;border-color:#bfdbfe}
.theme-pink .card-header{background:linear-gradient(90deg,#60a5fa,#3b82f6);color:#ffffff}
.theme-pink .panel{background:#eff6ff;border-color:#bfdbfe}
.theme-orange .card-header{background:linear-gradient(90deg,#60a5fa,#3b82f6);color:#ffffff}
.theme-orange .panel{background:#eff6ff;border-color:#bfdbfe}
</style>
@endpush

@section('content')
    <div class="page active" id="perindustrian-page">
      <div class="page-header"><h1>Data Perindustrian</h1><p>Kontribusi Sektor Industri dan Pertumbuhan Industri di Kabupaten Kolaka Utara</p></div>
      <div class="card industry-card theme-purple">
        <div class="card-header"><div><h3>Kontribusi Sektor Industri terhadap PDRB (HB) tahun 2019 s.d 2023</h3><div class="sub">Data kontribusi industri terhadap PDRB harga berlaku</div></div><div class="card-actions"><button class="btn btn-outline btn-sm" id="hb-export"><i class="fas fa-download"></i> Export</button><button class="btn btn-outline btn-sm" id="hb-add"><i class="fas fa-plus"></i> Tambah Data</button></div></div>
        <div class="card-body">
          <div class="panel" id="hb-panel" style="display:none;">
            <div class="form-title">Tambah Data Baru</div>
            <div class="form-group"><label>Uraian</label><input type="text" id="hb-uraian" class="form-control" placeholder="Contoh: PDRB Sektor Industri HB"></div>
            <div class="row-grid">
              <div class="year-group"><div class="year-label">2019</div><input type="text" id="hb-2019" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2020</div><input type="text" id="hb-2020" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2021</div><input type="text" id="hb-2021" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2022</div><input type="text" id="hb-2022" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2023</div><input type="text" id="hb-2023" class="form-control" placeholder="0.00"></div>
            </div>
            <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px;"><button class="btn btn-secondary" id="hb-cancel">Batal</button><button class="btn btn-purple" id="hb-save"><i class="fas fa-floppy-disk"></i> Simpan</button></div>
          </div>
          <div class="table-wrap"><table class="table table-compact table-industry"><thead><tr><th rowspan="2">No</th><th rowspan="2">Uraian</th><th colspan="5">Tahun</th><th rowspan="2">Aksi</th></tr><tr><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody id="hb-tbody"></tbody></table></div>
        </div>
      </div>

      <div class="card industry-card theme-pink">
        <div class="card-header"><div><h3>Kontribusi Sektor Industri terhadap PDRB (HK) tahun 2019 s/d 2023</h3><div class="sub">Data kontribusi industri terhadap PDRB harga konstan</div></div><div class="card-actions"><button class="btn btn-outline btn-sm" id="hk-export"><i class="fas fa-download"></i> Export</button><button class="btn btn-outline btn-sm" id="hk-add"><i class="fas fa-plus"></i> Tambah Data</button></div></div>
        <div class="card-body">
          <div class="panel" id="hk-panel" style="display:none;">
            <div class="form-title">Tambah Data Baru</div>
            <div class="form-group"><label>Uraian</label><input type="text" id="hk-uraian" class="form-control" placeholder="Contoh: PDRB Sektor Perindustrian HK"></div>
            <div class="row-grid">
              <div class="year-group"><div class="year-label">2019</div><input type="text" id="hk-2019" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2020</div><input type="text" id="hk-2020" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2021</div><input type="text" id="hk-2021" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2022</div><input type="text" id="hk-2022" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2023</div><input type="text" id="hk-2023" class="form-control" placeholder="0.00"></div>
            </div>
            <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px;"><button class="btn btn-secondary" id="hk-cancel">Batal</button><button class="btn btn-pink" id="hk-save"><i class="fas fa-floppy-disk"></i> Simpan</button></div>
          </div>
          <div class="table-wrap"><table class="table table-compact table-industry"><thead><tr><th rowspan="2">No</th><th rowspan="2">Uraian</th><th colspan="5">Tahun</th><th rowspan="2">Aksi</th></tr><tr><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody id="hk-tbody"></tbody></table></div>
        </div>
      </div>

      <div class="card industry-card theme-orange">
        <div class="card-header"><div><h3>Pertumbuhan Industri Menurut jenis dari tahun 2019 s/d 2023</h3><div class="sub">Ringkasan pertumbuhan industri menurut kategori</div></div><div class="card-actions"><button class="btn btn-outline btn-sm" id="gr-export"><i class="fas fa-download"></i> Export</button><button class="btn btn-outline btn-sm" id="gr-add"><i class="fas fa-plus"></i> Tambah Data</button></div></div>
        <div class="card-body">
          <div class="panel" id="gr-panel" style="display:none;">
            <div class="form-title">Tambah Data Baru</div>
            <div class="form-group"><label>Uraian</label><input type="text" id="gr-uraian" class="form-control" placeholder="Contoh: Industri Pengolahan"></div>
            <div class="row-grid">
              <div class="year-group"><div class="year-label">2019</div><input type="text" id="gr-2019" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2020</div><input type="text" id="gr-2020" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2021</div><input type="text" id="gr-2021" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2022</div><input type="text" id="gr-2022" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2023</div><input type="text" id="gr-2023" class="form-control" placeholder="0.00"></div>
            </div>
            <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px;"><button class="btn btn-secondary" id="gr-cancel">Batal</button><button class="btn btn-orange" id="gr-save"><i class="fas fa-floppy-disk"></i> Simpan</button></div>
          </div>
          <div class="table-wrap"><table class="table table-compact table-industry"><thead><tr><th rowspan="2">No</th><th rowspan="2">Uraian</th><th colspan="5">Tahun</th><th rowspan="2">Aksi</th></tr><tr><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody id="gr-tbody"></tbody></table></div>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
var hbData=[{no:1,uraian:"PDRB Sektor Industri HB",y2019:"85,272.20",y2020:"84,921.13",y2021:"87,048.22",y2022:"97,313.90",y2023:""},{no:2,uraian:"Total PDRB Harga Berlaku",y2019:"8,674,428.43",y2020:"8,831,478.38",y2021:"9,165,726.95",y2022:"10,110,990",y2023:""},{no:3,uraian:"Kontribusi Sektor Perindustrian terhadap PDRB",y2019:"0.98",y2020:"0.96",y2021:"0.95",y2022:"0.96",y2023:""}];
var hkData=[{no:1,uraian:"PDRB Sektor Perindustrian HK",y2019:"61,472.46",y2020:"59,828.60",y2021:"59,334.60",y2022:"64,086.20",y2023:""},{no:2,uraian:"Total PDRB Harga Konstan",y2019:"6,331,548.66",y2020:"6,356,664.5",y2021:"6,525,446.01",y2022:"6,781,751.40",y2023:""},{no:3,uraian:"Kontribusi Sektor Perindustrian terhadap PDRB (HK)",y2019:"0.98",y2020:"0.96",y2021:"0.95",y2022:"0.96",y2023:""}];
var grData=[{no:1,uraian:"Industri Pengolahan",y2019:"",y2020:"",y2021:"",y2022:"",y2023:""}];
function renderRows(data,tb,key){tb.innerHTML=data.map(function(r,i){return '<tr><td>'+r.no+'</td><td>'+r.uraian+'</td><td>'+r.y2019+'</td><td>'+r.y2020+'</td><td>'+r.y2021+'</td><td>'+r.y2022+'</td><td>'+r.y2023+'</td><td><button class="btn btn-outline btn-sm action-btn" data-ed="'+key+':'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-del="'+key+':'+i+'"><i class="fas fa-trash"></i></button></td></tr>'}).join('');
  tb.querySelectorAll('button[data-ed]').forEach(function(b){b.onclick=function(){var p=b.getAttribute('data-ed').split(':');var i=parseInt(p[1],10);var set=key==='hb'?hbData:key==='hk'?hkData:grData;var r=set[i];var panelId=key+'-panel';document.getElementById(key+'-uraian').value=r.uraian;document.getElementById(key+'-2019').value=r.y2019;document.getElementById(key+'-2020').value=r.y2020;document.getElementById(key+'-2021').value=r.y2021;document.getElementById(key+'-2022').value=r.y2022;document.getElementById(key+'-2023').value=r.y2023;document.getElementById(panelId).style.display='block';document.getElementById(key+'-add').innerHTML='<i class="fas fa-times"></i> Tutup Form';};});
  tb.querySelectorAll('button[data-del]').forEach(function(b){b.onclick=null;});}
function init(){renderRows(hbData,document.getElementById('hb-tbody'),'hb');renderRows(hkData,document.getElementById('hk-tbody'),'hk');renderRows(grData,document.getElementById('gr-tbody'),'gr');}
init();
function toggle(btn,panel){var open=panel.style.display!=='none';panel.style.display=open?'none':'block';btn.innerHTML=open?'<i class="fas fa-plus"></i> Tambah Data':'<i class="fas fa-times"></i> Tutup Form'}
document.getElementById('hb-add')?.addEventListener('click',function(){toggle(document.getElementById('hb-add'),document.getElementById('hb-panel'));});
document.getElementById('hk-add')?.addEventListener('click',function(){toggle(document.getElementById('hk-add'),document.getElementById('hk-panel'));});
document.getElementById('gr-add')?.addEventListener('click',function(){toggle(document.getElementById('gr-add'),document.getElementById('gr-panel'));});
document.getElementById('hb-cancel')?.addEventListener('click',function(){document.getElementById('hb-panel').style.display='none';document.getElementById('hb-add').innerHTML='<i class="fas fa-plus"></i> Tambah Data';});
document.getElementById('hk-cancel')?.addEventListener('click',function(){document.getElementById('hk-panel').style.display='none';document.getElementById('hk-add').innerHTML='<i class="fas fa-plus"></i> Tambah Data';});
document.getElementById('gr-cancel')?.addEventListener('click',function(){document.getElementById('gr-panel').style.display='none';document.getElementById('gr-add').innerHTML='<i class="fas fa-plus"></i> Tambah Data';});
  ['hb','hk','gr'].forEach(function(key){var tb=document.getElementById(key+'-tbody');tb&&tb.addEventListener('click',function(e){var ed=e.target.closest('[data-ed]');var del=e.target.closest('[data-del]');if(!ed&&!del)return;var p=(ed||del).getAttribute(ed?'data-ed':'data-del').split(':');var i=parseInt(p[1],10);var set=key==='hb'?hbData:key==='hk'?hkData:grData;var panelId=key+'-panel';if(ed){var r=set[i];document.getElementById(key+'-uraian').value=r.uraian;document.getElementById(key+'-2019').value=r.y2019;document.getElementById(key+'-2020').value=r.y2020;document.getElementById(key+'-2021').value=r.y2021;document.getElementById(key+'-2022').value=r.y2022;document.getElementById(key+'-2023').value=r.y2023;document.getElementById(panelId).style.display='block';document.getElementById(key+'-add').innerHTML='<i class="fas fa-times"></i> Tutup Form';} else {Utils.confirm('Hapus baris ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(function(yes){if(!yes)return;set.splice(i,1);set.forEach(function(r,idx){r.no=idx+1});renderRows(set,document.getElementById(key+'-tbody'),key);Utils.showToast('Baris dihapus','success');});}});});
document.getElementById('hb-save')?.addEventListener('click',function(){hbData.push({no:hbData.length+1,uraian:document.getElementById('hb-uraian').value,y2019:document.getElementById('hb-2019').value,y2020:document.getElementById('hb-2020').value,y2021:document.getElementById('hb-2021').value,y2022:document.getElementById('hb-2022').value,y2023:document.getElementById('hb-2023').value});renderRows(hbData,document.getElementById('hb-tbody'),'hb');document.getElementById('hb-panel').style.display='none';document.getElementById('hb-add').innerHTML='<i class="fas fa-plus"></i> Tambah Data';});
document.getElementById('hk-save')?.addEventListener('click',function(){hkData.push({no:hkData.length+1,uraian:document.getElementById('hk-uraian').value,y2019:document.getElementById('hk-2019').value,y2020:document.getElementById('hk-2020').value,y2021:document.getElementById('hk-2021').value,y2022:document.getElementById('hk-2022').value,y2023:document.getElementById('hk-2023').value});renderRows(hkData,document.getElementById('hk-tbody'),'hk');document.getElementById('hk-panel').style.display='none';document.getElementById('hk-add').innerHTML='<i class="fas fa-plus"></i> Tambah Data';});
document.getElementById('gr-save')?.addEventListener('click',function(){grData.push({no:grData.length+1,uraian:document.getElementById('gr-uraian').value,y2019:document.getElementById('gr-2019').value,y2020:document.getElementById('gr-2020').value,y2021:document.getElementById('gr-2021').value,y2022:document.getElementById('gr-2022').value,y2023:document.getElementById('gr-2023').value});renderRows(grData,document.getElementById('gr-tbody'),'gr');document.getElementById('gr-panel').style.display='none';document.getElementById('gr-add').innerHTML='<i class="fas fa-plus"></i> Tambah Data';Utils.showToast('Data ditambahkan','success');});
function exportCsv(filename, headers, rows){var csv=[headers].concat(rows).map(function(row){return row.map(function(v){var s=(''+(v==null?'':v)).replace(/"/g,'""');return '"'+s+'"';}).join(',');}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download=filename;document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url);} 
document.getElementById('hb-export')?.addEventListener('click',function(){var h=['No','Uraian','2019','2020','2021','2022','2023'];var rows=hbData.map(function(r){return [r.no,r.uraian,r.y2019,r.y2020,r.y2021,r.y2022,r.y2023]});exportCsv('perindustrian-hb.csv',h,rows)});
document.getElementById('hk-export')?.addEventListener('click',function(){var h=['No','Uraian','2019','2020','2021','2022','2023'];var rows=hkData.map(function(r){return [r.no,r.uraian,r.y2019,r.y2020,r.y2021,r.y2022,r.y2023]});exportCsv('perindustrian-hk.csv',h,rows)});
document.getElementById('gr-export')?.addEventListener('click',function(){var h=['No','Uraian','2019','2020','2021','2022','2023'];var rows=grData.map(function(r){return [r.no,r.uraian,r.y2019,r.y2020,r.y2021,r.y2022,r.y2023]});exportCsv('perindustrian-pertumbuhan.csv',h,rows)});
</script>
@endpush
