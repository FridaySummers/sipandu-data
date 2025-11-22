@extends('layouts.app')
@section('title', 'Status Dinas')
@section('body-class', 'dashboard-page force-light')

@section('content')
    <div class="page active" id="dinas-status-page">
        <div class="page-header"><h1>Status Dinas</h1><p>Progres dan kelengkapan data per OPD</p></div>
        <div class="kpi-grid">
            <div class="kpi-card" id="ds-kpi-total"><div class="kpi-icon info"><i class="fas fa-building"></i></div><div class="kpi-content"><div class="kpi-value">0</div><div class="kpi-label">Total OPD</div></div></div>
            <div class="kpi-card" id="ds-kpi-complete"><div class="kpi-icon success"><i class="fas fa-check-circle"></i></div><div class="kpi-content"><div class="kpi-value">0</div><div class="kpi-label">Complete</div></div></div>
            <div class="kpi-card" id="ds-kpi-progress"><div class="kpi-icon warning"><i class="fas fa-hourglass-half"></i></div><div class="kpi-content"><div class="kpi-value">0</div><div class="kpi-label">Progress</div></div></div>
            <div class="kpi-card" id="ds-kpi-pending"><div class="kpi-icon"><i class="fas fa-exclamation-circle"></i></div><div class="kpi-content"><div class="kpi-value">0</div><div class="kpi-label">Pending</div></div></div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3>Daftar OPD</h3>
                <div class="card-actions" style="gap:8px;display:flex;align-items:center;">
                    <div class="search-box compact"><i class="fas fa-search"></i><input type="text" id="ds-search" placeholder="Cari OPD..."></div>
                    <select id="ds-status" class="form-control" style="min-width:140px"><option value="">Semua Status</option><option value="Complete">Complete</option><option value="Progress">Progress</option><option value="Pending">Pending</option></select>
                    <button class="btn btn-outline btn-sm" id="ds-export"><i class="fas fa-file-csv"></i> Export CSV</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-wrap"><table class="table table-compact" id="ds-table"></table></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
