@extends('layouts.app')
@section('title', 'Badan Pendapatan Daerah')
@section('body-class', 'dashboard-page force-light')

@section('content')
    <div class="page active" id="bapenda-page">
      <div class="page-header"><h1>Data Bapenda</h1><p>Pendapatan daerah dan realisasi</p></div>
      <div class="card">
        <div class="card-header">
          <div class="head-left">
            <h3>Tabel Pendapatan</h3>
            <div class="sub">Data pendapatan daerah Kabupaten Kolaka Utara</div>
          </div>
          <div class="head-actions"><button class="btn btn-outline btn-sm" id="bp-export"><i class="fas fa-download"></i> Export</button><button class="btn btn-primary btn-sm" id="bp-add"><i class="fas fa-plus"></i> Ajukan Data</button></div>
        </div>
        <div class="card-body">
          <div class="table-wrap">
            <table class="table table-compact">
              <thead><tr><th>No</th><th>Uraian</th><th>2022</th><th>2023</th><th>2024</th><th class="col-actions">Aksi</th></tr></thead>
              <tbody id="bp-tbody"></tbody>
            </table>
          </div>
          <!-- Inline form for adding/editing -->
          <div class="card" id="bp-panel" style="margin-top:20px; display:none;">
            <div class="card-header">
              <h3 id="bp-panel-title">Tambah Data Bapenda</h3>
            </div>
            <div class="card-body">
              <div class="form-group">
                <label>Uraian</label>
                <input type="text" id="bp-uraian" class="form-control" placeholder="Contoh: Pendapatan Asli Daerah">
              </div>
              <div class="form-row" style="display:flex; gap:12px;">
                <div class="form-group" style="flex:1;">
                  <label>2022</label>
                  <input type="text" id="bp-2022" class="form-control" placeholder="0">
                </div>
                <div class="form-group" style="flex:1;">
                  <label>2023</label>
                  <input type="text" id="bp-2023" class="form-control" placeholder="0">
                </div>
                <div class="form-group" style="flex:1;">
                  <label>2024</label>
                  <input type="text" id="bp-2024" class="form-control" placeholder="0">
                </div>
              </div>
              <div class="form-actions" style="display:flex; gap:8px; justify-content:flex-end; margin-top:12px;">
                <button class="btn btn-secondary" id="bp-cancel">Batal</button>
                <button class="btn btn-primary" id="bp-save">Simpan</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
var bpRows = [], bpEditId = null;
var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
var opdName = 'Badan Pendapatan Daerah';
var tableKey = 'bapenda_pendapatan';

async function fetchBp() {
    try {
        const res = await fetch(`/opd/rows?opd=${encodeURIComponent(opdName)}&key=${tableKey}`, { headers:{ 'Accept':'application/json' } });
        const data = await res.json();
        bpRows = Array.isArray(data) ? data.map((r, i) => ({
            id: r.id,
            no: i+1,
            uraian: r.uraian,
            y2022: (r.values || {}).y2022 || '',
            y2023: (r.values || {}).y2023 || '',
            y2024: (r.values || {}).y2024 || ''
        })) : [];
        renderBp();
    } catch (_) {
        bpRows = [];
        renderBp();
    }
}

function renderBp() {
    const tb = document.getElementById('bp-tbody');
    if(!tb) return;
    tb.innerHTML = bpRows.map(function(r, i) {
        return `<tr data-row="${i}">
            <td>${r.no}</td>
            <td class="c-uraian">${r.uraian}</td>
            <td class="c-y2022">${r.y2022||'-'}</td>
            <td class="c-y2023">${r.y2023||'-'}</td>
            <td class="c-y2024">${r.y2024||'-'}</td>
            <td><button class="btn btn-outline btn-sm action-btn" data-bp-ed="${i}"><i class="fas fa-pen"></i></button> <button class="btn btn-outline btn-sm action-btn" data-bp-del="${i}"><i class="fas fa-trash"></i></button></td>
        </tr>`;
    }).join('');
}

document.addEventListener('DOMContentLoaded', function() {
    fetchBp();
});

