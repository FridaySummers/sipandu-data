@extends('layouts.app')
@section('title', 'DPMPTSP')
@section('body-class', 'dashboard-page force-light')

@section('content')
    <div class="page active" id="dpmptsp-page">
      <div class="page-header">
        <h1>Data {{ $dinas->nama_dinas }}</h1>
        <p>Realisasi Investasi PMDN & PMA Tahun 2025–2029</p>
        
        @if(in_array(auth()->user()->role, ['super_admin', 'admin_dinas']))
        <div class="header-actions">
          <button class="btn btn-outline btn-sm" id="dpm-export"><i class="fas fa-download"></i> Export</button>
          <button class="btn btn-primary btn-sm" id="dpm-add"><i class="fas fa-plus"></i> Tambah Data</button>
        </div>
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

      <div class="card dpm-card">
        <div class="card-header dpm-header">
          <div class="head-left">
            <h3>Tabel Realisasi Investasi 2025–2029</h3>
            <div class="sub">Data perkembangan PMDN & PMA</div>
          </div>
        </div>
        <div class="card-body">
          <div class="table-wrap">
            <table class="table table-compact dpm-table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Indikator</th>
                  <th>Tipe</th>
                  <th>2025</th>
                  <th>2026</th>
                  <th>2027</th>
                  <th>2028</th>
                  <th>2029</th>
                  <th>File</th>
                  <th>Status</th>
                  <th class="col-actions">Aksi</th>
                </tr>
              </thead>
              <tbody id="dpm-tbody">
                @foreach($dataSubmissions as $index => $submission)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $submission->judul_data }}</td>
                  <td>
                    @if(isset($submission->structured_data['tipe']))
                      <span class="chip {{ $submission->structured_data['tipe'] === 'PMDN' ? 'pmdn' : 'pma' }}">
                        {{ $submission->structured_data['tipe'] }}
                      </span>
                    @endif
                  </td>
                  <td>{{ $submission->structured_data['y2025'] ?? '' }}</td>
                  <td>{{ $submission->structured_data['y2026'] ?? '' }}</td>
                  <td>{{ $submission->structured_data['y2027'] ?? '' }}</td>
                  <td>{{ $submission->structured_data['y2028'] ?? '' }}</td>
                  <td>{{ $submission->structured_data['y2029'] ?? '' }}</td>
                  <td>
                    @if($submission->file_path)
                      <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="btn btn-sm btn-outline">
                        <i class="fas fa-file-download"></i>
                      </a>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td>
                    @if($submission->status === 'approved')
                      <span class="badge bg-success">Approved</span>
                    @elseif($submission->status === 'rejected')
                      <span class="badge bg-danger">Rejected</span>
                    @else
                      <span class="badge bg-warning">Pending</span>
                    @endif
                  </td>
                  <td>
                    <a href="{{ route('datamanagement.edit', $submission->id) }}" class="btn btn-sm btn-info">
                      <i class="fas fa-eye"></i>
                    </a>
                    @if(auth()->user()->role === 'super_admin' || auth()->user()->id === $submission->user_id)
                    <a href="{{ route('datamanagement.edit', $submission->id) }}" class="btn btn-sm btn-warning">
                      <i class="fas fa-edit"></i>
                    </a>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Modal Form -->
      <div class="modal-overlay" id="dpm-modal" style="display:none;">
        <div class="modal dpm-modal">
          <div class="modal-header">
            <h3>Tambah Data DPMPTSP</h3>
            <button class="btn btn-outline btn-sm" id="dpm-close">✕</button>
          </div>
          <form id="dpm-form" action="{{ route('datamanagement.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="dinas_id" value="{{ $dinas->id }}">
            <input type="hidden" name="tahun_perencanaan" value="2025">
            <div class="modal-body">
              <div class="tip-bar">
                💡 <span>Anda bisa menambahkan lebih dari 1 indikator. Klik "Tambah Row".</span>
              </div>
              
              <!-- FORM ROWS - DI ATAS -->
              <div id="dpm-rows"></div>

              <!-- TOMBOL TAMBAH ROW -->
              <div class="form-actions" style="margin-top:10px">
                <button type="button" class="btn btn-outline btn-sm" id="dpm-add-row">Tambah Row Baru</button>
              </div>

              <!-- FILE UPLOAD SECTION - DIPINDAH KE BAWAH SETELAH TOMBOL -->
              <div class="file-upload-section">
                <div class="form-row">
                  <label>File Pendukung (Opsional)</label>
                  <div class="file-upload-area">
                    <input type="file" name="file_path" id="file-upload" class="file-input" accept=".pdf,.doc,.docx,.xlsx,.xls,.jpg,.jpeg,.png">
                    <div class="file-upload-placeholder">
                      <i class="fas fa-cloud-upload-alt"></i>
                      <p>Klik untuk upload file pendukung</p>
                      <small>Format: PDF, DOC, XLSX, JPG, PNG (Maks. 2MB)</small>
                    </div>
                    <div class="file-upload-preview" style="display: none;">
                      <i class="fas fa-file"></i>
                      <span class="file-name"></span>
                      <button type="button" class="btn-remove-file">✕</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <div class="hint" id="dpm-save-hint">Akan menyimpan 0 data</div>
              <button type="button" class="btn btn-secondary" id="dpm-cancel">Batal</button>
              <button type="submit" class="btn btn-primary" id="dpm-save">Simpan Semua (0 data)</button>
            </div>
          </form>
        </div>
      </div>
    </div>
