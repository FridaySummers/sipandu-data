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
        <div class="card-header"><div><h3>Kontribusi Sektor Industri terhadap PDRB (HB) tahun 2019 s.d 2023</h3><div class="sub">Data kontribusi industri terhadap PDRB harga berlaku</div></div><div class="card-actions"><button class="btn btn-outline btn-sm" id="hb-export"><i class="fas fa-download"></i> Export</button><button class="btn btn-outline btn-sm" id="hb-add"><i class="fas fa-plus"></i> Ajukan Data</button></div></div>
        <div class="card-body">
          <div class="panel" id="hb-panel" style="display:none;">
            <div class="form-title">Ajukan Data Baru</div>
            <div class="form-group"><label>Nama Data</label><input type="text" id="hb-uraian" class="form-control" placeholder="Contoh: Nama Data"></div>
            <div class="row-grid">
              <div class="year-group"><div class="year-label">2019</div><input type="text" id="hb-2019" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2020</div><input type="text" id="hb-2020" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2021</div><input type="text" id="hb-2021" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2022</div><input type="text" id="hb-2022" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2023</div><input type="text" id="hb-2023" class="form-control" placeholder="0.00"></div>
            </div>
            <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px;"><button class="btn btn-secondary" id="hb-cancel">Batal</button><button class="btn btn-purple" id="hb-save"><i class="fas fa-floppy-disk"></i> Simpan</button></div>
          </div>
          <div class="table-wrap"><table class="table table-compact table-industry"><thead><tr><th rowspan="2">No</th><th rowspan="2">Nama Data</th><th colspan="5">Tahun</th><th rowspan="2">Aksi</th></tr><tr><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody id="hb-tbody"></tbody></table></div>
        </div>
      </div>

      <div class="card industry-card theme-pink">
        <div class="card-header"><div><h3>Kontribusi Sektor Industri terhadap PDRB (HK) tahun 2019 s/d 2023</h3><div class="sub">Data kontribusi industri terhadap PDRB harga konstan</div></div><div class="card-actions"><button class="btn btn-outline btn-sm" id="hk-export"><i class="fas fa-download"></i> Export</button><button class="btn btn-outline btn-sm" id="hk-add"><i class="fas fa-plus"></i> Ajukan Data</button></div></div>
        <div class="card-body">
          <div class="panel" id="hk-panel" style="display:none;">
            <div class="form-title">Ajukan Data Baru</div>
            <div class="form-group"><label>Nama Data</label><input type="text" id="hk-uraian" class="form-control" placeholder="Contoh: Nama Data"></div>
            <div class="row-grid">
              <div class="year-group"><div class="year-label">2019</div><input type="text" id="hk-2019" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2020</div><input type="text" id="hk-2020" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2021</div><input type="text" id="hk-2021" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2022</div><input type="text" id="hk-2022" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2023</div><input type="text" id="hk-2023" class="form-control" placeholder="0.00"></div>
            </div>
            <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px;"><button class="btn btn-secondary" id="hk-cancel">Batal</button><button class="btn btn-pink" id="hk-save"><i class="fas fa-floppy-disk"></i> Simpan</button></div>
          </div>
          <div class="table-wrap"><table class="table table-compact table-industry"><thead><tr><th rowspan="2">No</th><th rowspan="2">Nama Data</th><th colspan="5">Tahun</th><th rowspan="2">Aksi</th></tr><tr><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody id="hk-tbody"></tbody></table></div>
        </div>
      </div>

      <div class="card industry-card theme-orange">
        <div class="card-header"><div><h3>Pertumbuhan Industri Menurut jenis dari tahun 2019 s/d 2023</h3><div class="sub">Ringkasan pertumbuhan industri menurut kategori</div></div><div class="card-actions"><button class="btn btn-outline btn-sm" id="gr-export"><i class="fas fa-download"></i> Export</button><button class="btn btn-outline btn-sm" id="gr-add"><i class="fas fa-plus"></i> Ajukan Data</button></div></div>
        <div class="card-body">
          <div class="panel" id="gr-panel" style="display:none;">
            <div class="form-title">Ajukan Data Baru</div>
            <div class="form-group"><label>Nama Data</label><input type="text" id="gr-uraian" class="form-control" placeholder="Contoh: Nama Data"></div>
            <div class="row-grid">
              <div class="year-group"><div class="year-label">2019</div><input type="text" id="gr-2019" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2020</div><input type="text" id="gr-2020" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2021</div><input type="text" id="gr-2021" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2022</div><input type="text" id="gr-2022" class="form-control" placeholder="0.00"></div>
              <div class="year-group"><div class="year-label">2023</div><input type="text" id="gr-2023" class="form-control" placeholder="0.00"></div>
            </div>
            <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px;"><button class="btn btn-secondary" id="gr-cancel">Batal</button><button class="btn btn-orange" id="gr-save"><i class="fas fa-floppy-disk"></i> Simpan</button></div>
          </div>
          <div class="table-wrap"><table class="table table-compact table-industry"><thead><tr><th rowspan="2">No</th><th rowspan="2">Nama Data</th><th colspan="5">Tahun</th><th rowspan="2">Aksi</th></tr><tr><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody id="gr-tbody"></tbody></table></div>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
