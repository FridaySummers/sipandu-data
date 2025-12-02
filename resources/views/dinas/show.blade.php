@extends('layouts.app')

@section('title', 'Data ' . $dinas->nama_dinas)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Data {{ $dinas->nama_dinas }}</h1>
                
                @if(in_array(auth()->user()->role, ['super_admin', 'admin_dinas']))
                <a href="{{ route('datamanagement.create') }}?dinas_id={{ $dinas->id }}" 
                   class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>
                @endif
            </div>

            <!-- Status Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">{{ $dataSubmissions->count() }}</h5>
                            <p class="card-text">Total Data</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">{{ $dataSubmissions->where('status', 'approved')->count() }}</h5>
                            <p class="card-text">Approved</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title">{{ $dataSubmissions->where('status', 'pending')->count() }}</h5>
                            <p class="card-text">Pending</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h5 class="card-title">{{ $dataSubmissions->where('status', 'rejected')->count() }}</h5>
                            <p class="card-text">Rejected</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Submissions</h5>
                    
                    @if($dataSubmissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Judul Data</th>
                                    <th>User</th>
                                    <th>Status</th>
                                    <th>Tanggal Submit</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dataSubmissions as $submission)
                                <tr>
                                    <td>{{ $submission->judul_data }}</td>
                                    <td>{{ $submission->user->name }}</td>
                                    <td>
                                        @if($submission->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($submission->status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $submission->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('datamanagement.edit', $submission->id) }}" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                        
                                        @if(auth()->user()->role === 'super_admin' || 
                                            auth()->user()->id === $submission->user_id ||
                                            auth()->user()->role === 'admin_dinas')
                                        <a href="{{ route('datamanagement.edit', $submission->id) }}" 
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-database fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada data untuk dinas ini</p>
                        @if(in_array(auth()->user()->role, ['super_admin', 'admin_dinas']))
                        <a href="{{ route('datamanagement.create') }}?dinas_id={{ $dinas->id }}" 
                           class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Data Pertama
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection