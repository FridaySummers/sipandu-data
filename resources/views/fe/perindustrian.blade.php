@extends('layouts.app')
@section('title', 'Perindustrian')
@section('body-class', 'dashboard-page force-light')
@push('styles')
<style>
.industry-card{border-radius:16px;overflow:hidden;margin-bottom:18px}
.industry-card .card-header{padding:16px 20px;display:flex;align-items:center;justify-content:space-between}
.industry-card .card-actions .btn{background:#fff;border-color:#fff;color:#111827;border-radius:12px;width:34px;height:34px;display:flex;align-items:center;justify-content:center}
.panel{margin:16px;border-radius:16px;padding:16px;border:1px solid #e5e7eb}
.panel .form-title{font-weight:600;margin-bottom:8px}
.panel .form-group{display:flex;flex-direction:column;gap:8px;margin-bottom:8px}
.panel .form-control{border:1px solid #d1d5db;background:#f8fafc;border-radius:12px;padding:10px 12px}
.panel .form-control::placeholder{color:#9ca3af}
.panel .row-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:12px}
.year-group{display:flex;flex-direction:column;gap:6px}
.theme-purple .year-label{font-size:12px;color:#5b21b6;font-weight:600}
.theme-pink .year-label{font-size:12px;color:#9d174d;font-weight:600}
.theme-orange .year-label{font-size:12px;color:#9a3412;font-weight:600}
.btn-purple{background:#7c3aed;border-color:#7c3aed;color:#fff}
.btn-pink{background:#db2777;border-color:#db2777;color:#fff}
.btn-orange{background:#f97316;border-color:#f97316;color:#fff}
.table-industry{width:100%;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;border-collapse:separate;border-spacing:0;table-layout:fixed}
.table-industry thead th{background:#ffffff;color:#111827;font-weight:600}
.table-industry th,.table-industry td{border-bottom:1px solid #e5e7eb;border-right:1px solid #e5e7eb;padding:10px 12px}
.table-industry thead tr th:first-child{border-left:1px solid #e5e7eb}
.table-industry tbody tr td:first-child{border-left:1px solid #e5e7eb}
.theme-purple .card-header{background:#ede9fe;color:#111827}
.theme-purple .panel{background:#f5f3ff;border-color:#ddd6fe}
.theme-pink .card-header{background:#fde2e8;color:#111827}
.theme-pink .panel{background:#fdf2f8;border-color:#fbcfe8}
.theme-orange .card-header{background:#ffedd5;color:#111827}
.theme-orange .panel{background:#fff7ed;border-color:#fed7aa}
</style>
@endpush

@section('content')
    <div class="page active" id="perindustrian-page">
      <div class="page-header"><h1>Data Perindustrian</h1><p>Kontribusi Sektor Industri dan Pertumbuhan Industri di Kabupaten Kolaka Utara</p></div>
      <div class="card industry-card theme-purple">
        <div class="card-header"><h3>Kontribusi Sektor Industri terhadap PDRB (HB) tahun 2019 s.d 2023</h3><div class="card-actions"><button class="btn btn-outline btn-sm" id="hb-add"><i class="fas fa-plus"></i></button></div></div>
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
          <div class="table-wrap"><table class="table table-compact table-industry"><thead><tr><th>No.</th><th>Uraian</th><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody id="hb-tbody"></tbody></table></div>
        </div>
      </div>

      <div class="card industry-card theme-pink">
        <div class="card-header"><h3>Kontribusi Sektor Industri terhadap PDRB (HK) tahun 2019 s/d 2023</h3><div class="card-actions"><button class="btn btn-outline btn-sm" id="hk-add"><i class="fas fa-plus"></i></button></div></div>
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
          <div class="table-wrap"><table class="table table-compact table-industry"><thead><tr><th>No.</th><th>Uraian</th><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody id="hk-tbody"></tbody></table></div>
        </div>
      </div>

      <div class="card industry-card theme-orange">
        <div class="card-header"><h3>Pertumbuhan Industri Menurut jenis dari tahun 2019 s/d 2023</h3><div class="card-actions"><button class="btn btn-outline btn-sm" id="gr-add"><i class="fas fa-plus"></i></button></div></div>
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
          <div class="table-wrap"><table class="table table-compact table-industry"><thead><tr><th>No.</th><th>Uraian</th><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody id="gr-tbody"></tbody></table></div>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
var hbData=[{no:1,uraian:"PDRB Sektor Industri HB",y2019:"85,272.20",y2020:"84,921.13",y2021:"87,048.22",y2022:"97,313.90",y2023:""},{no:2,uraian:"Total PDRB Harga Berlaku",y2019:"8,674,428.43",y2020:"8,831,478.38",y2021:"9,165,726.95",y2022:"10,110,990",y2023:""},{no:3,uraian:"Kontribusi Sektor Perindustrian terhadap PDRB",y2019:"0.98",y2020:"0.96",y2021:"0.95",y2022:"0.96",y2023:""}];
var hkData=[{no:1,uraian:"PDRB Sektor Perindustrian HK",y2019:"61,472.46",y2020:"59,828.60",y2021:"59,334.60",y2022:"64,086.20",y2023:""},{no:2,uraian:"Total PDRB Harga Konstan",y2019:"6,331,548.66",y2020:"6,356,664.5",y2021:"6,525,446.01",y2022:"6,781,751.40",y2023:""},{no:3,uraian:"Kontribusi Sektor Perindustrian terhadap PDRB (HK)",y2019:"0.98",y2020:"0.96",y2021:"0.95",y2022:"0.96",y2023:""}];
var grData=[{no:1,uraian:"Industri Pengolahan",y2019:"",y2020:"",y2021:"",y2022:"",y2023:""}];
function renderRows(data,tb){tb.innerHTML=data.map(function(r){return '<tr><td>'+r.no+'</td><td>'+r.uraian+'</td><td>'+r.y2019+'</td><td>'+r.y2020+'</td><td>'+r.y2021+'</td><td>'+r.y2022+'</td><td>'+r.y2023+'</td></tr>'}).join('');}
function init(){renderRows(hbData,document.getElementById('hb-tbody'));renderRows(hkData,document.getElementById('hk-tbody'));renderRows(grData,document.getElementById('gr-tbody'));}
init();
function toggle(btn,panel){var open=panel.style.display!=='none';panel.style.display=open?'none':'block';btn.innerHTML=open?'<i class="fas fa-plus"></i>':'<i class="fas fa-times"></i>'}
document.getElementById('hb-add')?.addEventListener('click',function(){toggle(document.getElementById('hb-add'),document.getElementById('hb-panel'));});
document.getElementById('hk-add')?.addEventListener('click',function(){toggle(document.getElementById('hk-add'),document.getElementById('hk-panel'));});
document.getElementById('gr-add')?.addEventListener('click',function(){toggle(document.getElementById('gr-add'),document.getElementById('gr-panel'));});
document.getElementById('hb-cancel')?.addEventListener('click',function(){document.getElementById('hb-panel').style.display='none';document.getElementById('hb-add').innerHTML='<i class="fas fa-plus"></i>';});
document.getElementById('hk-cancel')?.addEventListener('click',function(){document.getElementById('hk-panel').style.display='none';document.getElementById('hk-add').innerHTML='<i class="fas fa-plus"></i>';});
document.getElementById('gr-cancel')?.addEventListener('click',function(){document.getElementById('gr-panel').style.display='none';document.getElementById('gr-add').innerHTML='<i class="fas fa-plus"></i>';});
document.getElementById('hb-save')?.addEventListener('click',function(){hbData.push({no:hbData.length+1,uraian:document.getElementById('hb-uraian').value,y2019:document.getElementById('hb-2019').value,y2020:document.getElementById('hb-2020').value,y2021:document.getElementById('hb-2021').value,y2022:document.getElementById('hb-2022').value,y2023:document.getElementById('hb-2023').value});renderRows(hbData,document.getElementById('hb-tbody'));document.getElementById('hb-panel').style.display='none';document.getElementById('hb-add').innerHTML='<i class="fas fa-plus"></i>';});
document.getElementById('hk-save')?.addEventListener('click',function(){hkData.push({no:hkData.length+1,uraian:document.getElementById('hk-uraian').value,y2019:document.getElementById('hk-2019').value,y2020:document.getElementById('hk-2020').value,y2021:document.getElementById('hk-2021').value,y2022:document.getElementById('hk-2022').value,y2023:document.getElementById('hk-2023').value});renderRows(hkData,document.getElementById('hk-tbody'));document.getElementById('hk-panel').style.display='none';document.getElementById('hk-add').innerHTML='<i class="fas fa-plus"></i>';});
document.getElementById('gr-save')?.addEventListener('click',function(){grData.push({no:grData.length+1,uraian:document.getElementById('gr-uraian').value,y2019:document.getElementById('gr-2019').value,y2020:document.getElementById('gr-2020').value,y2021:document.getElementById('gr-2021').value,y2022:document.getElementById('gr-2022').value,y2023:document.getElementById('gr-2023').value});renderRows(grData,document.getElementById('gr-tbody'));document.getElementById('gr-panel').style.display='none';document.getElementById('gr-add').innerHTML='<i class="fas fa-plus"></i>';});
</script>
@endpush
.theme-purple .table-industry thead th{background:#ede9fe}
.theme-pink .table-industry thead th{background:#fde2e8}
.theme-orange .table-industry thead th{background:#ffedd5}
