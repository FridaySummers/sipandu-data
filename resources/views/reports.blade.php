@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="reports-container">
    <div class="page-header">
        <h1>Reports & Analytics</h1>
    </div>

    <div class="report-filters">
        <select id="filter-period" class="form-control">
            <option value="month">This Month</option>
            <option value="quarter">This Quarter</option>
            <option value="year">This Year</option>
        </select>
        <button class="btn btn-secondary" id="export-btn">
            <i class="fas fa-download"></i> Export
        </button>
    </div>

    <div class="reports-grid">
        <div class="card">
            <div class="card-header">
                <h3>Overall Progress</h3>
            </div>
            <div class="card-body">
                <canvas id="overallChart"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Submissions by Dinas</h3>
            </div>
            <div class="card-body">
                <canvas id="dinasChart"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Status Distribution</h3>
            </div>
            <div class="card-body">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Timeline</h3>
            </div>
            <div class="card-body">
                <canvas id="timelineChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Overall Progress Chart
        const overallCtx = document.getElementById('overallChart');
        if (overallCtx) {
            new Chart(overallCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Completed', 'In Progress', 'Pending'],
                    datasets: [{
                        data: [60, 25, 15],
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444']
                    }]
                },
                options: { responsive: true }
            });
        }
    });
</script>
@endpush
