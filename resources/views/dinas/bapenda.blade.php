@extends('layouts.app')
@section('title', 'Badan Pendapatan Daerah')
@section('body-class', 'dashboard-page force-light')

@section('content')
    <div class="page active" id="bapenda-page">
      <div class="page-header"><h1>Data Bapenda</h1><p>Pendapatan daerah dan realisasi</p></div>
      <div class="card">
        <div class="card-header">
          <div class="head-left">
            <h3>Tabel Pendapatan</h3>
            <div class="sub">Data contoh untuk Bapenda</div>
          </div>
          <div class="head-actions"><button class="btn btn-outline btn-sm" id="bp-export"><i class="fas fa-download"></i> Export</button><button class="btn btn-primary btn-sm" id="bp-add"><i class="fas fa-plus"></i> Ajukan Data</button></div>
        </div>
        <div class="card-body">
          <div class="table-wrap">
            <table class="table table-compact">
              <thead><tr><th>No</th><th>Uraian</th><th>2022</th><th>2023</th><th>2024</th><th class="col-actions">Aksi</th></tr></thead>
              <tbody id="bp-tbody"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
var bpRows=[{no:1,uraian:'Pendapatan Asli Daerah',y2022:100,y2023:120,y2024:130}];
function renderBp(){var tb=document.getElementById('bp-tbody');if(!tb)return;tb.innerHTML=bpRows.map(function(r,i){return '<tr><td>'+(i+1)+'</td><td>'+r.uraian+'</td><td>'+r.y2022+'</td><td>'+r.y2023+'</td><td>'+r.y2024+'</td><td><button class="btn btn-outline btn-sm"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm"><i class="fas fa-trash"></i></button></td></tr>'}).join('')}
renderBp();
</script>
@endpush
