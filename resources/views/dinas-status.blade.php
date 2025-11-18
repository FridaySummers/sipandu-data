@extends('layouts.app')

@section('title', 'Dinas Status')

@section('content')
<div class="dinas-status-container">
    <div class="page-header">
        <h1>Dinas Status</h1>
    </div>

    <div class="dinas-grid">
        @forelse([
            ['nama' => 'Bappeda', 'status' => 'active', 'submissions' => 12],
            ['nama' => 'DPMPTSP', 'status' => 'active', 'submissions' => 10],
            ['nama' => 'Dinas Perdagangan', 'status' => 'pending', 'submissions' => 8],
            ['nama' => 'Dinas Perindustrian', 'status' => 'active', 'submissions' => 11],
            ['nama' => 'Dinas Koperasi dan UKM', 'status' => 'active', 'submissions' => 9],
            ['nama' => 'Dinas Pertanian', 'status' => 'pending', 'submissions' => 5],
        ] as $dinas)
            <div class="card dinas-card">
                <div class="card-body">
                    <div class="dinas-info">
                        <h3>{{ $dinas['nama'] }}</h3>
                        <p class="dinas-status status-{{ $dinas['status'] }}">
                            {{ ucfirst($dinas['status']) }}
                        </p>
                        <div class="dinas-submissions">
                            <span><i class="fas fa-file"></i> {{ $dinas['submissions'] }} Submissions</span>
                        </div>
                    </div>
                    <div class="dinas-actions">
                        <button class="btn btn-sm btn-secondary">View</button>
                    </div>
                </div>
            </div>
        @empty
            <p>No dinas available</p>
        @endforelse
    </div>
</div>
@endsection
