@extends('layouts.app')
@section('title', 'Ketapang')
@section('body-class', 'dashboard-page force-light')

@push('styles')
<style>
.kt-card{border-radius:16px;overflow:hidden;box-shadow:0 8px 24px rgba(239,68,68,0.08);background:#fff}
.kt-card .card-header{padding:18px 22px;background:linear-gradient(180deg,#fff7ed,#fde68a);color:#b45309;display:flex;align-items:center;justify-content:space-between}
.kt-card .card-sub{display:block;font-size:14px;opacity:.9;margin-top:6px}
.kt-actions .btn{border-radius:12px}
.kt-badge{display:inline-flex;align-items:center;gap:8px;border:1px solid #fdba74;color:#a16207;background:#fff7ed;border-radius:12px;padding:8px 12px;font-weight:600}
.kt-badge i{color:#f97316}
.kt-panel{margin:16px;border-radius:16px;padding:16px;border:1px solid #fed7aa;background:#fff7ed}
.kt-panel .form-title{font-weight:700;color:#b45309;margin-bottom:8px}
.kt-panel .row{display:grid;grid-template-columns:repeat(5,1fr);gap:12px}
.kt-panel .year-group{display:flex;flex-direction:column;gap:6px}
.kt-panel .year-label{font-size:12px;color:#b45309;font-weight:600}
.kt-panel .form-control{border:1px solid #f59e0b;background:#ffffff;border-radius:12px;padding:10px 12px}
.kt-table-wrap{border:1px solid #fdba74;border-radius:16px;padding:10px;background:#fff7ed}
.kt-table{width:100%;border-collapse:separate;border-spacing:0;overflow:hidden;border-radius:12px;table-layout:fixed}
.kt-table thead th{background:#fef3c7;color:#111827;font-weight:600;padding:12px;border-bottom:1px solid #fdba74;border-right:1px solid #fdba74}
.kt-table thead tr th:first-child{border-left:1px solid #fdba74}
.kt-table td{padding:12px;border-bottom:1px solid #fdba74;border-right:1px solid #fdba74}
.kt-table tbody tr td:first-child{border-left:1px solid #fdba74}
.kt-table tbody tr:nth-child(even){background:#fff}
.kt-table tbody tr:nth-child(odd){background:#fffbeb}
.kt-table td:first-child,.kt-table th:first-child{text-align:left}
.kt-table td:not(:first-child),.kt-table th:not(:first-child){text-align:center}
.kt-info{margin-top:12px;background:#fde68a;border:1px solid #fdba74;color:#92400e;border-radius:12px;padding:12px}
.edit-cell{border:1px solid #fdba74;background:#ffffff;border-radius:10px;padding:8px 10px}
.btn-red{background:#ef4444;border-color:#ef4444;color:#fff}
.btn-orange{background:#f97316;border-color:#f97316;color:#fff}
.btn-secondary{background:#e5e7eb;color:#111827}
</style>
@endpush

@section('content')
    <div class="page active" id="ketapang-page">
      <div class="card kt-card">
        <div class="card-header"><div><h3>Capaian Kinerja Sasaran Daerah yang Terkait Urusan Pangan</h3><span class="card-sub">Data Tahun 2019 - 2023</span></div><div class="kt-actions"><button class="btn btn-red btn-sm" id="kt-add">+ Tambah Indikator</button></div></div>
        <div class="card-body">
          <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 12px"><div class="kt-badge"><i class="fas fa-chart-line"></i><span id="kt-total">Total: 0 Indikator</span></div></div>

          <div class="kt-panel" id="kt-panel" style="display:none;">
            <div class="form-title">Form Tambah Indikator Baru</div>
            <div class="form-group"><label>Uraian Indikator</label><input type="text" id="kt-uraian" class="form-control" placeholder="Masukkan uraian indikator..."></div>
            <div class="row">
              <div class="year-group"><div class="year-label">Tahun 2019</div><input type="text" id="kt-2019" class="form-control" placeholder="0"></div>
              <div class="year-group"><div class="year-label">Tahun 2020</div><input type="text" id="kt-2020" class="form-control" placeholder="0"></div>
              <div class="year-group"><div class="year-label">Tahun 2021</div><input type="text" id="kt-2021" class="form-control" placeholder="0"></div>
              <div class="year-group"><div class="year-label">Tahun 2022</div><input type="text" id="kt-2022" class="form-control" placeholder="0"></div>
              <div class="year-group"><div class="year-label">Tahun 2023</div><input type="text" id="kt-2023" class="form-control" placeholder="0"></div>
            </div>
            <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:10px"><button class="btn btn-secondary" id="kt-cancel">Batal</button><button class="btn btn-orange" id="kt-save">Simpan Data</button></div>
          </div>

          <div class="kt-table-wrap">
            <table class="kt-table">
              <thead>
                <tr>
                  <th rowspan="2">Uraian</th>
                  <th colspan="5">Tahun</th>
                </tr>
                <tr>
                  <th>2019</th>
                  <th>2020</th>
                  <th>2021</th>
                  <th>2022</th>
                  <th>2023</th>
                </tr>
              </thead>
              <tbody id="kt-tbody"></tbody>
            </table>
          </div>

          <div class="kt-info"><i class="fas fa-square-poll-horizontal"></i> Indikator capaian kinerja sasaran daerah yang terkait dengan urusan pangan di wilayah Kabupaten Kolaka Utara</div>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
var ktRows=[
 {uraian:"Prosentase Ketersediaan pangan",y2019:"66",y2020:"66",y2021:"67",y2022:"-",y2023:"-"},
 {uraian:"Tersedianya lumbung pangan",y2019:"0",y2020:"0",y2021:"0",y2022:"-",y2023:"-"},
 {uraian:"Jumlah Rumah Tangga (RT) yang",y2019:"60",y2020:"120",y2021:"240",y2022:"-",y2023:"-"},
 {uraian:"Jumlah Kelompok yang terbina",y2019:"2",y2020:"2",y2021:"10",y2022:"-",y2023:"-"},
 {uraian:"Skor PPH (Pola Pangan Harapan)",y2019:"78.7",y2020:"82.4",y2021:"83.5",y2022:"-",y2023:"-"}
];
function render(){document.getElementById('kt-tbody').innerHTML=ktRows.map(function(r){return '<tr><td class="c-uraian">'+r.uraian+'</td><td class="c-y2019">'+r.y2019+'</td><td class="c-y2020">'+r.y2020+'</td><td class="c-y2021">'+r.y2021+'</td><td class="c-y2022">'+r.y2022+'</td><td class="c-y2023">'+r.y2023+'</td></tr>';}).join('');document.getElementById('kt-total').textContent='Total: '+ktRows.length+' Indikator';}
render();
function toggle(){var p=document.getElementById('kt-panel');var show=p.style.display==='none';p.style.display=show?'block':'none';document.getElementById('kt-add').textContent=show?'− Tutup Form':'+ Tambah Indikator';}
document.getElementById('kt-add')?.addEventListener('click',toggle);
document.getElementById('kt-cancel')?.addEventListener('click',function(){document.getElementById('kt-panel').style.display='none';document.getElementById('kt-add').textContent='+ Tambah Indikator';});
document.getElementById('kt-save')?.addEventListener('click',function(){ktRows.push({uraian:document.getElementById('kt-uraian').value,y2019:document.getElementById('kt-2019').value,y2020:document.getElementById('kt-2020').value,y2021:document.getElementById('kt-2021').value,y2022:document.getElementById('kt-2022').value,y2023:document.getElementById('kt-2023').value});render();document.getElementById('kt-panel').style.display='none';document.getElementById('kt-add').textContent='+ Tambah Indikator';});
document.getElementById('kt-tbody')?.addEventListener('dblclick',function(e){var td=e.target.closest('td');if(!td)return;if(!td.className.match(/^c\-/))return;var val=td.textContent;var controls='<div style="display:flex;align-items:center;gap:6px"><input class="edit-cell" value="'+val+'" style="flex:1"><button class="btn btn-orange btn-xs ok"><i class="fas fa-check"></i></button><button class="btn btn-outline btn-xs cancel"><i class="fas fa-times"></i></button></div>';td.setAttribute('data-prev',val);td.innerHTML=controls;});
document.getElementById('kt-tbody')?.addEventListener('click',function(e){var ok=e.target.closest('.ok');var cancel=e.target.closest('.cancel');if(!ok&&!cancel)return;var td=e.target.closest('td');var tr=td.closest('tr');var idx=Array.prototype.indexOf.call(tr.parentNode.children,tr);var r=ktRows[idx];if(ok){var val=td.querySelector('input').value;var cls=td.className;switch(true){case /c-uraian/.test(cls): r.uraian=val; break;case /c-y2019/.test(cls): r.y2019=val; break;case /c-y2020/.test(cls): r.y2020=val; break;case /c-y2021/.test(cls): r.y2021=val; break;case /c-y2022/.test(cls): r.y2022=val; break;case /c-y2023/.test(cls): r.y2023=val; break;}td.textContent=val;}else{td.textContent=td.getAttribute('data-prev');}});
</script>
@endpush
