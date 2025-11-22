@extends('layouts.app')
@section('title', 'DLH')
@section('body-class', 'dashboard-page force-light')

@push('styles')
<style>
.dlh-card{border-radius:16px;overflow:hidden;margin-bottom:18px}
.dlh-card .card-header{padding:16px 20px;display:flex;align-items:center;justify-content:space-between;background:#059669;color:#ffffff;border-radius:16px 16px 0 0}
.dlh-card .card-header h3{display:flex;align-items:center;gap:10px}
.dlh-card .card-header .icon{display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:9999px;background:rgba(255,255,255,.2)}
.dlh-card .card-actions .btn{border-radius:9999px;padding:8px 14px;font-weight:600;background:#ffffff;color:#065f46;border:1px solid #a7f3d0;box-shadow:0 2px 6px rgba(0,0,0,.08)}
.dlh-panel{margin:16px;border-radius:16px;padding:16px;border:1px solid #86efac;background:#ecfdf5}
.dlh-panel .form-group{display:flex;flex-direction:column;gap:8px;margin-bottom:8px}
.dlh-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:12px}
.year-group{display:flex;flex-direction:column;gap:6px}
.year-label{font-size:12px;color:#065f46;font-weight:600}
.form-control{border:1px solid #cbd5e1;background:#ffffff;border-radius:12px;padding:10px 12px}
.form-control:focus{outline:none;border-color:#86efac;box-shadow:0 0 0 3px rgba(134,239,172,0.35)}
.dlh-table{width:100%;border:1px solid #10b981;border-radius:12px;overflow:hidden;border-collapse:separate;border-spacing:0;table-layout:fixed}
.dlh-table thead th{background:#dcfce7;color:#064e3b;font-weight:600}
.dlh-table th,.dlh-table td{border-bottom:1px solid #10b981;border-right:1px solid #10b981;padding:10px 12px}
.dlh-table thead tr th:first-child{border-left:1px solid #10b981}
.dlh-table tbody tr td:first-child{border-left:1px solid #10b981}
.dlh-table th{font-weight:600;text-align:center}
.dlh-table td:nth-child(1),.dlh-table td:nth-child(2),.dlh-table th:nth-child(1),.dlh-table th:nth-child(2){text-align:left}
.btn-outline{background:#fff;border:1px solid #e5e7eb;color:#111827;border-radius:12px}
.toolbar{display:flex;align-items:center;justify-content:flex-end;margin-bottom:10px}
</style>
@endpush

@section('content')
    <div class="page active" id="dlh-page">
      <div class="card dlh-card">
        <div class="card-header">
          <h3><span class="icon"><i class="fas fa-tree"></i></span> Daya Tampung TPA <span style="font-weight:400">di Kabupaten Kolaka Utara 2019-2023</span></h3>
          <div class="card-actions"><button class="btn btn-sm" id="dlh-add"><i class="fas fa-plus"></i> Tambah Data</button></div>
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
            <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px"><button class="btn btn-outline" id="dlh-cancel">Batal</button><button class="btn btn-green" id="dlh-save">Simpan</button></div>
          </div>
          <div class="toolbar"><button class="btn btn-outline btn-sm" id="dlh-export"><i class="fas fa-download"></i> Export Data</button></div>
          <div class="table-wrap">
            <table class="table table-compact dlh-table">
              <thead>
                <tr>
                  <th rowspan="2">No</th>
                  <th rowspan="2">Uraian</th>
                  <th class="th-group-year" colspan="5">Tahun</th>
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
function renderDlh(){var tb=document.getElementById('dlh-tbody');if(!tb)return;tb.innerHTML=dlhRows.map(function(r){return '<tr><td>'+r.no+'</td><td>'+r.uraian+'</td><td>'+(r.y2019||'-')+'</td><td>'+(r.y2020||'-')+'</td><td>'+(r.y2021||'-')+'</td><td>'+(r.y2022||'-')+'</td><td>'+(r.y2023||'-')+'</td></tr>';}).join('');}
renderDlh();
document.getElementById('dlh-add')?.addEventListener('click',function(){var p=document.getElementById('dlh-form-panel');var open=p.style.display!=='none';p.style.display=open?'none':'block';});
document.getElementById('dlh-cancel')?.addEventListener('click',function(){document.getElementById('dlh-form-panel').style.display='none';});
document.getElementById('dlh-save')?.addEventListener('click',function(){dlhRows.push({no:dlhRows.length+1,uraian:document.getElementById('dlh-uraian').value,y2019:document.getElementById('dlh-2019').value,y2020:document.getElementById('dlh-2020').value,y2021:document.getElementById('dlh-2021').value,y2022:document.getElementById('dlh-2022').value,y2023:document.getElementById('dlh-2023').value});renderDlh();document.getElementById('dlh-form-panel').style.display='none';});
</script>
@endpush
