@extends('layouts.app')
@section('title', 'Data Management')
@section('body-class', 'dashboard-page force-light')

@push('styles')
<style>
.dm-card{border-radius:16px;overflow:hidden}
.dm-card .card-header{padding:16px 20px;display:flex;align-items:center;justify-content:space-between;background:linear-gradient(90deg,#8b5cf6,#ec4899);color:#ffffff;border-radius:16px 16px 0 0}
.dm-card .card-header h3{display:flex;flex-direction:column;line-height:1.25}
.dm-card .card-header h3 span{font-weight:500;font-size:13px;opacity:.9}
.dm-card .card-actions .btn{background:#fff;border-color:#fff;color:#111827;border-radius:12px;height:34px}
.dm-toolbar{display:flex;align-items:center;gap:12px;margin:12px 0}
.dm-toolbar .search-box{flex:1}
.dm-toolbar .select{height:34px;border:1px solid var(--gray-300);background:#fff;border-radius:9999px;padding:0 28px 0 12px;appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24'%3E%3Cpath fill='%236b7280' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 10px center;background-size:16px}
.dm-table{width:100%;border:1px solid var(--gray-200);border-radius:12px;overflow:hidden;border-collapse:separate;border-spacing:0}
.dm-table thead th{background:#f8fafc;color:#111827;font-weight:600}
.dm-table th,.dm-table td{border-bottom:1px solid var(--gray-200);border-right:1px solid var(--gray-200);padding:10px 12px}
.dm-table thead tr th:first-child{border-left:1px solid var(--gray-200)}
.dm-table tbody tr td:first-child{border-left:1px solid var(--gray-200)}
.dm-table .col-actions{width:120px}
.chip{display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:9999px;font-size:12px;border:1px solid var(--gray-200);background:#fff;color:var(--gray-700)}
.chip.gray{background:#f1f5f9;color:#374151}
.chip.purple{background:#ede9fe;color:#6d28d9;border-color:#ddd6fe}
.chip.blue{background:#e0f2fe;color:#0369a1;border-color:#bae6fd}
.chip.rose{background:#ffe4e6;color:#be123c;border-color:#fecdd3}
.status-badge{display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:9999px;font-size:12px;font-weight:600}
.status-complete{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0}
.status-progress{background:#eef2ff;color:#4338ca;border:1px solid #c7d2fe}
.status-review{background:#fff7ed;color:#9a3412;border:1px solid #fed7aa}
.progress-line{width:120px;height:8px;background:#e5e7eb;border-radius:9999px;overflow:hidden}
.progress-line .fill{height:8px;background:#8b5cf6}
.action-btn{background:#fff;border:1px solid var(--gray-300);border-radius:10px;width:28px;height:28px;display:inline-flex;align-items:center;justify-content:center;color:#374151}
.action-btn:hover{background:#f8fafc}
</style>
@endpush

@section('content')
    <div class="page active" id="dm-page">
      <div class="card dm-card">
        <div class="card-header">
          <h3>Daftar Data Terpadu<span>Manajemen & Monitoring Data Seluruh Dinas</span></h3>
          <div class="card-actions"><button class="btn btn-outline btn-sm" id="dm-export"><i class="fas fa-download"></i> Export</button><button class="btn btn-primary btn-sm" id="dm-add"><i class="fas fa-plus"></i> Tambah Data</button></div>
        </div>
        <div class="card-body">
          <div class="dm-toolbar">
            <div class="search-box compact" style="max-width:320px"><i class="fas fa-search"></i><input id="dm-search" type="text" placeholder="Cari data..."></div>
            <select id="dm-filter-status" class="select"><option value="">Semua Status</option><option value="Complete">Complete</option><option value="In Progress">In Progress</option><option value="Pending Review">Pending Review</option><option value="Draft">Draft</option></select>
            <select id="dm-filter-opd" class="select"><option value="">Semua Dinas</option><option>Perindustrian</option><option>Koperasi</option><option>Tanaman Pangan</option><option>Perkebunan</option><option>Pariwisata</option><option>DLH</option><option>Perikanan</option><option>Lainnya</option></select>
            <select id="dm-filter-priority" class="select"><option value="">Semua Priority</option><option>High</option><option>Medium</option><option>Low</option></select>
          </div>
          <div class="table-wrap">
            <table class="table table-compact dm-table">
              <thead>
                <tr>
                  <th>Nama Data</th>
                  <th>Kategori</th>
                  <th>Periode</th>
                  <th>Priority</th>
                  <th>Status</th>
                  <th>Progress</th>
                  <th>PIC</th>
                  <th>Update</th>
                  <th class="col-actions">Aksi</th>
                </tr>
              </thead>
              <tbody id="dm-tbody"></tbody>
            </table>
          </div>
          <div class="table-footer"><div id="dm-count" class="hint"></div></div>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
var dmRows=[
  {name:'Data Penerbitan Izin Usaha 2023',cat:'Perizinan',period:'2023',priority:'High',status:'Complete',progress:100,pic:'Ahmad Fauzi',update:'2024-01-15'},
  {name:'Kapasitas Produksi Industri Kecil',cat:'Produksi',period:'2023',priority:'Medium',status:'Pending Review',progress:90,pic:'Budi Santoso',update:'2024-01-18'},
  {name:'Data Koperasi Aktif Kabupaten',cat:'Keanggotaan',period:'2023',priority:'Medium',status:'Complete',progress:100,pic:'Dewi Lestari',update:'2024-01-12'},
  {name:'Produksi Padi dan Jagung 2023',cat:'Produksi',period:'2023',priority:'High',status:'In Progress',progress:45,pic:'Eko Prasetyo',update:'2024-01-22'},
  {name:'Data Produksi Perikanan Tangkap',cat:'Statistik',period:'2023',priority:'Low',status:'Draft',progress:25,pic:'Rina Kusuma',update:'2024-01-25'}
]; // Tidak ada entri DPMPTSP/Perdagangan sesuai permintaan

function badgeStatus(s){switch(s){case 'Complete':return '<span class="status-badge status-complete">Complete</span>';case 'In Progress':return '<span class="status-badge status-progress">In Progress</span>';case 'Pending Review':return '<span class="status-badge status-review">Pending Review</span>';default:return '<span class="chip gray">Draft</span>';}}
function chipCat(c){return '<span class="chip gray"><i class="fas fa-tag"></i> '+c+'</span>'}
function chipPeriod(p){return '<span class="chip blue"><i class="fas fa-calendar"></i> '+p+'</span>'}
function chipPriority(p){var m=p==='High'?'rose':p==='Medium'?'purple':'gray';return '<span class="chip '+m+'">'+p+'</span>'}

function render(){var q=document.getElementById('dm-search').value.toLowerCase();var fs=document.getElementById('dm-filter-status').value;var fo=document.getElementById('dm-filter-opd').value;var fp=document.getElementById('dm-filter-priority').value;var rows=dmRows.filter(function(r){var ok=r.name.toLowerCase().includes(q)||r.cat.toLowerCase().includes(q);if(fs&&r.status!==fs)return false;if(fp&&r.priority!==fp)return false;return true;});var tb=document.getElementById('dm-tbody');if(!tb)return;tb.innerHTML=rows.map(function(r,i){return '<tr data-idx="'+i+'"><td>'+r.name+'</td><td>'+chipCat(r.cat)+'</td><td>'+chipPeriod(r.period)+'</td><td>'+chipPriority(r.priority)+'</td><td>'+badgeStatus(r.status)+'</td><td><div class="progress-line"><div class="fill" style="width:'+r.progress+'%"></div></div></td><td>'+r.pic+'</td><td>'+r.update+'</td><td><button class="action-btn" title="Lihat" data-view="'+i+'"><i class="fas fa-eye"></i></button> <button class="action-btn" title="Edit" data-edit="'+i+'"><i class="fas fa-pen"></i></button> <button class="action-btn" title="Hapus" data-del="'+i+'"><i class="fas fa-trash"></i></button></td></tr>';}).join('');document.getElementById('dm-count').textContent='Menampilkan '+rows.length+' dari '+dmRows.length+' data';}
render();

['dm-search','dm-filter-status','dm-filter-opd','dm-filter-priority'].forEach(function(id){document.getElementById(id)?.addEventListener('input',render);document.getElementById(id)?.addEventListener('change',render);});

document.getElementById('dm-tbody')?.addEventListener('click',function(e){var b=e.target.closest('button');if(!b)return;if(b.dataset.view){var i=parseInt(b.dataset.view,10);var r=dmRows[i];alert('Detail: '+r.name+'\nStatus: '+r.status+'\nProgress: '+r.progress+'%');}else if(b.dataset.edit){var i=parseInt(b.dataset.edit,10);openModal(i);}else if(b.dataset.del){var i=parseInt(b.dataset.del,10);dmRows.splice(i,1);render();}});

function openModal(editIdx){document.getElementById('dm-modal').style.display='flex';var f=document.getElementById('dm-form');if(editIdx!=null){var r=dmRows[editIdx];f.nama.value=r.name;f.periode.value=r.period;f.pic.value=r.pic;f.progress.value=r.progress;f.status.value=r.status;f.priority.value=r.priority;f.kategori.value=r.cat;f.dataset.edit=editIdx;}else{f.reset();f.dataset.edit='';}}
function closeModal(){document.getElementById('dm-modal').style.display='none';}
document.getElementById('dm-add')?.addEventListener('click',function(){openModal(null);});
document.getElementById('dm-cancel')?.addEventListener('click',function(){closeModal();});
document.addEventListener('click',function(e){var m=document.getElementById('dm-modal');if(e.target===m)closeModal();});

document.getElementById('dm-save')?.addEventListener('click',function(){var f=document.getElementById('dm-form');var row={name:f.nama.value,cat:f.kategori.value,period:f.periode.value,priority:f.priority.value,status:f.status.value,progress:parseInt(f.progress.value||'0',10)||0,pic:f.pic.value,update:new Date().toISOString().slice(0,10)};if(f.dataset.edit!==''){var i=parseInt(f.dataset.edit,10);dmRows[i]=row;}else{dmRows.push(row);}render();closeModal();});

document.getElementById('dm-export')?.addEventListener('click',function(){var rows=[["Nama Data","Kategori","Periode","Priority","Status","Progress","PIC","Update"]];dmRows.forEach(function(r){rows.push([r.name,r.cat,r.period,r.priority,r.status,r.progress+'%',r.pic,r.update]);});var csv=rows.map(function(x){return x.map(function(v){var s=(''+v).replace(/"/g,'""');return '"'+s+'"';}).join(',');}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download='data-management.csv';document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url);});
</script>
<div class="modal-overlay" id="dm-modal" style="display:none;">
  <div class="modal">
    <div class="modal-header"><h3>Tambah Data Baru</h3><button class="btn btn-outline btn-sm" id="dm-close">✕</button></div>
    <div class="modal-body">
      <div class="tip-bar" style="display:flex;align-items:center;gap:8px;padding:10px 12px;background:#eef2ff;color:#1e3a8a;border:1px solid #c7d2fe;border-radius:12px;margin-bottom:12px"><span>💡</span><span>Pastikan semua field wajib (*) sudah terisi dengan benar.</span></div>
      <form id="dm-form">
        <div class="form-row"><label>Dinas *</label><select name="opd" class="form-control"><option value="">Pilih Dinas</option><option>Perindustrian</option><option>Koperasi</option><option>Tanaman Pangan</option><option>Perkebunan</option><option>Pariwisata</option><option>DLH</option><option>Perikanan</option><option>Lainnya</option></select></div>
        <div class="form-row"><label>Kategori *</label><select name="kategori" class="form-control" id="kategori"><option value="">Pilih Kategori</option><option>Perizinan</option><option>Ekspor-Impor</option><option>Produksi</option><option>Keanggotaan</option><option>Statistik</option></select></div>
        <div class="form-row"><label>Nama Data *</label><input name="nama" type="text" class="form-control" placeholder="Contoh: Data Produksi Tahun 2024"></div>
        <div class="form-row"><label>Periode *</label><input name="periode" type="text" class="form-control" placeholder="Contoh: 2024, Q1 2024"></div>
        <div class="form-row"><label>Penanggung Jawab *</label><input name="pic" type="text" class="form-control" placeholder="Nama PJ"></div>
        <div class="form-row"><label>Status *</label><select name="status" class="form-control"><option>Draft</option><option>In Progress</option><option>Pending Review</option><option>Complete</option></select></div>
        <div class="form-row"><label>Priority *</label><select name="priority" class="form-control"><option>Low</option><option>Medium</option><option>High</option></select></div>
        <div class="form-row"><label>Progress (%)</label><input name="progress" type="number" min="0" max="100" class="form-control" value="0"></div>
      </form>
    </div>
    <div class="modal-footer"><button class="btn btn-secondary" id="dm-cancel">Batal</button><button class="btn btn-primary" id="dm-save">Simpan Data</button></div>
  </div>
</div>
<script>document.getElementById('dm-close')?.addEventListener('click',function(){document.getElementById('dm-modal').style.display='none';});</script>
@endpush

