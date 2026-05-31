@extends('layouts.app')
@section('title', 'Badan Pendapatan Daerah (Bapenda)')
@section('body-class', 'dashboard-page force-light')
@push('styles')
<style>
#bapenda-page .segmented{display:inline-flex;gap:8px;background:#f1f5f9;padding:6px;border-radius:12px}
#bapenda-page .segmented .btn{height:36px;border-radius:8px;font-weight:600;border:none;box-shadow:none;color:#475569;background:transparent;transition:all 0.2s ease}
#bapenda-page .segmented .btn.btn-primary{background:#ffffff;color:#0f172a;box-shadow:0 2px 8px rgba(0,0,0,0.05)}
#bapenda-page .segmented .btn:hover:not(.btn-primary){background:#e2e8f0}
.bp-card{border-radius:20px;overflow:hidden;margin-bottom:18px;background:#ffffff;border:1px solid #f1f5f9;box-shadow:0 4px 20px rgba(0,0,0,0.02)}
.bp-card .card-header{padding:20px 24px;display:flex;align-items:center;justify-content:space-between;border:1px solid #cbd5e1;background:linear-gradient(135deg,#ffffff,#f8fafc)}
.bp-card .card-header h3{font-size:1.25rem;font-weight:700;color:#0f172a;margin-bottom:4px}
.bp-card .card-header .sub{font-size:13px;color:#64748b}
.bp-panel{margin:24px;border-radius:16px;padding:24px;border:1px solid #f1f5f9;background:#f8fafc;box-shadow:inset 0 2px 4px rgba(0,0,0,0.01)}
.bp-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px}
.bp-grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.form-group label{font-weight:600;font-size:13px;color:#475569;margin-bottom:6px;display:block}
.form-control{border:1px solid #cbd5e1;background:#ffffff;border-radius:12px;padding:12px 16px;transition:all 0.2s ease;color:#0f172a}
.form-control:focus{outline:none;border-color:#3b82f6;box-shadow:0 0 0 4px rgba(59,130,246,0.1)}
input[type="date"].form-control{height:44px;padding-right:16px}
input[type="date"].form-control::-webkit-calendar-picker-indicator{opacity:.6;cursor:pointer;padding:4px}
input[type="time"].form-control{height:44px}
.bp-tabs{display:flex;align-items:center;justify-content:flex-start;margin:16px 24px;gap:8px}
.bp-table-wrap{border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;background:#fff;box-shadow:0 1px 3px rgba(0,0,0,0.05);margin-bottom:24px;margin-left:24px;margin-right:24px}
.bp-table{width:100%;border-collapse:collapse;text-align:left}
.bp-table thead th{background:#f8fafc;color:#475569;font-weight:600;font-size:13px;padding:12px 16px;border:1px solid #cbd5e1}
.bp-table tbody tr{transition:background 0.2s}
.bp-table tbody tr:hover{background:#f1f5f9}
.bp-table td{padding:12px 16px;border:1px solid #cbd5e1;color:#0f172a;font-size:14px}
.bp-table tfoot th{background:#f8fafc;color:#0f172a;font-weight:700;padding:14px 16px;border:1px solid #cbd5e1}
.bp-table input.form-control{height:36px;padding:8px 12px;border-radius:8px;border:1px solid transparent;background:transparent;box-shadow:none}
.bp-table input.form-control:focus{background:#ffffff;border:1px solid #3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,0.1)}
.bp-table input.form-control:hover:not(:focus){border:1px solid #cbd5e1;background:#ffffff}
.bp-subtitle{font-weight:700;color:#0f172a;margin:24px 24px 12px;font-size:1.1rem;display:flex;align-items:center;gap:8px}
.bp-subtitle::before{content:'';display:block;width:4px;height:18px;background:#3b82f6;border-radius:4px}
.info{background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;border-radius:12px;padding:14px 16px;margin:24px;font-size:14px;display:flex;align-items:flex-start;gap:12px}
.info::before{content:'\f05a';font-family:'Font Awesome 6 Free';font-weight:900;font-size:16px;color:#3b82f6;margin-top:2px}
.btn-green{background:#10b981;color:#fff;border:none}
.btn-green:hover{background:#059669}
.btn-outline{background:#ffffff;border:1px solid #cbd5e1;color:#475569;font-weight:600}
.btn-outline:hover{background:#f8fafc;color:#0f172a}
.btn-primary{background:linear-gradient(135deg,#3b82f6,#2563eb);color:#ffffff;border:none;font-weight:600}
.btn-primary:hover{box-shadow:0 4px 12px rgba(37,99,235,0.2);transform:translateY(-1px)}
.actions-row{display:flex;gap:12px;justify-content:flex-end;margin:24px;padding-top:24px;border:1px solid #cbd5e1}
.summary-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:16px;margin:16px 24px}
.summary-cell{background:linear-gradient(135deg,#f8fafc,#f1f5f9);border:1px solid #e2e8f0;border-radius:12px;padding:16px;transition:transform 0.2s}
.summary-cell:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,0.03)}
.summary-cell .label{font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.02em}
.summary-cell .value{font-weight:700;color:#0f172a;font-size:1.25rem;margin-top:4px}
.status-badge{display:inline-flex;align-items:center;gap:8px;border:1px solid #fed7aa;background:#fff7ed;color:#9a3412;border-radius:999px;padding:6px 14px;font-weight:600;font-size:13px;margin:0 24px}
.chart-card{background:#ffffff;border:1px solid #e2e8f0;border-radius:16px;padding:24px;box-shadow:0 4px 12px rgba(0,0,0,0.02);display:flex;flex-direction:column;gap:16px;transition:transform 0.2s}
.chart-card:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(0,0,0,0.04)}
.cc-head{font-size:14px;font-weight:600;color:#475569;text-transform:uppercase;letter-spacing:0.02em}
.cc-val{font-size:36px;font-weight:800;color:#0f172a;line-height:1}
.cc-bar-wrap{height:16px;background:#f1f5f9;border-radius:999px;overflow:hidden;box-shadow:inset 0 1px 3px rgba(0,0,0,0.05);margin-top:8px}
.cc-bar-fill{height:100%;background:linear-gradient(90deg,#3b82f6,#8b5cf6);border-radius:999px;transition:width 1s cubic-bezier(0.4,0,0.2,1)}
.dropzone{border:2px dashed #94a3b8;background:#f8fafc;color:#64748b;border-radius:16px;min-height:160px;display:flex;align-items:center;justify-content:center;margin:16px 24px;text-align:center;cursor:pointer;transition:all 0.2s;font-weight:500}
.dropzone:hover{border-color:#3b82f6;background:#eff6ff;color:#2563eb}
.file-list{display:flex;flex-direction:column;gap:12px;margin:16px 24px}
.file-item{display:flex;align-items:center;justify-content:space-between;border:1px solid #e2e8f0;background:#ffffff;border-radius:12px;padding:12px 16px;box-shadow:0 2px 4px rgba(0,0,0,0.02)}
.row-actions .fa-pen{color:#3b82f6}
</style>
@endpush

@section('content')
    <div class="page active" id="bapenda-page">
      <div class="page-header"><h1>Formulir Input Data PAD & Realisasi</h1><p>RPKD 2025–2029 | Badan Pendapatan Daerah (Bapenda) Kabupaten Kolaka Utara</p></div>
      <div class="bp-card">
        <div class="card-header"><div><h3>Identitas Pelapor</h3><div class="sub">Isi identitas pelapor dan data pengajuan</div></div><div></div></div>
        <div class="card-body">
          <div class="bp-panel">
            <div class="bp-grid-3" style="margin-bottom:12px">
              <div class="form-group"><label>Organisasi</label><input id="bp-org" class="form-control" value="Badan Pendapatan Daerah (Bapenda)"></div>
              <div class="form-group"><label>Nama Pelapor *</label><input id="bp-nama" class="form-control" placeholder="Nama lengkap pelapor"></div>
              <div class="form-group"><label>Jabatan *</label><input id="bp-jabatan" class="form-control" placeholder="Contoh: Kepala Bapenda"></div>
            </div>
            <div class="bp-grid-3" style="margin-bottom:12px">
              <div class="form-group"><label>Email</label><input id="bp-email" class="form-control" placeholder="email@bapenda.kolakautara.go.id"></div>
              <div class="form-group"><label>No. Telepon</label><input id="bp-telp" class="form-control" placeholder="Contoh: 081234567890"></div>
              <div class="form-group"><label>Tanggal Laporan *</label><input id="bp-date" type="date" class="form-control"></div>
            </div>
            <div class="bp-tabs segmented"><button class="btn btn-primary btn-sm" id="bp-tab-target">Target 2025-2029</button><button class="btn btn-outline btn-sm" id="bp-tab-realisasi">Realisasi Tahun Berjalan</button><button class="btn btn-outline btn-sm" id="bp-tab-analisis">Analisis & Grafik</button><button class="btn btn-outline btn-sm" id="bp-tab-lampiran">Lampiran</button></div>
            <div id="bp-target">
              <div class="info">Masukkan target Pendapatan Asli Daerah untuk masing-masing kategori dan jenis pajak/retribusi.</div>
              <div class="bp-subtitle">A. Pajak Daerah</div>
              <div class="bp-table-wrap"><table class="bp-table"><thead><tr><th>Jenis Pajak</th><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th></tr></thead><tbody id="bp-cat-a"></tbody><tfoot><tr><th>Subtotal</th><th id="sub-a-2025">0</th><th id="sub-a-2026">0</th><th id="sub-a-2027">0</th><th id="sub-a-2028">0</th><th id="sub-a-2029">0</th></tr></tfoot></table></div>
              <div class="bp-subtitle">B. Retribusi Daerah</div>
              <div class="bp-table-wrap"><table class="bp-table"><thead><tr><th>Jenis Retribusi</th><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th></tr></thead><tbody id="bp-cat-b"></tbody><tfoot><tr><th>Subtotal</th><th id="sub-b-2025">0</th><th id="sub-b-2026">0</th><th id="sub-b-2027">0</th><th id="sub-b-2028">0</th><th id="sub-b-2029">0</th></tr></tfoot></table></div>
              <div class="bp-subtitle">C. Hasil Pengelolaan Kekayaan Daerah</div>
              <div class="bp-table-wrap"><table class="bp-table"><thead><tr><th>Jenis Hasil Pengelolaan</th><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th></tr></thead><tbody id="bp-cat-c"></tbody><tfoot><tr><th>Subtotal</th><th id="sub-c-2025">0</th><th id="sub-c-2026">0</th><th id="sub-c-2027">0</th><th id="sub-c-2028">0</th><th id="sub-c-2029">0</th></tr></tfoot></table></div>
              <div class="bp-subtitle">D. Lain-Lain PAD</div>
              <div class="bp-table-wrap"><table class="bp-table"><thead><tr><th>Jenis Lain-Lain PAD</th><th>2025</th><th>2026</th><th>2027</th><th>2028</th><th>2029</th></tr></thead><tbody id="bp-cat-d"></tbody><tfoot><tr><th>Subtotal</th><th id="sub-d-2025">0</th><th id="sub-d-2026">0</th><th id="sub-d-2027">0</th><th id="sub-d-2028">0</th><th id="sub-d-2029">0</th></tr></tfoot></table></div>
              <div class="bp-subtitle">TOTAL TARGET PAD (Rp Juta)</div>
              <div class="summary-grid"><div class="summary-cell"><div class="label">2025</div><div class="value" id="ttl-2025">0</div></div><div class="summary-cell"><div class="label">2026</div><div class="value" id="ttl-2026">0</div></div><div class="summary-cell"><div class="label">2027</div><div class="value" id="ttl-2027">0</div></div><div class="summary-cell"><div class="label">2028</div><div class="value" id="ttl-2028">0</div></div><div class="summary-cell"><div class="label">2029</div><div class="value" id="ttl-2029">0</div></div></div>
              <div class="bp-subtitle">Persetujuan & Tanda Tangan Digital</div>
              <div class="bp-grid" style="align-items:flex-start"><div class="form-group"><label>Tanggal Pengajuan</label><input id="bp-submit-date" type="date" class="form-control"></div><div class="form-group"><label>Jam Pengajuan</label><input id="bp-submit-time" type="time" class="form-control"></div><div class="form-group" style="grid-column:1/-1"><div class="info"><strong>Pernyataan:</strong> Saya menyatakan bahwa data yang saya masukkan adalah benar dan sesuai dengan data aktual PAD Bapenda Kabupaten Kolaka Utara. Saya siap bertanggung jawab atas kebenaran data ini.</div><label style="display:flex;align-items:center;gap:8px"><input type="checkbox" id="bp-consent"> <span>Saya memahami dan setuju dengan pernyataan di atas</span></label></div></div><div class="status-badge bp-status">DRAFT - Belum Dikirim</div>
              <div class="actions-row"><button class="btn btn-outline bp-reset">Reset Form</button><button class="btn btn-green bp-export">Export ke Excel</button><button class="btn btn-outline bp-print">Print Formulir</button><button class="btn btn-primary bp-submit">Kirim ke Bappeda</button></div>
            </div>
            <div id="bp-realisasi" style="display:none">
              <div class="info">Data realisasi hingga bulan berjalan. Sistem menghitung % capaian dan proyeksi akhir tahun.</div>
              <div class="bp-grid" style="margin-bottom:12px"><div class="form-group"><label>Periode Pelaporan *</label><select id="bp-period" class="form-control"></select></div></div>
              <div class="bp-subtitle">Realisasi Pajak Daerah</div>
              <div class="bp-table-wrap"><table class="bp-table"><thead><tr><th>Jenis Pajak</th><th>Target 2025</th><th id="col-r-label-a">Realisasi s/d</th><th>% Capaian</th><th>Selisih</th><th>Proj. Akhir Tahun</th></tr></thead><tbody id="bp-r-a"></tbody><tfoot><tr><th>Subtotal</th><th id="r-a-t-2025">0</th><th id="r-a-real">0</th><th id="r-a-per">0%</th><th id="r-a-diff">0</th><th id="r-a-proj">0</th></tr></tfoot></table></div>
              <div class="bp-subtitle">Realisasi Retribusi Daerah</div>
              <div class="bp-table-wrap"><table class="bp-table"><thead><tr><th>Jenis Retribusi</th><th>Target 2025</th><th id="col-r-label-b">Realisasi s/d</th><th>% Capaian</th><th>Selisih</th><th>Proj. Akhir Tahun</th></tr></thead><tbody id="bp-r-b"></tbody><tfoot><tr><th>Subtotal</th><th id="r-b-t-2025">0</th><th id="r-b-real">0</th><th id="r-b-per">0%</th><th id="r-b-diff">0</th><th id="r-b-proj">0</th></tr></tfoot></table></div>
              <div class="bp-subtitle">TOTAL REALISASI PAD (Rp Juta)</div>
              <div class="summary-grid"><div class="summary-cell"><div class="label">Target 2025</div><div class="value" id="r-ttl-t">0</div></div><div class="summary-cell"><div class="label">Realisasi s/d Bulan</div><div class="value" id="r-ttl-real">0</div></div><div class="summary-cell"><div class="label">% Capaian</div><div class="value" id="r-ttl-per">0%</div></div><div class="summary-cell"><div class="label">Selisih</div><div class="value" id="r-ttl-diff">0</div></div><div class="summary-cell"><div class="label">Proyeksi Akhir Tahun</div><div class="value" id="r-ttl-proj">0</div></div></div>
              <div class="bp-subtitle">Persetujuan & Tanda Tangan Digital</div>
              <div class="bp-grid" style="align-items:flex-start"><div class="form-group"><label>Tanggal Pengajuan</label><input type="date" class="form-control"></div><div class="form-group"><label>Jam Pengajuan</label><input type="time" class="form-control"></div><div class="form-group" style="grid-column:1/-1"><div class="info"><strong>Pernyataan:</strong> Saya menyatakan bahwa data yang saya masukkan adalah benar dan sesuai dengan data aktual PAD Bapenda Kabupaten Kolaka Utara. Saya siap bertanggung jawab atas kebenaran data ini.</div><label style="display:flex;align-items:center;gap:8px"><input type="checkbox" class="bp-consent"> <span>Saya memahami dan setuju dengan pernyataan di atas</span></label></div></div><div class="status-badge bp-status">DRAFT - Belum Dikirim</div>
              <div class="actions-row"><button class="btn btn-outline bp-reset">Reset Form</button><button class="btn btn-green bp-export">Export ke Excel</button><button class="btn btn-outline bp-print">Print Formulir</button><button class="btn btn-primary bp-submit">Kirim ke Bappeda</button></div>
            </div>
            <div id="bp-analisis" style="display:none">
              <div class="bp-subtitle">Analisis & Visualisasi Data</div>
              <div class="bp-grid" style="margin: 0 24px 24px;">
                <div class="chart-card"><div class="cc-head">Progress Capaian Target 2025</div><div class="cc-val" id="ch-progress-val">0%</div><div class="cc-bar-wrap"><div class="cc-bar-fill" id="ch-progress" style="width:0%"></div></div></div>
                <div class="chart-card"><div class="cc-head">Realisasi vs Target</div><div class="cc-val" id="ch-real-val">0%</div><div class="cc-bar-wrap"><div class="cc-bar-fill" id="ch-real" style="width:0%"></div></div></div>
              </div>
              <div class="bp-subtitle">Kendala Pencapaian Target</div>
              <div class="bp-panel"><div class="bp-grid"><div class="form-group"><label>Kategori Kendala</label><select id="an-kendala" class="form-control"><option value="">Pilih Kategori Kendala</option><option>Data</option><option>Operasional</option><option>Regulasi</option><option>Koordinasi</option></select></div><div class="form-group"><label>Deskripsi Kendala</label><textarea id="an-desc" class="form-control" rows="3" placeholder="Jelaskan kendala yang dihadapi..."></textarea></div></div></div>
              <div class="bp-subtitle">Rencana Akselerasi</div>
              <div class="bp-panel"><div class="bp-grid"><div class="form-group"><label>Target Akselerasi (Bulan)</label><select id="an-bulan" class="form-control"><option value="">Pilih Bulan</option></select></div><div class="form-group"><label>Rencana Akselerasi</label><textarea id="an-plan" class="form-control" rows="3" placeholder="Deskripsikan rencana akselerasi..."></textarea></div></div></div>
              <div class="bp-subtitle">Catatan Pelapor</div>
              <div class="bp-panel"><div class="form-group"><textarea id="an-notes" class="form-control" rows="3" placeholder="Catatan tambahan atau informasi penting lainnya..."></textarea></div></div>
              <div class="bp-subtitle">Persetujuan & Tanda Tangan Digital</div>
              <div class="bp-grid" style="align-items:flex-start"><div class="form-group"><label>Tanggal Pengajuan</label><input type="date" class="form-control"></div><div class="form-group"><label>Jam Pengajuan</label><input type="time" class="form-control"></div><div class="form-group" style="grid-column:1/-1"><div class="info"><strong>Pernyataan:</strong> Saya menyatakan bahwa data yang saya masukkan adalah benar dan sesuai dengan data aktual PAD Bapenda Kabupaten Kolaka Utara. Saya siap bertanggung jawab atas kebenaran data ini.</div><label style="display:flex;align-items:center;gap:8px"><input type="checkbox" class="bp-consent"> <span>Saya memahami dan setuju dengan pernyataan di atas</span></label></div></div><div class="status-badge bp-status">DRAFT - Belum Dikirim</div>
              <div class="actions-row"><button class="btn btn-outline bp-reset">Reset Form</button><button class="btn btn-green bp-export">Export ke Excel</button><button class="btn btn-outline bp-print">Print Formulir</button><button class="btn btn-primary bp-submit">Kirim ke Bappeda</button></div>
            </div>
            <div id="bp-lampiran" style="display:none">
              <div class="bp-subtitle">Upload Dokumen Pendukung</div>
              <div class="info">Upload dokumen pendukung seperti detail PAD per sumber, laporan analisis, atau surat persetujuan dari DPA. Max 5MB per file.</div>
              <div class="dropzone" id="dz">Drag & Drop File Di Sini<br>atau klik untuk memilih file</div>
              <input type="file" id="dz-input" style="display:none" multiple>
              <div class="file-list" id="file-list"></div>
              <div class="bp-subtitle">Persetujuan & Tanda Tangan Digital</div>
              <div class="bp-grid" style="align-items:flex-start"><div class="form-group"><label>Tanggal Pengajuan</label><input type="date" class="form-control"></div><div class="form-group"><label>Jam Pengajuan</label><input type="time" class="form-control"></div><div class="form-group" style="grid-column:1/-1"><div class="info"><strong>Pernyataan:</strong> Saya menyatakan bahwa data yang saya masukkan adalah benar dan sesuai dengan data aktual PAD Bapenda Kabupaten Kolaka Utara. Saya siap bertanggung jawab atas kebenaran data ini.</div><label style="display:flex;align-items:center;gap:8px"><input type="checkbox" class="bp-consent"> <span>Saya memahami dan setuju dengan pernyataan di atas</span></label></div></div><div class="status-badge bp-status">DRAFT - Belum Dikirim</div>
              <div class="actions-row"><button class="btn btn-outline bp-reset">Reset Form</button><button class="btn btn-green bp-export">Export ke Excel</button><button class="btn btn-outline bp-print">Print Formulir</button><button class="btn btn-primary bp-submit">Kirim ke Bappeda</button></div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection

@push('scripts')
<script>
var csrfToken=document.querySelector('meta[name="csrf-token"]')?.content||'';window.USER_ROLE=document.body.dataset.userRole||'';var opdName='Badan Pendapatan Daerah (Bapenda)';var dinasId=(document.body.dataset.dinasId||'')||null;
var years=[2025,2026,2027,2028,2029];
var CAT_A=['Pajak Hotel','Pajak Restoran','Pajak Hiburan','Pajak Reklame','Pajak Penerangan Jalan','Pajak Mineral Bukan Logam & Batuan','Pajak Parkir','Pajak Air Tanah','Pajak Sarang Burung Walet','Pajak Bumi dan Bangunan - P2','Bea Perolehan Hak atas Tanah & Bangunan'];
var CAT_B=['Retribusi Jasa Umum','Retribusi Jasa Usaha','Retribusi Perizinan Tertentu'];
var CAT_C=['Hasil Pengelolaan Barang Milik Daerah','Hasil Pemanfaatan Aset Daerah'];
var CAT_D=['Denda Pajak & Retribusi','Hasil Penyelesaian Piutang','Penerimaan Komisi & Lainnya'];
function rowHtml(name,cat){return '<tr><td>'+name+'</td>'+years.map(function(y){return '<td><input class="form-control pad-input" inputmode="decimal" placeholder="0" data-cat="'+cat+'" data-item="'+name+'" data-year="'+y+'"></td>'}).join('')+'</tr>'}
function renderCat(catId,items){var tb=document.getElementById(catId);if(!tb)return;tb.innerHTML=items.map(function(n){return rowHtml(n,catId.split('-')[2].toUpperCase())}).join('')}
renderCat('bp-cat-a',CAT_A);renderCat('bp-cat-b',CAT_B);renderCat('bp-cat-c',CAT_C);renderCat('bp-cat-d',CAT_D);
function sumCat(cat,year){var inputs=[].slice.call(document.querySelectorAll('.pad-input[data-cat="'+cat+'"][data-year="'+year+'"]').values?document.querySelectorAll('.pad-input[data-cat="'+cat+'"][data-year="'+year+'"]').values:document.querySelectorAll('.pad-input[data-cat="'+cat+'"][data-year="'+year+'"]'));var s=0;inputs.forEach(function(i){var v=parseFloat((i.value||'').toString().replace(/,/g,''));if(!isNaN(v))s+=v});return s}
function updateTotals(){years.forEach(function(y){var a=sumCat('A',y);var b=sumCat('B',y);var c=sumCat('C',y);var d=sumCat('D',y);document.getElementById('sub-a-'+y).textContent=a;document.getElementById('sub-b-'+y).textContent=b;document.getElementById('sub-c-'+y).textContent=c;document.getElementById('sub-d-'+y).textContent=d;document.getElementById('ttl-'+y).textContent=a+b+c+d})}
document.addEventListener('input',function(e){if(e.target.classList.contains('pad-input')){updateTotals()}});
function exportCsv(filename, headers, rows){var csv=[headers].concat(rows).map(function(row){return row.map(function(v){var s=(''+(v==null?'':v)).replace(/"/g,'""');return '"'+s+'"'}).join(',')}).join('\n');var blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});var url=URL.createObjectURL(blob);var a=document.createElement('a');a.href=url;a.download=filename;document.body.appendChild(a);a.click();document.body.removeChild(a);URL.revokeObjectURL(url)}
function collectRows(catId,label){var rows=[];[].slice.call(document.querySelectorAll('#'+catId+' tr')).forEach(function(tr){var name=tr.children[0]?.textContent||'';var vals=years.map(function(y,idx){var inp=tr.children[idx+1]?.querySelector('input');return inp?inp.value:''});rows.push([label+' - '+name].concat(vals))});return rows}
document.querySelectorAll('.bp-export').forEach(function(b){b.addEventListener('click',function(){var headers=['Jenis','2025','2026','2027','2028','2029'];var rows=[];rows=rows.concat(collectRows('bp-cat-a','A'));rows=rows.concat(collectRows('bp-cat-b','B'));rows=rows.concat(collectRows('bp-cat-c','C'));rows=rows.concat(collectRows('bp-cat-d','D'));exportCsv('target-pad-bapenda.csv',headers,rows)})});
document.querySelectorAll('.bp-print').forEach(function(b){b.addEventListener('click',function(){window.print()})});
document.querySelectorAll('.bp-reset').forEach(function(b){b.addEventListener('click',function(){document.querySelectorAll('.pad-input').forEach(function(i){i.value=''});updateTotals()})});
function toggleTab(active){['bp-target','bp-realisasi','bp-analisis','bp-lampiran'].forEach(function(id){document.getElementById(id).style.display=id===active?'block':'none'});var t1=document.getElementById('bp-tab-target');var t2=document.getElementById('bp-tab-realisasi');var t3=document.getElementById('bp-tab-analisis');var t4=document.getElementById('bp-tab-lampiran');[t1,t2,t3,t4].forEach(function(b){b.classList.add('btn-outline');b.classList.remove('btn-primary')});if(active==='bp-target'){t1.classList.add('btn-primary');t1.classList.remove('btn-outline')}else if(active==='bp-realisasi'){t2.classList.add('btn-primary');t2.classList.remove('btn-outline')}else if(active==='bp-analisis'){t3.classList.add('btn-primary');t3.classList.remove('btn-outline')}else{t4.classList.add('btn-primary');t4.classList.remove('btn-outline')}}
document.getElementById('bp-tab-target')?.addEventListener('click',function(){toggleTab('bp-target')});
document.getElementById('bp-tab-realisasi')?.addEventListener('click',function(){toggleTab('bp-realisasi')});
document.getElementById('bp-tab-analisis')?.addEventListener('click',function(){toggleTab('bp-analisis')});
document.getElementById('bp-tab-lampiran')?.addEventListener('click',function(){toggleTab('bp-lampiran')});
var months=['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
var periodSel=document.getElementById('bp-period');if(periodSel){periodSel.innerHTML=months.map(function(m,i){return '<option value="'+(i+1)+'">'+m+' 2025</option>'}).join('');var cur=(new Date()).getMonth()+1;periodSel.value=String(cur)}
function renderRealisasiCat(catId,items){var tb=document.getElementById(catId);if(!tb)return;var m=parseInt(periodSel?.value||'1',10);var label=months[m-1]+' 2025';document.getElementById(catId==='bp-r-a'?'col-r-label-a':'col-r-label-b').textContent='Realisasi s/d '+label;tb.innerHTML=items.map(function(n){return '<tr><td>'+n+'</td><td><input class="form-control r-target" inputmode="decimal" placeholder="0" data-rcat="'+catId+'" data-item="'+n+'"></td><td><input class="form-control r-real" inputmode="decimal" placeholder="0" data-rcat="'+catId+'" data-item="'+n+'"></td><td class="r-per">0%</td><td class="r-diff">0</td><td class="r-proj">0</td></tr>'}).join('')}
renderRealisasiCat('bp-r-a',CAT_A);renderRealisasiCat('bp-r-b',CAT_B);
function updateRealisasiTotals(){var m=parseInt(periodSel?.value||'1',10);var mult=12/Math.max(1,m);var sumT=function(cat){var s=0;document.querySelectorAll('#'+cat+' .r-target').forEach(function(i){var v=parseFloat(i.value||'0');if(!isNaN(v))s+=v});return s};var sumR=function(cat){var s=0;document.querySelectorAll('#'+cat+' .r-real').forEach(function(i){var v=parseFloat(i.value||'0');if(!isNaN(v))s+=v});return s};var tA=sumT('bp-r-a'), rA=sumR('bp-r-a');var tB=sumT('bp-r-b'), rB=sumR('bp-r-b');document.getElementById('r-a-t-2025').textContent=tA;document.getElementById('r-a-real').textContent=rA;document.getElementById('r-a-per').textContent=(tA?((rA/tA*100)||0):0).toFixed(1)+'%';document.getElementById('r-a-diff').textContent=(rA-tA);document.getElementById('r-a-proj').textContent=(rA*mult).toFixed(0);document.getElementById('r-b-t-2025').textContent=tB;document.getElementById('r-b-real').textContent=rB;document.getElementById('r-b-per').textContent=(tB?((rB/tB*100)||0):0).toFixed(1)+'%';document.getElementById('r-b-diff').textContent=(rB-tB);document.getElementById('r-b-proj').textContent=(rB*mult).toFixed(0);var tT=tA+tB;var rT=rA+rB;document.getElementById('r-ttl-t').textContent=tT;document.getElementById('r-ttl-real').textContent=rT;document.getElementById('r-ttl-per').textContent=(tT?((rT/tT*100)||0):0).toFixed(1)+'%';document.getElementById('r-ttl-diff').textContent=(rT-tT);document.getElementById('r-ttl-proj').textContent=(rT*mult).toFixed(0);var p=(tT?((rT/tT*100)||0):0);document.getElementById('ch-progress').style.width=p+'%';document.getElementById('ch-progress-val').textContent=p.toFixed(1)+'%';document.getElementById('ch-real').style.width=p+'%';document.getElementById('ch-real-val').textContent=p.toFixed(1)+'%'}
document.addEventListener('input',function(e){if(e.target.classList.contains('r-target')||e.target.classList.contains('r-real')){var tr=e.target.closest('tr');if(tr){var t=parseFloat(tr.querySelector('.r-target').value||'0');var r=parseFloat(tr.querySelector('.r-real').value||'0');var m=parseInt(periodSel?.value||'1',10);var mult=12/Math.max(1,m);tr.querySelector('.r-per').textContent=(t?((r/t*100)||0):0).toFixed(1)+'%';tr.querySelector('.r-diff').textContent=(r-t);tr.querySelector('.r-proj').textContent=(r*mult).toFixed(0)}updateRealisasiTotals()}});
periodSel?.addEventListener('change',function(){renderRealisasiCat('bp-r-a',CAT_A);renderRealisasiCat('bp-r-b',CAT_B);updateRealisasiTotals()});
var anBulan=document.getElementById('an-bulan');if(anBulan){anBulan.innerHTML='<option value="">Pilih Bulan</option>'+months.map(function(m,i){return '<option value="'+(i+1)+'">'+m+'</option>'}).join('')}
function submitDM(){var nama=document.getElementById('bp-nama').value.trim();var jab=document.getElementById('bp-jabatan').value.trim();var dt=document.getElementById('bp-date').value.trim();if(!nama||!jab||!dt){Utils.showToast('Isi identitas pelapor wajib','error');return}var panels=['bp-target','bp-realisasi','bp-analisis','bp-lampiran'];var consEls=[].slice.call(document.querySelectorAll('.bp-consent'));var idEl=document.getElementById('bp-consent');if(idEl) consEls.push(idEl);var hasConsent=consEls.some(function(c){var p=panels.map(function(id){return document.getElementById(id)}).find(function(el){return el&&el.contains(c)});var visible=p? (p.style.display!=='none') : true;return visible&&c.checked});if(!hasConsent){Utils.showToast('Centang persetujuan di tab aktif','error');return}var year=(new Date(dt)).getFullYear().toString();var payload={opd:opdName,dinas_id:dinasId,judul_data:'Target PAD 2025-2029',file_path:'bapenda_pad_target_2025_2029',tahun_perencanaan:year,deskripsi:('Pengajuan oleh '+nama+' ('+jab+') pada '+dt)};fetch('/data-management/submit',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify(payload)}).then(async function(res){if(res.ok){Utils.showToast('Pengajuan dikirim','success');document.querySelectorAll('.bp-status').forEach(function(el){el.textContent='Terkirim - Menunggu Persetujuan'});await syncBapendaRows();await upsertSummaryRow()}else{Utils.showToast('Gagal mengirim','error')}}).catch(function(){Utils.showToast('Gagal mengirim','error')})}
function collectPadTargets(){var rows=[];['A','B','C','D'].forEach(function(cat){var items=(cat==='A'?CAT_A:(cat==='B'?CAT_B:(cat==='C'?CAT_C:CAT_D)));items.forEach(function(name){var vals={y2025:'',y2026:'',y2027:'',y2028:'',y2029:''};years.forEach(function(y){var inp=document.querySelector('.pad-input[data-cat="'+cat+'"][data-item="'+name+'"][data-year="'+y+'"]');vals['y'+y]=inp?inp.value.trim():''});rows.push({uraian:name,satuan:null,values:vals})})});return rows}
async function syncBapendaRows(){if(!(window.USER_ROLE==='admin_dinas'||window.USER_ROLE==='super_admin'))return;var list=collectPadTargets();for(var i=0;i<list.length;i++){var row=list[i];try{var res=await fetch('/bapenda/pad',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:row.uraian,satuan:row.satuan,values:row.values})});}catch(__){}}}
async function upsertSummaryRow(){if(!(window.USER_ROLE==='admin_dinas'||window.USER_ROLE==='super_admin'))return;var vals={y2025:(sumCat('A',2025)+sumCat('B',2025)+sumCat('C',2025)+sumCat('D',2025)).toString(),y2026:(sumCat('A',2026)+sumCat('B',2026)+sumCat('C',2026)+sumCat('D',2026)).toString(),y2027:(sumCat('A',2027)+sumCat('B',2027)+sumCat('C',2027)+sumCat('D',2027)).toString(),y2028:(sumCat('A',2028)+sumCat('B',2028)+sumCat('C',2028)+sumCat('D',2028)).toString(),y2029:(sumCat('A',2029)+sumCat('B',2029)+sumCat('C',2029)+sumCat('D',2029)).toString()};try{var res=await fetch('/bapenda/pad',{headers:{'Accept':'application/json'}});var data=await res.json();var exist=(Array.isArray(data)?data:[]).find(function(r){return (r.uraian||'')==='TOTAL TARGET PAD'});if(exist&&exist.id){await fetch('/bapenda/pad/'+exist.id,{method:'PUT',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:'TOTAL TARGET PAD',satuan:'Rp Juta',values:vals})});}else{await fetch('/bapenda/pad',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},body:JSON.stringify({uraian:'TOTAL TARGET PAD',satuan:'Rp Juta',values:vals})});}}catch(_){}}
document.querySelectorAll('.bp-submit').forEach(function(b){b.addEventListener('click',function(e){var btn=e.currentTarget;if(btn.dataset.loading==='1')return;btn.dataset.loading='1';var old=btn.textContent;btn.textContent='Mengirim...';btn.classList.add('disabled');setTimeout(function(){btn.textContent=old;btn.classList.remove('disabled');btn.dataset.loading='0'},900);submitDM()})});

var dz=document.getElementById('dz');var dzInput=document.getElementById('dz-input');var fileList=document.getElementById('file-list');var attachments=[];function renderFiles(){fileList.innerHTML=attachments.map(function(f,i){return '<div class="file-item"><div>'+f.name+' ('+Math.round(f.size/1024)+' KB)</div><button class="btn btn-outline btn-sm" data-rm="'+i+'">Hapus</button></div>'}).join('')}dz?.addEventListener('click',function(){dzInput.click()});dzInput?.addEventListener('change',function(){attachments=[].slice.call(dzInput.files||[]);renderFiles()});['dragenter','dragover'].forEach(function(ev){dz?.addEventListener(ev,function(e){e.preventDefault();dz.style.borderColor='#2563eb'})});['dragleave','drop'].forEach(function(ev){dz?.addEventListener(ev,function(e){e.preventDefault();dz.style.borderColor='#22c3e5'})});dz?.addEventListener('drop',function(e){e.preventDefault();var files=e.dataTransfer?.files||[];attachments=[].slice.call(files);renderFiles()});document.addEventListener('click',function(e){var rm=e.target.closest('[data-rm]');if(!rm)return;attachments.splice(parseInt(rm.getAttribute('data-rm'),10),1);renderFiles()});
updateTotals();updateRealisasiTotals();
</script>
@endpush

