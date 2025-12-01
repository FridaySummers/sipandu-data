@extends('layouts.app')
@section('title', 'Forum Diskusi')
@section('body-class', 'dashboard-page force-light')

@section('content')
    <div class="page active" id="forum-page">
        <div class="page-header"><h1>Forum Diskusi</h1><p>Koordinasi dan diskusi lintas OPD</p></div>
        <div class="forum-threads">
            <div class="threads-header">
                <h3>Topik Terbaru</h3>
                <div class="threads-actions">
                    <div class="search-box compact"><i class="fas fa-search"></i><input id="forum-search" type="text" placeholder="Cari diskusi, topik, atau pengguna..."></div>
                    <select id="forum-category"><option value="">Semua Kategori</option><option value="Metodologi">Metodologi</option><option value="Best Practice">Best Practice</option><option value="Pengumuman">Pengumuman</option><option value="Problem Solving">Problem Solving</option></select>
                    <button class="btn btn-primary btn-sm" id="forum-new"><i class="fas fa-plus"></i> Buat Diskusi Baru</button>
                </div>
            </div>
            <div class="thread-list" id="thread-list"></div>
        </div>
        <div class="modal-overlay" id="forum-modal" style="display:none;">
            <div class="modal">
                <div class="modal-header"><h3>Buat Topik Baru</h3><button class="btn btn-outline btn-sm" id="forum-close">✕</button></div>
                <div class="modal-body">
                    <div class="form-row"><label>Judul<span class="req">*</span></label><input id="forum-title" type="text" placeholder="Judul topik" maxlength="80"><div class="field-meta"><span id="forum-title-count">0/80</span></div><div class="field-help">Gunakan judul ringkas dan jelas</div><div class="field-error" id="forum-title-error"></div></div>
                    <div class="form-row"><label>OPD<span class="req">*</span></label><select id="forum-opd"></select><div class="field-error" id="forum-opd-error"></div></div>
                    <div class="form-row"><label>Kategori<span class="req">*</span></label><select id="forum-cat"><option value="">Pilih Kategori</option><option value="Metodologi">Metodologi</option><option value="Best Practice">Best Practice</option><option value="Pengumuman">Pengumuman</option><option value="Problem Solving">Problem Solving</option></select><div class="field-error" id="forum-cat-error"></div></div>
                    <div class="form-row"><label>Konten<span class="req">*</span></label><textarea id="forum-content" rows="5" placeholder="Isi diskusi" maxlength="500"></textarea><div class="field-meta"><span id="forum-content-count">0/500</span></div><div class="field-help">Jelaskan konteks, data, atau pertanyaan utama</div><div class="field-error" id="forum-content-error"></div></div>
                </div>
                <div class="modal-footer"><button class="btn btn-secondary" id="forum-cancel">Batal</button><button class="btn btn-primary" id="forum-save" disabled>Simpan</button></div>
            </div>
        </div>
        <div class="modal-overlay" id="forum-detail-modal" style="display:none;">
            <div class="modal" style="max-width: 860px;">
                <div class="modal-header"><h3>Detail Diskusi</h3><button class="btn btn-outline btn-sm" id="fdm-close">✕</button></div>
                <div class="modal-body" id="fdm-body">Pilih topik untuk melihat detail</div>
                <div class="modal-footer"><div style="flex:1"></div><button class="btn btn-secondary" id="fdm-reply-cancel">Batal</button><button class="btn btn-primary" id="fdm-reply-send">Kirim</button></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', ()=>{ UIComponents.renderNotifications(); });
</script>
@endpush

@push('styles')
<style>
#forum-page .modal .form-row{display:flex !important;flex-direction:column;gap:8px;padding:12px;border-radius:12px;background:#ffffff;border:1px solid #e5e7eb;box-shadow:var(--shadow-sm);border-left:4px solid #93c5fd;margin-bottom:12px}
#forum-page .modal .form-row > label{font-weight:600;font-size:14px;color:#334155;display:flex;align-items:center;gap:6px;justify-content:flex-start !important;text-align:left !important;align-self:flex-start}
#forum-page .modal .form-row .req{color:#ef4444}
#forum-page .modal .field-meta{display:flex;justify-content:flex-start;color:#64748b;font-size:12px}
#forum-page .modal .field-help{color:#64748b;font-size:12px}
#forum-page .modal .field-error{color:#ef4444;font-size:12px;min-height:16px}
#forum-page .modal .btn[disabled]{opacity:.6;cursor:not-allowed}

/* tanp a garis label; aksen biru di kiri kotak */

#forum-page .modal .form-row input,
#forum-page .modal .form-row select,
#forum-page .modal .form-row textarea{border:1px solid #93c5fd;border-radius:12px;background:#ffffff;padding:10px 12px}
#forum-page .modal .form-row.valid input,
#forum-page .modal .form-row.valid select,
#forum-page .modal .form-row.valid textarea{border-color:#93c5fd}
#forum-page .modal .form-row.invalid input,
#forum-page .modal .form-row.invalid select,
#forum-page .modal .form-row.invalid textarea{border-color:#ef4444;box-shadow:0 0 0 2px rgba(244,63,94,0.12)}
#forum-page .modal .form-row input:focus,
#forum-page .modal .form-row select:focus,
#forum-page .modal .form-row textarea:focus{outline:none;border-color:var(--primary-blue);box-shadow:0 0 0 3px rgba(37,99,235,0.12);background:#ffffff}

#forum-page .modal .custom-select .cs-control{border-radius:12px;background:#ffffff;border:1px solid #93c5fd;padding:10px 12px}
#forum-detail-modal .file-item{padding:12px 0;border:none;border-bottom:1px solid #e5e7eb;border-radius:0;background:transparent;box-shadow:none;margin:0}
#forum-detail-modal .file-item:first-child{border-top:1px solid #e5e7eb}
#forum-page .modal .custom-select .cs-menu{border-radius:var(--radius)}
#forum-detail-modal .modal-header h3{font-size:var(--font-size-lg);color:#0f172a}
#forum-detail-modal .modal-body h3{font-size:var(--font-size-xl);margin-bottom:8px;color:#0f172a}
#forum-detail-modal .thread-meta{font-size:12px;color:#64748b}
#forum-detail-modal textarea#fdm-reply-input{border:1px solid #93c5fd;border-radius:12px;background:#ffffff;padding:10px 12px;box-shadow:var(--shadow-sm)}
#forum-detail-modal .modal-footer .btn{border-radius:12px}
</style>
@endpush
