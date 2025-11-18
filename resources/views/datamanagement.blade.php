@extends('layouts.app')

@section('title', 'Data Management')

@section('content')
<div class="datamanagement-container">
    <div class="page-header">
        <h1>Data Management</h1>
        <button class="btn btn-primary" id="new-submission-btn">
            <i class="fas fa-plus"></i> Buat Submission Baru
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h2>Data Submissions</h2>
            <div class="filter-group">
                <input type="text" id="search" placeholder="Cari submission..." class="form-control">
                <select id="filter-status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Dinas</th>
                            <th>Status</th>
                            <th>Submitted By</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions ?? [] as $submission)
                            <tr>
                                <td>{{ $submission->title }}</td>
                                <td>{{ $submission->dinas->nama ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-{{ $submission->status ?? 'pending' }}">
                                        {{ ucfirst($submission->status ?? 'pending') }}
                                    </span>
                                </td>
                                <td>{{ $submission->submitted_by ?? 'System' }}</td>
                                <td>{{ $submission->submitted_at?->format('d M Y') ?? 'N/A' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-secondary" onclick="viewSubmission({{ $submission->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($submissions)
                <div class="pagination-wrapper">
                    {{ $submissions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal for new submission -->
<div id="submission-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Buat Data Submission Baru</h2>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>
        <form method="POST" action="{{ route('datamanagement') }}" class="modal-body">
            @csrf
            <div class="form-group">
                <label for="dinas_id">Pilih Dinas</label>
                <select id="dinas_id" name="dinas_id" class="form-control" required>
                    <option value="">-- Pilih Dinas --</option>
                    <!-- Options will be populated by JavaScript -->
                </select>
            </div>

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label for="data">Data (JSON)</label>
                <textarea id="data" name="data" class="form-control" rows="6" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-full">Submit</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function closeModal() {
        document.getElementById('submission-modal').style.display = 'none';
    }

    document.getElementById('new-submission-btn')?.addEventListener('click', function() {
        document.getElementById('submission-modal').style.display = 'flex';
    });

    function viewSubmission(id) {
        // Implement view submission logic
        console.log('View submission', id);
    }

    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('submission-modal');
        if (e.target === modal) {
            closeModal();
        }
    });
</script>
@endpush
