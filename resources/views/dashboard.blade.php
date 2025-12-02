@extends('layouts.app')
@section('title', 'Dashboard')
@section('body-class', 'dashboard-page force-light')

@section('content')
    <div class="page active" id="dashboard-page">
            <div class="page-header"><h1>Dashboard Overview</h1><p>Monitoring real-time SIPANDU DATA - RKPD 2025</p></div>
            <div class="kpi-grid">
                <div class="kpi-card"><div class="kpi-icon"><i class="fas fa-building"></i></div><div class="kpi-content"><div class="kpi-number" id="total-dinas">{{ $totalDinas }}</div><div class="kpi-label">Total Dinas</div><div class="kpi-trend positive"><i class="fas fa-arrow-up"></i><span>+0%</span></div></div></div>
                <div class="kpi-card"><div class="kpi-icon success"><i class="fas fa-check-circle"></i></div><div class="kpi-content"><div class="kpi-number" id="complete-data">{{ $completeData }}</div><div class="kpi-label">Data Complete</div><div class="kpi-trend positive"><i class="fas fa-arrow-up"></i><span>+2</span></div></div></div>
                <div class="kpi-card"><div class="kpi-icon warning"><i class="fas fa-percentage"></i></div><div class="kpi-content"><div class="kpi-number" id="avg-progress">{{ $avgProgress }}</div><div class="kpi-label">Rata-rata Progress</div><div class="kpi-trend positive"><i class="fas fa-arrow-up"></i><span>+5%</span></div></div></div>
                <div class="kpi-card"><div class="kpi-icon info"><i class="fas fa-clock"></i></div><div class="kpi-content"><div class="kpi-number" id="pending-reviews">{{ $pendingReviews }}</div><div class="kpi-label">Pending Reviews</div><div class="kpi-trend negative"><i class="fas fa-arrow-down"></i><span>-2</span></div></div></div>
            </div>
            <div class="charts-grid">
                <div class="chart-card"><div class="card-header"><h3>Trend Progress Bulanan</h3><div class="card-actions"><button class="btn btn-sm btn-outline"><i class="fas fa-download"></i></button></div></div><div class="card-body"><canvas id="monthly-progress-chart"></canvas></div></div>
                <div class="chart-card"><div class="card-header"><h3>Status Data per Dinas</h3><div class="card-actions"><button class="btn btn-sm btn-outline"><i class="fas fa-expand"></i></button></div></div><div class="card-body"><canvas id="dinas-status-chart"></canvas></div></div>
                <div class="chart-card"><div class="card-header"><h3>Kategori Data</h3></div><div class="card-body"><canvas id="data-category-chart"></canvas></div></div>
            </div>
            <div class="bottom-grid">
                <div class="activity-card"><div class="card-header"><h3>Aktivitas Terbaru</h3><a href="#" class="view-all">Lihat Semua</a></div><div class="activity-list" id="activity-list"></div></div>
                <div class="quick-actions-card"><div class="card-header"><h3>Quick Actions</h3></div><div class="quick-actions"><button class="quick-action-btn"><i class="fas fa-upload"></i><span>Upload Data</span></button><button class="quick-action-btn"><i class="fas fa-file-alt"></i><span>Generate Report</span></button><button class="quick-action-btn"><i class="fas fa-plus"></i><span>New Discussion</span></button><button class="quick-action-btn"><i class="fas fa-calendar-plus"></i><span>Add Event</span></button></div></div>
            </div>
        </div>
@endsection

@push('scripts')
@endpush
