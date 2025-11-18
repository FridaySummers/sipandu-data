@extends('layouts.app')

@section('title', 'Dashboard')
@section('body-class', 'dashboard-page force-light')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Dashboard SIPANDU DATA</h1>
        <p>Selamat datang, {{ auth()->user()->name }}</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-building"></i>
            </div>
            <div class="stat-content">
                <h3>Dinas Terdaftar</h3>
                <p class="stat-number">{{ $dinasCount ?? 12 }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-content">
                <h3>Data Submission</h3>
                <p class="stat-number">{{ $submissionsCount ?? 0 }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="stat-content">
                <h3>Pending Submission</h3>
                <p class="stat-number">{{ $pendingSubmissions ?? 0 }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3>Completion Rate</h3>
                <p class="stat-number">75%</p>
            </div>
        </div>
    </div>

    <div class="dashboard-content">
        <div class="dashboard-main">
            <div class="card">
                <div class="card-header">
                    <h2>Data Submission Progress</h2>
                </div>
                <div class="card-body">
                    <canvas id="progressChart"></canvas>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2>Recent Submissions</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Dinas</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated by JavaScript or PHP loop -->
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No data available</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-sidebar">
            <div class="card">
                <div class="card-header">
                    <h3>Quick Actions</h3>
                </div>
                <div class="card-body">
                    <ul class="action-list">
                        <li><a href="{{ route('datamanagement') }}"><i class="fas fa-plus"></i> Buat Submission</a></li>
                        <li><a href="{{ route('reports') }}"><i class="fas fa-file-pdf"></i> Lihat Laporan</a></li>
                        <li><a href="{{ route('calendar') }}"><i class="fas fa-calendar"></i> Kalender Deadline</a></li>
                        <li><a href="{{ route('forum') }}"><i class="fas fa-comments"></i> Forum Diskusi</a></li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Notifications</h3>
                </div>
                <div class="card-body">
                    <div class="notification-item">
                        <i class="fas fa-info-circle"></i>
                        <p>New deadline reminder for Dinas Perdagangan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('progressChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Dinas 1', 'Dinas 2', 'Dinas 3', 'Dinas 4', 'Dinas 5'],
                    datasets: [{
                        label: 'Submission Progress (%)',
                        data: [100, 80, 60, 90, 75],
                        backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'],
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
