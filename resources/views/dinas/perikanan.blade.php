@extends('layouts.app')
@section('title', 'Perikanan')
@section('body-class', 'dashboard-page force-light')

@push('styles')
<style>
.fk-card{border-radius:16px;overflow:hidden;margin-bottom:18px}
.fk-card .card-header{padding:16px 20px;display:flex;align-items:center;justify-content:space-between;background:#eef4ff;color:#1e3a8a;border-radius:16px 16px 0 0}
.fk-card .card-header h3{display:flex;flex-direction:column;line-height:1.25}
.fk-card .card-header h3 span{font-weight:500;font-size:13px;opacity:.9}
.fk-card .card-actions .btn{border-radius:9999px;padding:8px 14px;font-weight:600;background:#ffffff;color:#2563eb;border:1px solid #93c5fd;box-shadow:0 1px 4px rgba(37,99,235,.12)}
.fk-panel{margin:16px;border-radius:16px;padding:16px;border:1px solid #cfe0ff;background:#f8fbff}
.fk-panel .form-group{display:flex;flex-direction:column;gap:8px;margin-bottom:8px}
.fk-sub{font-size:13px;color:#1e3a8a;opacity:.9;margin-top:4px}
.pending-wrap{margin-top:10px}
.pending-wrap .pending-actions{display:flex;gap:8px;justify-content:flex-end;margin-bottom:6px}
.pending-table{width:100%;border:1px solid #93c5fd;border-radius:10px;overflow:hidden;border-collapse:separate;border-spacing:0}
.pending-table th,.pending-table td{border-bottom:1px solid #93c5fd;border-right:1px solid #93c5fd;padding:8px 10px}
.pending-table thead th{background:#eaf2ff;color:#1e3a8a;font-weight:600;text-align:center}
.pending-table tbody td input{width:100%;box-sizing:border-box;border:1px solid #93c5fd;border-radius:8px;padding:8px 10px}
.fk-grid{display:grid;gap:12px}
.fk-grid-2{grid-template-columns:repeat(2,1fr)}
.fk-grid-3{grid-template-columns:repeat(3,1fr)}
.fk-grid-5{grid-template-columns:repeat(5,minmax(90px,1fr))}
.year-group{display:flex;flex-direction:column;gap:6px}
.year-label{font-size:12px;color:#0f172a;font-weight:600}
.form-control{border:1px solid #93c5fd;background:#ffffff;border-radius:12px;padding:10px 12px}
.form-control:focus{outline:none;border-color:#7dd3fc;box-shadow:0 0 0 3px rgba(125,211,252,0.35)}
.table-wrap{border-radius:12px;overflow:hidden;background:#fff;box-shadow:0 1px 6px rgba(0,0,0,0.04)}
.fk-table{width:100%;border:1px solid #93c5fd !important;border-radius:12px;overflow:hidden;border-collapse:separate;border-spacing:0;table-layout:fixed}
.fk-table thead th{background:#dbeafe;color:#0f172a;font-weight:600;border-bottom:1px solid #93c5fd !important}
.fk-table th,.fk-table td{border-bottom:1px solid #93c5fd !important;border-right:1px solid #93c5fd !important;padding:10px 12px}
.fk-table thead tr th:first-child{border-left:1px solid #93c5fd}
.fk-table tbody tr td:first-child{border-left:1px solid #93c5fd}
.fk-table th{font-weight:600;text-align:center;vertical-align:middle}
.fk-table td{text-align:center;vertical-align:middle}
.fk-table th:last-child,.fk-table td:last-child{text-align:center}
.fk-table td:last-child{display:table-cell !important;text-align:center !important;white-space:nowrap}
#perikanan-page .fk-table td:last-child .btn{margin-right:0 !important}
.toolbar{display:flex;align-items:center;justify-content:flex-end;margin-bottom:10px}
.btn-outline{background:#fff;border:1px solid #e5e7eb;color:#111827;border-radius:12px}
.btn-primary{background:#2563eb;border-color:#2563eb;color:#fff}
.btn-secondary{background:#f1f5f9;color:#111827;border:1px solid #e5e7eb}
</style>
@endpush

@section('content')
    <div class="page active" id="perikanan-page">
      <div class="page-header"><h1>Data Perikanan</h1><p>Infrastruktur, alat tangkap, budidaya, produksi, dan bina kelompok</p></div>
      <style>
        #perikanan-page .card .card-header{background:linear-gradient(90deg,#60a5fa,#3b82f6);color:#ffffff;border-bottom:1px solid transparent}
        #perikanan-page .card .card-header .card-actions .btn{border-radius:8px;height:34px;padding:0 12px}
        #perikanan-page .btn.btn-outline.btn-sm.action-btn{border-radius:8px;width:30px;height:30px}
        #perikanan-page .toolbar{display:none !important}
      </style>
      <div class="card fk-card">
          <div class="card-header">
          <h3>Data Perikanan Lengkap<span>Kabupaten Kolaka Utara Tahun 2019 - 2023</span></h3>
          <div class="card-actions"><button class="btn btn-outline btn-sm" id="fk-export"><i class="fas fa-download"></i> Export</button> <button class="btn btn-outline btn-sm" id="fk-add"><i class="fas fa-plus"></i> Tambah Data</button></div>
        </div>
        <div class="card-body">
          <div class="segmented"><button class="btn btn-primary btn-sm" id="fk-tab-inf">Infrastruktur</button><button class="btn btn-outline btn-sm" id="fk-tab-alt">Alat Tangkap</button><button class="btn btn-outline btn-sm" id="fk-tab-bud">Budidaya</button><button class="btn btn-outline btn-sm" id="fk-tab-pro">Produksi</button><button class="btn btn-outline btn-sm" id="fk-tab-bin">Bina Kelompok</button></div>

          <div id="fk-inf">
            <h4>Perkembangan Jumlah Rumah Tangga Nelayan dan Alat Penangkapan Ikan</h4>
            <div class="fk-sub">Perkembangan jumlah RT nelayan dan alat penangkapan 2019–2023</div>
            <div class="fk-panel" id="fk-inf-panel" style="display:none;">
              <div class="form-group"><label>Uraian Infrastruktur Perikanan Tangkap</label><input class="form-control" id="inf-uraian" placeholder="Contoh: Jumlah Rumah Tangga Nelayan"></div>
              <div class="fk-grid fk-grid-5"><div class="form-group"><label>Tahun 2019</label><input class="form-control" id="inf-2019" placeholder="0"></div><div class="form-group"><label>Tahun 2020</label><input class="form-control" id="inf-2020" placeholder="0"></div><div class="form-group"><label>Tahun 2021</label><input class="form-control" id="inf-2021" placeholder="0"></div><div class="form-group"><label>Tahun 2022</label><input class="form-control" id="inf-2022" placeholder="0"></div><div class="form-group"><label>Tahun 2023</label><input class="form-control" id="inf-2023" placeholder="0"></div></div>
              <div style="display:flex;gap:8px;justify-content:flex-end"><button class="btn btn-secondary" id="inf-cancel">Batal</button><button class="btn btn-primary" id="inf-save">Simpan</button></div>
            </div>
            <div class="toolbar"></div>
            <div class="table-wrap"><table class="table table-compact fk-table"><thead><tr><th rowspan="2">Uraian Infrastruktur Perikanan Tangkap</th><th colspan="5">Jumlah (Unit)</th><th rowspan="2">Aksi</th></tr><tr><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody id="fk-inf-tbody"></tbody></table></div>
          </div>

          <div id="fk-alt" style="display:none;">
            <h4>Perkembangan Alat Penangkapan Ikan Menurut Jenis</h4>
            <div class="fk-sub">Jumlah alat penangkapan menurut kategori dan jenis 2019–2023</div>
            <div class="fk-panel" id="fk-alt-panel" style="display:none;">
              <div class="fk-grid fk-grid-2"><div class="form-group"><label>Kategori (Pukat/Jaring/Perangkap/Pancing)</label><input class="form-control" id="alt-kat" placeholder="Contoh: Jaring"></div><div class="form-group"><label>Jenis Alat Penangkapan Ikan</label><input class="form-control" id="alt-jenis" placeholder="Contoh: Jaring insang tetap"></div></div>
              <div class="fk-grid fk-grid-5"><div class="form-group"><label>Tahun 2019</label><input class="form-control" id="alt-2019" placeholder="0"></div><div class="form-group"><label>Tahun 2020</label><input class="form-control" id="alt-2020" placeholder="0"></div><div class="form-group"><label>Tahun 2021</label><input class="form-control" id="alt-2021" placeholder="0"></div><div class="form-group"><label>Tahun 2022</label><input class="form-control" id="alt-2022" placeholder="0"></div><div class="form-group"><label>Tahun 2023</label><input class="form-control" id="alt-2023" placeholder="0"></div></div>
              
              <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:10px"><button class="btn btn-secondary" id="alt-cancel">Batal</button><button class="btn btn-primary" id="alt-save">Simpan Semua</button></div>
            </div>
            <div class="toolbar"></div>
            <div class="table-wrap"><table class="table table-compact fk-table"><thead><tr><th colspan="2">Uraian Jenis Alat Penangkapan Ikan</th><th colspan="5">Tahun (Unit)</th><th rowspan="2">Aksi</th></tr><tr><th>Kategori</th><th>Jenis Alat</th><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody id="fk-alt-tbody"></tbody></table></div>
          </div>

          <div id="fk-bud" style="display:none;">
            <h4>Perkembangan Budidaya Perairan Menurut Jenis Pengolahan</h4>
            <div class="fk-sub">Ringkasan indikator budidaya perairan dan pengolahan 2019–2023</div>
            <div class="fk-panel" id="fk-bud-panel" style="display:none;">
              <div class="fk-grid fk-grid-2"><div class="form-group"><label>No</label><input class="form-control" id="bud-no" placeholder="1"></div><div class="form-group"><label>Uraian</label><input class="form-control" id="bud-uraian" placeholder="Contoh: Rumah Tangga Perikanan Budidaya"></div></div>
              <div class="fk-grid fk-grid-5"><div class="form-group"><label>Tahun 2019</label><input class="form-control" id="bud-2019" placeholder="0"></div><div class="form-group"><label>Tahun 2020</label><input class="form-control" id="bud-2020" placeholder="0"></div><div class="form-group"><label>Tahun 2021</label><input class="form-control" id="bud-2021" placeholder="0"></div><div class="form-group"><label>Tahun 2022</label><input class="form-control" id="bud-2022" placeholder="0"></div><div class="form-group"><label>Tahun 2023</label><input class="form-control" id="bud-2023" placeholder="0"></div></div>
              
              <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:10px"><button class="btn btn-secondary" id="bud-cancel">Batal</button><button class="btn btn-primary" id="bud-save">Simpan Semua</button></div>
            </div>
            <div class="toolbar"></div>
            <div class="table-wrap"><table class="table table-compact fk-table"><thead><tr><th rowspan="2">No</th><th rowspan="2">Uraian</th><th colspan="5">Tahun</th><th rowspan="2">Aksi</th></tr><tr><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody id="fk-bud-tbody"></tbody></table></div>
          </div>

          <div id="fk-pro" style="display:none;">
            <h4>Produksi Perikanan di Kabupaten Kolaka Utara</h4>
            <div class="fk-sub">Produksi perikanan per kategori dan uraian 2019–2023</div>
            <div class="fk-panel" id="fk-pro-panel" style="display:none;">
              <div class="fk-grid fk-grid-2"><div class="form-group"><label>Kategori</label><input class="form-control" id="pro-kat" placeholder="Contoh: Produksi Budidaya Perikanan (Ton)"></div><div class="form-group"><label>Uraian</label><input class="form-control" id="pro-uraian" placeholder="Contoh: Perikanan Laut (Ton)"></div></div>
              <div class="fk-grid fk-grid-5"><div class="form-group"><label>Tahun 2019</label><input class="form-control" id="pro-2019" placeholder="0"></div><div class="form-group"><label>Tahun 2020</label><input class="form-control" id="pro-2020" placeholder="0"></div><div class="form-group"><label>Tahun 2021</label><input class="form-control" id="pro-2021" placeholder="0"></div><div class="form-group"><label>Tahun 2022</label><input class="form-control" id="pro-2022" placeholder="0"></div><div class="form-group"><label>Tahun 2023</label><input class="form-control" id="pro-2023" placeholder="0"></div></div>
              
              <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:10px"><button class="btn btn-secondary" id="pro-cancel">Batal</button><button class="btn btn-primary" id="pro-save">Simpan Semua</button></div>
            </div>
            <div class="toolbar"></div>
            <div class="table-wrap"><table class="table table-compact fk-table"><thead><tr><th rowspan="2">No</th><th rowspan="2">Uraian</th><th colspan="5">Tahun</th><th rowspan="2">Aksi</th></tr><tr><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody id="fk-pro-tbody"></tbody></table></div>
          </div>

          <div id="fk-bin" style="display:none;">
            <h4>Cakupan Bina Kelompok Perikanan Tahun 2019 s.d 2023</h4>
            <div class="fk-sub">Ringkasan cakupan bina kelompok perikanan 2019–2023</div>
            <div class="fk-panel" id="fk-bin-panel" style="display:none;">
              <div class="fk-grid fk-grid-2"><div class="form-group"><label>No</label><input class="form-control" id="bin-no" placeholder="1"></div><div class="form-group"><label>Uraian</label><input class="form-control" id="bin-uraian" placeholder="Contoh: Jumlah Kelompok"></div></div>
              <div class="fk-grid fk-grid-5"><div class="form-group"><label>Tahun 2019</label><input class="form-control" id="bin-2019" placeholder="0"></div><div class="form-group"><label>Tahun 2020</label><input class="form-control" id="bin-2020" placeholder="0"></div><div class="form-group"><label>Tahun 2021</label><input class="form-control" id="bin-2021" placeholder="0"></div><div class="form-group"><label>Tahun 2022</label><input class="form-control" id="bin-2022" placeholder="0"></div><div class="form-group"><label>Tahun 2023</label><input class="form-control" id="bin-2023" placeholder="0"></div></div>
              <div style="display:flex;gap:8px;justify-content:flex-end"><button class="btn btn-secondary" id="bin-cancel">Batal</button><button class="btn btn-primary" id="bin-save">Simpan</button></div>
            </div>
            <div class="toolbar"></div>
            <div class="table-wrap"><table class="table table-compact fk-table"><thead><tr><th style="width:60px" rowspan="2">No</th><th style="width:40%" rowspan="2">Uraian</th><th colspan="5">Tahun</th><th rowspan="2">Aksi</th></tr><tr><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody id="fk-bin-tbody"></tbody></table></div>
          </div>

        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
var infRows=[{uraian:'Jumlah Rumah Tangga Nelayan',y2019:'1774',y2020:'2215',y2021:'1961',y2022:'1906',y2023:'2015'},{uraian:'Jumlah Perahu/Kapal (KK)',y2019:'1501',y2020:'1879',y2021:'1626',y2022:'1678',y2023:'1717'},{uraian:'Tambat Labuh',y2019:'1',y2020:'1',y2021:'1',y2022:'1',y2023:'2'},{uraian:'Jumlah TPI/PP',y2019:'3',y2020:'3',y2021:'3',y2022:'3',y2023:'3'},{uraian:'Unit alat penangkapan ikan',y2019:'1507',y2020:'2215',y2021:'1961',y2022:'1906',y2023:'1917'}];
var altRows=[{kat:'Pukat',jenis:'Pukat tarik pantai (Pukat Pantai)',y2019:'76',y2020:'50',y2021:'50',y2022:'27',y2023:'18'},{kat:'Pukat',jenis:'Payang',y2019:'76',y2020:'86',y2021:'-',y2022:'-',y2023:'-'},{kat:'Pukat',jenis:'Pukat hela pertengahan berpasangan',y2019:'71',y2020:'-',y2021:'-',y2022:'-',y2023:'-'},{kat:'Pukat',jenis:'Pukat udang',y2019:'58',y2020:'76',y2021:'76',y2022:'50',y2023:'48'},{kat:'Jaring',jenis:'Jaring insang tetap (Jaring liong bun)',y2019:'126',y2020:'236',y2021:'256',y2022:'271',y2023:'289'}];
var budRows=[{no:1,uraian:'Rumah Tangga Perikanan Budidaya',y2019:'1.751',y2020:'1.751',y2021:'1.754',y2022:'1443',y2023:'1442'},{no:2,uraian:'A. Pembenihan Air Payau (UPR)',y2019:'2',y2020:'2',y2021:'2',y2022:'2',y2023:'2'},{no:3,uraian:'B. Pembenihan Air Tawar (UPR) (Unit)',y2019:'1',y2020:'3',y2021:'3',y2022:'3',y2023:'3'}];
var proRows=[{no:1,uraian:'Produksi Budidaya Perikanan Perikanan Laut (Ton)',y2019:'15.675',y2020:'14.891',y2021:'15.338',y2022:'751',y2023:'378'},{no:2,uraian:'Produksi Budidaya Perikanan Perikanan Darat (Ton)',y2019:'27.661',y2020:'26.219',y2021:'27.612',y2022:'11.025',y2023:'3.329'},{no:3,uraian:'Produksi Budidaya Perikanan Total',y2019:'43.336',y2020:'41.111',y2021:'42.950',y2022:'11.776',y2023:'10.907'}];
var binRows=[{no:1,uraian:'Jumlah Kelompok yang mendapat pembinaan',y2019:'65',y2020:'56',y2021:'55',y2022:'55',y2023:'136'},{no:2,uraian:'Jumlah Kelompok Perikanan',y2019:'254',y2020:'234',y2021:'226',y2022:'226',y2023:'230'},{no:3,uraian:'Persentase',y2019:'26',y2020:'24',y2021:'24',y2022:'24',y2023:'59'}];

function renderInf(){var tb=document.getElementById('fk-inf-tbody');if(!tb)return;tb.innerHTML=infRows.map(function(r,i){return '<tr><td>'+r.uraian+'</td><td>'+r.y2019+'</td><td>'+r.y2020+'</td><td>'+r.y2021+'</td><td>'+r.y2022+'</td><td>'+r.y2023+'</td><td><button class="btn btn-outline btn-sm action-btn" data-fk-ed="inf:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-fk-del="inf:'+i+'"><i class="fas fa-trash"></i></button></td></tr>';}).join('');}
function renderAlt(){var tb=document.getElementById('fk-alt-tbody');if(!tb)return;tb.innerHTML=altRows.map(function(r,i){return '<tr><td>'+r.kat+'</td><td>'+r.jenis+'</td><td>'+r.y2019+'</td><td>'+r.y2020+'</td><td>'+r.y2021+'</td><td>'+r.y2022+'</td><td>'+r.y2023+'</td><td><button class="btn btn-outline btn-sm action-btn" data-fk-ed="alt:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-fk-del="alt:'+i+'"><i class="fas fa-trash"></i></button></td></tr>';}).join('');}
function renderBud(){var tb=document.getElementById('fk-bud-tbody');if(!tb)return;tb.innerHTML=budRows.map(function(r,i){return '<tr><td>'+r.no+'</td><td>'+r.uraian+'</td><td>'+r.y2019+'</td><td>'+r.y2020+'</td><td>'+r.y2021+'</td><td>'+r.y2022+'</td><td>'+r.y2023+'</td><td><button class="btn btn-outline btn-sm action-btn" data-fk-ed="bud:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-fk-del="bud:'+i+'"><i class="fas fa-trash"></i></button></td></tr>';}).join('');}
function renderPro(){var tb=document.getElementById('fk-pro-tbody');if(!tb)return;tb.innerHTML=proRows.map(function(r,i){return '<tr><td>'+r.no+'</td><td>'+r.uraian+'</td><td>'+r.y2019+'</td><td>'+r.y2020+'</td><td>'+r.y2021+'</td><td>'+r.y2022+'</td><td>'+r.y2023+'</td><td><button class="btn btn-outline btn-sm action-btn" data-fk-ed="pro:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-fk-del="pro:'+i+'"><i class="fas fa-trash"></i></button></td></tr>';}).join('');}
function renderBin(){var tb=document.getElementById('fk-bin-tbody');if(!tb)return;tb.innerHTML=binRows.map(function(r,i){return '<tr><td>'+r.no+'</td><td>'+r.uraian+'</td><td>'+r.y2019+'</td><td>'+r.y2020+'</td><td>'+r.y2021+'</td><td>'+r.y2022+'</td><td>'+r.y2023+'</td><td><button class="btn btn-outline btn-sm action-btn" data-fk-ed="bin:'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-fk-del="bin:'+i+'"><i class="fas fa-trash"></i></button></td></tr>';}).join('');}
function init(){renderInf();renderAlt();renderBud();renderPro();renderBin();}
init();

function toggleTab(active){['fk-inf','fk-alt','fk-bud','fk-pro','fk-bin'].forEach(function(id){document.getElementById(id).style.display=id===active?'block':'none';});var ids=['fk-tab-inf','fk-tab-alt','fk-tab-bud','fk-tab-pro','fk-tab-bin'];ids.forEach(function(i){var b=document.getElementById(i);b.classList.add('btn-outline');b.classList.remove('btn-primary');});var map={'fk-inf':'fk-tab-inf','fk-alt':'fk-tab-alt','fk-bud':'fk-tab-bud','fk-pro':'fk-tab-pro','fk-bin':'fk-tab-bin'};var act=document.getElementById(map[active]);act.classList.add('btn-primary');act.classList.remove('btn-outline');}
document.getElementById('fk-tab-inf')?.addEventListener('click',function(){toggleTab('fk-inf');});
document.getElementById('fk-tab-alt')?.addEventListener('click',function(){toggleTab('fk-alt');});
document.getElementById('fk-tab-bud')?.addEventListener('click',function(){toggleTab('fk-bud');});
document.getElementById('fk-tab-pro')?.addEventListener('click',function(){toggleTab('fk-pro');});
document.getElementById('fk-tab-bin')?.addEventListener('click',function(){toggleTab('fk-bin');});

 

document.getElementById('fk-add')?.addEventListener('click',function(){var current=document.querySelector('.fk-card .fk-panel:not([style*="display:none"])');if(current){current.style.display='none';}
  var active=['fk-inf','fk-alt','fk-bud','fk-pro','fk-bin'].find(function(id){return document.getElementById(id).style.display!=='none';})||'fk-inf';
  var panelId={fk_inf:'fk-inf-panel',fk_alt:'fk-alt-panel',fk_bud:'fk-bud-panel',fk_pro:'fk-pro-panel',fk_bin:'fk-bin-panel'};var pid=panelId[active.replace('-', '_')];document.getElementById(pid).style.display='block';});

document.getElementById('inf-cancel')?.addEventListener('click',function(){document.getElementById('fk-inf-panel').style.display='none';});
document.getElementById('alt-cancel')?.addEventListener('click',function(){document.getElementById('fk-alt-panel').style.display='none';});
document.getElementById('bud-cancel')?.addEventListener('click',function(){document.getElementById('fk-bud-panel').style.display='none';});
document.getElementById('pro-cancel')?.addEventListener('click',function(){document.getElementById('fk-pro-panel').style.display='none';});
document.getElementById('bin-cancel')?.addEventListener('click',function(){document.getElementById('fk-bin-panel').style.display='none';});
document.addEventListener('click',function(e){var ed=e.target.closest('[data-fk-ed]');var del=e.target.closest('[data-fk-del]');if(!ed&&!del)return;var p=(ed||del).getAttribute(ed?'data-fk-ed':'data-fk-del').split(':');var key=p[0],i=parseInt(p[1],10);var set=key==='inf'?infRows:key==='alt'?altRows:key==='bud'?budRows:key==='pro'?proRows:binRows;var panelId={'inf':'fk-inf-panel','alt':'fk-alt-panel','bud':'fk-bud-panel','pro':'fk-pro-panel','bin':'fk-bin-panel'}[key];if(ed){var r=set[i];if(key==='inf'){document.getElementById('inf-uraian').value=r.uraian;document.getElementById('inf-2019').value=r.y2019;document.getElementById('inf-2020').value=r.y2020;document.getElementById('inf-2021').value=r.y2021;document.getElementById('inf-2022').value=r.y2022;document.getElementById('inf-2023').value=r.y2023}else if(key==='alt'){document.getElementById('alt-kat').value=r.kat;document.getElementById('alt-jenis').value=r.jenis;document.getElementById('alt-2019').value=r.y2019;document.getElementById('alt-2020').value=r.y2020;document.getElementById('alt-2021').value=r.y2021;document.getElementById('alt-2022').value=r.y2022;document.getElementById('alt-2023').value=r.y2023}else{document.getElementById(key+'-no')&& (document.getElementById(key+'-no').value=r.no);document.getElementById(key+'-uraian').value=r.uraian;document.getElementById(key+'-2019').value=r.y2019;document.getElementById(key+'-2020').value=r.y2020;document.getElementById(key+'-2021').value=r.y2021;document.getElementById(key+'-2022').value=r.y2022;document.getElementById(key+'-2023').value=r.y2023}document.getElementById(panelId).style.display='block';} else {Utils.confirm('Hapus baris ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(function(yes){if(!yes)return;set.splice(i,1);set.forEach(function(r,idx){if(r.no!=null)r.no=idx+1});var renderMap={'inf':renderInf,'alt':renderAlt,'bud':renderBud,'pro':renderPro,'bin':renderBin};renderMap[key]();Utils.showToast('Baris dihapus','success');});}});



 

 

document.getElementById('inf-save')?.addEventListener('click',function(){infRows.push({uraian:document.getElementById('inf-uraian').value,y2019:document.getElementById('inf-2019').value,y2020:document.getElementById('inf-2020').value,y2021:document.getElementById('inf-2021').value,y2022:document.getElementById('inf-2022').value,y2023:document.getElementById('inf-2023').value});renderInf();document.getElementById('fk-inf-panel').style.display='none';});
document.getElementById('alt-save')?.addEventListener('click',function(){altRows.push({kat:document.getElementById('alt-kat').value,jenis:document.getElementById('alt-jenis').value,y2019:document.getElementById('alt-2019').value,y2020:document.getElementById('alt-2020').value,y2021:document.getElementById('alt-2021').value,y2022:document.getElementById('alt-2022').value,y2023:document.getElementById('alt-2023').value});renderAlt();document.getElementById('fk-alt-panel').style.display='none';});
document.getElementById('bud-save')?.addEventListener('click',function(){budRows.push({no:document.getElementById('bud-no').value,uraian:document.getElementById('bud-uraian').value,y2019:document.getElementById('bud-2019').value,y2020:document.getElementById('bud-2020').value,y2021:document.getElementById('bud-2021').value,y2022:document.getElementById('bud-2022').value,y2023:document.getElementById('bud-2023').value});renderBud();document.getElementById('fk-bud-panel').style.display='none';});
document.getElementById('pro-save')?.addEventListener('click',function(){proRows.push({no:proRows.length+1,uraian:(document.getElementById('pro-kat').value?document.getElementById('pro-kat').value+' ':'')+document.getElementById('pro-uraian').value,y2019:document.getElementById('pro-2019').value,y2020:document.getElementById('pro-2020').value,y2021:document.getElementById('pro-2021').value,y2022:document.getElementById('pro-2022').value,y2023:document.getElementById('pro-2023').value});renderPro();document.getElementById('fk-pro-panel').style.display='none';});
document.getElementById('bin-save')?.addEventListener('click',function(){binRows.push({no:document.getElementById('bin-no').value,uraian:document.getElementById('bin-uraian').value,y2019:document.getElementById('bin-2019').value,y2020:document.getElementById('bin-2020').value,y2021:document.getElementById('bin-2021').value,y2022:document.getElementById('bin-2022').value,y2023:document.getElementById('bin-2023').value});renderBin();document.getElementById('fk-bin-panel').style.display='none';});
function exportCsv(filename, headers, rows){var csv=[headers].concat(rows).map(function(row){return row.map(function(v){var s=(''+(v==null?'':v)).replace(/"/g,'""');return '"'+s+'"';}).join(',');}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download=filename;document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url);} 
document.getElementById('fk-export')?.addEventListener('click',function(){var sections=['fk-inf','fk-alt','fk-bud','fk-pro','fk-bin'];var active=sections.find(function(id){return document.getElementById(id).style.display!=='none';});if(active==='fk-inf'){var h=['Uraian','2019','2020','2021','2022','2023'];var rows=infRows.map(function(r){return [r.uraian,r.y2019,r.y2020,r.y2021,r.y2022,r.y2023]});exportCsv('perikanan-infrastruktur.csv',h,rows);}else if(active==='fk-alt'){var h=['Kategori','Jenis','2019','2020','2021','2022','2023'];var rows=altRows.map(function(r){return [r.kat,r.jenis,r.y2019,r.y2020,r.y2021,r.y2022,r.y2023]});exportCsv('perikanan-alat-tangkap.csv',h,rows);}else if(active==='fk-bud'){var h=['No','Uraian','2019','2020','2021','2022','2023'];var rows=budRows.map(function(r){return [r.no,r.uraian,r.y2019,r.y2020,r.y2021,r.y2022,r.y2023]});exportCsv('perikanan-budidaya.csv',h,rows);}else if(active==='fk-pro'){var h=['No','Uraian','2019','2020','2021','2022','2023'];var rows=proRows.map(function(r){return [r.no,r.uraian,r.y2019,r.y2020,r.y2021,r.y2022,r.y2023]});exportCsv('perikanan-produksi.csv',h,rows);}else if(active==='fk-bin'){var h=['No','Uraian','2019','2020','2021','2022','2023'];var rows=binRows.map(function(r){return [r.no,r.uraian,r.y2019,r.y2020,r.y2021,r.y2022,r.y2023]});exportCsv('perikanan-bina-kelompok.csv',h,rows);}});
</script>
@endpush
