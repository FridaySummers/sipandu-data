@extends('layouts.app')
@section('title', 'Status Dinas')
@section('body-class', 'dashboard-page force-light')

@section('content')
    <div class="page active" id="dinas-status-page">
        <div class="page-header"><h1>Status Dinas</h1><p>Progres dan kelengkapan data per OPD</p></div>
        <div class="kpi-grid">
            <div class="kpi-card" id="ds-kpi-total"><div class="kpi-icon info"><i class="fas fa-building"></i></div><div class="kpi-content"><div class="kpi-value">{{ $totalDinas ?? 0 }}</div><div class="kpi-label">Total OPD</div></div></div>
            <div class="kpi-card" id="ds-kpi-complete"><div class="kpi-icon success"><i class="fas fa-check-circle"></i></div><div class="kpi-content"><div class="kpi-value">{{ $completeDinas ?? 0 }}</div><div class="kpi-label">Complete</div></div></div>
            <div class="kpi-card" id="ds-kpi-progress"><div class="kpi-icon warning"><i class="fas fa-hourglass-half"></i></div><div class="kpi-content"><div class="kpi-value">{{ $progressDinas ?? 0 }}</div><div class="kpi-label">Progress</div></div></div>
            <div class="kpi-card" id="ds-kpi-pending"><div class="kpi-icon"><i class="fas fa-exclamation-circle"></i></div><div class="kpi-content"><div class="kpi-value">{{ $pendingDinas ?? 0 }}</div><div class="kpi-label">Pending</div></div></div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="ds-hero" style="width:100%">
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div class="ds-hero-icon"><i class="fas fa-building"></i></div>
                            <div>
                                <h3>Daftar OPD</h3>
                                <div class="sub">Manajemen & Monitoring Data Seluruh Dinas</div>
                            </div>
                        </div>
                        <div style="display:flex;gap:10px;align-items:center;">
                            <button class="btn btn-outline btn-wide" id="ds-export"><i class="fas fa-file-export"></i> Export</button>
                        </div>
                    </div>
                    <div class="controls-row">
                        <div class="search-box compact"><i class="fas fa-search"></i><input id="ds-search" type="text" placeholder="Cari OPD..."></div>
                        <select id="ds-status" class="form-control"><option value="">Semua Status</option><option value="Complete">Complete</option><option value="Progress">Progress</option><option value="Pending">Pending</option></select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-wrap"><table class="table table-compact" id="ds-table"></table></div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
#dinas-status-page .ds-hero{background:linear-gradient(135deg,#60a5fa,#3b82f6);border-radius:16px;padding:16px;color:#ffffff;box-shadow:var(--shadow)}
#dinas-status-page .ds-hero .sub{opacity:0.9;font-size:12px}
#dinas-status-page .ds-hero .btn{height:34px;border-radius:8px}
#dinas-status-page .ds-hero .btn.btn-outline{background:#ffffff;color:#111827;border-color:#e5e7eb}
#dinas-status-page .ds-hero-icon{width:36px;height:36px;background:#1e3a8a;border-radius:12px;display:flex;align-items:center;justify-content:center}
#dinas-status-page .ds-hero-icon i{color:#ffffff}
#dinas-status-page .controls-row{display:grid;grid-template-columns:1fr 220px;gap:10px;margin-top:10px}
#dinas-status-page .controls-row .search-box.compact{position:relative;background:#ffffff;border:1px solid #93c5fd;border-radius:12px;padding:0 12px;height:36px;box-shadow:var(--shadow-sm);width:100%}
#dinas-status-page .controls-row .search-box.compact i{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#6b7280}
#dinas-status-page .controls-row .search-box.compact input{border:none;outline:none;background:transparent;width:100%;padding:8px 10px 8px 34px;color:#111827}
#dinas-status-page .controls-row .form-control{height:36px;border:1px solid rgba(255,255,255,0.7) !important;background:rgba(255,255,255,0.08) !important;color:#ffffff !important;border-radius:6px !important;box-shadow:none !important;padding:0 32px 0 12px;appearance:none;-webkit-appearance:none;backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24'%3E%3Cpath fill='%23ffffff' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 10px center;background-size:16px}
#dinas-status-page .controls-row .form-control:focus{border-color:#ffffff;outline:none;box-shadow:none}
#dinas-status-page #ds-status option{color:#111827;background:#ffffff}
#dinas-status-page #ds-table thead tr{background:#eaf2ff}
#dinas-status-page #ds-table thead th{background:transparent !important;color:#0b3a82 !important;border-bottom:1px solid #c7d2fe !important;font-weight:600 !important;letter-spacing:0.01em !important}

/* Custom select in ds-hero (override rounded default) */
#dinas-status-page .ds-hero .custom-select{width:220px}
#dinas-status-page .ds-hero select.form-control{width:220px}
#dinas-status-page .ds-hero .cs-control{background:rgba(255,255,255,0.08);color:#ffffff;border:1px solid rgba(255,255,255,0.7);border-radius:6px;padding:6px 12px;backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px)}
#dinas-status-page .ds-hero .cs-chevron{stroke:#ffffff}
#dinas-status-page .ds-hero .cs-menu{background:#ffffff;border:1px solid rgba(0,0,0,0.08);box-shadow:var(--shadow-lg)}
</style>
@endpush

@push('scripts')
@endpush
