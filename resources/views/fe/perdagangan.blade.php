@extends('layouts.app')
@section('title', 'Perdagangan')
@section('body-class', 'dashboard-page force-light')

@section('content')
    <div class="page active" id="perdagangan-page">
      <div class="page-header"><h1>Data Perdagangan</h1><p>Kontribusi Sektor Perdagangan dan Perkembangan Ekspor/Impor</p></div>
      <div style="display:flex;gap:8px;margin-bottom:10px"><button class="btn btn-primary btn-sm" id="trade-tab-pdrb">Kontribusi Sektor Perdagangan</button><button class="btn btn-outline btn-sm" id="trade-tab-eks">Ekspor & Impor ADHB/ADHK</button></div>

      <div class="card section-card" id="pdrb-section">
        <div class="card-header header-green">
          <div class="title">Kontribusi Sektor Perdagangan terhadap PDRB (HB) 2025–2029</div>
          <div class="head-actions"><button class="btn btn-outline btn-sm" id="pdrb-add"><i class="fas fa-plus"></i></button><button class="btn btn-outline btn-sm" id="pdrb-close" style="display:none"><i class="fas fa-times"></i></button></div>
        </div>
        <div class="card-body">
          <div class="inline-panel panel-green" id="pdrb-panel" style="display:none">
            <div class="panel-inner">
              <div class="form-row"><label class="form-label">Uraian</label><input type="text" id="pdrb-uraian" class="form-control" placeholder="Contoh: PDRB Sektor Perdagangan HB"></div>
              <div class="year-grid">
                <div class="form-row"><label class="form-label">2025</label><input type="text" id="pdrb-2025" class="form-control" placeholder="0.00"></div>
                <div class="form-row"><label class="form-label">2026</label><input type="text" id="pdrb-2026" class="form-control" placeholder="0.00"></div>
                <div class="form-row"><label class="form-label">2027</label><input type="text" id="pdrb-2027" class="form-control" placeholder="0.00"></div>
                <div class="form-row"><label class="form-label">2028</label><input type="text" id="pdrb-2028" class="form-control" placeholder="0.00"></div>
                <div class="form-row"><label class="form-label">2029</label><input type="text" id="pdrb-2029" class="form-control" placeholder="0.00"></div>
              </div>
              <div class="panel-actions"><button class="btn btn-secondary" id="pdrb-cancel">Batal</button><button class="btn btn-green" id="pdrb-save"><i class="fas fa-floppy-disk"></i> Simpan Data</button></div>
            </div>
          </div>
          <div class="table-wrap">
            <table class="table table-compact trade-table trade-green">
              <thead>
                <tr><th>No.</th><th>Uraian</th><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th></tr>
              </thead>
              <tbody id="pdrb-tbody"></tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="card section-card" id="eks-section" style="display:none">
        <div class="card-header header-blue">
          <div class="title">Perkembangan Ekspor ADHB Tahun 2019–2023</div>
          <div class="head-actions"><button class="btn btn-outline btn-sm" id="eks-add"><i class="fas fa-plus"></i></button><button class="btn btn-outline btn-sm" id="eks-close" style="display:none"><i class="fas fa-times"></i></button></div>
        </div>
        <div class="card-body">
          <div class="inline-panel panel-blue" id="eks-panel" style="display:none">
            <div class="panel-inner">
              <div class="form-row"><label class="form-label">Uraian</label><input type="text" id="eks-uraian" class="form-control" placeholder="Contoh: Nilai Ekspor ADHB"></div>
              <div class="year-grid">
                <div class="form-row"><label class="form-label">2019</label><input type="text" id="eks-2019" class="form-control" placeholder="0.00"></div>
                <div class="form-row"><label class="form-label">2020</label><input type="text" id="eks-2020" class="form-control" placeholder="0.00"></div>
                <div class="form-row"><label class="form-label">2021</label><input type="text" id="eks-2021" class="form-control" placeholder="0.00"></div>
                <div class="form-row"><label class="form-label">2022</label><input type="text" id="eks-2022" class="form-control" placeholder="0.00"></div>
                <div class="form-row"><label class="form-label">2023</label><input type="text" id="eks-2023" class="form-control" placeholder="0.00"></div>
              </div>
              <div class="panel-actions"><button class="btn btn-secondary" id="eks-cancel">Batal</button><button class="btn btn-blue" id="eks-save"><i class="fas fa-floppy-disk"></i> Simpan Data</button></div>
            </div>
          </div>
          <div class="table-wrap">
            <table class="table table-compact trade-table trade-blue">
              <thead>
                <tr><th>No.</th><th>Uraian</th><th>2019</th><th>2020</th><th>2021</th><th>2022</th><th>2023</th></tr>
              </thead>
              <tbody id="eks-tbody"></tbody>
            </table>
            <div class="hint">* Angka sangat sementara/proyeksi</div>
          </div>
        </div>
      </div>
    </div>