@endsection

@push('styles')
<style>
  .header-actions { display: flex; gap: 12px; margin-top: 15px; }
  .dpm-card{border-radius:16px;overflow:hidden}
  .dpm-header{padding:16px 20px;display:flex;align-items:center;justify-content:space-between;background:linear-gradient(90deg,#60a5fa,#3b82f6);color:#fff}
  .dpm-header h3{color:#fff}
  .dpm-header .sub{font-size:13px;opacity:.9;color:#fff}
  .dpm-table{width:100%;border:1px solid #93c5fd;border-radius:12px;overflow:hidden;border-collapse:separate;border-spacing:0}
  .dpm-table thead th{background:#fff7ed;color:#9a3412;font-weight:600;border-bottom:1px solid #93c5fd}
  .dpm-table th,.dpm-table td{padding:10px 12px;border-right:1px solid #93c5fd}
  .dpm-table thead tr th:first-child{border-left:1px solid #93c5fd}
  .dpm-table tbody tr td:first-child{border-left:1px solid #93c5fd}
  .dpm-table tbody tr + tr td{border-top:1px solid #93c5fd}
  .dpm-table tbody tr:last-child td{border-bottom:1px solid #93c5fd}
  .dpm-table td:last-child{text-align:center;white-space:nowrap}
  .col-actions{width:120px}
  .chip{display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:9999px;font-size:12px;border:none;background:#fff;color:#374151;box-shadow:0 1px 3px rgba(0,0,0,0.08)}
  .chip.pmdn{background:linear-gradient(90deg,#3b82f6,#2563eb);color:#fff}
  .chip.pma{background:linear-gradient(90deg,#ec4899,#f43f5e);color:#fff}
  .action-btn{background:#fff;border:1px solid var(--gray-300);border-radius:8px;width:30px;height:30px;display:inline-flex;align-items:center;justify-content:center;color:#374151}
  .action-btn:hover{background:#f8fafc}
  #dpmptsp-page .action-btn{background:transparent;border:none;box-shadow:none;width:auto;height:auto;padding:0}
  #dpmptsp-page .action-btn .fa-pen{color:#f97316}
  .dpm-header .head-actions{display:flex;gap:12px}
  .dpm-header .head-actions .btn{border-radius:8px;height:34px;padding:0 12px}
  .dpm-modal{max-width:720px}
  .tip-bar{display:flex;align-items:center;gap:8px;padding:10px 12px;background:#ecfeff;color:#0e7490;border:1px solid #a5f3fc;border-radius:12px;margin-bottom:12px}
  .row-card{border:1px solid #fed7aa;background:#fff7ed;border-radius:12px;padding:12px;margin-bottom:10px}
  .row-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:10px}
  .row-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:8px}
  .form-row{display:flex;flex-direction:column;gap:6px}
  .year-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:8px}
  .row-actions{display:flex;gap:8px;justify-content:flex-end;margin-top:8px}
  .hint{margin-right:auto;color:#64748b}

  /* File Upload Styles - DIPINDAH KE BAWAH */
  .file-upload-section {
    margin: 20px 0;
    padding: 15px;
    background: #f8fafc;
    border-radius: 12px;
    border: 2px dashed #cbd5e1;
  }

  .file-upload-area {
    position: relative;
    cursor: pointer;
  }

  .file-input {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
  }

  .file-upload-placeholder, .file-upload-preview {
    padding: 20px;
    text-align: center;
    border-radius: 8px;
    transition: all 0.3s ease;
  }

  .file-upload-placeholder {
    background: #ffffff;
    border: 2px dashed #cbd5e1;
    color: #64748b;
  }

  .file-upload-placeholder:hover {
    border-color: #3b82f6;
    background: #eff6ff;
  }

  .file-upload-placeholder i {
    font-size: 2rem;
    margin-bottom: 10px;
    color: #94a3b8;
  }

  .file-upload-preview {
    background: #dcfce7;
    border: 2px solid #22c55e;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px;
  }

  .file-upload-preview i {
    color: #22c55e;
    font-size: 1.5rem;
  }

  .file-name {
    flex: 1;
    margin-left: 10px;
    text-align: left;
    font-weight: 500;
    color: #166534;
  }

  .btn-remove-file {
    background: #ef4444;
    color: white;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
  }
</style>
@endpush

@push('scripts')
<script>
  // Fungsi untuk generate form row
  function rowTemplate(idx, data) {
    data = data || { indikator: '', tipe: 'PMDN', y2025: '', y2026: '', y2027: '', y2028: '', y2029: '' };
    return `
    <div class="row-card" data-row="${idx}">
      <div class="row-head">
        <div>Row #${idx + 1}</div>
        <button type="button" class="btn btn-outline btn-sm" data-remove="${idx}">Hapus</button>
      </div>
      <div class="form-row">
        <label>Indikator *</label>
        <input type="text" class="form-control" name="structured_data[${idx}][judul_data]" value="${data.indikator || ''}" placeholder="Contoh: Jumlah investor Berskala" required>
      </div>
      <div class="form-row">
        <label>Tipe Investasi *</label>
        <select class="form-control" name="structured_data[${idx}][tipe]" required>
          <option value="PMDN" ${data.tipe === 'PMDN' ? 'selected' : ''}>PMDN</option>
          <option value="PMA" ${data.tipe === 'PMA' ? 'selected' : ''}>PMA</option>
        </select>
      </div>
      <div class="year-grid">
        <div class="form-row"><label>2025</label><input type="number" class="form-control" name="structured_data[${idx}][y2025]" value="${data.y2025 || ''}" required></div>
        <div class="form-row"><label>2026</label><input type="number" class="form-control" name="structured_data[${idx}][y2026]" value="${data.y2026 || ''}" required></div>
        <div class="form-row"><label>2027</label><input type="number" class="form-control" name="structured_data[${idx}][y2027]" value="${data.y2027 || ''}" required></div>
        <div class="form-row"><label>2028</label><input type="number" class="form-control" name="structured_data[${idx}][y2028]" value="${data.y2028 || ''}" required></div>
        <div class="form-row"><label>2029</label><input type="number" class="form-control" name="structured_data[${idx}][y2029]" value="${data.y2029 || ''}" required></div>
      </div>
    </div>`;
  }

  // File Upload Functionality
  document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file-upload');
    const filePlaceholder = document.querySelector('.file-upload-placeholder');
    const filePreview = document.querySelector('.file-upload-preview');
    const fileName = document.querySelector('.file-name');
    const removeBtn = document.querySelector('.btn-remove-file');

    if (fileInput && filePlaceholder) {
      fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
          const file = e.target.files[0];
          
          // Validasi file size (max 2MB)
          if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB');
            fileInput.value = '';
            return;
          }

          // Validasi file type
          const allowedTypes = ['application/pdf', 'application/msword', 
                               'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                               'application/vnd.ms-excel', 
                               'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                               'image/jpeg', 'image/jpg', 'image/png'];
          
          if (!allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung. Gunakan PDF, DOC, XLSX, JPG, atau PNG');
            fileInput.value = '';
            return;
          }

          fileName.textContent = file.name;
          filePlaceholder.style.display = 'none';
          filePreview.style.display = 'flex';
        }
      });

      removeBtn.addEventListener('click', function() {
        fileInput.value = '';
        filePreview.style.display = 'none';
        filePlaceholder.style.display = 'block';
      });

      // Drag and drop functionality
      const uploadArea = document.querySelector('.file-upload-area');
      
      uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.style.background = '#eff6ff';
      });

      uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.style.background = '';
      });

      uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.style.background = '';
        
        if (e.dataTransfer.files.length > 0) {
          fileInput.files = e.dataTransfer.files;
          const event = new Event('change', { bubbles: true });
          fileInput.dispatchEvent(event);
        }
      });
    }
  });

  // Modal functions
  function openModal(prefill, editIdx) {
    var m = document.getElementById('dpm-modal');
    if (!m) return;
    
    m.style.display = 'flex';
    m.dataset.edit = editIdx != null ? editIdx : '';
    var wrap = document.getElementById('dpm-rows');
    wrap.innerHTML = '';
    var rows = prefill && prefill.length ? prefill : [{}];
    rows.forEach(function(d, i) {
      wrap.insertAdjacentHTML('beforeend', rowTemplate(i, d));
    });
    updateSaveCount();
  }

  function closeModal() {
    var m = document.getElementById('dpm-modal');
    if (!m) return;
    
    m.style.display = 'none';
    
    // Reset file upload when closing modal
    const fileInput = document.getElementById('file-upload');
    const filePreview = document.querySelector('.file-upload-preview');
    const filePlaceholder = document.querySelector('.file-upload-placeholder');
    
    if (fileInput && filePreview && filePlaceholder) {
      fileInput.value = '';
      filePreview.style.display = 'none';
      filePlaceholder.style.display = 'block';
    }
  }

  function addRow() {
    var wrap = document.getElementById('dpm-rows');
    if (!wrap) return;
    
    var idx = wrap.children.length;
    wrap.insertAdjacentHTML('beforeend', rowTemplate(idx, {}));
    updateSaveCount();
  }

  function updateSaveCount() {
    var c = document.getElementById('dpm-rows') ? document.getElementById('dpm-rows').children.length : 0;
    var saveBtn = document.getElementById('dpm-save');
    var hint = document.getElementById('dpm-save-hint');
    
    if (saveBtn) saveBtn.textContent = 'Simpan Semua (' + c + ' data)';
    if (hint) hint.textContent = 'Akan menyimpan ' + c + ' data';
  }

  // Event listeners
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('dpm-add')?.addEventListener('click', function() {
      openModal(null, null);
    });

    document.getElementById('dpm-cancel')?.addEventListener('click', function() {
      closeModal();
    });

    document.getElementById('dpm-close')?.addEventListener('click', function() {
      closeModal();
    });

    document.getElementById('dpm-add-row')?.addEventListener('click', function() {
      addRow();
    });

    document.getElementById('dpm-rows')?.addEventListener('click', function(e) {
      var btn = e.target.closest('button');
      if (!btn) return;
      if (btn.dataset.remove != null) {
        var idx = parseInt(btn.dataset.remove, 10);
        var wrap = document.getElementById('dpm-rows');
        if (wrap.children[idx]) {
          if (confirm('Hapus baris formulir ini?')) {
            wrap.children[idx].remove();
            updateSaveCount();
          }
        }
      }
    });

    // Form submission
    document.getElementById('dpm-form')?.addEventListener('submit', function(e) {
      var rows = document.getElementById('dpm-rows') ? document.getElementById('dpm-rows').children.length : 0;
      if (rows === 0) {
        e.preventDefault();
        alert('Tambah minimal 1 data terlebih dahulu');
        return;
      }
    });
  });
</script>
@endpush