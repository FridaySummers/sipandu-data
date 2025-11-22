@extends('layouts.app')
@section('title', 'Perikanan')
@section('body-class', 'dashboard-page force-light')

@push('styles')
<style>
.fk-card{border-radius:16px;overflow:hidden;margin-bottom:18px}
.fk-card .card-header{padding:16px 20px;display:flex;align-items:center;justify-content:space-between;background:#eef4ff;color:#1e3a8a;border-radius:16px 16px 0 0}
.fk-card .card-header h3{display:flex;flex-direction:column;line-height:1.25}
.fk-card .card-header h3 span{font-weight:500;font-size:13px;opacity:.9}
.fk-card .card-actions .btn{border-radius:9999px;padding:8px 14px;font-weight:600;background:#2563eb;color:#fff;border:1px solid #1d4ed8;box-shadow:0 2px 6px rgba(37,99,235,.2)}
.fk-tabs{display:flex;gap:8px;align-items:center;justify-content:flex-start;margin:14px 0}
.fk-tabs .seg{display:flex;gap:16px;background:#eef4ff;border:1px solid #cfe0ff;border-radius:9999px;padding:12px 16px;width:100%;justify-content:space-between}
.fk-tabs .seg .btn{border-radius:9999px;padding:12px 28px;font-weight:700;transition:all .2s ease;font-size:15px;flex:1 1 auto;text-align:center;min-width:140px}
.fk-tabs .seg .btn-primary{background:#ffffff;color:#1e3a8a;border:1px solid #cfe0ff;box-shadow:0 2px 6px rgba(29,78,216,.12),0 0 0 3px rgba(207,224,255,.6)}
.fk-tabs .seg .btn-outline{background:#eaf2ff;color:#1e3a8a;border:1px solid #cfe0ff}
.fk-tabs .seg .btn:hover{transform:translateY(-0.5px)}
.fk-panel{margin:16px;border-radius:16px;padding:16px;border:1px solid #cfe0ff;background:#f8fbff}
.fk-panel .form-group{display:flex;flex-direction:column;gap:8px;margin-bottom:8px}
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
.fk-table{width:100%;border:1px solid #93c5fd;border-radius:12px;overflow:hidden;border-collapse:separate;border-spacing:0;table-layout:fixed}
.fk-table thead th{background:#dbeafe;color:#0f172a;font-weight:600}
.fk-table th,.fk-table td{border-bottom:1px solid #93c5fd;border-right:1px solid #93c5fd;padding:10px 12px}
.fk-table thead tr th:first-child{border-left:1px solid #93c5fd}
.fk-table tbody tr td:first-child{border-left:1px solid #93c5fd}
.fk-table th{font-weight:600;text-align:center}
.fk-table td:nth-child(1),.fk-table td:nth-child(2),.fk-table th:nth-child(1),.fk-table th:nth-child(2){text-align:left}
.toolbar{display:flex;align-items:center;justify-content:flex-end;margin-bottom:10px}
.btn-outline{background:#fff;border:1px solid #e5e7eb;color:#111827;border-radius:12px}
.btn-primary{background:#2563eb;border-color:#2563eb;color:#fff}
.btn-secondary{background:#f1f5f9;color:#111827;border:1px solid #e5e7eb}
</style>
@endpush

@section('content')
    <div class="page active" id="perikanan-page">
      <div class="card fk-card">
        <div class="card-header">
          <h3>Data Perikanan Lengkap<span>Kabupaten Kolaka Utara Tahun 2019 - 2023</span></h3>
          <div class="card-actions"><button class="btn btn-sm" id="fk-add"><i class="fas fa-plus"></i> Tambah Data</button></div>
        </div>
        <div class="card-body">
          <div class="fk-tabs"><div class="seg"><button class="btn btn-primary btn-sm" id="fk-tab-inf">Infrastruktur</button><button class="btn btn-outline btn-sm" id="fk-tab-alt">Alat Tangkap</button><button class="btn btn-outline btn-sm" id="fk-tab-bud">Budidaya</button><button class="btn btn-outline btn-sm" id="fk-tab-pro">Produksi</button><button class="btn btn-outline btn-sm" id="fk-tab-bin">Bina Kelompok</button></div></div>

          <div id="fk-inf">
            <h4>Perkembangan Jumlah Rumah Tangga Nelayan dan Alat Penangkapan Ikan</h4>
            <div class="fk-panel" id="fk-inf-panel" style="display:none;">
              <div class="form-group"><label>Uraian Infrastruktur Perikanan Tangkap</label><input class="form-control" id="inf-uraian" placeholder="Contoh: Jumlah Rumah Tangga Nelayan"></div>
              <div class="fk-grid fk-grid-5"><div class="form-group"><label>Tahun 2019</label><input class="form-control" id="inf-2019" placeholder="0"></div><div class="form-group"><label>Tahun 2020</label><input class="form-control" id="inf-2020" placeholder="0"></div><div class="form-group"><label>Tahun 2021</label><input class="form-control" id="inf-2021" placeholder="0"></div><div class="form-group"><label>Tahun 2022</label><input class="form-control" id="inf-2022" placeholder="0"></div><div class="form-group"><label>Tahun 2023</label><input class="form-control" id="inf-2023" placeholder="0"></div></div>
              <div style="display:flex;gap:8px;justify-content:flex-end"><button class="btn btn-secondary" id="inf-cancel">Batal</button><button class="btn btn-primary" id="inf-save">Simpan</button></div>
            </div>
            <div class="table-wrap"><table class="table table-compact fk-table"><thead><tr><th rowspan="2">Uraian Infrastruktur Perikanan Tangkap</th><th colspan="5">Jumlah (Unit)</th></tr><tr><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody id="fk-inf-tbody"></tbody></table></div>
          </div>

          <div id="fk-alt" style="display:none;">
            <h4>Perkembangan Alat Penangkapan Ikan Menurut Jenis</h4>
            <div class="fk-panel" id="fk-alt-panel" style="display:none;">
              <div class="fk-grid fk-grid-2"><div class="form-group"><label>Kategori (Pukat/Jaring/Perangkap/Pancing)</label><input class="form-control" id="alt-kat" placeholder="Contoh: Jaring"></div><div class="form-group"><label>Jenis Alat Penangkapan Ikan</label><input class="form-control" id="alt-jenis" placeholder="Contoh: Jaring insang tetap"></div></div>
              <div class="fk-grid fk-grid-5"><div class="form-group"><label>Tahun 2019</label><input class="form-control" id="alt-2019" placeholder="0"></div><div class="form-group"><label>Tahun 2020</label><input class="form-control" id="alt-2020" placeholder="0"></div><div class="form-group"><label>Tahun 2021</label><input class="form-control" id="alt-2021" placeholder="0"></div><div class="form-group"><label>Tahun 2022</label><input class="form-control" id="alt-2022" placeholder="0"></div><div class="form-group"><label>Tahun 2023</label><input class="form-control" id="alt-2023" placeholder="0"></div></div>
              
              <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:10px"><button class="btn btn-secondary" id="alt-cancel">Batal</button><button class="btn btn-primary" id="alt-save">Simpan Semua</button></div>
            </div>
            <div class="table-wrap"><table class="table table-compact fk-table"><thead><tr><th colspan="2">Uraian Jenis Alat Penangkapan Ikan</th><th colspan="5">Tahun (Unit)</th></tr><tr><th>Kategori</th><th>Jenis Alat</th><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody id="fk-alt-tbody"></tbody></table></div>
          </div>

          <div id="fk-bud" style="display:none;">
            <h4>Perkembangan Budidaya Perairan Menurut Jenis Pengolahan</h4>
            <div class="fk-panel" id="fk-bud-panel" style="display:none;">
              <div class="fk-grid fk-grid-2"><div class="form-group"><label>No</label><input class="form-control" id="bud-no" placeholder="1"></div><div class="form-group"><label>Uraian</label><input class="form-control" id="bud-uraian" placeholder="Contoh: Rumah Tangga Perikanan Budidaya"></div></div>
              <div class="fk-grid fk-grid-5"><div class="form-group"><label>Tahun 2019</label><input class="form-control" id="bud-2019" placeholder="0"></div><div class="form-group"><label>Tahun 2020</label><input class="form-control" id="bud-2020" placeholder="0"></div><div class="form-group"><label>Tahun 2021</label><input class="form-control" id="bud-2021" placeholder="0"></div><div class="form-group"><label>Tahun 2022</label><input class="form-control" id="bud-2022" placeholder="0"></div><div class="form-group"><label>Tahun 2023</label><input class="form-control" id="bud-2023" placeholder="0"></div></div>
              
              <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:10px"><button class="btn btn-secondary" id="bud-cancel">Batal</button><button class="btn btn-primary" id="bud-save">Simpan Semua</button></div>
            </div>
            <div class="table-wrap"><table class="table table-compact fk-table"><thead><tr><th>No</th><th>Uraian</th><th colspan="5">Tahun</th></tr><tr><th></th><th></th><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody id="fk-bud-tbody"></tbody></table></div>
          </div>

          <div id="fk-pro" style="display:none;">
            <h4>Produksi Perikanan di Kabupaten Kolaka Utara</h4>
            <div class="fk-panel" id="fk-pro-panel" style="display:none;">
              <div class="fk-grid fk-grid-2"><div class="form-group"><label>Kategori</label><input class="form-control" id="pro-kat" placeholder="Contoh: Produksi Budidaya Perikanan (Ton)"></div><div class="form-group"><label>Uraian</label><input class="form-control" id="pro-uraian" placeholder="Contoh: Perikanan Laut (Ton)"></div></div>
              <div class="fk-grid fk-grid-5"><div class="form-group"><label>Tahun 2019</label><input class="form-control" id="pro-2019" placeholder="0"></div><div class="form-group"><label>Tahun 2020</label><input class="form-control" id="pro-2020" placeholder="0"></div><div class="form-group"><label>Tahun 2021</label><input class="form-control" id="pro-2021" placeholder="0"></div><div class="form-group"><label>Tahun 2022</label><input class="form-control" id="pro-2022" placeholder="0"></div><div class="form-group"><label>Tahun 2023</label><input class="form-control" id="pro-2023" placeholder="0"></div></div>
              
              <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:10px"><button class="btn btn-secondary" id="pro-cancel">Batal</button><button class="btn btn-primary" id="pro-save">Simpan Semua</button></div>
            </div>
            <div class="table-wrap"><table class="table table-compact fk-table"><thead><tr><th>No</th><th>Uraian</th><th colspan="5">Tahun</th></tr><tr><th></th><th></th><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody id="fk-pro-tbody"></tbody></table></div>
          </div>

          <div id="fk-bin" style="display:none;">
            <h4>Cakupan Bina Kelompok Perikanan Tahun 2019 s.d 2023</h4>
            <div class="fk-panel" id="fk-bin-panel" style="display:none;">
              <div class="fk-grid fk-grid-2"><div class="form-group"><label>No</label><input class="form-control" id="bin-no" placeholder="1"></div><div class="form-group"><label>Uraian</label><input class="form-control" id="bin-uraian" placeholder="Contoh: Jumlah Kelompok"></div></div>
              <div class="fk-grid fk-grid-5"><div class="form-group"><label>Tahun 2019</label><input class="form-control" id="bin-2019" placeholder="0"></div><div class="form-group"><label>Tahun 2020</label><input class="form-control" id="bin-2020" placeholder="0"></div><div class="form-group"><label>Tahun 2021</label><input class="form-control" id="bin-2021" placeholder="0"></div><div class="form-group"><label>Tahun 2022</label><input class="form-control" id="bin-2022" placeholder="0"></div><div class="form-group"><label>Tahun 2023</label><input class="form-control" id="bin-2023" placeholder="0"></div></div>
              <div style="display:flex;gap:8px;justify-content:flex-end"><button class="btn btn-secondary" id="bin-cancel">Batal</button><button class="btn btn-primary" id="bin-save">Simpan</button></div>
            </div>
            <div class="table-wrap"><table class="table table-compact fk-table"><thead><tr><th style="width:60px">No</th><th style="width:40%">Uraian</th><th colspan="5">Tahun</th></tr><tr><th></th><th></th><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr></thead><tbody id="fk-bin-tbody"></tbody></table></div>
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

function renderInf(){var tb=document.getElementById('fk-inf-tbody');if(!tb)return;tb.innerHTML=infRows.map(function(r){return '<tr><td>'+r.uraian+'</td><td>'+r.y2019+'</td><td>'+r.y2020+'</td><td>'+r.y2021+'</td><td>'+r.y2022+'</td><td>'+r.y2023+'</td></tr>';}).join('');}
function renderAlt(){var tb=document.getElementById('fk-alt-tbody');if(!tb)return;tb.innerHTML=altRows.map(function(r){return '<tr><td>'+r.kat+'</td><td>'+r.jenis+'</td><td>'+r.y2019+'</td><td>'+r.y2020+'</td><td>'+r.y2021+'</td><td>'+r.y2022+'</td><td>'+r.y2023+'</td></tr>';}).join('');}
function renderBud(){var tb=document.getElementById('fk-bud-tbody');if(!tb)return;tb.innerHTML=budRows.map(function(r){return '<tr><td>'+r.no+'</td><td>'+r.uraian+'</td><td>'+r.y2019+'</td><td>'+r.y2020+'</td><td>'+r.y2021+'</td><td>'+r.y2022+'</td><td>'+r.y2023+'</td></tr>';}).join('');}
function renderPro(){var tb=document.getElementById('fk-pro-tbody');if(!tb)return;tb.innerHTML=proRows.map(function(r){return '<tr><td>'+r.no+'</td><td>'+r.uraian+'</td><td>'+r.y2019+'</td><td>'+r.y2020+'</td><td>'+r.y2021+'</td><td>'+r.y2022+'</td><td>'+r.y2023+'</td></tr>';}).join('');}
function renderBin(){var tb=document.getElementById('fk-bin-tbody');if(!tb)return;tb.innerHTML=binRows.map(function(r){return '<tr><td>'+r.no+'</td><td>'+r.uraian+'</td><td>'+r.y2019+'</td><td>'+r.y2020+'</td><td>'+r.y2021+'</td><td>'+r.y2022+'</td><td>'+r.y2023+'</td></tr>';}).join('');}
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



 

 

document.getElementById('inf-save')?.addEventListener('click',function(){infRows.push({uraian:document.getElementById('inf-uraian').value,y2019:document.getElementById('inf-2019').value,y2020:document.getElementById('inf-2020').value,y2021:document.getElementById('inf-2021').value,y2022:document.getElementById('inf-2022').value,y2023:document.getElementById('inf-2023').value});renderInf();document.getElementById('fk-inf-panel').style.display='none';});
document.getElementById('alt-save')?.addEventListener('click',function(){altRows.push({kat:document.getElementById('alt-kat').value,jenis:document.getElementById('alt-jenis').value,y2019:document.getElementById('alt-2019').value,y2020:document.getElementById('alt-2020').value,y2021:document.getElementById('alt-2021').value,y2022:document.getElementById('alt-2022').value,y2023:document.getElementById('alt-2023').value});renderAlt();document.getElementById('fk-alt-panel').style.display='none';});
document.getElementById('bud-save')?.addEventListener('click',function(){budRows.push({no:document.getElementById('bud-no').value,uraian:document.getElementById('bud-uraian').value,y2019:document.getElementById('bud-2019').value,y2020:document.getElementById('bud-2020').value,y2021:document.getElementById('bud-2021').value,y2022:document.getElementById('bud-2022').value,y2023:document.getElementById('bud-2023').value});renderBud();document.getElementById('fk-bud-panel').style.display='none';});
document.getElementById('pro-save')?.addEventListener('click',function(){proRows.push({no:proRows.length+1,uraian:(document.getElementById('pro-kat').value?document.getElementById('pro-kat').value+' ':'')+document.getElementById('pro-uraian').value,y2019:document.getElementById('pro-2019').value,y2020:document.getElementById('pro-2020').value,y2021:document.getElementById('pro-2021').value,y2022:document.getElementById('pro-2022').value,y2023:document.getElementById('pro-2023').value});renderPro();document.getElementById('fk-pro-panel').style.display='none';});
document.getElementById('bin-save')?.addEventListener('click',function(){binRows.push({no:document.getElementById('bin-no').value,uraian:document.getElementById('bin-uraian').value,y2019:document.getElementById('bin-2019').value,y2020:document.getElementById('bin-2020').value,y2021:document.getElementById('bin-2021').value,y2022:document.getElementById('bin-2022').value,y2023:document.getElementById('bin-2023').value});renderBin();document.getElementById('fk-bin-panel').style.display='none';});
</script>
@endpush
