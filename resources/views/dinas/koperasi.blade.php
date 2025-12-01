@extends('layouts.app')
@section('title', 'Koperasi')
@section('body-class', 'dashboard-page force-light')
@push('styles')
<style>
.kop-card{border-radius:16px;overflow:hidden}
.kop-card .card-header{background:linear-gradient(90deg,#60a5fa,#3b82f6);color:#ffffff;border-bottom:1px solid transparent;padding:16px 20px;display:flex;align-items:center;justify-content:space-between}
.kop-card .card-header .sub{font-size:13px;opacity:.9;color:#fff}
.kop-card .card-actions .btn{background:#fff;border-color:#fff;color:#111827;border-radius:8px;height:34px;padding:0 12px}
.kop-table{width:100%;border:1px solid #93c5fd;border-radius:12px;overflow:hidden;border-collapse:separate;border-spacing:0;table-layout:fixed}
.kop-table thead th{background:#dbeafe;color:#1e3a8a;font-weight:600}
.kop-table th,.kop-table td{border-bottom:1px solid #93c5fd;border-right:1px solid #93c5fd;padding:10px 12px}
.kop-table tr#kop-addrow td{background:#fff7ed}
.kop-table tr#kop-addrow input{border:1px solid #93c5fd;width:100%;box-sizing:border-box;padding:8px 10px;border-radius:10px}
.kop-table thead tr:first-child th{border-top:1px solid #93c5fd}
.kop-table thead tr th:first-child{border-left:1px solid #93c5fd}
.kop-table tbody tr td:first-child{border-left:1px solid #93c5fd}
.kop-table thead tr:nth-child(2) th{border-top:1px solid #93c5fd}
.kop-table th,.kop-table td{vertical-align:middle}
.kop-table th:last-child,.kop-table td:last-child{text-align:center}
.kop-table td:last-child{display:table-cell !important;text-align:center !important;white-space:nowrap}
.kop-table tbody tr:last-child td{border-bottom:1px solid #93c5fd}
.kop-table th:nth-child(1),.kop-table th:nth-child(2),.kop-table td:nth-child(1),.kop-table td:nth-child(2){text-align:left}
.kop-table th:not(:nth-child(1)):not(:nth-child(2)),.kop-table td:not(:nth-child(1)):not(:nth-child(2)){text-align:center}
.kop-inline-tip{margin-top:12px;background:#eff6ff;border:1px solid #bfdbfe;color:#1e3a8a;border-radius:12px;padding:12px}
.kop-inline-actions{display:flex;gap:8px}
.btn-kop-save{background:#16a34a;border-color:#16a34a;color:#fff}
.btn-kop-cancel{background:#e5e7eb;color:#111827}
.edit-input{border:1px solid #93c5fd;background:#ffffff;border-radius:10px;padding:6px 10px}
</style>
@endpush

@section('content')
    <div class="page active" id="koperasi-page">
      <div class="page-header"><h1>Perkembangan Perkoperasian</h1><p>Tahun 2019-2023 di Kabupaten Kolaka Utara</p></div>
      <div class="card kop-card">
        <div class="card-header"><div><h3>Data Koperasi</h3><div class="sub">Perkembangan koperasi tahun 2019–2023</div></div><div class="card-actions"><button class="btn btn-outline btn-sm" id="kop-export"><i class="fas fa-download"></i> Export Data</button> <button class="btn btn-outline btn-sm" id="kop-toggle"><i class="fas fa-plus"></i> Tambah Baris</button></div></div>
        <div class="card-body">
          <div class="table-wrap">
            <table class="table table-compact kop-table">
              <thead>
                <tr>
                  <th rowspan="2">No</th>
                  <th rowspan="2">Uraian</th>
                  <th colspan="5">Tahun</th>
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
              <tbody id="kop-tbody"></tbody>
            </table>
          </div>
          <div class="kop-inline-tip">Tips: Klik tombol "Edit" untuk mengubah data, atau "Tambah Baris" untuk menambah data baru secara inline.</div>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
var kopRows=[
  {no:1,uraian:'Jumlah Koperasi Sehat',y2019:'46',y2020:'46',y2021:'8',y2022:'15',y2023:'14'},
  {no:2,uraian:'Jumlah Layanan Izin Usaha Simpan Pinjam',y2019:'7',y2020:'10',y2021:'15',y2022:'17',y2023:'20'},
  {no:3,uraian:'Jumlah Kesehatan KSP/USP Koperasi yang dinilai',y2019:'15',y2020:'15',y2021:'15',y2022:'15',y2023:'28'}
];
function renderKop(){var tb=document.getElementById('kop-tbody');if(!tb)return;var addRow='<tr id="kop-addrow" class="add-row" style="display:none;"><td>+</td><td><input class="edit-input" id="ka-uraian" placeholder="Uraian baru..."></td><td><input class="edit-input" id="ka-2019" placeholder="0"></td><td><input class="edit-input" id="ka-2020" placeholder="0"></td><td><input class="edit-input" id="ka-2021" placeholder="0"></td><td><input class="edit-input" id="ka-2022" placeholder="0"></td><td><input class="edit-input" id="ka-2023" placeholder="0"></td><td class="kop-inline-actions"><button class="btn btn-kop-save" id="ka-save"><i class="fas fa-floppy-disk"></i></button><button class="btn btn-kop-cancel" id="ka-cancel">Batal</button></td></tr>';var rows=kopRows.map(function(r,idx){return '<tr data-row="'+r.no+'"><td>'+r.no+'</td><td class="c-uraian">'+r.uraian+'</td><td class="c-2019">'+r.y2019+'</td><td class="c-2020">'+r.y2020+'</td><td class="c-2021">'+r.y2021+'</td><td class="c-2022">'+r.y2022+'</td><td class="c-2023">'+r.y2023+'</td><td><button class="btn btn-outline btn-sm edit-btn action-btn"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-del="'+idx+'"><i class="fas fa-trash"></i></button></td></tr>';}).join('');tb.innerHTML=addRow+rows;}
renderKop();
var toggleBtn=document.getElementById('kop-toggle');
toggleBtn?.addEventListener('click',function(){var addRowEl=document.getElementById('kop-addrow');if(!addRowEl){renderKop();addRowEl=document.getElementById('kop-addrow');}
  var isHidden=(addRowEl.style.display==='none' || getComputedStyle(addRowEl).display==='none');
  addRowEl.style.display=isHidden?'table-row':'none';
  toggleBtn.innerHTML=isHidden?'Tutup Form':'<i class="fas fa-plus"></i> Tambah Baris';});
function exportCsv(filename, headers, rows){var csv=[headers].concat(rows).map(function(row){return row.map(function(v){var s=(''+(v==null?'':v)).replace(/"/g,'""');return '"'+s+'"';}).join(',');}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download=filename;document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url);} 
document.getElementById('kop-export')?.addEventListener('click',function(){var h=['No','Uraian','2019','2020','2021','2022','2023'];var rows=kopRows.map(function(r){return [r.no,r.uraian,r.y2019,r.y2020,r.y2021,r.y2022,r.y2023]});exportCsv('koperasi.csv',h,rows)});
document.getElementById('kop-tbody')?.addEventListener('click',function(e){var btn=e.target.closest('button');if(!btn)return;if(btn.id==='ka-save'){kopRows.unshift({no:1,uraian:document.getElementById('ka-uraian').value,y2019:document.getElementById('ka-2019').value,y2020:document.getElementById('ka-2020').value,y2021:document.getElementById('ka-2021').value,y2022:document.getElementById('ka-2022').value,y2023:document.getElementById('ka-2023').value});kopRows.forEach(function(r,i){r.no=i+1});renderKop();var addRowEl=document.getElementById('kop-addrow');if(addRowEl){addRowEl.style.display='none';}var t=document.getElementById('kop-toggle');if(t){t.innerHTML='<i class="fas fa-plus"></i> Tambah Baris';}Utils.showToast('Baris ditambahkan','success');return;}if(btn.id==='ka-cancel'){var addRowEl=document.getElementById('kop-addrow');if(addRowEl){addRowEl.style.display='none';}var t=document.getElementById('kop-toggle');if(t){t.innerHTML='<i class="fas fa-plus"></i> Tambah Baris';}return;}if(btn.classList.contains('edit-btn')){var tr=btn.closest('tr');['c-uraian','c-2019','c-2020','c-2021','c-2022','c-2023'].forEach(function(cls){var td=tr.querySelector('.'+cls);var val=td.textContent;td.innerHTML='<input class="edit-input" value="'+val+'">';});btn.textContent='Simpan';btn.classList.add('saving');return;}if(btn.dataset.del!=null){var i=parseInt(btn.dataset.del,10);Utils.confirm('Hapus baris ini?',{okText:'Hapus',cancelText:'Batal',variant:'danger'}).then(function(yes){if(!yes)return;kopRows.splice(i,1);kopRows.forEach(function(r,idx){r.no=idx+1});renderKop();Utils.showToast('Baris dihapus','success');});}});
document.getElementById('kop-tbody')?.addEventListener('click',function(e){var btn=e.target.closest('.saving');if(!btn)return;var tr=btn.closest('tr');var idx=parseInt(tr.dataset.row,10)-1;var inputs=tr.querySelectorAll('input');kopRows[idx]={no:idx+1,uraian:inputs[0].value,y2019:inputs[1].value,y2020:inputs[2].value,y2021:inputs[3].value,y2022:inputs[4].value,y2023:inputs[5].value};renderKop();});
</script>
@endpush