@endsection

@push('styles')
<style>
  .section-card{border-radius:12px;overflow:hidden}
  .card-header{display:flex;align-items:center;justify-content:space-between}
  .card-header .title{font-weight:600;color:#fff}
  .header-green{background:#10b981;color:#fff}
  .header-blue{background:#2563eb;color:#fff}
  .head-actions{display:flex;gap:8px}
  .inline-panel{margin:12px 0;border-radius:12px;border:1px solid}
  .panel-green{background:#ecfdf5;border-color:#a7f3d0}
  .panel-blue{background:#eff6ff;border-color:#bfdbfe}
  .panel-inner{border:1px solid currentColor;border-color:inherit;border-radius:12px;padding:12px}
  .year-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:12px}
  .form-row{display:flex;flex-direction:column;gap:6px}
  .trade-table{width:100%;border:1px solid var(--gray-200);border-radius:12px;overflow:hidden;border-collapse:separate;border-spacing:0}
  .trade-table thead th{font-weight:600;text-align:center}
  .trade-table th,.trade-table td{border-bottom:1px solid var(--gray-200);border-right:1px solid var(--gray-200);padding:10px 12px}
  .trade-table td:nth-child(1),.trade-table td:nth-child(2),.trade-table th:nth-child(1),.trade-table th:nth-child(2){text-align:left}
  .trade-green thead th{background:#d1fae5;color:#065f46}
  .trade-blue thead th{background:#dbeafe;color:#1e3a8a}
  .btn-green{background:#10b981;color:#fff;border-color:#10b981}
  .btn-blue{background:#2563eb;color:#fff;border-color:#2563eb}
  .form-label{color:#065f46}
  .panel-blue .form-label{color:#1e3a8a}
  .panel-green .form-control{border:1px solid #86efac;background:#ffffff;border-radius:12px;padding:10px 12px}
  .panel-green .form-control:focus{outline:none;border-color:#10b981;box-shadow:0 0 0 3px rgba(16,185,129,0.25)}
  .panel-blue .form-control{border:1px solid #93c5fd;background:#ffffff;border-radius:12px;padding:10px 12px}
  .panel-blue .form-control:focus{outline:none;border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,0.25)}
  .panel-actions{display:flex;gap:8px;justify-content:flex-end;margin-top:8px}
  .input-year{height:auto;padding:10px 12px;border-width:1px;text-align:left}
</style>
@endpush

@push('scripts')
<script>
  var k1='sipandu_trade_pdrb',k2='sipandu_trade_eks';
  function load(k){try{return JSON.parse(localStorage.getItem(k))||[]}catch(e){return []}}
  function save(k,d){localStorage.setItem(k,JSON.stringify(d))}
  var pdrbRows=load(k1);if(!pdrbRows.length){pdrbRows=[{uraian:'PDRB Sektor Perdagangan HB',y2025:'1,126.22',y2026:'1,128.71',y2027:'1,198.31',y2028:'1,364.10',y2029:'1,400.15'}];save(k1,pdrbRows)}
  var eksRows=load(k2);if(!eksRows.length){eksRows=[{uraian:'Nilai Ekspor ADHB',y2019:'2,498.40',y2020:'2,850.98',y2021:'3,118.61',y2022:'3,842.44',y2023:'4,152.55'}];save(k2,eksRows)}
  function renderPdrb(){var tb=document.getElementById('pdrb-tbody');if(!tb)return;tb.innerHTML=pdrbRows.map(function(r,i){return '<tr><td>'+ (i+1) +'</td><td>'+ r.uraian +'</td><td>'+ r.y2025 +'</td><td>'+ r.y2026 +'</td><td>'+ r.y2027 +'</td><td>'+ r.y2028 +'</td><td>'+ r.y2029 +'</td></tr>'}).join('')}
  function renderEks(){var tb=document.getElementById('eks-tbody');if(!tb)return;tb.innerHTML=eksRows.map(function(r,i){return '<tr><td>'+ (i+1) +'</td><td>'+ r.uraian +'</td><td>'+ r.y2019 +'</td><td>'+ r.y2020 +'</td><td>'+ r.y2021 +'</td><td>'+ r.y2022 +'</td><td>'+ r.y2023 +'</td></tr>'}).join('')}
  renderPdrb();renderEks();
  function toggle(btn,panel,closeBtn){var show=panel.style.display==='none';panel.style.display=show?'block':'none';btn.style.display=show?'none':'inline-flex';closeBtn.style.display=show?'inline-flex':'none'}
  document.getElementById('pdrb-add')?.addEventListener('click',function(){toggle(this,document.getElementById('pdrb-panel'),document.getElementById('pdrb-close'))});
  document.getElementById('pdrb-close')?.addEventListener('click',function(){toggle(document.getElementById('pdrb-add'),document.getElementById('pdrb-panel'),this)});
  document.getElementById('eks-add')?.addEventListener('click',function(){toggle(this,document.getElementById('eks-panel'),document.getElementById('eks-close'))});
  document.getElementById('eks-close')?.addEventListener('click',function(){toggle(document.getElementById('eks-add'),document.getElementById('eks-panel'),this)});
  document.getElementById('pdrb-save')?.addEventListener('click',function(){pdrbRows.push({uraian:document.getElementById('pdrb-uraian').value,y2025:document.getElementById('pdrb-2025').value,y2026:document.getElementById('pdrb-2026').value,y2027:document.getElementById('pdrb-2027').value,y2028:document.getElementById('pdrb-2028').value,y2029:document.getElementById('pdrb-2029').value});save(k1,pdrbRows);renderPdrb();document.getElementById('pdrb-panel').style.display='none';document.getElementById('pdrb-add').style.display='inline-flex';document.getElementById('pdrb-close').style.display='none'});
  document.getElementById('eks-save')?.addEventListener('click',function(){eksRows.push({uraian:document.getElementById('eks-uraian').value,y2019:document.getElementById('eks-2019').value,y2020:document.getElementById('eks-2020').value,y2021:document.getElementById('eks-2021').value,y2022:document.getElementById('eks-2022').value,y2023:document.getElementById('eks-2023').value});save(k2,eksRows);renderEks();document.getElementById('eks-panel').style.display='none';document.getElementById('eks-add').style.display='inline-flex';document.getElementById('eks-close').style.display='none'});
  document.getElementById('pdrb-cancel')?.addEventListener('click',function(){document.getElementById('pdrb-panel').style.display='none';document.getElementById('pdrb-add').style.display='inline-flex';document.getElementById('pdrb-close').style.display='none'});
  document.getElementById('eks-cancel')?.addEventListener('click',function(){document.getElementById('eks-panel').style.display='none';document.getElementById('eks-add').style.display='inline-flex';document.getElementById('eks-close').style.display='none'});
  document.getElementById('trade-tab-pdrb')?.addEventListener('click',function(){document.getElementById('trade-tab-pdrb').classList.add('btn-primary');document.getElementById('trade-tab-pdrb').classList.remove('btn-outline');document.getElementById('trade-tab-eks').classList.add('btn-outline');document.getElementById('trade-tab-eks').classList.remove('btn-primary');document.getElementById('pdrb-section').style.display='block';document.getElementById('eks-section').style.display='none'});
  document.getElementById('trade-tab-eks')?.addEventListener('click',function(){document.getElementById('trade-tab-eks').classList.add('btn-primary');document.getElementById('trade-tab-eks').classList.remove('btn-outline');document.getElementById('trade-tab-pdrb').classList.add('btn-outline');document.getElementById('trade-tab-pdrb').classList.remove('btn-primary');document.getElementById('pdrb-section').style.display='none';document.getElementById('eks-section').style.display='block'});
</script>
@endpush
