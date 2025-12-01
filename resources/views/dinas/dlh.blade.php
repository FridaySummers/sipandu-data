@extends('layouts.app')
@section('title', 'DLH')
@section('body-class', 'dashboard-page force-light')

@push('styles')
<style>
.dlh-card{border-radius:16px;overflow:hidden;margin-bottom:18px}
.dlh-card .card-header{padding:16px 20px;display:flex;align-items:center;justify-content:space-between;background:linear-gradient(90deg,#60a5fa,#3b82f6);color:#ffffff;border-radius:16px 16px 0 0}
.dlh-card .card-header h3{display:flex;align-items:center;gap:10px}
.dlh-card .card-header .icon{display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:9999px;background:rgba(255,255,255,.2)}
.dlh-card .card-header .sub{font-size:13px;opacity:.9;color:#ffffff}
.dlh-card .card-actions .btn{border-radius:9999px;padding:8px 14px;font-weight:600;pointer-events:auto;position:relative;z-index:2}
.dlh-card .card-header .btn.btn-outline{background:#ffffff;color:#2563eb;border:1px solid #93c5fd;box-shadow:0 2px 6px rgba(29,78,216,.12)}
.dlh-panel{margin:16px;border-radius:16px;padding:16px;border:1px solid #cfe0ff;background:#f8fbff}
.dlh-panel .form-group{display:flex;flex-direction:column;gap:8px;margin-bottom:8px}
.dlh-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:12px}
.year-group{display:flex;flex-direction:column;gap:6px}
.year-label{font-size:12px;color:#0f172a;font-weight:600}
.form-control{border:1px solid #93c5fd;background:#ffffff;border-radius:12px;padding:10px 12px}
.form-control:focus{outline:none;border-color:#7dd3fc;box-shadow:0 0 0 3px rgba(125,211,252,0.35)}
.dlh-table{width:100%;border:1px solid #60a5fa;border-radius:12px;overflow:hidden;border-collapse:separate;border-spacing:0;table-layout:fixed}
.dlh-table thead th{background:#dbeafe;color:#1e3a8a;font-weight:600}
.dlh-table th,.dlh-table td{border-bottom:1px solid #60a5fa;border-right:1px solid #60a5fa;padding:10px 12px}
.dlh-table thead tr th:first-child{border-left:1px solid #60a5fa}
.dlh-table tbody tr td:first-child{border-left:1px solid #60a5fa}
.dlh-table th{font-weight:600;text-align:center}
.dlh-table td:nth-child(1),.dlh-table td:nth-child(2),.dlh-table th:nth-child(1),.dlh-table th:nth-child(2){text-align:left}
.btn-outline{background:#fff;border:1px solid #93c5fd;color:#2563eb;border-radius:12px}
.btn-primary{background:#2563eb;border-color:#2563eb;color:#fff}
.toolbar{display:flex;align-items:center;justify-content:flex-end;margin-bottom:10px}
.dlh-table th:last-child,.dlh-table td:last-child{text-align:center}
.dlh-table td:last-child{display:table-cell !important;text-align:center !important;white-space:nowrap}
.dlh-table thead th:last-child{width:120px !important}
</style>
@endpush

@section('content')
    <div class="page active" id="dlh-page">
      <div class="page-header"><h1>Data Lingkungan Hidup</h1><p>Pengelolaan persampahan dan daya tampung TPA di Kabupaten Kolaka Utara</p></div>
      <div class="card dlh-card">
        <div class="card-header" style="position:relative;z-index:1">
          <div><h3><span class="icon"><i class="fas fa-tree"></i></span> Daya Tampung TPA</h3><div class="sub">Daya tampung TPA dan jumlah sampah masuk 2019–2023</div></div>
          <div class="card-actions"><button class="btn btn-outline btn-sm" id="dlh-export"><i class="fas fa-download"></i> Export Data</button> <button class="btn btn-outline btn-sm" id="dlh-add" onclick="(function(e){e.preventDefault();e.stopPropagation();var p=document.getElementById('dlh-form-panel');var open=p.style.display!=='none';p.style.display=open?'none':'block';})(event)"><i class="fas fa-plus"></i> Tambah Data</button></div>
        </div>
        <div class="card-body">
          <div class="dlh-panel" id="dlh-form-panel" style="display:none;">
            <div class="form-group"><label>Uraian</label><input type="text" id="dlh-uraian" class="form-control" placeholder="Contoh: Daya tampung TPA, Luas RTH, dll"></div>
            <div class="form-group"><label>Satuan</label><input type="text" id="dlh-satuan" class="form-control" placeholder="Contoh: Ton, Ha, %"></div>
            <div class="dlh-grid">
              <div class="year-group"><div class="year-label">Tahun 2019</div><input class="form-control" id="dlh-2019" placeholder="0"></div>
              <div class="year-group"><div class="year-label">Tahun 2020</div><input class="form-control" id="dlh-2020" placeholder="0"></div>
              <div class="year-group"><div class="year-label">Tahun 2021</div><input class="form-control" id="dlh-2021" placeholder="0"></div>
              <div class="year-group"><div class="year-label">Tahun 2022</div><input class="form-control" id="dlh-2022" placeholder="0"></div>
              <div class="year-group"><div class="year-label">Tahun 2023</div><input class="form-control" id="dlh-2023" placeholder="0"></div>
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
                  <th rowspan="2">Aksi</th>
                </tr>
                <tr>
                  <th>2019</th>
                  <th>2020</th>
                  <th>2021</th>
                  <th>2022</th>
                  <th>2023</th>
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
var dlhRows=[{no:1,uraian:"Daya tampung TPA Totallang",y2019:"16.568",y2020:"13.823",y2021:"11.078",y2022:"8.319",y2023:"5.002"},{no:2,uraian:"Jumlah sampah yg masuk TPA Totallang",y2019:"2.744",y2020:"2.744",y2021:"2.759",y2022:"2.761",y2023:"2.753"}];
function renderDlh(){var tb=document.getElementById('dlh-tbody');if(!tb)return;tb.innerHTML=dlhRows.map(function(r,i){return '<tr><td>'+r.no+'</td><td>'+r.uraian+'</td><td>'+(r.y2019||'-')+'</td><td>'+(r.y2020||'-')+'</td><td>'+(r.y2021||'-')+'</td><td>'+(r.y2022||'-')+'</td><td>'+(r.y2023||'-')+'</td><td><button class="btn btn-outline btn-sm action-btn" data-dlh-ed="'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-dlh-del="'+i+'"><i class="fas fa-trash"></i></button></td></tr>';}).join('');}
renderDlh();
document.addEventListener('DOMContentLoaded',function(){renderDlh();});
function dlhSave(){var btn=document.getElementById('dlh-save');if(btn&&btn.dataset.busy==='1')return;if(btn)btn.dataset.busy='1';dlhRows.push({no:dlhRows.length+1,uraian:document.getElementById('dlh-uraian').value,y2019:document.getElementById('dlh-2019').value,y2020:document.getElementById('dlh-2020').value,y2021:document.getElementById('dlh-2021').value,y2022:document.getElementById('dlh-2022').value,y2023:document.getElementById('dlh-2023').value});renderDlh();document.getElementById('dlh-form-panel').style.display='none';try{Utils.showToast('Data DLH ditambahkan','success');}catch(e){}if(btn)setTimeout(function(){btn.dataset.busy='0';},0);}
function dlhCancel(){document.getElementById('dlh-form-panel').style.display='none';}
window.dlhSave=dlhSave;window.dlhCancel=dlhCancel;
document.addEventListener('click', function(e) {
  var ed = e.target.closest('[data-dlh-ed]');
  var del = e.target.closest('[data-dlh-del]');
  if (!ed && !del) return;

  var i = parseInt((ed || del).getAttribute(ed ? 'data-dlh-ed' : 'data-dlh-del'), 10);

  if (ed) {
    var r = dlhRows[i];
    document.getElementById('dlh-uraian').value = r.uraian;
    document.getElementById('dlh-2019').value = r.y2019;
    document.getElementById('dlh-2020').value = r.y2020;
    document.getElementById('dlh-2021').value = r.y2021;
    document.getElementById('dlh-2022').value = r.y2022;
    document.getElementById('dlh-2023').value = r.y2023;
    document.getElementById('dlh-form-panel').style.display = 'block';
  } else {
    Utils.confirm('Hapus baris ini?', { okText: 'Hapus', cancelText: 'Batal', variant: 'danger' })
      .then(function(yes) {
        if (!yes) return;
        dlhRows.splice(i, 1);
        dlhRows.forEach(function(r, idx) { r.no = idx + 1; });
        renderDlh();
        Utils.showToast('Baris dihapus', 'success');
      });
  }
});
function exportCsv(filename, headers, rows){var csv=[headers].concat(rows).map(function(row){return row.map(function(v){var s=(''+(v==null?'':v)).replace(/"/g,'""');return '"'+s+'"';}).join(',');}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download=filename;document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url);} 
;(function(){var ex=document.getElementById('dlh-export');if(ex){ex.addEventListener('click',function(){var h=['No','Uraian','2019','2020','2021','2022','2023'];var rows=dlhRows.map(function(r){return [r.no,r.uraian,r.y2019,r.y2020,r.y2021,r.y2022,r.y2023]});exportCsv('dlh.csv',h,rows)});}})();
</script>
@endpush
