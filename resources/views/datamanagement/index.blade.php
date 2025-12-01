<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Management - SIPANDU DATA</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datamanagement.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Data Management</h1>
            <p>Kelola data perencanaan dan monitoring</p>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-database"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $data->count() }}</h3>
                    <p>Total Data</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon pending">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $data->where('status', 'pending')->count() }}</h3>
                    <p>Pending Review</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon approved">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $data->where('status', 'approved')->count() }}</h3>
                    <p>Approved</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon rejected">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $data->where('status', 'rejected')->count() }}</h3>
                    <p>Rejected</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-bar">
            <a href="{{ route('datamanagement.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Data Baru
            </a>
            
            <div class="filters">
                <select id="status-filter" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
                
                <select id="dinas-filter" class="filter-select">
                    <option value="">Semua Dinas</option>
                    @foreach($dinasOptions ?? [] as $dinas)
                        <option value="{{ $dinas->id }}">{{ $dinas->nama_dinas }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Data Table -->
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Judul Data</th>
                        <th>Dinas</th>
                        <th>Tahun</th>
                        <th>Status</th>
                        <th>Dibuat Oleh</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                        <tr data-status="{{ $item->status }}" data-dinas="{{ $item->dinas_id }}">
                            <td>
                                <div class="data-title">
                                    <strong>{{ $item->judul_data }}</strong>
                                    @if($item->deskripsi)
                                        <small>{{ Str::limit($item->deskripsi, 50) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $item->dinas->nama_dinas }}</td>
                            <td>{{ $item->tahun_perencanaan }}</td>
                            <td>
                                <span class="status-badge status-{{ $item->status }}">
                                    @if($item->status == 'approved')
                                        <i class="fas fa-check"></i>
                                    @elseif($item->status == 'rejected')
                                        <i class="fas fa-times"></i>
                                    @else
                                        <i class="fas fa-clock"></i>
                                    @endif
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    @if($item->file_path)
                                        <a href="{{ Storage::url($item->file_path) }}" target="_blank" class="btn-icon" title="Download File">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                    
                                    @if($user->isSuperAdmin() || ($user->isAdminDinas() && $item->dinas_id == $user->dinas_id) || ($user->isUser() && $item->user_id == $user->id))
                                        <a href="{{ route('datamanagement.edit', $item->id) }}" class="btn-icon" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif

                                    @if(($user->isAdminDinas() || $user->isSuperAdmin()) && $item->status == 'pending')
                                        <form action="{{ route('datamanagement.approve', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-icon btn-success" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        
                                        <button type="button" class="btn-icon btn-danger reject-btn" 
                                                data-id="{{ $item->id }}" 
                                                data-title="{{ $item->judul_data }}"
                                                title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif

                                    @if($user->isSuperAdmin() || ($user->isAdminDinas() && $item->dinas_id == $user->dinas_id) || ($user->isUser() && $item->user_id == $user->id))
                                        <form action="{{ route('datamanagement.destroy', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon btn-danger" 
                                                    onclick="return confirm('Yakin ingin menghapus data ini?')"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <h3>Belum ada data</h3>
                                    <p>Mulai dengan menambahkan data pertama Anda</p>
                                    <a href="{{ route('datamanagement.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Data
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tolak Data</h3>
                <button class="close-modal">&times;</button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Anda akan menolak data: <strong id="rejectTitle"></strong></p>
                    <div class="form-group">
                        <label for="catatan_revisi">Alasan Penolakan</label>
                        <textarea name="catatan_revisi" id="catatan_revisi" rows="4" 
                                  placeholder="Berikan alasan mengapa data ini ditolak..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const statusFilter = document.getElementById('status-filter');
            const dinasFilter = document.getElementById('dinas-filter');
            const tableRows = document.querySelectorAll('.data-table tbody tr');

            function filterTable() {
                const statusValue = statusFilter.value;
                const dinasValue = dinasFilter.value;

                tableRows.forEach(row => {
                    const rowStatus = row.getAttribute('data-status');
                    const rowDinas = row.getAttribute('data-dinas');

                    const statusMatch = !statusValue || rowStatus === statusValue;
                    const dinasMatch = !dinasValue || rowDinas === dinasValue;

                    if (statusMatch && dinasMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            statusFilter.addEventListener('change', filterTable);
            dinasFilter.addEventListener('change', filterTable);

            // Reject modal functionality
            const rejectModal = document.getElementById('rejectModal');
            const rejectButtons = document.querySelectorAll('.reject-btn');
            const closeButtons = document.querySelectorAll('.close-modal');
            const rejectForm = document.getElementById('rejectForm');
            const rejectTitle = document.getElementById('rejectTitle');

            rejectButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const dataId = this.getAttribute('data-id');
                    const title = this.getAttribute('data-title');
                    
                    rejectTitle.textContent = title;
                    rejectForm.action = `/data-management/${dataId}/reject`;
                    rejectModal.style.display = 'flex';
                });
            });

            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    rejectModal.style.display = 'none';
                });
            });

            // Close modal when clicking outside
            rejectModal.addEventListener('click', function(e) {
                if (e.target === rejectModal) {
                    rejectModal.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>