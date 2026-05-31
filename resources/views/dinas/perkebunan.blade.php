@extends('layouts.app')
@section('title', 'Perkebunan')
@section('body-class', 'dashboard-page force-light')

@push('styles')
<style>
.pk-toolbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px}
.segmented{display:inline-flex;gap:6px;background:#f1f5f9;padding:6px;border-radius:12px}
.segmented .btn{height:36px;border-radius:8px;font-weight:600;font-size:13px;padding:0 16px;transition:all .2s;border:none}
.segmented .btn-primary{background:#fff;color:#1d4ed8;box-shadow:0 2px 8px rgba(0,0,0,0.08)}
.segmented .btn-outline{background:transparent;color:#64748b}
.segmented .btn-outline:hover{background:rgba(255,255,255,0.6);color:#374151}
.pk-card{border-radius:20px;overflow:hidden;margin-bottom:24px;box-shadow:0 4px 24px rgba(0,0,0,0.06);border:1px solid #e2e8f0;background:#fff}
.pk-card .card-header{padding:20px 24px;display:flex;align-items:center;justify-content:space-between;color:#fff}
.pk-card .card-header h3{font-size:1.1rem;font-weight:700;margin:0}
.pk-card .card-header .sub{font-size:13px;opacity:.85;color:#fff;margin-top:4px}
.pk-card .card-actions{display:flex;gap:10px}
.pk-card .card-actions .btn{border-radius:10px;height:36px;padding:0 14px;font-weight:600;font-size:13px;transition:all .2s;display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.4);backdrop-filter:blur(4px)}
.pk-card .card-actions .btn:hover{background:rgba(255,255,255,0.25)}
.pk-card .card-actions .btn:last-child{background:#fff;color:#1d4ed8;border:none}
.pk-card .card-actions .btn:last-child:hover{box-shadow:0 4px 12px rgba(255,255,255,0.3);transform:translateY(-1px)}
.theme-green .card-actions .btn:last-child{color:#15803d}
.theme-amber .card-actions .btn:last-child{color:#b45309}
.theme-teal .card-actions .btn:last-child{color:#0f766e}
.pk-panel{margin:20px;border-radius:14px;padding:20px;border:1px solid #e2e8f0;background:#f8fafc}
.pk-panel .form-group{display:flex;flex-direction:column;gap:6px;margin-bottom:12px}
.pk-panel .form-group label{font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.04em}
.pk-grid{display:grid;gap:12px}
.pk-grid-5{grid-template-columns:repeat(5,1fr)}
.pk-grid-6{grid-template-columns:repeat(6,1fr)}
.year-group{display:flex;flex-direction:column;gap:6px}
.year-label{font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.04em}
.theme-green .year-label{color:#15803d}
.theme-amber .year-label{color:#b45309}
.theme-teal .year-label{color:#0f766e}
.pk-table-wrap{border-radius:14px;overflow-x:auto;border:1px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,0.04);margin:0 20px 20px}
.pk-table{width:100%;border-collapse:separate;border-spacing:0;background:#fff;margin:0}
.pk-table thead th{color:#1e3a8a;font-weight:700;font-size:13px;border-bottom:2px solid #bfdbfe;padding:12px 14px}
.th-group-year{background:rgba(0,0,0,0.02)}
.pk-table th,.pk-table td{border:1px solid #cbd5e1;border-right:1px solid #f1f5f9;padding:11px 14px;word-break:break-word;white-space:normal}
.pk-table tbody tr{transition:background .15s ease}
.pk-table tbody tr:hover{background:#f8fafc}
.pk-table th:last-child,.pk-table td:last-child{text-align:center;border-right:none}
.pk-table td:last-child{display:table-cell !important;white-space:nowrap}
.pk-table th{text-align:center}
.pk-table td:nth-child(1),.pk-table td:nth-child(2),.pk-table th:nth-child(1),.pk-table th:nth-child(2){text-align:left}
.pk-table td:not(:nth-child(1)):not(:nth-child(2)){text-align:center}
.edit-cell{border:1.5px solid #bfdbfe;background:#ffffff;border-radius:8px;padding:7px 10px;width:100%;box-sizing:border-box;transition:all .2s}
.edit-cell:focus{border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,0.12);outline:none}
.form-control{border:1.5px solid #e2e8f0;background:#ffffff;border-radius:10px;padding:10px 14px;font-size:14px;transition:all .2s;width:100%;box-sizing:border-box}
.form-control:focus{outline:none;border-color:#3b82f6;box-shadow:0 0 0 4px rgba(59,130,246,0.12)}
.theme-green .card-header{background:linear-gradient(135deg,#16a34a,#22c55e)}
.theme-green .pk-panel{background:#f0fdf4;border-color:#bbf7d0}
.theme-green .pk-table thead th{background:linear-gradient(135deg,#f0fdf4,#dcfce7);color:#14532d;border-bottom-color:#bbf7d0}
.theme-amber .card-header{background:linear-gradient(135deg,#d97706,#f59e0b)}
.theme-amber .pk-panel{background:#fffbeb;border-color:#fde68a}
.theme-amber .pk-table thead th{background:linear-gradient(135deg,#fffbeb,#fef3c7);color:#78350f;border-bottom-color:#fde68a}
.theme-teal .card-header{background:linear-gradient(135deg,#0d9488,#14b8a6)}
.theme-teal .pk-panel{background:#f0fdfa;border-color:#99f6e4}
.theme-teal .pk-table thead th{background:linear-gradient(135deg,#f0fdfa,#ccfbf1);color:#134e4a;border-bottom-color:#99f6e4}
.status-badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600}
.status-approved{background:#dcfce7;color:#166534}
.status-rejected{background:#fee2e2;color:#991b1b}
.status-menunggu{background:#fef3c7;color:#92400e}
.pk-table thead th:last-child{width:100px !important}
@media (max-width: 640px){.pk-table thead th:last-child{width:auto !important}.pk-table td:last-child{white-space:normal !important}}
.row-actions{display:flex;gap:6px;justify-content:center}
.action-btn{border-radius:8px;width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#f8fafc;border:1px solid #e2e8f0;transition:all .2s;color:#374151}
.action-btn:hover{background:#eff6ff;border-color:#93c5fd;color:#2563eb}
.btn-primary{background:linear-gradient(135deg,#3b82f6,#2563eb);color:#fff;border:none;border-radius:10px;font-weight:600;transition:all .2s}
.btn-primary:hover{box-shadow:0 4px 14px rgba(37,99,235,0.3);transform:translateY(-1px)}
.btn-secondary{background:#f1f5f9;color:#374151;border:1px solid #e2e8f0;border-radius:10px;font-weight:600;transition:all .2s}
.btn-secondary:hover{background:#e2e8f0}
</style>
@endpush

@section('content')
    <div class="page active" id="perkebunan-page" data-opd-name="{{ optional(optional(auth()->user())->dinas)->nama_dinas ?? 'Dinas Perkebunan' }}">
      <div class="page-header"><h1>Perkebunan</h1><p>Populasi Ternak, Produksi Tanaman, dan Luas Areal (2025-2029)</p></div>
      <div class="pk-toolbar"><div class="segmented"><button class="btn btn-primary btn-sm" id="pk-tab-pop">Populasi Ternak</button><button class="btn btn-outline btn-sm" id="pk-tab-prod">Produksi Tanaman</button><button class="btn btn-outline btn-sm" id="pk-tab-luas">Luas Areal</button></div><div></div></div>

      <div id="pk-pop">
        <div class="card pk-card theme-green">
          <div class="card-header"><div><h3>Populasi Ternak (2025-2029)</h3><div class="sub">Ringkasan populasi ternak perkebunan</div></div><div class="card-actions"><button class="btn btn-primary btn-sm" id="pk-pop-export"><i class="fas fa-download"></i> Export Data</button> <button class="btn btn-primary btn-sm" id="pk-pop-add"><i class="fas fa-plus"></i> Ajukan Data</button></div></div>
          <div class="card-body">
            <div class="pk-panel" id="pk-pop-panel" style="display:none;">
              <div class="form-group"><label>Nama Data</label><input type="text" id="pop-uraian" class="form-control" placeholder="Contoh: Nama Data"></div>
              <div class="pk-grid pk-grid-6">
                <div class="year-group"><div class="year-label">2025</div><input class="form-control" id="pop-2025" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2026</div><input class="form-control" id="pop-2026" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2027</div><input class="form-control" id="pop-2027" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2028</div><input class="form-control" id="pop-2028" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2029</div><input class="form-control" id="pop-2029" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">Trend (%)</div><input class="form-control" id="pop-trend" placeholder="0.00"></div>
              </div>
              <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px"><button class="btn btn-secondary" id="pk-pop-cancel">Batal</button><button class="btn btn-primary" id="pk-pop-save">Simpan</button></div>
            </div>
            <div class="pk-table-wrap"><table class="table table-compact pk-table"><thead><tr><th rowspan="2">No.</th><th rowspan="2">Uraian</th><th class="th-group-year" colspan="5">Tahun</th><th rowspan="2">Trend (%)</th><th rowspan="2">Status</th><th rowspan="2">Aksi</th></tr><tr><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th></tr></thead><tbody id="pk-pop-tbody"></tbody></table></div>
          </div>
        </div>
      </div>

      <div id="pk-prod" style="display:none;">
        <div class="card pk-card theme-amber">
          <div class="card-header"><div><h3>Produksi Tanaman Perkebunan (2025-2029)</h3><div class="sub">Rekap produksi tiap komoditas</div></div><div class="card-actions"><button class="btn btn-primary btn-sm" id="pk-prod-export"><i class="fas fa-download"></i> Export Data</button> <button class="btn btn-primary btn-sm" id="pk-prod-add"><i class="fas fa-plus"></i> Ajukan Data</button></div></div>
          <div class="card-body">
            <div class="pk-panel" id="pk-prod-panel" style="display:none;">
              <div class="form-group"><label>Nama Data</label><input type="text" id="prod-uraian" class="form-control" placeholder="Contoh: Nama Data"></div>
              <div class="pk-grid pk-grid-5">
                <div class="year-group"><div class="year-label">2025</div><input class="form-control" id="prod-2025" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2026</div><input class="form-control" id="prod-2026" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2027</div><input class="form-control" id="prod-2027" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2028</div><input class="form-control" id="prod-2028" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2029</div><input class="form-control" id="prod-2029" placeholder="0.00"></div>
              </div>
              <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px"><button class="btn btn-secondary" id="pk-prod-cancel">Batal</button><button class="btn btn-primary" id="pk-prod-save">Simpan</button></div>
            </div>
            <div class="pk-table-wrap"><table class="table table-compact pk-table"><thead><tr><th>No.</th><th>Komoditas</th><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th><th>Status</th><th>Aksi</th></tr></thead><tbody id="pk-prod-tbody"></tbody></table></div>
          </div>
        </div>
      </div>

      <div id="pk-luas" style="display:none;">
        <div class="card pk-card theme-teal">
          <div class="card-header"><div><h3>Luas Areal Tanaman Perkebunan (ha)</h3><div class="sub">Perkembangan luas areal komoditas</div></div><div class="card-actions"><button class="btn btn-primary btn-sm" id="pk-luas-export"><i class="fas fa-download"></i> Export Data</button> <button class="btn btn-primary btn-sm" id="pk-luas-add"><i class="fas fa-plus"></i> Ajukan Data</button></div></div>
          <div class="card-body">
            <div class="pk-panel" id="pk-luas-panel" style="display:none;">
              <div class="form-group"><label>Nama Data</label><input type="text" id="luas-uraian" class="form-control" placeholder="Contoh: Nama Data"></div>
              <div class="pk-grid pk-grid-5">
                <div class="year-group"><div class="year-label">2025</div><input class="form-control" id="luas-2025" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2026</div><input class="form-control" id="luas-2026" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2027</div><input class="form-control" id="luas-2027" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2028</div><input class="form-control" id="luas-2028" placeholder="0.00"></div>
                <div class="year-group"><div class="year-label">2029</div><input class="form-control" id="luas-2029" placeholder="0.00"></div>
              </div>
              <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px"><button class="btn btn-secondary" id="pk-luas-cancel">Batal</button><button class="btn btn-primary" id="pk-luas-save">Simpan</button></div>
            </div>
            <div class="pk-table-wrap"><table class="table table-compact pk-table"><thead><tr><th>No.</th><th>Komoditas</th><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th><th>Status</th><th>Aksi</th></tr></thead><tbody id="pk-luas-tbody"></tbody></table></div>
          </div>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
var csrfToken=document.querySelector('meta[name="csrf-token"]')?.content||'';window.USER_ROLE=document.body.dataset.userRole||'';
var opdName=document.getElementById('perkebunan-page').dataset.opdName||'Dinas Perkebunan';
var keys={pop:'perkebunan_populasi',prod:'perkebunan_produksi',luas:'perkebunan_luas'};
var dinasId=(document.body.dataset.dinasId||'')||null;
var popRows=[],prodRows=[],luasRows=[];
var dmStatuses={};function statusLabel(s){if(s==='Disetujui')return 'Disetujui';if(s==='Ditolak')return 'Ditolak';if(s==='Menunggu Persetujuan')return 'Menunggu Persetujuan';return '-'}function statusClass(s){if(s==='Disetujui')return 'status-approved';if(s==='Ditolak')return 'status-rejected';if(s==='Menunggu Persetujuan')return 'status-menunggu';return ''}
async function fetchDmStatuses(){try{var res=await fetch('/data-management/records',{headers:{'Accept':'application/json'},credentials:'same-origin'});var data=await res.json();dmStatuses={};(Array.isArray(data)?data:[]).forEach(function(rec){if(rec.opd===opdName){dmStatuses[rec.name]=rec.status;}});}catch(_){dmStatuses={}}}
function mapPop(data){return (Array.isArray(data)?data:[]).map(function(r,i){var v=r.values||{};return {id:r.id,no:i+1,uraian:r.uraian,y2025:v.y2025||'',y2026:v.y2026||'',y2027:v.y2027||'',y2028:v.y2028||'',y2029:v.y2029||'',trend:v.trend||''};});}
function mapStd(data){return (Array.isArray(data)?data:[]).map(function(r,i){var v=r.values||{};return {id:r.id,no:i+1,uraian:r.uraian,y2025:v.y2025||'',y2026:v.y2026||'',y2027:v.y2027||'',y2028:v.y2028||'',y2029:v.y2029||''};});}
function renderRows(data,tb,includeTrend,key){tb.innerHTML=data.map(function(r,i){var cells='<td>'+r.no+'</td><td class="c-uraian">'+r.uraian+'</td><td class="c-y2025">'+(r.y2025||'-')+'</td><td class="c-y2026">'+(r.y2026||'-')+'</td><td class="c-y2027">'+(r.y2027||'-')+'</td><td class="c-y2028">'+(r.y2028||'-')+'</td><td class="c-y2029">'+(r.y2029||'-')+'</td>';if(includeTrend){cells+='<td class="c-trend">'+(r.trend||'-')+'</td>';}var st=dmStatuses[r.uraian];cells+='<td class="c-status"><span class="status-badge '+statusClass(st)+'">'+statusLabel(st)+'</span></td>';var actions=(window.USER_ROLE==='user')?('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-pk-ed="'+key+':'+i+'"><i class="fas fa-pen"></i></button></div>'):('<div class="row-actions"><button class="btn btn-outline btn-sm action-btn" data-pk-ed="'+key+':'+i+'"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-pk-del="'+key+':'+i+'"><i class="fas fa-trash"></i></button></div>');cells+='<td>'+actions+'</td>';return '<tr data-row="'+r.no+'">'+cells+'</tr>';}).join('');}
async function fetchRows(key){try{var url= key==='pop' ? '/perkebunan/pop' : (key==='prod' ? '/perkebunan/prod' : '/perkebunan/luas'); var res=await fetch(url,{headers:{'Accept':'application/json'}});var data=await res.json();if(key==='pop'){popRows=mapPop(data);renderRows(popRows,document.getElementById('pk-pop-tbody'),true,'pop');}else if(key==='prod'){prodRows=mapStd(data);renderRows(prodRows,document.getElementById('pk-prod-tbody'),false,'prod');}else{luasRows=mapStd(data);renderRows(luasRows,document.getElementById('pk-luas-tbody'),false,'luas');}}catch(_){if(key==='pop'){popRows=[];renderRows(popRows,document.getElementById('pk-pop-tbody'),true,'pop');}else if(key==='prod'){prodRows=[];renderRows(prodRows,document.getElementById('pk-prod-tbody'),false,'prod');}else{luasRows=[];renderRows(luasRows,document.getElementById('pk-luas-tbody'),false,'luas');}}}
document.addEventListener('DOMContentLoaded',async function(){await fetchRows('pop');await fetchRows('prod');await fetchRows('luas');await fetchDmStatuses();document.getElementById('pk-pop-tbody')&&renderRows(popRows,document.getElementById('pk-pop-tbody'),true,'pop');document.getElementById('pk-prod-tbody')&&renderRows(prodRows,document.getElementById('pk-prod-tbody'),false,'prod');document.getElementById('pk-luas-tbody')&&renderRows(luasRows,document.getElementById('pk-luas-tbody'),false,'luas');});
function toggleTab(active){['pk-pop','pk-prod','pk-luas'].forEach(function(id){var el=document.getElementById(id);if(el){el.style.display=id===active?'block':'none';}});var tp=document.getElementById('pk-tab-pop');var td=document.getElementById('pk-tab-prod');var tl=document.getElementById('pk-tab-luas');[tp,td,tl].filter(Boolean).forEach(function(b){b.classList.add('btn-outline');b.classList.remove('btn-primary');});if(active==='pk-pop'&&tp){tp.classList.add('btn-primary');tp.classList.remove('btn-outline');}else if(active==='pk-prod'&&td){td.classList.add('btn-primary');td.classList.remove('btn-outline');}else if(tl){tl.classList.add('btn-primary');tl.classList.remove('btn-outline');}}
document.getElementById('pk-tab-pop')?.addEventListener('click',function(){toggleTab('pk-pop');});
document.getElementById('pk-tab-prod')?.addEventListener('click',function(){toggleTab('pk-prod');});
document.getElementById('pk-tab-luas')?.addEventListener('click',function(){toggleTab('pk-luas');});
function toggle(btn,panel){var open=panel.style.display!=='none';panel.style.display=open?'none':'block';btn.innerHTML=open?'<i class="fas fa-plus"></i> Ajukan Data':'Tutup Form';}
document.getElementById('pk-pop-add')?.addEventListener('click',function(){popEditIndex=-1;popEditId='';toggle(this,document.getElementById('pk-pop-panel'));});
document.getElementById('pk-prod-add')?.addEventListener('click',function(){prodEditIndex=-1;prodEditId='';toggle(this,document.getElementById('pk-prod-panel'));});
document.getElementById('pk-luas-add')?.addEventListener('click',function(){luasEditIndex=-1;luasEditId='';toggle(this,document.getElementById('pk-luas-panel'));});
document.getElementById('pk-pop-cancel')?.addEventListener('click',function(){popEditIndex=-1;popEditId='';document.getElementById('pk-pop-panel').style.display='none';document.getElementById('pk-pop-add').textContent='+ Ajukan Data';});
document.getElementById('pk-prod-cancel')?.addEventListener('click',function(){prodEditIndex=-1;prodEditId='';document.getElementById('pk-prod-panel').style.display='none';document.getElementById('pk-prod-add').textContent='+ Ajukan Data';});
document.getElementById('pk-luas-cancel')?.addEventListener('click',function(){luasEditIndex=-1;luasEditId='';document.getElementById('pk-luas-panel').style.display='none';document.getElementById('pk-luas-add').textContent='+ Ajukan Data';});
async function submitDM(judul,year,key){try{var fp= key==='pop' ? 'perkebunan_pop' : (key==='prod' ? 'perkebunan_prod' : 'perkebunan_luas'); await fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({opd:opdName,dinas_id:dinasId,judul_data:judul,deskripsi:null,file_path:fp,tahun_perencanaan:year})});await fetchDmStatuses();}catch(_){}}
document.getElementById('pk-pop-save')?.addEventListener('click',async function(){var ura=document.getElementById('pop-uraian').value.trim();if(!ura){Utils.showToast('Isi Nama Data','error');return;}var vals={y2025:document.getElementById('pop-2025').value.trim(),y2026:document.getElementById('pop-2026').value.trim(),y2027:document.getElementById('pop-2027').value.trim(),y2028:document.getElementById('pop-2028').value.trim(),y2029:document.getElementById('pop-2029').value.trim(),trend:document.getElementById('pop-trend').value.trim()};var hasVal=(vals.y2025||vals.y2026||vals.y2027||vals.y2028||vals.y2029);if(!hasVal){Utils.showToast('Isi minimal salah satu nilai tahun','error');return;}var _yrRaw=(vals.y2029||'').replace(/[^0-9]/g,'');var year=(_yrRaw&&_yrRaw.length===4)?_yrRaw:new Date().getFullYear().toString();var isUser=(window.USER_ROLE==='user');try{if(popEditId){if(!isUser){var res=await fetch('/perkebunan/pop/'+popEditId,{method:'PUT',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:ura,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows('pop');}}else{if(!isUser){var res=await fetch('/perkebunan/pop',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:ura,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows('pop');}}await submitDM(ura,year,'pop');document.getElementById('pk-pop-panel').style.display='none';document.getElementById('pk-pop-add').innerHTML='<i class="fas fa-plus"></i> Ajukan Data';popEditIndex=-1;popEditId='';Utils.showToast('Data disimpan','success');}catch(e){Utils.showToast('Gagal menyimpan','error');}});
document.getElementById('pk-prod-save')?.addEventListener('click',async function(){var ura=document.getElementById('prod-uraian').value.trim();if(!ura){Utils.showToast('Isi Nama Data','error');return;}var vals={y2025:document.getElementById('prod-2025').value.trim(),y2026:document.getElementById('prod-2026').value.trim(),y2027:document.getElementById('prod-2027').value.trim(),y2028:document.getElementById('prod-2028').value.trim(),y2029:document.getElementById('prod-2029').value.trim()};var hasVal=(vals.y2025||vals.y2026||vals.y2027||vals.y2028||vals.y2029);if(!hasVal){Utils.showToast('Isi minimal salah satu nilai tahun','error');return;}var _yrRaw=(vals.y2029||'').replace(/[^0-9]/g,'');var year=(_yrRaw&&_yrRaw.length===4)?_yrRaw:new Date().getFullYear().toString();var isUser=(window.USER_ROLE==='user');try{if(prodEditId){if(!isUser){var res=await fetch('/perkebunan/prod/'+prodEditId,{method:'PUT',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:ura,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows('prod');}}else{if(!isUser){var res=await fetch('/perkebunan/prod',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:ura,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows('prod');}}await submitDM(ura,year,'prod');document.getElementById('pk-prod-panel').style.display='none';document.getElementById('pk-prod-add').innerHTML='<i class="fas fa-plus"></i> Ajukan Data';prodEditIndex=-1;prodEditId='';Utils.showToast('Data disimpan','success');}catch(e){Utils.showToast('Gagal menyimpan','error');}});
document.getElementById('pk-luas-save')?.addEventListener('click',async function(){var ura=document.getElementById('luas-uraian').value.trim();if(!ura){Utils.showToast('Isi Nama Data','error');return;}var vals={y2025:document.getElementById('luas-2025').value.trim(),y2026:document.getElementById('luas-2026').value.trim(),y2027:document.getElementById('luas-2027').value.trim(),y2028:document.getElementById('luas-2028').value.trim(),y2029:document.getElementById('luas-2029').value.trim()};var hasVal=(vals.y2025||vals.y2026||vals.y2027||vals.y2028||vals.y2029);if(!hasVal){Utils.showToast('Isi minimal salah satu nilai tahun','error');return;}var _yrRaw=(vals.y2029||'').replace(/[^0-9]/g,'');var year=(_yrRaw&&_yrRaw.length===4)?_yrRaw:new Date().getFullYear().toString();var isUser=(window.USER_ROLE==='user');try{if(luasEditId){if(!isUser){var res=await fetch('/perkebunan/luas/'+luasEditId,{method:'PUT',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:ura,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows('luas');}}else{if(!isUser){var res=await fetch('/perkebunan/luas',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:ura,values:vals})});if(!res.ok){Utils.showToast('Gagal menyimpan','error');return;}await fetchRows('luas');}}await submitDM(ura,year,'luas');document.getElementById('pk-luas-panel').style.display='none';document.getElementById('pk-luas-add').innerHTML='<i class="fas fa-plus"></i> Ajukan Data';luasEditIndex=-1;luasEditId='';Utils.showToast('Data disimpan','success');}catch(e){Utils.showToast('Gagal menyimpan','error');}});
var popEditIndex=-1,prodEditIndex=-1,luasEditIndex=-1;var popEditId='',prodEditId='',luasEditId='';
function enableInlineEdit(tbody,data,key){tbody?.addEventListener('dblclick',function(e){var td=e.target.closest('td');if(!td)return;if(!td.className.match(/^c\-/))return;var val=td.textContent;var controls='<div style="display:flex;align-items:center;gap:6px"><input class="edit-cell" value="'+val+'" style="flex:1"><button class="btn btn-primary btn-xs ok"><i class="fas fa-check"></i></button><button class="btn btn-outline btn-xs cancel"><i class="fas fa-times"></i></button></div>';td.setAttribute('data-prev',val);td.innerHTML=controls;});tbody?.addEventListener('click',function(e){var ok=e.target.closest('.ok');var cancel=e.target.closest('.cancel');if(!ok&&!cancel)return;var td=e.target.closest('td');var tr=td.closest('tr');var idx=parseInt(tr.dataset.row,10)-1;var r=data[idx];if(ok){var val=td.querySelector('input').value;var cls=td.className;switch(true){case /c-uraian/.test(cls): r.uraian=val; break;case /c-y2025/.test(cls): r.y2025=val; break;case /c-y2026/.test(cls): r.y2026=val; break;case /c-y2027/.test(cls): r.y2027=val; break;case /c-y2028/.test(cls): r.y2028=val; break;case /c-y2029/.test(cls): r.y2029=val; break;case /c-trend/.test(cls): r.trend=val; break;}td.textContent=val;var isUser=(window.USER_ROLE==='user');if(isUser){Utils.showToast('Hanya admin yang bisa mengubah','error');td.textContent=td.getAttribute('data-prev');return;}var id=r.id;var payload={uraian:r.uraian,values:{y2025:r.y2025,y2026:r.y2026,y2027:r.y2027,y2028:r.y2028,y2029:r.y2029}};if(key==='pop'){payload.values.trend=r.trend||''}var url = key==='pop'?('/perkebunan/pop/'+id):(key==='prod'?('/perkebunan/prod/'+id):('/perkebunan/luas/'+id));fetch(url,{method:'PUT',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify(payload)}).then(async function(res){if(res.ok){await fetchRows(key);Utils.showToast('Data diperbarui','success');}else{Utils.showToast('Gagal menyimpan','error');}}).catch(function(){Utils.showToast('Gagal menyimpan','error');});}else{td.textContent=td.getAttribute('data-prev');}})}
enableInlineEdit(document.getElementById('pk-pop-tbody'),popRows,'pop');
enableInlineEdit(document.getElementById('pk-prod-tbody'),prodRows,'prod');
enableInlineEdit(document.getElementById('pk-luas-tbody'),luasRows,'luas');
document.addEventListener('click',function(e){
  var ed=e.target.closest('[data-pk-ed]');
  var del=e.target.closest('[data-pk-del]');
  if(!ed&&!del)return;
  var p=(ed||del).getAttribute(ed?'data-pk-ed':'data-pk-del').split(':');
  var key=p[0],i=parseInt(p[1],10);
  var set=key==='pop'?popRows:key==='prod'?prodRows:luasRows;
  var panelId=key==='pop'?'pk-pop-panel':key==='prod'?'pk-prod-panel':'pk-luas-panel';
  if(ed){
    var r=set[i];
    if(key==='pop'){
      popEditIndex=i;popEditId=r.id;
      document.getElementById('pop-uraian').value=r.uraian;
      document.getElementById('pop-2025').value=r.y2025;
      document.getElementById('pop-2026').value=r.y2026;
      document.getElementById('pop-2027').value=r.y2027;
      document.getElementById('pop-2028').value=r.y2028;
      document.getElementById('pop-2029').value=r.y2029;
      document.getElementById('pop-trend').value=r.trend||'';
    }else if(key==='prod'){
      prodEditIndex=i;prodEditId=r.id;
      document.getElementById('prod-uraian').value=r.uraian;
      document.getElementById('prod-2025').value=r.y2025;
      document.getElementById('prod-2026').value=r.y2026;
      document.getElementById('prod-2027').value=r.y2027;
      document.getElementById('prod-2028').value=r.y2028;
      document.getElementById('prod-2029').value=r.y2029;
    }else{
      luasEditIndex=i;luasEditId=r.id;
      document.getElementById('luas-uraian').value=r.uraian;
      document.getElementById('luas-2025').value=r.y2025;
      document.getElementById('luas-2026').value=r.y2026;
      document.getElementById('luas-2027').value=r.y2027;
      document.getElementById('luas-2028').value=r.y2028;
      document.getElementById('luas-2029').value=r.y2029;
    }
    document.getElementById(panelId).style.display='block';
  }else{
    var id=set[i]?.id;
    Utils.confirm('Hapus baris ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(async function(yes){
      if(!yes)return;
      try{
        var url = key==='pop'?('/perkebunan/pop/'+id):(key==='prod'?('/perkebunan/prod/'+id):('/perkebunan/luas/'+id));
        var res=await fetch(url,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrfToken}});
        if(res.ok){
          await fetchRows(key);
          Utils.showToast('Baris dihapus','success');
        }else{
          Utils.showToast('Gagal menghapus','error');
        }
      }catch(e){
        Utils.showToast('Gagal menghapus','error');
      }
    });
  }
});
function exportCsv(filename, headers, rows){var csv=[headers].concat(rows).map(function(row){return row.map(function(v){var s=(''+(v==null?'':v)).replace(/"/g,'""');return '"'+s+'"';}).join(',');}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download=filename;document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url);} 
document.getElementById('pk-pop-export')?.addEventListener('click',function(){var headers=["No","Uraian","2025","2026","2027","2028","2029","Trend (%)"];var rows=popRows.map(function(r){return [r.no,r.uraian,r.y2025,r.y2026,r.y2027,r.y2028,r.y2029,r.trend];});exportCsv('perkebunan-populasi.csv',headers,rows);});
document.getElementById('pk-prod-export')?.addEventListener('click',function(){var headers=["No","Komoditas","2025","2026","2027","2028","2029"];var rows=prodRows.map(function(r){return [r.no,r.uraian,r.y2025,r.y2026,r.y2027,r.y2028,r.y2029];});exportCsv('perkebunan-produksi.csv',headers,rows);});
document.getElementById('pk-luas-export')?.addEventListener('click',function(){var headers=["No","Komoditas","2025","2026","2027","2028","2029"];var rows=luasRows.map(function(r){return [r.no,r.uraian,r.y2025,r.y2026,r.y2027,r.y2028,r.y2029];});exportCsv('perkebunan-luas.csv',headers,rows);});
</script>
@endpush