window.USER_ROLE = document.body.dataset.userRole || '';
var opdName = 'Perindustrian';
var dinasId=(document.body.dataset.dinasId||'')||null;
var tableKeys = { hb: 'perindustrian_hb', hk: 'perindustrian_hk', gr: 'perindustrian_growth' };
var hbRows = [], hkRows = [], grRows = [];

function mapRows(data){return (Array.isArray(data)?data:[]).map(function(r,i){var v=r.values||{};return { id:r.id, no:i+1, uraian:r.uraian, y2019:v.y2019||'', y2020:v.y2020||'', y2021:v.y2021||'', y2022:v.y2022||'', y2023:v.y2023||'' }; });}

async function fetchRows(key){try{var url= key==='hb' ? '/perindustrian/hb' : (key==='hk' ? '/perindustrian/hk' : '/perindustrian/gr'); var res=await fetch(url,{headers:{'Accept':'application/json'}});var data=await res.json();var mapped=mapRows(data);if(key==='hb'){hbRows=mapped;renderRows(hbRows,document.getElementById('hb-tbody'),'hb');}else if(key==='hk'){hkRows=mapped;renderRows(hkRows,document.getElementById('hk-tbody'),'hk');}else{grRows=mapped;renderRows(grRows,document.getElementById('gr-tbody'),'gr');}}catch(_){}}