document.getElementById('bp-add')?.addEventListener('click', function() {
    bpEditId = null;
    document.getElementById('bp-uraian').value = '';
    document.getElementById('bp-2022').value = '';
    document.getElementById('bp-2023').value = '';
    document.getElementById('bp-2024').value = '';
    document.getElementById('bp-panel-title').textContent = 'Tambah Data Bapenda';
    document.getElementById('bp-panel').style.display = 'block';
});

document.getElementById('bp-cancel')?.addEventListener('click', function() {
    document.getElementById('bp-panel').style.display = 'none';
});

document.getElementById('bp-save')?.addEventListener('click', async function() {
    const uraian = document.getElementById('bp-uraian').value.trim();
    if (!uraian) {
        Utils.showToast('Isi Uraian', 'error');
        return;
    }

    const values = {
        y2022: document.getElementById('bp-2022').value.trim(),
        y2023: document.getElementById('bp-2023').value.trim(),
        y2024: document.getElementById('bp-2024').value.trim()
    };

    const hasValue = values.y2022 || values.y2023 || values.y2024;
    if (!hasValue) {
        Utils.showToast('Isi minimal salah satu nilai tahun', 'error');
        return;
    }

    const isUser = window.USER_ROLE === 'user';
    if (!isUser) {
        try {
            const payload = {
                opd: opdName,
                key: tableKey,
                uraian: uraian,
                values: values
            };

            const url = bpEditId ? `/opd/rows/${bpEditId}` : '/opd/rows';
            const method = bpEditId ? 'PUT' : 'POST';

            const res = await fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            if (!res.ok) {
                Utils.showToast('Gagal menyimpan', 'error');
                return;
            }

            await fetchBp();
            document.getElementById('bp-panel').style.display = 'none';
            Utils.showToast(bpEditId ? 'Data diperbarui' : 'Data ditambahkan', 'success');
        } catch(e) {
            Utils.showToast('Gagal menyimpan', 'error');
        }
    }
});

// Handle edit and delete actions
document.getElementById('bp-tbody')?.addEventListener('click', function(e) {
    const editBtn = e.target.closest('[data-bp-ed]');
    const deleteBtn = e.target.closest('[data-bp-del]');

    if (editBtn) {
        const idx = parseInt(editBtn.getAttribute('data-bp-ed'), 10);
        const r = bpRows[idx];
        document.getElementById('bp-uraian').value = r.uraian;
        document.getElementById('bp-2022').value = r.y2022 || '';
        document.getElementById('bp-2023').value = r.y2023 || '';
        document.getElementById('bp-2024').value = r.y2024 || '';
        bpEditId = r.id;
        document.getElementById('bp-panel-title').textContent = 'Ubah Data Bapenda';
        document.getElementById('bp-panel').style.display = 'block';
    } else if (deleteBtn) {
        const idx = parseInt(deleteBtn.getAttribute('data-bp-del'), 10);
        const id = bpRows[idx]?.id;
        if (!id) {
            Utils.showToast('ID data tidak ditemukan', 'error');
            return;
        }
        Utils.confirm('Hapus baris ini?', {
            okText: 'Hapus',
            cancelText: 'Batal',
            variant: 'danger'
        }).then(async function(yes) {
            if (!yes) return;
            try {
                const res = await fetch(`/opd/rows/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                if (res.ok) {
                    await fetchBp();
                    Utils.showToast('Baris dihapus', 'success');
                } else {
                    Utils.showToast('Gagal menghapus', 'error');
                }
            } catch(e) {
                Utils.showToast('Gagal menghapus', 'error');
            }
        });
    }
});

// Export functionality
document.getElementById('bp-export')?.addEventListener('click', function() {
    const headers = ['No', 'Uraian', '2022', '2023', '2024'];
    const rows = bpRows.map(function(r) {
        return [r.no, r.uraian, r.y2022, r.y2023, r.y2024];
    });

    // Create CSV
    const csv = [headers].concat(rows).map(function(row) {
        return row.map(function(v) {
            const s = ('' + (v == null ? '' : v)).replace(/"/g, '""');
            return '"' + s + '"';
        }).join(',');
    }).join('\n');

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'bapenda-pendapatan.csv';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
});
</script>
@endpush