function renderRows(data,tb,key){tb.innerHTML=data.map(function(r,i){return '<tr><td>'+r.no+'</td><td>'+r.uraian+'</td><td>'+(r.y2019||'-')+'</td><td>'+(r.y2020||'-')+'</td><td>'+(r.y2021||'-')+'</td><td>'+(r.y2022||'-')+'</td><td>'+(r.y2023||'-')+'</td><td><button class="btn btn-outline btn-sm action-btn" data-ed="'+key+':'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-del="'+key+':'+i+'"><i class="fas fa-trash"></i></button></td></tr>';}).join('');
  tb.querySelectorAll('button[data-ed]').forEach(function(b){b.onclick=function(){var p=b.getAttribute('data-ed').split(':');var i=parseInt(p[1],10);var set=key==='hb'?hbRows:key==='hk'?hkRows:grRows;var r=set[i];var panelId=key+'-panel';document.getElementById(key+'-uraian').value=r.uraian;document.getElementById(key+'-2019').value=r.y2019;document.getElementById(key+'-2020').value=r.y2020;document.getElementById(key+'-2021').value=r.y2021;document.getElementById(key+'-2022').value=r.y2022;document.getElementById(key+'-2023').value=r.y2023;document.getElementById(panelId).style.display='block';document.getElementById(key+'-add').innerHTML='<i class="fas fa-times"></i> Tutup Form';set._editId=r.id;};});
  tb.querySelectorAll('button[data-del]').forEach(function(b){b.onclick=function(){var p=b.getAttribute('data-del').split(':');var i=parseInt(p[1],10);var set=key==='hb'?hbRows:key==='hk'?hkRows:grRows;var id=set[i]?.id;Utils.confirm('Hapus baris ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(async function(yes){if(!yes)return;try{var url = key==='hb'?('/perindustrian/hb/'+id):(key==='hk'?('/perindustrian/hk/'+id):('/perindustrian/gr/'+id)); var res=await fetch(url,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrfToken}});if(res.ok){await fetchRows(key);Utils.showToast('Baris dihapus','success');}else{Utils.showToast('Gagal menghapus','error');}}catch(e){Utils.showToast('Gagal menghapus','error');}})};});}

function init(){fetchRows('hb');fetchRows('hk');fetchRows('gr');}
init();

function toggle(btn,panel){var open=panel.style.display!=='none';panel.style.display=open?'none':'block';btn.innerHTML=open?'<i class="fas fa-plus"></i> Ajukan Data':'<i class="fas fa-times"></i> Tutup Form'}
document.getElementById('hb-add')?.addEventListener('click',function(){toggle(document.getElementById('hb-add'),document.getElementById('hb-panel'));});
document.getElementById('hk-add')?.addEventListener('click',function(){toggle(document.getElementById('hk-add'),document.getElementById('hk-panel'));});
document.getElementById('gr-add')?.addEventListener('click',function(){toggle(document.getElementById('gr-add'),document.getElementById('gr-panel'));});
document.getElementById('hb-cancel')?.addEventListener('click',function(){document.getElementById('hb-panel').style.display='none';document.getElementById('hb-add').innerHTML='<i class="fas fa-plus"></i> Ajukan Data';hbRows._editId=null;});
document.getElementById('hk-cancel')?.addEventListener('click',function(){document.getElementById('hk-panel').style.display='none';document.getElementById('hk-add').innerHTML='<i class="fas fa-plus"></i> Ajukan Data';hkRows._editId=null;});
document.getElementById('gr-cancel')?.addEventListener('click',function(){document.getElementById('gr-panel').style.display='none';document.getElementById('gr-add').innerHTML='<i class="fas fa-plus"></i> Ajukan Data';grRows._editId=null;});

async function saveKey(key){var ura=document.getElementById(key+'-uraian').value.trim();if(!ura){Utils.showToast('Isi Nama Data','error');return;}var vals={y2019:document.getElementById(key+'-2019').value.trim(),y2020:document.getElementById(key+'-2020').value.trim(),y2021:document.getElementById(key+'-2021').value.trim(),y2022:document.getElementById(key+'-2022').value.trim(),y2023:document.getElementById(key+'-2023').value.trim()};var editId=(key==='hb'?hbRows:(key==='hk'?hkRows:grRows))._editId;var isUser=(window.USER_ROLE==='user');try{if(!isUser){var payload={uraian:ura,values:vals};var url= editId ? (key==='hb'?('/perindustrian/hb/'+editId):(key==='hk'?('/perindustrian/hk/'+editId):('/perindustrian/gr/'+editId))) : (key==='hb'?'/perindustrian/hb':(key==='hk'?'/perindustrian/hk':'/perindustrian/gr'));var method= editId ? 'PUT' : 'POST';var res=await fetch(url,{method:method,headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify(payload)});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows(key);Utils.showToast(editId?'Data diperbarui':'Data ditambahkan','success');}var year=(vals.y2023||'').replace(/[^0-9]/g,'').slice(0,4)||new Date().getFullYear().toString();try{var fp = key==='hb' ? 'perindustrian_hb' : (key==='hk' ? 'perindustrian_hk' : 'perindustrian_growth'); await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({opd:opdName,dinas_id:dinasId,judul_data:ura,deskripsi:null,file_path:fp,tahun_perencanaan:year})});if(isUser){Utils.showToast('Pengajuan dikirim ke Data Management','success');}}catch(_){ }document.getElementById(key+'-panel').style.display='none';document.getElementById(key+'-add').innerHTML='<i class=\"fas fa-plus\"></i> Ajukan Data';(key==='hb'?hbRows:key==='hk'?hkRows:grRows)._editId=null;}catch(e){Utils.showToast('Gagal menyimpan','error');}}

document.getElementById('hb-save')?.addEventListener('click',function(){saveKey('hb')});
document.getElementById('hk-save')?.addEventListener('click',function(){saveKey('hk')});
document.getElementById('gr-save')?.addEventListener('click',function(){saveKey('gr')});

function exportCsv(filename, headers, rows){var csv=[headers].concat(rows).map(function(row){return row.map(function(v){var s=(''+(v==null?'':v)).replace(/"/g,'""');return '"'+s+'"';}).join(',');}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download=filename;document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url);} 
document.getElementById('hb-export')?.addEventListener('click',function(){var h=['No','Nama Data','2019','2020','2021','2022','2023'];var rows=hbRows.map(function(r){return [r.no,r.uraian,r.y2019,r.y2020,r.y2021,r.y2022,r.y2023]});exportCsv('perindustrian-hb.csv',h,rows)});
document.getElementById('hk-export')?.addEventListener('click',function(){var h=['No','Nama Data','2019','2020','2021','2022','2023'];var rows=hkRows.map(function(r){return [r.no,r.uraian,r.y2019,r.y2020,r.y2021,r.y2022,r.y2023]});exportCsv('perindustrian-hk.csv',h,rows)});
document.getElementById('gr-export')?.addEventListener('click',function(){var h=['No','Nama Data','2019','2020','2021','2022','2023'];var rows=grRows.map(function(r){return [r.no,r.uraian,r.y2019,r.y2020,r.y2021,r.y2022,r.y2023]});exportCsv('perindustrian-pertumbuhan.csv',h,rows)});
</script>
@endpush
